@extends('layouts.app')

@section('title', 'Upload de Arquivo')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header -->
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center mb-6">
            <a href="{{ route('files.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Upload de Arquivo</h1>
                <p class="text-gray-600 mt-1">Faça upload de seus arquivos de forma segura</p>
            </div>
        </div>
    </div>

    <!-- Main Upload Area -->
    <div class="container mx-auto px-4 pb-6">
        <div class="max-w-7xl mx-auto">
            <form id="uploadForm" action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Full Height Drag and Drop Area -->
                <div id="dropZone" class="relative min-h-[calc(100vh-200px)] bg-white rounded-2xl border-2 border-dashed border-gray-200 hover:border-blue-400 overflow-hidden group">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-5">
                        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, #3b82f6 2px, transparent 2px), radial-gradient(circle at 75% 75%, #8b5cf6 2px, transparent 2px); background-size: 50px 50px;"></div>
                    </div>
                    

                    
                    <div id="dropContent" class="relative z-10 flex flex-col items-center justify-center h-full min-h-[calc(100vh-200px)] p-12">
                        <!-- Large Upload Icon -->
                        <div class="mb-8 relative">
                            <div class="relative bg-gradient-to-r from-blue-500 to-purple-600 rounded-full p-8">
                                <i class="fas fa-cloud-upload-alt text-8xl text-white"></i>
                            </div>
                        </div>
                        
                        <!-- Main Heading -->
                        <h2 class="text-4xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-4 text-center">
                            Arraste e solte seus arquivos aqui
                        </h2>
                        
                        <!-- Subtitle -->
                        <p class="text-xl text-gray-500 mb-8 text-center max-w-md">
                            Compartilhe seus arquivos de forma rápida e segura
                        </p>
                        
                        <!-- Divider -->
                        <div class="flex items-center mb-8 w-full max-w-xs">
                            <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                            <span class="px-4 text-gray-400 font-medium">ou</span>
                            <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                        </div>
                        
                        <!-- Select Files Button -->
                        <button type="button" id="selectFiles" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-12 py-4 rounded-2xl font-semibold text-lg">
                            <span class="flex items-center">
                                <i class="fas fa-folder-open mr-3 text-xl"></i>
                                Selecionar Arquivos
                            </span>
                        </button>
                        
                        <input type="file" id="fileInput" name="files[]" multiple class="hidden" accept="*/*">
                        
                        <!-- File Size Info -->
                        <div class="mt-8 flex items-center space-x-6 text-sm text-gray-400">
                            <div class="flex items-center">
                                <i class="fas fa-shield-alt mr-2 text-green-500"></i>
                                <span>100% Seguro</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-infinity mr-2 text-blue-500"></i>
                                <span>Sem limites</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-2 text-purple-500"></i>
                                <span>Máx: 1GB por arquivo</span>
                            </div>
                        </div>
                        
                        <!-- Drag Indicator -->
                        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100">
                            <div class="flex items-center space-x-2 text-blue-500">
                                <i class="fas fa-mouse-pointer"></i>
                                <span class="text-sm font-medium">Solte os arquivos aqui</span>
                            </div>
                        </div>
                    </div>
                
                    <!-- Upload Progress -->
                    <div id="uploadProgress" class="hidden relative z-10 flex flex-col items-center justify-center h-full min-h-[calc(100vh-200px)] p-12">
                        <!-- Progress Icon -->
                        <div class="mb-8 relative">
                            <div class="relative bg-gradient-to-r from-blue-500 to-purple-600 rounded-full p-8">
                                <i class="fas fa-spinner fa-spin text-8xl text-white"></i>
                            </div>
                        </div>
                        
                        <h3 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-6">Fazendo upload...</h3>
                        
                        <!-- Progress Bar -->
                        <div class="w-full max-w-md mb-4">
                            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                <div id="progressBar" class="bg-gradient-to-r from-blue-500 to-purple-600 h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                        </div>
                        
                        <!-- Progress Details -->
                        <div class="text-center mb-4">
                            <p id="progressText" class="text-2xl text-gray-800 font-bold mb-2">0%</p>
                            <p id="uploadSpeed" class="text-sm text-gray-600">0 MB / 0 MB</p>
                        </div>
                        
                        <!-- Upload Animation -->
                        <div class="mt-8 flex space-x-2">
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                            <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        </div>
                    </div>
                </div>
            
                <!-- Selected Files Preview -->
                <div id="selectedFiles" class="hidden mb-6">
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                            <h3 class="text-xl font-bold text-gray-800">Arquivos Selecionados</h3>
                        </div>
                        <div id="filesList" class="p-6 space-y-4 max-h-96 overflow-y-auto"></div>
                    </div>
                </div>
            
                <!-- File Details Form -->
                <div id="fileDetailsForm" class="hidden">
                    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden max-w-7xl mx-auto">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                            <h3 class="text-xl font-bold text-gray-800">Detalhes dos Arquivos</h3>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <!-- Category Selection -->
                            <div>
                                <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Categoria
                                </label>
                                <select name="category_id" id="category_id" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 hover:bg-white">
                                    <option value="">Selecione uma categoria</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Descrição (opcional)
                                </label>
                                <textarea name="description" id="description" rows="4" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 hover:bg-white resize-none"
                                          placeholder="Adicione uma descrição para seus arquivos..."></textarea>
                                @error('description')
                                    <div class="text-red-500 text-sm mt-2 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-4 p-6 bg-gray-50 border-t border-gray-100">
                            <button type="button" id="cancelUpload" 
                                    class="px-6 py-3 text-sm font-semibold text-gray-700 bg-white hover:bg-gray-100 border border-gray-300 rounded-xl">
                                <i class="fas fa-times mr-2"></i>Cancelar
                            </button>
                            <button type="submit" id="uploadButton" 
                                    class="px-8 py-3 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 rounded-xl">
                                <i class="fas fa-upload mr-2"></i>Fazer Upload
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
class FileUploader {
    constructor() {
        this.dropZone = document.getElementById('dropZone');
        this.fileInput = document.getElementById('fileInput');
        this.selectFilesBtn = document.getElementById('selectFiles');
        this.selectedFiles = document.getElementById('selectedFiles');
        this.filesList = document.getElementById('filesList');
        this.fileDetailsForm = document.getElementById('fileDetailsForm');
        this.uploadForm = document.getElementById('uploadForm');
        this.uploadProgress = document.getElementById('uploadProgress');
        this.dropContent = document.getElementById('dropContent');
        this.progressBar = document.getElementById('progressBar');
        this.progressText = document.getElementById('progressText');
        this.cancelBtn = document.getElementById('cancelUpload');
        
        this.files = [];
        this.maxFileSize = 1024 * 1024 * 1024; // 1GB
        
        this.initEventListeners();
    }
    
