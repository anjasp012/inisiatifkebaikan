<?php

use Livewire\Attributes\Rule;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    #[Rule('required|email')]
    public $email = '';

    #[Rule('required')]
    public $password = '';

    public function messages()
    {
        return [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ];
    }

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();

            if (!Auth::user()->user_verified_at) {
                session()->put('otp_type', 'otp-login');
                $this->redirect(route('verification'), navigate: true);
                return;
            }

            $this->redirect(session()->pull('url.intended', route('home')), navigate: true);
        }

        $this->addError('email', 'Email atau password salah.');
    }
};
?>

<div class="bg-white min-vh-100 d-flex flex-column font-jakarta">
    <section class="py-5 flex-grow-1 d-flex align-items-center">
        <div class="container-fluid px-4">
            <div class="text-center mb-5">
                <div class="mb-4 d-inline-block">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="h-auto object-fit-contain"
                        style="max-height: 48px;">
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
                            class="form-control py-3 bg-transparent border-0 shadow-none @error('email') is-invalid @enderror"
                            placeholder="user@email.com">
                    </div>
                    @error('email')
                        <div class="text-danger extra-small mt-1 px-1 fw-bold">{{ $message }}</div>
                    @enderror
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
                            class="form-control py-3 bg-transparent border-0 shadow-none @error('password') is-invalid @enderror"
                            placeholder="••••••••">
                    </div>
                    @error('password')
                        <div class="text-danger extra-small mt-1 px-1 fw-bold">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold mb-4 shadow-auth rounded-pill">
                    <span wire:loading.remove>Masuk Sekarang</span>
                    <span wire:loading>Memproses...</span>
                </button>

            </form>

            <div class="text-center mt-5">
                <p class="text-muted small">
                    Belum punya akun? <a href="{{ route('register') }}" wire:navigate
                        class="fw-bold text-primary text-decoration-none border-bottom border-primary border-2">Daftar
                        disini</a>
                </p>
                <a href="{{ route('home') }}" wire:navigate
                    class="text-muted text-decoration-none extra-small fw-bold mt-3 d-inline-block opacity-75">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </section>
</div>
