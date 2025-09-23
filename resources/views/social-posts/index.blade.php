@extends('layouts.app')

@section('title', 'Redes Sociais')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header com Tags de Navegação -->
    <div class="mb-6">
        <!-- Tags de Navegação Rápida -->
        <div class="flex flex-wrap gap-2 mb-4">
            <a href="{{ route('social-posts.index') }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ !request('status') ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                <i class="fas fa-list mr-2"></i>
                Todos os Posts
            </a>
            <a href="{{ route('social-posts.index', ['status' => 'rascunho']) }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ request('status') == 'rascunho' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                <i class="fas fa-edit mr-2"></i>
                Rascunhos
            </a>
            <a href="{{ route('social-posts.index', ['status' => 'arquivado']) }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ request('status') == 'arquivado' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' : 'border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                <i class="fas fa-archive mr-2"></i>
                Arquivados
            </a>
            <a href="{{ route('social-posts.index', ['status' => 'publicado']) }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ request('status') == 'publicado' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                <i class="fas fa-check-circle mr-2"></i>
                Publicados
            </a>
        </div>
    </div>
    
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Redes Sociais</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Gerencie seus posts para Instagram e Facebook</p>
            </div>

        </div>
    </div>

<div>
    <!-- Cards de Resumo -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total de Posts -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total de Posts</p>
                    <p class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Rascunhos -->
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Rascunhos</p>
                    <p class="text-2xl font-bold">{{ $stats['rascunhos'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Arquivados -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Arquivados</p>
                    <p class="text-2xl font-bold">{{ $stats['arquivados'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Publicados -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Publicados</p>
                    <p class="text-2xl font-bold">{{ $stats['publicados'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

<div class="">
    <div class="text-gray-900 dark:text-gray-100">
        <!-- Filtro de Busca -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <form method="GET" action="{{ route('social-posts.index') }}" class="flex items-center gap-4">
                <div class="flex-1">
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
                               placeholder="Buscar por título, legenda ou hashtags..." 
                               value="{{ request('search') }}">
                        @if(request('search'))
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <a href="{{ route('social-posts.index') }}" 
                               class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                <div id="loading-indicator" class="hidden">
                    <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </form>
        </div>

    <!-- Posts List -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($socialPosts as $post)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 h-full flex flex-col relative hover:shadow-lg transition-all duration-300 group">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h6 class="text-lg font-semibold text-gray-900 dark:text-white mb-1  group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $post->titulo }}</h6>
                        <span class="text-gray-500 dark:text-gray-400 text-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $post->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>

                </div>
            </div>
            <div class="p-6 flex-1 flex flex-col">
                <!-- Status Badge -->
                <div class="mb-3">
                    @php
                        $statusConfig = [
                            'rascunho' => ['class' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fas fa-edit', 'text' => 'Rascunho'],
                            'arquivado' => ['class' => 'bg-orange-100 text-orange-800', 'icon' => 'fas fa-archive', 'text' => 'Arquivado'],
                            'publicado' => ['class' => 'bg-green-100 text-green-800', 'icon' => 'fas fa-check-circle', 'text' => 'Publicado']
                        ];
                        $config = $statusConfig[$post->status] ?? ['class' => 'bg-gray-100 text-gray-800', 'icon' => 'fas fa-question', 'text' => $post->status];
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['class'] }}">
                        <i class="{{ $config['icon'] }} mr-1"></i>
                        {{ $config['text'] }}
                    </span>
                </div>

                <!-- Caption Preview -->
                @if($post->legenda)
                <div class="mb-4">
                    <div class="flex items-center mb-2">
                        <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Legenda</p>
                    </div>
                    <p class="text-gray-800 dark:text-gray-200 text-sm leading-relaxed bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">{{ Str::limit(strip_tags($post->legenda), 100) }}</p>
                </div>
                @endif

                <!-- Hashtags -->
                @if($post->hashtags->count() > 0)
                <div class="mb-4">
                    <div class="flex items-center mb-2">
                        <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                        </svg>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Hashtags</p>
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($post->hashtags->take(3) as $hashtag)
                        <span class="inline-block bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300 text-xs px-2.5 py-1 rounded-full font-medium">#{{ $hashtag->name }}</span>
                        @endforeach
                        @if($post->hashtags->count() > 3)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400">+{{ $post->hashtags->count() - 3 }}</span>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Carousel Texts Count -->
                @if($post->carouselTexts->count() > 0)
                <div class="mb-4">
                    <div class="flex items-center justify-between p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm font-medium text-purple-800 dark:text-purple-300">Carrossel</span>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/40 text-purple-800 dark:text-purple-300">
                            {{ $post->carouselTexts->count() }} texto(s)
                        </span>
                    </div>
                </div>
                @endif

                <!-- Final Text Preview -->
                @if($post->texto_final)
                <div class="mb-4">
                    <div class="flex items-center mb-2">
                        <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Call-to-Action</p>
                    </div>
                    <div class="bg-gradient-to-r from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 rounded-lg p-3 border border-orange-200 dark:border-orange-800">
                        <p class="text-gray-800 dark:text-gray-200 text-sm font-medium">{{ Str::limit(strip_tags($post->texto_final), 80) }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="border-t border-gray-200 dark:border-gray-700 mt-auto">
                <div class="flex justify-end space-x-3 p-4">
                    <!-- View Button -->
                    <a href="{{ route('social-posts.show', $post) }}" 
                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors duration-200"
                       title="Visualizar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </a>
                    
                    <!-- Edit Button -->
                    <a href="{{ route('social-posts.edit', $post) }}" 
                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
                       title="Editar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                    
                    <!-- Generate Images Button -->
                    <a href="{{ route('social-posts.generate-images', $post) }}" 
                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-purple-600 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-colors duration-200"
                       title="Gerar Imagens">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </a>
                    
                    <!-- Delete Button -->
                    <button type="button" 
                            onclick="confirmDelete({{ $post->id }}, '{{ addslashes($post->titulo) }}')"
                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200"
                            title="Excluir">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

        </div>
        @empty
        <div class="col-span-full">
            <div class="text-center py-16">
                <div class="mx-auto h-24 w-24 text-gray-400 dark:text-gray-500 mb-6">
                    <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Nenhum post encontrado</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto">Comece criando seu primeiro post para redes sociais e alcance sua audiência.</p>
                <div class="space-y-4">
                    <a href="{{ route('social-posts.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Criar Primeiro Post
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($socialPosts->hasPages())
    <div class="mt-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex justify-center">
                {{ $socialPosts->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Floating Add Button -->
<div class="fixed bottom-6 right-6 z-50">
    <a href="{{ route('social-posts.create') }}" class="group inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-full shadow-lg hover:shadow-2xl transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-indigo-500/50 transform hover:scale-110" 
       title="Novo Post">
        <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
    </a>
</div>

<!-- Delete Confirmation Modal -->
<div class="fixed inset-0 z-[10003] hidden" id="deleteModal">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/20 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">Confirmar Exclusão</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tem certeza que deseja excluir o post <strong id="deletePostTitle"></strong>? Esta ação não pode ser desfeita.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                            Excluir
                        </button>
                    </form>
                    <button type="button" onclick="closeDeleteModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-600 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-500 hover:bg-gray-50 dark:hover:bg-gray-500 sm:mt-0 sm:w-auto">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Modal -->
<div class="fixed inset-0 z-50 hidden" id="statsModal">
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-white">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Estatísticas dos Posts
                        </h3>
                        <button type="button" onclick="closeStatsModal()" class="rounded-md bg-white dark:bg-gray-800 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-white dark:bg-gray-700 border border-blue-200 dark:border-blue-600 rounded-lg p-6">
                            <div class="text-center">
                                <h3 class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total'] ?? 0 }}</h3>
                                <p class="text-gray-600 dark:text-gray-300 mt-1">Total de Posts</p>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-700 border border-yellow-200 dark:border-yellow-600 rounded-lg p-6">
                            <div class="text-center">
                                <h3 class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['rascunhos'] ?? 0 }}</h3>
                                <p class="text-gray-600 dark:text-gray-300 mt-1">Rascunhos</p>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-700 border border-cyan-200 dark:border-cyan-600 rounded-lg p-6">
                            <div class="text-center">
                                <h3 class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $stats['arquivados'] ?? 0 }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">Arquivados</p>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-700 border border-green-200 dark:border-green-600 rounded-lg p-6">
                            <div class="text-center">
                                <h3 class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['publicados'] ?? 0 }}</h3>
                                <p class="text-gray-600 dark:text-gray-300 mt-1">Publicados</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="closeStatsModal()" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
@endsection

@push('scripts')
<script>
function confirmDelete(postId, postTitle) {
    document.getElementById('deletePostTitle').textContent = postTitle;
    document.getElementById('deleteForm').action = '/social-posts/' + postId;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
    document.body.style.overflow = 'auto';
}

function showStatsModal() {
    document.getElementById('statsModal').classList.remove('hidden');
    document.getElementById('statsModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeStatsModal() {
    document.getElementById('statsModal').classList.add('hidden');
    document.getElementById('statsModal').classList.remove('flex');
    document.body.style.overflow = 'auto';
}

function hideModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.getElementById(modalId).classList.remove('flex');
    document.body.style.overflow = 'auto';
}

function toggleDropdown(button) {
    const dropdown = button.nextElementSibling;
    const isHidden = dropdown.classList.contains('hidden');
    
    // Close all dropdowns
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.classList.add('hidden');
    });
    
    // Toggle current dropdown
    if (isHidden) {
        dropdown.classList.remove('hidden');
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const deleteModal = document.getElementById('deleteModal');
        const statsModal = document.getElementById('statsModal');
        
        if (!deleteModal.classList.contains('hidden')) {
            closeDeleteModal();
        }
        if (!statsModal.classList.contains('hidden')) {
            closeStatsModal();
        }
    }
});

// Search functionality
let searchTimeout;
function handleSearch() {
    const searchInput = document.getElementById('search');
    const loadingIndicator = document.getElementById('loading-indicator');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            
            searchTimeout = setTimeout(() => {
                if (loadingIndicator) {
                    loadingIndicator.classList.remove('hidden');
                }
                
                // Submit the form automatically
                this.form.submit();
            }, 500); // Wait 500ms after user stops typing
        });
        
        // Show/hide clear button
        searchInput.addEventListener('input', function() {
            const clearButton = this.parentElement.querySelector('a[href*="social-posts.index"]');
            if (clearButton) {
                if (this.value.length > 0) {
                    clearButton.style.display = 'block';
                } else {
                    clearButton.style.display = 'none';
                }
            }
        });
    }
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Initialize search functionality
    handleSearch();
    
    // Close modals when clicking on backdrop
    document.getElementById('deleteModal').addEventListener('click', function(event) {
        if (event.target === this || event.target.classList.contains('bg-black')) {
            closeDeleteModal();
        }
    });
    
    document.getElementById('statsModal').addEventListener('click', function(event) {
        if (event.target === this || event.target.classList.contains('bg-black')) {
            closeStatsModal();
        }
    });
    
    // Smooth scroll to top when clicking floating button
    const floatingButton = document.querySelector('a[href*="create"]');
    if (floatingButton && floatingButton.classList.contains('fixed')) {
        floatingButton.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
});
</script>
@endpush