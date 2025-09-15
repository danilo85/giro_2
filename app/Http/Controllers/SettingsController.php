<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Models\Setting;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $settings = $this->getSettings();
        
        return view('settings', compact('settings'));
    }

    /**
     * Update the specified settings.
     */
    public function update(Request $request)
    {
        $section = $request->input('section');
        
        switch ($section) {
            case 'general':
                return $this->updateGeneralSettings($request);
            case 'appearance':
                return $this->updateAppearanceSettings($request);
            case 'security':
                return $this->updateSecuritySettings($request);
            case 'notifications':
                return $this->updateNotificationSettings($request);
            case 'system':
                return $this->updateSystemSettings($request);
            default:
                return redirect()->route('settings.index')->with('error', 'Seção de configuração inválida.');
        }
    }

    /**
     * Update general settings.
     */
    private function updateGeneralSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'default_language' => 'required|in:pt-BR,en-US,es-ES',
            'timezone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('settings.index')
                ->withErrors($validator)
                ->withInput();
        }

        $settings = [
            'site_name' => $request->site_name,
            'site_description' => $request->site_description,
            'default_language' => $request->default_language,
            'timezone' => $request->timezone,
        ];

        $this->saveSettings('general', $settings);

        return redirect()->route('settings.index')->with('success', 'Configurações gerais atualizadas com sucesso!');
    }

    /**
     * Update appearance settings.
     */
    private function updateAppearanceSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'default_theme' => 'required|in:light,dark,auto',
            'primary_color' => 'required|in:blue,green,purple,red',
            'sidebar_auto_collapse' => 'nullable|boolean',
            'sidebar_remember_state' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('settings.index')
                ->withErrors($validator)
                ->withInput();
        }

        $settings = [
            'default_theme' => $request->default_theme,
            'primary_color' => $request->primary_color,
            'sidebar_auto_collapse' => $request->boolean('sidebar_auto_collapse'),
            'sidebar_remember_state' => $request->boolean('sidebar_remember_state'),
        ];

        $this->saveSettings('appearance', $settings);

        return redirect()->route('settings.index')->with('success', 'Configurações de aparência atualizadas com sucesso!');
    }

    /**
     * Update security settings.
     */
    private function updateSecuritySettings(Request $request)
    {
        // Only admins can update security settings
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('settings.index')->with('error', 'Acesso negado.');
        }

        $validator = Validator::make($request->all(), [
            'session_lifetime' => 'required|integer|min:30|max:1440',
            'max_login_attempts' => 'required|integer|min:3|max:10',
            'password_min_length' => 'nullable|boolean',
            'password_uppercase' => 'nullable|boolean',
            'password_numbers' => 'nullable|boolean',
            'password_special_chars' => 'nullable|boolean',
            'enable_2fa' => 'nullable|boolean',
            'force_2fa_admin' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('settings.index')
                ->withErrors($validator)
                ->withInput();
        }

        $settings = [
            'session_lifetime' => $request->session_lifetime,
            'max_login_attempts' => $request->max_login_attempts,
            'password_min_length' => $request->boolean('password_min_length'),
            'password_uppercase' => $request->boolean('password_uppercase'),
            'password_numbers' => $request->boolean('password_numbers'),
            'password_special_chars' => $request->boolean('password_special_chars'),
            'enable_2fa' => $request->boolean('enable_2fa'),
            'force_2fa_admin' => $request->boolean('force_2fa_admin'),
        ];

        $this->saveSettings('security', $settings);

        return redirect()->route('settings.index')->with('success', 'Configurações de segurança atualizadas com sucesso!');
    }

    /**
     * Update notification settings.
     */
    private function updateNotificationSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notification_frequency' => 'required|in:immediate,hourly,daily,weekly',
            'email_new_user' => 'nullable|boolean',
            'email_user_login' => 'nullable|boolean',
            'email_password_reset' => 'nullable|boolean',
            'email_account_changes' => 'nullable|boolean',
            'system_maintenance' => 'nullable|boolean',
            'system_updates' => 'nullable|boolean',
            'system_security' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('settings.index')
                ->withErrors($validator)
                ->withInput();
        }

        $settings = [
            'notification_frequency' => $request->notification_frequency,
            'email_new_user' => $request->boolean('email_new_user'),
            'email_user_login' => $request->boolean('email_user_login'),
            'email_password_reset' => $request->boolean('email_password_reset'),
            'email_account_changes' => $request->boolean('email_account_changes'),
            'system_maintenance' => $request->boolean('system_maintenance'),
            'system_updates' => $request->boolean('system_updates'),
            'system_security' => $request->boolean('system_security'),
        ];

        $this->saveSettings('notifications', $settings);

        return redirect()->route('settings.index')->with('success', 'Configurações de notificações atualizadas com sucesso!');
    }

    /**
     * Update system settings.
     */
    private function updateSystemSettings(Request $request)
    {
        // Only admins can update system settings
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('settings.index')->with('error', 'Acesso negado.');
        }

        $validator = Validator::make($request->all(), [
            'log_retention' => 'required|integer|min:7|max:365',
            'backup_frequency' => 'required|in:daily,weekly,monthly',
            'maintenance_mode' => 'nullable|boolean',
            'allow_registration' => 'nullable|boolean',
            'email_verification' => 'nullable|boolean',
            'admin_approval' => 'nullable|boolean',
            'auto_backup' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('settings.index')
                ->withErrors($validator)
                ->withInput();
        }

        $settings = [
            'log_retention' => $request->log_retention,
            'backup_frequency' => $request->backup_frequency,
            'maintenance_mode' => $request->boolean('maintenance_mode'),
            'allow_registration' => $request->boolean('allow_registration'),
            'email_verification' => $request->boolean('email_verification'),
            'admin_approval' => $request->boolean('admin_approval'),
            'auto_backup' => $request->boolean('auto_backup'),
        ];

        $this->saveSettings('system', $settings);

        return redirect()->route('settings.index')->with('success', 'Configurações do sistema atualizadas com sucesso!');
    }

    /**
     * Get all settings from cache or defaults.
     */
    private function getSettings()
    {
        return Cache::remember('app_settings', 3600, function () {
            return [
                'general' => [
                    'site_name' => 'Giro',
                    'site_description' => 'Sistema de gestão de usuários moderno e eficiente',
                    'default_language' => 'pt-BR',
                    'timezone' => 'America/Sao_Paulo',
                ],
                'appearance' => [
                    'default_theme' => 'light',
                    'primary_color' => 'blue',
                    'sidebar_auto_collapse' => false,
                    'sidebar_remember_state' => true,
                ],
                'security' => [
                    'session_lifetime' => 120,
                    'max_login_attempts' => 5,
                    'password_min_length' => true,
                    'password_uppercase' => true,
                    'password_numbers' => true,
                    'password_special_chars' => false,
                    'enable_2fa' => false,
                    'force_2fa_admin' => false,
                ],
                'notifications' => [
                    'notification_frequency' => 'immediate',
                    'email_new_user' => true,
                    'email_user_login' => false,
                    'email_password_reset' => true,
                    'email_account_changes' => true,
                    'system_maintenance' => true,
                    'system_updates' => true,
                    'system_security' => true,
                ],
                'system' => [
                    'log_retention' => 30,
                    'backup_frequency' => 'daily',
                    'maintenance_mode' => false,
                    'allow_registration' => true,
                    'email_verification' => true,
                    'admin_approval' => false,
                    'auto_backup' => true,
                ],
            ];
        });
    }

    /**
     * Save settings to cache.
     */
    private function saveSettings($section, $settings)
    {
        $allSettings = $this->getSettings();
        $allSettings[$section] = array_merge($allSettings[$section], $settings);
        
        Cache::put('app_settings', $allSettings, 3600);
    }

    /**
     * Get a specific setting value.
     */
    public static function get($section, $key, $default = null)
    {
        $settings = Cache::get('app_settings');
        
        if (!$settings || !isset($settings[$section][$key])) {
            return $default;
        }
        
        return $settings[$section][$key];
    }

    /**
     * Toggle public registration setting.
     */
    public function toggleRegistration(Request $request)
    {
        // Only admins can toggle registration
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso negado.'
            ], 403);
        }

        try {
            $enabled = $request->boolean('enabled');
            
            // Update or create the setting
            Setting::updateOrCreate(
                ['key' => 'public_registration_enabled'],
                ['value' => $enabled ? '1' : '0']
            );

            return response()->json([
                'success' => true,
                'message' => $enabled ? 'Registro público habilitado com sucesso!' : 'Registro público desabilitado com sucesso!',
                'enabled' => $enabled
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar configuração: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear settings cache.
     */
    public function clearCache()
    {
        Cache::forget('app_settings');
        
        return redirect()->route('settings.index')->with('success', 'Cache de configurações limpo com sucesso!');
    }
}