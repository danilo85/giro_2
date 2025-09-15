@extends('layouts.app')

@section('title', 'Contas Bancárias - Giro')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Tags de Navegação -->
    <div class="mb-6">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('financial.dashboard') }}" 
               class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                </svg>
                Dashboard
            </a>
            
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Bancos
            </span>
            
            <a href="{{ route('financial.credit-cards.index') }}" 
               class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                Cartões
            </a>
            
            <a href="{{ route('financial.categories.index') }}" 
               class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                Categorias
            </a>
            
            <a href="{{ route('financial.transactions.index') }}" 
               class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2-2v16a2 2 0 002 2z"></path>
                </svg>
                Transações
            </a>
        </div>
    </div>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div class="flex items-center space-x-3">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Contas Bancárias</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Gerencie suas contas bancárias e acompanhe os saldos</p>
        </div>
        </div>
    </div>


    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-emerald-100">Saldo Total</p>
                    <p id="total-balance" class="text-2xl font-bold text-white">R$ 0,00</p>
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
                    <p class="text-sm font-medium text-blue-100">Contas Ativas</p>
                    <p class="text-2xl font-bold text-white">{{ $banks->where('ativo', true)->count() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-amber-100">Maior Saldo</p>
                    <p id="highest-balance" class="text-2xl font-bold text-white">R$ 0,00</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Campo de Pesquisa -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4">
            <div class="flex items-center gap-3">
                <div class="flex-1 relative">
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Buscar por nome do banco, tipo de conta ou agência..."
                           class="w-full pl-10 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <button type="button" id="clearSearch" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 {{ request()->filled('search') ? '' : 'hidden' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Banks Grid -->
    @if($banks->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3  gap-6" id="banks-grid">
            @foreach($banks as $bank)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg p-6 transition-all duration-200 overflow-hidden bank-card flex flex-col" data-bank-id="{{ $bank->id }}">
                    <!-- Bank Header -->
                    <div class="">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                @php
                                    $bankLogos = [
                                        'Banco do Brasil' => '🏛️',
                                        'Itaú Unibanco' => '🔶',
                                        'Bradesco' => '🔴',
                                        'Santander' => '🔺',
                                        'Caixa Econômica Federal' => '🏦',
                                        'Nubank' => '💜',
                                        'Inter' => '🧡',
                                        'C6 Bank' => '⚫',
                                        'BTG Pactual' => '⚡',
                                        'Original' => '🟢'
                                    ];
                                    $logo = $bankLogos[$bank->nome] ?? '🏦';
                                @endphp
                                <div class="w-14 h-14 rounded-full flex items-center justify-center text-2xl overflow-hidden bg-white dark:bg-gray-100">
                                    @if($bank->logo_url)
                                        <img src="{{ $bank->logo_url }}" alt="{{ $bank->nome }}" class="w-12 h-12 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="w-full h-full bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center text-lg font-semibold text-gray-700 dark:text-gray-300" style="display: none;">
                                            {{ strtoupper(substr($bank->nome, 0, 2)) }}
                                        </div>
                                    @else
                                        <div class="w-full h-full bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center text-lg font-semibold text-gray-700 dark:text-gray-300">
                                            {{ strtoupper(substr($bank->nome, 0, 2)) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $bank->nome }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $bank->tipo_conta }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Badges Row -->
                        <div class="flex flex-wrap gap-2 mb-4">
                            @if($bank->ativo)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Ativa
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Inativa
                                </span>
                            @endif
                            
                            @if($bank->agencia)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    Ag: {{ $bank->agencia }}
                                </span>
                            @endif
                            
                            @if($bank->conta)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    Cc: {{ $bank->conta }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Bank Details -->
                    <div class="px-6 pb-6">
                        <div class="space-y-4">
                            <!-- Balance with Variation -->
                            <div class="text-center bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Saldo Atual</p>
                                <p class="text-3xl font-bold bank-balance {{ $bank->saldo_atual >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}" data-balance="{{ $bank->saldo_atual }}">
                                    R$ {{ number_format($bank->saldo_atual, 2, ',', '.') }}
                                </p>
                                
                                @php
                                    $saldoInicial = $bank->saldo_inicial ?? 0;
                                    $saldoAtual = $bank->saldo_atual ?? 0;
                                    $variacao = 0;
                                    $percentual = 0;
                                    
                                    if ($saldoInicial != 0) {
                                        $variacao = $saldoAtual - $saldoInicial;
                                        $percentual = ($variacao / abs($saldoInicial)) * 100;
                                    }
                                @endphp
                                
                                @if($saldoInicial != 0)
                                    <div class="flex items-center justify-center mt-2 space-x-2">
                                        @if($variacao > 0)
                                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-green-600 dark:text-green-400">
                                                +{{ number_format($percentual, 1) }}%
                                            </span>
                                        @elseif($variacao < 0)
                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-red-600 dark:text-red-400">
                                                {{ number_format($percentual, 1) }}%
                                            </span>
                                        @else
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                                0.0%
                                            </span>
                                        @endif
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            (R$ {{ number_format($variacao, 2, ',', '.') }})
                                        </span>
                                    </div>
                                @endif
                            </div>


                        </div>
                    </div>

                    <!-- Flex grow para empurrar os botões para o rodapé -->
                    <div class="flex-grow"></div>

                    <!-- Actions Footer -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700 mt-auto">
                        <div class="flex space-x-3">
                            <a href="{{ route('financial.banks.show', $bank) }}" class="p-2 rounded-lg text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-blue-900/20" title="Ver Extrato">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('financial.banks.edit', $bank) }}" class="p-2 rounded-lg text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700" title="Editar">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <button onclick="recalculateBalance({{ $bank->id }})" class="p-2 rounded-lg text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 transition-all duration-200 hover:bg-green-50 dark:hover:bg-green-900/20" title="Recalcular Saldo">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="flex space-x-3">
                            <button onclick="deleteBank({{ $bank->id }})" class="p-2 rounded-lg text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-all duration-200 hover:bg-red-50 dark:hover:bg-red-900/20" title="Excluir">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Nenhuma conta bancária cadastrada</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto">
                    Comece adicionando sua primeira conta bancária para gerenciar suas finanças e acompanhar seus saldos.
                </p>
                <a href="{{ route('financial.banks.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Adicionar Primeira Conta
                </a>
            </div>
        </div>
    @endif

    <!-- Floating Action Button -->
    <div class="fixed bottom-6 right-6">
        <a href="{{ route('financial.banks.create') }}" class="inline-flex items-center justify-center w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-200" title="Adicionar Nova Conta">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </a>
    </div>

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
                        Tem certeza que deseja excluir esta conta bancária? Esta ação não pode ser desfeita.
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
@endsection

@push('scripts')
<script>
// Initialize search functionality for the new search model
function initializeSearch() {
    const searchInput = document.getElementById('search');
    const clearButton = document.getElementById('clearSearch');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const value = this.value;
            filterBanks(value);
            
            if (clearButton) {
                if (value.trim() !== '') {
                    clearButton.classList.remove('hidden');
                } else {
                    clearButton.classList.add('hidden');
                }
            }
        });
        
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Escape') {
                clearSearch();
            }
        });
    }
    
    if (clearButton) {
        clearButton.addEventListener('click', clearSearch);
    }
    
    // Initialize status filter links
    const statusLinks = document.querySelectorAll('.status-filter');
    statusLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const status = this.getAttribute('data-status');
            filterByStatus(status);
        });
    });
}

