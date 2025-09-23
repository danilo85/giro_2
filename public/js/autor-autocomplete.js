class AutorAutocomplete {
    constructor(inputElement) {
        this.input = inputElement;
        this.dropdown = null;
        this.selectedAutores = [];
        this.debounceTimer = null;
        this.container = document.getElementById('autores_container');
        
        this.init();
    }
    
    init() {
        this.createDropdown();
        this.bindEvents();
        this.bindRemoveEvents();
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
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 0.375rem 0.375rem;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
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
            if (this.input.value.length > 0) {
                this.search(this.input.value);
            }
        });
        
        this.input.addEventListener('blur', (e) => {
            setTimeout(() => {
                this.hideDropdown();
            }, 150);
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
    
    bindRemoveEvents() {
        document.addEventListener('click', (e) => {
            if (e.target.closest('.remove-autor')) {
                const autorId = e.target.closest('.remove-autor').dataset.autorId;
                this.removeAutor(autorId);
            }
        });
    }
    
    async search(query) {
        if (query.length < 2) {
            this.hideDropdown();
            return;
        }
        
        try {
            const response = await fetch(`/api/budget/autores/autocomplete?q=${encodeURIComponent(query)}`, {
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
            
            if (!response.ok) {
                console.error('Erro na resposta:', response.status, response.statusText);
                this.hideDropdown();
                return;
            }
            
            const autores = await response.json();
            
            if (!Array.isArray(autores)) {
                console.error('Resposta não é um array:', autores);
                this.hideDropdown();
                return;
            }
            
            this.showResults(autores, query);
        } catch (error) {
            console.error('Erro na busca de autores:', error);
            this.hideDropdown();
        }
    }
    
    showResults(autores, query) {
        this.dropdown.innerHTML = '';
        
        if (!Array.isArray(autores)) {
            console.error('autores não é um array:', autores);
            return;
        }
        
        // Filtrar autores já selecionados
        const autoresDisponiveis = autores.filter(autor => 
            !this.selectedAutores.includes(autor.id.toString())
        );
        
        if (autoresDisponiveis.length === 0) {
            const newItem = this.createNewAutorItem(query);
            this.dropdown.appendChild(newItem);
        } else {
            autoresDisponiveis.forEach(autor => {
                const item = this.createAutorItem(autor);
                this.dropdown.appendChild(item);
            });
            
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
            transition: background-color 0.15s;
        `;
        
        item.innerHTML = `
            <div class="font-medium text-gray-900">${autor.nome}</div>
            ${autor.email ? `<div class="text-sm text-gray-500">${autor.email}</div>` : ''}
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
            transition: background-color 0.15s;
            border-top: 1px solid #e5e7eb;
        `;
        
        item.innerHTML = `
            <div class="font-medium text-blue-600">
                <i class="fas fa-plus mr-2"></i>
                Criar novo autor: "${query}"
            </div>
            <div class="text-sm text-gray-500">Será cadastrado automaticamente ao salvar</div>
        `;
        
        item.addEventListener('mouseenter', () => {
            item.style.backgroundColor = '#eff6ff';
        });
        
        item.addEventListener('mouseleave', () => {
            item.style.backgroundColor = 'white';
        });
        
        item.addEventListener('click', () => {
            this.selectNewAutor(query);
        });
        
        return item;
    }
    
    selectAutor(autor) {
        this.addAutorToContainer(autor, 'existing');
        this.selectedAutores.push(autor.id.toString());
        this.input.value = '';
        this.hideDropdown();
    }
    
    selectNewAutor(nome) {
        const newAutor = { nome: nome, isNew: true, id: 'new_' + Date.now() };
        this.addAutorToContainer(newAutor, 'new');
        this.selectedAutores.push(newAutor.id.toString());
        this.input.value = '';
        this.hideDropdown();
    }
    
    addAutorToContainer(autor, type) {
        const autorCard = document.createElement('div');
        autorCard.className = 'relative bg-white border border-gray-200 rounded-lg p-3 shadow-sm hover:shadow-md transition-shadow';
        autorCard.setAttribute('data-autor-id', autor.id);
        
        const bgColor = type === 'existing' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700';
        const label = type === 'existing' ? 'Existente' : 'Novo';
        const inputValue = type === 'existing' ? autor.id : 'new:' + autor.nome;
        
        autorCard.innerHTML = `
            <input type="checkbox" name="autores[]" value="${inputValue}" checked class="hidden">
            
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                    ${autor.nome.substring(0, 2).toUpperCase()}
                </div>
                
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">${autor.nome}</p>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium ${bgColor}">
                        ${label}
                    </span>
                </div>
            </div>
            
            <button type="button" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 remove-autor" data-autor-id="${autor.id}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
        
        this.container.appendChild(autorCard);
    }
    
    removeAutor(autorId) {
        const autorCard = document.querySelector(`[data-autor-id="${autorId}"]`);
        if (autorCard) {
            autorCard.remove();
            this.selectedAutores = this.selectedAutores.filter(id => id !== autorId.toString());
        }
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
        } else if (this.input.value.trim()) {
            this.selectNewAutor(this.input.value.trim());
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
    
    if (autorInput) {
        new AutorAutocomplete(autorInput);
    }
});