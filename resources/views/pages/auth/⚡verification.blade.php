<?php

use Livewire\Attributes\Rule;
use Livewire\Component;
use App\Models\User;
use App\Models\NotificationTemplate;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.app')] class extends Component {
    #[Rule('required|digits:6')]
    public $otp = '';

    public function mount()
    {
        if (!Auth::check()) {
            $this->redirect(route('login'), navigate: true);
            return;
        }

        if (Auth::user()->user_verified_at) {
            $this->redirect(route('home'), navigate: true);
            return;
        }

        $this->ensureOtpSent();
    }

    public function ensureOtpSent()
    {
        $user = Auth::user();
        if (!$user->otp_code || now()->greaterThan($user->otp_expires_at)) {
            $otp = rand(100000, 999999);
            $user->update([
                'otp_code' => $otp,
                'otp_expires_at' => now()->addMinutes(5),
            ]);
            NotificationTemplate::sendOTP($user, $otp, session('otp_type', 'otp-login'));
            session()->flash('success', 'Kode OTP baru telah dikirim ke WhatsApp Anda.');
        }
    }

    public function resend()
    {
        $user = Auth::user();
        $otp = rand(100000, 999999);
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(5),
        ]);
        NotificationTemplate::sendOTP($user, $otp, session('otp_type', 'otp-login'));
        session()->flash('success', 'Kode OTP baru telah dikirim ke WhatsApp Anda.');
    }

    public function verify()
    {
        $this->validate();

        /** @var User */
        $user = Auth::user();

        // Ensure user is authenticated
        if (!$user) {
            $this->redirect(route('login'), navigate: true);
            return;
        }

        // Logic check
        if ($user->otp_code == $this->otp) {
            // Check expiry
            if ($user->otp_expires_at && now()->lessThanOrEqualTo($user->otp_expires_at)) {
                // Update user verification status
                $user->user_verified_at = now();
                $user->otp_code = null;
                $user->otp_expires_at = null;
                $user->save();

                $this->redirect(route('home'), navigate: true);
            } else {
                $this->addError('otp', 'Kode OTP sudah kadaluarsa. Silakan minta kirim ulang.');
                return;
            }
        } else {
            $this->addError('otp', 'Kode OTP salah. Silakan coba lagi.');
            return;
        }
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
                <h3 class="fw-bold mb-1">Verifikasi Nomor</h3>
                <p class="text-muted small">Masukkan kode OTP yang dikirim ke WhatsApp Anda.</p>
            </div>

            <form wire:submit.prevent="verify" class="mt-4" style="max-width: 400px; margin: 0 auto;">
                @if (session()->has('success'))
                    <div class="alert alert-success small mb-4 border-0 bg-success bg-opacity-10 text-success">
                        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                    </div>
                @endif

                <div class="mb-4">
                    <label class="form-label fw-bold small text-muted text-uppercase ls-1 text-center w-100">Kode OTP (6
                        Digit)</label>
                    <div class="input-group bg-light rounded-3 overflow-hidden border-0">
                        <span class="input-group-text bg-transparent border-0 pe-1 text-muted">
                            <i class="bi bi-shield-lock"></i>
                        </span>
                        <input type="text" wire:model="otp" maxlength="6"
                            class="form-control py-3 bg-transparent border-0 shadow-none text-center fw-bold fs-4 @error('otp') is-invalid @enderror"
                            placeholder="------" style="letter-spacing: 0.5em;">
                    </div>
                    @error('otp')
                        <div class="text-danger extra-small mt-1 px-1 fw-bold text-center">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold mb-3 shadow-md rounded-pill">
                    <span wire:loading.remove>Verifikasi</span>
                    <span wire:loading>Memproses...</span>
                </button>

                <div class="text-center">
                    <p class="text-muted small mb-0">
                        Tidak menerima kode?
                        <button type="button" wire:click="resend" wire:loading.attr="disabled"
                            class="btn btn-link p-0 fw-bold text-decoration-none text-primary small border-0 bg-transparent">
                            Kirim Ulang
                        </button>
                    </p>
                </div>
            </form>

            <div class="text-center mt-5">
                <livewire:auth.logout />
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
