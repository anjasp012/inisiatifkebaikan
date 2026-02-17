<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use App\Models\Donation;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts.admin')] class extends Component {
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $status = 'all';

    protected $queryString = ['search', 'status'];

    #[Computed]
    public function donations()
    {
        return Donation::with(['campaign', 'paymentProofs', 'bank'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('donor_name', 'like', '%' . $this->search . '%')
                        ->orWhere('transaction_id', 'like', '%' . $this->search . '%')
                        ->orWhere('donor_phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status !== 'all', function ($query) {
                $query->where('status', $this->status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function setStatus($status)
    {
        $this->status = $status;
        $this->resetPage();
    }

    public function destroy(Donation $donation): void
    {
        $donation->delete();
        $this->dispatch('toast', type: 'success', message: 'Data donasi berhasil dihapus ✅');
    }

    public function export()
    {
        $donations = Donation::with('campaign')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('donor_name', 'like', '%' . $this->search . '%')
                        ->orWhere('transaction_id', 'like', '%' . $this->search . '%')
                        ->orWhere('donor_phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status !== 'all', function ($query) {
                $query->where('status', $this->status);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=donasi-export-' . now()->format('YmdHis') . '.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($donations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID Transaksi', 'Nama Donatur', 'No WA', 'Campaign', 'Nominal', 'Metode', 'Status', 'Tanggal']);

            foreach ($donations as $row) {
                $method = $row->bank ? $row->bank->bank_name : str_replace('_', ' ', $row->payment_method);
                $channel = $row->bank ? $row->bank->type : $row->payment_channel;
                fputcsv($file, [$row->transaction_id, $row->donor_name, $row->donor_phone, $row->campaign->title ?? '-', $row->amount, $method . ($channel ? ' (' . $channel . ')' : ''), $row->status, $row->created_at->format('Y-m-d H:i:s')]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
};

?>

@php
    $totalSuccess = \App\Models\Donation::where('status', 'success')->sum('amount');
    $pendingCount = \App\Models\Donation::where('status', 'pending')->count();
    $totalToday = \App\Models\Donation::whereDate('created_at', today())->where('status', 'success')->sum('amount');
@endphp

<div>
    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 bg-primary text-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-dark bg-opacity-10 py-2 px-3 rounded-3 me-3">
                            <i class="bi bi-wallet2 fs-3"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 small opacity-75">Total Penghimpunan</h6>
                            <h3 class="fw-bold mb-0">Rp {{ number_format($totalSuccess, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-warning h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center text-dark">
                        <div class="bg-dark bg-opacity-10 py-2 px-3 rounded-3 me-3">
                            <i class="bi bi-clock-history fs-3"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 small opacity-75">Pending Verifikasi</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($pendingCount) }} Transaksi</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-success text-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center text-white">
                        <div class="bg-dark bg-opacity-10 py-2 px-3 rounded-3 me-3">
                            <i class="bi bi-graph-up fs-3"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 small opacity-75">Sukses Hari Ini</h6>
                            <h3 class="fw-bold mb-0">Rp {{ number_format($totalToday, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Donasi</h5>
                    <p class="text-muted small mb-0">Manajemen semua donasi masuk baik manual maupun otomatis.</p>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <div class="btn-group">
                        <a href="{{ route('admin.donasi.tambah') }}" wire:navigate class="btn btn-primary text-white">
                            <i class="bi bi-plus-lg me-1"></i> Donasi Manual
                        </a>
                        <button wire:click="export" class="btn btn-outline-success">
                            <i class="bi bi-download me-1"></i> Export
                        </button>
                    </div>

                    <div wire:ignore class="d-inline-block" style="min-width: 150px;">
                        <select x-data="{
                            tom: null,
                            init() {
                                this.tom = new TomSelect(this.$el, {
                                    placeholder: 'Semua Status',
                                    allowEmptyOption: false,
                                    maxOptions: 50,
                                    onChange: (value) => {
                                        $wire.set('status', value);
                                    }
                                });
                            }
                        }" class="form-select">
                            <option value="all">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="success">Sukses</option>
                            <option value="failed">Gagal</option>
                        </select>
                    </div>

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
                        <th>TRANSAKSI ID</th>
                        <th>DONATUR</th>
                        <th>PROGRAM CAMPAIGN</th>
                        <th>JUMLAH</th>
                        <th class="text-center">METODE</th>
                        <th class="text-center">STATUS</th>
                        <th class="text-end">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->donations as $no => $donation)
                        <tr>
                            <td class="text-center text-muted small">{{ $this->donations->firstItem() + $no }}</td>
                            <td>
                                <div class="fw-bold text-primary small">{{ $donation->transaction_id }}</div>
                                <div class="x-small text-muted">{{ $donation->created_at->format('d M Y, H:i') }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $donation->donor_name }}</div>
                                <div class="x-small text-muted">{{ $donation->donor_phone ?? '-' }}</div>
                            </td>
                            <td>
                                @if ($donation->campaign)
                                    <div class="fw-semibold text-truncate" style="max-width: 200px;"
                                        title="{{ $donation->campaign->title }}">
                                        {{ $donation->campaign->title }}
                                    </div>
                                    <div class="mt-1">
                                        @if ($donation->campaign->category)
                                            <span
                                                class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 border border-primary border-opacity-10 x-small"
                                                style="font-size: 10px; font-weight: 600;">
                                                <i class="bi bi-tag-fill me-1"></i>
                                                {{ $donation->campaign->category->name }}
                                            </span>
                                        @else
                                            <span
                                                class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 border border-secondary border-opacity-10 x-small"
                                                style="font-size: 10px; font-weight: 600;">
                                                -
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-dark">Rp {{ number_format($donation->amount, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="small fw-semibold text-capitalize">
                                    {{ $donation->bank ? $donation->bank->bank_name : $donation->payment_channel }}
                                </div>
                                <div class="x-small text-muted text-capitalize">
                                    {{ $donation->bank ? $donation->bank->type : str_replace('_', ' ', $donation->payment_method) }}
                                </div>
                                @if ($donation->paymentProofs->count() > 0)
                                    <span
                                        class="badge bg-info bg-opacity-10 text-info x-small mt-1 px-2 border border-info border-opacity-25">
                                        <i class="bi bi-image me-1"></i> BUKTI
                                        ({{ $donation->paymentProofs->count() }})
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($donation->status == 'success')
                                    <span
                                        class="badge bg-success bg-opacity-10 text-success px-3 py-2 border border-success border-opacity-10">
                                        <i class="bi bi-check-circle-fill me-1"></i> Sukses
                                    </span>
                                @elseif($donation->status == 'pending')
                                    <span
                                        class="badge bg-warning bg-opacity-10 text-dark px-3 py-2 border border-warning border-opacity-25">
                                        <i class="bi bi-clock-fill me-1"></i> Menunggu
                                    </span>
                                @else
                                    <span
                                        class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 border border-danger border-opacity-10">
                                        <i class="bi bi-x-circle-fill me-1"></i> Gagal
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admin.donasi.detail', $donation) }}" wire:navigate
                                        class="btn btn-sm btn-info text-white" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $donation->donor_phone) }}"
                                        target="_blank" class="btn btn-sm btn-success text-white" title="Hubungi WA">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                    <button wire:click="destroy({{ $donation->id }})"
                                        wire:confirm="Yakin ingin menghapus data donasi ini?"
                                        class="btn btn-sm btn-danger text-white" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white border-top py-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                <div class="text-muted small">
                    Menampilkan <strong>{{ $this->donations->firstItem() }}</strong> -
                    <strong>{{ $this->donations->lastItem() }}</strong> dari
                    <strong>{{ $this->donations->total() }}</strong> donasi
                </div>
                <div>
                    {{ $this->donations->links() }}
                </div>
            </div>
        </div>
    </div>
    <style>
        .x-small {
            font-size: 0.7rem;
        }
    </style>
</div>
