/**
 * Orcamento File Upload Component with Drag & Drop
 * Handles file uploads for budget module with categories (anexo, avatar, logo)
 */
class OrcamentoFileUpload {
    constructor(options = {}) {
        // Default options
        this.options = {
            uploadUrl: '/api/budget/orcamentos/{orcamento_id}/files/upload',
            getFilesUrl: '/api/budget/orcamentos/',
            deleteUrl: '/api/budget/orcamentos/files/',
            downloadUrl: '/api/budget/orcamentos/files/',
            maxFileSize: 10 * 1024 * 1024, // 10MB
            allowedTypes: ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
            orcamentoId: null,
            categoria: 'anexo', // anexo, avatar, logo
            ...options
        };
        
        this.container = document.getElementById(this.options.containerId || 'file-upload-container');
        if (!this.container) {
            console.error('Container element not found');
            return;
        }
        
        this.init();
    }
    
    init() {
        this.createUploadArea();
        this.bindEvents();
        
        if (this.options.orcamentoId) {
            this.loadExistingFiles();
        }
    }
    
    createUploadArea() {
        this.container.innerHTML = `
            <div class="orcamento-file-upload-wrapper">
                <!-- Category Selector -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categoria do Arquivo</label>
                    <select id="categoria-select" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="anexo" ${this.options.categoria === 'anexo' ? 'selected' : ''}>Anexo</option>
                        <option value="avatar" ${this.options.categoria === 'avatar' ? 'selected' : ''}>Avatar</option>
                        <option value="logo" ${this.options.categoria === 'logo' ? 'selected' : ''}>Logo</option>
                    </select>
                </div>
                
                <!-- Upload Area -->
                <div class="upload-area border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors cursor-pointer" id="upload-area">
                    <div class="upload-icon mb-4">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <p class="text-lg font-medium text-gray-900 mb-2">Clique para selecionar arquivos</p>
                    <p class="text-sm text-gray-500 mb-4">ou arraste e solte aqui</p>
                    <p class="text-xs text-gray-400">PDF, JPG, PNG, DOC, DOCX, XLS, XLSX até 10MB</p>
                    <input type="file" id="file-input" class="hidden" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
                </div>
                
                <!-- Description Input -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descrição (opcional)</label>
                    <input type="text" id="file-description" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Descreva o arquivo...">
                </div>
                
                <!-- Progress Bar -->
                <div class="upload-progress mt-6 hidden">
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
        const categoriaSelect = this.container.querySelector('#categoria-select');
        
        // Click to select files
        uploadArea.addEventListener('click', () => {
            fileInput.click();
        });
        
        // File input change
        fileInput.addEventListener('change', (e) => {
            this.handleFiles(e.target.files);
        });
        
        // Drag and drop
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
        
        // Category change
        categoriaSelect.addEventListener('change', (e) => {
            this.options.categoria = e.target.value;
            this.loadExistingFiles();
        });
    }
    
    handleFiles(files) {
        const validFiles = Array.from(files).filter(file => this.validateFile(file));
        if (validFiles.length > 0) {
            this.uploadFiles(validFiles);
        }
    }
    
    validateFile(file) {
        // Check file size
        if (file.size > this.options.maxFileSize) {
            this.showError(`Arquivo "${file.name}" é muito grande. Tamanho máximo: 10MB`);
            return false;
        }
        
        // Check file type
        if (!this.options.allowedTypes.includes(file.type)) {
            this.showError(`Tipo de arquivo "${file.name}" não permitido.`);
            return false;
        }
        
        return true;
    }
    
    async uploadFiles(files) {
        if (!this.options.orcamentoId) {
            this.showError('ID do orçamento não informado.');
            return;
        }
        
        const progressContainer = this.container.querySelector('.upload-progress');
        const progressBar = this.container.querySelector('#progress-bar');
        const progressText = this.container.querySelector('#progress-text');
        const descricao = this.container.querySelector('#file-description').value;
        
        progressContainer.classList.remove('hidden');
        
        let uploadedCount = 0;
        const totalFiles = files.length;
        
        for (const file of files) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('categoria', this.options.categoria);
            if (descricao) {
                formData.append('descricao', descricao);
            }
            
            try {
                // Add timeout to prevent hanging requests
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout
                
                const uploadUrl = this.options.uploadUrl.replace('{orcamento_id}', this.options.orcamentoId);
                const response = await fetch(uploadUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    signal: controller.signal
                });
                
                clearTimeout(timeoutId);
                
                const result = await response.json();
                
                if (result.success) {
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
                if (error.name === 'AbortError') {
                    this.showError(`Timeout ao enviar "${file.name}": A requisição demorou muito para responder.`);
                } else {
                    this.showError(`Erro ao enviar "${file.name}": ${error.message}`);
                }
            }
        }
        
        // Hide progress and reload files
        setTimeout(() => {
            progressContainer.classList.add('hidden');
            progressBar.style.width = '0%';
            this.loadExistingFiles();
            
            // Clear description
            this.container.querySelector('#file-description').value = '';
        }, 1000);
    }
    
    async loadExistingFiles() {
        if (!this.options.orcamentoId) return;
        
        try {
            const url = `${this.options.getFilesUrl}${this.options.orcamentoId}/files?categoria=${this.options.categoria}`;
            
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 15000); // 15 second timeout
            
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin',
                signal: controller.signal
            });
            
            clearTimeout(timeoutId);
            
            const result = await response.json();
            
            if (result.success) {
                this.renderFiles(result.files);
            }
        } catch (error) {
            if (error.name === 'AbortError') {
                console.error('Timeout ao carregar arquivos: A requisição demorou muito para responder.');
            } else {
                console.error('Erro ao carregar arquivos:', error);
            }
        }
    }
    
    renderFiles(files) {
        const filesGrid = this.container.querySelector('#files-grid');
        const filesList = this.container.querySelector('.files-list');
        
        if (files.length === 0) {
            filesList.classList.add('hidden');
            return;
        }
        
        filesList.classList.remove('hidden');
        
        filesGrid.innerHTML = files.map(file => `
            <div class="file-item bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-3 flex-1">
                        <div class="file-icon">
                            ${this.getFileIcon(file.type)}
                        </div>
                        <div class="file-info flex-1 min-w-0">
                            <h5 class="text-sm font-medium text-gray-900 truncate" title="${file.name}">${file.name}</h5>
                            <p class="text-xs text-gray-500 mt-1">${file.size} • ${file.created_at}</p>
                            ${file.descricao ? `<p class="text-xs text-gray-600 mt-1 italic">${file.descricao}</p>` : ''}
                            <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full mt-2">${file.categoria}</span>
                        </div>
                    </div>
                    <div class="file-actions flex items-center space-x-2 ml-4">
                        ${file.is_image ? `<button onclick="orcamentoFileUpload.previewFile('${file.url}', '${file.name}')" class="text-blue-600 hover:text-blue-800" title="Visualizar">
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
                        <button onclick="orcamentoFileUpload.editDescription(${file.id}, '${file.descricao || ''}')" class="text-yellow-600 hover:text-yellow-800" title="Editar Descrição">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button onclick="orcamentoFileUpload.deleteFile(${file.id})" class="text-red-600 hover:text-red-800" title="Excluir">
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
        
        if (type.includes('word')) {
            return '<svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
        }
        
        if (type.includes('excel') || type.includes('sheet')) {
            return '<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path></svg>';
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
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 15000); // 15 second timeout
            
            const response = await fetch(`${this.options.deleteUrl}${fileId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                credentials: 'same-origin',
                signal: controller.signal
            });
            
            clearTimeout(timeoutId);
            
            const result = await response.json();
            
            if (result.success) {
                this.showSuccess('Arquivo excluído com sucesso.');
                this.loadExistingFiles();
            } else {
                this.showError('Erro ao excluir arquivo: ' + result.message);
            }
        } catch (error) {
            if (error.name === 'AbortError') {
                this.showError('Timeout ao excluir arquivo: A requisição demorou muito para responder.');
            } else {
                this.showError('Erro ao excluir arquivo: ' + error.message);
            }
        }
    }
    
    previewFile(url, name) {
        // Create modal for image preview
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="max-w-4xl max-h-full p-4">
                <div class="bg-white rounded-lg overflow-hidden">
                    <div class="flex justify-between items-center p-4 border-b">
                        <h3 class="text-lg font-medium">${name}</h3>
                        <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="p-4">
                        <img src="${url}" alt="${name}" class="max-w-full max-h-96 mx-auto">
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
    
    async editDescription(fileId, currentDescription) {
        const newDescription = prompt('Digite a nova descrição:', currentDescription || '');
        
        if (newDescription === null) return; // User cancelled
        
        try {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 15000); // 15 second timeout
            
            const response = await fetch(`${this.options.deleteUrl}${fileId}/description`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                credentials: 'same-origin',
                body: JSON.stringify({ descricao: newDescription }),
                signal: controller.signal
            });
            
            clearTimeout(timeoutId);
            
            const result = await response.json();
            
            if (result.success) {
                this.showSuccess('Descrição atualizada com sucesso.');
                this.loadExistingFiles();
            } else {
                this.showError('Erro ao atualizar descrição: ' + result.message);
            }
        } catch (error) {
            if (error.name === 'AbortError') {
                this.showError('Timeout ao atualizar descrição: A requisição demorou muito para responder.');
            } else {
                this.showError('Erro ao atualizar descrição: ' + error.message);
            }
        }
    }
    
    showSuccess(message) {
        this.showNotification(message, 'success');
    }
    
    showError(message) {
        this.showNotification(message, 'error');
    }
    
    showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
}

// Global instance for easy access
let orcamentoFileUpload;