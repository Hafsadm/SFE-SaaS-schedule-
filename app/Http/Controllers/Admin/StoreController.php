<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Exception;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::with(['schedules', 'exceptions', 'holidays'])->get();
        
        // Pour chaque magasin, vérifier s'il est fermé aujourd'hui
        foreach ($stores as $store) {
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
        
        return view('admin.stores.index', compact('stores'));
    }

    public function create()
    {
        // Pas besoin de passer la variable $store ici
        return view('admin.stores.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'pays' => 'required|string|max:255',
            'code_postal' => 'nullable|string|max:20',
            'region' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'ouvert_jusqua' => 'required|date_format:H:i',
            'lien_rdv' => 'nullable|url',
            'site_web' => 'nullable|url',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'services_text' => 'nullable|string',
            'annee_ouverture' => 'nullable|integer|min:1900|max:' . date('Y'),
            'exterior_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'interior_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'equipment_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        $data = $request->except(['image', 'exterior_image', 'interior_image', 'equipment_image', 'services_text']);
        
        // Traitement des services (conversion du texte en tableau)
        if ($request->has('services_text')) {
            $servicesText = $request->input('services_text');
            $servicesArray = array_map('trim', explode(',', $servicesText));
            $data['services'] = $servicesArray;
        }
        
        // Traitement de l'image principale (ancienne colonne)
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('stores', 'public');
        }
    
        // Traitement de l'image extérieure
     // Traitement de l'image extérieure
     if ($request->hasFile('exterior_image')) {
        $file = $request->file('exterior_image');
        $filename = $this->sanitizeFilename($file->getClientOriginalName());
        $file->move(public_path('Pic Stores'), $filename);
        $data['exterior_image'] = $filename;
    }
    
   
    
    // Traitement de l'image intérieure
    if ($request->hasFile('interior_image')) {
        $file = $request->file('interior_image');
        $filename = $this->sanitizeFilename($file->getClientOriginalName());
        $file->move(public_path('Pic Stores'), $filename);
        $data['interior_image'] = $filename;
    }
    
    // Traitement de l'image des équipements
    if ($request->hasFile('equipment_image')) {
        $file = $request->file('equipment_image');
        $filename = $this->sanitizeFilename($file->getClientOriginalName());
        $file->move(public_path('Pic Stores'), $filename);
        $data['equipment_image'] = $filename;
    }
    
        $store = Store::create($data);
    
        return redirect()->route('admin.stores.index')
            ->with('success', 'Point de vente créé avec succès');
    
        }

        private function sanitizeFilename($filename)
        {
            // Remplace les espaces par des underscores
            $clean = str_replace(' ', '_', $filename);
            // Supprime les caractères spéciaux dangereux
            $clean = preg_replace('/[^A-Za-z0-9_.-]/', '', $clean);
            // Garde la dernière extension (gère les fichiers avec plusieurs points)
            return pathinfo($clean, PATHINFO_FILENAME) . '.' . strtolower(pathinfo($clean, PATHINFO_EXTENSION));
        }


    public function edit(Store $store)
    {
        return view('admin.stores.edit', compact('store'));
    }

    public function update(Request $request, Store $store)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'pays' => 'required|string|max:255',
            'code_postal' => 'nullable|string|max:20',
            'region' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'ouvert_jusqua' => 'required|date_format:H:i',
            'lien_rdv' => 'nullable|url',
            'site_web' => 'nullable|url',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'services_text' => 'nullable|string',
            'annee_ouverture' => 'nullable|integer|min:1900|max:' . date('Y'),
            'exterior_image' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'interior_image' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'equipment_image' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        $data = $request->except(['image', 'exterior_image', 'interior_image', 'equipment_image', 'services_text']);
        
        // Traitement des services
        if ($request->has('services_text')) {
            $servicesText = $request->input('services_text');
            $servicesArray = array_map('trim', explode(',', $servicesText));
            $data['services'] = $servicesArray;
        }
        
        // Méthode helper pour traiter les images
        $processImage = function ($file, $currentImage) {
            // Supprimer l'ancienne image si elle existe
            if ($currentImage && file_exists(public_path('Pic Stores/'.$currentImage))) {
                unlink(public_path('Pic Stores/'.$currentImage));
            }
            
            $filename = $this->sanitizeFilename($file->getClientOriginalName());
            $file->move(public_path('Pic Stores'), $filename);
            return $filename;
        };
    
        // Traitement des images
        if ($request->hasFile('exterior_image')) {
            $data['exterior_image'] = $processImage($request->file('exterior_image'), $store->exterior_image);
        }
    
        if ($request->hasFile('interior_image')) {
            $data['interior_image'] = $processImage($request->file('interior_image'), $store->interior_image);
        }
    
        if ($request->hasFile('equipment_image')) {
            $data['equipment_image'] = $processImage($request->file('equipment_image'), $store->equipment_image);
        }
    
        $store->update($data);
    
        return redirect()->route('admin.stores.index')
            ->with('success', 'Point de vente mis à jour avec succès');
    }
    public function destroy(Store $store)
    {
        // Supprimer les images si elles existent
        $deleteImage = function ($filename) {
            if ($filename && file_exists(public_path('Pic Stores/'.$filename))) {
                unlink(public_path('Pic Stores/'.$filename));
            }
        };
    
        // Suppression de toutes les images
        $deleteImage($store->image);
        $deleteImage($store->exterior_image);
        $deleteImage($store->interior_image);
        $deleteImage($store->equipment_image);
    
        $store->delete();
    
        return redirect()->route('admin.stores.index')
            ->with('success', 'Point de vente supprimé avec succès');
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
        
        $html .= '</div>';
        
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
