<?php
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use App\Models\Campaign;
use Livewire\Component;
use Livewire\WithPagination;
?>

<div>
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Campaign</h5>
                    <p class="text-muted small mb-0">Manajemen semua program kebaikan yang sedang berjalan.</p>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="<?php echo e(route('admin.campaign.tambah')); ?>" wire:navigate class="btn btn-primary text-white">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Campaign
                    </a>

                    <div class="position-relative">
                        <input type="text" class="form-control ps-5" placeholder="Cari campaign..."
                            wire:model.live.debounce.250ms="search" style="min-width: 250px;">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-borderless align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">NO</th>
                        <th style="width: 120px;">THUMBNAIL</th>
                        <th>JUDUL CAMPAIGN</th>
                        <th class="text-center">VIEWS</th>
                        <th class="text-center">DONATUR</th>
                        <th class="text-center">STATUS</th>
                        <th class="text-center">SLIDER</th>
                        <th>DIBUAT</th>
                        <th class="text-end pe-3">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->campaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr>
                            <td class="text-center"><?php echo e($this->campaigns->firstItem() + $no); ?></td>
                            <td>
                                <img loading="lazy" src="<?php echo e($campaign->thumbnail_url); ?>" width="100px" class="rounded"
                                    alt="<?php echo e($campaign->title); ?>">
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo e($campaign->title); ?></div>
                                <div class="small text-muted mb-1">
                                    <?php echo e($campaign->fundraiser->foundation_name ?? 'Inisiatif Kebaikan'); ?></div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($campaign->category): ?>
                                    <span
                                        class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 border border-primary border-opacity-10"
                                        style="font-size: 10px; font-weight: 600;">
                                        <i class="bi bi-tag-fill me-1"></i>
                                        <?php echo e($campaign->category->name); ?>

                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-eye me-1"></i> <?php echo e(number_format($campaign->views_count)); ?>

                                </span>
                            </td>
                            <td class="text-center">
                                <span
                                    class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10">
                                    <i class="bi bi-people-fill me-1"></i>
                                    <?php echo e(number_format($campaign->donations_count)); ?>

                                </span>
                            </td>
                            <td class="text-center">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($campaign->status == 'completed'): ?>
                                    <span
                                        class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 border border-primary border-opacity-10">
                                        <i class="bi bi-check-circle-fill me-1"></i> Selesai
                                    </span>
                                <?php else: ?>
                                    <div class="d-inline-flex align-items-center">
                                        <?php
                                            $isActive = $campaign->status == 'active';
                                            $statusColor = $isActive ? 'success' : 'secondary';
                                        ?>
                                        <label
                                            class="badge bg-<?php echo e($statusColor); ?> bg-opacity-10 text-<?php echo e($statusColor); ?> py-2 px-3 border border-<?php echo e($statusColor); ?> border-opacity-10 d-flex align-items-center gap-2 cursor-pointer"
                                            for="statusSwitch<?php echo e($campaign->id); ?>">
                                            <div class="form-check form-switch p-0 m-0" style="min-height: auto;">
                                                <input class="form-check-input cursor-pointer m-0" type="checkbox"
                                                    role="switch" wire:click="toggleStatus(<?php echo e($campaign->id); ?>)"
                                                    id="statusSwitch<?php echo e($campaign->id); ?>" <?php if($isActive): echo 'checked'; endif; ?>
                                                    style="width: 1.8em; height: 1em;">
                                            </div>
                                            <span class="x-small fw-bold text-uppercase" style="letter-spacing: 0.5px;">
                                                <?php echo e($isActive ? 'Aktif' : 'Tersembunyi'); ?>

                                            </span>
                                        </label>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input cursor-pointer" type="checkbox" role="switch"
                                        wire:click="toggleSlider(<?php echo e($campaign->id); ?>)"
                                        id="sliderSwitch<?php echo e($campaign->id); ?>" <?php if($campaign->is_slider): echo 'checked'; endif; ?>>
                                </div>
                            </td>
                            <td><?php echo e($campaign->created_at->diffForHumans()); ?></td>
                            <td class="text-end pe-3">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="<?php echo e(route('admin.campaign.updates', $campaign)); ?>" wire:navigate
                                        class="btn btn-sm btn-info text-white" title="Kelola Update">
                                        <i class="bi bi-newspaper"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.campaign.ubah', $campaign)); ?>" wire:navigate
                                        class="btn btn-sm btn-warning text-white" title="Ubah"><i
                                            class="bi bi-pencil"></i></a>
                                    <button wire:click="destroy(<?php echo e($campaign->id); ?>)"
                                        wire:confirm="Anda yakin menghapus campaign ini?"
                                        class="btn btn-sm btn-danger text-white" title="Hapus">
                                        <span wire:loading.remove wire:target="destroy(<?php echo e($campaign->id); ?>)">
                                            <i class="bi bi-trash"></i>
                                        </span>
                                        <span wire:loading wire:target="destroy(<?php echo e($campaign->id); ?>)">
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
                        Menampilkan <strong><?php echo e($this->campaigns->firstItem()); ?></strong> -
                        <strong><?php echo e($this->campaigns->lastItem()); ?></strong> dari
                        <strong><?php echo e($this->campaigns->total()); ?></strong> campaign
                    </div>
                    <div>
                        <?php echo e($this->campaigns->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/01b1508f.blade.php ENDPATH**/ ?>