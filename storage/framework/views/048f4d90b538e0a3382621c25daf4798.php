<nav class="bottom-nav">
    <a href="<?php echo e(route('home')); ?>" wire:navigate wire:current="active" class="nav-item">
        <i class="bi <?php echo e(request()->routeIs('home') ? 'bi-heart-fill' : 'bi-heart'); ?>"></i>
        <span>Donasi</span>
    </a>

    <a href="#" class="nav-item">
        <i class="bi bi-plus-circle"></i>
        <span>Galang Dana</span>
    </a>

    <a href="<?php echo e(route('donasi-saya')); ?>" wire:navigate wire:current="active" class="nav-item">
        <i class="bi <?php echo e(request()->routeIs('donasi-saya') ? 'bi-list-ul' : 'bi-list-ul'); ?>"></i>
        <span>Donasi Saya</span>
    </a>

    <a href="#" class="nav-item">
        <i class="bi bi-file-earmark-bar-graph"></i>
        <span>Report</span>
    </a>

    <a href="<?php echo e(route('login')); ?>" wire:navigate wire:current="active" class="nav-item">
        <i class="bi <?php echo e(request()->routeIs('login') ? 'bi-person-fill' : 'bi-person'); ?>"></i>
        <span>Akun</span>
    </a>
</nav>
<?php /**PATH C:\laragon\www\inisiatif\resources\views/components/app/bottom-nav.blade.php ENDPATH**/ ?>