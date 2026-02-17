<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use App\Models\Article;

new #[Layout('layouts.admin')] class extends Component {
    use WithFileUploads;

    public Article $article;

    public $thumbnail;
    public string $title = '';
    public string $category = 'Berita';
    public string $content = '';
    public bool $is_published = true;

    public function mount(Article $article)
    {
        $this->article = $article;
        $this->title = $article->title;
        $this->category = $article->category;
        $this->content = $article->content;
        $this->is_published = (bool) $article->is_published;
    }

    public function update(): void
    {
        $rules = [
            'thumbnail' => 'nullable|image|max:2048',
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'content' => 'required|string',
        ];

        $messages = [
            'thumbnail.image' => 'Thumbnail harus berupa gambar.',
            'title.required' => 'Judul artikel wajib diisi.',
            'category.required' => 'Kategori wajib dipilih.',
            'content.required' => 'Konten artikel wajib diisi.',
        ];

        $this->validate($rules, $messages);

        if ($this->thumbnail) {
            // Delete old thumbnail if exists
            if ($this->article->thumbnail && file_exists(public_path('storage/' . $this->article->thumbnail))) {
                unlink(public_path('storage/' . $this->article->thumbnail));
            }
            $thumbnailPath = $this->thumbnail->store('articles', 'public');
        } else {
            $thumbnailPath = $this->article->thumbnail;
        }

        $this->article->update([
            'thumbnail' => $thumbnailPath,
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'category' => $this->category,
            'content' => $this->content,
            'is_published' => $this->is_published,
        ]);

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Artikel berhasil diperbarui ✅',
        ]);
        $this->redirectRoute('admin.artikel', navigate: true);
    }
};
?>

<div>
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Ubah Artikel</h5>
                    <p class="text-muted small mb-0">Edit konten artikel yang sudah ada.</p>
                </div>
                <a href="{{ route('admin.artikel') }}" wire:navigate class="btn btn-light border">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <form wire:submit="update">
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <x-admin.file-upload model="thumbnail" label="Thumbnail Artikel" :preview="$thumbnail
                            ? $thumbnail->temporaryUrl()
                            : ($article->thumbnail
                                ? asset('storage/' . $article->thumbnail)
                                : null)" />
                    </div>

                    <div class="col-md-8">
                        <label for="title" class="form-label">Judul Artikel</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                            wire:model="title" id="title" placeholder="Masukan judul artikel">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="category" class="form-label">Kategori</label>
                        <div class="@error('category') is-invalid-tomselect @enderror">
                            <div wire:ignore>
                                <select class="form-select @error('category') is-invalid @enderror" id="category"
                                    x-data="{
                                        tom: null,
                                        init() {
                                            this.tom = new TomSelect(this.$el, {
                                                placeholder: 'Pilih atau cari atau buat baru Kategori...',
                                                create: true,
                                                allowEmptyOption: false,
                                                onChange: (value) => {
                                                    $wire.set('category', value);
                                                }
                                            });
                                        }
                                    }">
                                    <option value="">Pilih atau buat...</option>
                                    @php
                                        $categories = \App\Models\Article::distinct()
                                            ->pluck('category')
                                            ->filter()
                                            ->toArray();
                                        $defaultCategories = ['Berita', 'Inspirasi', 'Edukasi'];
                                        $allCategories = array_unique(array_merge($defaultCategories, $categories));
                                    @endphp
                                    @foreach ($allCategories as $cat)
                                        <option value="{{ $cat }}" {{ $cat == $category ? 'selected' : '' }}>
                                            {{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label for="content" class="form-label">Konten Artikel</label>
                        <x-admin.text-editor model="content" id="content" />
                        @error('content')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" wire:model="is_published" id="is_published">
                            <label class="form-check-label" for="is_published">
                                Publish artikel (tampilkan di website)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 mt-4 border-top">
                    <a href="{{ route('admin.artikel') }}" class="btn btn-light border px-4 fw-semibold"
                        wire:navigate>Batal</a>
                    <button type="submit" class="btn btn-primary text-white fw-semibold px-4"
                        wire:loading.attr="disabled" wire:target="update">
                        <span wire:loading.remove wire:target="update">
                            Simpan Perubahan <i class="bi bi-floppy-fill ms-2"></i>
                        </span>
                        <span wire:loading wire:target="update">
                            <div class="spinner-border spinner-border-sm" role="status"></div>
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
