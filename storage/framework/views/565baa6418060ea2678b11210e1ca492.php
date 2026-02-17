<?php
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\Campaign;
use App\Models\CampaignCategory;
?>

<div>
    <?php if (isset($component)) { $__componentOriginal0f1e1c18620a01537b7924e43a775a2a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0f1e1c18620a01537b7924e43a775a2a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.navbar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0f1e1c18620a01537b7924e43a775a2a)): ?>
<?php $attributes = $__attributesOriginal0f1e1c18620a01537b7924e43a775a2a; ?>
<?php unset($__attributesOriginal0f1e1c18620a01537b7924e43a775a2a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0f1e1c18620a01537b7924e43a775a2a)): ?>
<?php $component = $__componentOriginal0f1e1c18620a01537b7924e43a775a2a; ?>
<?php unset($__componentOriginal0f1e1c18620a01537b7924e43a775a2a); ?>
<?php endif; ?>

    <section class="py-5 bg-white">
        <div class="container-fluid">
            <!-- Header & Filter -->
            <div class="row align-items-center mb-4 g-3">
                <div class="col-md-6">
                    <h2 class="fw-bold mb-1">Daftar Program Kebaikan</h2>
                    <p class="text-muted mb-0">Mari berkontribusi untuk mereka yang membutuhkan.</p>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search"
                            class="form-control bg-light border-start-0" placeholder="Cari program kebaikan...">
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div class="d-flex flex-wrap gap-2 mb-4">
                <button wire:click="setCategory('')"
                    class="btn btn-sm rounded-pill <?php echo e($category == '' ? 'btn-primary' : 'btn-outline-secondary border-0 bg-light'); ?>">
                    Semua
                </button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->categories(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <button wire:click="setCategory('<?php echo e($cat->slug); ?>')"
                        class="btn btn-sm rounded-pill <?php echo e($category == $cat->slug ? 'btn-primary' : 'btn-outline-secondary border-0 bg-light'); ?>">
                        <?php echo e($cat->name); ?>

                    </button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>

            <!-- Grid -->
            <div class="row g-3" wire:loading.class="opacity-50">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->campaigns(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                        <?php if (isset($component)) { $__componentOriginalabd5299e7c64663b70bcc905a2f57b0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalabd5299e7c64663b70bcc905a2f57b0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.campaign-card','data' => ['campaign' => $campaign]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.campaign-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['campaign' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($campaign)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalabd5299e7c64663b70bcc905a2f57b0a)): ?>
<?php $attributes = $__attributesOriginalabd5299e7c64663b70bcc905a2f57b0a; ?>
<?php unset($__attributesOriginalabd5299e7c64663b70bcc905a2f57b0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalabd5299e7c64663b70bcc905a2f57b0a)): ?>
<?php $component = $__componentOriginalabd5299e7c64663b70bcc905a2f57b0a; ?>
<?php unset($__componentOriginalabd5299e7c64663b70bcc905a2f57b0a); ?>
<?php endif; ?>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <div class="col-12 text-center py-5">
                        <div class="mb-3 text-muted opacity-50">
                            <i class="bi bi-search display-1"></i>
                        </div>
                        <h5 class="fw-bold text-muted">Tidak ditemukan</h5>
                        <p class="text-muted small">Coba kata kunci lain atau kategori berbeda.</p>
                        <button wire:click="$set('search', ''); $set('category', '')"
                            class="btn btn-outline-primary btn-sm rounded-pill mt-2">
                            Reset Filter
                        </button>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-center">
                <?php echo e($this->campaigns()->links()); ?>

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
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/303c6548.blade.php ENDPATH**/ ?>