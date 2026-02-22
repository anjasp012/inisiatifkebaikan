<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use App\Models\CampaignCategory;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts.admin')] class extends Component {
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;

    protected $queryString = ['search'];

    #[Computed]
    public function categories()
    {
        return CampaignCategory::withCount('campaigns')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function destroy(CampaignCategory $campaignCategory): void
    {
        if (!$campaignCategory) {
            $this->dispatch('toast', type: 'error', message: 'Kategori campaign tidak ditemukan ❎');
            return;
        }

        if (!str_starts_with($campaignCategory->icon, 'bi-') && $campaignCategory->icon && file_exists(public_path('storage/' . $campaignCategory->icon))) {
            unlink(public_path('storage/' . $campaignCategory->icon));
        }

        $campaignCategory->delete();

        $this->dispatch('toast', type: 'success', message: 'Kategori campaign berhasil dihapus ✅');
    }
};
//
?>

<div>
    <div class="card card-dashboard border-0">
        <div class="card-body border-bottom">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Kategori Campaign</h5>
                    <p class="text-muted small mb-0">Manajemen kategori untuk pengelompokan campaign.</p>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('admin.kategori-campaign.tambah') }}" wire:navigate
                        class="btn btn-primary text-white">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
                    </a>
                    <div class="position-relative">
                        <input type="text" class="form-control ps-5 w-250" placeholder="Cari kategori..."
                            wire:model.live.debounce.250ms="search">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center col-no">NO</th>
                        <th>ICON KATEGORI</th>
                        <th>NAMA KATEGORI</th>
                        <th class="text-center">CAMPAIGN</th>
                        <th>STATUS</th>
                        <th>DIBUAT</th>
                        <th class="text-end col-actions pe-3">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->categories as $no => $category)
                        <tr>
                            <td class="text-center">{{ $this->categories->firstItem() + $no }}</td>
                            <td>
                                @if (str_starts_with($category->icon, 'bi-'))
                                    <div
                                        class="d-flex align-items-center justify-content-center bg-light rounded-2 avatar-md">
                                        <i class="bi {{ $category->icon }} fs-3 text-primary"></i>
                                    </div>
                                @else
                                    <img loading="lazy" src="{{ $category->icon_url }}"
                                        class="rounded-2 object-fit-cover avatar-md" alt="{{ $category->name }}">
                                @endif
                            </td>
                            <td>{{ $category->name }}</td>
                            <td class="text-center">
                                <span class="fw-bold">{{ number_format($category->campaigns_count) }}</span>
                                <div class="extra-small text-muted">Program</div>
                            </td>
                            <td>
                                @if ($category->is_active)
                                    <span
                                        class="badge bg-success bg-opacity-10 text-success px-3 py-2 border border-success border-opacity-10">
                                        <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                    </span>
                                @else
                                    <span
                                        class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 border border-secondary border-opacity-10">
                                        <i class="bi bi-x-circle-fill me-1"></i> Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td>{{ $category->created_at->diffForHumans() }}</td>
                            <td class="text-end pe-3">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admin.kategori-campaign.ubah', $category) }}" wire:navigate
                                        class="btn btn-sm btn-warning text-white" title="Ubah"><i
                                            class="bi bi-pencil"></i></a>
                                    <button wire:click="destroy({{ $category->id }})"
                                        wire:confirm="Anda yakin menghapus kategori ini?"
                                        class="btn btn-sm btn-danger text-white" title="Hapus">
                                        <span wire:loading.remove wire:target="destroy({{ $category->id }})">
                                            <i class="bi bi-trash"></i>
                                        </span>
                                        <span wire:loading wire:target="destroy({{ $category->id }})">
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
                        Menampilkan <strong>{{ $this->categories->firstItem() }}</strong> -
                        <strong>{{ $this->categories->lastItem() }}</strong> dari
                        <strong>{{ $this->categories->total() }}</strong> kategori
                    </div>
                    <div>
                        {{ $this->categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
