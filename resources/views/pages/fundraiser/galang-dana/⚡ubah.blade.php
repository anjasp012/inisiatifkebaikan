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
    public $campaign;
    public $categories;

    public $thumbnail;
    public string $title = '';
    public $category_id = '';
    public string $description = '';
    public $target_amount = '';
    public $start_date = '';
    public $end_date = '';

    public function mount(Campaign $campaign)
    {
        $this->fundraiser = Fundraiser::where('user_id', Auth::id())->first();

        if (!$this->fundraiser || $this->fundraiser->status !== 'approved') {
            session()->flash('error', 'Akses ditolak');
            $this->redirectRoute('fundraiser.dashboard', navigate: true);
            return;
        }

        // Check if campaign belongs to this fundraiser
        if ($campaign->fundraiser_id !== $this->fundraiser->id) {
            session()->flash('error', 'Campaign tidak ditemukan');
            $this->redirectRoute('fundraiser.galang-dana.index', navigate: true);
            return;
        }

        $this->campaign = $campaign;
        $this->categories = CampaignCategory::all();

        // Load existing data
        $this->title = $campaign->title;
        $this->category_id = $campaign->category_id;
        $this->description = $campaign->description;
        $this->target_amount = $campaign->target_amount;
        $this->start_date = $campaign->start_date->format('Y-m-d');
        $this->end_date = $campaign->end_date->format('Y-m-d');
    }

    public function update()
    {
        $rules = [
            'thumbnail' => 'nullable|image|max:2048',
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:campaign_categories,id',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:100000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ];

        $this->validate($rules);

        $data = [
            'title' => $this->title,
            'category_id' => $this->category_id,
            'description' => $this->description,
            'target_amount' => $this->target_amount,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];

        if ($this->thumbnail) {
            $thumbnailPath = $this->thumbnail->store('campaigns', 'public');
            $data['thumbnail'] = $thumbnailPath;
        }

        $this->campaign->update($data);

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Campaign berhasil diperbarui ✅',
        ]);

        $this->redirectRoute('fundraiser.galang-dana.index', navigate: true);
    }
}; ?>

<div>
    <x-app.navbar-secondary title="Edit Campaign" :route="route('fundraiser.galang-dana.kelola', $campaign->slug)" />

    <section class="fundraiser-ubah-page py-4">
        <div class="container-fluid">
            <form wire:submit="update">
                {{-- Thumbnail Update --}}
                <div class="mb-4">
                    <x-admin.file-upload model="thumbnail" label="Thumbnail Campaign" :preview="$thumbnail ? $thumbnail->temporaryUrl() : $campaign->thumbnail_url" />
                </div>

                {{-- Detail Information --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-body p-4">
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

                        <div class="mb-4">
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
                                            this.tom.setValue(@js($category_id));
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

                        <div class="mb-0">
                            <label class="form-label small fw-bold text-dark">Deskripsi & Cerita</label>
                            <x-admin.text-editor model="description" id="description" />
                            @error('description')
                                <span
                                    class="text-danger extra-small mt-2 d-block fw-medium text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Target & Time --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark mb-4 d-flex align-items-center">
                            <i class="bi bi-calendar-check-fill text-primary me-2"></i> Target & Durasi
                        </h6>

                        <div class="mb-4">
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
                    </div>
                </div>

                <div class="p-4 bg-white border-top shadow-sm rounded-4 mb-5">
                    <button type="submit" wire:loading.attr="disabled"
                        class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-soft border-0 text-white">
                        <span wire:loading.remove wire:target="update">Simpan Perubahan</span>
                        <span wire:loading wire:target="update">
                            <span class="spinner-border spinner-border-sm me-2"></span> Menyimpan...
                        </span>
                    </button>
                </div>
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
