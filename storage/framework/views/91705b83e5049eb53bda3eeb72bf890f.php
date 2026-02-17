<?php
use Livewire\Attributes\Layout;
use App\Models\Withdrawal;
use App\Models\Donation;
use Livewire\Component;
?>

<div>
    
    <div class="mb-4">
        <a href="<?php echo e(route('admin.pencairan')); ?>" wire:navigate class="btn btn-light border px-3">
            <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar Pencairan
        </a>
    </div>



    <div class="row g-4">
        
        <div class="col-lg-8">
            
            <div class="card border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="fw-bold mb-0">Rincian Pengajuan Pencairan</h5>
                </div>
                <div class="card-body">
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="text-muted x-small fw-bold text-uppercase mb-2 d-block">Pemohon</label>
                            <div class="p-3 bg-light rounded-3">
                                <div class="fw-bold"><?php echo e($withdrawal->requester_name); ?></div>
                                <div class="small text-muted">
                                    <?php echo e($withdrawal->fundraiser ? $withdrawal->fundraiser->user->email : 'Admin System'); ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted x-small fw-bold text-uppercase mb-2 d-block">Campaign</label>
                            <div class="p-3 bg-light rounded-3">
                                <div class="fw-bold text-truncate"><?php echo e($withdrawal->campaign->title); ?></div>
                                <div class="d-flex gap-2 align-items-center mt-1">
                                    <span class="small text-muted">Target: Rp
                                        <?php echo e(number_format($withdrawal->campaign->target_amount, 0, ',', '.')); ?></span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($withdrawal->campaign->is_optimized): ?>
                                        <span
                                            class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-10 x-small">
                                            <i class="bi bi-lightning-fill me-1"></i>Optimasi
                                        </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="row g-3 mb-4">
                        <div class="col-4">
                            <div class="p-3 bg-light rounded-3 text-center">
                                <div class="x-small text-muted fw-semibold text-uppercase mb-1">PPN Ads</div>
                                <div class="fw-bold text-primary">11%</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-light rounded-3 text-center">
                                <div class="x-small text-muted fw-semibold text-uppercase mb-1">Fee Platform</div>
                                <div class="fw-bold text-primary">5%</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-light rounded-3 text-center">
                                <div class="x-small text-muted fw-semibold text-uppercase mb-1">Fee Optimasi</div>
                                <div
                                    class="fw-bold <?php echo e($withdrawal->campaign->is_optimized ? 'text-primary' : 'text-muted'); ?>">
                                    <?php echo e($withdrawal->campaign->is_optimized ? '15%' : '-'); ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <?php if (isset($component)) { $__componentOriginal8a17b1f728b4d3b64d1256cf0f2b5c31 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a17b1f728b4d3b64d1256cf0f2b5c31 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.input-rupiah','data' => ['model' => 'ads_fee','label' => 'Biaya Ads (Manual)','placeholder' => '0','disabled' => $withdrawal->status != 'pending']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.input-rupiah'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'ads_fee','label' => 'Biaya Ads (Manual)','placeholder' => '0','disabled' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($withdrawal->status != 'pending')]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a17b1f728b4d3b64d1256cf0f2b5c31)): ?>
