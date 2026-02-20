<?php

use Livewire\Attributes\Rule;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    #[Rule('required|min:8|confirmed')]
    public $password = '';
    public $password_confirmation = '';

    public function mount()
    {
        if (!session()->has('can_reset_password')) {
            $this->redirect(route('password.request'), navigate: true);
        }
    }

    public function resetPassword()
    {
        $this->validate();

        $userId = session('can_reset_password');
        $user = User::find($userId);

        if (!$user) {
            $this->redirect(route('login'), navigate: true);
            return;
        }

        $user->update([
            'password' => Hash::make($this->password),
        ]);

        session()->forget(['can_reset_password', 'temp_user_id', 'otp_type']);

        // Auto login after reset
        Auth::login($user);

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Password berhasil diubah. Selamat datang kembali! ✅',
        ]);

        $this->redirect(route('home'), navigate: true);
    }
};
?>

<div class="min-vh-100 d-flex align-items-center justify-content-center flex-column">
    <section class="reset-password-page w-100">
        <div class="container-fluid">
            <div class="text-center">
                <div class="mb-4 d-inline-block">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="h-auto object-fit-contain"
                        style="max-height: 48px;">
                </div>
                <h3 class="fw-bold text-dark mb-1">Pasang Password Baru</h3>
                <p class="text-muted small">Silakan buat password baru yang kuat untuk akun Anda.</p>
            </div>

            <form wire:submit="resetPassword" class="mt-4">
                <div class="mb-4">
                    <label class="form-label fw-bold small text-muted text-uppercase ls-1">Password Baru</label>
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
                            <i class="bi bi-lock-check"></i>
                        </span>
                        <input type="password" wire:model="password_confirmation"
                            class="form-control py-3 bg-transparent border-0 shadow-none" placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold mb-4 shadow-auth rounded-pill">
                    <span wire:loading.remove>Simpan & Masuk</span>
                    <span wire:loading>Memproses...</span>
                </button>
            </form>
        </div>
    </section>
</div>
