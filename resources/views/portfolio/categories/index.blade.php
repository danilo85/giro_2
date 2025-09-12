@extends('layouts.app')

@section('title', 'Categorias de Portfólio')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900" x-data="categoriesPage()">
<div class="max-w-7xl mx-auto">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard'), 'icon' => 'fas fa-home'],
        ['label' => 'Portfólio', 'url' => '#'],
        ['label' => 'Categorias']
    ]" />

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Categorias de Portfólio</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Gerencie as categorias dos seus trabalhos de portfólio</p>
            </div>
        </div>
    </div>

<div>
    <!-- Cards de Resumo -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total de Categorias -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total de Categorias</p>
                    <p class="text-2xl font-bold">{{ $categories->total() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Categorias Ativas -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Categorias Ativas</p>
                    <p class="text-2xl font-bold">{{ $categories->where('is_active', true)->count() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total de Trabalhos -->
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Total de Trabalhos</p>
                    <p class="text-2xl font-bold">{{ $categories->sum('works_count') }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Categorias Inativas -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Categorias Inativas</p>
                    <p class="text-2xl font-bold">{{ $categories->where('is_active', false)->count() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

<div class="">
    <div class="text-gray-900 dark:text-gray-100">
        <!-- Filtro de Busca -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" 
                       id="search" 
                       name="search" 
                       class="block w-full pl-10 pr-12 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" 
                       placeholder="Buscar por nome ou descrição..." 
                       value="{{ request('search') }}">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <button type="button" 
                            id="clear-search" 
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" 
                            style="display: none;">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Grid de Cards de Categorias -->
        @if($categories->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="categories-grid">
                @foreach($categories as $category)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-200 group flex flex-col h-full" 
                         data-category-id="{{ $category->id }}" 
                         draggable="true">
                        <!-- Header do Card -->
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="h-12 w-12 rounded-full flex items-center justify-center flex-shrink-0" 
                                         style="background-color: {{ $category->color ?? '#6B7280' }}">
                                        <span class="text-white font-bold text-lg">{{ strtoupper(substr($category->name, 0, 2)) }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">{{ $category->name }}</h3>
                                        <div class="flex items-center space-x-2 mt-1">
                                            @if($category->is_active)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Ativa
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Inativa
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- Drag Handle -->
                                <div class="p-1 text-gray-400 cursor-move opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Descrição -->
                            @if($category->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">{{ $category->description }}</p>
                            @endif
                            
                            <!-- Estatísticas -->
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $category->works_count }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Trabalhos</div>
                                </div>
                                <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $category->order }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Ordem</div>
                                </div>
                            </div>
                            
                            <!-- Data de Criação -->
                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                                Criada em {{ $category->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                        
                        <!-- Flex grow para empurrar os botões para o rodapé -->
                        <div class="flex-grow"></div>
                        
                        <!-- Footer com Ações -->
                        <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200 dark:border-gray-700 mt-auto">
                            <div class="flex space-x-3">
                                <!-- Toggle Status -->
                                <button @click="toggleStatus({{ $category->id }})" 
                                        class="p-2 rounded-lg {{ $category->is_active ? 'text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 transition-all duration-200 hover:bg-green-50 dark:hover:bg-green-900/20' : 'text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-all duration-200 hover:bg-red-50 dark:hover:bg-red-900/20' }}"
                                        title="{{ $category->is_active ? 'Desativar' : 'Ativar' }}">
                                    @if($category->is_active)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @endif
                                </button>
                                
                                <!-- Edit -->
                                <button onclick="openEditModal({{ $category->toJson() }})" 
                                        class="p-2 rounded-lg text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-blue-900/20"
                                        title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="flex space-x-3">
                                <!-- Delete -->
                                <button onclick="openDeleteModal({{ $category->id }}, '{{ $category->name }}', {{ $category->works_count ?? 0 }})" 
                                        class="p-2 rounded-lg text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-all duration-200 hover:bg-red-50 dark:hover:bg-red-900/20"
                                        title="Excluir Categoria">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Estado Vazio -->
            <div class="text-center py-16">
                <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Nenhuma categoria encontrada</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto">Comece criando uma nova categoria para organizar seus trabalhos de portfólio de forma eficiente.</p>
                <button onclick="openCreateModal()" 
                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nova Categoria
                </button>
            </div>
        @endif

        <!-- Pagination -->
        @if($categories->hasPages())
            <div class="mt-6">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
    
    <!-- Botão Flutuante -->
    <div class="fixed bottom-6 right-6 z-50">
        <button onclick="openCreateModal()" 
                class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg hover:shadow-xl transition-all duration-200 group">
            <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        </button>
    </div>
</div>
</div>

<!-- Universal Modal Container -->
<div id="universal-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>
        
        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div id="modal-content">
                <!-- Dynamic content will be inserted here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Função Alpine.js para categoriesPage
    function categoriesPage() {
        return {
            // Estado do modal
            showModal: false,
            modalType: '',
            modalData: {},
            loading: false,
            errors: {},
            
            // Métodos do Alpine.js
            init() {
                console.log('Categories page initialized');
            },
            
            // Abrir modal de criação
            openCreateModal() {
                openCreateModal();
            },
            
            // Abrir modal de edição
            openEditModal(category) {
                openEditModal(category);
            },
            
            // Abrir modal de exclusão
            openDeleteModal(categoryId, categoryName, worksCount) {
                openDeleteModal(categoryId, categoryName, worksCount);
            },
            
            // Toggle status da categoria
            toggleStatus(categoryId) {
                toggleStatus(categoryId);
            },
            
            // Fechar modal
            closeModal() {
                closeModal();
            }
        };
    }
    
    // Global modal state
    let modalState = {
        isOpen: false,
        type: null, // 'create', 'edit', 'delete'
        data: {},
        loading: false,
        errors: {}
    };
    
    // Modal control functions
    function openModal() {
        const modal = document.getElementById('universal-modal');
        modal.classList.remove('hidden');
        modalState.isOpen = true;
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal() {
        const modal = document.getElementById('universal-modal');
        modal.classList.add('hidden');
        modalState.isOpen = false;
        modalState.type = null;
        modalState.data = {};
        modalState.loading = false;
        modalState.errors = {};
        document.body.style.overflow = '';
    }
    
    // Create modal content
    function openCreateModal() {
        modalState.type = 'create';
        modalState.data = {
            name: '',
            description: '',
            color: '#6B7280',
            is_active: true
        };
        renderModalContent();
        openModal();
    }
    
    // Edit modal content
    function openEditModal(category) {
        modalState.type = 'edit';
        modalState.data = { ...category };
        renderModalContent();
        openModal();
    }
    
    // Delete modal content
    function openDeleteModal(categoryId, categoryName, worksCount) {
        modalState.type = 'delete';
        modalState.data = {
            id: categoryId,
            name: categoryName,
            worksCount: worksCount
        };
        renderModalContent();
        openModal();
    }
    
    // Render modal content based on type
    function renderModalContent() {
        const content = document.getElementById('modal-content');
        
        if (modalState.type === 'create' || modalState.type === 'edit') {
            content.innerHTML = renderFormModal();
        } else if (modalState.type === 'delete') {
            content.innerHTML = renderDeleteModal();
        }
    }
    
    // Form modal template
    function renderFormModal() {
        const isEdit = modalState.type === 'edit';
        const title = isEdit ? 'Editar Categoria' : 'Nova Categoria';
        const buttonText = isEdit ? 'Atualizar' : 'Criar';
        
        return `
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">${title}</h2>
                
                <form onsubmit="submitForm(event)">
                    <div class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label for="modal-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome *</label>
                            <input type="text" id="modal-name" value="${modalState.data.name || ''}" required
                                   class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   onchange="updateFormData('name', this.value)">
                            <div id="error-name" class="mt-1 text-sm text-red-600 hidden"></div>
                        </div>
                        
                        <!-- Description -->
                        <div>
                            <label for="modal-description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição</label>
                            <textarea id="modal-description" rows="3"
                                      class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                      onchange="updateFormData('description', this.value)">${modalState.data.description || ''}</textarea>
                            <div id="error-description" class="mt-1 text-sm text-red-600 hidden"></div>
                        </div>
                        
                        <!-- Color -->
                        <div>
                            <label for="modal-color" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cor</label>
                            <div class="mt-1 flex items-center space-x-3">
                                <input type="color" id="modal-color" value="${modalState.data.color || '#6B7280'}"
                                       class="h-10 w-16 border border-gray-300 dark:border-gray-600 rounded-md"
                                       onchange="updateFormData('color', this.value); document.getElementById('modal-color-text').value = this.value">
                                <input type="text" id="modal-color-text" value="${modalState.data.color || '#6B7280'}" placeholder="#6B7280"
                                       class="flex-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       onchange="updateFormData('color', this.value); document.getElementById('modal-color').value = this.value">
                            </div>
                            <div id="error-color" class="mt-1 text-sm text-red-600 hidden"></div>
                        </div>
                        
                        <!-- Status -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" id="modal-is-active" ${modalState.data.is_active ? 'checked' : ''}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                       onchange="updateFormData('is_active', this.checked)">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Categoria ativa</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeModal()" 
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancelar
                        </button>
                        <button type="submit" id="submit-btn"
                                class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-bold py-2 px-4 rounded">
                            <span id="submit-text">${buttonText}</span>
                            <span id="submit-loading" class="hidden">Salvando...</span>
                        </button>
                    </div>
                </form>
            </div>
        `;
    }
    
    // Delete modal template
    function renderDeleteModal() {
        const worksMessage = modalState.data.worksCount > 0 
            ? `Esta categoria possui ${modalState.data.worksCount} trabalho(s) associado(s) que também serão removidos.`
            : '';
            
        return `
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Confirmar Exclusão de Categoria</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Tem certeza que deseja excluir a categoria "${modalState.data.name}"? ${worksMessage}
                        </p>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-center space-x-3">
                    <button type="button" onclick="closeModal()" 
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold px-6 py-2 rounded">
                        Cancelar
                    </button>
                    <button type="button" onclick="confirmDelete()" id="delete-btn"
                            class="bg-red-600 hover:bg-red-700 disabled:opacity-50 text-white font-bold px-6 py-2 rounded">
                        <span id="delete-text">Excluir Categoria</span>
                        <span id="delete-loading" class="hidden">Excluindo...</span>
                    </button>
                </div>
            </div>
        `;
    }
    
    // Update form data
    function updateFormData(field, value) {
        modalState.data[field] = value;
    }
    
    // Submit form
    function submitForm(event) {
        event.preventDefault();
        
        modalState.loading = true;
        modalState.errors = {};
        
        const submitBtn = document.getElementById('submit-btn');
        const submitText = document.getElementById('submit-text');
        const submitLoading = document.getElementById('submit-loading');
        
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        submitLoading.classList.remove('hidden');
        
        const isEdit = modalState.type === 'edit';
        const url = isEdit 
            ? `/portfolio/categories/${modalState.data.id}`
            : '/portfolio/categories';
        const method = isEdit ? 'PUT' : 'POST';
        
        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(modalState.data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                closeModal();
                setTimeout(() => window.location.reload(), 1000);
            } else {
                if (data.errors) {
                    modalState.errors = data.errors;
                    showFormErrors();
                } else {
                    showToast(data.message || 'Erro ao salvar categoria', 'error');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Erro ao salvar categoria', 'error');
        })
        .finally(() => {
            modalState.loading = false;
            submitBtn.disabled = false;
            submitText.classList.remove('hidden');
            submitLoading.classList.add('hidden');
        });
    }
    
    // Show form errors
    function showFormErrors() {
        Object.keys(modalState.errors).forEach(field => {
            const errorDiv = document.getElementById(`error-${field}`);
            if (errorDiv) {
                errorDiv.textContent = modalState.errors[field][0];
                errorDiv.classList.remove('hidden');
            }
        });
    }
    
    // Confirm delete
    function confirmDelete() {
        modalState.loading = true;
        
        const deleteBtn = document.getElementById('delete-btn');
        const deleteText = document.getElementById('delete-text');
        const deleteLoading = document.getElementById('delete-loading');
        
        deleteBtn.disabled = true;
        deleteText.classList.add('hidden');
        deleteLoading.classList.remove('hidden');
        
        fetch(`/portfolio/categories/${modalState.data.id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                closeModal();
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showToast(data.message || 'Erro ao excluir categoria', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Erro ao excluir categoria', 'error');
        })
        .finally(() => {
            modalState.loading = false;
            deleteBtn.disabled = false;
            deleteText.classList.remove('hidden');
            deleteLoading.classList.add('hidden');
        });
    }
    
    // Toggle category status
    function toggleStatus(categoryId) {
        fetch(`/portfolio/categories/${categoryId}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showToast(data.message || 'Erro ao alterar status', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Erro ao alterar status da categoria', 'error');
        });
    }
    
    // Close modal on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && modalState.isOpen) {
            closeModal();
        }
    });



// Sistema de Modal Global com JavaScript Vanilla
const ModalSystem = {
    currentModal: null,
    modalData: {},
    
    init() {
        // Inicializar event listeners
        this.setupEventListeners();
    },
    
    setupEventListeners() {
        // Event delegation para botões
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-action="create-category"]')) {
                this.openCreateModal();
            }
            if (e.target.matches('[data-action="edit-category"]')) {
                const categoryData = JSON.parse(e.target.getAttribute('data-category'));
                this.openEditModal(categoryData);
            }
            if (e.target.matches('[data-action="delete-category"]')) {
                const categoryId = e.target.getAttribute('data-id');
                const categoryName = e.target.getAttribute('data-name');
                const worksCount = e.target.getAttribute('data-works-count') || 0;
                this.openDeleteModal(categoryId, categoryName, worksCount);
            }
            if (e.target.matches('[data-action="close-modal"]')) {
                this.closeModal();
            }
            if (e.target.matches('[data-action="submit-form"]')) {
                this.submitForm();
            }
            if (e.target.matches('[data-action="confirm-delete"]')) {
                this.confirmDelete();
            }
        });
        
        // Fechar modal ao clicar no backdrop
        document.addEventListener('click', (e) => {
            if (e.target.matches('.modal-backdrop')) {
                this.closeModal();
            }
        });
        
        // Fechar modal com ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.currentModal) {
                this.closeModal();
            }
        });
    },
    
    openModal(type, data = {}) {
        this.currentModal = type;
        this.modalData = data;
        
        const modal = document.getElementById('universal-modal');
        const modalContent = document.getElementById('modal-content');
        
        if (modal && modalContent) {
            modalContent.innerHTML = this.renderModalContent(type, data);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Focus no primeiro input
            setTimeout(() => {
                const firstInput = modal.querySelector('input, textarea, select');
                if (firstInput) firstInput.focus();
            }, 100);
        }
    },
    
    closeModal() {
        const modal = document.getElementById('universal-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        this.currentModal = null;
        this.modalData = {};
    },
    
    openCreateModal() {
        this.openModal('create');
    },
    
    openEditModal(category) {
        this.openModal('edit', category);
    },
    
    openDeleteModal(categoryId, categoryName, worksCount) {
        this.openModal('delete', { id: categoryId, name: categoryName, worksCount: worksCount });
    },
    
    renderModalContent(type, data) {
        switch (type) {
            case 'create':
                return this.renderCreateEditForm(false, {});
            case 'edit':
                return this.renderCreateEditForm(true, data);
            case 'delete':
                return this.renderDeleteConfirmation(data);
            default:
                return '';
        }
    },
    
    renderCreateEditForm(isEdit, data) {
        const title = isEdit ? 'Editar Categoria' : 'Nova Categoria';
        const submitText = isEdit ? 'Atualizar' : 'Criar';
        
        return `
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">${title}</h3>
                    <button type="button" data-action="close-modal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="category-form" class="p-6 space-y-4">
                    ${isEdit ? `<input type="hidden" name="id" value="${data.id || ''}">` : ''}
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                        <input type="text" id="name" name="name" value="${data.name || ''}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               required>
                        <div id="name-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                        <textarea id="description" name="description" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">${data.description || ''}</textarea>
                        <div id="description-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Cor</label>
                        <input type="color" id="color" name="color" value="${data.color || '#6B7280'}" 
                               class="w-full h-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" ${(data.is_active !== false) ? 'checked' : ''} 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">Categoria ativa</label>
                    </div>
                </form>
                
                <div class="flex justify-end space-x-3 p-6 border-t border-gray-200">
                    <button type="button" data-action="close-modal" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                        Cancelar
                    </button>
                    <button type="button" data-action="submit-form" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                        ${submitText}
                    </button>
                </div>
            </div>
        `;
    },
    
    renderDeleteConfirmation(data) {
        const message = data.worksCount > 0 
            ? `A categoria "${data.name}" possui ${data.worksCount} trabalho(s) associado(s). Tem certeza que deseja excluí-la? Todos os trabalhos serão desvinculados desta categoria.`
            : `Tem certeza que deseja excluir a categoria "${data.name}"? Esta ação não pode ser desfeita.`;
            
        return `
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Confirmar Exclusão</h3>
                    <button type="button" data-action="close-modal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-700">${message}</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 p-6 border-t border-gray-200">
                    <button type="button" data-action="close-modal" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                        Cancelar
                    </button>
                    <button type="button" data-action="confirm-delete" 
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md">
                        Excluir
                    </button>
                </div>
            </div>
        `;
    },
    
    async submitForm() {
        const form = document.getElementById('category-form');
        if (!form) return;
        
        const formData = new FormData(form);
        const isEdit = this.currentModal === 'edit';
        
        // Limpar erros anteriores
        document.querySelectorAll('[id$="-error"]').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        
        try {
            const url = isEdit 
                ? `/portfolio/categories/${this.modalData.id}`
                : '/portfolio/categories';
                
            if (isEdit) {
                formData.append('_method', 'PUT');
            }
            
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (response.ok && data.success) {
                this.closeModal();
                showToast(data.message || 'Categoria salva com sucesso!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const errorEl = document.getElementById(`${field}-error`);
                        if (errorEl) {
                            errorEl.textContent = data.errors[field][0];
                            errorEl.classList.remove('hidden');
                        }
                    });
                } else {
                    showToast(data.message || 'Erro ao salvar categoria', 'error');
                }
            }
        } catch (error) {
            console.error('Erro ao salvar categoria:', error);
            showToast('Erro de conexão. Tente novamente.', 'error');
        }
    },
    
    async confirmDelete() {
        if (!this.modalData.id) {
            showToast('Erro: ID da categoria não encontrado', 'error');
            return;
        }
        
        try {
            const response = await fetch(`/portfolio/categories/${this.modalData.id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (response.ok && data.success) {
                this.closeModal();
                showToast(data.message || 'Categoria excluída com sucesso!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showToast(data.message || 'Erro ao excluir categoria', 'error');
            }
        } catch (error) {
            console.error('Erro ao excluir categoria:', error);
            showToast('Erro de conexão. Tente novamente.', 'error');
        }
    },
    
    // Função para toggle de status (mantida separada)
    async toggleStatus(categoryId) {
        try {
            const response = await fetch(`/portfolio/categories/${categoryId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (response.ok && data.success) {
                showToast(data.message || 'Status alterado com sucesso!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showToast(data.message || 'Erro ao alterar status', 'error');
            }
        } catch (error) {
            console.error('Erro ao alterar status:', error);
            showToast('Erro de conexão ao alterar status', 'error');
        }
    }
};

// Inicializar o sistema quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    ModalSystem.init();
});

// Função para atualizar a ordem das categorias
async function updateOrder(draggedId, targetId) {
    if (draggedId === targetId) return;
    
    try {
        const response = await fetch('/portfolio/categories/update-order', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                dragged_id: draggedId,
                target_id: targetId
            })
        });
        
        if (response.ok) {
            window.location.reload();
        }
    } catch (error) {
        console.error('Erro ao atualizar ordem:', error);
    }
}

