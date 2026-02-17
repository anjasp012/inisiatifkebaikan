<?php
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Campaign;
use App\Models\Withdrawal;
use App\Models\Donation;
?>

<div>
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Buat Pencairan Baru</h5>
                    <p class="text-muted small mb-0">Ajukan pencairan dana untuk campaign internal atau operasional.</p>
                </div>
                <a href="<?php echo e(route('admin.pencairan')); ?>" wire:navigate class="btn btn-light border">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <form wire:submit="save">
                <div class="row g-4">
                    
                    <div class="col-lg-8">
                        <div class="bg-light p-4 rounded-4 h-100">
                            <h6 class="fw-bold mb-4 border-bottom pb-2">Formulir Pencairan</h6>

                            
                            <div class="mb-4 <?php $__errorArgs = ['campaign_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid-tomselect <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <label class="form-label small fw-bold text-uppercase">Pilih Campaign</label>
                                <div wire:ignore>
                                    <select x-data="{
                                        tom: null,
                                        init() {
                                            this.tom = new TomSelect(this.$el, {
                                                placeholder: 'Pilih atau cari Campaign...',
                                                allowEmptyOption: false,
                                                maxOptions: 50,
                                                onDropdownOpen: function() {
                                                    this.clear(true);
                                                },
                                                onChange: (value) => {
                                                    $wire.set('campaign_id', value || null);
                                                }
                                            });
                                        }
                                    }"
                                        class="form-select <?php $__errorArgs = ['campaign_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
