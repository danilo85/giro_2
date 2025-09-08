@extends('layouts.app')

@section('title', 'Meu Perfil - Giro')

@section('content')
<style>
    /* Drag and Drop Styles */
    .logo-drop-zone {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .logo-drop-zone:hover {
        border-color: #3b82f6 !important;
        background-color: #eff6ff;
    }
    
    .logo-drop-zone.drag-over {
        border-color: #3b82f6 !important;
        background-color: #dbeafe !important;
        transform: scale(1.02);
    }
    
    .upload-icon {
        transition: transform 0.2s ease;
    }
    
    .logo-drop-zone:hover .upload-icon {
        transform: scale(1.1);
    }
    
    .feedback-message {
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Meu Perfil</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Gerencie suas informações pessoais e configurações de conta</p>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Picture Section -->
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Foto do Perfil</h2>
                
                <!-- Two Column Layout -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column: Avatar and Form -->
                    <div class="text-center">
                        <div class="relative inline-block mb-4">
                            <img id="avatar-preview" 
                                 class="w-32 h-32 rounded-full mx-auto border-4 border-gray-200 dark:border-gray-600" 
                                 src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=3B82F6&background=EBF4FF' }}" 
                                 alt="{{ auth()->user()->name }}">
                            
                            <!-- Online Status -->
                            <div class="absolute bottom-2 right-2 w-8 h-8 bg-green-400 border-4 border-white dark:border-gray-800 rounded-full"></div>
                            
                            <!-- Admin Crown -->
                            @if(auth()->user()->is_admin)
                                <div class="absolute -top-2 -right-2 w-8 h-8 text-yellow-500">
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <form id="avatar-form" action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" id="avatar-input" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                            <button type="button" onclick="document.getElementById('avatar-input').click()" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors mb-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Alterar Foto
                            </button>
                            <button type="submit" id="save-avatar-btn" 
                                    class="hidden w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Salvar Foto
                            </button>
                        </form>
                        
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            JPG, PNG ou GIF. Máximo 2MB.
                        </p>
                    </div>
                    
                    <!-- Right Column: Account Information with Badges -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Informações da Conta</h3>
                        
                        <!-- Member Since -->
                        <div class="space-y-2">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Membro desde:</span>
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ auth()->user()->created_at->locale('pt_BR')->isoFormat('MMMM [de] YYYY') }}
                            </div>
                        </div>
                        
                        <!-- Access Level -->
                        <div class="space-y-2">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Nível de acesso:</span>
                            @if(auth()->user()->is_admin)
                                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    Administrador
                                </div>
                            @else
                                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Usuário Padrão
                                </div>
                            @endif
                        </div>
                        
                        <!-- Status -->
                        <div class="space-y-2">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Status:</span>
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                Ativo
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Profile Information Form -->
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Informações Pessoais</h2>
                
                <form id="profile-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nome Completo *
                            </label>
                            <input type="text" id="name" name="name" 
                                   value="{{ old('name', auth()->user()->name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   required>
                            <div id="name-error" class="hidden text-sm text-red-600 dark:text-red-400 mt-1"></div>
                            @error('name')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Email -->
                        <div class="md:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email *
                            </label>
                            <input type="email" id="email" name="email" 
                                   value="{{ old('email', auth()->user()->email) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   required>
                            <div id="email-error" class="hidden text-sm text-red-600 dark:text-red-400 mt-1"></div>
                            @error('email')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- CPF/CNPJ -->
                        <div class="md:col-span-2">
                            <label for="cpf_cnpj" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                CPF/CNPJ
                            </label>
                            <input type="text" id="cpf_cnpj" name="cpf_cnpj" 
                                   value="{{ old('cpf_cnpj', auth()->user()->cpf_cnpj) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="000.000.000-00 ou 00.000.000/0000-00">
                            <div id="cpf-cnpj-error" class="hidden text-sm text-red-600 dark:text-red-400 mt-1"></div>
                            @error('cpf_cnpj')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Save Button -->
                    <div class="flex justify-end mt-6">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Logomarcas Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 mt-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Logomarcas da Empresa</h2>
                
                <form id="logos-form" action="{{ route('profile.logo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Logo Horizontal -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Logo Horizontal
                            </label>
                            <div class="logo-drop-zone border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 text-center hover:border-blue-400 transition-colors" 
                                 data-logo-type="horizontal" 
                                 ondrop="handleDrop(event, 'horizontal')" 
                                 ondragover="handleDragOver(event)" 
                                 ondragenter="handleDragEnter(event)" 
                                 ondragleave="handleDragLeave(event)">
                                @if(auth()->user()->getLogoByType('horizontal'))
                                    <img id="logo-horizontal-preview" 
                                         src="{{ auth()->user()->getLogoByType('horizontal')->url }}" 
                                         alt="Logo Horizontal" 
                                         class="max-h-20 mx-auto mb-2">
                                @else
                                    <div id="logo-horizontal-placeholder" class="text-gray-400 mb-2">
                                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <img id="logo-horizontal-preview" class="max-h-20 mx-auto mb-2 hidden" alt="Logo Horizontal">
                                @endif
                                <input type="file" id="logo-horizontal" name="logo_horizontal" accept="image/*" class="hidden" onchange="previewLogo(this, 'horizontal')">
                                <button type="button" onclick="document.getElementById('logo-horizontal').click()" 
                                        class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                    {{ auth()->user()->getLogoByType('horizontal') ? 'Alterar' : 'Selecionar' }} Arquivo
                                </button>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PNG, JPG, SVG até 2MB</p>
                            </div>
                            @error('logo_horizontal')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Logo Vertical -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Logo Vertical
                            </label>
                            <div class="logo-drop-zone border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 text-center hover:border-blue-400 transition-colors" 
                                 data-logo-type="vertical" 
                                 ondrop="handleDrop(event, 'vertical')" 
                                 ondragover="handleDragOver(event)" 
                                 ondragenter="handleDragEnter(event)" 
                                 ondragleave="handleDragLeave(event)">
                                @if(auth()->user()->getLogoByType('vertical'))
                                    <img id="logo-vertical-preview" 
                                         src="{{ auth()->user()->getLogoByType('vertical')->url }}" 
                                         alt="Logo Vertical" 
                                         class="max-h-20 mx-auto mb-2">
                                @else
                                    <div id="logo-vertical-placeholder" class="text-gray-400 mb-2">
                                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <img id="logo-vertical-preview" class="max-h-20 mx-auto mb-2 hidden" alt="Logo Vertical">
                                @endif
                                <input type="file" id="logo-vertical" name="logo_vertical" accept="image/*" class="hidden" onchange="previewLogo(this, 'vertical')">
                                <button type="button" onclick="document.getElementById('logo-vertical').click()" 
                                        class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                    {{ auth()->user()->getLogoByType('vertical') ? 'Alterar' : 'Selecionar' }} Arquivo
                                </button>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PNG, JPG, SVG até 2MB</p>
                            </div>
                            @error('logo_vertical')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Logo Ícone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ícone
                            </label>
                            <div class="logo-drop-zone border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 text-center hover:border-blue-400 transition-colors" 
                                 data-logo-type="icone" 
                                 ondrop="handleDrop(event, 'icone')" 
                                 ondragover="handleDragOver(event)" 
                                 ondragenter="handleDragEnter(event)" 
                                 ondragleave="handleDragLeave(event)">
                                @if(auth()->user()->getLogoByType('icone'))
                                    <img id="logo-icone-preview" 
                                         src="{{ auth()->user()->getLogoByType('icone')->url }}" 
                                         alt="Ícone" 
                                         class="max-h-20 mx-auto mb-2">
                                @else
                                    <div id="logo-icone-placeholder" class="text-gray-400 mb-2">
                                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <img id="logo-icone-preview" class="max-h-20 mx-auto mb-2 hidden" alt="Ícone">
                                @endif
                                <input type="file" id="logo-icone" name="logo_icone" accept="image/*" class="hidden" onchange="previewLogo(this, 'icone')">
                                <button type="button" onclick="document.getElementById('logo-icone').click()" 
                                        class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                    {{ auth()->user()->getLogoByType('icone') ? 'Alterar' : 'Selecionar' }} Arquivo
                                </button>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PNG, JPG, SVG até 2MB</p>
                            </div>
                            @error('logo_icone')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Save Logos Button -->
                    <div class="flex justify-end mt-6">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Salvar Logomarcas
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Assinatura Digital Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 mt-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Assinatura Digital</h2>
                
                <form id="signature-form" action="{{ route('profile.signature') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Upload Area with Drag & Drop -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Arquivo da Assinatura
                            </label>
                            <div id="signature-drop-zone" class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-blue-400 transition-all duration-200 cursor-pointer">
                                <div class="text-gray-400 mb-4">
                                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </div>
                                <input type="file" id="assinatura-digital" name="assinatura" accept="image/*" class="hidden">
                                <div id="signature-upload-text">
                                    <p class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Arraste sua assinatura aqui
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                        ou clique para selecionar
                                    </p>
                                    <button type="button" id="signature-select-btn" 
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                        {{ auth()->user()->assinatura_digital ? 'Alterar' : 'Selecionar' }} Assinatura
                                    </button>
                                </div>
                                <div id="signature-upload-progress" class="hidden">
                                    <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                        <div id="signature-progress-bar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Enviando assinatura...</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">PNG, JPG, SVG até 2MB</p>
                            </div>
                            @error('assinatura')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Preview Area -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Visualização
                            </label>
                            <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 bg-gray-50 dark:bg-gray-700 min-h-[200px] flex items-center justify-center">
                                @if(auth()->user()->assinatura_digital)
                                    <img id="signature-preview" 
                                         src="{{ auth()->user()->assinatura_url }}" 
                                         alt="Assinatura Digital" 
                                         class="max-h-32 max-w-full">
                                @else
                                    <div id="signature-placeholder" class="text-center text-gray-400">
                                        <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                        <p class="text-sm">Nenhuma assinatura carregada</p>
                                    </div>
                                    <img id="signature-preview" class="max-h-32 max-w-full hidden" alt="Assinatura Digital">
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Save Signature Button -->
                    <div class="flex justify-end mt-6">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Salvar Assinatura
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Change Password Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 mt-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Alterar Senha</h2>
                
                <form id="password-form" action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Senha Atual *
                            </label>
                            <div class="relative">
                                <input type="password" id="current_password" name="current_password" 
                                       class="w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       required>
                                <button type="button" onclick="togglePassword('current_password')" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nova Senha *
                            </label>
                            <div class="relative">
                                <input type="password" id="password" name="password" 
                                       class="w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       required>
                                <button type="button" onclick="togglePassword('password')" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                            <div id="password-error" class="hidden text-sm text-red-600 dark:text-red-400 mt-1"></div>
                            @error('password')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Confirmar Nova Senha *
                            </label>
                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation" 
                                       class="w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                       required>
                                <button type="button" onclick="togglePassword('password_confirmation')" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                            <div id="password-confirmation-error" class="hidden text-sm text-red-600 dark:text-red-400 mt-1"></div>
                        </div>
                    </div>
                    
                    <!-- Password Requirements -->
                    <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Requisitos da senha:</h4>
                        <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Mínimo de 8 caracteres
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Pelo menos uma letra maiúscula
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Pelo menos um número
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Save Password Button -->
                    <div class="flex justify-end mt-6">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Alterar Senha
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Delete Account Section - Positioned Below All Other Cards -->
    <div class="mt-8 ">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-red-200 dark:border-red-700 p-6">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-2">Deletar Conta</h3>
                    <p class="text-sm text-red-700 dark:text-red-300 mb-4">
                        Esta ação é <strong>irreversível</strong>. Todos os seus dados, incluindo perfil, configurações e histórico, serão permanentemente removidos do sistema.
                    </p>
                    <button type="button" 
                            onclick="openDeleteAccountModal()"
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Deletar Minha Conta
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

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