<?php $attributes = $__attributesOriginal8a17b1f728b4d3b64d1256cf0f2b5c31; ?>
<?php unset($__attributesOriginal8a17b1f728b4d3b64d1256cf0f2b5c31); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a17b1f728b4d3b64d1256cf0f2b5c31)): ?>
<?php $component = $__componentOriginal8a17b1f728b4d3b64d1256cf0f2b5c31; ?>
<?php unset($__componentOriginal8a17b1f728b4d3b64d1256cf0f2b5c31); ?>
<?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label x-small fw-bold text-muted text-uppercase">Biaya Merchant
                                (Otomatis)</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-light border-end-0 small">Rp</span>
                                <input type="text" value="<?php echo e(number_format($this->merchant_fee, 0, ',', '.')); ?>"
                                    class="form-control bg-light border-start-0 fw-bold text-danger" disabled>
                            </div>
                            <div class="x-small text-muted mt-1">Dihitung dari transaksi VA & E-Wallet</div>
                        </div>
                    </div>

                    
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tbody>
                                
                                <tr class="border-bottom">
                                    <td class="ps-0">
                                        <div class="fw-bold">Nominal Pengajuan</div>
                                        <small class="text-muted">Total dana diajukan mitra</small>
                                    </td>
                                    <td class="text-end pe-0 h5 fw-bold text-primary">
                                        Rp <?php echo e(number_format($withdrawal->amount, 0, ',', '.')); ?>

                                    </td>
                                </tr>

                                
                                <tr>
                                    <td colspan="2" class="ps-0 pb-0 pt-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 rounded-3"
                                                style="width: 28px; height: 28px;">
                                                <i class="bi bi-percent text-danger small"></i>
                                            </div>
                                            <span class="fw-bold">Potongan</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4">
                                        <div class="small">Biaya Ads (+PPN 11%)</div>
                                        <div class="x-small text-muted">Biaya iklan + PPN dihitung otomatis</div>
                                    </td>
                                    <td class="text-end pe-0 text-danger fw-semibold">
                                        - Rp
                                        <?php echo e(number_format($withdrawal->ads_fee + $withdrawal->ads_vat, 0, ',', '.')); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4">
                                        <div class="small">Fee Platform (5%)</div>
                                        <div class="x-small text-muted">Biaya pemeliharaan sistem</div>
                                    </td>
                                    <td class="text-end pe-0 text-danger fw-semibold">
                                        - Rp <?php echo e(number_format($withdrawal->platform_fee, 0, ',', '.')); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4">
                                        <div class="small">Fee Optimasi (15%)</div>
                                        <div class="x-small text-muted">Hanya campaign berlabel "Optimasi"</div>
                                    </td>
                                    <td
                                        class="text-end pe-0 fw-semibold <?php echo e($withdrawal->optimization_fee > 0 ? 'text-danger' : 'text-muted'); ?>">
                                        <?php echo e($withdrawal->optimization_fee > 0 ? '- Rp ' . number_format($withdrawal->optimization_fee, 0, ',', '.') : 'Rp 0'); ?>

                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <td class="ps-4">
                                        <div class="small">Biaya Merchant</div>
                                        <div class="x-small text-muted">Kalkulasi biaya admin payment gateway</div>
                                    </td>
                                    <td class="text-end pe-0 text-danger fw-semibold">
                                        - Rp <?php echo e(number_format($withdrawal->merchant_fee, 0, ',', '.')); ?>

                                    </td>
                                </tr>

                                
                                <?php
                                    $totalPotongan =
                                        $withdrawal->ads_fee +
                                        $withdrawal->ads_vat +
                                        $withdrawal->platform_fee +
                                        $withdrawal->optimization_fee +
                                        $withdrawal->merchant_fee;
                                ?>
                                <tr class="border-bottom">
                                    <td class="ps-4">
                                        <div class="fw-bold text-danger small">Total Potongan</div>
                                    </td>
                                    <td class="text-end pe-0 fw-bold text-danger">
                                        - Rp <?php echo e(number_format($totalPotongan, 0, ',', '.')); ?>

                                    </td>
                                </tr>

                                
                                <tr>
                                    <td class="ps-0 pt-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-3"
                                                style="width: 28px; height: 28px;">
                                                <i class="bi bi-check-lg text-success small"></i>
                                            </div>
                                            <span class="fw-bold text-success">Diterima</span>
                                        </div>
                                    </td>
                                    <td class="text-end pe-0 pt-3">
                                        <div class="h4 fw-bold text-success mb-0">
                                            Rp <?php echo e(number_format($withdrawal->net_amount, 0, ',', '.')); ?>

                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-lg-4">
            
            <div class="card border-0 mb-4">
                <div class="card-body p-4 text-center">
                    <h6 class="text-uppercase text-muted small fw-bold mb-4">Status Pencairan</h6>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($withdrawal->status == 'pending'): ?>
                        <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 rounded-3 mb-3"
                            style="width: 72px; height: 72px;">
                            <i class="bi bi-hourglass-split fs-1 text-warning"></i>
                        </div>
                        <h5 class="fw-bold">Menunggu Verifikasi</h5>
                        <p class="small text-muted mb-4 px-3">Tinjau rincian biaya lalu setujui atau tolak pengajuan.
                        </p>

                        <div class="d-grid gap-2">
                            <button wire:click="approve" wire:confirm="Setujui pencairan dana ini?"
                                class="btn btn-success py-2 fw-bold">
                                <i class="bi bi-check-circle-fill me-2"></i> Setujui Pencairan
                            </button>
                            <button wire:click="reject" wire:confirm="Tolak pencairan dana ini?"
                                class="btn btn-outline-danger py-2">
                                <i class="bi bi-x-circle me-2"></i> Tolak Pengajuan
                            </button>
                        </div>
                    <?php elseif($withdrawal->status == 'success'): ?>
                        <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 rounded-3 mb-3"
                            style="width: 72px; height: 72px;">
                            <i class="bi bi-check-lg fs-1 text-success"></i>
                        </div>
                        <h5 class="fw-bold">Selesai / Terbayar</h5>
                        <p class="small text-muted mb-0">Pencairan diselesaikan pada
                            <?php echo e($withdrawal->updated_at->format('d M Y, H:i')); ?></p>
                    <?php else: ?>
                        <div class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 rounded-3 mb-3"
                            style="width: 72px; height: 72px;">
                            <i class="bi bi-x-lg fs-1 text-danger"></i>
                        </div>
                        <h5 class="fw-bold">Pengajuan Ditolak</h5>
                        <p class="small text-muted mb-0">Pencairan ini dibatalkan oleh admin.</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            
            <div class="card border-0 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 d-flex align-items-center">
                        <i class="bi bi-bank me-2 text-primary"></i> Data Rekening Tujuan
                    </h6>
                    <div class="p-3 bg-light rounded-3 border-start border-4 border-primary">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($withdrawal->fundraiser): ?>
                            <div class="x-small text-muted mb-1">Nama Bank</div>
                            <div class="fw-bold mb-2"><?php echo e($withdrawal->fundraiser->bank_name ?? 'BCA'); ?></div>

                            <div class="x-small text-muted mb-1">Atas Nama</div>
                            <div class="fw-bold mb-2">
                                <?php echo e($withdrawal->fundraiser->bank_account_name ?? 'YAYASAN KEBAIKAN'); ?></div>

                            <div class="x-small text-muted mb-1">No. Rekening</div>
                            <div class="fs-4 fw-bold text-primary">
                                <?php echo e($withdrawal->fundraiser->bank_account_number ?? '1234567890'); ?></div>
                        <?php else: ?>
                            <div class="text-center py-2">
                                <span class="badge bg-secondary">Pencairan Internal / Tunai</span>
                                <p class="small text-muted mt-2 mb-0">Dana dicairkan langsung oleh admin untuk
                                    operasional campaign.</p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="card border-0 border-start border-4 border-info">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 x-small text-uppercase text-muted">
                        <i class="bi bi-calculator me-1"></i> Catatan Kalkulasi
                    </h6>
                    <ul class="list-unstyled mb-0 small text-muted">
                        <li class="mb-2"><i class="bi bi-dot text-info"></i> PPN 11% dihitung dari biaya iklan yg
                            diinput manual</li>
                        <li class="mb-2"><i class="bi bi-dot text-info"></i> Fee Platform 5% dari nominal pengajuan
                            (otomatis)</li>
                        <li class="mb-2"><i class="bi bi-dot text-info"></i> Fee Optimasi 15% hanya untuk campaign
                            yang dioptimasi admin</li>
                        <li><i class="bi bi-dot text-info"></i> Biaya Merchant = kalkulasi admin payment gateway
                            (VA/E-Wallet)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/a580190c.blade.php ENDPATH**/ ?>