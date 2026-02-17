<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts.admin')] class extends Component {
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $category = 'all';

    protected $queryString = ['search', 'category'];

    #[Computed]
    public function articles()
    {
        return Article::with('author')
            ->withViewsCount()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->when($this->category !== 'all', function ($query) {
                $query->where('category', $this->category);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function destroy(Article $article): void
    {
        if (!$article) {
            $this->dispatch('toast', type: 'error', message: 'Artikel tidak ditemukan ❎');
            return;
        }

        if ($article->thumbnail && file_exists(public_path('storage/' . $article->thumbnail))) {
            unlink(public_path('storage/' . $article->thumbnail));
        }

        $article->delete();

        $this->dispatch('toast', type: 'success', message: 'Artikel berhasil dihapus ✅');
    }
};

?>

<div>
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Manajemen Artikel</h5>
                    <p class="text-muted small mb-0">Kelola berita, cerita inspirasi, dan edukasi.</p>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('admin.artikel.tambah') }}" wire:navigate class="btn btn-primary text-white">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Artikel
                    </a>
                    <div wire:ignore class="d-inline-block" style="min-width: 200px;">
                        <select x-data="{
                            tom: null,
                            init() {
                                this.tom = new TomSelect(this.$el, {
                                    placeholder: 'Semua Kategori',
                                    allowEmptyOption: false,
                                    maxOptions: 50,
                                    onChange: (value) => {
                                        $wire.set('category', value);
                                    }
                                });
                            }
                        }" class="form-select">
                            <option value="all">Semua Kategori</option>
                            <option value="Berita">Berita</option>
                            <option value="Inspirasi">Inspirasi</option>
                            <option value="Edukasi">Edukasi</option>
                        </select>
                    </div>
                    <div class="position-relative">
                        <input type="text" class="form-control ps-5" placeholder="Cari artikel..."
                            wire:model.live.debounce.250ms="search" style="min-width: 250px;">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">NO</th>
                        <th>THUMBNAIL</th>
                        <th>JUDUL ARTIKEL</th>
                        <th>PENULIS</th>
                        <th>VIEWS</th>
                        <th>STATUS</th>
                        <th>DIBUAT</th>
                        <th class="text-end">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->articles as $no => $article)
                        <tr>
                            <td class="text-center">{{ $this->articles->firstItem() + $no }}</td>
                            <td>
                                <img loading="lazy" src="{{ $article->thumbnail_url }}" width="100px" class="rounded-1"
                                    alt="{{ $article->title }}">
                            </td>
                            <td>
                                <div class="fw-bold">{{ $article->title }}</div>
                                <div class="mt-1">
                                    <span
                                        class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 border border-primary border-opacity-10"
                                        style="font-size: 10px; font-weight: 600;">
                                        <i class="bi bi-tag-fill me-1"></i>
                                        {{ $article->category }}
                                    </span>
                                </div>
                            </td>
                            <td>{{ $article->author->name ?? '-' }}</td>
                            <td>{{ number_format($article->views_count) }}</td>
                            <td>
                                @if ($article->is_published)
                                    <span
                                        class="badge bg-success bg-opacity-10 text-success px-3 py-2 border border-success border-opacity-10">
                                        <i class="bi bi-check-circle-fill me-1"></i> Published
                                    </span>
                                @else
                                    <span
                                        class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 border border-secondary border-opacity-10">
                                        <i class="bi bi-pencil-fill me-1"></i> Draft
                                    </span>
                                @endif
                            </td>
                            <td>{{ $article->created_at->diffForHumans() }}</td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admin.artikel.ubah', $article) }}" wire:navigate
                                        class="btn btn-sm btn-warning text-white" title="Ubah"><i
                                            class="bi bi-pencil"></i></a>
                                    <button wire:click="destroy({{ $article->id }})"
                                        wire:confirm="Anda yakin menghapus artikel ini?"
                                        class="btn btn-sm btn-danger text-white" title="Hapus">
                                        <span wire:loading.remove wire:target="destroy({{ $article->id }})">
                                            <i class="bi bi-trash"></i>
                                        </span>
                                        <span wire:loading wire:target="destroy({{ $article->id }})">
                                            <div class="spinner-border spinner-border-sm" role="status"></div>
                                        </span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                    <div class="text-muted small">
                        Menampilkan <strong>{{ $this->articles->firstItem() }}</strong> -
                        <strong>{{ $this->articles->lastItem() }}</strong> dari
                        <strong>{{ $this->articles->total() }}</strong> artikel
                    </div>
                    <div>
                        {{ $this->articles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