@if(session('error'))
    <div id="toast-error" class="fixed top-4 right-4 z-50 flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3 text-sm font-normal">{{ session('error') }}</div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" onclick="document.getElementById('toast-error').remove()">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
@endif

<!-- Delete Account Modal -->
<div id="deleteAccountModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                            Confirmar Exclusão da Conta
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Esta ação não pode ser desfeita. Para confirmar a exclusão da sua conta, digite sua senha atual abaixo.
                            </p>
                        </div>
                        <div class="mt-4">
                            <form id="deleteAccountForm" action="{{ route('profile.delete') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <label for="delete_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Senha Atual *
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                           id="delete_password" 
                                           name="password" 
                                           required
                                           class="w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                                           placeholder="Digite sua senha atual">
                                    <button type="button" onclick="togglePassword('delete_password')" 
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div id="delete-password-error" class="hidden text-sm text-red-600 dark:text-red-400 mt-1"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" 
                        onclick="confirmDeleteAccount()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Deletar Conta
                </button>
                <button type="button" 
                        onclick="closeDeleteAccountModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-hide toasts after 5 seconds
setTimeout(() => {
    const successToast = document.getElementById('toast-success');
    const errorToast = document.getElementById('toast-error');
    if (successToast) successToast.remove();
    if (errorToast) errorToast.remove();
}, 5000);

