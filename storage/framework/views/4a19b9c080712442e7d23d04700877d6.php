<?php $__env->startSection('title', 'Dashboard Financeiro'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
        ['label' => 'Home', 'url' => route('dashboard')],
        ['label' => 'Financeiro', 'url' => '#'],
        ['label' => 'Dashboard', 'url' => route('financial.dashboard')]
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['label' => 'Home', 'url' => route('dashboard')],
        ['label' => 'Financeiro', 'url' => '#'],
        ['label' => 'Dashboard', 'url' => route('financial.dashboard')]
    ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $attributes = $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2)): ?>
<?php $component = $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2; ?>
<?php unset($__componentOriginale19f62b34dfe0bfdf95075badcb45bc2); ?>
<?php endif; ?>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
  
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard Financeiro</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Visão geral das suas finanças</p>
        </div>
        <!-- Navegação de período -->
        <div class="flex items-center space-x-4 mt-4 sm:mt-0">
            <a href="<?php echo e(route('financial.dashboard', ['year' => $currentYear, 'month' => $currentMonth - 1])); ?>" 
               class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            
            <div class="text-center">
                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                    <?php echo e(\Carbon\Carbon::create($currentYear, $currentMonth)->locale('pt_BR')->isoFormat('MMMM YYYY')); ?>

                </div>
            </div>
            
            <a href="<?php echo e(route('financial.dashboard', ['year' => $currentYear, 'month' => $currentMonth + 1])); ?>" 
               class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Cards de resumo financeiro -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Receitas -->
        <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-emerald-100">Receitas</p>
                    <p class="text-2xl font-bold text-white">R$ <?php echo e(number_format($summary['receitas_total'], 2, ',', '.')); ?></p>
                    <p class="text-xs text-emerald-100 mt-1">
                        Pagas: R$ <?php echo e(number_format($summary['receitas_pagas'], 2, ',', '.')); ?>

                    </p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Despesas -->
        <div class="bg-gradient-to-br from-red-500 to-rose-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-red-100">Despesas</p>
                    <p class="text-2xl font-bold text-white">R$ <?php echo e(number_format($summary['despesas_total'], 2, ',', '.')); ?></p>
                    <p class="text-xs text-red-100 mt-1">
                        Pagas: R$ <?php echo e(number_format($summary['despesas_pagas'], 2, ',', '.')); ?>

                    </p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Saldo -->
        <div class="bg-gradient-to-br <?php echo e($summary['saldo'] >= 0 ? 'from-emerald-500 to-green-600' : 'from-red-500 to-rose-600'); ?> rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium <?php echo e($summary['saldo'] >= 0 ? 'text-emerald-100' : 'text-red-100'); ?>">Saldo</p>
                    <p class="text-2xl font-bold text-white">
                        R$ <?php echo e(number_format($summary['saldo'], 2, ',', '.')); ?>

                    </p>
                    <p class="text-xs <?php echo e($summary['saldo'] >= 0 ? 'text-emerald-100' : 'text-red-100'); ?> mt-1">
                        <?php echo e($summary['saldo'] >= 0 ? 'Positivo' : 'Negativo'); ?>

                    </p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Transações -->
        <div class="bg-gradient-to-br from-amber-500 to-yellow-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-amber-100">Transações</p>
                    <p class="text-2xl font-bold text-white"><?php echo e($summary['total_transacoes']); ?></p>
                    <p class="text-xs text-amber-100 mt-1">
                        Pendentes: <?php echo e($summary['transacoes_pendentes']); ?>

                    </p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Contas Bancárias -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Contas Bancárias</h3>
                <a href="<?php echo e(route('financial.banks.index')); ?>" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium">
                    Ver todas
                </a>
            </div>
            
            <?php if($banks->count() > 0): ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $banks->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/20 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white"><?php echo e($bank->nome); ?></p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($bank->banco); ?></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    R$ <?php echo e(number_format($bank->saldo_atual, 2, ',', '.')); ?>

                                </p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <p class="text-gray-500 mb-4">Nenhuma conta bancária cadastrada</p>
                    <a href="<?php echo e(route('financial.banks.create')); ?>" class="inline-flex items-center px-4 py-2 rounded-lg hover:bg-blue-50 transition-colors" title="Adicionar Conta">
                        <svg class="w-5 h-5 text-blue-600 hover:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Cartões de Crédito -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Cartões de Crédito</h3>
                <a href="<?php echo e(route('financial.credit-cards.index')); ?>" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium">
                    Ver todos
                </a>
            </div>
            
            <?php if($creditCards->count() > 0): ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $creditCards->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/20 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white"><?php echo e($card->nome); ?></p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($card->bandeira); ?></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                        <?php echo e(number_format($card->percentual_utilizado, 1)); ?>% usado
                                    </p>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                <div class="bg-purple-600 dark:bg-purple-400 h-2 rounded-full" style="width: <?php echo e($card->percentual_utilizado); ?>%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                                <span>R$ <?php echo e(number_format($card->limite_utilizado, 2, ',', '.')); ?></span>
                                <span>R$ <?php echo e(number_format($card->limite_total, 2, ',', '.')); ?></span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <p class="text-gray-500 mb-4">Nenhum cartão cadastrado</p>
                    <a href="<?php echo e(route('financial.credit-cards.create')); ?>" class="inline-flex items-center px-4 py-2 rounded-lg hover:bg-purple-50 transition-colors" title="Adicionar Cartão">
                        <svg class="w-5 h-5 text-purple-600 hover:text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Transações Pendentes -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pendentes</h3>
                <a href="<?php echo e(route('financial.transactions.index', ['status' => 'pendente'])); ?>" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium">
                    Ver todas
                </a>
            </div>
            
            <?php if($pendingTransactions->count() > 0): ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $pendingTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 <?php echo e($transaction->tipo === 'receita' ? 'bg-green-100 dark:bg-green-900/20' : 'bg-red-100 dark:bg-red-900/20'); ?> rounded-full flex items-center justify-center">
                                    <?php if($transaction->tipo === 'receita'): ?>
                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    <?php else: ?>
                                        <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white text-sm"><?php echo e($transaction->descricao); ?></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($transaction->data->format('d/m/Y')); ?></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-sm <?php echo e($transaction->tipo === 'receita' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'); ?>">
                                    R$ <?php echo e(number_format($transaction->valor, 2, ',', '.')); ?>

                                </p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">Nenhuma transação pendente</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Botão flutuante para adicionar transação -->
    <div class="fixed bottom-6 right-6">
        <div class="relative group">
            <button id="fab-button" class="w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </button>
            
            <!-- Menu de opções -->
            <div id="fab-menu" class="absolute bottom-16 right-0 hidden space-y-2">
                <a href="<?php echo e(route('financial.transactions.create')); ?>" class="flex items-center space-x-2 bg-white dark:bg-gray-800 shadow-lg rounded-lg px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Nova Transação</span>
                </a>
                <a href="<?php echo e(route('financial.banks.create')); ?>" class="flex items-center space-x-2 bg-white dark:bg-gray-800 shadow-lg rounded-lg px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Nova Conta</span>
                </a>
                <a href="<?php echo e(route('financial.credit-cards.create')); ?>" class="flex items-center space-x-2 bg-white dark:bg-gray-800 shadow-lg rounded-lg px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Novo Cartão</span>
                </a>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Controle do botão flutuante
    document.getElementById('fab-button').addEventListener('click', function() {
        const menu = document.getElementById('fab-menu');
        menu.classList.toggle('hidden');
    });
    
    // Fechar menu ao clicar fora
    document.addEventListener('click', function(event) {
        const fabButton = document.getElementById('fab-button');
        const fabMenu = document.getElementById('fab-menu');
        
        if (!fabButton.contains(event.target) && !fabMenu.contains(event.target)) {
            fabMenu.classList.add('hidden');
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\giro\resources\views/financial/dashboard.blade.php ENDPATH**/ ?>