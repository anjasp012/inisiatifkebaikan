<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use App\Models\Withdrawal;
use App\Models\Donation;
use App\Models\Campaign;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts.admin')] class extends Component {
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search;
    public $statusFilter = 'all';
    public $selectedWithdrawal = null;

    public function showReceipt($id)
    {
        $this->selectedWithdrawal = Withdrawal::with(['fundraiser', 'campaign'])->find($id);
    }

    public function closeReceipt()
    {
        $this->selectedWithdrawal = null;
    }

    #[Computed]
    public function totalCollected()
    {
        return Donation::where('status', 'success')->sum('amount');
    }

    #[Computed]
    public function totalDisbursed()
    {
        return Withdrawal::where('status', 'success')->sum('net_amount');
    }

    #[Computed]
    public function totalAds()
    {
        return Withdrawal::where('status', 'success')->sum('ads_fee');
    }

    #[Computed]
    public function availableBalance()
    {
        return $this->totalCollected - Withdrawal::where('status', 'success')->sum('amount');
    }

    #[Computed]
    public function withdrawals()
    {
        return Withdrawal::with(['fundraiser', 'campaign'])
            ->when($this->search, function ($q) {
                $q->whereHas('fundraiser', function ($f) {
                    $f->where('foundation_name', 'like', '%' . $this->search . '%');
                })->orWhereHas('campaign', function ($c) {
                    $c->where('title', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter != 'all', function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }
};
?>
<div>
    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 bg-primary text-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-dark bg-opacity-10 py-2 px-3 rounded-3 me-3">
                            <i class="bi bi-wallet2 fs-3"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 small opacity-75">Saldo Tersedia</h6>
                            <h3 class="fw-bold mb-0">Rp {{ number_format($this->availableBalance, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 bg-success text-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-dark bg-opacity-10 py-2 px-3 rounded-3 me-3">
                            <i class="bi bi-cart-check fs-3"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 small opacity-75">Total Dicairkan</h6>
                            <h3 class="fw-bold mb-0">Rp {{ number_format($this->totalDisbursed, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 bg-warning h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center text-dark">
                        <div class="bg-dark bg-opacity-10 py-2 px-3 rounded-3 me-3">
                            <i class="bi bi-envelope-check fs-3"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 small opacity-75">Total Penghimpunan</h6>
                            <h3 class="fw-bold mb-0">Rp {{ number_format($this->totalCollected, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 bg-info text-white h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-dark bg-opacity-10 py-2 px-3 rounded-3 me-3">
                            <i class="bi bi-megaphone fs-3"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 small opacity-75">Total Biaya Ads</h6>
                            <h3 class="fw-bold mb-0">Rp {{ number_format($this->totalAds, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Withdrawal Table --}}
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Riwayat Pencairan</h5>
                    <p class="text-muted small mb-0">Klik baris untuk melihat struk pencairan.</p>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('admin.pencairan.tambah') }}" wire:navigate class="btn btn-primary text-white">
                        <i class="bi bi-plus-lg me-1"></i> Buat Pencairan
                    </a>

                    <div wire:ignore class="d-inline-block w-150">
                        <select x-data="{
                            tom: null,
                            init() {
                                this.tom = new TomSelect(this.$el, {
                                    placeholder: 'Semua Status',
                                    allowEmptyOption: false,
                                    maxOptions: 50,
                                    onChange: (value) => {
                                        $wire.set('statusFilter', value);
                                    }
                                });
                            }
                        }" class="form-select">
                            <option value="all">Semua Status</option>
                            <option value="pending">Menunggu</option>
                            <option value="success">Sukses</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>
                    <div class="position-relative">
                        <input type="text" class="form-control ps-5 w-250" placeholder="Cari campaign/mitra..."
                            wire:model.live.debounce.300ms="search">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">CAMPAIGN</th>
                        <th>PEMOHON</th>
                        <th class="text-center">TANGGAL</th>
                        <th class="text-center">STATUS</th>
                        <th class="text-end pe-4">PENCAIRAN BERSIH</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->withdrawals as $w)
                        <tr wire:click="showReceipt({{ $w->id }})" style="cursor: pointer;"
                            class="transition-row">
                            <td class="ps-4">
                                <div class="fw-bold extra-small text-dark text-truncate max-w-200">
                                    {{ $w->campaign->title }}</div>
                            </td>
                            <td>
                                <div class="small text-muted">{{ $w->requester_name }}</div>
                            </td>
                            <td class="text-center extra-small text-muted">
                                {{ $w->created_at->format('d-m-Y') }}
                            </td>
                            <td class="text-center">
                                @if ($w->status == 'pending')
                                    <span
                                        class="badge bg-warning bg-opacity-10 text-dark px-3 py-2 border border-warning border-opacity-25">
                                        <i class="bi bi-clock-fill me-1"></i> Menunggu
                                    </span>
                                @elseif($w->status == 'success')
                                    <span
                                        class="badge bg-success bg-opacity-10 text-success px-3 py-2 border border-success border-opacity-10">
                                        <i class="bi bi-check-circle-fill me-1"></i> Sukses
                                    </span>
                                @else
                                    <span
                                        class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 border border-danger border-opacity-10">
                                        <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <span class="fw-bold {{ $w->status == 'success' ? 'text-success' : 'text-dark' }}">
                                    Rp {{ number_format($w->net_amount, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada data pencairan dana.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                    <div class="text-muted small">
                        Menampilkan <strong>{{ $this->withdrawals->firstItem() }}</strong> -
                        <strong>{{ $this->withdrawals->lastItem() }}</strong> dari
                        <strong>{{ $this->withdrawals->total() }}</strong> pencairan
                    </div>
                    <div>
                        {{ $this->withdrawals->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Receipt Modal --}}
    @if ($selectedWithdrawal)
        <div class="modal-backdrop-custom" wire:click="closeReceipt">
            <div class="receipt-modal col-11 col-md-5 col-lg-4" wire:click.stop>
                <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 20px;">
                    {{-- Receipt Header --}}
                    <div class="card-header bg-white border-0 pt-4 pb-0 text-center position-relative">
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-3"
                            wire:click="closeReceipt" style="font-size: 0.8rem;"></button>
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle mb-3"
                            style="width: 60px; height: 60px;">
                            <i class="bi bi-receipt text-primary fs-3"></i>
                        </div>
                        <h5 class="fw-bold mb-1">Struk Pencairan Dana</h5>
                        <p class="text-muted small mb-0 px-3 text-truncate">{{ $selectedWithdrawal->campaign->title }}
                        </p>
                    </div>

                    <div class="card-body p-4">
                        {{-- Meta Info --}}
                        <div class="d-flex justify-content-between mb-4 pb-3 border-bottom border-dashed">
                            <div class="text-start">
                                <small class="text-muted d-block text-uppercase fw-bold"
                                    style="font-size: 0.65rem;">ID
                                    Transaksi</small>
                                <span
                                    class="small fw-bold">#WD-{{ $selectedWithdrawal->id }}{{ date('ymd') }}</span>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block text-uppercase fw-bold"
                                    style="font-size: 0.65rem;">Tanggal</small>
                                <span
                                    class="small fw-bold">{{ $selectedWithdrawal->created_at->translatedFormat('d M Y, H:i') }}</span>
                            </div>
                        </div>

                        {{-- Status & Bruto --}}
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Status
                                    Transaksi</small>
                                @if ($selectedWithdrawal->status == 'success')
                                    <span
                                        class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Sukses</span>
                                @elseif($selectedWithdrawal->status == 'pending')
                                    <span
                                        class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3">Menunggu</span>
                                @else
                                    <span
                                        class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">Ditolak</span>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between align-items-baseline">
                                <small class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Nominal
                                    Pengajuan</small>
                                <span class="fw-bold text-dark fs-5">Rp
                                    {{ number_format($selectedWithdrawal->amount, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Details Table --}}
                        <div class="bg-light rounded-4 p-3 mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted font-monospace" style="font-size: 0.75rem;">Biaya Ads + PPN
                                    11%</small>
                                <small class="fw-semibold text-danger">- Rp
                                    {{ number_format($selectedWithdrawal->ads_fee + $selectedWithdrawal->ads_vat, 0, ',', '.') }}</small>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted font-monospace" style="font-size: 0.75rem;">Fee Platform
                                    (5%)</small>
                                <small class="fw-semibold text-danger">- Rp
                                    {{ number_format($selectedWithdrawal->platform_fee, 0, ',', '.') }}</small>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted font-monospace" style="font-size: 0.75rem;">Fee Optimasi
                                    (15%)</small>
                                <small class="fw-semibold text-danger">- Rp
                                    {{ number_format($selectedWithdrawal->optimization_fee, 0, ',', '.') }}</small>
                            </div>
                            <div class="d-flex justify-content-between mb-0">
                                <small class="text-muted font-monospace" style="font-size: 0.75rem;">Biaya
                                    Merchant</small>
                                <small class="fw-semibold text-danger">- Rp
                                    {{ number_format($selectedWithdrawal->merchant_fee, 0, ',', '.') }}</small>
                            </div>

                            <div class="border-top border-dashed my-2"></div>

                            <div class="d-flex justify-content-between">
                                <small class="fw-bold text-uppercase" style="font-size: 0.65rem;">Total
                                    Potongan</small>
                                @php
                                    $totalPotongan =
                                        $selectedWithdrawal->ads_fee +
                                        $selectedWithdrawal->ads_vat +
                                        $selectedWithdrawal->platform_fee +
                                        $selectedWithdrawal->optimization_fee +
                                        $selectedWithdrawal->merchant_fee;
                                @endphp
                                <small class="fw-bold text-danger">Rp
                                    {{ number_format($totalPotongan, 0, ',', '.') }}</small>
                            </div>
                        </div>

                        {{-- Net Amount --}}
                        <div class="text-center p-3 rounded-4"
                            style="background: linear-gradient(135deg, #10b981, #059669);">
                            <small class="text-white opacity-75 d-block text-uppercase fw-bold mb-1"
                                style="font-size: 0.65rem; letter-spacing: 1px;">Dana Bersih Diterima</small>
                            <h3 class="text-white fw-bold mb-0">
                                Rp {{ number_format($selectedWithdrawal->net_amount, 0, ',', '.') }}
                            </h3>
                        </div>
                    </div>

                    {{-- Footer Actions --}}
                    <div class="p-4 pt-0 border-0">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.pencairan.detail', $selectedWithdrawal->id) }}" wire:navigate
                                class="btn btn-primary py-2 rounded-3 fw-bold">
                                <i class="bi bi-eye-fill me-1"></i> Lihat Rincian Lengkap
                            </a>
                            <button wire:click="closeReceipt"
                                class="btn btn-link btn-sm text-muted text-decoration-none">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
