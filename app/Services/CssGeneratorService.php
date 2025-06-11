<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CssGeneratorService
{
    /**
     * Générer le fichier CSS personnalisé pour un utilisateur
     */
    public function generateUserCss($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            throw new \Exception("Utilisateur non trouvé");
        }

        // Récupérer les paramètres de couleur de l'utilisateur
        $settings = Setting::getUserSettings($userId, [
            'primary_color',
            'secondary_color',
            'accent_color',
            'theme'
        ]);

        // Valeurs par défaut
        $colors = [
            'primary' => $settings['primary_color'] ?? '#0A2E2E',
            'secondary' => $settings['secondary_color'] ?? '#2A6363',
            'accent' => $settings['accent_color'] ?? '#8E6E53',
            'theme' => $settings['theme'] ?? 'light'
        ];

        // Générer les couleurs dérivées
        $derivedColors = $this->generateDerivedColors($colors);

        // Créer le contenu CSS
        $cssContent = $this->buildCssContent($colors, $derivedColors);

        // Sauvegarder le fichier CSS
        $fileName = "user-{$userId}-theme.css";
        $filePath = "css/themes/{$fileName}";
        
        // Créer le dossier s'il n'existe pas
        $directory = dirname(public_path($filePath));
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Écrire le fichier CSS
        File::put(public_path($filePath), $cssContent);

        return $filePath;
    }

    /**
     * Générer les couleurs dérivées (variations, opacités, etc.)
     */
    private function generateDerivedColors($colors)
    {
        return [
            'primary_light' => $this->lightenColor($colors['primary'], 20),
            'primary_dark' => $this->darkenColor($colors['primary'], 20),
            'primary_alpha_10' => $this->addAlpha($colors['primary'], 0.1),
            'primary_alpha_20' => $this->addAlpha($colors['primary'], 0.2),
            
            'secondary_light' => $this->lightenColor($colors['secondary'], 20),
            'secondary_dark' => $this->darkenColor($colors['secondary'], 20),
            'secondary_alpha_10' => $this->addAlpha($colors['secondary'], 0.1),
            'secondary_alpha_20' => $this->addAlpha($colors['secondary'], 0.2),
            
            'accent_light' => $this->lightenColor($colors['accent'], 20),
            'accent_dark' => $this->darkenColor($colors['accent'], 20),
            'accent_alpha_10' => $this->addAlpha($colors['accent'], 0.1),
            'accent_alpha_20' => $this->addAlpha($colors['accent'], 0.2),
        ];
    }

    /**
     * Construire le contenu CSS complet
     */
    private function buildCssContent($colors, $derivedColors)
    {
        $css = "/* CSS Généré automatiquement pour l'utilisateur */\n";
        $css .= "/* Généré le: " . now()->format('Y-m-d H:i:s') . " */\n\n";

        // Variables CSS personnalisées
        $css .= ":root {\n";
        $css .= "    /* Couleurs principales */\n";
        $css .= "    --primary: {$colors['primary']};\n";
        $css .= "    --secondary: {$colors['secondary']};\n";
        $css .= "    --accent: {$colors['accent']};\n\n";
        
        $css .= "    /* Couleurs dérivées */\n";
        $css .= "    --primary-light: {$derivedColors['primary_light']};\n";
        $css .= "    --primary-dark: {$derivedColors['primary_dark']};\n";
        $css .= "    --primary-alpha-10: {$derivedColors['primary_alpha_10']};\n";
        $css .= "    --primary-alpha-20: {$derivedColors['primary_alpha_20']};\n\n";
        
        $css .= "    --secondary-light: {$derivedColors['secondary_light']};\n";
        $css .= "    --secondary-dark: {$derivedColors['secondary_dark']};\n";
        $css .= "    --secondary-alpha-10: {$derivedColors['secondary_alpha_10']};\n";
        $css .= "    --secondary-alpha-20: {$derivedColors['secondary_alpha_20']};\n\n";
        
        $css .= "    --accent-light: {$derivedColors['accent_light']};\n";
        $css .= "    --accent-dark: {$derivedColors['accent_dark']};\n";
        $css .= "    --accent-alpha-10: {$derivedColors['accent_alpha_10']};\n";
        $css .= "    --accent-alpha-20: {$derivedColors['accent_alpha_20']};\n\n";
        
        // Couleurs système
        $css .= "    /* Couleurs système */\n";
        $css .= "    --success: #5DBB63;\n";
        $css .= "    --error: #dc3545;\n";
        $css .= "    --warning: #f59e0b;\n";
        $css .= "    --info: #3b82f6;\n";
        $css .= "    --text-dark: #000000;\n";
        $css .= "    --text-light: #FFFFFF;\n";
        $css .= "    --border: #E6D8C3;\n";
        $css .= "    --card-shadow: 0 4px 12px rgba(10, 46, 46, 0.1);\n";
        $css .= "}\n\n";

        // Classes utilitaires
        $css .= $this->generateUtilityClasses($colors, $derivedColors);

        return $css;
    }

    /**
     * Générer les classes utilitaires
     */
    private function generateUtilityClasses($colors, $derivedColors)
    {
        $css = "/* Classes utilitaires */\n";
        
        // Classes de couleur de fond
        $css .= ".bg-primary { background-color: var(--primary) !important; }\n";
        $css .= ".bg-secondary { background-color: var(--secondary) !important; }\n";
        $css .= ".bg-accent { background-color: var(--accent) !important; }\n";
        $css .= ".bg-primary-light { background-color: var(--primary-light) !important; }\n";
        $css .= ".bg-secondary-light { background-color: var(--secondary-light) !important; }\n\n";
        
        // Classes de couleur de texte
        $css .= ".text-primary { color: var(--primary) !important; }\n";
        $css .= ".text-secondary { color: var(--secondary) !important; }\n";
        $css .= ".text-accent { color: var(--accent) !important; }\n\n";
        
        // Classes de bordure
        $css .= ".border-primary { border-color: var(--primary) !important; }\n";
        $css .= ".border-secondary { border-color: var(--secondary) !important; }\n";
        $css .= ".border-accent { border-color: var(--accent) !important; }\n\n";

        return $css;
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
     * Assombrir une couleur
     */
    private function darkenColor($hex, $percent)
    {
        $hex = str_replace('#', '', $hex);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = max(0, $r - ($percent / 100) * $r);
        $g = max(0, $g - ($percent / 100) * $g);
        $b = max(0, $b - ($percent / 100) * $b);

        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }

    /**
     * Ajouter de la transparence à une couleur
     */
    private function addAlpha($hex, $alpha)
    {
        $hex = str_replace('#', '', $hex);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return "rgba({$r}, {$g}, {$b}, {$alpha})";
    }

    /**
     * Obtenir le chemin du fichier CSS d'un utilisateur
     */
    public function getUserCssPath($userId)
    {
        return "css/themes/user-{$userId}-theme.css";
    }

    /**
     * Vérifier si un utilisateur a un thème personnalisé
     */
    public function hasCustomTheme($userId)
    {
        $filePath = public_path($this->getUserCssPath($userId));
        return File::exists($filePath);
    }
}
