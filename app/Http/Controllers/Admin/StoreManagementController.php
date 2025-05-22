<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Product;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreManagementController extends Controller
{
    /**
     * Affiche le panneau d'administration pour un point de vente
     */
    public function manage($id)
    {
        $store = Store::with(['products', 'staff'])->findOrFail($id);
        
        return view('admin.stores.manage', compact('store'));
    }
    
    /**
     * Affiche le formulaire pour ajouter un produit
     */
    public function createProduct($storeId)
    {
        $store = Store::findOrFail($storeId);
        
        // Déterminer les catégories de produits en fonction des services
        $categories = $this->getProductCategories($store);
        
        return view('admin.stores.products.create', compact('store', 'categories'));
    }
    
    /**
     * Enregistre un nouveau produit
     */
    public function storeProduct(Request $request, $storeId)
    {
        $store = Store::findOrFail($storeId);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'images.*' => 'nullable|image|max:5120', // Accepte plusieurs images jusqu'à 5MB chacune
            'is_available' => 'nullable',
        ]);
        
        // Créer le produit d'abord
        $product = new Product();
        $product->store_id = $store->id;
        $product->name = $validated['name'];
        $product->description = $validated['description'] ?? null;
        $product->price = $validated['price'];
        $product->category = $validated['category'];
        $product->is_available = $request->has('is_available') ? true : false;
        
        // Gérer l'upload de plusieurs images
        $imagesPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('Produit'), $filename);
                $imagesPaths[] = $filename;
            }
        }
        
        // Stocker les chemins des images au format JSON
        $product->image = !empty($imagesPaths) ? json_encode($imagesPaths) : null;
        
        // Sauvegarder le produit
        $store->products()->save($product);
        
        return redirect()->route('admin.stores.manage', $storeId)
            ->with('success', 'Produit ajouté avec succès');
    }
    
    /**
     * Affiche le formulaire pour éditer un produit
     */
    public function editProduct($storeId, $productId)
    {
        $store = Store::findOrFail($storeId);
        $product = Product::findOrFail($productId);
        
        // Vérifier que le produit appartient bien au magasin
        if ($product->store_id != $storeId) {
            return redirect()->route('admin.stores.manage', $storeId)
                ->with('error', 'Ce produit n\'appartient pas à ce point de vente');
        }
        
        $categories = $this->getProductCategories($store);
        
        // Décoder les images JSON en tableau
        $product->imageArray = $product->image ? json_decode($product->image) : [];
        
        return view('admin.stores.products.edit', compact('store', 'product', 'categories'));
    }
    
    /**
     * Met à jour un produit
     */
    public function updateProduct(Request $request, $storeId, $productId)
    {
        $product = Product::findOrFail($productId);
        
        // Vérifier que le produit appartient bien au magasin
        if ($product->store_id != $storeId) {
            return redirect()->route('admin.stores.manage', $storeId)
                ->with('error', 'Ce produit n\'appartient pas à ce point de vente');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'images.*' => 'nullable|image|max:5120',
            'is_available' => 'nullable',
            'delete_images' => 'nullable|array',
        ]);
        
        // Mettre à jour les informations de base
        $product->name = $validated['name'];
        $product->description = $validated['description'] ?? null;
        $product->price = $validated['price'];
        $product->category = $validated['category'];
        $product->is_available = $request->has('is_available') ? true : false;
        
        // Récupérer les images existantes
        $existingImages = $product->image ? json_decode($product->image, true) : [];
        
        // Supprimer les images sélectionnées
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $index) {
                if (isset($existingImages[$index])) {
                    $imagePath = public_path('Produit/' . $existingImages[$index]);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                    unset($existingImages[$index]);
                }
            }
            // Réindexer le tableau
            $existingImages = array_values($existingImages);
        }
        
        // Ajouter de nouvelles images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('Produit'), $filename);
                $existingImages[] = $filename;
            }
        }
        
        // Mettre à jour le champ image
        $product->image = !empty($existingImages) ? json_encode($existingImages) : null;
        
        // Sauvegarder les modifications
        $product->save();
        
        return redirect()->route('admin.stores.manage', $storeId)
            ->with('success', 'Produit mis à jour avec succès');
    }
    
    /**
     * Supprime un produit
     */
    public function destroyProduct($storeId, $productId)
    {
        $product = Product::findOrFail($productId);
        
        // Vérifier que le produit appartient bien au magasin
        if ($product->store_id != $storeId) {
            return redirect()->route('admin.stores.manage', $storeId)
                ->with('error', 'Ce produit n\'appartient pas à ce point de vente');
        }
        
        // Supprimer les images si elles existent
        if ($product->image) {
            $images = json_decode($product->image, true);
            foreach ($images as $image) {
                $imagePath = public_path('Produit/' . $image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }
        
        // Supprimer le produit
        $product->delete();
        
        return redirect()->route('admin.stores.manage', $storeId)
            ->with('success', 'Produit supprimé avec succès');
    }
    
    /**
     * Affiche le formulaire pour ajouter un membre du personnel
     */
    public function createStaff($storeId)
    {
        $store = Store::findOrFail($storeId);
        
        // Déterminer les rôles en fonction des services
        $roles = $this->getStaffRoles($store);
        
        return view('admin.stores.staff.create', compact('store', 'roles'));
    }
    
    /**
     * Enregistre un nouveau membre du personnel
     */
    public function storeStaff(Request $request, $storeId)
    {
        $store = Store::findOrFail($storeId);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string',
            'bio' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|image|max:2048',
        ]);
        
        // Créer le membre du personnel
        $staff = new Staff();
        $staff->store_id = $store->id;
        $staff->name = $validated['name'];
        $staff->role = $validated['role'];
        $staff->bio = $validated['bio'] ?? null;
        $staff->email = $validated['email'] ?? null;
        $staff->phone = $validated['phone'] ?? null;
        
        // Gérer l'upload d'image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('Stuff'), $filename);
            $staff->image = $filename;
        }
        
        // Sauvegarder le membre du personnel
        $store->staff()->save($staff);
        
        return redirect()->route('admin.stores.manage', $storeId)
            ->with('success', 'Membre du personnel ajouté avec succès');
    }
    
    /**
     * Affiche le formulaire pour éditer un membre du personnel
     */
    public function editStaff($storeId, $staffId)
    {
        $store = Store::findOrFail($storeId);
        $staff = Staff::findOrFail($staffId);
        
        // Vérifier que le membre du personnel appartient bien au magasin
        if ($staff->store_id != $storeId) {
            return redirect()->route('admin.stores.manage', $storeId)
                ->with('error', 'Ce membre du personnel n\'appartient pas à ce point de vente');
        }
        
        $roles = $this->getStaffRoles($store);
        
        return view('admin.stores.staff.edit', compact('store', 'staff', 'roles'));
    }
    
    /**
     * Met à jour un membre du personnel
     */
    public function updateStaff(Request $request, $storeId, $staffId)
    {
        $staff = Staff::findOrFail($staffId);
        
        // Vérifier que le membre du personnel appartient bien au magasin
        if ($staff->store_id != $storeId) {
            return redirect()->route('admin.stores.manage', $storeId)
                ->with('error', 'Ce membre du personnel n\'appartient pas à ce point de vente');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string',
            'bio' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|image|max:2048',
        ]);
        
        // Mettre à jour les informations
        $staff->name = $validated['name'];
        $staff->role = $validated['role'];
        $staff->bio = $validated['bio'] ?? null;
        $staff->email = $validated['email'] ?? null;
        $staff->phone = $validated['phone'] ?? null;
        
        // Gérer l'upload d'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($staff->image && file_exists(public_path('Stuff/' . $staff->image))) {
                unlink(public_path('Stuff/' . $staff->image));
            }
            
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('Stuff'), $filename);
            $staff->image = $filename;
        }
        
        // Sauvegarder les modifications
        $staff->save();
        
        return redirect()->route('admin.stores.manage', $storeId)
            ->with('success', 'Membre du personnel mis à jour avec succès');
    }
    
    /**
     * Supprime un membre du personnel
     */
    public function destroyStaff($storeId, $staffId)
    {
        $staff = Staff::findOrFail($staffId);
        
        // Vérifier que le membre du personnel appartient bien au magasin
        if ($staff->store_id != $storeId) {
            return redirect()->route('admin.stores.manage', $storeId)
                ->with('error', 'Ce membre du personnel n\'appartient pas à ce point de vente');
        }
        
        // Supprimer l'image si elle existe
        if ($staff->image && file_exists(public_path('Stuff/' . $staff->image))) {
            unlink(public_path('Stuff/' . $staff->image));
        }
        
        // Supprimer le membre du personnel
        $staff->delete();
        
        return redirect()->route('admin.stores.manage', $storeId)
            ->with('success', 'Membre du personnel supprimé avec succès');
    }
    
    /**
     * Retourne les catégories de produits en fonction des services du magasin
     */
    private function getProductCategories($store)
    {
        $categories = [];
        
        if (is_array($store->services)) {
            foreach ($store->services as $service) {
                if ($service == '1' || $service == 'Dentiste') {
                    $categories = array_merge($categories, [
                        'Consultation' => 'Consultation',
                        'Soins dentaires' => 'Soins dentaires',
                        'Prothèses' => 'Prothèses',
                        'Blanchiment' => 'Blanchiment',
                        'Orthodontie' => 'Orthodontie',
                    ]);
                }
                
                if ($service == '2' || $service == 'Opticien') {
                    $categories = array_merge($categories, [
                        'Montures' => 'Montures',
                        'Verres' => 'Verres',
                        'Lentilles' => 'Lentilles',
                        'Solaires' => 'Solaires',
                        'Accessoires' => 'Accessoires',
                    ]);
                }
                
                if ($service == '3' || $service == 'Audition') {
                    $categories = array_merge($categories, [
                        'Appareils auditifs' => 'Appareils auditifs',
                        'Accessoires auditifs' => 'Accessoires auditifs',
                        'Piles' => 'Piles',
                        'Protection auditive' => 'Protection auditive',
                    ]);
                }
            }
        }
        
        // Ajouter une catégorie générique si aucune n'a été trouvée
        if (empty($categories)) {
            $categories = [
                'Général' => 'Général',
                'Accessoires' => 'Accessoires',
                'Services' => 'Services',
            ];
        }
        
        return $categories;
    }
    
    /**
     * Retourne les rôles du personnel en fonction des services du magasin
     */
    private function getStaffRoles($store)
    {
        $roles = [
            'Responsable' => 'Responsable',
            'Assistant(e)' => 'Assistant(e)',
            'Réceptionniste' => 'Réceptionniste',
        ];
        
        if (is_array($store->services)) {
            foreach ($store->services as $service) {
                if ($service == '1' || $service == 'Dentiste') {
                    $roles = array_merge($roles, [
                        'Dentiste' => 'Dentiste',
                        'Orthodontiste' => 'Orthodontiste',
                        'Assistant(e) dentaire' => 'Assistant(e) dentaire',
                        'Hygiéniste' => 'Hygiéniste',
                    ]);
                }
                
                if ($service == '2' || $service == 'Opticien') {
                    $roles = array_merge($roles, [
                        'Opticien' => 'Opticien',
                        'Optométriste' => 'Optométriste',
                        'Conseiller(ère) en optique' => 'Conseiller(ère) en optique',
                    ]);
                }
                
                if ($service == '3' || $service == 'Audition') {
                    $roles = array_merge($roles, [
                        'Audioprothésiste' => 'Audioprothésiste',
                        'Technicien(ne) en audition' => 'Technicien(ne) en audition',
                        'Conseiller(ère) en audition' => 'Conseiller(ère) en audition',
                    ]);
                }
            }
        }
        
        return $roles;
    }
}
