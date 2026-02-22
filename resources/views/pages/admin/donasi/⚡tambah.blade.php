<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Donation;
use App\Models\Campaign;
use App\Models\PaymentProof;
use App\Models\Bank;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;

new #[Layout('layouts.admin')] #[Title('Tambah Donasi')] class extends Component {
    use WithFileUploads;

    public $campaign_id;
    public $donor_name;
    public $donor_phone;
    public $donor_email;
    public $amount;
    public $merchant_fee = 0;
    public $message;
    public $is_anonymous = false;
    public $bank_id;
    public $status = 'success';
    public $created_at;
    public $payment_proofs = []; // Array of uploaded files

    public function mount()
    {
        $this->created_at = date('Y-m-d H:i');
    }

    public function removeProof($index)
    {
        unset($this->payment_proofs[$index]);
        $this->payment_proofs = array_values($this->payment_proofs);
    }

    public function store()
    {
        $this->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'bank_id' => 'required|exists:banks,id',
            'status' => 'required|in:pending,success,failed',
            'donor_name' => 'required|string',
            'donor_phone' => 'nullable|string',
            'donor_email' => 'nullable|email',
            'amount' => 'required|numeric|min:1000',
            'merchant_fee' => 'nullable|numeric|min:0',
            'created_at' => 'required|date',
            'payment_proofs.*' => 'nullable|image|max:5120', // Max 5MB per image
        ]);

        $bank = Bank::find($this->bank_id);

        $donation = Donation::create([
            'transaction_id' => 'MAN-' . strtoupper(Str::random(8)),
            'campaign_id' => $this->campaign_id,
            'bank_id' => $this->bank_id,
            'donor_name' => $this->donor_name,
            'donor_phone' => $this->donor_phone,
            'donor_email' => $this->donor_email,
            'amount' => $this->amount,
            'merchant_fee' => $this->merchant_fee ?: 0,
            'message' => $this->message,
            'is_anonymous' => $this->is_anonymous,
            'status' => $this->status,
            'payment_method' => 'manual',
            'paid_at' => $this->created_at,
            'created_at' => $this->created_at,
        ]);

        // Save payment proofs if any
        if (!empty($this->payment_proofs)) {
            foreach ($this->payment_proofs as $index => $proof) {
                if ($proof) {
                    $path = $proof->store('payment-proofs', 'public');

                    PaymentProof::create([
                        'donation_id' => $donation->id,
                        'file_path' => $path,
                    ]);
                }
            }
        }

        // Update campaign collected amount if status is success
        if ($this->status === 'success') {
            $campaign = Campaign::find($this->campaign_id);
            $campaign->increment('collected_amount', $this->amount);
        }

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Donasi manual berhasil ditambahkan ✅',
        ]);
        return $this->redirect(route('admin.donasi'), navigate: true);
    }

    #[Computed]
    public function campaigns()
    {
        return Campaign::where('status', 'active')->get();
    }

    #[Computed]
    public function banks()
    {
        return Bank::where('is_active', true)->get();
    }
};
?>

