/**
 * File Upload Component with Drag & Drop
 * Handles file uploads to S3 with preview and management
 */
class FileUpload {
    constructor(options = {}) {
        // Default options
        this.options = {
            uploadUrl: '/api/financial/files/upload',
            uploadTempUrl: '/api/financial/files/upload-temp',
            getFilesUrl: '/api/financial/files/transaction/',
            getTempFilesUrl: '/api/financial/files/temp/',
            deleteUrl: '/api/financial/files/',
            deleteTempUrl: '/api/financial/files/temp/',
            downloadUrl: '/api/financial/files/',
            moveTempUrl: '/api/financial/files/move-temp-to-transaction',
            maxFileSize: 10 * 1024 * 1024, // 10MB
            allowedTypes: ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
            transactionId: null,
            ...options
        };
        
        // Detectar se estamos no create ou edit mode - mais conservador
        this.isCreateMode = this.options.transactionId === 'new' || this.options.transactionId === null || this.options.transactionId === undefined;
        this.tempId = this.isCreateMode ? this.generateTempId() : null;
        
        console.log('FileUpload mode:', this.isCreateMode ? 'CREATE' : 'EDIT');
        console.log('Transaction ID:', this.options.transactionId);
        console.log('Temp ID:', this.tempId);
        
        this.container = document.querySelector(this.options.container);
        this.files = [];
        
        if (this.container) {
            this.init();
        }
    }
    
    init() {
        this.createUploadArea();
        this.bindEvents();
        
        if (this.isCreateMode) {
            // No create mode, carregar arquivos temporários se existirem
            this.loadExistingTempFiles();
        } else if (this.options.transactionId) {
            // No edit mode, carregar arquivos da transação
            this.loadExistingFiles();
        }
    }
    
    generateTempId() {
        return 'temp_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }
    
    createUploadArea() {
        this.container.innerHTML = `
            <div class="file-upload-wrapper">
                <!-- Upload Area -->
                <div class="upload-area border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors cursor-pointer" id="upload-area">
                    <div class="upload-icon mb-4">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="upload-text">
                        <p class="text-lg font-medium text-gray-900 mb-2">Arraste arquivos aqui ou clique para selecionar</p>
                        <p class="text-sm text-gray-500">Máximo 10MB por arquivo. Formatos: PDF, DOC, XLS, JPG, PNG</p>
                    </div>
                    <input type="file" id="file-input" class="hidden" multiple accept="${this.options.allowedTypes.join(',')}">
                </div>
                
                <!-- Progress Bar -->
                <div class="upload-progress hidden mt-4">
                    <div class="bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%" id="progress-bar"></div>
                    </div>
                    <p class="text-sm text-gray-600 mt-2" id="progress-text">Enviando arquivos...</p>
                </div>
                
                <!-- Files List -->
                <div class="files-list mt-6" id="files-list">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Arquivos Anexados</h4>
                    <div class="files-grid grid gap-4" id="files-grid">
                        <!-- Files will be loaded here -->
                    </div>
                </div>
            </div>
        `;
    }
    
    bindEvents() {
        const uploadArea = this.container.querySelector('#upload-area');
        const fileInput = this.container.querySelector('#file-input');
        
        // Click to select files
        uploadArea.addEventListener('click', () => {
            fileInput.click();
        });
        
        // File input change
        fileInput.addEventListener('change', (e) => {
            this.handleFiles(e.target.files);
        });
        
        // Drag and drop events
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('border-blue-400', 'bg-blue-50');
        });
        
