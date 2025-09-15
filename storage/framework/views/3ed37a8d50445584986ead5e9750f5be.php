<?php $__env->startSection('title', 'Pipeline de Portfólio'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">
    <!-- Breadcrumb -->
    <?php if (isset($component)) { $__componentOriginale19f62b34dfe0bfdf95075badcb45bc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale19f62b34dfe0bfdf95075badcb45bc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumb','data' => ['items' => [
        ['label' => 'Dashboard', 'url' => route('dashboard'), 'icon' => 'fas fa-home'],
        ['label' => 'Portfólio', 'url' => '#'],
        ['label' => 'Pipeline']
    ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
        ['label' => 'Dashboard', 'url' => route('dashboard'), 'icon' => 'fas fa-home'],
        ['label' => 'Portfólio', 'url' => '#'],
        ['label' => 'Pipeline']
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
    <div class="mb-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Pipeline de Portfólio</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Orçamentos finalizados prontos para serem adicionados ao portfólio</p>
            </div>
        </div>
    </div>

<div>
    <!-- Cards de Resumo -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total de Orçamentos -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total de Orçamentos</p>
                    <p class="text-2xl font-bold"><?php echo e($orcamentos->count()); ?></p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- No Portfólio -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">No Portfólio</p>
                    <p class="text-2xl font-bold"><?php echo e($orcamentos->where('status', 'portfolio')->count()); ?></p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pendentes -->
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Pendentes</p>
                    <p class="text-2xl font-bold"><?php echo e($orcamentos->whereIn('status', ['pendente', 'em_analise'])->count()); ?></p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Projetos Finalizados -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Finalizados</p>
                    <p class="text-2xl font-bold"><?php echo e($orcamentos->whereNotNull('data_finalizacao')->count()); ?></p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

<div class="">
    <div class="text-gray-900 dark:text-gray-100">
        <!-- Filtro de Busca -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" 
                       id="search" 
                       name="search" 
                       class="block w-full pl-10 pr-12 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" 
                       placeholder="Buscar por cliente, título ou autor..." 
                       value="<?php echo e(request('search')); ?>">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <button type="button" 
                            id="clear-search" 
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" 
                            style="display: none;">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Pipeline Table -->
        <div class="">
            <div class="">
                <!-- Grid de Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="pipeline-cards">
                            <?php $__empty_1 = true; $__currentLoopData = $budgets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $budget): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <!-- Card do Orçamento -->
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 border border-gray-200 dark:border-gray-700" data-budget-id="<?php echo e($budget->id); ?>">
                                    <div class="p-6">
                                        <!-- Header do Card -->
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex-1">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                                    #<?php echo e($budget->numero); ?>

                                                </h3>
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                                    <?php echo e(Str::limit($budget->titulo, 50)); ?>

                                                </p>
                                            </div>
                                            <div class="ml-4">
                                                <?php if($budget->portfolioWork): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 status-badge">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        No Portfólio
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 status-badge">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Pendente
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Informações do Cliente com Avatar -->
                        <div class="mb-4">
                            <div class="flex items-center space-x-3">
                                <?php if($budget->cliente): ?>
                                    <?php if($budget->cliente->avatar): ?>
                                        <img src="<?php echo e(Storage::url($budget->cliente->avatar)); ?>" 
                                             alt="<?php echo e($budget->cliente->nome); ?>" 
                                             class="w-10 h-10 rounded-full object-cover">
                                    <?php else: ?>
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                            <span class="text-white font-semibold text-sm">
                                                <?php echo e(strtoupper(substr($budget->cliente->nome, 0, 1))); ?>

                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            <?php echo e($budget->cliente->nome); ?>

                                        </p>
                                        <?php if($budget->cliente->email): ?>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                <?php echo e($budget->cliente->email); ?>

                                            </p>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            Cliente não encontrado
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Informações do Autor com Avatar -->
                        <div class="mb-4">
                            <div class="flex items-center space-x-3">
                                <?php if($budget->autores && $budget->autores->count() > 0): ?>
                                    <?php $autor = $budget->autores->first(); ?>
                                    <?php if($autor->avatar): ?>
                                        <img src="<?php echo e(Storage::url($autor->avatar)); ?>" 
                                             alt="<?php echo e($autor->nome); ?>" 
                                             class="w-10 h-10 rounded-full object-cover">
                                    <?php else: ?>
                                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center">
                                            <span class="text-white font-semibold text-sm">
                                                <?php echo e(strtoupper(substr($autor->nome, 0, 1))); ?>

                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            <?php echo e($autor->nome); ?>

                                            <?php if($budget->autores->count() > 1): ?>
                                                <span class="text-xs text-gray-500">+<?php echo e($budget->autores->count() - 1); ?> outros</span>
                                            <?php endif; ?>
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Autor do projeto
                                        </p>
                                    </div>
                                <?php else: ?>
                                    <div class="w-10 h-10 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                            Sem autor
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                                        <!-- Data de Finalização -->
                        <div class="mb-4">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Finalização</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            <?php echo e($budget->data_finalizacao ? $budget->data_finalizacao->format('d/m/Y') : 'Não finalizado'); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                                        <!-- Ações -->
                                        <div class="flex items-center justify-end space-x-2 pt-4 border-t border-gray-200 dark:border-gray-600">
                                            <a href="<?php echo e(route('orcamentos.show', $budget)); ?>" 
                                               class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-800 hover:bg-blue-50 dark:text-blue-400 dark:hover:text-blue-300 dark:hover:bg-blue-900/20 rounded-lg transition-all duration-200"
                                               title="Visualizar Orçamento">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <?php if(!$budget->portfolioWork): ?>
                                                <button onclick="createPortfolioWork(<?php echo e($budget->id); ?>)" 
                                                        class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:text-green-800 hover:bg-green-50 dark:text-green-400 dark:hover:text-green-300 dark:hover:bg-green-900/20 rounded-lg transition-all duration-200 action-button"
                                                        title="Adicionar ao Portfólio">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                </button>
                                            <?php else: ?>
                                                <a href="<?php echo e(route('portfolio.works.show', $budget->portfolioWork)); ?>" 
                                                   class="inline-flex items-center justify-center w-8 h-8 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:text-indigo-300 dark:hover:bg-indigo-900/20 rounded-lg transition-all duration-200 action-button"
                                                   title="Ver no Portfólio">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                    </svg>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="col-span-full">
                                    <div class="text-center py-12">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhum orçamento encontrado</h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Não há orçamentos que correspondam aos filtros aplicados.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php if($budgets->hasPages()): ?>
                    <div class="mt-6">
                        <?php echo e($budgets->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<!-- Botão Flutuante de Atualização -->
<div class="fixed bottom-6 right-6 z-50">
    <button onclick="window.location.reload()" 
            class="group bg-indigo-600 hover:bg-indigo-700 text-white rounded-full p-4 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50"
            title="Atualizar página">
        <svg class="w-6 h-6 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        <span class="sr-only">Atualizar página</span>
    </button>
</div>

<!-- Create Portfolio Work Modal -->
<div id="createWorkModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Criar Trabalho de Portfólio</h3>
                <button onclick="closeCreateWorkModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="createWorkForm">
                <input type="hidden" id="budget_id" name="budget_id">
                <input type="hidden" id="orcamento_id" name="orcamento_id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="work_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título</label>
                        <input type="text" id="work_title" name="title" required 
                               class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="work_category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoria</label>
                        <select id="work_category" name="portfolio_category_id" required 
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecione uma categoria</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label for="work_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select id="work_status" name="status" 
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="draft">Rascunho</option>
                            <option value="published">Publicado</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label for="work_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                        <textarea id="work_description" name="description" rows="3" 
                                  class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                </div>
                <div class="flex justify-end mt-6 space-x-3">
                    <button type="button" onclick="closeCreateWorkModal()" 
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                        Criar Trabalho
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
let currentBudgetId = null;

function refreshPipeline() {
    window.location.reload();
}

// Funcionalidade de busca
let searchTimeout;

function performSearch() {
    const searchValue = document.getElementById('search').value;
    const params = new URLSearchParams();
    
    if (searchValue.trim()) {
        params.append('search', searchValue.trim());
    }
    
    window.location.href = '<?php echo e(route("portfolio.pipeline")); ?>?' + params.toString();
}

function clearSearch() {
    document.getElementById('search').value = '';
    document.getElementById('clear-search').style.display = 'none';
    window.location.href = '<?php echo e(route("portfolio.pipeline")); ?>';
}

// Event listeners para o campo de busca
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const clearButton = document.getElementById('clear-search');
    
    // Mostrar/ocultar botão de limpar
    function toggleClearButton() {
        if (searchInput.value.trim()) {
            clearButton.style.display = 'block';
        } else {
            clearButton.style.display = 'none';
        }
    }
    
    // Verificar valor inicial
    toggleClearButton();
    
    // Busca com delay
    searchInput.addEventListener('input', function() {
        toggleClearButton();
        
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            performSearch();
        }, 500);
    });
    
    // Busca ao pressionar Enter
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            clearTimeout(searchTimeout);
            performSearch();
        }
    });
    
    // Limpar busca
    clearButton.addEventListener('click', clearSearch);
});

