@extends('layouts.app')

@section('title', 'Gerenciar Imagens - ' . $orcamento->titulo)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Mensagens de Sucesso e Erro -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('dashboard')],
        ['label' => 'Orçamentos', 'url' => route('orcamentos.index')],
        ['label' => $orcamento->titulo, 'url' => route('orcamentos.show', $orcamento)],
        ['label' => 'Gerenciar Imagens']
    ]" />

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gerenciar Imagens</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $orcamento->titulo }}</p>
        </div>
        <a href="{{ route('orcamentos.show', $orcamento) }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Voltar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- QR Code Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">QR Code</h3>
            
            <!-- Current QR Code -->
            <div class="mb-6">
                @if($orcamento->qrcode_image)
                    <div class="text-center">
                        <img src="{{ Storage::url($orcamento->qrcode_image) }}" 
                             alt="QR Code" 
                             class="mx-auto max-w-48 max-h-48 rounded-lg border border-gray-200 dark:border-gray-600">
                        <div class="mt-3">
                            <button onclick="deleteQrCode()" 
                                    class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Remover
                            </button>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Nenhum QR Code carregado</p>
                    </div>
                @endif
            </div>

            <!-- Upload QR Code -->
            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-gray-400 dark:hover:border-gray-500 transition-colors"
                 id="qrcode-dropzone"
                 ondrop="handleDrop(event, 'qrcode')" 
                 ondragover="handleDragOver(event)" 
                 ondragleave="handleDragLeave(event)">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <div class="mt-4">
                    <label for="qrcode-file" class="cursor-pointer">
                        <span class="mt-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Clique para fazer upload ou arraste o arquivo aqui
                        </span>
                        <span class="mt-1 block text-xs text-gray-500 dark:text-gray-400">
                            PNG, JPG até 2MB
                        </span>
                    </label>
                    <input id="qrcode-file" name="qrcode" type="file" class="sr-only" accept="image/png,image/jpeg,image/jpg" onchange="handleFileSelect(event, 'qrcode')">
                </div>
            </div>
        </div>

        <!-- Logo Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Logo</h3>
            
            <!-- Current Logo -->
            <div class="mb-6">
                @if($orcamento->logo_image)
                    <div class="text-center">
                        <img src="{{ Storage::url($orcamento->logo_image) }}" 
                             alt="Logo" 
                             class="mx-auto max-w-48 max-h-48 rounded-lg border border-gray-200 dark:border-gray-600">
                        <div class="mt-3">
                            <button onclick="deleteLogo()" 
                                    class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Remover
                            </button>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Nenhum logo carregado</p>
                    </div>
                @endif
            </div>

            <!-- Upload Logo -->
            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-gray-400 dark:hover:border-gray-500 transition-colors"
                 id="logo-dropzone"
                 ondrop="handleDrop(event, 'logo')" 
                 ondragover="handleDragOver(event)" 
                 ondragleave="handleDragLeave(event)">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <div class="mt-4">
                    <label for="logo-file" class="cursor-pointer">
                        <span class="mt-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Clique para fazer upload ou arraste o arquivo aqui
                        </span>
                        <span class="mt-1 block text-xs text-gray-500 dark:text-gray-400">
                            PNG, JPG até 2MB
                        </span>
                    </label>
                    <input id="logo-file" name="logo" type="file" class="sr-only" accept="image/png,image/jpeg,image/jpg" onchange="handleFileSelect(event, 'logo')">
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 flex items-center space-x-4">
            <svg class="animate-spin h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-900 dark:text-white">Fazendo upload...</span>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // CSRF Token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Drag and Drop handlers
    function handleDragOver(e) {
        e.preventDefault();
        e.currentTarget.classList.add('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
    }
    
    function handleDragLeave(e) {
        e.preventDefault();
        e.currentTarget.classList.remove('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
    }
    
    function handleDrop(e, type) {
        e.preventDefault();
        e.currentTarget.classList.remove('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/20');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            uploadFile(files[0], type);
        }
    }
    
    function handleFileSelect(e, type) {
        const file = e.target.files[0];
        if (file) {
            uploadFile(file, type);
        }
    }
    
    function uploadFile(file, type) {
        // Validate file
        if (!validateFile(file)) {
            return;
        }
        
        const formData = new FormData();
        formData.append(type, file);
        formData.append('_token', csrfToken);
        
        const url = type === 'qrcode' 
            ? `{{ route('orcamentos.upload-qrcode', $orcamento) }}`
            : `{{ route('orcamentos.upload-logo', $orcamento) }}`;
        
        showLoading();
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showSuccess(data.message);
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showError(data.message || 'Erro ao fazer upload');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showError('Erro ao fazer upload');
        });
    }
    
    function validateFile(file) {
        // Check file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            showError('Apenas arquivos JPG e PNG são permitidos');
            return false;
        }
        
        // Check file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            showError('O arquivo deve ter no máximo 2MB');
            return false;
        }
        
        return true;
    }
    
    function deleteQrCode() {
        if (!confirm('Tem certeza que deseja remover o QR Code?')) {
            return;
        }
        
        showLoading();
        
        fetch(`{{ route('orcamentos.delete-qrcode', $orcamento) }}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showSuccess(data.message);
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showError(data.message || 'Erro ao remover QR Code');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showError('Erro ao remover QR Code');
        });
    }
    
    function deleteLogo() {
        if (!confirm('Tem certeza que deseja remover o logo?')) {
            return;
        }
        
        showLoading();
        
        fetch(`{{ route('orcamentos.delete-logo', $orcamento) }}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showSuccess(data.message);
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showError(data.message || 'Erro ao remover logo');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showError('Erro ao remover logo');
        });
    }
    
    function showLoading() {
        document.getElementById('loading-overlay').classList.remove('hidden');
    }
    
    function hideLoading() {
        document.getElementById('loading-overlay').classList.add('hidden');
    }
    
    function showSuccess(message) {
        // Create success notification
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
    
    function showError(message) {
        // Create error notification
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }
</script>
@endpush
@endsection