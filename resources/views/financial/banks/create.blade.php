@extends('layouts.app')

@section('title', 'Nova Conta Bancária - Giro')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nova Conta Bancária</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Adicione uma nova conta bancária ao seu sistema</p>
        </div>
        <a href="{{ route('financial.banks.index') }}" 
           class="inline-flex items-center p-2 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors group" title="Voltar">
            <i class="fas fa-arrow-left w-5 h-5 text-blue-500 group-hover:text-blue-600 transition-colors"></i>
        </a>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Section -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <form id="bank-form" action="{{ route('financial.banks.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Bank Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Bank Name -->
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nome da Conta *
                    </label>
                    <input type="text" 
                           id="nome" 
                           name="nome" 
                           value="{{ old('nome') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white @error('nome') border-red-500 @enderror"
                           placeholder="Ex: Conta Corrente Principal, Poupança..."
                           required>
                    @error('nome')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bank Institution -->
                <div>
                    <label for="banco" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Instituição Bancária *
                    </label>
                    <input type="text" 
                           id="banco" 
                           name="banco" 
                           value="{{ old('banco') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white @error('banco') border-red-500 @enderror"
                           placeholder="Ex: Banco do Brasil, Itaú, Nubank..."
                           required>
                    @error('banco')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

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
                    <option value="Conta Corrente" {{ old('tipo_conta') == 'Conta Corrente' ? 'selected' : '' }}>Conta Corrente</option>
                    <option value="Conta Poupança" {{ old('tipo_conta') == 'Conta Poupança' ? 'selected' : '' }}>Conta Poupança</option>
                    <option value="Conta Salário" {{ old('tipo_conta') == 'Conta Salário' ? 'selected' : '' }}>Conta Salário</option>
                    <option value="Conta Investimento" {{ old('tipo_conta') == 'Conta Investimento' ? 'selected' : '' }}>Conta Investimento</option>
                    <option value="Conta Digital" {{ old('tipo_conta') == 'Conta Digital' ? 'selected' : '' }}>Conta Digital</option>
                </select>
                @error('tipo_conta')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
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
                           value="{{ old('agencia') }}"
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
                           value="{{ old('conta') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white @error('conta') border-red-500 @enderror"
                           placeholder="Ex: 12345-6">
                    @error('conta')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Initial Balance -->
                <div>
                    <label for="saldo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Saldo Inicial *
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500 dark:text-gray-400">R$</span>
                        <input type="text" 
                               id="saldo" 
                               name="saldo" 
                               value="{{ old('saldo', '0,00') }}"
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
                          placeholder="Informações adicionais sobre esta conta...">{{ old('observacoes') }}</textarea>
                @error('observacoes')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Logo URL -->
            <div>
                <label for="logo_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    URL do Logo do Banco
                </label>
                <input type="url" 
                       id="logo_url" 
                       name="logo_url" 
                       value="{{ old('logo_url') }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white @error('logo_url') border-red-500 @enderror"
                       placeholder="https://exemplo.com/logo-banco.png">
                @error('logo_url')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">URL da imagem do logo do banco (opcional)</p>
            </div>

            <!-- Status -->
            <div class="flex items-center">
                <input type="hidden" name="ativo" value="0">
                <input type="checkbox" 
                       id="ativo" 
                       name="ativo" 
                       value="1"
                       {{ old('ativo', '1') ? 'checked' : '' }}
                       class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="ativo" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Conta ativa
                </label>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('financial.banks.index') }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center gap-2">
                    <i class="fas fa-times"></i>
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center gap-2">
                    <i class="fas fa-save w-4 h-4"></i>
                    Criar Conta Bancária
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
                        <!-- Empty State -->
                        <div id="preview-empty" class="text-center py-8">
                            <i class="fas fa-university text-gray-300 text-4xl mb-3"></i>
                            <p class="text-gray-500 dark:text-gray-400">Preencha os campos para ver o preview</p>
                        </div>
                        
                        <!-- Preview Card -->
                        <div id="preview-card" class="hidden">
                            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-4 text-white mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span id="preview-bank-name" class="font-semibold">Nome do Banco</span>
                                    <span id="preview-account-type" class="text-sm bg-white/20 px-2 py-1 rounded">Tipo</span>
                                </div>
                                <div class="text-sm opacity-90">
                                    <div>Agência: <span id="preview-agency">-</span></div>
                                    <div>Conta: <span id="preview-account-number">-</span></div>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Saldo Inicial:</span>
                                    <span id="preview-balance" class="font-semibold text-green-600">R$ 0,00</span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                    <span id="preview-status" class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Ativo</span>
                                </div>
                                
                                <div id="preview-observations-section" class="hidden">
                                    <span class="text-gray-600 dark:text-gray-400 text-sm">Observações:</span>
                                    <p id="preview-observations" class="text-sm text-gray-700 dark:text-gray-300 mt-1"></p>
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
                            <li>• O saldo inicial pode ser ajustado posteriormente</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
