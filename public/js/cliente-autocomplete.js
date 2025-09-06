class ClienteAutocomplete {
    constructor(inputElement, hiddenElement) {
        this.input = inputElement;
        this.hidden = hiddenElement;
        this.dropdown = null;
        this.selectedCliente = null;
        this.debounceTimer = null;
        
        this.init();
    }
    
    init() {
        this.createDropdown();
        this.bindEvents();
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
            // Delay para permitir clique nos itens do dropdown
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
    
    async search(query) {
        if (query.length < 2) {
            this.hideDropdown();
            return;
        }
        
        try {
            const response = await fetch(`/clientes/autocomplete?q=${encodeURIComponent(query)}`, {
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
            
            const clientes = await response.json();
            
            // Verificar se clientes é um array válido
            if (!Array.isArray(clientes)) {
                console.error('Resposta não é um array:', clientes);
                this.hideDropdown();
                return;
            }
            
            this.showResults(clientes, query);
        } catch (error) {
            console.error('Erro na busca de clientes:', error);
            this.hideDropdown();
        }
    }
    
    showResults(clientes, query) {
        this.dropdown.innerHTML = '';
        
        // Verificar se clientes é um array válido
        if (!Array.isArray(clientes)) {
            console.error('clientes não é um array:', clientes);
            return;
        }
        
        if (clientes.length === 0) {
            // Mostrar opção para criar novo cliente
            const newItem = this.createNewClienteItem(query);
            this.dropdown.appendChild(newItem);
        } else {
            // Mostrar clientes encontrados
            clientes.forEach(cliente => {
                const item = this.createClienteItem(cliente);
                this.dropdown.appendChild(item);
            });
            
            // Adicionar opção para criar novo cliente no final
            const newItem = this.createNewClienteItem(query);
            this.dropdown.appendChild(newItem);
        }
        
        this.showDropdown();
    }
    
    createClienteItem(cliente) {
        const item = document.createElement('div');
        item.className = 'autocomplete-item';
        item.style.cssText = `
            padding: 0.75rem;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            transition: background-color 0.15s;
        `;
        
        item.innerHTML = `
            <div class="font-medium text-gray-900">${cliente.nome}</div>
            ${cliente.email ? `<div class="text-sm text-gray-500">${cliente.email}</div>` : ''}
        `;
        
        item.addEventListener('mouseenter', () => {
            item.style.backgroundColor = '#f9fafb';
        });
        
        item.addEventListener('mouseleave', () => {
            item.style.backgroundColor = 'white';
        });
        
        item.addEventListener('click', () => {
            this.selectCliente(cliente);
        });
        
        return item;
    }
    
    createNewClienteItem(query) {
        const item = document.createElement('div');
        item.className = 'autocomplete-item new-cliente';
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
                Criar novo cliente: "${query}"
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
            this.selectNewCliente(query);
        });
        
        return item;
    }
    
    selectCliente(cliente) {
        this.selectedCliente = cliente;
        this.hidden.value = cliente.id;
        this.showSelectedTag(cliente.nome, 'existing');
        this.hideDropdown();
    }
    
    selectNewCliente(nome) {
        this.selectedCliente = { nome: nome, isNew: true };
        this.hidden.value = 'new:' + nome;
        this.showSelectedTag(nome, 'new');
        this.hideDropdown();
    }
    
    showSelectedTag(nome, type) {
        // Esconder o input e mostrar a tag
        this.input.style.display = 'none';
        
        // Remover tag anterior se existir
        const existingTag = this.input.parentNode.querySelector('.cliente-tag');
        if (existingTag) {
            existingTag.remove();
        }
        
        const tag = document.createElement('div');
        tag.className = 'cliente-tag';
        
        const bgColor = type === 'existing' ? 'bg-green-100 text-green-800 border-green-200' : 'bg-red-100 text-red-800 border-red-200';
        const icon = type === 'existing' ? 'fa-check' : 'fa-plus';
        
        tag.innerHTML = `
            <div class="inline-flex items-center px-3 py-2 rounded-md border ${bgColor}">
                <i class="fas ${icon} mr-2"></i>
                <span class="font-medium">${nome}</span>
                <button type="button" class="ml-2 text-gray-400 hover:text-gray-600" onclick="this.closest('.cliente-tag').nextElementSibling.style.display='block'; this.closest('.cliente-tag').remove(); this.closest('div').querySelector('input[type=hidden]').value='';">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        this.input.parentNode.insertBefore(tag, this.input);
    }
    
    handleEnter() {
        const items = this.dropdown.querySelectorAll('.autocomplete-item');
        if (items.length > 0) {
            // Se há apenas um item ou o primeiro é um cliente existente, seleciona ele
            const firstItem = items[0];
            if (!firstItem.classList.contains('new-cliente')) {
                firstItem.click();
            } else {
                // Se o primeiro item é "criar novo", cria com o valor atual do input
                this.selectNewCliente(this.input.value.trim());
            }
        } else if (this.input.value.trim()) {
            // Se não há resultados mas há texto, cria novo cliente
            this.selectNewCliente(this.input.value.trim());
        }
    }
    
    showDropdown() {
        this.dropdown.style.display = 'block';
    }
    
    hideDropdown() {
        this.dropdown.style.display = 'none';
    }
    
    reset() {
        this.selectedCliente = null;
        this.hidden.value = '';
        this.input.value = '';
        this.input.style.display = 'block';
        
        const existingTag = this.input.parentNode.querySelector('.cliente-tag');
        if (existingTag) {
            existingTag.remove();
        }
        
        this.hideDropdown();
    }
}

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    const clienteInput = document.getElementById('cliente_autocomplete');
    const clienteHidden = document.getElementById('cliente_id');
    
    if (clienteInput && clienteHidden) {
        new ClienteAutocomplete(clienteInput, clienteHidden);
    }
});