@extends('layouts.app')

@section('title', 'Detalhes da Transação')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Cabeçalho -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detalhes da Transação</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $transaction->description }}</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="refreshData()" 
                    class="p-2 rounded-lg transition-colors text-blue-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20" 
                    title="Atualizar">
                <i class="fas fa-sync-alt text-lg"></i>
            </button>
            <a href="{{ route('financial.transactions.edit', $transaction->id) }}" 
               class="p-2 rounded-lg transition-colors text-green-500 hover:text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20" 
               title="Editar">
                <i class="fas fa-edit text-lg"></i>
            </a>
            <a href="{{ route('financial.transactions.index') }}" 
               class="p-2 rounded-lg transition-colors text-gray-500 hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-900/20" 
               title="Voltar">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações Principais -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Card Principal da Transação -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="border-l-4 {{ $transaction->type === 'income' ? 'border-green-500' : 'border-red-500' }} p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <div class="w-12 h-12 {{ $transaction->type === 'income' ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900' }} rounded-full flex items-center justify-center mr-4">
                                    <i class="fas {{ $transaction->category->icon ?? 'fa-circle' }} {{ $transaction->type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $transaction->description }}</h2>
                                    <p class="text-gray-600 dark:text-gray-400">{{ $transaction->category->name ?? 'Sem categoria' }}</p>
                                </div>
                            </div>
                            
                            @if($transaction->notes)
                            <div class="mt-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $transaction->notes }}</p>
                            </div>
                            @endif
                        </div>
                        
                        <div class="text-right ml-6">
                            <div class="text-3xl font-bold {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }} mb-2">
                                R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                            </div>
                            
                            <div class="flex items-center justify-end space-x-2">
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-medium {{
                                    $transaction->status === 'paid' 
                                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                        : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                                }}">
                                    {{ $transaction->status === 'paid' ? 'Pago' : 'Pendente' }}
                                </span>
                                
                                @if($transaction->status === 'paid')
                                <div class="paid-stamp">
                                    <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações Detalhadas -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações Detalhadas</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Coluna 1 -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tipo</label>
                            <div class="flex items-center mt-1">
                                <i class="fas {{ $transaction->type === 'income' ? 'fa-arrow-up text-green-500' : 'fa-arrow-down text-red-500' }} mr-2"></i>
                                <span class="text-gray-900 dark:text-white font-medium">
                                    {{ $transaction->type === 'income' ? 'Receita' : 'Despesa' }}
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Categoria</label>
                            <div class="flex items-center mt-1">
                                <span class="mr-2">{{ $transaction->category->icon ?? '📂' }}</span>
                                <span class="text-gray-900 dark:text-white font-medium">{{ $transaction->category->name ?? 'Sem categoria' }}</span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Conta/Cartão</label>
                            <div class="flex items-center mt-1">
                                @if($transaction->bank)
                                    <i class="fas fa-university text-blue-500 mr-2"></i>
                                    <span class="text-gray-900 dark:text-white font-medium">{{ $transaction->bank->name }} - {{ $transaction->bank->account_number }}</span>
                                @elseif($transaction->creditCard)
                                    <i class="fas fa-credit-card text-purple-500 mr-2"></i>
                                    <span class="text-gray-900 dark:text-white font-medium">{{ $transaction->creditCard->name }} - ****{{ substr($transaction->creditCard->number, -4) }}</span>
                                @else
                                    <i class="fas fa-question-circle text-gray-400 mr-2"></i>
                                    <span class="text-gray-500 dark:text-gray-400">Não definido</span>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Data de Vencimento</label>
                            <div class="flex items-center mt-1">
                                <i class="fas fa-calendar text-orange-500 mr-2"></i>
                                <span class="text-gray-900 dark:text-white font-medium">{{ $transaction->due_date?->format('d/m/Y') ?? 'N/A' }}</span>
                                @if($transaction->due_date->isPast() && $transaction->status === 'pending')
                                    <span class="ml-2 px-2 py-1 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 text-xs rounded-full">
                                        Vencida
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Coluna 2 -->
                    <div class="space-y-4">
                        @if($transaction->is_installment)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Parcelamento</label>
                            <div class="flex items-center mt-1">
                                <i class="fas fa-list-ol text-indigo-500 mr-2"></i>
                                <span class="text-gray-900 dark:text-white font-medium">
                                    {{ $transaction->installment_number }}/{{ $transaction->total_installments }}
                                </span>
                            </div>
                        </div>
                        @endif
                        
                        @if($transaction->is_recurring)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Recorrência</label>
                            <div class="flex items-center mt-1">
                                <i class="fas fa-repeat text-purple-500 mr-2"></i>
                                <span class="text-gray-900 dark:text-white font-medium">
                                    {{ ucfirst($transaction->recurring_type) }}
                                </span>
                            </div>
                        </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Criado em</label>
                            <div class="flex items-center mt-1">
                                <i class="fas fa-clock text-gray-500 mr-2"></i>
                                <span class="text-gray-900 dark:text-white font-medium">{{ $transaction->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Última atualização</label>
                            <div class="flex items-center mt-1">
                                <i class="fas fa-edit text-gray-500 mr-2"></i>
                                <span class="text-gray-900 dark:text-white font-medium">{{ $transaction->updated_at?->format('d/m/Y H:i') ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transações Relacionadas -->
            @if($transaction->is_installment || $transaction->is_recurring)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    @if($transaction->is_installment)
                        Outras Parcelas
                    @else
                        Transações Recorrentes
                    @endif
                </h3>
                
                <div id="related-transactions" class="space-y-3">
                    <!-- Carregado via AJAX -->
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin text-gray-400 text-xl"></i>
                        <p class="text-gray-500 dark:text-gray-400 mt-2">Carregando transações relacionadas...</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Arquivos Anexos -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Arquivos Anexos</h3>
                </div>
                
                <!-- Componente de Upload de Arquivos -->
                <div id="file-upload-container" data-transaction-id="{{ $transaction->id }}">
                    <!-- Botão para adicionar arquivo -->
                    <div class="mb-4">
                        <button onclick="uploadFile()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-plus mr-2"></i>Adicionar Arquivo
                        </button>
                    </div>
                    
                    <!-- Lista de arquivos -->
                    <div id="transaction-files">
                        <!-- Os arquivos serão carregados via JavaScript -->
                        <div class="text-center py-4">
                            <i class="fas fa-spinner fa-spin text-gray-400 text-xl"></i>
                            <p class="text-gray-500 dark:text-gray-400 mt-2">Carregando arquivos...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Ações Rápidas -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ações Rápidas</h3>
                
                <div class="space-y-3">
                    @if($transaction->status === 'pending')
                    <button onclick="markAsPaid()" class="w-full bg-green-100 hover:bg-green-200 dark:bg-green-900 dark:hover:bg-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg transition-colors text-left">
                        <i class="fas fa-check mr-2"></i>Marcar como Pago
                    </button>
                    @else
                    <button onclick="markAsPending()" class="w-full bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-900 dark:hover:bg-yellow-800 text-yellow-800 dark:text-yellow-200 px-4 py-3 rounded-lg transition-colors text-left">
                        <i class="fas fa-clock mr-2"></i>Marcar como Pendente
                    </button>
                    @endif
                    
                    <button onclick="duplicateTransaction()" class="w-full bg-purple-100 hover:bg-purple-200 dark:bg-purple-900 dark:hover:bg-purple-800 text-purple-800 dark:text-purple-200 px-4 py-3 rounded-lg transition-colors text-left">
                        <i class="fas fa-copy mr-2"></i>Duplicar Transação
                    </button>
                    
                    <a href="{{ route('financial.transactions.edit', $transaction->id) }}" class="block w-full bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:hover:bg-blue-800 text-blue-800 dark:text-blue-200 px-4 py-3 rounded-lg transition-colors text-left">
                        <i class="fas fa-edit mr-2"></i>Editar Transação
                    </a>
                    
                    <button onclick="addNote()" class="w-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 px-4 py-3 rounded-lg transition-colors text-left">
                        <i class="fas fa-sticky-note mr-2"></i>Adicionar Nota
                    </button>
                </div>
            </div>

            <!-- Resumo da Conta/Cartão -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Resumo da Conta</h3>
                
                <div id="account-summary">
                    <!-- Carregado via AJAX -->
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin text-gray-400 text-xl"></i>
                        <p class="text-gray-500 dark:text-gray-400 mt-2">Carregando resumo...</p>
                    </div>
                </div>
            </div>

            <!-- Estatísticas da Categoria -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Estatísticas da Categoria</h3>
                
                <div id="category-stats">
                    <!-- Carregado via AJAX -->
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin text-gray-400 text-xl"></i>
                        <p class="text-gray-500 dark:text-gray-400 mt-2">Carregando estatísticas...</p>
                    </div>
                </div>
            </div>

            <!-- Links Rápidos -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Links Rápidos</h3>
                
                <div class="space-y-2">
                    <a href="{{ route('financial.transactions.create', ['type' => $transaction->type]) }}" class="block text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                        <i class="fas fa-plus mr-2"></i>Nova {{ $transaction->type === 'income' ? 'Receita' : 'Despesa' }}
                    </a>
                    
                    <a href="{{ route('financial.categories.show', $transaction->category_id) }}" class="block text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                        <i class="fas fa-tag mr-2"></i>Ver Categoria
                    </a>
                    
                    @if($transaction->bank)
                    <a href="{{ route('financial.banks.show', $transaction->bank_id) }}" class="block text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                        <i class="fas fa-university mr-2"></i>Ver Conta Bancária
                    </a>
                    @endif
                    
                    @if($transaction->creditCard)
                    <a href="{{ route('financial.credit-cards.show', $transaction->credit_card_id) }}" class="block text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                        <i class="fas fa-credit-card mr-2"></i>Ver Cartão de Crédito
                    </a>
                    @endif
                    
                    <a href="{{ route('financial.transactions.index', ['category' => $transaction->category_id]) }}" class="block text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                        <i class="fas fa-list mr-2"></i>Outras Transações desta Categoria
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Upload de Arquivo -->
<div id="upload-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Adicionar Arquivo</h3>
                    <button onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="upload-form" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Arquivo</label>
                        <input type="file" id="file" name="file" required
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div class="mb-4">
                        <label for="file_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descrição</label>
                        <input type="text" id="file_description" name="description"
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Descrição do arquivo...">
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeUploadModal()" 
                                class="p-2 rounded-lg transition-colors text-gray-500 hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-900/20" 
                                title="Cancelar">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                        <button type="submit" 
                                class="p-2 rounded-lg transition-colors text-blue-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20" 
                                title="Enviar">
                            <i class="fas fa-upload text-lg"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Debug inicial
console.log('Script da página show.blade.php carregado!');

// Dados da transação
const transaction = {
    id: {{ $transaction->id }},
    type: '{{ $transaction->type }}',
    category_id: {{ $transaction->category_id }},
    bank_id: {{ $transaction->bank_id ?? 'null' }},
    credit_card_id: {{ $transaction->credit_card_id ?? 'null' }},
    is_installment: {{ $transaction->is_installment ? 'true' : 'false' }},
    is_recurring: {{ $transaction->is_recurring ? 'true' : 'false' }},
    installment_group: '{{ $transaction->installment_group ?? '' }}',
    recurring_group: '{{ $transaction->recurring_group ?? '' }}'
};

console.log('Objeto transaction criado:', transaction);
console.log('Transaction ID:', transaction.id);
console.log('Tipo do transaction.id:', typeof transaction.id);

// Inicialização
document.addEventListener('DOMContentLoaded', function() {
    loadRelatedTransactions();
    loadTransactionFiles();
    loadAccountSummary();
    loadCategoryStats();
    setupUploadForm();
});

// Carregar dados
function loadRelatedTransactions() {
    if (!transaction.is_installment && !transaction.is_recurring) {
        return;
    }
    
    const group = transaction.is_installment ? transaction.installment_group : transaction.recurring_group;
    const type = transaction.is_installment ? 'installment' : 'recurring';
    
    fetch(`/api/financial/transactions/${transaction.id}/related?type=${type}&group=${group}`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('related-transactions');
            
            if (data.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                        <p class="text-gray-500 dark:text-gray-400 mt-2">Nenhuma transação relacionada encontrada</p>
                    </div>
                `;
                return;
            }
            
            container.innerHTML = data.map(t => `
                <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 ${t.type === 'income' ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900'} rounded-full flex items-center justify-center mr-3">
                            <i class="fas ${t.type === 'income' ? 'fa-arrow-up text-green-600' : 'fa-arrow-down text-red-600'}"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">${t.description}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">${formatDate(t.due_date)}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-semibold ${t.type === 'income' ? 'text-green-600' : 'text-red-600'}">
                            R$ ${formatCurrency(t.amount)}
                        </div>
                        <span class="inline-block px-2 py-1 rounded-full text-xs ${
                            t.status === 'paid' 
                                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                        }">
                            ${t.status === 'paid' ? 'Pago' : 'Pendente'}
                        </span>
                    </div>
                    <div class="ml-3">
                        <a href="/financial/transactions/${t.id}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>
            `).join('');
        })
        .catch(error => {
            console.error('Erro ao carregar transações relacionadas:', error);
            document.getElementById('related-transactions').innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
                    <p class="text-red-500 dark:text-red-400 mt-2">Erro ao carregar transações relacionadas</p>
                </div>
            `;
        });
}

function loadTransactionFiles() {
    fetch(`/api/financial/transactions/${transaction.id}/files`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('transaction-files');
            
            if (data.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-file-alt text-gray-400 text-2xl"></i>
                        <p class="text-gray-500 dark:text-gray-400 mt-2">Nenhum arquivo anexado</p>
                    </div>
                `;
                return;
            }
            
            container.innerHTML = data.map(file => `
                <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-3">
                            <i class="fas ${getFileIcon(file.file_type)} text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">${file.original_name}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">${file.description || 'Sem descrição'}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="${file.file_url}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            <i class="fas fa-download"></i>
                        </a>
                        <button onclick="deleteFile(${file.id})" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `).join('');
        })
        .catch(error => {
            console.error('Erro ao carregar arquivos:', error);
            document.getElementById('transaction-files').innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
                    <p class="text-red-500 dark:text-red-400 mt-2">Erro ao carregar arquivos</p>
                </div>
            `;
        });
}

function loadAccountSummary() {
    const accountType = transaction.bank_id ? 'bank' : 'credit_card';
    const accountId = transaction.bank_id || transaction.credit_card_id;
    
    if (!accountId) {
        document.getElementById('account-summary').innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-info-circle text-gray-400 text-xl"></i>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Conta não definida</p>
            </div>
        `;
        return;
    }
    
    fetch(`/api/financial/${accountType}s/${accountId}/summary`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('account-summary');
            
            if (accountType === 'bank') {
                container.innerHTML = `
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Saldo Atual</span>
                            <span class="font-semibold text-gray-900 dark:text-white">R$ ${formatCurrency(data.current_balance)}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Receitas do Mês</span>
                            <span class="font-semibold text-green-600">R$ ${formatCurrency(data.monthly_income)}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Despesas do Mês</span>
                            <span class="font-semibold text-red-600">R$ ${formatCurrency(data.monthly_expenses)}</span>
                        </div>
                    </div>
                `;
            } else {
                container.innerHTML = `
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Limite Total</span>
                            <span class="font-semibold text-gray-900 dark:text-white">R$ ${formatCurrency(data.credit_limit)}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Limite Usado</span>
                            <span class="font-semibold text-red-600">R$ ${formatCurrency(data.used_limit)}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Limite Disponível</span>
                            <span class="font-semibold text-green-600">R$ ${formatCurrency(data.available_limit)}</span>
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Erro ao carregar resumo da conta:', error);
            document.getElementById('account-summary').innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
                    <p class="text-red-500 dark:text-red-400 mt-2">Erro ao carregar resumo</p>
                </div>
            `;
        });
}

function loadCategoryStats() {
    fetch(`/api/financial/categories/${transaction.category_id}/stats`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('category-stats');
            
            container.innerHTML = `
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Total do Mês</span>
                        <span class="font-semibold ${transaction.type === 'income' ? 'text-green-600' : 'text-red-600'}">
                            R$ ${formatCurrency(data.monthly_total)}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Transações do Mês</span>
                        <span class="font-semibold text-gray-900 dark:text-white">${data.monthly_count}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Média por Transação</span>
                        <span class="font-semibold text-gray-900 dark:text-white">R$ ${formatCurrency(data.average_amount)}</span>
                    </div>
                </div>
            `;
        })
        .catch(error => {
            console.error('Erro ao carregar estatísticas da categoria:', error);
            document.getElementById('category-stats').innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
                    <p class="text-red-500 dark:text-red-400 mt-2">Erro ao carregar estatísticas</p>
                </div>
            `;
        });
}

// Ações
function refreshData() {
    loadRelatedTransactions();
    loadTransactionFiles();
    loadAccountSummary();
    loadCategoryStats();
    showToast('Dados atualizados!', 'success');
}

function markAsPaid() {
    if (confirm('Marcar esta transação como paga?')) {
        fetch(`/api/financial/transactions/${transaction.id}/mark-paid`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Transação marcada como paga!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message || 'Erro ao marcar como paga', 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao marcar como paga', 'error');
        });
    }
}

function markAsPending() {
    if (confirm('Marcar esta transação como pendente?')) {
        fetch(`/api/financial/transactions/${transaction.id}/mark-pending`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Transação marcada como pendente!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message || 'Erro ao marcar como pendente', 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao marcar como pendente', 'error');
        });
    }
}

function duplicateTransaction() {
    if (confirm('Duplicar esta transação?')) {
        fetch(`/api/financial/transactions/${transaction.id}/duplicate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Transação duplicada com sucesso!', 'success');
                setTimeout(() => {
                    window.location.href = `/financial/transactions/${data.transaction.id}/edit`;
                }, 1500);
            } else {
                showToast(data.message || 'Erro ao duplicar transação', 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao duplicar transação', 'error');
        });
    }
}

function addNote() {
    const currentNote = '{{ $transaction->notes }}';
    const newNote = prompt('Adicionar/editar nota:', currentNote);
    
    if (newNote !== null) {
        fetch(`/api/financial/transactions/${transaction.id}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ notes: newNote })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Nota atualizada!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message || 'Erro ao atualizar nota', 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao atualizar nota', 'error');
        });
    }
}

