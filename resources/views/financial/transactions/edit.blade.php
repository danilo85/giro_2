@extends('layouts.app')

@section('title', 'Editar Transação')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Transação</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Edite os dados da transação</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('financial.transactions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-md transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulário -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Dados da Transação</h3>
                </div>
                <form id="transaction-form" action="{{ route('financial.transactions.update', $transaction->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')
                
                <!-- Tipo de Transação -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Tipo de Transação</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative">
                            <input type="radio" id="income-radio" name="tipo" value="receita" class="sr-only" {{ $transaction->tipo === 'receita' ? 'checked' : '' }}>
                            <div class="type-option border-2 border-gray-300 dark:border-gray-600 rounded-lg p-4 cursor-pointer transition-all hover:border-green-500">
                                <div class="flex items-center justify-center mb-2">
                                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                        <i class="fas fa-arrow-up text-green-600 dark:text-green-400 text-xl"></i>
                                    </div>
                                </div>
                                <h3 class="text-center font-medium text-gray-900 dark:text-white">Receita</h3>
                                <p class="text-center text-sm text-gray-600 dark:text-gray-400">Dinheiro que entra</p>
                            </div>
                        </label>
                        <label class="relative">
                            <input type="radio" id="expense-radio" name="tipo" value="despesa" class="sr-only" {{ $transaction->tipo === 'despesa' ? 'checked' : '' }}>
                            <div class="type-option border-2 border-gray-300 dark:border-gray-600 rounded-lg p-4 cursor-pointer transition-all hover:border-red-500">
                                <div class="flex items-center justify-center mb-2">
                                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                                        <i class="fas fa-arrow-down text-red-600 dark:text-red-400 text-xl"></i>
                                    </div>
                                </div>
                                <h3 class="text-center font-medium text-gray-900 dark:text-white">Despesa</h3>
                                <p class="text-center text-sm text-gray-600 dark:text-gray-400">Dinheiro que sai</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Descrição -->
                <div class="mb-4">
                    <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descrição</label>
                    <input type="text" id="descricao" name="descricao" value="{{ old('descricao', $transaction->descricao) }}"
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Ex: Salário, Aluguel, Compras...">
                </div>

                <!-- Valor -->
                <div class="mb-4">
                    <label for="valor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Valor *</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500 dark:text-gray-400">R$</span>
                        <input type="text" id="valor" name="valor" value="{{ old('valor', number_format($transaction->valor, 2, ',', '.')) }}" required
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-lg pl-10 pr-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="0,00">
                    </div>
                </div>

                <!-- Categoria -->
                <div class="mb-4">
                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Categoria *</label>
                    <select id="category_id" name="category_id" required
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Selecione uma categoria</option>
                    </select>
                </div>

                <!-- Conta/Cartão -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Conta/Cartão *</label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Conta Bancária</label>
                            <select id="bank_id" name="bank_id"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Selecione um banco</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Cartão de Crédito</label>
                            <select id="credit_card_id" name="credit_card_id"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Selecione um cartão</option>
                            </select>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Selecione apenas uma opção</p>
                </div>

                <!-- Data de Vencimento -->
                <div class="mb-4">
                    <label for="data" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Data de Vencimento *</label>
                    <input type="date" id="data" name="data" value="{{ old('data', $transaction->data?->format('Y-m-d')) }}" required
                           class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative">
                            <input type="radio" name="status" value="pendente" class="sr-only" {{ $transaction->status === 'pendente' ? 'checked' : '' }}>
                            <div class="status-option border-2 border-gray-300 dark:border-gray-600 rounded-lg p-3 cursor-pointer transition-all hover:border-yellow-500">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-clock text-yellow-600 dark:text-yellow-400"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white">Pendente</h4>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">Aguardando pagamento</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <label class="relative">
                            <input type="radio" name="status" value="pago" class="sr-only" {{ $transaction->status === 'pago' ? 'checked' : '' }}>
                            <div class="status-option border-2 border-gray-300 dark:border-gray-600 rounded-lg p-3 cursor-pointer transition-all hover:border-green-500">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-check text-green-600 dark:text-green-400"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white">Pago</h4>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">Já foi pago</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Opções de Parcelamento e Recorrência -->
                <div class="mb-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Parcelamento -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-credit-card text-blue-500 mr-2"></i>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Parcelamento</h4>
                        </div>
                        <label class="flex items-center mb-3">
                            <input type="checkbox" id="is_installment" name="is_installment" {{ old('is_installment', $transaction->is_installment) ? 'checked' : '' }} class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Parcelar esta transação</span>
                        </label>
                        <div id="installment-options" class="space-y-3 hidden">
                            <div>
                                <label for="installments" class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Número de parcelas</label>
                                <input type="number" id="installments" name="installments" min="2" max="60" value="{{ old('installments', $transaction->installments ?? 2) }}"
                                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="installment_value" class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Valor por parcela</label>
                                <input type="text" id="installment_value" name="installment_value" value="{{ old('installment_value', $transaction->installment_value ? number_format($transaction->installment_value, 2, ',', '.') : '') }}" readonly
                                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-gray-100 dark:bg-gray-600 text-gray-900 dark:text-white">
                            </div>
                        </div>
                    </div>

                    <!-- Recorrência -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-sync-alt text-green-500 mr-2"></i>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Recorrência</h4>
                        </div>
                        <label class="flex items-center mb-3">
                            <input type="checkbox" id="is_recurring" name="is_recurring" value="1"
                                @if($transaction->is_recurring == 1 || $transaction->is_recurring === true) checked @endif
                                class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Transação recorrente</span>
                        </label>
                        <!-- Campo frequency_type para compatibilidade com o badge -->
                        <input type="hidden" id="frequency_type" name="frequency_type" value="{{ old('frequency_type', $transaction->frequency_type ?? ($transaction->is_recurring ? 'recorrente' : 'unica')) }}">
                        <div id="recurring-options" class="space-y-3 hidden">
                            <div>
                                <label for="recurring_type" class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Frequência</label>
                                <select id="recurring_type" name="recurring_type"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="monthly" {{ old('recurring_type', $transaction->recurring_type) === 'monthly' ? 'selected' : '' }}>Mensal</option>
                                    <option value="weekly" {{ old('recurring_type', $transaction->recurring_type) === 'weekly' ? 'selected' : '' }}>Semanal</option>
                                    <option value="yearly" {{ old('recurring_type', $transaction->recurring_type) === 'yearly' ? 'selected' : '' }}>Anual</option>
                                </select>
                            </div>
                            <div>
                                <label for="recurring_end_date" class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Data final (opcional)</label>
                                <input type="date" id="recurring_end_date" name="recurring_end_date" value="{{ old('recurring_end_date', $transaction->recurring_end_date?->format('Y-m-d')) }}"
                                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Observações -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Observações</label>
                    <textarea id="notes" name="notes" rows="3"
                              class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Informações adicionais sobre esta transação...">{{ old('notes', $transaction->notes) }}</textarea>
                </div>

                <!-- Upload de Arquivos -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        <i class="fas fa-paperclip mr-2 text-blue-500"></i>
                        Arquivos Anexos
                    </label>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <div id="file-upload-container" data-transaction-id="{{ $transaction->id }}">
                            <!-- O componente será inicializado automaticamente pelo JavaScript -->
                        </div>
                        <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                            <i class="fas fa-info-circle mr-1"></i>
                            Formatos aceitos: PDF, JPG, JPEG, PNG • Tamanho máximo: 10MB por arquivo
                        </div>
                    </div>
                </div>

                </form>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-center sm:justify-end gap-3 mt-6">
                <button type="button" onclick="window.location.href='{{ route('financial.transactions.index') }}'" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-md transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </button>
                <button type="submit" form="transaction-form" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Atualizar Transação
                </button>
            </div>
        </div>

        <!-- Preview Section -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 sticky top-6">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Preview da Transação</h3>
                </div>
                
                <div class="p-6">
                    <div id="transaction-preview" class="space-y-4">
                        <!-- Preview Content -->
                        <div class="border-l-4 border-gray-300 p-4 bg-gray-50 dark:bg-gray-700 rounded">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 id="preview-type" class="text-lg font-bold text-gray-500">Tipo não definido</h4>
                                    <p id="preview-description" class="text-sm text-gray-600 dark:text-gray-400">Descrição não informada</p>
                                </div>
                                <div class="text-right">
                                    <p id="preview-amount" class="text-xl font-bold text-gray-900 dark:text-white">R$ 0,00</p>
                                    <p id="preview-status" class="text-xs text-gray-500">Status não definido</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Categoria:</span>
                                    <p id="preview-category" class="font-medium text-gray-900 dark:text-white">Não informada</p>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Conta/Cartão:</span>
                                    <p id="preview-account" class="font-medium text-gray-900 dark:text-white">Não informada</p>
                                </div>
                                <div>
                                    <span class="text-gray-500 dark:text-gray-400">Data:</span>
                                    <p id="preview-date" class="font-medium text-gray-900 dark:text-white">Não informada</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Empty State (hidden by default) -->
                        <div id="preview-empty" class="text-center text-gray-500 dark:text-gray-400 py-8 hidden">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <p class="text-sm">Preencha os campos para ver o preview</p>
                        </div>
                    </div>
                </div>
                
                <!-- Tips Section -->
                <div class="px-6 pb-6">
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                            Dicas
                        </h4>
                        <ul class="text-xs text-blue-800 dark:text-blue-200 space-y-2">
                            <li class="flex items-start">
                                <span class="w-1 h-1 bg-blue-600 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                Use categorias para organizar suas transações
                            </li>
                            <li class="flex items-start">
                                <span class="w-1 h-1 bg-blue-600 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                Parcele compras grandes para melhor controle
                            </li>
                            <li class="flex items-start">
                                <span class="w-1 h-1 bg-blue-600 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                Configure recorrência para gastos fixos
                            </li>
                            <li class="flex items-start">
                                <span class="w-1 h-1 bg-blue-600 rounded-full mt-2 mr-2 flex-shrink-0"></span>
                                Anexe comprovantes quando necessário
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@push('scripts')
<script src="https://unpkg.com/imask"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Funções de inicialização e event listeners
    setupEventListeners();
    initializeForm();
    loadCategories();
    loadBanks();
    loadCreditCards();
    updatePreview();

    // Configurar máscara de valor (sem cifrão)
    const valorInput = document.getElementById('valor');
    if (valorInput) {
        IMask(valorInput, {
            mask: Number,
            thousandsSeparator: '.',
            radix: ',',
            scale: 2,
            padFractionalZeros: true
        });
    }
    
    // Preencher data de vencimento com hoje se não houver valor
    const dataInput = document.getElementById('data');
    if (dataInput && !dataInput.value) {
        const today = new Date();
        const formattedDate = today.toISOString().split('T')[0];
        dataInput.value = formattedDate;
    }
});

