<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer l'admin par son slug s'il est fourni dans l'URL
        $admin = null;
        $adminSlug = $request->route('admin');
        
        // Si pas de slug dans l'URL, essayer de récupérer depuis la session (domaine)
        if (!$adminSlug) {
            $adminSlug = Session::get('current_admin_slug');
        }
        
        if ($adminSlug) {
            $admin = User::where('admin_slug', $adminSlug)
                         ->where('is_active', true)
                         ->where('role', 'admin')
                         ->first();
            
            if (!$admin) {
                abort(404, 'Admin non trouvé');
            }
        }

        // Construire la requête des stores
        $query = Store::with(['schedules', 'exceptions', 'holidays'])
            ->select([
                'id',
                'nom',
                'adresse',
                'ville',
                'pays',
                'phone',
                'ouvert_jusqua',
                'lien_rdv',
                'latitude',
                'longitude',
                'services',
                'user_id'
            ]);

        // Filtrer par admin si spécifié
        if ($admin) {
            $query->where('user_id', $admin->id);
        } else {
            // Si aucun admin n'est spécifié et qu'on est en mode multi-tenant,
            // on peut soit montrer tous les stores, soit rediriger vers une page d'erreur
            $currentDomain = $request->getHost();
            $cleanDomain = preg_replace('/^www\./', '', $currentDomain);
            
            if (!in_array($cleanDomain, ['localhost', '127.0.0.1']) && 
                !str_ends_with($cleanDomain, '.test') && 
                !str_ends_with($cleanDomain, '.local')) {
                // En production, on ne montre que les stores des admins qui ont configuré leur domaine
                $adminId = Session::get('current_admin_id');
                if ($adminId) {
                    $query->where('user_id', $adminId);
                } else {
                    // Aucun admin trouvé pour ce domaine
                    return view('user.domain-error', [
                        'domain' => $cleanDomain,
                        'message' => 'Ce domaine n\'est pas configuré dans notre système.'
                    ]);
                }
            }
        }

        $stores = $query->get();
            
        // Pour chaque magasin, vérifier s'il est fermé aujourd'hui
        foreach ($stores as $store) {
            $this->addScheduleInfo($store);
        }

        return view('user.home', compact('stores', 'admin'));
    }

    public function filterStores(Request $request)
    {
        Log::info('Filtrage des magasins', $request->all());
        
        $query = Store::with(['schedules', 'exceptions', 'holidays']);
        
        // Filtrer par admin si spécifié
        $adminSlug = $request->input('admin_slug') ?? Session::get('current_admin_slug');
        $adminId = Session::get('current_admin_id');
        
        if ($adminSlug) {
            $admin = User::where('admin_slug', $adminSlug)->first();
            if ($admin) {
                $query->where('user_id', $admin->id);
            }
        } elseif ($adminId) {
            // Utiliser l'ID admin de la session (détecté par le domaine)
            $query->where('user_id', $adminId);
        } else {
            // Si on est sur un domaine spécifique mais qu'aucun admin n'est trouvé
            $currentDomain = $request->getHost();
            $cleanDomain = preg_replace('/^www\./', '', $currentDomain);
            
            if (!in_array($cleanDomain, ['localhost', '127.0.0.1']) && 
                !str_ends_with($cleanDomain, '.test') && 
                !str_ends_with($cleanDomain, '.local')) {
                // En production, on renvoie une erreur
                return response()->json([
                    'error' => 'Ce domaine n\'est pas configuré dans notre système.',
                    'stores' => []
                ], 403);
            }
        }

        // Filtre par SERVICES
        if ($request->has('service_search') && !empty($request->service_search)) {
            $serviceSearch = strtolower($request->service_search);
            $query->where(function ($q) use ($serviceSearch) {
                $q->whereRaw('LOWER(JSON_EXTRACT(services, "$[*]")) LIKE ?', ['%' . $serviceSearch . '%'])
                  ->orWhereRaw('LOWER(services) LIKE ?', ['%' . $serviceSearch . '%']);
            });
        }

        // Filtre par HORAIRES
        if ($request->has('horaire') && !empty($request->horaire)) {
            $this->applyScheduleFilter($query, $request->horaire);
        }

        // Filtre par RECHERCHE TEXTE
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('ville', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('pays', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('adresse', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('nom', 'LIKE', "%{$searchTerm}%");
            });
        }

        // OPTIMISATION GÉOLOCALISATION - Filtre "Autour de moi"
        if ($request->has(['latitude', 'longitude'])) {
            $stores = $this->getStoresNearLocation(
                $query, 
                $request->latitude, 
                $request->longitude,
                $request->input('radius', 50) // Rayon par défaut de 50km
            );
        } else {
            $stores = $query->get();
        }
        
        // Ajouter les informations d'horaires pour chaque magasin
        foreach ($stores as $store) {
            $this->addScheduleInfo($store);
        }

        return response()->json($stores);
    }

    // Vos méthodes existantes (inchangées)
    private function getStoresNearLocation($query, $userLat, $userLng, $radiusKm = 50)
    {
        // Validation des coordonnées
        if (!is_numeric($userLat) || !is_numeric($userLng)) {
            Log::warning('Coordonnées invalides', ['lat' => $userLat, 'lng' => $userLng]);
            return $query->get();
        }

        // Conversion en float pour sécurité
        $userLat = (float) $userLat;
        $userLng = (float) $userLng;
        $radiusKm = (float) $radiusKm;

        Log::info('Recherche géolocalisée', [
            'user_lat' => $userLat,
            'user_lng' => $userLng,
            'radius_km' => $radiusKm
        ]);

        // Calcul des bornes approximatives pour optimiser la requête
        // 1 degré ≈ 111 km
        $latDelta = $radiusKm / 111;
        $lngDelta = $radiusKm / (111 * cos(deg2rad($userLat)));

        $stores = $query
            // Pré-filtrage par bornes rectangulaires (plus rapide)
            ->whereBetween('latitude', [$userLat - $latDelta, $userLat + $latDelta])
            ->whereBetween('longitude', [$userLng - $lngDelta, $userLng + $lngDelta])
            // Calcul précis de la distance avec formule de Haversine
            ->selectRaw(
                "*, 
                (6371 * acos(
                    LEAST(1.0, 
                        GREATEST(-1.0,
                            cos(radians(?)) * cos(radians(latitude)) * 
                            cos(radians(longitude) - radians(?)) + 
                            sin(radians(?)) * sin(radians(latitude))
                        )
                    )
                )) AS distance",
                [$userLat, $userLng, $userLat]
            )
            ->get()
            // Filtrage final par distance exacte
            ->filter(function ($store) use ($radiusKm) {
                return $store->distance <= $radiusKm;
            })
            // Tri par distance croissante
            ->sortBy('distance')
            ->values(); // Réindexer la collection

        Log::info('Magasins trouvés dans le rayon', [
            'count' => $stores->count(),
            'radius_km' => $radiusKm
        ]);

        return $stores;
    }

    private function applyScheduleFilter($query, $horaireFilter)
    {
        $now = Carbon::now();
        $currentDay = strtolower($now->englishDayOfWeek);

        switch ($horaireFilter) {
            case 'open_now':
                $query->where(function($q) use ($currentDay, $now) {
                    // Magasins avec ouvert_jusqua défini et encore ouvert
                    $q->where(function($subQ) use ($now) {
                        $subQ->whereNotNull('ouvert_jusqua')
                            ->whereRaw('TIME(ouvert_jusqua) > ?', [$now->format('H:i:s')]);
                    });
                    
                    // OU magasins avec horaires réguliers ouverts aujourd'hui
                    $q->orWhereHas('schedules', function($scheduleQ) use ($currentDay, $now) {
                        $scheduleQ->where('day_of_week', $currentDay)
                            ->where('is_closed', false)
                            ->whereRaw('JSON_EXTRACT(time_slots, "$[*].start") <= ?', [$now->format('H:i')])
                            ->whereRaw('JSON_EXTRACT(time_slots, "$[*].end") > ?', [$now->format('H:i')]);
                    });
                })
                // Exclure les exceptions fermées aujourd'hui
                ->whereDoesntHave('exceptions', function ($q) use ($now) {
                    $q->whereDate('exception_date', $now->toDateString())
                      ->where('is_closed', true);
                })
                // Exclure les jours fériés
                ->whereDoesntHave('holidays', function ($q) use ($now) {
                    $q->whereDate('holiday_date', $now->toDateString());
                });
                break;

            case 'morning':
                $query->whereHas('schedules', function($scheduleQ) use ($currentDay) {
                    $scheduleQ->where('day_of_week', $currentDay)
                        ->where('is_closed', false)
                        ->whereRaw('JSON_EXTRACT(time_slots, "$[*].start") <= ?', ['12:00']);
                });
                break;

            case 'afternoon':
                $query->whereHas('schedules', function($scheduleQ) use ($currentDay) {
                    $scheduleQ->where('day_of_week', $currentDay)
                        ->where('is_closed', false)
                        ->whereRaw('JSON_EXTRACT(time_slots, "$[*].start") >= ?', ['12:00'])
                        ->whereRaw('JSON_EXTRACT(time_slots, "$[*].end") <= ?', ['18:00']);
                });
                break;

            case 'evening':
                $query->whereHas('schedules', function($scheduleQ) use ($currentDay) {
                    $scheduleQ->where('day_of_week', $currentDay)
                        ->where('is_closed', false)
                        ->whereRaw('JSON_EXTRACT(time_slots, "$[*].end") > ?', ['18:00']);
                });
                break;

            case 'weekend':
                $query->whereHas('schedules', function($scheduleQ) {
                    $scheduleQ->whereIn('day_of_week', ['saturday', 'sunday'])
                        ->where('is_closed', false);
                });
                break;
        }
    }

    private function addScheduleInfo(Store $store)
    {
        $today = Carbon::today();
        $now = Carbon::now();
        $dayOfWeek = strtolower($today->englishDayOfWeek);
        
        $exception = $store->exceptions()
            ->whereDate('exception_date', $today)
            ->first();
        
        $holiday = $store->holidays()
            ->whereDate('holiday_date', $today)
            ->first();
        
        $regularSchedule = $store->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->first();
        
        $store->is_closed = false;
        $store->closed_reason = null;
        
        if ($exception) {
            $store->is_closed = $exception->is_closed;
            if ($exception->is_closed) {
                $store->closed_reason = 'Exception: ' . $exception->exception_raison;
            }
        } elseif ($holiday) {
            $store->is_closed = true;
            $store->closed_reason = 'Jour férié: ' . $holiday->holiday_name;
        } elseif ($regularSchedule && $regularSchedule->is_closed) {
            $store->is_closed = true;
            $store->closed_reason = 'Fermé le ' . $this->getFrenchDayName($dayOfWeek);
        }
        
        $store->is_open = false;
        $store->today_status = 'Fermé';
        
        if (!$store->is_closed) {
            if ($store->ouvert_jusqua) {
                $heureFermeture = Carbon::parse($store->ouvert_jusqua);
                $store->is_open = $now->lt($heureFermeture);
                $store->today_status = $store->is_open ? 'Ouvert' : 'Fermé';
            }
        
            if ($regularSchedule && !$regularSchedule->is_closed && isset($regularSchedule->time_slots) && is_array($regularSchedule->time_slots)) {
                foreach ($regularSchedule->time_slots as $slot) {
                    if (isset($slot['start']) && isset($slot['end'])) {
                        $start = Carbon::parse($slot['start']);
                        $end = Carbon::parse($slot['end']);
                    
                        if ($now->between($start, $end)) {
                            $store->is_open = true;
                            $store->today_status = 'Ouvert';
                            break;
                        }
                    }
                }
            }
        } else {
            $store->today_status = $store->closed_reason ?? 'Fermé';
        }
        
        $store->formatted_weekly_hours = $this->generateWeeklyHoursHtml($store);
    }
    
    private function generateWeeklyHoursHtml(Store $store)
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $today = Carbon::today();
        $currentDayOfWeek = strtolower($today->englishDayOfWeek);
        
        $html = '<div class="weekly-hours">';
        
        foreach ($days as $day) {
            $isToday = ($day === $currentDayOfWeek);
            $dayClass = $isToday ? 'day-schedule today' : 'day-schedule';
            
            $schedule = $store->schedules()->where('day_of_week', $day)->first();
            
            $html .= '<div class="' . $dayClass . '">';
            $html .= '<div class="day-name">' . $this->getFrenchDayName($day) . ($isToday ? ' (Aujourd\'hui)' : '') . '</div>';
            
            if ($schedule) {
                if ($schedule->is_closed) {
                    $html .= '<div class="closed-text">Fermé</div>';
                } else {
                    if (isset($schedule->time_slots) && is_array($schedule->time_slots)) {
                        foreach ($schedule->time_slots as $slot) {
                            if (isset($slot['start']) && isset($slot['end'])) {
                                $html .= '<div class="time-slot">' . $slot['start'] . ' - ' . $slot['end'] . '</div>';
                            }
                        }
                    } else {
                        $html .= '<div class="no-hours">Horaires non définis</div>';
                    }
                }
            } else {
                $html .= '<div class="no-hours">Horaires non définis</div>';
            }
            
            $html .= '</div>';
        }
        return $html;
    }

    private function getFrenchDayName($day)
    {
        $frenchDays = [
            'monday' => 'Lundi',
            'tuesday' => 'Mardi',
            'wednesday' => 'Mercredi',
            'thursday' => 'Jeudi',
            'friday' => 'Vendredi',
            'saturday' => 'Samedi',
            'sunday' => 'Dimanche'
        ];
        
        return $frenchDays[$day] ?? $day;
    }
}
