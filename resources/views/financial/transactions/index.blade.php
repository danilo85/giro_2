@extends('layouts.app')

@section('title', 'Lançamentos - Giro')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('dashboard'), 'icon' => 'fas fa-home'],
        ['label' => 'Financeiro', 'url' => '#'],
        ['label' => 'Lançamentos']
    ]" />
    
    <!-- Mensagens de Sucesso e Erro -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Lançamentos</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Gerencie suas receitas e despesas</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <button onclick="openFilterModal()" class="inline-flex items-center justify-center w-10 h-10 text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 rounded-md transition-colors relative" title="Filtros">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <span id="filter-count" class="hidden absolute -top-2 -right-2 bg-blue-600 text-white text-xs rounded-full px-2 py-1 min-w-[20px] text-center">0</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-emerald-100">Receitas</p>
                    <p id="total-income" class="text-2xl font-bold text-white">R$ 0,00</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-rose-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-red-100">Despesas</p>
                    <p id="total-expenses" class="text-2xl font-bold text-white">R$ 0,00</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-gray-300 to-gray-400 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-100">Saldo</p>
                    <p id="balance" class="text-2xl font-bold text-white">R$ 0,00</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-amber-100">Pendentes</p>
                    <p id="pending-count" class="text-2xl font-bold text-white">0</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Period Navigation -->
    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 mb-8">
        <div class="flex items-center justify-center space-x-3">
            <button id="prev-month" title="Mês anterior" class="group flex items-center justify-center w-8 h-8 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            
            <h3 id="current-period" class="text-xl font-semibold text-gray-800 dark:text-white px-4">Janeiro 2024</h3>
            
            <button id="current-month-btn" title="Voltar ao mês atual" class="group flex items-center justify-center w-6 h-6 text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </button>
            
            <button id="next-month" title="Próximo mês" class="group flex items-center justify-center w-8 h-8 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Modal de Filtros -->
    <div id="filter-modal" class="fixed inset-0 bg-black bg-opacity-50 z-[9999] hidden flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-95 opacity-0" id="filter-modal-content">
            <!-- Header do Modal -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg mr-3">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Filtros de Transações</h3>
                </div>
                <button onclick="closeFilterModal()" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Conteúdo do Modal -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Filtro de Tipo -->
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                </svg>
                            </div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Transação</label>
                        </div>
                        <select id="filter-type" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Todos os tipos</option>
                            <option value="income">💰 Receitas</option>
                            <option value="expense">💸 Despesas</option>
                        </select>
                    </div>

                    <!-- Filtro de Status -->
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        </div>
                        <select id="filter-status" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Todos os status</option>
                            <option value="paid">✅ Pago</option>
                            <option value="pending">⏳ Pendente</option>
                        </select>
                    </div>

                    <!-- Filtro de Categoria -->
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Categoria</label>
                        </div>
                        <select id="filter-category" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">🏷️ Todas as categorias</option>
                        </select>
                    </div>

                    <!-- Filtro de Conta/Cartão -->
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <div class="p-2 bg-indigo-100 dark:bg-indigo-900 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            </div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Conta/Cartão</label>
                        </div>
                        <select id="filter-account" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">💳 Todas as contas</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Footer do Modal -->
            <div class="flex items-center justify-between p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <button onclick="clearFilters()" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-all duration-200 hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Limpar Filtros
                </button>
                <div class="flex space-x-3">
                    <button onclick="closeFilterModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-all duration-200">
                        Cancelar
                    </button>
                    <button onclick="applyFilters()" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200 hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Aplicar Filtros
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Faturas de Cartão de Crédito -->
    <div id="invoices-section" class="mb-8 hidden">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                Faturas de Cartão de Crédito
            </h2>
        </div>
        <div id="invoices-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8"></div>
    </div>

    <!-- Lista de Transações -->
    <div id="transactions-container">

        <!-- Loading State -->
        <div id="loading" class="text-center py-12 hidden">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="mt-4 text-gray-600 dark:text-gray-400">Carregando transações...</p>
        </div>

        <!-- Transactions Grid -->
        <div id="transactions-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
    </div>

    <!-- Empty State -->
    <div id="empty-state" class="flex flex-col items-center justify-center py-16 px-4 hidden">
        <div class="text-center max-w-md mx-auto">
            <div class="mx-auto w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Nenhuma transação cadastrada</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">Comece adicionando sua primeira transação para gerenciar suas finanças.</p>
            <a href="{{ route('financial.transactions.create') }}" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Adicionar Primeira Transação
            </a>
        </div>
    </div>

    <!-- Paginação -->
    <div id="pagination" class="mt-6 hidden">
        <!-- Paginação será carregada aqui via JavaScript -->
    </div>
</div>

<!-- Botão Flutuante -->
<div class="fixed bottom-6 right-6 z-50">
    <div class="relative">
        <button id="fab-main" onclick="toggleFab()" class="bg-blue-600 hover:bg-blue-700 text-white w-14 h-14 rounded-full shadow-lg transition-all duration-300 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        </button>
        <div id="fab-menu" class="absolute bottom-16 right-0 space-y-2 hidden">
            <a href="{{ route('financial.transactions.create', ['type' => 'income']) }}" class="bg-green-500 hover:bg-green-600 text-white w-12 h-12 rounded-full shadow-lg transition-all duration-300 flex items-center justify-center" title="Nova Receita">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                </svg>
            </a>
            <a href="{{ route('financial.transactions.create', ['type' => 'expense']) }}" class="bg-red-500 hover:bg-red-600 text-white w-12 h-12 rounded-full shadow-lg transition-all duration-300 flex items-center justify-center" title="Nova Despesa">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- Transaction Card Template -->
<template id="transaction-card-template">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-600 flex flex-col min-h-[200px]">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center">
                <div class="transaction-avatar w-12 h-12 rounded-full flex items-center justify-center mr-4 text-white font-semibold">
                    <span class="transaction-avatar-content text-lg"></span>
                </div>
                <div class="flex-1">
                    <h3 class="transaction-description font-semibold text-gray-900 dark:text-white text-lg mb-1"></h3>
                    <span class="transaction-category-badge inline-flex items-center px-2 py-1 text-xs font-medium rounded-full"></span>
                </div>
            </div>
            <span class="transaction-status-badge px-3 py-1 text-xs font-medium rounded-full"></span>
        </div>
        
        <div class="flex items-center justify-between mb-4">
            <span class="transaction-date text-sm text-gray-600 dark:text-gray-400"></span>
            <div class="flex items-center gap-2">
                <span class="transaction-installment-badge hidden px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-700 dark:bg-purple-800 dark:text-purple-200"></span>
                <span class="transaction-recurring-badge hidden px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700 dark:bg-blue-800 dark:text-blue-200">
                    <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Recorrente
                </span>
                <span class="transaction-amount font-bold text-xl"></span>
            </div>
        </div>
        
        <div class="transaction-notes text-sm text-gray-600 dark:text-gray-400 mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hidden"></div>
        
        <!-- Spacer para empurrar os botões para o final -->
        <div class="flex-grow"></div>
        
        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700 mt-auto">
            <div class="flex space-x-3">
                <button class="btn-toggle-status p-2 rounded-lg transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700" title="Alternar Status">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </button>
                <button class="btn-duplicate p-2 rounded-lg text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-blue-900/20" title="Duplicar">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </button>
                <button class="btn-edit p-2 rounded-lg text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700" title="Editar">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </button>
            </div>
            <div class="flex space-x-3">
                <button class="btn-delete p-2 rounded-lg text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-all duration-200 hover:bg-red-50 dark:hover:bg-red-900/20" title="Excluir">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

