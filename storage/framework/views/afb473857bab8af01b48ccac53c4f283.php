<aside class="col-8 sidebar" :class="sidebarOpen ? 'sidebar-open' : ''">
    <a href="#" wire:navigate class="sidebar-brand">
        <img loading="lazy" src="<?php echo e(asset('assets/images/logo-dashboard.png')); ?>" alt="logo">
    </a>
    <ul class="sidebar-items p-3">
        <li class="sidebar-label px-3 text-uppercase fw-bold mb-2">Utama</li>
        <?php if (isset($component)) { $__componentOriginalcbbd6fe8562b9975f1f44673965482e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcbbd6fe8562b9975f1f44673965482e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar-item','data' => ['href' => ''.e(route('admin.dashboard')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.dashboard')).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

            <i class="bi bi-speedometer2"></i>
            Dashboard
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcbbd6fe8562b9975f1f44673965482e9)): ?>
<?php $attributes = $__attributesOriginalcbbd6fe8562b9975f1f44673965482e9; ?>
<?php unset($__attributesOriginalcbbd6fe8562b9975f1f44673965482e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcbbd6fe8562b9975f1f44673965482e9)): ?>
<?php $component = $__componentOriginalcbbd6fe8562b9975f1f44673965482e9; ?>
<?php unset($__componentOriginalcbbd6fe8562b9975f1f44673965482e9); ?>
<?php endif; ?>

        <li class="sidebar-label px-3 text-uppercase fw-bold mb-2 mt-3">Program</li>
        <?php if (isset($component)) { $__componentOriginalcbbd6fe8562b9975f1f44673965482e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcbbd6fe8562b9975f1f44673965482e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar-item','data' => ['href' => ''.e(route('admin.kategori-campaign')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.kategori-campaign')).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

            <i class="bi bi-tags"></i>
            Kategori Campaign
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcbbd6fe8562b9975f1f44673965482e9)): ?>
<?php $attributes = $__attributesOriginalcbbd6fe8562b9975f1f44673965482e9; ?>
<?php unset($__attributesOriginalcbbd6fe8562b9975f1f44673965482e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcbbd6fe8562b9975f1f44673965482e9)): ?>
<?php $component = $__componentOriginalcbbd6fe8562b9975f1f44673965482e9; ?>
<?php unset($__componentOriginalcbbd6fe8562b9975f1f44673965482e9); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginalcbbd6fe8562b9975f1f44673965482e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcbbd6fe8562b9975f1f44673965482e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar-item','data' => ['href' => ''.e(route('admin.campaign')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.campaign')).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

            <i class="bi bi-megaphone"></i>
            Manajemen Campaign
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcbbd6fe8562b9975f1f44673965482e9)): ?>
<?php $attributes = $__attributesOriginalcbbd6fe8562b9975f1f44673965482e9; ?>
<?php unset($__attributesOriginalcbbd6fe8562b9975f1f44673965482e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcbbd6fe8562b9975f1f44673965482e9)): ?>
<?php $component = $__componentOriginalcbbd6fe8562b9975f1f44673965482e9; ?>
<?php unset($__componentOriginalcbbd6fe8562b9975f1f44673965482e9); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginalcbbd6fe8562b9975f1f44673965482e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcbbd6fe8562b9975f1f44673965482e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar-item','data' => ['href' => ''.e(route('admin.distribusi')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.distribusi')).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

            <i class="bi bi-box2-heart"></i>
            Kabar Penyaluran
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcbbd6fe8562b9975f1f44673965482e9)): ?>
<?php $attributes = $__attributesOriginalcbbd6fe8562b9975f1f44673965482e9; ?>
<?php unset($__attributesOriginalcbbd6fe8562b9975f1f44673965482e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcbbd6fe8562b9975f1f44673965482e9)): ?>
<?php $component = $__componentOriginalcbbd6fe8562b9975f1f44673965482e9; ?>
<?php unset($__componentOriginalcbbd6fe8562b9975f1f44673965482e9); ?>
<?php endif; ?>

        <li class="sidebar-label px-3 text-uppercase fw-bold mb-2 mt-3">Keuangan</li>
        <?php if (isset($component)) { $__componentOriginalcbbd6fe8562b9975f1f44673965482e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcbbd6fe8562b9975f1f44673965482e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar-item','data' => ['href' => ''.e(route('admin.donasi')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.donasi')).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

            <i class="bi bi-cash-coin"></i>
            Manajemen Donasi
            <?php
                $pendingDonations = \App\Models\Donation::where('status', 'pending')->count();
            ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingDonations > 0): ?>
                <span class="badge rounded-pill bg-danger ms-auto">
                    <?php echo e($pendingDonations > 9 ? '9+' : $pendingDonations); ?>

                </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcbbd6fe8562b9975f1f44673965482e9)): ?>
