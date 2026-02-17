<nav class="navbar navbar-inisiatif">
    <div class="container-fluid">
        <div class="w-100 d-flex align-items-center <?php echo e(@$class); ?> justify-content-between">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(@$closeSearch): ?>
                <button @click="searchOpen = false" class="btn btn-secondary btn-search">
                    <i class="bi bi-x"></i>
                </button>
            <?php else: ?>
                <a href="<?php echo e($route ?? route('home')); ?>" wire:navigate class="btn btn-secondary btn-search">
                    <i class="bi bi-arrow-left"></i>
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <span class="fw-semibold"><?php echo e($title); ?></small>
        </div>
    </div>
</nav>
<?php /**PATH C:\laragon\www\inisiatif\resources\views/components/app/navbar-secondary.blade.php ENDPATH**/ ?>