<?php
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use App\Models\Campaign;
use App\Models\CampaignCategory;
?>

<div>
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Tambah Campaign Baru</h5>
                    <p class="text-muted small mb-0">Buat program kebaikan untuk mulai menggalang donasi.</p>
                </div>
                <a href="<?php echo e(route('admin.campaign')); ?>" wire:navigate class="btn btn-light border">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <form wire:submit="store">
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <?php if (isset($component)) { $__componentOriginal6384af2cfbb3fb249311eef9f601626b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6384af2cfbb3fb249311eef9f601626b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.file-upload','data' => ['model' => 'thumbnail','label' => 'Thumbnail Campaign','preview' => $thumbnail ? $thumbnail->temporaryUrl() : null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.file-upload'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'thumbnail','label' => 'Thumbnail Campaign','preview' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($thumbnail ? $thumbnail->temporaryUrl() : null)]); ?>
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

                    <div class="col-md-6">
                        <label for="title" class="form-label">Judul Campaign</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            wire:model="title" id="title" placeholder="Masukan judul campaign">
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

                    <div class="col-md-6">
                        <label for="category_id" class="form-label">Kategori</label>
                        <div class="<?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid-tomselect <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <div wire:ignore>
                                <select class="form-select <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="category_id"
                                    x-data="{
                                        tom: null,
                                        init() {
                                            this.tom = new TomSelect(this.$el, {
                                                placeholder: 'Pilih atau cari Kategori...',
                                                allowEmptyOption: false,
                                                maxOptions: 50,
                                                onDropdownOpen: function() {
                                                    this.clear(true);
                                                },
                                                onChange: (value) => {
                                                    $wire.set('category_id', value || null);
                                                }
                                            });
                                        }
                                    }">
                                    <option value="">Pilih Kategori</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['category_id'];
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

                    <div class="col-md-12">
                        <label for="description" class="form-label">Deskripsi</label>
                        <?php if (isset($component)) { $__componentOriginal677a12d6ed87baafb23d5a8c9258b967 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal677a12d6ed87baafb23d5a8c9258b967 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.text-editor','data' => ['model' => 'description','id' => 'description']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.text-editor'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'description','id' => 'description']); ?>
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
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['description'];
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

                    <div class="col-md-4">
                        <?php if (isset($component)) { $__componentOriginal8a17b1f728b4d3b64d1256cf0f2b5c31 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a17b1f728b4d3b64d1256cf0f2b5c31 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.input-rupiah','data' => ['model' => 'target_amount','label' => 'Target Donasi','placeholder' => 'Masukan target donasi']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.input-rupiah'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'target_amount','label' => 'Target Donasi','placeholder' => 'Masukan target donasi']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a17b1f728b4d3b64d1256cf0f2b5c31)): ?>
<?php $attributes = $__attributesOriginal8a17b1f728b4d3b64d1256cf0f2b5c31; ?>
<?php unset($__attributesOriginal8a17b1f728b4d3b64d1256cf0f2b5c31); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a17b1f728b4d3b64d1256cf0f2b5c31)): ?>
<?php $component = $__componentOriginal8a17b1f728b4d3b64d1256cf0f2b5c31; ?>
<?php unset($__componentOriginal8a17b1f728b4d3b64d1256cf0f2b5c31); ?>
<?php endif; ?>
                    </div>

                    <div class="col-md-4">
                        <?php if (isset($component)) { $__componentOriginald1ee22dc0d4069f9cc5cebafdb28cf83 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald1ee22dc0d4069f9cc5cebafdb28cf83 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.input-calendar','data' => ['model' => 'start_date','label' => 'Tanggal Mulai']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.input-calendar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'start_date','label' => 'Tanggal Mulai']); ?>
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

                    <div class="col-md-4">
                        <?php if (isset($component)) { $__componentOriginald1ee22dc0d4069f9cc5cebafdb28cf83 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald1ee22dc0d4069f9cc5cebafdb28cf83 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.input-calendar','data' => ['model' => 'end_date','label' => 'Tanggal Selesai']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.input-calendar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'end_date','label' => 'Tanggal Selesai']); ?>
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

                    <div class="col-md-12">
                        <div class="card p-3 bg-light border-0">
                            <h6 class="mb-3 fw-bold">Opsi Klasifikasi Campaign</h6>
                            <div class="d-flex flex-wrap gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="is_emergency"
                                        id="is_emergency">
                                    <label class="form-check-label fw-semibold" for="is_emergency text-danger">
                                        Darurat & Mendesak
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="is_priority"
                                        id="is_priority">
                                    <label class="form-check-label fw-semibold" for="is_priority">
                                        Prioritas Kebaikan Hari ini
                                    </label>
                                </div>


                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="is_optimized"
                                        id="is_optimized">
                                    <label class="form-check-label fw-semibold text-primary" for="is_optimized">
                                        Optimasi Iklan (Fee 15%)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
                    <a href="<?php echo e(route('admin.campaign')); ?>" class="btn btn-light border px-4 fw-semibold"
                        wire:navigate>Batal</a>
                    <button class="btn btn-primary text-white fw-semibold px-4" wire:loading.attr="disabled"
                        wire:target="store">
                        <span wire:loading.remove wire:target="store">
                            Simpan <i class="bi bi-floppy-fill ms-2"></i>
                        </span>
                        <span wire:loading wire:target="store">
                            <div class="spinner-border spinner-border-sm" role="status"></div>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/056dc238.blade.php ENDPATH**/ ?>