// Upload de arquivos
function uploadFile() {
    document.getElementById('upload-modal').classList.remove('hidden');
}

function closeUploadModal() {
    document.getElementById('upload-modal').classList.add('hidden');
    document.getElementById('upload-form').reset();
}

function setupUploadForm() {
    document.getElementById('upload-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Debug do transaction_id
        console.log('Transaction ID original:', transaction.id);
        console.log('Tipo do transaction.id:', typeof transaction.id);
        console.log('Transaction ID convertido para int:', parseInt(transaction.id));
        console.log('Tipo após parseInt:', typeof parseInt(transaction.id));
        
        // Garantir que seja um número inteiro
        const transactionIdInt = parseInt(transaction.id);
        formData.append('transaction_id', transactionIdInt);
        
        console.log('FormData transaction_id:', formData.get('transaction_id'));
        console.log('Tipo do FormData transaction_id:', typeof formData.get('transaction_id'));
        
        fetch('/api/financial/transaction-files', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Arquivo enviado com sucesso!', 'success');
                closeUploadModal();
                loadTransactionFiles();
            } else {
                showToast(data.message || 'Erro ao enviar arquivo', 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao enviar arquivo', 'error');
        });
    });
}

function deleteFile(fileId) {
    if (confirm('Excluir este arquivo?')) {
        fetch(`/api/financial/transaction-files/${fileId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Arquivo excluído!', 'success');
                loadTransactionFiles();
            } else {
                showToast(data.message || 'Erro ao excluir arquivo', 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao excluir arquivo', 'error');
        });
    }
}

// Utilitários
function formatCurrency(value) {
    return parseFloat(value).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('pt-BR');
}

function getFileIcon(fileType) {
    const icons = {
        'pdf': 'fa-file-pdf',
        'doc': 'fa-file-word',
        'docx': 'fa-file-word',
        'xls': 'fa-file-excel',
        'xlsx': 'fa-file-excel',
        'jpg': 'fa-file-image',
        'jpeg': 'fa-file-image',
        'png': 'fa-file-image',
        'gif': 'fa-file-image',
        'txt': 'fa-file-alt',
        'zip': 'fa-file-archive',
        'rar': 'fa-file-archive'
    };
    
    return icons[fileType.toLowerCase()] || 'fa-file';
}

function showToast(message, type = 'info') {
    // Implementar sistema de toast
    console.log(`${type.toUpperCase()}: ${message}`);
    alert(message); // Temporário
}
</script>

<!-- Incluir o componente de upload de arquivos -->
<script src="{{ asset('js/file-upload.js') }}"></script>
@endsection