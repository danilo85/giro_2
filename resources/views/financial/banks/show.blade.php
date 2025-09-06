@extends('layouts.app')

@section('title', $bank->nome . ' - Detalhes da Conta - Giro')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('dashboard'), 'icon' => 'fas fa-home'],
        ['label' => 'Financeiro', 'url' => '#'],
        ['label' => 'Bancos', 'url' => route('financial.banks.index')],
        ['label' => $bank->nome]
    ]" />
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $bank->nome }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $bank->tipo_conta }} - Detalhes da conta</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('financial.banks.edit', $bank) }}" class="flex items-center justify-center w-10 h-10 text-blue-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg" title="Editar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </a>
            <button onclick="updateBalance()" class="flex items-center justify-center w-10 h-10 text-green-500 hover:text-green-600 dark:hover:text-green-400 transition-colors hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg" title="Atualizar Saldo">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </button>
            <a href="{{ route('financial.banks.index') }}" class="flex items-center justify-center w-10 h-10 text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 transition-colors hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg" title="Voltar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Bank Details Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Main Info -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Informações da Conta</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $bank->ativo ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                        {{ $bank->ativo ? 'Ativa' : 'Inativa' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nome do Banco</label>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $bank->nome }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tipo de Conta</label>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $bank->tipo_conta }}</p>
                    </div>
                    @if($bank->agencia)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Agência</label>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $bank->agencia }}</p>
                    </div>
                    @endif
                    @if($bank->conta)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Número da Conta</label>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $bank->conta }}</p>
                    </div>
                    @endif
                </div>

                @if($bank->observacoes)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Observações</label>
                    <p class="text-gray-900 dark:text-white">{{ $bank->observacoes }}</p>
                </div>
                @endif

                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Criada em:</span>
                            <span class="text-gray-900 dark:text-white ml-1">{{ $bank->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Última atualização:</span>
                            <span class="text-gray-900 dark:text-white ml-1">{{ $bank->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Balance Card -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-sm text-white">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Saldo Atual</h3>
                    <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="mb-4">
                    <p class="text-3xl font-bold" id="current-balance">R$ {{ number_format($bank->saldo_atual, 2, ',', '.') }}</p>
                    <p class="text-green-100 text-sm mt-1">Atualizado em {{ $bank->updated_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="flex items-center text-sm text-green-100">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    ID: #{{ $bank->id }}
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Transações Recentes</h2>
                <a href="{{ route('financial.transactions.index', ['bank_id' => $bank->id]) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium">
                    Ver todas →
                </a>
            </div>

            <div id="transactions-list">
                <!-- Transactions will be loaded here via AJAX -->
                <div class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div>
                    <span class="ml-2 text-gray-600 dark:text-gray-400">Carregando transações...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('financial.transactions.create', ['bank_id' => $bank->id, 'type' => 'receita']) }}" class="flex items-center justify-center p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
            <svg class="w-6 h-6 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <div>
                <p class="font-medium text-green-900 dark:text-green-300">Nova Receita</p>
                <p class="text-sm text-green-600 dark:text-green-400">Adicionar entrada</p>
            </div>
        </a>

        <a href="{{ route('financial.transactions.create', ['bank_id' => $bank->id, 'type' => 'despesa']) }}" class="flex items-center justify-center p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors">
            <svg class="w-6 h-6 text-red-600 dark:text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
            </svg>
            <div>
                <p class="font-medium text-red-900 dark:text-red-300">Nova Despesa</p>
                <p class="text-sm text-red-600 dark:text-red-400">Adicionar saída</p>
            </div>
        </a>

        <a href="{{ route('financial.banks.extrato', $bank) }}" class="flex items-center justify-center p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <div>
                <p class="font-medium text-blue-900 dark:text-blue-300">Extrato</p>
                <p class="text-sm text-blue-600 dark:text-blue-400">Ver movimentação</p>
            </div>
        </a>
    </div>
</div>

@push('scripts')
<script>
// Load recent transactions
function loadRecentTransactions() {
    fetch(`/api/financial/banks/{{ $bank->id }}/transactions?limit=5`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('transactions-list');
            
            if (data.transactions && data.transactions.length > 0) {
                container.innerHTML = data.transactions.map(transaction => `
                    <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg mb-3 last:mb-0">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center ${
                                transaction.tipo === 'receita' ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900'
                            }">
                                <svg class="w-5 h-5 ${
                                    transaction.tipo === 'receita' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'
                                }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    ${transaction.tipo === 'receita' ? 
                                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>' :
                                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>'
                                    }
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-900 dark:text-white">${transaction.descricao}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">${transaction.data_vencimento}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold ${
                                transaction.tipo === 'receita' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'
                            }">
                                ${transaction.tipo === 'receita' ? '+' : '-'} R$ ${transaction.valor_formatado}
                            </p>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${
                                transaction.pago ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'
                            }">
                                ${transaction.pago ? 'Pago' : 'Pendente'}
                            </span>
                        </div>
                    </div>
                `).join('');
            } else {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">Nenhuma transação encontrada</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Comece adicionando uma nova transação</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading transactions:', error);
            document.getElementById('transactions-list').innerHTML = `
                <div class="text-center py-8">
                    <p class="text-red-500 dark:text-red-400">Erro ao carregar transações</p>
                </div>
            `;
        });
}

// Update balance
function updateBalance() {
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    
    button.innerHTML = `
        <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        Atualizando...
    `;
    button.disabled = true;
    
    fetch(`/api/financial/banks/{{ $bank->id }}/balance`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('current-balance').textContent = `R$ ${data.balance_formatted}`;
            
            // Show success message
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            toast.textContent = 'Saldo atualizado com sucesso!';
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    })
    .catch(error => {
        console.error('Error updating balance:', error);
        
        // Show error message
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        toast.textContent = 'Erro ao atualizar saldo';
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Load transactions on page load
document.addEventListener('DOMContentLoaded', function() {
    loadRecentTransactions();
});
</script>
@endpush
@endsection