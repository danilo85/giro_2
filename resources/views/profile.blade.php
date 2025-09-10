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
                    
                    <!-- Telefone/WhatsApp -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label for="telefone_whatsapp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Telefone/WhatsApp
                            </label>
                            <input type="tel" 
                                   id="telefone_whatsapp" 
                                   name="telefone_whatsapp" 
                                   value="{{ old('telefone_whatsapp', auth()->user()->telefone_whatsapp) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="(11) 99999-9999 - Digite apenas números"
                                   maxlength="15"
                                   title="Digite seu telefone ou WhatsApp com DDD">
                            @error('telefone_whatsapp')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Email Extra -->
                        <div>
                            <label for="email_extra" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email Adicional
                            </label>
                            <input type="email" 
                                   id="email_extra" 
                                   name="email_extra" 
                                   value="{{ old('email_extra', auth()->user()->email_extra) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="email@exemplo.com">
                            @error('email_extra')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Profissão -->
                    <div class="mt-6">
                        <label for="profissao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Profissão
                        </label>
                        <input type="text" 
                               id="profissao" 
                               name="profissao" 
                               value="{{ old('profissao', auth()->user()->profissao) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                               placeholder="Ex: Desenvolvedor, Designer, Contador..."
                               maxlength="100">
                        @error('profissao')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Biografia -->
                    <div class="mt-6">
                        <label for="biografia" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Biografia
                        </label>
                        
                        <!-- Editor Toolbar -->
                        <div class="border border-gray-300 dark:border-gray-600 rounded-t-md bg-gray-50 dark:bg-gray-800 p-2 flex flex-wrap gap-1">
                            <button type="button" onclick="formatText('bold')" class="editor-btn" title="Negrito">
                                <i class="fas fa-bold"></i>
                            </button>
                            <button type="button" onclick="formatText('italic')" class="editor-btn" title="Itálico">
                                <i class="fas fa-italic"></i>
                            </button>
                            <button type="button" onclick="formatText('underline')" class="editor-btn" title="Sublinhado">
                                <i class="fas fa-underline"></i>
                            </button>
                            <button type="button" onclick="formatText('strikeThrough')" class="editor-btn" title="Riscado">
                                <i class="fas fa-strikethrough"></i>
                            </button>
                            <div class="border-l border-gray-300 dark:border-gray-600 mx-1"></div>
                            <button type="button" onclick="formatText('insertOrderedList')" class="editor-btn" title="Lista Numerada">
                                <i class="fas fa-list-ol"></i>
                            </button>
                            <button type="button" onclick="formatText('insertUnorderedList')" class="editor-btn" title="Lista com Marcadores">
                                <i class="fas fa-list-ul"></i>
                            </button>
                            <div class="border-l border-gray-300 dark:border-gray-600 mx-1"></div>
                            <button type="button" onclick="showLinkModal()" class="editor-btn" title="Link">
                                <i class="fas fa-link"></i>
                            </button>
                            <button type="button" onclick="insertLineBreak()" class="editor-btn" title="Quebra de Linha">
                                <i class="fas fa-level-down-alt"></i>
                            </button>
                            <div class="border-l border-gray-300 dark:border-gray-600 mx-1"></div>
                            <button type="button" onclick="undoEdit()" class="editor-btn" title="Desfazer">
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
                        
                        <!-- Editor Content -->
                        <div id="biografia-editor" 
                             contenteditable="true"
                             class="w-full min-h-[150px] px-3 py-2 border border-t-0 border-gray-300 dark:border-gray-600 rounded-b-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                             style="max-height: 300px; overflow-y: auto;"
                             placeholder="Conte um pouco sobre você ou sua empresa...">{!! old('biografia', auth()->user()->biografia) !!}</div>
                        
                        <!-- Hidden input to store the content -->
                        <input type="hidden" id="biografia" name="biografia" value="{{ old('biografia', auth()->user()->biografia) }}">
                        
                        @error('biografia')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
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

            <!-- Modal para inserir link -->
            <div id="linkModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-96 max-w-md mx-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Inserir Link</h3>
                    <div class="mb-4">
                        <label for="linkText" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Texto do Link:</label>
                        <input type="text" id="linkText" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Digite o texto do link">
                    </div>
                    <div class="mb-6">
                        <label for="linkUrl" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">URL:</label>
                        <input type="url" id="linkUrl" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="https://exemplo.com">
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeLinkModal()" class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">Cancelar</button>
                        <button type="button" onclick="insertLinkFromModal()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Inserir Link</button>
                    </div>
                </div>
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
            
            <!-- Social Media Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 mt-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Redes Sociais</h2>
                
                <form id="social-media-form" action="{{ route('profile.social-media.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <!-- Facebook -->
                        <div>
                            <label for="facebook_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                    Facebook
                                </div>
                            </label>
                            <input type="url" id="facebook_url" name="facebook_url" 
                                   value="{{ old('facebook_url', auth()->user()->facebook_url) }}"
                                   placeholder="https://facebook.com/seu-perfil"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            @error('facebook_url')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Instagram -->
                        <div>
                            <label for="instagram_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-pink-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.323-1.297C4.198 14.895 3.708 13.744 3.708 12.447s.49-2.448 1.418-3.323c.875-.807 2.026-1.297 3.323-1.297s2.448.49 3.323 1.297c.928.875 1.418 2.026 1.418 3.323s-.49 2.448-1.418 3.244c-.875.807-2.026 1.297-3.323 1.297zm7.83-9.608c-.384 0-.735-.147-.997-.384-.262-.262-.384-.613-.384-.997 0-.384.122-.735.384-.997.262-.237.613-.384.997-.384s.735.147.997.384c.262.262.384.613.384.997 0 .384-.122.735-.384.997-.262.237-.613.384-.997.384zm.997 2.448c0 .875-.122 1.75-.367 2.448-.245.698-.613 1.297-1.075 1.759-.462.462-1.061.83-1.759 1.075-.698.245-1.573.367-2.448.367-.875 0-1.75-.122-2.448-.367-.698-.245-1.297-.613-1.759-1.075-.462-.462-.83-1.061-1.075-1.759-.245-.698-.367-1.573-.367-2.448 0-.875.122-1.75.367-2.448.245-.698.613-1.297 1.075-1.759.462-.462 1.061-.83 1.759-1.075.698-.245 1.573-.367 2.448-.367.875 0 1.75.122 2.448.367.698.245 1.297.613 1.759 1.075.462.462.83 1.061 1.075 1.759.245.698.367 1.573.367 2.448z"/>
                                    </svg>
                                    Instagram
                                </div>
                            </label>
                            <input type="url" id="instagram_url" name="instagram_url" 
                                   value="{{ old('instagram_url', auth()->user()->instagram_url) }}"
                                   placeholder="https://instagram.com/seu-perfil"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            @error('instagram_url')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Twitter/X -->
                        <div>
                            <label for="twitter_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-black dark:text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                    </svg>
                                    Twitter/X
                                </div>
                            </label>
                            <input type="url" id="twitter_url" name="twitter_url" 
                                   value="{{ old('twitter_url', auth()->user()->twitter_url) }}"
                                   placeholder="https://twitter.com/seu-perfil ou https://x.com/seu-perfil"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            @error('twitter_url')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- LinkedIn -->
                        <div>
                            <label for="linkedin_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                    LinkedIn
                                </div>
                            </label>
                            <input type="url" id="linkedin_url" name="linkedin_url" 
                                   value="{{ old('linkedin_url', auth()->user()->linkedin_url) }}"
                                   placeholder="https://linkedin.com/in/seu-perfil"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            @error('linkedin_url')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- YouTube -->
                        <div>
                            <label for="youtube_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                    </svg>
                                    YouTube
                                </div>
                            </label>
                            <input type="url" id="youtube_url" name="youtube_url" 
                                   value="{{ old('youtube_url', auth()->user()->youtube_url) }}"
                                   placeholder="https://youtube.com/c/seu-canal"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            @error('youtube_url')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- TikTok -->
                        <div>
                            <label for="tiktok_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-black dark:text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                                    </svg>
                                    TikTok
                                </div>
                            </label>
                            <input type="url" id="tiktok_url" name="tiktok_url" 
                                   value="{{ old('tiktok_url', auth()->user()->tiktok_url) }}"
                                   placeholder="https://tiktok.com/@seu-perfil"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            @error('tiktok_url')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- WhatsApp -->
                        <div>
                            <label for="whatsapp_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                    </svg>
                                    WhatsApp
                                </div>
                            </label>
                            <input type="url" id="whatsapp_url" name="whatsapp_url" 
                                   value="{{ old('whatsapp_url', auth()->user()->whatsapp_url) }}"
                                   placeholder="https://wa.me/5511999999999"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            @error('whatsapp_url')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Website -->
                        <div>
                            <label for="website_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                    </svg>
                                    Website
                                </div>
                            </label>
                            <input type="url" id="website_url" name="website_url" 
                                   value="{{ old('website_url', auth()->user()->website_url) }}"
                                   placeholder="https://seu-site.com"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            @error('website_url')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Connected Social Accounts Display -->
                    @if(auth()->user()->socialAccounts->count() > 0)
                        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Contas Conectadas via OAuth:</h4>
                            <div class="space-y-2">
                                @foreach(auth()->user()->socialAccounts as $account)
                                    <div class="flex items-center justify-between p-2 bg-white dark:bg-gray-600 rounded border">
                                        <div class="flex items-center">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 capitalize">{{ $account->provider }}</span>
                                            <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">Conectado</span>
                                        </div>
                                        <form action="{{ route('profile.social-disconnect', $account->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                Desconectar
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Save Social Media Button -->
                    <div class="flex justify-end mt-6">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Salvar Redes Sociais
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

