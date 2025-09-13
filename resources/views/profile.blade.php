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

<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Meu Perfil</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Gerencie suas informações pessoais e configurações de conta</p>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Profile Picture Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 mb-8">
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

        <!-- Profile Information Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Informações Pessoais</h2>
            
            <form id="profile-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Full Name -->
                    <div class="">
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
                    <div class="">
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
                    <div class="">
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
                    
                    <!-- Phone/WhatsApp -->
                    <div>
                        <label for="telefone_whatsapp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Telefone/WhatsApp
                        </label>
                        <input type="text" id="telefone_whatsapp" name="telefone_whatsapp" 
                               value="{{ old('telefone_whatsapp', auth()->user()->telefone_whatsapp) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                               placeholder="(11) 99999-9999">
                        <div id="telefone-error" class="hidden text-sm text-red-600 dark:text-red-400 mt-1"></div>
                        @error('telefone_whatsapp')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Profissão -->
                    <div>
                        <label for="profissao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Profissão
                        </label>
                        <input type="text" id="profissao" name="profissao" 
                               value="{{ old('profissao', auth()->user()->profissao) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                               placeholder="Ex: Desenvolvedor Web, Designer Gráfico">
                        <div id="profissao-error" class="hidden text-sm text-red-600 dark:text-red-400 mt-1"></div>
                        @error('profissao')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Extra Email -->
                    <div>
                        <label for="email_extra" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email Extra
                        </label>
                        <input type="email" id="email_extra" name="email_extra" 
                               value="{{ old('email_extra', auth()->user()->email_extra) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                               placeholder="email.adicional@exemplo.com">
                        <div id="email-extra-error" class="hidden text-sm text-red-600 dark:text-red-400 mt-1"></div>
                        @error('email_extra')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Biography -->
                    <div class="md:col-span-2">
                        <label for="biografia" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Biografia
                        </label>
                        <div class="border border-gray-300 dark:border-gray-600 rounded-md focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500">
                            <!-- Editor Toolbar -->
                            <div class="flex items-center space-x-1 p-2 border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 rounded-t-md">
                                <button type="button" onclick="execCommand('bold')" class="editor-btn" title="Negrito">
                                    <i class="fas fa-bold"></i>
                                </button>
                                <button type="button" onclick="execCommand('italic')" class="editor-btn" title="Itálico">
                                    <i class="fas fa-italic"></i>
                                </button>
                                <button type="button" onclick="execCommand('underline')" class="editor-btn" title="Sublinhado">
                                    <i class="fas fa-underline"></i>
                                </button>
                                <div class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></div>
                                <button type="button" onclick="execCommand('justifyLeft')" class="editor-btn" title="Alinhar à esquerda">
                                    <i class="fas fa-align-left"></i>
                                </button>
                                <button type="button" onclick="execCommand('justifyCenter')" class="editor-btn" title="Centralizar">
                                    <i class="fas fa-align-center"></i>
                                </button>
                                <button type="button" onclick="execCommand('justifyRight')" class="editor-btn" title="Alinhar à direita">
                                    <i class="fas fa-align-right"></i>
                                </button>
                                <div class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></div>
                                <button type="button" onclick="execCommand('insertUnorderedList')" class="editor-btn" title="Lista com marcadores">
                                    <i class="fas fa-list-ul"></i>
                                </button>
                                <button type="button" onclick="execCommand('insertOrderedList')" class="editor-btn" title="Lista numerada">
                                    <i class="fas fa-list-ol"></i>
                                </button>
                                <div class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></div>
                                <button type="button" onclick="insertLink()" class="editor-btn" title="Inserir link">
                                    <i class="fas fa-link"></i>
                                </button>
                                <button type="button" onclick="execCommand('unlink')" class="editor-btn" title="Remover link">
                                    <i class="fas fa-unlink"></i>
                                </button>
                                <div class="w-px h-6 bg-gray-300 dark:bg-gray-600 mx-1"></div>
                                <button type="button" onclick="undoEdit()" class="editor-btn" title="Desfazer">
                                    <i class="fas fa-undo"></i>
                                </button>
                                <button type="button" onclick="redoEdit()" class="editor-btn" title="Refazer">
                                    <i class="fas fa-redo"></i>
                                </button>
                            </div>
                            
                            <!-- Editor Content -->
                            <div id="biografia-editor" 
                                 class="min-h-[120px] p-3 focus:outline-none dark:bg-gray-700 dark:text-white rounded-b-md" 
                                 contenteditable="true" 
                                 placeholder="Conte um pouco sobre você...">{!! old('biografia', auth()->user()->biografia) !!}</div>
                        </div>
                        <input type="hidden" id="biografia" name="biografia" value="{{ old('biografia', auth()->user()->biografia) }}">
                        <div id="biografia-error" class="hidden text-sm text-red-600 dark:text-red-400 mt-1"></div>
                        @error('biografia')
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
                        Salvar Informações
                    </button>
                </div>
            </form>
        </div>

        <!-- Social Media Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Redes Sociais</h2>
            
            <form action="{{ route('profile.social-media.update') }}" method="POST" class="no-loading">
                    @method('PUT')
                    @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Instagram -->
                    <div>
                        <label for="instagram" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Instagram
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fab fa-instagram text-gray-400"></i>
                            </div>
                            <input type="text" id="instagram" name="instagram" 
                                   value="{{ old('instagram', auth()->user()->instagram_url) }}"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="@seuusuario">
                        </div>
                        @error('instagram')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Facebook -->
                    <div>
                        <label for="facebook" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Facebook
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fab fa-facebook text-gray-400"></i>
                            </div>
                            <input type="text" id="facebook" name="facebook" 
                                   value="{{ old('facebook', auth()->user()->facebook_url) }}"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="facebook.com/seuusuario">
                        </div>
                        @error('facebook')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- LinkedIn -->
                    <div>
                        <label for="linkedin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            LinkedIn
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fab fa-linkedin text-gray-400"></i>
                            </div>
                            <input type="text" id="linkedin" name="linkedin" 
                                   value="{{ old('linkedin', auth()->user()->linkedin_url) }}"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="linkedin.com/in/seuusuario">
                        </div>
                        @error('linkedin')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Twitter -->
                    <div>
                        <label for="twitter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Twitter
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fab fa-twitter text-gray-400"></i>
                            </div>
                            <input type="text" id="twitter" name="twitter" 
                                   value="{{ old('twitter', auth()->user()->twitter_url) }}"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="@seuusuario">
                        </div>
                        @error('twitter')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- YouTube -->
                    <div>
                        <label for="youtube" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            YouTube
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fab fa-youtube text-gray-400"></i>
                            </div>
                            <input type="text" id="youtube" name="youtube" 
                                   value="{{ old('youtube', auth()->user()->youtube_url) }}"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="youtube.com/c/seucanal">
                        </div>
                        @error('youtube')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- TikTok -->
                    <div>
                        <label for="tiktok" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            TikTok
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fab fa-tiktok text-gray-400"></i>
                            </div>
                            <input type="text" id="tiktok" name="tiktok" 
                                   value="{{ old('tiktok', auth()->user()->tiktok_url) }}"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="@seuusuario">
                        </div>
                        @error('tiktok')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- WhatsApp -->
                    <div>
                        <label for="whatsapp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            WhatsApp
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fab fa-whatsapp text-gray-400"></i>
                            </div>
                            <input type="text" id="whatsapp" name="whatsapp" 
                                   value="{{ old('whatsapp', auth()->user()->whatsapp_url) }}"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="wa.me/5511999999999">
                        </div>
                        @error('whatsapp')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Website -->
                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Website
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-globe text-gray-400"></i>
                            </div>
                            <input type="text" id="website" name="website" 
                                   value="{{ old('website', auth()->user()->website_url) }}"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="www.seusite.com">
                        </div>
                        @error('website')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Connected Social Accounts -->
                @if(auth()->user()->socialAccounts && auth()->user()->socialAccounts->count() > 0)
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Contas Conectadas</h3>
                        <div class="space-y-2">
                            @foreach(auth()->user()->socialAccounts as $account)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                    <div class="flex items-center">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 capitalize">{{ $account->provider }}</span>
                                        <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">Conectado</span>
                                    </div>
                                    <form action="{{ route('profile.social-media.delete', $account->provider) }}" method="POST" class="inline">
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

        <!-- Logos da Empresa -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Logos da Empresa</h2>
            
            <!-- Grid de três colunas responsivo -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Logo Horizontal -->
                <div class="text-center">
                    <h3 class="text-md font-medium text-gray-900 dark:text-white mb-4">Logo Horizontal</h3>
                    
                    <div class="relative inline-block mb-4">
                        <img id="horizontal-logo-preview" 
                             class="w-40 h-24 object-contain border-4 border-gray-200 dark:border-gray-600 rounded-lg bg-white" 
                             src="{{ auth()->user()->getLogoByType('horizontal') ? auth()->user()->getLogoByType('horizontal')->url : 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYwIiBoZWlnaHQ9Ijk2IiB2aWV3Qm94PSIwIDAgMTYwIDk2IiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cmVjdCB3aWR0aD0iMTYwIiBoZWlnaHQ9Ijk2IiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik04MCA0OEw2NCA2NEg5Nkw4MCA0OFoiIGZpbGw9IiM5Q0EzQUYiLz4KPHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4PSI2OCIgeT0iMzYiPgo8cGF0aCBkPSJNNCAyMEgxNkMxNy4xIDIwIDE4IDE5LjEgMTggMThWNkMxOCA0LjkgMTcuMSA0IDE2IDRINEMyLjkgNCAyIDQuOSAyIDZWMThDMiAxOS4xIDIuOSAyMCA0IDIwWk00IDZIMTZWMThINFY2WiIgZmlsbD0iIzlDQTNBRiIvPgo8cGF0aCBkPSJNOC41IDEzLjVMMTEgMTZMMTQuNSAxMS41TDE3IDE2SDdMOC41IDEzLjVaIiBmaWxsPSIjOUNBM0FGIi8+Cjwvc3ZnPgo8L3N2Zz4K' }}" 
                             alt="Logo Horizontal">
                        
                        <!-- Delete Button -->
                        @if(auth()->user()->getLogoByType('horizontal'))
                            <form action="{{ route('profile.logo.delete', 'horizontal') }}" method="POST" class="absolute -top-2 -right-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white rounded-full p-2 shadow-lg" 
                                        onclick="return confirm('Tem certeza que deseja remover este logo?')" title="Remover logo">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                    
                    <form id="horizontal-logo-form" action="{{ route('profile.logo', 'horizontal') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" id="horizontal-logo-input" name="logo" accept="image/*" class="hidden" onchange="previewLogo(this, 'horizontal-logo-preview')">
                        <button type="button" onclick="document.getElementById('horizontal-logo-input').click()" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors mb-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Alterar Logo
                        </button>
                        <button type="submit" id="save-horizontal-logo-btn" 
                                class="hidden w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Salvar Logo
                        </button>
                    </form>
                    
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        PNG, JPG ou GIF. Máximo 2MB. Formato horizontal recomendado.
                    </p>
                </div>
                
                <!-- Logo Vertical -->
                <div class="text-center">
                    <h3 class="text-md font-medium text-gray-900 dark:text-white mb-4">Logo Vertical</h3>
                    
                    <div class="relative inline-block mb-4">
                        <img id="vertical-logo-preview" 
                             class="w-24 h-40 object-contain border-4 border-gray-200 dark:border-gray-600 rounded-lg bg-white" 
                             src="{{ auth()->user()->getLogoByType('vertical') ? auth()->user()->getLogoByType('vertical')->url : 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iOTYiIGhlaWdodD0iMTYwIiB2aWV3Qm94PSIwIDAgOTYgMTYwIiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cmVjdCB3aWR0aD0iOTYiIGhlaWdodD0iMTYwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik00OCA4MEwzMiA5Nkg2NEw0OCA4MFoiIGZpbGw9IiM5Q0EzQUYiLz4KPHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4PSIzNiIgeT0iNjgiPgo8cGF0aCBkPSJNNCAyMEgxNkMxNy4xIDIwIDE4IDE5LjEgMTggMThWNkMxOCA0LjkgMTcuMSA0IDE2IDRINEMyLjkgNCAyIDQuOSAyIDZWMThDMiAxOS4xIDIuOSAyMCA0IDIwWk00IDZIMTZWMThINFY2WiIgZmlsbD0iIzlDQTNBRiIvPgo8cGF0aCBkPSJNOC41IDEzLjVMMTEgMTZMMTQuNSAxMS41TDE3IDE2SDdMOC41IDEzLjVaIiBmaWxsPSIjOUNBM0FGIi8+Cjwvc3ZnPgo8L3N2Zz4K' }}" 
                             alt="Logo Vertical">
                        
                        <!-- Delete Button -->
                        @if(auth()->user()->getLogoByType('vertical'))
                            <form action="{{ route('profile.logo.delete', 'vertical') }}" method="POST" class="absolute -top-2 -right-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white rounded-full p-2 shadow-lg" 
                                        onclick="return confirm('Tem certeza que deseja remover este logo?')" title="Remover logo">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                    
                    <form id="vertical-logo-form" action="{{ route('profile.logo', 'vertical') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" id="vertical-logo-input" name="logo" accept="image/*" class="hidden" onchange="previewLogo(this, 'vertical-logo-preview')">
                        <button type="button" onclick="document.getElementById('vertical-logo-input').click()" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors mb-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Alterar Logo
                        </button>
                        <button type="submit" id="save-vertical-logo-btn" 
                                class="hidden w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Salvar Logo
                        </button>
                    </form>
                    
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        PNG, JPG ou GIF. Máximo 2MB. Formato vertical recomendado.
                    </p>
                </div>
                
                <!-- Logo Ícone -->
                <div class="space-y-4">
                    <h3 class="text-md font-medium text-gray-900 dark:text-white">Ícone</h3>
                    
                    <div class="text-center">
                        <div class="relative inline-block mb-4">
                            <img id="icone-logo-preview" 
                                 class="w-24 h-24 object-contain border-4 border-gray-200 dark:border-gray-600 rounded-lg bg-white" 
                                 src="{{ auth()->user()->getLogoByType('icone') ? auth()->user()->getLogoByType('icone')->url : 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iOTYiIGhlaWdodD0iOTYiIHZpZXdCb3g9IjAgMCA5NiA5NiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9Ijk2IiBoZWlnaHQ9Ijk2IiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik00OCA0OEwzMiA2NEg2NEw0OCA0OFoiIGZpbGw9IiM5Q0EzQUYiLz4KPHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4PSIzNiIgeT0iMzYiPgo8cGF0aCBkPSJNNCAyMEgxNkMxNy4xIDIwIDE4IDE5LjEgMTggMThWNkMxOCA0LjkgMTcuMSA0IDE2IDRINEMyLjkgNCAyIDQuOSAyIDZWMThDMiAxOS4xIDIuOSAyMCA0IDIwWk00IDZIMTZWMThINFY2WiIgZmlsbD0iIzlDQTNBRiIvPgo8cGF0aCBkPSJNOC41IDEzLjVMMTEgMTZMMTQuNSAxMS41TDE3IDE2SDdMOC41IDEzLjVaIiBmaWxsPSIjOUNBM0FGIi8+Cjwvc3ZnPgo8L3N2Zz4K' }}" 
                                 alt="Logo Ícone">
                            
                            <!-- Delete Button -->
                            @if(auth()->user()->getLogoByType('icone'))
                                <form action="{{ route('profile.logo.delete', 'icone') }}" method="POST" class="absolute -top-2 -right-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white rounded-full p-2 shadow-lg" 
                                            onclick="return confirm('Tem certeza que deseja remover este logo?')" title="Remover logo">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                        
                        <form id="icone-logo-form" action="{{ route('profile.logo', 'icone') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" id="icone-logo-input" name="logo" accept="image/*" class="hidden" onchange="previewLogo(this, 'icone-logo-preview')">
                            <button type="button" onclick="document.getElementById('icone-logo-input').click()" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors mb-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Alterar Logo
                            </button>
                            <button type="submit" id="save-icone-logo-btn" 
                                    class="hidden w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Salvar Logo
                            </button>
                        </form>
                        
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            PNG, JPG ou GIF. Máximo 2MB. Formato quadrado recomendado.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assinatura Digital -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Assinatura Digital</h2>
            
            <div class="text-center">
                <div class="relative inline-block mb-4">
                    @if(auth()->user()->assinatura_digital)
                        <img id="signature-preview" src="{{ auth()->user()->assinatura_url }}" alt="Assinatura Digital" 
                             class="w-48 h-24 object-contain border-4 border-gray-200 dark:border-gray-600 rounded-lg bg-white">
                    @else
                        <div id="signature-preview" class="w-48 h-24 bg-gray-100 dark:bg-gray-700 border-4 border-dashed border-gray-300 dark:border-gray-600 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Delete Button -->
                    @if(auth()->user()->assinatura_digital)
                        <form action="{{ route('profile.signature.delete') }}" method="POST" class="absolute -top-2 -right-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white rounded-full p-2 shadow-lg" 
                                    onclick="return confirm('Tem certeza que deseja remover esta assinatura?')" title="Remover assinatura">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </form>
                    @endif
                </div>
                
                <form id="signature-form" action="{{ route('profile.signature') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" id="signature-input" name="assinatura" accept="image/*" class="hidden" onchange="previewSignatureImage(this)">
                    <button type="button" onclick="document.getElementById('signature-input').click()" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors mb-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Alterar Assinatura
                    </button>
                    <button type="submit" id="save-signature-btn" 
                            class="hidden w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Salvar Assinatura
                    </button>
                </form>
                
                @error('assinatura')
                    <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                @enderror
                
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    PNG, JPG até 2MB. Recomendado: fundo transparente.
                </p>
            </div>
        </div>

        <!-- Imagens do Orçamento -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Imagens do Orçamento</h2>
            
            <!-- Grid de duas colunas responsivo -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Coluna 1: Imagem de Rodapé -->
                <div class="space-y-4">
                    <h3 class="text-md font-medium text-gray-900 dark:text-white">Imagem de Rodapé</h3>
                    
                    <div class="text-center">
                        <div class="relative inline-block mb-4">
                            @if(auth()->user()->rodape_image)
                                <img id="rodape-preview" src="{{ auth()->user()->rodape_image_url }}" alt="Imagem de Rodapé" 
                                     class="w-24 h-24 object-cover border-4 border-gray-200 dark:border-gray-600 rounded-lg">
                            @else
                                <div id="rodape-preview" class="w-24 h-24 bg-gray-100 dark:bg-gray-700 border-4 border-dashed border-gray-300 dark:border-gray-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Delete Button -->
                            @if(auth()->user()->rodape_image)
                                <form action="{{ route('profile.rodape.delete') }}" method="POST" class="absolute -top-2 -right-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white rounded-full p-2 shadow-lg" 
                                            onclick="return confirm('Tem certeza que deseja remover esta imagem?')" title="Remover imagem">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                        
                        <form id="rodape-form" action="{{ route('profile.rodape.upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" id="rodape-input" name="rodape_image" accept="image/*" class="hidden" onchange="previewRodape(this)">
                            <button type="button" onclick="document.getElementById('rodape-input').click()" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors mb-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Alterar Imagem
                            </button>
                            <button type="submit" id="save-rodape-btn" 
                                    class="hidden w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Salvar Imagem
                            </button>
                        </form>
                        
                        @error('rodape_image')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                        @enderror
                        
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            PNG, JPG ou GIF. Máximo 2MB.
                        </p>
                    </div>
                </div>
                
                <!-- Coluna 2: QR Code -->
                <div class="space-y-4">
                    <h3 class="text-md font-medium text-gray-900 dark:text-white">QR Code</h3>
                    
                    <div class="text-center">
                        <div class="relative inline-block mb-4">
                            @if(auth()->user()->qrcode_image)
                                <img id="qrcode-preview" src="{{ auth()->user()->qrcode_image_url }}" alt="QR Code" 
                                     class="w-24 h-24 object-cover border-4 border-gray-200 dark:border-gray-600 rounded-lg">
                            @else
                                <div id="qrcode-preview" class="w-24 h-24 bg-gray-100 dark:bg-gray-700 border-4 border-dashed border-gray-300 dark:border-gray-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Delete Button -->
                            @if(auth()->user()->qrcode_image)
                                <form action="{{ route('profile.qrcode.delete') }}" method="POST" class="absolute -top-2 -right-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white rounded-full p-2 shadow-lg" 
                                            onclick="return confirm('Tem certeza que deseja remover este QR Code?')" title="Remover QR Code">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                        
                        <form id="qrcode-form" action="{{ route('profile.qrcode.upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" id="qrcode-input" name="qrcode_image" accept="image/*" class="hidden" onchange="previewQrcode(this)">
                            <button type="button" onclick="document.getElementById('qrcode-input').click()" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors mb-2">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                </svg>
                                Alterar QR Code
                            </button>
                            <button type="submit" id="save-qrcode-btn" 
                                    class="hidden w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Salvar QR Code
                            </button>
                        </form>
                        
                        @error('qrcode_image')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                        @enderror
                        
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            PNG, JPG ou GIF. Máximo 2MB.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alterar Senha -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 mb-8">
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
                        @error('password_confirmation')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
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

        <!-- Delete Account Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-red-200 dark:border-red-700 p-6 mb-8">
            <h2 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-4">Zona de Perigo</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                Uma vez que você deletar sua conta, não há como voltar atrás. Por favor, tenha certeza.
            </p>
            
            <form action="{{ route('profile.delete') }}" method="POST" onsubmit="return confirm('Tem certeza que deseja deletar sua conta? Esta ação não pode ser desfeita.')">
                @csrf
                @method('DELETE')
                
                <button type="submit" 
                        class="inline-flex items-center px-6 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Deletar Conta
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Link Modal -->
<div id="linkModal" class="hidden">
    <div class="modal-content">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Inserir Link</h3>
        <div class="space-y-4">
            <div>
                <label for="linkText" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Texto do Link:</label>
                <input type="text" id="linkText" placeholder="Digite o texto do link">
            </div>
            <div>
                <label for="linkUrl" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">URL:</label>
                <input type="url" id="linkUrl" placeholder="https://exemplo.com">
            </div>
        </div>
        <div class="flex justify-end space-x-3 mt-6">
            <button type="button" onclick="closeLinkModal()" class="btn-secondary">Cancelar</button>
            <button type="button" onclick="insertLinkFromModal()" class="btn-primary">Inserir</button>
        </div>
    </div>
