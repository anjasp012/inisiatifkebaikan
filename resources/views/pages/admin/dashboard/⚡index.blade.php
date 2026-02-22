<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Fundraiser;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\DB;

new #[Layout('layouts.admin')] #[Title('Dashboard')] class extends Component {
    public $period = 'today'; // today, week, month, year
    public $campaignTab = 'views'; // views, donations

    // Statistics
    public $totalDonations = 0;
    public $successDonations = 0;
    public $pendingDonations = 0;
    public $failedDonations = 0;

    public $totalAmount = 0;
    public $successAmount = 0;
    public $pendingAmount = 0;
    public $failedAmount = 0;

    // Point 17 Statistics
    public $totalFundraisers = 0;
    public $totalActiveCampaigns = 0;
    public $totalDonorsUnique = 0;
    public $totalWithdrawn = 0;

    // Financial Statistics
    public $saldoTersedia = 0;
    public $totalDicairkan = 0;
    public $totalPenghimpunan = 0;
    public $totalBiayaAds = 0;

    public $paymentMethodStats = [];
    public $recentDonations = [];

    // Chart Data
    public $chartLabels = [];
    public $chartTransactions = ['success' => [], 'pending' => [], 'failed' => []];
    public $chartAmounts = ['success' => [], 'pending' => [], 'failed' => []];
    public $paymentDetailStats = [];

    public function mount()
    {
        $this->loadStatistics();
    }

    public function updatedPeriod()
    {
        $this->loadStatistics();
        $this->dispatch('chart-update', [
            'labels' => $this->chartLabels,
            'amounts' => $this->chartAmounts['success'],
            'failed_amounts' => $this->chartAmounts['failed'],
        ]);
    }

    public function loadStatistics()
    {
        $query = Donation::query();

        // Filter by period
        switch ($this->period) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
        }

        // Count donations by status
        $this->totalDonations = $query->count();
        $this->successDonations = (clone $query)->where('status', 'success')->count();
        $this->pendingDonations = (clone $query)->where('status', 'pending')->count();
        $this->failedDonations = (clone $query)->whereIn('status', ['pending', 'failed'])->count();

        // Sum amounts by status
        $this->totalAmount = $query->sum('amount');
        $this->successAmount = (clone $query)->where('status', 'success')->sum('amount');
        $this->pendingAmount = (clone $query)->where('status', 'pending')->sum('amount');
        $this->failedAmount = (clone $query)->whereIn('status', ['pending', 'failed'])->sum('amount');

        // Point 17 Calculations
        $this->totalFundraisers = Fundraiser::count();
        $this->totalActiveCampaigns = Campaign::where('status', 'active')->count();
        $this->totalDonorsUnique = Donation::where('status', 'success')->distinct('donor_phone')->count();
        $this->totalWithdrawn = Withdrawal::where('status', 'success')->sum('amount');

        // Financial Statistics (Global/Overall for Balance)
        $this->totalPenghimpunan = Donation::where('status', 'success')->sum('amount');
        $this->totalDicairkan = Withdrawal::where('status', 'success')->sum('net_amount');

        $withdrawalStats = Withdrawal::where('status', 'success')->selectRaw('SUM(ads_fee) as ads, SUM(ads_vat) as vat, SUM(platform_fee) as platform, SUM(optimization_fee) as opt, SUM(merchant_fee) as merchant')->first();

        $this->totalBiayaAds = ($withdrawalStats->ads ?? 0) + ($withdrawalStats->vat ?? 0);

        // Saldo Tersedia = Total Himpunan - Total Pencairan (Net) - Semua Fee yang sudah diambil ke platform
        // Tapi untuk dashboard, user ingin melihat "Saldo Platform" yang belum dicairkan?
        // Let's stick to simple logic: Saldo = Himpunan - (Semua dana yang sudah keluar/net) - (Semua fee platform)
        // Sebenarnya Saldo Platform = Total Himpunan - Total Pencairan Bruto?
        // No, let's show Himpunan - Bruto Pencairan = Saldo yg belum dicairkan.
        $this->saldoTersedia = $this->totalPenghimpunan - Withdrawal::where('status', 'success')->sum('amount');

        // Payment method statistics
        $this->paymentMethodStats = (clone $query)->select('payment_channel', DB::raw('count(*) as total'))->groupBy('payment_channel')->orderByDesc('total')->get();

        // Recent donations (real-time)
        $this->recentDonations = Donation::with(['campaign', 'paymentProofs'])
            ->latest()
            ->limit(10)
            ->get();

        // Calculate data for charts based on period
        $this->calculateChartData();
    }

    public function getMostViewedCampaignsProperty()
    {
        return Campaign::orderByViews('desc')->limit(5)->get();
    }

    public function getTopCampaignsProperty()
    {
        return Campaign::withSum(
            [
                'donations' => function ($q) {
                    $q->where('status', 'success');
                    if ($this->period == 'today') {
                        $q->whereDate('created_at', today());
                    } elseif ($this->period == 'week') {
                        $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    } elseif ($this->period == 'month') {
                        $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                    } elseif ($this->period == 'year') {
                        $q->whereYear('created_at', now()->year);
                    }
                },
            ],
            'amount',
        )
            ->orderByDesc('donations_sum_amount')
            ->limit(5)
            ->get();
    }

    public function calculateChartData()
    {
        $this->chartLabels = [];
        $this->chartTransactions = ['success' => [], 'pending' => [], 'failed' => []];
        $this->chartAmounts = ['success' => [], 'pending' => [], 'failed' => []];

        $labels = [];
        $successCount = [];
        $pendingCount = [];
        $failedCount = [];
        $successSum = [];
        $pendingSum = [];
        $failedSum = [];

        if ($this->period == 'today') {
            $dataAll = Donation::whereDate('created_at', today())->select(DB::raw('HOUR(created_at) as time_key'), 'status', DB::raw('count(*) as count'), DB::raw('sum(amount) as sum'))->groupBy('time_key', 'status')->get();

            for ($i = 0; $i < 24; $i++) {
                $labels[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
                $hourData = $dataAll->where('time_key', $i);
                $this->mapData($hourData, $successCount, $pendingCount, $failedCount, $successSum, $pendingSum, $failedSum);
            }
        } elseif ($this->period == 'week') {
            $dataAll = Donation::where('created_at', '>=', now()->subDays(6)->startOfDay())
                ->select(DB::raw('DATE(created_at) as time_key'), 'status', DB::raw('count(*) as count'), DB::raw('sum(amount) as sum'))
                ->groupBy('time_key', 'status')
                ->get();

            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->translatedFormat('D');
                $dayData = $dataAll->where('time_key', $date->toDateString());
                $this->mapData($dayData, $successCount, $pendingCount, $failedCount, $successSum, $pendingSum, $failedSum);
            }
        } elseif ($this->period == 'month') {
            $dataAll = Donation::where('created_at', '>=', now()->startOfMonth())
                ->select(DB::raw('DAY(created_at) as time_key'), 'status', DB::raw('count(*) as count'), DB::raw('sum(amount) as sum'))
                ->groupBy('time_key', 'status')
                ->get();

            $daysInMonth = now()->daysInMonth;
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $labels[] = $i;
                $dayData = $dataAll->where('time_key', $i);
                $this->mapData($dayData, $successCount, $pendingCount, $failedCount, $successSum, $pendingSum, $failedSum);
            }
        } elseif ($this->period == 'year') {
            $dataAll = Donation::whereYear('created_at', now()->year)->select(DB::raw('MONTH(created_at) as time_key'), 'status', DB::raw('count(*) as count'), DB::raw('sum(amount) as sum'))->groupBy('time_key', 'status')->get();

            for ($i = 1; $i <= 12; $i++) {
                $labels[] = \Carbon\Carbon::create()->month($i)->translatedFormat('M');
                $monthData = $dataAll->where('time_key', $i);
                $this->mapData($monthData, $successCount, $pendingCount, $failedCount, $successSum, $pendingSum, $failedSum);
            }
        }

        $this->chartLabels = $labels;
        $this->chartTransactions = ['success' => $successCount, 'pending' => $pendingCount, 'failed' => $failedCount];
        $this->chartAmounts = ['success' => $successSum, 'pending' => $pendingSum, 'failed' => $failedSum];

        // Detailed Payment Stats per period
        $query = Donation::query();
        switch ($this->period) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
        }
        $this->paymentDetailStats = (clone $query)->select('payment_channel', DB::raw('count(*) as total'), DB::raw('sum(amount) as sum'))->groupBy('payment_channel')->orderByDesc('total')->get();
    }

    private function mapData($collection, &$successCount, &$pendingCount, &$failedCount, &$successSum, &$pendingSum, &$failedSum)
    {
        $s = $collection->where('status', 'success')->first();
        $p = $collection->where('status', 'pending')->first();
        $f = $collection->where('status', 'failed')->first();

        $successCount[] = (int) ($s->count ?? 0);
        $pendingCount[] = (int) ($p->count ?? 0);
        $failedCount[] = (int) ($p->count ?? 0) + (int) ($f->count ?? 0);
        $successSum[] = (float) ($s->sum ?? 0);
        $pendingSum[] = (float) ($p->sum ?? 0);
        $failedSum[] = (float) ($p->sum ?? 0) + (float) ($f->sum ?? 0);
    }
};
?>

