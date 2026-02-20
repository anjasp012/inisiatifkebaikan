<?php
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DonaturExport;
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
                    <button wire:click="export" class="btn btn-success text-white" title="Export Excel">
                        <i class="bi bi-download me-1"></i> Export
                    </button>
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
                        <th class="text-end pe-3">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr>
                            <td class="text-center"><?php echo e($this->users->firstItem() + $no); ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold avatar-md">
                                        <?php echo e(substr($item->name, 0, 1)); ?>

                                    </div>
                                    <div>
                                        <div class="fw-bold"><?php echo e($item->name); ?></div>
                                        <div class="extra-small text-muted">Bergabung:
                                            <?php echo e($item->created_at->format('d M Y')); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small fw-bold"><?php echo e($item->email); ?></div>
                                <div class="extra-small text-muted"><?php echo e($item->phone ?? '-'); ?></div>
                            </td>
                            <td class="text-center">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->isVerified()): ?>
                                    <span
                                        class="badge bg-success bg-opacity-10 text-success border border-success-subtle">
                                        <i class="bi bi-patch-check-fill me-1"></i> Verified
                                    </span>
                                <?php else: ?>
                                    <span
                                        class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle">
                                        <i class="bi bi-exclamation-circle-fill me-1"></i> Unverified
                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="text-center fw-bold text-success">
                                Rp <?php echo e(number_format($item->donations_sum_amount ?? 0, 0, ',', '.')); ?>

                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border px-2 py-1">
                                    <?php echo e(number_format($item->donations_count)); ?> Kali
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <button wire:click="destroy(<?php echo e($item->id); ?>)"
                                    wire:confirm="Anda yakin menghapus user ini beserta data seluruhnya?"
                                    class="btn btn-sm btn-danger text-white" title="Hapus"><i
                                        class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->users->isEmpty()): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2 opacity-25"></i>
                                Tidak ada data user ditemukan.
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                    <div class="text-muted small">
                        Menampilkan <strong><?php echo e($this->users->firstItem()); ?></strong> -
                        <strong><?php echo e($this->users->lastItem()); ?></strong> dari
                        <strong><?php echo e($this->users->total()); ?></strong> user
                    </div>
                    <div>
                        <?php echo e($this->users->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/4644ac82.blade.php ENDPATH**/ ?>