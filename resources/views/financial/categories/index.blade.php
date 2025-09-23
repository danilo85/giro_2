@extends('layouts.app')

@section('title', 'Categorias - Giro')

@section('content')

<div class="max-w-7xl mx-auto">
    <!-- Tags de Navegação -->
    <div class="mb-6">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('financial.dashboard') }}" 
               class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200 dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4l2 2 4-4"></path>
                </svg>
                Dashboard
            </a>
            
            <a href="{{ route('financial.banks.index') }}" 
               class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200 dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Bancos
            </a>
            
            <a href="{{ route('financial.credit-cards.index') }}" 
               class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200 dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                Cartões
            </a>
            
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-600 text-white dark:bg-blue-700">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                Categorias
            </span>
            
            <a href="{{ route('financial.transactions.index') }}" 
               class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200 dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16a2 2 0 002 2z"></path>
                </svg>
                Transações
            </a>
        </div>
    </div>

  <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Categorias</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Gerencie as categorias de receitas e despesas</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-emerald-100">Total de Categorias</p>
                    <p id="total-categories" class="text-2xl font-bold text-white">{{ $categories->count() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-100">Receitas</p>
                    <p class="text-2xl font-bold text-white">{{ $categories->where('tipo', 'receita')->count() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-amber-100">Despesas</p>
                    <p class="text-2xl font-bold text-white">{{ $categories->where('tipo', 'despesa')->count() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Campo de Pesquisa -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4">
            <div class="flex items-center gap-3">
                <div class="flex-1 relative">
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Buscar por nome da categoria, tipo ou descrição..."
                           class="w-full pl-10 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <button type="button" id="clearSearch" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 hidden">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Categories Grid -->
    <div id="categories-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <!-- Loading State -->
        <div class="col-span-full flex items-center justify-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <span class="ml-2 text-gray-600 dark:text-gray-400">Carregando categorias...</span>
        </div>
    </div>
    
    <!-- No Results State -->
    <div id="no-results" class="hidden text-center py-12">
        <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Nenhuma categoria encontrada</h3>
        <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">Não encontramos categorias que correspondam à sua pesquisa. Tente usar termos diferentes.</p>
    </div>
    <footer class="mt-8">
        <div class="text-center py-6">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                © {{ date('Y') }} Danilo Miguel. Todos os direitos reservados.
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                Sistema de Gestão Financeira - Desenvolvido com Laravel
            </p>
        </div>
    </footer>

    @if($categories->count() == 0)
        <!-- Empty State -->
        <div id="empty-state" class="text-center py-12">
            <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Nenhuma categoria encontrada</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto">Comece criando sua primeira categoria para organizar suas receitas e despesas de forma eficiente.</p>
            <a href="{{ route('financial.categories.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-br from-emerald-500 to-green-600 text-white rounded-lg hover:shadow-lg transition-all duration-200 group">
                <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Criar Primeira Categoria
            </a>
        </div>
    @endif
</div>

<!-- Floating Action Button -->
<div class="fixed bottom-6 right-6 z-50">
    <a href="{{ route('financial.categories.create') }}" class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-br from-emerald-500 to-green-600 rounded-full shadow-lg hover:shadow-xl transition-all duration-200 group" title="Criar Nova Categoria">
        <svg class="w-6 h-6 text-white group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
    </a>
</div>

<!-- Template para card de categoria -->
<template id="category-card-template">
    <div class="category-card bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 overflow-hidden" data-type="">
        <!-- Category Header -->
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center text-2xl overflow-hidden bg-white dark:bg-gray-100 category-icon-bg">
                        <span class="category-icon"></span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white category-name"></h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 category-type capitalize"></p>
                    </div>
                </div>
            </div>
            
            <!-- Badges Row -->
            <div class="flex flex-wrap gap-2 mb-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 category-status-badge">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Ativa
                </span>
                
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 category-type-badge">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span class="category-type-text"></span>
                </span>
            </div>
        </div>

        <!-- Category Details -->
        <div class="px-6 pb-6">
            <div class="space-y-4">
                <!-- Description -->
                <div class="text-center bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Descrição</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white category-description">-</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="px-6 pb-6">
            <div class="flex justify-center items-center">
                <div class="flex space-x-3">
                    <a href="#" class="flex items-center justify-center w-10 h-10 text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 transition-colors hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg edit-category" title="Editar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                    <button class="flex items-center justify-center w-10 h-10 text-red-500 hover:text-red-600 dark:hover:text-red-400 transition-colors hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg delete-category" title="Excluir">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
let categories = [];

// Icon mapping
const iconMapping = {
    'dollar-sign': '💰',
    'home': '🏠',
    'car': '🚗',
    'shopping-cart': '🛒',
    'utensils': '🍽️',
    'heart': '❤️',
    'briefcase': '💼',
    'graduation-cap': '🎓',
    'plane': '✈️',
    'gift': '🎁',
    'coffee': '☕',
    'smartphone': '📱',
    'tv': '📺',
    'music': '🎵',
    'book': '📚',
    'gamepad': '🎮',
    'camera': '📷',
    'bicycle': '🚲',
    'bus': '🚌',
    'train': '🚆',
    'fuel': '⛽',
    'wrench': '🔧',
    'shield': '🛡️',
    'credit-card': '💳',
    'piggy-bank': '🐷',
    'chart-line': '📈',
    'coins': '🪙',
    'banknote': '💵'
};

document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
    initializeSearch();
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.relative')) {
            document.querySelectorAll('.menu-dropdown').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });
});



// Initialize search functionality
function initializeSearch() {
    const searchInput = document.getElementById('search');
    const clearButton = document.getElementById('clearSearch');
    
    if (!searchInput || !clearButton) {
        console.error('Search elements not found:', {
            searchInput: !!searchInput,
            clearButton: !!clearButton
        });
        return;
    }
    
    console.log('Search functionality initialized successfully');
    
    // Search input event listener
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.trim();
        
        if (searchTerm.length > 0) {
            clearButton.classList.remove('hidden');
        } else {
            clearButton.classList.add('hidden');
        }
        
        filterCategories(searchTerm);
    });
    
    // Clear button event listener
    clearButton.addEventListener('click', function() {
        searchInput.value = '';
        clearButton.classList.add('hidden');
        filterCategories('');
        searchInput.focus();
    });
    
    // Enter key to focus search
    document.addEventListener('keydown', function(e) {
        if (e.key === '/' && !e.ctrlKey && !e.metaKey && !e.altKey) {
            e.preventDefault();
            searchInput.focus();
        }
    });
}

