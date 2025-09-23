@extends('layouts.app')

@section('title', 'Editar Autor')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Editar Autor</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Atualize as informações do autor</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <a href="{{ route('autores.show', $autor) }}" 
                   class="inline-flex items-center justify-center w-10 h-10 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </a>
                <a href="{{ route('autores.index') }}" 
                   class="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulário Principal -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Informações do Autor</h2>
                    
                    <form method="POST" action="{{ route('autores.update', $autor) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Avatar -->
                        <div class="flex items-center space-x-6">
                            <div class="shrink-0">
                                <img id="avatar-preview" 
                                     class="h-16 w-16 object-cover rounded-full" 
                                     src="{{ $autor->avatar ? asset('storage/' . $autor->avatar) : 'data:image/svg+xml,%3csvg width=\'100\' height=\'100\' xmlns=\'http://www.w3.org/2000/svg\'%3e%3crect width=\'100\' height=\'100\' fill=\'%23f3f4f6\'/%3e%3ctext x=\'50%25\' y=\'50%25\' font-size=\'18\' text-anchor=\'middle\' alignment-baseline=\'middle\' font-family=\'monospace, sans-serif\' fill=\'%236b7280\'%3e' . strtoupper(substr($autor->nome, 0, 2)) . '%3c/text%3e%3c/svg%3e' }}" 
                                     alt="Avatar preview">
                            </div>
                            <div class="flex-1">
                                <label for="avatar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Avatar
                                </label>
                                <input type="file" 
                                       id="avatar" 
                                       name="avatar" 
                                       accept="image/*"
                                       onchange="previewAvatar(this)"
                                       class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-300">
                                @error('avatar')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">PNG, JPG ou GIF até 2MB</p>
                            </div>
                        </div>
                        
                        <!-- Nome -->
                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nome Completo *
                            </label>
                            <input type="text" 
                                   id="nome" 
                                   name="nome" 
                                   value="{{ old('nome', $autor->nome) }}"
                                   required
                                   onkeyup="updatePreview()"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('nome') border-red-500 @enderror">
                            @error('nome')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                E-mail *
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $autor->email) }}"
                                   required
                                   onkeyup="updatePreview()"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Contato -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="telefone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Telefone
                                </label>
                                <input type="tel" 
                                       id="telefone" 
                                       name="telefone" 
                                       value="{{ old('telefone', $autor->telefone) }}"
                                       placeholder="(11) 99999-9999"
                                       onkeyup="updatePreview()"
                                       oninput="applyPhoneMask(this)"
                                       maxlength="15"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('telefone') border-red-500 @enderror">
                                @error('telefone')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="whatsapp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    WhatsApp
                                </label>
                                <input type="tel" 
                                       id="whatsapp" 
                                       name="whatsapp" 
                                       value="{{ old('whatsapp', $autor->whatsapp) }}"
                                       placeholder="(11) 99999-9999"
                                       onkeyup="updatePreview()"
                                       oninput="applyPhoneMask(this)"
                                       maxlength="15"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('whatsapp') border-red-500 @enderror">
                                @error('whatsapp')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Biografia -->
                        <div>
                            <label for="biografia" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Biografia
                            </label>
                            <textarea id="biografia" 
                                      name="biografia" 
                                      rows="4"
                                      onkeyup="updatePreview()"
                                      placeholder="Breve descrição sobre o autor..."
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('biografia') border-red-500 @enderror">{{ old('biografia', $autor->biografia) }}</textarea>
                            @error('biografia')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('autores.index') }}" 
                               class="w-full sm:w-auto px-4 py-2 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors text-center inline-flex items-center justify-center">
                                <i class="fas fa-times mr-2"></i>
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="w-full sm:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors inline-flex items-center justify-center">
                                <i class="fas fa-save mr-2"></i>
                                Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Seção de Preview -->
        <div class="lg:col-span-1">
            <div class="space-y-6">
                <!-- Preview Atual -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dados Atuais</h3>
                        
                        <div class="text-center mb-6">
                            <div class="mx-auto h-20 w-20 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center mb-4">
                                @if($autor->avatar)
                                    <img src="{{ asset('storage/' . $autor->avatar) }}" class="h-20 w-20 rounded-full object-cover">
                                @else
                                    <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                @endif
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <h4 class="text-base font-semibold text-gray-900 dark:text-white">{{ $autor->nome }}</h4>
                            </div>
                            
                            <div class="space-y-2">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>{{ $autor->email }}</span>
                                </div>
                                
                                @if($autor->telefone)
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span>{{ $autor->telefone }}</span>
                                </div>
                                @endif
                                
                                @if($autor->whatsapp)
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.787"/>
                                    </svg>
                                    <span>{{ $autor->whatsapp }}</span>
                                </div>
                                @endif
                            </div>
                            
                            @if($autor->biografia)
                            <div>
                                <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Biografia</h5>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $autor->biografia }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Preview Novo -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Preview das Alterações</h3>
                        
                        <div class="text-center mb-6">
                            <div class="mx-auto h-20 w-20 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center mb-4" id="preview-avatar">
                                @if($autor->avatar)
                                    <img src="{{ asset('storage/' . $autor->avatar) }}" class="h-20 w-20 rounded-full object-cover">
                                @else
                                    <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                @endif
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div>
                                <h4 class="text-base font-semibold text-gray-900 dark:text-white" id="preview-nome">{{ $autor->nome }}</h4>
                            </div>
                            
                            <div class="space-y-2">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span id="preview-email">{{ $autor->email }}</span>
                                </div>
                                
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400" id="preview-telefone-container" style="{{ $autor->telefone ? 'display: flex;' : 'display: none;' }}">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span id="preview-telefone">{{ $autor->telefone }}</span>
                                </div>
                                
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400" id="preview-whatsapp-container" style="{{ $autor->whatsapp ? 'display: flex;' : 'display: none;' }}">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.787"/>
                                    </svg>
                                    <span id="preview-whatsapp">{{ $autor->whatsapp }}</span>
                                </div>
                            </div>
                            
                            <div id="preview-biografia-container" style="{{ $autor->biografia ? 'display: block;' : 'display: none;' }}">
                                <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Biografia</h5>
                                <p class="text-sm text-gray-600 dark:text-gray-400" id="preview-biografia">{{ $autor->biografia }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview').src = e.target.result;
            document.getElementById('preview-avatar').innerHTML = `<img src="${e.target.result}" class="h-20 w-20 rounded-full object-cover">`;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function applyPhoneMask(input) {
    let value = input.value.replace(/\D/g, '');
    
    if (value.length <= 11) {
        value = value.replace(/(\d{2})(\d)/, '($1) $2');
        value = value.replace(/(\d{4,5})(\d{4})$/, '$1-$2');
    }
    
    input.value = value;
}

function updatePreview() {
    const nome = document.getElementById('nome').value;
    const email = document.getElementById('email').value;
    const telefone = document.getElementById('telefone').value;
    const whatsapp = document.getElementById('whatsapp').value;
    const biografia = document.getElementById('biografia').value;
    
    document.getElementById('preview-nome').textContent = nome;
    document.getElementById('preview-email').textContent = email;
    
    // Telefone
    if (telefone) {
        document.getElementById('preview-telefone').textContent = telefone;
        document.getElementById('preview-telefone-container').style.display = 'flex';
    } else {
        document.getElementById('preview-telefone-container').style.display = 'none';
    }
    
    // WhatsApp
    if (whatsapp) {
        document.getElementById('preview-whatsapp').textContent = whatsapp;
        document.getElementById('preview-whatsapp-container').style.display = 'flex';
    } else {
        document.getElementById('preview-whatsapp-container').style.display = 'none';
    }
    
    // Biografia
    if (biografia) {
        document.getElementById('preview-biografia').textContent = biografia;
        document.getElementById('preview-biografia-container').style.display = 'block';
    } else {
        document.getElementById('preview-biografia-container').style.display = 'none';
    }
}

// Inicializar preview na carga da página
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
});
</script>
@endsection