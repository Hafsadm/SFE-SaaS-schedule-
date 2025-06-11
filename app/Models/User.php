<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'company_name',
        'website_name',
        'website_url',
        'logo',
        'description',
        'primary_color',
        'secondary_color',
        'accent_color',
        'theme',
        'admin_slug',
        'timezone',
        'locale',
        'is_active',
        'settings_updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
            'is_active' => 'boolean',
            'settings_updated_at' => 'datetime',
        ];
    }

    /**
     * Vérifications de rôle
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Relations
     */
    public function stores()
    {
        return $this->hasMany(Store::class);
    }

    public function settings()
    {
        return $this->hasMany(Setting::class);
    }

    /**
     * Accesseurs pour les couleurs avec valeurs par défaut
     */
    public function getPrimaryColorAttribute($value)
    {
        return $value ?: '#0A2E2E';
    }

    public function getSecondaryColorAttribute($value)
    {
        return $value ?: '#2A6363';
    }

    public function getAccentColorAttribute($value)
    {
        return $value ?: '#8E6E53';
    }

    public function getThemeAttribute($value)
    {
        return $value ?: 'light';
    }

    public function getTimezoneAttribute($value)
    {
        return $value ?: 'Europe/Paris';
    }

    public function getLocaleAttribute($value)
    {
        return $value ?: 'fr';
    }

    /**
     * Accesseur pour le logo avec URL complète
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo && Storage::disk('public')->exists($this->logo)) {
            return asset('storage/' . $this->logo);
        }
        return null;
    }

    /**
     * Accesseur pour le slug admin avec génération automatique
     */
    public function getAdminSlugAttribute($value)
    {
        if (!$value && $this->isAdmin()) {
            return Str::slug($this->name . '-' . $this->id);
        }
        return $value;
    }

    /**
     * Mutateur pour website_url (nettoie l'URL)
     */
    public function setWebsiteUrlAttribute($value)
    {
        if ($value) {
            // Enlever le protocole et www si présents
            $cleanUrl = preg_replace('/^https?:\/\//', '', $value);
            $cleanUrl = preg_replace('/^www\./', '', $cleanUrl);
            // Enlever le trailing slash
            $cleanUrl = rtrim($cleanUrl, '/');
            $this->attributes['website_url'] = $cleanUrl;
        } else {
            $this->attributes['website_url'] = null;
        }
    }

    /**
     * Mutateur pour admin_slug (génération automatique)
     */
    public function setAdminSlugAttribute($value)
    {
        if (!$value && $this->isAdmin()) {
            $this->attributes['admin_slug'] = Str::slug($this->name . '-' . time());
        } else {
            $this->attributes['admin_slug'] = $value ? Str::slug($value) : null;
        }
    }

    /**
     * Scope pour les admins actifs
     */
    public function scopeActiveAdmins($query)
    {
        return $query->where('role', 'admin')
                    ->where('is_active', true);
    }

    /**
     * Scope pour recherche par domaine
     */
    public function scopeByDomain($query, $domain)
    {
        return $query->where('website_url', $domain);
    }

    /**
     * ========================================
     * MÉTHODES DE MISE À JOUR DES PARAMÈTRES
     * ========================================
     */

    /**
     * Mettre à jour les paramètres d'apparence
     */
    public function updateAppearanceSettings($data, $logoFile = null)
    {
        try {
            $logoPath = $this->logo;

            // Gérer l'upload du logo
            if ($logoFile) {
                Log::info('Logo upload détecté pour l\'utilisateur: ' . $this->id);
                
                // Supprimer l'ancien logo s'il existe
                if ($this->logo && Storage::disk('public')->exists($this->logo)) {
                    Storage::disk('public')->delete($this->logo);
                    Log::info('Ancien logo supprimé: ' . $this->logo);
                }

                // Créer le dossier logos s'il n'existe pas
                if (!Storage::disk('public')->exists('logos')) {
                    Storage::disk('public')->makeDirectory('logos');
                }

                // Générer un nom unique pour le fichier
                $fileName = 'user_' . $this->id . '_' . time() . '.' . $logoFile->getClientOriginalExtension();
                
                // Sauvegarder le nouveau logo
                $logoPath = $logoFile->storeAs('logos', $fileName, 'public');
                
                if (!$logoPath) {
                    Log::error('Erreur lors de la sauvegarde du logo');
                    throw new \Exception('Erreur lors de la sauvegarde du logo.');
                }
                
                Log::info('Nouveau logo sauvegardé: ' . $logoPath);
            }

            // Mettre à jour les paramètres
            $this->update([
                'theme' => $data['theme'] ?? $this->theme,
                'primary_color' => $data['primary_color'] ?? $this->primary_color,
                'secondary_color' => $data['secondary_color'] ?? $this->secondary_color,
                'accent_color' => $data['accent_color'] ?? $this->accent_color,
                'logo' => $logoPath,
                'settings_updated_at' => now(),
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des paramètres d\'apparence: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Mettre à jour les paramètres système
     */
    public function updateSystemSettings($data)
    {
        try {
            $this->update([
                'website_name' => $data['app_name'] ?? $this->website_name,
                'description' => $data['app_description'] ?? $this->description,
                'company_name' => $data['company_name'] ?? $this->company_name,
                'website_url' => $data['website_url'] ?? $this->website_url,
                'timezone' => $data['timezone'] ?? $this->timezone,
                'locale' => $data['language'] ?? $this->locale,
                'settings_updated_at' => now(),
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des paramètres système: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Mettre à jour les paramètres de domaine
     */
    public function updateDomainSettings($data)
    {
        try {
            // Nettoyer l'URL du site web
            $websiteUrl = $data['website_url'];
            $websiteUrl = preg_replace('/^https?:\/\//', '', $websiteUrl);
            $websiteUrl = preg_replace('/^www\./', '', $websiteUrl);
            $websiteUrl = rtrim($websiteUrl, '/');
            
            $this->update([
                'website_url' => $websiteUrl,
                'admin_slug' => $data['admin_slug'],
                'company_name' => $data['company_name'],
                'website_name' => $data['website_name'],
                'settings_updated_at' => now(),
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des paramètres de domaine: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Mettre à jour un paramètre spécifique dans la table settings
     */
    public function updateUserSetting($key, $value)
    {
        return \App\Models\Setting::setUserSetting($this->id, $key, $value);
    }

    /**
     * Récupérer un paramètre spécifique depuis la table settings
     */
    public function getUserSetting($key, $default = null)
    {
        $setting = \App\Models\Setting::where('user_id', $this->id)->where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Mettre à jour les paramètres de notification
     */
    public function updateNotificationSettings($data)
    {
        try {
            $this->updateUserSetting('email_notifications', $data['email_notifications'] ?? false);
            $this->updateUserSetting('push_notifications', $data['push_notifications'] ?? false);
            $this->updateUserSetting('sms_notifications', $data['sms_notifications'] ?? false);
            $this->updateUserSetting('notification_frequency', $data['notification_frequency'] ?? 'daily');
            $this->updateUserSetting('notify_new_store', $data['notify_new_store'] ?? false);
            $this->updateUserSetting('notify_schedule_change', $data['notify_schedule_change'] ?? false);
            $this->updateUserSetting('notify_exception', $data['notify_exception'] ?? false);
            $this->updateUserSetting('notify_holiday', $data['notify_holiday'] ?? false);
            $this->updateUserSetting('email_template', $data['email_template'] ?? 'default');

            return true;

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des paramètres de notification: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Mettre à jour les paramètres de sécurité
     */
    public function updateSecuritySettings($data)
    {
        try {
            $this->updateUserSetting('two_factor_auth', $data['two_factor_auth'] ?? false);
            $this->updateUserSetting('login_attempts', $data['login_attempts'] ?? 5);
            $this->updateUserSetting('password_expiry_days', $data['password_expiry_days'] ?? 90);
            $this->updateUserSetting('session_timeout', $data['session_timeout'] ?? 30);
            $this->updateUserSetting('ip_restriction', $data['ip_restriction'] ?? false);
            $this->updateUserSetting('allowed_ips', $data['allowed_ips'] ?? '');
            $this->updateUserSetting('force_password_change', $data['force_password_change'] ?? false);

            return true;

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des paramètres de sécurité: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Mettre à jour les paramètres système avancés (stockés dans la table settings)
     */
    public function updateAdvancedSystemSettings($data)
    {
        try {
            $this->updateUserSetting('app_version', $data['app_version'] ?? '1.0.0');
            $this->updateUserSetting('date_format', $data['date_format'] ?? 'd/m/Y');
            $this->updateUserSetting('time_format', $data['time_format'] ?? 'H:i');
            $this->updateUserSetting('cache_duration', $data['cache_duration'] ?? '60');
            $this->updateUserSetting('pagination_limit', $data['pagination_limit'] ?? '15');
            $this->updateUserSetting('enable_cache', $data['enable_cache'] ?? true);
            $this->updateUserSetting('session_lifetime', $data['session_lifetime'] ?? '120');
            $this->updateUserSetting('max_login_attempts', $data['max_login_attempts'] ?? '5');
            $this->updateUserSetting('force_https', $data['force_https'] ?? false);
            $this->updateUserSetting('enable_2fa', $data['enable_2fa'] ?? false);
            $this->updateUserSetting('maintenance_mode', $data['maintenance_mode'] ?? false);
            $this->updateUserSetting('maintenance_message', $data['maintenance_message'] ?? 'L\'application est temporairement indisponible pour maintenance. Veuillez réessayer plus tard.');
            $this->updateUserSetting('log_level', $data['log_level'] ?? 'info');
            $this->updateUserSetting('log_retention', $data['log_retention'] ?? '30');
            $this->updateUserSetting('enable_monitoring', $data['enable_monitoring'] ?? true);

            return true;

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des paramètres système avancés: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Vérifier si l'utilisateur a configuré ses paramètres
     */
    public function hasConfiguredSettings()
    {
        return !is_null($this->settings_updated_at);
    }

    /**
     * Obtenir les couleurs CSS pour cet utilisateur
     */
    public function getCssColors()
    {
        return [
            '--primary' => $this->primary_color,
            '--secondary' => $this->secondary_color,
            '--accent' => $this->accent_color,
        ];
    }

    /**
     * Générer le CSS personnalisé pour cet utilisateur
     */
    public function generateCustomCss()
    {
        $colors = $this->getCssColors();
        $css = ":root {\n";
        foreach ($colors as $property => $value) {
            $css .= "    {$property}: {$value};\n";
        }
        $css .= "}\n";
        
        return $css;
    }

    /**
     * Obtenir tous les paramètres d'apparence
     */
    public function getAppearanceSettings()
    {
        return [
            'theme' => $this->theme,
            'primary_color' => $this->primary_color,
            'secondary_color' => $this->secondary_color,
            'accent_color' => $this->accent_color,
            'logo' => $this->logo,
            'logo_url' => $this->logo_url,
        ];
    }

    /**
     * Obtenir tous les paramètres système
     */
    public function getSystemSettings()
    {
        return [
            'app_name' => $this->website_name,
            'app_description' => $this->description,
            'company_name' => $this->company_name,
            'website_url' => $this->website_url,
            'timezone' => $this->timezone,
            'language' => $this->locale,
            'admin_slug' => $this->admin_slug,
        ];
    }

    /**
     * Obtenir tous les paramètres de notification
     */
    public function getNotificationSettings()
    {
        return [
            'email_notifications' => $this->getUserSetting('email_notifications', true),
            'push_notifications' => $this->getUserSetting('push_notifications', false),
            'sms_notifications' => $this->getUserSetting('sms_notifications', false),
            'notification_frequency' => $this->getUserSetting('notification_frequency', 'daily'),
            'notify_new_store' => $this->getUserSetting('notify_new_store', true),
            'notify_schedule_change' => $this->getUserSetting('notify_schedule_change', true),
            'notify_exception' => $this->getUserSetting('notify_exception', true),
            'notify_holiday' => $this->getUserSetting('notify_holiday', true),
            'email_template' => $this->getUserSetting('email_template', 'default'),
        ];
    }

    /**
     * Obtenir tous les paramètres de sécurité
     */
    public function getSecuritySettings()
    {
        return [
            'two_factor_auth' => $this->getUserSetting('two_factor_auth', false),
            'login_attempts' => $this->getUserSetting('login_attempts', 5),
            'password_expiry_days' => $this->getUserSetting('password_expiry_days', 90),
            'session_timeout' => $this->getUserSetting('session_timeout', 30),
            'ip_restriction' => $this->getUserSetting('ip_restriction', false),
            'allowed_ips' => $this->getUserSetting('allowed_ips', ''),
            'force_password_change' => $this->getUserSetting('force_password_change', false),
        ];
    }

    /**
     * Mettre à jour les paramètres d'apparence - Méthode spécifique pour éviter les erreurs
     */
    public function updateAppearanceData($theme, $primaryColor, $secondaryColor, $accentColor, $logoPath = null)
    {
        try {
            // Préparer les données à mettre à jour
            $updateData = [
                'theme' => $theme,
                'primary_color' => $primaryColor,
                'secondary_color' => $secondaryColor,
                'accent_color' => $accentColor,
                'settings_updated_at' => now()
            ];

            // Ajouter le logo seulement s'il est fourni
            if ($logoPath !== null) {
                $updateData['logo'] = $logoPath;
            }

            // Effectuer la mise à jour
            return $this->update($updateData);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des paramètres d\'apparence: ' . $e->getMessage());
            throw new \Exception('Impossible de mettre à jour les paramètres d\'apparence: ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour les paramètres d'apparence depuis un array
     */
    public function updateAppearanceFromRequest($requestData, $logoPath = null)
    {
        try {
            return $this->updateAppearanceData(
                $requestData['theme'],
                $requestData['primary_color'],
                $requestData['secondary_color'],
                $requestData['accent_color'],
                $logoPath
            );
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour depuis la requête: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Boot method pour les événements du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Générer automatiquement un slug admin lors de la création
        static::creating(function ($user) {
            if ($user->isAdmin() && !$user->admin_slug) {
                $user->admin_slug = Str::slug($user->name . '-' . time());
            }
        });

        // Mettre à jour settings_updated_at lors de modifications
        static::updating(function ($user) {
            if ($user->isDirty(['primary_color', 'secondary_color', 'accent_color', 'theme', 'timezone', 'locale'])) {
                $user->settings_updated_at = now();
            }
        });
    }

    /**
     * Mettre à jour les paramètres d'apparence avec la méthode update standard
     */
    public function updateAppearance($theme, $primaryColor, $secondaryColor, $accentColor, $logoPath = null)
    {
        return $this->update([
            'theme' => $theme,
            'primary_color' => $primaryColor,
            'secondary_color' => $secondaryColor,
            'accent_color' => $accentColor,
            'logo' => $logoPath ?? $this->logo,
            'settings_updated_at' => now()
        ]);
    }

    /**
     * Mettre à jour les paramètres d'apparence depuis un array de données
     */
    public function updateAppearanceFromArray(array $data, $logoPath = null)
    {
        return $this->update([
            'theme' => $data['theme'] ?? $this->theme,
            'primary_color' => $data['primary_color'] ?? $this->primary_color,
            'secondary_color' => $data['secondary_color'] ?? $this->secondary_color,
            'accent_color' => $data['accent_color'] ?? $this->accent_color,
            'logo' => $logoPath ?? $this->logo,
            'settings_updated_at' => now()
        ]);
    }
}