// Avatar preview function
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview').src = e.target.result;
            document.getElementById('save-avatar-btn').classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
    field.setAttribute('type', type);
}

// Real-time validation
document.getElementById('name').addEventListener('input', function() {
    const value = this.value.trim();
    const errorDiv = document.getElementById('name-error');
    
    if (value.length < 2) {
        errorDiv.textContent = 'Nome deve ter pelo menos 2 caracteres';
        errorDiv.classList.remove('hidden');
    } else {
        errorDiv.classList.add('hidden');
    }
});

document.getElementById('email').addEventListener('input', function() {
    const value = this.value.trim();
    const errorDiv = document.getElementById('email-error');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (!emailRegex.test(value)) {
        errorDiv.textContent = 'Por favor, insira um email válido';
        errorDiv.classList.remove('hidden');
    } else {
        errorDiv.classList.add('hidden');
    }
});

document.getElementById('password').addEventListener('input', function() {
    const value = this.value;
    const errorDiv = document.getElementById('password-error');
    const confirmField = document.getElementById('password_confirmation');
    const confirmErrorDiv = document.getElementById('password-confirmation-error');
    
    // Password strength validation
    const hasMinLength = value.length >= 8;
    const hasUpperCase = /[A-Z]/.test(value);
    const hasNumber = /\d/.test(value);
    
    if (!hasMinLength || !hasUpperCase || !hasNumber) {
        errorDiv.textContent = 'A senha deve ter pelo menos 8 caracteres, uma letra maiúscula e um número';
        errorDiv.classList.remove('hidden');
    } else {
        errorDiv.classList.add('hidden');
    }
    
    // Check password confirmation match
    if (confirmField.value && confirmField.value !== value) {
        confirmErrorDiv.textContent = 'As senhas não coincidem';
        confirmErrorDiv.classList.remove('hidden');
    } else {
        confirmErrorDiv.classList.add('hidden');
    }
});

