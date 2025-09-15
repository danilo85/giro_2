<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
           
                    <h1 class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">Editar Trabalho</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400"><?php echo e($work->title); ?></p>
                </div>
                
                <div class="flex space-x-3">
                    <?php if($work->status === 'published'): ?>
                        <a href="<?php echo e(route('public.portfolio.public.work', $work->slug)); ?>" target="_blank"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            Ver Público
                        </a>
                    <?php endif; ?>
                    
                    <form action="<?php echo e(route('portfolio.works.destroy', $work)); ?>" method="POST" class="inline"
                          onsubmit="return confirm('Tem certeza que deseja excluir este trabalho?')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Excluir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto py-0 px-4 sm:px-6 lg:px-8">
        <form action="<?php echo e(route('portfolio.works.update', $work)); ?>" method="POST" enctype="multipart/form-data" 
              x-data="workForm()" @submit="submitForm($event)" class="space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <!-- Progress Steps -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <nav aria-label="Progress">
                    <ol class="flex items-center">
                        <li class="relative" :class="currentStep >= 1 ? 'text-blue-600' : 'text-gray-500'">
                            <button type="button" @click="setStep(1)" class="flex items-center">
                                <span class="flex items-center justify-center w-8 h-8 border-2 rounded-full" 
                                      :class="currentStep >= 1 ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300'">
                                    1
                                </span>
                                <span class="ml-2 text-sm font-medium">Informações Básicas</span>
                            </button>
                        </li>
                        
                        <li class="relative ml-8" :class="currentStep >= 2 ? 'text-blue-600' : 'text-gray-500'">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="h-0.5 w-full bg-gray-200 dark:bg-gray-700" :class="currentStep >= 2 ? 'bg-blue-600' : ''"></div>
                            </div>
                            <button type="button" @click="setStep(2)" class="relative flex items-center bg-white dark:bg-gray-800">
                                <span class="flex items-center justify-center w-8 h-8 border-2 rounded-full" 
                                      :class="currentStep >= 2 ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300'">
                                    2
                                </span>
                                <span class="ml-2 text-sm font-medium">Imagens</span>
                            </button>
                        </li>
                        
                        <li class="relative ml-8" :class="currentStep >= 3 ? 'text-blue-600' : 'text-gray-500'">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="h-0.5 w-full bg-gray-200 dark:bg-gray-700" :class="currentStep >= 3 ? 'bg-blue-600' : ''"></div>
                            </div>
                            <button type="button" @click="setStep(3)" class="relative flex items-center bg-white dark:bg-gray-800">
                                <span class="flex items-center justify-center w-8 h-8 border-2 rounded-full" 
                                      :class="currentStep >= 3 ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300'">
                                    3
                                </span>
                                <span class="ml-2 text-sm font-medium">SEO & Publicação</span>
                            </button>
                        </li>
                    </ol>
                </nav>
            </div>
            
            <!-- Step 1: Basic Information -->
            <div x-show="currentStep === 1" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Informações Básicas</h2>
                
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título *</label>
                    <input type="text" name="title" id="title" value="<?php echo e(old('title', $work->title)); ?>" required
                           x-model="form.title" @input="generateSlug()"
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                    <input type="text" name="slug" id="slug" value="<?php echo e(old('slug', $work->slug)); ?>"
                           x-model="form.slug"
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">URL amigável</p>
                </div>
                
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição Curta</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('description', $work->description)); ?></textarea>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Conteúdo Completo
                    </label>
                    
                    <!-- Editor de Texto Rico -->
                    <div class="border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <!-- Barra de Ferramentas -->
                        <div class="bg-gray-50 dark:bg-gray-700 border-b border-gray-300 dark:border-gray-600 p-2 flex flex-wrap gap-1">
                            <button type="button" onclick="formatText('bold')" class="editor-btn" title="Negrito">
                                <i class="fas fa-bold"></i>
                            </button>
                            <button type="button" onclick="formatText('italic')" class="editor-btn" title="Itálico">
                                <i class="fas fa-italic"></i>
                            </button>
                            <button type="button" onclick="formatText('underline')" class="editor-btn" title="Sublinhado">
                                <i class="fas fa-underline"></i>
                            </button>
                            <button type="button" onclick="formatText('strikeThrough')" class="editor-btn" title="Riscado">
                                <i class="fas fa-strikethrough"></i>
                            </button>
                            <div class="border-l border-gray-300 dark:border-gray-600 mx-1"></div>
                            <button type="button" onclick="formatText('insertOrderedList')" class="editor-btn" title="Lista Numerada">
                                <i class="fas fa-list-ol"></i>
                            </button>
                            <button type="button" onclick="formatText('insertUnorderedList')" class="editor-btn" title="Lista com Marcadores">
                                <i class="fas fa-list-ul"></i>
                            </button>
                            <div class="border-l border-gray-300 dark:border-gray-600 mx-1"></div>
                            <button type="button" onclick="showLinkModal()" class="editor-btn" title="Link">
                                <i class="fas fa-link"></i>
                            </button>
                            <button type="button" onclick="insertLineBreak()" class="editor-btn" title="Quebra de Linha">
                                <i class="fas fa-level-down-alt"></i>
                            </button>
                            <div class="border-l border-gray-300 dark:border-gray-600 mx-1"></div>
                            <button type="button" onclick="undoEdit()" class="editor-btn" title="Desfazer">
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
                        
                        <!-- Editor Content -->
                        <div id="content-editor" 
                             contenteditable="true"
                             class="w-full min-h-[200px] px-3 py-2 border border-t-0 border-gray-300 dark:border-gray-600 rounded-b-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                             style="max-height: 400px; overflow-y: auto;"
                             placeholder="Descreva detalhadamente o trabalho, processo criativo, resultados, etc."><?php echo old('content', $work->content); ?></div>
                        
                        <!-- Hidden input to store the content -->
                         <input type="hidden" id="content" name="content" value="<?php echo e(old('content', $work->content)); ?>">
                     </div>
                     
                     <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                         <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                     <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                 </div>

                 <!-- Modal para inserção de links -->
                 <div id="linkModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                     <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-96 max-w-md mx-4">
                         <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Inserir Link</h3>
                         
                         <div class="space-y-4">
                             <div>
                                 <label for="linkText" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                     Texto do Link
                                 </label>
                                 <input type="text" 
                                        id="linkText" 
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                                        placeholder="Digite o texto que será exibido">
                             </div>
                             
                             <div>
                                 <label for="linkUrl" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                     URL do Link
                                 </label>
                                 <input type="url" 
                                        id="linkUrl" 
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                                        placeholder="https://exemplo.com">
                             </div>
                         </div>
                         
                         <div class="flex justify-end space-x-3 mt-6">
                             <button type="button" 
                                     onclick="document.getElementById('linkModal').classList.add('hidden'); document.getElementById('linkText').value = ''; document.getElementById('linkUrl').value = '';" 
                                     class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md hover:bg-gray-200 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                 Cancelar
                             </button>
                             <button type="button" 
                                     onclick="insertLinkFromModal()" 
                                     class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                 Inserir Link
                             </button>
                         </div>
                     </div>
                 </div>
                
                <!-- Category -->
                <div>
                    <label for="portfolio_category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categoria *</label>
                    <select name="portfolio_category_id" id="portfolio_category_id" required
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['portfolio_category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">Selecione uma categoria</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e(old('portfolio_category_id', $work->portfolio_category_id) == $category->id ? 'selected' : ''); ?>>
                                <?php echo e($category->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['portfolio_category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <!-- Client -->
                <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente</label>
                    <select name="client_id" id="client_id"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">Selecione um cliente (opcional)</option>
                        <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($client->id); ?>" <?php echo e(old('client_id', $work->client_id) == $client->id ? 'selected' : ''); ?>>
                                <?php echo e($client->nome); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <!-- Authors -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Autores</label>
                    <div class="space-y-2 max-h-40 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md p-3">
                        <?php $__currentLoopData = $authors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $author): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-center">
                                <input type="checkbox" name="authors[]" value="<?php echo e($author->id); ?>"
                                       <?php echo e(in_array($author->id, old('authors', $work->authors->pluck('id')->toArray())) ? 'checked' : ''); ?>

                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300"><?php echo e($author->nome); ?></span>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php $__errorArgs = ['authors'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <!-- Project Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="project_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">URL do Projeto</label>
                        <input type="url" name="project_url" id="project_url" value="<?php echo e(old('project_url', $work->project_url ?? '')); ?>"
                               class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['project_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['project_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div>
                        <label for="completion_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data de Conclusão</label>
                        <input type="date" name="completion_date" id="completion_date" value="<?php echo e(old('completion_date', $work->completion_date?->format('Y-m-d'))); ?>"
                               class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['completion_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['completion_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                
                <!-- Technologies -->
                <div>
                    <label for="technologies" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tecnologias Utilizadas</label>
                    <input type="text" name="technologies" id="technologies" value="<?php echo e(is_array(old('technologies', $work->technologies)) ? implode(', ', old('technologies', $work->technologies)) : old('technologies', $work->technologies)); ?>"
                           placeholder="Ex: Laravel, Vue.js, Tailwind CSS"
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['technologies'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['technologies'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            
            <!-- Step 2: Images -->
            <div x-show="currentStep === 2" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Imagens do Trabalho</h2>
                
                <!-- Current Images -->
                <?php if($work->images->count() > 0): ?>
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Imagens Atuais</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" x-data="{ imagesToDelete: [] }">
                            <?php $__currentLoopData = $work->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="relative group" x-data="{ marked: false }">
                                    <img src="<?php echo e($image->url); ?>" alt="<?php echo e($image->alt_text); ?>" class="w-full h-32 object-cover rounded-lg" :class="marked ? 'opacity-50' : ''">
                                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                        <button type="button" @click="marked = !marked; toggleImageForDeletion(<?php echo e($image->id); ?>)"
                                                class="text-white hover:text-red-300" :class="marked ? 'text-red-400' : ''">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="absolute top-2 left-2">
                                        <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded"><?php echo e($loop->iteration); ?></span>
                                        <?php if($image->is_cover): ?>
                                            <span class="bg-green-600 text-white text-xs px-2 py-1 rounded ml-1">Capa</span>
                                        <?php endif; ?>
                                    </div>
                                    <div x-show="marked" class="absolute top-2 right-2">
                                        <span class="bg-red-600 text-white text-xs px-2 py-1 rounded">Excluir</span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <input type="hidden" name="delete_images" id="delete_images" value="">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Clique nas imagens que deseja excluir</p>
                    </div>
                <?php endif; ?>
                
                <!-- Add New Images -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Adicionar Novas Imagens</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors"
                         @dragover.prevent @drop.prevent="handleDrop($event)">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                <label for="images" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Clique para selecionar</span>
                                    <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*" @change="handleFileSelect($event)">
                                </label>
                                <p class="pl-1">ou arraste e solte</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF até 10MB cada</p>
                        </div>
                    </div>
                    <?php $__errorArgs = ['images'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <!-- New Images Preview -->
                <div x-show="selectedImages.length > 0" class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Novas Imagens</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <template x-for="(image, index) in selectedImages" :key="index">
                            <div class="relative group">
                                <img :src="image.preview" :alt="image.name" class="w-full h-32 object-cover rounded-lg">
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                    <button type="button" @click="removeImage(index)" class="text-white hover:text-red-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="absolute top-2 left-2">
                                    <span class="bg-green-600 text-white text-xs px-2 py-1 rounded">Nova</span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            
            <!-- Step 3: SEO & Publication -->
            <div x-show="currentStep === 3" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">SEO & Publicação</h2>
                
                <!-- Meta Title -->
                <div>
                    <label for="meta_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Título (SEO)</label>
                    <input type="text" name="meta_title" id="meta_title" value="<?php echo e(old('meta_title', $work->meta_title)); ?>"
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['meta_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['meta_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <!-- Meta Description -->
                <div>
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Descrição (SEO)</label>
                    <textarea name="meta_description" id="meta_description" rows="3"
                              class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['meta_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('meta_description', $work->meta_description)); ?></textarea>
                    <?php $__errorArgs = ['meta_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <!-- Status Options -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Opções de Publicação</h3>
                    
                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="status" value="draft" <?php echo e(old('status', $work->status) === 'draft' ? 'checked' : ''); ?>

                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Rascunho - Não visível no site público</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="status" value="published" <?php echo e(old('status', $work->status) === 'published' ? 'checked' : ''); ?>

                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Publicado - Visível no site público</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Featured -->
                    <div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1" <?php echo e(old('is_featured', $work->is_featured) ? 'checked' : ''); ?>

                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <label for="is_featured" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Trabalho em destaque</label>
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Trabalhos em destaque aparecem em posição de destaque no site</p>
                    </div>
                </div>
            </div>
            
            <!-- Navigation Buttons -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div class="flex justify-between">
                    <button type="button" @click="previousStep()" x-show="currentStep > 1"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition-colors">
                        Anterior
                    </button>
                    
                    <div class="flex space-x-3">
                        <a href="<?php echo e(route('portfolio.works.index')); ?>" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition-colors">
                            Cancelar
                        </a>
                        
                        <button type="button" @click="nextStep()" x-show="currentStep < 3"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
                            Próximo
                        </button>
                        
                        <button type="submit" x-show="currentStep === 3"
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors">
                            Salvar Alterações
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function workForm() {
    return {
        currentStep: 1,
        selectedImages: [],
        imagesToDelete: [],
        form: {
            title: '<?php echo e($work->title); ?>',
            slug: '<?php echo e($work->slug); ?>'
        },
        
        submitForm(event) {
            console.log('Enviando formulário de edição...');
            console.log('Imagens selecionadas:', this.selectedImages.length);
            
            // Log dos arquivos no input
            const fileInput = document.getElementById('images');
            console.log('Arquivos no input para envio:', fileInput.files.length);
            for (let i = 0; i < fileInput.files.length; i++) {
                console.log(`Arquivo ${i}:`, fileInput.files[i].name, fileInput.files[i].size + ' bytes');
            }
            
            // Processar technologies como array
            const technologiesInput = document.getElementById('technologies');
            const technologiesValue = technologiesInput.value.trim();
            
            if (technologiesValue) {
                // Converter string separada por vírgulas em array
                const technologiesArray = technologiesValue.split(',').map(tech => tech.trim()).filter(tech => tech.length > 0);
                
                // Remover o input original
                technologiesInput.remove();
                
                // Criar inputs hidden para cada tecnologia
                const form = event.target;
                technologiesArray.forEach((tech, index) => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = `technologies[${index}]`;
                    hiddenInput.value = tech;
                    form.appendChild(hiddenInput);
                });
            }
            
            return true; // Permitir envio do formulário
        },
        
        setStep(step) {
            this.currentStep = step;
        },
        
        nextStep() {
            if (this.currentStep < 3) {
                this.currentStep++;
            }
        },
        
        previousStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
            }
        },
        
        generateSlug() {
            if (!this.form.slug || this.slugAutoGenerated) {
                this.form.slug = this.form.title
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '') // Remove accents
                    .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                    .replace(/\s+/g, '-') // Replace spaces with hyphens
                    .replace(/-+/g, '-') // Replace multiple hyphens with single
                    .replace(/^-|-$/g, ''); // Remove leading/trailing hyphens
                
                document.getElementById('slug').value = this.form.slug;
                this.slugAutoGenerated = true;
            }
        },
        
        handleFileSelect(event) {
            // Método simplificado - deixar o input file gerenciar os arquivos diretamente
            const files = event.target.files;
            console.log('Arquivos selecionados:', files.length);
            
            // Limpar array de imagens selecionadas e recriar com base nos arquivos do input
            this.selectedImages = [];
            
            Array.from(files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.selectedImages.push({
                            file: file,
                            name: file.name,
                            preview: e.target.result,
                            index: index
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });
        },
        
        handleDrop(event) {
            // Para drag & drop, vamos usar o método tradicional
            const files = event.dataTransfer.files;
            const fileInput = document.getElementById('images');
            
            // Atualizar o input file diretamente
            fileInput.files = files;
            
            // Processar para preview
            this.handleFileSelect({ target: { files: files } });
        },
        
        processFiles(files) {
            // Método removido - funcionalidade movida para handleFileSelect
        },
        
        removeImage(index) {
            // Remover da lista de preview
            this.selectedImages.splice(index, 1);
            
            // Criar novo FileList sem o arquivo removido
            const fileInput = document.getElementById('images');
            const dt = new DataTransfer();
            
            this.selectedImages.forEach(image => {
                dt.items.add(image.file);
            });
            
            fileInput.files = dt.files;
            console.log('Arquivo removido. Arquivos restantes:', fileInput.files.length);
        },
        

        
        toggleImageForDeletion(imageId) {
            const index = this.imagesToDelete.indexOf(imageId);
            if (index > -1) {
                this.imagesToDelete.splice(index, 1);
            } else {
                this.imagesToDelete.push(imageId);
            }
            document.getElementById('delete_images').value = this.imagesToDelete.join(',');
        }
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Editor de Texto Rico - Variáveis globais
let editorHistory = [];
let historyIndex = -1;
let currentEditor = null;

// Salvar estado do editor
function saveState(editorId) {
    const editor = document.getElementById(editorId);
    if (!editor) return;
    
    const content = editor.innerHTML;
    
    // Remove estados futuros se estivermos no meio do histórico
    if (historyIndex < editorHistory.length - 1) {
        editorHistory = editorHistory.slice(0, historyIndex + 1);
    }
    
    editorHistory.push(content);
    
    // Limita o histórico a 50 estados
    if (editorHistory.length > 50) {
        editorHistory.shift();
    } else {
        historyIndex++;
    }
}

// Formatação de texto
function formatText(command) {
    saveState('content-editor');
    document.execCommand(command, false, null);
    updateHiddenInput('content-editor', 'content');
}

// Mostrar modal de link
function showLinkModal() {
    document.getElementById('linkModal').classList.remove('hidden');
    document.getElementById('linkText').focus();
}

// Inserir link do modal
function insertLinkFromModal() {
    const linkText = document.getElementById('linkText').value;
    const linkUrl = document.getElementById('linkUrl').value;
    
    if (linkText && linkUrl) {
        saveState('content-editor');
        
        const selection = window.getSelection();
        if (selection.rangeCount > 0) {
            const range = selection.getRangeAt(0);
            const link = document.createElement('a');
            link.href = linkUrl;
            link.textContent = linkText;
            link.target = '_blank';
            
            range.deleteContents();
            range.insertNode(link);
            
            // Limpar seleção
            selection.removeAllRanges();
        }
        
        updateHiddenInput('content-editor', 'content');
    }
    
    // Fechar modal
    document.getElementById('linkModal').classList.add('hidden');
    document.getElementById('linkText').value = '';
    document.getElementById('linkUrl').value = '';
}

// Inserir quebra de linha
function insertLineBreak() {
    saveState('content-editor');
    document.execCommand('insertHTML', false, '<br>');
    updateHiddenInput('content-editor', 'content');
}

// Desfazer edição
function undoEdit() {
    if (historyIndex > 0) {
        historyIndex--;
        const editor = document.getElementById('content-editor');
        editor.innerHTML = editorHistory[historyIndex];
        updateHiddenInput('content-editor', 'content');
    }
}

// Atualizar campo hidden
function updateHiddenInput(editorId, hiddenInputId) {
    const editor = document.getElementById(editorId);
    const hiddenInput = document.getElementById(hiddenInputId);
    
    if (editor && hiddenInput) {
        hiddenInput.value = editor.innerHTML;
    }
}

// Inicialização do editor
document.addEventListener('DOMContentLoaded', function() {
    const editor = document.getElementById('content-editor');
    
    if (editor) {
        // Salvar estado inicial
        saveState('content-editor');
        
        // Atualizar campo hidden quando o conteúdo mudar
        editor.addEventListener('input', function() {
            updateHiddenInput('content-editor', 'content');
        });
        
        // Sincronizar antes do envio do formulário
        const form = editor.closest('form');
        if (form) {
            form.addEventListener('submit', function() {
                updateHiddenInput('content-editor', 'content');
            });
        }
    }
});
</script>

<style>
.editor-btn {
    @apply px-2 py-1 text-sm bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors;
}

.editor-btn:hover {
    @apply bg-gray-100 dark:bg-gray-500;
}

#content-editor:empty:before {
    content: attr(placeholder);
    color: #9CA3AF;
    pointer-events: none;
}

#content-editor:focus:before {
    content: '';
}
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\giro_2\resources\views/portfolio/works/edit.blade.php ENDPATH**/ ?>