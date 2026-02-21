<?php

use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.admin-auth')] class extends Component {
    #[Rule('required|email')]
    public $email = '';

    #[Rule('required')]
    public $password = '';

    public function mount()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            $this->redirect(route('admin.dashboard'), navigate: true);
            return;
        }
    }

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

            if (Auth::user()->role !== 'admin') {
                Auth::logout();
                $this->addError('email', 'Anda bukan admin.');
                return;
            }

            $intended = session()->pull('url.intended', route('admin.dashboard'));
            $this->redirect($intended, navigate: true);
            return;
        }

        $this->addError('email', 'Email atau password salah.');
    }
};
?>

<div class="d-flex min-vh-100 font-jakarta bg-white">
    <!-- Left Side - Form -->
    <div class="col-12 col-lg-5 d-flex align-items-center justify-content-center p-5">
        <div class="w-100" style="max-width: 420px;">
            <div class="mb-5 text-center text-lg-start">
                <img src="{{ \App\Models\Setting::get('logo') ? asset('storage/' . \App\Models\Setting::get('logo')) : asset('assets/images/logo.png') }}"
                    alt="{{ \App\Models\Setting::get('website_name', 'Inisiatif Kebaikan') }}" class="mb-4"
                    style="height: 48px;">
                <h2 class="fw-bold mb-2 text-dark">Selamat Datang, Admin!</h2>
                <p class="text-muted">Silakan masuk untuk mengelola kebaikan.</p>
            </div>

            <form wire:submit="login">
                <div class="mb-4">
                    <label class="form-label fw-bold small text-muted text-uppercase ls-1">Email</label>
                    <div class="input-group bg-light rounded-3 overflow-hidden border-0">
                        <span class="input-group-text bg-transparent border-0 pe-1 text-muted">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" wire:model="email"
                            class="form-control py-3 bg-transparent border-0 shadow-none @error('email') is-invalid @enderror"
                            placeholder="admin@inisiatif.com">
                    </div>
                    @error('email')
                        <div class="text-danger extra-small mt-1 px-1 fw-bold">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
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

                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold mb-4 shadow-md rounded-pill">
                    <span wire:loading.remove>Masuk Dashboard</span>
                    <span wire:loading>Memproses...</span>
                </button>

                <div class="text-center">
                    <a href="{{ route('home') }}"
                        class="text-decoration-none small text-muted fw-bold opacity-75 hover-opacity-100">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Website
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Side - Image Background -->
    <div class="col-lg-7 d-none d-lg-block position-relative bg-light">
        <div class="position-absolute top-0 start-0 w-100 h-100"
            style="background-image: url('https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2670&auto=format&fit=crop');
                    background-size: cover;
                    background-position: center;">
            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50"></div>
        </div>

        <div class="position-relative h-100 d-flex flex-column justify-content-end p-5 text-white">
            <div class="mb-5">
                <h1 class="display-4 fw-bold mb-3">Login Admin</h1>
                <p class="lead text-white-50 mb-0">Kelola donasi, penyaluran, dan laporan program kebaikan anda dalam
                    satu dashboard terintegrasi.</p>
            </div>
            <div
                class="d-flex justify-content-between align-items-end small text-white-50 border-top border-white border-opacity-25 pt-4">
                <div>&copy; {{ date('Y') }} {{ \App\Models\Setting::get('website_name', 'Inisiatif Kebaikan') }}
                </div>
                <div>Panel Administrator v1.0</div>
            </div>
        </div>
    </div>
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

    .form-control:focus {
        background-color: transparent;
    }

    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 30px #f8f9fa inset !important;
    }
</style>
