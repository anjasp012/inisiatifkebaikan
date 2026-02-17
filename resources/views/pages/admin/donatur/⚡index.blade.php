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
    public function donateurs()
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
                    <h5 class="fw-bold mb-1">Daftar Donatur</h5>
                    <p class="text-muted small mb-0">Manajemen data user yang terdaftar sebagai donatur.</p>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <div class="position-relative">
                        <input type="text" class="form-control ps-5" placeholder="Cari donatur..."
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
                        <th class="text-center" style="width: 50px;">NO</th>
                        <th>NAMA LENGKAP</th>
                        <th>EMAIL</th>
                        <th class="text-center">TOTAL DONASI</th>
                        <th class="text-center">JUMLAH TRANSAKSI</th>
                        <th>BERGABUNG</th>
                        <th class="text-end pe-3">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->donateurs as $no => $donatur)
                        <tr>
                            <td class="text-center">{{ $this->donateurs->firstItem() + $no }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold"
                                        style="width: 40px; height: 40px;">
                                        {{ substr($donatur->name, 0, 1) }}
                                    </div>
                                    <div class="fw-bold">{{ $donatur->name }}</div>
                                </div>
                            </td>
                            <td>{{ $donatur->email }}</td>
                            <td class="text-center fw-bold text-success">
                                Rp {{ number_format($donatur->donations_sum_amount ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border px-2 py-1">
                                    {{ number_format($donatur->donations_count) }} Kali
                                </span>
                            </td>
                            <td>
                                <div class="small">{{ $donatur->created_at->format('d M Y') }}</div>
                                <div class="x-small text-muted">{{ $donatur->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="text-end pe-3">
                                <button class="btn btn-sm btn-light border" title="Detail User (Soon)">
                                    <i class="bi bi-person-lines-fill"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    @if ($this->donateurs->isEmpty())
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2 opacity-25"></i>
                                Tidak ada data donatur ditemukan.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                    <div class="text-muted small">
                        Menampilkan <strong>{{ $this->donateurs->firstItem() }}</strong> -
                        <strong>{{ $this->donateurs->lastItem() }}</strong> dari
                        <strong>{{ $this->donateurs->total() }}</strong> donatur
                    </div>
                    <div>
                        {{ $this->donateurs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