function setupEventListeners() {
    // Adicionar event listeners para todos os campos relevantes
    const fieldsToListen = [
        'descricao', 'valor', 'data', 'category_id',
        'bank_id', 'credit_card_id', 'notes'
    ];
    
    fieldsToListen.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', updatePreview);
            element.addEventListener('change', updatePreview);
        }
    });

    // Listeners para tipo de transação com feedback visual
    document.querySelectorAll('input[name="tipo"]').forEach(radio => {
        radio.addEventListener('change', function() {
            updateTypeSelection();
            updatePreview();
        });
    });

    // Listeners para status com feedback visual
    document.querySelectorAll('input[name="status"]').forEach(radio => {
        radio.addEventListener('change', function() {
            updateStatusSelection();
            updatePreview();
        });
    });

    // Listeners para clique nas opções visuais de tipo
    document.querySelectorAll('.type-option').forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.parentElement.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
                updateTypeSelection();
                updatePreview();
            }
        });
    });

    // Listeners para clique nas opções visuais de status
    document.querySelectorAll('.status-option').forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.parentElement.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
                updateStatusSelection();
                updatePreview();
            }
        });
    });

    // Listener para o formulário
    const transactionForm = document.getElementById('transaction-form');
    if (transactionForm) {
        transactionForm.addEventListener('submit', handleFormSubmit);
    }

    // Listener para o botão de salvar
    const saveButton = document.getElementById('save-button');
    if (saveButton) {
        saveButton.addEventListener('click', () => {
            if (validateForm()) {
                transactionForm.dispatchEvent(new Event('submit', { cancelable: true }));
            }
        });
    }

    // Event listener para checkbox de parcelamento
    const installmentCheckbox = document.getElementById('is_installment');
    if (installmentCheckbox) {
        installmentCheckbox.addEventListener('change', function() {
            const installmentOptions = document.getElementById('installment-options');
            if (installmentOptions) {
                if (this.checked) {
                    installmentOptions.classList.remove('hidden');
                } else {
                    installmentOptions.classList.add('hidden');
                    // Limpar campos quando desmarcar
                    const installmentsInput = document.getElementById('installments');
                    const installmentValueInput = document.getElementById('installment_value');
                    if (installmentsInput) installmentsInput.value = '';
                    if (installmentValueInput) installmentValueInput.value = '';
                }
            }
        });
    }

    // Event listener para checkbox de recorrência
    const recurringCheckbox = document.getElementById('is_recurring');
    if (recurringCheckbox) {
        recurringCheckbox.addEventListener('change', function() {
            const recurringOptions = document.getElementById('recurring-options');
            const frequencyTypeField = document.getElementById('frequency_type');
            
            if (recurringOptions) {
                if (this.checked) {
                    recurringOptions.classList.remove('hidden');
                    // Atualizar frequency_type para 'recorrente'
                    if (frequencyTypeField) frequencyTypeField.value = 'recorrente';
                } else {
                    recurringOptions.classList.add('hidden');
                    // Atualizar frequency_type para 'unica'
                    if (frequencyTypeField) frequencyTypeField.value = 'unica';
                    // Limpar campos quando desmarcar
                    const frequencySelect = document.getElementById('frequency');
                    const endDateInput = document.getElementById('end_date');
                    if (frequencySelect) frequencySelect.value = '';
                    if (endDateInput) endDateInput.value = '';
                }
            }
        });
    }

    // Event listener para cálculo automático do valor por parcela
    const installmentsInput = document.getElementById('installments');
    const valorInput = document.getElementById('valor');
    if (installmentsInput && valorInput) {
        function calculateInstallmentValue() {
            const totalAmount = valorInput.value.replace(/[^\d,]/g, '').replace(',', '.');
            const installments = parseInt(installmentsInput.value);
            const installmentValueInput = document.getElementById('installment_value');
            
            if (installmentValueInput && totalAmount && installments > 0) {
                const installmentValue = (parseFloat(totalAmount) / installments).toFixed(2);
                const formattedValue = installmentValue.replace('.', ',');
                installmentValueInput.value = `${formattedValue}`;
            }
        }
        
        installmentsInput.addEventListener('input', calculateInstallmentValue);
        valorInput.addEventListener('input', calculateInstallmentValue);
    }
}

