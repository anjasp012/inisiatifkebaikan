<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use App\Models\Page;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts.admin')] class extends Component {
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;

    #[Computed]
    public function pages()
    {
        return Page::when($this->search, function ($query) {
            $query->where('title', 'like', '%' . $this->search . '%')->orWhere('slug', 'like', '%' . $this->search . '%');
        })
            ->orderBy('title', 'asc')
            ->paginate(10);
    }

    public function destroy(Page $page): void
    {
        $page->delete();
        $this->dispatch('toast', type: 'success', message: 'Halaman berhasil dihapus ✅');
    }

    public function toggleStatus(Page $page)
    {
        $page->is_active = !$page->is_active;
        $page->save();
        $this->dispatch('toast', type: 'success', message: 'Status halaman diperbarui ✅');
    }
};
?>

<div>
    <div class="card card-dashboard border-0">
        <div class="card-body border-bottom">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Manajemen Halaman Statis</h5>
                    <p class="text-muted small mb-0">Kelola konten halaman seperti Tentang Kami, Syarat & Ketentuan, dll.
                    </p>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('admin.page.tambah') }}" wire:navigate
                        class="btn btn-primary text-white scale-hover">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Halaman
                    </a>
                    <div class="position-relative">
                        <input type="text" class="form-control ps-5 w-250 border-light bg-light"
                            placeholder="Cari halaman..." wire:model.live.debounce.250ms="search">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">NO</th>
                        <th>JUDUL HALAMAN</th>
                        <th>SLUG / URL</th>
                        <th class="text-center">STATUS</th>
                        <th class="text-center">TERAKHIR DIUBAH</th>
                        <th class="text-end pe-3" style="width: 120px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->pages as $no => $page)
                        <tr>
                            <td class="text-center text-muted">{{ $this->pages->firstItem() + $no }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $page->title }}</div>
                            </td>
                            <td>
                                <code
                                    class="small text-primary bg-primary bg-opacity-10 px-2 py-1 rounded">/{{ $page->slug }}</code>
                                <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="ms-1 text-muted">
                                    <i class="bi bi-box-arrow-up-right extra-small"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <button wire:click="toggleStatus({{ $page->id }})" class="btn btn-sm p-0">
                                    @if ($page->is_active)
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success px-3 py-2 border border-success border-opacity-10">
                                            <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                        </span>
                                    @else
                                        <span
                                            class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 border border-secondary border-opacity-10">
                                            <i class="bi bi-x-circle-fill me-1"></i> Non-Aktif
                                        </span>
                                    @endif
                                </button>
                            </td>
                            <td class="text-center text-muted small">
                                {{ $page->updated_at->format('d M Y H:i') }}
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admin.page.ubah', $page) }}" wire:navigate
                                        class="btn btn-sm btn-warning text-white btn-action" title="Ubah">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button wire:click="destroy({{ $page->id }})"
                                        wire:confirm="Anda yakin menghapus halaman ini?"
                                        class="btn btn-sm btn-danger text-white btn-action" title="Hapus">
                                        <span wire:loading.remove wire:target="destroy({{ $page->id }})">
                                            <i class="bi bi-trash"></i>
                                        </span>
                                        <span wire:loading wire:target="destroy({{ $page->id }})">
                                            <div class="spinner-border spinner-border-sm" role="status"></div>
                                        </span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($this->pages->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-file-earmark-x display-1 text-muted opacity-25 mb-3 d-block"></i>
                    <h6 class="fw-bold text-muted">Belum ada halaman ditemukan.</h6>
                </div>
            @endif
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                    <div class="text-muted small">
                        Menampilkan <strong>{{ $this->pages->firstItem() }}</strong> -
                        <strong>{{ $this->pages->lastItem() }}</strong> dari
                        <strong>{{ $this->pages->total() }}</strong> halaman
                    </div>
                    <div>
                        {{ $this->pages->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-action {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }

    .scale-hover {
        transition: transform 0.2s;
    }

    .scale-hover:hover {
        transform: scale(1.02);
    }
</style>