<!-- Invoice Card Template -->
<template id="invoice-card-template">
    <div class="bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 rounded-xl shadow-sm border-2 border-purple-200 dark:border-purple-700 p-6 hover:shadow-lg transition-all duration-200 hover:border-purple-300 dark:hover:border-purple-600">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center mr-4 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="invoice-card-name font-bold text-gray-900 dark:text-white text-lg mb-1"></h3>
                    <span class="invoice-period text-sm text-gray-600 dark:text-gray-400"></span>
                </div>
            </div>
            <span class="invoice-status-badge px-3 py-1 text-xs font-medium rounded-full"></span>
        </div>
        
        <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-600 dark:text-gray-400">Total da Fatura</span>
                <span class="invoice-total font-bold text-2xl text-purple-600 dark:text-purple-400"></span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-600 dark:text-gray-400">Transações</span>
                <span class="invoice-transaction-count text-gray-900 dark:text-white font-medium"></span>
            </div>
        </div>
        
        <div class="flex items-center justify-between pt-4 border-t border-purple-200 dark:border-purple-700">
            <div class="flex space-x-2">
                <button class="btn-view-transactions px-3 py-2 text-sm bg-purple-100 hover:bg-purple-200 dark:bg-purple-800 dark:hover:bg-purple-700 text-purple-700 dark:text-purple-200 rounded-lg transition-colors" title="Ver Transações">
                    <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Ver
                </button>
            </div>
            <button class="btn-toggle-invoice px-4 py-2 text-sm font-medium rounded-lg transition-colors">
                <!-- Conteúdo será definido dinamicamente -->
            </button>
        </div>
    </div>
</template>

<script>
let currentMonth = new Date().getMonth() + 1;
let currentYear = new Date().getFullYear();
let currentFilters = {};
let fabOpen = false;

// Verificação de autenticação
function checkAuthentication() {
    return fetch('/api/auth/check', {
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (response.status === 401) {
            // Usuário não autenticado, redirecionar para login
            window.location.href = '/login';
            return false;
        }
        if (response.ok) {
            return true;
        }
        throw new Error('Erro na verificação de autenticação');
    })
    .catch(error => {
        console.error('Erro ao verificar autenticação:', error);
        // Em caso de erro, redirecionar para login por segurança
        window.location.href = '/login';
        return false;
    });
}

// Inicialização
document.addEventListener('DOMContentLoaded', function() {
    // Carregar período salvo antes da verificação de autenticação
    if (!loadSavedPeriod()) {
        // Se não há período salvo, usar o mês atual
        const now = new Date();
        currentMonth = now.getMonth() + 1;
        currentYear = now.getFullYear();
    }
    
    // Verificar autenticação antes de carregar dados
    checkAuthentication().then(isAuthenticated => {
        if (isAuthenticated) {
            updatePeriodDisplay();
            loadCategories();
            loadAccounts();
            loadTransactions();
        }
    });
});

// Period navigation event listeners
document.getElementById('prev-month').addEventListener('click', function() {
    currentMonth--;
    if (currentMonth < 1) {
        currentMonth = 12;
        currentYear--;
    }
    updatePeriodDisplay();
    saveCurrentPeriod();
    loadTransactions();
});

document.getElementById('next-month').addEventListener('click', function() {
    currentMonth++;
    if (currentMonth > 12) {
        currentMonth = 1;
        currentYear++;
    }
    updatePeriodDisplay();
    saveCurrentPeriod();
    loadTransactions();
});

// Botão para voltar ao mês atual
document.getElementById('current-month-btn').addEventListener('click', function() {
    const now = new Date();
    currentMonth = now.getMonth() + 1;
    currentYear = now.getFullYear();
    updatePeriodDisplay();
    saveCurrentPeriod();
    loadTransactions();
    
    // Animação de feedback visual
    const btn = this;
    btn.classList.add('animate-pulse');
    setTimeout(() => {
        btn.classList.remove('animate-pulse');
    }, 600);
});

// Funções de persistência do período
function saveCurrentPeriod() {
    const periodData = {
        month: currentMonth,
        year: currentYear
    };
    localStorage.setItem('giro_current_period', JSON.stringify(periodData));
}

function loadSavedPeriod() {
    try {
        const savedPeriod = localStorage.getItem('giro_current_period');
        if (savedPeriod) {
            const periodData = JSON.parse(savedPeriod);
            currentMonth = periodData.month;
            currentYear = periodData.year;
            return true;
        }
    } catch (error) {
        console.warn('Erro ao carregar período salvo:', error);
    }
    return false;
}

// Period navigation functions
function previousMonth() {
    currentMonth--;
    if (currentMonth < 1) {
        currentMonth = 12;
        currentYear--;
    }
    updatePeriodDisplay();
    saveCurrentPeriod();
    loadTransactions();
}

function nextMonth() {
    currentMonth++;
    if (currentMonth > 12) {
        currentMonth = 1;
        currentYear++;
    }
    updatePeriodDisplay();
    saveCurrentPeriod();
    loadTransactions();
}

function updatePeriodDisplay() {
    const months = [
        'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
    ];
    const periodElement = document.getElementById('current-period');
    if (periodElement) {
        periodElement.textContent = `${months[currentMonth - 1]} ${currentYear}`;
        
        // Animação suave na mudança de período
        periodElement.style.transform = 'scale(1.05)';
        setTimeout(() => {
            periodElement.style.transform = 'scale(1)';
        }, 200);
    }
}

// Filtros - Modal
function openFilterModal() {
    const modal = document.getElementById('filter-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Animação de entrada
    setTimeout(() => {
        const content = document.getElementById('filter-modal-content');
        if (content) {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }
    }, 10);
}

function closeFilterModal() {
    const modal = document.getElementById('filter-modal');
    const content = document.getElementById('filter-modal-content');
    
    // Animação de saída
    if (content) {
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
    }
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 200);
}

function updateFilterCount() {
    const filterCount = Object.keys(currentFilters).length;
    const countElement = document.getElementById('filter-count');
    
    if (filterCount > 0) {
        countElement.textContent = filterCount;
        countElement.classList.remove('hidden');
    } else {
        countElement.classList.add('hidden');
    }
}

function clearFilters() {
    document.getElementById('filter-type').value = '';
    document.getElementById('filter-status').value = '';
    document.getElementById('filter-category').value = '';
    document.getElementById('filter-account').value = '';
    currentFilters = {};
    updateFilterCount();
    loadTransactions();
    closeFilterModal();
}

function applyFilters() {
    const type = document.getElementById('filter-type').value;
    const status = document.getElementById('filter-status').value;
    const category = document.getElementById('filter-category').value;
    const account = document.getElementById('filter-account').value;
    
    currentFilters = {};
    if (type) currentFilters.tipo = type;
    if (status) currentFilters.status = status;
    if (category) currentFilters.category_id = category;
    if (account) {
        if (account.startsWith('bank_')) {
            currentFilters.conta_id = account.replace('bank_', '');
        } else if (account.startsWith('card_')) {
            currentFilters.credit_card_id = account.replace('card_', '');
        }
    }
    
    updateFilterCount();
    loadTransactions();
    closeFilterModal();
}

// Função para fechar modal ao clicar fora
function handleModalClick(event) {
    if (event.target.id === 'filter-modal') {
        closeFilterModal();
    }
}

// Função legacy para compatibilidade
function toggleFilters() {
    openFilterModal();
}

function showFilters() {
    openFilterModal();
}

