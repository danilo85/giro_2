@extends('layouts.app')

@section('title', 'Gerar Imagens - ' . $socialPost->titulo)

@push('head')
<link href="https://fonts.googleapis.com/css2?family=Libre+Franklin:wght@400;600;700;900&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
@endpush



@section('content')
<div class="space-y-8">

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
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Novo Post</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="flex items-center justify-between">
                <div>
                     <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gerar Imagens</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $socialPost->titulo }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <!-- Ícones de Ações Rápidas -->
                    <button id="generateAllBtn" class="p-2 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200" title="Gerar Todas as Imagens">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </button>
                    <button id="downloadAllBtn" class="p-2 text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 hover:bg-green-50 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200" title="Baixar Todas as Imagens">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </button>
                    <!-- Botão Voltar -->
                    <a href="{{ route('social-posts.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200" title="Voltar">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>

    <!-- Main Content -->
    <div class="space-y-8">
        <!-- Control Panel - Reorganizado em 2 Colunas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Coluna 1: Formato das Imagens -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Formato das Imagens</h2>
                </div>
                <div class="space-y-4">
                    <label class="cursor-pointer block">
                        <input type="radio" name="format" value="stories" class="sr-only format-radio" checked>
                        <div class="format-option border-2 border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:border-purple-500 hover:shadow-md transition-all duration-200 flex items-center gap-6 sm:gap-4">
                            <div class="w-10 h-16 bg-gradient-to-b from-purple-400 to-pink-400 rounded-lg flex-shrink-0 shadow-sm"></div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 dark:text-white">Stories</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300">1080 x 1920 (9:16)</p>
                                <p class="text-xs text-purple-600 font-medium">Ideal para Instagram Stories</p>
                            </div>
                        </div>
                    </label>
                    <label class="cursor-pointer block">
                        <input type="radio" name="format" value="square" class="sr-only format-radio">
                        <div class="format-option border-2 border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:border-blue-500 hover:shadow-md transition-all duration-200 flex items-center gap-6 sm:gap-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-400 rounded-lg flex-shrink-0 shadow-sm"></div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 dark:text-white">Quadrado</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300">1080 x 1080 (1:1)</p>
                                <p class="text-xs text-blue-600 font-medium">Ideal para Feed e Reels</p>
                            </div>
                        </div>
                    </label>
                    <label class="cursor-pointer block">
                        <input type="radio" name="format" value="rectangular" class="sr-only format-radio">
                        <div class="format-option border-2 border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:border-green-500 hover:shadow-md transition-all duration-200 flex items-center gap-6 sm:gap-4">
                            <div class="w-10 h-12 bg-gradient-to-r from-green-400 to-blue-400 rounded-lg flex-shrink-0 shadow-sm"></div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 dark:text-white">Retangular</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300">1080 x 1350 (4:5)</p>
                                <p class="text-xs text-green-600 font-medium">Ideal para Feed vertical</p>
                            </div>
                        </div>
                    </label>
                </div>
                <!-- Status do formato atual -->
                <div class="mt-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-700 dark:text-gray-300 font-medium">📐 Formato selecionado: <span id="currentFormatDisplay" class="text-gray-900 dark:text-white font-semibold">Stories (9:16)</span></span>
                        <span class="text-gray-500 dark:text-gray-400">Todas as imagens serão geradas neste formato</span>
                    </div>
                </div>
            </div>

            <!-- Coluna 2: Personalização de Cores -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Personalização de Cores</h2>
                </div>
                
                <!-- Seletor de Nova Cor -->
                <div class="space-y-8 mb-6">
                    <div>
                        <label for="colorPicker" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">🎨 Escolher Nova Cor</label>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <input type="color" id="colorPicker" value="#FFD700" class="w-14 h-12 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer shadow-sm hover:shadow-md transition-shadow">
                                    <input type="text" id="colorHex" value="#FFD700" class="flex-1 px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg text-sm font-mono bg-white dark:bg-gray-800 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" placeholder="#FFD700">
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <button id="saveColorBtn" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 dark:from-blue-700 dark:to-blue-800 dark:hover:from-blue-800 dark:hover:to-blue-900 text-white px-4 py-3 rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
                                        💾 Salvar Cor
                                    </button>
                                    <button id="setDefaultColorBtn" class="bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 dark:from-yellow-700 dark:to-yellow-800 dark:hover:from-yellow-800 dark:hover:to-yellow-900 text-white px-4 py-3 rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
                                        ⭐ Definir como Padrão
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Cores Salvas -->
                <div id="savedColorsSection">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">🎨 Cores Salvas</label>
                        
                        @if(isset($postColors) && $postColors->count() > 0)
                        <div class="mb-4">
                            <h4 class="text-xs font-semibold text-blue-600 dark:text-blue-400 mb-3 flex items-center">
                                <span class="w-2 h-2 bg-blue-500 dark:bg-blue-400 rounded-full mr-2"></span>
                                Cores deste post
                            </h4>
                            <div class="grid grid-cols-8 gap-2">
                                @foreach($postColors as $color)
                                <div class="saved-color-item relative group" data-color-id="{{ $color->id }}" data-color="{{ $color->color_hex }}">
                                    <div class="w-10 h-10 rounded-xl border-3 border-blue-400 cursor-pointer hover:border-blue-500 transition-all duration-300 transform hover:scale-110 relative overflow-hidden" 
                                         style="background: linear-gradient(135deg, {{ $color->color_hex }} 0%, {{ $color->color_hex }}dd 100%)" 
                                         title="{{ $color->color_name ?? 'Cor personalizada' }} (Post específico)">
                                        <!-- Badge de post específico -->
                                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-blue-500 rounded-full flex items-center justify-center shadow-md">
                                            <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <!-- Efeito de brilho -->
                                        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white to-transparent opacity-0 group-hover:opacity-30 transition-opacity duration-300 transform -skew-x-12 translate-x-full group-hover:-translate-x-full"></div>
                                    </div>
                                    <button class="delete-color-btn absolute -top-2 -left-2 w-5 h-5 bg-red-500 hover:bg-red-600 text-white rounded-full text-xs opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg font-bold" 
                                            data-color-id="{{ $color->id }}" title="Excluir cor">
                                        ×
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <div id="savedColorsList">
                            @if(isset($defaultColors) && $defaultColors->count() > 0)
                            <div class="mb-4">
                                <h4 class="text-xs font-semibold text-yellow-600 dark:text-yellow-400 mb-3 flex items-center">
                                    <span class="w-2 h-2 bg-yellow-500 dark:bg-yellow-400 rounded-full mr-2"></span>
                                    Cores padrão
                                </h4>
                                <div class="grid grid-cols-8 gap-2">
                                    @foreach($defaultColors as $color)
                                    <div class="saved-color-item relative group" data-color-id="{{ $color->id }}" data-color="{{ $color->color_hex }}">
                                        <div class="w-10 h-10 rounded-md border-3 border-yellow-400 cursor-pointer hover:border-yellow-500 transition-all duration-300 transform hover:scale-110 relative overflow-hidden" 
                                             style="background: linear-gradient(135deg, {{ $color->color_hex }} 0%, {{ $color->color_hex }}dd 100%)" 
                                             title="{{ $color->color_name ?? 'Cor padrão' }} (Padrão)">
                                            <!-- Efeito de brilho -->
                                            <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white to-transparent opacity-0 group-hover:opacity-30 transition-opacity duration-300 transform -skew-x-12 translate-x-full group-hover:-translate-x-full"></div>
                                        </div>
                                        <button class="delete-color-btn absolute -top-2 -left-2 w-5 h-5 bg-red-500 hover:bg-red-600 text-white rounded-full text-xs opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg font-bold" 
                                                data-color-id="{{ $color->id }}" title="Excluir cor">
                                            ×
                                        </button>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            @if(isset($globalColors) && $globalColors->count() > 0)
                            <div class="mb-4">
                                <h4 class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-3 flex items-center">
                                    <span class="w-2 h-2 bg-gray-500 dark:bg-gray-400 rounded-full mr-2"></span>
                                    Cores globais
                                </h4>
                                <div class="grid grid-cols-8 gap-2">
                                    @foreach($globalColors as $color)
                                    <div class="saved-color-item relative group" data-color-id="{{ $color->id }}" data-color="{{ $color->color_hex }}">
                                        <div class="w-10 h-10 rounded-md border-3 border-gray-400 cursor-pointer hover:border-gray-500 transition-all duration-300 transform hover:scale-110 relative overflow-hidden" 
                                             style="background: linear-gradient(135deg, {{ $color->color_hex }} 0%, {{ $color->color_hex }}dd 100%)" 
                                             title="{{ $color->color_name ?? 'Cor global' }} (Global)">
                                            <!-- Efeito de brilho -->
                                            <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white to-transparent opacity-0 group-hover:opacity-30 transition-opacity duration-300 transform -skew-x-12 translate-x-full group-hover:-translate-x-full"></div>
                                        </div>
                                        <button class="delete-color-btn absolute -top-2 -left-2 w-5 h-5 bg-red-500 hover:bg-red-600 text-white rounded-full text-xs opacity-0 group-hover:opacity-100 transition-all duration-200 shadow-lg font-bold" 
                                                data-color-id="{{ $color->id }}" title="Excluir cor">
                                            ×
                                        </button>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            @if((!isset($postColors) || $postColors->count() == 0) && (!isset($defaultColors) || $defaultColors->count() == 0) && (!isset($globalColors) || $globalColors->count() == 0))
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-8 h-8 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Nenhuma cor salva ainda</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">Escolha uma cor e clique em "Salvar Cor"</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Previews -->
        <div class="space-y-8">
            <!-- Title Image -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Imagem do Título</h3>
                    <button class="download-btn bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200" data-type="title">
                        Baixar
                    </button>
                </div>
                
                <!-- Layout de Duas Colunas -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-4">
                    <!-- Primeira Coluna: Imagem do Título -->
                    <div class="order-2 lg:order-1">
                        <div class="image-preview-container">
                            <canvas id="titleCanvas" class="border border-gray-300 dark:border-gray-600 rounded-lg max-w-full h-auto"></canvas>
                        </div>
                    </div>
                    
                    <!-- Segunda Coluna: Controles de Alinhamento Justificado -->
                    <div class="order-1 lg:order-2">
                        <div class="p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm h-fit">
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-300 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                Controles de Alinhamento
                            </h4>
                            
                            <!-- Controle de Quebra de Linha -->
                            <div class="mb-3">
                                <label class="block text-xs font-medium text-blue-700 dark:text-blue-400 mb-1">Quebra de Linha</label>
                                <div class="flex items-center space-x-2">
                                    <input type="range" id="lineBreakSlider" min="1" max="5" value="3" class="flex-1 h-2 bg-blue-200 rounded-lg appearance-none cursor-pointer">
                                    <span id="lineBreakValue" class="text-xs font-mono text-blue-600 dark:text-blue-400 w-8">3</span>
                                </div>
                            </div>
                            
                            <!-- Controle de Linha Destacada -->
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Linha com Caixa Preta</label>
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                        <span>Nenhuma</span>
                                        <span>5ª linha</span>
                                    </div>
                                    <div class="relative">
                                        <input type="range" id="highlightLineSelect" min="0" max="5" value="2" step="1" 
                                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider-thumb">
                                        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1 px-1">
                                            <span>0</span>
                                            <span>1</span>
                                            <span>2</span>
                                            <span>3</span>
                                            <span>4</span>
                                            <span>5</span>
                                        </div>
                                    </div>
                                    <div class="text-xs text-center text-gray-600 dark:text-gray-300 mt-1">
                                        <span id="highlightLineLabel">Segunda linha</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Controle de Espaçamento -->
                            <div class="mb-3">
                                <label class="block text-xs font-medium text-blue-700 dark:text-blue-400 mb-1">Espaçamento entre Linhas</label>
                                <div class="flex items-center space-x-2">
                                    <input type="range" id="lineSpacingSlider" min="1.0" max="2.5" step="0.1" value="1.5" class="flex-1 h-2 bg-blue-200 rounded-lg appearance-none cursor-pointer">
                                    <span id="lineSpacingValue" class="text-xs font-mono text-blue-600 dark:text-blue-400 w-8">1.5</span>
                                </div>
                            </div>
                            
                            <!-- Controle de Espaçamento Personalizado -->
                            <div class="mb-3">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Espaçamento Personalizado</label>
                                    <button id="customSpacingToggle" class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-300 dark:bg-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" data-state="off">
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white dark:bg-gray-200 shadow-lg transition-transform translate-x-1"></span>
                                        <span class="sr-only">Toggle espaçamento personalizado</span>
                                    </button>
                                </div>
                                <div id="customSpacingControls" class="space-y-2 hidden">
                                    <div class="text-xs text-blue-600 dark:text-blue-400 mb-1">Ajuste o espaçamento de cada linha:</div>
                                    <div id="lineSpacingInputs" class="space-y-1">
                                        <!-- Controles serão gerados dinamicamente -->
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Botões de Controle -->
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Justificar Texto</label>
                                    <button id="justifyToggle" class="relative inline-flex h-6 w-11 items-center rounded-full bg-blue-600 dark:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800" data-state="on">
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white dark:bg-gray-200 shadow-lg transition-transform translate-x-6"></span>
                                        <span class="sr-only">Toggle justificar texto</span>
                                    </button>
                                </div>
                                <button id="previewBtn" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Visualizar Preview
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carousel Images -->            <!-- Debug: CarouselTexts count = {{ $socialPost->carouselTexts->count() }} -->            {{-- Temporariamente removendo a condição para testar os modais --}}            @if(true || $socialPost->carouselTexts->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Imagens do Carrossel</h3>
                    <div class="flex space-x-2">
                        <button id="globalCarouselSettings" class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                            Configurações Globais
                        </button>
                        <button class="download-btn bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200" data-type="carousel">
                            Baixar Todas
                        </button>
                    </div>
                </div>
                
                <!-- Modal de Configurações Globais -->
                <div id="globalCarouselModal" class="hidden fixed inset-0 bg-black bg-opacity-50 dark:bg-black dark:bg-opacity-70 z-[10003]">
                    <div class="flex items-center justify-center min-h-screen p-4">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-white">Configurações Globais do Carrossel</h4>
                                <button id="closeGlobalModal" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="space-y-4">
                                <!-- Tamanho da Fonte Global -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tamanho da Fonte</label>
                                    <div class="flex items-center space-x-3">
                                        <input type="range" id="globalFontSize" min="16" max="60" value="54" class="flex-1 h-2 bg-blue-200 rounded-lg appearance-none cursor-pointer">
                            <span id="globalFontSizeValue" class="text-sm font-mono text-blue-600 dark:text-blue-400 w-12">54px</span>
                                    </div>
                                </div>
                                
                                <!-- Espessura da Fonte Global -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Espessura da Fonte</label>
                                    <select id="globalFontWeight" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2">
                                        <option value="300">Fina (Light)</option>
                                        <option value="400" selected>Normal</option>
                                        <option value="500">Média (Medium)</option>
                                        <option value="600">Semi-negrito</option>
                                        <option value="700">Negrito (Bold)</option>
                                        <option value="800">Extra-negrito</option>
                                    </select>
                                </div>
                                
                                <!-- Quebra de Texto Global -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quebra de Texto</label>
                                    <select id="globalTextWrap" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2">
                                        <option value="auto">Automática</option>
                                        <option value="manual">Manual</option>
                                        <option value="none">Sem Quebra</option>
                                    </select>
                                </div>
                                
                                <!-- Alinhamento do Texto Global -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alinhamento do Texto</label>
                                    <select id="globalTextAlignment" class="w-full border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2">
                                        <option value="left">Esquerda</option>
                                        <option value="center">Centro</option>
                                        <option value="right">Direita</option>
                                        <option value="justify">Justificado</option>
                                    </select>
                                </div>
                                
                                <!-- Padding Global -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Padding (Distância do Texto à Borda)</label>
                                    <div class="flex items-center space-x-3">
                                        <input type="range" id="globalPadding" min="0" max="100" value="30" class="flex-1 h-2 bg-blue-200 rounded-lg appearance-none cursor-pointer">
                                        <span id="globalPaddingValue" class="text-sm font-mono text-blue-600 dark:text-blue-400 w-12">30px</span>
                                    </div>
                                </div>
                                
                                <!-- Formatação Global -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Formatação</label>
                                    <div class="flex space-x-2">
                                        <button id="globalUppercase" class="px-3 py-2 text-sm bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 dark:text-white rounded-lg transition-colors" title="Maiúsculas">ABC</button>
                                        <button id="globalNumbering" class="px-3 py-2 text-sm bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 dark:text-white rounded-lg transition-colors" title="Numeração">123</button>
                                    </div>
                                </div>
                                
                                <!-- Botões de Ação -->
                                <div class="flex justify-between pt-4 border-t border-gray-200 dark:border-gray-600">
                                    <button id="resetGlobalSettings" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 underline">
                                        Resetar Tudo
                                    </button>
                                    <div class="space-x-2">
                                        <button id="applyToAll" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                            Aplicar a Todos
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($socialPost->carouselTexts->sortBy('position') as $index => $carouselText)
                    <div class="carousel-item relative">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Slide {{ $index + 1 }}</h4>
                            <div class="flex items-center space-x-2">
                                <button class="carousel-settings-btn relative" data-index="{{ $index }}" title="Configurações do slide">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                        <circle cx="10" cy="3" r="1.5"/>
                                        <circle cx="10" cy="10" r="1.5"/>
                                        <circle cx="10" cy="17" r="1.5"/>
                                    </svg>
                                </button>
                                <button class="download-btn bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-2 py-1 rounded text-xs font-medium transition-colors duration-200" data-type="carousel" data-index="{{ $index }}">
                                    Baixar
                                </button>
                            </div>
                        </div>
                        <canvas id="carouselCanvas{{ $index }}" class="border border-gray-300 dark:border-gray-600 rounded-lg w-full h-auto" data-text="{{ $carouselText->texto }}"></canvas>
                        
                        <!-- Modal de Configurações Flutuante -->
                        <div id="carouselModal{{ $index }}" class="carousel-modal hidden absolute top-0 right-0 mt-8 mr-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg p-4 z-50 w-80">
                            <div class="flex items-center justify-between mb-3">
                                <h5 class="text-sm font-semibold text-gray-800 dark:text-white">Configurações - Slide {{ $index + 1 }}</h5>
                                <button class="close-modal text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300" data-index="{{ $index }}">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="space-y-3">
                                <!-- Tamanho da Fonte -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tamanho da Fonte</label>
                                    <div class="flex items-center space-x-2">
                                        <input type="range" id="fontSize_{{ $index }}" min="16" max="60" value="54" class="flex-1 h-2 bg-blue-200 rounded-lg appearance-none cursor-pointer">
                                    <span id="fontSizeValue_{{ $index }}" class="text-xs font-mono text-blue-600 dark:text-blue-400 w-8">54</span>
                                    </div>
                                </div>
                                
                                <!-- Espessura da Fonte -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Espessura da Fonte</label>
                                    <select id="fontWeight_{{ $index }}" class="w-full text-xs border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-2 py-1">
                                        <option value="300">Fina (Light)</option>
                                        <option value="400" selected>Normal</option>
                                        <option value="500">Média (Medium)</option>
                                        <option value="600">Semi-negrito</option>
                                        <option value="700">Negrito (Bold)</option>
                                        <option value="800">Extra-negrito</option>
                                    </select>
                                </div>
                                
                                <!-- Quebra de Texto -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Quebra de Texto</label>
                                    <select id="textWrap_{{ $index }}" class="w-full text-xs border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-2 py-1">
                                        <option value="auto">Automática</option>
                                        <option value="manual">Manual</option>
                                        <option value="none">Sem Quebra</option>
                                    </select>
                                </div>
                                
                                <!-- Alinhamento do Texto -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Alinhamento do Texto</label>
                                    <select id="textAlign_{{ $index }}" class="w-full text-xs border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-2 py-1">
                                        <option value="left">Esquerda</option>
                                        <option value="center">Centro</option>
                                        <option value="right">Direita</option>
                                        <option value="justify">Justificado</option>
                                    </select>
                                </div>
                                
                                <!-- Formatação -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Formatação</label>
                                    <div class="flex space-x-1">
                                        <button id="uppercase_{{ $index }}" class="px-2 py-1 text-xs bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 dark:text-white rounded transition-colors" title="Maiúsculas">ABC</button>
                                        <button id="numbering_{{ $index }}" class="px-2 py-1 text-xs bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 dark:text-white rounded transition-colors" title="Numeração">123</button>
                                    </div>
                                </div>
                                
                                <!-- Texto Personalizado -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Texto Personalizado</label>
                                    <textarea id="customText_{{ $index }}" class="w-full text-xs border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-2 py-1 h-16 resize-none" placeholder="Digite o texto personalizado...">{{ $carouselText->texto }}</textarea>
                                </div>
                                
                                <!-- Botões de Ação -->
                                <div class="flex justify-between pt-2 border-t border-gray-200 dark:border-gray-600">
                                    <button id="resetSlide_{{ $index }}" class="text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 underline">
                                        Resetar
                                    </button>
                                    <div class="flex space-x-2">
                                        <button id="previewSlide_{{ $index }}" class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white px-3 py-1 rounded text-xs font-medium transition-colors">
                                            Preview
                                        </button>
                                        <button id="saveSlide_{{ $index }}" class="bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600 text-white px-3 py-1 rounded text-xs font-medium transition-colors">
                                            Salvar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Call to Action Image -->
            @if($socialPost->texto_final || $socialPost->call_to_action_image)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Imagem Call-to-Action</h3>
                    <button class="download-btn bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200" data-type="cta">
                        Baixar
                    </button>
                </div>
    
                
                <!-- Canvas Principal para CTA (sempre presente quando há conteúdo) -->
                <div class="image-preview-container mb-4">
                    <canvas id="ctaCanvas" class="border border-gray-300 dark:border-gray-600 rounded-lg max-w-full h-auto"></canvas>
                </div>
                
                @if($socialPost->call_to_action_image)
                    <!-- Informações sobre Imagem -->
                    <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Imagem carregada do storage</span>
                        </div>
                    </div>
                @endif
                
                @if($socialPost->texto_final)
                    <!-- Informações sobre Texto -->
                    <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Texto final: "{{ Str::limit($socialPost->texto_final, 50) }}"</span>
                        </div>
                    </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

<script>


// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM carregado, aguardando inicialização completa...');
});