function createPortfolioWork(budgetId) {
    currentBudgetId = budgetId;
    document.getElementById('budget_id').value = budgetId;
    document.getElementById('orcamento_id').value = budgetId;
    document.getElementById('createWorkModal').classList.remove('hidden');
}

function closeCreateWorkModal() {
    document.getElementById('createWorkModal').classList.add('hidden');
    document.getElementById('createWorkForm').reset();
    currentBudgetId = null;
}

document.getElementById('createWorkForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Mostrar loading no botão
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    submitButton.disabled = true;
    submitButton.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Criando...';
    
    const formData = new FormData(this);
    
    try {
        const response = await fetch('<?php echo e(route("portfolio.works.store")); ?>', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            closeCreateWorkModal();
            
            // Mostrar mensagem de sucesso
            if (result.message) {
                // Criar e mostrar notificação de sucesso
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                notification.textContent = result.message;
                document.body.appendChild(notification);
                
                // Remover notificação após 2 segundos e redirecionar
                setTimeout(() => {
                    notification.remove();
                    // Redirecionar para a listagem de trabalhos
                    window.location.href = '<?php echo e(route("portfolio.works.index")); ?>';
                }, 2000);
            } else {
                // Redirecionar imediatamente se não houver mensagem
                window.location.href = '<?php echo e(route("portfolio.works.index")); ?>';
            }
        } else {
            alert('Erro ao criar trabalho: ' + (result.message || 'Erro desconhecido'));
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Erro ao criar trabalho');
    } finally {
        // Restaurar botão sempre (sucesso ou erro)
        submitButton.disabled = false;
        submitButton.textContent = originalText;
    }
});

// Close modal when clicking outside
document.getElementById('createWorkModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCreateWorkModal();
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\giro\resources\views/portfolio/pipeline.blade.php ENDPATH**/ ?>