// Filter by status function
function filterByStatus(status) {
    const params = new URLSearchParams(window.location.search);
    
    if (status && status !== 'todos') {
        params.set('status', status);
    } else {
        params.delete('status');
    }
    
    // Preserve search parameter
    const searchInput = document.getElementById('bank-search');
    if (searchInput && searchInput.value) {
        params.set('search', searchInput.value);
    }
    
    // Redirect to new URL
    const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
    window.location.href = newUrl;
}

// Filter banks based on search term
function filterBanks(searchTerm = null) {
    if (searchTerm === null) {
        const searchInput = document.getElementById('bank-search');
        searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
    } else {
        searchTerm = searchTerm.toLowerCase().trim();
    }
    
    const bankCards = document.querySelectorAll('.bank-card[data-bank-id]');
    const banksGrid = document.getElementById('banks-grid');
    let visibleCount = 0;
    
    bankCards.forEach(card => {
        const bankName = card.querySelector('h3').textContent.toLowerCase();
        const shouldShow = bankName.includes(searchTerm);
        
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
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhuma conta encontrada</h3>
                <p class="text-gray-600 dark:text-gray-400">Tente pesquisar com um termo diferente.</p>
            `;
            banksGrid.appendChild(noResultsMessage);
        }
        noResultsMessage.style.display = 'block';
    } else if (noResultsMessage) {
        noResultsMessage.style.display = 'none';
    }
}

// Calculate and update summary
function updateSummary() {
    const balanceElements = document.querySelectorAll('.bank-balance');
    
    const balances = Array.from(balanceElements).map(el => {
        return parseFloat(el.dataset.balance) || 0;
    });
    
    const totalBalance = balances.reduce((sum, balance) => sum + balance, 0);
    const highestBalance = Math.max(...balances, 0);
    
    const totalElement = document.getElementById('total-balance');
    const highestElement = document.getElementById('highest-balance');
    
    if (totalElement) {
        totalElement.textContent = 'R$ ' + totalBalance.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
    
    if (highestElement) {
        highestElement.textContent = 'R$ ' + highestBalance.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
}

// Update individual bank balance
function updateBalance(bankId) {
    fetch(`/financial/api/banks/${bankId}/balance`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const balanceElement = document.querySelector(`[data-bank-id="${bankId}"] .bank-balance`);
            balanceElement.textContent = 'R$ ' + data.balance.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            balanceElement.dataset.balance = data.balance;
            
            // Update color based on balance
            balanceElement.className = balanceElement.className.replace(/text-(green|red)-\d+/g, '');
            if (data.balance >= 0) {
                balanceElement.classList.add('text-green-600', 'dark:text-green-400');
            } else {
                balanceElement.classList.add('text-red-600', 'dark:text-red-400');
            }
            
            updateSummary();
        }
    })
    .catch(error => {
        console.error('Error updating balance:', error);
    });
}

// Recalculate bank balance based on transactions
function recalculateBalance(bankId) {
    fetch(`/financial/banks/${bankId}/recalculate-saldo`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const balanceElement = document.querySelector(`[data-bank-id="${bankId}"] .bank-balance`);
            balanceElement.textContent = 'R$ ' + data.saldo_atual.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            balanceElement.dataset.balance = data.saldo_atual;
            
            // Update color based on balance
            balanceElement.className = balanceElement.className.replace(/text-(green|red)-\d+/g, '');
            if (data.saldo_atual >= 0) {
                balanceElement.classList.add('text-green-600', 'dark:text-green-400');
            } else {
                balanceElement.classList.add('text-red-600', 'dark:text-red-400');
            }
            
            updateSummary();
            showToast('Saldo recalculado com sucesso!', 'success');
        } else {
            showToast(data.message || 'Erro ao recalcular saldo', 'error');
        }
    })
    .catch(error => {
        console.error('Error recalculating balance:', error);
        showToast('Erro ao recalcular saldo', 'error');
    });
}

// Refresh all balances
function refreshBalances() {
    const bankCards = document.querySelectorAll('.bank-card');
    bankCards.forEach(card => {
        const bankId = card.getAttribute('data-bank-id');
        if (bankId) {
            updateBalance(bankId);
        }
    });
}

// Delete bank
let bankToDelete = null;

function deleteBank(bankId) {
    bankToDelete = bankId;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastIcon = document.getElementById('toastIcon');
    const toastMessage = document.getElementById('toastMessage');
    
    // Define o ícone baseado no tipo
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
    
    toastMessage.textContent = message;
    toast.classList.remove('hidden');
    
    // Auto-hide após 5 segundos
    setTimeout(() => {
        toast.classList.add('hidden');
    }, 5000);
}

function hideModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    bankToDelete = null;
    
    // Reset button state
    const deleteButton = document.getElementById('confirmDelete');
    const deleteButtonText = document.getElementById('deleteButtonText');
    const deleteSpinner = document.getElementById('deleteSpinner');
    
    deleteButton.disabled = false;
    deleteButtonText.textContent = 'Excluir';
    deleteSpinner.classList.add('hidden');
}

// Clear search function
function clearSearch() {
    const searchInput = document.getElementById('search');
    const clearButton = document.getElementById('clearSearch');
    
    if (searchInput) {
        searchInput.value = '';
        if (clearButton) {
            clearButton.classList.add('hidden');
        }
        filterBanks('');
        searchInput.focus();
    }
}

// Filter banks function with AJAX support
function filterBanks(searchTerm = '') {
    if (!searchTerm) {
        searchTerm = document.getElementById('search') ? 
            document.getElementById('search').value.toLowerCase().trim() : '';
    } else {
        searchTerm = searchTerm.toLowerCase().trim();
    }
    
    // Get current status filter from URL
    const urlParams = new URLSearchParams(window.location.search);
    const currentStatus = urlParams.get('status') || '';
    
    // Build URL with search and status parameters
    const params = new URLSearchParams();
    if (searchTerm) {
        params.set('search', searchTerm);
    }
    if (currentStatus) {
        params.set('status', currentStatus);
    }
    
    // Update URL without page reload
    const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
    window.history.replaceState({}, '', newUrl);
    
    // For now, use client-side filtering for immediate feedback
    const bankCards = document.querySelectorAll('.bank-card[data-bank-id]');
    const banksGrid = document.getElementById('banks-grid');
    let visibleCount = 0;
    
    bankCards.forEach(card => {
        const bankName = card.querySelector('h3').textContent.toLowerCase();
        const bankInstitution = card.querySelector('.text-gray-600').textContent.toLowerCase();
        const shouldShow = bankName.includes(searchTerm) || bankInstitution.includes(searchTerm);
        
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
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhuma conta encontrada</h3>
                <p class="text-gray-600 dark:text-gray-400">Tente pesquisar com um termo diferente.</p>
            `;
            banksGrid.appendChild(noResultsMessage);
        }
        noResultsMessage.style.display = 'block';
    } else if (noResultsMessage) {
        noResultsMessage.style.display = 'none';
    }
}