// Dados do post - declaração global
let postData;

try {
    console.log('=== SCRIPT INICIADO ===');
    // Dados do post
    postData = {
        titulo: {!! json_encode(strip_tags($socialPost->titulo, '<b><i><u><s><sub><sup>')) !!},
        legenda: {!! json_encode(strip_tags($socialPost->legenda, '<b><i><u><s><sub><sup>')) !!},
        textoFinal: {!! json_encode(strip_tags($socialPost->texto_final, '<b><i><u><s><sub><sup>')) !!},
        carouselTexts: {!! json_encode($socialPost->carouselTexts->pluck('texto')->map(function($text) { return strip_tags($text, '<b><i><u><s><sub><sup>'); })->toArray()) !!},
        callToActionImage: @json($socialPost->call_to_action_image)
    };
    
    // Dados já contêm as tags HTML preservadas para processamento visual    console.log('Dados do post carregados:', postData);    console.log('CarouselTexts count:', postData.carouselTexts ? postData.carouselTexts.length : 0);    console.log('CarouselTexts data:', postData.carouselTexts);
} catch (error) {
    console.error('Erro ao carregar dados do post:', error);
    alert('Erro no JavaScript: ' + error.message);
    // Fallback para evitar erros
    postData = {
        titulo: '',
        legenda: '',
        textoFinal: '',
        carouselTexts: []
    };
}

// Dados das cores
const savedColors = @json($savedColors ?? []);
const defaultColor = @json($defaultColor ?? null);
const postColor = @json($postColor ?? null);
const postColors = @json($postColors ?? []);
const defaultColors = @json($defaultColors ?? []);
const globalColors = @json($globalColors ?? []);

// Configurações de formato
const formatConfigs = {
    stories: { width: 1080, height: 1920, ratio: '9:16' },
    square: { width: 1080, height: 1080, ratio: '1:1' },
    rectangular: { width: 1080, height: 1350, ratio: '4:5' }
};

let currentFormat = 'stories';
// Prioridade: cor específica do post > cor padrão > cor dourada
let currentBackgroundColor = postColor ? postColor.color_hex : (defaultColor ? defaultColor.color_hex : '#FFD700');
let currentPostColorId = postColor ? postColor.id : null;

// Teste de execução do script
window.addEventListener('load', function() {
    console.log('=== WINDOW LOAD - SCRIPT EXECUTANDO ===');
    console.log('Título do post:', postData.titulo);
});

console.log('=== SCRIPT EXECUTANDO ===');
console.log('Título do post:', postData.titulo);

// Inicializar quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== PÁGINA CARREGADA ===');
    console.log('JavaScript funcionando - iniciando geração de imagens...');
    
    // Aguardar carregamento da fonte Libre Franklin
    document.fonts.ready.then(function() {
        console.log('Fontes carregadas - iniciando aplicação');
        initializeFormatSelector();
        initializeColorSelector();
        initializeJustifyControls();
        generateAllImages();
        setupDownloadButtons();
        updateFormatDisplay();
        updateColorInputs();
        highlightActiveColor();
        // Inicializar editor global e modais individuais
        initializeGlobalCarouselEditor();
        initializeCarouselModals();
        setupDeleteColorModalEvents();
    }).catch(function() {
        console.log('Erro no carregamento das fontes - iniciando mesmo assim');
        initializeFormatSelector();
        initializeColorSelector();
        initializeJustifyControls();
        generateAllImages();
        setupDownloadButtons();
        updateFormatDisplay();
        updateColorInputs();
        highlightActiveColor();
        
        // Inicializar editor global e modais individuais (fallback)
        initializeGlobalCarouselEditor();
        initializeCarouselModals();
        setupDeleteColorModalEvents();
    });
});

// Inicializar controles de alinhamento justificado
function initializeJustifyControls() {
    // Slider de quebra de linha
    const lineBreakSlider = document.getElementById('lineBreakSlider');
    const lineBreakValue = document.getElementById('lineBreakValue');
    
    if (lineBreakSlider && lineBreakValue) {
        lineBreakSlider.addEventListener('input', function() {
            maxLinesForBreak = parseInt(this.value);
            lineBreakValue.textContent = this.value;
            updateHighlightOptions();
            generateTitleImage();
        });
    }
    
    // Seletor de linha destacada
    const highlightLineSelect = document.getElementById('highlightLineSelect');
    if (highlightLineSelect) {
        highlightLineSelect.addEventListener('change', function() {
            highlightedLineIndex = this.value === 'none' ? 0 : parseInt(this.value);
            generateTitleImage();
        });
    }
    
    // Slider de espaçamento
    const lineSpacingSlider = document.getElementById('lineSpacingSlider');
    const lineSpacingValue = document.getElementById('lineSpacingValue');
    
    if (lineSpacingSlider && lineSpacingValue) {
        lineSpacingSlider.addEventListener('input', function() {
            lineSpacingMultiplier = parseFloat(this.value);
            lineSpacingValue.textContent = this.value;
            generateTitleImage();
        });
    }
    
    // Botão de toggle justificar
    const justifyToggle = document.getElementById('justifyToggle');
    if (justifyToggle) {
        justifyToggle.addEventListener('click', function() {
            isJustifyEnabled = !isJustifyEnabled;
            this.textContent = `Justificar: ${isJustifyEnabled ? 'ON' : 'OFF'}`;
            this.className = isJustifyEnabled 
                ? 'flex-1 bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs font-medium transition-colors duration-200'
                : 'flex-1 bg-gray-600 hover:bg-gray-700 text-white px-2 py-1 rounded text-xs font-medium transition-colors duration-200';
            generateTitleImage();
        });
    }
    
    // Botão de preview
    const previewBtn = document.getElementById('previewBtn');
    if (previewBtn) {
        previewBtn.addEventListener('click', function() {
            generateTitleImage();
            showNotification('Preview atualizado!', 'success');
        });
    }
    
    // Inicializar opções do seletor
    updateHighlightOptions();
    
    // Inicializar controles de espaçamento personalizado
    initializeCustomSpacingControls();
}

// Atualizar opções do seletor de linha destacada baseado no número de linhas
function updateHighlightOptions() {
    const highlightLineSelect = document.getElementById('highlightLineSelect');
    if (!highlightLineSelect) return;
    
    const currentValue = highlightLineSelect.value;
    highlightLineSelect.innerHTML = '<option value="none">Nenhuma</option>';
    
    for (let i = 1; i <= maxLinesForBreak; i++) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = `${getOrdinalText(i)} linha`;
        if (currentValue == i) {
            option.selected = true;
        }
        highlightLineSelect.appendChild(option);
    }
    
    // Se o valor atual não existe mais, resetar para "nenhuma"
    if (parseInt(currentValue) > maxLinesForBreak && currentValue !== 'none') {
        highlightLineSelect.value = 'none';
        highlightedLineIndex = 0;
    }
}

// Função auxiliar para texto ordinal
function getOrdinalText(num) {
    const ordinals = ['', 'Primeira', 'Segunda', 'Terceira', 'Quarta', 'Quinta'];
    return ordinals[num] || `${num}ª`;
}

// Configurar seletor de formato
function initializeFormatSelector() {
    const formatRadios = document.querySelectorAll('.format-radio');
    const formatOptions = document.querySelectorAll('.format-option');
    
    formatRadios.forEach((radio, index) => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                currentFormat = this.value;
                
                // Atualizar visual dos seletores
                formatOptions.forEach(option => {
                    option.classList.remove('border-blue-500', 'bg-blue-50', 'dark:border-blue-500', 'dark:bg-blue-900');
                    option.classList.add('border-gray-200', 'dark:border-gray-600');
                });
                
                formatOptions[index].classList.remove('border-gray-200', 'dark:border-gray-600');
                formatOptions[index].classList.add('border-blue-500', 'bg-blue-50', 'dark:border-blue-500', 'dark:bg-blue-900');
                
                // Atualizar display do formato
                updateFormatDisplay();
                
                // Regenerar imagens
                generateAllImages();
            }
        });
    });
    
    // Marcar o primeiro como selecionado
    formatOptions[0].classList.add('border-blue-500', 'bg-blue-50', 'dark:border-blue-500', 'dark:bg-blue-900');
}

// Gerar todas as imagens
function generateAllImages() {
    console.log('=== GENERATEALLIMAGES INICIADA ===');
    console.log('postData completo:', postData);
    console.log('postData.textoFinal:', postData.textoFinal);
    console.log('postData.callToActionImage:', postData.callToActionImage);
    
    generateTitleImage();
    
    // Garantir que o carouselEditorConfig está inicializado antes de gerar imagens
    if (postData.carouselTexts && postData.carouselTexts.length > 0) {
        initializeCarouselEditorConfig();
        generateCarouselImages();
    }
    
    console.log('Verificando condição para CTA - callToActionImage existe?', !!postData.callToActionImage);
    console.log('Verificando condição para CTA - textoFinal existe?', !!postData.textoFinal);
    if (postData.callToActionImage || postData.textoFinal) {
        console.log('Chamando generateCtaImage...');
        generateCtaImage();
    } else {
        console.log('Não chamando generateCtaImage - nem imagem nem texto final existem');
    }
    
    console.log('=== GENERATEALLIMAGES FINALIZADA ===');
}

// Função para inicializar carouselEditorConfig se necessário
function initializeCarouselEditorConfig() {
    // Garantir que carouselEditorConfig existe
    if (!window.carouselEditorConfig) {
        window.carouselEditorConfig = {
            isOpen: false,
            globalSettings: {
                fontSize: 54,
                fontWeight: '600',
                textWrap: 'auto',
                textAlignment: 'center',
                uppercase: false,
                numbering: false
            },
            individualSettings: {}
        };
    }
    
    // Garantir que individualSettings existe
    if (!window.carouselEditorConfig.individualSettings) {
        window.carouselEditorConfig.individualSettings = {};
    }
    
    // Verificar se individualSettings está vazio ou não inicializado
    if (Object.keys(window.carouselEditorConfig.individualSettings).length === 0) {
        if (postData.carouselTexts && postData.carouselTexts.length > 0) {
            postData.carouselTexts.forEach((text, index) => {
                window.carouselEditorConfig.individualSettings[index] = {
                    fontSize: window.carouselEditorConfig.globalSettings.fontSize,
                    textWrap: window.carouselEditorConfig.globalSettings.textWrap,
                    textAlignment: window.carouselEditorConfig.globalSettings.textAlignment,
                    uppercase: window.carouselEditorConfig.globalSettings.uppercase,
                    numbering: window.carouselEditorConfig.globalSettings.numbering,
                    customText: text || ''
                };
            });
        }
    }
    
    // Garantir que todos os slides tenham customText definido
    if (postData.carouselTexts && postData.carouselTexts.length > 0) {
        postData.carouselTexts.forEach((text, index) => {
            if (!window.carouselEditorConfig.individualSettings[index]) {
                window.carouselEditorConfig.individualSettings[index] = {
                    fontSize: window.carouselEditorConfig.globalSettings.fontSize,
                    textWrap: window.carouselEditorConfig.globalSettings.textWrap,
                    textAlignment: window.carouselEditorConfig.globalSettings.textAlignment,
                    uppercase: window.carouselEditorConfig.globalSettings.uppercase,
                    numbering: window.carouselEditorConfig.globalSettings.numbering,
                    customText: text || ''
                };
            }
            
            // Garantir que customText sempre existe
            if (window.carouselEditorConfig.individualSettings[index].customText === undefined) {
                window.carouselEditorConfig.individualSettings[index].customText = text || '';
            }
        });
    }
}

