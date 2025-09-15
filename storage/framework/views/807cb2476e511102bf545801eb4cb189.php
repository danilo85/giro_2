<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['items' => []]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['items' => []]); ?>
<?php foreach (array_filter((['items' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="inline-flex items-center">
                <?php if($index > 0): ?>
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                <?php endif; ?>
                
                <?php if(isset($item['url']) && !$loop->last): ?>
                    <a href="<?php echo e($item['url']); ?>" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <?php if(isset($item['icon'])): ?>
                            <i class="<?php echo e($item['icon']); ?> mr-2"></i>
                        <?php endif; ?>
                        <?php echo e($item['label']); ?>

                    </a>
                <?php else: ?>
                    <span class="inline-flex items-center text-sm font-medium text-gray-500 dark:text-gray-400">
                        <?php if(isset($item['icon'])): ?>
                            <i class="<?php echo e($item['icon']); ?> mr-2"></i>
                        <?php endif; ?>
                        <?php echo e($item['label']); ?>

                    </span>
                <?php endif; ?>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ol>
</nav><?php /**PATH C:\laragon\www\giro\resources\views/components/breadcrumb.blade.php ENDPATH**/ ?>