<!-- Simple Text Editor JavaScript -->
<script>
// Editor functionality
let editorHistory = [];
let historyIndex = -1;

// Save state for undo functionality
function saveState() {
    const editor = document.getElementById('biografia-editor');
    const content = editor.innerHTML;
    
    // Remove future history if we're not at the end
    if (historyIndex < editorHistory.length - 1) {
        editorHistory = editorHistory.slice(0, historyIndex + 1);
    }
    
    editorHistory.push(content);
    historyIndex = editorHistory.length - 1;
    
    // Limit history to 50 states
    if (editorHistory.length > 50) {
        editorHistory.shift();
        historyIndex--;
    }
}

// Format text using execCommand
function formatText(command) {
    saveState();
    document.execCommand(command, false, null);
    updateHiddenInput();
}

// Show link modal
function showLinkModal() {
    const modal = document.getElementById('linkModal');
    const linkText = document.getElementById('linkText');
    const linkUrl = document.getElementById('linkUrl');
    
    // Limpar campos
    linkText.value = '';
    linkUrl.value = '';
    
    // Verificar se há texto selecionado
    const selection = window.getSelection();
    if (selection.rangeCount > 0 && !selection.isCollapsed) {
        linkText.value = selection.toString();
    }
    
    modal.classList.remove('hidden');
    linkText.focus();
}

