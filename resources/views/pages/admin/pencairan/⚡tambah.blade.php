<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Campaign;
use App\Models\Withdrawal;
use App\Models\Donation;

new #[Layout('layouts.admin')] class extends Component {
    use WithFileUploads;

    public $campaign_id = '';
    public $amount = '';
    public $ads_fee = 0;
    public $platform_fee = 0;
    public $optimization_fee = 0;
    public $merchant_fee = 0;
    public $notes = '';
    public $proof_image;

    // Trigger update when campaign changes
    public function updatedCampaignId()
    {
        $this->calculateMerchantFee();
        // Set amount to max available by default
        $this->amount = $this->maxAmount;
        $this->calculateFees();
    }

    public function updatedAmount()
    {
        $this->calculateFees();
    }

    public function updatedAdsFee()
    {
        // Recalculate if ads fee changes (VA manual override maybe?)
    }

    public function calculateMerchantFee()
    {
        if (!$this->campaign_id) {
            $this->merchant_fee = 0;
            return;
        }

        // Logic from detail page: Sum all merchant fees from successful donations
        $totalMerchantFeesIncurred = Donation::where('campaign_id', $this->campaign_id)->where('status', 'success')->sum('merchant_fee');

        // Subtract merchant fees already accounted for in previous APPROVED withdrawals
        $previousMerchantFees = Withdrawal::where('campaign_id', $this->campaign_id)->where('status', 'success')->sum('merchant_fee');

        $this->merchant_fee = max(0, $totalMerchantFeesIncurred - $previousMerchantFees);
    }

    public function calculateFees()
    {
        // Simple fee defaults based on percentages if needed, or keep manual.
        // For 'tambah', usually admin inputs the requested amount.
        // Platform fee logic: 5% of amount
        if ($this->amount) {
            $amountVal = (float) ($this->amount ?: 0); // Assuming amount is already numeric or handled by input-rupiah
            // handle if input-rupiah passes raw string, though autoNumeric usually handles value.
            // Actually input-rupiah binds to model differently. Let's assume raw value.
            // If using x-admin.input-rupiah with defer, it might be string.

            // Let's rely on computed for final Net, but here we can pre-fill fees.
            // $this->platform_fee = $amountVal * 0.05;
            // $this->optimization_fee = ...
        }
    }

    #[Computed]
    public function selectedCampaign()
    {
        return $this->campaign_id ? Campaign::find($this->campaign_id) : null;
    }

    #[Computed]
    public function maxAmount()
    {
        if (!$this->selectedCampaign) {
            return 0;
        }

        $alreadyWithdrawn = Withdrawal::where('campaign_id', $this->campaign_id)->where('status', '!=', 'rejected')->sum('amount');

        return max(0, $this->selectedCampaign->collected_amount - $alreadyWithdrawn);
    }

    #[Computed]
    public function netAmount()
    {
        $amount = (float) ($this->amount ?: 0);
        $ads = (float) ($this->ads_fee ?: 0);
        $ads_vat = $ads * 0.11;

        // Auto-calc platform fee if not manually set? Or just allow manual.
        // Let's match manual input logic but usually it's auto.
        // Let's auto-calc for preview unless overridden (which is hard in one field).
        // Let's use properties.

        $platform = (float) ($this->platform_fee ?: 0);
        $optimization = (float) ($this->optimization_fee ?: 0);
        $merchant = (float) ($this->merchant_fee ?: 0);

        return max(0, $amount - ($ads + $ads_vat + $platform + $optimization + $merchant));
    }

    #[Computed]
    public function adsVat()
    {
        return (float) ($this->ads_fee ?: 0) * 0.11;
    }

    public function save()
    {
        $this->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'amount' => 'required|numeric|min:10000',
            'ads_fee' => 'nullable|numeric|min:0',
            'platform_fee' => 'nullable|numeric|min:0',
            'optimization_fee' => 'nullable|numeric|min:0',
            'merchant_fee' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'proof_image' => 'nullable|image|max:2048',
        ]);

        if ((float) $this->amount > $this->maxAmount) {
            $this->addError('amount', 'Nominal melebihi saldo tersedia.');
            return;
        }

        $path = $this->proof_image ? $this->proof_image->store('withdrawals', 'public') : null;

        Withdrawal::create([
            'fundraiser_id' => null,
            'campaign_id' => $this->campaign_id,
            'amount' => $this->amount,
            'ads_fee' => $this->ads_fee ?: 0,
            'ads_vat' => $this->adsVat,
            'platform_fee' => $this->platform_fee ?: 0,
            'optimization_fee' => $this->optimization_fee ?: 0,
            'merchant_fee' => $this->merchant_fee ?: 0,
            'net_amount' => $this->netAmount,
            'status' => 'success',
            'notes' => $this->notes,
            'proof_image' => $path,
        ]);

        session()->flash('success', 'Pencairan berhasil dibuat.');
        $this->redirect(route('admin.pencairan'), navigate: true);
    }
};
?>

