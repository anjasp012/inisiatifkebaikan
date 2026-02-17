<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Donation;
use App\Models\PaymentProof;
use Illuminate\Support\Str;

new class extends Component {
    use WithFileUploads;

    public $donation;
    public $activeInstruction = 0;
    public $proof;
    public $isUploading = false;
    public $uploadSuccess = false;

    public function mount($transaction_id)
    {
        $this->donation = Donation::with(['campaign', 'bank', 'paymentProofs'])
            ->where('transaction_id', $transaction_id)
            ->firstOrFail();

        if ($this->donation->paymentProofs->count() > 0) {
            $this->uploadSuccess = true;
        }
    }

    public function updatedProof()
    {
        $this->validate([
            'proof' => 'image|max:2048',
        ]);

        $this->uploadProof();
    }

    public function refreshStatus()
    {
        $this->donation->refresh();

        // If status becomes success and it was previously pending, we might want to reload or just let the UI update
        // The UI already handles the status display, so refreshing the model is enough.
    }

    public function uploadProof()
    {
        $this->isUploading = true;

        try {
            $path = $this->proof->store('payment_proofs', 'public');

            PaymentProof::create([
                'donation_id' => $this->donation->id,
                'file_path' => $path,
                'claimed_amount' => $this->donation->amount,
                'notes' => 'Uploaded by donor via instruction page.',
            ]);

            $this->uploadSuccess = true;
            $this->isUploading = false;
            $this->donation->load('paymentProofs');
            session()->flash('success_upload', 'Bukti berhasil diunggah.');
        } catch (\Exception $e) {
            $this->isUploading = false;
            session()->flash('error_upload', 'Gagal: ' . $e->getMessage());
        }
    }

    public function getSteps()
    {
        if ($this->donation->payment_method === 'manual') {
            return [
                [
                    'title' => 'Cara Transfer',
                    'steps' => ['Pilih menu <strong>Transfer</strong> pada m-Banking/ATM.', 'Tujuan: <strong>' . ($this->donation->bank->bank_name ?? 'Bank') . '</strong>.', 'Nomor Rekening: <strong>' . $this->donation->payment_code . '</strong>.', 'Atas Nama: <strong>' . ($this->donation->bank->account_name ?? 'Inisiatif Kebaikan') . '</strong>.', 'Nominal: <strong>Rp ' . number_format($this->donation->amount, 0, ',', '.') . '</strong>.', 'Upload bukti transfer setelah pembayaran.'],
                ],
            ];
        }

        if (!empty($this->donation->payment_instructions)) {
            $instructions = $this->donation->payment_instructions;
            if (isset($instructions[0]['steps'])) {
                return $instructions;
            }
            return [['title' => 'Langkah', 'steps' => $instructions]];
        }

        return [['title' => 'Langkah', 'steps' => ['Selesaikan pembayaran melalui aplikasi/merchant pilihan Anda.', 'Pastikan nominal sesuai dengan tagihan.']]];
    }
};
?>