// Variáveis globais para controles de texto
let isJustifyEnabled = true;
let maxLinesForBreak = 3;
let lineSpacingMultiplier = 1.5;
let highlightedLineIndex = 2; // Linha destacada (1-based, 0 = nenhuma)

// Configurações de espaçamento personalizado para cada linha
let customLineSpacing = {
    enabled: false,
    spacings: [1.2, 1.5, 1.8, 2.0, 1.6] // Espaçamento para cada linha (multiplicador)
};

// Configurações do editor de carrossel (declarada globalmente)
// carouselEditorConfig será inicializada pela função initializeCarouselEditorConfig()

// Função para ativar/desativar espaçamento personalizado
function toggleCustomLineSpacing() {
    customLineSpacing.enabled = !customLineSpacing.enabled;
    generateTitleImage();
}

// Função para definir espaçamento de uma linha específica
function setLineSpacing(lineIndex, spacing) {
    if (lineIndex >= 0 && lineIndex < customLineSpacing.spacings.length) {
        customLineSpacing.spacings[lineIndex] = spacing;
        if (customLineSpacing.enabled) {
            generateTitleImage();
        }
    }
}

// Função para obter espaçamento de uma linha específica
function getLineSpacing(lineIndex) {
    if (customLineSpacing.enabled && lineIndex < customLineSpacing.spacings.length) {
        return customLineSpacing.spacings[lineIndex];
    }
    return lineSpacingMultiplier; // Usar espaçamento padrão
}

// Inicializar controles de espaçamento personalizado
function initializeCustomSpacingControls() {
    const toggleButton = document.getElementById('customSpacingToggle');
    const controlsDiv = document.getElementById('customSpacingControls');
    
    if (toggleButton) {
        toggleButton.addEventListener('click', function() {
            toggleCustomLineSpacing();
            updateCustomSpacingUI();
        });
    }
}

// Atualizar interface do espaçamento personalizado
function updateCustomSpacingUI() {
    const toggleButton = document.getElementById('customSpacingToggle');
    const controlsDiv = document.getElementById('customSpacingControls');
    const inputsDiv = document.getElementById('lineSpacingInputs');
    
    if (!toggleButton || !controlsDiv || !inputsDiv) return;
    
    if (customLineSpacing.enabled) {
        toggleButton.textContent = 'ON';
        toggleButton.classList.remove('bg-gray-600', 'hover:bg-gray-700');
        toggleButton.classList.add('bg-green-600', 'hover:bg-green-700');
        controlsDiv.classList.remove('hidden');
        
        // Gerar controles para cada linha
        generateLineSpacingInputs();
    } else {
        toggleButton.textContent = 'OFF';
        toggleButton.classList.remove('bg-green-600', 'hover:bg-green-700');
        toggleButton.classList.add('bg-gray-600', 'hover:bg-gray-700');
        controlsDiv.classList.add('hidden');
        inputsDiv.innerHTML = '';
    }
}

// Gerar inputs para controle de espaçamento de cada linha
function generateLineSpacingInputs() {
    const inputsDiv = document.getElementById('lineSpacingInputs');
    if (!inputsDiv) return;
    
    // Simular criação de linhas para obter o número correto
    const canvas = document.getElementById('titleCanvas');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    const config = formatConfigs[currentFormat];
    const text = postData.titulo.toUpperCase();
    const maxWidth = config.width * 0.85;
    
    let lines;
    if (isJustifyEnabled) {
        lines = createJustifiedLines(ctx, text, maxWidth, maxLinesForBreak);
    } else {
        const baseFontSize = Math.floor(config.width / 18);
        ctx.font = `bold ${baseFontSize}px Libre Franklin, Arial, sans-serif`;
        lines = wrapText(ctx, text, maxWidth).map(line => ({
            text: line,
            fontSize: baseFontSize,
            isHighlighted: false
        }));
    }
    
    inputsDiv.innerHTML = '';
    
    lines.forEach((line, index) => {
        // Garantir que temos espaçamento para esta linha
        if (index >= customLineSpacing.spacings.length) {
            customLineSpacing.spacings.push(lineSpacingMultiplier);
        }
        
        const currentSpacing = customLineSpacing.spacings[index];
        
        const lineDiv = document.createElement('div');
        lineDiv.className = 'flex items-center space-x-2';
        
        lineDiv.innerHTML = `
            <label class="text-xs text-blue-600 w-12 flex-shrink-0">L${index + 1}:</label>
            <input type="range" 
                   id="lineSpacing_${index}" 
                   min="0.5" 
                   max="3.0" 
                   step="0.1" 
                   value="${currentSpacing}" 
                   class="flex-1 h-2 bg-blue-200 rounded-lg appearance-none cursor-pointer">
            <span id="lineSpacingValue_${index}" class="text-xs font-mono text-blue-600 w-8 flex-shrink-0">${currentSpacing.toFixed(1)}</span>
        `;
        
        inputsDiv.appendChild(lineDiv);
        
        // Adicionar event listener
        const slider = lineDiv.querySelector(`#lineSpacing_${index}`);
        const valueSpan = lineDiv.querySelector(`#lineSpacingValue_${index}`);
        
        if (slider && valueSpan) {
            slider.addEventListener('input', function() {
                const value = parseFloat(this.value);
                setLineSpacing(index, value);
                valueSpan.textContent = value.toFixed(1);
            });
        }
    });
}

// Função auxiliar para adicionar imagem de fundo
function addBackgroundImage(ctx, config, callback) {
    const backgroundImg = new Image();
    backgroundImg.crossOrigin = 'anonymous';
    
    backgroundImg.onload = function() {
        // Posicionar no canto inferior direito
        const originalWidth = backgroundImg.width;
        const originalHeight = backgroundImg.height;
        
        // Aumentar o tamanho da imagem (escala de 1.5x)
        const imgWidth = originalWidth * 1.5;
        const imgHeight = originalHeight * 1.5;
        
        // Calcular posição: bottom-right, estendendo além da borda direita e inferior
        const x = config.width - (imgWidth * 0.7); // 30% da imagem fica fora da tela à direita
        const y = config.height - (imgHeight * 0.8); // 20% da imagem fica fora da tela na parte inferior
        
        // Aplicar opacidade de 80%
        ctx.globalAlpha = 0.8;
        
        // Aplicar blend mode 'multiply' para criar efeito mais integrado
        ctx.globalCompositeOperation = 'multiply';
        
        // Desenhar a imagem de fundo com tamanho aumentado
        ctx.drawImage(backgroundImg, x, y, imgWidth, imgHeight);
        
        // Restaurar blend mode padrão e opacidade para próximos elementos
        ctx.globalCompositeOperation = 'source-over';
        ctx.globalAlpha = 1.0;
        
        console.log(`Imagem de fundo adicionada com blend multiply: ${imgWidth}x${imgHeight} (escala 1.5x) na posição (${x}, ${y})`);
        
        // Executar callback se fornecido
        if (callback) callback();
    };
    
    backgroundImg.onerror = function() {
        console.warn('Erro ao carregar imagem de fundo fundo_storie.png');
        // Continuar sem a imagem de fundo
        if (callback) callback();
    };
    
    // Carregar a imagem do storage
    backgroundImg.src = '/storage/fundo_storie.png';
}

// Função para adicionar seta de navegação no canto inferior direito
function addNavigationArrow(ctx, config, callback) {
    // Configurações da seta
    const arrowSize = 120; // Tamanho aumentado para 3x (40px * 3 = 120px)
    const padding = 30; // Distância das bordas
    const arrowX = config.width - padding - arrowSize;
    const arrowY = config.height - padding - arrowSize;
    
    // Criar nova imagem para a seta
    const arrowImg = new Image();
    
    arrowImg.onload = function() {
        // Salvar estado do contexto
        ctx.save();
        
        // Calcular proporções da imagem para manter aspect ratio
        const imgWidth = arrowImg.naturalWidth;
        const imgHeight = arrowImg.naturalHeight;
        const aspectRatio = imgWidth / imgHeight;
        
        // Calcular dimensões finais mantendo proporções
        let finalWidth, finalHeight;
        if (aspectRatio > 1) {
            // Imagem mais larga que alta
            finalWidth = arrowSize;
            finalHeight = arrowSize / aspectRatio;
        } else {
            // Imagem mais alta que larga ou quadrada
            finalHeight = arrowSize;
            finalWidth = arrowSize * aspectRatio;
        }
        
        // Ajustar posição para manter alinhamento no canto inferior direito
        const adjustedX = config.width - padding - finalWidth;
        const adjustedY = config.height - padding - finalHeight;
        
        // Desenhar a imagem da seta com proporções corretas
        ctx.drawImage(arrowImg, adjustedX, adjustedY, finalWidth, finalHeight);
        
        // Restaurar estado do contexto
        ctx.restore();
        
        console.log(`Seta de navegação (logo_seta.png) adicionada na posição (${adjustedX}, ${adjustedY}) com dimensões ${finalWidth}x${finalHeight}px (aspect ratio: ${aspectRatio.toFixed(2)})`);
        
        // Chamar callback se fornecido
        if (callback) callback();
    };
    
    arrowImg.onerror = function() {
        console.warn('Erro ao carregar imagem da seta logo_seta.png');
        // Continuar sem a seta
        if (callback) callback();
    };
    
    // Carregar a imagem do storage
    arrowImg.src = '/storage/logo_seta.png';
}

// Gerar imagem do título com alinhamento justificado
// Função para processar tags HTML e converter em texto formatado
function processHtmlFormatting(htmlText) {
    if (!htmlText) return '';
    
    // Remover tags HTML e manter apenas o texto
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = htmlText;
    return tempDiv.textContent || tempDiv.innerText || '';
}

// Função para aplicar formatação visual no canvas baseada em tags HTML
function renderFormattedText(ctx, htmlText, x, y, maxWidth, fontSize, color = '#000000') {
    if (!htmlText) return;
    
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = htmlText;
    
    let currentX = x;
    let currentY = y;
    
    // Processar cada nó do HTML
    function processNode(node) {
        if (node.nodeType === Node.TEXT_NODE) {
            // Texto simples
            ctx.fillStyle = color;
            ctx.font = `${fontSize}px Libre Franklin, Arial, sans-serif`;
            ctx.fillText(node.textContent, currentX, currentY);
            currentX += ctx.measureText(node.textContent).width;
        } else if (node.nodeType === Node.ELEMENT_NODE) {
            // Elemento HTML
            const tagName = node.tagName.toLowerCase();
            
            // Salvar configurações atuais
            const originalFont = ctx.font;
            const originalFillStyle = ctx.fillStyle;
            
            // Aplicar formatação baseada na tag
            switch (tagName) {
                case 'b':
                case 'strong':
                    ctx.font = `bold ${fontSize}px Libre Franklin, Arial, sans-serif`;
                    break;
                case 'i':
                case 'em':
                    ctx.font = `italic ${fontSize}px Libre Franklin, Arial, sans-serif`;
                    break;
                case 'u':
                    // Para sublinhado, renderizar texto e depois linha
                    ctx.font = `${fontSize}px Libre Franklin, Arial, sans-serif`;
                    break;
                case 's':
                case 'strike':
                case 'del':
                    // Para riscado, renderizar texto e depois linha
                    ctx.font = `${fontSize}px Libre Franklin, Arial, sans-serif`;
                    break;
                case 'sub':
                    ctx.font = `${Math.floor(fontSize * 0.7)}px Libre Franklin, Arial, sans-serif`;
                    currentY += fontSize * 0.2; // Mover para baixo
                    break;
                case 'sup':
                    ctx.font = `${Math.floor(fontSize * 0.7)}px Libre Franklin, Arial, sans-serif`;
                    currentY -= fontSize * 0.2; // Mover para cima
                    break;
            }
            
            // Processar conteúdo do elemento
            for (let child of node.childNodes) {
                processNode(child);
            }
            
            // Adicionar decorações especiais
            if (tagName === 'u') {
                // Desenhar linha de sublinhado
                const textWidth = ctx.measureText(node.textContent).width;
                ctx.beginPath();
                ctx.moveTo(currentX - textWidth, currentY + fontSize * 0.1);
                ctx.lineTo(currentX, currentY + fontSize * 0.1);
                ctx.strokeStyle = color;
                ctx.lineWidth = 1;
                ctx.stroke();
            } else if (tagName === 's' || tagName === 'strike' || tagName === 'del') {
                // Desenhar linha de riscado
                const textWidth = ctx.measureText(node.textContent).width;
                ctx.beginPath();
                ctx.moveTo(currentX - textWidth, currentY - fontSize * 0.2);
                ctx.lineTo(currentX, currentY - fontSize * 0.2);
                ctx.strokeStyle = color;
                ctx.lineWidth = 1;
                ctx.stroke();
            }
            
            // Restaurar configurações
            if (tagName === 'sub' || tagName === 'sup') {
                currentY = y; // Restaurar posição Y original
            }
            ctx.font = originalFont;
            ctx.fillStyle = originalFillStyle;
        }
    }
    
    // Processar todos os nós
    for (let child of tempDiv.childNodes) {
        processNode(child);
    }
}

function generateTitleImage() {
    const canvas = document.getElementById('titleCanvas');
    if (!canvas) {
        console.error('Canvas titleCanvas não encontrado!');
        return;
    }
    
    const ctx = canvas.getContext('2d');
    const config = formatConfigs[currentFormat];
    
    // Configurar canvas
    canvas.width = config.width;
    canvas.height = config.height;
    
    // Fundo com cor selecionada
    ctx.fillStyle = currentBackgroundColor;
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    
    // Adicionar imagem de fundo primeiro
    addBackgroundImage(ctx, config, function() {
        // Continuar com a geração do título após adicionar o fundo
        generateTitleContent(ctx, config, canvas);
    });
}

// Função separada para gerar o conteúdo do título
function generateTitleContent(ctx, config, canvas) {
    
    // Configurações de texto
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    
    // Processar HTML tags para formatação visual
    const text = processHtmlFormatting(postData.titulo).toUpperCase();
    const maxWidth = config.width * 0.85;
    
    let lines;
    if (isJustifyEnabled) {
        lines = createJustifiedLines(ctx, text, maxWidth, maxLinesForBreak);
    } else {
        // Usar quebra de linha tradicional
        const baseFontSize = Math.floor(config.width / 18);
        ctx.font = `bold ${baseFontSize}px Libre Franklin, Arial, sans-serif`;
        const enableHyphenation = isJustifyEnabled; // Habilitar hifenização se justificado estiver ativo
        lines = wrapText(ctx, text, maxWidth, enableHyphenation).map(line => ({
            text: line,
            fontSize: baseFontSize,
            isHighlighted: false
        }));
    }
    
    // Calcular posicionamento vertical com espaçamento dinâmico personalizado
    let totalHeight = 0;
    lines.forEach((line, index) => {
        // Usar espaçamento personalizado se ativado, senão usar padrão
        const spacingMultiplier = getLineSpacing(index);
        const baseSpacing = line.fontSize * spacingMultiplier;
        
        // Adicionar espaçamento extra se a linha anterior tem destaque
        const extraSpacing = (index > 0 && lines[index - 1].isHighlighted) ? line.fontSize * 0.4 : 0;
        totalHeight += baseSpacing + extraSpacing;
        
        // Log para debug do espaçamento personalizado
        if (customLineSpacing.enabled) {
            console.log(`Linha ${index + 1}: espaçamento ${spacingMultiplier}x = ${baseSpacing}px`);
        }
    });
    
    let currentY = (config.height - totalHeight) / 2;
    
    // Renderizar cada linha
    lines.forEach((lineData, index) => {
        const { text, fontSize, isHighlighted } = lineData;
        
        // Configurar fonte para esta linha
        ctx.font = `bold ${fontSize}px Libre Franklin, Arial, sans-serif`;
        
        // Calcular espaçamento personalizado para esta linha
        const spacingMultiplier = getLineSpacing(index);
        const baseSpacing = fontSize * spacingMultiplier;
        const extraSpacing = (index > 0 && lines[index - 1].isHighlighted) ? fontSize * 0.4 : 0;
        
        // Calcular posição Y para esta linha
        currentY += (baseSpacing + extraSpacing) / 2;
        
        if (isHighlighted) {
            // Medir o texto para criar a caixa de fundo
            const textMetrics = ctx.measureText(text);
            const textWidth = textMetrics.width;
            const padding = fontSize * 0.3;
            // Aumentar altura da caixa para dar mais espaço na parte inferior
            const boxHeight = fontSize + (padding * 1.8);
            
            // Ajustar posição da caixa para mover ligeiramente para cima
            const boxY = currentY - (boxHeight / 2) - (fontSize * 0.1);
            
            // Desenhar caixa de fundo preta
            ctx.fillStyle = '#000000';
            ctx.fillRect(
                (config.width - textWidth) / 2 - padding,
                boxY,
                textWidth + (padding * 2),
                boxHeight
            );
            
            // Desenhar sombra branca opaca na parte inferior (espessura aumentada para 6px)
            const shadowHeight = 16;
            ctx.fillStyle = 'rgba(255, 255, 255, 1)';
            ctx.fillRect(
                (config.width - textWidth) / 2 - padding,
                boxY + boxHeight,
                textWidth + (padding * 2),
                shadowHeight
            );
            
            // Texto branco centralizado sobre fundo preto
            ctx.fillStyle = '#FFFFFF';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            
            // Posicionar texto exatamente no currentY (centro da linha)
            ctx.fillText(text, config.width / 2, currentY);
        } else {
            // Texto sem destaque na cor preta (mesma cor da caixa de destaque)
            ctx.fillStyle = '#000000';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(text, config.width / 2, currentY);
        }
        
        // Avançar para próxima linha
        currentY += (baseSpacing + extraSpacing) / 2;
    });
    
    // Adicionar seta de navegação
    addNavigationArrow(ctx, config, function() {
        // Ajustar tamanho de exibição após adicionar a seta
        adjustCanvasDisplaySize(canvas);
    });
}

