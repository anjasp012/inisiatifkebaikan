<?php
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Donation;
use App\Models\PaymentProof;
use Livewire\Component;
use Livewire\WithFileUploads;
?>

<div>
    <div class="row g-4">
        <!-- Main Stats & Actions -->
        <div class="col-lg-8">
            <div class="card card-dashboard border-0 mb-4">
                <div class="card-body border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <h5 class="mb-1 fw-bold">Detail Transaksi</h5>
                                <p class="text-muted small mb-0">ID: <?php echo e($donation->transaction_id); ?></p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="<?php echo e(route('admin.donasi')); ?>" wire:navigate class="btn btn-light border">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="p-4 bg-light border-top">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <h6 class="text-uppercase text-muted extra-small fw-bold mb-2 ls-sm">NOMINAL DONASI</h6>
                                <div class="display-6 fw-bold text-primary mb-3">
                                    Rp <?php echo e(number_format($donation->amount, 0, ',', '.')); ?>

                                </div>
                                <div class="d-flex gap-2 mb-3">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($donation->status == 'success'): ?>
                                        <span class="badge rounded-pill px-3 py-2 bg-success text-white">
                                            <i class="bi bi-check-circle-fill me-1"></i> Status: Sukses
                                        </span>
                                    <?php elseif($donation->status == 'pending'): ?>
                                        <span class="badge rounded-pill px-3 py-2 bg-warning text-dark">
                                            <i class="bi bi-clock-fill me-1"></i> Status: Pending
                                        </span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill px-3 py-2 bg-danger text-white">
                                            <i class="bi bi-x-circle-fill me-1"></i> Status: Gagal
                                        </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <span class="badge rounded-pill px-3 py-2 bg-light text-dark border">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($donation->bank): ?>
                                            <?php echo e($donation->bank->type == 'manual' ? 'Transfer Manual' : 'Otomatis (' . ucfirst($donation->bank->type) . ')'); ?>

                                        <?php else: ?>
                                            <?php echo e(str_replace('_', ' ', $donation->payment_method)); ?>

                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-5 text-md-end">
                                <div class="p-3 bg-white rounded-4  border border-primary border-opacity-10">
                                    <small class="text-muted d-block mb-1">Metode Pembayaran</small>
                                    <h6 class="fw-bold mb-2 text-primary text-uppercase">
                                        <?php echo e($donation->bank ? $donation->bank->bank_name : str_replace('_', ' ', $donation->payment_method)); ?>

                                    </h6>
                                    <div class="bg-light p-2 rounded extra-small font-monospace text-break border">
                                        <?php echo e($donation->bank ? ($donation->bank->account_number ? $donation->bank->account_number . ' a/n ' . $donation->bank->account_name : $donation->bank->bank_code) : $donation->payment_code); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border-top">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3 d-flex align-items-center">
                                    <i class="bi bi-person-circle me-2 text-primary"></i> Data Donatur
                                </h6>
                                <div class="list-group list-group-flush border rounded-3 overflow-hidden">
                                    <div
                                        class="list-group-item d-flex justify-content-between align-items-center bg-light bg-opacity-50">
                                        <span class="text-muted small">Nama</span>
                                        <span class="fw-semibold"><?php echo e($donation->donor_name); ?>

                                            <?php echo $donation->is_anonymous ? '<small class="text-muted extra-small ms-1">(Anonim)</small>' : ''; ?></span>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($donation->donor_email): ?>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <span class="text-muted small">Email</span>
                                            <span class="fw-semibold"><?php echo e($donation->donor_email); ?></span>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="text-muted small">No. WhatsApp</span>
                                        <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', $donation->donor_phone)); ?>"
                                            target="_blank"
                                            class="fw-semibold text-decoration-none text-success d-flex align-items-center">
                                            <?php echo e($donation->donor_phone); ?> <i class="bi bi-whatsapp ms-2"></i>
                                        </a>
                                    </div>
                                    <div
                                        class="list-group-item d-flex justify-content-between align-items-center bg-light bg-opacity-50">
                                        <span class="text-muted small">Terdaftar</span>
                                        <span
                                            class="fw-semibold"><?php echo e($donation->user_id ? $donation->user->name : 'Non-User (Guest)'); ?></span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="text-muted small">Waktu Order</span>
                                        <span
                                            class="fw-semibold"><?php echo e($donation->created_at->translatedFormat('d M Y, H:i')); ?></span>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($donation->paid_at): ?>
                                        <div
                                            class="list-group-item d-flex justify-content-between align-items-center bg-success bg-opacity-10">
                                            <span class="text-success small fw-bold">Waktu Bayar</span>
                                            <span
                                                class="fw-bold text-success"><?php echo e($donation->paid_at->translatedFormat('d M Y, H:i')); ?></span>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($donation->message): ?>
                                    <div
                                        class="mt-4 p-3 bg-light border-start border-4 border-primary rounded-3 border-0 border-start">
                                        <h6 class="fw-bold extra-small text-muted text-uppercase mb-2">Pesan / Doa
                                            Donatur
                                        </h6>
                                        <p class="mb-0 fst-italic">"<?php echo e($donation->message); ?>"</p>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3 d-flex align-items-center">
                                    <i class="bi bi-megaphone-fill me-2 text-primary"></i> Campaign Terkait
                                </h6>
                                <div class="card border rounded-3 p-3 bg-light bg-opacity-10 border-0 shadow-sm">
                                    <div class="d-flex gap-3 mb-3">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($donation->campaign->thumbnail): ?>
                                            <img src="<?php echo e($donation->campaign->thumbnail_url); ?>"
                                                class="rounded avatar-md object-fit-cover">
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <div class="overflow-hidden flex-grow-1">
                                            <div class="fw-bold text-dark text-truncate">
                                                <?php echo e($donation->campaign->title); ?>

                                            </div>
                                            <div class="extra-small text-muted mb-1">
                                                <?php echo e($donation->campaign->category->name ?? 'Zakat & Wakaf'); ?></div>
                                            <div wire:ignore class="progress" style="height: 6px;">
                                                <?php $percent = min(100, ($donation->campaign->collected_amount / max(1, $donation->campaign->target_amount)) * 100); ?>
                                                <div class="progress-bar bg-primary"
                                                    style="width: <?php echo e($percent); ?>%">
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between mt-1 extra-small">
                                                <span class="fw-bold text-primary">Rp
                                                    <?php echo e(number_format($donation->campaign->collected_amount, 0, ',', '.')); ?></span>
                                                <span class="text-muted">Target:
                                                    <?php echo e(number_format($donation->campaign->target_amount, 0, ',', '.')); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-grid">
                                        <a href="<?php echo e(route('admin.campaign.ubah', $donation->campaign_id)); ?>"
                                            wire:navigate class="btn btn-outline-primary btn-sm">
                                            Lihat Campaign <i class="bi bi-box-arrow-up-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(
                $donation->payment_method == 'manual' ||
                    $donation->payment_method == 'manual_transfer' ||
                    $donation->paymentProofs->isNotEmpty()): ?>
                <div class="card card-dashboard border-0 mb-4">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold d-flex align-items-center">
                            <i class="bi bi-images me-2 text-primary"></i> Bukti Transfer
                            <span
                                class="badge bg-primary bg-opacity-10 text-primary ms-2"><?php echo e($donation->paymentProofs->count()); ?>

                                File</span>
                        </h6>
                        <div>
                            <input type="file" wire:model="newProofs" multiple class="d-none" id="addProofInput"
                                accept="image/*">
                            <label for="addProofInput"
                                class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold cursor-pointer mb-0">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Bukti admin
                            </label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div wire:loading wire:target="newProofs" class="text-primary small mb-3">
                            <div class="spinner-border spinner-border-sm me-2"></div> Sedang mengunggah...
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="extra-small text-muted text-uppercase fw-bold">
                                        <th class="ps-3 py-2">No</th>
                                        <th class="py-2">Bukti</th>
                                        <th class="py-2">Waktu Upload</th>
                                        <th class="text-end pe-3 py-2">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $donation->paymentProofs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proof): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <tr>
                                            <td class="ps-3 small text-muted"><?php echo e($loop->iteration); ?></td>
                                            <td>
                                                <img src="<?php echo e($proof->file_url); ?>" class="rounded border shadow-sm"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            </td>
                                            <td class="small">
                                                <?php echo e($proof->created_at->translatedFormat('d M Y, H:i')); ?>

                                            </td>
                                            <td class="text-end pe-3">
                                                <a href="<?php echo e($proof->file_url); ?>" target="_blank"
                                                    class="btn btn-sm btn-outline-primary py-0 px-2 extra-small">Lihat</a>
                                            </td>
                                        </tr>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-3 small text-muted">Belum ada
                                                bukti</td>
                                        </tr>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Sidebar Actions -->
        <div class="col-lg-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($donation->status == 'pending'): ?>
                <div class="card card-dashboard border-0 mb-4 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3 text-primary">Tindakan Admin</h5>
                        <p class="small text-muted mb-4">Verifikasi pembayaran ini sesuai dengan bukti transfer yang
                            diunggah donatur.</p>

                        <div class="bg-light p-3 rounded-3 text-dark mb-4 border">
                            <label class="form-label fw-bold small text-primary mb-2">SESUAIKAN NOMINAL
                                (OPSIONAL)</label>



                            <div class="input-group border rounded-3 overflow-hidden">
                                <span
                                    class="input-group-text bg-white border-0 extra-small fw-bold text-muted">Rp</span>
                                <input type="number" wire:model="editAmount"
                                    class="form-control border-0 bg-white fw-bold text-primary"
                                    placeholder="Check bukti bayar...">
                            </div>
                            <small class="text-muted mt-2 d-block extra-small">
                                UIbah nominal jika jumlah yang ditransfer berbeda.
                            </small>
                        </div>

                        <div class="d-grid gap-2">
                            <button wire:click="approve" wire:confirm="Anda yakin menyetujui donasi ini?"
                                class="btn btn-success text-white py-2 fw-bold">
                                <i class="bi bi-check-circle-fill me-2"></i> Setujui Donasi
                            </button>
                            <button wire:click="reject" wire:confirm="Anda yakin menolak donasi ini?"
                                class="btn btn-outline-danger py-2 fw-bold">
                                <i class="bi bi-x-circle-fill me-2"></i> Tolak Donasi
                            </button>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="card card-dashboard border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center <?php echo e($donation->status == 'success' ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10'); ?> rounded-3 mb-3"
                                style="width: 72px; height: 72px;">
                                <i
                                    class="bi <?php echo e($donation->status === 'success' ? 'bi-check-lg text-success' : 'bi-x-lg text-danger'); ?> fs-1"></i>
                            </div>
                            <h5 class="fw-bold">Donasi Telah Diproses</h5>
                            <p class="text-muted small">Status transaksi ini adalah
                                <strong><?php echo e(strtoupper($donation->status)); ?></strong>.
                            </p>
                        </div>

                        <div class="d-grid gap-2">
                            <button wire:click="revert"
                                wire:confirm="PENTING: Status akan kembali ke Pending dan Saldo Campaign akan dikurangi. Lanjutkan?"
                                class="btn btn-outline-warning btn-sm">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Revert ke Pending
                            </button>
                        </div>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="card card-dashboard border-0 border-start border-4 border-info">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 small opacity-75 text-uppercase">Log Notifikasi WA</h6>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->waLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="d-flex align-items-start mb-2">
                            <i
                                class="bi bi-chat-dots-fill me-2 <?php echo e($log->status === 'success' ? 'text-success' : 'text-danger'); ?> mt-1"></i>
                            <div>
                                <div
                                    class="small fw-bold <?php echo e($log->status === 'success' ? 'text-success' : 'text-danger'); ?>">
                                    <?php echo e($log->status === 'success' ? 'Berhasil Terkirim' : 'Gagal Kirim'); ?>

                                </div>
                                <div class="extra-small text-muted"><?php echo e($log->created_at->format('d/m H:i')); ?></div>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-chat-dots me-2"></i>
                            <span class="small">Belum ada notifikasi terkirim</span>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/f5fea251.blade.php ENDPATH**/ ?>