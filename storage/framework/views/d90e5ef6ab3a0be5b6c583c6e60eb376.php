<?php
use Livewire\Attributes\Layout;
use App\Models\Fundraiser;
use Livewire\Component;
?>

<div>
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Detail Fundraiser</h5>
                    <p class="text-muted small mb-0">Informasi lengkap mengenai mitra fundraiser.</p>
                </div>
                <a href="<?php echo e(route('admin.fundraiser')); ?>" wire:navigate class="btn btn-light border">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">

            <div class="row">
                <!-- Left Column: Basic Info -->
                <div class="col-md-6">
                    <div class="card bg-light border-0 mb-3">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted small fw-bold mb-3">Informasi Dasar</h6>

                            <div class="mb-3 text-center">
                                <img src="<?php echo e($fundraiser->logo_url); ?>" class="rounded " width="120" alt="Logo">
                            </div>

                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted" width="150">Nama Yayasan</td>
                                    <td class="fw-bold"><?php echo e($fundraiser->foundation_name ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">User</td>
                                    <td class="fw-bold"><?php echo e($fundraiser->user->name); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Email</td>
                                    <td class="fw-bold"><?php echo e($fundraiser->user->email); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Alamat Kantor</td>
                                    <td class="fw-bold"><?php echo e($fundraiser->office_address ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status</td>
                                    <td>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fundraiser->status == 'approved'): ?>
                                            <span class="badge bg-success">Approved</span>
                                        <?php elseif($fundraiser->status == 'pending'): ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Rejected</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Terdaftar</td>
                                    <td class="fw-bold"><?php echo e($fundraiser->created_at->format('d M Y, H:i')); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Bank Info -->
                    <div class="card border-primary border-2 mb-3">
                        <div class="card-body">
                            <h6 class="text-uppercase text-primary small fw-bold mb-3">Informasi Rekening</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted" width="150">Nama Bank</td>
                                    <td class="fw-bold"><?php echo e($fundraiser->bank_name ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Nama Pemilik</td>
                                    <td class="fw-bold"><?php echo e($fundraiser->bank_account_name ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">No. Rekening</td>
                                    <td class="fw-bold"><?php echo e($fundraiser->bank_account_number ?? '-'); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Documents -->
                <div class="col-md-6">
                    <div class="card bg-light border-0 mb-3">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted small fw-bold mb-3">Dokumen Legal</h6>

                            <!-- Izin Lembaga -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Izin Lembaga</label>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fundraiser->permit_doc): ?>
                                    <div>
                                        <a href="<?php echo e($fundraiser->permit_doc_url); ?>" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-file-earmark-pdf"></i> Lihat Dokumen
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted small">Belum upload</p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <!-- SK Kumham -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small">SK Kumham</label>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fundraiser->legal_doc): ?>
                                    <div>
                                        <a href="<?php echo e($fundraiser->legal_doc_url); ?>" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-file-earmark-pdf"></i> Lihat Dokumen
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted small">Belum upload</p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <!-- Akta Notaris -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Akta Notaris</label>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fundraiser->notary_doc): ?>
                                    <div>
                                        <a href="<?php echo e($fundraiser->notary_doc_url); ?>" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-file-earmark-pdf"></i> Lihat Dokumen
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted small">Belum upload</p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <!-- NPWP -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small">NPWP</label>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fundraiser->tax_id): ?>
                                    <div>
                                        <a href="<?php echo e($fundraiser->tax_id_url); ?>" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-file-earmark-pdf"></i> Lihat Dokumen
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted small">Belum upload</p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <!-- Office Photo -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Foto Kantor</label>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fundraiser->office_image): ?>
                                    <div>
                                        <a href="<?php echo e($fundraiser->office_image_url); ?>" target="_blank">
                                            <img src="<?php echo e($fundraiser->office_image_url); ?>" class="img-fluid rounded"
                                                alt="Foto Kantor">
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted small">Belum upload</p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campaigns List -->
            <div class="card bg-light border-0 mt-3">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted small fw-bold mb-3">Campaign yang Dibuat</h6>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fundraiser->campaigns->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Target</th>
                                        <th>Terkumpul</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $fundraiser->campaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <tr>
                                            <td><?php echo e($campaign->title); ?></td>
                                            <td>Rp <?php echo e(number_format($campaign->target_amount, 0, ',', '.')); ?></td>
                                            <td>Rp <?php echo e(number_format($campaign->collected_amount, 0, ',', '.')); ?></td>
                                            <td>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($campaign->status == 'active'): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span
                                                        class="badge bg-secondary"><?php echo e(ucfirst($campaign->status)); ?></span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center">Belum ada campaign</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fundraiser->status == 'pending'): ?>
                <div class="mt-4 d-flex gap-2 justify-content-end">
                    <button wire:click="approve" wire:confirm="Setujui fundraiser ini?" class="btn btn-success">
                        <i class="bi bi-check-circle me-2"></i> Approve Fundraiser
                    </button>
                    <button wire:click="reject" wire:confirm="Tolak fundraiser ini?" class="btn btn-danger">
                        <i class="bi bi-x-circle me-2"></i> Reject Fundraiser
                    </button>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/3b78d7d8.blade.php ENDPATH**/ ?>