<?php $__env->startSection('title', 'Dashboard - Portfólio'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <!-- Header com Tags de Navegação -->
    <div class="mb-6">
        
        <!-- Tags de Navegação Rápida -->
        <div class="flex flex-wrap gap-2 mb-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                <i class="fas fa-chart-pie mr-2"></i>
                Dashboard
            </span>
            <a href="<?php echo e(route('portfolio.pipeline')); ?>" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                <i class="fas fa-tasks mr-2"></i>
                Pipeline
            </a>
            <a href="<?php echo e(route('portfolio.categories.index')); ?>" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                <i class="fas fa-folder mr-2"></i>
                Categorias
            </a>
            <a href="<?php echo e(route('portfolio.works.index')); ?>" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                <i class="fas fa-briefcase mr-2"></i>
                Trabalhos
            </a>
        </div>
    </div>
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard do Portfólio</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Visão geral do seu portfólio</p>
            </div>
        </div>
    </div>
    <!-- Cards de Estatísticas Principais -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total de Trabalhos -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total de Trabalhos</p>
                    <p class="text-3xl font-bold"><?php echo e($totalWorks); ?></p>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Trabalhos Publicados -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Publicados</p>
                    <p class="text-3xl font-bold"><?php echo e($publishedWorks); ?></p>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Categorias Ativas -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Categorias</p>
                    <p class="text-3xl font-bold"><?php echo e($activeCategories); ?></p>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pipeline -->
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">No Pipeline</p>
                    <p class="text-3xl font-bold"><?php echo e($pipelineCount); ?></p>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Trabalhos por Categoria -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Trabalhos por Categoria</h3>
            </div>
            <div class="p-6">
                <?php if($worksByCategory->count() > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $worksByCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-blue-500 mr-3"></div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white"><?php echo e($category->name); ?></span>
                                </div>
                                <span class="text-sm text-gray-600 dark:text-gray-400"><?php echo e($category->works_count); ?> trabalhos</span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">Nenhuma categoria com trabalhos encontrada.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Trabalhos Recentes -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Trabalhos Recentes</h3>
            </div>
            <div class="p-6">
                <?php if($recentWorks->count() > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $recentWorks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $work): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white"><?php echo e($work->title); ?></h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        <?php echo e($work->category->name ?? 'Sem categoria'); ?> • 
                                        <?php echo e($work->created_at->format('d/m/Y')); ?>

                                    </p>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    <?php echo e($work->status === 'published' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'); ?>">
                                    <?php echo e($work->status === 'published' ? 'Publicado' : 'Rascunho'); ?>

                                </span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">Nenhum trabalho encontrado.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Pipeline Recente -->
    <?php if($recentPipeline->count() > 0): ?>
        <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Orçamentos no Pipeline</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <?php $__currentLoopData = $recentPipeline; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orcamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white"><?php echo e($orcamento->titulo); ?></h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    <?php echo e($orcamento->cliente->nome); ?> • 
                                    <?php echo e($orcamento->created_at->format('d/m/Y')); ?>

                                </p>
                            </div>
                            <a href="<?php echo e(route('portfolio.pipeline')); ?>" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 text-sm font-medium">
                                Ver Pipeline
                            </a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Acesso Rápido -->
    <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Acesso Rápido</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="<?php echo e(route('portfolio.pipeline')); ?>" class="flex items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-tasks text-orange-500 mr-3"></i>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Pipeline</span>
                </a>
                <a href="<?php echo e(route('portfolio.works.create')); ?>" class="flex items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-plus text-green-500 mr-3"></i>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Novo Trabalho</span>
                </a>
                <a href="<?php echo e(route('portfolio.categories.index')); ?>" class="flex items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-folder text-purple-500 mr-3"></i>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Categorias</span>
                </a>
                <a href="<?php echo e(route('portfolio.works.index')); ?>" class="flex items-center p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-briefcase text-blue-500 mr-3"></i>
                    <span class="text-sm font-medium text-gray-900 dark:text-white">Todos os Trabalhos</span>
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\giro_2\resources\views/portfolio/dashboard.blade.php ENDPATH**/ ?>