// Filter categories based on search term
function filterCategories(searchTerm) {
    const container = document.getElementById('categories-container');
    const noResults = document.getElementById('no-results');
    
    if (!container || !noResults) {
        console.error('Filter elements not found:', {
            container: !!container,
            noResults: !!noResults
        });
        return;
    }
    
    const categoryCards = container.querySelectorAll('.category-card');
    
    if (!searchTerm) {
        // Show all categories
        categoryCards.forEach(card => {
            card.style.display = 'block';
        });
        noResults.classList.add('hidden');
        return;
    }
    
    const searchLower = searchTerm.toLowerCase();
    let visibleCount = 0;
    
    categoryCards.forEach(card => {
        const categoryName = card.querySelector('.category-name');
        const categoryDescription = card.querySelector('.category-description');
        const categoryType = card.querySelector('.category-type-text');
        
        if (categoryName && categoryDescription && categoryType) {
            const name = categoryName.textContent.toLowerCase();
            const description = categoryDescription.textContent.toLowerCase();
            const type = categoryType.textContent.toLowerCase();
            
            const matches = name.includes(searchLower) || 
                          description.includes(searchLower) || 
                          type.includes(searchLower);
            
            if (matches) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        }
    });
    
    // Show/hide no results message
    if (visibleCount === 0 && searchTerm.length > 0) {
        noResults.classList.remove('hidden');
    } else {
        noResults.classList.add('hidden');
    }
    
    console.log(`Filter applied: "${searchTerm}" - ${visibleCount} categories visible`);
}

