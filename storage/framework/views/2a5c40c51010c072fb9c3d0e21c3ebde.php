<?php $__env->startSection('title', 'Gestão de Usuários - Giro'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Gestão de Usuários</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Gerencie todos os usuários do sistema</p>
            </div>
            
            <!-- Controle de Registro Público -->
            <div class="mt-4 sm:mt-0">
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center justify-between">
                        <div class="mr-4">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">Registro Público</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Permitir novos cadastros</p>
                        </div>
                        <form id="toggle-registration-form" method="POST" action="<?php echo e(route('settings.toggle-registration')); ?>">
                            <?php echo csrf_field(); ?>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       id="public-registration-toggle"
                                       class="sr-only peer" 
                                       <?php echo e(App\Models\Setting::isPublicRegistrationEnabled() ? 'checked' : ''); ?>

                                       onchange="toggleRegistration()">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    <?php echo e(App\Models\Setting::isPublicRegistrationEnabled() ? 'Habilitado' : 'Desabilitado'); ?>

                                </span>
                            </label>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Campo de Pesquisa -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="p-4">
            <div class="flex items-center gap-3">
                <div class="flex-1 relative">
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="<?php echo e(request('search')); ?>"
                           placeholder="Buscar por nome do usuário..."
                           class="w-full pl-10 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <button type="button" id="clearSearch" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 hidden">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Filter Tags -->
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="<?php echo e(route('users.index', ['status' => 'active'])); ?>" 
           class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?php echo e(request('status') === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600'); ?> transition-colors">
            <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
            Ativos
        </a>
        <a href="<?php echo e(route('users.index', ['status' => 'inactive'])); ?>" 
           class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?php echo e(request('status') === 'inactive' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600'); ?> transition-colors">
            <div class="w-2 h-2 bg-red-400 rounded-full mr-2"></div>
            Inativos
        </a>
        <a href="<?php echo e(route('users.index', ['is_admin' => '1'])); ?>" 
           class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?php echo e(request('is_admin') === '1' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600'); ?> transition-colors">
            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
            </svg>
            Administradores
        </a>
    </div>

    <!-- Users Grid -->
    <?php if($users->count() > 0): ?>
        <div id="users-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="user-card bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <!-- User Avatar & Status -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="relative">
                            <img class="w-12 h-12 rounded-full" 
                                 src="<?php echo e($user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=3B82F6&background=EBF4FF'); ?>" 
                                 alt="<?php echo e($user->name); ?>">
                            <!-- Online Status -->
                            <div class="absolute bottom-0 right-0 w-3 h-3 <?php echo e($user->is_online ? 'bg-green-400' : 'bg-gray-400'); ?> border-2 border-white dark:border-gray-800 rounded-full"></div>
                            <!-- Admin Crown -->
                            <?php if($user->isAdmin()): ?>
                                <div class="absolute -top-1 -right-1 w-4 h-4 text-yellow-500">
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Status Badge -->
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?php echo e($user->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'); ?>">
                            <?php echo e($user->is_active ? 'Ativo' : 'Inativo'); ?>

                        </span>
                    </div>
                    
                    <!-- User Info -->
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1"><?php echo e($user->name); ?></h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2"><?php echo e($user->email); ?></p>
                        <p class="text-xs text-gray-600 dark:text-gray-300">
                            <?php echo e($user->isAdmin() ? 'Administrador' : 'Usuário Padrão'); ?>

                        </p>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex space-x-2">
                            <a href="<?php echo e(route('users.show', $user)); ?>" 
                               class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 dark:text-blue-400 transition-all duration-200" 
                               title="Visualizar usuário">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="<?php echo e(route('users.edit', $user)); ?>" 
                               class="p-2 rounded-lg text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 dark:text-green-400 transition-all duration-200" 
                               title="Editar usuário">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                        
                        <div class="flex space-x-2">
                            <?php if($user->id !== auth()->id()): ?>
                                <!-- Toggle Status -->
                                <form method="POST" action="<?php echo e(route('users.toggle-status', $user)); ?>" class="inline toggle-status-form" data-user-id="<?php echo e($user->id); ?>">
                                            <?php echo csrf_field(); ?>
                                    <button type="submit" 
                                            class="p-2 rounded-lg <?php echo e($user->is_active ? 'text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 dark:text-yellow-400' : 'text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 dark:text-green-400'); ?> transition-all duration-200" 
                                            title="<?php echo e($user->is_active ? 'Desativar usuário' : 'Ativar usuário'); ?>">
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
                                </form>
                                
                                <!-- Delete -->
                                <button type="button" 
                                        onclick="confirmDelete('<?php echo e($user->id); ?>', '<?php echo e($user->name); ?>')"
                                        class="p-2 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 dark:text-red-400 transition-all duration-200" 
                                        title="Deletar usuário">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <!-- No Results Message -->
        <div id="no-results" class="hidden bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="text-center py-16">
                <div class="mx-auto w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum usuário encontrado</h3>
                <p class="text-gray-500 dark:text-gray-400">Tente ajustar sua pesquisa ou limpar os filtros.</p>
            </div>
        </div>
        
        <!-- Pagination -->
        <div id="pagination" class="flex justify-center">
            <?php echo e($users->withQueryString()->links()); ?>

        </div>
    <?php else: ?>
        <!-- Estado vazio -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="text-center py-16">
                <div class="mx-auto w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum usuário encontrado</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Comece criando um novo usuário para gerenciar o sistema.</p>
                <a href="<?php echo e(route('users.create')); ?>" 
                   class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Criar Primeiro Usuário
                </a>
            </div>
        </div>
    <?php endif; ?>
    <!-- Botão Flutuante -->
    <a href="<?php echo e(route('users.create')); ?>" 
       class="fixed bottom-6 right-6 z-50 bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-full shadow-lg hover:shadow-xl transition-all duration-200 group">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        <span class="sr-only">Novo Usuário</span>
    </a>
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

<?php if(session('error')): ?>
    <div id="toast-error" class="fixed top-4 right-4 z-50 flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3 text-sm font-normal"><?php echo e(session('error')); ?></div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" onclick="document.getElementById('toast-error').remove()">
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
    const errorToast = document.getElementById('toast-error');
    if (successToast) successToast.remove();
    if (errorToast) errorToast.remove();
}, 5000);

// Toggle status via AJAX - Versão Simplificada
document.addEventListener('DOMContentLoaded', function() {
    const toggleForms = document.querySelectorAll('.toggle-status-form');
    
    toggleForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const button = this.querySelector('button');
            const originalHtml = button.innerHTML;
            
            // Mostrar loading simples
            button.disabled = true;
            button.textContent = 'Carregando...';
            
            // Fazer requisição AJAX
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualizar interface
                    const userCard = this.closest('.bg-white');
                    const statusBadge = userCard.querySelector('.inline-flex.items-center.px-2.py-1');
                    
                    // Atualizar badge de status
                    if (data.is_active) {
                        statusBadge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                        statusBadge.textContent = 'Ativo';
                        
                        // Atualizar botão para desativar
                        button.className = 'p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors duration-200 dark:hover:bg-yellow-900/20 dark:text-yellow-400';
                        button.title = 'Desativar usuário';
                        button.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"></path></svg>';
                    } else {
                        statusBadge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                        statusBadge.textContent = 'Inativo';
                        
                        // Atualizar botão para ativar
                        button.className = 'p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors duration-200 dark:hover:bg-green-900/20 dark:text-green-400';
                        button.title = 'Ativar usuário';
                        button.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                    }
                    
                    // Mostrar toast de sucesso
                    showToast(data.message, 'success');
                } else {
                    // Em caso de erro, restaurar botão original
                    button.innerHTML = originalHtml;
                    showToast(data.message || 'Erro ao alterar status do usuário', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                button.innerHTML = originalHtml;
                showToast('Erro ao alterar status do usuário', 'error');
            })
            .finally(() => {
                // Sempre reabilitar o botão
                button.disabled = false;
            });
        });
    });
});