// Debug: Log when script loads
console.log('Bank create form script loaded');

// Format currency input
document.getElementById('saldo').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    value = (value / 100).toFixed(2);
    value = value.replace('.', ',');
    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    e.target.value = value;
});

// Format agency input - maximum 4 digits
document.getElementById('agencia').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 4) {
        value = value.substring(0, 4);
    }
    e.target.value = value;
});

// Debug: Add form submission listener
document.querySelector('form').addEventListener('submit', function(e) {
    console.log('Form being submitted');
    console.log('Form data:', new FormData(this));
    
    // Check required fields
    const nome = document.getElementById('nome').value;
    const tipo_conta = document.getElementById('tipo_conta').value;
    const saldo = document.getElementById('saldo').value;
    
    console.log('Nome:', nome);
    console.log('Tipo conta:', tipo_conta);
    console.log('Saldo:', saldo);
    
    if (!nome || !tipo_conta || !saldo) {
        console.error('Required fields missing');
        alert('Por favor, preencha todos os campos obrigatórios');
        e.preventDefault();
        return false;
    }
});

// Format account input - number with check digit (format: 12345-6)
document.getElementById('conta').addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^\d-]/g, '');
    
    // Remove existing hyphens to reformat
    value = value.replace(/-/g, '');
    
    // Add hyphen before the last digit if there are at least 2 digits
    if (value.length >= 2) {
        value = value.slice(0, -1) + '-' + value.slice(-1);
    }
    
    // Limit to reasonable account number length (max 10 digits + hyphen)
    if (value.replace(/-/g, '').length > 10) {
        value = value.substring(0, 11); // 10 digits + 1 hyphen
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
    const previewEmpty = document.getElementById('preview-empty');
    const previewCard = document.getElementById('preview-card');
    
    // Form fields - IDs corretos
    const bankNameField = document.getElementById('nome');
    const bankInstitutionField = document.getElementById('banco');
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
        // Verificar se os campos existem antes de acessar
        const hasContent = (bankNameField && bankNameField.value) || 
                          (accountTypeField && accountTypeField.value) || 
                          (agencyField && agencyField.value) || 
                          (accountNumberField && accountNumberField.value);
        
        if (hasContent) {
            previewEmpty.classList.add('hidden');
            previewCard.classList.remove('hidden');
            
            // Update bank name - usar nome + banco
            const bankName = (bankNameField ? bankNameField.value : '') || 'Nome da Conta';
            const bankInstitution = (bankInstitutionField ? bankInstitutionField.value : '');
            previewBankName.textContent = bankInstitution ? `${bankName} - ${bankInstitution}` : bankName;
            
            // Update account type
            if (accountTypeField && accountTypeField.selectedIndex >= 0) {
                const accountTypeText = accountTypeField.options[accountTypeField.selectedIndex]?.text || 'Tipo';
                previewAccountType.textContent = accountTypeText;
            } else {
                previewAccountType.textContent = 'Tipo';
            }
            
            // Update agency
            previewAgency.textContent = (agencyField ? agencyField.value : '') || '-';
            
            // Update account number
            previewAccountNumber.textContent = (accountNumberField ? accountNumberField.value : '') || '-';
            
            // Update balance
            if (balanceField) {
                const balanceValue = balanceField.value.replace(/[^\d,]/g, '').replace(',', '.');
                const balance = parseFloat(balanceValue) || 0;
                previewBalance.textContent = new Intl.NumberFormat('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                }).format(balance);
            }
            
            // Update status - checkbox ativo
            if (statusField) {
                const isActive = statusField.checked;
                previewStatus.textContent = isActive ? 'Ativo' : 'Inativo';
                previewStatus.className = 'px-2 py-1 rounded-full text-xs font-medium';
                if (isActive) {
                    previewStatus.classList.add('bg-green-100', 'text-green-800');
                } else {
                    previewStatus.classList.add('bg-red-100', 'text-red-800');
                }
            }
            
            // Update observations
            if (observationsField && observationsField.value) {
                previewObservationsSection.classList.remove('hidden');
                previewObservations.textContent = observationsField.value;
            } else {
                previewObservationsSection.classList.add('hidden');
            }
        } else {
            previewEmpty.classList.remove('hidden');
            previewCard.classList.add('hidden');
        }
    }
    
    // Add event listeners - verificar se os campos existem
    const fields = [bankNameField, bankInstitutionField, accountTypeField, agencyField, accountNumberField, balanceField, statusField, observationsField];
    
    fields.forEach(field => {
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