<div class="py-2">
    <!-- Header & Period Filter -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="fw-bold mb-0">Statistik Dashboard</h4>
            <p class="text-muted small mb-0">Ringkasan performa platform
                ({{ [
                    'today' => 'Hari Ini',
                    'week' => 'Minggu Ini',
                    'month' => 'Bulan Ini',
                    'year' => 'Tahun Ini',
                ][$period] ?? $period }})
            </p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <div class="d-inline-flex bg-white p-1 rounded-3 border">
                <button wire:click="$set('period', 'today')"
                    class="btn btn-sm px-3 {{ $period == 'today' ? 'btn-primary text-white fw-medium' : 'btn-light border-0' }}">Hari</button>
                <button wire:click="$set('period', 'week')"
                    class="btn btn-sm px-3 {{ $period == 'week' ? 'btn-primary text-white fw-medium' : 'btn-light border-0' }}">Minggu</button>
                <button wire:click="$set('period', 'month')"
                    class="btn btn-sm px-3 {{ $period == 'month' ? 'btn-primary text-white fw-medium' : 'btn-light border-0' }}">Bulan</button>
                <button wire:click="$set('period', 'year')"
                    class="btn btn-sm px-3 {{ $period == 'year' ? 'btn-primary text-white fw-medium' : 'btn-light border-0' }}">Tahun</button>
            </div>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard border-0 ">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 py-2 px-3 rounded-3 me-3">
                            <i class="bi bi-people text-primary fs-3"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 small opacity-75">Total Donatur</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($totalDonorsUnique) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard border-0 ">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 py-2 px-3 rounded-3 me-3">
                            <i class="bi bi-megaphone text-info fs-3"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 small opacity-75">Campaign Berjalan</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($totalActiveCampaigns) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard border-0 ">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 py-2 px-3 rounded-3 me-3">
                            <i class="bi bi-person-workspace text-warning fs-3"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 small opacity-75">Mitra Fundraiser</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($totalFundraisers) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-dashboard border-0 ">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 py-2 px-3 rounded-3 me-3">
                            <i class="bi bi-cash-stack text-success fs-3"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 small opacity-75">Total Pencairan</h6>
                            <h3 class="fw-bold mb-0">Rp {{ number_format($totalWithdrawn, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Main Chart Section -->
        <div class="col-lg-8">
            <div class="card card-dashboard" x-data="{
                s: true,
                f: true,
                init() {
                    let chart = null;
                    const render = (l, s_data, f_data) => {
                        const ctx = this.$refs.canvas.getContext('2d');
                        if (chart) chart.destroy();
            
                        const gs = ctx.createLinearGradient(0, 0, 0, 400);
                        gs.addColorStop(0, 'rgba(37, 99, 235, 0.18)');
                        gs.addColorStop(1, 'rgba(37, 99, 235, 0.01)');
            
                        const gf = ctx.createLinearGradient(0, 0, 0, 400);
                        gf.addColorStop(0, 'rgba(239, 68, 68, 0.18)');
                        gf.addColorStop(1, 'rgba(239, 68, 68, 0.01)');
            
                        chart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: l,
                                datasets: [{
                                        label: 'Berhasil',
                                        data: s_data,
                                        borderColor: '#2563eb',
                                        backgroundColor: gs,
                                        fill: true,
                                        tension: 0.4,
                                        borderWidth: 3,
                                        pointRadius: 0,
                                        hidden: !this.s
                                    },
                                    {
                                        label: 'Gagal',
                                        data: f_data,
                                        borderColor: '#ef4444',
                                        backgroundColor: gf,
                                        fill: true,
                                        tension: 0.4,
                                        borderWidth: 2,
                                        pointRadius: 0,
                                        hidden: !this.f
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        mode: 'index',
                                        intersect: false,
                                        padding: 12,
                                        backgroundColor: '#1e293b',
                                        callbacks: {
                                            label: (c) => {
                                                let v = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(c.parsed.y);
                                                return `${c.dataset.label}: ${v}`;
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        grid: { color: '#f8fafc' },
                                        ticks: {
                                            font: { size: 10 },
                                            callback: (v) => v >= 1000000 ? 'Rp ' + (v / 1000000) + 'jt' : (v >= 1000 ? 'Rp ' + (v / 1000) + 'rb' : 'Rp ' + v)
                                        }
                                    },
                                    x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                                }
                            }
                        });
                    };
            
                    this.$nextTick(() => {
                        render(@js($chartLabels), @js($chartAmounts['success']), @js($chartAmounts['failed']));
                    });
            
                    Livewire.on('chart-update', (e) => {
                        const d = Array.isArray(e) ? e[0] : e;
                        render(d.labels, d.amounts, d.failed_amounts);
                    });
            
                    this.$watch('s', (v) => {
                        if (chart) {
                            chart.setDatasetVisibility(0, v);
                            chart.update();
                        }
                    });
                    this.$watch('f', (v) => {
                        if (chart) {
                            chart.setDatasetVisibility(1, v);
                            chart.update();
                        }
                    });
                }
            }">
                <div
                    class="card-body border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h5 class="fw-bold mb-1 text-dark">Tren Donasi Masuk</h5>
                        <p class="text-muted small mb-0">Visualisasi data donasi berhasil vs belum terbayar.</p>
                    </div>
                    <div class="d-flex align-items-center gap-1 bg-light p-1 rounded-pill border shadow-sm ls-lg"
                        style="min-width: 220px;">
                        <button type="button" @click="s = !s"
                            :class="s ? 'bg-white shadow-sm' : 'text-muted opacity-50'"
                            :style="s ? 'color: #2563eb !important' : ''"
                            class="btn btn-sm border-0 flex-fill rounded-pill py-2 px-3 transition-200 d-flex align-items-center justify-content-center gap-2">
                            <span class="rounded-circle d-inline-block"
                                style="width: 8px; height: 8px; background: #2563eb;"></span>
                            <span class="extra-small fw-bold">Berhasil</span>
                        </button>
                        <button type="button" @click="f = !f"
                            :class="f ? 'bg-white shadow-sm text-danger' : 'text-muted opacity-50'"
                            class="btn btn-sm border-0 flex-fill rounded-pill py-2 px-3 transition-200 d-flex align-items-center justify-content-center gap-2">
                            <span class="rounded-circle d-inline-block"
                                style="width: 8px; height: 8px; background: #ef4444;"></span>
                            <span class="extra-small fw-bold">Gagal</span>
                        </button>
                    </div>
                </div>
                <div class="card-body px-4 pb-4" wire:ignore>
                    <div style="height: 300px;"><canvas x-ref="canvas"></canvas></div>
                </div>
            </div>
        </div>
        <!-- Balance Breakdown Section -->
        <div class="col-lg-4">
            <div class="card card-dashboard bg-dark text-white mb-4"
                style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="text-white-50 small fw-bold mb-0 ls-widest mt-1">SALDO TERSEDIA</h6>
                        <i class="bi bi-wallet2 fs-4 text-white-50"></i>
                    </div>
                    <h2 class="fw-bold mb-4">Rp {{ number_format($saldoTersedia, 0, ',', '.') }}</h2>
                </div>
            </div>
            <div class="card card-dashboard">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Ringkasan Keuangan</h6>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>Total Penghimpunan</span>
                            <span class="fw-bold text-dark">Rp
                                {{ number_format($totalPenghimpunan, 0, ',', '.') }}</span>
                        </div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                    </div>
                    <div class="mb-0">
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>Pencairan (Net)</span>
                            <span class="fw-bold text-dark">Rp
                                {{ number_format($totalDicairkan, 0, ',', '.') }}</span>
                        </div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-success"
                                style="width: {{ $totalPenghimpunan > 0 ? ($totalDicairkan / $totalPenghimpunan) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Recent Activity List -->
        <div class="col-lg-6">
            <div class="card card-dashboard">
                <div class="card-body border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1">Donasi Terkini</h5>
                            <p class="text-muted small mb-0">Transaksi donasi yang baru saja masuk.</p>
                        </div>
                        <a href="{{ route('admin.donasi') }}"
                            class="btn btn-light btn-sm px-3 rounded-pill fw-bold">Lihat Semua</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <thead class="table-light">
                            <tr class="small text-muted text-uppercase">
                                <th class="ps-4">Donatur</th>
                                <th>Program</th>
                                <th class="text-end pe-4">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentDonations->take(6) as $donation)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="fw-bold small text-dark">{{ $donation->donor_name }}</div>
                                            @if ($donation->paymentProofs->count() > 0)
                                                <span
                                                    class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 extra-small">
                                                    <i class="bi bi-image"></i>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-muted extra-small">
                                            {{ $donation->created_at->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div class="text-truncate small text-muted" style="max-width: 200px;">
                                            {{ $donation->campaign->title ?? '-' }}</div>
                                    </td>
                                    <td class="text-end pe-4 py-3">
                                        <div class="fw-bold text-success small">Rp
                                            {{ number_format($donation->amount, 0, ',', '.') }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Campaign Stats Card with Tabs -->
        <div class="col-lg-6">
            <div class="card card-dashboard">
                <div class="card-body border-bottom">
                    <div class="d-flex flex-column gap-3">
                        <div>
                            <h5 class="fw-bold mb-1">Statistik Campaign</h5>
                            <p class="text-muted small mb-0">Campaign dengan performa terbaik.</p>
                        </div>
                        <!-- Tab Buttons -->
                        <div class="btn-group w-100 " role="group">
                            <button wire:click="$set('campaignTab', 'views')"
                                class="btn btn-sm {{ $campaignTab === 'views' ? 'btn-primary text-white fw-medium' : 'btn-outline-primary' }}">
                                <i class="bi bi-eye me-1"></i> Terbanyak Dikunjungi
                            </button>
                            <button wire:click="$set('campaignTab', 'donations')"
                                class="btn btn-sm {{ $campaignTab === 'donations' ? 'btn-primary text-white fw-medium' : 'btn-outline-primary' }}">
                                <i class="bi bi-cash-stack me-1"></i> Top Donasi
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    @if ($campaignTab === 'views')
                        <!-- Most Viewed Campaigns -->
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="table-light">
                                <tr class="small text-muted text-uppercase">
                                    <th class="ps-4">No</th>
                                    <th>Campaign</th>
                                    <th class="text-end pe-4">Kunjungan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($this->mostViewedCampaigns as $campaign)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <span
                                                class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2">#{{ $loop->iteration }}</span>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $campaign->thumbnail_url }}"
                                                    class="rounded me-2 avatar-xs object-fit-cover">
                                                <div class="text-truncate small fw-bold text-dark"
                                                    style="max-width: 180px;">
                                                    {{ $campaign->title }}</div>
                                            </div>
                                        </td>
                                        <td class="text-end pe-4 py-3">
                                            <div
                                                class="d-flex align-items-center justify-content-end text-muted small">
                                                <i class="bi bi-eye-fill me-1 text-primary"></i>
                                                <span
                                                    class="fw-bold">{{ number_format($campaign->views()->count()) }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <!-- Top Donation Campaigns -->
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="table-light">
                                <tr class="small text-muted text-uppercase">
                                    <th class="ps-4">No</th>
                                    <th>Campaign</th>
                                    <th class="text-end pe-4">Total Donasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($this->topCampaigns as $campaign)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">#{{ $loop->iteration }}</span>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $campaign->thumbnail_url }}"
                                                    class="rounded me-2 avatar-xs object-fit-cover">
                                                <div class="text-truncate small fw-bold text-dark"
                                                    style="max-width: 180px;">
                                                    {{ $campaign->title }}</div>
                                            </div>
                                        </td>
                                        <td class="text-end pe-4 py-3">
                                            <div
                                                class="d-flex align-items-center justify-content-end text-muted small">
                                                <i class="bi bi-cash-stack me-1 text-success"></i>
                                                <span class="fw-bold text-success">Rp
                                                    {{ number_format($campaign->donations_sum_amount ?? 0, 0, ',', '.') }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