    initEventListeners() {
        // Drag and drop events
        this.dropZone.addEventListener('dragover', this.handleDragOver.bind(this));
        this.dropZone.addEventListener('dragleave', this.handleDragLeave.bind(this));
        this.dropZone.addEventListener('drop', this.handleDrop.bind(this));
        
        // File selection
        this.selectFilesBtn.addEventListener('click', () => this.fileInput.click());
        this.fileInput.addEventListener('change', this.handleFileSelect.bind(this));
        
        // Form submission
        this.uploadForm.addEventListener('submit', this.handleSubmit.bind(this));
        
        // Cancel upload
        this.cancelBtn.addEventListener('click', this.resetForm.bind(this));
    }
    
    handleDragOver(e) {
        e.preventDefault();
        this.dropZone.classList.add('border-blue-400', 'bg-blue-50');
    }
    
    handleDragLeave(e) {
        e.preventDefault();
        this.dropZone.classList.remove('border-blue-400', 'bg-blue-50');
    }
    
    handleDrop(e) {
        e.preventDefault();
        this.dropZone.classList.remove('border-blue-400', 'bg-blue-50');
        
        const files = Array.from(e.dataTransfer.files);
        this.processFiles(files);
    }
    
    handleFileSelect(e) {
        const files = Array.from(e.target.files);
        this.processFiles(files);
    }
    
    processFiles(files) {
        // Validate files
        const validFiles = [];
        const errors = [];
        
        files.forEach(file => {
            if (file.size > this.maxFileSize) {
                errors.push(`${file.name}: Arquivo muito grande (máximo 1GB)`);
            } else {
                validFiles.push(file);
            }
        });
        
        if (errors.length > 0) {
            alert('Erros encontrados:\n' + errors.join('\n'));
        }
        
        if (validFiles.length > 0) {
            this.files = validFiles;
            this.displaySelectedFiles();
            this.showFileDetailsForm();
        }
    }
    
