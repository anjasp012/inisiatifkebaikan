<?php
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->donateurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $donatur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr>
                            <td class="text-center"><?php echo e($this->donateurs->firstItem() + $no); ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold"
                                        style="width: 40px; height: 40px;">
                                        <?php echo e(substr($donatur->name, 0, 1)); ?>

                                    </div>
                                    <div class="fw-bold"><?php echo e($donatur->name); ?></div>
                                </div>
                            </td>
                            <td><?php echo e($donatur->email); ?></td>
                            <td class="text-center fw-bold text-success">
                                Rp <?php echo e(number_format($donatur->donations_sum_amount ?? 0, 0, ',', '.')); ?>

                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border px-2 py-1">
                                    <?php echo e(number_format($donatur->donations_count)); ?> Kali
                                </span>
                            </td>
                            <td>
                                <div class="small"><?php echo e($donatur->created_at->format('d M Y')); ?></div>
                                <div class="x-small text-muted"><?php echo e($donatur->created_at->diffForHumans()); ?></div>
                            </td>
                            <td class="text-end pe-3">
                                <button class="btn btn-sm btn-light border" title="Detail User (Soon)">
                                    <i class="bi bi-person-lines-fill"></i>
                                </button>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->donateurs->isEmpty()): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2 opacity-25"></i>
                                Tidak ada data donatur ditemukan.
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                    <div class="text-muted small">
                        Menampilkan <strong><?php echo e($this->donateurs->firstItem()); ?></strong> -
                        <strong><?php echo e($this->donateurs->lastItem()); ?></strong> dari
                        <strong><?php echo e($this->donateurs->total()); ?></strong> donatur
                    </div>
                    <div>
                        <?php echo e($this->donateurs->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/4644ac82.blade.php ENDPATH**/ ?>