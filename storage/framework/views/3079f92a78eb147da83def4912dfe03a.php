<?php $__env->startSection('title', 'Histórico do Projeto - ' . $orcamento->titulo); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="<?php echo e(route('dashboard')); ?>" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                
                    Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="<?php echo e(route('orcamentos.index')); ?>" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Orçamentos</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="<?php echo e(route('orcamentos.show', $orcamento)); ?>" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white"><?php echo e(Str::limit($orcamento->titulo, 30)); ?></a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Histórico</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
               <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Histórico do Projeto</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1"><?php echo e($orcamento->titulo); ?></p>
            </div>
            <div class="flex items-center">
                <a href="<?php echo e(route('orcamentos.show', $orcamento)); ?>" class="inline-flex items-center p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 rounded-lg transition-all duration-200" title="Voltar ao Orçamento">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

 
    <!-- Timeline -->
    <div>
        <?php if($entries->count() > 0): ?>
            <div class="max-w-4xl mx-auto mt-10 px-4 sm:px-6">
                <!-- Timeline Container -->
                <div class="relative">
                    <!-- Vertical Line -->
                    <div class="absolute left-36 top-0 bottom-0 w-0.5 bg-gray-300 dark:bg-gray-600"></div>
                    
                    <?php $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="relative flex items-start mb-8 group">
                            <!-- Date/Time Column -->
                            <div class="w-32 flex-shrink-0 text-right pr-6">
                                <div class="text-lg font-bold text-gray-900 dark:text-white">
                                    <?php echo e($entry->entry_date->locale('pt_BR')->isoFormat('DD [de] MMMM [de] YYYY')); ?>

                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    <?php echo e($entry->entry_date->format('H:i')); ?>

                                </div>
                            </div>
                            
                            <!-- Timeline Dot -->
                            <div class="relative flex-shrink-0">
                                <div class="w-3 h-3 bg-gray-800 dark:bg-gray-200 rounded-full border-2 border-white dark:border-gray-800 shadow-sm z-10 relative"></div>
                            </div>
                            
                            <!-- Content Column -->
                            <div class="flex-1 ml-6">
                                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 shadow-sm hover:shadow-md transition-shadow duration-200" data-entry-id="<?php echo e($entry->id); ?>">
                                    <!-- Header with Title, Type, Checkbox and Actions -->
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex items-start gap-3 flex-1">
                                            <!-- Checkbox -->
                                            <div class="flex-shrink-0 mt-1">
                                                <input type="checkbox" 
                                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 status-checkbox" 
                                                       data-entry-id="<?php echo e($entry->id); ?>"
                                                       <?php echo e($entry->completed ? 'checked' : ''); ?>>
                                            </div>
                                            
                                            <!-- Title -->
                            <div class="entry-content flex-1" data-entry-id="<?php echo e($entry->id); ?>">
                                <h3 id="title-<?php echo e($entry->id); ?>" class="text-lg font-semibold text-gray-900 dark:text-white editable-title <?php echo e($entry->completed ? 'line-through' : ''); ?>" 
                                    contenteditable="false" 
                                    data-original="<?php echo e($entry->title); ?>">
                                    <?php echo e($entry->title); ?>

                                </h3>
                                
                                <!-- Seção de gerenciamento de arquivos (visível apenas no modo de edição) -->
                                <div class="file-management-section hidden mt-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        Gerenciar Arquivos
                                    </h4>
                                    
                                    <!-- Área de upload -->
                                    <div id="upload-area-<?php echo e($entry->id); ?>" class="upload-area mb-3 p-3 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg text-center hover:border-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors cursor-pointer" 
                                         data-entry-id="<?php echo e($entry->id); ?>">
                                        <svg class="w-6 h-6 mx-auto mb-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Clique para selecionar arquivos ou arraste aqui</p>
                                        <p class="text-xs text-gray-400">Máximo 10MB por arquivo</p>
                                        <input type="file" id="file-input-<?php echo e($entry->id); ?>" class="file-input hidden" multiple accept="image/*,.pdf,.doc,.docx,.txt,.xlsx,.xls" data-entry-id="<?php echo e($entry->id); ?>">
                                    </div>
                                    
                                    <!-- Lista de arquivos para upload -->
                                    <div id="file-preview-<?php echo e($entry->id); ?>" class="upload-preview hidden mb-4">
                                        <h5 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Arquivos selecionados:</h5>
                                        <div class="upload-files-list space-y-2"></div>
                                        <div class="mt-3 flex gap-2">
                                            <button type="button" id="upload-files-<?php echo e($entry->id); ?>" class="upload-files-btn px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors hidden">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                                Fazer Upload
                                            </button>
                                            <button type="button" id="cancel-upload-<?php echo e($entry->id); ?>" class="cancel-upload-btn px-3 py-1 bg-gray-500 text-white text-sm rounded hover:bg-gray-600 transition-colors hidden">
                                                Cancelar
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Arquivos existentes -->
                                    <div class="existing-files">
                                        <h5 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Arquivos existentes:</h5>
                                        <div class="files-grid grid grid-cols-2 md:grid-cols-3 gap-3" data-entry-id="<?php echo e($entry->id); ?>">
                                            <?php if($entry->files && count($entry->files) > 0): ?>
                                                <?php $__currentLoopData = $entry->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="file-item bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 transition-colors relative group" data-file-id="<?php echo e($file->id); ?>">
                                                        <!-- Botão de exclusão -->
                                                        <button type="button" class="delete-file-btn absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600 flex items-center justify-center" 
                                                                data-file-id="<?php echo e($file->id); ?>" data-file-name="<?php echo e($file->original_name); ?>">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                        
                                                        <?php if($file->isImage()): ?>
                                                            <!-- Imagem -->
                                                            <div class="aspect-square bg-gray-200 rounded-md mb-2 overflow-hidden cursor-pointer" 
                                                                 onclick="openImageModal('<?php echo e($file->download_url); ?>', '<?php echo e(addslashes($file->original_name)); ?>')">
                                                                <img src="<?php echo e($file->download_url); ?>" 
                                                                     alt="<?php echo e($file->original_name); ?>" 
                                                                     class="w-full h-full object-cover hover:scale-105 transition-transform">
                                                            </div>
                                                        <?php else: ?>
                                                            <!-- Outros arquivos -->
                                                            <div class="aspect-square bg-blue-100 rounded-md mb-2 flex items-center justify-center">
                                                                <?php
                                                                    $extension = strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION));
                                                                ?>
                                                                <?php if($extension === 'pdf'): ?>
                                                                    <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                <?php elseif(in_array($extension, ['doc', 'docx'])): ?>
                                                                    <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                <?php else: ?>
                                                                    <svg class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        <p class="text-xs text-gray-600 dark:text-gray-400 truncate" title="<?php echo e($file->original_name); ?>"><?php echo e($file->original_name); ?></p>
                                                        <p class="text-xs text-gray-400"><?php echo e(number_format($file->file_size / 1024, 1)); ?> KB</p>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <div class="col-span-full text-center py-4 text-gray-500 dark:text-gray-400 text-sm">
                                                    Nenhum arquivo anexado
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                        </div>
                                        
                                        <div class="flex items-center gap-2 ml-4">
                                            <!-- Type Badge -->
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                <?php switch($entry->type):
                                                    case ('milestone'): ?> bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 <?php break; ?>
                                                    <?php case ('issue'): ?> bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 <?php break; ?>
                                                    <?php case ('resolution'): ?> bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 <?php break; ?>
                                                    <?php case ('update'): ?> bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 <?php break; ?>
                                                    <?php case ('note'): ?> bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 <?php break; ?>
                                                    <?php case ('meeting'): ?> bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200 <?php break; ?>
                                                    <?php case ('decision'): ?> bg-teal-100 text-teal-800 dark:bg-teal-900 dark:text-teal-200 <?php break; ?>
                                                    <?php default: ?> bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                                <?php endswitch; ?>">
                                                <?php echo e(ucfirst($entry->type)); ?>

                                            </span>
                                            
                                            <!-- Action Buttons -->
                                            <div class="flex items-center gap-1 transition-opacity duration-200">
                                                <!-- Edit/Save Buttons -->
                                                <button type="button" 
                                                        class="edit-toggle-btn p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded transition-colors" 
                                                        data-entry-id="<?php echo e($entry->id); ?>"
                                                        title="Editar entrada">
                                                    <svg class="w-4 h-4 edit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    <svg class="w-4 h-4 save-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                                <button type="button" 
                                                        class="cancel-edit-btn p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors hidden" 
                                                        data-entry-id="<?php echo e($entry->id); ?>"
                                                        title="Cancelar edição">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                                
                                                <!-- Delete Button -->
                                                <button type="button" 
                                                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors delete-btn" 
                                                        data-entry-id="<?php echo e($entry->id); ?>"
                                                        title="Excluir">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Description -->
                    <p id="description-<?php echo e($entry->id); ?>" class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed mb-3 editable-description <?php echo e($entry->completed ? 'line-through' : ''); ?>" 
                       contenteditable="false" 
                       data-original="<?php echo e($entry->description); ?>">
                        <?php echo e($entry->description); ?>

                    </p>
                                    
                                    <!-- User Info with Avatar -->
                                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mb-3">
                                        <?php if($entry->user): ?>
                                            <div class="flex items-center mr-4">
                                                <div class="w-6 h-6 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white text-xs font-semibold mr-2">
                                                    <?php echo e(strtoupper(substr($entry->user->name, 0, 1))); ?>

                                                </div>
                                                <span><?php echo e($entry->user->name); ?></span>
                                            </div>
                                        <?php else: ?>
                                            <div class="flex items-center mr-4">
                                                <div class="w-6 h-6 rounded-full bg-gray-400 flex items-center justify-center text-white text-xs font-semibold mr-2">
                                                    ?
                                                </div>
                                                <span>Usuário não encontrado</span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if($orcamento->cliente): ?>
                                            <div class="flex items-center">
                                                <div class="w-6 h-6 rounded-full bg-gradient-to-r from-green-500 to-teal-600 flex items-center justify-center text-white text-xs font-semibold mr-2">
                                                    <?php echo e(strtoupper(substr($orcamento->cliente->nome, 0, 1))); ?>

                                                </div>
                                                <span>Cliente: <?php echo e($orcamento->cliente->nome); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Files -->
                                    <?php if($entry->files->count() > 0): ?>
                                        <div class="border-t border-gray-100 dark:border-gray-700 pt-3">
                                            <h4 class="text-xs font-semibold text-gray-900 dark:text-white mb-2 flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                                Arquivos (<?php echo e($entry->files->count()); ?>)
                                            </h4>
                                            <div class="grid grid-cols-4 sm:grid-cols-6 gap-2">
                                                <?php $__currentLoopData = $entry->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="relative group">
                                                        <?php if($file->isImage()): ?>
                                                            <div class="aspect-square rounded-md overflow-hidden border border-gray-200 dark:border-gray-600 hover:border-blue-400 transition-colors">
                                                                <img src="<?php echo e($file->download_url); ?>" 
                                                                     alt="<?php echo e($file->original_name); ?>" 
                                                                     class="w-full h-full object-cover cursor-pointer hover:scale-105 transition-transform duration-200"
                                                                     onclick="openImageModal('<?php echo e($file->download_url); ?>', '<?php echo e(addslashes($file->original_name)); ?>')">
                                                            </div>
                                                        <?php else: ?>
                                                            <?php
                                                                $extension = strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION));
                                                            ?>
                                                            <div class="aspect-square flex items-center justify-center cursor-pointer relative transition-colors"
                                                                 onclick="window.open('<?php echo e($file->download_url); ?>', '_blank')"
                                                                 title="<?php echo e($file->original_name); ?>">
                                                                <?php switch($extension):
                                                                    case ('pdf'): ?>
                                                                        <svg class="w-12 h-12 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM9.498 16.19c-.309.29-.765.42-1.296.42a2.23 2.23 0 0 1-.308-.018v1.426H7v-3.936A7.558 7.558 0 0 1 8.219 14c.557 0 .953.106 1.22.319.254.202.426.533.426.923-.001.392-.131.723-.367.948zm3.807 1.355c-.42.349-1.059.515-1.84.515-.468 0-.799-.03-1.024-.06v-3.917A7.947 7.947 0 0 1 11.66 14c.757 0 1.249.136 1.633.426.415.308.675.799.675 1.504 0 .763-.279 1.29-.663 1.615zM17 14.77h-1.532v.911H16.9v.734h-1.432v1.604h-.906V14.03H17v.74zM14 9h-1V4l5 5h-4z"/>
                                                                        </svg>
                                                                        <?php break; ?>
                                                                    <?php case ('doc'): ?>
                                                                    <?php case ('docx'): ?>
                                                                        <svg class="w-12 h-12 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM16.5 18L15 15h-1v3h-1v-4h1.5l1.5 3 1.5-3H19v4h-1v-3l-1.5 3zM6 9h5v2H6V9zm0 4h8v2H6v-2zM14 9V4l5 5h-4z"/>
                                                                        </svg>
                                                                        <?php break; ?>
                                                                    <?php default: ?>
                                                                        <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                                                        </svg>
                                                                <?php endswitch; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Paginação -->
            <div class="flex justify-center mt-8">
                <?php echo e($entries->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <div class="mb-4">
                    <i class="fas fa-history text-6xl text-gray-400 dark:text-gray-500"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhuma entrada no histórico</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Comece adicionando a primeira entrada ao histórico do projeto.</p>
                <a href="<?php echo e(route('orcamentos.historico.create', $orcamento)); ?>" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Adicionar Primeira Entrada
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Botão Flutuante Nova Entrada -->
<div class="fixed bottom-6 right-6 z-50">
    <a href="<?php echo e(route('orcamentos.historico.create', $orcamento)); ?>" class="inline-flex items-center justify-center w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105" title="Nova Entrada">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
    </a>
</div>

<!-- Modal para confirmação de exclusão -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Confirmar Exclusão</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Esta ação não pode ser desfeita.</p>
                </div>
            </div>
            
            <div class="mb-6">
                <p class="text-gray-700 dark:text-gray-300">Tem certeza que deseja excluir a entrada:</p>
                <p id="deleteItemTitle" class="font-semibold text-gray-900 dark:text-white mt-2 p-2 bg-gray-50 dark:bg-gray-700 rounded"></p>
            </div>
            
            <div class="flex gap-3 justify-end">
                <button type="button" id="cancelDelete" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Cancelar
                </button>
                <button type="button" id="confirmDelete" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                    Excluir
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para visualização de imagens -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
        <div class="absolute bottom-4 left-4 bg-black bg-opacity-50 text-white px-3 py-1 rounded">
            <span id="modalImageName"></span>
        </div>
    </div>
</div>

<!-- CSS para edição inline -->
<style>
    .editable-title[contenteditable="true"],
    .editable-description[contenteditable="true"] {
        background-color: #f9fafb;
        border: 2px solid #3b82f6;
        border-radius: 4px;
        padding: 4px 8px;
        outline: none;
        transition: all 0.2s ease;
    }
    
    .dark .editable-title[contenteditable="true"],
    .dark .editable-description[contenteditable="true"] {
        background-color: #374151;
        border-color: #60a5fa;
        color: #f9fafb;
    }
    
    .editing-mode {
        /* Removido fundo azul - apenas destaque nos campos editáveis */
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    
    .dark .editing-mode {
        /* Removido fundo azul no modo escuro */
    }
    
    /* Estilos para gerenciamento de arquivos */
    .file-management {
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        padding: 16px;
        margin-top: 12px;
        background-color: #f9fafb;
        transition: all 0.2s ease;
    }
    
    .dark .file-management {
        border-color: #4b5563;
        background-color: #374151;
    }
    
    .file-upload-area {
        border: 2px dashed #9ca3af;
        border-radius: 6px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .file-upload-area:hover {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }
    
    .dark .file-upload-area:hover {
        border-color: #60a5fa;
        background-color: #1e3a8a;
    }
    
    .file-upload-area.dragover {
        border-color: #3b82f6;
        background-color: #dbeafe;
    }
    
    .dark .file-upload-area.dragover {
        border-color: #60a5fa;
        background-color: #1e40af;
    }
    
    .file-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
    }
    
    .file-preview-item {
        position: relative;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 8px;
        background-color: white;
        display: flex;
        align-items: center;
        gap: 8px;
        max-width: 200px;
    }
    
    .dark .file-preview-item {
        border-color: #4b5563;
        background-color: #1f2937;
    }
    
    .file-preview-item img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 4px;
    }
    
    .file-preview-item .file-info {
        flex: 1;
        min-width: 0;
    }
    
    .file-preview-item .file-name {
        font-size: 12px;
        font-weight: 500;
        color: #374151;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .dark .file-preview-item .file-name {
        color: #f3f4f6;
    }
    
    .file-preview-item .file-size {
        font-size: 11px;
        color: #6b7280;
    }
    
    .dark .file-preview-item .file-size {
        color: #9ca3af;
    }
    
    .file-remove-btn {
        position: absolute;
        top: -6px;
        right: -6px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #ef4444;
        color: white;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        transition: background-color 0.2s;
    }
    
    .file-remove-btn:hover {
        background-color: #dc2626;
    }
    
    .upload-progress {
        width: 100%;
        height: 4px;
        background-color: #e5e7eb;
        border-radius: 2px;
        overflow: hidden;
        margin-top: 8px;
    }
    
    .upload-progress-bar {
        height: 100%;
        background-color: #3b82f6;
        transition: width 0.3s ease;
    }
    
    .existing-files {
        margin-top: 12px;
    }
    
    .existing-file-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        margin-bottom: 6px;
        background-color: white;
    }
    
    .dark .existing-file-item {
        border-color: #374151;
        background-color: #1f2937;
    }
    
    .existing-file-item img {
        width: 32px;
        height: 32px;
        object-fit: cover;
        border-radius: 4px;
    }
    
    .existing-file-info {
        flex: 1;
        min-width: 0;
    }
    
    .existing-file-name {
        font-size: 13px;
        font-weight: 500;
        color: #374151;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .dark .existing-file-name {
        color: #f3f4f6;
    }
