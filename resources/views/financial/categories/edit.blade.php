@extends('layouts.app')

@section('title', 'Nova Categoria - Giro')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Categoria</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Adicione uma nova categoria ao seu sistema</p>
        </div>
        <a href="{{ route('financial.categories.index') }}" 
           class="inline-flex items-center p-2 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors group" title="Voltar">
            <svg class="w-5 h-5 text-blue-500 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <form id="categoryForm" action="{{ route('financial.categories.update', $category) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Nome -->
                        <div class="mb-6">
                            <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nome da Categoria *
                            </label>
                            <input type="text" id="nome" name="nome" value="{{ $category->nome }}" required maxlength="100"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors"
                                placeholder="Ex: Alimentação, Salário, Transporte...">
                            <div class="text-red-500 text-sm mt-1 hidden" id="nome-error"></div>
                        </div>

                        <!-- Tipo -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                Tipo de Categoria *
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="tipo" value="receita" class="sr-only peer" required {{ $category->tipo == 'receita' ? 'checked' : '' }}>
                                    <div class="p-4 border-2 border-gray-200 dark:border-gray-600 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 transition-all hover:border-green-300">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center text-white text-xl">
                                                💰
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-gray-900 dark:text-white">Receita</h3>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">Dinheiro que entra</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="tipo" value="despesa" class="sr-only peer" required {{ $category->tipo == 'despesa' ? 'checked' : '' }}>
                                    <div class="p-4 border-2 border-gray-200 dark:border-gray-600 rounded-lg peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/20 transition-all hover:border-red-300">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center text-white text-xl">
                                                💸
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-gray-900 dark:text-white">Despesa</h3>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">Dinheiro que sai</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div class="text-red-500 text-sm mt-1 hidden" id="tipo-error"></div>
                        </div>

                        <!-- Ícone -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                Ícone da Categoria *
                            </label>
                            <input type="hidden" id="icone" name="icone" value="{{ $category->icone_url }}" required>
                            
                            <!-- Search Icons -->
                            <div class="mb-4">
                                <input type="text" id="icon-search" placeholder="Pesquisar ícones..."
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <!-- Icon Grid -->
                            <div id="icon-grid" class="grid grid-cols-5 sm:grid-cols-8 md:grid-cols-10 gap-2 sm:gap-3 max-h-64 overflow-y-auto border border-gray-200 dark:border-gray-600 rounded-lg p-3 sm:p-4">
                                <!-- Icons will be populated by JavaScript -->
                            </div>
                            <div class="text-red-500 text-sm mt-1 hidden" id="icone-error"></div>
                        </div>

                        <!-- Descrição -->
                        <div class="mb-6">
                            <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Descrição
                            </label>
                            <textarea id="descricao" name="descricao" rows="3" maxlength="255"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white resize-none transition-colors"
                                placeholder="Descreva o propósito desta categoria (opcional)">{{ $category->descricao }}</textarea>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                <span id="descricao-count">0</span>/255 caracteres
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-6">
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div>
                                    <h3 class="font-medium text-gray-900 dark:text-white">Status da Categoria</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Categorias ativas podem ser usadas em transações</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="ativo" value="1" class="sr-only peer" {{ $category->ativo ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>

                    </form>
                </div>

                <!-- Botões de Ação -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700 rounded-b-lg">
                    <div class="flex flex-col sm:flex-row justify-end gap-3">
                        <a href="{{ route('financial.categories.index') }}" 
                           class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-times w-4 h-4"></i>
                            Cancelar
                        </a>
                        <button type="submit" form="categoryForm" 
                                class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                            <i class="fas fa-save w-4 h-4"></i>
                            Atualizar Categoria
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 sticky top-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Preview da Categoria</h3>
                    
                    <div id="category-preview" class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center">
                        <div id="preview-icon" class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center text-2xl mx-auto mb-3">
                            ❓
                        </div>
                        <h4 id="preview-name" class="font-semibold text-gray-900 dark:text-white mb-1">Nome da Categoria</h4>
                        <span id="preview-type" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                            Selecione o tipo
                        </span>
                        <p id="preview-description" class="text-sm text-gray-600 dark:text-gray-400 mt-2">Descrição da categoria</p>
                    </div>
                    
                    <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <div class="flex items-start space-x-2">
                            <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm text-blue-700 dark:text-blue-300">
                                <p class="font-medium mb-1">Dicas:</p>
                                <ul class="space-y-1 text-xs">
                                    <li>• Use nomes descritivos e únicos</li>
                                    <li>• Escolha ícones que representem bem a categoria</li>
                                    <li>• Categorias inativas não aparecem em formulários</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Available icons with their emojis and keywords
const availableIcons = {
    'dollar-sign': { emoji: '💰', keywords: ['dinheiro', 'money', 'dollar', 'salario', 'renda'] },
    'home': { emoji: '🏠', keywords: ['casa', 'home', 'moradia', 'aluguel', 'imovel'] },
    'car': { emoji: '🚗', keywords: ['carro', 'car', 'veiculo', 'transporte', 'combustivel'] },
    'shopping-cart': { emoji: '🛒', keywords: ['compras', 'shopping', 'mercado', 'supermercado'] },
    'utensils': { emoji: '🍽️', keywords: ['comida', 'food', 'restaurante', 'alimentacao', 'refeicao'] },
    'heart': { emoji: '❤️', keywords: ['saude', 'health', 'medico', 'hospital', 'medicina'] },
    'briefcase': { emoji: '💼', keywords: ['trabalho', 'work', 'emprego', 'negocios', 'escritorio'] },
    'graduation-cap': { emoji: '🎓', keywords: ['educacao', 'education', 'escola', 'curso', 'estudo'] },
    'plane': { emoji: '✈️', keywords: ['viagem', 'travel', 'aviao', 'ferias', 'turismo'] },
    'gift': { emoji: '🎁', keywords: ['presente', 'gift', 'aniversario', 'natal', 'festa'] },
    'coffee': { emoji: '☕', keywords: ['cafe', 'coffee', 'bebida', 'lanche', 'padaria'] },
    'smartphone': { emoji: '📱', keywords: ['celular', 'phone', 'telefone', 'tecnologia', 'internet'] },
    'tv': { emoji: '📺', keywords: ['tv', 'televisao', 'streaming', 'netflix', 'entretenimento'] },
    'music': { emoji: '🎵', keywords: ['musica', 'music', 'spotify', 'show', 'concerto'] },
    'book': { emoji: '📚', keywords: ['livro', 'book', 'leitura', 'biblioteca', 'revista'] },
    'gamepad': { emoji: '🎮', keywords: ['jogo', 'game', 'videogame', 'diversao', 'console'] },
    'camera': { emoji: '📷', keywords: ['foto', 'camera', 'fotografia', 'imagem', 'video'] },
    'bicycle': { emoji: '🚲', keywords: ['bicicleta', 'bike', 'ciclismo', 'esporte', 'exercicio'] },
    'bus': { emoji: '🚌', keywords: ['onibus', 'bus', 'transporte', 'publico', 'passagem'] },
    'train': { emoji: '🚆', keywords: ['trem', 'train', 'metro', 'transporte', 'viagem'] },
    'fuel': { emoji: '⛽', keywords: ['combustivel', 'fuel', 'gasolina', 'posto', 'alcool'] },
    'wrench': { emoji: '🔧', keywords: ['manutencao', 'repair', 'conserto', 'ferramenta', 'oficina'] },
    'shield': { emoji: '🛡️', keywords: ['seguro', 'insurance', 'protecao', 'seguranca', 'plano'] },
    'credit-card': { emoji: '💳', keywords: ['cartao', 'card', 'credito', 'debito', 'banco'] },
    'piggy-bank': { emoji: '🐷', keywords: ['poupanca', 'savings', 'investimento', 'reserva', 'economia'] },
    'chart-line': { emoji: '📈', keywords: ['investimento', 'investment', 'acao', 'bolsa', 'renda'] },
    'coins': { emoji: '🪙', keywords: ['moeda', 'coins', 'troco', 'dinheiro', 'metal'] },
    'banknote': { emoji: '💵', keywords: ['nota', 'banknote', 'dinheiro', 'papel', 'cedula'] }
};

let selectedIcon = '{{ $category->icone_url }}';

document.addEventListener('DOMContentLoaded', function() {
    initializeForm();
    setupEventListeners();
    renderIcons();
    updatePreview();
    
    // Set initial icon value
    if (selectedIcon) {
        document.getElementById('icone').value = selectedIcon;
    }
    
    // Inicializar preview com dados existentes
    initializeExistingData();
});

function initializeForm() {
    // Character counter for description
    const descricaoField = document.getElementById('descricao');
    const descricaoCount = document.getElementById('descricao-count');
    
    if (descricaoField && descricaoCount) {
        descricaoField.addEventListener('input', function() {
            descricaoCount.textContent = this.value.length;
            updatePreview();
        });
    }
    
    // Focar no primeiro campo
    document.getElementById('nome').focus();
}

function initializeExistingData() {
    // Selecionar ícone existente se houver
    const existingIcon = selectedIcon;
    if (existingIcon) {
        const iconElement = document.querySelector(`[data-icon="${existingIcon}"]`);
        if (iconElement) {
            selectIcon(existingIcon);
        }
    }
    
    // Atualizar contador de caracteres da descrição
    const descricaoField = document.getElementById('descricao');
    const descricaoCount = document.getElementById('descricao-count');
    if (descricaoField && descricaoCount) {
        descricaoCount.textContent = descricaoField.value.length;
    }
}

function setupEventListeners() {
    // Form fields
    document.getElementById('nome').addEventListener('input', updatePreview);
    document.querySelectorAll('input[name="tipo"]').forEach(radio => {
        radio.addEventListener('change', updatePreview);
    });
    
    // Icon search
    document.getElementById('icon-search').addEventListener('input', function() {
        renderIcons(this.value.toLowerCase());
    });
    
    // Form submission
    document.getElementById('categoryForm').addEventListener('submit', handleSubmit);
}

function renderIcons(searchTerm = '') {
    const iconGrid = document.getElementById('icon-grid');
    if (!iconGrid) {
        console.error('Icon grid element not found');
        return;
    }
    
    iconGrid.innerHTML = '';
    
    Object.entries(availableIcons).forEach(([iconKey, iconData]) => {
        // Filter by search term
        if (searchTerm && !iconData.keywords.some(keyword => keyword.includes(searchTerm))) {
            return;
        }
        
        const iconButton = document.createElement('button');
        iconButton.type = 'button';
        iconButton.className = `icon-option w-12 h-12 rounded-lg border-2 border-gray-200 dark:border-gray-600 hover:border-blue-500 flex items-center justify-center text-xl transition-all ${
            selectedIcon === iconKey ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : ''
        }`;
        iconButton.innerHTML = iconData.emoji;
        iconButton.title = iconKey.replace('-', ' ');
        iconButton.dataset.icon = iconKey;
        
        iconButton.addEventListener('click', function() {
            selectIcon(iconKey);
        });
        
        iconGrid.appendChild(iconButton);
    });
}

function selectIcon(iconKey) {
    selectedIcon = iconKey;
    document.getElementById('icone').value = iconKey;
    
    // Update visual selection
    document.querySelectorAll('.icon-option').forEach(btn => {
        btn.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
        btn.classList.add('border-gray-200', 'dark:border-gray-600');
    });
    
    const selectedButton = document.querySelector(`[data-icon="${iconKey}"]`);
    if (selectedButton) {
        selectedButton.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
        selectedButton.classList.remove('border-gray-200', 'dark:border-gray-600');
    }
    
    updatePreview();
    clearError('icone');
}

function updatePreview() {
    const nomeElement = document.getElementById('nome');
    const descricaoElement = document.getElementById('descricao');
    const nome = nomeElement ? (nomeElement.value || 'Nome da Categoria') : 'Nome da Categoria';
    const tipo = document.querySelector('input[name="tipo"]:checked')?.value;
    const descricao = descricaoElement ? (descricaoElement.value || 'Descrição da categoria') : 'Descrição da categoria';
    
    // Update preview elements with null checks
    const previewName = document.getElementById('preview-name');
    if (previewName) {
        previewName.textContent = nome;
    }
    
    const previewDescription = document.getElementById('preview-description');
    if (previewDescription) {
        previewDescription.textContent = descricao;
    }
    
    // Update icon
    const previewIcon = document.getElementById('preview-icon');
    if (previewIcon) {
        if (selectedIcon && availableIcons[selectedIcon]) {
            previewIcon.innerHTML = availableIcons[selectedIcon].emoji;
            previewIcon.className = `w-16 h-16 rounded-lg flex items-center justify-center text-2xl mx-auto mb-3 ${
                tipo === 'receita' ? 'bg-green-500' : tipo === 'despesa' ? 'bg-red-500' : 'bg-gray-200 dark:bg-gray-700'
            }`;
        } else {
            previewIcon.innerHTML = '❓';
            previewIcon.className = 'w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center text-2xl mx-auto mb-3';
        }
    }
    
    // Update type badge
    const previewType = document.getElementById('preview-type');
    if (previewType) {
        if (tipo) {
            previewType.textContent = tipo === 'receita' ? 'Receita' : 'Despesa';
            previewType.className = `inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${
                tipo === 'receita' 
                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                    : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
            }`;
        } else {
            previewType.textContent = 'Selecione o tipo';
            previewType.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
        }
    }
}

function handleSubmit(e) {
    e.preventDefault();
    
    if (!validateForm()) {
        return;
    }
    
    const form = e.target;
    // Find submit button either inside form or with form attribute
    let submitButton = form.querySelector('button[type="submit"]');
    if (!submitButton) {
        submitButton = document.querySelector('button[type="submit"][form="categoryForm"]');
    }
    
    if (!submitButton) {
        console.error('Submit button not found');
        return;
    }
    
    const originalText = submitButton.innerHTML;
    
    // Show loading state
    submitButton.innerHTML = `
        <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        Atualizando...
    `;
    submitButton.disabled = true;
    
    // Submit form
    const formData = new FormData(form);
    
    // Add selected icon to form data
    if (selectedIcon) {
        formData.set('icone', selectedIcon);
    }
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Categoria atualizada com sucesso!', 'success');
            setTimeout(() => {
                window.location.href = data.redirect || '{{ route('financial.categories.index') }}';
            }, 1500);
        } else {
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    showError(field, data.errors[field][0]);
                });
            } else {
                showToast(data.message || 'Erro ao criar categoria', 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error creating category:', error);
        showToast('Erro ao atualizar categoria', 'error');
    })
    .finally(() => {
        if (submitButton) {
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        }
    });
}

function validateForm() {
    let isValid = true;
    
    // Clear previous errors
    clearAllErrors();
    
    // Validate nome
    const nomeElement = document.getElementById('nome');
    if (!nomeElement) {
        console.error('Nome element not found');
        return false;
    }
    
    const nome = nomeElement.value.trim();
    if (!nome) {
        showError('nome', 'O nome da categoria é obrigatório');
        isValid = false;
    } else if (nome.length > 100) {
        showError('nome', 'O nome deve ter no máximo 100 caracteres');
        isValid = false;
    }
    
    // Validate tipo
    const tipo = document.querySelector('input[name="tipo"]:checked');
    if (!tipo) {
        showError('tipo', 'Selecione o tipo da categoria');
        isValid = false;
    }
    
    // Validate icone
    if (!selectedIcon) {
        showError('icone', 'Selecione um ícone para a categoria');
        isValid = false;
    }
    
    return isValid;
}

function showError(field, message) {
    const errorElement = document.getElementById(`${field}-error`);
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
    }
    
    const fieldElement = document.getElementById(field) || document.querySelector(`input[name="${field}"]`);
    if (fieldElement) {
        fieldElement.classList.add('border-red-500', 'focus:ring-red-500');
        fieldElement.classList.remove('border-gray-300', 'focus:ring-blue-500');
    }
}

function clearError(field) {
    const errorElement = document.getElementById(`${field}-error`);
    if (errorElement) {
        errorElement.classList.add('hidden');
    }
    
    const fieldElement = document.getElementById(field) || document.querySelector(`input[name="${field}"]`);
    if (fieldElement) {
        fieldElement.classList.remove('border-red-500', 'focus:ring-red-500');
        fieldElement.classList.add('border-gray-300', 'focus:ring-blue-500');
    }
}

function clearAllErrors() {
    ['nome', 'tipo', 'icone'].forEach(field => {
        clearError(field);
    });
}

// Show toast notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    
    toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}
</script>
@endpush
@endsection