function initializeForm() {
    const urlParams = new URLSearchParams(window.location.search);
    const type = urlParams.get('type');
    
    const incomeRadio = document.getElementById('income-radio');
    const expenseRadio = document.getElementById('expense-radio');
    if (type === 'income' && incomeRadio) {
        incomeRadio.checked = true;
    } else if (type === 'expense' && expenseRadio) {
        expenseRadio.checked = true;
    }

    // Definir status padrão como 'pendente' apenas se não houver transação
    @if(!isset($transaction))
    const pendingStatus = document.querySelector('input[name="status"][value="pending"]');
    if (pendingStatus) {
        pendingStatus.checked = true;
    }
    @endif

    // Mostrar seções de parcelamento e recorrência se estiverem marcadas
    const installmentCheckbox = document.getElementById('is_installment');
    const recurringCheckbox = document.getElementById('is_recurring');
    
    if (installmentCheckbox && installmentCheckbox.checked) {
        const installmentOptions = document.getElementById('installment-options');
        if (installmentOptions) {
            installmentOptions.classList.remove('hidden');
        }
    }
    
    if (recurringCheckbox && recurringCheckbox.checked) {
        const recurringOptions = document.getElementById('recurring-options');
        if (recurringOptions) {
            recurringOptions.classList.remove('hidden');
        }
    }

    // Atualizar seleções visuais
    updateTypeSelection();
    updateStatusSelection();
}

