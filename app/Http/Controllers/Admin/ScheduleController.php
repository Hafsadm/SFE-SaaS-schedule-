<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Schedule;
use App\Models\Exception;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Affiche la liste des horaires d'un magasin
     */
    public function index(Store $store)
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
        
        return view('admin.stores.schedules.index', compact('store', 'regularSchedules', 'exceptionSchedules', 'holidaySchedules'));
    }

    /**
     * Affiche le formulaire pour choisir le type d'horaire à ajouter
     */
    public function selectType(Store $store)
    {
        return view('admin.stores.schedules.select-type', compact('store'));
    }

    /**
     * Affiche le formulaire pour ajouter un horaire régulier
     */
    public function createRegular(Store $store)
    {
        // Récupérer les jours de la semaine déjà configurés
        $existingDays = $store->schedules()
            ->pluck('day_of_week')
            ->toArray();
        
        // Liste des jours de la semaine
        $days = [
            'monday' => 'Lundi',
            'tuesday' => 'Mardi',
            'wednesday' => 'Mercredi',
            'thursday' => 'Jeudi',
            'friday' => 'Vendredi',
            'saturday' => 'Samedi',
            'sunday' => 'Dimanche',
        ];
        
        // Filtrer les jours disponibles
        $availableDays = array_diff_key($days, array_flip($existingDays));
   
        // Vérifier si le dimanche est déjà existant 
        $sundayExists = in_array('sunday', $existingDays);
        if ($sundayExists) {
            $sundaySchedule = $store->schedules()
                ->where('day_of_week', 'sunday')
                ->first();

            // Si le dimanche est configuré, vérifier s'il est fermé    
            if ($sundaySchedule && $sundaySchedule->is_closed) {
                $availableDays['sunday'] = 'Dimanche (fermé)';
            } else {
                unset($availableDays['sunday']);
            }
        } else {
            // Si le dimanche n'est pas configuré, l'ajouter à la liste des jours disponibles
            $availableDays['sunday'] = 'Dimanche (fermé)'; 
        }   
 
        return view('admin.stores.schedules.regular', compact('store', 'availableDays', 'sundayExists'));
    }

    /**
     * Enregistre un nouvel horaire régulier
     */
    public function store(Request $request, Store $store)
    {
        Log::info('Début de la méthode store avec les données:', $request->all());
        
        // Validation
        $rules = [
            'day_of_week' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
        ];
        
        // Si le magasin n'est pas fermé, valider les créneaux horaires
        if (!$request->has('is_closed')) {
            $rules['time_slots'] = 'required|array|min:1';
            $rules['time_slots.*.start'] = 'required|date_format:H:i';
            $rules['time_slots.*.end'] = 'required|date_format:H:i|after:time_slots.*.start';
        }
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            Log::error('Validation échouée:', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            // Vérifier si un horaire existe déjà pour ce jour
            $existingSchedule = $store->schedules()
                ->where('day_of_week', $request->day_of_week)
                ->first();
                
            if ($existingSchedule) {
                return redirect()->back()
                    ->with('error', 'Un horaire existe déjà pour ce jour')
                    ->withInput();
            }
            
            // Préparer les données
            $data = [
                'store_id' => $store->id,
                'day_of_week' => $request->day_of_week,
                'is_closed' => $request->has('is_closed'),
                'time_slots' => $request->has('is_closed') ? [] : $request->time_slots,
            ];
            
            // Cas spécial pour le dimanche
            if ($request->day_of_week === 'sunday' && !$request->has('sunday_override')) {
                $data['is_closed'] = true;
            }
            
            // Créer l'horaire
            $schedule = Schedule::create($data);
            
            Log::info('Horaire créé avec succès:', ['id' => $schedule->id]);
            
            return redirect()->route('admin.stores.schedules.index', $store)
                ->with('success', 'Horaire ajouté avec succès');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de l\'horaire: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            
            return redirect()->back()
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Affiche le formulaire pour modifier un horaire
     */
    public function edit(Store $store, Schedule $schedule)
    {
        // Liste des jours de la semaine
        $days = [
            'monday' => 'Lundi',
            'tuesday' => 'Mardi',
            'wednesday' => 'Mercredi',
            'thursday' => 'Jeudi',
            'friday' => 'Vendredi',
            'saturday' => 'Samedi',
            'sunday' => 'Dimanche',
        ];
        
        // Vérifier si c'est un dimanche
        $isSunday = ($schedule->day_of_week === 'sunday');
        $type = 'regular';
        return view('admin.stores.schedules.edit', compact('store', 'schedule', 'days', 'isSunday', 'type'));
}
    /**
     * Met à jour un horaire
     */
    
    public function update(Request $request, Store $store, Schedule $schedule)
    {
        Log::info('Début de la méthode update avec les données:', $request->all());
        
        // Validation
        $rules = [
            'day_of_week' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
        ];
        
        // Si le magasin n'est pas fermé, valider les créneaux horaires
        if (!$request->has('is_closed')) {
            $rules['time_slots'] = 'required|array|min:1';
            $rules['time_slots.*.start'] = 'required|date_format:H:i';
            $rules['time_slots.*.end'] = 'required|date_format:H:i|after:time_slots.*.start';
        }
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            Log::error('Validation échouée lors de la mise à jour:', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            // Vérifier si un autre horaire existe déjà pour ce jour
            if ($request->day_of_week !== $schedule->day_of_week) {
                $existingSchedule = $store->schedules()
                    ->where('day_of_week', $request->day_of_week)
                    ->where('id', '!=', $schedule->id)
                    ->first();
                    
                if ($existingSchedule) {
                    return redirect()->back()
                        ->with('error', 'Un horaire existe déjà pour ce jour')
                        ->withInput();
                }
            }
            
            // Préparer les données
            $data = [
                'day_of_week' => $request->day_of_week,
                'is_closed' => $request->has('is_closed'),
                'time_slots' => $request->has('is_closed') ? [] : $request->time_slots,
            ];
            
            // Cas spécial pour le dimanche
            if ($request->day_of_week === 'sunday' && !$request->has('sunday_override')) {
                $data['is_closed'] = true;
            }
            
            // Mettre à jour l'horaire
            $schedule->update($data);
            
            Log::info('Horaire mis à jour avec succès:', ['id' => $schedule->id]);
            
            return redirect()->route('admin.stores.schedules.index', $store)
                ->with('success', 'Horaire mis à jour avec succès');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de l\'horaire: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            
            return redirect()->back()
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Supprime un horaire
     */
    public function destroy(Store $store, Schedule $schedule)
    {
        try {
            $schedule->delete();
            
            return redirect()->route('admin.stores.schedules.index', $store)
                ->with('success', 'Horaire supprimé avec succès');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de l\'horaire: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }
}