<?php
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use App\Models\Campaign;
use Illuminate\Support\Facades\Auth;
?>

<div class="bg-white min-vh-100">
    <?php if (isset($component)) { $__componentOriginal87a36371cd3c2919bc8ca9c86f23c76e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal87a36371cd3c2919bc8ca9c86f23c76e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.navbar-secondary','data' => ['route' => ''.e(route('donation.amount', ['campaign' => $campaign->slug, 'amount' => $amount])).'','title' => 'Isi Data Diri']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.navbar-secondary'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => ''.e(route('donation.amount', ['campaign' => $campaign->slug, 'amount' => $amount])).'','title' => 'Isi Data Diri']); ?>
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
            
            <div class="card border-0 bg-light mb-4">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <span class="text-muted fw-medium">Nominal Donasi</span>
                    <span class="fw-bold text-primary fs-5">Rp <?php echo e(number_format($amount, 0, ',', '.')); ?></span>
                </div>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->guest()): ?>
                <div class="alert alert-primary border-0 mb-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-circle fs-4 me-3"></i>
                        <div class="lh-sm">
                            <div class="fw-bold">Sudah punya akun?</div>
                            <small>Login untuk kemudahan donasi.</small>
                        </div>
                    </div>
                    <button wire:click="redirectToLogin"
                        class="btn btn-sm btn-primary rounded-pill px-3 fw-bold">Login</button>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <div class="mb-4">
                <h6 class="fw-bold mb-3">Informasi Donatur</h6>

                <div class="mb-3">
                    <label class="form-label small text-muted fw-bold">Nama Lengkap</label>
                    <input type="text" wire:model="name"
                        class="form-control form-control-lg fs-6 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="Masukkan nama anda">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
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
                    <label class="form-label small text-muted fw-bold">Email</label>
                    <input type="email" wire:model="email"
                        class="form-control form-control-lg fs-6 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="Contoh: nama@email.com">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
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
                    <label class="form-label small text-muted fw-bold">Nomor WhatsApp (Opsional)</label>
                    <input type="tel" wire:model="phone"
                        class="form-control form-control-lg fs-6 <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="Contoh: 0812...">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['phone'];
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

                <div class="form-check form-switch p-0 d-flex justify-content-between align-items-center mb-0 bg-light px-3 py-2 cursor-pointer"
                    style="min-height: 50px;">
                    <label class="form-check-label ms-0 fw-bold text-dark cursor-pointer"
                        for="anonymousSwitch">Sembunyikan
                        Nama (Hamba Allah)</label>
                    <input class="form-check-input ms-0 fs-5 cursor-pointer" type="checkbox" role="switch"
                        id="anonymousSwitch" wire:model="is_anonymous">
                </div>
            </div>

            
            <div class="mb-5">
                <h6 class="fw-bold mb-3">Doa & Dukungan (Opsional)</h6>
                <textarea wire:model="message" class="form-control form-control-lg fs-6 <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    rows="4" placeholder="Tuliskan doa atau pesan dukungan Anda (Akan tampil di halaman donasi)"></textarea>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="form-text text-end" x-data="{ count: 0 }" x-init="count = $wire.message ? $wire.message.length : 0"
                    x-on:input="count = $event.target.value.length">
                    <span x-text="count"></span>/500
                </div>
            </div>
        </div>
    </section>

    
    <div class="detail-cta" style="height: auto; z-index: 100;">
        <div class="w-100">
            <button wire:click="submit" class="detail-cta__button w-100 border-0 rounded-pill">
                Pilih Metode Pembayaran <i class="bi bi-chevron-right ms-2"></i>
            </button>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/59954199.blade.php ENDPATH**/ ?>