// Função para criar linhas justificadas com tamanhos de fonte variáveis
function createJustifiedLines(ctx, text, maxWidth, maxLines) {
    const words = text.split(' ');
    const lines = [];
    
    // Dividir texto em linhas baseado no número máximo
    const wordsPerLine = Math.ceil(words.length / maxLines);
    
    for (let i = 0; i < maxLines && i * wordsPerLine < words.length; i++) {
        const startIndex = i * wordsPerLine;
        const endIndex = Math.min(startIndex + wordsPerLine, words.length);
        const lineWords = words.slice(startIndex, endIndex);
        const lineText = lineWords.join(' ');
        
        // Calcular tamanho de fonte ideal para esta linha ocupar 100% da largura
        const optimalFontSize = calculateOptimalFontSize(ctx, lineText, maxWidth);
        
        // Determinar se esta linha deve ter destaque baseado no controle
        const isHighlighted = highlightedLineIndex > 0 && (i + 1) === highlightedLineIndex;
        
        lines.push({
            text: lineText,
            fontSize: optimalFontSize,
            isHighlighted: isHighlighted
        });
    }
    
    return lines;
}

// Função para calcular o tamanho de fonte ideal
function calculateOptimalFontSize(ctx, text, targetWidth) {
    let fontSize = 20;
    let maxFontSize = 200;
    let minFontSize = 20;
    
    // Busca binária para encontrar o tamanho ideal
    while (maxFontSize - minFontSize > 1) {
        fontSize = Math.floor((maxFontSize + minFontSize) / 2);
        ctx.font = `bold ${fontSize}px Libre Franklin, Arial, sans-serif`;
        
        const textWidth = ctx.measureText(text).width;
        
        if (textWidth > targetWidth) {
            maxFontSize = fontSize;
        } else {
            minFontSize = fontSize;
        }
    }
    
    return minFontSize;
}

// Gerar imagens do carrossel
function generateCarouselImages() {
    postData.carouselTexts.forEach((text, index) => {
        generateCarouselImageWithSettings(index);
    });
}

// Função para gerar imagem do carrossel com configurações específicas
function generateCarouselImageWithSettings(index, customSettings = null) {
    const canvas = document.getElementById(`carouselCanvas${index}`);
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    const config = formatConfigs[currentFormat];
    
    // Garantir que carouselEditorConfig está inicializado
    if (!window.carouselEditorConfig.individualSettings) {
        window.carouselEditorConfig.individualSettings = {};
    }
    
    // Usar configurações do editor se disponíveis
    const editorSettings = window.carouselEditorConfig.individualSettings[index] || {};
    
    // Garantir que sempre temos um texto válido
    let fallbackText = '';
    if (postData.carouselTexts && postData.carouselTexts[index]) {
        fallbackText = postData.carouselTexts[index];
    } else if (index === 0) {
        fallbackText = 'Texto do slide não encontrado';
    } else {
        fallbackText = `Slide ${index + 1}`;
    }
    
    // Garantir que globalSettings existe
    if (!window.carouselEditorConfig.globalSettings) {
        window.carouselEditorConfig.globalSettings = {
        fontSize: 54,
        fontWeight: '600',
        textWrap: 'auto',
        textAlignment: 'center',
        uppercase: false,
        numbering: false
    };
    }
    
    const settings = customSettings || {
        fontSize: editorSettings.fontSize || window.carouselEditorConfig.globalSettings.fontSize,
        textWrap: editorSettings.textWrap || window.carouselEditorConfig.globalSettings.textWrap,
        textAlignment: editorSettings.textAlignment || window.carouselEditorConfig.globalSettings.textAlignment,
        uppercase: editorSettings.uppercase !== undefined ? editorSettings.uppercase : window.carouselEditorConfig.globalSettings.uppercase,
        numbering: editorSettings.numbering !== undefined ? editorSettings.numbering : window.carouselEditorConfig.globalSettings.numbering,
        customText: editorSettings.customText !== undefined ? editorSettings.customText : fallbackText
    };
    
    // Configurar canvas
    canvas.width = config.width;
    canvas.height = config.height;
    
    // Fundo com cor selecionada
    ctx.fillStyle = currentBackgroundColor;
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    
    // Adicionar imagem de fundo primeiro
    addBackgroundImage(ctx, config, function() {
        // Continuar com a geração do carrossel após adicionar o fundo
        generateCarouselImageContent(ctx, config, canvas, index, settings);
    });
}

// Função separada para gerar o conteúdo do carrossel
function generateCarouselImageContent(ctx, config, canvas, index, settings) {
    // Texto (cor preta para consistência)
    ctx.fillStyle = '#000000';
    ctx.textAlign = 'center';
    
    // Preparar texto - verificação de segurança adicional
    let displayText = settings.customText;
    
    // Verificação final para evitar undefined
    if (!displayText || displayText === undefined || displayText === null) {
        displayText = `Slide ${index + 1} - Texto não disponível`;
        console.warn(`Texto undefined detectado para slide ${index}, usando fallback:`, displayText);
    }
    
    // Processar HTML tags para formatação visual
    displayText = processHtmlFormatting(displayText);
    
    // Aplicar formatação maiúscula
    if (settings.uppercase) {
        displayText = displayText.toUpperCase();
    }
    
    // Adicionar numeração se habilitada
    if (settings.numbering) {
        displayText = `${index + 1}. ${displayText}`;
    }
    
    // Detectar título até o primeiro ponto final
    const firstPeriodIndex = displayText.indexOf('.');
    let titleText = '';
    let explanatoryText = '';
    
    if (firstPeriodIndex !== -1) {
        titleText = displayText.substring(0, firstPeriodIndex + 1).trim();
        explanatoryText = displayText.substring(firstPeriodIndex + 1).trim();
    } else {
        titleText = displayText.trim();
    }
    
    let allLines = [];
    
    // Usar tamanho de fonte personalizado ou padrão
    const baseFontSize = settings.fontSize || Math.floor(config.width / 25);
    
    // Processar título
    if (titleText) {
        const fontSize = baseFontSize;
        const fontWeight = settings.fontWeight || 'normal';
        ctx.font = `${fontWeight} ${fontSize}px Libre Franklin, Arial, sans-serif`;
        
        let titleLines = [];
        if (settings.textWrap === 'none') {
            titleLines = [titleText];
        } else {
            const enableHyphenation = settings.textAlignment === 'justify';
            titleLines = wrapText(ctx, titleText, config.width * 0.8, enableHyphenation);
        }
        
        titleLines.forEach(line => {
            allLines.push({ text: line, fontSize, fontWeight, isTitle: true });
        });
        
        // Adicionar espaço após o título se há texto explicativo
        if (explanatoryText) {
            allLines.push({ text: '', fontSize, fontWeight, isTitle: false, isSpace: true });
        }
    }
    
    // Processar texto explicativo
    if (explanatoryText) {
        const fontSize = baseFontSize;
        const fontWeight = settings.fontWeight || 'normal';
        ctx.font = `${fontWeight} ${fontSize}px Libre Franklin, Arial, sans-serif`;
        
        let explanatoryLines = [];
        if (settings.textWrap === 'none') {
            explanatoryLines = [explanatoryText];
        } else {
            const enableHyphenation = settings.textAlignment === 'justify';
            explanatoryLines = wrapText(ctx, explanatoryText, config.width * 0.8, enableHyphenation);
        }
        
        explanatoryLines.forEach(line => {
            allLines.push({ text: line, fontSize, fontWeight, isTitle: false });
        });
    }
    
    // Calcular altura total
    const baseLineHeight = baseFontSize * 1.2;
    const totalHeight = allLines.reduce((height, line) => {
        if (line.isSpace) return height + baseLineHeight * 0.5;
        return height + (line.fontSize * 1.2);
    }, 0);
    
    // Sempre centralizar verticalmente
    let currentY = (config.height - totalHeight) / 2;
    ctx.textBaseline = 'top';
    
    // Configurar alinhamento horizontal
    switch (settings.textAlignment) {
        case 'left':
            ctx.textAlign = 'left';
            break;
        case 'right':
            ctx.textAlign = 'right';
            break;
        case 'justify':
            ctx.textAlign = 'left'; // Justificado será tratado especialmente
            break;
        case 'center':
        default:
            ctx.textAlign = 'center';
            break;
    }
    
    // Renderizar todas as linhas
    allLines.forEach(line => {
        if (line.isSpace) {
            currentY += baseLineHeight * 0.5;
            return;
        }
        
        ctx.font = `${line.fontWeight} ${line.fontSize}px Libre Franklin, Arial, sans-serif`;
        const lineHeight = line.fontSize * 1.2;
        
        // Calcular posição X baseada no alinhamento
        let x;
        switch (settings.textAlignment) {
            case 'left':
                x = 50; // Margem esquerda
                ctx.fillText(line.text, x, currentY);
                break;
            case 'right':
                x = config.width - 50; // Margem direita
                ctx.fillText(line.text, x, currentY);
                break;
            case 'justify':
                // Renderização justificada com espaçamento distribuído
                renderJustifiedText(ctx, line.text, 50, config.width - 100, currentY, allLines.indexOf(line) === allLines.length - 1);
                break;
            case 'center':
            default:
                x = config.width / 2;
                ctx.fillText(line.text, x, currentY);
                break;
        }
        currentY += lineHeight;
    });
    
    // Adicionar seta de navegação
    addNavigationArrow(ctx, config, function() {
        // Ajustar tamanho de exibição após adicionar a seta
        adjustCanvasDisplaySize(canvas);
    });
}

// Gerar imagem do CTA
function generateCtaImage() {
    console.log('=== INICIANDO GERAÇÃO DA IMAGEM CTA ===');
    console.log('Texto final:', postData.textoFinal);
    console.log('Imagem CTA do postData:', postData.callToActionImage);
    
    // Verificar se o DOM está carregado
    if (document.readyState === 'loading') {
        console.log('DOM ainda carregando, aguardando...');
        document.addEventListener('DOMContentLoaded', generateCtaImage);
        return;
    }
    
    const canvas = document.getElementById('ctaCanvas');
    if (!canvas) {
        console.error('Canvas ctaCanvas não encontrado! Tentando novamente em 200ms...');
        // Tentar novamente após um pequeno delay para garantir que o DOM esteja pronto
        setTimeout(() => {
            const retryCanvas = document.getElementById('ctaCanvas');
            if (retryCanvas) {
                console.log('Canvas encontrado na segunda tentativa');
                generateCtaImageWithCanvas(retryCanvas);
            } else {
                console.error('Canvas ctaCanvas definitivamente não encontrado no DOM');
                console.log('Elementos canvas disponíveis:', document.querySelectorAll('canvas'));
            }
        }, 200);
        return;
    }
    
    generateCtaImageWithCanvas(canvas);
}

// Função auxiliar para gerar CTA com canvas válido
function generateCtaImageWithCanvas(canvas) {
    console.log('Canvas CTA encontrado:', canvas);
    const ctx = canvas.getContext('2d');
    const config = formatConfigs[currentFormat];
    console.log('Configuração do formato:', config);
    
    // Configurar canvas seguindo o mesmo padrão das outras seções
    canvas.width = config.width;
    canvas.height = config.height;
    
    // Fundo com cor selecionada
    ctx.fillStyle = currentBackgroundColor;
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    
    // Adicionar imagem de fundo primeiro
    addBackgroundImage(ctx, config, function() {
        // Continuar com a geração do CTA após adicionar o fundo
        generateCtaContent(ctx, config, canvas);
    });
}

// Função separada para gerar o conteúdo do CTA
function generateCtaContent(ctx, config, canvas) {
    // Verificar se existe imagem call-to-action salva no postData
    if (postData.callToActionImage) {
    
        // Se existe imagem, renderizar no canvas seguindo o padrão das outras seções
        const img = new Image();
        img.crossOrigin = 'anonymous';
        
        img.onload = function() {
            // Usar as mesmas proporções das outras imagens (85% da largura máxima)
            const maxWidth = config.width * 0.85;
            const maxHeight = config.height * 0.85;
            
            // Calcular proporção da imagem original
            const aspectRatio = img.width / img.height;
            
            // Calcular dimensões finais mantendo proporção (mesmo algoritmo das outras seções)
            let imgWidth, imgHeight;
            
            // Determinar dimensões baseadas na proporção
            if (aspectRatio > maxWidth / maxHeight) {
                // Imagem mais larga - limitar pela largura
                imgWidth = maxWidth;
                imgHeight = imgWidth / aspectRatio;
            } else {
                // Imagem mais alta - limitar pela altura
                imgHeight = maxHeight;
                imgWidth = imgHeight * aspectRatio;
            }
            
            // Centralizar a imagem no canvas (mesmo padrão das outras seções)
            const x = (config.width - imgWidth) / 2;
            const y = (config.height - imgHeight) / 2;
            
            // Desenhar a imagem com as mesmas dimensões das outras seções
            ctx.drawImage(img, x, y, imgWidth, imgHeight);
            
            console.log(`Imagem CTA renderizada no canvas: ${imgWidth}x${imgHeight} na posição (${x}, ${y})`);
            
            
            // Ajustar tamanho de exibição
            adjustCanvasDisplaySize(canvas);
        };
        
        img.onerror = function() {
            console.error('Erro ao carregar imagem call-to-action:', imageUrl);
            console.error('Verificar se o arquivo existe no storage:', postData.callToActionImage);
            console.error('Caminho completo esperado: {{ storage_path("app/public") }}/call-to-action-images/');
            
            // Tentar URL alternativa sem asset helper
            const alternativeUrl = `/storage/${postData.callToActionImage}`;
            console.log('Tentando URL alternativa:', alternativeUrl);
            
            const fallbackImg = new Image();
            fallbackImg.crossOrigin = 'anonymous';
            
            fallbackImg.onload = function() {
                console.log('Imagem carregada com URL alternativa');
                // Usar as mesmas proporções das outras imagens (85% da largura máxima)
                const maxWidth = config.width * 0.85;
                const maxHeight = config.height * 0.85;
                
                // Calcular proporção da imagem original
                const aspectRatio = fallbackImg.width / fallbackImg.height;
                
                // Calcular dimensões finais mantendo proporção
                let imgWidth, imgHeight;
                
                if (aspectRatio > maxWidth / maxHeight) {
                    imgWidth = maxWidth;
                    imgHeight = imgWidth / aspectRatio;
                } else {
                    imgHeight = maxHeight;
                    imgWidth = imgHeight * aspectRatio;
                }
                
                // Centralizar a imagem no canvas
                const x = (config.width - imgWidth) / 2;
                const y = (config.height - imgHeight) / 2;
                
                // Desenhar a imagem
                ctx.drawImage(fallbackImg, x, y, imgWidth, imgHeight);
                console.log(`Imagem CTA renderizada com URL alternativa: ${imgWidth}x${imgHeight}`);
                adjustCanvasDisplaySize(canvas);
            };
            
            fallbackImg.onerror = function() {
                console.error('Falha também com URL alternativa:', alternativeUrl);
                // Fallback para texto se a imagem falhar
                if (postData.textoFinal) {
                    console.log('Usando texto final como fallback para CTA');
                    generateCtaText(ctx, config, canvas);
                } else {
                    console.warn('Nem imagem nem texto final disponíveis para CTA');
                    // Desenhar placeholder
                    ctx.fillStyle = '#f3f4f6';
                    ctx.fillRect(config.width * 0.1, config.height * 0.4, config.width * 0.8, config.height * 0.2);
                    ctx.fillStyle = '#6b7280';
                    ctx.font = `${Math.floor(config.width / 30)}px Arial`;
                    ctx.textAlign = 'center';
                    ctx.fillText('Imagem não encontrada', config.width / 2, config.height / 2);
                    adjustCanvasDisplaySize(canvas);
                }
            };
            
            fallbackImg.src = alternativeUrl;
        };
        
        // Construir URL da imagem a partir do storage usando asset helper do Laravel
        const imageUrl = `{{ asset('storage') }}/${postData.callToActionImage}`;
        console.log('URL da imagem CTA:', imageUrl);
        
        // Verificar se callToActionImage não está vazio
        if (!postData.callToActionImage || postData.callToActionImage.trim() === '') {
            console.warn('callToActionImage está vazio, usando texto final');
            if (postData.textoFinal) {
                generateCtaText(ctx, config, canvas);
            } else {
                adjustCanvasDisplaySize(canvas);
            }
            return;
        }
        
        img.src = imageUrl;
    } else if (postData.textoFinal) {
        // Se não há imagem, usar texto
        console.log('Usando texto final para CTA');
        generateCtaText(ctx, config, canvas);
    } else {
        console.warn('Nem imagem nem texto final encontrados para CTA');
        // Ajustar tamanho de exibição mesmo sem conteúdo
        adjustCanvasDisplaySize(canvas);
    }
}