</div>

<script>
// Editor functionality
let editorHistory = [];
let historyIndex = -1;

function execCommand(command, value = null) {
    document.execCommand(command, false, value);
    updateHiddenInput();
    saveState();
}

function saveState() {
    const editor = document.getElementById('biografia-editor');
    if (editor) {
        const currentState = editor.innerHTML;
        // Remove states after current index
        editorHistory = editorHistory.slice(0, historyIndex + 1);
        editorHistory.push(currentState);
        historyIndex = editorHistory.length - 1;
        
        // Limit history to 50 states
        if (editorHistory.length > 50) {
            editorHistory.shift();
            historyIndex--;
        }
    }
}

function undoEdit() {
    if (historyIndex > 0) {
        historyIndex--;
        const editor = document.getElementById('biografia-editor');
        if (editor) {
            editor.innerHTML = editorHistory[historyIndex];
            updateHiddenInput();
        }
    }
}

function redoEdit() {
    if (historyIndex < editorHistory.length - 1) {
        historyIndex++;
        const editor = document.getElementById('biografia-editor');
        if (editor) {
            editor.innerHTML = editorHistory[historyIndex];
            updateHiddenInput();
        }
    }
}

function insertLink() {
    document.getElementById('linkModal').classList.remove('hidden');
    document.getElementById('linkText').focus();
}