<div>
    <div class="card card-dashboard border-0">
        <div class="card-body border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Input Donasi Manual</h5>
                    <p class="text-muted small mb-0">Catat donasi yang masuk melalui transfer manual atau offline.</p>
                </div>
                <a href="{{ route('admin.donasi') }}" wire:navigate class="btn btn-light border">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="store">
                <div class="row g-4">
                    <!-- Data Donasi -->
                    <div class="col-lg-7">
                        <div class="bg-light p-4 rounded-4 h-100 border-0">
                            <h6 class="fw-bold mb-4 border-bottom pb-2">Informasi Donasi</h6>

                            <div class="mb-3 @error('campaign_id') is-invalid-tomselect @enderror">
                                <label class="form-label small fw-bold text-uppercase">Program / Campaign</label>
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
                                        <option value="">-- Pilih Program --</option>
                                        @foreach ($this->campaigns as $c)
                                            <option value="{{ $c->id }}">{{ $c->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('campaign_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-uppercase">Nama Donatur</label>
                                <input type="text" wire:model="donor_name"
                                    class="form-control @error('donor_name') is-invalid @enderror"
                                    placeholder="Nama lengkap donatur">
                                @error('donor_name')
                                    <div class="invalid-feedback extra-small">{{ $message }}</div>
                                @enderror
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" wire:model="is_anonymous"
                                        id="anon">
                                    <label class="form-check-label small" for="anon">Sembunyikan nama (Sahabat
                                        Inisiat)</label>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-12">
                                    <div class="mb-3 @error('bank_id') is-invalid-tomselect @enderror">
                                        <label class="form-label small fw-bold text-uppercase">Bank Tujuan
                                            Transfer</label>
                                        <div wire:ignore>
                                            <select x-data="{
                                                tom: null,
                                                init() {
                                                    this.tom = new TomSelect(this.$el, {
                                                        placeholder: 'Pilih atau cari Bank...',
                                                        allowEmptyOption: false,
                                                        maxOptions: 50,
                                                        onDropdownOpen: function() {
                                                            this.clear(true);
                                                        },
                                                        onChange: (value) => {
                                                            $wire.set('bank_id', value || null);
                                                        }
                                                    });
                                                }
                                            }"
                                                class="form-select @error('bank_id')
is-invalid
@enderror">
                                                <option value="">-- Pilih Bank --</option>
                                                @foreach ($this->banks as $b)
                                                    <option value="{{ $b->id }}">{{ $b->bank_name }} -
                                                        {{ $b->account_number }} ({{ $b->account_name }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('bank_id')
                                            <div class="invalid-feedback text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase">No. WhatsApp</label>
                                    <input type="text" wire:model="donor_phone"
                                        class="form-control @error('donor_phone') is-invalid @enderror"
                                        placeholder="628xxx">
                                    @error('donor_phone')
                                        <div class="invalid-feedback extra-small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase">Email (Opsional)</label>
                                    <input type="email" wire:model="donor_email"
                                        class="form-control @error('donor_email') is-invalid @enderror"
                                        placeholder="email@contoh.com">
                                    @error('donor_email')
                                        <div class="invalid-feedback extra-small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <x-admin.input-rupiah model="amount" label="Nominal Donasi (Gross)"
                                        placeholder="0" />
                                </div>
                                <div class="col-md-6">
                                    <x-admin.input-rupiah model="merchant_fee" label="Merchant Fee (Biaya)"
                                        placeholder="0" />
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase">Status Pembayaran</label>
                                    <div class="@error('status') is-invalid-tomselect @enderror">
                                        <div wire:ignore>
                                            <select x-data="{
                                                tom: null,
                                                init() {
                                                    this.tom = new TomSelect(this.$el, {
                                                        placeholder: 'Pilih Status',
                                                        allowEmptyOption: false,
                                                        onDropdownOpen: function() {
                                                            this.clear(true);
                                                        },
                                                        onChange: (value) => {
                                                            $wire.set('status', value);
                                                        }
                                                    });
                                                }
                                            }"
                                                class="form-select @error('status')
is-invalid
@enderror">
                                                <option value="success">Sukses (Diterima)</option>
                                                <option value="pending">Pending (Belum Bayar)</option>
                                                <option value="failed">Gagal / Dibatalkan</option>
                                            </select>
                                        </div>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <x-admin.input-calendar model="created_at" label="Tanggal Donasi"
                                        enableTime="true" />
                                </div>
                            </div>

                            <div class="mb-0">
                                <label class="form-label small fw-bold text-uppercase">Pesan / Doa
                                    (Opsional)</label>
                                <textarea wire:model="message" class="form-control" rows="3" placeholder="Tulis doa atau pesan dari donatur..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Bukti Transfer -->
                    <div class="col-lg-5">
                        <div class="bg-light p-4 rounded-4 h-100 border-0">
                            <h6 class="fw-bold mb-4 border-bottom pb-2">Bukti Transfer</h6>

                            <div class="mb-4" x-data="{ isDropping: false }">
                                <label
                                    class="form-label small fw-bold d-flex justify-content-between align-items-center">
                                    <span>UPLOAD BUKTI TRANSFER (MULTIPLE)</span>
                                    <span class="badge bg-white text-muted border fw-normal">Max 5MB/file</span>
                                </label>

                                <div class="position-relative">
                                    <label for="payment_proofs"
                                        class="file-upload-dropzone w-100 d-flex flex-column align-items-center justify-content-center border-2 border-dashed rounded-4 p-4 transition-all"
                                        :class="isDropping ? 'border-primary bg-primary bg-opacity-10' :
                                            'border-light-subtle bg-white'"
                                        @dragover.prevent="isDropping = true" @dragleave.prevent="isDropping = false"
                                        @drop.prevent="isDropping = false" style="cursor: pointer; min-height: 120px;">

                                        <div class="upload-icon-circle mb-2 bg-light rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                            style="width: 45px; height: 45px;">
                                            <i class="bi bi-cloud-arrow-up fs-5 text-primary"></i>
                                        </div>
                                        <span class="fw-bold text-dark small">Klik atau Seret Gambar Ke Sini</span>
                                        <span class="text-muted extra-small mt-1">PNG, JPG atau WebP</span>

                                        <input type="file" id="payment_proofs" wire:model="payment_proofs"
                                            multiple class="position-absolute opacity-0 w-100 h-100 top-0 start-0"
                                            accept="image/*" style="cursor: pointer;">
                                    </label>

                                    <div wire:loading.flex wire:target="payment_proofs"
                                        class="position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-75 rounded-4 align-items-center justify-content-center z-3">
                                        <div class="text-center">
                                            <div class="spinner-border spinner-border-sm text-primary mb-2"
                                                role="status"></div>
                                            <div class="extra-small fw-bold text-primary">Mengunggah...</div>
                                        </div>
                                    </div>
                                </div>

                                @if ($payment_proofs)
                                    <div class="mt-4 row g-2">
                                        @foreach ($payment_proofs as $index => $proof)
                                            @if ($proof)
                                                <div class="col-2">
                                                    <div
                                                        class="position-relative ratio ratio-1x1 border rounded-3 overflow-hidden shadow-sm bg-white">
                                                        @if ($proof->isPreviewable())
                                                            <img src="{{ $proof->temporaryUrl() }}"
                                                                class="object-fit-cover w-100 h-100">
                                                        @endif

                                                        <button type="button"
                                                            wire:click="removeProof({{ $index }})"
                                                            class="position-absolute top-0 end-0 btn btn-danger btn-sm p-0 d-flex align-items-center justify-content-center m-1 shadow-sm rounded-circle"
                                                            style="width: 22px; height: 22px; z-index: 5; border: 2px solid white;">
                                                            <i class="bi bi-x fs-6 fw-bold"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            @error('payment_proofs.*')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.donasi') }}" wire:navigate class="btn btn-light px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-5 fw-bold" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="store">
                            <i class="bi bi-check-circle me-2"></i> Simpan Donasi
                        </span>
                        <span wire:loading wire:target="store">
                            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
