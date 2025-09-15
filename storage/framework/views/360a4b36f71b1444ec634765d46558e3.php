<?php $__env->startSection('title', 'Portfólio'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 sm:mb-0">Portfólio</h3>
                    <div class="flex flex-wrap gap-2">
                        <a href="<?php echo e(route('portfolio.pipeline')); ?>" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <i class="fas fa-tasks mr-2"></i> Pipeline
                        </a>
                        <a href="<?php echo e(route('portfolio.categories.index')); ?>" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition-colors">
                            <i class="fas fa-tags mr-2"></i> Categorias
                        </a>
                        <a href="<?php echo e(route('portfolio.works.index')); ?>" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                            <i class="fas fa-briefcase mr-2"></i> Trabalhos
                        </a>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-briefcase text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-blue-100 text-sm font-medium">Total de Trabalhos</p>
                                <p class="text-2xl font-bold"><?php echo e($totalWorks ?? 0); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-tags text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-green-100 text-sm font-medium">Categorias</p>
                                <p class="text-2xl font-bold"><?php echo e($totalCategories ?? 0); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-6 text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-tasks text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-yellow-100 text-sm font-medium">Em Andamento</p>
                                <p class="text-2xl font-bold"><?php echo e($worksInProgress ?? 0); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Acesso Rápido</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <a href="<?php echo e(route('portfolio.pipeline')); ?>" class="group block">
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-tasks text-blue-600 dark:text-blue-400 text-xl"></i>
                                        </div>
                                    </div>
                                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-blue-600 transition-colors"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Pipeline</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Gerenciar fluxo de trabalho</p>
                            </div>
                        </a>
                        
                        <a href="<?php echo e(route('portfolio.works.index')); ?>" class="group block">
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-briefcase text-green-600 dark:text-green-400 text-xl"></i>
                                        </div>
                                    </div>
                                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-green-600 transition-colors"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Trabalhos</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Gerenciar portfólio</p>
                            </div>
                        </a>
                        
                        <a href="<?php echo e(route('portfolio.categories.index')); ?>" class="group block">
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-tags text-yellow-600 dark:text-yellow-400 text-xl"></i>
                                        </div>
                                    </div>
                                    <i class="fas fa-arrow-right text-gray-400 group-hover:text-yellow-600 transition-colors"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Categorias</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Organizar trabalhos</p>
                            </div>
                        </a>
                        
                        <a href="<?php echo e(route('portfolio.public.index')); ?>" class="group block" target="_blank">
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-eye text-red-600 dark:text-red-400 text-xl"></i>
                                        </div>
                                    </div>
                                    <i class="fas fa-external-link-alt text-gray-400 group-hover:text-red-600 transition-colors"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Público</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Visualizar portfólio</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\giro\resources\views/portfolio/index.blade.php ENDPATH**/ ?>