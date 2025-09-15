<?php $__env->startSection('title', 'Novo Cliente'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="<?php echo e(route('clientes.index')); ?>" 
                   class="inline-flex items-center justify-center w-10 h-10 text-gray-500 hover:text-gray-900 dark:hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Novo Cliente</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Adicione um novo cliente ao sistema</p>
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
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Informações do Cliente</h2>
                    
                    <form method="POST" action="<?php echo e(route('clientes.store')); ?>" enctype="multipart/form-data" class="space-y-6">
            <?php echo csrf_field(); ?>
            
            <!-- Avatar -->
            <div class="flex items-center space-x-6">
                <div class="shrink-0">
                    <img id="avatar-preview" 
                         class="h-16 w-16 object-cover rounded-full border-2 border-gray-300 dark:border-gray-600" 
                         src="data:image/svg+xml,%3csvg width='100' height='100' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100' height='100' fill='%23f3f4f6'/%3e%3ctext x='50%25' y='50%25' font-size='18' text-anchor='middle' alignment-baseline='middle' font-family='monospace, sans-serif' fill='%236b7280'%3eAvatar%3c/text%3e%3c/svg%3e" 
                         alt="Avatar preview">
                </div>
                <div class="flex-1">
                    <label for="avatar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Avatar
                    </label>
                    <input type="file" 
                           id="avatar" 
                           name="avatar" 
                           accept="image/*"
                           onchange="previewAvatarImage(this)"
                           class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-300">
                    <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">PNG, JPG ou GIF até 2MB</p>
                </div>
            </div>
            
            <!-- Informações Básicas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nome Completo *
                    </label>
                    <input type="text" 
                           id="nome" 
                           name="nome" 
                           value="<?php echo e(old('nome')); ?>"
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?php $__errorArgs = ['nome'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['nome'];
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
                
                <div>
                    <label for="pessoa_contato" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Pessoa de Contato
                    </label>
                    <input type="text" 
                           id="pessoa_contato" 
                           name="pessoa_contato" 
                           value="<?php echo e(old('pessoa_contato')); ?>"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?php $__errorArgs = ['pessoa_contato'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['pessoa_contato'];
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
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    E-mail *
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="<?php echo e(old('email')); ?>"
                       required
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['email'];
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
            
            <!-- Contato -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="telefone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Telefone
                    </label>
                    <input type="tel" 
                           id="telefone" 
                           name="telefone" 
                           value="<?php echo e(old('telefone')); ?>"
                           placeholder="(11) 99999-9999"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?php $__errorArgs = ['telefone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['telefone'];
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
                
                <div>
                    <label for="whatsapp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        WhatsApp
                    </label>
                    <input type="tel" 
                           id="whatsapp" 
                           name="whatsapp" 
                           value="<?php echo e(old('whatsapp')); ?>"
                           placeholder="(11) 99999-9999"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white <?php $__errorArgs = ['whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['whatsapp'];
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

            
            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="<?php echo e(route('clientes.index')); ?>" 
                   class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    Criar Cliente
                </button>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
        <!-- Seção de Preview -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Preview do Cliente</h3>
                    
                    <div class="text-center mb-6">
                        <div class="mx-auto h-24 w-24 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center mb-4" id="avatar-preview">
                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Avatar do cliente</p>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nome</label>
                            <p class="text-gray-900 dark:text-white" id="preview-nome">-</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Pessoa de Contato</label>
                            <p class="text-gray-900 dark:text-white" id="preview-pessoa-contato">-</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">E-mail</label>
                            <p class="text-gray-900 dark:text-white" id="preview-email">-</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Telefone</label>
                            <p class="text-gray-900 dark:text-white" id="preview-telefone">-</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">WhatsApp</label>
                            <p class="text-gray-900 dark:text-white" id="preview-whatsapp">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

<script>
    // Phone mask functions
    function formatPhone(value) {
        // Remove todos os caracteres não numéricos
        const numbers = value.replace(/\D/g, '');
        
        // Limita a 11 dígitos (DDD + 9 dígitos para celular)
        const limitedNumbers = numbers.substring(0, 11);
        
        // Aplica a formatação baseada no número de dígitos
        if (limitedNumbers.length <= 2) {
            return limitedNumbers;
        } else if (limitedNumbers.length <= 6) {
            return `(${limitedNumbers.substring(0, 2)}) ${limitedNumbers.substring(2)}`;
        } else if (limitedNumbers.length <= 10) {
            // Telefone fixo: (XX) XXXX-XXXX
            return `(${limitedNumbers.substring(0, 2)}) ${limitedNumbers.substring(2, 6)}-${limitedNumbers.substring(6)}`;
        } else {
            // Celular: (XX) XXXXX-XXXX
            return `(${limitedNumbers.substring(0, 2)}) ${limitedNumbers.substring(2, 7)}-${limitedNumbers.substring(7)}`;
        }
    }

    function applyPhoneMask(input) {
        const cursorPosition = input.selectionStart;
        const oldValue = input.value;
        const oldLength = oldValue.length;
        
        // Aplica a formatação
        const newValue = formatPhone(input.value);
        input.value = newValue;
        
        // Ajusta a posição do cursor
        const newLength = newValue.length;
        const lengthDiff = newLength - oldLength;
        
        // Calcula nova posição do cursor
        let newCursorPosition = cursorPosition + lengthDiff;
        
        // Garante que o cursor não fique em uma posição inválida
        if (newCursorPosition < 0) newCursorPosition = 0;
        if (newCursorPosition > newLength) newCursorPosition = newLength;
        
        // Define a nova posição do cursor
        setTimeout(() => {
            input.setSelectionRange(newCursorPosition, newCursorPosition);
        }, 0);
    }

    // Função para preview do avatar
    function previewAvatarImage(input) {
        const file = input.files[0];
        const preview = document.getElementById('avatar-preview');
        const previewSidebar = document.getElementById('avatar-preview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Atualiza o preview principal (formulário)
                preview.src = e.target.result;
                // Atualiza o preview da sidebar
                if (previewSidebar) {
                    previewSidebar.innerHTML = `<img src="${e.target.result}" class="h-24 w-24 object-cover rounded-full">`;
                }
            };
            reader.readAsDataURL(file);
        } else {
            // Reset para imagem padrão
            preview.src = "data:image/svg+xml,%3csvg width='100' height='100' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100' height='100' fill='%23f3f4f6'/%3e%3ctext x='50%25' y='50%25' font-size='18' text-anchor='middle' alignment-baseline='middle' font-family='monospace, sans-serif' fill='%236b7280'%3eAvatar%3c/text%3e%3c/svg%3e";
            if (previewSidebar) {
                previewSidebar.innerHTML = `
                    <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                `;
            }
        }
    }
    
    // Preview do avatar usando event listener também
    document.getElementById('avatar').addEventListener('change', function(e) {
        previewAvatarImage(this);
    });
    
    // Preview dos campos de texto
    function updatePreview() {
        document.getElementById('preview-nome').textContent = document.getElementById('nome').value || '-';
        document.getElementById('preview-pessoa-contato').textContent = document.getElementById('pessoa_contato').value || '-';
        document.getElementById('preview-email').textContent = document.getElementById('email').value || '-';
        document.getElementById('preview-telefone').textContent = document.getElementById('telefone').value || '-';
        document.getElementById('preview-whatsapp').textContent = document.getElementById('whatsapp').value || '-';
    }
    
    // Adicionar listeners para atualizar o preview
    document.getElementById('nome').addEventListener('input', updatePreview);
    document.getElementById('pessoa_contato').addEventListener('input', updatePreview);
    document.getElementById('email').addEventListener('input', updatePreview);
    
    // Configurar máscaras de telefone
    const telefoneInput = document.getElementById('telefone');
    const whatsappInput = document.getElementById('whatsapp');
    
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            applyPhoneMask(this);
            updatePreview();
        });
        
        telefoneInput.addEventListener('paste', function(e) {
            setTimeout(() => {
                applyPhoneMask(this);
                updatePreview();
            }, 0);
        });
    }
    
    if (whatsappInput) {
        whatsappInput.addEventListener('input', function(e) {
            applyPhoneMask(this);
            updatePreview();
        });
        
        whatsappInput.addEventListener('paste', function(e) {
            setTimeout(() => {
                applyPhoneMask(this);
                updatePreview();
            }, 0);
        });
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\giro_2\resources\views/clientes/create.blade.php ENDPATH**/ ?>