// Função auxiliar para gerar texto CTA
function generateCtaText(ctx, config, canvas) {
    // Verificar se textoFinal existe e não é null
    if (!postData.textoFinal || postData.textoFinal === null || postData.textoFinal === undefined) {
        console.warn('Texto final não encontrado para CTA');
        adjustCanvasDisplaySize(canvas);
        return;
    }
    
    // Verificar se textoFinal é uma string válida
    let textoFinal = String(postData.textoFinal).trim();
    if (textoFinal === '' || textoFinal === 'null' || textoFinal === 'undefined') {
        console.warn('Texto final está vazio ou inválido para CTA');
        adjustCanvasDisplaySize(canvas);
        return;
    }
    
    // Processar HTML tags para formatação visual
    textoFinal = processHtmlFormatting(textoFinal);
    
    // Texto do CTA (cor preta para consistência)
    ctx.fillStyle = '#000000';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    
    // Calcular tamanho da fonte
    const fontSize = Math.floor(config.width / 22);
    ctx.font = `bold ${fontSize}px Libre Franklin, Arial, sans-serif`;
    
    // Quebrar texto em linhas (convertendo para maiúsculas com verificação)
    // Habilitar hifenização para melhor quebra de texto
    const lines = wrapText(ctx, textoFinal.toUpperCase(), config.width * 0.8, true);
    const lineHeight = fontSize * 1.2;
    const totalHeight = lines.length * lineHeight;
    const startY = (config.height - totalHeight) / 2 + lineHeight / 2;
    
    lines.forEach((line, index) => {
        ctx.fillText(line, config.width / 2, startY + (index * lineHeight));
    });
    
    // Ajustar tamanho de exibição
    adjustCanvasDisplaySize(canvas);
}

// Função para quebrar texto em linhas
function wrapText(ctx, text, maxWidth, enableHyphenation = false) {
    const words = text.split(' ');
    const lines = [];
    let currentLine = words[0];
    
    for (let i = 1; i < words.length; i++) {
        const word = words[i];
        const width = ctx.measureText(currentLine + ' ' + word).width;
        if (width < maxWidth) {
            currentLine += ' ' + word;
        } else {
            // Se hifenização está habilitada e a palavra é longa, tentar quebrar
            if (enableHyphenation && word.length > 6) {
                const hyphenatedResult = tryHyphenateWord(ctx, currentLine, word, maxWidth);
                if (hyphenatedResult.success) {
                    lines.push(hyphenatedResult.firstLine);
                    currentLine = hyphenatedResult.secondLine;
                } else {
                    lines.push(currentLine);
                    currentLine = word;
                }
            } else {
                lines.push(currentLine);
                currentLine = word;
            }
        }
    }
    lines.push(currentLine);
    return lines;
}

// Função para tentar hifenizar uma palavra
function tryHyphenateWord(ctx, currentLine, word, maxWidth) {
    // Pontos comuns de hifenização em português
    const hyphenationPoints = [
        // Prefixos comuns
        'anti', 'auto', 'contra', 'entre', 'extra', 'inter', 'multi', 'sobre', 'super',
        // Sufixos comuns
        'ção', 'são', 'dade', 'mente', 'ismo', 'ista', 'ável', 'ível'
    ];
    
    // Tentar quebrar em pontos de hifenização conhecidos
    for (let point of hyphenationPoints) {
        const index = word.toLowerCase().indexOf(point);
        if (index > 2 && index < word.length - 2) {
            const firstPart = word.substring(0, index + point.length) + '-';
            const secondPart = word.substring(index + point.length);
            
            const testLine = currentLine + ' ' + firstPart;
            if (ctx.measureText(testLine).width <= maxWidth) {
                return {
                    success: true,
                    firstLine: testLine,
                    secondLine: secondPart
                };
            }
        }
    }
    
    // Se não encontrou ponto de hifenização, tentar quebrar no meio de palavras longas
    if (word.length > 8) {
        const breakPoint = Math.floor(word.length * 0.6);
        const firstPart = word.substring(0, breakPoint) + '-';
        const secondPart = word.substring(breakPoint);
        
        const testLine = currentLine + ' ' + firstPart;
        if (ctx.measureText(testLine).width <= maxWidth) {
            return {
                success: true,
                firstLine: testLine,
                secondLine: secondPart
            };
        }
    }
    
    return { success: false };
}

// Função para renderizar texto justificado
function renderJustifiedText(ctx, text, startX, maxWidth, y, isLastLine = false) {
    // Se for a última linha ou linha com uma só palavra, não justificar
    const words = text.split(' ');
    if (isLastLine || words.length === 1) {
        ctx.fillText(text, startX, y);
        return;
    }
    
    // Calcular largura total do texto sem espaços extras
    const totalTextWidth = words.reduce((width, word) => {
        return width + ctx.measureText(word).width;
    }, 0);
    
    // Calcular espaço disponível para distribuir entre as palavras
    const availableSpace = maxWidth - totalTextWidth;
    const gaps = words.length - 1;
    
    if (gaps === 0 || availableSpace <= 0) {
        ctx.fillText(text, startX, y);
        return;
    }
    
    // Calcular espaçamento entre palavras
    const spacePerGap = availableSpace / gaps;
    
    // Renderizar cada palavra com espaçamento calculado
    let currentX = startX;
    for (let i = 0; i < words.length; i++) {
        const word = words[i];
        ctx.fillText(word, currentX, y);
        
        // Mover para a próxima posição
        currentX += ctx.measureText(word).width;
        if (i < words.length - 1) {
            currentX += spacePerGap;
        }
    }
}

// Ajustar tamanho de exibição do canvas
function adjustCanvasDisplaySize(canvas) {
    const container = canvas.parentElement;
    const containerWidth = container.clientWidth;
    const aspectRatio = canvas.height / canvas.width;
    
    canvas.style.width = Math.min(containerWidth, 400) + 'px';
    canvas.style.height = (Math.min(containerWidth, 400) * aspectRatio) + 'px';
}

// Configurar botões de download
function setupDownloadButtons() {
    // Botão "Gerar Todas"
    const generateAllBtn = document.getElementById('generateAllBtn');
    if (generateAllBtn) {
        generateAllBtn.addEventListener('click', function() {
            generateAllImages();
            showNotification('Todas as imagens foram geradas!', 'success');
        });
    }
    
    // Botão "Baixar Todas" (sem ZIP)
    const downloadAllBtn = document.getElementById('downloadAllBtn');
    if (downloadAllBtn) {
        downloadAllBtn.addEventListener('click', function() {
            // Download individual de cada imagem
            downloadCanvas('titleCanvas', `${postData.titulo}_titulo`);
            
            // Download das imagens do carrossel se existirem
            if (postData.carouselTexts && postData.carouselTexts.length > 0) {
                downloadCarouselImages();
            }
            
            // Download da imagem CTA se existir
            if (postData.callToActionImage || postData.textoFinal) {
                setTimeout(() => {
                    downloadCanvas('ctaCanvas', `${postData.titulo}_cta`);
                }, 200);
            }
            
            showNotification('Download de todas as imagens iniciado!', 'success');
        });
    }
    
    // Botões individuais
    const downloadBtns = document.querySelectorAll('.download-btn');
    downloadBtns.forEach((btn, index) => {
        btn.addEventListener('click', function() {
            const type = this.dataset.type;
            const index = this.dataset.index;
            
            if (type === 'title') {
                downloadCanvas('titleCanvas', `${postData.titulo}_titulo`);
            } else if (type === 'carousel' && index !== undefined) {
                downloadCanvas(`carouselCanvas${index}`, `${postData.titulo}_carousel_${parseInt(index) + 1}`);
            } else if (type === 'carousel') {
                downloadCarouselImages();
            } else if (type === 'cta') {
                downloadCanvas('ctaCanvas', `${postData.titulo}_cta`);
            }
        })
    });
}

// ===== FUNÇÕES DOS MODAIS FLUTUANTES DO CARROSSEL =====

// Configuração dos modais de carrossel
let carouselModalConfig = {
    settings: {},
    cache: {}
};

// Inicializar modais de carrossel
function initializeCarouselModals() {
    console.log('=== INICIALIZANDO MODAIS DE CARROSSEL ===');
    
    // Carregar configurações do cache
    loadCarouselSettingsFromCache();
    
    // Verificar quantos modais existem no DOM
    const allModals = document.querySelectorAll('[id^="carouselModal"]');
    console.log('Modais encontrados no DOM:', allModals.length);
    allModals.forEach((modal, idx) => {
        console.log(`Modal ${idx}: ID = ${modal.id}, classes = ${modal.className}`);
    });
    
    // Configurar event listeners para os botões de configuração
    console.log('=== INICIALIZANDO MODAIS DO CARROSSEL ===');
    const settingsButtons = document.querySelectorAll('.carousel-settings-btn');
    console.log('Botões de configuração encontrados:', settingsButtons.length);
    
    // Debug: verificar se os botões existem no DOM
    settingsButtons.forEach((btn, index) => {
        console.log(`Botão ${index}:`, btn, 'data-index:', btn.dataset.index);
    });
    
    if (settingsButtons.length === 0) {
        console.warn('AVISO: Nenhum botão .carousel-settings-btn encontrado. Isso pode ser normal se não há textos de carrossel.');
        // Tentar encontrar por outros seletores
        const allButtons = document.querySelectorAll('button');
        console.log('Total de botões na página:', allButtons.length);
        const carouselButtons = document.querySelectorAll('[data-index]');
        console.log('Botões com data-index:', carouselButtons.length);
        
        // Verificar se existem textos de carrossel no postData
        if (typeof postData !== 'undefined' && postData.carouselTexts && postData.carouselTexts.length > 0) {
            console.warn('Textos de carrossel existem no postData, mas botões não foram encontrados. Aguardando DOM...');
            // Tentar novamente após um delay
            setTimeout(() => {
                const retryButtons = document.querySelectorAll('.carousel-settings-btn');
                if (retryButtons.length > 0) {
                    console.log('Botões encontrados na segunda tentativa:', retryButtons.length);
                    setupCarouselButtonListeners(retryButtons);
                }
            }, 1000);
        }
        return; // Sair da função se não há botões
    }
    
    // Adicionar listener global para debug de cliques
    document.addEventListener('click', function(e) {
        if (e.target.closest('.carousel-settings-btn')) {
            console.log('=== CLIQUE GLOBAL DETECTADO EM BOTÃO CAROUSEL ===');
            console.log('Target:', e.target);
            console.log('Closest button:', e.target.closest('.carousel-settings-btn'));
            console.log('Data-index do botão:', e.target.closest('.carousel-settings-btn').dataset.index);
        }
        // Debug para qualquer clique em botões
        if (e.target.tagName === 'BUTTON' || e.target.closest('button')) {
            const button = e.target.tagName === 'BUTTON' ? e.target : e.target.closest('button');
            console.log('Clique em botão detectado:', button.className, 'data-index:', button.dataset.index);
        }
    });
    
    // Configurar listeners para os botões encontrados
    setupCarouselButtonListeners(settingsButtons);
    
    console.log('=== MODAIS DE CARROSSEL INICIALIZADOS ===');

    // Configurar event listeners para fechar modais
    document.querySelectorAll('.close-modal').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const index = this.dataset.index;
            closeCarouselModal(index);
        });
    });
    
    // Fechar modais ao clicar fora
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.carousel-modal') && !e.target.closest('.carousel-settings-btn')) {
            closeAllCarouselModals();
        }
    });
    
    // Inicializar controles de cada modal
    initializeModalControls();
}

// Função para configurar listeners dos botões do carrossel
function setupCarouselButtonListeners(settingsButtons) {
    settingsButtons.forEach((btn, btnIndex) => {
        console.log(`Configurando botão ${btnIndex}, index: ${btn.dataset.index}, elemento:`, btn);
        
        // Remover listeners anteriores se existirem
        btn.removeEventListener('click', handleCarouselButtonClick);
        
        // Adicionar novo listener
        btn.addEventListener('click', handleCarouselButtonClick);
        
        console.log(`Event listener adicionado ao botão ${btnIndex}`);
    });
}

// Função separada para handle do clique
function handleCarouselButtonClick(e) {
    console.log('=== CLIQUE NO BOTÃO DETECTADO ===');
    console.log('Event object:', e);
    console.log('Target element:', e.target);
    console.log('Current element (this):', this);
    
    e.preventDefault();
    e.stopPropagation();
    
    const index = this.dataset.index;
    console.log(`Clique no botão de configuração, index: ${index}`);
    console.log('Dataset completo:', this.dataset);
    console.log('Classes do botão:', this.className);
    
    if (!index) {
        console.error('ERRO: Index não encontrado no dataset do botão!');
        return;
    }
    
    console.log(`Tentando abrir modal para index: ${index}`);
    toggleCarouselModal(index);
}

// Inicializar editor global do carrossel
function initializeGlobalCarouselEditor() {
    const globalBtn = document.getElementById('globalCarouselSettings');
    const globalModal = document.getElementById('globalCarouselModal');
    const closeGlobalBtn = document.getElementById('closeGlobalModal');
    const applyToAllBtn = document.getElementById('applyToAll');
    const resetGlobalBtn = document.getElementById('resetGlobalSettings');
    
    // Abrir modal global
    if (globalBtn) {
        globalBtn.addEventListener('click', function() {
            globalModal.classList.remove('hidden');
        });
    }
    
    // Fechar modal global
    if (closeGlobalBtn) {
        closeGlobalBtn.addEventListener('click', function() {
            globalModal.classList.add('hidden');
        });
    }
    
    // Fechar modal ao clicar no fundo
    if (globalModal) {
        globalModal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    }
    
    // Configurar controles globais
    setupGlobalControls();
    
    // Aplicar configurações a todos os slides
    if (applyToAllBtn) {
        applyToAllBtn.addEventListener('click', function() {
            applyGlobalSettingsToAll();
            globalModal.classList.add('hidden');
        });
    }
    
    // Resetar configurações globais
    if (resetGlobalBtn) {
        resetGlobalBtn.addEventListener('click', function() {
            resetAllCarouselSettings();
        });
    }
}

// Configurar controles globais
function setupGlobalControls() {
    // Font Size Global
    const globalFontSize = document.getElementById('globalFontSize');
    const globalFontSizeValue = document.getElementById('globalFontSizeValue');
    if (globalFontSize && globalFontSizeValue) {
        globalFontSize.addEventListener('input', function() {
            globalFontSizeValue.textContent = this.value + 'px';
        });
    }
    
    // Padding Global
    const globalPadding = document.getElementById('globalPadding');
    const globalPaddingValue = document.getElementById('globalPaddingValue');
    if (globalPadding && globalPaddingValue) {
        globalPadding.addEventListener('input', function() {
            const paddingValue = parseInt(this.value);
            globalPaddingValue.textContent = paddingValue + 'px';
            
            // Aplicar padding a todos os slides em tempo real
            postData.carouselTexts.forEach((text, index) => {
                if (carouselModalConfig.settings[index]) {
                    carouselModalConfig.settings[index].padding = paddingValue;
                    applySettingsRealTime(index);
                }
            });
            
            // Salvar no cache
            saveCarouselSettingsToCache();
        });
    }
    
    // Uppercase Toggle Global
    const globalUppercase = document.getElementById('globalUppercase');
    if (globalUppercase) {
        globalUppercase.addEventListener('click', function() {
            this.classList.toggle('bg-blue-500');
            this.classList.toggle('text-white');
            this.classList.toggle('bg-gray-200');
        });
    }
    
    // Numbering Toggle Global
    const globalNumbering = document.getElementById('globalNumbering');
    if (globalNumbering) {
        globalNumbering.addEventListener('click', function() {
            this.classList.toggle('bg-blue-500');
            this.classList.toggle('text-white');
            this.classList.toggle('bg-gray-200');
        });
    }
}

