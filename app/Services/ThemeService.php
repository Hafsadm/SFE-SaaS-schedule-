<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ThemeService
{
    /**
     * Récupérer les couleurs de l'utilisateur connecté ou les couleurs par défaut
     */
    public function getUserColors($userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        if (!$userId) {
            return $this->getDefaultColors();
        }

        // Cache les couleurs pour 1 heure pour améliorer les performances
        return Cache::remember("user_colors_{$userId}", 3600, function () use ($userId) {
            $settings = Setting::getUserSettings($userId, [
                'primary_color',
                'secondary_color',
                'accent_color',
                'theme'
            ]);

            return [
                'primary' => $settings['primary_color'] ?? '#0A2E2E',
                'secondary' => $settings['secondary_color'] ?? '#2A6363',
                'accent' => $settings['accent_color'] ?? '#8E6E53',
                'light' => $this->lightenColor($settings['primary_color'] ?? '#0A2E2E', 30),
                'theme' => $settings['theme'] ?? 'light'
            ];
        });
    }

    /**
     * Couleurs par défaut
     */
    public function getDefaultColors()
    {
        return [
            'primary' => '#0A2E2E',
            'secondary' => '#2A6363',
            'accent' => '#8E6E53',
            'light' => '#C69C72',
            'theme' => 'light'
        ];
    }

    /**
     * Générer le CSS avec les couleurs personnalisées
     */
    public function generateCssVariables($userId = null)
    {
        $colors = $this->getUserColors($userId);
        
        return "
        :root {
            --primary: {$colors['primary']};
            --secondary: {$colors['secondary']};
            --tertiary: {$colors['accent']};
            --light: {$colors['light']};
            --text-dark: #000000;
            --text-light: #FFFFFF;
            --success: #5DBB63;
            --error: #dc3545;
            --danger: #dc3545;
            --warning: #f59e0b;
            --border: #E6D8C3;
            --card-shadow: 0 4px 12px rgba(10, 46, 46, 0.1);
            --transition: all 0.3s ease;
        }
        ";
    }

    /**
     * Vérifier si l'utilisateur a des couleurs personnalisées
     */
    public function hasCustomColors($userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        if (!$userId) {
            return false;
        }

        $customColors = Setting::where('user_id', $userId)
            ->whereIn('key', ['primary_color', 'secondary_color', 'accent_color'])
            ->exists();

        return $customColors;
    }

    /**
     * Éclaircir une couleur
     */
    private function lightenColor($hex, $percent)
    {
        $hex = str_replace('#', '', $hex);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = min(255, $r + ($percent / 100) * (255 - $r));
        $g = min(255, $g + ($percent / 100) * (255 - $g));
        $b = min(255, $b + ($percent / 100) * (255 - $b));

        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }

    /**
     * Invalider le cache des couleurs utilisateur
     */
    public function clearUserColorsCache($userId)
    {
        Cache::forget("user_colors_{$userId}");
    }
}
