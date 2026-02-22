<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use App\Models\Campaign;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public Campaign $campaign;

    #[Url]
    public $amount = 0;

    #[Rule('required|string|min:3')]
    public $name = ''; // Or phone validation if needed

    #[Rule('required|email')]
    public $email = '';

    #[Rule('nullable|string')]
    public $phone = '';

    public $is_anonymous = false;

    #[Rule('nullable|string|max:500')]
    public $message = '';

    public function mount(Campaign $campaign)
    {
        $this->campaign = $campaign;

        // If amount is invalid, redirect back
        if ($this->amount < 10000) {
            return $this->redirect(route('donation.amount', $this->campaign->slug), navigate: true);
        }

        // Auto-fill if logged in
        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone; // Assuming phone exists in User model
        }
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama lengkap minimal 3 karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid (contoh: user@mail.com).',
            'phone.required' => 'Nomor WhatsApp wajib diisi.', // If required
            'message.max' => 'Pesan doa maksimal 500 karakter.',
        ];
    }

    public function redirectToLogin()
    {
        // Store current URL in session to redirect back after login
        session()->put('url.intended', url()->full());
        return redirect()->route('login');
    }

    public function submit()
    {
        $this->validate();

        // Proceed to Payment Method Selection
        // We can pass data via session or just params if sensitive data isn't an issue?
        // Better to store temporary donation data in session or database (pending).

        // Strategy: Create a Pending Donation record OR pass everything to next step.
        // User request: "tombol pilih metode pembayaran". So next page is Payment Method.
        // Let's pass data via Session and redirect to Payment Method page.

        // For now, let's assume we redirect to a payment method page.
        // Or show a modal? User said "tombol pilih metode pembayaran".
        // Likely a separate page or a bottom sheet.
        // Given the flow, a Payment Page seems best.

        session([
            'donation_data' => [
                'campaign_id' => $this->campaign->id,
                'amount' => $this->amount,
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'is_anonymous' => $this->is_anonymous,
                'message' => $this->message,
            ],
        ]);

        return $this->redirect(route('donation.payment', $this->campaign->slug), navigate: true);
    }
};
?>

<div x-init="fbq('track', 'AddPaymentInfo')">
    <x-app.navbar-secondary route="{{ route('donation.amount', ['campaign' => $campaign->slug, 'amount' => $amount]) }}"
        title="Isi Data Diri" />

    <section class="donation-data-page py-4">
        <div class="container-fluid">
            {{-- Nominal Summary --}}
            <div class="card border-0 bg-light mb-4">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <span class="text-muted fw-medium">Nominal Donasi</span>
                    <span class="fw-bold text-primary fs-5">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Login Prompt --}}
            @guest
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
            @endguest

            {{-- Form Data Diri --}}
            <div class="mb-4">
                <h6 class="fw-bold mb-3">Informasi Donatur</h6>

                <div class="mb-3">
                    <label class="form-label small text-muted fw-bold">Nama Lengkap</label>
                    <input type="text" wire:model="name"
                        class="form-control form-control-lg fs-6 @error('name') is-invalid @enderror"
                        placeholder="Masukkan nama anda">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label small text-muted fw-bold">Email</label>
                    <input type="email" wire:model="email"
                        class="form-control form-control-lg fs-6 @error('email') is-invalid @enderror"
                        placeholder="Contoh: nama@email.com">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label small text-muted fw-bold">Nomor WhatsApp (Opsional)</label>
                    <input type="tel" wire:model="phone"
                        class="form-control form-control-lg fs-6 @error('phone') is-invalid @enderror"
                        placeholder="Contoh: 0812...">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check form-switch p-0 d-flex justify-content-between align-items-center mb-0 bg-light px-3 py-2 cursor-pointer h-auto"
                    style="min-height: 50px;">
                    <label class="form-check-label ms-0 fw-bold text-dark cursor-pointer"
                        for="anonymousSwitch">Sembunyikan
                        Nama (Sahabat Inisiatif)</label>
                    <input class="form-check-input ms-0 fs-5 cursor-pointer" type="checkbox" role="switch"
                        id="anonymousSwitch" wire:model="is_anonymous">
                </div>
            </div>

            {{-- Doa / Pesan --}}
            <div class="mb-5">
                <h6 class="fw-bold mb-3">Doa & Dukungan (Opsional)</h6>
                <textarea wire:model="message" class="form-control form-control-lg fs-6 @error('message') is-invalid @enderror"
                    rows="4" placeholder="Tuliskan doa atau pesan dukungan Anda (Akan tampil di halaman donasi)"></textarea>
                @error('message')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text text-end" x-data="{ count: 0 }" x-init="count = $wire.message ? $wire.message.length : 0"
                    x-on:input="count = $event.target.value.length">
                    <span x-text="count"></span>/500
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <div class="detail-cta h-auto z-100">
        <div class="w-100">
            <button wire:click="submit" class="detail-cta__button w-100 border-0 rounded-pill">
                Pilih Metode Pembayaran <i class="bi bi-chevron-right ms-2"></i>
            </button>
        </div>
    </div>


</div>
