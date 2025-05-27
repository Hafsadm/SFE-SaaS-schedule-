<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Schedule;
use App\Models\Exception;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ScheduleDashboardController extends Controller
{
    /**
     * Affiche le tableau de bord des horaires
     */
    public function index(Request $request)
    {
        // Récupérer tous les magasins accessibles par l'utilisateur
        $user = Auth::user();
        $query = Store::query();
        
        // Si l'utilisateur n'est pas super admin, limiter aux magasins qu'il gère
        if ($user->role !== 'super_admin') {
            $query->where('user_id', $user->id);
        }
        
        $stores = $query->orderBy('nom')->get();
        
        // Calculer les statistiques pour aujourd'hui
        $today = Carbon::today();
        $dayOfWeek = strtolower($today->englishDayOfWeek);
        
        $openToday = 0;
        $closedToday = 0;
        
        // Pour chaque magasin, vérifier s'il est ouvert ou fermé aujourd'hui
        foreach ($stores as $store) {
            $isOpen = $this->isStoreOpenToday($store, $today, $dayOfWeek);
            
            if ($isOpen) {
                $openToday++;
            } else {
                $closedToday++;
            }
        }
        
        // Récupérer les statistiques globales
        $stats = [
            'open_today' => $openToday,
            'closed_today' => $closedToday,
            'total_exceptions' => Exception::whereDate('exception_date', '>=', Carbon::today())->count(),
            'upcoming_holidays' => Holiday::whereDate('holiday_date', '>=', Carbon::today())->count(),
        ];
        
        // Récupérer les exceptions et jours fériés à venir
        $upcomingExceptions = Exception::with('store')
            ->whereDate('exception_date', '>=', Carbon::today())
            ->orderBy('exception_date')
            ->limit(5)
            ->get();
            
        $upcomingHolidays = Holiday::with('store')
            ->whereDate('holiday_date', '>=', Carbon::today())
            ->orderBy('holiday_date')
            ->limit(5)
            ->get();
        
        return view('admin.schedules.dashboard', compact(
            'stores', 
            'stats', 
            'upcomingExceptions', 
            'upcomingHolidays'
        ));
    }
    
    /**
     * Vérifie si un magasin est ouvert aujourd'hui
     */
    private function isStoreOpenToday($store, $today, $dayOfWeek)
    {
        // Vérifier s'il y a une exception pour aujourd'hui
        $exception = $store->exceptions()
            ->whereDate('exception_date', $today)
            ->first();
        
        if ($exception) {
            return !$exception->is_closed;
        }
        
        // Vérifier s'il y a un jour férié pour aujourd'hui
        $holiday = $store->holidays()
            ->whereDate('holiday_date', $today)
            ->first();
        
        if ($holiday) {
            return false; // Les jours fériés sont toujours fermés
        }
        
        // Vérifier l'horaire régulier pour aujourd'hui
        $regularSchedule = $store->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->first();
        
        if ($regularSchedule) {
            return !$regularSchedule->is_closed;
        }
        
        // Si aucun horaire n'est défini, considérer comme fermé
        return false;
    }
    
    /**
     * Affiche la vue calendrier des horaires
     */
    public function calendar(Request $request)
    {
        // Récupérer tous les magasins accessibles par l'utilisateur
        $user = Auth::user();
        $query = Store::query();
        
        // Si l'utilisateur n'est pas super admin, limiter aux magasins qu'il gère
        if ($user->role !== 'super_admin') {
            $query->where('user_id', $user->id);
        }
        
        $stores = $query->orderBy('nom')->get();
        
        // Récupérer le mois et l'année actuels ou ceux spécifiés dans la requête
        $month = $request->month ?? Carbon::now()->month;
        $year = $request->year ?? Carbon::now()->year;
        
        // Créer un calendrier pour le mois spécifié
        $firstDay = Carbon::createFromDate($year, $month, 1);
        $lastDay = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        // Récupérer toutes les exceptions pour ce mois
        $exceptions = Exception::with('store')
            ->whereYear('exception_date', $year)
            ->whereMonth('exception_date', $month)
            ->get()
            ->groupBy(function($exception) {
                return $exception->exception_date->format('Y-m-d');
            });
            
        // Récupérer tous les jours fériés pour ce mois
        $holidays = Holiday::with('store')
            ->whereYear('holiday_date', $year)
            ->whereMonth('holiday_date', $month)
            ->get()
            ->groupBy(function($holiday) {
                return $holiday->holiday_date->format('Y-m-d');
            });
        
        return view('admin.schedules.calendar', compact(
            'stores', 
            'month', 
            'year', 
            'firstDay', 
            'lastDay', 
            'exceptions', 
            'holidays'
        ));
    }
    
    /**
     * Affiche les horaires d'un magasin spécifique
     */
    public function storeSchedules(Store $store)
    {
        // Vérifier que l'utilisateur a le droit de voir ce magasin
        $user = Auth::user();
        if ($user->role !== 'super_admin' && $store->user_id !== $user->id) {
            abort(403, 'Vous n\'avez pas les droits pour voir les horaires de ce magasin');
        }
        
        // Récupérer les horaires réguliers (jours de la semaine)
        $regularSchedules = $store->schedules()
            ->orderByRaw("FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')")
            ->get();
        
        // Récupérer les exceptions
        $exceptionSchedules = $store->exceptions()
            ->orderBy('exception_date')
            ->get();
        
        // Récupérer les jours fériés
        $holidaySchedules = $store->holidays()
            ->orderBy('holiday_date')
            ->get();
        
        return view('admin.schedules.store', compact('store', 'regularSchedules', 'exceptionSchedules', 'holidaySchedules'));
    }
    
    /**
     * Affiche l'interface de gestion des horaires en masse
     */
    public function bulkManagement()
    {
        // Récupérer tous les magasins accessibles par l'utilisateur
        $user = Auth::user();
        $query = Store::query();
        
        // Si l'utilisateur n'est pas super admin, limiter aux magasins qu'il gère
        if ($user->role !== 'super_admin') {
            $query->where('user_id', $user->id);
        }
        
        $stores = $query->orderBy('nom')->get();
        
        // Récupérer les jours de la semaine
        $days = [
            'monday' => 'Lundi',
            'tuesday' => 'Mardi',
            'wednesday' => 'Mercredi',
            'thursday' => 'Jeudi',
            'friday' => 'Vendredi',
            'saturday' => 'Samedi',
            'sunday' => 'Dimanche',
        ];
        
        return view('admin.schedules.bulk', compact('stores', 'days'));
    }
    
    /**
     * Applique des horaires en masse aux magasins sélectionnés
     */
    public function applyBulkSchedules(Request $request)
    {
        // Valider la requête
        $request->validate([
            'store_ids' => 'required|array',
            'store_ids.*' => 'exists:stores,id',
            'action_type' => 'required|in:regular_schedule,exception,holiday',
        ]);
        
        // Vérifier que l'utilisateur a le droit de modifier ces magasins
        $user = Auth::user();
        $storeIds = $request->store_ids;
        
        if ($user->role !== 'super_admin') {
            $authorizedStores = Store::where('user_id', $user->id)->pluck('id')->toArray();
            $unauthorizedStores = array_diff($storeIds, $authorizedStores);
            
            if (!empty($unauthorizedStores)) {
                return redirect()->back()->with('error', 'Vous n\'avez pas les droits pour modifier certains des magasins sélectionnés');
            }
        }
        
        // Traiter selon le type d'action
        switch ($request->action_type) {
            case 'regular_schedule':
                // Valider les données spécifiques aux horaires réguliers
                $request->validate([
                    'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
                    'time_slots' => 'required_unless:is_closed,on|array',
                    'time_slots.*.start' => 'required_unless:is_closed,on|date_format:H:i',
                    'time_slots.*.end' => 'required_unless:is_closed,on|date_format:H:i|after:time_slots.*.start',
                ]);
                
                // Appliquer les horaires réguliers
                foreach ($storeIds as $storeId) {
                    $store = Store::findOrFail($storeId);
                    
                    // Vérifier si un horaire existe déjà pour ce jour
                    $schedule = Schedule::where('store_id', $storeId)
                        ->where('day_of_week', $request->day_of_week)
                        ->first();
                    
                    if ($schedule) {
                        // Mettre à jour l'horaire existant
                        $schedule->update([
                            'is_closed' => $request->has('is_closed'),
                            'time_slots' => $request->has('is_closed') ? [] : $request->time_slots,
                        ]);
                    } else {
                        // Créer un nouvel horaire
                        Schedule::create([
                            'store_id' => $storeId,
                            'day_of_week' => $request->day_of_week,
                            'is_closed' => $request->has('is_closed'),
                            'time_slots' => $request->has('is_closed') ? [] : $request->time_slots,
                        ]);
                    }
                }
                break;
                
            case 'exception':
                // Valider les données spécifiques aux exceptions
                $request->validate([
                    'exception_date' => 'required|date',
                    'exception_raison' => 'required|string|max:255',
                    'time_slots' => 'required_unless:is_closed,on|array',
                    'time_slots.*.start' => 'required_unless:is_closed,on|date_format:H:i',
                    'time_slots.*.end' => 'required_unless:is_closed,on|date_format:H:i|after:time_slots.*.start',
                ]);
                
                // Appliquer les exceptions
                foreach ($storeIds as $storeId) {
                    $store = Store::findOrFail($storeId);
                    
                    // Vérifier si une exception existe déjà pour cette date
                    $exception = Exception::where('store_id', $storeId)
                        ->whereDate('exception_date', $request->exception_date)
                        ->first();
                    
                    if ($exception) {
                        // Mettre à jour l'exception existante
                        $exception->update([
                            'exception_raison' => $request->exception_raison,
                            'is_closed' => $request->has('is_closed'),
                            'time_slots' => $request->has('is_closed') ? [] : $request->time_slots,
                        ]);
                    } else {
                        // Créer une nouvelle exception
                        Exception::create([
                            'store_id' => $storeId,
                            'exception_date' => $request->exception_date,
                            'exception_raison' => $request->exception_raison,
                            'is_closed' => $request->has('is_closed'),
                            'time_slots' => $request->has('is_closed') ? [] : $request->time_slots,
                        ]);
                    }
                }
                break;
                
            case 'holiday':
                // Valider les données spécifiques aux jours fériés
                $request->validate([
                    'holiday_date' => 'required|date',
                    'holiday_name' => 'required|string|max:255',
                ]);
                
                // Appliquer les jours fériés
                foreach ($storeIds as $storeId) {
                    $store = Store::findOrFail($storeId);
                    
                    // Vérifier si un jour férié existe déjà pour cette date
                    $holiday = Holiday::where('store_id', $storeId)
                        ->whereDate('holiday_date', $request->holiday_date)
                        ->first();
                    
                    if ($holiday) {
                        // Mettre à jour le jour férié existant
                        $holiday->update([
                            'holiday_name' => $request->holiday_name,
                        ]);
                    } else {
                        // Créer un nouveau jour férié
                        Holiday::create([
                            'store_id' => $storeId,
                            'holiday_date' => $request->holiday_date,
                            'holiday_name' => $request->holiday_name,
                        ]);
                    }
                }
                break;
        }
        
        return redirect()->route('admin.schedules.dashboard')
            ->with('success', 'Horaires appliqués avec succès aux magasins sélectionnés');
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
