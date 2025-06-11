<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Services\CssGeneratorService;
use App\Services\ThemeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;


class SettingsController extends Controller
{
    protected $cssGenerator;
    protected $themeService;

    public function __construct(CssGeneratorService $cssGenerator, ThemeService $themeService)
    {
        $this->cssGenerator = $cssGenerator;
        $this->themeService = $themeService;

    }

    public function index()
    {
        return view('admin.settings.index');
    }

    public function system()
    {
        $user = Auth::user();
        
        // Récupérer les paramètres depuis la table users ET settings
        $settings = [
            // Depuis la table users
            'app_name' => $user->website_name ?? 'Mon SaaS',
            'app_description' => $user->description ?? 'Système de gestion des horaires de points de vente',
            'company_name' => $user->company_name ?? 'Ma Société',
            'website_url' => $user->website_url ?? 'mon-saas.com',
            'timezone' => $user->timezone ?? 'Europe/Paris',
            'language' => $user->locale ?? 'fr',
            
            // Depuis la table settings
            'date_format' => $this->getUserSetting($user->id, 'date_format', 'd/m/Y'),
            'time_format' => $this->getUserSetting($user->id, 'time_format', 'H:i'),
            'maintenance_mode' => $this->getUserSetting($user->id, 'maintenance_mode', false),
            'maintenance_message' => $this->getUserSetting($user->id, 'maintenance_message', 'L\'application est temporairement indisponible pour maintenance. Veuillez réessayer plus tard.'),
        ];

        // Récupérer les couleurs du thème pour le CSS
        $themeColors = [
            'primary_color' => $user->primary_color ?? '#0A2E2E',
            'secondary_color' => $user->secondary_color ?? '#2A6363',
        ];

        return view('admin.settings.system', compact('settings', 'themeColors'));
    }