function closeLinkModal() {
    document.getElementById('linkModal').classList.add('hidden');
    document.getElementById('linkText').value = '';
    document.getElementById('linkUrl').value = '';
}

function insertLinkFromModal() {
    const text = document.getElementById('linkText').value;
    const url = document.getElementById('linkUrl').value;
    
    if (text && url) {
        const link = `<a href="${url}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">${text}</a>`;
        document.execCommand('insertHTML', false, link);
        updateHiddenInput();
        saveState();
        closeLinkModal();
    }
}

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
    field.setAttribute('type', type);
}

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



function updateHiddenInput() {
    const editor = document.getElementById('biografia-editor');
    const hiddenInput = document.getElementById('biografia');
    if (editor && hiddenInput) {
        hiddenInput.value = editor.innerHTML;
    }
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

function applyPhoneMask(input) {
    const formattedValue = formatPhone(input.value);
    input.value = formattedValue;
}

// Logo preview functions
function previewLogo(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImg = document.getElementById(previewId);
            if (previewImg) {
                previewImg.src = e.target.result;
                
                // Show save button for the corresponding logo type
                if (previewId === 'horizontal-logo-preview') {
                    document.getElementById('save-horizontal-logo-btn').classList.remove('hidden');
                } else if (previewId === 'vertical-logo-preview') {
                    document.getElementById('save-vertical-logo-btn').classList.remove('hidden');
                } else if (previewId === 'icone-logo-preview') {
                    document.getElementById('save-icone-logo-btn').classList.remove('hidden');
                }
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}



// Drag and drop functionality
function setupDragAndDrop(dropZoneId, inputId, previewFunction) {
    const dropZone = document.getElementById(dropZoneId);
    const input = document.getElementById(inputId);
    
    if (!dropZone || !input) return;
    
    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });
    
    // Highlight drop zone when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });
    
    // Handle dropped files
    dropZone.addEventListener('drop', handleDrop, false);
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    function highlight(e) {
        dropZone.classList.add('border-blue-500', 'bg-blue-50');
    }
    
    function unhighlight(e) {
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
    }
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            input.files = files;
            previewFunction(input);
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const editor = document.getElementById('biografia-editor');
    if (editor) {
        // Save initial state
        saveState();
        
        // Add input event listener
        editor.addEventListener('input', function() {
            updateHiddenInput();
        });
    }
    
    // Initialize phone mask
    const phoneInput = document.getElementById('telefone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            applyPhoneMask(this);
        });
    }
    
    // Setup drag and drop for logos
    setupDragAndDrop('logo-horizontal-drop', 'logo-horizontal', function(input) {
        previewLogo(input, 'horizontal');
    });
    
    setupDragAndDrop('logo-vertical-drop', 'logo-vertical', function(input) {
        previewLogo(input, 'vertical');
    });
    
    setupDragAndDrop('icone-drop', 'icone-logo-input', function(input) {
        previewLogo(input, 'icone');
    });
    
    // Setup drag and drop for signature
    setupDragAndDrop('signature-drop', 'signature', previewSignatureImage);
    
    // Setup AJAX form submissions for logos
    setupLogoFormSubmission('horizontal-logo-form', 'horizontal');
    setupLogoFormSubmission('vertical-logo-form', 'vertical');
    setupLogoFormSubmission('icone-logo-form', 'icone');
    
    // Setup AJAX form submissions for other uploads
    setupSignatureFormSubmission();
    setupFormSubmission('rodape-form', 'rodape-preview', 'save-rodape-btn');
    setupFormSubmission('qrcode-form', 'qrcode-preview', 'save-qrcode-btn');
    
    // Setup AJAX form submission for social media
    setupSocialMediaFormSubmission();
    
    // Setup drag and drop for other upload areas
    setupImageDragAndDrop('icone-logo-input', 'icone-drop', 'icone-logo-preview', 'save-icone-logo-btn', function(input) { previewLogo(input, 'icone'); });
    setupImageDragAndDrop('signature-input', 'signature-drop', 'signature-preview', 'save-signature-btn', previewSignatureImage);
    setupImageDragAndDrop('rodape-input', 'rodape-drop', 'rodape-preview', 'save-rodape-btn', previewRodape);
    setupImageDragAndDrop('qrcode-input', 'qrcode-drop', 'qrcode-preview', 'save-qrcode-btn', previewQrcode);
});

