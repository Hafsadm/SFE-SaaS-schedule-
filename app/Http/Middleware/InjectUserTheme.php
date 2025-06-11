<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Services\ThemeService;
use App\Models\Setting;

class InjectUserTheme
{
    protected $themeService;

    public function __construct(ThemeService $themeService)
    {
        $this->themeService = $themeService;
    }

    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            
            // Récupérer les couleurs de l'utilisateur
            $userColors = $this->themeService->getUserColors($userId);
            $hasCustomColors = $this->themeService->hasCustomColors($userId);
            $cssVariables = $this->themeService->generateCssVariables($userId);
            
            // Récupérer le logo de l'utilisateur
            $userLogo = Setting::where('user_id', $userId)
                ->where('key', 'logo')
                ->first();
            
            $logoPath = $userLogo ? $userLogo->value : null;
            
            // Partager les données avec toutes les vues
            View::share([
                'userColors' => $userColors,
                'hasCustomColors' => $hasCustomColors,
                'cssVariables' => $cssVariables,
                'userLogo' => $logoPath
            ]);
        } else {
            // Utilisateur non connecté - utiliser les couleurs par défaut
            $defaultColors = $this->themeService->getDefaultColors();
            $cssVariables = $this->themeService->generateCssVariables();
            
            View::share([
                'userColors' => $defaultColors,
                'hasCustomColors' => false,
                'cssVariables' => $cssVariables,
                'userLogo' => null
            ]);
        }

        return $next($request);
    }
}
