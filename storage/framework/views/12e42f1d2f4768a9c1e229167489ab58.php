<?php
use Livewire\Component;
use App\Models\Donation;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
?>

<div x-data="{
    history: JSON.parse(localStorage.getItem('donation_history') || '[]')
}" x-init="if (<?php echo e(Auth::check() ? 'true' : 'false'); ?>) {
    $wire.syncHistory(history);
} else {
    $wire.setLocalHistory(history);
}"
    @history-synced.window="localStorage.removeItem('donation_history'); history = [];">
    <?php if (isset($component)) { $__componentOriginal87a36371cd3c2919bc8ca9c86f23c76e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal87a36371cd3c2919bc8ca9c86f23c76e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.navbar-secondary','data' => ['title' => 'Donasi Saya']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.navbar-secondary'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Donasi Saya']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal87a36371cd3c2919bc8ca9c86f23c76e)): ?>
<?php $attributes = $__attributesOriginal87a36371cd3c2919bc8ca9c86f23c76e; ?>
<?php unset($__attributesOriginal87a36371cd3c2919bc8ca9c86f23c76e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal87a36371cd3c2919bc8ca9c86f23c76e)): ?>
<?php $component = $__componentOriginal87a36371cd3c2919bc8ca9c86f23c76e; ?>
<?php unset($__componentOriginal87a36371cd3c2919bc8ca9c86f23c76e); ?>
<?php endif; ?>

    <section class="donasi-saya-section py-4">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 class="section-title">Riwayat Donasi</h2>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->guest()): ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($this->localHistory)): ?>
                    <div class="alert alert-primary border-0 rounded-4 mb-4 small">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        History ini sementara. <a href="<?php echo e(route('login')); ?>" class="fw-bold text-decoration-none">Login</a>
                        untuk simpan permanen.
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="donation-list">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->donations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $donation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <a href="<?php echo e(route('donation.instruction', $donation->transaction_id)); ?>" wire:navigate
                        class="card border-secondary mb-3 text-decoration-none overflow-hidden">
                        <div class="card-body p-3">
                            <div class="d-flex gap-3">
                                <div class="rounded-3 overflow-hidden"
                                    style="width: 80px; height: 60px; flex-shrink: 0;">
                                    <img src="<?php echo e($donation->campaign->thumbnail_url); ?>"
                                        class="w-100 h-100 object-fit-cover" alt="<?php echo e($donation->campaign->title); ?>">
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="fw-bold text-dark mb-1 text-truncate" style="font-size: 13px;">
                                        <?php echo e($donation->campaign->title); ?></h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-primary fw-bold" style="font-size: 14px;">Rp
                                            <?php echo e(number_format($donation->amount, 0, ',', '.')); ?></span>
                                        <span
                                            class="badge <?php echo e($donation->status === 'success' ? 'bg-success' : ($donation->status === 'failed' ? 'bg-danger' : 'bg-warning')); ?> px-2 py-1"
                                            style="font-size: 10px;">
                                            <?php echo e(ucfirst($donation->status)); ?>

                                        </span>
                                    </div>
                                    <div class="text-muted extra-small mt-1">
                                        <?php echo e($donation->created_at->translatedFormat('d M Y, H:i')); ?></div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-gift text-primary opacity-25" style="font-size: 80px;"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Belum ada donasi</h6>
                        <p class="text-muted small mb-4 px-4">
                            Anda belum memiliki riwayat donasi. Semua program kebaikan menanti bantuan Anda.
                        </p>
                        <a href="/" wire:navigate
                            class="btn btn-primary px-4 py-2 rounded-pill fw-bold shadow-sm small">
                            Cari Program Donasi
                        </a>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </section>

    <?php if (isset($component)) { $__componentOriginald5197275cb73aa1d2b33640fa04b6785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald5197275cb73aa1d2b33640fa04b6785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.bottom-nav','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.bottom-nav'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald5197275cb73aa1d2b33640fa04b6785)): ?>
<?php $attributes = $__attributesOriginald5197275cb73aa1d2b33640fa04b6785; ?>
<?php unset($__attributesOriginald5197275cb73aa1d2b33640fa04b6785); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald5197275cb73aa1d2b33640fa04b6785)): ?>
<?php $component = $__componentOriginald5197275cb73aa1d2b33640fa04b6785; ?>
<?php unset($__componentOriginald5197275cb73aa1d2b33640fa04b6785); ?>
<?php endif; ?>
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/f0f9a593.blade.php ENDPATH**/ ?>