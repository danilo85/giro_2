@extends('layouts.app')

@section('title', 'Editar Pagamento')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Editar Pagamento</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Atualize as informações do pagamento</p>
                </div>
            </div>
            <a href="{{ route('pagamentos.index') }}" 
               class="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
            </a>
        </div>
    </div>
    
    <!-- Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulário Principal -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Informações do Pagamento</h2>
                    
                    <form method="POST" action="{{ route('pagamentos.update', $pagamento) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Orçamento -->
            <div>
                <label for="orcamento_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Orçamento <span class="text-red-500">*</span>
                </label>
                <select id="orcamento_id" 
                        name="orcamento_id" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('orcamento_id') border-red-500 @enderror">
                    <option value="">Selecione um orçamento</option>
                    @foreach($orcamentos as $orcamento)
                        <option value="{{ $orcamento->id }}" 
                                {{ old('orcamento_id', $pagamento->orcamento_id) == $orcamento->id ? 'selected' : '' }}
                                data-valor="{{ $orcamento->valor_total }}"
                                data-pago="{{ $orcamento->pagamentos->sum('valor') }}"
                                data-saldo="{{ $orcamento->valor_total - $orcamento->pagamentos->sum('valor') }}">
                            {{ $orcamento->titulo }} - {{ $orcamento->cliente->nome }} 
                            (Saldo: R$ {{ number_format($orcamento->valor_total - $orcamento->pagamentos->sum('valor'), 2, ',', '.') }})
                        </option>
                    @endforeach
                </select>
                @error('orcamento_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Informações do Orçamento Selecionado -->
            <div id="orcamento-info" class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <h3 class="text-sm font-medium text-blue-900 dark:text-blue-300 mb-2">Informações do Orçamento</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-blue-700 dark:text-blue-400">Valor Total:</span>
                        <span id="valor-total" class="font-medium text-blue-900 dark:text-blue-300">R$ 0,00</span>
                    </div>
                    <div>
                        <span class="text-blue-700 dark:text-blue-400">Total Pago:</span>
                        <span id="total-pago" class="font-medium text-blue-900 dark:text-blue-300">R$ 0,00</span>
                    </div>
                    <div>
                        <span class="text-blue-700 dark:text-blue-400">Saldo Restante:</span>
                        <span id="saldo-restante" class="font-medium text-blue-900 dark:text-blue-300">R$ 0,00</span>
                    </div>
                </div>
            </div>
            
            <!-- Banco -->
            <div>
                <label for="bank_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Conta Bancária <span class="text-red-500">*</span>
                </label>
                <select id="bank_id" 
                        name="bank_id" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('bank_id') border-red-500 @enderror">
                    <option value="">Selecione uma conta bancária</option>
                    @foreach($bancos as $banco)
                        <option value="{{ $banco->id }}" 
                                {{ old('bank_id', $pagamento->bank_id) == $banco->id ? 'selected' : '' }}>
                            {{ $banco->nome }} - {{ $banco->tipo_conta }} 
                            (Saldo: R$ {{ number_format($banco->saldo_atual, 2, ',', '.') }})
                        </option>
                    @endforeach
                </select>
                @error('bank_id')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Valor -->
                <div>
                    <label for="valor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Valor <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400">R$</span>
                        <input type="number" 
                               id="valor" 
                               name="valor" 
                               step="0.01" 
                               min="0.01"
                               value="{{ old('valor', $pagamento->valor) }}"
                               required
                               placeholder="0,00"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('valor') border-red-500 @enderror">
                    </div>
                    @error('valor')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Data do Pagamento -->
                <div>
                    <label for="data_pagamento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Data do Pagamento <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="data_pagamento" 
                           name="data_pagamento" 
                           value="{{ old('data_pagamento', $pagamento->data_pagamento->format('Y-m-d')) }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('data_pagamento') border-red-500 @enderror">
                    @error('data_pagamento')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Forma de Pagamento -->
                <div>
                    <label for="forma_pagamento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Forma de Pagamento <span class="text-red-500">*</span>
                    </label>
                    <select id="forma_pagamento" 
                            name="forma_pagamento" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('forma_pagamento') border-red-500 @enderror">
                        <option value="">Selecione a forma de pagamento</option>
                        <option value="dinheiro" {{ old('forma_pagamento', $pagamento->forma_pagamento) == 'dinheiro' ? 'selected' : '' }}>Dinheiro</option>
                        <option value="pix" {{ old('forma_pagamento', $pagamento->forma_pagamento) == 'pix' ? 'selected' : '' }}>PIX</option>
                        <option value="cartao_credito" {{ old('forma_pagamento', $pagamento->forma_pagamento) == 'cartao_credito' ? 'selected' : '' }}>Cartão de Crédito</option>
                        <option value="cartao_debito" {{ old('forma_pagamento', $pagamento->forma_pagamento) == 'cartao_debito' ? 'selected' : '' }}>Cartão de Débito</option>
                        <option value="transferencia" {{ old('forma_pagamento', $pagamento->forma_pagamento) == 'transferencia' ? 'selected' : '' }}>Transferência Bancária</option>
                        <option value="boleto" {{ old('forma_pagamento', $pagamento->forma_pagamento) == 'boleto' ? 'selected' : '' }}>Boleto Bancário</option>
                        <option value="cheque" {{ old('forma_pagamento', $pagamento->forma_pagamento) == 'cheque' ? 'selected' : '' }}>Cheque</option>
                        <option value="outros" {{ old('forma_pagamento', $pagamento->forma_pagamento) == 'outros' ? 'selected' : '' }}>Outros</option>
                    </select>
                    @error('forma_pagamento')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" 
                            name="status" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('status') border-red-500 @enderror">
                        <option value="">Selecione o status</option>
                        <option value="pendente" {{ old('status', $pagamento->status) == 'pendente' ? 'selected' : '' }}>Pendente</option>
                        <option value="processando" {{ old('status', $pagamento->status) == 'processando' ? 'selected' : '' }}>Processando</option>
                        <option value="confirmado" {{ old('status', $pagamento->status) == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                        <option value="cancelado" {{ old('status', $pagamento->status) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Observações -->
            <div>
                <label for="observacoes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Observações
                </label>
                <textarea id="observacoes" 
                          name="observacoes" 
                          rows="4"
                          placeholder="Informações adicionais sobre o pagamento..."
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('observacoes') border-red-500 @enderror">{{ old('observacoes', $pagamento->observacoes) }}</textarea>
                @error('observacoes')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Botões -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('pagamentos.index') }}" 
                   class="w-full sm:w-auto px-4 py-2 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors text-center inline-flex items-center justify-center">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </a>
                <button type="submit" 
                        class="w-full sm:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors inline-flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i>
                    Atualizar Pagamento
                </button>
            </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Seção de Preview -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Preview do Pagamento</h3>
                    
                    <div class="space-y-4">
                        <div class="text-center p-6 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="mx-auto h-16 w-16 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center mb-4">
                                <svg class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Valor do pagamento</p>
                            <p id="preview-valor" class="text-2xl font-bold text-gray-900 dark:text-white">R$ 0,00</p>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Orçamento:</span>
                                <span id="preview-orcamento" class="text-sm font-medium text-gray-900 dark:text-white">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Conta Bancária:</span>
                                <span id="preview-banco" class="text-sm font-medium text-gray-900 dark:text-white">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Data:</span>
                                <span id="preview-data" class="text-sm font-medium text-gray-900 dark:text-white">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Forma:</span>
                                <span id="preview-forma" class="text-sm font-medium text-gray-900 dark:text-white">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Status:</span>
                                <span id="preview-status" class="text-sm font-medium text-gray-900 dark:text-white">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const orcamentoSelect = document.getElementById('orcamento_id');
    const orcamentoInfo = document.getElementById('orcamento-info');
    const valorTotal = document.getElementById('valor-total');
    const totalPago = document.getElementById('total-pago');
    const saldoRestante = document.getElementById('saldo-restante');
    const valorInput = document.getElementById('valor');
    const bankSelect = document.getElementById('bank_id');
    const dataInput = document.getElementById('data_pagamento');
    const formaSelect = document.getElementById('forma_pagamento');
    const statusSelect = document.getElementById('status');
    
    // Preview elements
    const previewOrcamento = document.getElementById('preview-orcamento');
    const previewBanco = document.getElementById('preview-banco');
    const previewValor = document.getElementById('preview-valor');
    const previewData = document.getElementById('preview-data');
    const previewForma = document.getElementById('preview-forma');
    const previewStatus = document.getElementById('preview-status');
    
    // Function to update preview
    function updatePreview() {
        // Update orçamento
        const selectedOrcamento = orcamentoSelect.options[orcamentoSelect.selectedIndex];
        previewOrcamento.textContent = selectedOrcamento.value ? selectedOrcamento.text : '-';
        
        // Update banco
        const selectedBank = bankSelect.options[bankSelect.selectedIndex];
        previewBanco.textContent = selectedBank.value ? selectedBank.text : '-';
        
        // Update valor
        const valor = valorInput.value;
        if (valor) {
            const valorFormatted = new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(parseFloat(valor));
            previewValor.textContent = valorFormatted;
        } else {
            previewValor.textContent = 'R$ 0,00';
        }
        
        // Update data
        if (dataInput.value) {
            const date = new Date(dataInput.value + 'T00:00:00');
            previewData.textContent = date.toLocaleDateString('pt-BR');
        } else {
            previewData.textContent = '-';
        }
        
        // Update forma
        previewForma.textContent = formaSelect.options[formaSelect.selectedIndex].text || '-';
        
        // Update status
        previewStatus.textContent = statusSelect.options[statusSelect.selectedIndex].text || '-';
    }
    
    orcamentoSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            const valor = parseFloat(selectedOption.dataset.valor) || 0;
            const pago = parseFloat(selectedOption.dataset.pago) || 0;
            const saldo = parseFloat(selectedOption.dataset.saldo) || 0;
            
            valorTotal.textContent = 'R$ ' + valor.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            totalPago.textContent = 'R$ ' + pago.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            saldoRestante.textContent = 'R$ ' + saldo.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            
            orcamentoInfo.classList.remove('hidden');
        } else {
            orcamentoInfo.classList.add('hidden');
        }
        
        updatePreview();
    });
    
    // Add event listeners for preview updates
    valorInput.addEventListener('input', updatePreview);
    bankSelect.addEventListener('change', updatePreview);
    dataInput.addEventListener('change', updatePreview);
    formaSelect.addEventListener('change', updatePreview);
    statusSelect.addEventListener('change', updatePreview);
    
    // Trigger change event if there's a pre-selected value
    if (orcamentoSelect.value) {
        orcamentoSelect.dispatchEvent(new Event('change'));
    }
    
    // Initial preview update
    updatePreview();
});
</script>
</div>
@endsection