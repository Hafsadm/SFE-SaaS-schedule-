<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Product;
use App\Models\Staff;
use App\Models\Schedule;
use App\Models\Exception;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();
        
        // Récupérer les points de vente de l'utilisateur
        $stores = Store::with(['schedules', 'exceptions', 'holidays', 'products', 'staff'])
            ->where('user_id', $user->id)
            ->get();
            
        // Pour chaque magasin, vérifier s'il est fermé aujourd'hui
        foreach ($stores as $store) {
            $this->addScheduleInfo($store);
        }

        // Statistiques pour les cartes
        $statsData = $this->getStatsData($user);
        
        // Données pour les graphiques
        $storesByCity = $this->getStoresByCity($user);
        $storesByService = $this->getStoresByService($user);
        $productsByCategory = $this->getProductsByCategory($user);
        $staffByRole = $this->getStaffByRole($user);
        
        // Données pour les activités récentes
        $recentActivities = $this->getRecentActivities($user);
        
        // Liste des points de vente pour le tableau
        $storesList = $this->getStoresList($user);

        return view('admin.dashboard', compact(
            'stores', 
            'statsData', 
            'storesByCity', 
            'storesByService', 
            'productsByCategory', 
            'staffByRole', 
            'recentActivities', 
            'storesList'
        ));
    }

    /**
     * Récupère les statistiques générales
     */
    private function getStatsData($user)
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();
        
        // Total des points de vente
        $totalStores = Store::where('user_id', $user->id)->count();
        
        // Nouvelles localisations ce mois-ci
        $newLocationsThisMonth = Store::where('user_id', $user->id)
            ->where('created_at', '>=', $startOfMonth)
            ->count();
        
        // Total des produits
        $totalProducts = Product::whereIn('store_id', function($query) use ($user) {
            $query->select('id')->from('stores')->where('user_id', $user->id);
        })->count();
        
        // Nouveaux produits ce mois-ci
        $newProductsThisMonth = Product::whereIn('store_id', function($query) use ($user) {
            $query->select('id')->from('stores')->where('user_id', $user->id);
        })->where('created_at', '>=', $startOfMonth)->count();
        
        // Total du personnel
        $totalStaff = Staff::whereIn('store_id', function($query) use ($user) {
            $query->select('id')->from('stores')->where('user_id', $user->id);
        })->count();
        
        // Nouveau personnel ce mois-ci
        $newStaffThisMonth = Staff::whereIn('store_id', function($query) use ($user) {
            $query->select('id')->from('stores')->where('user_id', $user->id);
        })->where('created_at', '>=', $startOfMonth)->count();
        
        // Points de vente actifs/inactifs
        $activeStores = Store::where('user_id', $user->id)
            ->where(function($query) {
                $query->whereNotNull('ouvert_jusqua')
                      ->orWhereHas('schedules', function($q) {
                          $q->where('is_closed', false);
                      });
            })->count();
        
        $activeStoresPercentage = $totalStores > 0 ? round(($activeStores / $totalStores) * 100) : 0;
        
        // Calcul du changement par rapport au mois dernier
        $lastMonthActiveStores = Store::where('user_id', $user->id)
            ->where('created_at', '<', $endOfLastMonth)
            ->where(function($query) {
                $query->whereNotNull('ouvert_jusqua')
                      ->orWhereHas('schedules', function($q) {
                          $q->where('is_closed', false);
                      });
            })->count();
        
        $lastMonthTotalStores = Store::where('user_id', $user->id)
            ->where('created_at', '<', $endOfLastMonth)
            ->count();
        
        $lastMonthPercentage = $lastMonthTotalStores > 0 ? round(($lastMonthActiveStores / $lastMonthTotalStores) * 100) : 0;
        $activeStoresChange = $lastMonthPercentage > 0 ? $activeStoresPercentage - $lastMonthPercentage : 0;
        
        return [
            'totalStores' => $totalStores,
            'newLocationsThisMonth' => $newLocationsThisMonth,
            'totalProducts' => $totalProducts,
            'newProductsThisMonth' => $newProductsThisMonth,
            'totalStaff' => $totalStaff,
            'newStaffThisMonth' => $newStaffThisMonth,
            'activeStoresPercentage' => $activeStoresPercentage,
            'activeStoresChange' => $activeStoresChange,
        ];
    }
    
    /**
     * Récupère la répartition des points de vente par ville
     */
    private function getStoresByCity($user)
    {
        $cities = Store::where('user_id', $user->id)
            ->select('ville', DB::raw('count(*) as total'))
            ->groupBy('ville')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        // Formater les données pour le graphique
        $result = [];
        foreach ($cities as $city) {
            $result[] = [
                'name' => $city->ville,
                'value' => $city->total
            ];
        }
        
        // Ajouter une catégorie "Autres" pour les villes restantes
        $otherCities = Store::where('user_id', $user->id)
            ->whereNotIn('ville', $cities->pluck('ville')->toArray())
            ->count();
        
        if ($otherCities > 0) {
            $result[] = [
                'name' => 'Autres',
                'value' => $otherCities
            ];
        }
        
        return $result;
    }
    
    /**
     * Récupère la répartition des points de vente par service
     */
    private function getStoresByService($user)
    {
        $stores = Store::where('user_id', $user->id)->get();
        
        $serviceCount = [
            'Dentiste' => 0,
            'Opticien' => 0,
            'Audition' => 0,
            'Autres' => 0
        ];
        
        foreach ($stores as $store) {
            if (is_array($store->services)) {
                foreach ($store->services as $service) {
                    if ($service == '1' || $service == 'Dentiste') {
                        $serviceCount['Dentiste']++;
                    } elseif ($service == '2' || $service == 'Opticien') {
                        $serviceCount['Opticien']++;
                    } elseif ($service == '3' || $service == 'Audition') {
                        $serviceCount['Audition']++;
                    } else {
                        $serviceCount['Autres']++;
                    }
                }
            } else {
                $serviceCount['Autres']++;
            }
        }
        
        // Formater les données pour le graphique
        $result = [];
        foreach ($serviceCount as $service => $count) {
            if ($count > 0) {
                $result[] = [
                    'name' => $service,
                    'value' => $count
                ];
            }
        }
        
        return $result;
    }
    
    /**
     * Récupère la répartition des produits par catégorie
     */
    private function getProductsByCategory($user)
    {
        $products = Product::whereIn('store_id', function($query) use ($user) {
            $query->select('id')->from('stores')->where('user_id', $user->id);
        })->get();
        
        $categoryCount = [];
        
        foreach ($products as $product) {
            $category = $product->category;
            if (!isset($categoryCount[$category])) {
                $categoryCount[$category] = 0;
            }
            $categoryCount[$category]++;
        }
        
        // Formater les données pour le graphique
        $result = [];
        foreach ($categoryCount as $category => $count) {
            $result[] = [
                'name' => $category,
                'value' => $count
            ];
        }
        
        // Trier par nombre de produits décroissant
        usort($result, function($a, $b) {
            return $b['value'] - $a['value'];
        });
        
        // Limiter à 5 catégories + "Autres"
        if (count($result) > 5) {
            $others = array_slice($result, 5);
            $othersCount = array_sum(array_column($others, 'value'));
            $result = array_slice($result, 0, 5);
            $result[] = [
                'name' => 'Autres',
                'value' => $othersCount
            ];
        }
        
        return $result;
    }
    
    /**
     * Récupère la répartition du personnel par rôle
     */
    private function getStaffByRole($user)
    {
        $staff = Staff::whereIn('store_id', function($query) use ($user) {
            $query->select('id')->from('stores')->where('user_id', $user->id);
        })->get();
        
        $roleCount = [];
        
        foreach ($staff as $member) {
            $role = $member->role;
            if (!isset($roleCount[$role])) {
                $roleCount[$role] = 0;
            }
            $roleCount[$role]++;
        }
        
        // Formater les données pour le graphique
        $result = [];
        foreach ($roleCount as $role => $count) {
            $result[] = [
                'name' => $role,
                'value' => $count
            ];
        }
        
        // Trier par nombre de membres décroissant
        usort($result, function($a, $b) {
            return $b['value'] - $a['value'];
        });
        
        // Limiter à 5 rôles + "Autres"
        if (count($result) > 5) {
            $others = array_slice($result, 5);
            $othersCount = array_sum(array_column($others, 'value'));
            $result = array_slice($result, 0, 5);
            $result[] = [
                'name' => 'Autres',
                'value' => $othersCount
            ];
        }
        
        return $result;
    }
    
    /**
     * Récupère les activités récentes
     */
    private function getRecentActivities($user)
    {
        // Récupérer les 3 derniers points de vente créés ou modifiés
        $recentStores = Store::where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->limit(3)
            ->get();
        
        // Récupérer les 2 derniers produits ajoutés
        $recentProducts = Product::whereIn('store_id', function($query) use ($user) {
            $query->select('id')->from('stores')->where('user_id', $user->id);
        })
        ->orderBy('created_at', 'desc')
        ->limit(2)
        ->get();
        
        // Récupérer les 2 derniers membres du personnel ajoutés
        $recentStaff = Staff::whereIn('store_id', function($query) use ($user) {
            $query->select('id')->from('stores')->where('user_id', $user->id);
        })
        ->orderBy('created_at', 'desc')
        ->limit(2)
        ->get();
        
        // Récupérer les 2 derniers horaires réguliers modifiés
        $recentSchedules = Schedule::whereIn('store_id', function($query) use ($user) {
            $query->select('id')->from('stores')->where('user_id', $user->id);
        })
        ->orderBy('updated_at', 'desc')
        ->limit(2)
        ->get();
        
        // Récupérer les 2 dernières exceptions ajoutées
        $recentExceptions = Exception::whereIn('store_id', function($query) use ($user) {
            $query->select('id')->from('stores')->where('user_id', $user->id);
        })
        ->orderBy('created_at', 'desc')
        ->limit(2)
        ->get();
        
        // Récupérer les 2 derniers jours fériés ajoutés
        $recentHolidays = Holiday::whereIn('store_id', function($query) use ($user) {
            $query->select('id')->from('stores')->where('user_id', $user->id);
        })
        ->orderBy('created_at', 'desc')
        ->limit(2)
        ->get();
        
        // Combiner les activités
        $activities = [];
        
        foreach ($recentStores as $store) {
            $isNew = $store->created_at->eq($store->updated_at);
            $activities[] = [
                'id' => 'store_' . $store->id,
                'type' => $isNew ? 'creation' : 'modification',
                'storeName' => $store->nom,
                'date' => $isNew ? $store->created_at : $store->updated_at,
                'user' => $user->name,
                'avatar' => $this->getInitials($user->name),
            ];
        }
        
        foreach ($recentProducts as $product) {
            $store = Store::find($product->store_id);
            if ($store) {
                $activities[] = [
                    'id' => 'product_' . $product->id,
                    'type' => 'product_added',
                    'storeName' => $store->nom . ' - ' . $product->name,
                    'date' => $product->created_at,
                    'user' => $user->name,
                    'avatar' => $this->getInitials($user->name),
                ];
            }
        }
        
        foreach ($recentStaff as $staff) {
            $store = Store::find($staff->store_id);
            if ($store) {
                $activities[] = [
                    'id' => 'staff_' . $staff->id,
                    'type' => 'staff_added',
                    'storeName' => $store->nom . ' - ' . $staff->name,
                    'date' => $staff->created_at,
                    'user' => $user->name,
                    'avatar' => $this->getInitials($user->name),
                ];
            }
        }
        
        // Ajouter les activités d'horaires réguliers
        foreach ($recentSchedules as $schedule) {
            $store = Store::find($schedule->store_id);
            if ($store) {
                $dayName = $this->getFrenchDayName($schedule->day_of_week);
                $activities[] = [
                    'id' => 'schedule_' . $schedule->id,
                    'type' => 'schedule_updated',
                    'storeName' => $store->nom . ' - Horaire ' . $dayName,
                    'date' => $schedule->updated_at,
                    'user' => $user->name,
                    'avatar' => $this->getInitials($user->name),
                    'details' => $schedule->is_closed ? 'Fermé' : 'Modifié',
                ];
            }
        }
        
        // Ajouter les activités d'exceptions
        foreach ($recentExceptions as $exception) {
            $store = Store::find($exception->store_id);
            if ($store) {
                $activities[] = [
                    'id' => 'exception_' . $exception->id,
                    'type' => 'exception_added',
                    'storeName' => $store->nom . ' - Exception le ' . $exception->exception_date->format('d/m/Y'),
                    'date' => $exception->created_at,
                    'user' => $user->name,
                    'avatar' => $this->getInitials($user->name),
                    'details' => $exception->exception_raison,
                ];
            }
        }
        
        // Ajouter les activités de jours fériés
        foreach ($recentHolidays as $holiday) {
            $store = Store::find($holiday->store_id);
            if ($store) {
                $activities[] = [
                    'id' => 'holiday_' . $holiday->id,
                    'type' => 'holiday_added',
                    'storeName' => $store->nom . ' - ' . $holiday->holiday_name,
                    'date' => $holiday->created_at,
                    'user' => $user->name,
                    'avatar' => $this->getInitials($user->name),
                    'details' => 'Jour férié le ' . $holiday->holiday_date->format('d/m/Y'),
                ];
            }
        }
        
        // Trier par date décroissante
        usort($activities, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        // Limiter à 8 activités
        return array_slice($activities, 0, 8);
    }
    
    /**
     * Récupère les initiales d'un nom
     */
    private function getInitials($name)
    {
        $words = explode(' ', $name);
        $initials = '';
        
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        
        return substr($initials, 0, 2);
    }
    
    /**
     * Récupère la liste des points de vente pour le tableau
     */
    private function getStoresList($user)
    {
        // Récupérer les 5 derniers points de vente modifiés
        $stores = Store::where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
        
        $result = [];
        foreach ($stores as $store) {
            // Déterminer si le magasin est actif
            $isActive = $this->isStoreActive($store);
            
            // Formater les services
            $services = [];
            if (is_array($store->services)) {
                foreach ($store->services as $service) {
                    if ($service == '1') {
                        $services[] = 'Dentiste';
                    } elseif ($service == '2') {
                        $services[] = 'Opticien';
                    } elseif ($service == '3') {
                        $services[] = 'Audition';
                    } else {
                        $services[] = $service;
                    }
                }
            } elseif (is_string($store->services)) {
                $services[] = $store->services;
            }
            
            // Compter les produits et le personnel
            $productsCount = $store->products()->count();
            $staffCount = $store->staff()->count();
            
            $result[] = [
                'id' => $store->id,
                'name' => $store->nom,
                'city' => $store->ville,
                'services' => $services,
                'productsCount' => $productsCount,
                'staffCount' => $staffCount,
                'status' => $isActive ? 'active' : 'inactive',
                'lastModified' => $store->updated_at,
            ];
        }
        
        return $result;
    }
    
    /**
     * Détermine si un magasin est actif
     */
    private function isStoreActive($store)
    {
        // Un magasin est considéré comme actif s'il a des horaires définis
        // et s'il n'est pas fermé définitivement
        return $store->ouvert_jusqua || 
               $store->schedules()->where('is_closed', false)->exists();
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
}
