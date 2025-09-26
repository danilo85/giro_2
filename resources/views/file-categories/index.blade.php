@extends('layouts.app')

@section('title', 'Categorias de Arquivos')

@section('content')
<div class="container mx-auto px-4 py-6">

        <!-- Tags de Navegação Rápida -->
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('files.index') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2v0a2 2 0 012-2h6l2 2h6a2 2 0 012 2v1"></path>
            </svg>
            Meus Arquivos
        </a>
        <a href="{{ route('files.create') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
            Upload
        </a>
        <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-blue-600 text-white">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            Categorias
        </span>
    </div>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Categorias de Arquivos</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Gerencie as categorias para organizar seus arquivos</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-emerald-100">Total de Categorias</p>
                    <p class="text-2xl font-bold text-white">{{ $categories->total() }}</p>
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
                    <p class="text-sm font-medium text-blue-100">Com Arquivos</p>
                    <p class="text-2xl font-bold text-white">{{ $categories->where('files_count', '>', 0)->count() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4l2 2 4-4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-amber-100">Vazias</p>
                    <p class="text-2xl font-bold text-white">{{ $categories->where('files_count', 0)->count() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <!-- Filtro de busca completo -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="flex-1 relative">
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Buscar categorias por nome..."
                           class="w-full pl-10 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    @if(request()->filled('search'))
                        <button type="button" id="clearSearch" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>

            </div>
        </div>
      </div>

    <!-- Categories Grid -->
    <div id="categoriesContainer">
        @if($categories->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($categories as $category)
                    <div class="category-card bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 overflow-hidden flex flex-col">
                        <!-- Category Header -->
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-14 h-14 rounded-full flex items-center justify-center text-white" style="background-color: {{ $category->color }}">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $category->name }}</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $category->files_count ?? 0 }} arquivo(s)</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Badges Row -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Ativa
                                </span>
                                
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium" style="background-color: {{ $category->color }}20; color: {{ $category->color }}">
                                    <div class="w-2 h-2 rounded-full mr-1" style="background-color: {{ $category->color }}"></div>
                                    Categoria
                                </span>
                            </div>
                        </div>

                        <!-- Category Details -->
                        <div class="px-6 pb-6">
                            <div class="space-y-4">
                                <!-- Description -->
                                <div class="text-center bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Descrição</p>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $category->description ?: '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Flex grow para empurrar os botões para o rodapé -->
                        <div class="flex-grow"></div>

                        <!-- Actions Footer -->
                        <div class="flex items-center justify-center px-6 py-4 border-t border-gray-200 dark:border-gray-700 mt-auto">
                            <div class="flex space-x-3">
                                <a href="{{ route('files.index', ['category' => $category->id]) }}" 
                                   class="flex items-center justify-center w-10 h-10 text-blue-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg" 
                                   title="Ver Arquivos">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2v0a2 2 0 012-2h6l2 2h6a2 2 0 012 2v1"></path>
                                    </svg>
                                </a>
                                <button onclick="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->description) }}', '{{ $category->color }}')" 
                                        class="flex items-center justify-center w-10 h-10 text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 transition-colors hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg" 
                                        title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button onclick="deleteCategory({{ $category->id }})" 
                                        class="flex items-center justify-center w-10 h-10 text-red-500 hover:text-red-600 dark:hover:text-red-400 transition-colors hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg" 
                                        title="Excluir">
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
            <div class="text-center py-16">
                <div class="bg-gray-100 dark:bg-gray-700 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 dark:text-gray-300 mb-2">Nenhuma categoria encontrada</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Crie sua primeira categoria para organizar seus arquivos</p>
                <button onclick="openCreateModal()" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Criar Categoria
                </button>
            </div>
        @endif
    </div>
    
    <!-- Pagination -->
    @if($categories->hasPages())
        <div class="mt-8">
            {{ $categories->links() }}
        </div>
    @endif

    <!-- Floating Action Button -->
    <button onclick="openCreateModal()" 
            class="fixed bottom-6 right-6 w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center z-50 group">
        <svg class="w-6 h-6 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
    </button>
</div>



<!-- Create/Edit Modal -->
<div id="categoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Nova Categoria</h3>
                <form id="categoryForm">
                    @csrf
                    <input type="hidden" id="categoryId">
                    <input type="hidden" id="formMethod" value="POST">
                    
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nome da Categoria *
                        </label>
                        <input type="text" id="name" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Digite o nome da categoria">
                        <div id="nameError" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Descrição
                        </label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Descrição opcional da categoria"></textarea>
                        <div id="descriptionError" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Cor da Categoria *
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="color" id="color" name="color" value="#3B82F6" required
                                   class="w-12 h-10 border border-gray-300 dark:border-gray-600 rounded-md cursor-pointer">
                            <div class="flex-1">
                                <input type="text" id="colorHex" value="#3B82F6" 
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="#000000">
                            </div>
                        </div>
                        <div id="colorError" class="text-red-500 text-sm mt-1 hidden"></div>
                        
                        <!-- Color Presets -->
                        <div class="mt-3">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Cores sugeridas:</p>
                            <div class="flex space-x-2">
                                <button type="button" onclick="setColor('#3B82F6')" class="w-8 h-8 rounded-full border-2 border-gray-300" style="background-color: #3B82F6" title="Azul"></button>
                                <button type="button" onclick="setColor('#10B981')" class="w-8 h-8 rounded-full border-2 border-gray-300" style="background-color: #10B981" title="Verde"></button>
                                <button type="button" onclick="setColor('#F59E0B')" class="w-8 h-8 rounded-full border-2 border-gray-300" style="background-color: #F59E0B" title="Amarelo"></button>
                                <button type="button" onclick="setColor('#EF4444')" class="w-8 h-8 rounded-full border-2 border-gray-300" style="background-color: #EF4444" title="Vermelho"></button>
                                <button type="button" onclick="setColor('#8B5CF6')" class="w-8 h-8 rounded-full border-2 border-gray-300" style="background-color: #8B5CF6" title="Roxo"></button>
                                <button type="button" onclick="setColor('#EC4899')" class="w-8 h-8 rounded-full border-2 border-gray-300" style="background-color: #EC4899" title="Rosa"></button>
                                <button type="button" onclick="setColor('#6B7280')" class="w-8 h-8 rounded-full border-2 border-gray-300" style="background-color: #6B7280" title="Cinza"></button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeCategoryModal()" 
                                class="px-4 py-2 text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                            Cancelar
                        </button>
                        <button type="submit" id="submitButton"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white rounded-md transition duration-200">
                            Criar Categoria
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Modal functions
function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Nova Categoria';
    document.getElementById('submitButton').textContent = 'Criar Categoria';
    document.getElementById('categoryForm').reset();
    document.getElementById('categoryId').value = '';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('color').value = '#3B82F6';
    document.getElementById('colorHex').value = '#3B82F6';
    clearErrors();
    document.getElementById('categoryModal').classList.remove('hidden');
}

function openEditModal(id, name, description, color) {
    document.getElementById('modalTitle').textContent = 'Editar Categoria';
    document.getElementById('submitButton').textContent = 'Salvar Alterações';
    document.getElementById('categoryId').value = id;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('name').value = name;
    document.getElementById('description').value = description || '';
    document.getElementById('color').value = color;
    document.getElementById('colorHex').value = color;
    clearErrors();
    document.getElementById('categoryModal').classList.remove('hidden');
}

function closeCategoryModal() {
    document.getElementById('categoryModal').classList.add('hidden');
    clearErrors();
}

function clearErrors() {
    const errorElements = document.querySelectorAll('[id$="Error"]');
    errorElements.forEach(element => {
        element.classList.add('hidden');
        element.textContent = '';
    });
}

function showError(field, message) {
    const errorElement = document.getElementById(field + 'Error');
    errorElement.textContent = message;
    errorElement.classList.remove('hidden');
}

// Color functions
function setColor(colorValue) {
    document.getElementById('color').value = colorValue;
    document.getElementById('colorHex').value = colorValue;
}

// Sync color inputs
document.getElementById('color').addEventListener('input', function() {
    document.getElementById('colorHex').value = this.value;
});

document.getElementById('colorHex').addEventListener('input', function() {
    const colorValue = this.value;
    if (/^#[0-9A-F]{6}$/i.test(colorValue)) {
        document.getElementById('color').value = colorValue;
    }
});

// Form submission
document.getElementById('categoryForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    clearErrors();
    
    const formData = new FormData(this);
    const categoryId = document.getElementById('categoryId').value;
    const method = document.getElementById('formMethod').value;
    
    // Add method override for PUT requests
    if (method === 'PUT') {
        formData.append('_method', 'PUT');
    }
    
    const url = categoryId ? `/file-categories/${categoryId}` : '/file-categories';
    
    try {
        const response = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Resposta não é JSON válido');
        }
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            closeCategoryModal();
            location.reload();
        } else {
            // Handle validation errors
            if (result.errors) {
                Object.keys(result.errors).forEach(field => {
                    showError(field, result.errors[field][0]);
                });
            } else {
                alert('Erro: ' + (result.message || 'Erro desconhecido'));
            }
        }
    } catch (error) {
        console.error('Erro ao salvar categoria:', error);
        alert('Erro ao salvar categoria: ' + error.message);
    }
});

// Delete function
function deleteCategory(categoryId) {
    if (confirm('Tem certeza que deseja excluir esta categoria? Esta ação não pode ser desfeita.')) {
        fetch(`/file-categories/${categoryId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async response => {
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Resposta não é JSON válido');
            }
            return response.json();
        })
        .then(result => {
            if (result.success) {
                location.reload();
            } else {
                alert('Erro ao excluir categoria: ' + (result.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro ao excluir categoria:', error);
            alert('Erro ao excluir categoria: ' + error.message);
        });
    }
}

// Close modal when clicking outside
document.getElementById('categoryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCategoryModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCategoryModal();
    }
});

// Search functionality
const searchInput = document.getElementById('search');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const categoryCards = document.querySelectorAll('.category-card');
        
        categoryCards.forEach(card => {
            const categoryName = card.querySelector('h3')?.textContent.toLowerCase() || '';
            const categoryDescription = card.querySelector('p')?.textContent.toLowerCase() || '';
            
            if (categoryName.includes(searchTerm) || categoryDescription.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
}

// Clear search functionality
const clearSearchBtn = document.getElementById('clearSearch');
if (clearSearchBtn) {
    clearSearchBtn.addEventListener('click', function() {
        const searchInput = document.getElementById('search');
        if (searchInput) {
            searchInput.value = '';
            // Trigger input event to reset the filter
            searchInput.dispatchEvent(new Event('input'));
        }
    });
}

// Filter by type functionality
function filterByType(type) {
    const filterTags = document.querySelectorAll('.filter-tag');
    const categoryCards = document.querySelectorAll('.category-card');
    
    // Update active filter tag
    filterTags.forEach(tag => {
        tag.classList.remove('active', 'bg-blue-100', 'text-blue-800', 'dark:bg-blue-900', 'dark:text-blue-200');
        tag.classList.add('bg-gray-100', 'text-gray-700', 'dark:bg-gray-700', 'dark:text-gray-300');
    });
    
    const activeTag = event.target;
    activeTag.classList.remove('bg-gray-100', 'text-gray-700', 'dark:bg-gray-700', 'dark:text-gray-300');
    activeTag.classList.add('active', 'bg-blue-100', 'text-blue-800', 'dark:bg-blue-900', 'dark:text-blue-200');
    
    // Show all cards if 'all' is selected
    if (type === 'all') {
        categoryCards.forEach(card => {
            card.style.display = 'block';
        });
        return;
    }
    
    // Filter cards based on type (this would need backend support to work properly)
    // For now, just show all cards since we don't have type information in the frontend
    categoryCards.forEach(card => {
        card.style.display = 'block';
    });
}
</script>
@endpush