<div>
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Buat Pencairan Baru</h5>
                    <p class="text-muted small mb-0">Ajukan pencairan dana untuk campaign internal atau operasional.</p>
                </div>
                <a href="{{ route('admin.pencairan') }}" wire:navigate class="btn btn-light border">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <form wire:submit="save">
                <div class="row g-4">
                    {{-- Form Input --}}
                    <div class="col-lg-8">
                        <div class="bg-light p-4 rounded-4 h-100">
                            <h6 class="fw-bold mb-4 border-bottom pb-2">Formulir Pencairan</h6>

                            {{-- Campaign Selection --}}
                            <div class="mb-4 @error('campaign_id') is-invalid-tomselect @enderror">
                                <label class="form-label small fw-bold text-uppercase">Pilih Campaign</label>
                                <div wire:ignore>
                                    <select x-data="{
                                        tom: null,
                                        init() {
                                            this.tom = new TomSelect(this.$el, {
                                                placeholder: 'Pilih atau cari Campaign...',
                                                allowEmptyOption: false,
                                                maxOptions: 50,
                                                onDropdownOpen: function() {
                                                    this.clear(true);
                                                },
                                                onChange: (value) => {
                                                    $wire.set('campaign_id', value || null);
                                                }
                                            });
                                        }
                                    }"
                                        class="form-select @error('campaign_id')