// Function to setup AJAX form submission for logo forms
function setupLogoFormSubmission(formId, logoType) {
    const form = document.getElementById(formId);
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Enviando...';
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update logo preview
                const logoPreview = document.getElementById(`${logoType}-logo-preview`);
                if (logoPreview && data.logo_url) {
                    logoPreview.src = data.logo_url;
                    logoPreview.style.display = 'block';
                }
                
                // Hide save button
                submitBtn.style.display = 'none';
                
                // Show success message
                showMessage(data.message, 'success');
                
                // Reset form
                form.reset();
                
                // Redirect to profile after 2 seconds
                setTimeout(() => {
                    window.location.href = '/profile';
                }, 2000);
            } else {
                showMessage(data.message || 'Erro ao enviar logo', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Erro ao enviar logo', 'error');
        })
        .finally(() => {
            // Restore button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
}

// Function to show messages
function showMessage(message, type) {
    // Remove existing messages
    const existingMessages = document.querySelectorAll('.feedback-message');
    existingMessages.forEach(msg => msg.remove());
    
    // Create new message
    const messageDiv = document.createElement('div');
    messageDiv.className = `feedback-message fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
        type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200'
    }`;
    messageDiv.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                ${type === 'success' 
                    ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>'
                    : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>'
                }
            </svg>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(messageDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        messageDiv.remove();
    }, 5000);
}

