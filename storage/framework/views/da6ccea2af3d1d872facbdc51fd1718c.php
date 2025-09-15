<?php $__env->startSection('title', $autor->nome); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="<?php echo e(route('autores.index')); ?>" 
                   class="inline-flex items-center justify-center w-10 h-10 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div class="flex items-center space-x-4">
                    <img class="h-16 w-16 object-cover rounded-full" 
                         src="<?php echo e($autor->avatar ? Storage::url($autor->avatar) : 'data:image/svg+xml,%3csvg width=\'100\' height=\'100\' xmlns=\'http://www.w3.org/2000/svg\'%3e%3crect width=\'100\' height=\'100\' fill=\'%23f3f4f6\'/%3e%3ctext x=\'50%25\' y=\'50%25\' font-size=\'18\' text-anchor=\'middle\' alignment-baseline=\'middle\' font-family=\'monospace, sans-serif\' fill=\'%236b7280\'%3e' . strtoupper(substr($autor->nome, 0, 2)) . '%3c/text%3e%3c/svg%3e'); ?>" 
                         alt="<?php echo e($autor->nome); ?>">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><?php echo e($autor->nome); ?></h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Autor #<?php echo e($autor->id); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <a href="<?php echo e(route('autores.edit', $autor)); ?>" 
                   class="inline-flex items-center justify-center w-10 h-10 text-blue-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Layout Principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Card de Contato -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contato Rápido</h3>
                <div class="space-y-3">
                    <?php if($autor->email): ?>
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900 dark:text-white truncate"><?php echo e($autor->email); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($autor->telefone): ?>
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900 dark:text-white truncate"><?php echo e($autor->telefone); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($autor->whatsapp): ?>
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900 dark:text-white truncate"><?php echo e($autor->whatsapp); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Informações do Sistema -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações do Sistema</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">ID do Autor</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">#<?php echo e($autor->id); ?></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Data de Criação</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white"><?php echo e($autor->created_at->format('d/m/Y H:i')); ?></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Última Atualização</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white"><?php echo e($autor->updated_at->format('d/m/Y H:i')); ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informações Principais -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Card de Informações do Autor -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Informações do Autor</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome</label>
                        <p class="text-gray-900 dark:text-white font-medium"><?php echo e($autor->nome); ?></p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">E-mail</label>
                        <p class="text-gray-900 dark:text-white"><?php echo e($autor->email ?? 'Não informado'); ?></p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telefone</label>
                        <p class="text-gray-900 dark:text-white"><?php echo e($autor->telefone ?? 'Não informado'); ?></p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">WhatsApp</label>
                        <p class="text-gray-900 dark:text-white"><?php echo e($autor->whatsapp ?? 'Não informado'); ?></p>
                    </div>
                </div>
                
                <?php if($autor->biografia): ?>
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Biografia</label>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <p class="text-gray-900 dark:text-white whitespace-pre-wrap"><?php echo e($autor->biografia); ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Ações Rápidas -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ações Rápidas</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="<?php echo e(route('autores.edit', $autor)); ?>" 
                       class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar Autor
                    </a>
                    <?php if($autor->telefone): ?>
                    <button type="button" 
                            class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        Ligar
                    </button>
                    <?php endif; ?>
                    <?php if($autor->whatsapp): ?>
                    <button type="button" 
                            class="inline-flex items-center justify-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        WhatsApp
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Cards de Resumo -->
    <div class="mt-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Resumo de Trabalhos</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card Valor Total Gerado -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Valor Total Gerado</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">R$ <?php echo e(number_format($resumo['valor_total_gerado'], 2, ',', '.')); ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Card Total de Trabalhos -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Trabalhos</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($resumo['total_trabalhos']); ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Card Trabalhos Feitos -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Trabalhos Feitos</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($resumo['trabalhos_feitos']); ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Card Trabalhos em Andamento -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Em Andamento</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($resumo['trabalhos_em_andamento']); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Principais Parceiros -->
    <?php if($resumo['principais_parceiros']->count() > 0): ?>
    <div class="mt-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Principais Parceiros</h2>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php $__currentLoopData = $resumo['principais_parceiros']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parceiro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <img class="h-10 w-10 object-cover rounded-full" 
                         src="<?php echo e($parceiro->avatar ? Storage::url($parceiro->avatar) : 'data:image/svg+xml,%3csvg width=\'100\' height=\'100\' xmlns=\'http://www.w3.org/2000/svg\'%3e%3crect width=\'100\' height=\'100\' fill=\'%23f3f4f6\'/%3e%3ctext x=\'50%25\' y=\'50%25\' font-size=\'18\' text-anchor=\'middle\' alignment-baseline=\'middle\' font-family=\'monospace, sans-serif\' fill=\'%236b7280\'%3e' . strtoupper(substr($parceiro->nome, 0, 2)) . '%3c/text%3e%3c/svg%3e'); ?>" 
                         alt="<?php echo e($parceiro->nome); ?>">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate"><?php echo e($parceiro->nome); ?></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($parceiro->colaboracoes); ?> colaborações</p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Portfólio do Autor -->
    <div class="mt-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                <svg class="w-6 h-6 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                Portfólio
            </h2>
            <a href="<?php echo e(route('portfolio.works.create', ['autor_id' => $autor->id])); ?>" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Novo Trabalho
            </a>
        </div>

        <?php
            $portfolioWorks = $autor->portfolioWorks()->with(['category', 'images', 'client'])->latest()->take(6)->get();
        ?>

        <?php if($portfolioWorks->count() > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <?php $__currentLoopData = $portfolioWorks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $work): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="group bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow duration-200">
                        <?php if($work->featured_image): ?>
                            <div class="aspect-w-16 aspect-h-9 bg-gray-200 dark:bg-gray-600">
                                <img src="<?php echo e(Storage::url($work->featured_image)); ?>" 
                                     alt="<?php echo e($work->title); ?>" 
                                     class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-200">
                            </div>
                        <?php else: ?>
                            <div class="h-48 bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-800 dark:to-purple-900 flex items-center justify-center">
                                <svg class="w-12 h-12 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        <?php endif; ?>
                        
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-semibold text-gray-900 dark:text-white text-lg truncate"><?php echo e($work->title); ?></h3>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    <?php if($work->status === 'published'): ?> bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                    <?php elseif($work->status === 'draft'): ?> bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                    <?php else: ?> bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 <?php endif; ?>">
                                    <?php echo e(ucfirst($work->status)); ?>

                                </span>
                            </div>
                            
                            <?php if($work->excerpt): ?>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2"><?php echo e($work->excerpt); ?></p>
                            <?php endif; ?>
                            
                            <div class="flex items-center justify-between mb-3">
                                <?php if($work->category): ?>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300">
                                        <?php echo e($work->category->name); ?>

                                    </span>
                                <?php endif; ?>
                                
                                <?php if($work->client): ?>
                                    <span class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($work->client->nome); ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                <span><?php echo e($work->created_at->format('d/m/Y')); ?></span>
                                <div class="flex space-x-2">
                                    <a href="<?php echo e(route('portfolio.works.edit', $work)); ?>" 
                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <?php if($work->status === 'published'): ?>
                                        <a href="<?php echo e(route('public.portfolio.work', $work->slug)); ?>" 
                                           target="_blank"
                                           class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <?php if($autor->portfolioWorks()->count() > 6): ?>
                <div class="text-center">
                    <a href="<?php echo e(route('portfolio.works.index', ['autor_id' => $autor->id])); ?>" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                        Ver todos os trabalhos no portfólio (<?php echo e($autor->portfolioWorks()->count()); ?>)
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum trabalho no portfólio</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Este autor ainda não tem trabalhos publicados no portfólio.</p>
                <a href="<?php echo e(route('portfolio.works.create', ['autor_id' => $autor->id])); ?>" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Criar Primeiro Trabalho
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Lista de Trabalhos -->
    <div class="mt-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Trabalhos Realizados</h2>
        <?php if($autor->orcamentos->count() > 0): ?>
        <div class="space-y-4">
            <?php $__currentLoopData = $autor->orcamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orcamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                <a href="<?php echo e(route('orcamentos.show', $orcamento)); ?>" class="hover:text-blue-600 dark:hover:text-blue-400">
                                    Orçamento #<?php echo e($orcamento->id); ?>

                                </a>
                            </h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                <?php if($orcamento->status === 'aprovado'): ?> bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                <?php elseif($orcamento->status === 'pendente'): ?> bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                <?php elseif($orcamento->status === 'em_analise'): ?> bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                <?php else: ?> bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                <?php endif; ?>">
                                <?php echo e(ucfirst(str_replace('_', ' ', $orcamento->status))); ?>

                            </span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Cliente</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white"><?php echo e($orcamento->cliente->nome); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Valor Total</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">R$ <?php echo e(number_format($orcamento->valor_total, 2, ',', '.')); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Data de Criação</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white"><?php echo e($orcamento->created_at->format('d/m/Y')); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Valor Pago</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">R$ <?php echo e(number_format($orcamento->pagamentos->sum('valor'), 2, ',', '.')); ?></p>
                            </div>
                        </div>
                        
                        <?php if($orcamento->autores->count() > 1): ?>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Autores Parceiros</p>
                            <div class="flex flex-wrap gap-2">
                                <?php $__currentLoopData = $orcamento->autores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $autorParceiro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($autorParceiro->id !== $autor->id): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        <?php echo e($autorParceiro->nome); ?>

                                    </span>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="ml-4">
                        <a href="<?php echo e(route('orcamentos.show', $orcamento)); ?>" 
                           class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhum trabalho encontrado</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Este autor ainda não participou de nenhum orçamento.</p>
        </div>
        <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\giro_2\resources\views/autores/show.blade.php ENDPATH**/ ?>