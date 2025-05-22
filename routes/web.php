<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\ShowStoresController;

// Routes publiques
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/filter', [HomeController::class, 'filterStores']);



// Routes pour les points de vente (côté utilisateur)
Route::get('/stores', [ShowStoresController::class, 'index'])->name('stores.index');
Route::get('/stores/{id}', [ShowStoresController::class, 'show'])->name('stores.show');
Route::get('/show', [ShowStoresController::class, 'show'])->name('show');

// Routes d'authentification
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Routes d'inscription
Route::get('/register', [RegisteredUserController::class, 'create'])
    ->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    // Route du profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard (accessible à tous les utilisateurs authentifiés)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Inclusion des routes admin
require base_path('routes/admin.php');
