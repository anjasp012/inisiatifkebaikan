<?php
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Campaign;
use App\Models\CampaignUpdate;
?>

<div>
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Ubah Update</h5>
                    <p class="text-muted small mb-0">
                        Edit pengumuman update yang sudah ada.
                    </p>
                </div>
                <a href="<?php echo e(route('admin.campaign.updates', $campaign)); ?>" wire:navigate class="btn btn-light border">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <form wire:submit="save">
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <?php if (isset($component)) { $__componentOriginal6384af2cfbb3fb249311eef9f601626b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6384af2cfbb3fb249311eef9f601626b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.file-upload','data' => ['model' => 'image','label' => 'Gambar Update (Opsional)','preview' => $image
                            ? $image->temporaryUrl()
                            : ($existingImage
                                ? asset('storage/' . $existingImage)
                                : null)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.file-upload'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'image','label' => 'Gambar Update (Opsional)','preview' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($image
                            ? $image->temporaryUrl()
                            : ($existingImage
                                ? asset('storage/' . $existingImage)
                                : null))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6384af2cfbb3fb249311eef9f601626b)): ?>
<?php $attributes = $__attributesOriginal6384af2cfbb3fb249311eef9f601626b; ?>
<?php unset($__attributesOriginal6384af2cfbb3fb249311eef9f601626b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6384af2cfbb3fb249311eef9f601626b)): ?>
<?php $component = $__componentOriginal6384af2cfbb3fb249311eef9f601626b; ?>
<?php unset($__componentOriginal6384af2cfbb3fb249311eef9f601626b); ?>
<?php endif; ?>
                    </div>

                    <div class="col-md-8">
                        <label for="title" class="form-label">Judul Update</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="title"
                            wire:model="title" placeholder="Masukkan judul update...">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="col-md-4">
                        <?php if (isset($component)) { $__componentOriginald1ee22dc0d4069f9cc5cebafdb28cf83 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald1ee22dc0d4069f9cc5cebafdb28cf83 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.input-calendar','data' => ['model' => 'published_at','label' => 'Tanggal Publikasi']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.input-calendar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'published_at','label' => 'Tanggal Publikasi']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald1ee22dc0d4069f9cc5cebafdb28cf83)): ?>
<?php $attributes = $__attributesOriginald1ee22dc0d4069f9cc5cebafdb28cf83; ?>
<?php unset($__attributesOriginald1ee22dc0d4069f9cc5cebafdb28cf83); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald1ee22dc0d4069f9cc5cebafdb28cf83)): ?>
<?php $component = $__componentOriginald1ee22dc0d4069f9cc5cebafdb28cf83; ?>
<?php unset($__componentOriginald1ee22dc0d4069f9cc5cebafdb28cf83); ?>
<?php endif; ?>
                    </div>

                    <div class="col-md-12" wire:ignore>
                        <label for="content" class="form-label">Konten Update</label>
                        <?php if (isset($component)) { $__componentOriginal677a12d6ed87baafb23d5a8c9258b967 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal677a12d6ed87baafb23d5a8c9258b967 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.text-editor','data' => ['model' => 'content','id' => 'content']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.text-editor'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'content','id' => 'content']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal677a12d6ed87baafb23d5a8c9258b967)): ?>
<?php $attributes = $__attributesOriginal677a12d6ed87baafb23d5a8c9258b967; ?>
<?php unset($__attributesOriginal677a12d6ed87baafb23d5a8c9258b967); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal677a12d6ed87baafb23d5a8c9258b967)): ?>
<?php $component = $__componentOriginal677a12d6ed87baafb23d5a8c9258b967; ?>
<?php unset($__componentOriginal677a12d6ed87baafb23d5a8c9258b967); ?>
<?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="<?php echo e(route('admin.campaign.updates', $campaign)); ?>" wire:navigate
                        class="btn btn-light border">Batal</a>
                    <button type="submit" class="btn btn-primary text-white">
                        <span wire:loading.remove>
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </span>
                        <span wire:loading>
                            <div class="spinner-border spinner-border-sm" role="status"></div> Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/b45fedba.blade.php ENDPATH**/ ?>