@extends('layouts.app')

@section('title', $category->nome . ' - Detalhes da Categoria - Giro')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('dashboard'), 'icon' => 'fas fa-home'],
        ['label' => 'Financeiro', 'url' => '#'],
        ['label' => 'Categorias', 'url' => route('financial.categories.index')],
        ['label' => $category->nome]
    ]" />
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $category->nome }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $category->tipo === 'receita' ? 'Receita' : 'Despesa' }} - Detalhes da categoria</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('financial.categories.edit', $category) }}" class="flex items-center justify-center w-10 h-10 text-blue-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg" title="Editar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </a>
            <button onclick="refreshData()" class="flex items-center justify-center w-10 h-10 text-green-500 hover:text-green-600 dark:hover:text-green-400 transition-colors hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg" title="Atualizar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </button>
            <a href="{{ route('financial.categories.index') }}" class="flex items-center justify-center w-10 h-10 text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 transition-colors hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg" title="Voltar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Category Details Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Main Info -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Informações da Categoria</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $category->ativo ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                        {{ $category->ativo ? 'Ativa' : 'Inativa' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nome da Categoria</label>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $category->nome }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tipo</label>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $category->tipo === 'receita' ? 'Receita' : 'Despesa' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Ícone</label>
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-lg {{ $category->tipo === 'receita' ? 'bg-green-500' : 'bg-red-500' }}">
                                {{ $category->icone_emoji ?? '🏷️' }}
                            </div>
                            <span class="text-gray-600 dark:text-gray-400 text-sm">{{ $category->icone }}</span>
                        </div>
                    </div>
                    @if($category->cor)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Cor</label>
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 rounded-full border border-gray-300" style="background-color: {{ $category->cor }}"></div>
                            <span class="text-gray-900 dark:text-white">{{ $category->cor }}</span>
                        </div>
                    </div>
                    @endif
                </div>

                @if($category->descricao)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Descrição</label>
                    <p class="text-gray-900 dark:text-white">{{ $category->descricao }}</p>
                </div>
                @endif

                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Criada em:</span>
                            <span class="text-gray-900 dark:text-white ml-1">{{ $category->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Última atualização:</span>
                            <span class="text-gray-900 dark:text-white ml-1">{{ $category->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="bg-gradient-to-br {{ $category->tipo === 'receita' ? 'from-green-500 to-green-600' : 'from-red-500 to-red-600' }} rounded-xl shadow-sm text-white">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Estatísticas</h3>
                    <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <div class="mb-4" id="category-stats">
                    <p class="text-3xl font-bold">R$ 0,00</p>
                    <p class="{{ $category->tipo === 'receita' ? 'text-green-100' : 'text-red-100' }} text-sm mt-1">Total movimentado</p>
                </div>
                <div class="flex items-center text-sm {{ $category->tipo === 'receita' ? 'text-green-100' : 'text-red-100' }}">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    ID: #{{ $category->id }}
                </div>
            </div>
        </div>
    </div>

            <!-- Statistics -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Estatísticas</h2>
                        <div class="flex items-center space-x-2">
                            <select id="period-filter" class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="current_month">Mês Atual</option>
                                <option value="last_month">Mês Passado</option>
                                <option value="current_year">Ano Atual</option>
                                <option value="all_time">Todo o Período</option>
                            </select>
                        </div>
                    </div>
                    
                    <div id="statistics-content" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Statistics will be loaded here -->
                        <div class="col-span-3 text-center py-8">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div>
                            <p class="text-gray-500 dark:text-gray-400 mt-2">Carregando estatísticas...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Transações Recentes</h2>
                        <a href="{{ route('financial.transactions.index', ['category_id' => $category->id]) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            Ver todas →
                        </a>
                    </div>
                    
                    <div id="recent-transactions">
                        <!-- Transactions will be loaded here -->
                        <div class="text-center py-8">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div>
                            <p class="text-gray-500 dark:text-gray-400 mt-2">Carregando transações...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ações Rápidas</h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('financial.transactions.create', ['category_id' => $category->id, 'tipo' => $category->tipo]) }}" class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r {{ $category->tipo === 'receita' ? 'from-green-500 to-green-600 hover:from-green-600 hover:to-green-700' : 'from-red-500 to-red-600 hover:from-red-600 hover:to-red-700' }} text-white rounded-lg font-medium transition-all duration-200 transform hover:scale-105 shadow-lg" title="Nova {{ $category->tipo === 'receita' ? 'Receita' : 'Despesa' }}">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Nova {{ $category->tipo === 'receita' ? 'Receita' : 'Despesa' }}
                        </a>
                        
                        <a href="{{ route('financial.categories.edit', $category) }}" class="w-full inline-flex items-center justify-center p-3 rounded-lg hover:bg-yellow-50 dark:hover:bg-yellow-900/20 transition-colors" title="Editar Categoria">
                            <svg class="w-5 h-5 text-yellow-500 hover:text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        
                        <button onclick="toggleCategoryStatus()" class="w-full inline-flex items-center justify-center p-3 rounded-lg {{ $category->ativo ? 'hover:bg-gray-50 dark:hover:bg-gray-900/20' : 'hover:bg-green-50 dark:hover:bg-green-900/20' }} transition-colors" title="{{ $category->ativo ? 'Desativar' : 'Ativar' }}">
                            <svg class="w-5 h-5 {{ $category->ativo ? 'text-gray-500 hover:text-gray-600' : 'text-green-500 hover:text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($category->ativo)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L5.636 5.636"></path>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                @endif
                            </svg>
                        </button>
                        
                        <button onclick="duplicateCategory()" class="w-full inline-flex items-center justify-center p-3 rounded-lg hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-colors" title="Duplicar">
                            <svg class="w-5 h-5 text-purple-500 hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Monthly Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Resumo Mensal</h3>
                    
                    <div id="monthly-summary">
                        <!-- Monthly summary will be loaded here -->
                        <div class="text-center py-4">
                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500 mx-auto"></div>
                            <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">Carregando...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Categories -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Categorias Relacionadas</h3>
                    
                    <div id="related-categories">
                        <!-- Related categories will be loaded here -->
                        <div class="text-center py-4">
                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500 mx-auto"></div>
                            <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">Carregando...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const categoryId = {{ $category->id }};
const categoryType = '{{ $category->tipo }}';
const categoryActive = {{ $category->ativo ? 'true' : 'false' }};

document.addEventListener('DOMContentLoaded', function() {
    loadStatistics();
    loadRecentTransactions();
    loadMonthlySummary();
    loadRelatedCategories();
    setupEventListeners();
});

function setupEventListeners() {
    document.getElementById('period-filter').addEventListener('change', function() {
        loadStatistics();
    });
}

function loadStatistics() {
    const period = document.getElementById('period-filter').value;
    const statisticsContent = document.getElementById('statistics-content');
    
    // Show loading
    statisticsContent.innerHTML = `
        <div class="col-span-3 text-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div>
            <p class="text-gray-500 dark:text-gray-400 mt-2">Carregando estatísticas...</p>
        </div>
    `;
    
    fetch(`/api/financial/categories/${categoryId}/statistics?period=${period}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderStatistics(data.data);
            } else {
                showError('Erro ao carregar estatísticas');
            }
        })
        .catch(error => {
            console.error('Error loading statistics:', error);
            showError('Erro ao carregar estatísticas');
        });
}

function renderStatistics(stats) {
    const statisticsContent = document.getElementById('statistics-content');
    
    statisticsContent.innerHTML = `
        <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">${stats.total_transactions}</div>
            <div class="text-sm text-blue-700 dark:text-blue-300">Total de Transações</div>
        </div>
        
        <div class="text-center p-4 ${categoryType === 'receita' ? 'bg-green-50 dark:bg-green-900/20' : 'bg-red-50 dark:bg-red-900/20'} rounded-lg">
            <div class="text-2xl font-bold ${categoryType === 'receita' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'}">R$ ${formatMoney(stats.total_amount)}</div>
            <div class="text-sm ${categoryType === 'receita' ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300'}">Valor Total</div>
        </div>
        
        <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">R$ ${formatMoney(stats.average_amount)}</div>
            <div class="text-sm text-purple-700 dark:text-purple-300">Valor Médio</div>
        </div>
    `;
}

function loadRecentTransactions() {
    const recentTransactions = document.getElementById('recent-transactions');
    
    fetch(`/api/financial/categories/${categoryId}/transactions?limit=5`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderRecentTransactions(data.data);
            } else {
                showError('Erro ao carregar transações');
            }
        })
        .catch(error => {
            console.error('Error loading transactions:', error);
            showError('Erro ao carregar transações');
        });
}

function renderRecentTransactions(transactions) {
    const recentTransactions = document.getElementById('recent-transactions');
    
    if (transactions.length === 0) {
        recentTransactions.innerHTML = `
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500 dark:text-gray-400">Nenhuma transação encontrada</p>
                <a href="/financial/transactions/create?category_id=${categoryId}&tipo=${categoryType}" class="inline-flex items-center mt-4 text-blue-600 hover:text-blue-700">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Criar primeira transação
                </a>
            </div>
        `;
        return;
    }
    
    const transactionsHtml = transactions.map(transaction => `
        <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white ${transaction.tipo === 'receita' ? 'bg-green-500' : 'bg-red-500'}">
                    ${transaction.tipo === 'receita' ? '💰' : '💸'}
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-white">${transaction.descricao}</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">${formatDate(transaction.data_transacao)}</p>
                </div>
            </div>
            <div class="text-right">
                <div class="font-semibold ${transaction.tipo === 'receita' ? 'text-green-600' : 'text-red-600'}">
                    ${transaction.tipo === 'receita' ? '+' : '-'} R$ ${formatMoney(transaction.valor)}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    ${transaction.conta_origem || transaction.cartao_credito || 'N/A'}
                </div>
            </div>
        </div>
    `).join('');
    
    recentTransactions.innerHTML = `<div class="space-y-3">${transactionsHtml}</div>`;
}

function loadMonthlySummary() {
    const monthlySummary = document.getElementById('monthly-summary');
    
    fetch(`/api/financial/categories/${categoryId}/monthly-summary`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderMonthlySummary(data.data);
            } else {
                showError('Erro ao carregar resumo mensal');
            }
        })
        .catch(error => {
            console.error('Error loading monthly summary:', error);
            showError('Erro ao carregar resumo mensal');
        });
}

function renderMonthlySummary(summary) {
    const monthlySummary = document.getElementById('monthly-summary');
    
    const summaryHtml = summary.map(month => `
        <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-600 last:border-b-0">
            <div>
                <div class="font-medium text-gray-900 dark:text-white">${month.month_name}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">${month.transactions_count} transações</div>
            </div>
            <div class="text-right">
                <div class="font-semibold ${categoryType === 'receita' ? 'text-green-600' : 'text-red-600'}">
                    R$ ${formatMoney(month.total_amount)}
                </div>
            </div>
        </div>
    `).join('');
    
    monthlySummary.innerHTML = summaryHtml || '<p class="text-gray-500 dark:text-gray-400 text-center py-4">Nenhum dado disponível</p>';
}

function loadRelatedCategories() {
    const relatedCategories = document.getElementById('related-categories');
    
    fetch(`/api/financial/categories?tipo=${categoryType}&exclude=${categoryId}&limit=5`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderRelatedCategories(data.data);
            } else {
                showError('Erro ao carregar categorias relacionadas');
            }
        })
        .catch(error => {
            console.error('Error loading related categories:', error);
            showError('Erro ao carregar categorias relacionadas');
        });
}

function renderRelatedCategories(categories) {
    const relatedCategories = document.getElementById('related-categories');
    
    if (categories.length === 0) {
        relatedCategories.innerHTML = '<p class="text-gray-500 dark:text-gray-400 text-center py-4">Nenhuma categoria relacionada</p>';
        return;
    }
    
    const categoriesHtml = categories.map(category => `
        <a href="/financial/categories/${category.id}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm ${category.tipo === 'receita' ? 'bg-green-500' : 'bg-red-500'}">
                ${category.icone_emoji || '📁'}
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-medium text-gray-900 dark:text-white truncate">${category.nome}</div>
                <div class="text-sm text-gray-600 dark:text-gray-400">${category.transactions_count || 0} transações</div>
            </div>
        </a>
    `).join('');
    
    relatedCategories.innerHTML = `<div class="space-y-2">${categoriesHtml}</div>`;
}

function refreshData() {
    loadStatistics();
    loadRecentTransactions();
    loadMonthlySummary();
    loadRelatedCategories();
    showToast('Dados atualizados com sucesso!', 'success');
}

function toggleCategoryStatus() {
    const newStatus = !categoryActive;
    const action = newStatus ? 'ativar' : 'desativar';
    
    if (confirm(`Tem certeza que deseja ${action} esta categoria?`)) {
        fetch(`/api/financial/categories/${categoryId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(`Categoria ${action}da com sucesso!`, 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showToast(data.message || `Erro ao ${action} categoria`, 'error');
            }
        })
        .catch(error => {
            console.error('Error toggling category status:', error);
            showToast(`Erro ao ${action} categoria`, 'error');
        });
    }
}

function duplicateCategory() {
    if (confirm('Deseja criar uma cópia desta categoria?')) {
        fetch(`/api/financial/categories/${categoryId}/duplicate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Categoria duplicada com sucesso!', 'success');
                setTimeout(() => {
                    window.location.href = `/financial/categories/${data.category.id}`;
                }, 1000);
            } else {
                showToast(data.message || 'Erro ao duplicar categoria', 'error');
            }
        })
        .catch(error => {
            console.error('Error duplicating category:', error);
            showToast('Erro ao duplicar categoria', 'error');
        });
    }
}

// Utility functions
function formatMoney(value) {
    return parseFloat(value || 0).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('pt-BR');
}

function showError(message) {
    showToast(message, 'error');
}

// Show toast notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    
    toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 3000);
}
</script>
@endpush
@endsection