<?php
use App\Models\CampaignCategory;
use Livewire\Component;
use Livewire\Attributes\Computed;
?>

<div>
    <?php if (isset($component)) { $__componentOriginal87a36371cd3c2919bc8ca9c86f23c76e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal87a36371cd3c2919bc8ca9c86f23c76e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.navbar-secondary','data' => ['title' => 'Kategori Program']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.navbar-secondary'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Kategori Program']); ?>
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

    <section class="category-section">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-4" bis_skin_checked="1">
                <h2 class="section-title">Mau berbuat baik apa hari ini?</h2>
            </div>
            <div class="row g-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->categories(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <div class="col-3">
                        <a href="<?php echo e(route('campaign.index', ['category' => $category->slug])); ?>" class="category-item">
                            <div class="category-icon">
                                <i class="<?php echo e($category->icon); ?>"></i>
                            </div>
                            <span><?php echo e($category->name); ?></span>
                        </a>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/6adc2d2c.blade.php ENDPATH**/ ?>