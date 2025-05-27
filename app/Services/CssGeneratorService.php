<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\File;

class CssGeneratorService
{
    public function generateCssForUser(User $user)
    {
        $cssContent = $this->generateCssContent($user);
        $cssPath = public_path($user->getCssFilePath());
        
        // Créer le dossier s'il n'existe pas
        $cssDir = dirname($cssPath);
        if (!File::exists($cssDir)) {
            File::makeDirectory($cssDir, 0755, true);
        }
        
        // Écrire le fichier CSS
        File::put($cssPath, $cssContent);
        
        return $cssPath;
    }

    private function generateCssContent(User $user)
    {
        return "
/* CSS personnalisé pour {$user->company_name} */
:root {
    --primary: {$user->primary_color};
    --secondary: {$user->secondary_color};
    --tertiary: {$user->accent_color};
    --light: " . $this->lightenColor($user->primary_color, 40) . ";
    --text-dark: #000000;
    --text-light: #FFFFFF;
    --success: #5DBB63;
    --error: #dc3545;
    --warning: #f59e0b;
    --border: " . $this->lightenColor($user->primary_color, 80) . ";
    --card-shadow: 0 4px 12px rgba(" . $this->hexToRgb($user->primary_color) . ", 0.1);
}

/* Thème sombre */
" . ($user->theme === 'dark' ? "
body {
    background-color: var(--primary);
    color: var(--text-light);
}

.store-card {
    background-color: rgba(" . $this->hexToRgb($user->secondary_color) . ", 0.8);
    border-color: var(--secondary);
}
" : "") . "

/* Styles personnalisés */
.btn-primary {
    background-color: var(--primary);
    border-color: var(--primary);
}

.btn-primary:hover {
    background-color: var(--secondary);
}

.dashboard-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
}

.title::after {
    background-color: var(--tertiary);
}

.store-badge {
    background-color: var(--primary);
}

.filter-button:hover {
    background-color: var(--secondary);
    color: var(--text-light);
}

.location-search {
    background-color: var(--primary);
}

.ok-button {
    background: var(--primary);
}
";
    }

    private function hexToRgb($hex)
    {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        return "$r, $g, $b";
    }

    private function lightenColor($hex, $percent)
    {
        $hex = ltrim($hex, '#');
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        $r = min(255, $r + ($percent * 255 / 100));
        $g = min(255, $g + ($percent * 255 / 100));
        $b = min(255, $b + ($percent * 255 / 100));
        
        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }
}