// Close link modal
function closeLinkModal() {
    const modal = document.getElementById('linkModal');
    modal.classList.add('hidden');
}

// Insert link from modal
function insertLinkFromModal() {
    const linkText = document.getElementById('linkText').value.trim();
    const linkUrl = document.getElementById('linkUrl').value.trim();
    
    if (!linkUrl) {
        alert('Por favor, digite uma URL válida.');
        return;
    }
    
    saveState();
    
    const editor = document.getElementById('biografia-editor');
    editor.focus();
    
    const selection = window.getSelection();
    let range;
    
    if (selection.rangeCount > 0) {
        range = selection.getRangeAt(0);
    } else {
        range = document.createRange();
        range.selectNodeContents(editor);
        range.collapse(false);
    }
    
    // Criar elemento de link
    const link = document.createElement('a');
    link.href = linkUrl;
    link.textContent = linkText || linkUrl;
    link.target = '_blank';
    
    // Inserir o link
    if (selection.isCollapsed || linkText) {
        range.deleteContents();
        range.insertNode(link);
    } else {
        // Se há texto selecionado e não foi fornecido texto personalizado
        const selectedText = selection.toString();
        link.textContent = selectedText;
        range.deleteContents();
        range.insertNode(link);
    }
    
    // Posicionar cursor após o link
    range.setStartAfter(link);
    range.collapse(true);
    selection.removeAllRanges();
    selection.addRange(range);
    
    updateHiddenInput();
    closeLinkModal();
}

// Insert line break
function insertLineBreak() {
    saveState();
    document.execCommand('insertHTML', false, '<br>');
    updateHiddenInput();
}

// Undo functionality
function undoEdit() {
    if (historyIndex > 0) {
        historyIndex--;
        const editor = document.getElementById('biografia-editor');
        editor.innerHTML = editorHistory[historyIndex];
        updateHiddenInput();
    }
}

// Update hidden input with editor content
function updateHiddenInput() {
    const editor = document.getElementById('biografia-editor');
    const hiddenInput = document.getElementById('biografia');
    hiddenInput.value = editor.innerHTML;
}

// Phone mask functions
function formatPhone(value) {
    // Remove todos os caracteres não numéricos
    const numbers = value.replace(/\D/g, '');
    
    // Limita a 11 dígitos (DDD + 9 dígitos para celular)
    const limitedNumbers = numbers.substring(0, 11);
    
    // Aplica a formatação baseada no número de dígitos
    if (limitedNumbers.length <= 2) {
        return limitedNumbers;
    } else if (limitedNumbers.length <= 6) {
        return `(${limitedNumbers.substring(0, 2)}) ${limitedNumbers.substring(2)}`;
    } else if (limitedNumbers.length <= 10) {
        // Telefone fixo: (XX) XXXX-XXXX
        return `(${limitedNumbers.substring(0, 2)}) ${limitedNumbers.substring(2, 6)}-${limitedNumbers.substring(6)}`;
    } else {
        // Celular: (XX) XXXXX-XXXX
        return `(${limitedNumbers.substring(0, 2)}) ${limitedNumbers.substring(2, 7)}-${limitedNumbers.substring(7)}`;
    }
}