document.getElementById('password_confirmation').addEventListener('input', function() {
    const value = this.value;
    const passwordField = document.getElementById('password');
    const errorDiv = document.getElementById('password-confirmation-error');
    
    if (value !== passwordField.value) {
        errorDiv.textContent = 'As senhas não coincidem';
        errorDiv.classList.remove('hidden');
    } else {
        errorDiv.classList.add('hidden');
    }
});

// CPF/CNPJ Mask Function
function applyCpfCnpjMask(input) {
    let value = input.value.replace(/\D/g, ''); // Remove tudo que não é dígito
    
    if (value.length <= 11) {
        // CPF: 000.000.000-00
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        input.placeholder = 'CPF: 000.000.000-00';
    } else {
        // CNPJ: 00.000.000/0000-00
        value = value.replace(/(\d{2})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1/$2');
        value = value.replace(/(\d{4})(\d{1,2})$/, '$1-$2');
        input.placeholder = 'CNPJ: 00.000.000/0000-00';
    }
    
    input.value = value;
    
    // Validação visual
    const isValid = validateCpfCnpj(value);
    const errorDiv = document.getElementById('cpf-cnpj-error');
    
    if (value.length > 0 && !isValid) {
        input.classList.add('border-red-500');
        input.classList.remove('border-green-500');
        if (errorDiv) {
            errorDiv.textContent = value.length <= 14 ? 'CPF inválido' : 'CNPJ inválido';
            errorDiv.classList.remove('hidden');
        }
    } else if (value.length > 0 && isValid) {
        input.classList.add('border-green-500');
        input.classList.remove('border-red-500');
        if (errorDiv) {
            errorDiv.classList.add('hidden');
        }
    } else {
        input.classList.remove('border-red-500', 'border-green-500');
        if (errorDiv) {
            errorDiv.classList.add('hidden');
        }
    }
}

// Validação de CPF/CNPJ
function validateCpfCnpj(value) {
    const numbers = value.replace(/\D/g, '');
    
    if (numbers.length === 11) {
        return validateCpf(numbers);
    } else if (numbers.length === 14) {
        return validateCnpj(numbers);
    }
    
    return false;
}

// Validação de CPF
function validateCpf(cpf) {
    if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
        return false;
    }
    
    let sum = 0;
    for (let i = 0; i < 9; i++) {
        sum += parseInt(cpf.charAt(i)) * (10 - i);
    }
    let remainder = (sum * 10) % 11;
    if (remainder === 10 || remainder === 11) remainder = 0;
    if (remainder !== parseInt(cpf.charAt(9))) return false;
    
    sum = 0;
    for (let i = 0; i < 10; i++) {
        sum += parseInt(cpf.charAt(i)) * (11 - i);
    }
    remainder = (sum * 10) % 11;
    if (remainder === 10 || remainder === 11) remainder = 0;
    if (remainder !== parseInt(cpf.charAt(10))) return false;
    
    return true;
}

