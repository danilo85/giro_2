@extends('layouts.app')

@section('title', 'Orçamentos')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('dashboard'), 'icon' => 'fas fa-home'],
        ['label' => 'Orçamentos', 'url' => '#'],
        ['label' => 'Listagem']
    ]" />
    
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Orçamentos</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Gerencie todos os orçamentos do sistema</p>
            </div>
            <!-- Busca expansível -->
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <button id="searchToggle" type="button" 
                        class="inline-flex items-center px-3 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                <a href="{{ route('modelos-propostas.index') }}"
                   class="inline-flex items-center p-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md transition-colors"
                   title="Modelos">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </a>
 
            </div>
        </div>
        
        <!-- Container de busca expansível -->
        <div id="searchContainer" class="{{ request()->filled('search') ? '' : 'hidden' }} mt-4">
            <form method="GET" action="{{ route('orcamentos.index') }}" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center gap-3">
                    <div class="flex-1 relative">
                        <input type="text" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Buscar por cliente, título ou autor..."
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
                    <button type="button" id="closeSearch" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-100">Total de Orçamentos</p>
                    <p class="text-2xl font-bold text-white">{{ $orcamentos->total() }}</p>
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
                    <p class="text-sm font-medium text-emerald-100">Aprovados</p>
                    <p class="text-2xl font-bold text-white">{{ $orcamentos->where('status', 'aprovado')->count() }}</p>
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
                    <p class="text-sm font-medium text-amber-100">Pendentes</p>
                    <p class="text-2xl font-bold text-white">{{ $orcamentos->whereIn('status', ['rascunho', 'analisando'])->count() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-100">Valor Total</p>
                    <p class="text-2xl font-bold text-white">R$ {{ number_format($orcamentos->sum('valor_total'), 2, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros rápidos por status -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('orcamentos.index') }}" 
                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full transition-colors duration-200 {{ !request('status') ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                    Todos
                </a>
                <a href="{{ route('orcamentos.index', ['status' => 'rascunho'] + request()->except('status')) }}" 
                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full transition-colors duration-200 {{ request('status') == 'rascunho' ? 'bg-gray-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600' }}">
                    <span class="w-2 h-2 bg-gray-400 rounded-full mr-1.5"></span>
                    Rascunho
                </a>
                <a href="{{ route('orcamentos.index', ['status' => 'analisando'] + request()->except('status')) }}" 
                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full transition-colors duration-200 {{ request('status') == 'analisando' ? 'bg-yellow-500 text-white' : 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200 dark:bg-yellow-900 dark:text-yellow-300 dark:hover:bg-yellow-800' }}">
                    <span class="w-2 h-2 bg-yellow-400 rounded-full mr-1.5"></span>
                    Analisando
                </a>
                <a href="{{ route('orcamentos.index', ['status' => 'rejeitado'] + request()->except('status')) }}" 
                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full transition-colors duration-200 {{ request('status') == 'rejeitado' ? 'bg-red-500 text-white' : 'bg-red-100 text-red-700 hover:bg-red-200 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-800' }}">
                    <span class="w-2 h-2 bg-red-400 rounded-full mr-1.5"></span>
                    Rejeitado
                </a>
                <a href="{{ route('orcamentos.index', ['status' => 'aprovado'] + request()->except('status')) }}" 
                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full transition-colors duration-200 {{ request('status') == 'aprovado' ? 'bg-green-500 text-white' : 'bg-green-100 text-green-700 hover:bg-green-200 dark:bg-green-900 dark:text-green-300 dark:hover:bg-green-800' }}">
                    <span class="w-2 h-2 bg-green-400 rounded-full mr-1.5"></span>
                    Aprovado
                </a>
                <a href="{{ route('orcamentos.index', ['status' => 'finalizado'] + request()->except('status')) }}" 
                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full transition-colors duration-200 {{ request('status') == 'finalizado' ? 'bg-blue-500 text-white' : 'bg-blue-100 text-blue-700 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800' }}">
                    <span class="w-2 h-2 bg-blue-400 rounded-full mr-1.5"></span>
                    Finalizado
                </a>
                <a href="{{ route('orcamentos.index', ['status' => 'pago'] + request()->except('status')) }}" 
                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full transition-colors duration-200 {{ request('status') == 'pago' ? 'bg-purple-500 text-white' : 'bg-purple-100 text-purple-700 hover:bg-purple-200 dark:bg-purple-900 dark:text-purple-300 dark:hover:bg-purple-800' }}">
                    <span class="w-2 h-2 bg-purple-400 rounded-full mr-1.5"></span>
                    Pago
                </a>
            </div>
        </div>
    </div>

    <!-- Orçamentos List -->
    <div class="space-y-4">
        @if($orcamentos->count() > 0)
            <!-- Grid de Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($orcamentos as $orcamento)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-200 flex flex-col h-full">
                        <!-- Header do Card -->
                        <div class="p-6 pb-4">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-3 min-w-0 flex-1">
                                    @if($orcamento->cliente->avatar)
                                        <img src="{{ Storage::url($orcamento->cliente->avatar) }}" 
                                             alt="{{ $orcamento->cliente->nome }}" 
                                             class="w-12 h-12 rounded-full object-cover flex-shrink-0">
                                    @else
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-white font-semibold text-lg">
                                                {{ strtoupper(substr($orcamento->cliente->nome, 0, 2)) }}
                                            </span>
                                        </div>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate" title="{{ $orcamento->titulo }}">
                                            {{ Str::limit($orcamento->titulo, 30) }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Status and Number Badges -->
                            <div class="flex justify-center items-center gap-2 mb-4">
                                <!-- Number Badge -->
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    #{{ $orcamento->id }}
                                </span>
                                
                                <!-- Status Badge -->
                                @php
                                    $statusColors = [
                                        'rascunho' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                        'analisando' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                        'aprovado' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        'rejeitado' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                        'finalizado' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                        'pago' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$orcamento->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($orcamento->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Detalhes do Orçamento -->
                        <div class="px-6 pb-6">
                            <div class="space-y-3">
                                <!-- Informações do Cliente -->
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <div class="space-y-2">
                                        <div class="flex items-center text-sm">
                                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-gray-900 dark:text-white truncate">{{ $orcamento->cliente->nome }}</span>
                                        </div>
                                        <div class="flex items-center text-sm">
                                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-gray-900 dark:text-white truncate">{{ $orcamento->cliente->email }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Valor e Data -->
                                <div class="text-center bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Valor Total</p>
                                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                                        R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}
                                    </p>
                                    @if($orcamento->pagamentos->count() > 0)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Pago: R$ {{ number_format($orcamento->pagamentos->sum('valor'), 2, ',', '.') }}
                                        </p>
                                    @endif
                                </div>
                                
                                <!-- Data de Criação -->
                                <div class="text-center">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Criado em {{ $orcamento->created_at->format('d/m/Y') }} às {{ $orcamento->created_at->format('H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Flex grow para empurrar os botões para o rodapé -->
                        <div class="flex-grow"></div>

                        <!-- Autores e Actions Footer -->
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 mt-auto">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex -space-x-2">
                                    @foreach($orcamento->autores->take(3) as $autor)
                                        @if($autor->avatar)
                                            <img src="{{ Storage::url($autor->avatar) }}" 
                                                 alt="{{ $autor->nome }}" 
                                                 title="{{ $autor->nome }}"
                                                 class="h-8 w-8 rounded-full object-cover border-2 border-white dark:border-gray-800">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center border-2 border-white dark:border-gray-800" 
                                                 title="{{ $autor->nome }}">
                                                <span class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ substr($autor->nome, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                    @if($orcamento->autores->count() > 3)
                                        <div class="h-8 w-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center border-2 border-white dark:border-gray-800">
                                            <span class="text-xs font-medium text-gray-600 dark:text-gray-300">+{{ $orcamento->autores->count() - 3 }}</span>
                                        </div>
                                    @endif
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $orcamento->autores->count() }} autor{{ $orcamento->autores->count() !== 1 ? 'es' : '' }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex space-x-3">
                                    <a href="{{ route('orcamentos.show', $orcamento) }}" 
                                       class="p-2 rounded-lg text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-blue-900/20" 
                                       title="Visualizar Orçamento">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('orcamentos.edit', $orcamento) }}" 
                                       class="p-2 rounded-lg text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                       title="Editar Orçamento">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                </div>
                                <div class="flex space-x-3">
                                    <button onclick="deleteOrcamento({{ $orcamento->id }})" 
                                            class="p-2 rounded-lg text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-all duration-200 hover:bg-red-50 dark:hover:bg-red-900/20" 
                                            title="Excluir Orçamento">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            @if($orcamentos->onFirstPage())
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                    Anterior
                                </span>
                            @else
                                <a href="{{ $orcamentos->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                                    Anterior
                                </a>
                            @endif
                            
                            @if($orcamentos->hasMorePages())
                                <a href="{{ $orcamentos->nextPageUrl() }}" class="relative ml-3 inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                                    Próximo
                                </a>
                            @else
                                <span class="relative ml-3 inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-md dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                    Próximo
                                </span>
                            @endif
                        </div>
                        
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    Mostrando
                                    <span class="font-medium">{{ $orcamentos->firstItem() ?? 0 }}</span>
                                    até
                                    <span class="font-medium">{{ $orcamentos->lastItem() ?? 0 }}</span>
                                    de
                                    <span class="font-medium">{{ $orcamentos->total() }}</span>
                                    resultados
                                </p>
                            </div>
                            <div>
                                {{ $orcamentos->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="text-center py-12">
                    <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-full h-full">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum orçamento encontrado</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                        @if(request()->hasAny(['search', 'status', 'cliente_id', 'periodo']))
                            Tente ajustar os filtros ou
                            <a href="{{ route('orcamentos.index') }}" class="text-blue-600 hover:text-blue-500">limpar a busca</a>.
                        @else
                            Comece criando seu primeiro orçamento para seus clientes.
                        @endif
                    </p>
                    <a href="{{ route('orcamentos.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        @if(request()->hasAny(['search', 'status', 'cliente_id', 'periodo']))
                            Novo Orçamento
                        @else
                            Criar Primeiro Orçamento
                        @endif
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Botão Flutuante -->
<div class="fixed bottom-6 right-6 z-50">
    <a href="{{ route('orcamentos.create') }}" 
       class="inline-flex items-center justify-center w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
    </a>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[10000]">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mt-2">Confirmar Exclusão</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Tem certeza que deseja excluir este orçamento? Esta ação não pode ser desfeita.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                        Excluir
                    </button>
                </form>
                <button onclick="closeDeleteModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-24 hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteOrcamento(id) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    form.action = `/orcamentos/${id}`;
    modal.classList.remove('hidden');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const searchToggle = document.getElementById('searchToggle');
    const searchContainer = document.getElementById('searchContainer');
    const searchInput = document.getElementById('search');
    const clearSearch = document.getElementById('clearSearch');
    const closeSearch = document.getElementById('closeSearch');
    
    // Toggle da busca
    if (searchToggle && searchContainer) {
        searchToggle.addEventListener('click', function() {
            searchContainer.classList.toggle('hidden');
            if (!searchContainer.classList.contains('hidden')) {
                setTimeout(() => searchInput?.focus(), 100);
            }
        });
    }
    
    // Fechar busca
    if (closeSearch && searchContainer) {
        closeSearch.addEventListener('click', function() {
            searchContainer.classList.add('hidden');
            if (searchInput) {
                searchInput.value = '';
            }
        });
    }
    
    // Limpar busca
    if (clearSearch && searchInput) {
        clearSearch.addEventListener('click', function() {
            searchInput.value = '';
            const searchForm = searchInput.closest('form');
            if (searchForm) {
                searchForm.submit();
            }
        });
    }
    
    // Busca automática
    if (searchInput) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const searchForm = this.closest('form');
                if (searchForm) {
                    searchForm.submit();
                }
            }, 500);
        });
    }
});
</script>
@endpush
@endsection