<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use App\Models\Campaign;
use App\Models\Bank;
use App\Models\Donation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public Campaign $campaign;
    public $donationData;
    public $banks;
    public $isProcessing = false;

    public function mount(Campaign $campaign)
    {
        $this->campaign = $campaign;
        $this->donationData = session('donation_data');

        // Fetch all active banks
        $this->banks = Bank::where('is_active', true)->get();

        if (!$this->donationData) {
            return $this->redirect(route('donation.amount', $this->campaign->slug), navigate: true);
        }
    }

    public function processPayment($bankId)
    {
        if ($this->isProcessing) {
            return;
        }

        $this->isProcessing = true;

        $bank = Bank::find($bankId);
        if (!$bank) {
            $this->isProcessing = false;
            return;
        }

        $amount = (int) $this->donationData['amount'];
        $uniqueCode = 0;

        if ($bank->type === 'manual') {
            $uniqueCode = rand(1, 999);
            $finalAmount = $amount + $uniqueCode;
        } else {
            $finalAmount = $amount;
        }

        $donation = Donation::create([
            'transaction_id' => 'INV-' . strtoupper(Str::random(10)),
            'campaign_id' => $this->campaign->id,
            'user_id' => Auth::id(),
            'bank_id' => $bank->id,
            'donor_name' => $this->donationData['name'],
            'donor_email' => $this->donationData['email'],
            'donor_phone' => $this->donationData['phone'],
            'amount' => $finalAmount,
            'message' => $this->donationData['message'] ?? null,
            'is_anonymous' => $this->donationData['is_anonymous'] ?? false,
            'payment_method' => $bank->type,
            'payment_channel' => $bank->bank_name,
            'payment_code' => $bank->account_number ?? '-',
            'status' => 'pending',
            'expired_at' => now()->addDay(),
        ]);

        if (Auth::guest()) {
            $this->dispatch('donation-created', transaction_id: $donation->transaction_id);
        }

        if ($bank->type === 'midtrans') {
            $midtrans = new \App\Services\MidtransService();
            $params = [
                'transaction_details' => [
                    'order_id' => $donation->transaction_id,
                    'gross_amount' => (int) $donation->amount,
                ],
                'customer_details' => [
                    'first_name' => $donation->donor_name,
                    'email' => $donation->donor_email,
                    'phone' => $donation->donor_phone,
                ],
            ];

            if ($bank->bank_code === 'mandiri_va') {
                $params['payment_type'] = 'echannel';
                $params['echannel'] = [
                    'bill_info1' => 'Donasi',
                    'bill_info2' => $this->campaign->title,
                ];
            } elseif ($bank->bank_code === 'permata_va') {
                $params['payment_type'] = 'permata';
            } elseif (Str::contains($bank->bank_code, '_va')) {
                $params['payment_type'] = 'bank_transfer';
                $params['bank_transfer'] = ['bank' => str_replace('_va', '', $bank->bank_code)];
            } elseif (in_array($bank->bank_code, ['atm_bersama', 'alto', 'prima'])) {
                // Network banks usually use Permata VA as aggregator
                $params['payment_type'] = 'permata';
            } elseif (in_array($bank->bank_code, ['gopay', 'shopeepay', 'qris', 'dana'])) {
                $params['payment_type'] = $bank->bank_code;
            }

            try {
                $response = $midtrans->charge($params);

                if ($response && (isset($response->va_numbers) || isset($response->payment_code) || isset($response->bill_key) || isset($response->permata_va_number) || isset($response->actions))) {
                    $payCode = '-';
                    $payUrl = null;
                    $expiryTime = $donation->expired_at;

                    if (isset($response->va_numbers[0]->va_number)) {
                        $payCode = $response->va_numbers[0]->va_number;
                    } elseif (isset($response->bill_key)) {
                        $payCode = $response->bill_key;
                    } elseif (isset($response->permata_va_number)) {
                        $payCode = $response->permata_va_number;
                    } elseif (isset($response->payment_code)) {
                        $payCode = $response->payment_code;
                    } elseif (isset($response->actions)) {
                        foreach ($response->actions as $action) {
                            if ($action->name === 'generate-qr-code') {
                                $payCode = $action->url;
                            } elseif ($action->name === 'deeplink-redirect') {
                                $payUrl = $action->url;
                            }
                        }
                    }

                    if (isset($response->expiry_time)) {
                        try {
                            $expiryTime = \Illuminate\Support\Carbon::parse($response->expiry_time);
                        } catch (\Exception $e) {
                        }
                    }

                    $donation->update([
                        'payment_code' => $payCode,
                        'payment_url' => $payUrl ?: $donation->payment_url,
                        'expired_at' => $expiryTime,
                    ]);
                    return $this->redirect(route('donation.instruction', $donation->transaction_id), navigate: true);
                }
            } catch (\Exception $e) {
                \Log::error('Midtrans Charge Error: ' . $e->getMessage());
            }

            $this->isProcessing = false;
            session()->flash('error', 'Gagal memproses pembayaran Midtrans.');
            return null;
        }

        if ($bank->type === 'tripay') {
            $tripay = new \App\Services\TripayService();
            $params = [
                'method' => $bank->bank_code,
                'merchant_ref' => $donation->transaction_id,
                'amount' => (int) $donation->amount,
                'customer_name' => $donation->donor_name,
                'customer_email' => $donation->donor_email,
                'customer_phone' => $donation->donor_phone,
                'campaign_name' => $this->campaign->title,
            ];

            try {
                $result = $tripay->requestTransaction($params);

                if (isset($result['success']) && $result['success']) {
                    $payData = $result['data'];
                    $payCode = $payData['pay_code'] ?? ($payData['qr_url'] ?? '-');
                    $payUrl = $payData['checkout_url'] ?? null;
                    $expiryTime = isset($payData['expired_time']) ? \Illuminate\Support\Carbon::createFromTimestamp($payData['expired_time']) : $donation->expired_at;

                    $donation->update([
                        'payment_code' => $payCode,
                        'payment_url' => $payUrl,
                        'payment_instructions' => $payData['instructions'] ?? null,
                        'expired_at' => $expiryTime,
                    ]);
                    return $this->redirect(route('donation.instruction', $donation->transaction_id), navigate: true);
                }

                session()->flash('error', $result['message'] ?? 'Gagal membuat transaksi ke Tripay.');
            } catch (\Exception $e) {
                \Log::error('Tripay Request Error: ' . $e->getMessage());
                session()->flash('error', 'Terjadi kesalahan saat menghubungi Tripay.');
            }

            $this->isProcessing = false;
            return null;
        }

        $this->sendNotification($donation, $bank);

        return $this->redirect(route('donation.instruction', $donation->transaction_id), navigate: true);
    }

    protected function sendNotification($donation, $bank)
    {
        $template = \App\Models\NotificationTemplate::where('slug', 'donation-created')->first();

        if ($template) {
            $waService = new \App\Services\WhacenterService();

            $content = $template->content;
            $placeholders = [
                '{donor_name}' => $donation->donor_name,
                '{campaign_title}' => $this->campaign->title,
                '{amount}' => 'Rp. ' . number_format($donation->amount, 0, ',', '.'),
                '{payment_channel}' => $donation->payment_channel,
                '{payment_code}' => $donation->payment_code,
                '{account_name}' => $bank->account_name ?? 'Yayasan Wahdah Inisiatif Kebaikan',
            ];

            foreach ($placeholders as $key => $value) {
                $content = str_replace($key, $value, $content);
            }

            $waService->sendMessage($donation->donor_phone, $content, $donation->id);
        }
    }
};
?>



