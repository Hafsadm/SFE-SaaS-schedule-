<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ExceptionController extends Controller
{
    /**
     * Affiche le formulaire pour ajouter une exception
     */
    public function create(Store $store)
    {
        // Récupérer les exceptions existantes pour les 3 prochains mois
        $startDate = Carbon::today();
        $endDate = Carbon::today()->addMonths(3);
        
        $existingExceptions = $store->exceptions()
            ->whereBetween('exception_date', [$startDate, $endDate])
            ->orderBy('exception_date')
            ->get();
        
        // Récupérer les horaires réguliers pour référence
        $regularSchedules = $store->schedules()
            ->orderByRaw("FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')")
            ->get();
        
        // Créer un tableau des jours de la semaine pour l'affichage
        $daysOfWeek = [
            'monday' => 'Lundi',
            'tuesday' => 'Mardi',
            'wednesday' => 'Mercredi',
            'thursday' => 'Jeudi',
            'friday' => 'Vendredi',
            'saturday' => 'Samedi',
            'sunday' => 'Dimanche',
        ];
        
        // Suggestions de créneaux horaires basées sur les horaires réguliers
        $suggestedTimeSlots = [];
        foreach ($regularSchedules as $schedule) {
            if (!$schedule->is_closed && !empty($schedule->time_slots)) {
                $suggestedTimeSlots = $schedule->time_slots;
                break;
            }
        }
        
        return view('admin.stores.schedules.exception', compact('store', 'existingExceptions', 'regularSchedules', 'daysOfWeek', 'suggestedTimeSlots'));
    }

    /**
     * Enregistre une nouvelle exception
     */
    public function store(Request $request, Store $store)
    {
        Log::info('Début de la méthode store avec les données:', $request->all());
        
        // Validation
        $rules = [
            'exception_date' => 'required|date|after_or_equal:today',
            'exception_raison' => 'required|string|max:255',
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
            // Vérifier si une exception existe déjà pour cette date
            $existingException = $store->exceptions()
                ->where('exception_date', $request->exception_date)
                ->first();
                
            if ($existingException) {
                return redirect()->back()
                    ->with('error', 'Une exception existe déjà pour cette date')
                    ->withInput();
            }
            
            // Préparer les données
            $data = [
                'store_id' => $store->id,
                'exception_date' => $request->exception_date,
                'exception_raison' => $request->exception_raison,
                'is_closed' => $request->has('is_closed'),
                'time_slots' => $request->has('is_closed') ? [] : $request->time_slots,
            ];
            
            // Créer l'exception
            $exception = Exception::create($data);
            
            Log::info('Exception créée avec succès:', ['id' => $exception->id]);
            
            return redirect()->route('admin.stores.schedules.index', $store)
                ->with('success', 'Exception ajoutée avec succès');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de l\'exception: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            
            return redirect()->back()
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Affiche le formulaire pour modifier une exception
     */
    public function edit(Store $store, Exception $exception)
    {
        // Récupérer les horaires réguliers pour référence
        $regularSchedules = $store->schedules()
            ->orderByRaw("FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')")
            ->get();
        
        // Suggestions de créneaux horaires basées sur les horaires réguliers
        $suggestedTimeSlots = [];
        foreach ($regularSchedules as $regularSchedule) {
            if (!$regularSchedule->is_closed && !empty($regularSchedule->time_slots)) {
                $suggestedTimeSlots = $regularSchedule->time_slots;
                break;
            }
        }

        
       $type = 'exception'; // <-- Ajoutez cette ligne
        
       return view('admin.stores.schedules.edit', compact('store', 'exception', 'regularSchedules', 'suggestedTimeSlots', 'type'));    }

    /**
     * Met à jour une exception
     */
    public function update(Request $request, Store $store, Exception $exception)
    {
        Log::info('Début de la méthode update avec les données:', $request->all());
        
        // Validation
        $rules = [
            'exception_date' => 'required|date',
            'exception_raison' => 'required|string|max:255',
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
            // Vérifier si une autre exception existe déjà pour cette date
            if ($request->exception_date != $exception->exception_date->format('Y-m-d')) {
                $existingException = $store->exceptions()
                    ->where('exception_date', $request->exception_date)
                    ->where('id', '!=', $exception->id)
                    ->first();
                    
                if ($existingException) {
                    return redirect()->back()
                        ->with('error', 'Une exception existe déjà pour cette date')
                        ->withInput();
                }
            }
            
            // Préparer les données
            $data = [
                'exception_date' => $request->exception_date,
                'exception_raison' => $request->exception_raison,
                'is_closed' => $request->has('is_closed'),
                'time_slots' => $request->has('is_closed') ? [] : $request->time_slots,
            ];
            
            // Mettre à jour l'exception
            $exception->update($data);
            
            Log::info('Exception mise à jour avec succès:', ['id' => $exception->id]);
            
            return redirect()->route('admin.stores.schedules.index', $store)
                ->with('success', 'Exception mise à jour avec succès');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de l\'exception: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            
            return redirect()->back()
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Supprime une exception
     */
    public function destroy(Store $store, Exception $exception)
    {
        try {
            $exception->delete();
            
            return redirect()->route('admin.stores.schedules.index', $store)
                ->with('success', 'Exception supprimée avec succès');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de l\'exception: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }
}