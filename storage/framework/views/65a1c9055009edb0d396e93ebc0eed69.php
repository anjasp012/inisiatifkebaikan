<?php
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use App\Models\CampaignCategory;
?>

<div>
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Ubah Kategori Campaign</h5>
                    <p class="text-muted small mb-0">Edit informasi kategori campaign yang sudah ada.</p>
                </div>
                <a href="<?php echo e(route('admin.kategori-campaign')); ?>" wire:navigate class="btn btn-light border">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <form wire:submit="store">
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <label class="form-label d-block">Tipe Icon</label>
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="iconType" id="typeSelect" value="select"
                                wire:model.live="iconType">
                            <label class="btn btn-outline-primary" for="typeSelect">
                                <i class="bi bi-grid me-1"></i> Pilih Icon
                            </label>

                            <input type="radio" class="btn-check" name="iconType" id="typeUpload" value="upload"
                                wire:model.live="iconType">
                            <label class="btn btn-outline-primary" for="typeUpload">
                                <i class="bi bi-upload me-1"></i> Upload Gambar
                            </label>
                        </div>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($iconType === 'upload'): ?>
                        <div class="col-md-12">
                            <?php if (isset($component)) { $__componentOriginal6384af2cfbb3fb249311eef9f601626b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6384af2cfbb3fb249311eef9f601626b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.file-upload','data' => ['model' => 'icon','label' => 'Upload Icon','preview' => $icon
                                ? $icon->temporaryUrl()
                                : ($campaignCategory->icon_url
                                    ? $campaignCategory->icon_url
                                    : null)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.file-upload'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'icon','label' => 'Upload Icon','preview' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($icon
                                ? $icon->temporaryUrl()
                                : ($campaignCategory->icon_url
                                    ? $campaignCategory->icon_url
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
                            <div class="form-text">Format: JPG, PNG. Maks: 2MB.</div>
                        </div>
                    <?php else: ?>
                        <div class="col-md-12">
                            <label class="form-label">Pilih Icon Bootstrap</label>
                            <input type="text" class="form-control mb-3"
                                placeholder="Cari icon (contoh: heart, user, money)..."
                                wire:model.live.debounce.300ms="searchIcon">

                            <div class="border rounded p-3 bg-light" style="max-height: 300px; overflow-y: auto;">
                                <div class="row row-cols-auto g-2 justify-content-center justify-content-md-start">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->filteredIcons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $biIcon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <div class="col">
                                            <button type="button"
                                                class="btn btn-outline-secondary d-flex align-items-center justify-content-center <?php echo e($selectedIcon === $biIcon ? 'active bg-primary text-white' : ''); ?>"
                                                style="width: 50px; height: 50px;"
                                                wire:click="selectIcon('<?php echo e($biIcon); ?>')">
                                                <i class="bi bi-<?php echo e($biIcon); ?> fs-4"></i>
                                            </button>
                                        </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($this->filteredIcons)): ?>
                                        <div class="col-12 text-center text-muted py-4">
                                            Icon tidak ditemukan
                                        </div>
                                    <?php elseif(empty($searchIcon) && count($bootstrapIcons) > 100): ?>
                                        <div class="col-12 text-center text-muted py-2 small">
                                            Menampilkan 100 icon pertama. Gunakan pencarian untuk hasil lainnya.
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['selectedIcon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedIcon): ?>
                                <div class="mt-2">
                                    <span class="text-muted small">Icon terpilih: </span>
                                    <span class="badge bg-primary"><i class="bi bi-<?php echo e($selectedIcon); ?> me-1"></i>
                                        <?php echo e($selectedIcon); ?></span>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="col-md-12">
                        <label for="name" class="form-label">Nama Kategori Campaign</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" wire:model="name"
                            id="name" placeholder="Masukan nama kategori campaign">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
                    <a href="<?php echo e(route('admin.kategori-campaign')); ?>" class="btn btn-light border px-4 fw-semibold"
                        wire:navigate>Batal</a>
                    <button type="submit" class="btn btn-primary text-white fw-semibold px-4"
                        wire:loading.attr="disabled" wire:target="store">
                        <span wire:loading.remove wire:target="store">
                            Simpan Perubahan <i class="bi bi-floppy-fill ms-2"></i>
                        </span>
                        <span wire:loading wire:target="store">
                            <div class="spinner-border spinner-border-sm" role="status"></div>
                        </span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/55261bc8.blade.php ENDPATH**/ ?>