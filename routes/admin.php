<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\ExceptionController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\Admin\StoreManagementController;
use App\Http\Controllers\User\HomeController;




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
            
            // Horaires réguliers
            Route::get('/regular', [ScheduleController::class, 'createRegular'])->name('regular');
            Route::post('/regular', [ScheduleController::class, 'store'])->name('store');
            Route::get('/{schedule}/edit', [ScheduleController::class, 'edit'])->name('edit');
            Route::put('/{schedule}', [ScheduleController::class, 'update'])->name('update');
            Route::delete('/{schedule}', [ScheduleController::class, 'destroy'])->name('destroy');
        });
        
        // Routes pour les exceptions
        Route::prefix('stores/{store}/exceptions')->name('stores.exceptions.')->group(function () {
            Route::get('/create', [ExceptionController::class, 'create'])->name('create');
            Route::post('/', [ExceptionController::class, 'store'])->name('store');
            Route::get('/{exception}/edit', [ExceptionController::class, 'edit'])->name('edit');
            Route::put('/{exception}', [ExceptionController::class, 'update'])->name('update');
            Route::delete('/{exception}', [ExceptionController::class, 'destroy'])->name('destroy');
        });


        
        // Routes pour les jours fériés
        Route::prefix('stores/{store}/holidays')->name('stores.holidays.')->group(function () {
            Route::get('/create', [HolidayController::class, 'create'])->name('create');
            Route::post('/', [HolidayController::class, 'store'])->name('store');
            Route::get('/{holiday}/edit', [HolidayController::class, 'edit'])->name('edit');
            Route::put('/{holiday}', [HolidayController::class, 'update'])->name('update');
            Route::delete('/{holiday}', [HolidayController::class, 'destroy'])->name('destroy');
        });


        Route::post('/stores/filter', [DashboardController::class, 'filterStores']);
        Route::post('/stores/nearby', [DashboardController::class, 'filterStores']);


        
    // Routes pour la gestion des points de vente (produits et personnel)
    Route::get('/stores/{store}/manage', [StoreManagementController::class, 'manage'])->name('stores.manage');
    
    // Routes pour les produits
    Route::get('/stores/{store}/products/create', [StoreManagementController::class, 'createProduct'])->name('stores.products.create');
    Route::post('/stores/{store}/products', [StoreManagementController::class, 'storeProduct'])->name('stores.products.store');
    Route::get('/stores/{store}/products/{product}/edit', [StoreManagementController::class, 'editProduct'])->name('stores.products.edit');
    Route::put('/stores/{store}/products/{product}', [StoreManagementController::class, 'updateProduct'])->name('stores.products.update');
    Route::delete('/stores/{store}/products/{product}', [StoreManagementController::class, 'destroyProduct'])->name('stores.products.destroy');
    
    // Routes pour le personnel
    Route::get('/stores/{store}/staff/create', [StoreManagementController::class, 'createStaff'])->name('stores.staff.create');
    Route::post('/stores/{store}/staff', [StoreManagementController::class, 'storeStaff'])->name('stores.staff.store');
    Route::get('/stores/{store}/staff/{staff}/edit', [StoreManagementController::class, 'editStaff'])->name('stores.staff.edit');
    Route::put('/stores/{store}/staff/{staff}', [StoreManagementController::class, 'updateStaff'])->name('stores.staff.update');
    Route::delete('/stores/{store}/staff/{staff}', [StoreManagementController::class, 'destroyStaff'])->name('stores.staff.destroy');
});
