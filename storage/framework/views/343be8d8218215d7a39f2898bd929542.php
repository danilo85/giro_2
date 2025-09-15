<?php $__env->startSection('title', 'Detalhes do Usuário - Giro'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">
    <!-- Breadcrumbs -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="<?php echo e(route('dashboard')); ?>" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                    <svg class="w-3 h-3 mr-2.5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"></path>
                    </svg>
                    <a href="<?php echo e(route('users.index')); ?>" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Usuários</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"></path>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400"><?php echo e($user->name); ?></span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detalhes do Usuário</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Informações completas de <?php echo e($user->name); ?></p>
            </div>
            
            <div class="flex space-x-3">
                <a href="<?php echo e(route('users.edit', $user)); ?>" 
                   title="Editar usuário"
                   class="inline-flex items-center p-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </a>
                
                <?php if($user->id !== auth()->id()): ?>
                    <button onclick="toggleUserStatus(<?php echo e($user->id); ?>, <?php echo e($user->is_active ? 'false' : 'true'); ?>)" 
                            title="<?php echo e($user->is_active ? 'Desativar usuário' : 'Ativar usuário'); ?>"
                            class="inline-flex items-center p-2 <?php echo e($user->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700'); ?> text-white rounded-md transition-colors">
                        <?php if($user->is_active): ?>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L5.636 5.636"></path>
                            </svg>
                        <?php else: ?>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        <?php endif; ?>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- User Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <!-- Avatar & Basic Info -->
                <div class="text-center mb-6">
                    <div class="relative inline-block">
                        <img class="w-24 h-24 rounded-full mx-auto" 
                             src="<?php echo e($user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=3B82F6&background=EBF4FF'); ?>" 
                             alt="<?php echo e($user->name); ?>">
                        <!-- Online Status -->
                        <div class="absolute bottom-1 right-1 w-6 h-6 <?php echo e($user->is_online ? 'bg-green-400' : 'bg-gray-400'); ?> border-4 border-white dark:border-gray-800 rounded-full"></div>
                        <!-- Admin Crown -->
                        <?php if($user->isAdmin()): ?>
                            <div class="absolute -top-2 -right-2 w-6 h-6 text-yellow-500">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-4"><?php echo e($user->name); ?></h2>
                    <p class="text-gray-500 dark:text-gray-400"><?php echo e($user->email); ?></p>
                    
                    <!-- Status Badges -->
                    <div class="flex justify-center space-x-2 mt-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo e($user->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'); ?>">
                            <?php echo e($user->is_active ? 'Ativo' : 'Inativo'); ?>

                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo e($user->isAdmin() ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'); ?>">
                            <?php echo e($user->isAdmin() ? 'Administrador' : 'Usuário Padrão'); ?>

                        </span>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Estatísticas Rápidas</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Membro desde:</span>
                            <span class="text-sm text-gray-900 dark:text-white"><?php echo e($user->created_at->format('M Y')); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Último acesso:</span>
                            <span class="text-sm text-gray-900 dark:text-white">
                                <?php if($user->last_login_at): ?>
                                    <?php echo e($user->last_login_at->diffForHumans()); ?>

                                <?php else: ?>
                                    Nunca
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Status online:</span>
                            <span class="text-sm <?php echo e($user->is_online ? 'text-green-600 dark:text-green-400' : 'text-gray-600 dark:text-gray-400'); ?>">
                                <?php echo e($user->is_online ? 'Online agora' : 'Offline'); ?>

                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Detailed Information -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Account Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Informações da Conta</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome Completo</label>
                        <p class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 px-3 py-2 rounded-md"><?php echo e($user->name); ?></p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <p class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 px-3 py-2 rounded-md"><?php echo e($user->email); ?></p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nível de Acesso</label>
                        <p class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 px-3 py-2 rounded-md">
                            <?php echo e($user->isAdmin() ? 'Administrador' : 'Usuário Padrão'); ?>

                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status da Conta</label>
                        <p class="text-sm bg-gray-50 dark:bg-gray-700 px-3 py-2 rounded-md">
                            <span class="inline-flex items-center <?php echo e($user->is_active ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'); ?>">
                                <div class="w-2 h-2 <?php echo e($user->is_active ? 'bg-green-400' : 'bg-red-400'); ?> rounded-full mr-2"></div>
                                <?php echo e($user->is_active ? 'Ativa' : 'Inativa'); ?>

                            </span>
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Verificado</label>
                        <p class="text-sm bg-gray-50 dark:bg-gray-700 px-3 py-2 rounded-md">
                            <?php if($user->email_verified_at): ?>
                                <span class="inline-flex items-center text-green-600 dark:text-green-400">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Verificado em <?php echo e($user->email_verified_at->format('d/m/Y')); ?>

                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center text-red-600 dark:text-red-400">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    Não verificado
                                </span>
                            <?php endif; ?>
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ID do Usuário</label>
                        <p class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 px-3 py-2 rounded-md font-mono"><?php echo e($user->id); ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Timestamps -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Histórico de Datas</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Conta Criada</label>
                        <p class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 px-3 py-2 rounded-md">
                            <?php echo e($user->created_at->format('d/m/Y H:i:s')); ?>

                            <span class="text-gray-500 dark:text-gray-400">(<?php echo e($user->created_at->diffForHumans()); ?>)</span>
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Última Atualização</label>
                        <p class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 px-3 py-2 rounded-md">
                            <?php echo e($user->updated_at->format('d/m/Y H:i:s')); ?>

                            <span class="text-gray-500 dark:text-gray-400">(<?php echo e($user->updated_at->diffForHumans()); ?>)</span>
                        </p>
                    </div>
                    
                    <?php if($user->last_login_at): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Último Login</label>
                            <p class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 px-3 py-2 rounded-md">
                                <?php echo e($user->last_login_at->format('d/m/Y H:i:s')); ?>

                                <span class="text-gray-500 dark:text-gray-400">(<?php echo e($user->last_login_at->diffForHumans()); ?>)</span>
                            </p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($user->email_verified_at): ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Verificado</label>
                            <p class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 px-3 py-2 rounded-md">
                                <?php echo e($user->email_verified_at->format('d/m/Y H:i:s')); ?>

                                <span class="text-gray-500 dark:text-gray-400">(<?php echo e($user->email_verified_at->diffForHumans()); ?>)</span>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Atividade Recente</h3>
                
                <?php if($user->activities && $user->activities->count() > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $user->activities->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-start space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                <div class="flex-shrink-0">
                                    <div class="w-2 h-2 bg-blue-400 rounded-full mt-2"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900 dark:text-white"><?php echo e($activity->description); ?></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?php echo e($activity->created_at->diffForHumans()); ?></p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhuma atividade registrada</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Este usuário ainda não possui atividades registradas.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notifications -->
<?php if(session('success')): ?>
    <div id="toast-success" class="fixed top-4 right-4 z-50 flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3 text-sm font-normal"><?php echo e(session('success')); ?></div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" onclick="document.getElementById('toast-success').remove()">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
<?php endif; ?>

<script>
// Auto-hide toasts after 5 seconds
setTimeout(() => {
    const successToast = document.getElementById('toast-success');
    if (successToast) successToast.remove();
}, 5000);

// Toggle user status function
function toggleUserStatus(userId, newStatus) {
    fetch(`/users/${userId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ is_active: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erro ao alterar status do usuário');
    });
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\giro_2\resources\views/users/show.blade.php ENDPATH**/ ?>