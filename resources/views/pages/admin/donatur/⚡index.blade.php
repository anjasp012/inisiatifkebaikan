<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts.admin')] class extends Component {
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search;

    protected $queryString = ['search'];

    #[Computed]
    public function users()
    {
        return User::where('role', 'donatur')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->withCount([
                'donations' => function ($query) {
                    $query->where('status', 'success');
                },
            ])
            ->withSum(
                [
                    'donations' => function ($query) {
                        $query->where('status', 'success');
                    },
                ],
                'amount',
            )
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }
};
?>

<div>
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Data User</h5>
                    <p class="text-muted small mb-0">Manajemen data user yang terdaftar sebagai donatur.</p>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <div class="position-relative">
                        <input type="text" class="form-control ps-5 w-250" placeholder="Cari donatur..."
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
                        <th>NAMA LENGKAP</th>
                        <th>INFO KONTAK</th>
                        <th class="text-center">STATUS</th>
                        <th class="text-center">TOTAL DONASI</th>
                        <th class="text-center">JUMLAH TRANSAKSI</th>
                    </tr>
                </thead>
                <tbody>
                <tbody>
                    @foreach ($this->users as $no => $item)
                        <tr>
                            <td class="text-center">{{ $this->users->firstItem() + $no }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold avatar-md">
                                        {{ substr($item->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $item->name }}</div>
                                        <div class="extra-small text-muted">Bergabung:
                                            {{ $item->created_at->format('d M Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small fw-bold">{{ $item->email }}</div>
                                <div class="extra-small text-muted">{{ $item->phone ?? '-' }}</div>
                            </td>
                            <td class="text-center">
                                @if ($item->isVerified())
                                    <span
                                        class="badge bg-success bg-opacity-10 text-success border border-success-subtle">
                                        <i class="bi bi-patch-check-fill me-1"></i> Verified
                                    </span>
                                @else
                                    <span
                                        class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle">
                                        <i class="bi bi-exclamation-circle-fill me-1"></i> Unverified
                                    </span>
                                @endif
                            </td>
                            <td class="text-center fw-bold text-success">
                                Rp {{ number_format($item->donations_sum_amount ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border px-2 py-1">
                                    {{ number_format($item->donations_count) }} Kali
                                </span>
                            </td>
                        </tr>
                    @endforeach
                    @if ($this->users->isEmpty())
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2 opacity-25"></i>
                                Tidak ada data user ditemukan.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                    <div class="text-muted small">
                        Menampilkan <strong>{{ $this->users->firstItem() }}</strong> -
                        <strong>{{ $this->users->lastItem() }}</strong> dari
                        <strong>{{ $this->users->total() }}</strong> user
                    </div>
                    <div>
                        {{ $this->users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