function cleanPhone(value) {
    // Remove todos os caracteres não numéricos
    return value.replace(/\D/g, '');
}

function applyPhoneMask(input) {
    const cursorPosition = input.selectionStart;
    const oldValue = input.value;
    const oldLength = oldValue.length;
    
    // Aplica a formatação
    const newValue = formatPhone(input.value);
    input.value = newValue;
    
    // Ajusta a posição do cursor
    const newLength = newValue.length;
    const lengthDiff = newLength - oldLength;
    
    // Calcula nova posição do cursor
    let newCursorPosition = cursorPosition + lengthDiff;
    
    // Garante que o cursor não fique em uma posição inválida
    if (newCursorPosition < 0) newCursorPosition = 0;
    if (newCursorPosition > newLength) newCursorPosition = newLength;
    
    // Define a nova posição do cursor
    setTimeout(() => {
        input.setSelectionRange(newCursorPosition, newCursorPosition);
    }, 0);
}

// Initialize editor and phone mask
document.addEventListener('DOMContentLoaded', function() {
    const editor = document.getElementById('biografia-editor');
    const hiddenInput = document.getElementById('biografia');
    
    // Save initial state
    saveState();
    
    // Update hidden input on content change
    editor.addEventListener('input', function() {
        updateHiddenInput();
    });
    
    // Save state on key events for undo
    editor.addEventListener('keydown', function(e) {
        // Save state on significant changes
        if (e.key === 'Enter' || e.key === 'Backspace' || e.key === 'Delete') {
            setTimeout(saveState, 10);
        }
    });
    
    // Handle paste events
    editor.addEventListener('paste', function(e) {
        setTimeout(() => {
            saveState();
            updateHiddenInput();
        }, 10);
    });
    
    // Ensure content is synced on form submit
    const form = editor.closest('form');
    if (form) {
        form.addEventListener('submit', function() {
            updateHiddenInput();
        });
    }
    
    // Initialize phone mask
    const phoneInput = document.getElementById('telefone_whatsapp');
    if (phoneInput) {
        // Aplica máscara no valor inicial se existir
        if (phoneInput.value) {
            phoneInput.value = formatPhone(phoneInput.value);
        }
        
        // Event listener para formatação em tempo real
        phoneInput.addEventListener('input', function(e) {
            applyPhoneMask(this);
        });
        
        // Event listener para paste
        phoneInput.addEventListener('paste', function(e) {
            setTimeout(() => {
                applyPhoneMask(this);
            }, 0);
        });
        
        // Limpa a máscara antes de enviar o formulário
        const phoneForm = phoneInput.closest('form');
        if (phoneForm) {
            phoneForm.addEventListener('submit', function() {
                // Cria um campo hidden com o valor limpo
                const cleanValue = cleanPhone(phoneInput.value);
                const hiddenPhoneInput = document.createElement('input');
                hiddenPhoneInput.type = 'hidden';
                hiddenPhoneInput.name = 'telefone_whatsapp_clean';
                hiddenPhoneInput.value = cleanValue;
                phoneForm.appendChild(hiddenPhoneInput);
            });
        }
    }
});
</script>

<!-- Editor Styles -->
<style>
.editor-btn {
    @apply px-3 py-2 text-gray-600 dark:text-gray-300 hover:bg-blue-100 dark:hover:bg-blue-800 hover:text-blue-600 dark:hover:text-blue-300 rounded-md transition-all duration-200 flex items-center justify-center min-w-[36px] min-h-[36px] border border-transparent hover:border-blue-200 dark:hover:border-blue-700;
}

.editor-btn:hover {
    @apply bg-blue-100 dark:bg-blue-800 text-blue-600 dark:text-blue-300 border-blue-200 dark:border-blue-700 shadow-sm;
}

.editor-btn:active {
    @apply bg-blue-200 dark:bg-blue-700 text-blue-700 dark:text-blue-200 transform scale-95;
}

.editor-btn i {
    @apply text-sm;
}

#biografia-editor:empty:before {
    content: attr(placeholder);
    color: #9CA3AF;
    pointer-events: none;
}

#biografia-editor:focus {
    outline: none;
    @apply ring-2 ring-blue-500 ring-opacity-50;
}

/* Modal styles */
#linkModal {
    @apply fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50;
}

#linkModal .modal-content {
    @apply bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md mx-4;
}

#linkModal input {
    @apply w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white;
}

#linkModal button {
    @apply px-4 py-2 rounded-md font-medium transition-colors duration-200;
}

#linkModal .btn-primary {
    @apply bg-blue-600 hover:bg-blue-700 text-white;
}

#linkModal .btn-secondary {
    @apply bg-gray-300 hover:bg-gray-400 text-gray-700 dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-200;
}
 </style>
 
 @endsection