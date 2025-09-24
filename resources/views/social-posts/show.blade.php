@php
use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('title', 'Visualizar Post - Redes Sociais')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('social-posts.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                            Redes Sociais
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ Str::limit($socialPost->titulo, 30) }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-4 sm:mb-0">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        <i class="fas fa-eye text-blue-600 mr-2"></i>
                        {{ $socialPost->titulo }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mr-2 {{ $socialPost->status == 'publicado' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : ($socialPost->status == 'arquivado' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200') }}">
                            {{ ucfirst($socialPost->status) }}
                        </span>
                        Criado em {{ $socialPost->created_at->format('d/m/Y H:i') }} por {{ $socialPost->user->name }}
                    </p>
                </div>
                
                <div class="flex items-center space-x-2 w-auto">
                    <a href="{{ route('social-posts.edit', $socialPost) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200" title="Editar">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button type="button" onclick="duplicatePost()" class="p-2 text-gray-500 hover:text-green-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200" title="Duplicar">
                        <i class="fas fa-copy"></i>
                    </button>
                    <button type="button" onclick="exportPost()" class="p-2 text-gray-500 hover:text-cyan-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200" title="Exportar">
                        <i class="fas fa-download"></i>
                    </button>
                    <button type="button" onclick="confirmDelete()" class="p-2 text-gray-500 hover:text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200" title="Excluir">
                        <i class="fas fa-trash"></i>
                    </button>
                    <a href="{{ route('social-posts.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200" title="Voltar">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2">
            <!-- Título -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border-0 mb-4">
                <div class="px-6 py-4 bg-transparent border-0">
                    <h6 class="text-lg font-medium mb-0 dark:text-white">
                        <i class="fas fa-heading text-blue-600 mr-2"></i>
                        Título do Post
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 ml-2">Imagem 1</span>
                    </h6>
                </div>
                <div class="px-6 pb-6">
                    <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded">
                        <h5 class="mb-0 dark:text-white">{{ $socialPost->titulo }}</h5>
                    </div>
                </div>
            </div>

            <!-- Legenda -->
            @if($socialPost->legenda)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border-0 mb-4">
                <div class="px-6 py-4 bg-transparent border-0">
                    <h6 class="text-lg font-medium mb-0 dark:text-white">
                        <i class="fas fa-align-left text-blue-600 mr-2"></i>
                        Legenda
                    </h6>
                </div>
                <div class="px-6 pb-6">
                    <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded">
                        <div class="formatted-text dark:text-white">{!! nl2br(e($socialPost->legenda)) !!}</div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Hashtags -->
            @if($socialPost->hashtags->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border-0 mb-4">
                <div class="px-6 py-4 bg-transparent border-0">
                    <div class="flex justify-between items-center">
                        <h6 class="text-lg font-medium mb-0 dark:text-white">
                            <i class="fas fa-hashtag text-blue-600 mr-2"></i>
                            Hashtags
                        </h6>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">{{ $socialPost->hashtags->count() }}/30</span>
                    </div>
                </div>
                <div class="px-6 pb-6">
                    <div class="flex flex-wrap gap-2">
                        @foreach($socialPost->hashtags as $hashtag)
                            <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                #{{ $hashtag->name }}
                                @if($hashtag->usage_count > 1)
                                    <small class="opacity-75">({{ $hashtag->usage_count }})</small>
                                @endif
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Textos do Carrossel -->
            @if($socialPost->carouselTexts->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border-0 mb-4">
                <div class="px-6 py-4 bg-transparent border-0">
                    <h6 class="text-lg font-medium mb-0 dark:text-white">
                        <i class="fas fa-images text-blue-600 mr-2"></i>
                        Textos do Carrossel
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 ml-2">{{ $socialPost->carouselTexts->count() }} texto(s)</span>
                    </h6>
                </div>
                <div class="px-6 pb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($socialPost->carouselTexts->sortBy('position') as $carouselText)
                            <div class="bg-gray-100 dark:bg-gray-700 border-0 rounded-lg h-full">
                                <div class="px-4 py-3 bg-transparent border-0">
                                    <h6 class="mb-0 dark:text-white">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 mr-2">{{ $carouselText->position }}</span>
                                        Imagem {{ $carouselText->position }}
                                    </h6>
                                </div>
                                <div class="px-4 pb-4">
                                    <div class="formatted-text dark:text-white">{!! nl2br(e($carouselText->texto)) !!}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Call-to-Action -->
            @if($socialPost->texto_final || $socialPost->call_to_action_image)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border-0 mb-4">
                <div class="px-6 py-4 bg-transparent border-0">
                    <h6 class="text-lg font-medium mb-0 dark:text-white">
                        <i class="fas fa-bullhorn text-blue-600 mr-2"></i>
                        Call-to-Action
                    </h6>
                </div>
                <div class="px-6 pb-6">
                    @if($socialPost->call_to_action_image)
                        <div class="text-center">
                            <img src="{{ asset('storage/' . $socialPost->call_to_action_image) }}" alt="Call-to-Action" class="max-w-full h-auto rounded-lg border border-gray-200 dark:border-gray-600">
                        </div>
                    @elseif($socialPost->texto_final)
                        <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded">
                            <div class="formatted-text dark:text-white">{!! nl2br(e($socialPost->texto_final)) !!}</div>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
            <!-- Informações -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border-0 mb-4">
                <div class="px-6 py-4 bg-transparent border-0">
                    <h6 class="text-lg font-medium mb-0 dark:text-white">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Informações
                    </h6>
                </div>
                <div class="px-6 pb-6">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-300">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $socialPost->status == 'publicado' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : ($socialPost->status == 'arquivado' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200') }}">
                                {{ ucfirst($socialPost->status) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-300">Criado em:</span>
                            <span class="dark:text-white">{{ $socialPost->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-300">Atualizado em:</span>
                            <span class="dark:text-white">{{ $socialPost->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-300">Criado por:</span>
                            <span class="dark:text-white">{{ $socialPost->user->name }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-300">Hashtags:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">{{ $socialPost->hashtags->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-300">Textos carrossel:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-200">{{ $socialPost->carouselTexts->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border-0 mb-4">
                <div class="px-6 py-4 bg-transparent border-0">
                    <h6 class="text-lg font-medium mb-0 dark:text-white">
                        <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                        Estatísticas
                    </h6>
                </div>
                <div class="px-6 pb-6">
                    <div class="grid grid-cols-2 gap-3 text-center">
                        <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded">
                            <div class="text-2xl font-bold mb-1 text-blue-600">{{ strlen($socialPost->titulo) }}</div>
                            <small class="text-gray-600 dark:text-gray-300">Caracteres no título</small>
                        </div>
                        <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded">
                            <div class="text-2xl font-bold mb-1 text-cyan-600">{{ strlen($socialPost->legenda ?? '') }}</div>
                            <small class="text-gray-600 dark:text-gray-300">Caracteres na legenda</small>
                        </div>
                        <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded">
                            <div class="text-2xl font-bold mb-1 text-green-600">{{ $socialPost->hashtags->count() }}</div>
                            <small class="text-gray-600 dark:text-gray-300">Total de hashtags</small>
                        </div>
                        <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded">
                            <div class="text-2xl font-bold mb-1 text-yellow-600">{{ $socialPost->carouselTexts->count() + 1 }}</div>
                            <small class="text-gray-600 dark:text-gray-300">Total de imagens</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Mobile -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border-0 mb-4">
                <div class="px-6 py-4 bg-transparent border-0">
                    <h6 class="text-lg font-medium mb-0 dark:text-white">
                        <i class="fas fa-mobile-alt text-blue-600 mr-2"></i>
                        Preview Mobile
                    </h6>
                </div>
                <div class="px-6 pb-6">
                    <div class="mobile-preview mx-auto" style="max-width: 300px;">
                        <div class="border rounded-lg p-3 bg-white dark:bg-gray-800 dark:border-gray-600" style="box-shadow: 0 0 20px rgba(0,0,0,0.1);">
                            <!-- Header do Instagram -->
                            <div class="flex items-center mb-3">
                                <div class="rounded-full bg-gradient" style="width: 32px; height: 32px; background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);"></div>
                                <div class="ml-2">
                                    <div class="font-bold dark:text-white" style="font-size: 0.9em;">{{ $socialPost->user->name }}</div>
                                </div>
                            </div>
                            
                            <!-- Imagem simulada -->
                            <div class="bg-gray-100 dark:bg-gray-700 rounded mb-3 flex items-center justify-center" style="height: 200px; font-size: 0.8em;">
                                <div class="text-center text-gray-600 dark:text-gray-300">
                                    <i class="fas fa-image fa-2x mb-2"></i>
                                    <div>{{ $socialPost->titulo }}</div>
                                </div>
                            </div>
                            
                            <!-- Ações -->
                            <div class="flex justify-between mb-3">
                                <div>
                                    <i class="far fa-heart mr-2 dark:text-white"></i>
                                    <i class="far fa-comment mr-2 dark:text-white"></i>
                                    <i class="far fa-paper-plane dark:text-white"></i>
                                </div>
                                <i class="far fa-bookmark dark:text-white"></i>
                            </div>
                            
                            <!-- Legenda -->
                            @if($socialPost->legenda)
                            <div style="font-size: 0.85em; line-height: 1.4;">
                                <span class="font-bold dark:text-white">{{ $socialPost->user->name }}</span>
                                <span class="dark:text-white">{{ Str::limit($socialPost->legenda, 100) }}</span>
                                @if(strlen($socialPost->legenda) > 100)
                                    <span class="text-gray-600 dark:text-gray-400">... mais</span>
                                @endif
                            </div>
                            @endif
                            
                            <!-- Hashtags -->
                            @if($socialPost->hashtags->count() > 0)
                            <div class="mt-2" style="font-size: 0.8em;">
                                @foreach($socialPost->hashtags->take(5) as $hashtag)
                                    <span class="text-blue-600 dark:text-blue-400">#{{ $hashtag->name }}</span>{{ !$loop->last ? ' ' : '' }}
                                @endforeach
                                @if($socialPost->hashtags->count() > 5)
                                    <span class="text-gray-600 dark:text-gray-400">... +{{ $socialPost->hashtags->count() - 5 }} mais</span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ações -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border-0">
                <div class="p-6">
                    <div class="space-y-2">
                        <a href="{{ route('social-posts.edit', $socialPost) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                            <i class="fas fa-edit mr-1"></i> Editar Post
                        </a>
                        <button type="button" class="w-full inline-flex items-center justify-center px-4 py-2 border border-green-300 text-green-700 rounded-md hover:bg-green-50 transition duration-200" onclick="duplicatePost()">
                            <i class="fas fa-copy mr-1"></i> Duplicar Post
                        </button>
                        <button type="button" class="w-full inline-flex items-center justify-center px-4 py-2 border border-cyan-300 text-cyan-700 rounded-md hover:bg-cyan-50 transition duration-200" onclick="exportPost()">
                            <i class="fas fa-download mr-1"></i> Exportar
                        </button>
                        <button type="button" class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 text-red-700 rounded-md hover:bg-red-50 transition duration-200" onclick="confirmDelete()">
                            <i class="fas fa-trash mr-1"></i> Excluir
                        </button>
                        <a href="{{ route('social-posts.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition duration-200">
                            <i class="fas fa-arrow-left mr-1"></i> Voltar à Lista
                        </a>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Duplicação -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[10003] flex items-center justify-center" id="duplicateModal">
    <div class="relative mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800 dark:border-gray-600">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    <i class="fas fa-copy text-green-500 mr-2"></i>
                    Confirmar Duplicação
                </h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-gray-100" onclick="closeDuplicateModal()">
                    <span class="sr-only">Fechar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 dark:text-gray-300">Deseja duplicar este post?</p>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Uma cópia será criada com status "rascunho".</p>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 transition duration-200" onclick="closeDuplicateModal()">Cancelar</button>
                <button type="button" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 transition duration-200" onclick="confirmDuplicatePost()">Duplicar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[10003] flex items-center justify-center" id="deleteModal">
    <div class="relative mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800 dark:border-gray-600">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                    Confirmar Exclusão
                </h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-gray-100" onclick="closeDeleteModal()">
                    <span class="sr-only">Fechar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 dark:text-gray-300">Tem certeza que deseja excluir este post?</p>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Esta ação não pode ser desfeita.</p>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 transition duration-200" onclick="closeDeleteModal()">Cancelar</button>
                <form method="POST" action="{{ route('social-posts.destroy', $socialPost) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 transition duration-200">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Exportação -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[10003] flex items-center justify-center" id="exportModal">
    <div class="relative mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800 dark:border-gray-600">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    <i class="fas fa-download text-cyan-500 mr-2"></i>
                    Exportar Post
                </h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-gray-100" onclick="closeExportModal()">
                    <span class="sr-only">Fechar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 dark:text-gray-300 mb-4">Escolha o formato de exportação:</p>
                <div class="space-y-2">
                    <button type="button" class="w-full inline-flex items-center justify-center px-4 py-2 border border-blue-300 text-blue-700 rounded-md hover:bg-blue-50 dark:border-blue-600 dark:text-blue-400 dark:hover:bg-blue-900 transition duration-200" onclick="exportAsText()">
                        <i class="fas fa-file-alt mr-2"></i> Exportar como Texto
                    </button>
                    <button type="button" class="w-full inline-flex items-center justify-center px-4 py-2 border border-cyan-300 text-cyan-700 rounded-md hover:bg-cyan-50 dark:border-cyan-600 dark:text-cyan-400 dark:hover:bg-cyan-900 transition duration-200" onclick="exportAsJson()">
                        <i class="fas fa-code mr-2"></i> Exportar como JSON
                    </button>
                    <button type="button" class="w-full inline-flex items-center justify-center px-4 py-2 border border-green-300 text-green-700 rounded-md hover:bg-green-50 dark:border-green-600 dark:text-green-400 dark:hover:bg-green-900 transition duration-200" onclick="copyToClipboard()">
                        <i class="fas fa-copy mr-2"></i> Copiar para Área de Transferência
                    </button>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="button" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 transition duration-200" onclick="closeExportModal()">Fechar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Funções para controlar modais
function hideModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function showModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

// Confirmar exclusão
function confirmDelete() {
    showModal('deleteModal');
}

function closeDeleteModal() {
    hideModal('deleteModal');
}

// Duplicar post
function duplicatePost() {
    showModal('duplicateModal');
}

function closeDuplicateModal() {
    hideModal('duplicateModal');
}

function confirmDuplicatePost() {
    // Criar um formulário para enviar a requisição POST
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route('social-posts.duplicate', $socialPost) }}';
    
    // Adicionar token CSRF
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    // Adicionar ao body e submeter
    document.body.appendChild(form);
    form.submit();
}

// Exportar post
function exportPost() {
    showModal('exportModal');
}

function closeExportModal() {
    hideModal('exportModal');
}

// Exportar como texto
function exportAsText() {
    const content = generateTextContent();
    downloadFile(content, 'post-{{ $socialPost->id }}.txt', 'text/plain');
    closeExportModal();
}

// Exportar como JSON
function exportAsJson() {
    const data = {
        id: {{ $socialPost->id }},
        titulo: '{{ addslashes($socialPost->titulo) }}',
        legenda: '{{ addslashes($socialPost->legenda ?? '') }}',
        texto_final: '{{ addslashes($socialPost->texto_final ?? '') }}',
        status: '{{ $socialPost->status }}',
        hashtags: @json($socialPost->hashtags->pluck('name')),
        carousel_texts: @json($socialPost->carouselTexts->map(function($text) {
            return ['position' => $text->position, 'texto' => $text->texto];
        })),
        created_at: '{{ $socialPost->created_at->toISOString() }}',
        updated_at: '{{ $socialPost->updated_at->toISOString() }}'
    };
    
    const content = JSON.stringify(data, null, 2);
    downloadFile(content, 'post-{{ $socialPost->id }}.json', 'application/json');
    closeExportModal();
}

// Copiar para área de transferência
function copyToClipboard() {
    const content = generateTextContent();
    navigator.clipboard.writeText(content).then(function() {
        alert('Conteúdo copiado para a área de transferência!');
        closeExportModal();
    }).catch(function(err) {
        console.error('Erro ao copiar:', err);
        alert('Erro ao copiar para a área de transferência.');
    });
}

// Gerar conteúdo em texto
function generateTextContent() {
    let content = '';
    
    content += 'TÍTULO:\n';
    content += '{{ $socialPost->titulo }}\n\n';
    
    @if($socialPost->legenda)
    content += 'LEGENDA:\n';
    content += '{{ addslashes($socialPost->legenda) }}\n\n';
    @endif
    
    @if($socialPost->hashtags->count() > 0)
    content += 'HASHTAGS:\n';
    @foreach($socialPost->hashtags as $hashtag)
    content += '#{{ $hashtag->name }} ';
    @endforeach
    content += '\n\n';
    @endif
    
    @if($socialPost->carouselTexts->count() > 0)
    content += 'TEXTOS DO CARROSSEL:\n';
    @foreach($socialPost->carouselTexts->sortBy('position') as $carouselText)
    content += 'Imagem {{ $carouselText->position }}: {{ addslashes($carouselText->texto) }}\n';
    @endforeach
    content += '\n';
    @endif
    
    @if($socialPost->texto_final)
    content += 'CALL-TO-ACTION:\n';
    content += '{{ addslashes($socialPost->texto_final) }}\n';
    @endif
    
    return content;
}

// Download de arquivo
function downloadFile(content, filename, contentType) {
    const blob = new Blob([content], { type: contentType });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}
</script>
@endpush

@push('styles')
<style>
.formatted-text {
    line-height: 1.6;
    word-wrap: break-word;
}

.mobile-preview {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.badge {
    font-size: 0.8em;
}

.card-header h6 {
    font-weight: 600;
}

.bg-gradient {
    background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%) !important;
}
</style>
@endpush