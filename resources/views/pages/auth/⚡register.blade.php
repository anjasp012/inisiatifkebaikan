<?php

use Livewire\Attributes\Rule;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

new class extends Component {
    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('required|email|unique:users,email')]
    public $email = '';

    #[Rule('required|numeric|unique:users,phone')]
    public $phone = '';

    #[Rule('required|min:8|confirmed')]
    public $password = '';

    public $password_confirmation = '';

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => Hash::make($this->password),
            'role' => 'donatur',
        ]);

        Auth::login($user);

        session()->put('otp_type', 'otp-register');
        $this->redirect(route('verification'), navigate: true);
    }
};
?>

<div class="bg-white min-vh-100 d-flex flex-column font-jakarta text-dark">
    <section class="py-5 flex-grow-1 d-flex align-items-center">
        <div class="container-fluid px-4">
            <div class="text-center mb-5">
                <div class="mb-4 d-inline-block">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo"
                        style="height: 48px; object-fit: contain;">
                </div>
                <h3 class="fw-bold mb-1">Daftar Akun</h3>
                <p class="text-muted small">Mulai langkah kebaikanmu hari ini.</p>
            </div>

            <form wire:submit="register" class="mt-4">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted text-uppercase ls-1">Nama Lengkap</label>
                    <div class="input-group bg-light rounded-3 overflow-hidden border-0">
                        <span class="input-group-text bg-transparent border-0 pe-1 text-muted">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text" wire:model="name"
                            class="form-control py-3 bg-transparent border-0 shadow-none @error('name') is-invalid @enderror"
                            placeholder="Contoh: Ahmad Fauzi">
                    </div>
                    @error('name')
                        <div class="text-danger extra-small mt-1 px-1 fw-bold">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
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

                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted text-uppercase ls-1">Nomor WhatsApp</label>
                    <div class="input-group bg-light rounded-3 overflow-hidden border-0">
                        <span class="input-group-text bg-transparent border-0 pe-1 text-muted">
                            <i class="bi bi-whatsapp"></i>
                        </span>
                        <input type="tel" wire:model="phone"
                            class="form-control py-3 bg-transparent border-0 shadow-none @error('phone') is-invalid @enderror"
                            placeholder="08123456789">
                    </div>
                    @error('phone')
                        <div class="text-danger extra-small mt-1 px-1 fw-bold">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted text-uppercase ls-1">Password</label>
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

                <div class="mb-4">
                    <label class="form-label fw-bold small text-muted text-uppercase ls-1">Konfirmasi Password</label>
                    <div class="input-group bg-light rounded-3 overflow-hidden border-0">
                        <span class="input-group-text bg-transparent border-0 pe-1 text-muted">
                            <i class="bi bi-shield-lock"></i>
                        </span>
                        <input type="password" wire:model="password_confirmation"
                            class="form-control py-3 bg-transparent border-0 shadow-none" placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold mb-4 shadow-md rounded-pill">
                    <span wire:loading.remove>Daftar Sekarang</span>
                    <span wire:loading>Memproses...</span>
                </button>

            </form>

            <div class="text-center mt-4">
                <p class="text-muted small">
                    Sudah punya akun? <a href="{{ route('login') }}" wire:navigate
                        class="fw-bold text-primary text-decoration-none border-bottom border-primary border-2 pb-0.5">Masuk
                        disini</a>
                </p>
                <a href="{{ route('home') }}" wire:navigate
                    class="text-muted text-decoration-none extra-small fw-bold mt-3 d-inline-block opacity-75 hover-opacity-100">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </section>
</div>

<style>
    .font-jakarta {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .ls-1 {
        letter-spacing: 0.1em;
    }

    .extra-small {
        font-size: 11px;
    }

    .shadow-md {
        box-shadow: 0 10px 15px -3px rgba(220, 82, 7, 0.2);
    }
</style>
