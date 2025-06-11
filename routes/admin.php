<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\ExceptionController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\Admin\StoreManagementController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Admin\ScheduleDashboardController;

Route::prefix('admin')->name('admin.')->group(function () {

    // Routes pour le tableau de bord des horaires (centralisé)
    Route::prefix('schedules')->name('schedules.')->group(function () {
        // Tableau de bord principal des horaires
        Route::get('/', [ScheduleDashboardController::class, 'index'])->name('dashboard');
        
        // Vue calendrier
        Route::get('/calendar', [ScheduleDashboardController::class, 'calendar'])->name('calendar');
        
        // Horaires d'un magasin spécifique
        Route::get('/store/{store}', [ScheduleDashboardController::class, 'storeSchedules'])->name('store');
        
        // Gestion des horaires en masse
        Route::get('/bulk', [ScheduleDashboardController::class, 'bulkManagement'])->name('bulk');
        Route::post('/apply-bulk', [ScheduleDashboardController::class, 'applyBulkSchedules'])->name('apply-bulk');
    });
    
    // Routes pour les paramètres
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::get('/appearance', [SettingsController::class, 'appearance'])->name('appearance');
        Route::post('/appearance', [SettingsController::class, 'updateAppearance'])->name('appearance.update');
        Route::get('/notifications', [SettingsController::class, 'notifications'])->name('notifications');
        Route::post('/notifications', [SettingsController::class, 'updateNotifications'])->name('notifications.update');
        Route::get('/security', [SettingsController::class, 'security'])->name('security');
        Route::post('/security', [SettingsController::class, 'updateSecurity'])->name('security.update');
        Route::get('/system', [SettingsController::class, 'system'])->name('system');
        Route::post('/system', [SettingsController::class, 'updateSystem'])->name('system.update');
        // Ajouter ces routes dans le groupe settings
        Route::post('/clear-cache', [SettingsController::class, 'clearCache'])->name('clear-cache');
        Route::get('/export-config-pdf', [SettingsController::class, 'exportConfigPdf'])->name('export-config-pdf');
        Route::post('/test-connection', [SettingsController::class, 'testConnection'])->name('test-connection');
    });
    
    Route::get('/stores/{store}/subsidiaries', [StoreController::class, 'manageSubsidiaries'])->name('stores.subsidiaries');
    Route::post('/stores/{store}/apply-schedules', [StoreController::class, 'applySchedulesToSubsidiaries'])->name('stores.apply-schedules');

    // Ajouter ces routes dans le groupe admin existant
    Route::get('/stores/bulk-schedule', [StoreController::class, 'bulkScheduleManager'])->name('stores.bulk-schedule');
    Route::post('/stores/apply-bulk-schedule', [StoreController::class, 'applyBulkSchedule'])->name('stores.apply-bulk-schedule');

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

      Route::prefix('translations')->name('translations.')->group(function () {
        Route::get('/', [TranslationController::class, 'index'])->name('index');
        Route::post('/add-language', [TranslationController::class, 'addLanguage'])->name('add-language');
        Route::delete('/remove-language/{languageCode}', [TranslationController::class, 'removeLanguage'])->name('remove-language');
        Route::post('/auto-translate', [TranslationController::class, 'autoTranslate'])->name('auto-translate');
        Route::post('/detect-language', [TranslationController::class, 'detectLanguage'])->name('detect-language');
        Route::post('/translate-files', [TranslationController::class, 'translateLanguageFiles'])->name('translate-files');
    });

});