is-invalid
@enderror">
                                        <option value=""></option>
                                        @foreach (\App\Models\Campaign::with('fundraiser')->where('collected_amount', '>', 0)->latest()->get() as $campaign)
                                            <option value="{{ $campaign->id }}">
                                                {{ $campaign->title }}
                                                @if ($campaign->fundraiser)
                                                    - {{ $campaign->fundraiser->foundation_name }}
                                                @else
                                                    (Internal)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('campaign_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Input Nominal --}}
                            <div class="mb-4">
                                <x-admin.input-rupiah model="amount" label="Nominal Pencairan" placeholder="0" />
                                <div class="d-flex justify-content-between x-small text-muted mt-1 px-1">
                                    <span>Min: Rp 10.000</span>
                                    <span class="fw-bold text-primary">Max: Rp
                                        {{ number_format($this->maxAmount, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <hr class="my-4 border-dashed">

                            {{-- Fees Breakdown --}}
                            <h6 class="fw-bold mb-3 small text-uppercase">Rincian Potongan & Biaya</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <x-admin.input-rupiah model="ads_fee" label="Biaya Iklan (Ads)" placeholder="0" />
                                    <div class="form-text x-small text-muted mt-0">PPN 11% otomatis: Rp
                                        {{ number_format($this->adsVat, 0, ',', '.') }}</div>
                                </div>
                                <div class="col-md-6">
                                    <x-admin.input-rupiah model="merchant_fee" label="Biaya Merchant (Otomatis)"
                                        disabled placeholder="0" />
                                    <div class="form-text x-small text-muted mt-0">Total biaya payment gateway belum
                                        diklaim</div>
                                </div>
                                <div class="col-md-6">
                                    <x-admin.input-rupiah model="platform_fee" label="Fee Platform (5%)"
                                        placeholder="0" />
                                </div>
                                <div class="col-md-6">
                                    <x-admin.input-rupiah model="optimization_fee" label="Fee Optimasi (15%)"
                                        placeholder="0" />
                                </div>
                            </div>

                            {{-- Proof & Notes --}}
                            <div class="row g-3 mb-4">
                                <div class="col-md-12">
                                    <label class="form-label small fw-bold text-uppercase">Catatan (Opsional)</label>
                                    <textarea wire:model="notes" class="form-control" rows="3" placeholder="Catatan internal..."></textarea>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label small fw-bold text-uppercase">Bukti Transfer
                                        (Opsional)</label>
                                    <input type="file" wire:model="proof_image" class="form-control">
                                    @error('proof_image')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Sidebar Info --}}
                    <div class="col-lg-4">
                        <div class="bg-light p-4 rounded-4 h-100">
                            <h6 class="fw-bold mb-4 border-bottom pb-2">Ringkasan</h6>

                            @if ($this->selectedCampaign)
                                <div class="mb-4">
                                    <h6 class="text-uppercase text-muted x-small fw-bold mb-2">Campaign</h6>
                                    <div class="fw-bold mb-1">{{ $this->selectedCampaign->title }}</div>
                                    <div class="small text-muted mb-2">Created
                                        {{ $this->selectedCampaign->created_at->format('d M Y') }}</div>

                                    <div class="p-3 bg-white rounded-3 border mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="x-small text-muted">Terkumpul</span>
                                            <span class="x-small fw-bold">Rp
                                                {{ number_format($this->selectedCampaign->collected_amount, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="x-small text-muted">Bisa Dicairkan</span>
                                            <span class="x-small fw-bold text-success">Rp
                                                {{ number_format($this->maxAmount, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    @if ($this->selectedCampaign->is_optimized)
                                        <div
                                            class="alert alert-info d-flex align-items-center p-2 mb-0 border-0 bg-info bg-opacity-10 text-info x-small">
                                            <i class="bi bi-lightning-fill me-2"></i>
                                            <span class="fw-bold">Campaign Dioptimasi (Fee 15%)</span>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="mb-4">
                                <h6 class="text-uppercase text-muted x-small fw-bold mb-2">Rekening Tujuan</h6>
                                <div class="p-3 bg-white rounded-3 border text-center">
                                    <span class="badge bg-secondary mb-2">Pencairan Internal / Tunai</span>
                                    <p class="x-small text-muted mb-0">Dana dicairkan langsung oleh admin.</p>
                                </div>
                            </div>

                            {{-- Total Net --}}
                            <div class="p-3 rounded-3 mb-4 text-center"
                                style="background: linear-gradient(135deg, #10b981, #059669);">
                                <small class="text-white opacity-75 d-block text-uppercase fw-bold mb-1"
                                    style="font-size: 0.65rem; letter-spacing: 1px;">Dana Bersih Diterima</small>
                                <h3 class="fw-bold mb-0 text-white">Rp
                                    {{ number_format($this->netAmount, 0, ',', '.') }}</h3>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 border-top pt-4 mt-4">
                    <a href="{{ route('admin.pencairan') }}" class="btn btn-light border px-4 py-2 fw-semibold"
                        wire:navigate>
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary fw-bold px-4 py-2" wire:loading.attr="disabled"
                        wire:target="save">
                        <span wire:loading.remove wire:target="save">
                            <i class="bi bi-save me-1"></i> Simpan Pencairan
                        </span>
                        <span wire:loading wire:target="save">
                            <div class="spinner-border spinner-border-sm me-2" role="status"></div> Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <style>
        .x-small {
            font-size: 0.75rem;
        }

        .form-label {
            color: #555;
        }

        /* Validation Style for TomSelect */
        .is-invalid-tomselect .ts-control {
            border-color: #dc3545 !important;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5zM6 8.2h.01'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
    </style>
</div>