// Drag and Drop functionality para grid de cards
let draggedElement = null;
let draggedId = null;
let isDragging = false;

// Aguardar o DOM estar carregado
document.addEventListener('DOMContentLoaded', function() {
    initializeDragAndDrop();
});

function initializeDragAndDrop() {
    const grid = document.getElementById('categories-grid');
    if (!grid) return;
    
    // Event listeners para drag and drop
    grid.addEventListener('dragstart', handleDragStart);
    grid.addEventListener('dragend', handleDragEnd);
    grid.addEventListener('dragover', handleDragOver);
    grid.addEventListener('dragenter', handleDragEnter);
    grid.addEventListener('dragleave', handleDragLeave);
    grid.addEventListener('drop', handleDrop);
}

function handleDragStart(e) {
    const cardElement = e.target.closest('[data-category-id]');
    if (!cardElement || !cardElement.hasAttribute('draggable')) return;
    
    draggedElement = cardElement;
    draggedId = cardElement.dataset.categoryId;
    isDragging = true;
    
    // Feedback visual para o elemento sendo arrastado
    cardElement.style.opacity = '0.6';
    cardElement.classList.add('transform', 'scale-95', 'rotate-2');
    
    // Adicionar classe para todos os outros cards
    document.querySelectorAll('[data-category-id]').forEach(card => {
        if (card !== cardElement) {
            card.classList.add('transition-all', 'duration-200');
        }
    });
    
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', cardElement.outerHTML);
}

