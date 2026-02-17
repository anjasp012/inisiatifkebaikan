<?php
use Livewire\Attributes\Layout;
use App\Models\Distribution;
use App\Models\Campaign;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\WithFileUploads;
?>

<div>
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card card-dashboard  mb-4">
                <div class="card-body border-bottom">
                    <h6 class="fw-bold mb-0">Input Penyaluran Dana</h6>
                </div>
                <div class="card-body bg-light bg-opacity-50">
                    <form wire:submit.prevent="store">
                        <div class="mb-3 <?php $__errorArgs = ['campaign_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid-tomselect <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <label class="form-label small fw-bold text-uppercase opacity-75">Program Campaign</label>
                            <div wire:ignore>
                                <select wire:model="campaign_id"
                                    class="form-select <?php $__errorArgs = ['campaign_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    x-init="new TomSelect($el, {
                                        placeholder: 'Pilih atau cari Campaign...',
                                        allowEmptyOption: false,
                                        onDropdownOpen: function() {
                                            this.clear(true);
                                        },
                                        onChange: (value) => {
                                            $wire.set('campaign_id', value);
                                        }
                                    })">
                                    <option value="">-- Pilih Campaign --</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->campaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <option value="<?php echo e($campaign->id); ?>"><?php echo e($campaign->title); ?></option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['campaign_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <?php if (isset($component)) { $__componentOriginal8a17b1f728b4d3b64d1256cf0f2b5c31 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a17b1f728b4d3b64d1256cf0f2b5c31 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.input-rupiah','data' => ['model' => 'amount','label' => 'Nominal Disalurkan','placeholder' => '0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.input-rupiah'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'amount','label' => 'Nominal Disalurkan','placeholder' => '0']); ?>
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

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase opacity-75">Penerima Manfaat</label>
                            <input type="text" wire:model="recipient_name"
                                class="form-control rounded-3 <?php $__errorArgs = ['recipient_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Contoh: Warga Terdampak Banjir">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['recipient_name'];
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

                        <div class="mb-3">
                            <?php if (isset($component)) { $__componentOriginald1ee22dc0d4069f9cc5cebafdb28cf83 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald1ee22dc0d4069f9cc5cebafdb28cf83 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.input-calendar','data' => ['model' => 'distribution_date','label' => 'Tanggal Penyaluran']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.input-calendar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'distribution_date','label' => 'Tanggal Penyaluran']); ?>
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

                        <div class="col-md-12 mb-3">
                            <label class="form-label small fw-bold text-uppercase opacity-75">Kabar Terbaru</label>
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

                        <div class="mb-4">
                            <?php if (isset($component)) { $__componentOriginal6384af2cfbb3fb249311eef9f601626b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6384af2cfbb3fb249311eef9f601626b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.file-upload','data' => ['model' => 'file_path','label' => 'Foto Dokumentasi','preview' => $file_path ? $file_path->temporaryUrl() : null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.file-upload'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'file_path','label' => 'Foto Dokumentasi','preview' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($file_path ? $file_path->temporaryUrl() : null)]); ?>
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

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary text-white fw-bold px-4 shadow-sm">
                                <i class="bi bi-send-fill me-2 text-white"></i> Publikasikan Kabar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card card-dashboard">
                <div class="card-body border-bottom">
                    <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center gap-3">
                        <div>
                            <h5 class="fw-bold mb-1">Riwayat Penyaluran</h5>
                            <p class="text-muted small mb-0">Daftar kabar terbaru penyaluran dana ke penerima manfaat.
                            </p>
                        </div>

                        <div class="position-relative">
                            <input type="text" class="form-control ps-5" placeholder="Cari penyaluran..."
                                wire:model.live.debounce.300ms="search" style="min-width: 250px;">
                            <i
                                class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-uppercase x-small fw-bold text-muted">
                                <th class="ps-4" style="width: 50px;">No</th>
                                <th>Program & Penerima</th>
                                <th class="text-end">Nominal</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->distributions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $dist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <tr <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processElementKey('dist-row-{{ $dist->id }}', get_defined_vars()); ?>wire:key="dist-row-<?php echo e($dist->id); ?>">
                                    <td class="ps-4 text-center text-muted small">
                                        <?php echo e($this->distributions->firstItem() + $index); ?>

                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark small">
                                            <?php echo e($dist->campaign->title ?? 'Campaign Terhapus'); ?></div>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($dist->campaign && $dist->campaign->category): ?>
                                                <span
                                                    class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 border border-primary border-opacity-10"
                                                    style="font-size: 9px; font-weight: 600;">
                                                    <i class="bi bi-tag-fill me-1"></i>
                                                    <?php echo e($dist->campaign->category->name); ?>

                                                </span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <div class="text-muted x-small">Penerima: <?php echo e($dist->recipient_name); ?></div>
                                        </div>
                                    </td>
                                    <td class="text-end fw-bold text-primary">
                                        Rp <?php echo e(number_format($dist->amount, 0, ',', '.')); ?>

                                    </td>
                                    <td class="text-center small">
                                        <div class="fw-medium">
                                            <?php echo e(Carbon\Carbon::parse($dist->distribution_date)->translatedFormat('d M Y')); ?>

                                        </div>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            <button type="button" <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processElementKey('show-{{ $dist->id }}', get_defined_vars()); ?>wire:key="show-<?php echo e($dist->id); ?>"
                                                wire:click="showDistribution(<?php echo e($dist->id); ?>)"
                                                class="btn btn-sm btn-info text-white" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button wire:click="destroy(<?php echo e($dist->id); ?>)"
                                                wire:confirm="Anda yakin ingin menghapus data ini?"
                                                class="btn btn-sm btn-danger text-white" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                                            Belum ada kabar penyaluran dana.
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                        <div class="text-muted small">
                            Menampilkan <strong><?php echo e($this->distributions->firstItem()); ?></strong> -
                            <strong><?php echo e($this->distributions->lastItem()); ?></strong> dari
                            <strong><?php echo e($this->distributions->total()); ?></strong> penyaluran
                        </div>
                        <div>
                            <?php echo e($this->distributions->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedDistribution): ?>
                    <div <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processElementKey('modal-content-{{ $selectedDistribution->id }}', get_defined_vars()); ?>wire:key="modal-content-<?php echo e($selectedDistribution->id); ?>">
                        <div class="modal-header border-bottom p-4">
                            <h5 class="modal-title fw-bold">Detail Penyaluran Dana</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="row g-4">
                                <div class="col-md-5">
                                    <label class="form-label x-small fw-bold text-uppercase text-muted">Foto
                                        Dokumentasi</label>
                                    <img src="<?php echo e($selectedDistribution->file_url); ?>"
                                        class="img-fluid rounded shadow-sm border" alt="Dokumentasi">
                                </div>
                                <div class="col-md-7">
                                    <label class="form-label x-small fw-bold text-uppercase text-muted">Nama
                                        Campaign</label>
                                    <h6 class="fw-bold mb-1 text-primary">
                                        <?php echo e($selectedDistribution->campaign->title ?? 'Campaign Terhapus'); ?>

                                    </h6>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedDistribution->campaign && $selectedDistribution->campaign->category): ?>
                                        <span
                                            class="badge bg-primary bg-opacity-10 text-primary x-small border border-primary border-opacity-10">
                                            <?php echo e($selectedDistribution->campaign->category->name); ?>

                                        </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-6">
                                        <label
                                            class="form-label x-small fw-bold text-uppercase text-muted">Penerima</label>
                                        <div class="fw-bold"><?php echo e($selectedDistribution->recipient_name); ?></div>
                                    </div>
                                    <div class="col-6">
                                        <label
                                            class="form-label x-small fw-bold text-uppercase text-muted">Tanggal</label>
                                        <div class="fw-bold">
                                            <?php echo e(Carbon\Carbon::parse($selectedDistribution->distribution_date)->translatedFormat('d M Y')); ?>

                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label x-small fw-bold text-uppercase text-muted">Total
                                            Disalurkan</label>
                                        <h5 class="fw-bold text-primary mb-0">
                                            Rp <?php echo e(number_format($selectedDistribution->amount, 0, ',', '.')); ?>

                                        </h5>
                                    </div>
                                </div>

                                <label class="form-label x-small fw-bold text-uppercase text-muted">Keterangan /
                                    Kabar</label>
                                <div class="p-3 bg-light rounded border small ck-content">
                                    <?php echo $selectedDistribution->description; ?>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div>



<?php $__env->startPush('scripts'); ?>
    
<?php $__env->stopPush(); ?><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/efd3db32.blade.php ENDPATH**/ ?>