// Function to setup AJAX form submission for signature
function setupSignatureFormSubmission() {
    const form = document.getElementById('signature-form');
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const submitBtn = document.getElementById('save-signature-btn');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Salvando...';
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update signature preview
                const signaturePreview = document.getElementById('signature-preview');
                if (signaturePreview && data.signature_url) {
                    if (signaturePreview.tagName === 'IMG') {
                        signaturePreview.src = data.signature_url;
                    } else {
                        signaturePreview.innerHTML = `<img src="${data.signature_url}" alt="Assinatura Digital" class="w-48 h-24 object-contain border-4 border-gray-200 dark:border-gray-600 rounded-lg bg-white">`;
                    }
                }
                
                // Hide save button
                submitBtn.classList.add('hidden');
                
                // Show success message
                showMessage(data.message, 'success');
                
                // Reset form
                form.reset();
                
                // Redirect to profile after 2 seconds
                setTimeout(() => {
                    window.location.href = '/profile';
                }, 2000);
            } else {
                showMessage(data.message || 'Erro ao salvar assinatura', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Erro ao salvar assinatura', 'error');
        })
        .finally(() => {
            // Restore button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
}

// Function to setup AJAX form submission for social media
function setupSocialMediaFormSubmission() {
    const form = document.querySelector('form[action*="social-media.update"]');
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Salvando...';
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
            } else {
                showMessage(data.message || 'Erro ao salvar redes sociais', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Erro ao salvar redes sociais', 'error');
        })
        .finally(() => {
            // Restore button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
}

// Function to setup AJAX form submission for other forms
function setupFormSubmission(formId, previewId, saveButtonId) {
    const form = document.getElementById(formId);
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const submitBtn = document.getElementById(saveButtonId);
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Salvando...';
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update image preview
                const imagePreview = document.getElementById(previewId);
                if (imagePreview && data.image_url) {
                    imagePreview.src = data.image_url;
                }
                
                // Hide save button
                submitBtn.classList.add('hidden');
                
                // Show success message
                showMessage(data.message, 'success');
                
                // Reset form
                form.reset();
                
                // Redirect to profile after 2 seconds
                setTimeout(() => {
                    window.location.href = '/profile';
                }, 2000);
            } else {
                showMessage(data.message || 'Erro ao enviar imagem', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Erro ao enviar imagem', 'error');
        })
        .finally(() => {
            // Restore button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
}

// Function to setup drag and drop for image uploads
function setupImageDragAndDrop(inputId, dropZoneId, previewId, saveButtonId, previewFunction) {
    const input = document.getElementById(inputId);
    const dropZone = document.getElementById(dropZoneId);
    const saveButton = document.getElementById(saveButtonId);
    
    if (!input || !dropZone) return;
    
    // Show drop zone when input is clicked
    input.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            dropZone.classList.remove('hidden');
            if (saveButton) saveButton.classList.remove('hidden');
            if (previewFunction) previewFunction(this);
        }
    });
    
    // Drag and drop events
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('border-blue-400', 'bg-blue-50');
    });
    
    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-400', 'bg-blue-50');
    });
    
    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-400', 'bg-blue-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            input.files = files;
            if (saveButton) saveButton.classList.remove('hidden');
            if (previewFunction) previewFunction(input);
        }
    });
    
    // Click to select file
    dropZone.addEventListener('click', function() {
        input.click();
    });
}

