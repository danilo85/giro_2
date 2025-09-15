@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto ">
    <!-- Tags de Navegação Rápida -->
    <div class="flex flex-wrap gap-2 mb-8">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-200">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Dashboard
        </a>
        <a href="{{ route('orcamentos.index') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-200">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Orçamentos
        </a>
        <a href="{{ route('clientes.index') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-200">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
            Clientes
        </a>
        <a href="{{ route('autores.index') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-200">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Autores
        </a>
        <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-blue-600 text-white">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
            Pagamentos
        </span>
        <a href="{{ route('modelos-propostas.index') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-200">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Modelos de Propostas
        </a>
    </div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Pagamentos</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Gerenciamento de pagamentos recebidos</p>
        </div>
 
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Recebido -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-md hover:shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-green-100">Total Recebido</p>
                    <p class="text-2xl font-bold text-white">R$ {{ number_format($totalRecebido, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Este Mês -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-md hover:shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-blue-100">Este Mês</p>
                    <p class="text-2xl font-bold text-white">R$ {{ number_format($totalMes, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Total de Pagamentos -->
        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg shadow-md hover:shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-indigo-100">Total de Pagamentos</p>
                    <p class="text-2xl font-bold text-white">{{ $totalPagamentos }}</p>
                </div>
            </div>
        </div>

        <!-- Data Atual -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow-md hover:shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-orange-100">Data</p>
                    <p class="text-2xl font-bold text-white">{{ \Carbon\Carbon::now()->locale('pt_BR')->isoFormat('DD MMM') }}</p>
                </div>
            </div>
        </div>
     </div>

    <!-- Filtros Expandíveis -->
    <div id="filters-container" class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 mb-8" style="display: none;">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Filtros</h3>
                <button type="button" onclick="toggleFilters()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <i class="fas fa-chevron-down" id="filter-icon"></i>
                </button>
            </div>
        </div>
        
        <div id="filter-content" class="px-6 py-4">
            <!-- Busca Rápida -->
            <div class="mb-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="busca" placeholder="Buscar por cliente, orçamento ou valor..." value="{{ request('busca') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
                </div>
            </div>
            
            <form method="GET" action="{{ route('pagamentos.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="orcamento_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Orçamento</label>
                    <select name="orcamento_id" id="orcamento_id" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm dark:bg-gray-700 dark:text-white">
                        <option value="">Todos os orçamentos</option>
                        @foreach($orcamentos as $orcamento)
                            <option value="{{ $orcamento->id }}" {{ request('orcamento_id') == $orcamento->id ? 'selected' : '' }}>
                                #{{ $orcamento->numero }} - {{ $orcamento->cliente->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="forma_pagamento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Forma de Pagamento</label>
                    <select name="forma_pagamento" id="forma_pagamento" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm dark:bg-gray-700 dark:text-white">
                        <option value="">Todas as formas</option>
                        <option value="dinheiro" {{ request('forma_pagamento') == 'dinheiro' ? 'selected' : '' }}>Dinheiro</option>
                        <option value="pix" {{ request('forma_pagamento') == 'pix' ? 'selected' : '' }}>PIX</option>
                        <option value="cartao_credito" {{ request('forma_pagamento') == 'cartao_credito' ? 'selected' : '' }}>Cartão de Crédito</option>
                        <option value="cartao_debito" {{ request('forma_pagamento') == 'cartao_debito' ? 'selected' : '' }}>Cartão de Débito</option>
                        <option value="transferencia" {{ request('forma_pagamento') == 'transferencia' ? 'selected' : '' }}>Transferência</option>
                        <option value="boleto" {{ request('forma_pagamento') == 'boleto' ? 'selected' : '' }}>Boleto</option>
                    </select>
                </div>
                
                <div>
                    <label for="data_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Início</label>
                    <input type="date" name="data_inicio" id="data_inicio" value="{{ request('data_inicio') }}" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm dark:bg-gray-700 dark:text-white">
                </div>
                
                <div>
                    <label for="data_fim" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data Fim</label>
                    <input type="date" name="data_fim" id="data_fim" value="{{ request('data_fim') }}" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm dark:bg-gray-700 dark:text-white">
                </div>
                
                <div class="md:col-span-4 flex flex-wrap gap-2 pt-2">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z" />
                        </svg>
                        Filtrar
                    </button>
                    <a href="{{ route('pagamentos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-times w-4 h-4 mr-2"></i>
                        Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Resultados -->
    @if(request()->hasAny(['orcamento_id', 'forma_pagamento', 'data_inicio', 'data_fim', 'busca']))
        <div class="mb-4">
            <p class="text-sm text-gray-600">
                <span class="font-medium">{{ $pagamentos->total() }}</span> 
                {{ $pagamentos->total() === 1 ? 'pagamento encontrado' : 'pagamentos encontrados' }}
                @if(request()->hasAny(['orcamento_id', 'forma_pagamento', 'data_inicio', 'data_fim', 'busca']))
                    com os filtros aplicados
                @endif
            </p>
        </div>
    @endif

    <!-- Lista de Pagamentos -->
   
        @if($pagamentos->count() > 0)
            <!-- Grid de Cards de Pagamentos Empilhados -->
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">
                @foreach($pagamentosAgrupados as $grupo)
                    <!-- Container do Grupo de Pagamentos -->
                    <div class="payment-stack-container relative" data-orcamento-id="{{ $grupo['orcamento_id'] }}">
                        @foreach($grupo['pagamentos'] as $index => $pagamento)
                    <div class="payment-card bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-300 flex flex-col" 
                          data-card-index="{{ $index }}" 
                          data-is-expanded="false">
                        <!-- Header do Card -->
                        <div class="p-6 pb-4">
                            <div class="flex items-center justify-between mb-4">
                                <!-- Avatar e Nome do Cliente -->
                                <div class="flex items-center space-x-3">
                                    @if($pagamento->orcamento->cliente->avatar)
                                        <img src="{{ Storage::url($pagamento->orcamento->cliente->avatar) }}" 
                                             alt="{{ $pagamento->orcamento->cliente->nome }}" 
                                             class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ substr($pagamento->orcamento->cliente->nome, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white " 
                                            title="{{ $pagamento->orcamento->cliente->nome }}">{{ Str::limit($pagamento->orcamento->cliente->nome, 20) }}</h3>
                                                                               <!-- Título do Orçamento -->
                        @if($pagamento->orcamento->titulo)
                            <p class="text-sm font-medium text-blue-600 dark:text-blue-400 mt-1" 
                               style="line-height: 1.4; max-height: 2.8em; overflow: hidden;" 
                               title="{{ $pagamento->orcamento->titulo }}">
                                {{ Str::limit($pagamento->orcamento->titulo, 20) }}
                            </p>
                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Badges de Orçamento e Forma de Pagamento -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                <!-- Orçamento Badge -->
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    #{{ $pagamento->orcamento->numero }}
                                </span>
                                
                                <!-- Forma de Pagamento Badge -->
                                @php
                                    $formaColors = [
                                        'dinheiro' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        'cartao_credito' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                        'cartao_debito' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                                        'pix' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                        'transferencia' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $formaColors[$pagamento->forma_pagamento] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $pagamento->forma_pagamento_formatted }}
                                </span>
                            </div>
                        </div>

                        <!-- Detalhes do Pagamento -->
                        <div class="px-6 pb-6">
                            <div class="space-y-3">
                                <!-- Valor -->
                                <div class="text-center bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Valor Pago</p>
                                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                                        {{ $pagamento->valor_formatted }}
                                    </p>
                                </div>
                                
                                <!-- Data do Pagamento -->
                                <div class="text-center">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Pago em {{ $pagamento->data_pagamento_formatted }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Flex grow para empurrar os botões para o rodapé -->
                        <div class="flex-grow"></div>

                        <!-- Actions Footer -->
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 mt-auto relative z-20">
                            <div class="flex items-center justify-between">
                                <div class="flex space-x-3">
                                    <a href="{{ route('pagamentos.show', $pagamento) }}" 
                                       class="relative z-30 p-2 rounded-lg text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-blue-900/20" 
                                       title="Visualizar Pagamento"
                                       onclick="event.stopPropagation();">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('pagamentos.edit', $pagamento) }}" 
                                       class="relative z-30 p-2 rounded-lg text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                       title="Editar Pagamento"
                                       onclick="event.stopPropagation();">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                </div>
                                <div class="flex space-x-3">
                                        <button type="button" 
                                            class="relative z-30 p-2 rounded-lg text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-all duration-200 hover:bg-red-50 dark:hover:bg-red-900/20" 
                                            title="Excluir Pagamento"
                                            onclick="event.stopPropagation(); deletePagamento({{ $pagamento->id }});">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                        @endforeach
                        
                        <!-- Indicador de Contagem -->
                        @if($grupo['count'] > 1)
                            <div class="stack-counter absolute -top-2 -right-2 bg-blue-600 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center z-20 shadow-lg">
                                {{ $grupo['count'] }}
                            </div>
                        @endif
                        
                        <!-- Barra Deslizante Vertical -->
                        @if($grupo['count'] > 1)
                            <div class="vertical-slider-container absolute -right-3 top-1/2 transform -translate-y-1/2 z-30">
                                <div class="vertical-slider bg-gray-300 dark:bg-gray-600 rounded-full w-2 h-32 relative cursor-pointer" 
                                     data-total-cards="{{ $grupo['count'] }}" 
                                     data-current-card="0">
                                    <div class="slider-thumb bg-blue-600 w-4 h-4 rounded-full absolute -left-1 top-0 transform -translate-y-1/2 transition-all duration-300 shadow-lg hover:bg-blue-700 cursor-grab active:cursor-grabbing"></div>
                                </div>
                                <div class="slider-tooltip absolute -right-8 top-1/2 transform -translate-y-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 transition-opacity duration-200 pointer-events-none">
                                    1/{{ $grupo['count'] }}
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            

            
            <!-- Pagination -->
            @if($pagamentos->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            @if ($pagamentos->onFirstPage())
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 cursor-default leading-5 rounded-md">
                                    « Anterior
                                </span>
                            @else
                                <a href="{{ $pagamentos->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    « Anterior
                                </a>
                            @endif

                            @if ($pagamentos->hasMorePages())
                                <a href="{{ $pagamentos->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300">
                                    Próximo »
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            @else
                                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 cursor-default leading-5 rounded-md">
                                    Próximo »
                                </span>
                            @endif
                        </div>

                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-5">
                                    Mostrando
                                    <span class="font-medium">{{ $pagamentos->firstItem() ?? 0 }}</span>
                                    até
                                    <span class="font-medium">{{ $pagamentos->lastItem() ?? 0 }}</span>
                                    de
                                    <span class="font-medium">{{ $pagamentos->total() }}</span>
                                    resultados
                                </p>
                            </div>

                            <div>
                                <span class="relative z-0 inline-flex shadow-sm rounded-md">
                                    {{-- Previous Page Link --}}
                                    @if ($pagamentos->onFirstPage())
                                        <span aria-disabled="true" aria-label="« Anterior">
                                            <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-l-md leading-5 dark:bg-gray-800 dark:border-gray-600" aria-hidden="true">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </span>
                                    @else
                                        <a href="{{ $pagamentos->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:active:bg-gray-700 dark:focus:border-blue-800" aria-label="« Anterior">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($pagamentos->getUrlRange(1, $pagamentos->lastPage()) as $page => $url)
                                        @if ($page == $pagamentos->currentPage())
                                            <span aria-current="page">
                                                <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-blue-600 border border-blue-600 cursor-default leading-5 dark:bg-blue-800 dark:border-blue-800">{{ $page }}</span>
                                            </span>
                                        @else
                                            <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:text-gray-300 dark:active:bg-gray-700 dark:focus:border-blue-800" aria-label="Ir para página {{ $page }}">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($pagamentos->hasMorePages())
                                        <a href="{{ $pagamentos->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150 dark:bg-gray-800 dark:border-gray-600 dark:active:bg-gray-700 dark:focus:border-blue-800" aria-label="Próximo »">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    @else
                                        <span aria-disabled="true" aria-label="Próximo »">
                                            <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-r-md leading-5 dark:bg-gray-800 dark:border-gray-600" aria-hidden="true">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    @if(request()->hasAny(['orcamento_id', 'forma_pagamento', 'data_inicio', 'data_fim', 'busca']))
                        Nenhum pagamento encontrado
                    @else
                        Nenhum pagamento cadastrado
                    @endif
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-sm mx-auto">
                    @if(request()->hasAny(['orcamento_id', 'forma_pagamento', 'data_inicio', 'data_fim', 'busca']))
                        Não encontramos pagamentos que correspondam aos filtros aplicados. Tente ajustar os critérios de busca.
                    @else
                        Comece registrando o primeiro pagamento do seu sistema.
                    @endif
                </p>
                @if(request()->hasAny(['orcamento_id', 'forma_pagamento', 'data_inicio', 'data_fim', 'busca']))
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('pagamentos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Limpar Filtros
                        </a>
                        <a href="{{ route('pagamentos.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Novo Pagamento
                        </a>
                    </div>
                @else
                    <a href="{{ route('pagamentos.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Registrar Primeiro Pagamento
                    </a>
                @endif
            </div>
        @endif

    <!-- Floating Action Button -->
    <div class="fixed bottom-6 right-6 z-50">
        <a href="{{ route('pagamentos.create') }}" 
           class="inline-flex items-center justify-center w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
        </a>
    </div>
<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-[10000]">
    <div class="mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mt-2">Confirmar Exclusão</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Tem certeza que deseja excluir este pagamento? Esta ação não pode ser desfeita.
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

    <script>
        function toggleFilters() {
            const content = document.getElementById('filter-content');
            const icon = document.getElementById('filter-icon');
            
            if (content.style.display === 'none') {
                content.style.display = 'block';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                content.style.display = 'none';
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }

        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchToggle = document.getElementById('search-toggle');
            const searchContainer = document.getElementById('search-container');
            const searchInput = document.getElementById('payment-search');
            const clearSearch = document.getElementById('clear-search');
            const closeSearch = document.getElementById('close-search');
            
            // Toggle search container
            if (searchToggle) {
                searchToggle.addEventListener('click', function() {
                    searchContainer.classList.toggle('hidden');
                    if (!searchContainer.classList.contains('hidden')) {
                        searchInput.focus();
                    }
                });
            }
            
            // Close search
            if (closeSearch) {
                closeSearch.addEventListener('click', function() {
                    searchContainer.classList.add('hidden');
                    searchInput.value = '';
                    clearSearch.classList.add('hidden');
                });
            }
            
            // Clear search
            if (clearSearch) {
                clearSearch.addEventListener('click', function() {
                    searchInput.value = '';
                    clearSearch.classList.add('hidden');
                    searchInput.focus();
                });
            }
            
            // Show/hide clear button
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    if (this.value.length > 0) {
                        clearSearch.classList.remove('hidden');
                    } else {
                        clearSearch.classList.add('hidden');
                    }
                });
                
                // Search functionality
                searchInput.addEventListener('keyup', function(e) {
                    if (e.key === 'Enter') {
                        console.log('Searching for:', this.value);
                    }
                });
            }

            // Auto-submit form when filters change
            const form = document.querySelector('form');
            if (form) {
                const inputs = form.querySelectorAll('select, input[type="date"]');
                
                inputs.forEach(input => {
                    input.addEventListener('change', function() {
                        form.submit();
                    });
                });
            }
        });
    </script>
</div>
@endsection

@push('styles')
<style>
    /* Estilos para cartas empilhadas */
    .payment-stack-container {
        position: relative;
        margin-bottom: 1.5rem;
        min-height: 400px; /* Altura mínima baseada no card */
    }
    
    .payment-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform-origin: center;
        width: 100%;
        height: 400px; /* Altura fixa para todos os cards */
        display: flex;
        flex-direction: column;
    }
    
    /* Cards empilhados - apenas o primeiro fica normal */
    .payment-stack-container:not(.expanded) .payment-card:first-child {
        position: relative;
        z-index: 10;
    }
    
    .payment-stack-container:not(.expanded) .payment-card:not(:first-child) {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
    }
    
    .payment-stack-container:not(.expanded) .payment-card:nth-child(2) {
        transform: translateY(4px) translateX(2px) rotate(1deg);
        z-index: 9;
    }
    
    .payment-stack-container:not(.expanded) .payment-card:nth-child(3) {
        transform: translateY(8px) translateX(-2px) rotate(-1deg);
        z-index: 8;
    }
    
    .payment-stack-container:not(.expanded) .payment-card:nth-child(4) {
        transform: translateY(12px) translateX(1px) rotate(0.5deg);
        z-index: 7;
    }
    
    .payment-stack-container:not(.expanded) .payment-card:nth-child(n+5) {
        transform: translateY(16px) translateX(-1px) rotate(-0.5deg);
        z-index: 6;
    }
    
    /* Barra deslizante vertical */
    .vertical-slider-container {
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .payment-stack-container:hover .vertical-slider-container {
        opacity: 1;
    }
    
    .vertical-slider {
        position: relative;
        background: linear-gradient(to bottom, #e5e7eb, #9ca3af);
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .slider-thumb {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        border: 2px solid white;
    }
    
    .slider-thumb:hover {
        transform: translateX(-1px) translateY(-50%) scale(1.1);
    }
    
    .vertical-slider-container:hover .slider-tooltip {
        opacity: 1;
    }
    
    /* Hover effects */
    .payment-stack-container:not(.expanded):hover .payment-card:first-child {
        transform: translateY(-2px);
    }
    
    .payment-stack-container:not(.expanded):hover .payment-card:nth-child(2) {
        transform: translateY(2px) translateX(3px) rotate(1.5deg);
    }
    
    .payment-stack-container:not(.expanded):hover .payment-card:nth-child(3) {
        transform: translateY(6px) translateX(-3px) rotate(-1.5deg);
    }
    
    /* Contador de pilha */
    .stack-counter {
        animation: pulse 2s infinite;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }
    
    /* Animações para transição entre cards */
    .payment-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .payment-card.card-transitioning {
        opacity: 0.7;
        transform: scale(0.98);
    }
    
    /* Garantir que os botões de ação fiquem acima */
    .payment-card .relative.z-20,
    .payment-card .relative.z-30 {
        pointer-events: auto;
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
        .payment-stack-container {
            min-height: 350px;
        }
        
        .payment-card {
            height: 350px; /* Altura menor em mobile */
        }
        
        .vertical-slider-container {
            right: -20px; /* Mais próximo em mobile */
        }
        
        .vertical-slider {
            height: 24px; /* Menor em mobile */
            width: 1.5px;
        }
        
        .slider-thumb {
            width: 12px;
            height: 12px;
        }
        
        .payment-stack-container:not(.expanded) .payment-card:nth-child(2) {
            transform: translateY(3px) translateX(1px) rotate(0.8deg);
        }
        
        .payment-stack-container:not(.expanded) .payment-card:nth-child(3) {
            transform: translateY(6px) translateX(-1px) rotate(-0.8deg);
        }
        
        .payment-stack-container:not(.expanded) .payment-card:nth-child(4) {
            transform: translateY(9px) translateX(1px) rotate(0.4deg);
        }
    }
</style>
@endpush


@push('scripts')
<script>
    function toggleFilters() {
        const filters = document.getElementById('filters');
        filters.classList.toggle('hidden');
    }
    
    // Sistema de barra deslizante vertical
    document.addEventListener('DOMContentLoaded', function() {
        initVerticalSliders();
    });
    
    function initVerticalSliders() {
        const sliders = document.querySelectorAll('.vertical-slider');
        
        sliders.forEach(slider => {
            const thumb = slider.querySelector('.slider-thumb');
            const container = slider.closest('.payment-stack-container');
            const cards = container.querySelectorAll('.payment-card');
            const tooltip = slider.parentElement.querySelector('.slider-tooltip');
            const totalCards = parseInt(slider.dataset.totalCards);
            
            let isDragging = false;
            let currentCardIndex = 0;
            
            // Configurar posição inicial
            updateCardStack(container, cards, currentCardIndex);
            updateTooltip(tooltip, currentCardIndex + 1, totalCards);
            
            // Event listeners para mouse
            thumb.addEventListener('mousedown', startDrag);
            document.addEventListener('mousemove', drag);
            document.addEventListener('mouseup', stopDrag);
            
            // Event listeners para touch (mobile)
            thumb.addEventListener('touchstart', startDrag, { passive: false });
            document.addEventListener('touchmove', drag, { passive: false });
            document.addEventListener('touchend', stopDrag);
            
            function startDrag(e) {
                isDragging = true;
                thumb.classList.add('active:cursor-grabbing');
                e.preventDefault();
            }
            
            function drag(e) {
                if (!isDragging) return;
                
                e.preventDefault();
                const rect = slider.getBoundingClientRect();
                const clientY = e.type.includes('touch') ? e.touches[0].clientY : e.clientY;
                const relativeY = clientY - rect.top;
                const percentage = Math.max(0, Math.min(1, relativeY / rect.height));
                
                // Calcular índice do card baseado na posição
                const newCardIndex = Math.round(percentage * (totalCards - 1));
                
                if (newCardIndex !== currentCardIndex) {
                    currentCardIndex = newCardIndex;
                    updateCardStack(container, cards, currentCardIndex);
                    updateTooltip(tooltip, currentCardIndex + 1, totalCards);
                }
                
                // Atualizar posição do thumb
                const thumbPosition = (currentCardIndex / (totalCards - 1)) * 100;
                thumb.style.top = `${thumbPosition}%`;
            }
            
            function stopDrag() {
                isDragging = false;
                thumb.classList.remove('active:cursor-grabbing');
            }
            
            // Click direto na barra
            slider.addEventListener('click', function(e) {
                if (e.target === thumb) return;
                
                const rect = slider.getBoundingClientRect();
                const relativeY = e.clientY - rect.top;
                const percentage = Math.max(0, Math.min(1, relativeY / rect.height));
                const newCardIndex = Math.round(percentage * (totalCards - 1));
                
                currentCardIndex = newCardIndex;
                updateCardStack(container, cards, currentCardIndex);
                updateTooltip(tooltip, currentCardIndex + 1, totalCards);
                
                const thumbPosition = (currentCardIndex / (totalCards - 1)) * 100;
                thumb.style.top = `${thumbPosition}%`;
            });
        });
    }
    
    function updateCardStack(container, cards, activeIndex) {
        cards.forEach((card, index) => {
            card.classList.add('card-transitioning');
            
            setTimeout(() => {
                if (index === activeIndex) {
                    // Card ativo no topo
                    card.style.zIndex = '15';
                    card.style.transform = 'translateY(0) translateX(0) rotate(0deg)';
                    card.style.opacity = '1';
                } else if (index < activeIndex) {
                    // Cards anteriores (atrás)
                    const offset = (activeIndex - index) * 2;
                    card.style.zIndex = `${10 - offset}`;
                    card.style.transform = `translateY(${offset * 2}px) translateX(${offset}px) rotate(${offset * 0.5}deg)`;
                    card.style.opacity = '0.8';
                } else {
                    // Cards posteriores (atrás)
                    const offset = (index - activeIndex) * 2;
                    card.style.zIndex = `${10 - offset}`;
                    card.style.transform = `translateY(${offset * 2}px) translateX(${-offset}px) rotate(${-offset * 0.5}deg)`;
                    card.style.opacity = '0.8';
                }
                
                card.classList.remove('card-transitioning');
            }, 50);
        });
    }
    
    function updateTooltip(tooltip, current, total) {
        tooltip.textContent = `${current}/${total}`;
    }
    
    // Funções do modal de exclusão
    function deletePagamento(id) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        form.action = `/pagamentos/${id}`;
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
</script>
@endpush