// Validação de CNPJ
function validateCnpj(cnpj) {
    if (cnpj.length !== 14 || /^(\d)\1{13}$/.test(cnpj)) {
        return false;
    }
    
    let length = cnpj.length - 2;
    let numbers = cnpj.substring(0, length);
    let digits = cnpj.substring(length);
    let sum = 0;
    let pos = length - 7;
    
    for (let i = length; i >= 1; i--) {
        sum += numbers.charAt(length - i) * pos--;
        if (pos < 2) pos = 9;
    }
    
    let result = sum % 11 < 2 ? 0 : 11 - sum % 11;
    if (result !== parseInt(digits.charAt(0))) return false;
    
    length = length + 1;
    numbers = cnpj.substring(0, length);
    sum = 0;
    pos = length - 7;
    
    for (let i = length; i >= 1; i--) {
        sum += numbers.charAt(length - i) * pos--;
        if (pos < 2) pos = 9;
    }
    
    result = sum % 11 < 2 ? 0 : 11 - sum % 11;
    if (result !== parseInt(digits.charAt(1))) return false;
    
    return true;
}

// Aplicar máscara ao campo CPF/CNPJ
const cpfCnpjField = document.getElementById('cpf_cnpj');
if (cpfCnpjField) {
    cpfCnpjField.addEventListener('input', function() {
        applyCpfCnpjMask(this);
    });
    
    cpfCnpjField.addEventListener('keypress', function(e) {
        // Permitir apenas números
        if (!/\d/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Escape', 'Enter'].includes(e.key)) {
            e.preventDefault();
        }
    });
    
    // Remover formatação ao enviar o formulário
    const form = cpfCnpjField.closest('form');
    if (form) {
        form.addEventListener('submit', function() {
            cpfCnpjField.value = cpfCnpjField.value.replace(/\D/g, '');
        });
    }
    
    // Aplicar máscara no valor inicial se existir
    if (cpfCnpjField.value) {
        applyCpfCnpjMask(cpfCnpjField);
    }
}

