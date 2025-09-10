@extends('layouts.app')

@section('title', 'Trabalhos do Portfólio')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="w-full">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Trabalhos do Portfólio</h1>
                <p class="text-gray-600 dark:text-gray-400">Gerencie todos os seus trabalhos de portfólio</p>
            </div>
            <div>
                <a href="{{ route('portfolio.works.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>Novo Trabalho
                </a>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
            <div class="p-6">
                <form method="GET" action="{{ route('portfolio.works.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="lg:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar</label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Título, descrição ou cliente...">
                    </div>
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Categoria</label>
                        <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" id="category" name="category">
                            <option value="">Todas</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" id="status" name="status">
                            <option value="">Todos</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Publicado</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Rascunho</option>
                        </select>
                    </div>
                    <div>
                        <label for="featured" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Destaque</label>
                        <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" id="featured" name="featured">
                            <option value="">Todos</option>
                            <option value="yes" {{ request('featured') == 'yes' ? 'selected' : '' }}>Em destaque</option>
                            <option value="no" {{ request('featured') == 'no' ? 'selected' : '' }}>Normal</option>
                        </select>
                    </div>
                    <div class="lg:col-span-5 flex flex-col sm:flex-row gap-2 pt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-search mr-2"></i>Filtrar
                        </button>
                        <a href="{{ route('portfolio.works.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors">
                            <i class="fas fa-times mr-2"></i>Limpar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Trabalhos -->
        @if($works->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($works as $work)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                        <!-- Imagem -->
                        <div class="relative">
                            <img src="{{ $work->featured_image_url }}" 
                                 class="w-full h-48 object-cover" 
                                 alt="{{ $work->title }}">
                            
                            <!-- Badges -->
                            <div class="absolute top-2 left-2 flex flex-wrap gap-1">
                                @if($work->is_featured)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        <i class="fas fa-star mr-1"></i>Destaque
                                    </span>
                                @endif
                                @if($work->is_published)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Publicado</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">Rascunho</span>
                                @endif
                            </div>
                        </div>

                        <div class="p-6 flex flex-col h-full">
                            <!-- Categoria -->
                            <div class="mb-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">{{ $work->category->name }}</span>
                            </div>

                            <!-- Título -->
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $work->title }}</h3>

                            <!-- Descrição -->
                            <p class="text-gray-600 dark:text-gray-400 text-sm flex-grow mb-4">
                                {{ Str::limit($work->description, 100) }}
                            </p>

                            <!-- Informações -->
                            <div class="space-y-1 mb-4">
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-user mr-2 w-4"></i>{{ $work->client->nome }}
                                </div>
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-calendar mr-2 w-4"></i>{{ $work->formatted_project_date }}
                                </div>
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-eye mr-2 w-4"></i>{{ $work->views_count }} visualizações
                                </div>
                            </div>

                            <!-- Ações -->
                            <div class="flex gap-2 mt-auto">
                                <a href="{{ route('portfolio.works.show', $work->slug) }}" 
                                   class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-blue-600 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 font-medium rounded-lg transition-colors text-sm">
                                    <i class="fas fa-eye mr-1"></i>Ver
                                </a>
                                <a href="{{ route('portfolio.works.edit', $work->slug) }}" 
                                   class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium rounded-lg transition-colors text-sm">
                                    <i class="fas fa-edit mr-1"></i>Editar
                                </a>
                                <form action="{{ route('portfolio.works.destroy', $work->slug) }}" 
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center px-3 py-2 border border-red-300 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 font-medium rounded-lg transition-colors text-sm"
                                            onclick="return confirm('Tem certeza que deseja excluir este trabalho?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginação -->
            <div class="flex justify-center mt-8">
                {{ $works->appends(request()->query())->links() }}
            </div>
        @else
            <!-- Estado vazio -->
            <div class="text-center py-12">
                <div class="mb-6">
                    <i class="fas fa-briefcase text-6xl text-gray-400 dark:text-gray-500"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Nenhum trabalho encontrado</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Não há trabalhos que correspondam aos filtros selecionados.</p>
                <a href="{{ route('portfolio.works.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>Criar Primeiro Trabalho
                </a>
            </div>
        @endif
        </div>
    </div>
</div>
@endsection