// Função para carregar categorias via AJAX
function loadCategories() {
    fetch('/api/financial/categories', {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro ao carregar categorias');
        }
        return response.json();
    })
    .then(data => {
        const categorySelect = document.getElementById('category_id');
        if (categorySelect && data && (data.receitas || data.despesas)) {
            categorySelect.innerHTML = '<option value="">Selecione uma categoria</option>';
            
            // Adicionar receitas
            if (data.receitas && Array.isArray(data.receitas)) {
                const receitasGroup = document.createElement('optgroup');
                receitasGroup.label = 'Receitas';
                data.receitas.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.nome;
                    // Selecionar categoria da transação se existir
                    @if(isset($transaction) && $transaction->category_id)
                    if (category.id == {{ $transaction->category_id }}) {
                        option.selected = true;
                    }
                    @endif
                    receitasGroup.appendChild(option);
                });
                categorySelect.appendChild(receitasGroup);
            }
            
            // Adicionar despesas
            if (data.despesas && Array.isArray(data.despesas)) {
                const despesasGroup = document.createElement('optgroup');
                despesasGroup.label = 'Despesas';
                data.despesas.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.nome;
                    // Selecionar categoria da transação se existir
                    @if(isset($transaction) && $transaction->category_id)
                    if (category.id == {{ $transaction->category_id }}) {
                        option.selected = true;
                    }
                    @endif
                    despesasGroup.appendChild(option);
                });
                categorySelect.appendChild(despesasGroup);
            }
        }
    })
    .catch(error => {
        console.error('Erro ao carregar categorias:', error);
        showToast('Erro ao carregar categorias', 'error');
    });
}