<?php $attributes = $__attributesOriginalcbbd6fe8562b9975f1f44673965482e9; ?>
<?php unset($__attributesOriginalcbbd6fe8562b9975f1f44673965482e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcbbd6fe8562b9975f1f44673965482e9)): ?>
<?php $component = $__componentOriginalcbbd6fe8562b9975f1f44673965482e9; ?>
<?php unset($__componentOriginalcbbd6fe8562b9975f1f44673965482e9); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginalcbbd6fe8562b9975f1f44673965482e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcbbd6fe8562b9975f1f44673965482e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar-item','data' => ['href' => ''.e(route('admin.pencairan')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.pencairan')).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

            <i class="bi bi-wallet2"></i>
            Withdrawal
            <?php
                $pendingWithdrawals = \App\Models\Withdrawal::where('status', 'pending')->count();
            ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingWithdrawals > 0): ?>
                <span class="badge rounded-pill bg-danger ms-auto">
                    <?php echo e($pendingWithdrawals > 9 ? '9+' : $pendingWithdrawals); ?>

                </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcbbd6fe8562b9975f1f44673965482e9)): ?>
<?php $attributes = $__attributesOriginalcbbd6fe8562b9975f1f44673965482e9; ?>
<?php unset($__attributesOriginalcbbd6fe8562b9975f1f44673965482e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcbbd6fe8562b9975f1f44673965482e9)): ?>
<?php $component = $__componentOriginalcbbd6fe8562b9975f1f44673965482e9; ?>
<?php unset($__componentOriginalcbbd6fe8562b9975f1f44673965482e9); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginalcbbd6fe8562b9975f1f44673965482e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcbbd6fe8562b9975f1f44673965482e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar-item','data' => ['href' => ''.e(route('admin.bank')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.bank')).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

            <i class="bi bi-bank"></i>
            Manajemen Bank
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcbbd6fe8562b9975f1f44673965482e9)): ?>
<?php $attributes = $__attributesOriginalcbbd6fe8562b9975f1f44673965482e9; ?>
<?php unset($__attributesOriginalcbbd6fe8562b9975f1f44673965482e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcbbd6fe8562b9975f1f44673965482e9)): ?>
<?php $component = $__componentOriginalcbbd6fe8562b9975f1f44673965482e9; ?>
<?php unset($__componentOriginalcbbd6fe8562b9975f1f44673965482e9); ?>
<?php endif; ?>

        <li class="sidebar-label px-3 text-uppercase fw-bold mb-2 mt-3">Stakeholder</li>
        <?php if (isset($component)) { $__componentOriginalcbbd6fe8562b9975f1f44673965482e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcbbd6fe8562b9975f1f44673965482e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar-item','data' => ['href' => ''.e(route('admin.fundraiser')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.fundraiser')).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

            <i class="bi bi-people"></i>
            Mitra Fundraiser
            <?php
                $pendingFundraisers = \App\Models\Fundraiser::where('status', 'pending')->count();
            ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingFundraisers > 0): ?>
                <span class="badge rounded-pill bg-danger ms-auto">
                    <?php echo e($pendingFundraisers > 9 ? '9+' : $pendingFundraisers); ?>

                </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcbbd6fe8562b9975f1f44673965482e9)): ?>
