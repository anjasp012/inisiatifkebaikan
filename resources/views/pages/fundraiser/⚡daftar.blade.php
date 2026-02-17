<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Fundraiser;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.mobile')] class extends Component {
    use WithFileUploads;

    // Form fields
    public $foundation_name = '';
    public $legal_doc;
    public $notary_doc;
    public $tax_id;
    public $bank_name = '';
    public $bank_account_name = '';
    public $bank_account_number = '';
    public $office_address = '';
    public $office_image;
    public $logo_image;
    public $step = 1; // Multi-step form for mobile friendliness

    public function register()
    {
        // Check if user is logged in
        if (!Auth::check()) {
            session()->flash('error', 'Anda harus login terlebih dahulu');
            $this->redirectRoute('login', navigate: true);
            return;
        }

        // Check if user already has fundraiser
        $existingFundraiser = Fundraiser::where('user_id', Auth::id())->first();
        if ($existingFundraiser) {
            session()->flash('error', 'Anda sudah terdaftar sebagai fundraiser');
            $this->redirectRoute('fundraiser.dashboard', navigate: true);
            return;
        }

        $this->validate([
            'foundation_name' => 'required|string|max:255',
            'legal_doc' => 'required|file|mimes:pdf|max:2048',
            'notary_doc' => 'required|file|mimes:pdf|max:2048',
            'tax_id' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'bank_name' => 'required|string',
            'bank_account_name' => 'required|string',
            'bank_account_number' => 'required|string',
            'office_address' => 'required|string',
            'office_image' => 'required|image|max:2048',
            'logo_image' => 'required|image|max:2048',
        ]);

        // Upload files
        $legalPath = $this->legal_doc->store('fundraisers/documents', 'public');
        $notaryPath = $this->notary_doc->store('fundraisers/documents', 'public');
        $taxPath = $this->tax_id->store('fundraisers/documents', 'public');
        $officePath = $this->office_image->store('fundraisers/photos', 'public');
        $logoPath = $this->logo_image->store('fundraisers/logos', 'public');

        // Create fundraiser
        Fundraiser::create([
            'user_id' => Auth::id(),
            'foundation_name' => $this->foundation_name,
            'legal_doc' => $legalPath,
            'notary_doc' => $notaryPath,
            'tax_id' => $taxPath,
            'bank_name' => $this->bank_name,
            'bank_account_name' => $this->bank_account_name,
            'bank_account_number' => $this->bank_account_number,
            'office_address' => $this->office_address,
            'office_image' => $officePath,
            'logo_image' => $logoPath,
            'status' => 'pending',
        ]);

        session()->flash('success', 'Pendaftaran berhasil! Menunggu verifikasi admin.');
        $this->redirectRoute('fundraiser.dashboard', navigate: true);
    }
}; ?>

<div class="d-flex flex-column min-vh-100 bg-white">
    <div class="p-3 border-bottom d-flex align-items-center gap-2">
        <a href="{{ route('home') }}" class="btn btn-light btn-sm rounded-circle" wire:navigate>
            <i class="bi bi-arrow-left"></i>
        </a>
        <h6 class="fw-bold mb-0">Daftar Fundraiser</h6>
    </div>

    <div class="p-4 flex-grow-1">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="mb-3" style="height: 40px;">
            <h5 class="fw-bold">Bergabung Bersama Kami</h5>
            <p class="text-muted small">Wujudkan lebih banyak kebaikan.</p>
        </div>

        @if (session('error'))
            <div class="alert alert-danger rounded-4 small">{{ session('error') }}</div>
        @endif

        <form wire:submit="register">
            <div class="mb-3">
                <label class="form-label small fw-bold text-uppercase">Informasi Yayasan</label>
                <input type="text" class="form-control bg-light border-0 rounded-4 py-3 mb-2"
                    wire:model="foundation_name" placeholder="Nama Yayasan">
                @error('foundation_name')
                    <span class="text-danger x-small">{{ $message }}</span>
                @enderror

                <textarea class="form-control bg-light border-0 rounded-4 py-3 mb-2" wire:model="office_address" rows="2"
                    placeholder="Alamat Kantor"></textarea>
                @error('office_address')
                    <span class="text-danger x-small">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-uppercase">Dokumen & Foto</label>
                <div class="small text-muted mb-2">Upload dokumen legalitas dan foto kantor.</div>

                <div class="d-grid gap-2">
                    <div class="p-3 bg-light rounded-4 border border-dashed text-center position-relative">
                        <i class="bi bi-camera fs-3 text-muted"></i>
                        <div class="small text-muted">Logo Yayasan</div>
                        <input type="file" wire:model="logo_image"
                            class="position-absolute w-100 h-100 top-0 start-0 opacity-0" accept="image/*">
                        @if ($logo_image)
                            <div class="badge bg-success position-absolute top-0 end-0 m-2">Uploaded</div>
                        @endif
                    </div>
                    <div class="p-3 bg-light rounded-4 border border-dashed text-center position-relative">
                        <i class="bi bi-building fs-3 text-muted"></i>
                        <div class="small text-muted">Foto Kantor</div>
                        <input type="file" wire:model="office_image"
                            class="position-absolute w-100 h-100 top-0 start-0 opacity-0" accept="image/*">
                    </div>
                </div>
                @error('logo_image')
                    <span class="text-danger x-small d-block">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold text-uppercase">Dokumen Legal (PDF)</label>
                <input type="file" class="form-control form-control-sm mb-2" wire:model="legal_doc" accept=".pdf">
                <input type="file" class="form-control form-control-sm mb-2" wire:model="notary_doc" accept=".pdf">
                <input type="file" class="form-control form-control-sm mb-2" wire:model="tax_id"
                    accept=".pdf,image/*">
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-uppercase">Informasi Bank</label>
                <select class="form-select bg-light border-0 rounded-4 py-3 mb-2" wire:model="bank_name">
                    <option value="">Pilih Bank</option>
                    <option value="BCA">BCA</option>
                    <option value="Mandiri">Mandiri</option>
                    <option value="BNI">BNI</option>
                    <option value="BRI">BRI</option>
                    <option value="BSI">BSI</option>
                </select>
                <input type="text" class="form-control bg-light border-0 rounded-4 py-3 mb-2"
                    wire:model="bank_account_number" placeholder="Nomor Rekening">
                <input type="text" class="form-control bg-light border-0 rounded-4 py-3"
                    wire:model="bank_account_name" placeholder="Atas Nama Rekening">
            </div>

            <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm mb-5">
                <span wire:loading.remove>Daftar Sekarang</span>
                <span wire:loading>Memproses...</span>
            </button>
        </form>
    </div>
</div>
