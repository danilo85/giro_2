@extends('layouts.app')

@section('title', 'Configurações - Giro')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Tags de Navegação Rápida -->
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-200 dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Dashboard
        </a>
        <a href="{{ route('users.index') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-200 dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
            Gestão de Usuários
        </a>
        <a href="{{ route('profile.show') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-200 dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Perfil
        </a>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Configurações
        </span>
    </div>
</div>

<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Configurações do Sistema</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Gerencie as configurações gerais do sistema e preferências</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Settings Navigation -->
        <div class="lg:col-span-1">
            <nav class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                <ul class="space-y-2">
                    <li>
                        <button onclick="showSection('general')" 
                                class="settings-nav-btn w-full text-left px-3 py-2 text-sm font-medium rounded-md transition-colors bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200" 
                                data-section="general">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Geral
                        </button>
                    </li>
                    <li>
                        <button onclick="showSection('appearance')" 
                                class="settings-nav-btn w-full text-left px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors" 
                                data-section="appearance">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                            </svg>
                            Aparência
                        </button>
                    </li>
                    <li>
                        <button onclick="showSection('security')" 
                                class="settings-nav-btn w-full text-left px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors" 
                                data-section="security">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Segurança
                        </button>
                    </li>
                    <li>
                        <button onclick="showSection('notifications')" 
                                class="settings-nav-btn w-full text-left px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors" 
                                data-section="notifications">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM4 15h8v-2H4v2zM4 11h8V9H4v2z"></path>
                            </svg>
                            Notificações
                        </button>
                    </li>
                    @if(auth()->user()->isAdmin())
                        <li>
                            <button onclick="showSection('system')" 
                                    class="settings-nav-btn w-full text-left px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors" 
                                    data-section="system">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                                </svg>
                                Sistema
                            </button>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
        
        <!-- Settings Content -->
        <div class="lg:col-span-3">
            <!-- General Settings -->
            <div id="general-section" class="settings-section">
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Configurações Gerais</h2>
                    
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="section" value="general">
                        
                        <div class="space-y-6">
                            <!-- Site Name -->
                            <div>
                                <label for="site_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nome do Site
                                </label>
                                <input type="text" id="site_name" name="site_name" 
                                       value="{{ old('site_name', $settings['general']['site_name'] ?? 'Giro') }}"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <!-- Site Description -->
                            <div>
                                <label for="site_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Descrição do Site
                                </label>
                                <textarea id="site_description" name="site_description" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">{{ old('site_description', $settings['general']['site_description'] ?? 'Sistema de gestão de usuários moderno e eficiente') }}</textarea>
                            </div>
                            
                            <!-- Default Language -->
                            <div>
                                <label for="default_language" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Idioma Padrão
                                </label>
                                <select id="default_language" name="default_language" 
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="pt-BR" {{ old('default_language', $settings['general']['default_language'] ?? 'pt-BR') == 'pt-BR' ? 'selected' : '' }}>Português (Brasil)</option>
                                    <option value="en-US" {{ old('default_language', $settings['general']['default_language'] ?? 'pt-BR') == 'en-US' ? 'selected' : '' }}>English (US)</option>
                                    <option value="es-ES" {{ old('default_language', $settings['general']['default_language'] ?? 'pt-BR') == 'es-ES' ? 'selected' : '' }}>Español</option>
                                </select>
                            </div>
                            
                            <!-- Timezone -->
                            <div>
                                <label for="timezone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Fuso Horário
                                </label>
                                <select id="timezone" name="timezone" 
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="America/Sao_Paulo" {{ old('timezone', $settings['general']['timezone'] ?? 'America/Sao_Paulo') == 'America/Sao_Paulo' ? 'selected' : '' }}>América/São Paulo (UTC-3)</option>
                                    <option value="America/New_York" {{ old('timezone', $settings['general']['timezone'] ?? 'America/Sao_Paulo') == 'America/New_York' ? 'selected' : '' }}>América/Nova York (UTC-5)</option>
                                    <option value="Europe/London" {{ old('timezone', $settings['general']['timezone'] ?? 'America/Sao_Paulo') == 'Europe/London' ? 'selected' : '' }}>Europa/Londres (UTC+0)</option>
                                    <option value="Asia/Tokyo" {{ old('timezone', $settings['general']['timezone'] ?? 'America/Sao_Paulo') == 'Asia/Tokyo' ? 'selected' : '' }}>Ásia/Tóquio (UTC+9)</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row justify-end mt-6 gap-3">
                            <button type="submit" 
                                    class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Salvar Configurações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Appearance Settings -->
            <div id="appearance-section" class="settings-section hidden">
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Configurações de Aparência</h2>
                    
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="section" value="appearance">
                        
                        <div class="space-y-6">
                            <!-- Theme Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                                    Tema do Sistema
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="relative">
                                        <input type="radio" id="theme-light" name="default_theme" value="light" class="sr-only" {{ old('default_theme', $settings['appearance']['default_theme'] ?? 'light') == 'light' ? 'checked' : '' }}>
                                        <label for="theme-light" class="flex flex-col items-center p-4 border-2 border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer hover:border-blue-500 transition-colors">
                                            <div class="w-16 h-12 bg-white border border-gray-300 rounded mb-2 flex items-center justify-center">
                                                <div class="w-8 h-2 bg-gray-300 rounded"></div>
                                            </div>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Claro</span>
                                        </label>
                                    </div>
                                    
                                    <div class="relative">
                                        <input type="radio" id="theme-dark" name="default_theme" value="dark" class="sr-only" {{ old('default_theme', $settings['appearance']['default_theme'] ?? 'light') == 'dark' ? 'checked' : '' }}>
                                        <label for="theme-dark" class="flex flex-col items-center p-4 border-2 border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer hover:border-blue-500 transition-colors">
                                            <div class="w-16 h-12 bg-gray-800 border border-gray-600 rounded mb-2 flex items-center justify-center">
                                                <div class="w-8 h-2 bg-gray-600 rounded"></div>
                                            </div>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Escuro</span>
                                        </label>
                                    </div>
                                    
                                    <div class="relative">
                                        <input type="radio" id="theme-auto" name="default_theme" value="auto" class="sr-only" {{ old('default_theme', $settings['appearance']['default_theme'] ?? 'light') == 'auto' ? 'checked' : '' }}>
                                        <label for="theme-auto" class="flex flex-col items-center p-4 border-2 border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer hover:border-blue-500 transition-colors">
                                            <div class="w-16 h-12 rounded mb-2 flex">
                                                <div class="w-8 h-12 bg-white border-l border-t border-b border-gray-300 rounded-l flex items-center justify-center">
                                                    <div class="w-4 h-2 bg-gray-300 rounded"></div>
                                                </div>
                                                <div class="w-8 h-12 bg-gray-800 border-r border-t border-b border-gray-600 rounded-r flex items-center justify-center">
                                                    <div class="w-4 h-2 bg-gray-600 rounded"></div>
                                                </div>
                                            </div>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Automático</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sidebar Settings -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                                    Configurações da Sidebar
                                </label>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="sidebar_auto_collapse" value="1" {{ old('sidebar_auto_collapse', $settings['appearance']['sidebar_auto_collapse'] ?? false) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Colapsar automaticamente em telas pequenas</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="sidebar_remember_state" value="1" {{ old('sidebar_remember_state', $settings['appearance']['sidebar_remember_state'] ?? true) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Lembrar estado da sidebar entre sessões</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Color Scheme -->
                            <div>
                                <label for="primary_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Cor Primária
                                </label>
                                <div class="flex space-x-3">
                                    <div class="flex items-center">
                                        <input type="radio" id="color-blue" name="primary_color" value="blue" class="sr-only" {{ old('primary_color', $settings['appearance']['primary_color'] ?? 'blue') == 'blue' ? 'checked' : '' }}>
                                        <label for="color-blue" class="w-8 h-8 bg-blue-600 rounded-full cursor-pointer border-4 border-white shadow-md hover:scale-110 transition-transform"></label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" id="color-green" name="primary_color" value="green" class="sr-only" {{ old('primary_color', $settings['appearance']['primary_color'] ?? 'blue') == 'green' ? 'checked' : '' }}>
                                        <label for="color-green" class="w-8 h-8 bg-green-600 rounded-full cursor-pointer border-4 border-white shadow-md hover:scale-110 transition-transform"></label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" id="color-purple" name="primary_color" value="purple" class="sr-only" {{ old('primary_color', $settings['appearance']['primary_color'] ?? 'blue') == 'purple' ? 'checked' : '' }}>
                                        <label for="color-purple" class="w-8 h-8 bg-purple-600 rounded-full cursor-pointer border-4 border-white shadow-md hover:scale-110 transition-transform"></label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio" id="color-red" name="primary_color" value="red" class="sr-only" {{ old('primary_color', $settings['appearance']['primary_color'] ?? 'blue') == 'red' ? 'checked' : '' }}>
                                        <label for="color-red" class="w-8 h-8 bg-red-600 rounded-full cursor-pointer border-4 border-white shadow-md hover:scale-110 transition-transform"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row justify-end mt-6 gap-3">
                            <button type="submit" 
                                    class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Salvar Configurações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Security Settings -->
            <div id="security-section" class="settings-section hidden">
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Configurações de Segurança</h2>
                    
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="section" value="security">
                        
                        <div class="space-y-6">
                            <!-- Password Requirements -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                                    Requisitos de Senha
                                </label>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="password_min_length" value="1" {{ old('password_min_length', $settings['security']['password_min_length'] ?? true) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Mínimo de 8 caracteres</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="password_uppercase" value="1" {{ old('password_uppercase', $settings['security']['password_uppercase'] ?? true) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Pelo menos uma letra maiúscula</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="password_numbers" value="1" {{ old('password_numbers', $settings['security']['password_numbers'] ?? true) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Pelo menos um número</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="password_special_chars" value="1" {{ old('password_special_chars', $settings['security']['password_special_chars'] ?? false) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Pelo menos um caractere especial</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Session Settings -->
                            <div>
                                <label for="session_lifetime" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Duração da Sessão (minutos)
                                </label>
                                <input type="number" id="session_lifetime" name="session_lifetime" 
                                       value="{{ old('session_lifetime', $settings['security']['session_lifetime'] ?? 120) }}" min="30" max="1440"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Entre 30 minutos e 24 horas</p>
                            </div>
                            
                            <!-- Login Attempts -->
                            <div>
                                <label for="max_login_attempts" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Máximo de Tentativas de Login
                                </label>
                                <input type="number" id="max_login_attempts" name="max_login_attempts" 
                                       value="{{ old('max_login_attempts', $settings['security']['max_login_attempts'] ?? 5) }}" min="3" max="10"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Entre 3 e 10 tentativas</p>
                            </div>
                            
                            <!-- Two Factor Authentication -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                                    Autenticação de Dois Fatores
                                </label>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="enable_2fa" value="1" {{ old('enable_2fa', $settings['security']['enable_2fa'] ?? false) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Habilitar autenticação de dois fatores</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="force_2fa_admin" value="1" {{ old('force_2fa_admin', $settings['security']['force_2fa_admin'] ?? false) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Obrigatório para administradores</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row justify-end mt-6 gap-3">
                            <button type="submit" 
                                    class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Salvar Configurações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Notifications Settings -->
            <div id="notifications-section" class="settings-section hidden">
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Configurações de Notificações</h2>
                    
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="section" value="notifications">
                        
                        <div class="space-y-6">
                            <!-- Email Notifications -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                                    Notificações por Email
                                </label>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="email_new_user" value="1" {{ old('email_new_user', $settings['notifications']['email_new_user'] ?? true) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Novo usuário registrado</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="email_user_login" value="1" {{ old('email_user_login', $settings['notifications']['email_user_login'] ?? false) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Login de usuário</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="email_password_reset" value="1" {{ old('email_password_reset', $settings['notifications']['email_password_reset'] ?? true) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Redefinição de senha</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="email_account_changes" value="1" {{ old('email_account_changes', $settings['notifications']['email_account_changes'] ?? true) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Alterações na conta</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- System Notifications -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                                    Notificações do Sistema
                                </label>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="system_maintenance" value="1" {{ old('system_maintenance', $settings['notifications']['system_maintenance'] ?? true) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Manutenção do sistema</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="system_updates" value="1" {{ old('system_updates', $settings['notifications']['system_updates'] ?? true) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Atualizações do sistema</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="system_security" value="1" {{ old('system_security', $settings['notifications']['system_security'] ?? true) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Alertas de segurança</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Notification Frequency -->
                            <div>
                                <label for="notification_frequency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Frequência de Notificações
                                </label>
                                <select id="notification_frequency" name="notification_frequency" 
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="immediate" {{ old('notification_frequency', $settings['notifications']['notification_frequency'] ?? 'immediate') == 'immediate' ? 'selected' : '' }}>Imediata</option>
                                    <option value="hourly" {{ old('notification_frequency', $settings['notifications']['notification_frequency'] ?? 'immediate') == 'hourly' ? 'selected' : '' }}>A cada hora</option>
                                    <option value="daily" {{ old('notification_frequency', $settings['notifications']['notification_frequency'] ?? 'immediate') == 'daily' ? 'selected' : '' }}>Diária</option>
                                    <option value="weekly" {{ old('notification_frequency', $settings['notifications']['notification_frequency'] ?? 'immediate') == 'weekly' ? 'selected' : '' }}>Semanal</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row justify-end mt-6 gap-3">
                            <button type="submit" 
                                    class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Salvar Configurações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- System Settings (Admin Only) -->
            @if(auth()->user()->isAdmin())
                <div id="system-section" class="settings-section hidden">
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Configurações do Sistema</h2>
                        
                        <form action="{{ route('settings.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="section" value="system">
                            
                            <div class="space-y-6">
                                <!-- Maintenance Mode -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                                        Modo de Manutenção
                                    </label>
                                    <div class="flex items-center space-x-4">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="maintenance_mode" value="1"
                                                   {{ old('maintenance_mode', $settings['system']['maintenance_mode'] ?? false) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Ativar modo de manutenção</span>
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Quando ativo, apenas administradores podem acessar o sistema</p>
                                </div>
                                
                                <!-- User Registration -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                                        Registro de Usuários
                                    </label>
                                    <div class="space-y-3">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="allow_registration" value="1"
                                                   {{ old('allow_registration', App\Models\Setting::isPublicRegistrationEnabled()) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Permitir registro de novos usuários</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="email_verification" value="1"
                                                   {{ old('email_verification', $settings['system']['email_verification'] ?? true) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Exigir verificação de email</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="admin_approval" value="1"
                                                   {{ old('admin_approval', $settings['system']['admin_approval'] ?? false) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Exigir aprovação do administrador</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- System Logs -->
                                <div>
                                    <label for="log_retention" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Retenção de Logs (dias)
                                    </label>
                                    <input type="number" id="log_retention" name="log_retention" 
                                           value="{{ old('log_retention', $settings['system']['log_retention'] ?? 30) }}" min="7" max="365"
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Entre 7 e 365 dias</p>
                                </div>
                                
                                <!-- Backup Settings -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                                        Configurações de Backup
                                    </label>
                                    <div class="space-y-3">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="auto_backup" value="1"
                                                   {{ old('auto_backup', $settings['system']['auto_backup'] ?? true) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Backup automático</span>
                                        </label>
                                        <div class="ml-6">
                                            <label for="backup_frequency" class="block text-sm text-gray-600 dark:text-gray-400 mb-1">
                                                Frequência do backup:
                                            </label>
                                            <select id="backup_frequency" name="backup_frequency" 
                                                    class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                                <option value="daily" {{ old('backup_frequency', $settings['system']['backup_frequency'] ?? 'daily') == 'daily' ? 'selected' : '' }}>Diário</option>
                                                <option value="weekly" {{ old('backup_frequency', $settings['system']['backup_frequency'] ?? 'daily') == 'weekly' ? 'selected' : '' }}>Semanal</option>
                                                <option value="monthly" {{ old('backup_frequency', $settings['system']['backup_frequency'] ?? 'daily') == 'monthly' ? 'selected' : '' }}>Mensal</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row justify-end mt-6 gap-3">
                                <button type="submit" 
                                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Salvar Configurações
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<footer class="mt-8">
    <div class="text-center py-6">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            © {{ date('Y') }} Danilo Miguel. Todos os direitos reservados.
        </p>
        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
            Sistema de Gestão Financeira - Desenvolvido com Laravel
        </p>
    </div>
</footer>

<!-- Toast Notifications -->
@if(session('success'))
    <div id="toast-success" class="fixed top-4 right-4 z-50 flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3 text-sm font-normal">{{ session('success') }}</div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" onclick="document.getElementById('toast-success').remove()">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
@endif

<script>
// Auto-hide toasts after 5 seconds
setTimeout(() => {
    const successToast = document.getElementById('toast-success');
    if (successToast) successToast.remove();
}, 5000);

// Settings navigation
function showSection(sectionName) {
    // Hide all sections
    document.querySelectorAll('.settings-section').forEach(section => {
        section.classList.add('hidden');
    });
    
    // Show selected section
    document.getElementById(sectionName + '-section').classList.remove('hidden');
    
    // Update navigation buttons
    document.querySelectorAll('.settings-nav-btn').forEach(btn => {
        btn.classList.remove('bg-blue-100', 'text-blue-700', 'dark:bg-blue-900', 'dark:text-blue-200');
        btn.classList.add('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');
    });
    
    // Highlight active button
    const activeBtn = document.querySelector(`[data-section="${sectionName}"]`);
    activeBtn.classList.remove('text-gray-700', 'dark:text-gray-300', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');
    activeBtn.classList.add('bg-blue-100', 'text-blue-700', 'dark:bg-blue-900', 'dark:text-blue-200');
}

// Theme selection and real-time application
document.querySelectorAll('input[name="default_theme"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Update visual selection
        document.querySelectorAll('label[for^="theme-"]').forEach(label => {
            label.classList.remove('border-blue-500');
            label.classList.add('border-gray-200', 'dark:border-gray-600');
        });
        
        if (this.checked) {
            const label = document.querySelector(`label[for="${this.id}"]`);
            label.classList.remove('border-gray-200', 'dark:border-gray-600');
            label.classList.add('border-blue-500');
            
            // Apply theme immediately
            applyTheme(this.value);
        }
    });
});

// Theme application function
function applyTheme(theme) {
    const html = document.documentElement;
    
    if (theme === 'dark') {
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else if (theme === 'light') {
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    } else if (theme === 'auto') {
        // Auto theme based on system preference
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }
        localStorage.setItem('theme', 'auto');
    }
}

// Initialize theme on page load
document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    const themeRadio = document.querySelector(`input[name="default_theme"][value="${savedTheme}"]`);
    
    if (themeRadio) {
        themeRadio.checked = true;
        themeRadio.dispatchEvent(new Event('change'));
    }
    
    // Listen for system theme changes when auto is selected
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
        const currentTheme = localStorage.getItem('theme');
        if (currentTheme === 'auto') {
            applyTheme('auto');
        }
    });
});

// Color scheme selection
document.querySelectorAll('input[name="primary_color"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('label[for^="color-"]').forEach(label => {
            label.classList.remove('ring-4', 'ring-blue-300');
        });
        
        if (this.checked) {
            const label = document.querySelector(`label[for="${this.id}"]`);
            label.classList.add('ring-4', 'ring-blue-300');
        }
    });
});
</script>
@endsection