<div class="bg-lighter min-vh-100 font-jakarta pb-5"
    @if ($donation->status === 'pending') wire:poll.10s="refreshStatus" @endif x-data="{
        expiry: {{ $donation->expired_at ? $donation->expired_at->timestamp : 0 }},
        timer: { h: '00', m: '00', s: '00' },
        activeGroup: 0,
        init() {
            if (this.expiry === 0) return;
            this.update();
            setInterval(() => this.update(), 1000);
        },
        update() {
            const now = Math.floor(Date.now() / 1000);
            const diff = this.expiry - now;
            if (diff <= 0) {
                this.timer = { h: '00', m: '00', s: '00' };
                return;
            }
            const h = Math.floor(diff / 3600);
            const m = Math.floor((diff % 3600) / 60);
            const s = Math.floor(diff % 60);
            this.timer.h = h.toString().padStart(2, '0');
            this.timer.m = m.toString().padStart(2, '0');
            this.timer.s = s.toString().padStart(2, '0');
        },
        copyText(text) {
            navigator.clipboard.writeText(text).then(() => alert('Tersalin!'));
        }
    }">

    <x-app.navbar-secondary title="Instruksi Pembayaran" route="{{ route('home') }}" />

    <div class="container-fluid px-3 pt-3" style="max-width: 480px;">

        {{-- Status Section --}}
        <div
            class="d-flex align-items-center justify-content-between mb-4 bg-white p-2 rounded-2 border border-light shadow-micro">
            <div class="d-flex align-items-center gap-2 ps-1">
                @if ($donation->status === 'success')
                    <div class="status-dot bg-success"></div>
                    <span class="text-success fw-bold extra-small text-uppercase">Berhasil</span>
                @elseif($donation->status === 'failed')
                    <div class="status-dot bg-danger"></div>
                    <span class="text-danger fw-bold extra-small text-uppercase">Gagal</span>
                @else
                    <div class="status-dot bg-warning pulsate"></div>
                    <span class="text-dark fw-bold extra-small text-uppercase">Menunggu</span>
                @endif
            </div>
            @if ($donation->status === 'pending' && $donation->expired_at)
                <div class="d-flex align-items-center gap-2 pe-1">
                    <i class="bi bi-clock-history text-muted extra-small"></i>
                    <span class="font-monospace fw-bold text-dark extra-small"
                        x-text="`${timer.h}:${timer.m}:${timer.s}`">00:00:00</span>
                </div>
            @endif
        </div>

        {{-- Payment Detail --}}
        <div class="card border-0 rounded-4 mb-4 border border-light overflow-hidden">
            <div class="card-body p-0 text-center">
                <span class="text-muted extra-small fw-bold text-uppercase ls-1 mb-1 d-block">Jumlah Donasi</span>
                <div class="h2 fw-bold text-dark tracking-tight mb-4">Rp
                    {{ number_format($donation->amount, 0, ',', '.') }}</div>

                <div class="p-3 bg-lighter rounded-3 border border-light text-start">
                    @php
                        $isEwallet =
                            ($donation->bank && $donation->bank->method === 'ewallet') ||
                            Str::contains(strtolower($donation->payment_channel), [
                                'gopay',
                                'shopeepay',
                                'dana',
                                'linkaja',
                                'ovo',
                            ]);
                    @endphp

                    @if (Str::startsWith($donation->payment_code, 'http') || ($isEwallet && $donation->payment_url))
                        <div class="text-center">
                            @if ($isEwallet && $donation->payment_url)
                                <a href="{{ $donation->payment_url }}"
                                    class="btn btn-primary w-100 rounded-pill py-2 fw-bold mb-3 shadow-md d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-phone-fill"></i> BAYAR SEKARANG
                                </a>
                            @endif
                            @if (Str::startsWith($donation->payment_code, 'http'))
                                <div class="bg-white p-2 rounded-4 border d-inline-block shadow-micro">
                                    <img src="{{ $donation->payment_code }}"
                                        style="width: 140px; height: 140px; object-fit: contain;">
                                </div>
                                <p class="text-muted extra-small fw-bold mt-3 mb-0">Scan / Screenshot QR Code</p>
                            @endif
                        </div>
                    @else
                        <div class="va-section">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span
                                    class="text-muted extra-small fw-bold text-uppercase">{{ $donation->payment_method === 'manual' ? 'Rekening Tujuan' : 'Nomor VA' }}</span>
                                <div class="d-flex align-items-center gap-2 opacity-50">
                                    @if ($donation->bank && $donation->bank->logo)
                                        <img src="{{ $donation->bank->logo_url }}"
                                            style="height: 10px; max-width: 25px; object-fit: contain; filter: grayscale(1);">
                                    @endif
                                    <span
                                        class="extra-small fw-bold">{{ $donation->bank->bank_name ?? $donation->payment_channel }}</span>
                                </div>
                            </div>
                            <div
                                class="d-flex justify-content-between align-items-center bg-white p-3 rounded-3 border border-light">
                                <span
                                    class="h4 fw-bold text-dark font-monospace mb-0 tracking-widest">{{ $donation->payment_code }}</span>
                                <button class="btn btn-primary btn-sm rounded-pill px-3 py-1 fw-bold extra-small"
                                    @click="copyText('{{ $donation->payment_code }}')">SALIN</button>
                            </div>
                            @if ($donation->payment_method === 'manual')
                                <div class="mt-2 text-dark extra-small border-top border-light pt-2">
                                    A/N <span
                                        class="fw-bold">{{ $donation->bank->account_name ?? 'Inisiatif Kebaikan' }}</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="mt-3 text-muted extra-small fw-medium opacity-25">ID#{{ $donation->transaction_id }}</div>
            </div>
        </div>

        {{-- Instructions Section --}}
        @php $instructionGroups = $this->getSteps(); @endphp
        <div class="mb-4">
            <h6 class="fw-bold extra-small text-uppercase ls-1 text-muted mb-3 px-1">Petunjuk Pembayaran</h6>

            @if (count($instructionGroups) > 1)
                <div class="d-flex gap-2 mb-3 overflow-x-auto no-scrollbar pb-1 px-1">
                    @foreach ($instructionGroups as $index => $group)
                        <button @click="activeGroup = {{ $index }}" class="tab-chip"
                            :class="activeGroup === {{ $index }} ? 'active' : ''">
                            {{ $group['title'] ?? ($group['name'] ?? 'Instruksi') }}
                        </button>
                    @endforeach
                </div>
            @endif

            <div class="card border-0 rounded-4 shadow-micro border border-light">
                <div class="card-body p-3">
                    @foreach ($instructionGroups as $index => $group)
                        <div x-show="activeGroup === {{ $index }}" x-transition>
                            <div class="stepper-minimal">
                                @foreach ($group['steps'] as $sIndex => $step)
                                    <div class="d-flex gap-3 {{ !$loop->last ? 'mb-3' : '' }}">
                                        <div class="step-bullet">{{ $sIndex + 1 }}</div>
                                        <div class="extra-small text-dark fw-medium lh-base pt-0.5">
                                            {!! is_array($step) ? $step['content'] ?? '' : $step !!}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Proof Upload --}}
        @if ($donation->payment_method === 'manual')
            <div class="card border-0 rounded-4 shadow-micro border border-light mb-4 text-center">
                <div class="card-body p-3">
                    @if ($donation->paymentProofs->isNotEmpty())
                        <div class="text-success extra-small fw-bold mb-3">
                            <i class="bi bi-shield-check-fill me-1"></i> Bukti Berhasil Terkirim
                        </div>
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            @foreach ($donation->paymentProofs as $proof)
                                <a href="{{ $proof->file_url }}" target="_blank"
                                    class="position-relative d-inline-block rounded-3 overflow-hidden border border-light shadow-micro"
                                    style="width: 100px; height: 100px;">
                                    <img src="{{ $proof->file_url }}" class="w-100 h-100 object-fit-cover"
                                        alt="Bukti Transfer">
                                </a>
                            @endforeach
                        </div>
                        @if (!$uploadSuccess)
                            <hr class="my-3 opacity-10">
                            <label class="cursor-pointer">
                                <span class="extra-small fw-bold text-primary text-uppercase">Tambah Bukti Lagi</span>
                                <input type="file" wire:model="proof" class="d-none">
                            </label>
                        @endif
                    @else
                        <label class="w-100 cursor-pointer">
                            <div class="upload-minimal py-3 border border-dashed rounded-3">
                                <div wire:loading.remove wire:target="proof">
                                    <i class="bi bi-camera text-primary fs-5"></i>
                                    <div class="extra-small fw-bold text-dark mt-1 text-uppercase">Upload Bukti Transfer
                                    </div>
                                </div>
                                <div wire:loading wire:target="proof"
                                    class="spinner-border spinner-border-sm text-primary"></div>
                            </div>
                            <input type="file" wire:model="proof" class="d-none">
                        </label>
                    @endif
                </div>
            </div>
        @endif

        {{-- Action Buttons --}}
        <div class="d-grid gap-2 mt-5 pb-5 px-1">
            <a href="{{ route('home') }}"
                class="btn btn-dark rounded-pill py-2.5 fw-bold extra-small text-uppercase tracking-widest shadow-sm">
                Selesai & Beranda
            </a>
            <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp_number') }}"
                class="btn btn-link py-1 text-decoration-none text-muted extra-small fw-bold opacity-50">
                Butuh Bantuan? Hubungi CS
            </a>
        </div>
    </div>
