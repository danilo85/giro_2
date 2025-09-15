<?php $__env->startSection('title', 'Novo Pagamento'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <?php if($orcamento && $orcamento->id): ?>
                    <a href="<?php echo e(route('orcamentos.show', $orcamento->id)); ?>" 
                       class="inline-flex items-center justify-center w-10 h-10 text-gray-500 hover:text-gray-900 dark:hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('pagamentos.index')); ?>" 
                       class="inline-flex items-center justify-center w-10 h-10 text-gray-500 hover:text-gray-900 dark:hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                <?php endif; ?>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Novo Pagamento</h1>
                    <?php if($orcamento && $orcamento->id): ?>
                         <p class="mt-2 text-gray-600 dark:text-gray-400">Registrar pagamento para o orçamento #<?php echo e($orcamento->id); ?></p>
                     <?php else: ?>
                         <p class="mt-2 text-gray-600 dark:text-gray-400">Registrar novo pagamento</p>
                     <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulário Principal -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Informações do Pagamento</h2>
                    
                    <form method="POST" action="<?php echo e(route('pagamentos.store')); ?>" class="space-y-6">
            <?php echo csrf_field(); ?>
            
            <!-- Orçamento -->
            <div>
                <label for="orcamento_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Orçamento <span class="text-red-500">*</span>
                </label>
                <select id="orcamento_id" 
                        name="orcamento_id" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?php $__errorArgs = ['orcamento_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="">Selecione um orçamento</option>
                    <?php $__currentLoopData = $orcamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orcamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($orcamento->id); ?>" 
                                <?php echo e(old('orcamento_id', request('orcamento_id')) == $orcamento->id ? 'selected' : ''); ?>

                                data-valor="<?php echo e($orcamento->valor_total); ?>"
                                data-pago="<?php echo e($orcamento->pagamentos->sum('valor')); ?>"
                                data-saldo="<?php echo e($orcamento->valor_total - $orcamento->pagamentos->sum('valor')); ?>">
                            <?php echo e($orcamento->titulo); ?> - <?php echo e($orcamento->cliente->nome); ?> 
                            (Saldo: R$ <?php echo e(number_format($orcamento->valor_total - $orcamento->pagamentos->sum('valor'), 2, ',', '.')); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['orcamento_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?php $__errorArgs = ['bank_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="">Selecione uma conta bancária</option>
                    <?php $__currentLoopData = $bancos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banco): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($banco->id); ?>" 
                                <?php echo e(old('bank_id') == $banco->id ? 'selected' : ''); ?>>
                            <?php echo e($banco->nome); ?> - <?php echo e($banco->tipo_conta); ?> 
                            (Saldo: R$ <?php echo e(number_format($banco->saldo_atual, 2, ',', '.')); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['bank_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                               value="<?php echo e(old('valor')); ?>"
                               required
                               placeholder="0,00"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?php $__errorArgs = ['valor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    </div>
                    <?php $__errorArgs = ['valor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <!-- Data do Pagamento -->
                <div>
                    <label for="data_pagamento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Data do Pagamento <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="data_pagamento" 
                           name="data_pagamento" 
                           value="<?php echo e(old('data_pagamento', date('Y-m-d'))); ?>"
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?php $__errorArgs = ['data_pagamento'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['data_pagamento'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?php $__errorArgs = ['forma_pagamento'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">Selecione a forma de pagamento</option>
                        <option value="dinheiro" <?php echo e(old('forma_pagamento') == 'dinheiro' ? 'selected' : ''); ?>>Dinheiro</option>
                        <option value="pix" <?php echo e(old('forma_pagamento') == 'pix' ? 'selected' : ''); ?>>PIX</option>
                        <option value="cartao_credito" <?php echo e(old('forma_pagamento') == 'cartao_credito' ? 'selected' : ''); ?>>Cartão de Crédito</option>
                        <option value="cartao_debito" <?php echo e(old('forma_pagamento') == 'cartao_debito' ? 'selected' : ''); ?>>Cartão de Débito</option>
                        <option value="transferencia" <?php echo e(old('forma_pagamento') == 'transferencia' ? 'selected' : ''); ?>>Transferência Bancária</option>
                        <option value="boleto" <?php echo e(old('forma_pagamento') == 'boleto' ? 'selected' : ''); ?>>Boleto Bancário</option>
                    </select>
                    <?php $__errorArgs = ['forma_pagamento'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" 
                            name="status" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="pendente" <?php echo e(old('status', 'pendente') == 'pendente' ? 'selected' : ''); ?>>Pendente</option>
                        <option value="processando" <?php echo e(old('status', 'pendente') == 'processando' ? 'selected' : ''); ?>>Processando</option>
                        <option value="confirmado" selected >Confirmado</option>
                        <option value="cancelado" <?php echo e(old('status', 'pendente') == 'cancelado' ? 'selected' : ''); ?>>Cancelado</option>
                    </select>
                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?php $__errorArgs = ['observacoes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('observacoes')); ?></textarea>
                <?php $__errorArgs = ['observacoes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            
            <!-- Botões -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="<?php echo e(route('pagamentos.index')); ?>" 
                   class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    Criar Pagamento
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
    
    // Preview elements
    const previewOrcamento = document.getElementById('preview-orcamento');
    const previewBanco = document.getElementById('preview-banco');
    const previewValor = document.getElementById('preview-valor');
    const previewData = document.getElementById('preview-data');
    const previewForma = document.getElementById('preview-forma');
    
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
        previewForma.textContent = formaSelect.value || '-';
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
            
            // Sugerir o valor do saldo restante
            if (saldo > 0) {
                valorInput.value = saldo.toFixed(2);
            }
            
            orcamentoInfo.classList.remove('hidden');
        } else {
            orcamentoInfo.classList.add('hidden');
            valorInput.value = '';
        }
        
        updatePreview();
    });
    
    // Add event listeners for preview updates
    valorInput.addEventListener('input', updatePreview);
    bankSelect.addEventListener('change', updatePreview);
    dataInput.addEventListener('change', updatePreview);
    formaSelect.addEventListener('change', updatePreview);
    
    // Trigger change event if there's a pre-selected value
    if (orcamentoSelect.value) {
        orcamentoSelect.dispatchEvent(new Event('change'));
    }
    
    // Initial preview update
    updatePreview();
});
</script>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\giro_2\resources\views/pagamentos/create.blade.php ENDPATH**/ ?>