<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class HolidayController extends Controller
{
    /**
     * Affiche le formulaire pour ajouter un jour férié
     */
    public function create(Store $store)
    {
        // Récupérer les jours fériés existants pour l'année en cours et la suivante
        $startDate = Carbon::today();
        $endDate = Carbon::today()->addYear()->endOfYear();
        
        $existingHolidays = $store->holidays()
            ->whereBetween('holiday_date', [$startDate, $endDate])
            ->orderBy('holiday_date')
            ->get();
        
        // Liste des jours fériés français courants pour suggestion
        $commonHolidays = [
            ['name' => 'Jour de l\'An', 'date' => Carbon::create(date('Y'), 1, 1)->format('Y-m-d')],
            ['name' => 'Lundi de Pâques', 'date' => $this->getEasterMonday(date('Y'))->format('Y-m-d')],
            ['name' => 'Fête du Travail', 'date' => Carbon::create(date('Y'), 5, 1)->format('Y-m-d')],
            ['name' => 'Victoire 1945', 'date' => Carbon::create(date('Y'), 5, 8)->format('Y-m-d')],
            ['name' => 'Ascension', 'date' => $this->getEasterMonday(date('Y'))->addDays(38)->format('Y-m-d')],
            ['name' => 'Lundi de Pentecôte', 'date' => $this->getEasterMonday(date('Y'))->addDays(49)->format('Y-m-d')],
            ['name' => 'Fête Nationale', 'date' => Carbon::create(date('Y'), 7, 14)->format('Y-m-d')],
            ['name' => 'Assomption', 'date' => Carbon::create(date('Y'), 8, 15)->format('Y-m-d')],
            ['name' => 'Toussaint', 'date' => Carbon::create(date('Y'), 11, 1)->format('Y-m-d')],
            ['name' => 'Armistice 1918', 'date' => Carbon::create(date('Y'), 11, 11)->format('Y-m-d')],
            ['name' => 'Noël', 'date' => Carbon::create(date('Y'), 12, 25)->format('Y-m-d')],
            // Ajouter l'année suivante
            ['name' => 'Jour de l\'An', 'date' => Carbon::create(date('Y')+1, 1, 1)->format('Y-m-d')],
            ['name' => 'Lundi de Pâques', 'date' => $this->getEasterMonday(date('Y')+1)->format('Y-m-d')],
            ['name' => 'Fête du Travail', 'date' => Carbon::create(date('Y')+1, 5, 1)->format('Y-m-d')],
            ['name' => 'Victoire 1945', 'date' => Carbon::create(date('Y')+1, 5, 8)->format('Y-m-d')],
            ['name' => 'Ascension', 'date' => $this->getEasterMonday(date('Y')+1)->addDays(38)->format('Y-m-d')],
            ['name' => 'Lundi de Pentecôte', 'date' => $this->getEasterMonday(date('Y')+1)->addDays(49)->format('Y-m-d')],
            ['name' => 'Fête Nationale', 'date' => Carbon::create(date('Y')+1, 7, 14)->format('Y-m-d')],
            ['name' => 'Assomption', 'date' => Carbon::create(date('Y')+1, 8, 15)->format('Y-m-d')],
            ['name' => 'Toussaint', 'date' => Carbon::create(date('Y')+1, 11, 1)->format('Y-m-d')],
            ['name' => 'Armistice 1918', 'date' => Carbon::create(date('Y')+1, 11, 11)->format('Y-m-d')],
            ['name' => 'Noël', 'date' => Carbon::create(date('Y')+1, 12, 25)->format('Y-m-d')],
        ];
        
        // Filtrer les jours fériés qui sont déjà enregistrés
        $existingHolidayDates = $existingHolidays->pluck('holiday_date')->map(function($date) {
            return $date->format('Y-m-d');
        })->toArray();
        
        $suggestedHolidays = array_filter($commonHolidays, function($holiday) use ($existingHolidayDates, $startDate) {
            return !in_array($holiday['date'], $existingHolidayDates) && Carbon::parse($holiday['date'])->gte($startDate);
        });
        
        return view('admin.stores.schedules.holiday', compact('store', 'existingHolidays', 'suggestedHolidays'));
    }

    /**
     * Enregistre un nouveau jour férié
     */
    public function store(Request $request, Store $store)
    {
        Log::info('Début de la méthode store avec les données:', $request->all());
        
        // Validation
        $rules = [
            'holiday_date' => 'required|date|after_or_equal:today',
            'holiday_name' => 'required|string|max:255',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            Log::error('Validation échouée:', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            // Vérifier si un jour férié existe déjà pour cette date
            $existingHoliday = $store->holidays()
                ->where('holiday_date', $request->holiday_date)
                ->first();
                
            if ($existingHoliday) {
                return redirect()->back()
                    ->with('error', 'Un jour férié existe déjà pour cette date')
                    ->withInput();
            }
            
            // Préparer les données
            $data = [
                'store_id' => $store->id,
                'holiday_date' => $request->holiday_date,
                'holiday_name' => $request->holiday_name,
            ];
            
            // Créer le jour férié
            $holiday = Holiday::create($data);
            
            Log::info('Jour férié créé avec succès:', ['id' => $holiday->id]);
            
            return redirect()->route('admin.stores.schedules.index', $store)
                ->with('success', 'Jour férié ajouté avec succès');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du jour férié: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            
            return redirect()->back()
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Affiche le formulaire pour modifier un jour férié
     */
    public function edit(Store $store, Holiday $holiday)
    {
        // Liste des jours fériés français courants pour suggestion
        $commonHolidays = [
            ['name' => 'Jour de l\'An', 'date' => Carbon::create(date('Y'), 1, 1)->format('Y-m-d')],
            ['name' => 'Lundi de Pâques', 'date' => $this->getEasterMonday(date('Y'))->format('Y-m-d')],
            ['name' => 'Fête du Travail', 'date' => Carbon::create(date('Y'), 5, 1)->format('Y-m-d')],
            ['name' => 'Victoire 1945', 'date' => Carbon::create(date('Y'), 5, 8)->format('Y-m-d')],
            ['name' => 'Ascension', 'date' => $this->getEasterMonday(date('Y'))->addDays(38)->format('Y-m-d')],
            ['name' => 'Lundi de Pentecôte', 'date' => $this->getEasterMonday(date('Y'))->addDays(49)->format('Y-m-d')],
            ['name' => 'Fête Nationale', 'date' => Carbon::create(date('Y'), 7, 14)->format('Y-m-d')],
            ['name' => 'Assomption', 'date' => Carbon::create(date('Y'), 8, 15)->format('Y-m-d')],
            ['name' => 'Toussaint', 'date' => Carbon::create(date('Y'), 11, 1)->format('Y-m-d')],
            ['name' => 'Armistice 1918', 'date' => Carbon::create(date('Y'), 11, 11)->format('Y-m-d')],
            ['name' => 'Noël', 'date' => Carbon::create(date('Y'), 12, 25)->format('Y-m-d')],
        ];
        
        $type = 'holiday'; // <-- Ajoutez cette ligne

        return view('admin.stores.schedules.edit', compact('store', 'holiday', 'commonHolidays', 'type'));
    }

    /**
     * Met à jour un jour férié
     */
    public function update(Request $request, Store $store, Holiday $holiday)
    {
        Log::info('Début de la méthode update avec les données:', $request->all());
        
        // Validation
        $rules = [
            'holiday_date' => 'required|date',
            'holiday_name' => 'required|string|max:255',
        ];
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            Log::error('Validation échouée lors de la mise à jour:', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        try {
            // Vérifier si un autre jour férié existe déjà pour cette date
            if ($request->holiday_date != $holiday->holiday_date->format('Y-m-d')) {
                $existingHoliday = $store->holidays()
                    ->where('holiday_date', $request->holiday_date)
                    ->where('id', '!=', $holiday->id)
                    ->first();
                    
                if ($existingHoliday) {
                    return redirect()->back()
                        ->with('error', 'Un jour férié existe déjà pour cette date')
                        ->withInput();
                }
            }
            
            // Préparer les données
            $data = [
                'holiday_date' => $request->holiday_date,
                'holiday_name' => $request->holiday_name,
            ];
            
            // Mettre à jour le jour férié
            $holiday->update($data);
            
            Log::info('Jour férié mis à jour avec succès:', ['id' => $holiday->id]);
            
            return redirect()->route('admin.stores.schedules.index', $store)
                ->with('success', 'Jour férié mis à jour avec succès');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du jour férié: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            
            return redirect()->back()
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Supprime un jour férié
     */
    public function destroy(Store $store, Holiday $holiday)
    {
        try {
            $holiday->delete();
            
            return redirect()->route('admin.stores.schedules.index', $store)
                ->with('success', 'Jour férié supprimé avec succès');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du jour férié: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    /**
     * Calcule la date du lundi de Pâques pour une année donnée
     */
    private function getEasterMonday($year)
    {
        $easter = Carbon::createFromTimestamp(easter_date($year));
        return $easter->addDay(); // Lundi de Pâques
    }
}