<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Exception;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ShowStoresController extends Controller
{
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'nom' => 'required|string|max:255',
    //         'adresse' => 'required|string|max:255',
    //         'ville' => 'required|string|max:255',
    //         'pays' => 'required|string|max:255',
    //         'phone' => 'nullable|string|max:20',
    //         'ouvert_jusqua' => 'required|date_format:H:i',
    //         'lien_rdv' => 'nullable|url',
    //         'latitude' => 'required|numeric',
    //         'longitude' => 'required|numeric',
    //         'services' => 'nullable|array',
    //         'services.*' => 'string'
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()
    //             ->withErrors($validator)
    //             ->withInput();
    //     }

    //     $store = Store::create($request->all());

    //     return redirect()->route('admin.stores.index')
    //         ->with('success', 'Point de vente créé avec succès');
    // }

    // public function show(Store $store)
    // {
    //     // $store->load(['schedules', 'exceptions', 'holidays']);
    //      $store->load(['schedules', 'exceptions', 'holidays']);

        
    //     // Générer le HTML pour les horaires hebdomadaires
    //     $store->formatted_weekly_hours = $this->generateWeeklyHoursHtml($store);
        
    //     return view('admin.stores.show', compact('store'));
    // }

    
    // public function search(Request $request)
    // {
    //     $query = $request->input('query');
        
    //     $stores = Store::query()
    //         ->where('ville', 'LIKE', "%{$query}%")
    //         ->orWhere('pays', 'LIKE', "%{$query}%")
    //         ->orWhere('adresse', 'LIKE', "%{$query}%")
    //         ->orWhere('nom', 'LIKE', "%{$query}%")
    //         ->with(['schedules', 'exceptions', 'holidays'])
    //         ->get();
            
    //     // Pour chaque magasin, ajouter les informations d'horaires
    //     foreach ($stores as $store) {
    //         if ($store instanceof \Illuminate\Database\Eloquent\Collection) {
    //             foreach ($store as $singleStore) {
    //                 $this->addScheduleInfo($singleStore);
    //             }
    //         } else {
    //             $this->addScheduleInfo($store);
    //         }
    //     }
        
    //     return response()->json($stores);
    // }
    
    /**
     * Ajoute les informations d'horaires à un magasin
     */
    private function addScheduleInfo(Store $store)
    {
        $today = Carbon::today();
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
                    foreach ($schedule->time_slots as $slot) {
                        $html .= '<div class="time-slot">' . $slot['start'] . ' - ' . $slot['end'] . '</div>';
                    }
                }
            } else {
                $html .= '<div class="no-hours">Horaires non définis</div>';
            }
            
            $html .= '</div>';
        }
        return $html;
    }  
        
        // Exceptions à venir
        // $upcomingExceptions = $store->exceptions()
        //     ->whereDate('exception_date', '>=', $today)
        //     ->orderBy('exception_date')
        //     ->take(5)
        //     ->get();
            
        // if ($upcomingExceptions->isNotEmpty()) {
        //     $html .= '<div class="exceptions-section">';
        //     $html .= '<div class="exceptions-title">Exceptions à venir</div>';
            
        //     foreach ($upcomingExceptions as $exception) {
        //         $html .= '<div class="exception-item">';
        //         $html .= '<div class="exception-date">' . $exception->exception_date->format('d/m/Y') . '</div>';
        //         $html .= '<div class="exception-reason">' . $exception->exception_raison . '</div>';
                
        //         if ($exception->is_closed) {
        //             $html .= '<div class="closed-text">Fermé</div>';
        //         } else {
        //             foreach ($exception->time_slots as $slot) {
        //                 $html .= '<div class="time-slot">' . $slot['start'] . ' - ' . $slot['end'] . '</div>';
        //             }
        //         }
                
        //         $html .= '</div>';
        //     }
            
        //     $html .= '</div>';
        // }
        
        // Jours fériés à venir
        // $upcomingHolidays = $store->holidays()
        //     ->whereDate('holiday_date', '>=', $today)
        //     ->orderBy('holiday_date')
        //     ->take(5)
        //     ->get();
            
        // if ($upcomingHolidays->isNotEmpty()) {
        //     $html .= '<div class="exceptions-section">';
        //     $html .= '<div class="exceptions-title">Jours fériés à venir</div>';
            
        //     foreach ($upcomingHolidays as $holiday) {
        //         $html .= '<div class="exception-item">';
        //         $html .= '<div class="exception-date">' . $holiday->holiday_date->format('d/m/Y') . '</div>';
        //         $html .= '<div class="holiday-text">' . $holiday->holiday_name . ' (Fermé)</div>';
        //         $html .= '</div>';
        //     }
            
        //     $html .= '</div>';
        // }
        
        // $html .= '</div>';
        
    //     return $html;
    // }
    
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


 
    public function show($id)
    {
        // Récupérer le magasin avec ses relations
        $store = Store::with(['schedules', 'exceptions', 'holidays', 'products', 'staff'])
            ->findOrFail($id);
        
        // Ajouter les informations d'horaires
        $this->addScheduleInfo($store);
        
        // Récupérer les avis (à implémenter selon votre modèle de données)
        // $reviews = Review::where('store_id', $id)->get();
        
        // Récupérer les magasins similaires (même service dans la même ville)
        $similarStores = Store::where('id', '!=', $id)
            ->where('ville', $store->ville)
            ->whereJsonContains('services', $store->services[0] ?? null)
            ->limit(3)
            ->get();
            
        foreach ($similarStores as $similarStore) {
            $this->addScheduleInfo($similarStore);
        }
        
        return view('user.stores.show', compact('store', 'similarStores'));
    }
    
    






}