<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Schedule;
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
            ->whereNotNull('day_of_week')
            ->orderByRaw("FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')")
            ->get();
        
        // Récupérer les exceptions (dates spécifiques, non fériées)
        $exceptionSchedules = $store->schedules()
            ->whereNotNull('exception_date')
            ->where('is_holiday', false)
            ->orderBy('exception_date')
            ->get();
        
        // Récupérer les jours fériés
        $holidaySchedules = $store->schedules()
            ->where('is_holiday', true)
            ->orderBy('exception_date')
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
            ->whereNotNull('day_of_week')
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
        
        return view('admin.stores.schedules.regular', compact('store', 'availableDays'));
    }

    /**
     * Affiche le formulaire pour ajouter une exception
     */
    public function createException(Store $store)
    {
        return view('admin.stores.schedules.exception', compact('store'));
    }

    /**
     * Affiche le formulaire pour ajouter un jour férié
     */
    public function createHoliday(Store $store)
    {
        return view('admin.stores.schedules.holiday', compact('store'));
    }

    /**
     * Enregistre un nouvel horaire
     */
    public function store(Request $request, Store $store)
    {
        // Validation commune
        $rules = [
            'is_closed' => 'boolean',
        ];
        
        // Validation spécifique selon le type
        switch ($request->type) {
            case 'regular':
                $rules['day_of_week'] = 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday';
                break;
                
            case 'exception':
                $rules['exception_date'] = 'required|date|after_or_equal:today';
                $rules['exception_reason'] = 'required|string|max:255';
                break;
                
            case 'holiday':
                $rules['exception_date'] = 'required|date|after_or_equal:today';
                $rules['exception_reason'] = 'required|string|max:255';
                $request->merge(['is_holiday' => true]);
                break;
                
            default:
                return redirect()->back()->with('error', 'Type d\'horaire invalide');
        }
        
        // Si le magasin n'est pas fermé, valider les créneaux horaires
        if (!$request->has('is_closed')) {
            $rules['time_slots'] = 'required|array|min:1';
            $rules['time_slots.*.start'] = 'required|date_format:H:i';
            $rules['time_slots.*.end'] = 'required|date_format:H:i|after:time_slots.*.start';
        }
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            // Préparer les données
            $data = [
                'store_id' => $store->id,
                'is_closed' => $request->has('is_closed'),
                'is_holiday' => $request->has('is_holiday'),
                'time_slots' => $request->has('is_closed') ? [] : $request->time_slots,
            ];
            
            // Ajouter les données spécifiques au type
            if ($request->type === 'regular') {
                $data['day_of_week'] = $request->day_of_week;
                
                // Vérifier si un horaire existe déjà pour ce jour
                $existingSchedule = $store->schedules()
                    ->where('day_of_week', $request->day_of_week)
                    ->first();
                    
                if ($existingSchedule) {
                    return redirect()->back()
                        ->with('error', 'Un horaire existe déjà pour ce jour')
                        ->withInput();
                }
            } else {
                $data['exception_date'] = $request->exception_date;
                $data['exception_reason'] = $request->exception_reason;
                
                // Vérifier si une exception existe déjà pour cette date
                $existingException = $store->schedules()
                    ->where('exception_date', $request->exception_date)
                    ->first();
                    
                if ($existingException) {
                    return redirect()->back()
                        ->with('error', 'Une exception existe déjà pour cette date')
                        ->withInput();
                }
            }
            
            // Créer l'horaire
            $schedule = $store->schedules()->create($data);
            
            return redirect()->route('admin.stores.schedules.index', $store)
                ->with('success', 'Horaire ajouté avec succès');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de l\'horaire: ' . $e->getMessage());
            
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
        // Déterminer le type d'horaire
        $type = null;
        if ($schedule->day_of_week) {
            $type = 'regular';
        } elseif ($schedule->is_holiday) {
            $type = 'holiday';
        } else {
            $type = 'exception';
        }
        
        return view('admin.stores.schedules.edit', compact('store', 'schedule', 'type'));
    }

    /**
     * Met à jour un horaire
     */
    public function update(Request $request, Store $store, Schedule $schedule)
    {
        // Validation commune
        $rules = [
            'is_closed' => 'boolean',
        ];
        
        // Validation spécifique selon le type
        if ($schedule->day_of_week) {
            // Horaire régulier
            $rules['day_of_week'] = 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday';
        } else {
            // Exception ou jour férié
            $rules['exception_date'] = 'required|date';
            $rules['exception_reason'] = 'required|string|max:255';
        }
        
        // Si le magasin n'est pas fermé, valider les créneaux horaires
        if (!$request->has('is_closed')) {
            $rules['time_slots'] = 'required|array|min:1';
            $rules['time_slots.*.start'] = 'required|date_format:H:i';
            $rules['time_slots.*.end'] = 'required|date_format:H:i|after:time_slots.*.start';
        }
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            // Préparer les données
            $data = [
                'is_closed' => $request->has('is_closed'),
                'time_slots' => $request->has('is_closed') ? [] : $request->time_slots,
            ];
            
            // Ajouter les données spécifiques au type
            if ($schedule->day_of_week) {
                $data['day_of_week'] = $request->day_of_week;
                
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
            } else {
                $data['exception_date'] = $request->exception_date;
                $data['exception_reason'] = $request->exception_reason;
                
                // Vérifier si une autre exception existe déjà pour cette date
                if ($request->exception_date != $schedule->exception_date->format('Y-m-d')) {
                    $existingException = $store->schedules()
                        ->where('exception_date', $request->exception_date)
                        ->where('id', '!=', $schedule->id)
                        ->first();
                        
                    if ($existingException) {
                        return redirect()->back()
                            ->with('error', 'Une exception existe déjà pour cette date')
                            ->withInput();
                    }
                }
            }
            
            // Mettre à jour l'horaire
            $schedule->update($data);
            
            return redirect()->route('admin.stores.schedules.index', $store)
                ->with('success', 'Horaire mis à jour avec succès');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de l\'horaire: ' . $e->getMessage());
            
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