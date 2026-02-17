<?php
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use App\Models\Bank;
use Livewire\Component;
use Livewire\WithPagination;
?>

<div>
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Manajemen Bank & Pembayaran</h5>
                    <p class="text-muted small mb-0">Kelola rekening bank manual dan integrasi payment gateway.</p>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <div class="dropdown">
                        <button class="btn btn-info text-white dropdown-toggle shadow-sm d-flex align-items-center gap-2"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false" wire:loading.attr="disabled">
                            <i class="bi bi-arrow-repeat"></i>
                            <span>Sync Gateway</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 mt-2"
                            style="border-radius: 12px;">
                            <li>
                                <button class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2 mb-1"
                                    type="button" wire:click="syncTripay" wire:loading.attr="disabled">
                                    <div class="bg-info bg-opacity-10 text-info rounded p-1"
                                        style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-credit-card"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold small">Sync Tripay</div>
                                        <div class="text-muted" style="font-size: 10px;">Update data from Tripay API
                                        </div>
                                    </div>
                                </button>
                            </li>
                            <li>
                                <button class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2"
                                    type="button" wire:click="syncMidtrans" wire:loading.attr="disabled">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded p-1"
                                        style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-shield-check"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold small">Sync Midtrans</div>
                                        <div class="text-muted" style="font-size: 10px;">Update common Midtrans channels
                                        </div>
                                    </div>
                                </button>
                            </li>
                        </ul>
                    </div>
                    <a href="<?php echo e(route('admin.bank.tambah')); ?>" wire:navigate class="btn btn-primary text-white">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Manual
                    </a>
                    <div class="position-relative">
                        <input type="text" class="form-control ps-5" placeholder="Cari bank..."
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
                        <th style="width: 100px;">LOGO</th>
                        <th>NAMA BANK</th>
                        <th>NOMOR REKENING</th>
                        <th>ATAS NAMA</th>
                        <th class="text-center">TIPE</th>
                        <th class="text-center">METODE</th>
                        <th class="text-center">DIGUNAKAN</th>
                        <th class="text-center">STATUS</th>
                        <th class="text-end pe-3">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->banks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr>
                            <td class="text-center"><?php echo e($this->banks->firstItem() + $no); ?></td>
                            <td>
                                <img src="<?php echo e($bank->logo_url); ?>" width="60px" class="rounded border"
                                    alt="<?php echo e($bank->bank_name); ?>">
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo e($bank->bank_name); ?></div>
                                <div class="x-small text-muted"><?php echo e($bank->bank_code); ?></div>
                            </td>
                            <td><?php echo e($bank->account_number ?? '-'); ?></td>
                            <td><?php echo e($bank->account_name ?? '-'); ?></td>
                            <td class="text-center">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bank->type == 'manual'): ?>
                                    <span
                                        class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 px-2 py-1">Manual</span>
                                <?php elseif($bank->type == 'tripay'): ?>
                                    <span
                                        class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-10 px-2 py-1 text-uppercase">Tripay</span>
                                <?php else: ?>
                                    <span
                                        class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 px-2 py-1 text-uppercase">Midtrans</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="text-center text-uppercase">
                                <span class="badge bg-light text-dark border x-small"><?php echo e($bank->method ?: '-'); ?></span>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold"><?php echo e(number_format($bank->donations_count)); ?></span>
                                <div class="x-small text-muted">Donasi</div>
                            </td>
                            <td class="text-center">
                                <div class="d-inline-flex align-items-center">
                                    <?php
                                        $isActive = $bank->is_active;
                                        $statusColor = $isActive ? 'success' : 'secondary';
                                    ?>
                                    <label
                                        class="badge bg-<?php echo e($statusColor); ?> bg-opacity-10 text-<?php echo e($statusColor); ?> py-2 px-3 border border-<?php echo e($statusColor); ?> border-opacity-10 d-flex align-items-center gap-2 cursor-pointer"
                                        for="statusSwitch<?php echo e($bank->id); ?>">
                                        <div class="form-check form-switch p-0 m-0" style="min-height: auto;">
                                            <input class="form-check-input cursor-pointer m-0" type="checkbox"
                                                role="switch" wire:click="toggleStatus(<?php echo e($bank->id); ?>)"
                                                id="statusSwitch<?php echo e($bank->id); ?>" <?php if($isActive): echo 'checked'; endif; ?>
                                                style="width: 1.8em; height: 1em;">
                                        </div>
                                        <span class="x-small fw-bold text-uppercase" style="letter-spacing: 0.5px;">
                                            <?php echo e($isActive ? 'Aktif' : 'Tersembunyi'); ?>

                                        </span>
                                    </label>
                                </div>
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="<?php echo e(route('admin.bank.ubah', $bank)); ?>" wire:navigate
                                        class="btn btn-sm btn-warning text-white" title="Ubah"><i
                                            class="bi bi-pencil"></i></a>
                                    <button wire:click="destroy(<?php echo e($bank->id); ?>)"
                                        wire:confirm="Anda yakin menghapus bank ini?"
                                        class="btn btn-sm btn-danger text-white" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                    <div class="text-muted small">
                        Menampilkan <strong><?php echo e($this->banks->firstItem()); ?></strong> -
                        <strong><?php echo e($this->banks->lastItem()); ?></strong> dari
                        <strong><?php echo e($this->banks->total()); ?></strong> bank
                    </div>
                    <div>
                        <?php echo e($this->banks->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/b186824c.blade.php ENDPATH**/ ?>