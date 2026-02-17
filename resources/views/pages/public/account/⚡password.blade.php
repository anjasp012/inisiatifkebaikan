<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rule;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Facades\View;

new #[Layout('layouts.app')] class extends Component {
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        if (!Auth::check()) {
            $this->redirect(route('login'), navigate: true);
        }

        $seoData = new SEOData(title: 'Ganti Kata Sandi | Inisiatif Kebaikan', robots: 'noindex, nofollow');
        View::share('seoData', $seoData);
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|confirmed|min:8|different:current_password',
        ]);

        Auth::user()->update([
            'password' => Hash::make($this->new_password),
        ]);

        session()->flash('success', 'Kata sandi berhasil diperbarui.');
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
    }

    public function cancel()
    {
        $this->redirect(route('account.index'), navigate: true);
    }
};
?>

<div class="bg-gray-50 min-vh-100 pb-5">
    <x-app.navbar-secondary title="Ganti Password" :route="route('account.index')" />

    <div class="container-fluid py-4">
        @if (session()->has('success'))
            <div
                class="alert alert-success border-0 bg-success bg-opacity-10 text-success rounded-3 mb-3 d-flex align-items-center gap-2 small">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="updatePassword">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Kata Sandi Lama</label>
                        <input type="password" wire:model="current_password"
                            class="form-control rounded-3 py-2 fs-6 @error('current_password') is-invalid @enderror">
                        @error('current_password')
                            <span class="text-danger extra-small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Kata Sandi Baru</label>
                        <input type="password" wire:model="new_password"
                            class="form-control rounded-3 py-2 fs-6 @error('new_password') is-invalid @enderror">
                        @error('new_password')
                            <span class="text-danger extra-small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" wire:model="new_password_confirmation"
                            class="form-control rounded-3 py-2 fs-6">
                    </div>
                </div>
            </div>

            <button type="submit" class="detail-cta__button">
                <span wire:loading.remove>Simpan Perubahan</span>
                <span wire:loading>Menyimpan...</span>
            </button>
        </form>
    </div>
</div>