    public function updateSystem(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'app_name' => 'required|string|max:255',
            'app_description' => 'nullable|string|max:1000',
            'company_name' => 'required|string|max:255',
            'website_url' => 'required|string|max:255',
            'timezone' => 'required|string',
            'language' => 'required|string|in:fr,en,es,de',
            'date_format' => 'required|string|in:d/m/Y,m/d/Y,Y-m-d',
            'time_format' => 'required|string|in:H:i,g:i A',
            'maintenance_message' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            Log::error('Validation échouée:', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = Auth::user();
            
            Log::info('Début de la mise à jour des paramètres système pour l\'utilisateur: ' . $user->id);

            DB::transaction(function () use ($user, $request) {
                
                // 1. METTRE À JOUR LA TABLE USERS
                $userUpdateData = [
                    'website_name' => $request->app_name,
                    'description' => $request->app_description,
                    'company_name' => $request->company_name,
                    'website_url' => $this->cleanWebsiteUrl($request->website_url),
                    'timezone' => $request->timezone,
                    'locale' => $request->language,
                    'settings_updated_at' => now(),
                ];

                $updated = User::where('id', $user->id)->update($userUpdateData);
                Log::info('Mise à jour table users réussie:', ['updated' => $updated]);

                // 2. METTRE À JOUR LA TABLE SETTINGS (méthode sécurisée)
                $this->safeUpdateUserSetting($user->id, 'date_format', $request->date_format);
                $this->safeUpdateUserSetting($user->id, 'time_format', $request->time_format);
                $this->safeUpdateUserSetting($user->id, 'maintenance_mode', $request->has('maintenance_mode') ? 'true' : 'false');
                
                if ($request->filled('maintenance_message')) {
                    $this->safeUpdateUserSetting($user->id, 'maintenance_message', $request->maintenance_message);
                }

                Log::info('Tous les paramètres système mis à jour avec succès');
            });

            return redirect()->route('admin.settings.system')
                ->with('success', 'Les paramètres système ont été mis à jour avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des paramètres système: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour des paramètres: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * MÉTHODE SÉCURISÉE : Mettre à jour ou créer un paramètre (évite absolument les doublons)
     */
    private function safeUpdateUserSetting($userId, $key, $value)
    {
        try {
            Log::info("Tentative de mise à jour du paramètre: {$key} = {$value} pour user {$userId}");
            
            // Vérifier d'abord si le paramètre existe
            $existingSetting = Setting::where('user_id', $userId)
                                    ->where('key', $key)
                                    ->first();
            
            if ($existingSetting) {
                // Le paramètre existe, le mettre à jour
                $existingSetting->value = $value;
                $existingSetting->updated_at = now();
                $result = $existingSetting->save();
                
                Log::info("Paramètre existant mis à jour: {$key} = {$value}, résultat: " . ($result ? 'succès' : 'échec'));
                return $existingSetting;
            } else {
                // Le paramètre n'existe pas, le créer
                $newSetting = new Setting();
                $newSetting->user_id = $userId;
                $newSetting->key = $key;
                $newSetting->value = $value;
                $newSetting->created_at = now();
                $newSetting->updated_at = now();
                $result = $newSetting->save();
                
                Log::info("Nouveau paramètre créé: {$key} = {$value}, résultat: " . ($result ? 'succès' : 'échec'));
                return $newSetting;
            }
            
        } catch (\Exception $e) {
            Log::error("Erreur lors de la mise à jour du paramètre {$key}: " . $e->getMessage());
            
            // En cas d'erreur, essayer une approche alternative avec DB::statement
            try {
                Log::info("Tentative de récupération avec requête SQL directe pour {$key}");
                
                $result = DB::statement("
                    INSERT INTO settings (user_id, key, value, created_at, updated_at) 
                    VALUES (?, ?, ?, NOW(), NOW()) 
                    ON DUPLICATE KEY UPDATE 
                    value = VALUES(value), 
                    updated_at = NOW()
                ", [$userId, $key, $value]);
                
                Log::info("Requête SQL directe réussie pour {$key}");
                return true;
                
            } catch (\Exception $e2) {
                Log::error("Erreur même avec la requête SQL directe pour {$key}: " . $e2->getMessage());
                throw $e2;
            }
        }
    }

    /**
     * Vider le cache de l'application
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            
            Log::info('Cache vidé avec succès');
            
            return response()->json([
                'success' => true,
                'message' => 'Cache vidé avec succès ! Les modifications seront visibles immédiatement.'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du vidage du cache: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du vidage du cache: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Tester la connexion à la base de données
     */
    public function testConnection()
    {
        try {
            $pdo = DB::connection()->getPdo();
            $result = DB::select('SELECT 1 as test');
            $connectionName = DB::getDefaultConnection();
            $databaseName = DB::connection()->getDatabaseName();
            
            Log::info('Test de connexion réussi');
            
            return response()->json([
                'success' => true,
                'message' => "Connexion à la base de données OK !\nBase: {$databaseName}\nConnexion: {$connectionName}"
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur de connexion: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur de connexion à la base de données: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Exporter la configuration
     */
    public function exportConfig()
    {
        try {
            $user = Auth::user();
            
            $config = [
                'informations_generales' => [
                    'app_name' => $user->website_name,
                    'company_name' => $user->company_name,
                    'description' => $user->description,
                    'website_url' => $user->website_url,
                ],
                'parametres_regionaux' => [
                    'timezone' => $user->timezone,
                    'language' => $user->locale,
                    'date_format' => $this->getUserSetting($user->id, 'date_format', 'd/m/Y'),
                    'time_format' => $this->getUserSetting($user->id, 'time_format', 'H:i'),
                ],
                'maintenance' => [
                    'maintenance_mode' => $this->getUserSetting($user->id, 'maintenance_mode', false),
                    'maintenance_message' => $this->getUserSetting($user->id, 'maintenance_message', ''),
                ],
                'meta' => [
                    'exported_at' => now()->toISOString(),
                    'exported_by' => $user->name,
                    'version' => '1.0'
                ]
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Configuration exportée avec succès !',
                'data' => $config
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'export: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'export: ' . $e->getMessage()
            ]);
        }
    }

    // Méthodes utilitaires
    private function getUserSetting($userId, $key, $default = null)
    {
        try {
            $setting = Setting::where('user_id', $userId)->where('key', $key)->first();
            $value = $setting ? $setting->value : $default;
            
            // Convertir les valeurs booléennes
            if ($value === 'true') return true;
            if ($value === 'false') return false;
            
            return $value;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du paramètre ' . $key . ': ' . $e->getMessage());
            return $default;
        }
    }

    private function cleanWebsiteUrl($url)
    {
        $cleanUrl = preg_replace('/^https?:\/\//', '', $url);
        $cleanUrl = preg_replace('/^www\./', '', $cleanUrl);
        return rtrim($cleanUrl, '/');
    }

        public function appearance()
    {
        $user = Auth::user();
        
        // Récupérer les paramètres d'apparence depuis le modèle User
        $settings = [
            'theme' => $user->theme ?? 'light',
            'primary_color' => $user->primary_color ?? '#0A2E2E',
            'secondary_color' => $user->secondary_color ?? '#2A6363',
            'accent_color' => $user->accent_color ?? '#8E6E53',
            'logo' => $user->logo
        ];

        return view('admin.settings.appearance', compact('settings'));
    }

    public function updateAppearance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'theme' => 'required|in:light,dark,auto',
            'primary_color' => 'required|regex:/^#[a-fA-F0-9]{6}$/',
            'secondary_color' => 'required|regex:/^#[a-fA-F0-9]{6}$/',
            'accent_color' => 'required|regex:/^#[a-fA-F0-9]{6}$/',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = Auth::user();
            $logoPath = $user->logo;

            // Gérer l'upload du logo
            if ($request->hasFile('logo')) {
                Log::info('Logo upload détecté pour l\'utilisateur: ' . $user->id);
                
                // Supprimer l'ancien logo s'il existe
                if ($user->logo && Storage::disk('public')->exists($user->logo)) {
                    Storage::disk('public')->delete($user->logo);
                    Log::info('Ancien logo supprimé: ' . $user->logo);
                }

                // Créer le dossier logos s'il n'existe pas
                if (!Storage::disk('public')->exists('logos')) {
                    Storage::disk('public')->makeDirectory('logos');
                }

                // Générer un nom unique pour le fichier
                $file = $request->file('logo');
                $fileName = 'user_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Sauvegarder le nouveau logo
                $logoPath = $file->storeAs('logos', $fileName, 'public');
                
                if (!$logoPath) {
                    Log::error('Erreur lors de la sauvegarde du logo');
                    return redirect()->back()
                        ->with('error', 'Erreur lors de la sauvegarde du logo.')
                        ->withInput();
                }
                
                Log::info('Nouveau logo sauvegardé: ' . $logoPath);
            }

            // Mettre à jour SEULEMENT la table users (plus simple et évite les conflits)
            DB::transaction(function () use ($user, $request, $logoPath) {
                User::where('id', $user->id)->update([
                    'theme' => $request->theme,
                    'primary_color' => $request->primary_color,
                    'secondary_color' => $request->secondary_color,
                    'accent_color' => $request->accent_color,
                    'logo' => $logoPath,
                    'settings_updated_at' => now()
                ]);
            });

            // Générer le fichier CSS personnalisé
            try {
                $this->cssGenerator->generateUserCss($user->id);
                Log::info('CSS généré avec succès pour l\'utilisateur: ' . $user->id);
            } catch (\Exception $e) {
                Log::warning('Erreur lors de la génération du CSS: ' . $e->getMessage());
            }
            
            // Invalider le cache des couleurs
            $this->themeService->clearUserColorsCache($user->id);

            return redirect()->route('admin.settings.appearance')
                ->with('success', 'Les paramètres d\'apparence ont été mis à jour avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des paramètres d\'apparence: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour des paramètres: ' . $e->getMessage())
                ->withInput();
        }
    }


}