// Load categories
function loadCategories() {
    const container = document.getElementById('categories-container');
    if (!container) {
        console.error('Container categories-container not found');
        return;
    }
    
    console.log('Loading categories from API...');
    
    fetch('/api/financial/categories', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('API Response status:', response.status);
        console.log('API Response headers:', response.headers);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return response.json();
    })
    .then(data => {
        console.log('API Response data:', data);
        // A API retorna categorias agrupadas por tipo (receitas e despesas)
        if (data.receitas && data.despesas) {
            categories = [...data.receitas, ...data.despesas];
        } else {
            // Fallback para formato de array direto
            categories = Array.isArray(data) ? data : (data.data || data.categories || []);
        }
        console.log('Categories loaded:', categories.length);
        renderCategories();
    })
    .catch(error => {
        console.error('Error loading categories:', error);
        console.error('Error details:', error.message);
        showError('Erro ao carregar categorias: ' + error.message);
    });
}



// Update summary cards
function updateSummaryCards() {
    const totalElement = document.getElementById('total-categories');
    if (totalElement) {
        totalElement.textContent = categories.length;
    }
    
    // Count by type
    const receitas = categories.filter(cat => cat.tipo === 'receita').length;
    const despesas = categories.filter(cat => cat.tipo === 'despesa').length;
    
    // Update receitas card - find by the blue gradient background
    const receitasCard = document.querySelector('.bg-gradient-to-br.from-blue-500');
    if (receitasCard) {
        const receitasCountElement = receitasCard.querySelector('p.text-2xl.font-bold');
        if (receitasCountElement) {
            receitasCountElement.textContent = receitas;
        }
    }
    
    // Update despesas card - find by the amber gradient background
    const despesasCard = document.querySelector('.bg-gradient-to-br.from-amber-500');
    if (despesasCard) {
        const despesasCountElement = despesasCard.querySelector('p.text-2xl.font-bold');
        if (despesasCountElement) {
            despesasCountElement.textContent = despesas;
        }
    }
    
    console.log(`Summary updated: Total=${categories.length}, Receitas=${receitas}, Despesas=${despesas}`);
}

// Update type-specific counters in real-time
function updateTypeCounters(deletedCategoryType) {
    if (!deletedCategoryType) return;
    
    if (deletedCategoryType === 'receita') {
        // Update receitas card
        const receitasCard = document.querySelector('.bg-gradient-to-br.from-blue-500');
        if (receitasCard) {
            const receitasCountElement = receitasCard.querySelector('p.text-2xl.font-bold');
            if (receitasCountElement) {
                const currentCount = parseInt(receitasCountElement.textContent) || 0;
                const newCount = Math.max(0, currentCount - 1);
                receitasCountElement.textContent = newCount;
                console.log(`Receitas counter updated: ${currentCount} -> ${newCount}`);
            }
        }
    } else if (deletedCategoryType === 'despesa') {
        // Update despesas card
        const despesasCard = document.querySelector('.bg-gradient-to-br.from-amber-500');
        if (despesasCard) {
            const despesasCountElement = despesasCard.querySelector('p.text-2xl.font-bold');
            if (despesasCountElement) {
                const currentCount = parseInt(despesasCountElement.textContent) || 0;
                const newCount = Math.max(0, currentCount - 1);
                despesasCountElement.textContent = newCount;
                console.log(`Despesas counter updated: ${currentCount} -> ${newCount}`);
            }
        }
    }
    
    // Update total categories counter
    const totalElement = document.getElementById('total-categories');
    if (totalElement) {
        const currentTotal = parseInt(totalElement.textContent) || 0;
        const newTotal = Math.max(0, currentTotal - 1);
        totalElement.textContent = newTotal;
        console.log(`Total categories counter updated: ${currentTotal} -> ${newTotal}`);
    }
}