function handleDragEnd(e) {
    const cardElement = e.target.closest('[data-category-id]');
    if (!cardElement) return;
    
    // Restaurar aparência do elemento arrastado
    cardElement.style.opacity = '1';
    cardElement.classList.remove('transform', 'scale-95', 'rotate-2');
    
    // Limpar estado
    draggedElement = null;
    draggedId = null;
    isDragging = false;
    
    // Remove highlight de todos os cards
    document.querySelectorAll('[data-category-id]').forEach(card => {
        card.classList.remove(
            'ring-2', 'ring-blue-500', 'ring-green-500', 
            'bg-blue-50', 'bg-green-50', 'dark:bg-blue-900', 'dark:bg-green-900',
            'transition-all', 'duration-200', 'scale-105'
        );
    });
}

function handleDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
}

function handleDragEnter(e) {
    e.preventDefault();
    
    const dropTarget = e.target.closest('[data-category-id]');
    if (!dropTarget || !isDragging || !draggedId || dropTarget.dataset.categoryId === draggedId) return;
    
    // Remove highlight de outros cards
    document.querySelectorAll('[data-category-id]').forEach(card => {
        if (card !== dropTarget && card !== draggedElement) {
            card.classList.remove(
                'ring-2', 'ring-green-500', 'bg-green-50', 'dark:bg-green-900', 'scale-105'
            );
        }
    });
    
    // Adiciona highlight no card de destino
    dropTarget.classList.add(
        'ring-2', 'ring-green-500', 'bg-green-50', 'dark:bg-green-900', 
        'transition-all', 'duration-200', 'scale-105'
    );
}