// Função para mostrar toast
function showToast(message, type) {
    // Remover toasts existentes
    const existingToasts = document.querySelectorAll('[id^="toast-"]');
    existingToasts.forEach(toast => toast.remove());
    
    const toastId = `toast-${type}-${Date.now()}`;
    const iconColor = type === 'success' ? 'text-green-500 bg-green-100 dark:bg-green-800 dark:text-green-200' : 'text-red-500 bg-red-100 dark:bg-red-800 dark:text-red-200';
    const iconPath = type === 'success' 
        ? 'M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z'
        : 'M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z';
    
    const toast = document.createElement('div');
    toast.id = toastId;
    toast.className = 'fixed top-4 right-4 z-50 flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800';
    toast.setAttribute('role', 'alert');
    
    toast.innerHTML = `
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 ${iconColor} rounded-lg">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="${iconPath}" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3 text-sm font-normal">${message}</div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" onclick="document.getElementById('${toastId}').remove()">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    `;
    
    document.body.appendChild(toast);
    
    // Auto-hide após 5 segundos
    setTimeout(() => {
        if (document.getElementById(toastId)) {
            document.getElementById(toastId).remove();
        }
    }, 5000);
}

// Pesquisa em tempo real
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const clearButton = document.getElementById('clearSearch');
    const usersGrid = document.getElementById('users-grid');
    const noResults = document.getElementById('no-results');
    const pagination = document.getElementById('pagination');
    const userCards = document.querySelectorAll('.user-card');
    
    let searchTimeout;
    
    // Função de pesquisa
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;
        
        userCards.forEach(card => {
            const userName = card.querySelector('h3').textContent.toLowerCase();
            const isVisible = userName.includes(searchTerm);
            
            card.style.display = isVisible ? 'block' : 'none';
            if (isVisible) visibleCount++;
        });
        
        // Mostrar/ocultar mensagem de "nenhum resultado"
        if (visibleCount === 0 && searchTerm !== '') {
            usersGrid.style.display = 'none';
            noResults.classList.remove('hidden');
            pagination.style.display = 'none';
        } else {
            usersGrid.style.display = 'grid';
            noResults.classList.add('hidden');
            pagination.style.display = searchTerm === '' ? 'flex' : 'none';
        }
        
        // Mostrar/ocultar botão de limpar
        if (searchTerm !== '') {
            clearButton.classList.remove('hidden');
        } else {
            clearButton.classList.add('hidden');
        }
    }
    
    // Event listener para pesquisa com debounce
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 300);
    });
    
    // Event listener para botão de limpar
    clearButton.addEventListener('click', function() {
        searchInput.value = '';
        performSearch();
        searchInput.focus();
    });
    
    // Verificar se já existe valor na pesquisa ao carregar a página
    if (searchInput.value.trim() !== '') {
        performSearch();
    }
});