// Render categories
function renderCategories() {
    const container = document.getElementById('categories-container');
    const template = document.getElementById('category-card-template');
    const emptyState = document.getElementById('empty-state');
    
    if (!container) {
        console.error('Container categories-container not found');
        return;
    }
    
    if (!template) {
        console.error('Template category-card-template not found');
        return;
    }
    
    // Update summary cards
    updateSummaryCards();
    
    // Clear container
    container.innerHTML = '';
    
    // Show/hide empty state
    if (categories.length === 0) {
        container.style.display = 'none';
        if (emptyState) {
            emptyState.style.display = 'block';
        }
        return;
    } else {
        container.style.display = 'grid';
        if (emptyState) {
            emptyState.style.display = 'none';
        }
    }
    
    // Render each category
    categories.forEach(category => {
        const card = template.content.cloneNode(true);
        
        // Set data attributes
        const cardElement = card.querySelector('.category-card');
        cardElement.setAttribute('data-type', category.tipo);
        cardElement.setAttribute('data-id', category.id);
        
        // Set icon
        const iconElement = card.querySelector('.category-icon');
        const iconBg = card.querySelector('.category-icon-bg');
        const iconEmoji = iconMapping[category.icone_url] || '🏷️';
        iconElement.textContent = iconEmoji;
        
        // Set background color based on category type
        if (category.tipo === 'receita') {
            iconBg.style.backgroundColor = 'rgba(34, 197, 94, 0.2)';
            iconBg.style.color = 'rgb(34, 197, 94)';
        } else {
            iconBg.style.backgroundColor = 'rgba(239, 68, 68, 0.2)';
            iconBg.style.color = 'rgb(239, 68, 68)';
        }
        
        // Set content
        card.querySelector('.category-name').textContent = category.nome;
        card.querySelector('.category-type').textContent = category.tipo;
        card.querySelector('.category-type-text').textContent = category.tipo;
        card.querySelector('.category-description').textContent = category.descricao || 'Sem descrição';
        
        // Update type badge color based on category type
        const typeBadge = card.querySelector('.category-type-badge');
        if (category.tipo === 'receita') {
            typeBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 category-type-badge';
        } else {
            typeBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 category-type-badge';
        }
        
        // Add event listeners
        const editBtn = card.querySelector('.edit-category');
        const deleteBtn = card.querySelector('.delete-category');
        
        if (editBtn) {
            editBtn.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = `/financial/categories/${category.id}/edit`;
            });
        }
        
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                deleteCategory(category.id, category.nome);
            });
        }
        
        container.appendChild(card);
    });
}

// Delete category
let categoryToDelete = null;

function deleteCategory(categoryId, categoryName) {
    categoryToDelete = categoryId;
    document.getElementById('deleteModal').classList.remove('hidden');
}

// Refresh categories
function refreshCategories(event) {
    let button;
    if (event && event.target) {
        button = event.target;
    } else {
        // Fallback: find the refresh button
        button = document.querySelector('button[onclick="refreshCategories()"]');
    }
    
    if (!button) {
        console.error('Refresh button not found');
        loadCategories();
        return;
    }
    
    const originalText = button.innerHTML;
    
    button.innerHTML = `
        <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        Atualizando...
    `;
    button.disabled = true;
    
    loadCategories();
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
        showToast('Categorias atualizadas!', 'success');
    }, 1000);
}

