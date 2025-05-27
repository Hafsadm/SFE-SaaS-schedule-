<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultSettings = [
            // Apparence
            ['key' => 'theme', 'value' => 'light', 'type' => 'string'],
            ['key' => 'primary_color', 'value' => '#0A2E2E', 'type' => 'string'],
            ['key' => 'secondary_color', 'value' => '#2A6363', 'type' => 'string'],
            ['key' => 'accent_color', 'value' => '#8E6E53', 'type' => 'string'],
            
            // Notifications
            ['key' => 'email_notifications', 'value' => 'true', 'type' => 'boolean'],
            ['key' => 'push_notifications', 'value' => 'true', 'type' => 'boolean'],
            ['key' => 'sms_notifications', 'value' => 'false', 'type' => 'boolean'],
            ['key' => 'notification_frequency', 'value' => 'immediate', 'type' => 'string'],
            
            // Sécurité
            ['key' => 'two_factor_auth', 'value' => 'false', 'type' => 'boolean'],
            ['key' => 'session_timeout', 'value' => '120', 'type' => 'integer'],
            ['key' => 'password_expiry', 'value' => '90', 'type' => 'integer'],
            ['key' => 'login_attempts', 'value' => '5', 'type' => 'integer'],
            
            // Système
            ['key' => 'app_name', 'value' => 'Horaire', 'type' => 'string'],
            ['key' => 'app_timezone', 'value' => 'Europe/Paris', 'type' => 'string'],
            ['key' => 'app_locale', 'value' => 'fr', 'type' => 'string'],
            ['key' => 'maintenance_mode', 'value' => 'false', 'type' => 'boolean'],
            ['key' => 'debug_mode', 'value' => 'false', 'type' => 'boolean'],
        ];

        foreach ($defaultSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
