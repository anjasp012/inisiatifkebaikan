<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Campaign;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts.admin')] #[Title('Daftar Program')] class extends Component {
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;

    protected $queryString = ['search'];

    #[Computed]
    public function campaigns()
    {
        return Campaign::with(['fundraiser', 'category'])
            ->withViewsCount()
            ->withCount('donations')
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }
    public function destroy(Campaign $campaign): void
    {
        if (!$campaign) {
            $this->dispatch('toast', type: 'error', message: 'Campaign tidak ditemukan ❎');
            return;
        }

        if ($campaign->thumbnail && file_exists(public_path($campaign->thumbnail))) {
            unlink(public_path($campaign->thumbnail));
        }

        $campaign->delete();

        $this->dispatch('toast', type: 'success', message: 'Campaign berhasil dihapus ✅');
    }

    public function toggleStatus(Campaign $campaign): void
    {
        $newStatus = $campaign->status === 'active' ? 'hidden' : 'active';
        $campaign->update(['status' => $newStatus]);
        $this->dispatch('toast', type: 'success', message: 'Campaign berhasil di' . ($newStatus === 'active' ? 'aktifkan' : 'sembunyikan') . ' ✅');
    }

    public function toggleSlider(Campaign $campaign): void
    {
        $campaign->update(['is_slider' => !$campaign->is_slider]);
        $this->dispatch('toast', type: 'success', message: 'Status slider campaign diperbarui ✅');
    }
};
//
?>

<div>
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Campaign</h5>
                    <p class="text-muted small mb-0">Manajemen semua program kebaikan yang sedang berjalan.</p>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('admin.campaign.tambah') }}" wire:navigate class="btn btn-primary text-white">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Campaign
                    </a>

                    <div class="position-relative">
                        <input type="text" class="form-control ps-5" placeholder="Cari campaign..."
                            wire:model.live.debounce.250ms="search" style="min-width: 250px;">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-borderless align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">NO</th>
                        <th style="width: 120px;">THUMBNAIL</th>
                        <th>JUDUL CAMPAIGN</th>
                        <th class="text-center">VIEWS</th>
                        <th class="text-center">DONATUR</th>
                        <th class="text-center">STATUS</th>
                        <th class="text-center">SLIDER</th>
                        <th>DIBUAT</th>
                        <th class="text-end pe-3">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->campaigns as $no => $campaign)
                        <tr>
                            <td class="text-center">{{ $this->campaigns->firstItem() + $no }}</td>
                            <td>
                                <img loading="lazy" src="{{ $campaign->thumbnail_url }}" width="100px" class="rounded"
                                    alt="{{ $campaign->title }}">
                            </td>
                            <td>
                                <div class="fw-bold">{{ $campaign->title }}</div>
                                <div class="small text-muted mb-1">
                                    {{ $campaign->fundraiser->foundation_name ?? 'Inisiatif Kebaikan' }}</div>
                                @if ($campaign->category)
                                    <span
                                        class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 border border-primary border-opacity-10"
                                        style="font-size: 10px; font-weight: 600;">
                                        <i class="bi bi-tag-fill me-1"></i>
                                        {{ $campaign->category->name }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-eye me-1"></i> {{ number_format($campaign->views_count) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span
                                    class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10">
                                    <i class="bi bi-people-fill me-1"></i>
                                    {{ number_format($campaign->donations_count) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if ($campaign->status == 'completed')
                                    <span
                                        class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 border border-primary border-opacity-10">
                                        <i class="bi bi-check-circle-fill me-1"></i> Selesai
                                    </span>
                                @else
                                    <div class="d-inline-flex align-items-center">
                                        @php
                                            $isActive = $campaign->status == 'active';
                                            $statusColor = $isActive ? 'success' : 'secondary';
                                        @endphp
                                        <label
                                            class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} py-2 px-3 border border-{{ $statusColor }} border-opacity-10 d-flex align-items-center gap-2 cursor-pointer"
                                            for="statusSwitch{{ $campaign->id }}">
                                            <div class="form-check form-switch p-0 m-0" style="min-height: auto;">
                                                <input class="form-check-input cursor-pointer m-0" type="checkbox"
                                                    role="switch" wire:click="toggleStatus({{ $campaign->id }})"
                                                    id="statusSwitch{{ $campaign->id }}" @checked($isActive)
                                                    style="width: 1.8em; height: 1em;">
                                            </div>
                                            <span class="x-small fw-bold text-uppercase" style="letter-spacing: 0.5px;">
                                                {{ $isActive ? 'Aktif' : 'Tersembunyi' }}
                                            </span>
                                        </label>
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input cursor-pointer" type="checkbox" role="switch"
                                        wire:click="toggleSlider({{ $campaign->id }})"
                                        id="sliderSwitch{{ $campaign->id }}" @checked($campaign->is_slider)>
                                </div>
                            </td>
                            <td>{{ $campaign->created_at->diffForHumans() }}</td>
                            <td class="text-end pe-3">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admin.campaign.updates', $campaign) }}" wire:navigate
                                        class="btn btn-sm btn-info text-white" title="Kelola Update">
                                        <i class="bi bi-newspaper"></i>
                                    </a>
                                    <a href="{{ route('admin.campaign.ubah', $campaign) }}" wire:navigate
                                        class="btn btn-sm btn-warning text-white" title="Ubah"><i
                                            class="bi bi-pencil"></i></a>
                                    <button wire:click="destroy({{ $campaign->id }})"
                                        wire:confirm="Anda yakin menghapus campaign ini?"
                                        class="btn btn-sm btn-danger text-white" title="Hapus">
                                        <span wire:loading.remove wire:target="destroy({{ $campaign->id }})">
                                            <i class="bi bi-trash"></i>
                                        </span>
                                        <span wire:loading wire:target="destroy({{ $campaign->id }})">
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
                        Menampilkan <strong>{{ $this->campaigns->firstItem() }}</strong> -
                        <strong>{{ $this->campaigns->lastItem() }}</strong> dari
                        <strong>{{ $this->campaigns->total() }}</strong> campaign
                    </div>
                    <div>
                        {{ $this->campaigns->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