// Delete Account Modal Functions
function openDeleteAccountModal() {
    document.getElementById('deleteAccountModal').classList.remove('hidden');
    document.getElementById('delete_password').focus();
}

function closeDeleteAccountModal() {
    document.getElementById('deleteAccountModal').classList.add('hidden');
    document.getElementById('delete_password').value = '';
    document.getElementById('delete-password-error').classList.add('hidden');
}

function confirmDeleteAccount() {
    const password = document.getElementById('delete_password').value;
    const errorDiv = document.getElementById('delete-password-error');
    
    if (!password) {
        errorDiv.textContent = 'Por favor, digite sua senha para confirmar';
        errorDiv.classList.remove('hidden');
        return;
    }
    
    // Submit the form
    document.getElementById('deleteAccountForm').submit();
}

// Close modal when clicking outside
document.getElementById('deleteAccountModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteAccountModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteAccountModal();
    }
});

// Logo preview function
function previewLogo(input, type) {
    const file = input.files[0];
    const preview = document.getElementById(`logo-${type}-preview`);
    const placeholder = document.getElementById(`logo-${type}-placeholder`);
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        };
        reader.readAsDataURL(file);
    }
}

// Drag and Drop Functions for Logos
function handleDragOver(e) {
    e.preventDefault();
    e.stopPropagation();
    e.currentTarget.classList.add('border-blue-500', 'bg-blue-50');
}

function handleDragEnter(e) {
    e.preventDefault();
    e.stopPropagation();
    e.currentTarget.classList.add('border-blue-500', 'bg-blue-50');
}

function handleDragLeave(e) {
    e.preventDefault();
    e.stopPropagation();
    e.currentTarget.classList.remove('border-blue-500', 'bg-blue-50');
}

function handleDrop(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const dropZone = e.currentTarget;
    dropZone.classList.remove('border-blue-500', 'bg-blue-50');
    
    const files = e.dataTransfer.files;
    const logoType = dropZone.getAttribute('data-logo-type');
    
    if (files.length > 0) {
        const file = files[0];
        
        // Validar tipo de arquivo
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/svg+xml'];
        if (!allowedTypes.includes(file.type)) {
            alert('Tipo de arquivo não permitido. Use apenas JPG, PNG ou SVG.');
            return;
        }
        
        // Validar tamanho (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Arquivo muito grande. O tamanho máximo é 2MB.');
            return;
        }
        
        // Atualizar o input file
        const fileInput = document.getElementById(`logo-${logoType}`);
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;
        
        // Mostrar preview
        previewLogo(fileInput, logoType);
        
        // Mostrar feedback de sucesso
        showUploadFeedback(logoType, 'Arquivo carregado com sucesso!');
    }
}

function showUploadFeedback(logoType, message) {
    // Criar elemento de feedback se não existir
    let feedback = document.getElementById(`feedback-${logoType}`);
    if (!feedback) {
        feedback = document.createElement('div');
        feedback.id = `feedback-${logoType}`;
        feedback.className = 'mt-2 text-sm text-green-600 font-medium';
        const dropZone = document.querySelector(`[data-logo-type="${logoType}"]`);
        dropZone.appendChild(feedback);
    }
    
    feedback.textContent = message;
    feedback.classList.remove('hidden');
    
    // Remover feedback após 3 segundos
    setTimeout(() => {
        feedback.classList.add('hidden');
    }, 3000);
}

