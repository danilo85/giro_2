@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Categorias de Portfólio</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Gerencie as categorias dos seus trabalhos de portfólio</p>
                </div>
                <button @click="$dispatch('open-modal', 'create-category')" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nova Categoria
                </button>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total de Categorias</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $categories->total() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Categorias Ativas</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $categories->where('is_active', true)->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total de Trabalhos</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $categories->sum('works_count') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
            <div class="p-6">
                <form method="GET" action="{{ route('portfolio.categories.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Buscar</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               placeholder="Nome ou descrição..."
                               class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativas</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativas</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ordenar por</label>
                        <select name="sort" id="sort" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="order" {{ request('sort', 'order') === 'order' ? 'selected' : '' }}>Ordem</option>
                            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nome</option>
                            <option value="works_count" {{ request('sort') === 'works_count' ? 'selected' : '' }}>Nº de Trabalhos</option>
                            <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Data de Criação</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Filtrar
                        </button>
                        <a href="{{ route('portfolio.categories.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Limpar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md">
            @if($categories->count() > 0)
                <ul class="divide-y divide-gray-200 dark:divide-gray-700" x-data="{ draggedItem: null, draggedOver: null }">
                    @foreach($categories as $category)
                        <li class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-move"
                            draggable="true"
                            @dragstart="draggedItem = {{ $category->id }}"
                            @dragover.prevent="draggedOver = {{ $category->id }}"
                            @dragleave="draggedOver = null"
                            @drop.prevent="updateOrder(draggedItem, {{ $category->id }}); draggedItem = null; draggedOver = null"
                            :class="{ 'bg-blue-50 dark:bg-blue-900': draggedOver === {{ $category->id }} }">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-lg flex items-center justify-center" 
                                             style="background-color: {{ $category->color ?? '#6B7280' }}">
                                            <span class="text-white font-semibold text-sm">{{ strtoupper(substr($category->name, 0, 2)) }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2">
                                            <h3 class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $category->name }}</h3>
                                            @if(!$category->is_active)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    Inativa
                                                </span>
                                            @endif
                                        </div>
                                        @if($category->description)
                                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $category->description }}</p>
                                        @endif
                                        <div class="flex items-center space-x-4 mt-1">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $category->works_count }} trabalhos</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">Ordem: {{ $category->order }}</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $category->created_at->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <!-- Toggle Status -->
                                    <button @click="toggleStatus({{ $category->id }})" 
                                            class="p-2 rounded-md {{ $category->is_active ? 'text-green-600 hover:bg-green-100 dark:hover:bg-green-900' : 'text-red-600 hover:bg-red-100 dark:hover:bg-red-900' }} transition-colors"
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
                                    <button @click="editCategory({{ $category->toJson() }})" 
                                            class="p-2 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900 rounded-md transition-colors"
                                            title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    
                                    <!-- Delete -->
                                    @if($category->works_count === 0)
                                        <button @click="deleteCategory({{ $category->id }}, '{{ $category->name }}')" 
                                                class="p-2 text-red-600 hover:bg-red-100 dark:hover:bg-red-900 rounded-md transition-colors"
                                                title="Excluir">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    @else
                                        <span class="p-2 text-gray-400 cursor-not-allowed" title="Não é possível excluir categoria com trabalhos">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </span>
                                    @endif
                                    
                                    <!-- Drag Handle -->
                                    <div class="p-2 text-gray-400 cursor-move">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhuma categoria encontrada</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comece criando uma nova categoria para organizar seus trabalhos.</p>
                    <div class="mt-6">
                        <button @click="$dispatch('open-modal', 'create-category')" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Nova Categoria
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($categories->hasPages())
            <div class="mt-6">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Create/Edit Category Modal -->