<div x-init="fbq('track', 'AddToCart')"
    @donation-created.window="
        (function() {
            try {
                let history = JSON.parse(localStorage.getItem('donation_history') || '[]');
                let currentId = $event.detail.transaction_id;
                if (currentId && !history.includes(currentId)) {
                    history.unshift(currentId);
                    localStorage.setItem('donation_history', JSON.stringify(history.slice(0, 50)));
                    console.log('Donation tracked (Payment):', currentId);
                }
            } catch (e) {}
        })();
    ">
    <x-app.navbar-secondary
        route="{{ route('donation.data', ['campaign' => $campaign->slug, 'amount' => $donationData['amount'] ?? 0]) }}"
        title="Pembayaran" />

    <section class="payment-page py-4">
        <div class="container-fluid">

            <div class="card border-0 bg-lighter rounded-4 mb-4 border border-light">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted extra-small fw-bold uppercase ls-md d-block">Total Donasi</span>
                        <div class="h3 fw-bold text-dark mb-0">Rp
                            {{ number_format($donationData['amount'] ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="bg-white p-2 rounded-3 border border-light shadow-micro text-primary">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
            </div>

            @if (session()->has('error'))
                <div
                    class="alert alert-danger border-0 rounded-3 mb-3 extra-small fw-bold py-2 d-flex align-items-center">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
                </div>
            @endif

            <div class="space-y-4" x-data="{
                isProcessing: @entangle('isProcessing'),
                localProcessing: false,
                startProcessing(bankId) {
                    if (this.isProcessing || this.localProcessing) return;
                    this.localProcessing = true;
                    $wire.processPayment(bankId);
                }
            }">

                {{-- Global Loading Overlay --}}
                <div x-show="isProcessing || localProcessing" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
                    style="background: rgba(255,255,255,0.8); z-index: 9999; backdrop-filter: blur(4px);">
                    <div class="text-center">
                        <div class="spinner-grow text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h6 class="fw-bold text-dark">Memproses Pembayaran...</h6>
                        <p class="text-muted small">Mohon tunggu sebentar, jangan tutup halaman ini.</p>
                    </div>
                </div>

                {{-- E-Wallet & QRIS --}}
                @php $ewalletBanks = $banks->whereIn('method', ['ewallet', 'qris']); @endphp
                @if ($ewalletBanks->count() > 0)
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3 px-1">
                            <i class="bi bi-phone text-primary fs-5"></i>
                            <div>
                                <h6 class="mb-0 fw-bold small">E-Wallet & QRIS</h6>
                                <p class="text-muted extra-small mb-0">Otomatis Terverifikasi</p>
                            </div>
                        </div>
                        <div class="card border rounded-4 shadow-micro overflow-hidden border-light">
                            <div class="list-group list-group-flush">
                                @foreach ($ewalletBanks as $bank)
                                    <button @click="startProcessing({{ $bank->id }})"
                                        :disabled="isProcessing || localProcessing"
                                        class="list-group-item d-flex align-items-center justify-content-between p-3 border-0">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bank-logo-mini">
                                                <img src="{{ $bank->logo_url }}" class="img-fluid"
                                                    alt="{{ $bank->bank_name }}">
                                            </div>
                                            <span class="fw-bold text-dark small">{{ $bank->bank_name }}</span>
                                        </div>
                                        <div x-show="isProcessing || localProcessing" wire:loading
                                            wire:target="processPayment({{ $bank->id }})"
                                            class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                        <i x-show="!(isProcessing || localProcessing)"
                                            class="bi bi-chevron-right text-muted extra-small"></i>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Virtual Account --}}
                @php $vaBanks = $banks->where('method', 'va'); @endphp
                @if ($vaBanks->count() > 0)
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3 px-1">
                            <i class="bi bi-credit-card text-primary fs-5"></i>
                            <div>
                                <h6 class="mb-0 fw-bold small">Virtual Account</h6>
                                <p class="text-muted extra-small mb-0">Transfer Bank Otomatis</p>
                            </div>
                        </div>
                        <div class="card border rounded-4 shadow-micro overflow-hidden border-light">
                            <div class="list-group list-group-flush">
                                @foreach ($vaBanks as $bank)
                                    <button @click="startProcessing({{ $bank->id }})"
                                        :disabled="isProcessing || localProcessing"
                                        class="list-group-item d-flex align-items-center justify-content-between p-3 border-0">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bank-logo-mini">
                                                <img src="{{ $bank->logo_url }}" class="img-fluid"
                                                    alt="{{ $bank->bank_name }}">
                                            </div>
                                            <span class="fw-bold text-dark small">{{ $bank->bank_name }}</span>
                                        </div>
                                        <div x-show="isProcessing || localProcessing" wire:loading
                                            wire:target="processPayment({{ $bank->id }})"
                                            class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                        <i x-show="!(isProcessing || localProcessing)"
                                            class="bi bi-chevron-right text-muted extra-small"></i>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Manual --}}
                @php $manualBanks = $banks->where('method', 'manual'); @endphp
                @if ($manualBanks->count() > 0)
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3 px-1">
                            <i class="bi bi-bank text-primary fs-5"></i>
                            <div>
                                <h6 class="mb-0 fw-bold small">Transfer Manual</h6>
                                <p class="text-muted extra-small mb-0">Konfirmasi via Upload Bukti</p>
                            </div>
                        </div>
                        <div class="card border rounded-4 shadow-micro overflow-hidden border-light">
                            <div class="list-group list-group-flush">
                                @foreach ($manualBanks as $bank)
                                    <button @click="startProcessing({{ $bank->id }})"
                                        :disabled="isProcessing || localProcessing"
                                        class="list-group-item d-flex align-items-center justify-content-between p-3 border-0">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bank-logo-mini">
                                                <img src="{{ $bank->logo_url }}" class="img-fluid"
                                                    alt="{{ $bank->bank_name }}">
                                            </div>
                                            <div>
                                                <span
                                                    class="text-start d-block fw-bold text-dark small">{{ $bank->bank_name }}</span>
                                                <div class="text-muted extra-small text-start"><span>a.n
                                                        {{ $bank->account_name }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div x-show="isProcessing || localProcessing" wire:loading
                                            wire:target="processPayment({{ $bank->id }})"
                                            class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                        <i x-show="!(isProcessing || localProcessing)"
                                            class="bi bi-chevron-right text-muted extra-small"></i>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Retail --}}
                @php $retailBanks = $banks->where('method', 'retail'); @endphp
                @if ($retailBanks->count() > 0)
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3 px-1">
                            <i class="bi bi-shop text-primary fs-5"></i>
                            <div>
                                <h6 class="mb-0 fw-bold small">Gerai Retail</h6>
                                <p class="text-muted extra-small mb-0">Alfamart / Indomaret</p>
                            </div>
                        </div>
                        <div class="card border rounded-4 shadow-micro overflow-hidden border-light">
                            <div class="list-group list-group-flush">
                                @foreach ($retailBanks as $bank)
                                    <button @click="startProcessing({{ $bank->id }})"
                                        :disabled="isProcessing || localProcessing"
                                        class="list-group-item d-flex align-items-center justify-content-between p-3 border-0">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bank-logo-mini">
                                                <img src="{{ $bank->logo_url }}" class="img-fluid"
                                                    alt="{{ $bank->bank_name }}">
                                            </div>
                                            <span class="fw-bold text-dark small">{{ $bank->bank_name }}</span>
                                        </div>
                                        <div x-show="isProcessing || localProcessing" wire:loading
                                            wire:target="processPayment({{ $bank->id }})"
                                            class="spinner-border spinner-border-sm text-primary" role="status">
                                        </div>
                                        <i x-show="!(isProcessing || localProcessing)"
                                            class="bi bi-chevron-right text-muted extra-small"></i>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-5 pb-5 text-center opacity-50">
                <i class="bi bi-shield-check text-success extra-small"></i>
                <span class="extra-small fw-bold text-muted ps-1 uppercase ls-md">Keamanan Transaksi Terjamin</span>
            </div>
        </div>
    </section>


</div>
