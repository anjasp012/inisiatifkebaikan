<?php
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use App\Models\Fundraiser;
use Livewire\Component;
use Livewire\WithPagination;
?>

<div>
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Fundraiser</h5>
                    <p class="text-muted small mb-0">Manajemen mitra yayasan dan penggalang dana.</p>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <select wire:model.live="statusFilter" class="form-select w-auto">
                        <option value="all">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    <div class="position-relative">
                        <input type="text" class="form-control ps-5" placeholder="Cari fundraiser..."
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
                        <th class="text-center">NO</th>
                        <th>LOGO</th>
                        <th>NAMA YAYASAN</th>
                        <th>USER</th>
                        <th>BANK</th>
                        <th>STATUS</th>
                        <th>DIBUAT</th>
                        <th class="text-end">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->fundraisers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $fundraiser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr>
                            <td class="text-center"><?php echo e($this->fundraisers->firstItem() + $no); ?></td>
                            <td>
                                <img loading="lazy" src="<?php echo e($fundraiser->logo_url); ?>" width="60px" class="rounded"
                                    alt="<?php echo e($fundraiser->foundation_name); ?>">
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo e($fundraiser->foundation_name ?? '-'); ?></div>
                                <small class="text-muted">NPWP: <?php echo e($fundraiser->tax_id ?? '-'); ?></small>
                            </td>
                            <td>
                                <div><?php echo e($fundraiser->user->name); ?></div>
                                <small class="text-muted"><?php echo e($fundraiser->user->email); ?></small>
                            </td>
                            <td>
                                <div class="fw-semibold"><?php echo e($fundraiser->bank_name ?? '-'); ?></div>
                                <small class="text-muted"><?php echo e($fundraiser->bank_account_number ?? '-'); ?></small>
                                <br>
                                <small class="text-muted"><?php echo e($fundraiser->bank_account_name ?? '-'); ?></small>
                            </td>
                            <td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fundraiser->status == 'approved'): ?>
                                    <span
                                        class="badge bg-success bg-opacity-10 text-success px-3 py-2 border border-success border-opacity-10">
                                        <i class="bi bi-check-circle-fill me-1"></i> Disetujui
                                    </span>
                                <?php elseif($fundraiser->status == 'pending'): ?>
                                    <span
                                        class="badge bg-warning bg-opacity-10 text-dark px-3 py-2 border border-warning border-opacity-25">
                                        <i class="bi bi-clock-fill me-1"></i> Menunggu
                                    </span>
                                <?php else: ?>
                                    <span
                                        class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 border border-danger border-opacity-10">
                                        <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td><?php echo e($fundraiser->created_at->diffForHumans()); ?></td>
                            <td class="text-end">
                                <div class="d-flex gap-1 align-items-center justify-content-end">
                                    <a href="<?php echo e(route('admin.fundraiser.detail', $fundraiser)); ?>" wire:navigate
                                        class="btn btn-sm btn-info text-white" title="Detail"><i
                                            class="bi bi-eye"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                    <div class="text-muted small">
                        Menampilkan <strong><?php echo e($this->fundraisers->firstItem()); ?></strong> -
                        <strong><?php echo e($this->fundraisers->lastItem()); ?></strong> dari
                        <strong><?php echo e($this->fundraisers->total()); ?></strong> fundraiser
                    </div>
                    <div>
                        <?php echo e($this->fundraisers->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/62926d97.blade.php ENDPATH**/ ?>