// Aplicar configurações globais a todos os slides
function applyGlobalSettingsToAll() {
    const globalSettings = {
        fontSize: parseInt(document.getElementById('globalFontSize').value),
        fontWeight: document.getElementById('globalFontWeight').value,
        textWrap: document.getElementById('globalTextWrap').value,
        textAlignment: document.getElementById('globalTextAlignment').value,
        uppercase: document.getElementById('globalUppercase').classList.contains('bg-blue-500'),
        numbering: document.getElementById('globalNumbering').classList.contains('bg-blue-500'),
        padding: parseInt(document.getElementById('globalPadding').value)
    };
    
    // Aplicar a todos os slides
    postData.carouselTexts.forEach((text, index) => {
        // Garantir que as configurações existem antes de aplicar
        if (!carouselModalConfig.settings[index]) {
            carouselModalConfig.settings[index] = {
                fontSize: 54,
                fontWeight: '600',
                textWrap: 'auto',
                textAlignment: 'center',
                uppercase: false,
                customText: text || ''
            };
        }
        
        // Preservar customText ao aplicar configurações globais
        const currentCustomText = carouselModalConfig.settings[index].customText || text || '';
        
        carouselModalConfig.settings[index] = {
            ...carouselModalConfig.settings[index],
            ...globalSettings,
            customText: currentCustomText // Preservar o texto personalizado
        };
        
        // Atualizar controles individuais
        updateIndividualModalControls(index, globalSettings);
        
        // Aplicar em tempo real
        applySettingsRealTime(index);
    });
    
    // Salvar no cache
    saveCarouselSettingsToCache();
    
    // Configurações aplicadas silenciosamente
}

// Atualizar controles de um modal individual
function updateIndividualModalControls(index, settings) {
    const modal = document.getElementById(`carouselModal${index}`);
    if (!modal) return;
    
    // Atualizar font size
    const fontSizeSlider = modal.querySelector(`#fontSize_${index}`);
    const fontSizeValue = modal.querySelector(`#fontSizeValue_${index}`);
    if (fontSizeSlider && fontSizeValue) {
        fontSizeSlider.value = settings.fontSize;
        fontSizeValue.textContent = settings.fontSize;
    }
    
    // Atualizar font weight
    const fontWeightSelect = modal.querySelector(`#fontWeight_${index}`);
    if (fontWeightSelect) {
        fontWeightSelect.value = settings.fontWeight;
    }
    
    // Atualizar text wrap
    const textWrapSelect = modal.querySelector(`#textWrap_${index}`);
    if (textWrapSelect) {
        textWrapSelect.value = settings.textWrap;
    }
    
    // Atualizar title position
    const textAlignmentSelect = modal.querySelector(`#textAlignment_${index}`);
    if (textAlignmentSelect) {
        textAlignmentSelect.value = settings.textAlignment;
    }
    
    // Atualizar uppercase
    const uppercaseBtn = modal.querySelector(`#uppercase_${index}`);
    if (uppercaseBtn) {
        if (settings.uppercase) {
            uppercaseBtn.classList.add('bg-blue-500', 'text-white');
            uppercaseBtn.classList.remove('bg-gray-200');
        } else {
            uppercaseBtn.classList.remove('bg-blue-500', 'text-white');
            uppercaseBtn.classList.add('bg-gray-200');
        }
    }
}

// Resetar todas as configurações do carrossel
function resetAllCarouselSettings() {
    // Resetar configurações individuais
    carouselModalConfig.settings = {};
    
    // Resetar controles globais
    document.getElementById('globalFontSize').value = 54;
    document.getElementById('globalFontSizeValue').textContent = '54px';
    document.getElementById('globalFontWeight').value = '600';
    document.getElementById('globalTextWrap').value = 'auto';
    document.getElementById('globalTextAlignment').value = 'center';
    document.getElementById('globalPadding').value = 30;
    document.getElementById('globalPaddingValue').textContent = '30px';
    
    const globalUppercase = document.getElementById('globalUppercase');
    globalUppercase.classList.remove('bg-blue-500', 'text-white');
    globalUppercase.classList.add('bg-gray-200');
    
    const globalNumbering = document.getElementById('globalNumbering');
    globalNumbering.classList.remove('bg-blue-500', 'text-white');
    globalNumbering.classList.add('bg-gray-200');
    
    // Reinicializar configurações padrão
    initializeModalControls();
    
    // Regenerar imagens
    generateCarouselImages();
    
    // Limpar cache
    localStorage.removeItem('carouselSettings');
    
    // Configurações resetadas silenciosamente
}

// Aplicar configurações em tempo real
// Abrir/fechar modal específico
function toggleCarouselModal(index) {
    console.log(`toggleCarouselModal chamada para index: ${index}`);
    const modal = document.getElementById(`carouselModal${index}`);
    console.log(`Modal encontrado:`, modal);
    
    if (modal) {
        const isVisible = !modal.classList.contains('hidden');
        console.log(`Modal visível: ${!isVisible}, classes: ${modal.className}`);
        closeAllCarouselModals();
        if (!isVisible) {
            modal.classList.remove('hidden');
            positionModal(modal, index);
            console.log(`Modal aberto para index: ${index}`);
        }
    } else {
        console.error(`Modal carouselModal${index} não encontrado!`);
    }
}

// Fechar modal específico
function closeCarouselModal(index) {
    const modal = document.getElementById(`carouselModal${index}`);
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Fechar todos os modais
function closeAllCarouselModals() {
    document.querySelectorAll('.carousel-modal').forEach(modal => {
        modal.classList.add('hidden');
    });
}

// Posicionar modal ao lado do slide
function positionModal(modal, index) {
    console.log(`=== POSICIONANDO MODAL ${index} ===`);
    const settingsBtn = document.querySelector(`[data-index="${index}"].carousel-settings-btn`);
    console.log('Botão encontrado:', settingsBtn);
    
    if (settingsBtn) {
        // Obter posição do botão de configurações
        const btnRect = settingsBtn.getBoundingClientRect();
        
        // Posicionar o modal próximo ao botão usando position fixed
         modal.style.position = 'fixed';
         modal.style.left = (btnRect.right + 10) + 'px';
         modal.style.top = btnRect.top + 'px';
         modal.style.zIndex = '999999';
         modal.style.transform = 'none';
         modal.style.margin = '0';
        
        // Verificar se o modal sai da tela e ajustar se necessário
        setTimeout(() => {
            const modalRect = modal.getBoundingClientRect();
            const viewportWidth = window.innerWidth;
            const viewportHeight = window.innerHeight;
            
            // Ajustar horizontalmente se sair da tela
            if (modalRect.right > viewportWidth) {
                modal.style.left = (btnRect.left - modal.offsetWidth - 10) + 'px';
            }
            
            // Ajustar verticalmente se sair da tela
            if (modalRect.bottom > viewportHeight) {
                modal.style.top = (viewportHeight - modal.offsetHeight - 10) + 'px';
            }
            
            // Garantir que não saia do topo
            if (modalRect.top < 0) {
                modal.style.top = '10px';
            }
            
            // Garantir que não saia da esquerda
            if (modalRect.left < 0) {
                modal.style.left = '10px';
            }
        }, 10);
    } else {
        // Fallback: posicionar no centro da viewport
        modal.style.position = 'fixed';
        modal.style.left = '50%';
        modal.style.top = '50%';
        modal.style.transform = 'translate(-50%, -50%)';
        modal.style.zIndex = '999999';
    }
}

// Carregar configurações do cache
function loadCarouselSettingsFromCache() {
    try {
        const cached = localStorage.getItem('carouselSettings');
        if (cached) {
            carouselModalConfig.cache = JSON.parse(cached);
        }
    } catch (e) {
        // Silenciar erro de cache
    }
}

// Salvar configurações no cache
function saveCarouselSettingsToCache() {
    try {
        localStorage.setItem('carouselSettings', JSON.stringify(carouselModalConfig.settings));
    } catch (e) {
        // Silenciar erro de cache
    }
}

// Inicializar controles dos modais
function initializeModalControls() {
    if (!postData.carouselTexts) return;
    
    postData.carouselTexts.forEach((text, index) => {
        // Inicializar configurações padrão
        if (!carouselModalConfig.settings[index]) {
            carouselModalConfig.settings[index] = {
                fontSize: 54,
                fontWeight: '600',
                textWrap: 'auto',
                textAlignment: 'center',
                uppercase: false,
                customText: text || '',
                padding: 30
            };
        }
        
        // Garantir que customText sempre existe
        if (!carouselModalConfig.settings[index].customText) {
            carouselModalConfig.settings[index].customText = text || '';
        }
        
        // Aplicar configurações do cache se existirem
        if (carouselModalConfig.cache[index]) {
            carouselModalConfig.settings[index] = {
                ...carouselModalConfig.settings[index],
                ...carouselModalConfig.cache[index]
            };
            
            // Garantir que customText não seja undefined após aplicar cache
            if (!carouselModalConfig.settings[index].customText) {
                carouselModalConfig.settings[index].customText = text || '';
            }
        }
        
        setupModalEventListeners(index);
    });
}

// Configurar event listeners para um modal específico
function setupModalEventListeners(index) {
    const modal = document.getElementById(`carouselModal${index}`);
    if (!modal) {
        console.error(`Modal carouselModal${index} não encontrado!`);
        return;
    }
    
    console.log(`=== CONFIGURANDO MODAL ${index} ===`);
    console.log('Settings para este modal:', carouselModalConfig.settings[index]);
    
    // Font Size
    const fontSizeSlider = document.getElementById(`fontSize_${index}`);
    const fontSizeValue = document.getElementById(`fontSizeValue_${index}`);
    console.log(`Font Size Slider encontrado para ${index}:`, !!fontSizeSlider);
    console.log(`Font Size Value encontrado para ${index}:`, !!fontSizeValue);
    if (fontSizeSlider && fontSizeValue) {
        fontSizeSlider.value = carouselModalConfig.settings[index].fontSize;
        fontSizeValue.textContent = carouselModalConfig.settings[index].fontSize + 'px';
        console.log(`Font Size definido para ${carouselModalConfig.settings[index].fontSize}px no modal ${index}`);
        
        fontSizeSlider.addEventListener('input', function() {
            const value = parseInt(this.value);
            carouselModalConfig.settings[index].fontSize = value;
            fontSizeValue.textContent = value + 'px';
            applySettingsRealTime(index);
            saveCarouselSettingsToCache();
        });
    }
    
    // Font Weight
    const fontWeightSelect = document.getElementById(`fontWeight_${index}`);
    if (fontWeightSelect) {
        fontWeightSelect.value = carouselModalConfig.settings[index].fontWeight;
        
        fontWeightSelect.addEventListener('change', function() {
            carouselModalConfig.settings[index].fontWeight = this.value;
            applySettingsRealTime(index);
            saveCarouselSettingsToCache();
        });
    }
    
    // Text Wrap
    const textWrapSelect = document.getElementById(`textWrap_${index}`);
    if (textWrapSelect) {
        textWrapSelect.value = carouselModalConfig.settings[index].textWrap;
        
        textWrapSelect.addEventListener('change', function() {
            carouselModalConfig.settings[index].textWrap = this.value;
            applySettingsRealTime(index);
            saveCarouselSettingsToCache();
        });
    }
    
    // Text Alignment
    const textAlignmentSelect = document.getElementById(`textAlignment_${index}`);
    if (textAlignmentSelect) {
        textAlignmentSelect.value = carouselModalConfig.settings[index].textAlignment;
        
        textAlignmentSelect.addEventListener('change', function() {
            carouselModalConfig.settings[index].textAlignment = this.value;
            applySettingsRealTime(index);
            saveCarouselSettingsToCache();
        });
    }
    
    // Uppercase Toggle
    const uppercaseToggle = document.getElementById(`uppercase_${index}`);
    if (uppercaseToggle) {
        if (carouselModalConfig.settings[index].uppercase) {
            uppercaseToggle.classList.add('bg-blue-500', 'text-white');
            uppercaseToggle.classList.remove('bg-gray-200');
        } else {
            uppercaseToggle.classList.remove('bg-blue-500', 'text-white');
            uppercaseToggle.classList.add('bg-gray-200');
        }
        
        uppercaseToggle.addEventListener('click', function() {
            carouselModalConfig.settings[index].uppercase = !carouselModalConfig.settings[index].uppercase;
            if (carouselModalConfig.settings[index].uppercase) {
                this.classList.add('bg-blue-500', 'text-white');
                this.classList.remove('bg-gray-200');
            } else {
                this.classList.remove('bg-blue-500', 'text-white');
                this.classList.add('bg-gray-200');
            }
            applySettingsRealTime(index);
            saveCarouselSettingsToCache();
        });
    }
    
    // Custom Text
    const customTextArea = document.getElementById(`customText_${index}`);
    console.log(`Custom Text Area encontrado para ${index}:`, !!customTextArea);
    if (customTextArea) {
        customTextArea.value = carouselModalConfig.settings[index].customText;
        console.log(`Custom Text definido para "${carouselModalConfig.settings[index].customText}" no modal ${index}`);
        
        customTextArea.addEventListener('input', function() {
            carouselModalConfig.settings[index].customText = this.value;
            // Atualizar também o postData.carouselTexts
            if (postData.carouselTexts && postData.carouselTexts[index] !== undefined) {
                postData.carouselTexts[index] = this.value;
            }
            applySettingsRealTime(index);
            saveCarouselSettingsToCache();
        });
    }
    
    // Reset Button
    const resetBtn = document.getElementById(`resetSlide_${index}`);
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            resetSlideSettings(index);
        });
    }
    
    // Preview Button
    const previewBtn = document.getElementById(`previewSlide_${index}`);
    if (previewBtn) {
        previewBtn.addEventListener('click', function() {
            applySettingsRealTime(index);
        });
    }
    
    // Save Button
    const saveBtn = document.getElementById(`saveSlide_${index}`);
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            saveSlideSettings(index);
        });
    }
}

// Aplicar configurações em tempo real
function applySettingsRealTime(index) {
    const settings = carouselModalConfig.settings[index];
    
    // Verificar se settings existe
    if (!settings) {
        console.error(`Settings não encontradas para index ${index}`);
        return;
    }
    
    // Atualizar texto do carrossel se customText existe
    if (settings.customText !== undefined && postData.carouselTexts && postData.carouselTexts[index] !== undefined) {
        postData.carouselTexts[index] = settings.customText;
    }
    
    console.log(`Aplicando configurações para slide ${index}:`, settings);
    
    // Regenerar imagem do slide específico
    generateSingleCarouselImage(index, settings);
    
    // Salvar configurações no cache
    saveCarouselSettingsToCache();
}

// Salvar configurações do slide e fechar modal
function saveSlideSettings(index) {
    console.log(`Salvando configurações do slide ${index}`);
    
    // Aplicar configurações em tempo real
    applySettingsRealTime(index);
    
    // Fechar o modal
    closeCarouselModal(index);
    
    // Mostrar notificação de sucesso
    showNotification('Configurações salvas com sucesso!', 'success');
}

// Resetar configurações de um slide
function resetSlideSettings(index) {
    carouselModalConfig.settings[index] = {
        fontSize: 54,
        fontWeight: '600',
        textWrap: 'auto',
        textAlignment: 'center',
        uppercase: false,
        padding: 30,
        customText: postData.carouselTexts[index] || ''
    };
    
    // Atualizar controles do modal
    setupModalEventListeners(index);
    
    // Aplicar mudanças
    applySettingsRealTime(index);
    
    // Salvar no cache
    saveCarouselSettingsToCache();
}

// Gerar imagem individual do carrossel com configurações específicas
function generateSingleCarouselImage(index, settings) {
    console.log(`Iniciando geração de imagem para slide ${index}`, settings);
    
    const canvas = document.getElementById(`carouselCanvas${index}`);
    if (!canvas) {
        console.error(`Canvas carouselCanvas${index} não encontrado`);
        return;
    }
    
    console.log(`Canvas encontrado para slide ${index}:`, canvas);
    
    const ctx = canvas.getContext('2d');
    const format = formatConfigs[currentFormat];
    
    console.log(`Formato atual: ${currentFormat}`, format);
    
    // Configurar canvas
    canvas.width = format.width;
    canvas.height = format.height;
    
    // Aplicar fundo usando a cor atual selecionada
    ctx.fillStyle = currentBackgroundColor || '#ffffff';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    
    // Adicionar imagem de fundo primeiro
    addBackgroundImage(ctx, format, function() {
        // Continuar com a geração do carrossel após adicionar o fundo
        generateCarouselContent(ctx, format, canvas, index, settings);
    });
}

