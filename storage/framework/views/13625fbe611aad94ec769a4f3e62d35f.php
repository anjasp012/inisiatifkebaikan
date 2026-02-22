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
?>

<div class="py-2">
    <!-- Header & Period Filter -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="fw-bold mb-0">Statistik Dashboard</h4>
            <p class="text-muted small mb-0">Ringkasan performa platform
                (<?php echo e([
                    'today' => 'Hari Ini',
                    'week' => 'Minggu Ini',
                    'month' => 'Bulan Ini',
                    'year' => 'Tahun Ini',
                ][$period] ?? $period); ?>)
            </p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <div class="d-inline-flex bg-white p-1 rounded-3 border">
                <button wire:click="$set('period', 'today')"
                    class="btn btn-sm px-3 <?php echo e($period == 'today' ? 'btn-primary text-white fw-medium' : 'btn-light border-0'); ?>">Hari</button>
                <button wire:click="$set('period', 'week')"
                    class="btn btn-sm px-3 <?php echo e($period == 'week' ? 'btn-primary text-white fw-medium' : 'btn-light border-0'); ?>">Minggu</button>
                <button wire:click="$set('period', 'month')"
                    class="btn btn-sm px-3 <?php echo e($period == 'month' ? 'btn-primary text-white fw-medium' : 'btn-light border-0'); ?>">Bulan</button>
                <button wire:click="$set('period', 'year')"
                    class="btn btn-sm px-3 <?php echo e($period == 'year' ? 'btn-primary text-white fw-medium' : 'btn-light border-0'); ?>">Tahun</button>
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
                            <h3 class="fw-bold mb-0"><?php echo e(number_format($totalDonorsUnique)); ?></h3>
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
                            <h3 class="fw-bold mb-0"><?php echo e(number_format($totalActiveCampaigns)); ?></h3>
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
                            <h3 class="fw-bold mb-0"><?php echo e(number_format($totalFundraisers)); ?></h3>
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
                            <h3 class="fw-bold mb-0">Rp <?php echo e(number_format($totalWithdrawn, 0, ',', '.')); ?></h3>
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
                        render(<?php echo \Illuminate\Support\Js::from($chartLabels)->toHtml() ?>, <?php echo \Illuminate\Support\Js::from($chartAmounts['success'])->toHtml() ?>, <?php echo \Illuminate\Support\Js::from($chartAmounts['failed'])->toHtml() ?>);
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
                    <h2 class="fw-bold mb-4">Rp <?php echo e(number_format($saldoTersedia, 0, ',', '.')); ?></h2>
                </div>
            </div>
            <div class="card card-dashboard">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Ringkasan Keuangan</h6>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>Total Penghimpunan</span>
                            <span class="fw-bold text-dark">Rp
                                <?php echo e(number_format($totalPenghimpunan, 0, ',', '.')); ?></span>
                        </div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                    </div>
                    <div class="mb-0">
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>Pencairan (Net)</span>
                            <span class="fw-bold text-dark">Rp
                                <?php echo e(number_format($totalDicairkan, 0, ',', '.')); ?></span>
                        </div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-success"
                                style="width: <?php echo e($totalPenghimpunan > 0 ? ($totalDicairkan / $totalPenghimpunan) * 100 : 0); ?>%">
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
                        <a href="<?php echo e(route('admin.donasi')); ?>"
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
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $recentDonations->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $donation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="fw-bold small text-dark"><?php echo e($donation->donor_name); ?></div>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($donation->paymentProofs->count() > 0): ?>
                                                <span
                                                    class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 extra-small">
                                                    <i class="bi bi-image"></i>
                                                </span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <div class="text-muted extra-small">
                                            <?php echo e($donation->created_at->diffForHumans()); ?>

                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div class="text-truncate small text-muted" style="max-width: 200px;">
                                            <?php echo e($donation->campaign->title ?? '-'); ?></div>
                                    </td>
                                    <td class="text-end pe-4 py-3">
                                        <div class="fw-bold text-success small">Rp
                                            <?php echo e(number_format($donation->amount, 0, ',', '.')); ?></div>
                                    </td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
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
                                class="btn btn-sm <?php echo e($campaignTab === 'views' ? 'btn-primary text-white fw-medium' : 'btn-outline-primary'); ?>">
                                <i class="bi bi-eye me-1"></i> Terbanyak Dikunjungi
                            </button>
                            <button wire:click="$set('campaignTab', 'donations')"
                                class="btn btn-sm <?php echo e($campaignTab === 'donations' ? 'btn-primary text-white fw-medium' : 'btn-outline-primary'); ?>">
                                <i class="bi bi-cash-stack me-1"></i> Top Donasi
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($campaignTab === 'views'): ?>
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
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->mostViewedCampaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <span
                                                class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2">#<?php echo e($loop->iteration); ?></span>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <img src="<?php echo e($campaign->thumbnail_url); ?>"
                                                    class="rounded me-2 avatar-xs object-fit-cover">
                                                <div class="text-truncate small fw-bold text-dark"
                                                    style="max-width: 180px;">
                                                    <?php echo e($campaign->title); ?></div>
                                            </div>
                                        </td>
                                        <td class="text-end pe-4 py-3">
                                            <div
                                                class="d-flex align-items-center justify-content-end text-muted small">
                                                <i class="bi bi-eye-fill me-1 text-primary"></i>
                                                <span
                                                    class="fw-bold"><?php echo e(number_format($campaign->views()->count())); ?></span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
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
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->topCampaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">#<?php echo e($loop->iteration); ?></span>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <img src="<?php echo e($campaign->thumbnail_url); ?>"
                                                    class="rounded me-2 avatar-xs object-fit-cover">
                                                <div class="text-truncate small fw-bold text-dark"
                                                    style="max-width: 180px;">
                                                    <?php echo e($campaign->title); ?></div>
                                            </div>
                                        </td>
                                        <td class="text-end pe-4 py-3">
                                            <div
                                                class="d-flex align-items-center justify-content-end text-muted small">
                                                <i class="bi bi-cash-stack me-1 text-success"></i>
                                                <span class="fw-bold text-success">Rp
                                                    <?php echo e(number_format($campaign->donations_sum_amount ?? 0, 0, ',', '.')); ?></span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </tbody>
                        </table>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/5cb1a7c0.blade.php ENDPATH**/ ?>