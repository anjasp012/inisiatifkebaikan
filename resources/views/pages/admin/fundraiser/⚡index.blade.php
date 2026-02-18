<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use App\Models\Fundraiser;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts.admin')] class extends Component {
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search;
    public $statusFilter = 'all';

    protected $queryString = ['search', 'statusFilter'];

    #[Computed]
    public function fundraisers()
    {
        return Fundraiser::with('user')
            ->when($this->search, function ($query) {
                $query->where('foundation_name', 'like', '%' . $this->search . '%')->orWhereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }
};
//
?>

<div>
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Fundraiser</h5>
                    <p class="text-muted small mb-0">Manajemen mitra yayasan dan penggalang dana.</p>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <select wire:model.live="statusFilter" class="form-select w-auto">
                        <option value="all">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    <div class="position-relative">
                        <input type="text" class="form-control ps-5 w-250" placeholder="Cari fundraiser..."
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
                        <th>LOGO</th>
                        <th>NAMA YAYASAN</th>
                        <th>USER</th>
                        <th>BANK</th>
                        <th>STATUS</th>
                        <th>DIBUAT</th>
                        <th class="text-end col-actions pe-3">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->fundraisers as $no => $fundraiser)
                        <tr>
                            <td class="text-center">{{ $this->fundraisers->firstItem() + $no }}</td>
                            <td>
                                <img loading="lazy" src="{{ $fundraiser->logo_url }}"
                                    class="rounded avatar-lg object-fit-cover" alt="{{ $fundraiser->foundation_name }}">
                            </td>
                            <td>
                                <div class="fw-bold">{{ $fundraiser->foundation_name ?? '-' }}</div>
                                <small class="text-muted">NPWP: {{ $fundraiser->tax_id ?? '-' }}</small>
                            </td>
                            <td>
                                <div>{{ $fundraiser->user->name }}</div>
                                <small class="text-muted">{{ $fundraiser->user->email }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $fundraiser->bank_name ?? '-' }}</div>
                                <small class="text-muted">{{ $fundraiser->bank_account_number ?? '-' }}</small>
                                <br>
                                <small class="text-muted">{{ $fundraiser->bank_account_name ?? '-' }}</small>
                            </td>
                            <td>
                                @if ($fundraiser->status == 'approved')
                                    <span
                                        class="badge bg-success bg-opacity-10 text-success px-3 py-2 border border-success border-opacity-10">
                                        <i class="bi bi-check-circle-fill me-1"></i> Disetujui
                                    </span>
                                @elseif($fundraiser->status == 'pending')
                                    <span
                                        class="badge bg-warning bg-opacity-10 text-dark px-3 py-2 border border-warning border-opacity-25">
                                        <i class="bi bi-clock-fill me-1"></i> Menunggu
                                    </span>
                                @else
                                    <span
                                        class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 border border-danger border-opacity-10">
                                        <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                    </span>
                                @endif
                            </td>
                            <td>{{ $fundraiser->created_at->diffForHumans() }}</td>
                            <td class="text-end pe-3">
                                <div class="d-flex gap-1 align-items-center justify-content-end">
                                    <a href="{{ route('admin.fundraiser.detail', $fundraiser) }}" wire:navigate
                                        class="btn btn-sm btn-info text-white" title="Detail"><i
                                            class="bi bi-eye"></i></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                    <div class="text-muted small">
                        Menampilkan <strong>{{ $this->fundraisers->firstItem() }}</strong> -
                        <strong>{{ $this->fundraisers->lastItem() }}</strong> dari
                        <strong>{{ $this->fundraisers->total() }}</strong> fundraiser
                    </div>
                    <div>
                        {{ $this->fundraisers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
