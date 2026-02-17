<?php
use Livewire\Attributes\Layout;
use App\Models\Donation;
use App\Models\Campaign;
use App\Models\PaymentProof;
use App\Models\Bank;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
?>

<div>
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Input Donasi Manual</h5>
                    <p class="text-muted small mb-0">Catat donasi yang masuk melalui transfer manual atau offline.</p>
                </div>
                <a href="<?php echo e(route('admin.donasi')); ?>" wire:navigate class="btn btn-light border">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="store">
                <div class="row g-4">
                    <!-- Data Donasi -->
                    <div class="col-lg-7">
                        <div class="bg-light p-4 rounded-4 h-100">
                            <h6 class="fw-bold mb-4 border-bottom pb-2">Informasi Donasi</h6>

                            <div class="mb-3 <?php $__errorArgs = ['campaign_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid-tomselect <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <label class="form-label small fw-bold text-uppercase">Program / Campaign</label>
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
                                        <option value="">-- Pilih Program --</option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->campaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                            <option value="<?php echo e($c->id); ?>"><?php echo e($c->title); ?></option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </select>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['campaign_id'];
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
                                <label class="form-label small fw-bold text-uppercase">Nama Donatur</label>
                                <input type="text" wire:model="donor_name" class="form-control"
                                    placeholder="Nama lengkap donatur">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" wire:model="is_anonymous"
                                        id="anon">
                                    <label class="form-check-label small" for="anon">Sembunyikan nama (Hamba
                                        Allah)</label>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-12">
                                    <div class="mb-3 <?php $__errorArgs = ['bank_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid-tomselect <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <label class="form-label small fw-bold text-uppercase">Bank Tujuan
                                            Transfer</label>
                                        <div wire:ignore>
                                            <select x-data="{
                                                tom: null,
                                                init() {
                                                    this.tom = new TomSelect(this.$el, {
                                                        placeholder: 'Pilih atau cari Bank...',
                                                        allowEmptyOption: false,
                                                        maxOptions: 50,
                                                        onDropdownOpen: function() {
                                                            this.clear(true);
                                                        },
                                                        onChange: (value) => {
                                                            $wire.set('bank_id', value || null);
                                                        }
                                                    });
                                                }
                                            }"
                                                class="form-select <?php $__errorArgs = ['bank_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
is-invalid
<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                <option value="">-- Pilih Bank --</option>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->banks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                    <option value="<?php echo e($b->id); ?>"><?php echo e($b->bank_name); ?> -
                                                        <?php echo e($b->account_number); ?> (<?php echo e($b->account_name); ?>)</option>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                            </select>
                                        </div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['bank_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback text-danger small mt-1"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase">No. WhatsApp</label>
                                    <input type="text" wire:model="donor_phone" class="form-control"
                                        placeholder="628xxx">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase">Email (Opsional)</label>
                                    <input type="email" wire:model="donor_email" class="form-control"
                                        placeholder="email@contoh.com">
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <?php if (isset($component)) { $__componentOriginal8a17b1f728b4d3b64d1256cf0f2b5c31 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a17b1f728b4d3b64d1256cf0f2b5c31 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.input-rupiah','data' => ['model' => 'amount','label' => 'Nominal Donasi','placeholder' => '0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.input-rupiah'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'amount','label' => 'Nominal Donasi','placeholder' => '0']); ?>
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
                                    <label class="form-label small fw-bold text-uppercase">Status Pembayaran</label>
                                    <div class="<?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid-tomselect <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <div wire:ignore>
                                            <select x-data="{
                                                tom: null,
                                                init() {
                                                    this.tom = new TomSelect(this.$el, {
                                                        placeholder: 'Pilih Status',
                                                        allowEmptyOption: false,
                                                        onDropdownOpen: function() {
                                                            this.clear(true);
                                                        },
                                                        onChange: (value) => {
                                                            $wire.set('status', value);
                                                        }
                                                    });
                                                }
                                            }"
                                                class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
is-invalid
<?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                <option value="success">Sukses (Diterima)</option>
                                                <option value="pending">Pending (Belum Bayar)</option>
                                                <option value="failed">Gagal / Dibatalkan</option>
                                            </select>
                                        </div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['status'];
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
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-12">
                                        <?php if (isset($component)) { $__componentOriginald1ee22dc0d4069f9cc5cebafdb28cf83 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald1ee22dc0d4069f9cc5cebafdb28cf83 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.input-calendar','data' => ['model' => 'created_at','label' => 'Tanggal Donasi','enableTime' => 'true']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.input-calendar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'created_at','label' => 'Tanggal Donasi','enableTime' => 'true']); ?>
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
                                </div>

                                <div class="mb-0">
                                    <label class="form-label small fw-bold text-uppercase">Pesan / Doa
                                        (Opsional)</label>
                                    <textarea wire:model="message" class="form-control" rows="3" placeholder="Tulis doa atau pesan dari donatur..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bukti Transfer -->
                    <div class="col-lg-5">
                        <div class="bg-light p-4 rounded-4 h-100">
                            <h6 class="fw-bold mb-4 border-bottom pb-2">Bukti Transfer</h6>

                            <div class="mb-4">
                                <label
                                    class="form-label small fw-bold d-flex justify-content-between align-items-center">
                                    <span>Upload Lampiran (Multiple)</span>
                                    <span class="badge bg-white text-muted border fw-normal">Max 5MB/file</span>
                                </label>

                                <input type="file" wire:model="payment_proofs" multiple class="form-control "
                                    accept="image/*">

                                <div wire:loading wire:target="payment_proofs" class="text-primary small mt-2">
                                    <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                                    Mengunggah file...
                                </div>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($payment_proofs): ?>
                                    <div class="mt-4 vstack gap-3">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $payment_proofs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $proof): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                            <div class="card border-0  overflow-hidden">
                                                <div class="card-body p-3">
                                                    <div class="d-flex gap-3">
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($proof->isPreviewable()): ?>
                                                            <img src="<?php echo e($proof->temporaryUrl()); ?>"
                                                                class="rounded object-fit-cover"
                                                                style="width: 70px; height: 70px;">
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        <div class="flex-grow-1">
                                                            <div class="row g-2 mb-2">
                                                                <div class="col-12">
                                                                    <div class="input-group input-group-sm">
                                                                        <span
                                                                            class="input-group-text bg-white">Rp</span>
                                                                        <input type="number"
                                                                            wire:model="claimed_amounts.<?php echo e($index); ?>"
                                                                            class="form-control"
                                                                            placeholder="Nominal di bukti">
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <input type="text"
                                                                        wire:model="proof_notes.<?php echo e($index); ?>"
                                                                        class="form-control form-control-sm"
                                                                        placeholder="Catatan (misal: Bank Pengirim)">
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="d-flex justify-content-between align-items-center">
                                                                <span class="x-small text-muted text-truncate"
                                                                    style="max-width: 150px;">
                                                                    <?php echo e($proof->getClientOriginalName()); ?>

                                                                </span>
                                                                <button type="button"
                                                                    wire:click="$set('payment_proofs.<?php echo e($index); ?>', null)"
                                                                    class="btn btn-link text-danger btn-sm p-0 text-decoration-none x-small">
                                                                    <i class="bi bi-trash"></i> Hapus
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['payment_proofs.*'];
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

                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                    <a href="<?php echo e(route('admin.donasi')); ?>" wire:navigate class="btn btn-light px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-5 fw-bold" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="store">
                            <i class="bi bi-check-circle me-2"></i> Simpan Donasi
                        </span>
                        <span wire:loading wire:target="store">
                            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/e8683235.blade.php ENDPATH**/ ?>