@extends('layouts.app')

@section('title', 'Detalhes do Cartão - Giro')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $creditCard->nome }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $creditCard->bandeira }} • •••• {{ substr($creditCard->numero, -4) }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('financial.credit-cards.edit', $creditCard) }}" 
               class="inline-flex items-center justify-center w-10 h-10 text-blue-600 hover:text-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-full transition-colors" 
               title="Editar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </a>
            <button onclick="updateUsedLimit()" 
                    class="inline-flex items-center justify-center w-10 h-10 text-green-600 hover:text-green-700 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-full transition-colors" 
                    title="Atualizar Limite">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </button>
            <a href="{{ route('financial.transactions.create', ['credit_card_id' => $creditCard->id]) }}" 
               class="inline-flex items-center justify-center w-10 h-10 text-purple-600 hover:text-purple-700 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-full transition-colors" 
               title="Nova Transação">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </a>
            <a href="{{ route('financial.credit-cards.statement', $creditCard) }}" 
               class="inline-flex items-center justify-center w-10 h-10 text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-full transition-colors" 
               title="Ver Extrato">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </a>
            <a href="{{ route('financial.credit-cards.index') }}" 
               class="inline-flex items-center justify-center w-10 h-10 text-gray-600 hover:text-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900/20 rounded-full transition-colors" 
               title="Voltar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Card Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Card Visual -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Cartão de Crédito</h2>
                
                <!-- Card Preview -->
                <div class="bg-gradient-to-br {{ $creditCard->ativo ? 'from-blue-500 to-purple-600' : 'from-gray-400 to-gray-500' }} rounded-xl p-6 text-white mb-4">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold">{{ $creditCard->nome }}</h3>
                            <p class="text-sm opacity-80">{{ $creditCard->bandeira }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs opacity-80">Limite Total</p>
                            <p class="text-lg font-bold">R$ {{ number_format($creditCard->limite_total, 2, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="mb-6">
                        <p class="text-2xl font-mono tracking-wider">•••• •••• •••• {{ substr($creditCard->numero, -4) }}</p>
                    </div>
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-xs opacity-80">Vencimento</p>
                            <p class="text-sm font-medium">{{ $creditCard->data_vencimento ?: '--' }}</p>
                        </div>
                        <div>
                            <p class="text-xs opacity-80">Fechamento</p>
                            <p class="text-sm font-medium">{{ $creditCard->data_fechamento ?: '--' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $creditCard->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $creditCard->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Limit Progress -->
                @php
                    $percentageUsed = $creditCard->limite_total > 0 ? ($creditCard->limite_utilizado / $creditCard->limite_total) * 100 : 0;
                    $availableLimit = $creditCard->limite_total - $creditCard->limite_utilizado;
                @endphp
                
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Limite Utilizado</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ number_format($percentageUsed, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                        <div class="h-3 rounded-full transition-all duration-300 {{ $percentageUsed > 80 ? 'bg-red-500' : ($percentageUsed > 60 ? 'bg-yellow-500' : 'bg-green-500') }}" 
                             style="width: {{ min($percentageUsed, 100) }}%"></div>
                    </div>
                    <div class="flex justify-between text-sm">
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">Utilizado:</span>
                            <span class="font-medium text-red-600 dark:text-red-400">R$ {{ number_format($creditCard->limite_utilizado, 2, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600 dark:text-gray-400">Disponível:</span>
                            <span class="font-medium text-green-600 dark:text-green-400">R$ {{ number_format($availableLimit, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Details -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações Detalhadas</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nome do Cartão</label>
                            <p class="text-gray-900 dark:text-white font-medium">{{ $creditCard->nome }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Bandeira</label>
                            <p class="text-gray-900 dark:text-white font-medium">{{ $creditCard->bandeira }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Número do Cartão</label>
                            <p class="text-gray-900 dark:text-white font-mono">{{ $creditCard->numero }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $creditCard->ativo ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                {{ $creditCard->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Limite de Crédito</label>
                            <p class="text-gray-900 dark:text-white font-medium">R$ {{ number_format($creditCard->limite_total, 2, ',', '.') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Limite Utilizado</label>
                            <p class="text-red-600 dark:text-red-400 font-medium">R$ {{ number_format($creditCard->limite_utilizado, 2, ',', '.') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Dia do Vencimento</label>
                            <p class="text-gray-900 dark:text-white font-medium">{{ $creditCard->data_vencimento ?: 'Não definido' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Dia do Fechamento</label>
                            <p class="text-gray-900 dark:text-white font-medium">{{ $creditCard->data_fechamento ?: 'Não definido' }}</p>
                        </div>
                    </div>
                </div>
                
                @if($creditCard->observacoes)
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Observações</label>
                    <p class="text-gray-900 dark:text-white">{{ $creditCard->observacoes }}</p>
                </div>
                @endif
                
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Criado em:</span>
                            <span class="text-gray-900 dark:text-white ml-2">{{ $creditCard->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Última atualização:</span>
                            <span class="text-gray-900 dark:text-white ml-2">{{ $creditCard->updated_at?->format('d/m/Y H:i') ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Transações Recentes</h2>
                    <a href="{{ route('financial.credit-cards.statement', $creditCard) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        Ver extrato completo →
                    </a>
                </div>
                
                <div id="recent-transactions" class="space-y-3">
                    <div class="flex items-center justify-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <span class="ml-2 text-gray-600 dark:text-gray-400">Carregando transações...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Summary -->
        <div class="space-y-6">

            <!-- Limit Alert -->
            @if($percentageUsed > 80)
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <div>
                        <h4 class="text-sm font-medium text-red-800 dark:text-red-400">Limite Alto</h4>
                        <p class="text-sm text-red-600 dark:text-red-300 mt-1">
                            Você está usando {{ number_format($percentageUsed, 1) }}% do seu limite. Considere fazer um pagamento.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Next Due Date -->
            @if($creditCard->data_vencimento)
            @php
                $nextDueDate = \Carbon\Carbon::now();
                if ($nextDueDate->day > $creditCard->data_vencimento) {
                    $nextDueDate->addMonth();
                }
                $nextDueDate->day = $creditCard->data_vencimento;
                $daysUntilDue = $nextDueDate->diffInDays(\Carbon\Carbon::now());
            @endphp
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Próximo Vencimento</h3>
                
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $daysUntilDue }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ $daysUntilDue == 1 ? 'dia' : 'dias' }}</div>
                    <div class="text-sm text-gray-900 dark:text-white mt-2">{{ $nextDueDate?->format('d/m/Y') ?? 'N/A' }}</div>
                    
                    @if($daysUntilDue <= 5)
                    <div class="mt-3 px-3 py-1 bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-400 rounded-full text-xs font-medium">
                        Vencimento próximo!
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Monthly Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Resumo do Mês</h3>
                
                <div id="monthly-summary" class="space-y-3">
                    <div class="flex items-center justify-center py-4">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Carregando...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadRecentTransactions();
    loadMonthlySummary();
});

// Load recent transactions
function loadRecentTransactions() {
    fetch(`/financial/api/credit-cards/{{ $creditCard->id }}/transactions?limit=5`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('recent-transactions');
        
        if (data.transactions && data.transactions.length > 0) {
            container.innerHTML = data.transactions.map(transaction => `
                <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center ${
                            transaction.tipo === 'receita' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'
                        }">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                ${transaction.tipo === 'receita' ? 
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>' :
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>'
                                }
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">${transaction.descricao}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">${transaction.data_vencimento}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-medium ${
                            transaction.tipo === 'receita' ? 'text-green-600' : 'text-red-600'
                        }">R$ ${transaction.valor}</p>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${
                            transaction.pago ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                        }">
                            ${transaction.pago ? 'Pago' : 'Pendente'}
                        </span>
                    </div>
                </div>
            `).join('');
        } else {
            container.innerHTML = `
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400">Nenhuma transação encontrada</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error loading transactions:', error);
        document.getElementById('recent-transactions').innerHTML = `
            <div class="text-center py-8">
                <p class="text-red-600 dark:text-red-400">Erro ao carregar transações</p>
            </div>
        `;
    });
}

// Load monthly summary
function loadMonthlySummary() {
    const currentMonth = new Date().toISOString().slice(0, 7);
    
    fetch(`/financial/api/credit-cards/{{ $creditCard->id }}/monthly-summary?month=${currentMonth}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('monthly-summary');
        
        container.innerHTML = `
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Transações:</span>
                    <span class="font-medium text-gray-900 dark:text-white">${data.total_transactions || 0}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Total gasto:</span>
                    <span class="font-medium text-red-600 dark:text-red-400">R$ ${data.total_spent || '0,00'}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Pendentes:</span>
                    <span class="font-medium text-yellow-600 dark:text-yellow-400">R$ ${data.pending_amount || '0,00'}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Pagas:</span>
                    <span class="font-medium text-green-600 dark:text-green-400">R$ ${data.paid_amount || '0,00'}</span>
                </div>
            </div>
        `;
    })
    .catch(error => {
        console.error('Error loading monthly summary:', error);
        document.getElementById('monthly-summary').innerHTML = `
            <div class="text-center py-4">
                <p class="text-red-600 dark:text-red-400 text-sm">Erro ao carregar resumo</p>
            </div>
        `;
    });
}

// Update used limit
function updateUsedLimit() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.innerHTML = `
        <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        Atualizando...
    `;
    button.disabled = true;
    
    fetch(`/financial/api/credit-cards/{{ $creditCard->id }}/update-used-limit`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Limite atualizado com sucesso!', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showToast(data.message || 'Erro ao atualizar limite', 'error');
        }
    })
    .catch(error => {
        console.error('Error updating used limit:', error);
        showToast('Erro ao atualizar limite', 'error');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Pay full invoice
function payFullInvoice() {
    if (confirm('Deseja pagar a fatura integral deste cartão? Todas as transações pendentes serão marcadas como pagas.')) {
        const button = event.target;
        const originalText = button.innerHTML;
        
        button.innerHTML = `
            <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Processando...
        `;
        button.disabled = true;
        
        fetch(`/financial/api/credit-cards/{{ $creditCard->id }}/pay-full-invoice`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Fatura paga com sucesso!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showToast(data.message || 'Erro ao pagar fatura', 'error');
            }
        })
        .catch(error => {
            console.error('Error paying invoice:', error);
            showToast('Erro ao pagar fatura', 'error');
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
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