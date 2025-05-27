<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function appearance()
    {
        $settings = $this->getSettings([
            'theme',
            'primary_color',
            'secondary_color',
            'accent_color',
            'logo'
        ]);

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
            // Sauvegarder les couleurs et le thème
            $this->updateSetting('theme', $request->theme);
            $this->updateSetting('primary_color', $request->primary_color);
            $this->updateSetting('secondary_color', $request->secondary_color);
            $this->updateSetting('accent_color', $request->accent_color);

            // Gérer l'upload du logo
            if ($request->hasFile('logo')) {
                // Supprimer l'ancien logo s'il existe
                $oldLogo = $this->getSetting('logo');
                if ($oldLogo && Storage::exists('public/' . $oldLogo)) {
                    Storage::delete('public/' . $oldLogo);
                }

                // Sauvegarder le nouveau logo
                $logoPath = $request->file('logo')->store('logos', 'public');
                $this->updateSetting('logo', $logoPath);
            }

            return redirect()->route('admin.settings.appearance')
                ->with('success', 'Les paramètres d\'apparence ont été mis à jour avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour des paramètres.')
                ->withInput();
        }
    }

    public function notifications()
    {
        $settings = $this->getSettings([
            'email_notifications',
            'push_notifications',
            'sms_notifications',
            'notification_frequency'
        ]);

        return view('admin.settings.notifications', compact('settings'));
    }

    public function updateNotifications(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'notification_frequency' => 'required|in:immediate,daily,weekly',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->updateSetting('email_notifications', $request->boolean('email_notifications'));
            $this->updateSetting('push_notifications', $request->boolean('push_notifications'));
            $this->updateSetting('sms_notifications', $request->boolean('sms_notifications'));
            $this->updateSetting('notification_frequency', $request->notification_frequency);

            return redirect()->route('admin.settings.notifications')
                ->with('success', 'Les paramètres de notifications ont été mis à jour avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour des paramètres.')
                ->withInput();
        }
    }

    public function security()
    {
        $settings = $this->getSettings([
            'two_factor_auth',
            'session_timeout',
            'password_expiry',
            'login_attempts'
        ]);

        return view('admin.settings.security', compact('settings'));
    }

    public function updateSecurity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'two_factor_auth' => 'boolean',
            'session_timeout' => 'required|integer|min:5|max:1440',
            'password_expiry' => 'required|integer|min:30|max:365',
            'login_attempts' => 'required|integer|min:3|max:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->updateSetting('two_factor_auth', $request->boolean('two_factor_auth'));
            $this->updateSetting('session_timeout', $request->session_timeout);
            $this->updateSetting('password_expiry', $request->password_expiry);
            $this->updateSetting('login_attempts', $request->login_attempts);

            return redirect()->route('admin.settings.security')
                ->with('success', 'Les paramètres de sécurité ont été mis à jour avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour des paramètres.')
                ->withInput();
        }
    }

    public function system()
    {
        $settings = $this->getSettings([
            'app_name',
            'app_timezone',
            'app_locale',
            'maintenance_mode',
            'debug_mode'
        ]);

        return view('admin.settings.system', compact('settings'));
    }

    public function updateSystem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_name' => 'required|string|max:255',
            'app_timezone' => 'required|string',
            'app_locale' => 'required|string|in:fr,en,es,de',
            'maintenance_mode' => 'boolean',
            'debug_mode' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $this->updateSetting('app_name', $request->app_name);
            $this->updateSetting('app_timezone', $request->app_timezone);
            $this->updateSetting('app_locale', $request->app_locale);
            $this->updateSetting('maintenance_mode', $request->boolean('maintenance_mode'));
            $this->updateSetting('debug_mode', $request->boolean('debug_mode'));

            return redirect()->route('admin.settings.system')
                ->with('success', 'Les paramètres système ont été mis à jour avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour des paramètres.')
                ->withInput();
        }
    }

    private function getSettings(array $keys)
    {
        $settings = [];
        foreach ($keys as $key) {
            $settings[$key] = $this->getSetting($key);
        }
        return $settings;
    }

    private function getSetting($key, $default = null)
    {
        $setting = Setting::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    private function updateSetting($key, $value)
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
