<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Fundraiser;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.app')] class extends Component {
    use WithFileUploads;

    // Form fields
    public $foundation_name = '';
    public $about = '';
    public $legal_doc;
    public $notary_doc;
    public $tax_id;
    public $bank_name = '';
    public $bank_account_name = '';
    public $bank_account_number = '';
    public $office_address = '';
    public $office_image;
    public $logo_image;
    public $step = 1;

    public function mount()
    {
        if (!Auth::check()) {
            return $this->redirectRoute('login', navigate: true);
        }

        $existingFundraiser = Fundraiser::where('user_id', Auth::id())->first();
        if ($existingFundraiser) {
            return $this->redirectRoute('fundraiser.dashboard', navigate: true);
        }
    }

    public function nextStep()
    {
        if ($this->step == 1) {
            $this->validate([
                'foundation_name' => 'required|string|max:255',
                'about' => 'required|string|min:50',
                'office_address' => 'required|string',
                'logo_image' => 'required|image|max:2048',
                'office_image' => 'required|image|max:2048',
            ]);
        } elseif ($this->step == 2) {
            $this->validate([
                'legal_doc' => 'required|file|mimes:pdf|max:2048',
                'notary_doc' => 'required|file|mimes:pdf|max:2048',
                'tax_id' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);
        }

        $this->step++;
    }

    public function prevStep()
    {
        $this->step--;
    }

    public function register()
    {
        $this->validate([
            'bank_name' => 'required|string',
            'bank_account_name' => 'required|string',
            'bank_account_number' => 'required|string',
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
            'slug' => \Illuminate\Support\Str::slug($this->foundation_name) . '-' . rand(100, 999),
            'about' => $this->about,
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

        session()->flash('success', 'Pendaftaran berhasil! Akun Anda sedang dalam proses verifikasi oleh tim kami.');
        return $this->redirectRoute('fundraiser.dashboard', navigate: true);
    }
}; ?>

<div>
    <x-app.navbar-secondary :title="$step == 1 ? 'Daftar Mitra' : ($step == 2 ? 'Dokumen Legal' : 'Informasi Bank')" :route="$step > 1 ? '#' : route('home')" />

    {{-- Progress Bar --}}
    <div class="bg-white px-4 py-3 border-bottom shadow-micro">
        <div class="d-flex justify-content-between mb-2">
            <span
                class="extra-small fw-bold text-uppercase {{ $step >= 1 ? 'text-primary' : 'text-muted opacity-50' }}">Yayasan</span>
            <span
                class="extra-small fw-bold text-uppercase {{ $step >= 2 ? 'text-primary' : 'text-muted opacity-50' }}">Dokumen</span>
            <span
                class="extra-small fw-bold text-uppercase {{ $step >= 3 ? 'text-primary' : 'text-muted opacity-50' }}">Rekening</span>
        </div>
        <div class="progress rounded-pill bg-light" style="height: 6px;">
            <div class="progress-bar bg-primary rounded-pill transition-300" role="progressbar"
                style="width: {{ $step == 1 ? '33%' : ($step == 2 ? '66%' : '100%') }}"></div>
        </div>
    </div>

    <section class="fundraiser-daftar-page py-4">
        <div class="container-fluid">
            @if ($step == 1)
                {{-- Step 1: Informasi Yayasan --}}
                <div class="animate__animated animate__fadeIn">
                    <div class="mb-4">
                        <h5 class="fw-bold mb-1">Informasi Yayasan</h5>
                        <p class="text-muted small">Lengkapi profil yayasan untuk identitas galang dana.</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Nama Yayasan</label>
                        <input type="text" class="form-control" wire:model="foundation_name"
                            placeholder="Contoh: Yayasan Peduli Sesama">
                        @error('foundation_name')
                            <span class="text-danger extra-small mt-1 d-block fw-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Alamat Kantor</label>
                        <textarea class="form-control" wire:model="office_address" rows="2" placeholder="Alamat lengkap kantor pusat"></textarea>
                        @error('office_address')
                            <span class="text-danger extra-small mt-1 d-block fw-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Tentang Penggalang</label>
                        <textarea class="form-control" wire:model="about" rows="4"
                            placeholder="Ceritakan profil singkat yayasan/organisasi Anda..."></textarea>
                        <div class="extra-small text-muted mt-1">Minimal 50 karakter.</div>
                        @error('about')
                            <span class="text-danger extra-small mt-1 d-block fw-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-dark">Logo Yayasan</label>
                            <div class="upload-minimal bg-white shadow-sm rounded-3 border-2 border-dashed d-flex flex-column align-items-center justify-content-center p-3 text-center position-relative"
                                style="height: 120px;">
                                @if ($logo_image)
                                    <img src="{{ $logo_image->temporaryUrl() }}"
                                        class="w-100 h-100 object-fit-contain p-1">
                                @else
                                    <i class="bi bi-camera text-primary fs-3 mb-1"></i>
                                    <span class="extra-small text-muted fw-bold">Upload Logo</span>
                                @endif
                                <input type="file" wire:model="logo_image"
                                    class="position-absolute w-100 h-100 top-0 start-0 opacity-0 cursor-pointer"
                                    accept="image/*">
                            </div>
                            @error('logo_image')
                                <span class="text-danger extra-small mt-1 d-block fw-medium">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-dark">Foto Kantor</label>
                            <div class="upload-minimal bg-white shadow-sm rounded-3 border-2 border-dashed d-flex flex-column align-items-center justify-content-center p-3 text-center position-relative"
                                style="height: 120px;">
                                @if ($office_image)
                                    <img src="{{ $office_image->temporaryUrl() }}"
                                        class="w-100 h-100 object-fit-cover rounded-2">
                                @else
                                    <i class="bi bi-building text-primary fs-3 mb-1"></i>
                                    <span class="extra-small text-muted fw-bold">Upload Foto</span>
                                @endif
                                <input type="file" wire:model="office_image"
                                    class="position-absolute w-100 h-100 top-0 start-0 opacity-0 cursor-pointer"
                                    accept="image/*">
                            </div>
                            @error('office_image')
                                <span class="text-danger extra-small mt-1 d-block fw-medium">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <button wire:click="nextStep"
                        class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-soft mt-3">
                        Lanjut ke Dokumen <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
            @elseif ($step == 2)
                {{-- Step 2: Dokumen Legal --}}
                <div class="animate__animated animate__fadeIn">
                    <div class="mb-4">
                        <h5 class="fw-bold mb-1 text-dark">Dokumen Legalitas</h5>
                        <p class="text-muted small">Pastikan dokumen masih berlaku dan terbaca jelas.</p>
                    </div>

                    <div class="card border-0 shadow-sm rounded-3 mb-3 overflow-hidden">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 d-flex align-items-center justify-content-center shadow-micro"
                                    style="width: 44px; height: 44px;">
                                    <i class="bi bi-file-earmark-pdf fs-5"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="fw-bold small mb-0 text-dark">SK Kemenkumham</h6>
                                    <p class="text-muted extra-small mb-0 text-truncate">
                                        {{ $legal_doc ? $legal_doc->getClientOriginalName() : 'Belum upload file (PDF)' }}
                                    </p>
                                </div>
                                <label
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3 position-relative extra-small fw-bold">
                                    {{ $legal_doc ? 'Ubah' : 'Upload' }}
                                    <input type="file" wire:model="legal_doc"
                                        class="position-absolute w-100 h-100 top-0 start-0 opacity-0 cursor-pointer"
                                        accept=".pdf">
                                </label>
                            </div>
                            @error('legal_doc')
                                <span class="text-danger extra-small mt-1 d-block fw-medium">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-3 mb-3 overflow-hidden">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 d-flex align-items-center justify-content-center shadow-micro"
                                    style="width: 44px; height: 44px;">
                                    <i class="bi bi-file-earmark-text fs-5"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="fw-bold small mb-0 text-dark">Akta Notaris</h6>
                                    <p class="text-muted extra-small mb-0 text-truncate">
                                        {{ $notary_doc ? $notary_doc->getClientOriginalName() : 'Belum upload file (PDF)' }}
                                    </p>
                                </div>
                                <label
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3 position-relative extra-small fw-bold">
                                    {{ $notary_doc ? 'Ubah' : 'Upload' }}
                                    <input type="file" wire:model="notary_doc"
                                        class="position-absolute w-100 h-100 top-0 start-0 opacity-0 cursor-pointer"
                                        accept=".pdf">
                                </label>
                            </div>
                            @error('notary_doc')
                                <span class="text-danger extra-small mt-1 d-block fw-medium">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 d-flex align-items-center justify-content-center shadow-micro"
                                    style="width: 44px; height: 44px;">
                                    <i class="bi bi-credit-card-2-front fs-5"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="fw-bold small mb-0 text-dark">NPWP Yayasan</h6>
                                    <p class="text-muted extra-small mb-0 text-truncate">
                                        {{ $tax_id ? $tax_id->getClientOriginalName() : 'Belum upload file (PDF/Image)' }}
                                    </p>
                                </div>
                                <label
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3 position-relative extra-small fw-bold">
                                    {{ $tax_id ? 'Ubah' : 'Upload' }}
                                    <input type="file" wire:model="tax_id"
                                        class="position-absolute w-100 h-100 top-0 start-0 opacity-0 cursor-pointer"
                                        accept=".pdf,image/*">
                                </label>
                            </div>
                            @error('tax_id')
                                <span class="text-danger extra-small mt-1 d-block fw-medium">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button wire:click="prevStep"
                            class="btn btn-light rounded-pill py-3 px-4 fw-bold border">Kembali</button>
                        <button wire:click="nextStep"
                            class="btn btn-primary flex-grow-1 rounded-pill py-3 fw-bold shadow-soft">
                            Lanjut ke Rekening <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            @elseif ($step == 3)
                {{-- Step 3: Informasi Bank --}}
                <div class="animate__animated animate__fadeIn">
                    <div class="mb-4">
                        <h5 class="fw-bold mb-1 text-dark">Rekening Bank</h5>
                        <p class="text-muted small">Rekening yang digunakan untuk pencairan dana donasi.</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Nama Bank</label>
                        <div class="position-relative">
                            <select class="form-select" wire:model="bank_name">
                                <option value="">Pilih Bank</option>
                                @foreach (['BCA', 'Mandiri', 'BNI', 'BRI', 'BSI', 'Muamalat', 'CIMB Niaga'] as $bank)
                                    <option value="{{ $bank }}">{{ $bank }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('bank_name')
                            <span class="text-danger extra-small mt-1 d-block fw-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Nomor Rekening</label>
                        <input type="number" class="form-control" wire:model="bank_account_number"
                            placeholder="Contoh: 000123456789">
                        @error('bank_account_number')
                            <span class="text-danger extra-small mt-1 d-block fw-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">Nama Pemilik Rekening</label>
                        <input type="text" class="form-control" wire:model="bank_account_name"
                            placeholder="Sesuai buku tabungan">
                        @error('bank_account_name')
                            <span class="text-danger extra-small mt-1 d-block fw-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="alert alert-warning rounded-3 p-3 border-0 bg-warning bg-opacity-10 d-flex gap-3 mb-5">
                        <i class="bi bi-exclamation-triangle-fill text-warning fs-5"></i>
                        <small class="text-dark fw-medium x-small">Pastikan data rekening benar agar tidak menghambat
                            proses verifikasi dan pencairan dana donasi.</small>
                    </div>

                    <div class="fixed-bottom p-4 bg-white border-top detail-cta h-auto">
                        <div class="w-100 d-flex gap-2">
                            <button wire:click="prevStep"
                                class="btn btn-light rounded-pill py-3 px-4 fw-bold border shadow-none"
                                wire:loading.attr="disabled">
                                <i class="bi bi-arrow-left"></i>
                            </button>
                            <button wire:click="register" wire:loading.attr="disabled"
                                class="btn btn-success flex-grow-1 rounded-pill py-3 fw-bold shadow-soft border-0">
                                <span wire:loading.remove wire:target="register">Kirim Pendaftaran Mitra</span>
                                <span wire:loading wire:target="register">Memproses...</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .transition-all {
        transition: all 0.3s ease;
    }

    /* Animation for smooth transitions */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate__fadeIn {
        animation: fadeIn 0.4s ease-out;
    }

    input:focus,
    select:focus,
    textarea:focus {
        box-shadow: 0 0 0 4px rgba(var(--bs-primary-rgb), 0.05) !important;
        background-color: #fff !important;
    }

    .detail-cta {
        max-width: 480px;
        left: 50% !important;
        transform: translateX(-50%) !important;
    }
</style>