        uploadArea.addEventListener('dragleave', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
        });
        
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
            this.handleFiles(e.dataTransfer.files);
        });
    }
    
    handleFiles(fileList) {
        const files = Array.from(fileList);
        
        // Validate files
        const validFiles = files.filter(file => this.validateFile(file));
        
        if (validFiles.length > 0) {
            this.uploadFiles(validFiles);
        }
    }
    
    validateFile(file) {
        // Check file size
        if (file.size > this.options.maxFileSize) {
            this.showError(`Arquivo "${file.name}" é muito grande. Máximo ${this.formatFileSize(this.options.maxFileSize)}.`);
            return false;
        }
        
        // Check file type
        const allowedTypes = this.options.allowedTypes;
        const isValidType = allowedTypes.some(type => {
            if (type.includes('*')) {
                return file.type.startsWith(type.replace('*', ''));
            }
            return file.type === type || file.name.toLowerCase().endsWith(type);
        });
        
        if (!isValidType) {
            this.showError(`Tipo de arquivo "${file.name}" não permitido.`);
            return false;
        }
        
        return true;
    }
    
    async uploadFiles(files) {
        if (!this.isCreateMode && !this.options.transactionId) {
            this.showError('ID da transação não informado.');
            return;
        }
        
        // Check if user is authenticated first
        try {
            const authCheck = await fetch('/api/auth/check');
            if (!authCheck.ok || authCheck.redirected) {
                this.showError('Usuário não autenticado. Por favor, faça login novamente.');
                window.location.href = '/login';
                return;
            }
        } catch (error) {
            this.showError('Erro ao verificar autenticação. Tente novamente.');
            return;
        }
        
        const progressContainer = this.container.querySelector('.upload-progress');
        const progressBar = this.container.querySelector('#progress-bar');
        const progressText = this.container.querySelector('#progress-text');
        
        progressContainer.classList.remove('hidden');
        
        let uploadedCount = 0;
        const totalFiles = files.length;
        
        for (const file of files) {
                try {
                    const formData = new FormData();
                    formData.append('file', file);
                    
                    let uploadUrl;
                    if (this.isCreateMode) {
                        // Upload temporário
                        formData.append('temp_id', this.tempId);
                        uploadUrl = this.options.uploadTempUrl;
                    } else {
                        // Upload normal com transaction_id
                        console.log('Transaction ID original (this.options.transactionId):', this.options.transactionId);
                        console.log('Tipo do this.options.transactionId:', typeof this.options.transactionId);
                        
                        // Usar o transactionId diretamente sem conversão
                        formData.append('transaction_id', this.options.transactionId);
                        uploadUrl = this.options.uploadUrl;
                    }
                    
                    formData.append('description', '');
                
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (!csrfToken) {
                    throw new Error('CSRF token não encontrado');
                }
                
                const response = await fetch(uploadUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                
                // Check if response is ok
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Upload error response:', errorText);
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const result = await response.json();
                
                if (result.success) {
                    this.files.push(result.file);
                    uploadedCount++;
                    
                    // Update progress
                    const progress = (uploadedCount / totalFiles) * 100;
                    progressBar.style.width = `${progress}%`;
                    progressText.textContent = `Enviado ${uploadedCount} de ${totalFiles} arquivos`;
                    
                    this.showSuccess(`Arquivo "${file.name}" enviado com sucesso.`);
                } else {
                    this.showError(`Erro ao enviar "${file.name}": ${result.message}`);
                }
                
            } catch (error) {
                this.showError(`Erro ao enviar "${file.name}": ${error.message}`);
            }
        }
        
        // Hide progress bar
        setTimeout(() => {
            progressContainer.classList.add('hidden');
            progressBar.style.width = '0%';
        }, 1000);
        
        // Refresh files list
        this.renderFiles();
    }
    
    async loadExistingFiles() {
        try {
            const response = await fetch(`${this.options.getFilesUrl}${this.options.transactionId}`);
            const result = await response.json();
            
            if (result.success) {
                this.files = result.files;
                this.renderFiles();
            }
        } catch (error) {
            console.error('Erro ao carregar arquivos:', error);
        }
    }
    
    async loadExistingTempFiles() {
        try {
            const response = await fetch(`${this.options.getTempFilesUrl}${this.tempId}`);
            const result = await response.json();
            
            if (result.success) {
                this.files = result.files;
                this.renderFiles();
            }
        } catch (error) {
            console.error('Erro ao carregar arquivos temporários:', error);
        }
    }
    
    async moveTempFilesToTransaction(transactionId) {
        if (!this.isCreateMode || !this.tempId) {
            return;
        }
        
        try {
            const response = await fetch(this.options.moveTempUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    temp_id: this.tempId,
                    transaction_id: transactionId
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                console.log('Arquivos temporários movidos com sucesso');
                this.isCreateMode = false;
                this.options.transactionId = transactionId;
                this.tempId = null;
            } else {
                console.error('Erro ao mover arquivos temporários:', result.message);
            }
        } catch (error) {
            console.error('Erro ao mover arquivos temporários:', error);
        }
    }
    
    renderFiles() {
        const filesGrid = this.container.querySelector('#files-grid');
        const filesList = this.container.querySelector('.files-list');
        
        if (this.files.length === 0) {
            filesList.classList.add('hidden');
            return;
        }
        
        filesList.classList.remove('hidden');
        
        filesGrid.innerHTML = this.files.map(file => `
            <div class="file-item bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-3 flex-1">
                        <div class="file-icon">
                            ${this.getFileIcon(file.type)}
                        </div>
                        <div class="file-info flex-1 min-w-0">
                            <h5 class="text-sm font-medium text-gray-900 truncate" title="${file.name}">
                                ${file.name}
                            </h5>
                            <p class="text-xs text-gray-500 mt-1">
                                ${file.size} • ${file.created_at}
                            </p>
                        </div>
                    </div>
                    <div class="file-actions flex items-center space-x-2 ml-4">
                        ${file.is_image ? `<button onclick="fileUpload.previewFile('${file.url}', '${file.name}')" class="text-blue-600 hover:text-blue-800" title="Visualizar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>` : ''}
                        <a href="${this.options.downloadUrl}${file.id}/download" class="text-green-600 hover:text-green-800" title="Download">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </a>
                        <button onclick="fileUpload.deleteFile(${file.id})" class="text-red-600 hover:text-red-800" title="Excluir">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    getFileIcon(type) {
        if (!type) return this.getDefaultIcon();
        
        if (type.startsWith('image/')) {
            return '<svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>';
        }
        
        if (type === 'application/pdf') {
            return '<svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>';
        }
        
        if (type.includes('word') || type.includes('document')) {
            return '<svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
        }
        
        if (type.includes('excel') || type.includes('spreadsheet')) {
            return '<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>';
        }
        
        return this.getDefaultIcon();
    }
    
    getDefaultIcon() {
        return '<svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
    }
    
    async deleteFile(fileId) {
        if (!confirm('Tem certeza que deseja excluir este arquivo?')) {
            return;
        }
        
        try {
            let deleteUrl;
            if (this.isCreateMode) {
                deleteUrl = `${this.options.deleteTempUrl}${fileId}`;
            } else {
                deleteUrl = `${this.options.deleteUrl}${fileId}`;
            }
            
            const response = await fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.files = this.files.filter(file => file.id !== fileId);
                this.renderFiles();
                this.showSuccess('Arquivo excluído com sucesso.');
            } else {
                this.showError(result.message);
            }
        } catch (error) {
            this.showError('Erro ao excluir arquivo: ' + error.message);
        }
    }
    
    previewFile(url, filename) {
        // Create modal for image preview
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="max-w-4xl max-h-full p-4">
                <div class="bg-white rounded-lg overflow-hidden">
                    <div class="flex justify-between items-center p-4 border-b">
                        <h3 class="text-lg font-medium">${filename}</h3>
                        <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="p-4">
                        <img src="${url}" alt="${filename}" class="max-w-full max-h-96 mx-auto">
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Close on click outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });
    }
    
    formatFileSize(bytes) {
        const units = ['B', 'KB', 'MB', 'GB'];
        let size = bytes;
        let unitIndex = 0;
        
        while (size >= 1024 && unitIndex < units.length - 1) {
            size /= 1024;
            unitIndex++;
        }
        
        return `${Math.round(size * 100) / 100} ${units[unitIndex]}`;
    }
    
    showSuccess(message) {
        this.showToast(message, 'success');
    }
    
    showError(message) {
        this.showToast(message, 'error');
    }
    
    showToast(message, type = 'info') {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;
        
        if (type === 'success') {
            toast.classList.add('bg-green-500', 'text-white');
        } else if (type === 'error') {
            toast.classList.add('bg-red-500', 'text-white');
        } else {
            toast.classList.add('bg-blue-500', 'text-white');
        }
        
        toast.innerHTML = `
            <div class="flex items-center space-x-2">
                <span>${message}</span>
                <button onclick="this.closest('.fixed').remove()" class="ml-2 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 300);
        }, 5000);
    }
}

// Global instance for easy access
let fileUpload = null;

// Initialize FileUpload when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing FileUpload...');
    
    const container = document.querySelector('#file-upload-container');
    if (container) {
        console.log('Container found:', container);
        console.log('Dataset:', container.dataset);
        
        const options = {
            container: '#file-upload-container',
            transactionId: container.dataset.transactionId
        };
        
        console.log('FileUpload options:', options);
        
        window.fileUploadInstance = new FileUpload(options);
        window.fileUploadInstance.init();
        
        console.log('FileUpload initialized successfully');
    } else {
        console.log('File upload container not found');
    }
});

// Função global para mover arquivos temporários após salvar transação
window.moveTemporaryFilesToTransaction = function(transactionId) {
    if (window.fileUploadInstance && window.fileUploadInstance.isCreateMode) {
        return window.fileUploadInstance.moveTempFilesToTransaction(transactionId);
    }
    return Promise.resolve();
};