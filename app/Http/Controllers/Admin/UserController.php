<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('check.role:super_admin');
    // }

    /**
     * Affiche la liste des utilisateurs
     */
    public function index()
    {
        $users = User::all();
        return view('admin.stores.users.index', compact('users'));
    }

    /**
     * Affiche le formulaire de création d'un utilisateur
     */
    public function create()
    {
        return view('admin.stores.users.create');
    }

    /**
     * Enregistre un nouvel utilisateur
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:admin,super_admin',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('super-admin.users.index')
            ->with('success', 'Utilisateur créé avec succès');
    }

    /**
     * Affiche le formulaire d'édition d'un utilisateur
     */
    public function edit(User $user)
    {
        return view('admin.stores.users.edit', compact('user'));
    }

    /**
     * Met à jour un utilisateur
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'role' => 'required|in:admin,super_admin',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Mettre à jour le mot de passe uniquement s'il est fourni
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('super-admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès');
    }

    /**
     * Supprime un utilisateur
     */
    public function destroy(User $user)
    {
        // Vérifier si l'utilisateur a des points de vente
        $storesCount = Store::where('user_id', $user->id)->count();
        
        if ($storesCount > 0) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer cet utilisateur car il possède des points de vente. Veuillez d\'abord réaffecter ou supprimer ces points de vente.');
        }
        
        $user->delete();
        
        return redirect()->route('super-admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès');
    }

    /**
     * Affiche le tableau de bord du super admin
     */
    public function dashboard()
    {
        // Statistiques des utilisateurs
        $totalUsers = User::count();
        $adminUsers = User::where('role', 'admin')->count();
        $superAdminUsers = User::where('role', 'super_admin')->count();
        
        // Statistiques des points de vente
        $totalStores = Store::count();
        $mainStores = Store::where('is_main_store', true)->count();
        $subsidiaries = Store::whereNotNull('parent_store_id')->count();
        $independentStores = Store::where('is_main_store', false)->whereNull('parent_store_id')->count();
        
        // Statistiques par utilisateur
        $userStats = User::withCount('stores')->orderBy('stores_count', 'desc')->get();
        
        return view('admin.stores.dashboard', compact(
            'totalUsers', 'adminUsers', 'superAdminUsers',
            'totalStores', 'mainStores', 'subsidiaries', 'independentStores',
            'userStats'
        ));
    }
}
