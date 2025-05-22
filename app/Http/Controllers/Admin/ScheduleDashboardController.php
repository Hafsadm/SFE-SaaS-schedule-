<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Schedule;
use App\Models\Exception;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleDashboardController extends Controller
{
    /**
     * Affiche le tableau de bord des horaires
     */
    public function index(Request $request)
    {
        // Récupérer tous les magasins
        $query = Store::query();
        
        // Filtrer par nom ou ville si spécifié
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('ville', 'like', "%{$search}%");
            });
        }
        
        // Filtrer par statut si spécifié
        if ($request->has('status') && !empty($request->status)) {
            if ($request->status === 'open') {
                $query->where('is_closed', false);
            } elseif ($request->status === 'closed') {
                $query->where('is_closed', true);
            }
        }
        
        $stores = $query->orderBy('nom')->get();
        
        // Récupérer les statistiques globales
        $stats = [
            'total_stores' => Store::count(),
            'open_today' => Store::where('is_closed', false)->count(),
            'closed_today' => Store::where('is_closed', true)->count(),
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
        
        // Récupérer les jours de la semaine pour l'affichage
        $days = [
            'monday' => 'Lundi',
            'tuesday' => 'Mardi',
            'wednesday' => 'Mercredi',
            'thursday' => 'Jeudi',
            'friday' => 'Vendredi',
            'saturday' => 'Samedi',
            'sunday' => 'Dimanche',
        ];
        
        // Récupérer le jour actuel
        $today = strtolower(Carbon::now()->locale('fr')->dayName);
        
        return view('admin.schedules.dashboard', compact(
            'stores', 
            'stats', 
            'upcomingExceptions', 
            'upcomingHolidays', 
            'days', 
            'today'
        ));
    }
    
    /**
     * Affiche la vue calendrier des horaires
     */
    public function calendar(Request $request)
    {
        // Récupérer tous les magasins
        $stores = Store::orderBy('nom')->get();
        
        // Récupérer le mois et l'année actuels ou ceux spécifiés dans la requête
        $month = $request->month ?? Carbon::now()->month;
        $year = $request->year ?? Carbon::now()->year;
        
        // Créer un calendrier pour le mois spécifié
        $firstDay = Carbon::createFromDate($year, $month, 1);
        $lastDay = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        // Récupérer toutes les exceptions pour ce mois
        $exceptions = Exception::whereYear('exception_date', $year)
            ->whereMonth('exception_date', $month)
            ->get()
            ->groupBy(function($exception) {
                return $exception->exception_date->format('Y-m-d');
            });
            
        // Récupérer tous les jours fériés pour ce mois
        $holidays = Holiday::whereYear('holiday_date', $year)
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
        $stores = Store::orderBy('nom')->get();
        
        return view('admin.schedules.bulk', compact('stores'));
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
        
        // Traiter selon le type d'action
        switch ($request->action_type) {
            case 'regular_schedule':
                // Logique pour appliquer des horaires réguliers en masse
                break;
                
            case 'exception':
                // Logique pour appliquer des exceptions en masse
                break;
                
            case 'holiday':
                // Logique pour appliquer des jours fériés en masse
                break;
        }
        
        return redirect()->route('admin.schedules.dashboard')
            ->with('success', 'Horaires appliqués avec succès aux magasins sélectionnés');
    }
}
