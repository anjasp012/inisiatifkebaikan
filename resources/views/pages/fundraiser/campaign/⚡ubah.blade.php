<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Fundraiser;
use App\Models\Campaign;
use App\Models\CampaignCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.mobile')] class extends Component {
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
            $this->redirectRoute('fundraiser.campaign', navigate: true);
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

        if ($this->thumbnail) {
            // Delete old thumbnail
            if ($this->campaign->thumbnail && file_exists(public_path('storage/' . $this->campaign->thumbnail))) {
                unlink(public_path('storage/' . $this->campaign->thumbnail));
            }
            $thumbnailPath = $this->thumbnail->store('campaigns', 'public');
        } else {
            $thumbnailPath = $this->campaign->thumbnail;
        }

        $this->campaign->update([
            'thumbnail' => $thumbnailPath,
            'title' => $this->title,
            'slug' => Str::slug($this->title) . '-' . Str::random(6),
            'category_id' => $this->category_id,
            'description' => $this->description,
            'target_amount' => $this->target_amount,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Campaign berhasil diperbarui ✅',
        ]);

        $this->redirectRoute('fundraiser.campaign', navigate: true);
    }
}; ?>

<div class="d-flex flex-column min-vh-100 bg-white">
    <div class="p-3 border-bottom d-flex align-items-center gap-2 sticky-top bg-white z-2">
        <a href="{{ route('fundraiser.campaign') }}" class="btn btn-light btn-sm rounded-circle" wire:navigate>
            <i class="bi bi-arrow-left"></i>
        </a>
        <h6 class="fw-bold mb-0">Edit Campaign</h6>
    </div>

    <form wire:submit="update" class="p-4 safe-area-bottom">

        {{-- Thumbnail Upload --}}
        <div class="mb-4 text-center">
            <div class="position-relative rounded-4 overflow-hidden border border-dashed bg-light d-flex align-items-center justify-content-center mx-auto"
                style="height: 180px; width: 100%;">
                @if ($thumbnail)
                    <img src="{{ $thumbnail->temporaryUrl() }}" class="w-100 h-100 object-fit-cover">
                @elseif($campaign->thumbnail_url)
                    <img src="{{ $campaign->thumbnail_url }}" class="w-100 h-100 object-fit-cover">
                @else
                    <div class="text-muted">
                        <i class="bi bi-image fs-1 mb-2"></i>
                        <p class="small mb-0">Upload Thumbnail</p>
                    </div>
                @endif
                <input type="file" wire:model="thumbnail"
                    class="position-absolute w-100 h-100 top-0 start-0 opacity-0 cursor-pointer" accept="image/*">
            </div>
            @error('thumbnail')
                <span class="text-danger x-small mt-1 d-block">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold text-uppercase text-muted">Detail Campaign</label>
            <input type="text" class="form-control bg-light border-0 rounded-4 py-3 mb-2" wire:model="title"
                placeholder="Judul Campaign">
            @error('title')
                <span class="text-danger x-small">{{ $message }}</span>
            @enderror

            <select class="form-select bg-light border-0 rounded-4 py-3 mb-2" wire:model="category_id">
                <option value="">Pilih Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')
                <span class="text-danger x-small">{{ $message }}</span>
            @enderror

            <textarea class="form-control bg-light border-0 rounded-4 py-3 mb-2" wire:model="description" rows="5"
                placeholder="Ceritakan kisah & tujuan campaign ini..."></textarea>
            @error('description')
                <span class="text-danger x-small">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label small fw-bold text-uppercase text-muted">Target & Waktu</label>
            <div class="input-group mb-2">
                <span class="input-group-text bg-light border-0 rounded-start-4 ps-3">Rp</span>
                <input type="number" class="form-control bg-light border-0 rounded-end-4 py-3"
                    wire:model="target_amount" placeholder="Target Donasi">
            </div>
            @error('target_amount')
                <span class="text-danger x-small mb-2 d-block">{{ $message }}</span>
            @enderror

            <div class="row g-2">
                <div class="col-6">
                    <label class="x-small text-muted mb-1">Mulai</label>
                    <input type="date" class="form-control bg-light border-0 rounded-4 py-3" wire:model="start_date">
                    @error('start_date')
                        <span class="text-danger x-small">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-6">
                    <label class="x-small text-muted mb-1">Selesai</label>
                    <input type="date" class="form-control bg-light border-0 rounded-4 py-3" wire:model="end_date">
                    @error('end_date')
                        <span class="text-danger x-small">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm mb-5">
            <span wire:loading.remove wire:target="update">
                Simpan Perubahan
            </span>
            <span wire:loading wire:target="update">
                Memproses...
            </span>
        </button>
    </form>
</div>

<style>
    .x-small {
        font-size: 0.75rem;
    }

    .safe-area-bottom {
        padding-bottom: 80px;
    }
</style>
