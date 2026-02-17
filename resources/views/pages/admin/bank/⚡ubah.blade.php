<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\Bank;

new #[Layout('layouts.admin')] class extends Component {
    use WithFileUploads;

    public Bank $bank;
    public $logo;
    public string $bank_name = '';
    public string $bank_code = '';
    public string $account_number = '';
    public string $account_name = '';
    public string $type = 'manual';
    public string $method = 'manual';
    public bool $is_active = true;

    public function mount(Bank $bank): void
    {
        $this->bank = $bank;
        $this->bank_name = $bank->bank_name;
        $this->bank_code = $bank->bank_code ?? '';
        $this->account_number = $bank->account_number ?? '';
        $this->account_name = $bank->account_name ?? '';
        $this->type = $bank->type;
        $this->method = $bank->method ?? 'manual';
        $this->is_active = $bank->is_active;
    }

    public function update(): void
    {
        $this->validate([
            'bank_name' => 'required|string|max:255',
            'bank_code' => 'required|string|max:50',
            'account_number' => $this->type == 'manual' ? 'required|string|max:50' : 'nullable|string|max:50',
            'account_name' => $this->type == 'manual' ? 'required|string|max:255' : 'nullable|string|max:255',
            'method' => 'required|string',
            'logo' => 'nullable|image|max:1024',
        ]);

        $data = [
            'bank_name' => $this->bank_name,
            'bank_code' => $this->bank_code,
            'account_number' => $this->account_number,
            'account_name' => $this->account_name,
            'method' => $this->method,
            'is_active' => $this->is_active,
        ];

        if ($this->logo) {
            if ($this->bank->logo && file_exists(public_path('storage/' . $this->bank->logo))) {
                unlink(public_path('storage/' . $this->bank->logo));
            }
            $data['logo'] = $this->logo->store('banks', 'public');
        }

        $this->bank->update($data);

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Data bank berhasil diperbarui ✅',
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
                    <h5 class="fw-bold mb-1">Ubah Data Bank</h5>
                    <p class="text-muted small mb-0">Perbarui informasi bank atau channel pembayaran Anda.</p>
                </div>
                <a href="{{ route('admin.bank') }}" wire:navigate class="btn btn-light border">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <form wire:submit="update">
                <div class="row g-3">
                    <div class="col-md-6 text-center">
                        <x-admin.file-upload model="logo" label="Logo Bank" :preview="$logo ? $logo->temporaryUrl() : $bank->logo_url" />
                        <div class="form-text small mt-2">Biarkan kosong jika tidak ingin mengubah logo.</div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Bank / Channel</label>
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
                                            placeholder: 'Pilih Metode Pembayaran',
                                            allowEmptyOption: false,
                                            maxOptions: 50,
                                            onChange: (value) => {
                                                $wire.set('method', value);
                                            }
                                        });
                                        // Set initial value
                                        this.tom.setValue('{{ $this->method }}');
                                    }
                                }"
                                    class="form-select @error('method')
is-invalid
@enderror">
                                    <option value="manual">Transfer Manual</option>
                                    <option value="va">Virtual Account</option>
                                    <option value="ewallet">E-Wallet</option>
                                    <option value="qris">QRIS</option>
                                    <option value="retail">Retail (Alfamart/Indomaret)</option>
                                </select>
                            </div>
                            @error('method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Kode Bank / Channel</label>
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
                            <input class="form-check-input" type="checkbox" wire:model="is_active" id="is_active">
                            <label class="form-check-label fw-bold" for="is_active">Aktifkan Bank</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.bank') }}" class="btn btn-light border px-4 fw-semibold"
                        wire:navigate>Batal</a>
                    <button type="submit" class="btn btn-warning text-white fw-bold px-4" wire:loading.attr="disabled">
                        <span wire:loading.remove>Update Data Bank <i class="bi bi-floppy-fill ms-2"></i></span>
                        <span wire:loading>
                            <div class="spinner-border spinner-border-sm"></div>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <style>
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
