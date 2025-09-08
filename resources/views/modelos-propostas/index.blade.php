@extends('layouts.app')

@section('title', 'Modelos de Propostas - Giro')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('dashboard'), 'icon' => 'fas fa-home'],
        ['label' => 'Modelos de Propostas']
    ]" />
    
    <!-- Header com título e toggle de busca -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Modelos de Propostas</h1>
            <button id="search-toggle" class="p-2 rounded-lg text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700" title="Buscar">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Search Container (Hidden by default) -->
    <div id="search-container" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6 hidden">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Buscar Modelos</h3>
            <button id="close-search" class="p-2 rounded-lg text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form method="GET" action="{{ route('modelos-propostas.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" 
                           placeholder="Nome do modelo..." 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="categoria" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Categoria</label>
                    <select id="categoria" name="categoria" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Todas as categorias</option>
                        <option value="servicos" {{ request('categoria') == 'servicos' ? 'selected' : '' }}>Serviços</option>
                        <option value="produtos" {{ request('categoria') == 'produtos' ? 'selected' : '' }}>Produtos</option>
                        <option value="consultoria" {{ request('categoria') == 'consultoria' ? 'selected' : '' }}>Consultoria</option>
                        <option value="manutencao" {{ request('categoria') == 'manutencao' ? 'selected' : '' }}>Manutenção</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        Filtrar
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-100">Total de Modelos</p>
                    <p class="text-2xl font-bold text-white">{{ $modelos->total() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-emerald-100">Modelos Ativos</p>
                    <p class="text-2xl font-bold text-white">{{ $modelos->count() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-amber-100">Mais Usado</p>
                    <p class="text-2xl font-bold text-white">{{ $modelos->sortByDesc('orcamentos_count')->first()->orcamentos_count ?? 0 }} usos</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    

    
    <!-- Models Grid -->
    @if($modelos->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="models-grid">
            @foreach($modelos as $modelo)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg p-6 transition-all duration-200 overflow-hidden model-card modelo-card flex flex-col" data-model-id="{{ $modelo->id }}">
                    <!-- Model Header -->
                    <div class="">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-14 h-14 rounded-full flex items-center justify-center text-2xl overflow-hidden bg-blue-100 dark:bg-blue-900">
                                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white modelo-name">{{ $modelo->nome }}</h3>
                                    @if($modelo->descricao)
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($modelo->descricao, 40) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Badges Row -->
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                @switch($modelo->categoria)
                                    @case('servicos')
                                        bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                        @break
                                    @case('produtos')
                                        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @break
                                    @case('consultoria')
                                        bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                        @break
                                    @case('manutencao')
                                        bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                        @break
                                    @default
                                        bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                @endswitch
                            ">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="modelo-category">{{ ucfirst($modelo->categoria) }}</span>
                            </span>
                            
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                {{ $modelo->usos ?? 0 }} usos
                            </span>
                        </div>
                    </div>

                    <!-- Model Details -->
                    <div class="px-6 pb-6">
                        <div class="space-y-4">
                            <!-- Creation Date -->
                            <div class="text-center bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Criado em</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $modelo->created_at->format('d/m/Y') }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $modelo->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Flex grow para empurrar os botões para o rodapé -->
                    <div class="flex-grow"></div>

                    <!-- Actions Footer -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700 mt-auto">
                        <div class="flex space-x-3">
                            <a href="{{ route('modelos-propostas.show', $modelo) }}" class="p-2 rounded-lg text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-blue-900/20" title="Visualizar">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <button onclick="useModel({{ $modelo->id }})" class="p-2 rounded-lg text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 transition-all duration-200 hover:bg-green-50 dark:hover:bg-green-900/20" title="Usar Modelo">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                            <a href="{{ route('modelos-propostas.edit', $modelo) }}" class="p-2 rounded-lg text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700" title="Editar">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                        <div class="flex space-x-3">
                            <button onclick="duplicateModel({{ $modelo->id }})" class="p-2 rounded-lg text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300 transition-all duration-200 hover:bg-purple-50 dark:hover:bg-purple-900/20" title="Duplicar">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                            <button onclick="deleteModel({{ $modelo->id }})" class="p-2 rounded-lg text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-all duration-200 hover:bg-red-50 dark:hover:bg-red-900/20" title="Excluir">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Paginação -->
        @if($modelos->hasPages())
            <div class="mt-6">
                {{ $modelos->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhum modelo encontrado</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comece criando um novo modelo de proposta.</p>
            <div class="mt-6">
                <a href="{{ route('modelos-propostas.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Novo Modelo
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Floating Action Button -->
<div class="fixed bottom-6 right-6 z-50">
    <a href="{{ route('modelos-propostas.create') }}" 
       class="group bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110 flex items-center justify-center"
       title="Novo Modelo">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        <!-- Tooltip -->
        <span class="absolute right-full mr-3 px-3 py-2 bg-gray-900 text-white text-sm rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
            Novo Modelo
        </span>
    </a>
</div>

<script>
// Search toggle functionality
function toggleSearch() {
    const searchContainer = document.getElementById('search-container');
    const searchToggle = document.getElementById('search-toggle');
    
    if (searchContainer.classList.contains('hidden')) {
        searchContainer.classList.remove('hidden');
        searchToggle.querySelector('svg').style.transform = 'rotate(180deg)';
    } else {
        searchContainer.classList.add('hidden');
        searchToggle.querySelector('svg').style.transform = 'rotate(0deg)';
    }
}

// Close search
const closeSearchBtn = document.getElementById('close-search');
if (closeSearchBtn) {
    closeSearchBtn.addEventListener('click', function() {
        const searchContainer = document.getElementById('search-container');
        const searchToggle = document.getElementById('search-toggle');
        if (searchContainer) {
            searchContainer.classList.add('hidden');
        }
        if (searchToggle) {
            const svg = searchToggle.querySelector('svg');
            if (svg) {
                svg.style.transform = 'rotate(0deg)';
            }
        }
    });
}

// Model actions
function useModel(id) {
    // Redirect to create budget with model
    window.location.href = `/orcamentos/create?modelo_id=${id}`;
}

function duplicateModel(id) {
    if (confirm('Deseja duplicar este modelo?')) {
        // Create and submit form for duplication
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/modelos-propostas/${id}/duplicate`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        const metaToken = document.querySelector('meta[name="csrf-token"]');
        if (metaToken) {
            csrfToken.value = metaToken.getAttribute('content');
        }
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteModel(id) {
    if (confirm('Tem certeza que deseja excluir este modelo? Esta ação não pode ser desfeita.')) {
        // Create and submit form for deletion
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/modelos-propostas/${id}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        const metaToken = document.querySelector('meta[name="csrf-token"]');
        if (metaToken) {
            csrfToken.value = metaToken.getAttribute('content');
        }
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all elements with null checks
        const searchToggle = document.getElementById('search-toggle');
        const searchContainer = document.getElementById('search-container');
        const searchInput = document.getElementById('modelo-search');
        const clearSearch = document.getElementById('clear-search');
        const closeSearch = document.getElementById('close-search');
        
        // Toggle search container
        if (searchToggle && searchContainer) {
            searchToggle.addEventListener('click', function() {
                searchContainer.classList.toggle('hidden');
                if (!searchContainer.classList.contains('hidden') && searchInput) {
                    searchInput.focus();
                }
            });
        }
        
        // Close search
        if (closeSearch && searchContainer) {
            closeSearch.addEventListener('click', function() {
                searchContainer.classList.add('hidden');
                if (searchInput) {
                    searchInput.value = '';
                }
                if (clearSearch) {
                    clearSearch.classList.add('hidden');
                }
            });
        }
        
        // Clear search
        if (clearSearch && searchInput) {
            clearSearch.addEventListener('click', function() {
                searchInput.value = '';
                clearSearch.classList.add('hidden');
                searchInput.focus();
            });
        }
        
        // Show/hide clear button
        if (searchInput && clearSearch) {
            searchInput.addEventListener('input', function() {
                if (this.value.length > 0) {
                    clearSearch.classList.remove('hidden');
                } else {
                    clearSearch.classList.add('hidden');
                }
            });
        }
        
        // Search functionality
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const modelCards = document.querySelectorAll('.modelo-card');
                
                if (modelCards.length > 0) {
                    modelCards.forEach(card => {
                        const modelName = card.querySelector('.modelo-name');
                        const modelCategory = card.querySelector('.modelo-category');
                        
                        if (modelName && modelCategory) {
                            const nameText = modelName.textContent.toLowerCase();
                            const categoryText = modelCategory.textContent.toLowerCase();
                            
                            if (nameText.includes(searchTerm) || categoryText.includes(searchTerm)) {
                                card.style.display = '';
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });
                }
            });
        }
    });
</script>
@endpush