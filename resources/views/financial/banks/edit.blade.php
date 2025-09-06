@extends('layouts.app')

@section('title', 'Editar Conta Bancária - Giro')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-breadcrumb :items="[
            ['label' => 'Home', 'url' => route('dashboard'), 'icon' => 'fas fa-home'],
            ['label' => 'Financeiro', 'url' => '#'],
            ['label' => 'Bancos', 'url' => route('financial.banks.index')],
            ['label' => $bank->nome, 'url' => route('financial.banks.show', $bank)]
        ]" />
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Conta Bancária</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Atualize as informações da conta {{ $bank->nome }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('financial.banks.show', $bank) }}" class="inline-flex items-center p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors group" title="Visualizar">
                <svg class="w-5 h-5 text-blue-500 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </a>
            <a href="{{ route('financial.banks.index') }}" class="inline-flex items-center p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-900/20 transition-colors group" title="Voltar">
                <svg class="w-5 h-5 text-gray-500 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Section -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <form id="bank-form" action="{{ route('financial.banks.update', $bank) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Bank Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Bank Name -->
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nome do Banco *
                    </label>
                    <input type="text" 
                           id="nome" 
                           name="nome" 
                           value="{{ old('nome', $bank->nome) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white @error('nome') border-red-500 @enderror"
                           placeholder="Ex: Banco do Brasil, Itaú, Nubank..."
                           required>
                    @error('nome')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Account Type -->
                <div>
                    <label for="tipo_conta" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tipo de Conta *
                    </label>
                    <select id="tipo_conta" 
                            name="tipo_conta" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white @error('tipo_conta') border-red-500 @enderror"
                            required>
                        <option value="">Selecione o tipo</option>
                        <option value="corrente" {{ old('tipo_conta', $bank->tipo_conta) == 'corrente' ? 'selected' : '' }}>Conta Corrente</option>
                        <option value="poupanca" {{ old('tipo_conta', $bank->tipo_conta) == 'poupanca' ? 'selected' : '' }}>Conta Poupança</option>
                        <option value="salario" {{ old('tipo_conta', $bank->tipo_conta) == 'salario' ? 'selected' : '' }}>Conta Salário</option>
                        <option value="investimento" {{ old('tipo_conta', $bank->tipo_conta) == 'investimento' ? 'selected' : '' }}>Conta Investimento</option>
                        <option value="digital" {{ old('tipo_conta', $bank->tipo_conta) == 'digital' ? 'selected' : '' }}>Conta Digital</option>
                    </select>
                    @error('tipo_conta')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Bank Details -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Agency -->
                <div>
                    <label for="agencia" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Agência
                    </label>
                    <input type="text" 
                           id="agencia" 
                           name="agencia" 
                           value="{{ old('agencia', $bank->agencia) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white @error('agencia') border-red-500 @enderror"
                           placeholder="Ex: 1234">
                    @error('agencia')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Account Number -->
                <div>
                    <label for="conta" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Número da Conta
                    </label>
                    <input type="text" 
                           id="conta" 
                           name="conta" 
                           value="{{ old('conta', $bank->conta) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white @error('conta') border-red-500 @enderror"
                           placeholder="Ex: 12345-6">
                    @error('conta')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Balance -->
                <div>
                    <label for="saldo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Saldo Atual *
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500 dark:text-gray-400">R$</span>
                        <input type="text" 
                               id="saldo" 
                               name="saldo" 
                               value="{{ old('saldo', number_format($bank->saldo_atual, 2, ',', '.')) }}"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white @error('saldo') border-red-500 @enderror"
                               placeholder="0,00"
                               required>
                    </div>
                    @error('saldo')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Observations -->
            <div>
                <label for="observacoes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Observações
                </label>
                <textarea id="observacoes" 
                          name="observacoes" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white @error('observacoes') border-red-500 @enderror"
                          placeholder="Informações adicionais sobre esta conta...">{{ old('observacoes', $bank->observacoes) }}</textarea>
                @error('observacoes')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- URL do Logo do Banco -->
            <div>
                <label for="logo_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    URL do Logo do Banco
                </label>
                <input type="url" id="logo_url" name="logo_url" 
                       value="{{ old('logo_url', $bank->logo_url) }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white"
                       placeholder="https://exemplo.com/logo-banco.png">
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    URL da imagem do logo do banco (opcional)
                </p>
            </div>

            <!-- Status -->
            <div class="flex items-center">
                <input type="hidden" name="ativo" value="0">
                <input type="checkbox" 
                       id="ativo" 
                       name="ativo" 
                       value="1"
                       {{ old('ativo', $bank->ativo) ? 'checked' : '' }}
                       class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="ativo" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Conta ativa
                </label>
            </div>

            <!-- Account Info -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Informações da Conta</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Criada em:</span>
                        <span class="text-gray-900 dark:text-white ml-1">{{ $bank->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Última atualização:</span>
                        <span class="text-gray-900 dark:text-white ml-1">{{ $bank->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">ID:</span>
                        <span class="text-gray-900 dark:text-white ml-1">#{{ $bank->id }}</span>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('financial.banks.index') }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                    <span class="flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Atualizar Conta Bancária</span>
                    </span>
                </button>
            </div>
        </form>
        
            </div>


        </div>

        <!-- Preview Section -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 sticky top-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-eye text-blue-500 mr-2"></i>
                        Preview da Conta Bancária
                    </h3>
                    
                    <!-- Preview Content -->
                    <div id="bank-preview" class="space-y-4">
                        <!-- Preview Card -->
                        <div id="preview-card">
                            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-4 text-white mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span id="preview-bank-name" class="font-semibold">{{ $bank->nome }}</span>
                                    <span id="preview-account-type" class="text-sm bg-white/20 px-2 py-1 rounded">{{ ucfirst($bank->tipo_conta) }}</span>
                                </div>
                                <div class="text-sm opacity-90">
                                    <div>Agência: <span id="preview-agency">{{ $bank->agencia ?: '-' }}</span></div>
                                    <div>Conta: <span id="preview-account-number">{{ $bank->conta ?: '-' }}</span></div>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Saldo Atual:</span>
                                    <span id="preview-balance" class="font-semibold text-green-600">{{ 'R$ ' . number_format($bank->saldo_atual, 2, ',', '.') }}</span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                    <span id="preview-status" class="px-2 py-1 rounded-full text-xs font-medium {{ $bank->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $bank->ativo ? 'Ativo' : 'Inativo' }}</span>
                                </div>
                                
                                <div id="preview-observations-section" class="{{ $bank->observacoes ? '' : 'hidden' }}">
                                    <span class="text-gray-600 dark:text-gray-400 text-sm">Observações:</span>
                                    <p id="preview-observations" class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $bank->observacoes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tips Section -->
                    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-300 mb-2">
                            <i class="fas fa-lightbulb mr-1"></i>
                            Dicas
                        </h4>
                        <ul class="text-xs text-blue-700 dark:text-blue-300 space-y-1">
                            <li>• Escolha um nome descritivo para facilitar a identificação</li>
                            <li>• Verifique os dados da agência e conta</li>
                            <li>• O saldo pode ser ajustado através de transações</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>


    </div>


@push('scripts')
<script>
// Format currency input
document.getElementById('saldo').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    value = (value / 100).toFixed(2);
    value = value.replace('.', ',');
    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    e.target.value = value;
});

// Format agency and account inputs
document.getElementById('agencia').addEventListener('input', function(e) {
    e.target.value = e.target.value.replace(/\D/g, '');
});

document.getElementById('conta').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 1) {
        value = value.slice(0, -1) + '-' + value.slice(-1);
    }
    e.target.value = value;
});
</script>
@endpush
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bank-form');
    
    // Form fields
    const bankNameField = document.getElementById('nome');
    const accountTypeField = document.getElementById('tipo_conta');
    const agencyField = document.getElementById('agencia');
    const accountNumberField = document.getElementById('conta');
    const balanceField = document.getElementById('saldo');
    const statusField = document.getElementById('ativo');
    const observationsField = document.getElementById('observacoes');
    
    // Preview elements
    const previewBankName = document.getElementById('preview-bank-name');
    const previewAccountType = document.getElementById('preview-account-type');
    const previewAgency = document.getElementById('preview-agency');
    const previewAccountNumber = document.getElementById('preview-account-number');
    const previewBalance = document.getElementById('preview-balance');
    const previewStatus = document.getElementById('preview-status');
    const previewObservations = document.getElementById('preview-observations');
    const previewObservationsSection = document.getElementById('preview-observations-section');
    
    function updatePreview() {
        // Update bank name
        if (bankNameField && bankNameField.value) {
            previewBankName.textContent = bankNameField.value;
        }
        
        // Update account type
        if (accountTypeField) {
            const accountTypeText = accountTypeField.options[accountTypeField.selectedIndex]?.text || 'Tipo';
            previewAccountType.textContent = accountTypeText;
        }
        
        // Update agency
        if (agencyField) {
            previewAgency.textContent = agencyField.value || '-';
        }
        
        // Update account number
        if (accountNumberField) {
            previewAccountNumber.textContent = accountNumberField.value || '-';
        }
        
        // Update balance
        if (balanceField && balanceField.value) {
            // Remove formatting and convert to number
            const cleanValue = balanceField.value.replace(/\./g, '').replace(',', '.');
            const balance = parseFloat(cleanValue) || 0;
            previewBalance.textContent = new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(balance);
        }
        
        // Update status
        if (statusField) {
            const statusText = statusField.checked ? 'Ativo' : 'Inativo';
            previewStatus.textContent = statusText;
            
            // Update status color
            previewStatus.className = 'px-2 py-1 rounded-full text-xs font-medium';
            if (statusField.checked) {
                previewStatus.classList.add('bg-green-100', 'text-green-800');
            } else {
                previewStatus.classList.add('bg-red-100', 'text-red-800');
            }
        }
        
        // Update observations
        if (observationsField) {
            if (observationsField.value) {
                previewObservationsSection.classList.remove('hidden');
                previewObservations.textContent = observationsField.value;
            } else {
                previewObservationsSection.classList.add('hidden');
            }
        }
    }
    
    // Add event listeners
    [bankNameField, accountTypeField, agencyField, accountNumberField, balanceField, statusField, observationsField].forEach(field => {
        if (field) {
            field.addEventListener('input', updatePreview);
            field.addEventListener('change', updatePreview);
        }
    });
    
    // Initial update
    updatePreview();
});
</script>
@endpush