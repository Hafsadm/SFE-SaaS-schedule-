<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Partager les couleurs personnalisées avec toutes les vues
        View::composer('*', function ($view) {
            // Priorité 1: Utilisateur connecté
            if (Auth::check()) {
                $user = Auth::user();
                $colors = [
                    'primary_color' => $user->primary_color,
                    'secondary_color' => $user->secondary_color,
                    'accent_color' => $user->accent_color,
                    'theme' => $user->theme
                ];
                $view->with('themeColors', $colors);
            } 
            // Priorité 2: Admin détecté par le domaine
            elseif (Session::has('current_admin_id')) {
                $adminId = Session::get('current_admin_id');
                $admin = User::find($adminId);
                
                if ($admin) {
                    $colors = [
                        'primary_color' => $admin->primary_color,
                        'secondary_color' => $admin->secondary_color,
                        'accent_color' => $admin->accent_color,
                        'theme' => $admin->theme
                    ];
                    $view->with('themeColors', $colors);
                }
            }
            // Priorité 3: Valeurs par défaut
            else {
                $colors = [
                    'primary_color' => '#0A2E2E',
                    'secondary_color' => '#2A6363',
                    'accent_color' => '#8E6E53',
                    'theme' => 'light'
                ];
                $view->with('themeColors', $colors);
            }
        });
    }
}
