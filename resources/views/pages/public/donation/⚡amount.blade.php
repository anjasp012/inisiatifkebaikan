<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use App\Models\Campaign;

new class extends Component {
    public Campaign $campaign;

    #[Rule('required|numeric|min:10000', as: 'Nominal')]
    public $amount = 0; // Default 0 or selected preset

    public $customAmount = '';

    public function mount(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function selectAmount($value)
    {
        $this->amount = $value;
        $this->customAmount = number_format($value, 0, '', '.');
    }

    public function updatedCustomAmount()
    {
        // Remove non-numeric
        $numericValue = preg_replace('/[^0-9]/', '', $this->customAmount);

        // Update amount
        $this->amount = (int) $numericValue;

        // Optional: Reformat input nicely if not empty
        if ($this->amount > 0) {
            $this->customAmount = number_format($this->amount, 0, '', '.');
        } else {
            $this->customAmount = '';
        }
    }

    public function messages()
    {
        return [
            'amount.required' => 'Nominal donasi harus diisi.',
            'amount.numeric' => 'Nominal donasi harus berupa angka.',
            'amount.min' => 'Minimal donasi adalah Rp 10.000.',
        ];
    }

    public function submit()
    {
        $this->validate();

        return $this->redirect(
            route('donation.data', [
                'campaign' => $this->campaign->slug,
                'amount' => $this->amount,
            ]),
            navigate: true,
        );
    }
};
?>

<div class="bg-white min-vh-100">
    <x-app.navbar-secondary route="{{ route('campaign.show', $campaign->slug) }}" title="Masukan Nominal" />

    <section class="py-3">
        <div class="container-fluid">
            {{-- Campaign Info --}}
            <div class="card border-0 bg-light mb-4 overflow-hidden">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <img src="{{ $campaign->thumbnail_url }}"
                            class="rounded-3 object-fit-cover flex-shrink-0 avatar-lg" alt="{{ $campaign->title }}">
                        <div class="ms-3 overflow-hidden">
                            <h6 class="fw-bold mb-1 text-dark text-truncate">{{ $campaign->title }}</h6>
                            <div class="d-flex align-items-center small text-muted">
                                <span class="bg-white border px-2 py-1 rounded-pill me-2 extra-small">
                                    {{ $campaign->category->name ?? 'Umum' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Custom Input High Priority --}}
            <div class="mb-4">
                <label class="fw-bold text-dark mb-2 d-block">Mau donasi berapa?</label>
                <div class="position-relative">
                    <div
                        class="input-group input-group-lg border overflow-hidden rounded-2 {{ $errors->has('amount') ? 'border-danger' : ($customAmount ? 'border-primary' : 'border-secondary-subtle') }}">
                        <span class="input-group-text bg-transparent border-0 pe-1 text-muted fw-bold">Rp</span>
                        <input type="tel" wire:model.live="customAmount"
                            class="form-control border-0 bg-transparent fw-bold fs-4 shadow-none ps-1" placeholder="0"
                            inputmode="numeric">
                    </div>
                    @if ($customAmount && (int) preg_replace('/[^0-9]/', '', $customAmount) < 10000)
                        <small class="text-danger position-absolute" style="bottom: -22px; left: 0;">Min. Rp
                            10.000</small>
                    @endif
                </div>
                @error('amount')
                    <div class="text-danger small mt-1 fw-medium">{{ $message }}</div>
                @enderror
            </div>

            {{-- Presets --}}
            <div class="mb-5">
                <label class="fw-bold text-muted small text-uppercase mb-3 d-block">Atau pilih nominal instan</label>
                <div class="row g-2">
                    @foreach ([10000, 20000, 50000, 100000, 200000, 500000] as $preset)
                        <div class="col-6">
                            <button wire:click="selectAmount({{ $preset }})"
                                class="btn w-100 py-3 rounded-3 fw-bold position-relative overflow-hidden transition-200 {{ $amount == $preset ? 'btn-primary border-primary' : 'btn-outline-secondary border-secondary-subtle text-dark bg-white' }}">
                                <span class="d-block {{ $amount == $preset ? 'text-white' : 'text-dark' }}">
                                    Rp {{ number_format($preset, 0, ',', '.') }}
                                </span>
                                @if ($amount == $preset)
                                    <div class="position-absolute top-0 end-0 p-1">
                                        <i class="bi bi-check-circle-fill text-white small"></i>
                                    </div>
                                @endif
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Security Badge --}}
            <div class="text-center text-muted opacity-50 mb-5">
                <i class="bi bi-shield-lock-fill me-1"></i> Pembayaran Aman & Terverifikasi
            </div>
        </div>
    </section>

    {{-- Sticky Bottom Button --}}
    <div class="detail-cta h-auto z-100">
        <div class="w-100">
            <button wire:click="submit" class="detail-cta__button w-100 border-0 rounded-pill"
                @if ($amount < 10000) disabled @endif>
                Lanjut Pembayaran <i class="bi bi-arrow-right ms-2"></i>
            </button>
        </div>
    </div>
</div>
