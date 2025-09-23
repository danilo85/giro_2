@extends('layouts.app')

@section('title', 'Novo Cartão de Crédito - Giro')

@section('content')
<div class="max-w-7xl mx-auto">


    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Novo Cartão de Crédito</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Adicione um novo cartão de crédito ao sistema</p>
        </div>
        <a href="{{ route('financial.credit-cards.index') }}" class="inline-flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-900/20 transition-colors group" title="Voltar">
            <svg class="w-5 h-5 text-gray-500 group-hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <form action="{{ route('financial.credit-cards.store') }}" method="POST" id="credit-card-form">
            @csrf
            
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Card Name -->
                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nome do Cartão *
                            </label>
                            <input type="text" id="nome" name="nome_cartao" value="{{ old('nome_cartao') }}" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors"
                                placeholder="Ex: Cartão Nubank, Itaú Mastercard">
                            @error('nome')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Card Brand -->
                        <div>
                            <label for="bandeira" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Bandeira *
                            </label>
                            <select id="bandeira" name="bandeira" required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors">
                                <option value="">Selecione a bandeira</option>
                                <option value="Visa" {{ old('bandeira') == 'Visa' ? 'selected' : '' }}>Visa</option>
                                <option value="Mastercard" {{ old('bandeira') == 'Mastercard' ? 'selected' : '' }}>Mastercard</option>
                                <option value="American Express" {{ old('bandeira') == 'American Express' ? 'selected' : '' }}>American Express</option>
                                <option value="Elo" {{ old('bandeira') == 'Elo' ? 'selected' : '' }}>Elo</option>
                                <option value="Hipercard" {{ old('bandeira') == 'Hipercard' ? 'selected' : '' }}>Hipercard</option>
                                <option value="Diners" {{ old('bandeira') == 'Diners' ? 'selected' : '' }}>Diners</option>
                                <option value="Discover" {{ old('bandeira') == 'Discover' ? 'selected' : '' }}>Discover</option>
                                <option value="JCB" {{ old('bandeira') == 'JCB' ? 'selected' : '' }}>JCB</option>
                                <option value="Outros" {{ old('bandeira') == 'Outros' ? 'selected' : '' }}>Outros</option>
                            </select>
                            @error('bandeira')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>



                        <!-- Card Number -->
                        <div>
                            <label for="numero" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Número do Cartão
                            </label>
                            <input type="text" id="numero" name="numero" value="{{ old('numero') }}"  maxlength="19"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors font-mono"
                                placeholder="0000 0000 0000 0000">
                            @error('numero')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Credit Limit -->
                        <div>
                            <label for="limite_total" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Limite de Crédito *
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400">R$</span>
                                <input type="text" id="limite_total" name="limite_total" value="{{ old('limite_total') }}" required
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors"
                                    placeholder="0,00">
                            </div>
                            @error('limite_total')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Used Limit -->
                        <div>
                            <label for="limite_utilizado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Limite Utilizado
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400">R$</span>
                                <input type="text" id="limite_utilizado" name="limite_utilizado" value="{{ old('limite_utilizado', '0,00') }}"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors"
                                    placeholder="0,00">
                            </div>
                            @error('limite_utilizado')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Due Day -->
                        <div>
                            <label for="data_vencimento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Dia de Vencimento *
                            </label>
                            <select id="data_vencimento" name="data_vencimento"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors">
                                <option value="">Selecione o dia</option>
                                @for($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}" {{ old('data_vencimento') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            @error('data_vencimento')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
                        </div>

                        <!-- Closing Day -->
                        <div>
                            <label for="data_fechamento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Dia de Fechamento *
                            </label>
                            <select id="data_fechamento" name="data_fechamento"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors">
                                <option value="">Selecione o dia</option>
                                @for($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}" {{ old('data_fechamento') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            @error('data_fechamento')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
                        </div>

                        <!-- Observations -->
                        <div>
                            <label for="observacoes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Observações
                            </label>
                            <textarea id="observacoes" name="observacoes" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-colors resize-none"
                                placeholder="Informações adicionais sobre o cartão...">{{ old('observacoes') }}</textarea>
                            @error('observacoes')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div>
                            <div class="flex items-center">
                                <input type="checkbox" id="ativo" name="ativo" value="1" {{ old('ativo', true) ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="ativo" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Cartão ativo
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Cartões inativos não aparecerão nas listagens principais
                            </p>
                            @error('ativo')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Card Preview -->
                        <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl p-6 text-white" id="card-preview">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-bold" id="preview-name">Nome do Cartão</h3>
                                    <p class="text-sm opacity-80" id="preview-brand">Bandeira</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs opacity-80">Limite</p>
                                    <p class="text-sm font-medium" id="preview-limit">R$ 0,00</p>
                                </div>
                            </div>
                            <div class="mb-4">
                                <p class="text-lg font-mono tracking-wider" id="preview-number">•••• •••• •••• ••••</p>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span id="preview-due">Venc: --</span>
                                <span id="preview-closing">Fech: --</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 rounded-b-xl">
                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('financial.credit-cards.index') }}" 
                       class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                        <i class="fas fa-save w-4 h-4"></i>
                        Criar Cartão
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format card number
    const numeroInput = document.getElementById('numero');
    numeroInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
        e.target.value = value;
        updatePreview();
    });

    // Format currency inputs
    const currencyInputs = ['limite_total', 'limite_utilizado'];
    currencyInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = (value / 100).toFixed(2);
            value = value.replace('.', ',');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            e.target.value = value;
            updatePreview();
        });
    });

    // Update preview on input changes
    const inputs = ['nome', 'bandeira', 'data_vencimento', 'data_fechamento'];
    inputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        input.addEventListener('input', updatePreview);
        input.addEventListener('change', updatePreview);
    });

    function updatePreview() {
        const nome = document.getElementById('nome').value || 'Nome do Cartão';
        const bandeira = document.getElementById('bandeira').value || 'Bandeira';
        const numero = document.getElementById('numero').value || '•••• •••• •••• ••••';
        const limite = document.getElementById('limite_total').value || '0,00';
        const diaVencimento = document.getElementById('data_vencimento').value;
            const diaFechamento = document.getElementById('data_fechamento').value;

        document.getElementById('preview-name').textContent = nome;
        document.getElementById('preview-brand').textContent = bandeira;
        document.getElementById('preview-limit').textContent = `R$ ${limite}`;
        
        // Format card number for preview
        if (numero.replace(/\s/g, '').length > 0) {
            const cleanNumber = numero.replace(/\s/g, '');
            if (cleanNumber.length >= 4) {
                const lastFour = cleanNumber.slice(-4);
                document.getElementById('preview-number').textContent = `•••• •••• •••• ${lastFour}`;
            } else {
                document.getElementById('preview-number').textContent = numero;
            }
        } else {
            document.getElementById('preview-number').textContent = '•••• •••• •••• ••••';
        }

        document.getElementById('preview-due').textContent = diaVencimento ? `Venc: ${diaVencimento}` : 'Venc: --';
        document.getElementById('preview-closing').textContent = diaFechamento ? `Fech: ${diaFechamento}` : 'Fech: --';
    }

    // Form validation
    document.getElementById('credit-card-form').addEventListener('submit', function(e) {
        // Convert formatted values to decimal format for backend
        const limiteInput = document.getElementById('limite_total');
        const limiteUtilizadoInput = document.getElementById('limite_utilizado');
        const limiteValue = limiteInput.value.replace(/\./g, '').replace(',', '.');
        const limiteUtilizadoValue = limiteUtilizadoInput.value.replace(/\./g, '').replace(',', '.');
        
        // Validate numeric values
        const limite = parseFloat(limiteValue) || 0;
        const limiteUtilizado = parseFloat(limiteUtilizadoValue) || 0;

        if (limiteUtilizado > limite) {
            e.preventDefault();
            alert('O limite utilizado não pode ser maior que o limite total do cartão.');
            return false;
        }

        const diaVencimento = parseInt(document.getElementById('data_vencimento').value);
            const diaFechamento = parseInt(document.getElementById('data_fechamento').value);
        
        if (diaFechamento && diaVencimento && diaFechamento >= diaVencimento) {
            e.preventDefault();
            alert('O dia de fechamento deve ser anterior ao dia de vencimento.');
            return false;
        }

        const numero = document.getElementById('numero').value.replace(/\s/g, '');
        if (numero.length > 0 && (numero.length < 13 || numero.length > 19)) {
            e.preventDefault();
            alert('O número do cartão deve ter entre 13 e 19 dígitos.');
            return false;
        }
        
        // Set the decimal values for form submission
        limiteInput.value = limiteValue;
        limiteUtilizadoInput.value = limiteUtilizadoValue;
    });

    // Initialize preview
    updatePreview();
});
</script>
@endpush
@endsection