is-invalid
<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value=""></option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = \App\Models\Campaign::with('fundraiser')->where('collected_amount', '>', 0)->latest()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                            <option value="<?php echo e($campaign->id); ?>">
                                                <?php echo e($campaign->title); ?>

                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($campaign->fundraiser): ?>
                                                    - <?php echo e($campaign->fundraiser->foundation_name); ?>

                                                <?php else: ?>
                                                    (Internal)
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </select>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['campaign_id'];
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
                                <?php if (isset($component)) { $__componentOriginal8a17b1f728b4d3b64d1256cf0f2b5c31 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a17b1f728b4d3b64d1256cf0f2b5c31 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.input-rupiah','data' => ['model' => 'amount','label' => 'Nominal Pencairan','placeholder' => '0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.input-rupiah'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'amount','label' => 'Nominal Pencairan','placeholder' => '0']); ?>
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
                                <div class="d-flex justify-content-between x-small text-muted mt-1 px-1">
                                    <span>Min: Rp 10.000</span>
                                    <span class="fw-bold text-primary">Max: Rp
                                        <?php echo e(number_format($this->maxAmount, 0, ',', '.')); ?></span>
                                </div>
                            </div>

                            <hr class="my-4 border-dashed">

                            
                            <h6 class="fw-bold mb-3 small text-uppercase">Rincian Potongan & Biaya</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <?php if (isset($component)) { $__componentOriginal8a17b1f728b4d3b64d1256cf0f2b5c31 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a17b1f728b4d3b64d1256cf0f2b5c31 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.input-rupiah','data' => ['model' => 'ads_fee','label' => 'Biaya Iklan (Ads)','placeholder' => '0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.input-rupiah'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'ads_fee','label' => 'Biaya Iklan (Ads)','placeholder' => '0']); ?>
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
                                    <div class="form-text x-small text-muted mt-0">PPN 11% otomatis: Rp
                                        <?php echo e(number_format($this->adsVat, 0, ',', '.')); ?></div>
                                </div>
                                <div class="col-md-6">
                                    <?php if (isset($component)) { $__componentOriginal8a17b1f728b4d3b64d1256cf0f2b5c31 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a17b1f728b4d3b64d1256cf0f2b5c31 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.input-rupiah','data' => ['model' => 'merchant_fee','label' => 'Biaya Merchant (Otomatis)','disabled' => true,'placeholder' => '0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.input-rupiah'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'merchant_fee','label' => 'Biaya Merchant (Otomatis)','disabled' => true,'placeholder' => '0']); ?>
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
                                    <div class="form-text x-small text-muted mt-0">Total biaya payment gateway belum
                                        diklaim</div>
                                </div>
                                <div class="col-md-6">
                                    <?php if (isset($component)) { $__componentOriginal8a17b1f728b4d3b64d1256cf0f2b5c31 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a17b1f728b4d3b64d1256cf0f2b5c31 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.input-rupiah','data' => ['model' => 'platform_fee','label' => 'Fee Platform (5%)','placeholder' => '0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.input-rupiah'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'platform_fee','label' => 'Fee Platform (5%)','placeholder' => '0']); ?>
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
                                <div class="col-md-6">
                                    <?php if (isset($component)) { $__componentOriginal8a17b1f728b4d3b64d1256cf0f2b5c31 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a17b1f728b4d3b64d1256cf0f2b5c31 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.input-rupiah','data' => ['model' => 'optimization_fee','label' => 'Fee Optimasi (15%)','placeholder' => '0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.input-rupiah'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'optimization_fee','label' => 'Fee Optimasi (15%)','placeholder' => '0']); ?>
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
                            </div>

                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-12">
                                    <label class="form-label small fw-bold text-uppercase">Catatan (Opsional)</label>
                                    <textarea wire:model="notes" class="form-control" rows="3" placeholder="Catatan internal..."></textarea>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label small fw-bold text-uppercase">Bukti Transfer
                                        (Opsional)</label>
                                    <input type="file" wire:model="proof_image" class="form-control">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['proof_image'];
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

                        </div>
                    </div>

                    
                    <div class="col-lg-4">
                        <div class="bg-light p-4 rounded-4 h-100">
                            <h6 class="fw-bold mb-4 border-bottom pb-2">Ringkasan</h6>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->selectedCampaign): ?>
                                <div class="mb-4">
                                    <h6 class="text-uppercase text-muted x-small fw-bold mb-2">Campaign</h6>
                                    <div class="fw-bold mb-1"><?php echo e($this->selectedCampaign->title); ?></div>
                                    <div class="small text-muted mb-2">Created
                                        <?php echo e($this->selectedCampaign->created_at->format('d M Y')); ?></div>

                                    <div class="p-3 bg-white rounded-3 border mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="x-small text-muted">Terkumpul</span>
                                            <span class="x-small fw-bold">Rp
                                                <?php echo e(number_format($this->selectedCampaign->collected_amount, 0, ',', '.')); ?></span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="x-small text-muted">Bisa Dicairkan</span>
                                            <span class="x-small fw-bold text-success">Rp
                                                <?php echo e(number_format($this->maxAmount, 0, ',', '.')); ?></span>
                                        </div>
                                    </div>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->selectedCampaign->is_optimized): ?>
                                        <div
                                            class="alert alert-info d-flex align-items-center p-2 mb-0 border-0 bg-info bg-opacity-10 text-info x-small">
                                            <i class="bi bi-lightning-fill me-2"></i>
                                            <span class="fw-bold">Campaign Dioptimasi (Fee 15%)</span>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <div class="mb-4">
                                <h6 class="text-uppercase text-muted x-small fw-bold mb-2">Rekening Tujuan</h6>
                                <div class="p-3 bg-white rounded-3 border text-center">
                                    <span class="badge bg-secondary mb-2">Pencairan Internal / Tunai</span>
                                    <p class="x-small text-muted mb-0">Dana dicairkan langsung oleh admin.</p>
                                </div>
                            </div>

                            
                            <div class="p-3 rounded-3 mb-4 text-center"
                                style="background: linear-gradient(135deg, #10b981, #059669);">
                                <small class="text-white opacity-75 d-block text-uppercase fw-bold mb-1"
                                    style="font-size: 0.65rem; letter-spacing: 1px;">Dana Bersih Diterima</small>
                                <h3 class="fw-bold mb-0 text-white">Rp
                                    <?php echo e(number_format($this->netAmount, 0, ',', '.')); ?></h3>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 border-top pt-4 mt-4">
                    <a href="<?php echo e(route('admin.pencairan')); ?>" class="btn btn-light border px-4 py-2 fw-semibold"
                        wire:navigate>
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary fw-bold px-4 py-2" wire:loading.attr="disabled"
                        wire:target="save">
                        <span wire:loading.remove wire:target="save">
                            <i class="bi bi-save me-1"></i> Simpan Pencairan
                        </span>
                        <span wire:loading wire:target="save">
                            <div class="spinner-border spinner-border-sm me-2" role="status"></div> Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/45b71347.blade.php ENDPATH**/ ?>