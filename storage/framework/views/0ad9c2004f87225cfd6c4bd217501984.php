<?php
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use App\Models\CampaignCategory;
use Livewire\Component;
use Livewire\WithPagination;
?>

<div>
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Kategori Campaign</h5>
                    <p class="text-muted small mb-0">Manajemen kategori untuk pengelompokan campaign.</p>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="<?php echo e(route('admin.kategori-campaign.tambah')); ?>" wire:navigate
                        class="btn btn-primary text-white">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
                    </a>
                    <div class="position-relative">
                        <input type="text" class="form-control ps-5" placeholder="Cari kategori..."
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
                        <th>ICON KATEGORI</th>
                        <th>NAMA KATEGORI</th>
                        <th class="text-center">CAMPAIGN</th>
                        <th>STATUS</th>
                        <th>DIBUAT</th>
                        <th class="text-end">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr>
                            <td class="text-center"><?php echo e($this->categories->firstItem() + $no); ?></td>
                            <td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->is_bootstrap_icon): ?>
                                    <div class="d-flex align-items-center justify-content-center bg-light rounded-1"
                                        style="width: 50px; height: 50px;">
                                        <i class="bi <?php echo e($category->icon); ?> fs-3 text-primary"></i>
                                    </div>
                                <?php else: ?>
                                    <img loading="lazy" src="<?php echo e($category->icon_url); ?>" width="50px" height="50px"
                                        class="rounded-1 object-fit-cover" alt="<?php echo e($category->name); ?>">
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td><?php echo e($category->name); ?></td>
                            <td class="text-center">
                                <span class="fw-bold"><?php echo e(number_format($category->campaigns_count)); ?></span>
                                <div class="x-small text-muted">Program</div>
                            </td>
                            <td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->is_active): ?>
                                    <span
                                        class="badge bg-success bg-opacity-10 text-success px-3 py-2 border border-success border-opacity-10">
                                        <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                    </span>
                                <?php else: ?>
                                    <span
                                        class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 border border-secondary border-opacity-10">
                                        <i class="bi bi-x-circle-fill me-1"></i> Tidak Aktif
                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td><?php echo e($category->created_at->diffForHumans()); ?></td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="<?php echo e(route('admin.kategori-campaign.ubah', $category)); ?>" wire:navigate
                                        class="btn btn-sm btn-warning text-white" title="Ubah"><i
                                            class="bi bi-pencil"></i></a>
                                    <button wire:click="destroy(<?php echo e($category->id); ?>)"
                                        wire:confirm="Anda yakin menghapus kategori ini?"
                                        class="btn btn-sm btn-danger text-white" title="Hapus">
                                        <span wire:loading.remove wire:target="destroy(<?php echo e($category->id); ?>)">
                                            <i class="bi bi-trash"></i>
                                        </span>
                                        <span wire:loading wire:target="destroy(<?php echo e($category->id); ?>)">
                                            <div class="spinner-border spinner-border-sm" role="status"></div>
                                        </span>
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
                        Menampilkan <strong><?php echo e($this->categories->firstItem()); ?></strong> -
                        <strong><?php echo e($this->categories->lastItem()); ?></strong> dari
                        <strong><?php echo e($this->categories->total()); ?></strong> kategori
                    </div>
                    <div>
                        <?php echo e($this->categories->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/0a12a3d5.blade.php ENDPATH**/ ?>