// Preview functions for different image types
function previewSignatureImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('signature-preview');
            if (preview) {
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Assinatura Digital" class="w-48 h-24 object-contain border-4 border-gray-200 dark:border-gray-600 rounded-lg bg-white">`;
                }
                // Show save button
                const saveBtn = document.getElementById('save-signature-btn');
                if (saveBtn) saveBtn.classList.remove('hidden');
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewSignature(input) {
    previewSignatureImage(input);
}

function previewRodape(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('rodape-preview');
            if (preview) {
                // If it's an img element, update src
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    // If it's a div, replace with img
                    preview.innerHTML = `<img src="${e.target.result}" alt="Rodapé" class="w-24 h-24 object-cover border-4 border-gray-200 dark:border-gray-600 rounded-lg">`;
                }
                // Show save button
                const saveBtn = document.getElementById('save-rodape-btn');
                if (saveBtn) saveBtn.classList.remove('hidden');
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewQrcode(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('qrcode-preview');
            if (preview) {
                // If it's an img element, update src
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    // If it's a div, replace with img
                    preview.innerHTML = `<img src="${e.target.result}" alt="QR Code" class="w-24 h-24 object-cover border-4 border-gray-200 dark:border-gray-600 rounded-lg">`;
                }
                // Show save button
                const saveBtn = document.getElementById('save-qrcode-btn');
                if (saveBtn) saveBtn.classList.remove('hidden');
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<style>
.editor-toolbar {
    border-bottom: 1px solid #e5e7eb;
    padding: 8px;
    background-color: #f9fafb;
    border-radius: 6px 6px 0 0;
}

.editor-toolbar button {
    margin-right: 4px;
    padding: 4px 8px;
    border: 1px solid #d1d5db;
    background-color: white;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
}

.editor-toolbar button:hover {
    background-color: #f3f4f6;
}

.editor-content {
    min-height: 120px;
    padding: 12px;
    border: 1px solid #e5e7eb;
    border-top: none;
    border-radius: 0 0 6px 6px;
    background-color: white;
    outline: none;
}

.editor-content:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 1px #3b82f6;
}

#linkModal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

#linkModal.hidden {
    display: none;
}

.modal-content {
    background-color: white;
    padding: 24px;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.modal-content input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
}

.modal-content input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 1px #3b82f6;
}

.btn-primary {
    background-color: #3b82f6;
    color: white;
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}

.btn-primary:hover {
    background-color: #2563eb;
}

.btn-secondary {
    background-color: #6b7280;
    color: white;
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}

.btn-secondary:hover {
    background-color: #4b5563;
}
</style>
@endsection