// Show error
function showError(message) {
    const container = document.getElementById('categories-container');
    if (!container) {
        console.error('Container categories-container not found');
        return;
    }
    
    container.innerHTML = `
        <div class="col-span-full text-center py-12">
            <svg class="w-16 h-16 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Erro ao carregar</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">${message}</p>
            <button onclick="loadCategories()" class="inline-flex items-center p-3 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors group" title="Tentar Novamente">
                <svg class="w-5 h-5 text-blue-500 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </button>
        </div>
    `;
}

// Toggle category menu
function toggleCategoryMenu(button) {
    const menu = button.nextElementSibling;
    const allMenus = document.querySelectorAll('.category-menu');
    
    // Close all other menus
    allMenus.forEach(m => {
        if (m !== menu) {
            m.classList.add('hidden');
        }
    });
    
    // Toggle current menu
    menu.classList.toggle('hidden');
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

// Modal functions
function hideModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    categoryToDelete = null;
}

function cancelDelete() {
    hideModal();
}

function confirmDelete() {
    if (!categoryToDelete) {
        console.error('No category selected for deletion');
        return;
    }
    
    // Find the category to get its type before deletion
    const categoryToDeleteObj = categories.find(cat => cat.id == categoryToDelete);
    const categoryType = categoryToDeleteObj ? categoryToDeleteObj.tipo : null;
    
    const deleteSpinner = document.getElementById('deleteSpinner');
    const confirmBtn = document.querySelector('#deleteModal button[onclick="confirmDelete()"]');
    
    // Show loading state
    deleteSpinner.classList.remove('hidden');
    confirmBtn.disabled = true;
    
    fetch(`/financial/categories/${categoryToDelete}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(async response => {
        const data = await response.json();
        
        if (!response.ok) {
            // Capturar mensagem específica do servidor para erros 422 e outros
            const errorMessage = data.message || `HTTP error! status: ${response.status}`;
            throw new Error(errorMessage);
        }
        
        return data;
    })
    .then(data => {
        if (data.success) {
            // Remove category from DOM with animation
            const categoryCard = document.querySelector(`[data-id="${categoryToDelete}"]`);
            if (categoryCard) {
                categoryCard.style.transform = 'scale(0.95)';
                categoryCard.style.opacity = '0';
                categoryCard.style.transition = 'all 0.3s ease';
                
                setTimeout(() => {
                    categoryCard.remove();
                    
                    // Update categories array
                    categories = categories.filter(cat => cat.id != categoryToDelete);
                    
                    // Update summary cards immediately
                    updateSummaryCards();
                    
                    // Update specific type counter in real-time
                    updateTypeCounters(categoryType);
                    
                    // Check if no categories left and show empty state
                    if (categories.length === 0) {
                        renderCategories();
                    }
                }, 300);
            }
            
            hideModal();
            showToast(data.message || 'Categoria excluída com sucesso!', 'success');
        } else {
            throw new Error(data.message || 'Erro ao excluir categoria');
        }
    })
    .catch(error => {
        console.error('Error deleting category:', error);
        hideModal();
        showToast(error.message, 'error');
    })
    .finally(() => {
        deleteSpinner.classList.add('hidden');
        confirmBtn.disabled = false;
    });
}

// Add event listeners for modal
document.addEventListener('DOMContentLoaded', function() {
    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideModal();
        }
    });
});
</script>
@endpush

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[10003] hidden">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4 transform transition-all">
        <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-100 dark:bg-red-900/20 rounded-full">
            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
        </div>
        
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white text-center mb-2">Confirmar Exclusão</h3>
        <p class="text-gray-600 dark:text-gray-400 text-center mb-6">Tem certeza que deseja excluir esta categoria? Esta ação não pode ser desfeita.</p>
        
        <div class="flex gap-3 space-x-0">
            <button type="button" onclick="cancelDelete()" class="flex-1 px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                Cancelar
            </button>
            <button type="button" onclick="confirmDelete()" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center">
                <span id="deleteSpinner" class="hidden w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></span>
                Excluir
            </button>
        </div>
    </div>
</div>

@endsection