// Função separada para gerar o conteúdo do carrossel
function generateCarouselContent(ctx, format, canvas, index, settings) {
    // Configurar texto com fonte Libre Franklin
    const fontSize = settings.fontSize || 54;
    const fontWeight = settings.fontWeight || 'normal';
    ctx.font = `${fontWeight} ${fontSize}px 'Libre Franklin', Arial, sans-serif`;
    ctx.fillStyle = '#000000';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    
    // Processar texto
    let text = settings.customText || '';
    console.log(`Texto original: "${text}", uppercase: ${settings.uppercase}`);
    
    if (settings.uppercase) {
        text = text.toUpperCase();
    }
    
    console.log(`Texto processado: "${text}"`);
    
    // Calcular posição Y (sempre centralizada verticalmente)
    let y = canvas.height / 2;
    
    // Usar padding das configurações
    const padding = settings.padding || 30;
    
    // Configurar alinhamento horizontal
    let x;
    switch (settings.textAlignment) {
        case 'left':
            x = padding;
            ctx.textAlign = 'left';
            break;
        case 'right':
            x = canvas.width - padding;
            ctx.textAlign = 'right';
            break;
        case 'justify':
            x = padding;
            ctx.textAlign = 'left'; // Justificado será tratado especialmente
            break;
        default: // center
            x = canvas.width / 2;
            ctx.textAlign = 'center';
            break;
    }
    
    console.log(`Posição calculada: x=${x}, y=${y}, textAlignment: ${settings.textAlignment}`);
    
    // Desenhar texto com quebra se necessário
    if (settings.textWrap === 'auto' && text.length > 0) {
        console.log('Desenhando texto com quebra automática');
        drawWrappedText(ctx, text, x, y, canvas.width - (padding * 2), fontSize * 1.4, settings.textAlignment);
    } else if (text.length > 0) {
        console.log('Desenhando texto simples');
        if (settings.textAlignment === 'justify') {
            // Para texto simples justificado, usar a função de quebra
            drawWrappedText(ctx, text, x, y, canvas.width - (padding * 2), fontSize * 1.4, settings.textAlignment);
        } else {
            ctx.fillText(text, x, y);
        }
    } else {
        console.log('Nenhum texto para desenhar');
    }
    
    console.log(`Imagem gerada para slide ${index} com texto: "${text}"`);
}

// Função auxiliar para desenhar texto com quebra
function drawWrappedText(ctx, text, x, y, maxWidth, lineHeight, textAlignment = 'left') {
    const words = text.split(' ');
    const lines = [];
    let line = '';
    
    // Primeiro, calcular todas as linhas
    for (let n = 0; n < words.length; n++) {
        const testLine = line + words[n] + ' ';
        const metrics = ctx.measureText(testLine);
        const testWidth = metrics.width;
        
        if (testWidth > maxWidth && n > 0) {
            lines.push(line.trim());
            line = words[n] + ' ';
        } else {
            line = testLine;
        }
    }
    if (line.trim()) {
        lines.push(line.trim());
    }
    
    // Calcular altura total do bloco de texto
    const totalHeight = lines.length * lineHeight;
    
    // Calcular posição Y inicial para centralizar verticalmente
    const startY = y - (totalHeight / 2) + (lineHeight / 2);
    
    // Desenhar cada linha com alinhamento apropriado
    lines.forEach((lineText, index) => {
        const currentY = startY + (index * lineHeight);
        
        if (textAlignment === 'justify' && index < lines.length - 1) {
            // Justificar linha (exceto a última)
            drawJustifiedLine(ctx, lineText, x, currentY, maxWidth);
        } else {
            // Alinhamento normal
            ctx.fillText(lineText, x, currentY);
        }
    });
}

// Função para desenhar linha justificada
function drawJustifiedLine(ctx, text, x, y, maxWidth) {
    const words = text.split(' ');
    if (words.length <= 1) {
        ctx.fillText(text, x, y);
        return;
    }
    
    // Calcular largura total das palavras
    const wordsWidth = words.reduce((total, word) => {
        return total + ctx.measureText(word).width;
    }, 0);
    
    // Calcular espaço disponível para distribuir
    const availableSpace = maxWidth - wordsWidth;
    const spaceBetweenWords = availableSpace / (words.length - 1);
    
    // Desenhar palavras com espaçamento justificado
    let currentX = x;
    words.forEach((word, index) => {
        ctx.fillText(word, currentX, y);
        currentX += ctx.measureText(word).width;
        if (index < words.length - 1) {
            currentX += spaceBetweenWords;
        }
    });
}

// Toggle do editor de carrossel
function toggleCarouselEditor() {
    const editorDiv = document.getElementById('carouselEditor');
    const toggleButton = document.getElementById('carouselEditorToggle');
    
    if (!editorDiv || !toggleButton) return;
    
    window.carouselEditorConfig.isOpen = !window.carouselEditorConfig.isOpen;
        
        if (window.carouselEditorConfig.isOpen) {
        editorDiv.classList.remove('hidden');
        toggleButton.textContent = 'Fechar Editor';
        toggleButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        toggleButton.classList.add('bg-red-600', 'hover:bg-red-700');
        generateIndividualSlideEditors();
    } else {
        editorDiv.classList.add('hidden');
        toggleButton.textContent = 'Editor de Texto';
        toggleButton.classList.remove('bg-red-600', 'hover:bg-red-700');
        toggleButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
    }
}

// Inicializar controles globais
function initializeGlobalCarouselControls() {
    const globalFontSize = document.getElementById('globalFontSize');
    const globalFontSizeValue = document.getElementById('globalFontSizeValue');
    const globalFontWeight = document.getElementById('globalFontWeight');
    const globalTextWrap = document.getElementById('globalTextWrap');
    const globalTextAlignment = document.getElementById('globalTextAlignment');
    const globalUppercase = document.getElementById('globalUppercase');
    const globalNumbering = document.getElementById('globalNumbering');
    const globalPadding = document.getElementById('globalPadding');
    const globalPaddingValue = document.getElementById('globalPaddingValue');
    
    // Controle de tamanho da fonte
    if (globalFontSize && globalFontSizeValue) {
        globalFontSize.addEventListener('input', function() {
            window.carouselEditorConfig.globalSettings.fontSize = parseInt(this.value);
            globalFontSizeValue.textContent = this.value;
            updateCarouselPreview();
        });
    }
    
    // Controle de peso da fonte
    if (globalFontWeight) {
        globalFontWeight.addEventListener('change', function() {
            window.carouselEditorConfig.globalSettings.fontWeight = this.value;
            updateCarouselPreview();
        });
    }
    
    // Controle de quebra de texto
    if (globalTextWrap) {
        globalTextWrap.addEventListener('change', function() {
            window.carouselEditorConfig.globalSettings.textWrap = this.value;
            updateCarouselPreview();
        });
    }
    
    // Controle de alinhamento do texto
    if (globalTextAlignment) {
        globalTextAlignment.addEventListener('change', function() {
            window.carouselEditorConfig.globalSettings.textAlignment = this.value;
            updateCarouselPreview();
        });
    }
    
    // Controle de padding
    if (globalPadding && globalPaddingValue) {
        globalPadding.addEventListener('input', function() {
            const paddingValue = parseInt(this.value);
            window.carouselEditorConfig.globalSettings.padding = paddingValue;
            globalPaddingValue.textContent = paddingValue + 'px';
            updateCarouselPreview();
        });
    }
    
    // Toggle maiúscula
    if (globalUppercase) {
        globalUppercase.addEventListener('click', function() {
            window.carouselEditorConfig.globalSettings.uppercase = !window.carouselEditorConfig.globalSettings.uppercase;
            this.classList.toggle('bg-blue-500');
            this.classList.toggle('text-white');
            updateCarouselPreview();
        });
    }
    
    // Toggle numeração
    if (globalNumbering) {
        globalNumbering.addEventListener('click', function() {
            window.carouselEditorConfig.globalSettings.numbering = !window.carouselEditorConfig.globalSettings.numbering;
            this.classList.toggle('bg-blue-500');
            this.classList.toggle('text-white');
            updateCarouselPreview();
        });
    }
}

// Gerar editores individuais para cada slide
function generateIndividualSlideEditors() {
    const editorsDiv = document.getElementById('individualSlideEditors');
    if (!editorsDiv || !postData.carouselTexts) return;
    
    editorsDiv.innerHTML = '';
    
    postData.carouselTexts.forEach((text, index) => {
        // Inicializar configurações individuais se não existirem
        if (!window.carouselEditorConfig.individualSettings[index]) {
            window.carouselEditorConfig.individualSettings[index] = {
                fontSize: window.carouselEditorConfig.globalSettings.fontSize,
                textWrap: window.carouselEditorConfig.globalSettings.textWrap,
                textAlignment: window.carouselEditorConfig.globalSettings.textAlignment,
                uppercase: window.carouselEditorConfig.globalSettings.uppercase,
                numbering: window.carouselEditorConfig.globalSettings.numbering,
                customText: text
            };
        }
        
        const editorHTML = `
            <div class="p-3 bg-white dark:bg-gray-800 rounded border dark:border-gray-700">
                <div class="flex items-center justify-between mb-3">
                    <h5 class="text-sm font-semibold text-gray-800 dark:text-white">Slide ${index + 1}</h5>
                    <button class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300" onclick="resetIndividualSlide(${index})">Resetar</button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3 mb-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Fonte</label>
                        <div class="flex items-center space-x-1">
                            <input type="range" id="fontSize_${index}" min="16" max="60" value="${window.carouselEditorConfig.individualSettings[index].fontSize}" class="flex-1 h-1 bg-gray-200 dark:bg-gray-700 rounded">
                    <span id="fontSizeValue_${index}" class="text-xs w-6 text-gray-900 dark:text-white">${window.carouselEditorConfig.individualSettings[index].fontSize}</span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Quebra</label>
                        <select id="textWrap_${index}" class="w-full text-xs border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-1 py-1">
                            <option value="auto" ${window.carouselEditorConfig.individualSettings[index].textWrap === 'auto' ? 'selected' : ''}>Auto</option>
                            <option value="manual" ${window.carouselEditorConfig.individualSettings[index].textWrap === 'manual' ? 'selected' : ''}>Manual</option>
                            <option value="none" ${window.carouselEditorConfig.individualSettings[index].textWrap === 'none' ? 'selected' : ''}>Sem</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Alinhamento</label>
                        <select id="textAlignment_${index}" class="w-full text-xs border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-1 py-1">
                            <option value="left" ${window.carouselEditorConfig.individualSettings[index].textAlignment === 'left' ? 'selected' : ''}>Esquerda</option>
                            <option value="center" ${window.carouselEditorConfig.individualSettings[index].textAlignment === 'center' ? 'selected' : ''}>Centro</option>
                            <option value="right" ${window.carouselEditorConfig.individualSettings[index].textAlignment === 'right' ? 'selected' : ''}>Direita</option>
                            <option value="justify" ${window.carouselEditorConfig.individualSettings[index].textAlignment === 'justify' ? 'selected' : ''}>Justificado</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Formato</label>
                        <div class="flex space-x-1">
                            <button id="uppercase_${index}" class="px-1 py-1 text-xs rounded transition-colors ${window.carouselEditorConfig.individualSettings[index].uppercase ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-600 dark:text-white'}">ABC</button>
                        <button id="numbering_${index}" class="px-1 py-1 text-xs rounded transition-colors ${window.carouselEditorConfig.individualSettings[index].numbering ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-600 dark:text-white'}">123</button>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Preview</label>
                        <button class="w-full px-2 py-1 text-xs bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white rounded" onclick="previewIndividualSlide(${index})">Ver</button>
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1">Texto do Slide</label>
                    <textarea id="customText_${index}" rows="3" class="w-full text-xs border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-2 py-1" placeholder="Texto personalizado para este slide...">${window.carouselEditorConfig.individualSettings[index].customText}</textarea>
                </div>
            </div>
        `;
        
        editorsDiv.innerHTML += editorHTML;
    });
    
    // Adicionar event listeners após criar os elementos
    postData.carouselTexts.forEach((text, index) => {
        addIndividualSlideListeners(index);
    });
}

// Adicionar event listeners para um slide individual
function addIndividualSlideListeners(index) {
    const fontSize = document.getElementById(`fontSize_${index}`);
    const fontSizeValue = document.getElementById(`fontSizeValue_${index}`);
    const textWrap = document.getElementById(`textWrap_${index}`);
    const textAlignment = document.getElementById(`textAlignment_${index}`);
    const uppercase = document.getElementById(`uppercase_${index}`);
    const numbering = document.getElementById(`numbering_${index}`);
    const customText = document.getElementById(`customText_${index}`);
    
    if (fontSize && fontSizeValue) {
        fontSize.addEventListener('input', function() {
            window.carouselEditorConfig.individualSettings[index].fontSize = parseInt(this.value);
            fontSizeValue.textContent = this.value;
        });
    }
    
    if (textWrap) {
        textWrap.addEventListener('change', function() {
            window.carouselEditorConfig.individualSettings[index].textWrap = this.value;
        });
    }
    
    if (textAlignment) {
        textAlignment.addEventListener('change', function() {
            window.carouselEditorConfig.individualSettings[index].textAlignment = this.value;
        });
    }
    
    if (uppercase) {
        uppercase.addEventListener('click', function() {
            window.carouselEditorConfig.individualSettings[index].uppercase = !window.carouselEditorConfig.individualSettings[index].uppercase;
            this.classList.toggle('bg-blue-500');
            this.classList.toggle('text-white');
            this.classList.toggle('bg-gray-200');
        });
    }
    
    if (numbering) {
        numbering.addEventListener('click', function() {
            window.carouselEditorConfig.individualSettings[index].numbering = !window.carouselEditorConfig.individualSettings[index].numbering;
            this.classList.toggle('bg-blue-500');
            this.classList.toggle('text-white');
            this.classList.toggle('bg-gray-200');
        });
    }
    
    if (customText) {
        customText.addEventListener('input', function() {
            window.carouselEditorConfig.individualSettings[index].customText = this.value;
        });
    }
}

// Resetar configurações de um slide individual
function resetIndividualSlide(index) {
    window.carouselEditorConfig.individualSettings[index] = {
            fontSize: window.carouselEditorConfig.globalSettings.fontSize,
            textWrap: window.carouselEditorConfig.globalSettings.textWrap,
            textAlignment: window.carouselEditorConfig.globalSettings.textAlignment,
            uppercase: window.carouselEditorConfig.globalSettings.uppercase,
            numbering: window.carouselEditorConfig.globalSettings.numbering,
        customText: postData.carouselTexts[index]
    };
    
    generateIndividualSlideEditors();
}

// Preview de um slide individual
function previewIndividualSlide(index) {
    // Aplicar configurações temporariamente e gerar preview
    const originalText = postData.carouselTexts[index];
    const settings = window.carouselEditorConfig.individualSettings[index];
    
    // Temporariamente substituir o texto
    postData.carouselTexts[index] = settings.customText;
    
    // Gerar imagem com configurações específicas
    generateSingleCarouselImage(index, settings);
    
    // Restaurar texto original
    postData.carouselTexts[index] = originalText;
}

// Aplicar todas as alterações do editor
function applyCarouselChanges() {
    // Aplicar configurações individuais aos textos
    Object.keys(window.carouselEditorConfig.individualSettings).forEach(index => {
        const settings = window.carouselEditorConfig.individualSettings[index];
        if (settings.customText !== undefined) {
            postData.carouselTexts[index] = settings.customText;
        }
    });
    
    // Regenerar todas as imagens do carrossel
    generateCarouselImages();
    
    alert('Alterações aplicadas com sucesso!');
}

// Resetar editor de carrossel
function resetCarouselEditor() {
    window.carouselEditorConfig.individualSettings = {};
    window.carouselEditorConfig.globalSettings = {
        fontSize: 54,
        textWrap: 'auto',
        textAlignment: 'center',
        uppercase: false,
        numbering: false
    };
    
    generateIndividualSlideEditors();
    
    // Resetar controles globais
    const globalFontSize = document.getElementById('globalFontSize');
    const globalFontSizeValue = document.getElementById('globalFontSizeValue');
    const globalTextWrap = document.getElementById('globalTextWrap');
    const globalTextAlignment = document.getElementById('globalTextAlignment');
    const globalUppercase = document.getElementById('globalUppercase');
    const globalNumbering = document.getElementById('globalNumbering');
    
    if (globalFontSize) globalFontSize.value = 54;
    if (globalFontSizeValue) globalFontSizeValue.textContent = '54';
    if (globalTextWrap) globalTextWrap.value = 'auto';
    if (globalTextAlignment) globalTextAlignment.value = 'center';
    if (globalUppercase) {
        globalUppercase.classList.remove('bg-blue-500', 'text-white');
        globalUppercase.classList.add('bg-gray-200');
    }
    if (globalNumbering) {
        globalNumbering.classList.remove('bg-blue-500', 'text-white');
        globalNumbering.classList.add('bg-gray-200');
    }
}

// Atualizar preview do carrossel
function updateCarouselPreview() {
    
    // Regenerar todas as imagens do carrossel
    if (postData.carouselTexts && postData.carouselTexts.length > 0) {
        postData.carouselTexts.forEach((text, index) => {
            generateSingleCarouselImage(index);
        });
    }
}

// Download de canvas individual
function downloadCanvas(canvasId, filename) {
    const canvas = document.getElementById(canvasId);
    const link = document.createElement('a');
    link.download = `${filename}_${currentFormat}.png`;
    link.href = canvas.toDataURL();
    link.click();
}

// Download de todas as imagens do carrossel
function downloadCarouselImages() {
    postData.carouselTexts.forEach((text, index) => {
        setTimeout(() => {
            downloadCanvas(`carouselCanvas${index}`, `${postData.titulo}_carousel_${index + 1}`);
        }, index * 100);
    });
}