// Signature preview function
function previewSignature(input) {
    const file = input.files[0];
    const preview = document.getElementById('signature-preview');
    const placeholder = document.getElementById('signature-placeholder');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        };
        reader.readAsDataURL(file);
    }
}

// Drag and Drop Functions for Signature
function handleSignatureDragOver(e) {
    e.preventDefault();
    e.stopPropagation();
    const dropZone = document.getElementById('signature-drop-zone');
    dropZone.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
}

function handleSignatureDragEnter(e) {
    e.preventDefault();
    e.stopPropagation();
    const dropZone = document.getElementById('signature-drop-zone');
    dropZone.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
}

function handleSignatureDragLeave(e) {
    e.preventDefault();
    e.stopPropagation();
    const dropZone = document.getElementById('signature-drop-zone');
    dropZone.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
}

function handleSignatureDrop(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const dropZone = document.getElementById('signature-drop-zone');
    dropZone.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
    
    const files = e.dataTransfer.files;
    
    if (files.length > 0) {
        const file = files[0];
        
        // Validar tipo de arquivo
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/svg+xml'];
        if (!allowedTypes.includes(file.type)) {
            showSignatureFeedback('Tipo de arquivo não permitido. Use apenas JPG, PNG ou SVG.', 'error');
            return;
        }
        
        // Validar tamanho (2MB)
        if (file.size > 2 * 1024 * 1024) {
            showSignatureFeedback('Arquivo muito grande. O tamanho máximo é 2MB.', 'error');
            return;
        }
        
        // Atualizar o input file
        const fileInput = document.getElementById('assinatura-digital');
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;
        
        // Mostrar preview
        previewSignature(fileInput);
        
        // Mostrar feedback de sucesso
        showSignatureFeedback('Assinatura carregada com sucesso!', 'success');
    }
}

function showSignatureFeedback(message, type) {
    // Criar elemento de feedback se não existir
    let feedback = document.getElementById('signature-feedback');
    if (!feedback) {
        feedback = document.createElement('div');
        feedback.id = 'signature-feedback';
        feedback.className = 'mt-2 text-sm font-medium';
        const dropZone = document.getElementById('signature-drop-zone');
        dropZone.parentNode.appendChild(feedback);
    }
    
    // Definir cor baseada no tipo
    feedback.className = `mt-2 text-sm font-medium ${
        type === 'error' ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400'
    }`;
    
    feedback.textContent = message;
    feedback.classList.remove('hidden');
    
    // Remover feedback após 3 segundos
    setTimeout(() => {
        feedback.classList.add('hidden');
    }, 3000);
}

// Initialize Signature Drag and Drop
document.addEventListener('DOMContentLoaded', function() {
    const signatureDropZone = document.getElementById('signature-drop-zone');
    const signatureInput = document.getElementById('assinatura-digital');
    const signatureSelectBtn = document.getElementById('signature-select-btn');
    
    if (signatureDropZone) {
        // Drag and drop events
        signatureDropZone.addEventListener('dragover', handleSignatureDragOver);
        signatureDropZone.addEventListener('dragenter', handleSignatureDragEnter);
        signatureDropZone.addEventListener('dragleave', handleSignatureDragLeave);
        signatureDropZone.addEventListener('drop', handleSignatureDrop);
        
        // Click to select file
        signatureDropZone.addEventListener('click', function(e) {
            if (e.target !== signatureSelectBtn) {
                signatureInput.click();
            }
        });
        
        // Button click
        if (signatureSelectBtn) {
            signatureSelectBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                signatureInput.click();
            });
        }
        
        // File input change
        if (signatureInput) {
            signatureInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    previewSignature(this);
                    showSignatureFeedback('Assinatura selecionada com sucesso!', 'success');
                }
            });
        }
    }
});
</script>
@endsection