<x-modal name="create-category" :show="false" maxWidth="md">
    <div class="p-6" x-data="categoryModal()">
        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4" x-text="editMode ? 'Editar Categoria' : 'Nova Categoria'"></h2>
        
        <form @submit.prevent="submitForm()">
            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome *</label>
                    <input type="text" id="name" x-model="form.name" required
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <div x-show="errors.name" class="mt-1 text-sm text-red-600" x-text="errors.name"></div>
                </div>
                
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição</label>
                    <textarea id="description" x-model="form.description" rows="3"
                              class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    <div x-show="errors.description" class="mt-1 text-sm text-red-600" x-text="errors.description"></div>
                </div>
                
                <!-- Color -->
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cor</label>
                    <div class="mt-1 flex items-center space-x-3">
                        <input type="color" id="color" x-model="form.color"
                               class="h-10 w-16 border border-gray-300 dark:border-gray-600 rounded-md">
                        <input type="text" x-model="form.color" placeholder="#6B7280"
                               class="flex-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div x-show="errors.color" class="mt-1 text-sm text-red-600" x-text="errors.color"></div>
                </div>
                
                <!-- Status -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" x-model="form.is_active" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Categoria ativa</span>
                    </label>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" @click="$dispatch('close-modal', 'create-category')" 
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Cancelar
                </button>
                <button type="submit" :disabled="loading"
                        class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-bold py-2 px-4 rounded">
                    <span x-show="!loading" x-text="editMode ? 'Atualizar' : 'Criar'"></span>
                    <span x-show="loading">Salvando...</span>
                </button>
            </div>
        </form>
    </div>
</x-modal>

@push('scripts')
<script>
function categoryModal() {
    return {
        editMode: false,
        loading: false,
        form: {
            id: null,
            name: '',
            description: '',
            color: '#6B7280',
            is_active: true
        },
        errors: {},
        
        init() {
            this.$watch('$store.modal.show', (show) => {
                if (!show) {
                    this.resetForm();
                }
            });
        },
        
        resetForm() {
            this.editMode = false;
            this.form = {
                id: null,
                name: '',
                description: '',
                color: '#6B7280',
                is_active: true
            };
            this.errors = {};
        },
        
        async submitForm() {
            this.loading = true;
            this.errors = {};
            
            try {
                const url = this.editMode 
                    ? `/portfolio/categories/${this.form.id}`
                    : '/portfolio/categories';
                    
                const formData = new FormData();
                
                // Adicionar dados do formulário
                Object.keys(this.form).forEach(key => {
                    if (key !== 'id' || this.editMode) {
                        let value = this.form[key];
                        // Converter booleano para string para o Laravel
                        if (key === 'is_active') {
                            value = value ? '1' : '0';
                        }
                        formData.append(key, value);
                    }
                });
                
                // Adicionar método para PUT se estiver editando
                if (this.editMode) {
                    formData.append('_method', 'PUT');
                }
                
                // Adicionar token CSRF
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
                    this.$dispatch('close-modal', 'create-category');
                    window.location.reload();
                } else {
                    this.errors = data.errors || {};
                    if (data.message) {
                        console.error('Erro:', data.message);
                    }
                }
            } catch (error) {
                console.error('Erro ao salvar categoria:', error);
            } finally {
                this.loading = false;
            }
        }
    }
}

function editCategory(category) {
    Alpine.store('categoryModal', {
        editMode: true,
        form: {
            id: category.id,
            name: category.name,
            description: category.description || '',
            color: category.color || '#6B7280',
            is_active: category.is_active
        }
    });
    
    Alpine.$dispatch('open-modal', 'create-category');
}

async function toggleStatus(categoryId) {
    try {
        const response = await fetch(`/portfolio/categories/${categoryId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            window.location.reload();
        }
    } catch (error) {
        console.error('Erro ao alterar status:', error);
    }
}

async function updateOrder(draggedId, targetId) {
    if (draggedId === targetId) return;
    
    try {
        const response = await fetch('/portfolio/categories/update-order', {
            method: 'POST',
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

async function deleteCategory(categoryId, categoryName) {
    if (!confirm(`Tem certeza que deseja excluir a categoria "${categoryName}"?`)) {
        return;
    }
    
    try {
        const response = await fetch(`/portfolio/categories/${categoryId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            window.location.reload();
        }
    } catch (error) {
        console.error('Erro ao excluir categoria:', error);
    }
}
</script>
@endpush
@endsection