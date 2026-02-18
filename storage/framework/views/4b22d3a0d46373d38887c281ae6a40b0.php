<?php
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Donation;
use Livewire\Component;
use Livewire\WithPagination;
?>

<?php
    $totalSuccess = \App\Models\Donation::where('status', 'success')->sum('amount');
    $pendingCount = \App\Models\Donation::where('status', 'pending')->count();
    $totalToday = \App\Models\Donation::whereDate('created_at', today())->where('status', 'success')->sum('amount');
?>

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
                            <h3 class="fw-bold mb-0">Rp <?php echo e(number_format($totalSuccess, 0, ',', '.')); ?></h3>
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
                            <h3 class="fw-bold mb-0"><?php echo e(number_format($pendingCount)); ?> Transaksi</h3>
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
                            <h3 class="fw-bold mb-0">Rp <?php echo e(number_format($totalToday, 0, ',', '.')); ?></h3>
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
                        <a href="<?php echo e(route('admin.donasi.tambah')); ?>" wire:navigate class="btn btn-primary text-white">
                            <i class="bi bi-plus-lg me-1"></i> Donasi Manual
                        </a>
                        <button wire:click="export" class="btn btn-outline-success">
                            <i class="bi bi-download me-1"></i> Export
                        </button>
                    </div>

                    <div wire:ignore class="d-inline-block w-150">
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->donations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $donation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr>
                            <td class="text-center text-muted small"><?php echo e($this->donations->firstItem() + $no); ?></td>
                            <td>
                                <div class="fw-bold text-primary small"><?php echo e($donation->transaction_id); ?></div>
                                <div class="x-small text-muted"><?php echo e($donation->created_at->format('d M Y, H:i')); ?></div>
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo e($donation->donor_name); ?></div>
                                <div class="x-small text-muted"><?php echo e($donation->donor_phone ?? '-'); ?></div>
                            </td>
                            <td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($donation->campaign): ?>
                                    <div class="fw-semibold text-truncate max-w-200"
                                        title="<?php echo e($donation->campaign->title); ?>">
                                        <?php echo e($donation->campaign->title); ?>

                                    </div>
                                    <div class="mt-1">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($donation->campaign->category): ?>
                                            <span
                                                class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 border border-primary border-opacity-10 extra-small fw-semibold">
                                                <i class="bi bi-tag-fill me-1"></i>
                                                <?php echo e($donation->campaign->category->name); ?>

                                            </span>
                                        <?php else: ?>
                                            <span
                                                class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 border border-secondary border-opacity-10 extra-small fw-semibold">
                                                -
                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">Rp <?php echo e(number_format($donation->amount, 0, ',', '.')); ?>

                                </div>
                            </td>
                            <td class="text-center">
                                <div class="small fw-semibold text-capitalize">
                                    <?php echo e($donation->bank ? $donation->bank->bank_name : $donation->payment_channel); ?>

                                </div>
                                <div class="x-small text-muted text-capitalize">
                                    <?php echo e($donation->bank ? $donation->bank->type : str_replace('_', ' ', $donation->payment_method)); ?>

                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($donation->paymentProofs->count() > 0): ?>
                                    <span
                                        class="badge bg-info bg-opacity-10 text-info extra-small mt-1 px-2 border border-info border-opacity-25">
                                        <i class="bi bi-image me-1"></i> BUKTI
                                        (<?php echo e($donation->paymentProofs->count()); ?>)
                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($donation->status == 'success'): ?>
                                    <span
                                        class="badge bg-success bg-opacity-10 text-success px-3 py-2 border border-success border-opacity-10">
                                        <i class="bi bi-check-circle-fill me-1"></i> Sukses
                                    </span>
                                <?php elseif($donation->status == 'pending'): ?>
                                    <span
                                        class="badge bg-warning bg-opacity-10 text-dark px-3 py-2 border border-warning border-opacity-25">
                                        <i class="bi bi-clock-fill me-1"></i> Menunggu
                                    </span>
                                <?php else: ?>
                                    <span
                                        class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 border border-danger border-opacity-10">
                                        <i class="bi bi-x-circle-fill me-1"></i> Gagal
                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="<?php echo e(route('admin.donasi.detail', $donation)); ?>" wire:navigate
                                        class="btn btn-sm btn-info text-white" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button wire:click="destroy(<?php echo e($donation->id); ?>)"
                                        wire:confirm="Yakin ingin menghapus data donasi ini?"
                                        class="btn btn-sm btn-danger text-white" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white border-top py-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                <div class="text-muted small">
                    Menampilkan <strong><?php echo e($this->donations->firstItem()); ?></strong> -
                    <strong><?php echo e($this->donations->lastItem()); ?></strong> dari
                    <strong><?php echo e($this->donations->total()); ?></strong> donasi
                </div>
                <div>
                    <?php echo e($this->donations->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/4345b579.blade.php ENDPATH**/ ?>