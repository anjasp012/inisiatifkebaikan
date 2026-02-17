<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Campaign;
use App\Models\CampaignUpdate;

new #[Layout('layouts.admin')] class extends Component {
    use WithFileUploads;

    public Campaign $campaign;
    public CampaignUpdate $update;

    public $title = '';
    public $content = '';
    public $published_at = '';
    public $image;
    public $existingImage;

    public function mount(Campaign $campaign, CampaignUpdate $update)
    {
        $this->campaign = $campaign;
        $this->update = $update;

        $this->title = $update->title;
        $this->content = $update->content;
        $this->published_at = $update->published_at ? $update->published_at->format('Y-m-d\TH:i') : null;
        $this->existingImage = $update->image;
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'published_at' => 'nullable|date',
            'image' => 'nullable|image|max:2048', // 2MB max
        ]);

        $imagePath = $this->existingImage;
        if ($this->image) {
            // Delete old image if updating
            if ($this->existingImage && file_exists(public_path('storage/' . $this->existingImage))) {
                unlink(public_path('storage/' . $this->existingImage));
            }

            $imagePath = $this->image->store('campaign-updates', 'public');
        }

        $this->update->update([
            'title' => $this->title,
            'content' => $this->content,
            'published_at' => $this->published_at,
            'image' => $imagePath,
        ]);

        $this->dispatch('toast', type: 'success', message: 'Update berhasil diperbarui ✅');
        $this->redirectRoute('admin.campaign.updates', $this->campaign, navigate: true);
    }
};
?>

<div>
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Ubah Update</h5>
                    <p class="text-muted small mb-0">
                        Edit pengumuman update yang sudah ada.
                    </p>
                </div>
                <a href="{{ route('admin.campaign.updates', $campaign) }}" wire:navigate class="btn btn-light border">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <form wire:submit="save">
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <x-admin.file-upload model="image" label="Gambar Update (Opsional)" :preview="$image
                            ? $image->temporaryUrl()
                            : ($existingImage
                                ? asset('storage/' . $existingImage)
                                : null)" />
                    </div>

                    <div class="col-md-8">
                        <label for="title" class="form-label">Judul Update</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                            wire:model="title" placeholder="Masukkan judul update...">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <x-admin.input-calendar model="published_at" label="Tanggal Publikasi" />
                    </div>

                    <div class="col-md-12" wire:ignore>
                        <label for="content" class="form-label">Konten Update</label>
                        <x-admin.text-editor model="content" id="content" />
                        @error('content')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="{{ route('admin.campaign.updates', $campaign) }}" wire:navigate
                        class="btn btn-light border">Batal</a>
                    <button type="submit" class="btn btn-primary text-white">
                        <span wire:loading.remove>
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </span>
                        <span wire:loading>
                            <div class="spinner-border spinner-border-sm" role="status"></div> Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
