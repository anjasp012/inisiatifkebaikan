<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Facades\View;

new #[Layout('layouts.app')] class extends Component {
    public function mount()
    {
        if (!Auth::check()) {
            $this->redirect(route('login'), navigate: true);
            return;
        }

        $seoData = new SEOData(title: 'Akun Saya | Inisiatif Kebaikan', robots: 'noindex, nofollow');

        View::share('seoData', $seoData);
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        $this->redirect(route('home'), navigate: true);
    }
};
?>

<div class="bg-gray-50 min-vh-100 pb-5">
    <x-app.navbar-secondary title="Akun Saya" />

    <div class="container-fluid py-4">
        <!-- Profile Header -->
        <div class="bg-white rounded-3 p-4 mb-3 d-flex align-items-center gap-3 shadow-sm border-0">
            <div class="avatar-wrapper">
                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}"
                    class="rounded-circle object-fit-cover" style="width: 64px; height: 64px;">
            </div>
            <div>
                <h5 class="fw-bold mb-1">{{ Auth::user()->name }}</h5>
                <p class="text-muted small mb-0">{{ Auth::user()->email }}</p>
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 mt-2"
                    style="font-size: 10px;">
                    {{ Auth::user()->role === 'admin' ? 'Administrator' : (Auth::user()->role === 'fundraiser' ? 'Fundraiser' : 'Donatur') }}
                </span>
            </div>
        </div>

        <!-- Menu List -->
        <div class="bg-white rounded-3 overflow-hidden shadow-sm border-0 mb-4">
            <div class="list-group list-group-flush py-1">
                <a href="{{ route('account.profile') }}"
                    class="list-group-item list-group-item-action p-2 d-flex align-items-center justify-content-between border-bottom-0 border-light"
                    wire:navigate>
                    <div class="d-flex align-items-center gap-2"> <!-- Reduced gap -->
                        <div class="bg-primary bg-opacity-10 text-primary px-1 rounded-2">
                            <!-- Reduced padding and rounded -->
                            <i class="bi bi-person fs-6"></i> <!-- Reduced icon size -->
                        </div>
                        <span class="fw-medium text-dark small">Edit Profil</span> <!-- Small text -->
                    </div>
                    <i class="bi bi-chevron-right text-muted" style="font-size: 10px;"></i>
                </a>
                <a href="{{ route('donasi-saya') }}"
                    class="list-group-item list-group-item-action p-2 d-flex align-items-center justify-content-between border-bottom-0 border-light"
                    wire:navigate>
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-info bg-opacity-10 text-info px-1 rounded-2">
                            <i class="bi bi-clock-history fs-6"></i>
                        </div>
                        <span class="fw-medium text-dark small">Riwayat Donasi</span>
                    </div>
                    <i class="bi bi-chevron-right text-muted" style="font-size: 10px;"></i>
                </a>
                <a href="{{ route('account.password') }}"
                    class="list-group-item list-group-item-action p-2 d-flex align-items-center justify-content-between border-bottom-0 border-light"
                    wire:navigate>
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-warning bg-opacity-10 text-warning px-1 rounded-2">
                            <i class="bi bi-shield-lock fs-6"></i>
                        </div>
                        <span class="fw-medium text-dark small">Ganti Password</span>
                    </div>
                    <i class="bi bi-chevron-right text-muted" style="font-size: 10px;"></i>
                </a>

                <div class="border-top my-1 mx-2"></div>

                <!-- Footer Links as Menu Items -->
                <a href="#"
                    class="list-group-item list-group-item-action p-2 d-flex align-items-center justify-content-between border-bottom-0 border-light">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-secondary bg-opacity-10 text-secondary px-1 rounded-2">
                            <i class="bi bi-info-circle fs-6"></i>
                        </div>
                        <span class="fw-medium text-dark small">Tentang Kami</span>
                    </div>
                    <i class="bi bi-chevron-right text-muted" style="font-size: 10px;"></i>
                </a>
                <a href="#"
                    class="list-group-item list-group-item-action p-2 d-flex align-items-center justify-content-between border-bottom-0 border-light">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-secondary bg-opacity-10 text-secondary px-1 rounded-2">
                            <i class="bi bi-shield-check fs-6"></i>
                        </div>
                        <span class="fw-medium text-dark small">Kebijakan Privasi</span>
                    </div>
                    <i class="bi bi-chevron-right text-muted" style="font-size: 10px;"></i>
                </a>
                <a href="#"
                    class="list-group-item list-group-item-action p-2 d-flex align-items-center justify-content-between border-bottom-0 border-light">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-secondary bg-opacity-10 text-secondary px-1 rounded-2">
                            <i class="bi bi-file-text fs-6"></i>
                        </div>
                        <span class="fw-medium text-dark small">Syarat & Ketentuan</span>
                    </div>
                    <i class="bi bi-chevron-right text-muted" style="font-size: 10px;"></i>
                </a>

                <div class="border-top my-1 mx-2"></div>

                <a href="#"
                    class="list-group-item list-group-item-action p-2 d-flex align-items-center justify-content-between border-bottom-0 border-light">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-success bg-opacity-10 text-success px-1 rounded-2">
                            <i class="bi bi-whatsapp fs-6"></i>
                        </div>
                        <span class="fw-medium text-dark small">Hubungi Kami</span>
                    </div>
                    <i class="bi bi-chevron-right text-muted" style="font-size: 10px;"></i>
                </a>

                <button wire:click="logout"
                    class="list-group-item list-group-item-action p-2 d-flex align-items-center justify-content-between text-danger w-100 border-0">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-danger bg-opacity-10 text-danger px-1 rounded-2">
                            <i class="bi bi-box-arrow-right fs-6"></i>
                        </div>
                        <span class="fw-bold small">Keluar</span>
                    </div>
                </button>
            </div>
        </div>

        <div class="text-center text-muted small my-4">
            <p class="mb-0">Versi Aplikasi 1.0.0</p>
            <p>&copy; {{ date('Y') }} Wahdah Inisiatif Kebaikan</p>
        </div>
    </div>

    <x-app.bottom-nav />
</div>
