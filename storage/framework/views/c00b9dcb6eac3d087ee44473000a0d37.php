<?php
use Livewire\Attributes\Rule;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
?>

<div class="bg-white min-vh-100 d-flex flex-column font-jakarta">
    <section class="py-5 flex-grow-1 d-flex align-items-center">
        <div class="container-fluid px-4">
            <div class="text-center mb-5">
                <div class="mb-4 d-inline-block">
                    <img src="<?php echo e(asset('assets/images/logo.png')); ?>" alt="Logo"
                        style="height: 48px; object-fit: contain;">
                </div>
                <h3 class="fw-bold text-dark mb-1">Selamat Datang!</h3>
                <p class="text-muted small">Masuk untuk melanjutkan kebaikanmu.</p>
            </div>

            <form wire:submit="login" class="mt-4">
                <div class="mb-4">
                    <label class="form-label fw-bold small text-muted text-uppercase ls-1">Email</label>
                    <div class="input-group bg-light rounded-3 overflow-hidden border-0">
                        <span class="input-group-text bg-transparent border-0 pe-1 text-muted">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" wire:model="email"
                            class="form-control py-3 bg-transparent border-0 shadow-none <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="user@email.com">
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger extra-small mt-1 px-1 fw-bold"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-bold small text-muted text-uppercase ls-1 mb-0">Password</label>
                        <a href="#" class="text-decoration-none extra-small fw-bold text-primary">Lupa
                            Password?</a>
                    </div>
                    <div class="input-group bg-light rounded-3 overflow-hidden border-0">
                        <span class="input-group-text bg-transparent border-0 pe-1 text-muted">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" wire:model="password"
                            class="form-control py-3 bg-transparent border-0 shadow-none <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="••••••••">
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger extra-small mt-1 px-1 fw-bold"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold mb-4 shadow-md rounded-pill">
                    <span wire:loading.remove>Masuk Sekarang</span>
                    <span wire:loading>Memproses...</span>
                </button>

                <div class="position-relative text-center mb-4">
                    <hr class="opacity-10">
                    <span
                        class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted extra-small fw-bold text-uppercase">Atau</span>
                </div>

                <button type="button"
                    class="btn btn-outline-dark w-100 py-3 fw-bold border border-light-subtle rounded-pill shadow-sm d-flex align-items-center justify-content-center gap-2">
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google"
                        style="height: 18px;">
                    <span class="small font-jakarta">Masuk dengan Google</span>
                </button>
            </form>

            <div class="text-center mt-5">
                <p class="text-muted small">
                    Belum punya akun? <a href="<?php echo e(route('register')); ?>" wire:navigate
                        class="fw-bold text-primary text-decoration-none border-bottom border-primary border-2 pb-0.5">Daftar
                        disini</a>
                </p>
            </div>
        </div>
    </section>
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/49254672.blade.php ENDPATH**/ ?>