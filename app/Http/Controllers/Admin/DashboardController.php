<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Termwind\Components\Dd;

class DashboardController extends Controller
{
    public function index()
    {
        $stores = Store::with(['schedules', 'exceptions', 'holidays'])
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
                'services'
            ])
            ->get();
            
        // Pour chaque magasin, vérifier s'il est fermé aujourd'hui
        foreach ($stores as $store) {
            $this->addScheduleInfo($store);
        }

        return view('admin.dashboard', compact('stores'));
    }


    public function book()
    {
        $stores = Store::with(['schedules', 'exceptions', 'holidays'])
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
                'services'
            ])
            ->get();
            
        // Pour chaque magasin, vérifier s'il est fermé aujourd'hui
        foreach ($stores as $store) {
            $this->addScheduleInfo($store);
        }

        return view('user.home', compact('stores'));
    }
    

    public function search(Request $request)
    {
        $query = $request->input('query');
        
        $stores = Store::query()
            ->where('ville', 'LIKE', "%{$query}%")
            ->orWhere('pays', 'LIKE', "%{$query}%")
            ->orWhere('adresse', 'LIKE', "%{$query}%")
            ->orWhere('nom', 'LIKE', "%{$query}%")
            ->with(['schedules', 'exceptions', 'holidays'])
            ->get();
            
        // Pour chaque magasin, ajouter les informations d'horaires
        foreach ($stores as $store) {
            $this->addScheduleInfo($store);
        }
        
        return response()->json($stores);
    }
    
    /**
     * Ajoute les informations d'horaires à un magasin
     */
    private function addScheduleInfo(Store $store)
    {
        $today = Carbon::today();
        $now = Carbon::now();
        $dayOfWeek = strtolower($today->englishDayOfWeek);
        
        // Vérifier s'il y a une exception pour aujourd'hui
        $exception = $store->exceptions()
            ->whereDate('exception_date', $today)
            ->first();
        
        // Vérifier s'il y a un jour férié pour aujourd'hui
        $holiday = $store->holidays()
            ->whereDate('holiday_date', $today)
            ->first();
        
        // Vérifier l'horaire régulier pour aujourd'hui
        $regularSchedule = $store->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->first();
        
        // Déterminer si le magasin est fermé aujourd'hui
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
        
        // Vérifier si le magasin est actuellement ouvert
        $store->is_open = false;
        
        if (!$store->is_closed) {
            // Vérifier si le magasin est ouvert selon le champ ouvert_jusqua
            if ($store->ouvert_jusqua) {
                $heureFermeture = Carbon::parse($store->ouvert_jusqua);
                $store->is_open = $now->lt($heureFermeture);
            }
        
            // Vérifier aussi les créneaux horaires
            if ($regularSchedule && !$regularSchedule->is_closed && isset($regularSchedule->time_slots) && is_array($regularSchedule->time_slots)) {
                foreach ($regularSchedule->time_slots as $slot) {
                    if (isset($slot['start']) && isset($slot['end'])) {
                        $start = Carbon::parse($slot['start']);
                        $end = Carbon::parse($slot['end']);
                    
                        if ($now->between($start, $end)) {
                            $store->is_open = true;
                            break;
                        }
                    }
                }
            }
        }
        
        // Générer le HTML pour les horaires hebdomadaires
        $store->formatted_weekly_hours = $this->generateWeeklyHoursHtml($store);
    }
    
    /**
     * Génère le HTML pour les horaires hebdomadaires
     */
    private function generateWeeklyHoursHtml(Store $store)
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $today = Carbon::today();
        $currentDayOfWeek = strtolower($today->englishDayOfWeek);
        
        $html = '<div class="weekly-hours">';
        
        // Horaires réguliers
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
        
    
    /**
     * Retourne le nom français d'un jour de la semaine
     */
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

    public function filterStores(Request $request)
    {
        // Log pour le débogage
        Log::info('Filtrage des magasins', $request->all());
        
        $query = Store::with(['schedules', 'exceptions', 'holidays']);

        // 1. Filtre par SERVICES (cases cochées Dentiste/Opticien/Audition)
        if ($request->has('specialite') && !empty($request->specialite)) {
            $selectedServices = (array) $request->input('specialite');
            Log::info('Services sélectionnés', $selectedServices);
            
            $query->where(function ($q) use ($selectedServices) {
                foreach ($selectedServices as $serviceId) {
                    // Utiliser LIKE pour rechercher dans le JSON
                    $q->orWhere('services', 'LIKE', "%$serviceId%");
                }
            });
        }
    

        // 2. Filtre par HORAIRES (boutons radio)
        if ($request->has('horaire') && !empty($request->horaire)) {
            $now = Carbon::now();
            $currentDay = strtolower($now->englishDayOfWeek); // "monday", "tuesday", etc.
            $currentTime = $now->format('H:i:s');
            
            Log::info('Filtre horaire', [
                'horaire' => $request->horaire,
                'currentDay' => $currentDay,
                'currentTime' => $currentTime
            ]);

            switch ($request->horaire) {
                case 'open_now':
                    // Approche simplifiée pour les magasins ouverts maintenant
                    $query->where(function($q) use ($currentDay, $currentTime, $now) {
                        // Magasins avec ouvert_jusqua défini et dans le futur
                        $q->where(function($subQ) use ($now) {
                            $subQ->whereNotNull('ouvert_jusqua')
                                ->whereRaw('TIME(ouvert_jusqua) > ?', [$now->format('H:i:s')]);
                        });
                        
                        // OU magasins avec des horaires pour aujourd'hui
                        $q->orWhereHas('schedules', function($scheduleQ) use ($currentDay, $currentTime) {
                            $scheduleQ->where('day_of_week', $currentDay)
                                ->where('is_closed', false);
                        });
                    })
                    // Exclure les magasins avec des exceptions aujourd'hui
                    ->whereDoesntHave('exceptions', function ($q) use ($now) {
                        $q->whereDate('exception_date', $now->toDateString())
                          ->where('is_closed', true);
                    })
                    // Exclure les magasins avec des jours fériés aujourd'hui
                    ->whereDoesntHave('holidays', function ($q) use ($now) {
                        $q->whereDate('holiday_date', $now->toDateString());
                    });
                    break;

                case 'morning': // 6h - 12h
                    $query->whereHas('schedules', function ($q) use ($currentDay) {
                        $q->where('day_of_week', $currentDay)
                          ->where('is_closed', false);
                    });
                    break;

                case 'afternoon': // 12h - 18h
                    $query->whereHas('schedules', function ($q) use ($currentDay) {
                        $q->where('day_of_week', $currentDay)
                          ->where('is_closed', false);
                    });
                    break;

                case 'evening': // 18h - 23h
                    $query->whereHas('schedules', function ($q) use ($currentDay) {
                        $q->where('day_of_week', $currentDay)
                          ->where('is_closed', false);
                    });
                    break;

                case 'weekend': // Samedi/Dimanche
                    $query->whereHas('schedules', function ($q) {
                        $q->whereIn('day_of_week', ['saturday', 'sunday'])
                          ->where('is_closed', false);
                    });
                    break;
            }
        }

        // 3. Filtre par RECHERCHE TEXTE (ville, pays, code postal)
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            Log::info('Terme de recherche', ['search' => $searchTerm]);
            
            $query->where(function ($q) use ($searchTerm) {
                $q->where('ville', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('pays', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('adresse', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('nom', 'LIKE', "%{$searchTerm}%");
            });
        }

        // 4. Filtre "AUTOUR DE MOI" (géolocalisation)
        if ($request->has(['latitude', 'longitude'])) {
            $userLat = $request->latitude;
            $userLng = $request->longitude;
            $radius = 10; // Rayon en km
            
            Log::info('Géolocalisation', [
                'latitude' => $userLat,
                'longitude' => $userLng,
                'radius' => $radius
            ]);

            $query->selectRaw(
                "*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
                cos(radians(longitude) - radians(?)) + sin(radians(?)) * 
                sin(radians(latitude)))) AS distance",
                [$userLat, $userLng, $userLat]
            )
            ->having('distance', '<', $radius)
            ->orderBy('distance');
        }

        // Exécute la requête
        $stores = $query->get();
        
        // Log pour le débogage
        Log::info('Résultats de la recherche', ['count' => $stores->count()]);
        
        // Ajouter les informations d'horaires pour chaque magasin
        foreach ($stores as $store) {
            $this->addScheduleInfo($store);
        }

        return response()->json($stores);
    }

    public function nearbyStores(Request $request)
    {
        // Validation des données d'entrée
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);
    
        $userLat = $request->latitude;
        $userLng = $request->longitude;
        $radius = 10; // Rayon en kilomètres
    
        // Log pour le débogage
        Log::info('Recherche de magasins à proximité', [
            'latitude' => $userLat,
            'longitude' => $userLng,
            'radius' => $radius
        ]);
    
        try {
            $stores = Store::with(['schedules', 'exceptions', 'holidays'])
                ->selectRaw(
                    "*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
                    cos(radians(longitude) - radians(?)) + sin(radians(?)) * 
                    sin(radians(latitude)))) AS distance",
                    [$userLat, $userLng, $userLat]
                )
                ->having('distance', '<', $radius)
                ->orderBy('distance')
                ->get();
                
            // Ajouter les informations d'horaires pour chaque magasin
            foreach ($stores as $store) {
                $this->addScheduleInfo($store);
            }
    
            // Log pour le débogage
            Log::info('Magasins trouvés', ['count' => $stores->count()]);
    
            return response()->json($stores);
        } catch (\Exception $e) {
            // Log l'erreur
            Log::error('Erreur lors de la recherche de magasins à proximité', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            
            return response()->json([
                'error' => 'Une erreur est survenue lors de la recherche de magasins à proximité',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