</div>

<style>
    .font-jakarta {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .bg-lighter {
        background-color: #f8f9fb;
    }

    .extra-small {
        font-size: 10px;
    }

    .ls-1 {
        letter-spacing: 0.1em;
    }

    .tracking-tight {
        letter-spacing: -0.02em;
    }

    .tracking-widest {
        letter-spacing: 1.5px;
    }

    .shadow-micro {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
    }

    .border-light {
        border-color: #f1f2f4 !important;
    }

    .border-dashed {
        border-style: dashed !important;
        border-color: #dfe2e6;
    }

    .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
    }

    .pulsate {
        animation: p-key 2s infinite ease-in-out;
    }

    @keyframes p-key {

        0%,
        100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: 0.5;
            transform: scale(1.3);
        }
    }

    .tab-chip {
        flex-shrink: 0;
        padding: 5px 16px;
        border-radius: 100px;
        border: 1px solid #e1e3e5;
        background: #fff;
        font-size: 10px;
        font-weight: 700;
        color: #888;
        transition: 0.2s;
        white-space: nowrap;
    }

    .tab-chip.active {
        border-color: #DC5207;
        background: #DC5207;
        color: #fff;
    }

    .step-bullet {
        width: 18px;
        height: 18px;
        border-radius: 6px;
        background: #DC5207;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
        font-weight: 800;
        flex-shrink: 0;
    }

    .upload-minimal {
        background: #fff;
        transition: 0.2s;
    }

    .upload-minimal:hover {
        border-color: #DC5207 !important;
    }

    .btn-dark {
        background: #000;
        border: 0;
    }

    .btn-primary {
        background: #DC5207;
        border: 0;
    }

    .shadow-md {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
    }

    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    [x-cloak] {
        display: none !important;
    }
</style>
