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
        $bank = Bank::find($bankId);
        if (!$bank) {
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

<div
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

            <div class="space-y-3" x-data="{ activeGroup: 'ewallet' }">

                {{-- E-Wallet & QRIS --}}
                <div class="card border rounded-4 shadow-micro overflow-hidden border-light">
                    <button class="w-100 border-0 bg-white p-3 d-flex align-items-center justify-content-between"
                        @click="activeGroup = activeGroup === 'ewallet' ? '' : 'ewallet'">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-phone text-muted fs-5"></i>
                            <div class="text-start">
                                <span class="d-block fw-bold text-dark small">E-Wallet & QRIS</span>
                                <span class="text-muted extra-small">Otomatis Terverifikasi</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-down extra-small transition-transform"
                            :class="activeGroup === 'ewallet' ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="activeGroup === 'ewallet'" x-collapse>
                        <div class="list-group list-group-flush border-top border-light">
                            @foreach ($banks->whereIn('method', ['ewallet', 'qris']) as $bank)
                                <button wire:click="processPayment({{ $bank->id }})"
                                    class="list-group-item d-flex align-items-center justify-content-between p-3 border-0">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bank-logo-mini">
                                            <img src="{{ $bank->logo_url }}" class="img-fluid"
                                                alt="{{ $bank->bank_name }}">
                                        </div>
                                        <span class="fw-bold text-dark small">{{ $bank->bank_name }}</span>
                                    </div>
                                    <i class="bi bi-chevron-right text-muted extra-small"></i>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Virtual Account --}}
                <div class="card border rounded-4 shadow-micro overflow-hidden border-light">
                    <button class="w-100 border-0 bg-white p-3 d-flex align-items-center justify-content-between"
                        @click="activeGroup = activeGroup === 'va' ? '' : 'va'">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-credit-card text-muted fs-5"></i>
                            <div class="text-start">
                                <span class="d-block fw-bold text-dark small">Virtual Account</span>
                                <span class="text-muted extra-small">Transfer Bank Otomatis</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-down extra-small transition-transform"
                            :class="activeGroup === 'va' ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="activeGroup === 'va'" x-collapse>
                        <div class="list-group list-group-flush border-top border-light">
                            @foreach ($banks->where('method', 'va') as $bank)
                                <button wire:click="processPayment({{ $bank->id }})"
                                    class="list-group-item d-flex align-items-center justify-content-between p-3 border-0">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bank-logo-mini">
                                            <img src="{{ $bank->logo_url }}" class="img-fluid"
                                                alt="{{ $bank->bank_name }}">
                                        </div>
                                        <span class="fw-bold text-dark small">{{ $bank->bank_name }}</span>
                                    </div>
                                    <i class="bi bi-chevron-right text-muted extra-small"></i>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Manual --}}
                <div class="card border rounded-4 shadow-micro overflow-hidden border-light">
                    <button class="w-100 border-0 bg-white p-3 d-flex align-items-center justify-content-between"
                        @click="activeGroup = activeGroup === 'manual' ? '' : 'manual'">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-bank text-muted fs-5"></i>
                            <div class="text-start">
                                <span class="d-block fw-bold text-dark small">Transfer Manual</span>
                                <span class="text-muted extra-small">Konfirmasi via Upload Bukti</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-down extra-small transition-transform"
                            :class="activeGroup === 'manual' ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="activeGroup === 'manual'" x-collapse>
                        <div class="list-group list-group-flush border-top border-light">
                            @foreach ($banks->where('method', 'manual') as $bank)
                                <button wire:click="processPayment({{ $bank->id }})"
                                    class="list-group-item d-flex align-items-center justify-content-between p-3 border-0">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bank-logo-mini">
                                            <img src="{{ $bank->logo_url }}" class="img-fluid"
                                                alt="{{ $bank->bank_name }}">
                                        </div>
                                        <div>
                                            <span
                                                class="text-start d-block fw-bold text-dark small">{{ $bank->bank_name }}</span>
                                            <div class="text-muted extra-small">
                                                <span class="fw-bold">{{ $bank->account_number }}</span>
                                                <span class="px-1">•</span>
                                                <span>a.n {{ $bank->account_name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <i class="bi bi-chevron-right text-muted extra-small"></i>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Retail --}}
                @if ($banks->where('method', 'retail')->count() > 0)
                    <div class="card border rounded-4 shadow-micro overflow-hidden border-light">
                        <button class="w-100 border-0 bg-white p-3 d-flex align-items-center justify-content-between"
                            @click="activeGroup = activeGroup === 'retail' ? '' : 'retail'">
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-shop text-muted fs-5"></i>
                                <div class="text-start">
                                    <span class="d-block fw-bold text-dark small">Gerai Retail</span>
                                    <span class="text-muted extra-small">Alfamart / Indomaret</span>
                                </div>
                            </div>
                            <i class="bi bi-chevron-down extra-small transition-transform"
                                :class="activeGroup === 'retail' ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="activeGroup === 'retail'" x-collapse>
                            <div class="list-group list-group-flush border-top border-light">
                                @foreach ($banks->where('method', 'retail') as $bank)
                                    <button wire:click="processPayment({{ $bank->id }})"
                                        class="list-group-item d-flex align-items-center justify-content-between p-3 border-0">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bank-logo-mini">
                                                <img src="{{ $bank->logo_url }}" class="img-fluid"
                                                    alt="{{ $bank->bank_name }}">
                                            </div>
                                            <span class="fw-bold text-dark small">{{ $bank->bank_name }}</span>
                                        </div>
                                        <i class="bi bi-chevron-right text-muted extra-small"></i>
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

    @push('scripts')
        <script>
            fbq('track', 'AddPaymentInfo');
            fbq('track', 'AddToCart');
        </script>
    @endpush
</div>
