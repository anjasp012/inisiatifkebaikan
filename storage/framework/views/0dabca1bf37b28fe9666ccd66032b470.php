<?php
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use App\Models\Campaign;
use App\Models\Bank;
use App\Models\Donation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
?>

<div class="bg-white min-vh-100 font-jakarta"
    @donation-created.window="
        (function() {
            try {
                let history = JSON.parse(localStorage.getItem('donation_history') || '[]');
                let currentId = $event.detail.transaction_id;
                if (currentId && !history.includes(currentId)) {
                    history.unshift(currentId);
                    localStorage.setItem('donation_history', JSON.stringify(history.slice(0, 50)));
                    console.log('Donation tracked (Payment):', currentId);
                }
            } catch (e) {}
        })();
    ">
    <?php if (isset($component)) { $__componentOriginal87a36371cd3c2919bc8ca9c86f23c76e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal87a36371cd3c2919bc8ca9c86f23c76e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.navbar-secondary','data' => ['route' => ''.e(route('donation.data', ['campaign' => $campaign->slug, 'amount' => $donationData['amount'] ?? 0])).'','title' => 'Pembayaran']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.navbar-secondary'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => ''.e(route('donation.data', ['campaign' => $campaign->slug, 'amount' => $donationData['amount'] ?? 0])).'','title' => 'Pembayaran']); ?>
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
        <div class="container-fluid px-3" style="max-width: 480px;">

            <div class="card border-0 bg-lighter rounded-4 mb-4 border border-light">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted extra-small fw-bold uppercase ls-sm d-block">Total Donasi</span>
                        <div class="h3 fw-bold text-dark mb-0">Rp
                            <?php echo e(number_format($donationData['amount'] ?? 0, 0, ',', '.')); ?></div>
                    </div>
                    <div class="bg-white p-2 rounded-3 border border-light shadow-micro text-primary">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('error')): ?>
                <div
                    class="alert alert-danger border-0 rounded-3 mb-3 extra-small fw-bold py-2 d-flex align-items-center">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="space-y-3" x-data="{ activeGroup: 'ewallet' }">

                
                <div class="card border rounded-4 shadow-micro overflow-hidden border-light">
                    <button class="w-100 border-0 bg-white p-3 d-flex align-items-center justify-content-between"
                        @click="activeGroup = activeGroup === 'ewallet' ? '' : 'ewallet'">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-phone text-muted fs-5"></i>
                            <div class="text-start">
                                <span class="d-block fw-bold text-dark small">E-Wallet & QRIS</span>
                                <span class="text-muted extra-small">Otomatis Terverifikasi</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-down extra-small transition-transform"
                            :class="activeGroup === 'ewallet' ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="activeGroup === 'ewallet'" x-collapse>
                        <div class="list-group list-group-flush border-top border-light">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $banks->whereIn('method', ['ewallet', 'qris']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <button wire:click="processPayment(<?php echo e($bank->id); ?>)"
                                    class="list-group-item d-flex align-items-center justify-content-between p-3 border-0">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bank-logo-mini">
                                            <img src="<?php echo e($bank->logo_url); ?>" class="img-fluid"
                                                alt="<?php echo e($bank->bank_name); ?>">
                                        </div>
                                        <span class="fw-bold text-dark small"><?php echo e($bank->bank_name); ?></span>
                                    </div>
                                    <i class="bi bi-chevron-right text-muted extra-small"></i>
                                </button>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="card border rounded-4 shadow-micro overflow-hidden border-light">
                    <button class="w-100 border-0 bg-white p-3 d-flex align-items-center justify-content-between"
                        @click="activeGroup = activeGroup === 'va' ? '' : 'va'">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-credit-card text-muted fs-5"></i>
                            <div class="text-start">
                                <span class="d-block fw-bold text-dark small">Virtual Account</span>
                                <span class="text-muted extra-small">Transfer Bank Otomatis</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-down extra-small transition-transform"
                            :class="activeGroup === 'va' ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="activeGroup === 'va'" x-collapse>
                        <div class="list-group list-group-flush border-top border-light">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $banks->where('method', 'va'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <button wire:click="processPayment(<?php echo e($bank->id); ?>)"
                                    class="list-group-item d-flex align-items-center justify-content-between p-3 border-0">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bank-logo-mini">
                                            <img src="<?php echo e($bank->logo_url); ?>" class="img-fluid"
                                                alt="<?php echo e($bank->bank_name); ?>">
                                        </div>
                                        <span class="fw-bold text-dark small"><?php echo e($bank->bank_name); ?></span>
                                    </div>
                                    <i class="bi bi-chevron-right text-muted extra-small"></i>
                                </button>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="card border rounded-4 shadow-micro overflow-hidden border-light">
                    <button class="w-100 border-0 bg-white p-3 d-flex align-items-center justify-content-between"
                        @click="activeGroup = activeGroup === 'manual' ? '' : 'manual'">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-bank text-muted fs-5"></i>
                            <div class="text-start">
                                <span class="d-block fw-bold text-dark small">Transfer Manual</span>
                                <span class="text-muted extra-small">Konfirmasi via Upload Bukti</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-down extra-small transition-transform"
                            :class="activeGroup === 'manual' ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="activeGroup === 'manual'" x-collapse>
                        <div class="list-group list-group-flush border-top border-light">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $banks->where('method', 'manual'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <button wire:click="processPayment(<?php echo e($bank->id); ?>)"
                                    class="list-group-item d-flex align-items-center justify-content-between p-3 border-0">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bank-logo-mini">
                                            <img src="<?php echo e($bank->logo_url); ?>" class="img-fluid"
                                                alt="<?php echo e($bank->bank_name); ?>">
                                        </div>
                                        <div>
                                            <span
                                                class="text-start d-block fw-bold text-dark small"><?php echo e($bank->bank_name); ?></span>
                                            <div class="text-muted" style="font-size: 9px;">
                                                <span class="fw-bold"><?php echo e($bank->account_number); ?></span>
                                                <span class="px-1">•</span>
                                                <span>a.n <?php echo e($bank->account_name); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <i class="bi bi-chevron-right text-muted extra-small"></i>
                                </button>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($banks->where('method', 'retail')->count() > 0): ?>
                    <div class="card border rounded-4 shadow-micro overflow-hidden border-light">
                        <button class="w-100 border-0 bg-white p-3 d-flex align-items-center justify-content-between"
                            @click="activeGroup = activeGroup === 'retail' ? '' : 'retail'">
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-shop text-muted fs-5"></i>
                                <div class="text-start">
                                    <span class="d-block fw-bold text-dark small">Gerai Retail</span>
                                    <span class="text-muted extra-small">Alfamart / Indomaret</span>
                                </div>
                            </div>
                            <i class="bi bi-chevron-down extra-small transition-transform"
                                :class="activeGroup === 'retail' ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="activeGroup === 'retail'" x-collapse>
                            <div class="list-group list-group-flush border-top border-light">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $banks->where('method', 'retail'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <button wire:click="processPayment(<?php echo e($bank->id); ?>)"
                                        class="list-group-item d-flex align-items-center justify-content-between p-3 border-0">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bank-logo-mini">
                                                <img src="<?php echo e($bank->logo_url); ?>" class="img-fluid"
                                                    alt="<?php echo e($bank->bank_name); ?>">
                                            </div>
                                            <span class="fw-bold text-dark small"><?php echo e($bank->bank_name); ?></span>
                                        </div>
                                        <i class="bi bi-chevron-right text-muted extra-small"></i>
                                    </button>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="mt-5 pb-5 text-center opacity-50">
                <i class="bi bi-shield-check text-success extra-small"></i>
                <span class="extra-small fw-bold text-muted ps-1 uppercase ls-sm">Keamanan Transaksi Terjamin</span>
            </div>
        </div>
    </section>
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/50ccfbcd.blade.php ENDPATH**/ ?>