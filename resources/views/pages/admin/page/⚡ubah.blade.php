<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;
use App\Models\Page;

new #[Layout('layouts.admin')] class extends Component {
    public Page $page;
    public string $title = '';
    public string $slug = '';
    public string $content = '';
    public bool $is_active = true;

    public function mount(Page $page)
    {
        $this->page = $page;
        $this->title = $page->title;
        $this->slug = $page->slug;
        $this->content = $page->content;
        $this->is_active = $page->is_active;
    }

    public function updatedTitle()
    {
        // Only auto-slug if it matches the current title slug (means it wasn't manually changed much)
        // Or just let user change it manually if they want.
    }

    public function update(): void
    {
        $rules = [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $this->page->id,
            'content' => 'required|string',
        ];

        $messages = [
            'title.required' => 'Judul halaman wajib diisi.',
            'slug.required' => 'Slug/URL wajib diisi.',
            'slug.unique' => 'Slug/URL sudah digunakan halaman lain.',
            'content.required' => 'Konten halaman wajib diisi.',
        ];

        $this->validate($rules, $messages);

        $this->page->update([
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'is_active' => $this->is_active,
        ]);

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Halaman berhasil diperbarui ✅',
        ]);

        $this->redirectRoute('admin.page', navigate: true);
    }
};
?>

<div>
    <div class="card card-dashboard border-0">
        <div class="card-body border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Edit Halaman</h5>
                    <p class="text-muted small mb-0">Perbarui konten halaman "{{ $page->title }}".</p>
                </div>
                <a href="{{ route('admin.page') }}" wire:navigate class="btn btn-light border">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <form wire:submit="update">
                <div class="row g-4">
                    <div class="col-md-7">
                        <label for="title" class="form-label fw-bold extra-small text-uppercase">Judul
                            Halaman</label>
                        <input type="text" class="form-control py-2 @error('title') is-invalid @enderror"
                            wire:model.live.debounce.300ms="title" id="title">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-5">
                        <label for="slug" class="form-label fw-bold extra-small text-uppercase">Slug / URL</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 extra-small">/</span>
                            <input type="text"
                                class="form-control py-2 border-start-0 @error('slug') is-invalid @enderror"
                                wire:model="slug" id="slug">
                        </div>
                        @error('slug')
                            <div class="text-danger extra-small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="content" class="form-label fw-bold extra-small text-uppercase">Konten
                            Halaman</label>
                        <x-admin.text-editor model="content" id="content" />
                        @error('content')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch p-0 ps-5 border rounded-3 p-3 bg-light bg-opacity-50">
                            <input class="form-check-input ms-0 me-2" type="checkbox" wire:model="is_active"
                                id="is_active">
                            <label class="form-check-label fw-bold" for="is_active">
                                Aktifkan Halaman
                            </label>
                            <div class="extra-small text-muted mt-1 px-4">Halaman akan dapat diakses publik melalui URL
                                yang ditentukan.</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-4 mt-5">
                    <a href="{{ route('admin.page') }}" class="btn btn-light border px-4 fw-bold"
                        wire:navigate>Batal</a>
                    <button class="btn btn-primary text-white fw-bold px-4 shadow-sm" wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            Simpan Perubahan <i class="bi bi-floppy-fill ms-2"></i>
                        </span>
                        <span wire:loading>
                            <div class="spinner-border spinner-border-sm me-2"></div>Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
