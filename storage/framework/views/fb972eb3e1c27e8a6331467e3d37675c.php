<?php
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Donation;
use App\Models\PaymentProof;
use Illuminate\Support\Str;
?>

<div class="bg-lighter min-vh-100 font-jakarta pb-5"
    <?php if($donation->status === 'pending'): ?> wire:poll.10s="refreshStatus" <?php endif; ?> x-data="{
        expiry: <?php echo e($donation->expired_at ? $donation->expired_at->timestamp : 0); ?>,
        timer: { h: '00', m: '00', s: '00' },
        activeGroup: 0,
        init() {
            if (this.expiry === 0) return;
            this.update();
            setInterval(() => this.update(), 1000);
        },
        update() {
            const now = Math.floor(Date.now() / 1000);
            const diff = this.expiry - now;
            if (diff <= 0) {
                this.timer = { h: '00', m: '00', s: '00' };
                return;
            }
            const h = Math.floor(diff / 3600);
            const m = Math.floor((diff % 3600) / 60);
            const s = Math.floor(diff % 60);
            this.timer.h = h.toString().padStart(2, '0');
            this.timer.m = m.toString().padStart(2, '0');
            this.timer.s = s.toString().padStart(2, '0');
        },
        copyText(text) {
            navigator.clipboard.writeText(text).then(() => alert('Tersalin!'));
        }
    }">

    <?php if (isset($component)) { $__componentOriginal87a36371cd3c2919bc8ca9c86f23c76e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal87a36371cd3c2919bc8ca9c86f23c76e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.navbar-secondary','data' => ['title' => 'Instruksi Pembayaran','route' => ''.e(route('home')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.navbar-secondary'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Instruksi Pembayaran','route' => ''.e(route('home')).'']); ?>
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

    <div class="container-fluid px-3 pt-3" style="max-width: 480px;">

        
        <div
            class="d-flex align-items-center justify-content-between mb-4 bg-white p-2 rounded-2 border border-light shadow-micro">
            <div class="d-flex align-items-center gap-2 ps-1">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($donation->status === 'success'): ?>
                    <div class="status-dot bg-success"></div>
                    <span class="text-success fw-bold extra-small text-uppercase">Berhasil</span>
                <?php elseif($donation->status === 'failed'): ?>
                    <div class="status-dot bg-danger"></div>
                    <span class="text-danger fw-bold extra-small text-uppercase">Gagal</span>
                <?php else: ?>
                    <div class="status-dot bg-warning pulsate"></div>
                    <span class="text-dark fw-bold extra-small text-uppercase">Menunggu</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($donation->status === 'pending' && $donation->expired_at): ?>
                <div class="d-flex align-items-center gap-2 pe-1">
                    <i class="bi bi-clock-history text-muted extra-small"></i>
                    <span class="font-monospace fw-bold text-dark extra-small"
                        x-text="`${timer.h}:${timer.m}:${timer.s}`">00:00:00</span>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div class="card border-0 rounded-4 mb-4 border border-light overflow-hidden">
            <div class="card-body p-0 text-center">
                <span class="text-muted extra-small fw-bold text-uppercase ls-1 mb-1 d-block">Jumlah Donasi</span>
                <div class="h2 fw-bold text-dark tracking-tight mb-4">Rp
                    <?php echo e(number_format($donation->amount, 0, ',', '.')); ?></div>

                <div class="p-3 bg-lighter rounded-3 border border-light text-start">
                    <?php
                        $isEwallet =
                            ($donation->bank && $donation->bank->method === 'ewallet') ||
                            Str::contains(strtolower($donation->payment_channel), [
                                'gopay',
                                'shopeepay',
                                'dana',
                                'linkaja',
                                'ovo',
                            ]);
                    ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Str::startsWith($donation->payment_code, 'http') || ($isEwallet && $donation->payment_url)): ?>
                        <div class="text-center">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isEwallet && $donation->payment_url): ?>
                                <a href="<?php echo e($donation->payment_url); ?>"
                                    class="btn btn-primary w-100 rounded-pill py-2 fw-bold mb-3 shadow-md d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-phone-fill"></i> BAYAR SEKARANG
                                </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Str::startsWith($donation->payment_code, 'http')): ?>
                                <div class="bg-white p-2 rounded-4 border d-inline-block shadow-micro">
                                    <img src="<?php echo e($donation->payment_code); ?>"
                                        style="width: 140px; height: 140px; object-fit: contain;">
                                </div>
                                <p class="text-muted extra-small fw-bold mt-3 mb-0">Scan / Screenshot QR Code</p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="va-section">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span
                                    class="text-muted extra-small fw-bold text-uppercase"><?php echo e($donation->payment_method === 'manual' ? 'Rekening Tujuan' : 'Nomor VA'); ?></span>
                                <div class="d-flex align-items-center gap-2 opacity-50">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($donation->bank && $donation->bank->logo): ?>
                                        <img src="<?php echo e($donation->bank->logo_url); ?>"
                                            style="height: 10px; max-width: 25px; object-fit: contain; filter: grayscale(1);">
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <span
                                        class="extra-small fw-bold"><?php echo e($donation->bank->bank_name ?? $donation->payment_channel); ?></span>
                                </div>
                            </div>
                            <div
                                class="d-flex justify-content-between align-items-center bg-white p-3 rounded-3 border border-light">
                                <span
                                    class="h4 fw-bold text-dark font-monospace mb-0 tracking-widest"><?php echo e($donation->payment_code); ?></span>
                                <button class="btn btn-primary btn-sm rounded-pill px-3 py-1 fw-bold extra-small"
                                    @click="copyText('<?php echo e($donation->payment_code); ?>')">SALIN</button>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($donation->payment_method === 'manual'): ?>
                                <div class="mt-2 text-dark extra-small border-top border-light pt-2">
                                    A/N <span
                                        class="fw-bold"><?php echo e($donation->bank->account_name ?? 'Inisiatif Kebaikan'); ?></span>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="mt-3 text-muted extra-small fw-medium opacity-25">ID#<?php echo e($donation->transaction_id); ?></div>
            </div>
        </div>

        
        <?php $instructionGroups = $this->getSteps(); ?>
        <div class="mb-4">
            <h6 class="fw-bold extra-small text-uppercase ls-1 text-muted mb-3 px-1">Petunjuk Pembayaran</h6>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($instructionGroups) > 1): ?>
                <div class="d-flex gap-2 mb-3 overflow-x-auto no-scrollbar pb-1 px-1">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $instructionGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <button @click="activeGroup = <?php echo e($index); ?>" class="tab-chip"
                            :class="activeGroup === <?php echo e($index); ?> ? 'active' : ''">
                            <?php echo e($group['title'] ?? ($group['name'] ?? 'Instruksi')); ?>

                        </button>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="card border-0 rounded-4 shadow-micro border border-light">
                <div class="card-body p-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $instructionGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div x-show="activeGroup === <?php echo e($index); ?>" x-transition>
                            <div class="stepper-minimal">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $group['steps']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sIndex => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <div class="d-flex gap-3 <?php echo e(!$loop->last ? 'mb-3' : ''); ?>">
                                        <div class="step-bullet"><?php echo e($sIndex + 1); ?></div>
                                        <div class="extra-small text-dark fw-medium lh-base pt-0.5">
                                            <?php echo is_array($step) ? $step['content'] ?? '' : $step; ?>

                                        </div>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($donation->payment_method === 'manual'): ?>
            <div class="card border-0 rounded-4 shadow-micro border border-light mb-4 text-center">
                <div class="card-body p-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($donation->paymentProofs->isNotEmpty()): ?>
                        <div class="text-success extra-small fw-bold mb-3">
                            <i class="bi bi-shield-check-fill me-1"></i> Bukti Berhasil Terkirim
                        </div>
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $donation->paymentProofs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proof): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <a href="<?php echo e($proof->file_url); ?>" target="_blank"
                                    class="position-relative d-inline-block rounded-3 overflow-hidden border border-light shadow-micro"
                                    style="width: 100px; height: 100px;">
                                    <img src="<?php echo e($proof->file_url); ?>" class="w-100 h-100 object-fit-cover"
                                        alt="Bukti Transfer">
                                </a>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$uploadSuccess): ?>
                            <hr class="my-3 opacity-10">
                            <label class="cursor-pointer">
                                <span class="extra-small fw-bold text-primary text-uppercase">Tambah Bukti Lagi</span>
                                <input type="file" wire:model="proof" class="d-none">
                            </label>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php else: ?>
                        <label class="w-100 cursor-pointer">
                            <div class="upload-minimal py-3 border border-dashed rounded-3">
                                <div wire:loading.remove wire:target="proof">
                                    <i class="bi bi-camera text-primary fs-5"></i>
                                    <div class="extra-small fw-bold text-dark mt-1 text-uppercase">Upload Bukti Transfer
                                    </div>
                                </div>
                                <div wire:loading wire:target="proof"
                                    class="spinner-border spinner-border-sm text-primary"></div>
                            </div>
                            <input type="file" wire:model="proof" class="d-none">
                        </label>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="d-grid gap-2 mt-5 pb-5 px-1">
            <a href="<?php echo e(route('home')); ?>"
                class="btn btn-dark rounded-pill py-2.5 fw-bold extra-small text-uppercase tracking-widest shadow-sm">
                Selesai & Beranda
            </a>
            <a href="https://wa.me/<?php echo e(\App\Models\Setting::get('whatsapp_number')); ?>"
                class="btn btn-link py-1 text-decoration-none text-muted extra-small fw-bold opacity-50">
                Butuh Bantuan? Hubungi CS
            </a>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/41eb1b94.blade.php ENDPATH**/ ?>