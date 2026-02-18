<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use App\Models\Bank;

new #[Layout('layouts.admin')] #[Title('Tambah Bank')] class extends Component {
    use WithFileUploads;

    public $logo;
    public string $bank_name = '';
    public string $bank_code = '';
    public string $account_number = '';
    public string $account_name = '';
    public string $type = 'manual';
    public string $method = 'manual';
    public bool $is_active = true;

    public function store(): void
    {
        $this->validate([
            'bank_name' => 'required|string|max:255',
            'bank_code' => 'nullable|string|max:50',
            'account_number' => 'required|string|max:50',
            'account_name' => 'required|string|max:255',
            'method' => 'required|string',
            'logo' => 'nullable|image|max:1024',
        ]);

        $logoPath = $this->logo ? $this->logo->store('banks', 'public') : null;

        Bank::create([
            'bank_name' => $this->bank_name,
            'bank_code' => $this->bank_code,
            'account_number' => $this->account_number,
            'account_name' => $this->account_name,
            'type' => 'manual',
            'method' => $this->method,
            'is_active' => $this->is_active,
            'logo' => $logoPath,
        ]);

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Bank berhasil ditambahkan ✅',
        ]);

        $this->redirectRoute('admin.bank', navigate: true);
    }
};
?>

<div>
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Tambah Bank Baru</h5>
                    <p class="text-muted small mb-0">Input rekening manual atau channel Tripay.</p>
                </div>
                <a href="{{ route('admin.bank') }}" wire:navigate class="btn btn-light border">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <form wire:submit="store">
                <div class="row g-3">
                    <div class="col-md-6 text-center">
                        <x-admin.file-upload model="logo" label="Logo Bank" :preview="$logo ? $logo->temporaryUrl() : null" />
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Bank</label>
                            <input type="text" class="form-control @error('bank_name') is-invalid @enderror"
                                wire:model="bank_name" placeholder="Contoh: Bank Central Asia">
                            @error('bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 @error('method') is-invalid-tomselect @enderror">
                            <label class="form-label fw-bold">Metode Pembayaran</label>
                            <div wire:ignore>
                                <select x-data="{
                                    tom: null,
                                    init() {
                                        this.tom = new TomSelect(this.$el, {
                                            placeholder: 'Pilih atau cari Metode Pembayaran...',
                                            allowEmptyOption: false,
                                            maxOptions: 50,
                                            onDropdownOpen: function() {
                                                this.clear(true);
                                            },
                                            onChange: (value) => {
                                                $wire.set('method', value);
                                            }
                                        });
                                    }
                                }"
                                    class="form-select @error('method')
is-invalid
@enderror">
                                    <option value="manual">Transfer Manual</option>
                                    <option value="va">Virtual Account</option>
                                    <option value="ewallet">E-Wallet</option>
                                    <option value="qris">QRIS</option>
                                    <option value="retail">Retail (Alfamaet/Indomaret)</option>
                                </select>
                            </div>
                            @error('method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Kode Bank / Channel (Opsional)</label>
                            <input type="text" class="form-control @error('bank_code') is-invalid @enderror"
                                wire:model="bank_code" placeholder="Contoh: 002 atau bca">
                            @error('bank_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor Rekening</label>
                            <input type="text" class="form-control @error('account_number') is-invalid @enderror"
                                wire:model="account_number" placeholder="Masukan nomor rekening">
                            @error('account_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Atas Nama</label>
                            <input type="text" class="form-control @error('account_name') is-invalid @enderror"
                                wire:model="account_name" placeholder="Masukan nama pemilik rekening">
                            @error('account_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" wire:model="is_active" id="is_active"
                                checked>
                            <label class="form-check-label fw-bold" for="is_active">Aktifkan Bank</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.bank') }}" class="btn btn-light border px-4 fw-semibold"
                        wire:navigate>Batal</a>
                    <button class="btn btn-primary text-white fw-bold px-4" wire:loading.attr="disabled"
                        wire:target="store">
                        <span wire:loading.remove wire:target="store">Simpan Bank <i
                                class="bi bi-floppy-fill ms-2"></i></span>
                        <span wire:loading wire:target="store">
                            <div class="spinner-border spinner-border-sm"></div> Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
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