// Download de todas as imagens em um único ZIP
async function downloadAllImages() {
    // Verificar se JSZip está disponível
    if (typeof JSZip === 'undefined') {
        // Fallback para downloads individuais se JSZip não estiver disponível
        let delay = 0;
        
        // Título
        setTimeout(() => {
            downloadCanvas('titleCanvas', `${postData.titulo}_titulo`);
        }, delay);
        delay += 100;
        
        // Carrossel
        postData.carouselTexts.forEach((text, index) => {
            setTimeout(() => {
                downloadCanvas(`carouselCanvas${index}`, `${postData.titulo}_carousel_${index + 1}`);
            }, delay);
            delay += 100;
        });
        
        // CTA
        if (postData.textoFinal) {
            setTimeout(() => {
                downloadCanvas('ctaCanvas', `${postData.titulo}_cta`);
            }, delay);
        }
        return;
    }

    // Mostrar loading
    const downloadBtn = document.getElementById('downloadZipBtn');
    const originalText = downloadBtn.innerHTML;
    downloadBtn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg><span>Preparando ZIP...</span>';
    downloadBtn.disabled = true;

    try {
        // Criar instância do JSZip
        const zip = new JSZip();
        const format = document.querySelector('input[name="format"]:checked').value;
        const formatNames = {
            'stories': 'Stories_9x16',
            'square': 'Quadrado_1x1', 
            'rectangular': 'Retangular_4x5'
        };
        
        // Adicionar imagem do título
        const titleCanvas = document.getElementById('titleCanvas');
        if (titleCanvas) {
            const titleBlob = await new Promise(resolve => {
                titleCanvas.toBlob(resolve, 'image/png');
            });
            zip.file(`${formatNames[format]}_01_Titulo.png`, titleBlob);
        }
        
        // Adicionar imagens do carrossel
        for (let i = 0; i < postData.carouselTexts.length; i++) {
            const carouselCanvas = document.getElementById(`carouselCanvas${i}`);
            if (carouselCanvas) {
                const carouselBlob = await new Promise(resolve => {
                    carouselCanvas.toBlob(resolve, 'image/png');
                });
                const fileName = `${formatNames[format]}_${String(i + 2).padStart(2, '0')}_Carousel_${i + 1}.png`;
                zip.file(fileName, carouselBlob);
            }
        }
        
        // Adicionar imagem CTA se existir
        if (postData.textoFinal) {
            const ctaCanvas = document.getElementById('ctaCanvas');
            if (ctaCanvas) {
                const ctaBlob = await new Promise(resolve => {
                    ctaCanvas.toBlob(resolve, 'image/png');
                });
                const ctaNumber = String(postData.carouselTexts.length + 2).padStart(2, '0');
                zip.file(`${formatNames[format]}_${ctaNumber}_CTA.png`, ctaBlob);
            }
        }
        
        // Gerar e baixar o ZIP
        downloadBtn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg><span>Gerando ZIP...</span>';
        
        const zipBlob = await zip.generateAsync({type: 'blob'});
        
        // Criar nome do arquivo com timestamp
        const now = new Date();
        const timestamp = now.toISOString().slice(0, 19).replace(/[T:]/g, '_').replace(/-/g, '');
        const zipFileName = `${postData.titulo}_${formatNames[format]}_${timestamp}.zip`;
        
        // Download do ZIP
        const url = URL.createObjectURL(zipBlob);
        const a = document.createElement('a');
        a.href = url;
        a.download = zipFileName;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        // Mostrar sucesso
        downloadBtn.innerHTML = '<svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg><span>✅ Download Concluído!</span>';
        
        setTimeout(() => {
            downloadBtn.innerHTML = originalText;
            downloadBtn.disabled = false;
        }, 2000);
        
    } catch (error) {
        console.error('Erro ao criar ZIP:', error);
        alert('Erro ao preparar o download. Tente novamente.');
        downloadBtn.innerHTML = originalText;
        downloadBtn.disabled = false;
    }
}

// Atualizar display do formato atual
function updateFormatDisplay() {
    const formatNames = {
        'stories': 'Stories (9:16)',
        'square': 'Quadrado (1:1)',
        'rectangular': 'Retangular (4:5)'
    };
    
    const displayElement = document.getElementById('currentFormatDisplay');
    if (displayElement) {
        displayElement.textContent = formatNames[currentFormat] || 'Stories (9:16)';
    }
}

// Inicializar seletor de cores
function initializeColorSelector() {
    const colorPicker = document.getElementById('colorPicker');
    const colorHex = document.getElementById('colorHex');
    const colorPreview = document.getElementById('colorPreview');
    const saveColorBtn = document.getElementById('saveColorBtn');
    const setDefaultColorBtn = document.getElementById('setDefaultColorBtn');
    
    // Sincronizar color picker com input de texto
    colorPicker.addEventListener('input', function() {
        const color = this.value;
        colorHex.value = color;
        currentBackgroundColor = color;
        generateAllImages();
    });
    
    // Sincronizar input de texto com color picker
    colorHex.addEventListener('input', function() {
        const color = this.value;
        if (isValidHexColor(color)) {
            colorPicker.value = color;
            currentBackgroundColor = color;
            generateAllImages();
        }
    });
    
    // Salvar cor
    saveColorBtn.addEventListener('click', function() {
        saveBackgroundColor();
    });
    
    // Definir cor como padrão
    if (setDefaultColorBtn) {
        setDefaultColorBtn.addEventListener('click', function() {
            setDefaultColorFromPicker();
        });
    }
    
    // Configurar cliques nas cores salvas
    setupSavedColorsEvents();
}

// Atualizar inputs de cor
function updateColorInputs() {
    const colorPicker = document.getElementById('colorPicker');
    const colorHex = document.getElementById('colorHex');
    
    colorPicker.value = currentBackgroundColor;
    colorHex.value = currentBackgroundColor;
}



// Validar cor hexadecimal
function isValidHexColor(hex) {
    return /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(hex);
}

// Destacar cor ativa visualmente
function highlightActiveColor() {
    // Remover destaque de todas as cores
    document.querySelectorAll('.saved-color-item').forEach(item => {
        const colorDiv = item.querySelector('div');
        colorDiv.classList.remove('ring-4', 'ring-blue-300', 'ring-yellow-300', 'ring-gray-300');
    });
    
    // Destacar a cor ativa
    if (currentPostColorId) {
        const activeItem = document.querySelector(`[data-color-id="${currentPostColorId}"]`);
        if (activeItem) {
            const colorDiv = activeItem.querySelector('div');
            if (postColors.some(c => c.id == currentPostColorId)) {
                colorDiv.classList.add('ring-4', 'ring-blue-300');
            } else if (defaultColors.some(c => c.id == currentPostColorId)) {
                colorDiv.classList.add('ring-4', 'ring-yellow-300');
            } else {
                colorDiv.classList.add('ring-4', 'ring-gray-300');
            }
        }
    }
}

// Configurar eventos das cores salvas
function setupSavedColorsEvents() {
    // Clique para selecionar cor
    document.querySelectorAll('.saved-color-item').forEach(item => {
        item.addEventListener('click', function() {
            const color = this.dataset.color;
            const colorId = this.dataset.colorId;
            if (color) {
                currentBackgroundColor = color;
                currentPostColorId = colorId;
                updateColorInputs();
                generateAllImages();
                highlightActiveColor();
            }
        });
        
        // Duplo clique para definir como padrão
        item.addEventListener('dblclick', function() {
            const colorId = this.dataset.colorId;
            setDefaultColor(colorId);
        });
    });
    
    // Botões de excluir
    document.querySelectorAll('.delete-color-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const colorId = this.dataset.colorId;
            deleteBackgroundColor(colorId);
        });
    });
}

// Configurar eventos do modal de exclusão
function setupDeleteColorModalEvents() {
    // Botão confirmar exclusão
    const confirmBtn = document.getElementById('confirmDeleteColor');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', confirmDeleteBackgroundColor);
    }
    
    // Botão cancelar
    const cancelBtn = document.getElementById('cancelDeleteColor');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', closeDeleteColorModal);
    }
    
    // Fechar modal clicando no fundo
    const modal = document.getElementById('deleteColorModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeDeleteColorModal();
            }
        });
    }
    
    // Fechar modal com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('deleteColorModal').classList.contains('hidden')) {
            closeDeleteColorModal();
        }
    });
}

// Salvar cor de fundo
function saveBackgroundColor() {
    const data = {
        color_name: null, // Nome não é mais necessário
        color_hex: currentBackgroundColor,
        post_id: {{ $socialPost->id }}, // ID do post atual
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
    
    fetch('/social-posts/background-colors', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': data._token
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Cor salva para este post!', 'success');
            // Recarregar a página para atualizar as cores específicas do post
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showNotification(data.message || 'Erro ao salvar cor', 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showNotification('Erro ao salvar cor', 'error');
    });
}

// Definir cor como padrão
function setDefaultColor(colorId) {
    const data = {
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
    
    fetch(`/social-posts/background-colors/${colorId}/default`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': data._token
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Cor definida como padrão!', 'success');
            location.reload();
        } else {
            showNotification(data.message || 'Erro ao definir cor padrão', 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showNotification('Erro ao definir cor padrão', 'error');
    });
}

// Definir cor atual do picker como padrão
function setDefaultColorFromPicker() {
    const data = {
        color_hex: currentBackgroundColor,
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
    
    fetch('/social-posts/background-colors/set-default', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': data._token
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Cor definida como padrão global!', 'success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showNotification(data.message || 'Erro ao definir cor padrão', 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showNotification('Erro ao definir cor padrão', 'error');
    });
}

// Variável para armazenar o ID da cor a ser excluída
let colorToDelete = null;

// Excluir cor de fundo
function deleteBackgroundColor(colorId) {
    colorToDelete = colorId;
    openDeleteColorModal();
}

// Abrir modal de confirmação
function openDeleteColorModal() {
    const modal = document.getElementById('deleteColorModal');
    if (modal) {
        modal.classList.remove('hidden');
    }
}

// Fechar modal de confirmação
function closeDeleteColorModal() {
    const modal = document.getElementById('deleteColorModal');
    if (modal) {
        modal.classList.add('hidden');
    }
    colorToDelete = null;
}

// Confirmar exclusão da cor
function confirmDeleteBackgroundColor() {
    if (!colorToDelete) {
        showNotification('Erro: ID da cor não encontrado', 'error');
        return;
    }
    
    const data = {
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
    
    fetch(`/social-posts/background-colors/${colorToDelete}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': data._token
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Cor excluída com sucesso!', 'success');
            closeDeleteColorModal();
            location.reload();
        } else {
            showNotification(data.message || 'Erro ao excluir cor', 'error');
            closeDeleteColorModal();
        }
    })
    .catch(error => {
        console.error('Erro ao excluir cor:', error);
        showNotification('Erro ao excluir cor', 'error');
        closeDeleteColorModal();
    });
}

// Função de teste para geração do título
function testTitleGeneration() {
    console.log('=== TESTE DE GERAÇÃO DO TÍTULO ===');
    console.log('Dados do post:', postData);
    console.log('Formato atual:', currentFormat);
    console.log('Cor de fundo atual:', currentBackgroundColor);
    
    const canvas = document.getElementById('titleCanvas');
    if (!canvas) {
        console.error('Canvas titleCanvas não encontrado!');
        alert('Erro: Canvas não encontrado!');
        return;
    }
    
    console.log('Canvas encontrado:', canvas);
    
    try {
        generateTitleImage();
        console.log('Geração do título executada com sucesso');
        showNotification('Teste de título executado! Verifique o console para detalhes.', 'success');
    } catch (error) {
        console.error('Erro na geração do título:', error);
        showNotification('Erro na geração do título: ' + error.message, 'error');
    }
}

// Mostrar notificação
function showNotification(message, type = 'info') {
    // Criar elemento de notificação
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-full`;
    
    // Definir cores baseadas no tipo
    const colors = {
        success: 'bg-green-500 text-white',
        error: 'bg-red-500 text-white',
        info: 'bg-blue-500 text-white',
        warning: 'bg-yellow-500 text-black'
    };
    
    notification.className += ` ${colors[type] || colors.info}`;
    notification.textContent = message;
    
    // Adicionar ao DOM
    document.body.appendChild(notification);
    
    // Animar entrada
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Remover após 3 segundos
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}
</script>

<!-- Modal de Confirmação para Exclusão de Cor -->
<div id="deleteColorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-75 overflow-y-auto h-full w-full hidden z-[10003]">
    <div class="relative top-20 mx-auto p-5 border dark:border-gray-700 w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-2">Confirmar Exclusão</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 dark:text-gray-300">
                    Tem certeza que deseja excluir esta cor de fundo? Esta ação não pode ser desfeita.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="confirmDeleteColor" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 dark:focus:ring-red-500">
                    Excluir
                </button>
                <button id="cancelDeleteColor" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-500">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Slider personalizado */
.slider-thumb::-webkit-slider-thumb {
    appearance: none;
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: #3b82f6;
    cursor: pointer;
    border: 2px solid #ffffff;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    transition: all 0.2s ease;
}

.slider-thumb::-webkit-slider-thumb:hover {
    background: #2563eb;
    transform: scale(1.1);
}

.slider-thumb::-moz-range-thumb {
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: #3b82f6;
    cursor: pointer;
    border: 2px solid #ffffff;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    transition: all 0.2s ease;
}

.slider-thumb:focus {
    outline: none;
}

.slider-thumb:focus::-webkit-slider-thumb {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}

/* Dark mode slider styles */
.dark .slider-thumb::-webkit-slider-thumb {
    background: #60a5fa;
    border: 2px solid #374151;
}

.dark .slider-thumb::-webkit-slider-thumb:hover {
    background: #3b82f6;
}

.dark .slider-thumb::-moz-range-thumb {
    background: #60a5fa;
    border: 2px solid #374151;
}

.dark .slider-thumb:focus::-webkit-slider-thumb {
    box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.3);
}

/* Toggle switch styles */
.toggle-switch[data-state="on"] {
    background-color: #3b82f6;
}

.toggle-switch[data-state="off"] {
    background-color: #d1d5db;
}

.toggle-switch[data-state="on"] .toggle-thumb {
    transform: translateX(1.25rem);
}

.toggle-switch[data-state="off"] .toggle-thumb {
    transform: translateX(0.25rem);
}

/* Dark mode toggle switch styles */
.dark .toggle-switch[data-state="on"] {
    background-color: #60a5fa;
}

.dark .toggle-switch[data-state="off"] {
    background-color: #6b7280;
}
</style>

<script>
// Inicializar controles modernos
document.addEventListener('DOMContentLoaded', function() {
    initializeModernControls();
});

function initializeModernControls() {
    // Controle do slider de linha destacada
    const highlightSlider = document.getElementById('highlightLineSelect');
    const highlightLabel = document.getElementById('highlightLineLabel');
    
    if (highlightSlider && highlightLabel) {
        const labels = ['Nenhuma', 'Primeira linha', 'Segunda linha', 'Terceira linha', 'Quarta linha', 'Quinta linha'];
        
        highlightSlider.addEventListener('input', function() {
            const value = parseInt(this.value);
            highlightLabel.textContent = labels[value] || 'Linha ' + value;
            highlightedLineIndex = value;
            generateTitleImage();
        });
        
        // Inicializar label
        const initialValue = parseInt(highlightSlider.value);
        highlightLabel.textContent = labels[initialValue] || 'Linha ' + initialValue;
    }
    
    // Controle do toggle de espaçamento personalizado
    const customSpacingToggle = document.getElementById('customSpacingToggle');
    if (customSpacingToggle) {
        customSpacingToggle.addEventListener('click', function() {
            const isOn = this.getAttribute('data-state') === 'on';
            const newState = isOn ? 'off' : 'on';
            
            this.setAttribute('data-state', newState);
            
            const thumb = this.querySelector('span');
            if (thumb) {
                if (newState === 'on') {
                    this.style.backgroundColor = '#3b82f6';
                    thumb.style.transform = 'translateX(1.25rem)';
                } else {
                    this.style.backgroundColor = '#d1d5db';
                    thumb.style.transform = 'translateX(0.25rem)';
                }
            }
            
            // Mostrar/ocultar controles personalizados
            const customControls = document.getElementById('customSpacingControls');
            if (customControls) {
                if (newState === 'on') {
                    customControls.classList.remove('hidden');
                } else {
                    customControls.classList.add('hidden');
                }
            }
        });
    }
    
    // Controle do toggle de justificar
    const justifyToggle = document.getElementById('justifyToggle');
    if (justifyToggle) {
        justifyToggle.addEventListener('click', function() {
            const isOn = this.getAttribute('data-state') === 'on';
            const newState = isOn ? 'off' : 'on';
            
            this.setAttribute('data-state', newState);
            
            const thumb = this.querySelector('span');
            if (thumb) {
                if (newState === 'on') {
                    this.style.backgroundColor = '#3b82f6';
                    thumb.style.transform = 'translateX(1.25rem)';
                    isJustifyEnabled = true;
                } else {
                    this.style.backgroundColor = '#d1d5db';
                    thumb.style.transform = 'translateX(0.25rem)';
                    isJustifyEnabled = false;
                }
            }
            
            generateTitleImage();
        });
    }
}
</script>

@endsection