// Toggle Registration function
function toggleRegistration() {
    const toggle = document.getElementById('public-registration-toggle');
    const form = document.getElementById('toggle-registration-form');
    const statusText = toggle.parentElement.querySelector('span');
    
    // Desabilitar toggle temporariamente
    toggle.disabled = true;
    
    // Fazer requisição AJAX
    fetch(form.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            enabled: toggle.checked
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Atualizar texto do status
            statusText.textContent = data.enabled ? 'Habilitado' : 'Desabilitado';
            
            // Mostrar toast de sucesso
            showToast(data.message, 'success');
        } else {
            // Reverter o toggle em caso de erro
            toggle.checked = !toggle.checked;
            showToast(data.message || 'Erro ao alterar configuração', 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        // Reverter o toggle em caso de erro
        toggle.checked = !toggle.checked;
        showToast('Erro ao alterar configuração de registro', 'error');
    })
    .finally(() => {
        // Reabilitar toggle
        toggle.disabled = false;
    });
}

// Confirm delete function
function confirmDelete(userId, userName) {
    if (confirm(`Tem certeza que deseja deletar o usuário "${userName}"? Esta ação não pode ser desfeita.`)) {
        // Criar e submeter formulário de delete
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/users/${userId}`;
        
        // Token CSRF
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);
        
        // Method DELETE
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\giro\resources\views/users/index.blade.php ENDPATH**/ ?>