<?php
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use App\Models\Campaign;
?>

<div class="bg-white min-vh-100">
    <?php if (isset($component)) { $__componentOriginal87a36371cd3c2919bc8ca9c86f23c76e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal87a36371cd3c2919bc8ca9c86f23c76e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.navbar-secondary','data' => ['route' => ''.e(route('campaign.show', $campaign->slug)).'','title' => 'Masukan Nominal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.navbar-secondary'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => ''.e(route('campaign.show', $campaign->slug)).'','title' => 'Masukan Nominal']); ?>
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

    <section class="py-3">
        <div class="container-fluid">
            
            <div class="card border-0 bg-light mb-4 overflow-hidden">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo e($campaign->thumbnail_url); ?>" class="rounded-3 object-fit-cover flex-shrink-0"
                            style="width: 70px; height: 70px;" alt="">
                        <div class="ms-3 overflow-hidden">
                            <h6 class="fw-bold mb-1 text-dark text-truncate"><?php echo e($campaign->title); ?></h6>
                            <div class="d-flex align-items-center small text-muted">
                                <span class="bg-white border px-2 py-1 rounded-pill me-2" style="font-size: 0.7rem;">
                                    <?php echo e($campaign->category->name ?? 'Umum'); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="mb-4">
                <label class="fw-bold text-dark mb-2 d-block">Mau donasi berapa?</label>
                <div class="position-relative">
                    <div
                        class="input-group input-group-lg border overflow-hidden rounded-2 <?php echo e($errors->has('amount') ? 'border-danger' : ($customAmount ? 'border-primary' : 'border-secondary-subtle')); ?>">
                        <span class="input-group-text bg-transparent border-0 pe-1 text-muted fw-bold">Rp</span>
                        <input type="tel" wire:model.live="customAmount"
                            class="form-control border-0 bg-transparent fw-bold fs-4 shadow-none ps-1" placeholder="0"
                            inputmode="numeric">
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($customAmount && (int) preg_replace('/[^0-9]/', '', $customAmount) < 10000): ?>
                        <small class="text-danger position-absolute" style="bottom: -22px; left: 0;">Min. Rp
                            10.000</small>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger small mt-1 fw-medium"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div class="mb-5">
                <label class="fw-bold text-muted small text-uppercase mb-3 d-block">Atau pilih nominal instan</label>
                <div class="row g-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [10000, 20000, 50000, 100000, 200000, 500000]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $preset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="col-6">
                            <button wire:click="selectAmount(<?php echo e($preset); ?>)"
                                class="btn w-100 py-3 rounded-3 fw-bold position-relative overflow-hidden <?php echo e($amount == $preset ? 'btn-primary border-primary' : 'btn-outline-secondary border-secondary-subtle text-dark bg-white'); ?>"
                                style="transition: all 0.2s;">
                                <span class="d-block <?php echo e($amount == $preset ? 'text-white' : 'text-dark'); ?>">
                                    Rp <?php echo e(number_format($preset, 0, ',', '.')); ?>

                                </span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($amount == $preset): ?>
                                    <div class="position-absolute top-0 end-0 p-1">
                                        <i class="bi bi-check-circle-fill text-white small"></i>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </button>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>

            
            <div class="text-center text-muted opacity-50 mb-5">
                <i class="bi bi-shield-lock-fill me-1"></i> Pembayaran Aman & Terverifikasi
            </div>
        </div>
    </section>

    
    <div class="detail-cta" style="height: auto; z-index: 100;">
        <div class="w-100">
            <button wire:click="submit" class="detail-cta__button w-100 border-0 rounded-pill"
                <?php if($amount < 10000): ?> disabled <?php endif; ?>>
                Lanjut Pembayaran <i class="bi bi-arrow-right ms-2"></i>
            </button>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/cbf5044b.blade.php ENDPATH**/ ?>