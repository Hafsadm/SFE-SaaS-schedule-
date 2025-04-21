<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;

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

Route::middleware(['auth'])->group(function () {
    // Route du profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
 
    // Routes pour l'administration
    Route::prefix('admin')->name('admin.')->group(function () {
        // Dashboard admin
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Routes pour la recherche de magasins
        Route::get('/stores/search', [StoreController::class, 'search'])->name('stores.search');
        
        // Routes pour les points de vente (CRUD)
        Route::resource('stores', StoreController::class);
        
        // Routes pour les horaires des points de vente
        Route::prefix('stores/{store}/schedules')->name('stores.schedules.')->group(function () {
            // Liste des horaires
            Route::get('/', [ScheduleController::class, 'index'])->name('index');
            
            // Sélection du type d'horaire
            Route::get('/select-type', [ScheduleController::class, 'selectType'])->name('select-type');
            
            // Création d'horaires selon le type
            Route::get('/regular', [ScheduleController::class, 'createRegular'])->name('regular');
            Route::get('/exception', [ScheduleController::class, 'createException'])->name('exception');
            Route::get('/holiday', [ScheduleController::class, 'createHoliday'])->name('holiday');
            
            // Enregistrement d'un horaire
            Route::post('/', [ScheduleController::class, 'store'])->name('store');
            
            // Édition et mise à jour d'un horaire
            Route::get('/{schedule}/edit', [ScheduleController::class, 'edit'])->name('edit');
            Route::put('/{schedule}', [ScheduleController::class, 'update'])->name('update');
            
            // Suppression d'un horaire
            Route::delete('/{schedule}', [ScheduleController::class, 'destroy'])->name('destroy');
        });
    });
});