@extends('layouts.app')

@section('title', 'Cartões de Crédito - Giro')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('dashboard'), 'icon' => 'fas fa-home'],
        ['label' => 'Financeiro', 'url' => '#'],
        ['label' => 'Cartões de Crédito']
    ]" />
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div class="flex items-center space-x-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Cartões de Crédito</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Gerencie seus cartões de crédito e acompanhe os limites</p>
            </div>
            <!-- Search Toggle Icon -->
            <button id="search-toggle" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all duration-200" title="Pesquisar cartões de crédito">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Expandable Search Filter -->
    <div id="search-container" class="mb-6 hidden">
        <div class="flex items-center space-x-3 max-w-md">
            <!-- Search Icon -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <!-- Search Input -->
                <input type="text" id="card-search" placeholder="Pesquisar por nome do cartão..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
            </div>
            <!-- Clear Button -->
            <button id="clear-search" class="px-3 py-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200 hidden" title="Limpar pesquisa">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <!-- Close Search Button -->
            <button id="close-search" class="px-3 py-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200" title="Fechar pesquisa">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-emerald-100">Limite Total</p>
                    <p id="total-limit" class="text-2xl font-bold text-white">R$ {{ number_format($creditCards->sum('limite_total'), 2, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-100">Cartões Ativos</p>
                    <p class="text-2xl font-bold text-white">{{ $creditCards->where('ativo', true)->count() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-amber-100">Limite Utilizado</p>
                    <p id="used-limit" class="text-2xl font-bold text-white">R$ {{ number_format($creditCards->sum('limite_utilizado'), 2, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Credit Cards Grid -->
    @if($creditCards->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="credit-cards-grid">
            @foreach($creditCards as $card)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg p-6 transition-all duration-200 overflow-hidden credit-card-item flex flex-col" data-card-id="{{ $card->id }}">
                    <!-- Card Header -->
                    <div class="">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                @php
                                    $cardLogos = [
                                        'Visa' => '💳',
                                        'Mastercard' => '💳',
                                        'Elo' => '💳',
                                        'American Express' => '💳',
                                        'Hipercard' => '💳',
                                        'Nubank' => '💜',
                                        'Inter' => '🧡',
                                        'C6 Bank' => '⚫'
                                    ];
                                    $logo = $cardLogos[$card->bandeira] ?? '💳';
                                @endphp
                                <div class="w-14 h-14 rounded-full flex items-center justify-center text-2xl overflow-hidden bg-white dark:bg-gray-100">
                                    @if($card->logo_url)
                                        <img src="{{ $card->logo_url }}" alt="{{ $card->bandeira }}" class="w-12 h-12 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-full h-full bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center text-lg font-semibold text-gray-700 dark:text-gray-300" style="display: none;">
                                            {{ strtoupper(substr($card->bandeira, 0, 2)) }}
                                        </div>
                                    @else
                                        <div class="w-full h-full bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center text-lg font-semibold text-gray-700 dark:text-gray-300">
                                            {{ strtoupper(substr($card->bandeira, 0, 2)) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $card->nome_cartao }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $card->bandeira }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Badges Row -->
                        <div class="flex flex-wrap gap-2 mb-4">
                            @if($card->ativo)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Ativo
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Inativo
                                </span>
                            @endif
                            
                            @if($card->dia_vencimento)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Vence: {{ $card->dia_vencimento }}
                                </span>
                            @endif
                            
                            @if($card->dia_fechamento)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Fecha: {{ $card->dia_fechamento }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Card Details -->
                    <div class="pb-6">
                        <div class="space-y-4">
                            <!-- Limite com Slider Visual -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Limite do Cartão</p>
                                    <button onclick="updateCardLimit({{ $card->id }})" class="p-1.5 rounded-lg text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-blue-900/20" title="Atualizar Limite">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                    </button>
                                </div>
                                
                                @php
                                    $limiteTotal = $card->limite_total ?? 0;
                                    $limiteUtilizado = $card->limite_utilizado ?? 0;
                                    $limiteDisponivel = $limiteTotal - $limiteUtilizado;
                                    $percentualUtilizado = 0;
                                    
                                    if ($limiteTotal > 0) {
                                        $percentualUtilizado = ($limiteUtilizado / $limiteTotal) * 100;
                                    }
                                @endphp
                                
                                <!-- Slider Visual -->
                                <div class="mb-3">
                                    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                                        <span>R$ 0</span>
                                        <span>R$ {{ number_format($limiteTotal, 2, ',', '.') }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-3 relative overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-300 {{ $percentualUtilizado < 50 ? 'bg-green-500' : ($percentualUtilizado < 80 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                             style="width: {{ min($percentualUtilizado, 100) }}%" 
                                             data-card-id="{{ $card->id }}" 
                                             data-percentage="{{ $percentualUtilizado }}">
                                        </div>
                                        @if($percentualUtilizado > 100)
                                            <div class="absolute top-0 left-0 h-full bg-red-600 animate-pulse" style="width: 100%"></div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Informações do Limite -->
                                <div class="grid grid-cols-2 gap-3 text-center">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Utilizado</p>
                                        <p class="text-sm font-semibold card-used-limit {{ $percentualUtilizado > 80 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}" data-card-id="{{ $card->id }}">
                                            R$ {{ number_format($limiteUtilizado, 2, ',', '.') }}
                                        </p>
                                        <p class="text-xs text-gray-400">{{ number_format($percentualUtilizado, 1) }}%</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Disponível</p>
                                        <p class="text-sm font-semibold card-available-limit {{ $limiteDisponivel >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}" data-card-id="{{ $card->id }}" data-available="{{ $limiteDisponivel }}">
                                            R$ {{ number_format($limiteDisponivel, 2, ',', '.') }}
                                        </p>
                                        <p class="text-xs text-gray-400">{{ number_format(100 - $percentualUtilizado, 1) }}%</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Flex grow para empurrar os botões para o rodapé -->
                    <div class="flex-grow"></div>

                    <!-- Actions Footer -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700 mt-auto">
                        <div class="flex space-x-3">
                            <a href="{{ route('financial.credit-cards.show', $card) }}" class="p-2 rounded-lg text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-blue-900/20" title="Ver Detalhes">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('financial.credit-cards.edit', $card) }}" class="p-2 rounded-lg text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700" title="Editar">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <button onclick="updateCardLimit({{ $card->id }})" class="p-2 rounded-lg text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 transition-all duration-200 hover:bg-green-50 dark:hover:bg-green-900/20" title="Atualizar Limite">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </button>

                        </div>
                        <div class="flex space-x-3">
                            <button onclick="deleteCreditCard({{ $card->id }})" class="p-2 rounded-lg text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-all duration-200 hover:bg-red-50 dark:hover:bg-red-900/20" title="Excluir">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-12">
                <div class="text-center">
                    <div class="mx-auto w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Nenhum cartão de crédito cadastrado</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto">
                        Comece adicionando seu primeiro cartão de crédito para gerenciar seus gastos e acompanhar seus limites.
                    </p>
                    <a href="{{ route('financial.credit-cards.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Adicionar Primeiro Cartão
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Floating Action Button -->
    @if($creditCards->count() > 0)
        <div class="fixed bottom-6 right-6">
            <a href="{{ route('financial.credit-cards.create') }}" class="inline-flex items-center justify-center w-14 h-14 rounded-full shadow-lg hover:shadow-xl hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200" title="Adicionar Novo Cartão">
                <svg class="w-6 h-6 text-green-500 hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </a>
        </div>
    @endif

    <!-- Modal de Confirmação de Exclusão -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-[10000] hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/20">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-4">Confirmar Exclusão</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Tem certeza que deseja excluir este cartão de crédito? Esta ação não pode ser desfeita.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <div class="flex space-x-3 justify-center">
                        <button id="cancelDelete" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                            Cancelar
                        </button>
                        <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                            <span id="deleteButtonText">Excluir</span>
                            <svg id="deleteSpinner" class="animate-spin w-5 h-5 text-white hidden ml-2 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast de Notificação -->
    <div id="toast" class="fixed top-4 right-4 z-50 hidden">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg p-4 max-w-sm">
            <div class="flex items-center">
                <div id="toastIcon" class="flex-shrink-0">
                    <!-- Ícone será inserido dinamicamente -->
                </div>
                <div class="ml-3">
                    <p id="toastMessage" class="text-sm font-medium text-gray-900 dark:text-white"></p>
                </div>
                <button id="closeToast" class="ml-auto -mx-1.5 -my-1.5 bg-white dark:bg-gray-800 text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 inline-flex h-8 w-8">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize search toggle functionality
function initializeSearchToggle() {
    const searchToggle = document.getElementById('search-toggle');
    const searchContainer = document.getElementById('search-container');
    const closeSearch = document.getElementById('close-search');
    const searchInput = document.getElementById('card-search');
    
    if (!searchToggle || !searchContainer || !closeSearch || !searchInput) {
        console.error('Search toggle elements not found');
        return;
    }
    
    // Toggle search visibility
    searchToggle.addEventListener('click', function() {
        searchContainer.classList.remove('hidden');
        searchInput.focus();
    });
    
    // Close search
    closeSearch.addEventListener('click', function() {
        searchContainer.classList.add('hidden');
        searchInput.value = '';
        document.getElementById('clear-search').classList.add('hidden');
        filterCards('');
    });
    
    // Close search on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !searchContainer.classList.contains('hidden')) {
            closeSearch.click();
        }
    });
    
    // Search input event listener
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.trim();
        const clearButton = document.getElementById('clear-search');
        
        if (searchTerm.length > 0) {
            clearButton.classList.remove('hidden');
        } else {
            clearButton.classList.add('hidden');
        }
        
        filterCards(searchTerm);
    });
    
    // Clear search button
    const clearButton = document.getElementById('clear-search');
    if (clearButton) {
        clearButton.addEventListener('click', function() {
            searchInput.value = '';
            this.classList.add('hidden');
            filterCards('');
            searchInput.focus();
        });
    }
}

// Filter credit cards based on search term
function filterCards(searchTerm = null) {
    if (searchTerm === null) {
        const searchInput = document.getElementById('card-search');
        searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
    } else {
        searchTerm = searchTerm.toLowerCase().trim();
    }
    
    const cardElements = document.querySelectorAll('.card-item[data-card-id]');
    const cardsGrid = document.getElementById('cards-grid');
    let visibleCount = 0;
    
    cardElements.forEach(card => {
        const cardName = card.querySelector('h3').textContent.toLowerCase();
        const shouldShow = cardName.includes(searchTerm);
        
        if (shouldShow) {
            card.style.display = 'flex';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Show/hide empty state message
    let noResultsMessage = document.getElementById('noResultsMessage');
    if (visibleCount === 0 && searchTerm !== '') {
        if (!noResultsMessage) {
            noResultsMessage = document.createElement('div');
            noResultsMessage.id = 'noResultsMessage';
            noResultsMessage.className = 'col-span-full text-center py-12';
            noResultsMessage.innerHTML = `
                <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum cartão encontrado</h3>
                <p class="text-gray-600 dark:text-gray-400">Tente pesquisar com um termo diferente.</p>
            `;
            cardsGrid.appendChild(noResultsMessage);
        }
        noResultsMessage.style.display = 'block';
    } else if (noResultsMessage) {
        noResultsMessage.style.display = 'none';
    }
}

function updateCardLimit(cardId) {
    // Recalcular limite baseado nas transações
    fetch(`/api/financial/credit-cards/${cardId}/calculate-limit`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Atualizar o slider e valores na tela
            const cardElement = document.querySelector(`[data-card-id="${cardId}"]`);
            if (cardElement) {
                const usedLimitElement = cardElement.querySelector('.card-used-limit');
                const availableLimitElement = cardElement.querySelector('.card-available-limit');
                const progressBar = cardElement.querySelector(`div[data-card-id="${cardId}"][data-percentage]`);
                
                if (usedLimitElement) {
                    usedLimitElement.textContent = formatCurrency(data.used_limit);
                }
                
                if (availableLimitElement) {
                    availableLimitElement.textContent = formatCurrency(data.available_limit);
                    availableLimitElement.setAttribute('data-available', data.available_limit);
                }
                
                if (progressBar) {
                    const percentage = data.total_limit > 0 ? (data.used_limit / data.total_limit) * 100 : 0;
                    progressBar.style.width = Math.min(percentage, 100) + '%';
                    progressBar.setAttribute('data-percentage', percentage);
                    
                    // Atualizar cor da barra baseada na porcentagem
                    progressBar.className = progressBar.className.replace(/bg-(green|yellow|red)-500/g, '');
                    if (percentage < 50) {
                        progressBar.classList.add('bg-green-500');
                    } else if (percentage < 80) {
                        progressBar.classList.add('bg-yellow-500');
                    } else {
                        progressBar.classList.add('bg-red-500');
                    }
                }
            }
            
            showToast('Limite recalculado com sucesso!', 'success');
            
            // Atualizar estatísticas gerais
            updateStatisticsRealTime();
        } else {
            showToast(data.message || 'Erro ao recalcular limite', 'error');
        }
    })
    .catch(error => {
        console.error('Erro ao recalcular limite:', error);
        showToast('Erro ao recalcular limite', 'error');
    });
}

let cardToDelete = null;

function deleteCreditCard(cardId) {
    cardToDelete = cardId;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    const toastIcon = document.getElementById('toastIcon');
    
    toastMessage.textContent = message;
    
    if (type === 'success') {
        toastIcon.innerHTML = `
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
        `;
    } else {
        toastIcon.innerHTML = `
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
            </div>
        `;
    }
    
    toast.classList.remove('hidden');
    
    setTimeout(() => {
        toast.classList.add('hidden');
    }, 5000);
}

// Event listeners para o modal
document.getElementById('cancelDelete').addEventListener('click', function() {
    document.getElementById('deleteModal').classList.add('hidden');
    cardToDelete = null;
});

document.getElementById('confirmDelete').addEventListener('click', function() {
    if (cardToDelete) {
        const deleteButton = this;
        const deleteButtonText = document.getElementById('deleteButtonText');
        const deleteSpinner = document.getElementById('deleteSpinner');
        
        // Mostrar spinner
        deleteButtonText.textContent = 'Excluindo...';
        deleteSpinner.classList.remove('hidden');
        deleteButton.disabled = true;
        
        fetch(`/financial/credit-cards/${cardToDelete}`, {
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
                // Remover o cartão da interface
                const cardElement = document.querySelector(`[data-card-id="${cardToDelete}"]`);
                if (cardElement) {
                    cardElement.remove();
                }
                
                showToast('Cartão de crédito excluído com sucesso!', 'success');
                
                // Atualizar estatísticas em tempo real
                updateStatisticsRealTime();
            } else {
                showToast(data.message || 'Erro ao excluir cartão de crédito', 'error');
            }
        })
        .catch(error => {
            showToast('Erro ao excluir cartão de crédito', 'error');
        })
        .finally(() => {
            // Esconder modal e resetar botão
            document.getElementById('deleteModal').classList.add('hidden');
            deleteButtonText.textContent = 'Excluir';
            deleteSpinner.classList.add('hidden');
            deleteButton.disabled = false;
            cardToDelete = null;
        });
    }
});

// Event listener para fechar toast
document.getElementById('closeToast').addEventListener('click', function() {
    document.getElementById('toast').classList.add('hidden');
});

// Função para atualizar estatísticas em tempo real
function updateStatisticsRealTime() {
    fetch('/api/financial/credit-cards/summary', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Atualizar limite total
            const totalLimitElement = document.getElementById('total-limit');
            if (totalLimitElement) {
                totalLimitElement.textContent = formatCurrency(data.total_limit);
            }
            
            // Atualizar limite utilizado
            const usedLimitElement = document.getElementById('used-limit');
            if (usedLimitElement) {
                usedLimitElement.textContent = formatCurrency(data.used_limit);
            }
            
            // Atualizar cartões ativos
            const activeCardsElement = document.getElementById('active-cards');
            if (activeCardsElement) {
                activeCardsElement.textContent = data.active_cards;
            }
            
            // Atualizar limite disponível se existir
            const availableLimitElement = document.getElementById('available-limit');
            if (availableLimitElement) {
                availableLimitElement.textContent = formatCurrency(data.available_limit);
            }
        }
    })
    .catch(error => {
        console.error('Erro ao atualizar estatísticas:', error);
    });
}

// Função para formatar valores em moeda brasileira
function formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(value);
}

// Update summary on page load
document.addEventListener('DOMContentLoaded', function() {
    updateStatisticsRealTime();
    initializeSearchToggle();
});

// Add smooth scroll behavior for better UX
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Search functionality
function filterCreditCards() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
    const cardItems = document.querySelectorAll('.credit-card-item[data-card-id]');
    const cardsGrid = document.getElementById('credit-cards-grid');
    let visibleCount = 0;
    
    cardItems.forEach(card => {
        const cardName = card.querySelector('h3').textContent.toLowerCase();
        const shouldShow = cardName.includes(searchTerm);
        
        if (shouldShow) {
            card.style.display = 'flex';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Show/hide empty state message
    let noResultsMessage = document.getElementById('noResultsMessage');
    if (visibleCount === 0 && searchTerm !== '') {
        if (!noResultsMessage) {
            noResultsMessage = document.createElement('div');
            noResultsMessage.id = 'noResultsMessage';
            noResultsMessage.className = 'col-span-full text-center py-12';
            noResultsMessage.innerHTML = `
                <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum cartão encontrado</h3>
                <p class="text-gray-600 dark:text-gray-400">Tente pesquisar com um termo diferente.</p>
            `;
            cardsGrid.appendChild(noResultsMessage);
        }
        noResultsMessage.style.display = 'block';
    } else if (noResultsMessage) {
        noResultsMessage.style.display = 'none';
    }
}

function clearSearch() {
    document.getElementById('searchInput').value = '';
    filterCreditCards();
    document.getElementById('searchInput').focus();
}

// Search event listeners
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clearButton = document.getElementById('clearSearch');
    
    if (searchInput) {
        searchInput.addEventListener('input', filterCreditCards);
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Escape') {
                clearSearch();
            }
        });
    }
    
    if (clearButton) {
        clearButton.addEventListener('click', clearSearch);
    }
});
</script>
@endsection