class AutorAutocomplete {
    constructor(inputElement, containerElement) {
        this.input = inputElement;
        this.container = containerElement;
        this.dropdown = null;
        this.selectedAutores = [];
        this.debounceTimer = null;
        
        this.init();
    }
    
    init() {
        this.createDropdown();
        this.bindEvents();
        this.loadExistingAutores();
    }
    
    createDropdown() {
        this.dropdown = document.createElement('div');
        this.dropdown.className = 'autocomplete-dropdown';
        this.dropdown.style.cssText = `
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 0.375rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        `;
        
        this.input.parentNode.style.position = 'relative';
        this.input.parentNode.appendChild(this.dropdown);
    }
    
    bindEvents() {
        this.input.addEventListener('input', (e) => {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this.search(e.target.value);
            }, 300);
        });
        
        this.input.addEventListener('focus', () => {
            if (this.input.value.trim()) {
                this.search(this.input.value);
            }
        });
        
        this.input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.handleEnter();
            } else if (e.key === 'Escape') {
                this.hideDropdown();
            }
        });
        
        document.addEventListener('click', (e) => {
            if (!this.input.parentNode.contains(e.target)) {
                this.hideDropdown();
            }
        });
    }
    
    async search(query) {
        if (!query || query.length < 2) {
            this.hideDropdown();
            return;
        }
        
        try {
            const response = await fetch(`/api/budget/autores/autocomplete?q=${encodeURIComponent(query)}`);
            const autores = await response.json();
            
            this.showResults(autores, query);
        } catch (error) {
            console.error('Erro ao buscar autores:', error);
        }
    }
    
    showResults(autores, query) {
        this.dropdown.innerHTML = '';
        
        // Filtrar autores já selecionados
        const availableAutores = autores.filter(autor => 
            !this.selectedAutores.some(selected => selected.id === autor.id)
        );
        
        // Mostrar autores existentes
        availableAutores.forEach(autor => {
            const item = this.createAutorItem(autor);
            this.dropdown.appendChild(item);
        });
        
        // Opção para criar novo autor se não encontrou correspondência exata
        const exactMatch = availableAutores.some(autor => 
            autor.nome.toLowerCase() === query.toLowerCase()
        );
        
        if (!exactMatch && query.trim()) {
            const newItem = this.createNewAutorItem(query);
            this.dropdown.appendChild(newItem);
        }
        
        this.showDropdown();
    }
    
    createAutorItem(autor) {
        const item = document.createElement('div');
        item.className = 'autocomplete-item';
        item.style.cssText = `
            padding: 0.75rem;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            transition: background-color 0.2s;
        `;
        
        item.innerHTML = `
            <div class="flex items-center">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                <div>
                    <div class="font-medium text-gray-900">${autor.nome}</div>
                    ${autor.email ? `<div class="text-sm text-gray-500">${autor.email}</div>` : ''}
                </div>
            </div>
        `;
        
        item.addEventListener('mouseenter', () => {
            item.style.backgroundColor = '#f9fafb';
        });
        
        item.addEventListener('mouseleave', () => {
            item.style.backgroundColor = 'white';
        });
        
        item.addEventListener('click', () => {
            this.selectAutor(autor);
        });
        
        return item;
    }
    
    createNewAutorItem(query) {
        const item = document.createElement('div');
        item.className = 'autocomplete-item new-autor';
        item.style.cssText = `
            padding: 0.75rem;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            transition: background-color 0.2s;
            background-color: #fef3f2;
        `;
        
        item.innerHTML = `
            <div class="flex items-center">
                <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                <div>
                    <div class="font-medium text-gray-900">Criar novo: "${query}"</div>
                    <div class="text-sm text-gray-500">Clique para adicionar este autor</div>
                </div>
            </div>
        `;
        
        item.addEventListener('mouseenter', () => {
            item.style.backgroundColor = '#fecaca';
        });
        
        item.addEventListener('mouseleave', () => {
            item.style.backgroundColor = '#fef3f2';
        });
        
        item.addEventListener('click', () => {
            this.selectNewAutor(query);
        });
        
        return item;
    }
    
    selectAutor(autor) {
        this.selectedAutores.push(autor);
        this.addAutorToList(autor, 'existing');
        this.input.value = '';
        this.hideDropdown();
        this.updateHiddenInputs();
    }
    
    selectNewAutor(nome) {
        const newAutor = { 
            id: 'new:' + nome, 
            nome: nome, 
            isNew: true 
        };
        this.selectedAutores.push(newAutor);
        this.addAutorToList(newAutor, 'new');
        this.input.value = '';
        this.hideDropdown();
        this.updateHiddenInputs();
    }
    
    addAutorToList(autor, type) {
        const autorItem = document.createElement('div');
        autorItem.className = 'relative bg-white border border-gray-200 rounded-lg p-3 shadow-sm hover:shadow-md transition-shadow';
        autorItem.dataset.autorId = autor.id;
        
        const tagColor = type === 'existing' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
        const tagText = type === 'existing' ? 'Existente' : 'Novo';
        
        // Gerar iniciais do nome para o avatar
        const initials = autor.nome.split(' ').map(word => word.charAt(0).toUpperCase()).slice(0, 2).join('');
        const avatarColor = type === 'existing' ? 'bg-blue-500' : 'bg-orange-500';
        
        autorItem.innerHTML = `
            <input type="checkbox" name="autores[]" value="${autor.id}" checked class="hidden">
            
            <!-- Avatar -->
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 ${avatarColor} rounded-full flex items-center justify-center text-white font-semibold text-sm">
                    ${initials}
                </div>
                
                <!-- Nome e Badge -->
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">${autor.nome}</p>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium ${tagColor}">
                        ${tagText}
                    </span>
                </div>
            </div>
            
            <!-- Botão de remoção -->
            <button type="button" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 remove-autor" data-autor-id="${autor.id}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
        
        // Adicionar evento de remoção
        const removeBtn = autorItem.querySelector('.remove-autor');
        removeBtn.addEventListener('click', () => {
            this.removeAutor(autor.id);
        });
        
        this.container.appendChild(autorItem);
    }
    
    removeAutor(autorId) {
        // Remover da lista de selecionados
        this.selectedAutores = this.selectedAutores.filter(autor => autor.id !== autorId);
        
        // Remover do DOM
        const autorItem = this.container.querySelector(`[data-autor-id="${autorId}"]`);
        if (autorItem) {
            autorItem.remove();
        }
        
        this.updateHiddenInputs();
    }
    
    updateHiddenInputs() {
        // Remover inputs hidden existentes
        const existingInputs = this.container.querySelectorAll('input[name="autores[]"]');
        existingInputs.forEach(input => {
            if (!input.closest('[data-autor-id]')) {
                input.remove();
            }
        });
    }
    
    loadExistingAutores() {
        // Carregar autores já selecionados (para página de edição)
        const existingCheckboxes = this.container.querySelectorAll('input[name="autores[]"]:checked');
        existingCheckboxes.forEach(checkbox => {
            const autorItem = checkbox.closest('[data-autor-id]');
            if (autorItem) {
                const autorId = autorItem.dataset.autorId;
                const autorNome = autorItem.querySelector('.font-medium').textContent;
                
                this.selectedAutores.push({
                    id: autorId,
                    nome: autorNome
                });
            }
        });
    }
    
    handleEnter() {
        const items = this.dropdown.querySelectorAll('.autocomplete-item');
        if (items.length > 0) {
            const firstItem = items[0];
            if (!firstItem.classList.contains('new-autor')) {
                firstItem.click();
            } else {
                this.selectNewAutor(this.input.value.trim());
            }
        }
    }
    
    showDropdown() {
        this.dropdown.style.display = 'block';
    }
    
    hideDropdown() {
        this.dropdown.style.display = 'none';
    }
}

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    const autorInput = document.getElementById('autor_autocomplete');
    const autorContainer = document.getElementById('autores_container');
    
    if (autorInput && autorContainer) {
        new AutorAutocomplete(autorInput, autorContainer);
    }
});