</style>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-25 z-40 hidden items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl">
        <div class="flex items-center gap-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
            <span class="text-gray-700 dark:text-gray-300">Processando...</span>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.timeline-container {
    position: relative;
    padding: 20px 0;
}

.timeline-container::before {
    content: '';
    position: absolute;
    left: 50%;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
    transform: translateX(-50%);
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
    width: 48%;
}

.timeline-left {
    left: 0;
}

.timeline-right {
    left: 52%;
}

.timeline-marker {
    position: absolute;
    top: 20px;
    width: 40px;
    height: 40px;
    z-index: 10;
}

.timeline-left .timeline-marker {
    right: -20px;
}

.timeline-right .timeline-marker {
    left: -20px;
}

.timeline-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
    border: 3px solid white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.timeline-icon.milestone { background-color: #0d6efd; }
.timeline-icon.update { background-color: #6f42c1; }
.timeline-icon.note { background-color: #fd7e14; }
.timeline-icon.meeting { background-color: #20c997; }
.timeline-icon.decision { background-color: #198754; }
.timeline-icon.issue { background-color: #dc3545; }
.timeline-icon.resolution { background-color: #198754; }

.timeline-content {
    position: relative;
}

.timeline-left .timeline-content {
    margin-right: 30px;
}

.timeline-right .timeline-content {
    margin-left: 30px;
}

.timeline-content .card {
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.timeline-content .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.file-item {
    transition: background-color 0.2s;
}

.file-item:hover {
    background-color: #f8f9fa;
}

/* Responsivo */
@media (max-width: 768px) {
    .timeline-container::before {
        left: 20px;
    }
    
    .timeline-item {
        width: calc(100% - 40px);
        left: 40px !important;
    }
    
    .timeline-marker {
        left: -20px !important;
        right: auto !important;
    }
    
    .timeline-content {
        margin-left: 20px !important;
        margin-right: 0 !important;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== SCRIPT CARREGADO ===');
    
    // Verificar se existem botões de exclusão na página
    const deleteButtons = document.querySelectorAll('.delete-btn');
    console.log('Botões de exclusão encontrados:', deleteButtons.length);
    console.log('Botões:', deleteButtons);
    
    // Verificar se há elementos com data-entry-id
    const elementsWithEntryId = document.querySelectorAll('[data-entry-id]');
    console.log('Elementos com data-entry-id:', elementsWithEntryId.length);
    console.log('Elementos:', elementsWithEntryId);
    // Verificar se os elementos existem antes de adicionar event listeners
    const filterType = document.getElementById('filter-type');
    const filterDateStart = document.getElementById('filter-date-start');
    const filterDateEnd = document.getElementById('filter-date-end');
    const applyFiltersBtn = document.getElementById('apply-filters');
    const clearFiltersBtn = document.getElementById('clear-filters');
    const timelineItems = document.querySelectorAll('.timeline-item');

    function applyFilters() {
        if (!filterType || !filterDateStart || !filterDateEnd) return;
        
        const typeFilter = filterType.value;
        const startDate = filterDateStart.value;
        const endDate = filterDateEnd.value;

        timelineItems.forEach(item => {
            let show = true;

            // Filtro por tipo
            if (typeFilter && item.dataset.type !== typeFilter) {
                show = false;
            }

            // Filtro por data
            if (startDate || endDate) {
                const itemDate = item.dataset.date;
                if (startDate && itemDate < startDate) show = false;
                if (endDate && itemDate > endDate) show = false;
            }

            item.style.display = show ? 'block' : 'none';
        });
    }

    function clearFilters() {
        if (filterType) filterType.value = '';
        if (filterDateStart) filterDateStart.value = '';
        if (filterDateEnd) filterDateEnd.value = '';
        timelineItems.forEach(item => {
            item.style.display = 'block';
        });
    }

    // Adicionar event listeners apenas se os elementos existirem
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', applyFilters);
    }
    
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', clearFilters);
    }

    // Aplicar filtros em tempo real
    [filterType, filterDateStart, filterDateEnd].forEach(element => {
        if (element) {
            element.addEventListener('change', applyFilters);
        }
    });

    // ===== VARIÁVEIS GLOBAIS =====
    let currentEditingId = null;
    let selectedFiles = [];

    // Função para mostrar loading
    function showLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
        }
    }

    // Função para esconder loading
    function hideLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
        }
    }

    // Função para mostrar notificação
    function showNotification(message, type = 'success') {
        // Criar elemento de notificação
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Animar entrada
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Remover após 3 segundos
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // Event listeners para checkboxes de status
    document.addEventListener('change', async function(e) {
        if (e.target.classList.contains('status-checkbox')) {
            const entryId = e.target.dataset.entryId;
            const isCompleted = e.target.checked;
            
            showLoading();
            
            try {
                const response = await fetch(`<?php echo e(route('orcamentos.historico.toggle-status', [$orcamento, '__ID__'])); ?>`.replace('__ID__', entryId), {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ completed: isCompleted })
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (data.success) {
                    // Atualizar visual do item
                    const entryCard = document.querySelector(`[data-entry-id="${entryId}"]`);
                    const title = document.getElementById(`title-${entryId}`);
                    const description = document.getElementById(`description-${entryId}`);
                    
                    if (isCompleted) {
                        title.classList.add('line-through');
                        description.classList.add('line-through');
                    } else {
                        title.classList.remove('line-through');
                        description.classList.remove('line-through');
                    }
                    
                    showNotification(data.message);
                } else {
                    e.target.checked = !isCompleted; // Reverter checkbox
                    showNotification(data.message || 'Erro ao atualizar status', 'error');
                }
            } catch (error) {
                e.target.checked = !isCompleted; // Reverter checkbox
                showNotification(error.message || 'Erro de conexão', 'error');
                console.error('Error:', error);
            } finally {
                hideLoading();
            }
        }
    });

    // Event listeners para edição inline
    document.addEventListener('click', function(e) {
        // Botão de editar/salvar
        if (e.target.closest('.edit-toggle-btn')) {
            const button = e.target.closest('.edit-toggle-btn');
            const entryId = button.dataset.entryId;
            const entryContent = document.querySelector(`[data-entry-id="${entryId}"]`);
            const titleElement = entryContent.querySelector('.editable-title');
            const descriptionElement = entryContent.querySelector('.editable-description');
            const editIcon = button.querySelector('.edit-icon');
            const saveIcon = button.querySelector('.save-icon');
            const cancelButton = button.parentElement.querySelector('.cancel-edit-btn');
            
            // Se está editando, salvar
            if (titleElement.contentEditable === 'true') {
                saveInlineEdit(entryId, titleElement, descriptionElement, button, editIcon, saveIcon, cancelButton);
            } else {
                // Entrar em modo de edição
                startInlineEdit(titleElement, descriptionElement, button, editIcon, saveIcon, cancelButton, entryContent);
            }
        }
        
        // Botão de cancelar edição
        if (e.target.closest('.cancel-edit-btn')) {
            const button = e.target.closest('.cancel-edit-btn');
            const entryId = button.dataset.entryId;
            const entryContent = document.querySelector(`[data-entry-id="${entryId}"]`);
            const titleElement = entryContent.querySelector('.editable-title');
            const descriptionElement = entryContent.querySelector('.editable-description');
            const editButton = button.parentElement.querySelector('.edit-toggle-btn');
            const editIcon = editButton.querySelector('.edit-icon');
            const saveIcon = editButton.querySelector('.save-icon');
            
            cancelInlineEdit(titleElement, descriptionElement, editButton, editIcon, saveIcon, button, entryContent);
        }
    });
    
    function startInlineEdit(titleElement, descriptionElement, editButton, editIcon, saveIcon, cancelButton, entryContent) {
        // Salvar valores originais
        titleElement.dataset.original = titleElement.textContent.trim();
        descriptionElement.dataset.original = descriptionElement.textContent.trim();
        
        // Ativar edição
        titleElement.contentEditable = 'true';
        descriptionElement.contentEditable = 'true';
        
        // Adicionar classe de edição
        entryContent.classList.add('editing-mode');
        
        // Trocar ícones
        editIcon.classList.add('hidden');
        saveIcon.classList.remove('hidden');
        cancelButton.classList.remove('hidden');
        
        // Mostrar seção de gerenciamento de arquivos
        const fileManagement = entryContent.querySelector('.file-management-section');
        if (fileManagement) {
            fileManagement.classList.remove('hidden');
            // Inicializar gerenciamento de arquivos
            const entryId = editButton.dataset.entryId;
            initFileManagement(entryId);
        }
        
        // Focar no título
        titleElement.focus();
        
        // Selecionar todo o texto
        const range = document.createRange();
        range.selectNodeContents(titleElement);
        const selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);
    }
    
    function cancelInlineEdit(titleElement, descriptionElement, editButton, editIcon, saveIcon, cancelButton, entryContent) {
        // Restaurar valores originais
        titleElement.textContent = titleElement.dataset.original;
        descriptionElement.textContent = descriptionElement.dataset.original;
        
        // Desativar edição
        titleElement.contentEditable = 'false';
        descriptionElement.contentEditable = 'false';
        
        // Remover classe de edição
        entryContent.classList.remove('editing-mode');
        
        // Ocultar seção de gerenciamento de arquivos
        const fileManagement = entryContent.querySelector('.file-management-section');
        if (fileManagement) {
            fileManagement.classList.add('hidden');
        }
        
        // Limpar arquivos selecionados
        selectedFiles = [];
        currentEditingId = null;
        
        // Trocar ícones
        editIcon.classList.remove('hidden');
        saveIcon.classList.add('hidden');
        cancelButton.classList.add('hidden');
    }
    
    async function saveInlineEdit(entryId, titleElement, descriptionElement, editButton, editIcon, saveIcon, cancelButton) {
        const title = titleElement.textContent.trim();
        const description = descriptionElement.textContent.trim();
        
        if (!title || !description) {
            showNotification('Título e descrição são obrigatórios', 'error');
            return;
        }
        
        // Buscar o elemento da entrada
        const entryContent = document.querySelector(`[data-entry-id="${entryId}"]`);
        if (!entryContent) {
            console.error('Elemento da entrada não encontrado para ID:', entryId);
            showNotification('Erro: Elemento da entrada não encontrado', 'error');
            return;
        }
        
        showLoading();
        
        try {
            const response = await fetch(`<?php echo e(route('orcamentos.historico.update', [$orcamento, '__ID__'])); ?>`.replace('__ID__', entryId), {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    title: title,
                    description: description
                })
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                // Desativar edição
                    titleElement.contentEditable = 'false';
                    descriptionElement.contentEditable = 'false';
                    
                    // Remover classe de edição
                    entryContent.classList.remove('editing-mode');
                    
                    // Ocultar seção de gerenciamento de arquivos
                    const fileManagement = entryContent.querySelector('.file-management-section');
                    if (fileManagement) {
                        fileManagement.classList.add('hidden');
                    }
                    
                    // Limpar arquivos selecionados
                    selectedFiles = [];
                    currentEditingId = null;
                    
                    // Trocar ícones
                    editIcon.classList.remove('hidden');
                    saveIcon.classList.add('hidden');
                    cancelButton.classList.add('hidden');
                
                // Atualizar valores originais
                titleElement.dataset.original = title;
                descriptionElement.dataset.original = description;
                
                showNotification(data.message);
            } else {
                throw new Error(data.message || 'Erro ao atualizar entrada');
            }
        } catch (error) {
            showNotification(error.message || 'Erro de conexão', 'error');
            console.error('Error:', error);
        } finally {
            hideLoading();
        }
    }

    // Variáveis para controle do modal de exclusão
    let entryToDelete = null;
    
    // Event listeners para botões de exclusão
    console.log('Registrando event listener de clique...');
    
    // Teste simples para verificar se o event listener funciona
    console.log('Testando se document.addEventListener funciona...');
    
    // Usar uma abordagem mais robusta
    function setupClickListener() {
        console.log('Configurando click listener...');
        document.addEventListener('click', function(e) {
            console.log('EVENT LISTENER FUNCIONANDO! Clique detectado em:', e.target.tagName);
        console.log('=== CLIQUE DETECTADO ===');
        console.log('Target:', e.target);
        console.log('Target classes:', e.target.className);
        console.log('Target tagName:', e.target.tagName);
        console.log('Target closest .delete-btn:', e.target.closest('.delete-btn'));
        
        // Log adicional para debug
        if (e.target.tagName === 'BUTTON' || e.target.closest('button')) {
            console.log('BOTÃO CLICADO!');
            const button = e.target.tagName === 'BUTTON' ? e.target : e.target.closest('button');
            console.log('Botão:', button);
            console.log('Classes do botão:', button.className);
            console.log('Dataset do botão:', button.dataset);
        }
        if (e.target.closest('.delete-btn')) {
            console.log('Clique em botão de exclusão detectado!');
            const deleteBtn = e.target.closest('.delete-btn');
            console.log('Botão de exclusão clicado:', deleteBtn);
            console.log('Dataset do botão:', deleteBtn ? deleteBtn.dataset : 'null');
            
            // Usar getAttribute diretamente para garantir que funcione
            const entryId = deleteBtn ? deleteBtn.getAttribute('data-entry-id') : null;
            console.log('Entry ID obtido:', entryId);
            console.log('Dataset completo:', deleteBtn ? deleteBtn.dataset : 'null');
            
            // Verificar se o atributo existe no HTML
            const entryIdFromAttribute = deleteBtn ? deleteBtn.getAttribute('data-entry-id') : null;
            console.log('Entry ID do atributo HTML:', entryIdFromAttribute);
            
            // Verificar se entryId existe
            if (!entryId) {
                console.error('ID da entrada não encontrado no dataset do botão');
                console.error('Botão completo:', deleteBtn);
                showNotification('Erro: ID da entrada não encontrado', 'error');
                return;
            }
            
            // Encontrar o container da entrada usando closest (busca no elemento pai)
            const entryElement = deleteBtn.closest('[data-entry-id]');
            console.log('Container da entrada encontrado:', entryElement);
            console.log('data-entry-id do container:', entryElement ? entryElement.dataset.entryId : 'não encontrado');
            
            // Verificar se entryElement existe
            if (!entryElement) {
                console.error('Container da entrada não encontrado para ID:', entryId);
                console.error('Elemento do botão:', deleteBtn);
                console.error('Seletores disponíveis:', document.querySelectorAll('[data-entry-id]'));
                showNotification('Erro: Container da entrada não encontrado', 'error');
                return;
            }
            
            // Verificar se o ID do container corresponde ao ID do botão
            if (entryElement.dataset.entryId !== entryId) {
                console.error('ID do container não corresponde ao ID do botão');
                console.error('ID do botão:', entryId);
                console.error('ID do container:', entryElement.dataset.entryId);
                showNotification('Erro: Inconsistência nos IDs', 'error');
                return;
            }
            
            const titleElement = entryElement.querySelector('.editable-title');
            const entryTitle = titleElement ? titleElement.textContent.trim() : 'Item sem título';
            console.log('Título da entrada:', entryTitle);
            
            // Armazenar dados para exclusão
            entryToDelete = {
                id: entryId,
                title: entryTitle,
                element: entryElement
            };
            
            console.log('entryToDelete criado:', entryToDelete);
            console.log('entryToDelete.id:', entryToDelete.id);
            
            // Mostrar modal de confirmação
            console.log('Chamando showDeleteModal com título:', entryTitle);
            try {
                showDeleteModal(entryTitle);
                console.log('showDeleteModal executada com sucesso');
            } catch (error) {
                console.error('ERRO ao chamar showDeleteModal:', error);
            }
        }
    });
    }
    
    // Chamar a função setupClickListener quando o DOM estiver carregado
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupClickListener);
    } else {
        setupClickListener();
    }
    
    // Funções para gerenciamento de arquivos
    function initFileManagement(entryId) {
        currentEditingId = entryId;
        selectedFiles = [];
        
        const fileInput = document.getElementById(`file-input-${entryId}`);
        const uploadArea = document.getElementById(`upload-area-${entryId}`);
        const filePreview = document.getElementById(`file-preview-${entryId}`);
        
        if (!fileInput || !uploadArea || !filePreview) return;
        
        // Event listeners para upload
        fileInput.addEventListener('change', handleFileSelect);
        uploadArea.addEventListener('click', () => fileInput.click());
        uploadArea.addEventListener('dragover', handleDragOver);
        uploadArea.addEventListener('dragleave', handleDragLeave);
        uploadArea.addEventListener('drop', handleFileDrop);
        
        // Event listeners para botões
        const uploadBtn = document.getElementById(`upload-files-${entryId}`);
        const cancelBtn = document.getElementById(`cancel-upload-${entryId}`);
        
        if (uploadBtn) uploadBtn.addEventListener('click', uploadFiles);
        if (cancelBtn) cancelBtn.addEventListener('click', cancelFileUpload);
        
        // Event listeners para botões de exclusão de arquivos existentes
        const deleteButtons = document.querySelectorAll(`[data-entry-id="${entryId}"] .delete-file-btn`);
        deleteButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const fileId = this.dataset.fileId;
                const fileName = this.dataset.fileName;
                deleteExistingFile(fileId, fileName);
            });
        });
    }
    
    function handleFileSelect(e) {
        const files = Array.from(e.target.files);
        addFilesToPreview(files);
    }
    
    function handleDragOver(e) {
        e.preventDefault();
        e.currentTarget.classList.add('dragover');
    }
    
    function handleDragLeave(e) {
        e.preventDefault();
        e.currentTarget.classList.remove('dragover');
    }
    
    function handleFileDrop(e) {
        e.preventDefault();
        e.currentTarget.classList.remove('dragover');
        const files = Array.from(e.dataTransfer.files);
        addFilesToPreview(files);
    }
    
    function addFilesToPreview(files) {
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        const maxSize = 10 * 1024 * 1024; // 10MB
        
        files.forEach(file => {
            if (!validTypes.includes(file.type)) {
                showNotification(`Tipo de arquivo não suportado: ${file.name}`, 'error');
                return;
            }
            
            if (file.size > maxSize) {
                showNotification(`Arquivo muito grande: ${file.name}`, 'error');
                return;
            }
            
            selectedFiles.push(file);
        });
        
        updateFilePreview();
    }
    
    function updateFilePreview() {
        const filePreview = document.getElementById(`file-preview-${currentEditingId}`);
        if (!filePreview) return;
        
        const filesList = filePreview.querySelector('.upload-files-list');
        if (!filesList) return;
        
        filesList.innerHTML = '';
        
        selectedFiles.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between p-2 bg-gray-100 dark:bg-gray-600 rounded';
            
            const isImage = file.type.startsWith('image/');
            const fileSize = formatFileSize(file.size);
            
            fileItem.innerHTML = `
                <div class="flex items-center">
                    <div class="w-8 h-8 mr-2 flex-shrink-0">
                        ${isImage ? `<img src="${URL.createObjectURL(file)}" alt="${file.name}" class="w-full h-full object-cover rounded">` : getFileIcon(file.type)}
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900 dark:text-white">${file.name}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">${fileSize}</div>
                    </div>
                </div>
                <button type="button" class="text-red-500 hover:text-red-700 p-1" onclick="removeFileFromPreview(${index})">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            
            filesList.appendChild(fileItem);
        });
        
        // Mostrar/ocultar seção de preview e botões
        const uploadBtn = document.getElementById(`upload-files-${currentEditingId}`);
        const cancelBtn = document.getElementById(`cancel-upload-${currentEditingId}`);
        
        if (selectedFiles.length > 0) {
            filePreview.classList.remove('hidden');
            if (uploadBtn) uploadBtn.classList.remove('hidden');
            if (cancelBtn) cancelBtn.classList.remove('hidden');
        } else {
            filePreview.classList.add('hidden');
            if (uploadBtn) uploadBtn.classList.add('hidden');
            if (cancelBtn) cancelBtn.classList.add('hidden');
        }
    }
    
    function removeFileFromPreview(index) {
        selectedFiles.splice(index, 1);
        updateFilePreview();
    }
    
    function cancelFileUpload() {
        selectedFiles = [];
        const fileInput = document.getElementById(`file-input-${currentEditingId}`);
        if (fileInput) fileInput.value = '';
        updateFilePreview();
    }
    
    async function uploadFiles() {
        if (selectedFiles.length === 0) return;
        
        showLoading();
        
        try {
            // Upload cada arquivo individualmente
            for (let i = 0; i < selectedFiles.length; i++) {
                const file = selectedFiles[i];
                const formData = new FormData();
                formData.append('file', file);
                formData.append('entry_id', currentEditingId);
                
                const response = await fetch(`<?php echo e(route('orcamentos.historico.upload', $orcamento)); ?>`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (!data.success) {
                    throw new Error(data.message || 'Erro ao fazer upload do arquivo');
                }
            }
            
            showNotification('Arquivos enviados com sucesso!');
            cancelFileUpload();
            // Recarregar a página para mostrar os novos arquivos
            window.location.reload();
            
        } catch (error) {
            showNotification(error.message || 'Erro de conexão', 'error');
            console.error('Error:', error);
        } finally {
            hideLoading();
        }
    }
    
    async function deleteExistingFile(fileId, fileName) {
        if (!confirm(`Tem certeza que deseja excluir o arquivo "${fileName}"?`)) {
            return;
        }
        
        showLoading();
        
        try {
            const response = await fetch(`<?php echo e(route('orcamentos.historico.files.delete', [$orcamento, '__FILE_ID__'])); ?>`.replace('__FILE_ID__', fileId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                showNotification(data.message);
                // Remover o arquivo da interface
                const fileElement = document.querySelector(`[data-file-id="${fileId}"]`);
                if (fileElement) {
                    fileElement.remove();
                }
            } else {
                throw new Error(data.message || 'Erro ao excluir arquivo');
            }
        } catch (error) {
            showNotification(error.message || 'Erro de conexão', 'error');
            console.error('Error:', error);
        } finally {
            hideLoading();
        }
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    function getFileIcon(fileType) {
        if (fileType === 'application/pdf') {
            return '<svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/></svg>';
        } else if (fileType.includes('word')) {
            return '<svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/></svg>';
        } else {
            return '<svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path></svg>';
        }
    }
    
    // Funções do modal de exclusão
    function showDeleteModal(itemTitle) {
        console.log('=== showDeleteModal CHAMADA ===');
        console.log('itemTitle recebido:', itemTitle);
        
        const modal = document.getElementById('deleteModal');
        const titleElement = document.getElementById('deleteItemTitle');
        const cancelButton = document.getElementById('cancelDelete');
        
        console.log('Modal encontrado:', modal);
        console.log('TitleElement encontrado:', titleElement);
        console.log('CancelButton encontrado:', cancelButton);
        
        if (modal && titleElement) {
            titleElement.textContent = itemTitle;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Focar no botão cancelar
            if (cancelButton) {
                setTimeout(() => cancelButton.focus(), 100);
            }
        }
    }
    
    function hideDeleteModal() {
        const modal = document.getElementById('deleteModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            entryToDelete = null;
        }
    }
    
    async function executeDelete() {
        console.log('=== INÍCIO executeDelete ===');
        console.log('executeDelete chamada, entryToDelete:', entryToDelete);
        console.log('Tipo de entryToDelete:', typeof entryToDelete);
        console.log('entryToDelete é null?', entryToDelete === null);
        console.log('entryToDelete é undefined?', entryToDelete === undefined);
        
        // Verificação mais robusta de entryToDelete
        if (entryToDelete === null) {
            console.error('ERRO: entryToDelete é null');
            showNotification('Erro: Dados de exclusão não encontrados (null)', 'error');
            return;
        }
        
        if (entryToDelete === undefined) {
            console.error('ERRO: entryToDelete é undefined');
            showNotification('Erro: Dados de exclusão não encontrados (undefined)', 'error');
            return;
        }
        
        if (typeof entryToDelete !== 'object') {
            console.error('ERRO: entryToDelete não é um objeto, tipo:', typeof entryToDelete);
            console.error('Valor:', entryToDelete);
            showNotification('Erro: Dados de exclusão inválidos', 'error');
            return;
        }
        
        console.log('entryToDelete é um objeto válido, verificando propriedades...');
        console.log('Propriedades disponíveis:', Object.keys(entryToDelete));
        console.log('entryToDelete.id:', entryToDelete.id);
        console.log('Tipo de entryToDelete.id:', typeof entryToDelete.id);
        
        // Verificação específica da propriedade id
        if (!entryToDelete.hasOwnProperty('id')) {
            console.error('ERRO: entryToDelete não possui a propriedade "id"');
            console.error('entryToDelete completo:', entryToDelete);
            showNotification('Erro: Propriedade ID não encontrada', 'error');
            return;
        }
        
        if (entryToDelete.id === null) {
            console.error('ERRO: entryToDelete.id é null');
            showNotification('Erro: ID da entrada é null', 'error');
            return;
        }
        
        if (entryToDelete.id === undefined) {
            console.error('ERRO: entryToDelete.id é undefined');
            showNotification('Erro: ID da entrada é undefined', 'error');
            return;
        }
        
        if (entryToDelete.id === '') {
            console.error('ERRO: entryToDelete.id é string vazia');
            showNotification('Erro: ID da entrada é vazio', 'error');
            return;
        }
        
        console.log('ID válido encontrado:', entryToDelete.id);
        console.log('Tipo do ID:', typeof entryToDelete.id);
        
        // Salvar o ID antes de fechar o modal
        const entryIdToDelete = entryToDelete.id;
        const entryElementToDelete = entryToDelete.element;
        
        showLoading();
        hideDeleteModal();
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                throw new Error('Token CSRF não encontrado');
            }
            
            const response = await fetch(`<?php echo e(route('orcamentos.historico.destroy', [$orcamento, '__ID__'])); ?>`.replace('__ID__', entryIdToDelete), {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                // Remover item da timeline com animação suave
                if (entryElementToDelete && entryElementToDelete.parentNode) {
                    // Buscar o elemento pai completo da timeline
                    const timelineItem = entryElementToDelete.closest('.relative.flex.items-start.mb-8.group');
                    
                    if (timelineItem) {
                        console.log('🎯 Removendo entrada completa da timeline:', timelineItem);
                        // Adicionar animação de fade out e slide
                        timelineItem.style.transition = 'all 0.4s ease-out';
                        timelineItem.style.opacity = '0';
                        timelineItem.style.transform = 'translateX(-30px) scale(0.95)';
                        timelineItem.style.maxHeight = timelineItem.offsetHeight + 'px';
                        
                        // Após a animação inicial, colapsar a altura
                        setTimeout(() => {
                            if (timelineItem && timelineItem.parentNode) {
                                timelineItem.style.maxHeight = '0';
                                timelineItem.style.marginTop = '0';
                                timelineItem.style.marginBottom = '0';
                                timelineItem.style.paddingTop = '0';
                                timelineItem.style.paddingBottom = '0';
                                
                                // Remover completamente após a animação
                                setTimeout(() => {
                                    if (timelineItem && timelineItem.parentNode) {
                                        timelineItem.remove();
                                        console.log('✅ Entrada completa removida da timeline');
                                    }
                                }, 300);
                            }
                        }, 200);
                    } else {
                        console.log('⚠️ Elemento pai da timeline não encontrado, removendo elemento direto');
                        // Fallback para o comportamento anterior
                        entryElementToDelete.style.transition = 'all 0.4s ease-out';
                        entryElementToDelete.style.opacity = '0';
                        entryElementToDelete.style.transform = 'translateX(-30px) scale(0.95)';
                        
                        setTimeout(() => {
                            if (entryElementToDelete && entryElementToDelete.parentNode) {
                                entryElementToDelete.remove();
                            }
                        }, 400);
                    }
                } else {
                    // Fallback: tentar encontrar o elemento pelo ID se não tiver referência
                    const fallbackElement = document.querySelector(`[data-entry-id="${entryIdToDelete}"]`);
                    if (fallbackElement) {
                        // Buscar o elemento pai completo da timeline (div com classes 'relative flex items-start mb-8 group')
                        const timelineItem = fallbackElement.closest('.relative.flex.items-start.mb-8.group');
                        if (timelineItem) {
                            console.log('🎯 Removendo entrada completa da timeline (fallback):', timelineItem);
                            timelineItem.style.transition = 'all 0.4s ease-out';
                            timelineItem.style.opacity = '0';
                            timelineItem.style.transform = 'translateX(-30px) scale(0.95)';
                            timelineItem.style.maxHeight = timelineItem.offsetHeight + 'px';
                            
                            setTimeout(() => {
                                timelineItem.style.maxHeight = '0';
                                timelineItem.style.marginBottom = '0';
                                timelineItem.style.paddingTop = '0';
                                timelineItem.style.paddingBottom = '0';
                                
                                setTimeout(() => {
                                    if (timelineItem && timelineItem.parentNode) {
                                        timelineItem.remove();
                                        console.log('✅ Entrada completa removida (fallback)');
                                    }
                                }, 300);
                            }, 200);
                        } else {
                            console.log('⚠️ Elemento pai da timeline não encontrado, removendo elemento direto');
                            fallbackElement.style.transition = 'all 0.4s ease-out';
                            fallbackElement.style.opacity = '0';
                            setTimeout(() => {
                                if (fallbackElement && fallbackElement.parentNode) {
                                    fallbackElement.remove();
                                }
                            }, 400);
                        }
                    }
                }
                
                showNotification(data.message || 'Item excluído com sucesso');
            } else {
                throw new Error(data.message || 'Erro ao excluir entrada');
            }
        } catch (error) {
            showNotification(error.message || 'Erro de conexão', 'error');
            console.error('Error:', error);
        } finally {
            hideLoading();
            entryToDelete = null;
        }
    }
    
    // Event listeners do modal de exclusão
    const cancelDeleteBtn = document.getElementById('cancelDelete');
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    
    if (cancelDeleteBtn) {
        cancelDeleteBtn.addEventListener('click', hideDeleteModal);
    }
    
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', executeDelete);
    }
    
    // Fechar modal ao clicar fora
    const deleteModalElement = document.getElementById('deleteModal');
    if (deleteModalElement) {
        deleteModalElement.addEventListener('click', function(e) {
            if (e.target === this) {
                hideDeleteModal();
            }
        });
    }



    // Fechar edição inline com ESC e modal de exclusão
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Verificar se o modal de exclusão está aberto
            const deleteModal = document.getElementById('deleteModal');
            if (deleteModal && !deleteModal.classList.contains('hidden')) {
                hideDeleteModal();
                return;
            }
            
            // Verificar se há edição inline ativa
            const editingElements = document.querySelectorAll('[contenteditable="true"]');
            if (editingElements.length > 0) {
                const titleElement = editingElements[0];
                const entryContent = titleElement.closest('.entry-content');
                const entryId = entryContent.dataset.entryId;
                const cancelButton = document.querySelector(`[data-entry-id="${entryId}"].cancel-edit-btn`);
                
                if (cancelButton) {
                    cancelButton.click();
                }
            }
        }
        
        // Salvar com Ctrl+Enter
        if (e.ctrlKey && e.key === 'Enter') {
            const editingElements = document.querySelectorAll('[contenteditable="true"]');
            if (editingElements.length > 0) {
                const titleElement = editingElements[0];
                const entryContent = titleElement.closest('.entry-content');
                const entryId = entryContent.dataset.entryId;
                const saveButton = document.querySelector(`[data-entry-id="${entryId}"].edit-toggle-btn`);
                
                if (saveButton) {
                    saveButton.click();
                }
            }
        }
    });
    
    // Funções para o modal de imagem
    window.openImageModal = function(imageUrl, imageName) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const modalImageName = document.getElementById('modalImageName');
        
        if (modal && modalImage && modalImageName) {
            modalImage.src = imageUrl;
            modalImage.alt = imageName;
            modalImageName.textContent = imageName;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    };

    window.closeImageModal = function() {
        const modal = document.getElementById('imageModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    };

    // Fechar modal ao clicar fora da imagem
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('imageModal');
        if (modal && e.target === modal) {
            closeImageModal();
        }
    });

    // Fechar modal com tecla ESC para imagem
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const imageModal = document.getElementById('imageModal');
            if (imageModal && !imageModal.classList.contains('hidden')) {
                closeImageModal();
            }
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\giro_2\resources\views/historico/index.blade.php ENDPATH**/ ?>