// Initialize summary on page load
document.addEventListener('DOMContentLoaded', function() {
    updateSummary();
    initializeSearch();
    
    // Event listeners for modal
    document.getElementById('cancelDelete').addEventListener('click', hideModal);
    
    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (!bankToDelete) return;
        
        const deleteButton = this;
        const deleteButtonText = document.getElementById('deleteButtonText');
        const deleteSpinner = document.getElementById('deleteSpinner');
        
        // Show loading state
        deleteButton.disabled = true;
        deleteButtonText.textContent = 'Excluindo...';
        deleteSpinner.classList.remove('hidden');
        
        fetch(`/financial/banks/${bankToDelete}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            hideModal();
            
            if (data.success) {
                showToast(data.message, 'success');
                
                // Remove o elemento do DOM com animação
                const bankElement = document.querySelector(`[data-bank-id="${bankToDelete}"]`);
                
                if (bankElement) {
                    // Adiciona animação de saída
                    bankElement.style.transition = 'all 0.3s ease';
                    bankElement.style.transform = 'scale(0.95)';
                    bankElement.style.opacity = '0';
                    
                    // Remove o elemento após a animação
                    setTimeout(() => {
                        bankElement.remove();
                        updateSummary();
                        
                        // Verifica se ainda há bancos na página
                        const remainingBanks = document.querySelectorAll('.bank-card[data-bank-id]');
                        if (remainingBanks.length === 0) {
                            // Se não há mais bancos, recarrega a página para mostrar a mensagem de "nenhum banco"
                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                        }
                    }, 300);
                } else {
                    // Fallback: recarrega a página se não conseguir remover o elemento
                    setTimeout(() => {
                        window.location.reload();
                    }, 100);
                }
            } else {
                showToast(data.message || 'Erro ao excluir conta bancária', 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            hideModal();
            showToast('Erro ao excluir conta bancária', 'error');
        });
    });
    
    // Close toast
    document.getElementById('closeToast').addEventListener('click', function() {
        document.getElementById('toast').classList.add('hidden');
    });
    
    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideModal();
        }
    });
});
</script>
@endpush