<?php $attributes = $__attributesOriginalcbbd6fe8562b9975f1f44673965482e9; ?>
<?php unset($__attributesOriginalcbbd6fe8562b9975f1f44673965482e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcbbd6fe8562b9975f1f44673965482e9)): ?>
<?php $component = $__componentOriginalcbbd6fe8562b9975f1f44673965482e9; ?>
<?php unset($__componentOriginalcbbd6fe8562b9975f1f44673965482e9); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginalcbbd6fe8562b9975f1f44673965482e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcbbd6fe8562b9975f1f44673965482e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar-item','data' => ['href' => ''.e(route('admin.donatur')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.donatur')).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

            <i class="bi bi-person-heart"></i>
            Daftar Donatur
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcbbd6fe8562b9975f1f44673965482e9)): ?>
<?php $attributes = $__attributesOriginalcbbd6fe8562b9975f1f44673965482e9; ?>
<?php unset($__attributesOriginalcbbd6fe8562b9975f1f44673965482e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcbbd6fe8562b9975f1f44673965482e9)): ?>
<?php $component = $__componentOriginalcbbd6fe8562b9975f1f44673965482e9; ?>
<?php unset($__componentOriginalcbbd6fe8562b9975f1f44673965482e9); ?>
<?php endif; ?>

        <li class="sidebar-label px-3 text-uppercase fw-bold mb-2 mt-3">Konten & Sistem</li>
        <?php if (isset($component)) { $__componentOriginalcbbd6fe8562b9975f1f44673965482e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcbbd6fe8562b9975f1f44673965482e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar-item','data' => ['href' => ''.e(route('admin.artikel')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.artikel')).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

            <i class="bi bi-newspaper"></i>
            Manajemen Artikel
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcbbd6fe8562b9975f1f44673965482e9)): ?>
<?php $attributes = $__attributesOriginalcbbd6fe8562b9975f1f44673965482e9; ?>
<?php unset($__attributesOriginalcbbd6fe8562b9975f1f44673965482e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcbbd6fe8562b9975f1f44673965482e9)): ?>
<?php $component = $__componentOriginalcbbd6fe8562b9975f1f44673965482e9; ?>
<?php unset($__componentOriginalcbbd6fe8562b9975f1f44673965482e9); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginalcbbd6fe8562b9975f1f44673965482e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcbbd6fe8562b9975f1f44673965482e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar-item','data' => ['href' => ''.e(route('admin.settings')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar-item'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.settings')).'']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

            <i class="bi bi-gear"></i>
            Pengaturan
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcbbd6fe8562b9975f1f44673965482e9)): ?>
<?php $attributes = $__attributesOriginalcbbd6fe8562b9975f1f44673965482e9; ?>
<?php unset($__attributesOriginalcbbd6fe8562b9975f1f44673965482e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcbbd6fe8562b9975f1f44673965482e9)): ?>
<?php $component = $__componentOriginalcbbd6fe8562b9975f1f44673965482e9; ?>
<?php unset($__componentOriginalcbbd6fe8562b9975f1f44673965482e9); ?>
<?php endif; ?>
    </ul>
    <div class="sidebar-footer">
        <ul class="sidebar-items p-3 mt-auto">
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('auth.logout', []);

$key = null;
$__componentSlots = [];

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-2670261584-0', $key);

$__html = app('livewire')->mount($__name, $__params, $key, $__componentSlots);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
        </ul>
    </div>
</aside>
<?php /**PATH C:\laragon\www\inisiatif\resources\views/components/admin/sidebar.blade.php ENDPATH**/ ?>