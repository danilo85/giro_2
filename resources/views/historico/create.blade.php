@extends('layouts.app')

@section('title', 'Nova Entrada - Histórico do Projeto')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nova Entrada no Histórico</h1>
                <p class="text-gray-600 dark:text-gray-400">{{ $orcamento->titulo }}</p>
            </div>
            <a href="{{ route('orcamentos.historico.index', $orcamento) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Voltar ao Histórico
            </a>
        </div>
    </div>

    <!-- Formulário -->
    <div class="max-w-4xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                    <i class="fas fa-plus-circle mr-2"></i> Adicionar Nova Entrada
                </h2>
            </div>
            <div class="p-6">
                    <form action="{{ route('orcamentos.historico.store', $orcamento) }}" method="POST" enctype="multipart/form-data" id="historico-form">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Título -->
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Título <span class="text-red-500">*</span></label>
                                <input type="text" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('title') border-red-500 @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" 
                                       placeholder="Ex: Reunião com cliente, Marco importante, etc." required>
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tipo -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo <span class="text-red-500">*</span></label>
                                <select class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('type') border-red-500 @enderror" id="type" name="type" required>
                                    <option value="">Selecione o tipo</option>
                                    <option value="manual" {{ old('type') == 'manual' ? 'selected' : '' }}>Manual</option>
                                    <option value="system" {{ old('type') == 'system' ? 'selected' : '' }}>Sistema</option>
                                    <option value="status_change" {{ old('type') == 'status_change' ? 'selected' : '' }}>Mudança de Status</option>
                                    <option value="payment" {{ old('type') == 'payment' ? 'selected' : '' }}>Pagamento</option>
                                    <option value="project_start" {{ old('type') == 'project_start' ? 'selected' : '' }}>Início de Projeto</option>
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Data -->
                        <div class="mt-6">
                            <div class="max-w-md">
                                <label for="entry_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Data e Hora <span class="text-red-500">*</span></label>
                                <input type="datetime-local" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('entry_date') border-red-500 @enderror" 
                                       id="entry_date" name="entry_date" 
                                       value="{{ old('entry_date', now()->format('Y-m-d\TH:i')) }}" required>
                                @error('entry_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Descrição -->
                        <div class="mt-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descrição <span class="text-red-500">*</span></label>
                            <textarea class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('description') border-red-500 @enderror" 
                                      id="description" name="description" rows="5" 
                                      placeholder="Descreva detalhadamente o que aconteceu, decisões tomadas, próximos passos, etc." required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Seja específico e detalhado para facilitar o acompanhamento futuro do projeto.</p>
                        </div>

                        <!-- Upload de Arquivos -->
                        <div class="mt-6">
                            <label for="files" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Arquivos Anexos</label>
                            <div class="upload-area border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center cursor-pointer hover:border-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" id="upload-area">
                                <div class="upload-content">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 dark:text-gray-500 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-600 dark:text-gray-400 mb-2">Arraste arquivos aqui ou clique para selecionar</h3>
                                    <p class="text-gray-500 dark:text-gray-400">Máximo 10 arquivos, 20MB cada</p>
                                    <input type="file" class="hidden" id="files" name="files[]" multiple 
                                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.zip,.rar">
                                </div>
                            </div>
                            @error('files')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            @error('files.*')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            
                            <!-- Preview dos arquivos selecionados -->
                            <div id="files-preview" class="mt-4 hidden">
                                <h4 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-3">Arquivos Selecionados:</h4>
                                <div id="files-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3"></div>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="flex flex-col sm:flex-row sm:justify-between gap-4 mt-8">
                            <a href="{{ route('orcamentos.historico.index', $orcamento) }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <i class="fas fa-times mr-2"></i> Cancelar
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors" id="submit-btn">
                                <i class="fas fa-save mr-2"></i> Salvar Entrada
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.upload-area.dragover {
    @apply border-blue-400 bg-blue-50 dark:bg-blue-900/20;
}

.file-preview {
    @apply relative border border-gray-200 dark:border-gray-600 rounded-lg p-3 bg-gray-50 dark:bg-gray-700;
}

.file-preview .remove-file {
    @apply absolute -top-2 -right-2 w-5 h-5 rounded-full bg-red-500 text-white border-none text-xs cursor-pointer flex items-center justify-center hover:bg-red-600 transition-colors;
}

.file-info {
    @apply flex items-center;
}

.file-icon {
    @apply text-2xl mr-2;
}

.file-details {
    @apply flex-1;
}

.file-name {
    @apply font-medium mb-1 text-gray-900 dark:text-white;
}

.file-size {
    @apply text-sm text-gray-500 dark:text-gray-400;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('upload-area');
    const fileInput = document.getElementById('files');
    const filesPreview = document.getElementById('files-preview');
    const filesList = document.getElementById('files-list');
    const form = document.getElementById('historico-form');
    const submitBtn = document.getElementById('submit-btn');
    
    let selectedFiles = [];

    // Click para selecionar arquivos
    uploadArea.addEventListener('click', () => {
        fileInput.click();
    });

    // Drag and drop
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        handleFiles(e.dataTransfer.files);
    });

    // Seleção de arquivos
    fileInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });

    function handleFiles(files) {
        const maxFiles = 10;
        const maxSize = 20 * 1024 * 1024; // 20MB
        
        if (selectedFiles.length + files.length > maxFiles) {
            alert(`Máximo de ${maxFiles} arquivos permitidos.`);
            return;
        }

        Array.from(files).forEach(file => {
            if (file.size > maxSize) {
                alert(`O arquivo "${file.name}" excede o tamanho máximo de 20MB.`);
                return;
            }
            
            selectedFiles.push(file);
        });

        updateFilesPreview();
        updateFileInput();
    }

    function updateFilesPreview() {
        if (selectedFiles.length === 0) {
            filesPreview.style.display = 'none';
            return;
        }

        filesPreview.style.display = 'block';
        filesList.innerHTML = '';

        selectedFiles.forEach((file, index) => {
            const fileDiv = document.createElement('div');
            fileDiv.className = 'file-preview';
            
            const isImage = file.type.startsWith('image/');
            const icon = isImage ? 'fas fa-image text-blue-500' : 'fas fa-file text-gray-500';
            
            fileDiv.innerHTML = `
                <button type="button" class="remove-file" onclick="removeFile(${index})">
                    <i class="fas fa-times"></i>
                </button>
                <div class="file-info">
                    <div class="file-icon">
                        <i class="${icon}"></i>
                    </div>
                    <div class="file-details">
                        <div class="file-name">${file.name}</div>
                        <div class="file-size">${formatFileSize(file.size)}</div>
                    </div>
                </div>
            `;
            
            filesList.appendChild(fileDiv);
        });
    }

    function updateFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
    }

    window.removeFile = function(index) {
        selectedFiles.splice(index, 1);
        updateFilesPreview();
        updateFileInput();
    };

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Validação do formulário
    form.addEventListener('submit', function(e) {
        const title = document.getElementById('title').value.trim();
        const type = document.getElementById('type').value;
        const description = document.getElementById('description').value.trim();
        const entryDate = document.getElementById('entry_date').value;

        if (!title || !type || !description || !entryDate) {
            e.preventDefault();
            alert('Por favor, preencha todos os campos obrigatórios.');
            return;
        }

        // Desabilitar botão para evitar duplo envio
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
    });
});
</script>
@endpush