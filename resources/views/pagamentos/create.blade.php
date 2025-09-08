@extends('layouts.app')

@section('title', 'Criar Pagamento')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                    <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <a href="{{ route('pagamentos.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Pagamentos</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Criar Pagamento</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('pagamentos.index') }}" 
                   class="inline-flex items-center justify-center w-10 h-10 text-gray-500 hover:text-gray-900 dark:hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Criar Pagamento</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Registre um novo pagamento no sistema</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="p-6 space-y-6">
            <form action="{{ route('pagamentos.store') }}" method="POST" id="payment-form">
            @csrf
            
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
                                {{ old('orcamento_id', request('orcamento_id')) == $orcamento->id ? 'selected' : '' }}
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
            <div id="orcamento-info" class="hidden bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
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
                                {{ old('bank_id') == $banco->id ? 'selected' : '' }}>
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
                               value="{{ old('valor') }}"
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
                           value="{{ old('data_pagamento', date('Y-m-d')) }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('data_pagamento') border-red-500 @enderror">
                    @error('data_pagamento')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
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
                    <option value="dinheiro" {{ old('forma_pagamento') == 'dinheiro' ? 'selected' : '' }}>Dinheiro</option>
                    <option value="pix" {{ old('forma_pagamento') == 'pix' ? 'selected' : '' }}>PIX</option>
                    <option value="cartao_credito" {{ old('forma_pagamento') == 'cartao_credito' ? 'selected' : '' }}>Cartão de Crédito</option>
                    <option value="cartao_debito" {{ old('forma_pagamento') == 'cartao_debito' ? 'selected' : '' }}>Cartão de Débito</option>
                    <option value="transferencia" {{ old('forma_pagamento') == 'transferencia' ? 'selected' : '' }}>Transferência Bancária</option>
                    <option value="boleto" {{ old('forma_pagamento') == 'boleto' ? 'selected' : '' }}>Boleto Bancário</option>
                    <option value="cheque" {{ old('forma_pagamento') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                    <option value="outros" {{ old('forma_pagamento') == 'outros' ? 'selected' : '' }}>Outros</option>
                </select>
                @error('forma_pagamento')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
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
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('observacoes') border-red-500 @enderror">{{ old('observacoes') }}</textarea>
                @error('observacoes')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Botões -->
            <div class="flex items-center justify-end pt-6 border-t border-gray-200 dark:border-gray-700">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    Criar Pagamento
                </button>
            </div>
        </form>
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
    
    orcamentoSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            const valor = parseFloat(selectedOption.dataset.valor) || 0;
            const pago = parseFloat(selectedOption.dataset.pago) || 0;
            const saldo = parseFloat(selectedOption.dataset.saldo) || 0;
            
            valorTotal.textContent = 'R$ ' + valor.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            totalPago.textContent = 'R$ ' + pago.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            saldoRestante.textContent = 'R$ ' + saldo.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            
            // Sugerir o valor do saldo restante
            if (saldo > 0) {
                valorInput.value = saldo.toFixed(2);
            }
            
            orcamentoInfo.classList.remove('hidden');
        } else {
            orcamentoInfo.classList.add('hidden');
            valorInput.value = '';
        }
    });
    
    // Trigger change event if there's a pre-selected value
    if (orcamentoSelect.value) {
        orcamentoSelect.dispatchEvent(new Event('change'));
    }
});
</script>
</div>