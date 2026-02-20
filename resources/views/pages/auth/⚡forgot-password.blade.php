<?php

use Livewire\Attributes\Rule;
use Livewire\Component;
use App\Models\User;
use App\Models\NotificationTemplate;

new class extends Component {
    #[Rule('required|email')]
    public $email = '';

    public function sendOtp()
    {
        $this->validate();

        $user = User::where('email', $this->email)->first();

        if (!$user) {
            $this->addError('email', 'Email tidak terdaftar dalam sistem kami.');
            return;
        }

        // Generate and send OTP
        $otp = rand(100000, 999999);
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        session()->put('temp_user_id', $user->id);
        session()->put('otp_type', 'otp-reset');

        NotificationTemplate::sendOTP($user, $otp, 'otp-reset');

        session()->flash('success', 'Kode OTP reset password telah dikirim ke WhatsApp Anda.');
        $this->redirect(route('verification'), navigate: true);
    }
};
?>

<div class="min-vh-100 d-flex align-items-center justify-content-center flex-column">
    <section class="forgot-password-page w-100">
        <div class="container-fluid">
            <div class="text-center">
                <div class="mb-4 d-inline-block">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="h-auto object-fit-contain"
                        style="max-height: 48px;">
                </div>
                <h3 class="fw-bold text-dark mb-1">Lupa Password?</h3>
                <p class="text-muted small">Masukkan email Anda untuk menerima kode OTP.</p>
            </div>

            <form wire:submit="sendOtp" class="mt-4">
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

                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold mb-4 shadow-auth rounded-pill">
                    <span wire:loading.remove>Kirim Kode OTP</span>
                    <span wire:loading>Memproses...</span>
                </button>

                <div class="text-center">
                    <a href="{{ route('login') }}" wire:navigate class="text-decoration-none small fw-bold text-muted">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Login
                    </a>
                </div>
            </form>
        </div>
    </section>
</div>