// Função para carregar bancos via AJAX
function loadBanks() {
    fetch('/api/financial/banks', {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro ao carregar bancos');
        }
        return response.json();
    })
    .then(data => {
        const bankSelect = document.getElementById('bank_id');
        if (bankSelect && Array.isArray(data)) {
            bankSelect.innerHTML = '<option value="">Selecione um banco</option>';
            data.forEach(bank => {
                const option = document.createElement('option');
                option.value = bank.id;
                option.textContent = bank.nome;
                // Selecionar banco da transação se existir
                @if(isset($transaction) && $transaction->bank_id)
                if (bank.id == {{ $transaction->bank_id }}) {
                    option.selected = true;
                }
                @endif
                bankSelect.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error('Erro ao carregar bancos:', error);
        showToast('Erro ao carregar bancos', 'error');
    });
}

// Função para carregar cartões de crédito via AJAX
function loadCreditCards() {
    fetch('/api/financial/credit-cards', {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro ao carregar cartões');
        }
        return response.json();
    })
    .then(data => {
        const cardSelect = document.getElementById('credit_card_id');
        if (cardSelect && Array.isArray(data)) {
            cardSelect.innerHTML = '<option value="">Selecione um cartão</option>';
            data.forEach(card => {
                const option = document.createElement('option');
                option.value = card.id;
                option.textContent = card.nome_cartao;
                // Selecionar cartão da transação se existir
                @if(isset($transaction) && $transaction->credit_card_id)
                if (card.id == {{ $transaction->credit_card_id }}) {
                    option.selected = true;
                }
                @endif
                cardSelect.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error('Erro ao carregar cartões:', error);
        showToast('Erro ao carregar cartões de crédito', 'error');
    });
}

// Função para atualizar seleção visual do tipo
function updateTypeSelection() {
    const typeOptions = document.querySelectorAll('.type-option');
    const checkedType = document.querySelector('input[name="type"]:checked');
    
    typeOptions.forEach(option => {
        const radio = option.parentElement.querySelector('input[type="radio"]');
        if (radio && radio.checked) {
            if (radio.value === 'receita') {
                option.classList.remove('border-gray-300', 'dark:border-gray-600');
                option.classList.add('border-green-500', 'bg-green-50', 'dark:bg-green-900/20');
            } else {
                option.classList.remove('border-gray-300', 'dark:border-gray-600');
                option.classList.add('border-red-500', 'bg-red-50', 'dark:bg-red-900/20');
            }
        } else {
            option.classList.remove('border-green-500', 'border-red-500', 'bg-green-50', 'bg-red-50', 'dark:bg-green-900/20', 'dark:bg-red-900/20');
            option.classList.add('border-gray-300', 'dark:border-gray-600');
        }
    });
}

// Função para atualizar seleção visual do status
function updateStatusSelection() {
    const statusOptions = document.querySelectorAll('.status-option');
    const checkedStatus = document.querySelector('input[name="status"]:checked');
    
    statusOptions.forEach(option => {
        const radio = option.parentElement.querySelector('input[type="radio"]');
        if (radio && radio.checked) {
            if (radio.value === 'pendente') {
                option.classList.remove('border-gray-300', 'dark:border-gray-600');
                option.classList.add('border-yellow-500', 'bg-yellow-50', 'dark:bg-yellow-900/20');
            } else {
                option.classList.remove('border-gray-300', 'dark:border-gray-600');
                option.classList.add('border-green-500', 'bg-green-50', 'dark:bg-green-900/20');
            }
        } else {
            option.classList.remove('border-yellow-500', 'border-green-500', 'bg-yellow-50', 'bg-green-50', 'dark:bg-yellow-900/20', 'dark:bg-green-900/20');
            option.classList.add('border-gray-300', 'dark:border-gray-600');
        }
    });
}

// Função toggleAdvanced() removida - não é mais necessária

function updatePreview() {
    // Atualizar tipo (Receita/Despesa)
    const typeChecked = document.querySelector('input[name="tipo"]:checked');
    const previewType = document.getElementById('preview-type');
    const previewContainer = document.querySelector('#transaction-preview .border-l-4');
    
    if (previewType) {
        if (typeChecked) {
            const isIncome = typeChecked.value === 'receita';
            previewType.textContent = isIncome ? 'Receita' : 'Despesa';
            previewType.className = `text-lg font-bold ${isIncome ? 'text-green-600' : 'text-red-600'}`;
            
            // Atualizar cor da borda do preview
            if (previewContainer) {
                previewContainer.className = previewContainer.className.replace(/border-l-(green|red|gray)-\d+/, '');
                previewContainer.classList.add(isIncome ? 'border-l-green-500' : 'border-l-red-500');
            }
        } else {
            previewType.textContent = 'Tipo não definido';
            previewType.className = 'text-lg font-bold text-gray-500';
            if (previewContainer) {
                previewContainer.className = previewContainer.className.replace(/border-l-(green|red)-\d+/, '');
                previewContainer.classList.add('border-l-gray-300');
            }
        }
    }

    // Atualizar descrição
    const descricaoElement = document.getElementById('descricao');
    const description = descricaoElement ? descricaoElement.value : '';
    const previewDescription = document.getElementById('preview-description');
    if (previewDescription) {
        previewDescription.textContent = description || 'Não informado';
    }

    // Atualizar valor
    const valorElement = document.getElementById('valor');
    const amount = valorElement ? valorElement.value : '';
    const previewAmount = document.getElementById('preview-amount');
    if (previewAmount) {
        previewAmount.textContent = amount || 'R$ 0,00';
    }

    // Atualizar data
    const dataElement = document.getElementById('data');
    const dueDate = dataElement ? dataElement.value : '';
    const previewDate = document.getElementById('preview-date');
    if (previewDate) {
        previewDate.textContent = dueDate ? new Date(dueDate).toLocaleDateString('pt-BR') : 'Não informada';
    }

    // Atualizar categoria
    const categorySelect = document.getElementById('category_id');
    const previewCategory = document.getElementById('preview-category');
    if (categorySelect && previewCategory) {
        const selectedCategory = categorySelect.options[categorySelect.selectedIndex];
        previewCategory.textContent = selectedCategory ? selectedCategory.text : 'Não informada';
    }

    // Atualizar conta/cartão
    const bankSelect = document.getElementById('bank_id');
    const cardSelect = document.getElementById('credit_card_id');
    const previewAccount = document.getElementById('preview-account');
    if (previewAccount) {
        let accountName = 'Não informada';
        if (bankSelect && bankSelect.value) {
            accountName = bankSelect.options[bankSelect.selectedIndex].text;
        } else if (cardSelect && cardSelect.value) {
            accountName = cardSelect.options[cardSelect.selectedIndex].text;
        }
        previewAccount.textContent = accountName;
    }

    // Atualizar status
    const statusChecked = document.querySelector('input[name="status"]:checked');
    const previewStatus = document.getElementById('preview-status');
    if (previewStatus) {
        previewStatus.textContent = statusChecked ? (statusChecked.value === 'pago' ? 'Pago' : 'Pendente') : 'Não definido';
    }
}

function handleFormSubmit(event) {
    event.preventDefault();
    
    if (!validateForm()) {
        return;
    }

    // Debug: verificar valor original e processado
    const valorElement = document.getElementById('valor');
    const originalAmount = valorElement ? valorElement.value : '';
    const processedAmount = originalAmount.replace(/[^\d,]/g, '').replace(',', '.');
    
    console.log('Debug - Valor original:', originalAmount);
    console.log('Debug - Valor processado:', processedAmount);
    console.log('Debug - Valor como número:', parseFloat(processedAmount));

    // Obter valores do frontend (já estão corretos)
    const typeValue = document.querySelector('input[name="tipo"]:checked')?.value;
    const statusValue = document.querySelector('input[name="status"]:checked')?.value;
    
    console.log('Debug - Tipo:', typeValue);
    console.log('Debug - Status:', statusValue);

    // Verificar se parcelamento ou recorrência estão marcados
    const isInstallmentChecked = document.getElementById('is_installment')?.checked;
    const isRecurringChecked = document.getElementById('is_recurring')?.checked;
    
    // Obter o tipo de frequência correto
    let frequencyType = 'unica';
    if (isInstallmentChecked) {
        frequencyType = 'parcelada';
    } else if (isRecurringChecked) {
        frequencyType = 'recorrente';
    }
    
    // Criar objeto com dados do formulário (usando nomes corretos do backend)
    const descricaoElement = document.getElementById('descricao');
    const categoryElement = document.getElementById('category_id');
    const bankElement = document.getElementById('bank_id');
    const cardElement = document.getElementById('credit_card_id');
    const dataElement = document.getElementById('data');
    
    const formData = {
        descricao: descricaoElement ? descricaoElement.value.trim() : '',
        valor: processedAmount,
        tipo: typeValue,
        category_id: categoryElement ? categoryElement.value : '',
        bank_id: bankElement ? bankElement.value || null : null,
        credit_card_id: cardElement ? cardElement.value || null : null,
        data: dataElement ? dataElement.value : '',
        status: statusValue,
        installment_count: document.getElementById('installments')?.value || null,
        frequency_type: frequencyType,
        is_recurring: isRecurringChecked ? 1 : 0,
        is_installment: isInstallmentChecked ? 1 : 0,
        observacoes: document.getElementById('notes')?.value || null,
        _method: 'PUT'
    };
    
    // Adicionar recurring_type se recorrência estiver marcada
    if (isRecurringChecked) {
        const recurringTypeSelect = document.getElementById('recurring_type');
        if (recurringTypeSelect && recurringTypeSelect.value) {
            formData.recurring_type = recurringTypeSelect.value;
        }
        
        const recurringEndDate = document.getElementById('recurring_end_date');
        if (recurringEndDate && recurringEndDate.value) {
            formData.recurring_end_date = recurringEndDate.value;
        }
    }
    
    console.log('Debug - FormData completo:', formData);
    
    fetch('/api/financial/transactions/{{ $transaction->id }}', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success || data.message) {
            showToast(data.message || 'Transação atualizada com sucesso!', 'success');
            setTimeout(() => {
                window.location.href = '/financial/transactions';
            }, 1500);
        } else {
            showToast('Erro ao atualizar transação', 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        if (error.errors) {
            // Mostrar erros de validação específicos
            const firstError = Object.values(error.errors)[0][0];
            showToast(firstError, 'error');
        } else {
            showToast(error.message || 'Dados inválidos. Verifique os campos obrigatórios.', 'error');
        }
    });
}

function validateForm() {
    const type = document.querySelector('input[name="tipo"]:checked');
    const valorElement = document.getElementById('valor');
    const amount = valorElement ? valorElement.value.trim() : '';
    const categoryElement = document.getElementById('category_id');
    const categoryId = categoryElement ? categoryElement.value : '';
    const bankElement = document.getElementById('bank_id');
    const bankId = bankElement ? bankElement.value : '';
    const cardElement = document.getElementById('credit_card_id');
    const cardId = cardElement ? cardElement.value : '';
    const dataElement = document.getElementById('data');
    const dueDate = dataElement ? dataElement.value : '';
    
    console.log('Debug - Validação:');
    console.log('- Tipo selecionado:', type?.value);
    console.log('- Valor:', amount);
    console.log('- Categoria:', categoryId);
    console.log('- Banco:', bankId);
    console.log('- Cartão:', cardId);
    console.log('- Data:', dueDate);
    
    if (!type) {
        showToast('Selecione o tipo de transação', 'error');
        return false;
    }
    
    if (!amount || parseFloat(amount.replace(/[^\d,]/g, '').replace(',', '.')) <= 0) {
        showToast('Informe um valor válido', 'error');
        return false;
    }
    
    if (!categoryId) {
        showToast('Selecione uma categoria', 'error');
        return false;
    }
    
    if (!bankId && !cardId) {
        showToast('Selecione uma conta bancária ou cartão de crédito', 'error');
        return false;
    }
    
    if (!dueDate) {
        showToast('Informe a data de vencimento', 'error');
        return false;
    }
    
    return true;
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${
                type === 'success' ? 'fa-check-circle' : 
                type === 'error' ? 'fa-exclamation-circle' : 
                'fa-info-circle'
            } mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}
</script>

<!-- Incluir o componente de upload de arquivos -->
<script src="{{ asset('js/file-upload.js') }}"></script>
@endpush