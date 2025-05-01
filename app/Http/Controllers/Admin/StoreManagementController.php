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
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
        ]);
        
        // Gérer l'upload d'image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }
        
        // Définir la disponibilité
        $validated['is_available'] = $request->has('is_available');
        
        // Créer le produit
        $product = new Product($validated);
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
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
        ]);
        
        // Gérer l'upload d'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }
        
        // Définir la disponibilité
        $validated['is_available'] = $request->has('is_available');
        
        // Mettre à jour le produit
        $product->update($validated);
        
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
        
        // Supprimer l'image si elle existe
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
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
            'role' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|image|max:2048',
        ]);
        
        // Gérer l'upload d'image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('staff', 'public');
            $validated['image'] = $imagePath;
        }
        
        // Créer le membre du personnel
        $staff = new Staff($validated);
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
            'role' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|image|max:2048',
        ]);
        
        // Gérer l'upload d'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($staff->image && Storage::disk('public')->exists($staff->image)) {
                Storage::disk('public')->delete($staff->image);
            }
            
            $imagePath = $request->file('image')->store('staff', 'public');
            $validated['image'] = $imagePath;
        }
        
        // Mettre à jour le membre du personnel
        $staff->update($validated);
        
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
        if ($staff->image && Storage::disk('public')->exists($staff->image)) {
            Storage::disk('public')->delete($staff->image);
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