function handleDragLeave(e) {
    const dropTarget = e.target.closest('[data-category-id]');
    if (!dropTarget) return;
    
    // Verificar se realmente saiu do elemento
    const rect = dropTarget.getBoundingClientRect();
    const x = e.clientX;
    const y = e.clientY;
    
    if (x < rect.left || x > rect.right || y < rect.top || y > rect.bottom) {
        dropTarget.classList.remove(
            'ring-2', 'ring-green-500', 'bg-green-50', 'dark:bg-green-900', 'scale-105'
        );
    }
}

function handleDrop(e) {
    e.preventDefault();
    
    const dropTarget = e.target.closest('[data-category-id]');
    if (!dropTarget || !draggedId || dropTarget.dataset.categoryId === draggedId) return;
    
    // Feedback visual de sucesso
    dropTarget.classList.add('ring-2', 'ring-blue-500', 'bg-blue-50', 'dark:bg-blue-900');
    
    // Atualizar ordem
    updateOrder(draggedId, dropTarget.dataset.categoryId);
    
    // Remove highlight após um delay
    setTimeout(() => {
        document.querySelectorAll('[data-category-id]').forEach(card => {
            card.classList.remove(
                'ring-2', 'ring-blue-500', 'ring-green-500', 
                'bg-blue-50', 'bg-green-50', 'dark:bg-blue-900', 'dark:bg-green-900',
                'scale-105'
            );
        });
    }, 300);
}

// Reinicializar drag and drop após atualizações AJAX
function reinitializeDragAndDrop() {
    setTimeout(() => {
        initializeDragAndDrop();
    }, 100);
}

// Função para mostrar toast notifications
function showToast(message, type = 'info') {
    // Remove toast anterior se existir
    const existingToast = document.querySelector('.toast-notification');
    if (existingToast) {
        existingToast.remove();
    }
    
    // Criar novo toast
    const toast = document.createElement('div');
    toast.className = 'toast-notification fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300';
    
    // Definir cores baseadas no tipo
    if (type === 'success') {
        toast.className += ' bg-green-500 text-white';
    } else if (type === 'error') {
        toast.className += ' bg-red-500 text-white';
    } else {
        toast.className += ' bg-blue-500 text-white';
    }
    
    toast.textContent = message;
    
    // Adicionar ao DOM
    document.body.appendChild(toast);
    
    // Animar entrada
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 10);
    
    // Remover após 3 segundos
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 3000);
}
</script>
@endpush
@endsection