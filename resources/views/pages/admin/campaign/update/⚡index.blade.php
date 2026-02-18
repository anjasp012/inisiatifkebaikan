<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Campaign;
use App\Models\CampaignUpdate;
use Illuminate\Support\Str;

new #[Layout('layouts.admin')] #[Title('Update Program')] class extends Component {
    use WithPagination;

    public Campaign $campaign;

    public function mount(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function destroy(CampaignUpdate $update)
    {
        if ($update->image) {
            // Delete image if exists
            if (file_exists(public_path('storage/' . $update->image))) {
                unlink(public_path('storage/' . $update->image));
            }
        }

        $update->delete();
        $this->dispatch('toast', type: 'success', message: 'Update berhasil dihapus ✅');
    }

    public function render()
    {
        $updates = $this->campaign->updates()->paginate(10);
        return view('pages.admin.campaign.update.⚡index', [
            'updates' => $updates,
        ]);
    }
};
?>

<div>
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Update Campaign</h5>
                    <p class="text-muted small mb-0">Kelola update kabar terbaru untuk campaign:
                        <strong>{{ $campaign->title }}</strong>
                    </p>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('admin.campaign') }}" wire:navigate class="btn btn-light border">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                    <a href="{{ route('admin.campaign.updates.tambah', $campaign) }}" wire:navigate
                        class="btn btn-primary text-white">
                        <i class="bi bi-plus-lg me-1"></i> Buat Update Baru
                    </a>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-borderless align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">NO</th>
                        <th style="width: 150px;">TANGGAL</th>
                        <th>JUDUL UPDATE</th>
                        <th class="text-end pe-3">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($updates as $no => $item)
                        <tr>
                            <td class="text-center">{{ $updates->firstItem() + $no }}</td>
                            <td>
                                <div class="fw-bold text-primary">
                                    {{ $item->published_at ? $item->published_at->format('d M Y') : '-' }}</div>
                                <div class="small text-muted">{{ $item->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $item->title }}</div>
                                <div class="small text-muted text-truncate" style="max-width: 400px;">
                                    {{ Str::limit(strip_tags($item->content), 100) }}</div>
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admin.campaign.updates.ubah', [$campaign, $item]) }}"
                                        wire:navigate class="btn btn-sm btn-warning text-white" title="Ubah">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button wire:click="destroy({{ $item->id }})"
                                        wire:confirm="Yakin ingin menghapus update ini?"
                                        class="btn btn-sm btn-danger text-white" title="Hapus">
                                        <span wire:loading.remove wire:target="destroy({{ $item->id }})">
                                            <i class="bi bi-trash"></i>
                                        </span>
                                        <span wire:loading wire:target="destroy({{ $item->id }})">
                                            <div class="spinner-border spinner-border-sm" role="status"></div>
                                        </span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-newspaper display-4 d-block mb-3 text-secondary opacity-50"></i>
                                Belum ada update untuk campaign ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="card-footer bg-white border-top py-3">
                {{ $updates->links() }}
            </div>
        </div>
    </div>
</div>
