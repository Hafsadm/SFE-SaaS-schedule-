<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\ShowStoresController;


Route::get('/', [HomeController::class, 'index'])->name('home');



Route::post('/filter', [HomeController::class, 'filterStores']);
Route::post('/nearby', [HomeController::class, 'nearbyStores']);



// Routes d'authentification
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::get('/filter', [HomeController::class, 'filterStores']);


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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
 

   });

// Route::prefix('user')->name('user.')->group(function () {


// });

require base_path('routes/admin.php');

            
        // Routes pour les points de vente (CRUD)
        Route::resource('stores', ShowStoresController::class);
        Route::get('/show', [ShowStoresController::class, 'show'])->name('show');



