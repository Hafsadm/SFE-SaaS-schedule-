<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Schedule;
use App\Models\Exception;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StoreController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('check.role:admin');
    // }

    public function index()
    {
        $user = Auth::user();
        
        // Si c'est un super admin, il peut voir tous les points de vente
        // Sinon, l'admin ne voit que ses propres points de vente
        if ($user->role === 'super_admin') {
            $stores = Store::with(['schedules', 'exceptions', 'holidays'])
                // ->orderBy('is_main_store', 'desc') // Afficher d'abord les magasins principaux
                ->get();
        } else {
            $stores = Store::with(['schedules', 'exceptions', 'holidays'])
                ->where('user_id', $user->id)
                // ->orderBy('is_main_store', 'desc') // Afficher d'abord les magasins principaux
                ->get();
        }
        
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
        $user = Auth::user();
        
        // Récupérer les magasins principaux de l'utilisateur pour le sélecteur de magasin parent
        if ($user->role === 'super_admin') {
            $mainStores = Store::where('is_main_store', true)->get();
        } else {
            $mainStores = Store::where('user_id', $user->id)
                // ->where('is_main_store', true)
                ->get();
        }
        
        return view('admin.stores.create', compact('mainStores'));
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
            // 'is_main_store' => 'nullable|boolean',
            'parent_store_id' => 'nullable|exists:stores,id',
            'copy_parent_schedule' => 'nullable|boolean',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        $data = $request->except(['image', 'exterior_image', 'interior_image', 'equipment_image', 'services_text', 'copy_parent_schedule']);
        
        // Traitement des services (conversion du texte en tableau)
        if ($request->has('services_text')) {
            $servicesText = $request->input('services_text');
            $servicesArray = array_map('trim', explode(',', $servicesText));
            $data['services'] = $servicesArray;
        }
        
        // Définir l'utilisateur propriétaire
        $data['user_id'] = Auth::id();
        
        // Gérer les relations magasin principal/filiale
        // if ($request->has('is_main_store') && $request->is_main_store) {
        //     $data['is_main_store'] = true;
        //     $data['parent_store_id'] = null;
        // } else {
        //     $data['is_main_store'] = false;
        //     // Si parent_store_id n'est pas fourni, c'est un magasin indépendant
        //     if (!$request->has('parent_store_id') || !$request->parent_store_id) {
        //         $data['parent_store_id'] = null;
        //     }
        // }
        
        // Traitement de l'image principale (ancienne colonne)
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('stores', 'public');
        }
    
        // Traitement des images
        if ($request->hasFile('exterior_image')) {
            $file = $request->file('exterior_image');
            $filename = $this->sanitizeFilename($file->getClientOriginalName());
            $file->move(public_path('Pic Stores'), $filename);
            $data['exterior_image'] = $filename;
        }
        
        if ($request->hasFile('interior_image')) {
            $file = $request->file('interior_image');
            $filename = $this->sanitizeFilename($file->getClientOriginalName());
            $file->move(public_path('Pic Stores'), $filename);
            $data['interior_image'] = $filename;
        }
        
        if ($request->hasFile('equipment_image')) {
            $file = $request->file('equipment_image');
            $filename = $this->sanitizeFilename($file->getClientOriginalName());
            $file->move(public_path('Pic Stores'), $filename);
            $data['equipment_image'] = $filename;
        }
    
        $store = Store::create($data);
        
        // Copier les horaires du magasin parent si demandé
        if ($request->has('copy_parent_schedule') && $request->copy_parent_schedule && $request->parent_store_id) {
            $this->copySchedulesFromParent($store, $request->parent_store_id);
        }
    
        return redirect()->route('admin.stores.index')
            ->with('success', 'Point de vente créé avec succès');
    }

    /**
     * Copie les horaires du magasin parent vers le magasin filiale
     */
    private function copySchedulesFromParent($store, $parentStoreId)
    {
        $parentStore = Store::findOrFail($parentStoreId);
        
        // Copier les horaires réguliers
        foreach ($parentStore->schedules as $parentSchedule) {
            $store->schedules()->create([
                'day_of_week' => $parentSchedule->day_of_week,
                'is_closed' => $parentSchedule->is_closed,
                'time_slots' => $parentSchedule->time_slots,
            ]);
        }
        
        // Copier les exceptions
        foreach ($parentStore->exceptions as $parentException) {
            $store->exceptions()->create([
                'exception_date' => $parentException->exception_date,
                'exception_raison' => $parentException->exception_raison,
                'is_closed' => $parentException->is_closed,
                'time_slots' => $parentException->time_slots,
            ]);
        }
        
        // Copier les jours fériés
        foreach ($parentStore->holidays as $parentHoliday) {
            $store->holidays()->create([
                'holiday_date' => $parentHoliday->holiday_date,
                'holiday_name' => $parentHoliday->holiday_name,
            ]);
        }
    }

    /**
     * Applique les horaires à toutes les filiales d'un magasin principal
     */
    public function applySchedulesToSubsidiaries(Store $store)
    {
        // Vérifier que c'est bien un magasin principal
        // if (!$store->is_main_store) {
        //     return redirect()->back()->with('error', 'Cette action n\'est disponible que pour les magasins principaux');
        // }
        
        // Vérifier que l'utilisateur a le droit de modifier ce magasin
        $user = Auth::user();
        if ($user->role !== 'super_admin' && $store->user_id !== $user->id) {
            abort(403, 'Vous n\'avez pas les droits pour effectuer cette action');
        }
        
        // Récupérer toutes les filiales
        $subsidiaries = $store->subsidiaries;
        
        // Pour chaque filiale, copier les horaires du magasin principal
        foreach ($subsidiaries as $subsidiary) {
            // Supprimer les horaires existants
            $subsidiary->schedules()->delete();
            $subsidiary->exceptions()->delete();
            $subsidiary->holidays()->delete();
            
            // Copier les nouveaux horaires
            $this->copySchedulesFromParent($subsidiary, $store->id);
        }
        
        return redirect()->route('admin.stores.index')
            ->with('success', 'Les horaires ont été appliqués à toutes les filiales avec succès');
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
        // Vérifier que l'utilisateur a le droit de modifier ce magasin
        $user = Auth::user();
        if ($user->role !== 'super_admin' && $store->user_id !== $user->id) {
            abort(403, 'Vous n\'avez pas les droits pour modifier ce point de vente');
        }
        
        // Récupérer les magasins principaux pour le sélecteur
        if ($user->role === 'super_admin') {
            $mainStores = Store::where('is_main_store', true)
                ->where('id', '!=', $store->id) // Exclure le magasin actuel
                ->get();
        } else {
            $mainStores = Store::where('user_id', $user->id)
                // ->where('is_main_store', true)
                ->where('id', '!=', $store->id) // Exclure le magasin actuel
                ->get();
        }
        
        return view('admin.stores.edit', compact('store', 'mainStores'));
    }

    public function update(Request $request, Store $store)
    {
        // Vérifier que l'utilisateur a le droit de modifier ce magasin
        $user = Auth::user();
        if ($user->role !== 'super_admin' && $store->user_id !== $user->id) {
            abort(403, 'Vous n\'avez pas les droits pour modifier ce point de vente');
        }
        
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
            // 'is_main_store' => 'nullable|boolean',
            'parent_store_id' => 'nullable|exists:stores,id',
            'copy_parent_schedule' => 'nullable|boolean',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        $data = $request->except(['image', 'exterior_image', 'interior_image', 'equipment_image', 'services_text', 'copy_parent_schedule']);
        
        // Traitement des services
        if ($request->has('services_text')) {
            $servicesText = $request->input('services_text');
            $servicesArray = array_map('trim', explode(',', $servicesText));
            $data['services'] = $servicesArray;
        }
        
        // Gérer les relations magasin principal/filiale
        // if ($request->has('is_main_store')) {
        //     if ($request->is_main_store) {
        //         $data['is_main_store'] = true;
        //         $data['parent_store_id'] = null;
        //     } else {
        //         $data['is_main_store'] = false;
        //         // Si parent_store_id n'est pas fourni, c'est un magasin indépendant
        //         if (!$request->has('parent_store_id') || !$request->parent_store_id) {
        //             $data['parent_store_id'] = null;
        //         } else {
        //             // Vérifier que le magasin parent n'est pas une filiale du magasin actuel
        //             // pour éviter les références circulaires
        //             $parentStore = Store::find($request->parent_store_id);
        //             if ($parentStore && $parentStore->parent_store_id == $store->id) {
        //                 return redirect()->back()
        //                     ->with('error', 'Impossible de créer une référence circulaire entre les magasins')
        //                     ->withInput();
        //             }
        //             $data['parent_store_id'] = $request->parent_store_id;
        //         }
        //     }
        // }
        
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
        
        // Copier les horaires du magasin parent si demandé
        if ($request->has('copy_parent_schedule') && $request->copy_parent_schedule && $request->parent_store_id) {
            // Supprimer les horaires existants
            $store->schedules()->delete();
            $store->exceptions()->delete();
            $store->holidays()->delete();
            
            // Copier les nouveaux horaires
            $this->copySchedulesFromParent($store, $request->parent_store_id);
        }
    
        return redirect()->route('admin.stores.index')
            ->with('success', 'Point de vente mis à jour avec succès');
    }

    public function destroy(Store $store)
    {
        // Vérifier que l'utilisateur a le droit de supprimer ce magasin
        $user = Auth::user();
        if ($user->role !== 'super_admin' && $store->user_id !== $user->id) {
            abort(403, 'Vous n\'avez pas les droits pour supprimer ce point de vente');
        }
        
        // Vérifier si c'est un magasin principal avec des filiales
        // if ($store->is_main_store && $store->subsidiaries()->count() > 0) {
        //     return redirect()->back()
        //         ->with('error', 'Impossible de supprimer ce magasin principal car il possède des filiales. Veuillez d\'abord supprimer ou réaffecter les filiales.');
        // }
        
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
        $user = Auth::user();
        
        // Construire la requête de base
        $storesQuery = Store::query()
            ->where(function($q) use ($query) {
                $q->where('ville', 'LIKE', "%{$query}%")
                  ->orWhere('pays', 'LIKE', "%{$query}%")
                  ->orWhere('adresse', 'LIKE', "%{$query}%")
                  ->orWhere('nom', 'LIKE', "%{$query}%");
            })
            ->with(['schedules', 'exceptions', 'holidays']);
        
        // Filtrer par utilisateur si ce n'est pas un super admin
        if ($user->role !== 'super_admin') {
            $storesQuery->where('user_id', $user->id);
        }
        
        $stores = $storesQuery->get();
            
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
    
    /**
     * Affiche la page de gestion des filiales pour un magasin principal
     */
    // public function manageSubsidiaries(Store $store)
    // {
    //     // Vérifier que c'est bien un magasin principal
    //     if (!$store->is_main_store) {
    //         return redirect()->route('admin.stores.index')
    //             ->with('error', 'Cette action n\'est disponible que pour les magasins principaux');
    //     }
        
    //     // Vérifier que l'utilisateur a le droit de gérer ce magasin
    //     $user = Auth::user();
    //     if ($user->role !== 'super_admin' && $store->user_id !== $user->id) {
    //         abort(403, 'Vous n\'avez pas les droits pour gérer ce point de vente');
    //     }
        
    //     // Récupérer les filiales
    //     $subsidiaries = $store->subsidiaries;
        
    //     return view('admin.stores.subsidiaries', compact('store', 'subsidiaries'));
    // }


    public function bulkScheduleManager()
    {
        // Récupérer les points de vente de l'utilisateur connecté
        $stores = Store::where('user_id', Auth::id())->get();
        
        // Récupérer les jours de la semaine en français
        $daysOfWeek = [
            'monday' => 'Lundi',
            'tuesday' => 'Mardi',
            'wednesday' => 'Mercredi',
            'thursday' => 'Jeudi',
            'friday' => 'Vendredi',
            'saturday' => 'Samedi',
            'sunday' => 'Dimanche'
        ];
        
        return view('admin.stores.bulk-schedule', compact('stores', 'daysOfWeek'));
    }
    
    /**
     * Applique les horaires ou fermetures à plusieurs points de vente
     */




 public function applyBulkSchedule(Request $request)
{
    // Valider les données
    $validated = $request->validate([
        'store_ids' => 'required|array',
        'store_ids.*' => 'exists:stores,id',
        'action_type' => 'required|in:regular_schedule,exception,temporary_closure,holiday',
        'day_of_week' => 'required_if:action_type,regular_schedule',
        'is_closed' => 'boolean',
        'time_slots' => 'array',
        'time_slots.*.start' => 'required_with:time_slots',
        'time_slots.*.end' => 'required_with:time_slots',
        'exception_date' => 'required_if:action_type,exception|date',
        'exception_raison' => 'nullable|string',
        'holiday_date' => 'required_if:action_type,holiday|date',
        'holiday_name' => 'required_if:action_type,holiday|string',
    ]);
    
    // Récupérer les points de vente sélectionnés
    $stores = Store::whereIn('id', $request->store_ids)->get();
    
    // Vérifier que l'utilisateur a accès à ces points de vente
    $user = Auth::user();
    foreach ($stores as $store) {
        if ($store->user_id !== $user->id && !($user->role === 'super_admin')) {
            return redirect()->back()->with('error', 'Vous n\'avez pas accès à certains points de vente sélectionnés.');
        }
    }
    
    // Appliquer les modifications selon le type d'action
    switch ($request->action_type) {
        case 'regular_schedule':
            $this->applyRegularSchedule($stores, $request);
            $message = 'Les horaires réguliers ont été appliqués avec succès à ' . count($stores) . ' points de vente.';
            break;
            
        case 'exception':
            $this->applyException($stores, $request);
            $message = 'L\'exception a été appliquée avec succès à ' . count($stores) . ' points de vente.';
            break;
            
        case 'temporary_closure':
            // Gérer les fermetures temporaires si nécessaire
            $message = 'La fermeture temporaire a été appliquée avec succès à ' . count($stores) . ' points de vente.';
            break;
            
        case 'holiday':
            $this->applyHoliday($stores, $request);
            $message = 'Le jour férié a été appliqué avec succès à ' . count($stores) . ' points de vente.';
            break;
    }
    
    return redirect()->route('admin.stores.index')->with('success', $message);
}
    
    /**
     * Applique un horaire régulier à plusieurs points de vente
     */
    private function applyRegularSchedule($stores, $request)
    {
        foreach ($stores as $store) {
            // Vérifier si un horaire existe déjà pour ce jour
            $schedule = Schedule::where('store_id', $store->id)
                ->where('day_of_week', $request->day_of_week)
                ->first();
                
            if ($schedule) {
                // Mettre à jour l'horaire existant
                $schedule->update([
                    'is_closed' => $request->has('is_closed'),
                    'time_slots' => $request->has('is_closed') ? null : $request->time_slots,
                ]);
            } else {
                // Créer un nouvel horaire
                Schedule::create([
                    'store_id' => $store->id,
                    'day_of_week' => $request->day_of_week,
                    'is_closed' => $request->has('is_closed'),
                    'time_slots' => $request->has('is_closed') ? null : $request->time_slots,
                ]);
            }
        }
    }
    
    /**
     * Applique une exception à plusieurs points de vente
     */
    private function applyException($stores, $request)
    {
        foreach ($stores as $store) {
            // Vérifier si une exception existe déjà pour cette date
            $exception = Exception::where('store_id', $store->id)
                ->whereDate('exception_date', $request->exception_date)
                ->first();
                
            if ($exception) {
                // Mettre à jour l'exception existante
                $exception->update([
                    'exception_raison' => $request->exception_raison,
                    'is_closed' => $request->has('is_closed'),
                    'time_slots' => $request->has('is_closed') ? null : $request->time_slots,
                ]);
            } else {
                // Créer une nouvelle exception
                Exception::create([
                    'store_id' => $store->id,
                    'exception_date' => $request->exception_date,
                    'exception_raison' => $request->exception_raison,
                    'is_closed' => $request->has('is_closed'),
                    'time_slots' => $request->has('is_closed') ? null : $request->time_slots,
                ]);
            }
        }
    }
    
    /**
     * Applique un jour férié à plusieurs points de vente
     */
    private function applyHoliday($stores, $request)
    {
        foreach ($stores as $store) {
            // Vérifier si un jour férié existe déjà pour cette date
            $holiday = Holiday::where('store_id', $store->id)
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
                    'store_id' => $store->id,
                    'holiday_date' => $request->holiday_date,
                    'holiday_name' => $request->holiday_name,
                ]);
            }
        }
    }




}