// Carregar dados
function loadCategories() {
    fetch('/api/financial/categories', {
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
        .then(response => {
            if (response.status === 401) {
                showError('Sessão expirada. Por favor, faça login novamente.');
                return null;
            }
            return response.json();
        })
        .then(data => {
            if (!data) return;
            
            const categorySelect = document.getElementById('filter-category');
            categorySelect.innerHTML = '<option value="">Todas</option>';
            
            if (data.receitas) {
                data.receitas.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.nome;
                    categorySelect.appendChild(option);
                });
            }
            
            if (data.despesas) {
                data.despesas.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.nome;
                    categorySelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Erro ao carregar categorias:', error);
        });
}

function loadAccounts() {
    // Carregar bancos
    fetch('/api/financial/banks', {
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
        .then(response => {
            if (response.status === 401) {
                showError('Sessão expirada. Por favor, faça login novamente.');
                return null;
            }
            return response.json();
        })
        .then(data => {
            if (!data) return;
            
            const select = document.getElementById('filter-account');
            if (Array.isArray(data)) {
                data.forEach(bank => {
                    const option = document.createElement('option');
                    option.value = `bank_${bank.id}`;
                    option.textContent = `${bank.nome} (Banco)`;
                    select.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Erro ao carregar bancos:', error));

    // Carregar cartões
    fetch('/api/financial/credit-cards', {
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
        .then(response => {
            if (response.status === 401) {
                showError('Sessão expirada. Por favor, faça login novamente.');
                return null;
            }
            return response.json();
        })
        .then(data => {
            if (!data) return;
            
            const select = document.getElementById('filter-account');
            if (Array.isArray(data)) {
                data.forEach(card => {
                    const option = document.createElement('option');
                    option.value = `card_${card.id}`;
                    option.textContent = `${card.nome_cartao} (Cartão)`;
                    select.appendChild(option);
                });
            }
        })
        .catch(error => console.error('Erro ao carregar cartões:', error));
}

async function loadTransactions() {
    showLoading();
    
    try {
        const params = new URLSearchParams({
            mes: currentMonth,
            ano: currentYear,
            ...currentFilters
        });
        
        // Carregar faturas de cartão de crédito
        await loadCreditCardInvoices();
        
        const response = await fetch(`/api/financial/transactions?${params}`, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });
        
        if (response.status === 401) {
            showError('Sessão expirada. Por favor, faça login novamente.');
            return;
        }
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.data !== undefined) {
            renderTransactions(data.data);
            updateSummary(data.summary || {});
            renderPagination(data);
        } else {
            // Se não há dados, mostrar empty state
            renderTransactions([]);
            updateSummary({});
        }
    } catch (error) {
        console.error('Erro:', error);
        if (error.message.includes('401')) {
            showError('Não autorizado. Verifique se você está logado.');
        } else {
            showError('Erro ao carregar transações. Tente novamente.');
        }
        // Mostrar empty state em caso de erro
        renderTransactions([]);
    } finally {
        hideLoading();
    }
}

function refreshTransactions() {
    loadTransactions();
}

// Funções para Faturas de Cartão de Crédito
async function loadCreditCardInvoices() {
    try {
        const params = new URLSearchParams({
            month: currentMonth,
            year: currentYear
        });
        
        const response = await fetch(`/api/financial/credit-card-invoices?${params}`, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        renderCreditCardInvoices(data.invoices || []);
    } catch (error) {
        console.error('Erro ao carregar faturas:', error);
        // Em caso de erro, ocultar seção de faturas
        document.getElementById('invoices-section').classList.add('hidden');
    }
}

function renderCreditCardInvoices(invoices) {
    const container = document.getElementById('invoices-grid');
    const section = document.getElementById('invoices-section');
    const template = document.getElementById('invoice-card-template');
    
    if (!invoices || invoices.length === 0) {
        section.classList.add('hidden');
        return;
    }
    
    section.classList.remove('hidden');
    container.innerHTML = '';
    
    invoices.forEach(invoice => {
        const card = template.content.cloneNode(true);
        
        // Preencher dados da fatura
        const nameEl = card.querySelector('.invoice-card-name');
        const periodEl = card.querySelector('.invoice-period');
        const totalEl = card.querySelector('.invoice-total');
        const countEl = card.querySelector('.invoice-transaction-count');
        const statusBadgeEl = card.querySelector('.invoice-status-badge');
        const toggleBtn = card.querySelector('.btn-toggle-invoice');
        const viewBtn = card.querySelector('.btn-view-transactions');
        
        if (nameEl) nameEl.textContent = invoice.credit_card_name;
        if (periodEl) periodEl.textContent = `${getMonthName(invoice.month)}/${invoice.year}`;
        if (totalEl) totalEl.textContent = formatCurrency(invoice.total_value);
        if (countEl) countEl.textContent = `${invoice.transaction_count} transações`;
        
        // Status badge
        if (statusBadgeEl) {
            if (invoice.is_paid) {
                statusBadgeEl.textContent = 'Paga';
                statusBadgeEl.className = 'invoice-status-badge px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
            } else {
                statusBadgeEl.textContent = 'Pendente';
                statusBadgeEl.className = 'invoice-status-badge px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
            }
        }
        
        // Botão de ação
        if (toggleBtn) {
            if (invoice.is_paid) {
                toggleBtn.textContent = 'Desfazer Pagamento';
                toggleBtn.className = 'btn-toggle-invoice px-4 py-2 text-sm font-medium rounded-lg transition-colors bg-yellow-600 hover:bg-yellow-700 text-white';
                toggleBtn.onclick = () => undoInvoicePayment(invoice.credit_card_id, invoice.month, invoice.year, toggleBtn);
            } else {
                toggleBtn.textContent = 'Pagar Fatura';
                toggleBtn.className = 'btn-toggle-invoice px-4 py-2 text-sm font-medium rounded-lg transition-colors bg-green-600 hover:bg-green-700 text-white';
                toggleBtn.onclick = () => payInvoice(invoice.credit_card_id, invoice.month, invoice.year, toggleBtn);
            }
        }
        
        // Botão ver transações
        if (viewBtn) {
            viewBtn.onclick = () => viewInvoiceTransactions(invoice.credit_card_id, invoice.month, invoice.year);
        }
        
        container.appendChild(card);
     });
 }
 
 async function payInvoice(creditCardId, month, year, button) {
     const originalText = button.textContent;
     button.textContent = 'Pagando...';
     button.disabled = true;
     
     try {
         const response = await fetch('/api/financial/credit-card-invoices/pay', {
             method: 'POST',
             headers: {
                 'Accept': 'application/json',
                 'Content-Type': 'application/json',
                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                 'X-Requested-With': 'XMLHttpRequest'
             },
             credentials: 'same-origin',
             body: JSON.stringify({
                 credit_card_id: creditCardId,
                 month: month,
                 year: year
             })
         });
         
         if (!response.ok) {
             throw new Error(`HTTP error! status: ${response.status}`);
         }
         
         const data = await response.json();
         
         if (data.success) {
             showToast('Fatura paga com sucesso!', 'success');
             // Recarregar faturas e transações
             await loadCreditCardInvoices();
             await loadTransactions();
         } else {
             throw new Error(data.message || 'Erro ao pagar fatura');
         }
     } catch (error) {
         console.error('Erro ao pagar fatura:', error);
         showToast('Erro ao pagar fatura: ' + error.message, 'error');
         button.textContent = originalText;
         button.disabled = false;
     }
 }
 
 async function undoInvoicePayment(creditCardId, month, year, button) {
     const originalText = button.textContent;
     button.textContent = 'Desfazendo...';
     button.disabled = true;
     
     try {
         const response = await fetch('/api/financial/credit-card-invoices/undo-payment', {
             method: 'POST',
             headers: {
                 'Accept': 'application/json',
                 'Content-Type': 'application/json',
                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                 'X-Requested-With': 'XMLHttpRequest'
             },
             credentials: 'same-origin',
             body: JSON.stringify({
                 credit_card_id: creditCardId,
                 month: month,
                 year: year
             })
         });
         
         if (!response.ok) {
             throw new Error(`HTTP error! status: ${response.status}`);
         }
         
         const data = await response.json();
         
         if (data.success) {
             showToast('Pagamento da fatura desfeito com sucesso!', 'success');
             // Recarregar faturas e transações
             await loadCreditCardInvoices();
             await loadTransactions();
         } else {
             throw new Error(data.message || 'Erro ao desfazer pagamento');
         }
     } catch (error) {
         console.error('Erro ao desfazer pagamento:', error);
         showToast('Erro ao desfazer pagamento: ' + error.message, 'error');
         button.textContent = originalText;
         button.disabled = false;
     }
 }
 
 function viewInvoiceTransactions(creditCardId, month, year) {
    // Aplicar filtro para mostrar apenas transações do cartão específico no mês
    const accountFilter = document.getElementById('filter-account');
    
    // Definir filtros específicos - usar 'mes' e 'ano' que o backend espera
    currentFilters.credit_card_id = creditCardId;
    
    // Atualizar mês e ano globais para que sejam enviados corretamente
    currentMonth = month;
    currentYear = year;
    
    // Atualizar o filtro de conta/cartão se existir
    if (accountFilter) {
        accountFilter.value = `credit_card_${creditCardId}`;
    }
    
    // Atualizar os filtros de mês e ano na interface se existirem
    const monthFilter = document.getElementById('month-filter');
    const yearFilter = document.getElementById('year-filter');
    
    if (monthFilter) {
        monthFilter.value = month;
    }
    
    if (yearFilter) {
        yearFilter.value = year;
    }
    
    // Mostrar painel de filtros se estiver oculto
    const filtersPanel = document.getElementById('filters-panel');
    if (filtersPanel && filtersPanel.classList.contains('hidden')) {
        filtersPanel.classList.remove('hidden');
    }
    
    // Carregar transações com os novos filtros
    loadTransactions();
    showToast(`Mostrando transações do cartão para ${getMonthName(month)}/${year}`, 'info');
}
 
 function getMonthName(month) {
     const months = [
         'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
         'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
     ];
     return months[month - 1] || month;
 }
 
 // Renderização
function showLoading() {
    document.getElementById('loading').classList.remove('hidden');
    document.getElementById('empty-state').classList.add('hidden');
    document.getElementById('transactions-grid').classList.add('hidden');
}

function hideLoading() {
    document.getElementById('loading').classList.add('hidden');
}

function updateSummary(summary) {
    const totalIncomeEl = document.getElementById('total-income');
    const totalExpensesEl = document.getElementById('total-expenses');
    const balanceEl = document.getElementById('balance');
    const pendingCountEl = document.getElementById('pending-count');
    
    if (totalIncomeEl) totalIncomeEl.textContent = formatCurrency(summary.total_income || 0);
    if (totalExpensesEl) totalExpensesEl.textContent = formatCurrency(summary.total_expenses || 0);
    if (balanceEl) balanceEl.textContent = formatCurrency(summary.balance || 0);
    if (pendingCountEl) pendingCountEl.textContent = summary.pending_count || 0;
    
    // Atualizar cor do saldo
    if (balanceEl) {
        const balance = summary.balance || 0;
        if (balance > 0) {
            balanceEl.className = 'text-2xl font-semibold text-green-600 dark:text-green-400';
        } else if (balance < 0) {
            balanceEl.className = 'text-2xl font-semibold text-red-600 dark:text-red-400';
        } else {
            balanceEl.className = 'text-2xl font-semibold text-gray-900 dark:text-white';
        }
    }
}

function renderTransactions(transactions) {
    const container = document.getElementById('transactions-grid');
    const emptyState = document.getElementById('empty-state');
    const template = document.getElementById('transaction-card-template');
    
    if (!transactions || transactions.length === 0) {
        container.classList.add('hidden');
        emptyState.classList.remove('hidden');
        return;
    }
    
    emptyState.classList.add('hidden');
    container.classList.remove('hidden');
    container.innerHTML = '';
    
    transactions.forEach(transaction => {
        const card = template.content.cloneNode(true);
        
        // Preencher dados
        const descriptionEl = card.querySelector('.transaction-description');
        const categoryBadgeEl = card.querySelector('.transaction-category-badge');
        const dateEl = card.querySelector('.transaction-date');
        const installmentBadgeEl = card.querySelector('.transaction-installment-badge');
        
        if (descriptionEl) {
            // Remover informação de parcela da descrição se existir
            let description = transaction.descricao;
            // Remove padrões como "(2/6)", "(1/12)", etc.
            description = description.replace(/\s*\(\d+\/\d+\)\s*$/, '');
            descriptionEl.textContent = description;
        }
        
        // Badge de categoria com cor baseada no tipo
        if (categoryBadgeEl) {
            const categoryName = transaction.category?.nome || 'Sem categoria';
            categoryBadgeEl.textContent = categoryName;
            
            if (transaction.tipo === 'receita') {
                categoryBadgeEl.classList.add('bg-green-100', 'text-green-800', 'dark:bg-green-900', 'dark:text-green-200');
            } else {
                categoryBadgeEl.classList.add('bg-red-100', 'text-red-800', 'dark:bg-red-900', 'dark:text-red-200');
            }
        }
        
        // Badge de parcelas (se for parcelado)
        if (installmentBadgeEl && transaction.frequency_type === 'parcelada' && transaction.installment_count) {
            installmentBadgeEl.textContent = `${transaction.installment_number}/${transaction.installment_count}`;
            installmentBadgeEl.classList.remove('hidden');
        }
        
        // Badge de recorrência (se for recorrente)
        const recurringBadgeEl = card.querySelector('.transaction-recurring-badge');
        if (recurringBadgeEl && (transaction.frequency_type === 'recorrente' || transaction.is_recurring === true)) {
            recurringBadgeEl.classList.remove('hidden');
        }
        
        if (dateEl) {
            dateEl.textContent = formatDate(transaction.data);
        }
        
        // Configurar avatar com fallback
        const avatarElement = card.querySelector('.transaction-avatar');
        const avatarContentEl = card.querySelector('.transaction-avatar-content');
        const amountElement = card.querySelector('.transaction-amount');
        
        if (avatarElement && avatarContentEl) {
            const categoryName = transaction.category?.nome || 'Sem categoria';
            
            // Verificar se a categoria tem ícone
            if (transaction.category?.icone_url) {
                // Mapear ícones (você pode expandir este mapeamento)
                const iconMapping = {
                    'home': '🏠',
                    'car': '🚗',
                    'food': '🍽️',
                    'shopping': '🛒',
                    'health': '🏥',
                    'education': '📚',
                    'entertainment': '🎬',
                    'travel': '✈️',
                    'salary': '💰',
                    'gift': '🎁',
                    'investment': '📈',
                    'other': '📋'
                };
                
                const emoji = iconMapping[transaction.category.icone_url] || '🏷️';
                avatarContentEl.textContent = emoji;
            } else {
                // Fallback: usar iniciais da categoria
                const initials = categoryName.split(' ').map(word => word.charAt(0)).join('').substring(0, 2).toUpperCase();
                avatarContentEl.textContent = initials;
            }
            
            // Cor do avatar baseada no tipo
            if (transaction.tipo === 'receita') {
                avatarElement.classList.add('bg-green-500');
                if (amountElement) amountElement.classList.add('text-green-600', 'dark:text-green-400');
            } else {
                avatarElement.classList.add('bg-red-500');
                if (amountElement) amountElement.classList.add('text-red-600', 'dark:text-red-400');
            }
        }
        
        // Valor
        if (amountElement) {
            amountElement.textContent = formatCurrency(transaction.valor);
        }
        
        // Status
        const statusBadge = card.querySelector('.transaction-status-badge');
        if (statusBadge) {
            statusBadge.textContent = transaction.status === 'pago' ? 'Pago' : 'Pendente';
            if (transaction.status === 'pago') {
                statusBadge.classList.add('bg-green-100', 'text-green-800', 'dark:bg-green-900', 'dark:text-green-200');
            } else {
                statusBadge.classList.add('bg-yellow-100', 'text-yellow-800', 'dark:bg-yellow-900', 'dark:text-yellow-200');
            }
        }
        
        // Notas
        if (transaction.observacoes) {
            const notesElement = card.querySelector('.transaction-notes');
            if (notesElement) {
                notesElement.textContent = transaction.observacoes;
                notesElement.style.display = 'block';
            }
        }
        
        // Configurar botões
        const toggleStatusBtn = card.querySelector('.btn-toggle-status');
        const editBtn = card.querySelector('.btn-edit');
        const duplicateBtn = card.querySelector('.btn-duplicate');
        const deleteBtn = card.querySelector('.btn-delete');
        
        // Configurar botão de alternar status
        if (toggleStatusBtn) {
            const svg = toggleStatusBtn.querySelector('svg');
            const path = svg.querySelector('path');
            
            if (transaction.status === 'pago') {
                // Mostrar ícone de clock para marcar como pendente
                toggleStatusBtn.classList.add('text-yellow-600', 'hover:text-yellow-800', 'dark:text-yellow-400', 'dark:hover:text-yellow-300');
                toggleStatusBtn.title = 'Marcar como Pendente';
                path.setAttribute('d', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z');
            } else {
                // Mostrar ícone de check para marcar como pago
                toggleStatusBtn.classList.add('text-green-600', 'hover:text-green-800', 'dark:text-green-400', 'dark:hover:text-green-300');
                toggleStatusBtn.title = 'Marcar como Pago';
                path.setAttribute('d', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z');
            }
        }
        
        // Adicionar event listeners
        if (toggleStatusBtn) {
            toggleStatusBtn.onclick = () => toggleTransactionStatus(toggleStatusBtn);
        }
        if (editBtn) {
            editBtn.onclick = () => window.location.href = `/financial/transactions/${transaction.id}/edit`;
        }
        if (duplicateBtn) {
            duplicateBtn.onclick = () => duplicateTransaction(duplicateBtn);
        }
        if (deleteBtn) {
            deleteBtn.onclick = () => deleteTransaction(deleteBtn);
        }
        
        // Adicionar data attributes para ações
        const cardElement = card.querySelector('div');
        if (cardElement) {
            cardElement.dataset.transactionId = transaction.id;
        }
        
        container.appendChild(card);
    });
    
    document.getElementById('transactions-grid').classList.remove('hidden');
}

function renderPagination(pagination) {
    // Implementar paginação se necessário
    // Por enquanto, vamos ocultar
    document.getElementById('pagination').classList.add('hidden');
}

// Ações
function toggleTransactionStatus(button) {
    const card = button.closest('div[data-transaction-id]');
    const transactionId = card.dataset.transactionId;
    const statusBadge = card.querySelector('.transaction-status-badge');
    const currentStatus = statusBadge.textContent.toLowerCase();
    const newStatus = currentStatus === 'pago' ? 'pendente' : 'pago';
    
    const endpoint = newStatus === 'pago' ? 'mark-paid' : 'mark-pending';
    
    fetch(`/financial/transactions/${transactionId}/${endpoint}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            // Tentar ler como texto primeiro para capturar mensagens de erro HTML
            return response.text().then(text => {
                let errorMessage = `HTTP error! status: ${response.status}`;
                
                // Se a resposta contém HTML, extrair mensagem de erro se possível
                if (text.includes('<') && text.includes('>')) {
                    errorMessage += ' - Servidor retornou HTML em vez de JSON';
                } else {
                    try {
                        const errorData = JSON.parse(text);
                        errorMessage = errorData.message || errorData.error || errorMessage;
                    } catch (e) {
                        errorMessage += ` - ${text}`;
                    }
                }
                
                throw new Error(errorMessage);
            });
        }
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                throw new Error('Resposta do servidor não é JSON válido: ' + text.substring(0, 100));
            });
        }
        
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Atualizar status badge
            statusBadge.textContent = newStatus === 'pago' ? 'Pago' : 'Pendente';
            
            if (newStatus === 'pago') {
                statusBadge.className = 'px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
            } else {
                statusBadge.className = 'px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
            }
            
            // Atualizar botão
            const svg = button.querySelector('svg');
            const path = svg.querySelector('path');
            
            // Remover classes de cor antigas
            button.classList.remove('text-green-600', 'hover:text-green-800', 'dark:text-green-400', 'dark:hover:text-green-300');
            button.classList.remove('text-yellow-600', 'hover:text-yellow-800', 'dark:text-yellow-400', 'dark:hover:text-yellow-300');
            
            if (newStatus === 'pago') {
                // Agora está pago, mostrar ícone de clock para marcar como pendente
                button.classList.add('text-yellow-600', 'hover:text-yellow-800', 'dark:text-yellow-400', 'dark:hover:text-yellow-300');
                button.title = 'Marcar como Pendente';
                path.setAttribute('d', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z');
            } else {
                // Agora está pendente, mostrar ícone de check para marcar como pago
                button.classList.add('text-green-600', 'hover:text-green-800', 'dark:text-green-400', 'dark:hover:text-green-300');
                button.title = 'Marcar como Pago';
                path.setAttribute('d', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z');
            }
            
            showToast(`Transação marcada como ${newStatus}!`, 'success');
            
            // Recarregar resumo
            setTimeout(() => loadTransactions(), 1000);
        } else {
            showToast(`Erro ao marcar como ${newStatus}`, 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showToast(`Erro ao marcar como ${newStatus}`, 'error');
    });
}

function duplicateTransaction(button) {
    const card = button.closest('div[data-transaction-id]');
    const transactionId = card.dataset.transactionId;
    
    fetch(`/api/financial/transactions/${transactionId}/duplicate`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Response is not JSON');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast('Transação duplicada com sucesso!', 'success');
            loadTransactions();
        } else {
            showToast('Erro ao duplicar transação', 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showToast('Erro ao duplicar transação', 'error');
    });
}

// Variáveis globais para o modal de exclusão
let transactionToDelete = null;
let deleteType = 'single'; // 'single' ou 'all'

function deleteTransaction(button) {
    const card = button.closest('div[data-transaction-id]');
    const transactionId = card.dataset.transactionId;
    
    // Buscar dados da transação para verificar se é parcelada
    const transactionData = getTransactionDataFromCard(card);
    
    transactionToDelete = transactionId;
    
    // Verificar se é uma compra parcelada
    if (transactionData.installments && transactionData.installments.total > 1) {
        showInstallmentDeleteModal(transactionData);
    } else {
        showSimpleDeleteModal();
    }
}

function getTransactionDataFromCard(card) {
    const installmentBadge = card.querySelector('.transaction-installment-badge');
    let installments = null;
    
    if (installmentBadge && !installmentBadge.classList.contains('hidden')) {
        const badgeText = installmentBadge.textContent.trim();
        const match = badgeText.match(/(\d+)\/(\d+)/);
        if (match) {
            installments = {
                current: parseInt(match[1]),
                total: parseInt(match[2])
            };
        }
    }
    
    return {
        installments: installments
    };
}

function showSimpleDeleteModal() {
    const deleteModal = document.getElementById('deleteModal');
    const installmentOptions = document.getElementById('installmentOptions');
    const simpleDeleteText = document.getElementById('simpleDeleteText');
    
    if (deleteModal) {
        deleteModal.classList.remove('hidden');
    }
    if (installmentOptions) {
        installmentOptions.classList.add('hidden');
    }
    if (simpleDeleteText) {
        simpleDeleteText.classList.remove('hidden');
    }
}

function showInstallmentDeleteModal(transactionData) {
    // Mostrar o novo modal de seleção de parcelas
    document.getElementById('installmentDeleteModal').classList.remove('hidden');
    
    // Carregar todas as parcelas
    loadInstallments(transactionToDelete);
}

function hideDeleteModal() {
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.classList.add('hidden');
    }
    
    transactionToDelete = null;
    deleteType = 'single';
    
    // Reset button state
    const deleteButton = document.getElementById('confirmDeleteBtn');
    const deleteButtonText = document.getElementById('deleteButtonText');
    const deleteSpinner = document.getElementById('deleteSpinner');
    
    if (deleteButton) {
        deleteButton.disabled = false;
    }
    if (deleteButtonText) {
        deleteButtonText.textContent = 'Excluir';
    }
    if (deleteSpinner) {
        deleteSpinner.classList.add('hidden');
    }
}

function hideInstallmentDeleteModal() {
    const installmentDeleteModal = document.getElementById('installmentDeleteModal');
    if (installmentDeleteModal) {
        installmentDeleteModal.classList.add('hidden');
    }
    
    transactionToDelete = null;
    
    // Limpar lista de parcelas
    const installmentsList = document.getElementById('installmentsList');
    if (installmentsList) {
        installmentsList.innerHTML = '';
    }
    
    // Reset button state
    const deleteButton = document.getElementById('deleteSelectedBtn');
    const deleteButtonText = document.getElementById('deleteSelectedText');
    const deleteSpinner = document.getElementById('deleteSelectedSpinner');
    
    if (deleteButton) {
        deleteButton.disabled = false;
    }
    if (deleteButtonText) {
        deleteButtonText.textContent = 'Excluir Selecionadas';
    }
    if (deleteSpinner) {
        deleteSpinner.classList.add('hidden');
    }
}

function selectDeleteType(type) {
    deleteType = type;
    
    // Update button styles
    const singleBtn = document.getElementById('deleteSingleBtn');
    const allBtn = document.getElementById('deleteAllBtn');
    
    if (!singleBtn || !allBtn) {
        console.warn('Delete type buttons not found in DOM');
        return;
    }
    
    if (type === 'single') {
        singleBtn.classList.add('bg-red-600', 'text-white');
        singleBtn.classList.remove('bg-gray-100', 'text-gray-700', 'dark:bg-gray-700', 'dark:text-gray-300');
        allBtn.classList.remove('bg-red-600', 'text-white');
        allBtn.classList.add('bg-gray-100', 'text-gray-700', 'dark:bg-gray-700', 'dark:text-gray-300');
    } else {
        allBtn.classList.add('bg-red-600', 'text-white');
        allBtn.classList.remove('bg-gray-100', 'text-gray-700', 'dark:bg-gray-700', 'dark:text-gray-300');
        singleBtn.classList.remove('bg-red-600', 'text-white');
        singleBtn.classList.add('bg-gray-100', 'text-gray-700', 'dark:bg-gray-700', 'dark:text-gray-300');
    }
}

function confirmDelete() {
    if (!transactionToDelete) {
        console.error('No transaction selected for deletion');
        return;
    }
    
    const deleteButton = document.getElementById('confirmDeleteBtn');
    const deleteButtonText = document.getElementById('deleteButtonText');
    const deleteSpinner = document.getElementById('deleteSpinner');
    
    // Show loading state
    deleteSpinner.classList.remove('hidden');
    deleteButtonText.textContent = 'Excluindo...';
    deleteButton.disabled = true;
    
    // Determinar a URL baseada no tipo de exclusão
    let deleteUrl;
    if (deleteType === 'all') {
        deleteUrl = `/financial/transactions/${transactionToDelete}/delete-all-installments`;
    } else {
        deleteUrl = `/financial/transactions/${transactionToDelete}`;
    }
    
    // Tentar exclusão via AJAX primeiro
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(deleteUrl, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(errorData => {
                throw new Error(errorData.message || errorData.error || `HTTP error! status: ${response.status}`);
            }).catch(() => {
                throw new Error(`HTTP error! status: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Transação excluída com sucesso!', 'success');
            
            // Remover card(s) da interface
            if (deleteType === 'all') {
                removeAllInstallmentCards();
            } else {
                const transactionCard = document.querySelector(`[data-transaction-id="${transactionToDelete}"]`);
                if (transactionCard) {
                    transactionCard.style.animation = 'fadeOut 0.3s ease-in-out';
                    setTimeout(() => {
                        transactionCard.remove();
                        updateTransactionCounters();
                    }, 300);
                }
            }
            
            hideDeleteModal();
        } else {
            throw new Error(data.message || data.error || 'Erro desconhecido');
        }
    })
    .catch(error => {
        console.error('AJAX deletion failed, falling back to form submission:', error);
        showToast('Tentando exclusão via formulário...', 'info');
        
        // Fallback: usar formulário HTML
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = deleteUrl;
        
        // Adicionar token CSRF
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
        
        // Adicionar método DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        // Adicionar ao DOM e submeter
        document.body.appendChild(form);
        form.submit();
    })
    .finally(() => {
        // Reset button state
        deleteSpinner.classList.add('hidden');
        deleteButtonText.textContent = 'Excluir';
        deleteButton.disabled = false;
    });
}

// Botão flutuante
function toggleFab() {
    const menu = document.getElementById('fab-menu');
    const mainButton = document.getElementById('fab-main');
    
    if (!menu || !mainButton) {
        console.warn('FAB elements not found in DOM');
        return;
    }
    
    fabOpen = !fabOpen;
    
    if (fabOpen) {
        menu.classList.remove('hidden');
        mainButton.style.transform = 'rotate(45deg)';
    } else {
        menu.classList.add('hidden');
        mainButton.style.transform = 'rotate(0deg)';
    }
}

// Utilitários
function getAccountName(transaction) {
    if (transaction.bank) {
        return `${transaction.bank.nome} (Banco)`;
    } else if (transaction.credit_card) {
        return `${transaction.credit_card.nome_cartao} (Cartão)`;
    }
    return 'Conta não informada';
}

function formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(value);
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('pt-BR');
}

function showToast(message, type = 'info') {
    // Remover toasts existentes
    const existingToasts = document.querySelectorAll('.toast-notification');
    existingToasts.forEach(toast => toast.remove());
    
    // Criar elemento do toast
    const toast = document.createElement('div');
    toast.className = `toast-notification fixed top-4 right-4 z-50 max-w-sm p-4 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out translate-x-full opacity-0`;
    
    // Definir cores baseadas no tipo
    let bgColor, textColor, iconSvg;
    switch (type) {
        case 'success':
            bgColor = 'bg-green-500';
            textColor = 'text-white';
            iconSvg = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>`;
            break;
        case 'error':
            bgColor = 'bg-red-500';
            textColor = 'text-white';
            iconSvg = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                      </svg>`;
            break;
        case 'warning':
            bgColor = 'bg-yellow-500';
            textColor = 'text-white';
            iconSvg = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                      </svg>`;
            break;
        default:
            bgColor = 'bg-blue-500';
            textColor = 'text-white';
            iconSvg = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>`;
    }
    
    toast.className += ` ${bgColor} ${textColor}`;
    
    toast.innerHTML = `
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                ${iconSvg}
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 ml-2 text-white hover:text-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    
    // Adicionar ao DOM
    document.body.appendChild(toast);
    
    // Animar entrada
    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');
    }, 10);
    
    // Auto-remover após 5 segundos
    setTimeout(() => {
        if (toast.parentElement) {
            toast.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 300);
        }
    }, 5000);
    
    // Log para debug
    console.log(`${type.toUpperCase()}: ${message}`);
}

function showError(message) {
    showToast(message, 'error');
}

// Função para atualizar contadores sem recarregar a lista completa
function updateTransactionCounters() {
    // Contar transações visíveis na página
    const visibleCards = document.querySelectorAll('.transaction-card:not([style*="display: none"])');
    const totalVisible = visibleCards.length;
    
    // Atualizar indicador de total se existir
    const totalIndicator = document.querySelector('.transaction-total-indicator');
    if (totalIndicator) {
        totalIndicator.textContent = `${totalVisible} transação${totalVisible !== 1 ? 'ões' : ''}`;
    }
    
    // Se não há mais transações visíveis, mostrar mensagem de lista vazia
    if (totalVisible === 0) {
        showEmptyState();
    }
}

// Função para remover todas as parcelas relacionadas
function removeAllInstallmentCards() {
    // Buscar o card da transação atual para obter informações das parcelas
    const currentCard = document.querySelector(`[data-transaction-id="${transactionToDelete}"]`);
    if (!currentCard) return;
    
    // Extrair informações da parcela do card atual
    const installmentBadge = currentCard.querySelector('.transaction-installment-badge');
    if (!installmentBadge) return;
    
    const installmentText = installmentBadge.textContent;
    const match = installmentText.match(/\d+\/(\d+)/);
    if (!match) return;
    
    const totalInstallments = parseInt(match[1]);
    
    // Buscar e remover todas as parcelas relacionadas
    const allCards = document.querySelectorAll('.transaction-card');
    allCards.forEach(card => {
        const badge = card.querySelector('.transaction-installment-badge');
        if (badge && badge.textContent.includes(`/${totalInstallments}`)) {
            // Verificar se é da mesma série de parcelas (mesmo valor e descrição)
            const currentDescription = currentCard.querySelector('.transaction-description')?.textContent;
            const cardDescription = card.querySelector('.transaction-description')?.textContent;
            
            if (currentDescription === cardDescription) {
                card.style.animation = 'fadeOut 0.3s ease-in-out';
                setTimeout(() => {
                    card.remove();
                }, 300);
            }
        }
    });
}

// Função para mostrar estado vazio
function showEmptyState() {
    const container = document.getElementById('transactions-container');
    if (container && container.children.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12">
                <div class="mx-auto h-12 w-12 text-gray-400">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhuma transação encontrada</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comece criando uma nova transação.</p>
            </div>
        `;
    }
}

// Funções para o modal de seleção de parcelas
function loadInstallments(transactionId) {
    const installmentsList = document.getElementById('installmentsList');
    const installmentModalInfo = document.getElementById('installmentModalInfo');
    
    if (!installmentsList) {
        console.error('Installments list element not found');
        return;
    }
    
    // Mostrar loading
    installmentsList.innerHTML = '<div class="text-center py-4"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div></div>';
    
    fetch(`/api/financial/transactions/${transactionId}/installments`)
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => {
                    throw new Error(errorData.message || errorData.error || `HTTP error! status: ${response.status}`);
                }).catch(() => {
                    throw new Error(`HTTP error! status: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const installments = data.installments;
                if (installmentModalInfo) {
                    installmentModalInfo.textContent = `Esta compra possui ${installments.length} parcelas. Selecione quais deseja excluir:`;
                }
                
                let html = '';
                installments.forEach(installment => {
                    const statusBadge = installment.status === 'paid' ? 
                        '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Pago</span>' :
                        '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Pendente</span>';
                    
                    const formattedValue = new Intl.NumberFormat('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    }).format(Math.abs(installment.valor));
                    
                    const formattedDate = new Date(installment.data_vencimento).toLocaleDateString('pt-BR');
                    
                    html += `
                        <div class="flex items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg mb-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <input type="checkbox" 
                                   id="installment_${installment.id}" 
                                   value="${installment.id}" 
                                   class="installment-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="installment_${installment.id}" class="ml-3 flex-1 cursor-pointer">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <span class="font-medium text-gray-900 dark:text-white">
                                            Parcela ${installment.installment_number}/${installment.installment_count}
                                        </span>
                                        ${statusBadge}
                                    </div>
                                    <div class="text-right">
                                        <div class="font-semibold text-gray-900 dark:text-white">${formattedValue}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Venc: ${formattedDate}</div>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    ${installment.descricao}
                                </div>
                            </label>
                        </div>
                    `;
                });
                
                installmentsList.innerHTML = html;
            } else {
                throw new Error(data.message || data.error || 'Erro ao carregar parcelas');
            }
        })
        .catch(error => {
            console.error('Error loading installments:', error);
            
            // Fechar o modal se a transação não for parcelada
            if (error.message.includes('não é parcelada') || error.message.includes('não possui parcelas')) {
                hideInstallmentDeleteModal();
                showToast('Esta transação não é parcelada. Use a exclusão simples.', 'warning');
                return;
            }
            
            installmentsList.innerHTML = `
                <div class="text-center py-8">
                    <div class="mx-auto h-12 w-12 text-red-400 mb-4">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-red-600 dark:text-red-400 font-medium">Erro ao carregar parcelas</p>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">${error.message}</p>
                </div>
            `;
            showToast('Erro ao carregar parcelas: ' + error.message, 'error');
        });
}

function selectAllInstallments() {
    const checkboxes = document.querySelectorAll('.installment-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
}

function deselectAllInstallments() {
    const checkboxes = document.querySelectorAll('.installment-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
}

// Funções de Loading State
function setButtonLoading(button, loading = true) {
    if (loading) {
        // Salvar texto original se não existir
        if (!button.dataset.originalText) {
            button.dataset.originalText = button.innerHTML;
        }
        
        button.disabled = true;
        button.classList.add('opacity-75', 'cursor-not-allowed');
        
        // Adicionar spinner
        const spinner = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        `;
        
        button.innerHTML = spinner + 'Processando...';
    } else {
        button.disabled = false;
        button.classList.remove('opacity-75', 'cursor-not-allowed');
        button.innerHTML = button.dataset.originalText || 'Excluir';
    }
}

function setCardLoading(card, loading = true) {
    if (loading) {
        card.classList.add('opacity-50', 'pointer-events-none');
        
        // Adicionar overlay de loading
        const overlay = document.createElement('div');
        overlay.className = 'absolute inset-0 bg-white bg-opacity-75 dark:bg-gray-800 dark:bg-opacity-75 flex items-center justify-center z-10 loading-overlay';
        overlay.innerHTML = `
            <div class="flex items-center space-x-2">
                <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-sm text-gray-600 dark:text-gray-300">Processando...</span>
            </div>
        `;
        
        card.style.position = 'relative';
        card.appendChild(overlay);
    } else {
        card.classList.remove('opacity-50', 'pointer-events-none');
        
        // Remover overlay de loading
        const overlay = card.querySelector('.loading-overlay');
        if (overlay) {
            overlay.remove();
        }
    }
}

function setModalLoading(modalId, loading = true) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    
    const content = modal.querySelector('.modal-content, [class*="modal"]');
    if (!content) return;
    
    if (loading) {
        content.classList.add('opacity-50', 'pointer-events-none');
        
        // Adicionar overlay de loading
        const overlay = document.createElement('div');
        overlay.className = 'absolute inset-0 bg-white bg-opacity-75 dark:bg-gray-800 dark:bg-opacity-75 flex items-center justify-center z-50 modal-loading-overlay';
        overlay.innerHTML = `
            <div class="flex flex-col items-center space-y-3">
                <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-sm text-gray-600 dark:text-gray-300">Carregando...</span>
            </div>
        `;
        
        content.style.position = 'relative';
        content.appendChild(overlay);
    } else {
        content.classList.remove('opacity-50', 'pointer-events-none');
        
        // Remover overlay de loading
        const overlay = content.querySelector('.modal-loading-overlay');
        if (overlay) {
            overlay.remove();
        }
    }
}

function deleteSelectedInstallments() {
    const selectedCheckboxes = document.querySelectorAll('.installment-checkbox:checked');
    
    if (selectedCheckboxes.length === 0) {
        showToast('Selecione pelo menos uma parcela para excluir', 'warning');
        return;
    }
    
    const selectedIds = Array.from(selectedCheckboxes).map(cb => cb.value);
    
    const deleteButton = document.getElementById('deleteSelectedBtn');
    const deleteButtonText = document.getElementById('deleteSelectedText');
    const deleteSpinner = document.getElementById('deleteSelectedSpinner');
    
    // Show loading state
    if (deleteSpinner) {
        deleteSpinner.classList.remove('hidden');
    }
    if (deleteButtonText) {
        deleteButtonText.textContent = 'Excluindo...';
    }
    if (deleteButton) {
        deleteButton.disabled = true;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const deleteUrl = '{{ route("api.financial.transactions.delete-selected-installments") }}';
    
    // Tentar exclusão via AJAX primeiro
    fetch(deleteUrl, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            installment_ids: selectedIds
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(errorData => {
                throw new Error(errorData.message || errorData.error || `HTTP error! status: ${response.status}`);
            }).catch(() => {
                throw new Error(`HTTP error! status: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast(data.message || `${data.deleted_count || selectedIds.length} parcela(s) excluída(s) com sucesso!`, 'success');
            
            // Remover cards das parcelas excluídas da interface
            selectedIds.forEach(id => {
                const transactionCard = document.querySelector(`[data-transaction-id="${id}"]`);
                if (transactionCard) {
                    transactionCard.style.animation = 'fadeOut 0.3s ease-in-out';
                    setTimeout(() => {
                        transactionCard.remove();
                    }, 300);
                }
            });
            
            // Atualizar contadores após um pequeno delay
            setTimeout(() => {
                updateTransactionCounters();
            }, 350);
            
            hideInstallmentDeleteModal();
        } else {
            throw new Error(data.message || data.error || 'Erro desconhecido');
        }
    })
    .catch(error => {
        console.error('AJAX batch deletion failed, falling back to form submission:', error);
        showToast('Tentando exclusão via formulário...', 'info');
        
        // Fallback: usar formulário HTML
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = deleteUrl;
        
        // Adicionar token CSRF
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
        
        // Adicionar método DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        // Adicionar IDs das parcelas selecionadas
        selectedIds.forEach(id => {
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'installment_ids[]';
            idInput.value = id;
            form.appendChild(idInput);
        });
        
        // Adicionar ao DOM e submeter
        document.body.appendChild(form);
        form.submit();
    })
    .finally(() => {
        // Reset button state
        if (deleteSpinner) {
            deleteSpinner.classList.add('hidden');
        }
        if (deleteButtonText) {
            deleteButtonText.textContent = 'Excluir Selecionadas';
        }
        if (deleteButton) {
            deleteButton.disabled = false;
        }
    });
}

// Fechar FAB ao clicar fora
document.addEventListener('click', function(event) {
    const fab = document.querySelector('.fixed.bottom-6.right-6');
    if (fabOpen && !fab.contains(event.target)) {
        toggleFab();
    }
});
</script>

<style>
@keyframes stamp {
    0% {
        transform: rotate(12deg) scale(0);
        opacity: 0;
    }
    50% {
        transform: rotate(12deg) scale(1.2);
        opacity: 1;
    }
    100% {
        transform: rotate(12deg) scale(1);
        opacity: 1;
    }
}

@keyframes fadeOut {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(-100%);
    }
}

.transaction-card:hover {
    transform: translateY(-2px);
}

.fab-menu {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Modal Animations */
.modal-content {
    transition: all 0.2s ease-out;
}

.scale-95 {
    transform: scale(0.95);
}

.scale-100 {
    transform: scale(1);
}

.opacity-0 {
    opacity: 0;
}

.opacity-100 {
    opacity: 1;
}

/* Filter Button Styles */
.filter-button {
    transition: all 0.2s ease;
}

.filter-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.filter-count {
    animation: pulse 0.3s ease-in-out;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

/* Estilos para Cards de Fatura */
.invoice-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    padding: 24px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
    border: 2px solid rgba(255, 255, 255, 0.2);
    color: white;
    position: relative;
    overflow: hidden;
}

.invoice-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
    pointer-events: none;
}

.invoice-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
}

.invoice-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}

.invoice-title {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 1.25rem;
    font-weight: 600;
}

.invoice-icon {
    width: 32px;
    height: 32px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.invoice-status {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.invoice-status.paid {
    background: rgba(34, 197, 94, 0.2);
    color: #22c55e;
    border: 1px solid rgba(34, 197, 94, 0.3);
}

.invoice-status.pending {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
    border: 1px solid rgba(251, 191, 36, 0.3);
}

.invoice-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 20px;
}

.invoice-detail {
    text-align: center;
}

.invoice-detail-label {
    font-size: 0.875rem;
    opacity: 0.8;
    margin-bottom: 4px;
}

.invoice-detail-value {
    font-size: 1.125rem;
    font-weight: 600;
}

.invoice-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
}

.invoice-btn {
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 6px;
}

.invoice-btn-primary {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.invoice-btn-primary:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-1px);
}

.invoice-btn-secondary {
    background: transparent;
    color: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.invoice-btn-secondary:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
}

.invoices-section {
    margin-bottom: 32px;
}

.invoices-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.invoices-title::before {
    content: '💳';
    font-size: 1.25rem;
}
</style>

<!-- Modal de Confirmação de Exclusão -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-[10000] hidden">
    <div class="relative top-10 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-2">Confirmar Exclusão</h3>
            
            <!-- Texto para exclusão simples -->
            <div id="simpleDeleteText" class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Tem certeza que deseja excluir esta transação? Esta ação não pode ser desfeita.
                </p>
            </div>
            
            <div class="items-center px-4 py-3">
                <div class="flex space-x-3">
                    <button onclick="hideDeleteModal()" 
                            class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancelar
                    </button>
                    <button id="confirmDeleteBtn" 
                            onclick="confirmDelete()" 
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 flex items-center justify-center">
                        <svg id="deleteSpinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span id="deleteButtonText">Excluir</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Seleção de Parcelas para Exclusão -->
<div id="installmentDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-[10000] hidden">
    <div class="relative top-5 mx-auto p-6 border max-w-2xl shadow-lg rounded-lg bg-white dark:bg-gray-800">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Selecionar Parcelas para Exclusão</h3>
            <button onclick="hideInstallmentDeleteModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="mb-4">
            <p id="installmentModalInfo" class="text-sm text-gray-600 dark:text-gray-400 mb-4"></p>
            
            <!-- Botões de Seleção -->
            <div class="flex space-x-3 mb-4">
                <button onclick="selectAllInstallments()" 
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Selecionar Todas
                </button>
                <button onclick="deselectAllInstallments()" 
                        class="px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Deselecionar Todas
                </button>
            </div>
        </div>
        
        <!-- Lista de Parcelas -->
        <div id="installmentsList" class="max-h-96 overflow-y-auto mb-6">
            <!-- Parcelas serão carregadas aqui via JavaScript -->
        </div>
        
        <!-- Botões de Ação -->
        <div class="flex justify-end space-x-3">
            <button onclick="hideInstallmentDeleteModal()" 
                    class="px-4 py-2 bg-gray-500 text-white font-medium rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                Cancelar
            </button>
            <button id="deleteSelectedBtn" 
                    onclick="deleteSelectedInstallments()" 
                    class="px-4 py-2 bg-red-600 text-white font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 flex items-center">
                <svg id="deleteSelectedSpinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span id="deleteSelectedText">Excluir Selecionadas</span>
            </button>
        </div>
    </div>
</div>

<script>
// Inicializar cards com dados do PHP
document.addEventListener('DOMContentLoaded', function() {
    // Dados passados do controller
    const summaryData = {
        receitas: {{ $receitas ?? 0 }},
        despesas: {{ $despesas ?? 0 }},
        saldo: {{ $saldo ?? 0 }},
        pendentes: {{ $pendentes ?? 0 }}
    };
    
    // Atualizar cards com os dados iniciais
    updateSummary(summaryData);
});
</script>

@endsection