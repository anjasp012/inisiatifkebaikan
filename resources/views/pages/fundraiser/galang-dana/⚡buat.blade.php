<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Fundraiser;
use App\Models\Campaign;
use App\Models\CampaignCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.app')] class extends Component {
    use WithFileUploads;

    public $fundraiser;
    public $categories;

    public $thumbnail;
    public string $title = '';
    public $category_id = '';
    public string $description = '';
    public $target_amount = '';
    public $start_date = '';
    public $end_date = '';

    public function mount()
    {
        $this->fundraiser = Fundraiser::where('user_id', Auth::id())->first();

        if (!$this->fundraiser || $this->fundraiser->status !== 'approved') {
            session()->flash('error', 'Akses ditolak');
            $this->redirectRoute('fundraiser.dashboard', navigate: true);
            return;
        }

        $this->categories = CampaignCategory::all();
        $this->start_date = now()->format('Y-m-d');
        $this->end_date = now()->addMonths(3)->format('Y-m-d');
    }

    public function store()
    {
        $rules = [
            'thumbnail' => 'required|image|max:2048',
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:campaign_categories,id',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:100000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ];

        $this->validate($rules);

        $thumbnailPath = $this->thumbnail->store('campaigns', 'public');

        Campaign::create([
            'thumbnail' => $thumbnailPath,
            'title' => $this->title,
            'slug' => Str::slug($this->title) . '-' . Str::random(6),
            'category_id' => $this->category_id,
            'description' => $this->description,
            'target_amount' => $this->target_amount,
            'collected_amount' => 0,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => 'pending', // Usually pending first for admin review
            'fundraiser_id' => $this->fundraiser->id,
            'user_id' => Auth::id(),
        ]);

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Campaign berhasil dibuat! Menunggu verifikasi admin.',
        ]);

        $this->redirectRoute('fundraiser.galang-dana.index', navigate: true);
    }
}; ?>

<div>
    <x-app.navbar-secondary title="Buat Campaign Baru" :route="route('fundraiser.galang-dana.index')" />

    <section class="fundraiser-buat-page">
        <div class="container-fluid">
            <form wire:submit="store">
                {{-- Thumbnail Upload --}}
                <div class="mb-4">
                    <x-admin.file-upload model="thumbnail" label="Thumbnail Campaign" :preview="$thumbnail ? $thumbnail->temporaryUrl() : null" />
                </div>

                {{-- Detail Information --}}
                <h6 class="fw-bold text-dark mb-4 d-flex align-items-center">
                    <i class="bi bi-info-circle-fill text-primary me-2"></i> Informasi Campaign
                </h6>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark">Judul Campaign</label>
                    <input type="text"
                        class="form-control bg-light border-0 rounded-3 py-3 @error('title') is-invalid @enderror"
                        wire:model="title" placeholder="Contoh: Bantu Adik Syifa Sembuh">
                    @error('title')
                        <span class="text-danger extra-small mt-1 d-block fw-medium">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label small fw-bold text-dark">Kategori</label>
                    <div class="@error('category_id') is-invalid-tomselect @enderror shadow-none">
                        <div wire:ignore>
                            <select class="form-select" id="category_id" x-data="{
                                tom: null,
                                init() {
                                    this.tom = new TomSelect(this.$el, {
                                        placeholder: 'Pilih Kategori...',
                                        allowEmptyOption: false,
                                        onDropdownOpen: function() {
                                            this.clear(true);
                                        },
                                        onChange: (value) => {
                                            $wire.set('category_id', value || null);
                                        }
                                    });
                                }
                            }">
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @error('category_id')
                        <span class="text-danger extra-small mt-1 d-block fw-medium">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark">Deskripsi & Cerita</label>
                    <x-admin.text-editor model="description" id="description" />
                    @error('description')
                        <span class="text-danger extra-small mt-2 d-block fw-medium text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <x-admin.input-rupiah model="target_amount" label="Target Donasi"
                        labelClass="form-label small fw-bold text-dark" placeholder="Masukan target donasi" />
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <x-admin.input-calendar model="start_date" label="Tanggal Mulai" />
                    </div>
                    <div class="col-6">
                        <x-admin.input-calendar model="end_date" label="Tanggal Selesai" />
                    </div>
                </div>

                <button type="submit" wire:loading.attr="disabled"
                    class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-soft border-0 text-white">
                    <span wire:loading.remove wire:target="store">Simpan Campaign</span>
                    <span wire:loading wire:target="store">
                        <span class="spinner-border spinner-border-sm me-2"></span> Menyimpan...
                    </span>
                </button>
            </form>
        </div>
    </section>
</div>

<style>
    .upload-minimal:hover {
        border-color: var(--bs-primary) !important;
        background-color: #f0f7ff !important;
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