    displaySelectedFiles() {
        this.filesList.innerHTML = '';
        
        this.files.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-md';
            
            // Verificar se é uma imagem
            const isImage = file.type.startsWith('image/');
            let fileIcon = '<i class="fas fa-file text-gray-400 mr-3"></i>';
            let imagePreview = '';
            
            if (isImage) {
                const imageUrl = URL.createObjectURL(file);
                fileIcon = `<img src="${imageUrl}" alt="${file.name}" class="w-12 h-12 object-cover rounded mr-3">`;
                imagePreview = `
                    <div class="mt-2 w-full">
                        <img src="${imageUrl}" alt="${file.name}" class="w-full h-32 object-cover rounded-md">
                    </div>
                `;
            }
            
            fileItem.innerHTML = `
                <div class="flex items-start w-full">
                    <div class="flex items-center flex-1">
                        ${fileIcon}
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">${file.name}</p>
                            <p class="text-sm text-gray-500">${this.formatFileSize(file.size)}</p>
                            ${imagePreview}
                        </div>
                    </div>
                    <button type="button" onclick="fileUploader.removeFile(${index})" 
                            class="text-red-600 hover:text-red-800 ml-3">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            this.filesList.appendChild(fileItem);
        });
        
        this.selectedFiles.classList.remove('hidden');
    }
    
    removeFile(index) {
        this.files.splice(index, 1);
        
        if (this.files.length === 0) {
            this.resetForm();
        } else {
            this.displaySelectedFiles();
        }
    }
    
    showFileDetailsForm() {
        this.fileDetailsForm.classList.remove('hidden');
    }
    
    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    async handleSubmit(e) {
        e.preventDefault();
        
        if (this.files.length === 0) {
            alert('Selecione pelo menos um arquivo.');
            return;
        }
        
        // Show progress
        this.showProgress();
        
        // Create FormData
        const formData = new FormData();
        
        // Add CSRF token
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        
        // Add files
        this.files.forEach(file => {
            formData.append('files[]', file);
        });
        
        // Add other form data
        const categoryId = document.getElementById('category_id').value;
        const description = document.getElementById('description').value;
        
        if (categoryId) formData.append('category_id', categoryId);
        if (description) formData.append('description', description);
        
        // Use XMLHttpRequest for real progress tracking
        this.uploadWithProgress(formData);
    }
    
    uploadWithProgress(formData) {
        const xhr = new XMLHttpRequest();
        
        // Track upload progress
        xhr.upload.addEventListener('progress', (e) => {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                this.updateProgress(percentComplete);
                this.updateProgressDetails(e.loaded, e.total);
            }
        });
        
        // Handle completion
        xhr.addEventListener('load', () => {
            if (xhr.status === 200) {
                try {
                    const result = JSON.parse(xhr.responseText);
                    // Success
                    this.updateProgress(100);
                    this.showSuccessMessage();
                    setTimeout(() => {
                        window.location.href = '{{ route("files.index") }}';
                    }, 1500);
                } catch (error) {
                    this.hideProgress();
                    alert('Erro ao processar resposta do servidor.');
                }
            } else {
                this.hideProgress();
                try {
                    const result = JSON.parse(xhr.responseText);
                    alert('Erro no upload: ' + (result.message || 'Erro desconhecido'));
                } catch (error) {
                    alert('Erro no upload: Erro do servidor (' + xhr.status + ')');
                }
            }
        });
        
        // Handle errors
        xhr.addEventListener('error', () => {
            this.hideProgress();
            alert('Erro de conexão durante o upload.');
        });
        
        // Handle abort
        xhr.addEventListener('abort', () => {
            this.hideProgress();
            alert('Upload cancelado.');
        });
        
        // Store xhr reference for potential cancellation
        this.currentXhr = xhr;
        
        // Start upload
        xhr.open('POST', this.uploadForm.action);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.send(formData);
    }
    
    showProgress() {
        this.dropContent.classList.add('hidden');
        this.uploadProgress.classList.remove('hidden');
        this.updateProgress(0);
        this.updateProgressDetails(0, 0);
    }
    
    updateProgress(percent) {
        this.progressBar.style.width = percent + '%';
        this.progressText.textContent = Math.round(percent) + '%';
    }
    
    updateProgressDetails(loaded, total) {
        if (total > 0) {
            const loadedMB = (loaded / (1024 * 1024)).toFixed(1);
            const totalMB = (total / (1024 * 1024)).toFixed(1);
            const speedElement = document.getElementById('uploadSpeed');
            if (speedElement) {
                speedElement.textContent = `${loadedMB} MB / ${totalMB} MB`;
            }
        }
    }
    
    showSuccessMessage() {
        const progressIcon = this.uploadProgress.querySelector('.fas');
        const progressTitle = this.uploadProgress.querySelector('h3');
        
        if (progressIcon) {
            progressIcon.className = 'fas fa-check-circle text-8xl text-white';
        }
        if (progressTitle) {
            progressTitle.textContent = 'Upload concluído!';
        }
    }
    
    hideProgress() {
        this.uploadProgress.classList.add('hidden');
        this.dropContent.classList.remove('hidden');
    }
    
    resetForm() {
        // Cancel ongoing upload if exists
        if (this.currentXhr) {
            this.currentXhr.abort();
            this.currentXhr = null;
        }
        
        this.files = [];
        this.fileInput.value = '';
        this.selectedFiles.classList.add('hidden');
        this.fileDetailsForm.classList.add('hidden');
        this.hideProgress();
        document.getElementById('category_id').value = '';
        document.getElementById('description').value = '';
    }
}

// Initialize uploader
const fileUploader = new FileUploader();
</script>
@endpush