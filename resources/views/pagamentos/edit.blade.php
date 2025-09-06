@extends('layouts.app')

@section('title', 'Editar Pagamento')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Editar Pagamento</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Atualize as informações do pagamento</p>
            </div>
            
            <a href="{{ route('pagamentos.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar
            </a>
        </div>
    </div>
    
    <!-- Formulário -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <form method="POST" action="{{ route('pagamentos.update', $pagamento) }}" class="p-6 space-y-6">
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
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('pagamentos.index') }}" 
                   class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    Atualizar Pagamento
                </button>
            </div>
        </form>
    </div>
</div>
@endsection