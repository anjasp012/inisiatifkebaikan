<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Campaign;
use App\Models\Withdrawal;
use App\Models\Donation;

new #[Layout('layouts.admin')] #[Title('Tambah Pencairan')] class extends Component {
    use WithFileUploads;

    public $campaign_id = '';
    public $amount = '';
    public $ads_fee = 0;
    public $platform_fee = 0;
    public $optimization_fee = 0;
    public $merchant_fee = 0;
    public $notes = '';
    public $proof_image;

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['campaign_id', 'amount', 'ads_fee'])) {
            if ($propertyName === 'campaign_id') {
                $this->amount = $this->maxAmount();
            }
            $this->calculateFees();
        }
    }

    public function calculateFees()
    {
        $amountVal = (float) ($this->amount ?: 0);

        if ($amountVal > 0) {
            $this->platform_fee = $amountVal * 0.05;

            if ($this->selectedCampaign() && $this->selectedCampaign()->is_optimized) {
                $this->optimization_fee = $amountVal * 0.15;
            } else {
                $this->optimization_fee = 0;
            }

            // Also update merchant fee
            $this->calculateMerchantFee();
        } else {
            $this->platform_fee = 0;
            $this->optimization_fee = 0;
            $this->merchant_fee = 0;
        }
    }

    public function calculateMerchantFee()
    {
        if (!$this->campaign_id || !$this->selectedCampaign() || $this->selectedCampaign()->collected_amount <= 0) {
            $this->merchant_fee = 0;
            return;
        }

        // Logic: Sum all merchant fees from successful donations
        $totalMerchantFeesIncurred = Donation::where('campaign_id', $this->campaign_id)->where('status', 'success')->sum('merchant_fee');

        // Subtract merchant fees already accounted for in previous APPROVED withdrawals
        $previousMerchantFees = Withdrawal::where('campaign_id', $this->campaign_id)->where('status', 'success')->sum('merchant_fee');

        $unclaimedFees = max(0, $totalMerchantFeesIncurred - $previousMerchantFees);

        if ($this->amount && $this->maxAmount() > 0) {
            // Proportional to the amount being withdrawn relative to available balance (maxAmount)
            $ratio = min(1, (float) $this->amount / (float) $this->maxAmount());
            $this->merchant_fee = round($unclaimedFees * $ratio);
        } else {
            $this->merchant_fee = 0;
        }
    }

    public function selectedCampaign()
    {
        return $this->campaign_id ? Campaign::find($this->campaign_id) : null;
    }

    public function maxAmount()
    {
        if (!$this->selectedCampaign()) {
            return 0;
        }

        $alreadyWithdrawn = Withdrawal::where('campaign_id', $this->campaign_id)->where('status', '!=', 'rejected')->sum('amount');

        return max(0, $this->selectedCampaign()->collected_amount - $alreadyWithdrawn);
    }

    public function netAmount()
    {
        $amount = (float) ($this->amount ?: 0);
        $ads = (float) ($this->ads_fee ?: 0);
        $ads_vat = $ads * 0.11;

        $platform = (float) ($this->platform_fee ?: 0);
        $optimization = (float) ($this->optimization_fee ?: 0);
        $merchant = (float) ($this->merchant_fee ?: 0);

        return max(0, $amount - ($ads + $ads_vat + $platform + $optimization + $merchant));
    }

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
            'ads_vat' => $this->adsVat(),
            'platform_fee' => $this->platform_fee ?: 0,
            'optimization_fee' => $this->optimization_fee ?: 0,
            'merchant_fee' => $this->merchant_fee ?: 0,
            'net_amount' => $this->netAmount(),
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
                        <div class="bg-light p-4 rounded-4 h-100 border-0">
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
                                        class="form-select bg-white @error('campaign_id')
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
                                <div class="d-flex justify-content-between extra-small text-muted mt-1 px-1">
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
                                    <div class="form-text extra-small text-muted mt-0">PPN 11% otomatis: Rp
                                        {{ number_format($this->adsVat, 0, ',', '.') }}</div>
                                </div>
                                <div class="col-md-6">
                                    <x-admin.input-rupiah model="merchant_fee" label="Biaya Merchant (Otomatis)"
                                        disabled placeholder="0" />
                                    <div class="form-text extra-small text-muted mt-0">Total biaya payment gateway belum
                                        diklaim</div>
                                </div>
                                <div class="col-md-6">
                                    <x-admin.input-rupiah model="platform_fee" label="Fee Platform (5%)" placeholder="0"
                                        readonly />
                                </div>
                                <div class="col-md-6">
                                    <x-admin.input-rupiah model="optimization_fee" label="Fee Optimasi (15%)"
                                        placeholder="0" readonly />
                                </div>
                            </div>

                            {{-- Proof & Notes --}}
                            <div class="row g-3 mb-4">
                                <div class="col-md-12">
                                    <label class="form-label small fw-bold text-uppercase">Catatan (Opsional)</label>
                                    <textarea wire:model="notes" class="form-control" rows="3" placeholder="Catatan internal..."></textarea>
                                </div>
                                <div class="col-md-12">
                                    <x-admin.file-upload model="proof_image" label="Bukti Transfer (Opsional)"
                                        :preview="$this->proof_image ? $this->proof_image->temporaryUrl() : null" />
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Sidebar Info --}}
                    <div class="col-lg-4">
                        <div class="bg-light p-4 rounded-4 h-100 border-0">
                            <h6 class="fw-bold mb-4 border-bottom pb-2">Ringkasan</h6>

                            @if ($this->selectedCampaign())
                                <div class="mb-4">
                                    <h6 class="text-uppercase text-muted extra-small fw-bold mb-2">Campaign</h6>
                                    <div class="fw-bold mb-1">{{ $this->selectedCampaign()->title }}</div>
                                    <div class="small text-muted mb-2">Created
                                        {{ $this->selectedCampaign()->created_at->format('d M Y') }}</div>

                                    <div class="p-3 bg-white rounded-3 border mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="extra-small text-muted">Terkumpul</span>
                                            <span class="extra-small fw-bold">Rp
                                                {{ number_format($this->selectedCampaign()->collected_amount, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="extra-small text-muted">Bisa Dicairkan</span>
                                            <span class="extra-small fw-bold text-success">Rp
                                                {{ number_format($this->maxAmount(), 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    @if ($this->selectedCampaign()->is_optimized)
                                        <div
                                            class="alert alert-info d-flex align-items-center p-2 mb-0 border-0 bg-info bg-opacity-10 text-info extra-small">
                                            <i class="bi bi-lightning-fill me-2"></i>
                                            <span class="fw-bold">Campaign Dioptimasi (Fee 15%)</span>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="mb-4">
                                <h6 class="text-uppercase text-muted extra-small fw-bold mb-2">Rekening Tujuan</h6>
                                <div class="p-3 bg-white rounded-3 border text-center">
                                    <span class="badge bg-secondary mb-2">Pencairan Internal / Tunai</span>
                                    <p class="extra-small text-muted mb-0">Dana dicairkan langsung oleh admin.</p>
                                </div>
                            </div>

                            {{-- Total Net --}}
                            <div class="p-3 rounded-3 mb-4 text-center border-0"
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
</div>
