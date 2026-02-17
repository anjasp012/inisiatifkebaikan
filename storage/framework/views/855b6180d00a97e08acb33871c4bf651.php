<?php
use Livewire\Attributes\Layout;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;
?>

<?php $__env->startPush('styles'); ?>
    
<?php $__env->stopPush(); ?>

<div>
    <div class="card card-dashboard border-0 overflow-hidden">
        <form wire:submit.prevent="save">
            <div class="card-body p-0">
                <ul class="nav nav-pills p-3 bg-white border-bottom gap-2" id="settingsTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active px-4 py-2 fw-bold" id="general-tab" data-bs-toggle="tab"
                            data-bs-target="#general" type="button" role="tab">
                            <i class="bi bi-gear me-2"></i>Umum
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4 py-2 fw-bold" id="tripay-tab" data-bs-toggle="tab"
                            data-bs-target="#tripay" type="button" role="tab">
                            <i class="bi bi-credit-card me-2"></i>Tripay
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4 py-2 fw-bold" id="midtrans-tab" data-bs-toggle="tab"
                            data-bs-target="#midtrans" type="button" role="tab">
                            <i class="bi bi-shield-check me-2"></i>Midtrans
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4 py-2 fw-bold" id="whatsapp-tab" data-bs-toggle="tab"
                            data-bs-target="#whatsapp" type="button" role="tab">
                            <i class="bi bi-whatsapp me-2"></i>WhaCenter (Notif)
                        </button>
                    </li>
                </ul>

                <div class="tab-content p-4" id="settingsTabContent">
                    <!-- General Settings -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label fw-bold small text-uppercase">Logo Website</label>
                                <?php if (isset($component)) { $__componentOriginal6384af2cfbb3fb249311eef9f601626b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6384af2cfbb3fb249311eef9f601626b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.file-upload','data' => ['model' => 'logo','label' => 'Logo Website','preview' => $logo
                                    ? $logo->temporaryUrl()
                                    : ($current_logo
                                        ? asset('storage/' . $current_logo)
                                        : asset('assets/images/logo.png'))]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.file-upload'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => 'logo','label' => 'Logo Website','preview' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($logo
                                    ? $logo->temporaryUrl()
                                    : ($current_logo
                                        ? asset('storage/' . $current_logo)
                                        : asset('assets/images/logo.png')))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6384af2cfbb3fb249311eef9f601626b)): ?>
<?php $attributes = $__attributesOriginal6384af2cfbb3fb249311eef9f601626b; ?>
<?php unset($__attributesOriginal6384af2cfbb3fb249311eef9f601626b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6384af2cfbb3fb249311eef9f601626b)): ?>
<?php $component = $__componentOriginal6384af2cfbb3fb249311eef9f601626b; ?>
<?php unset($__componentOriginal6384af2cfbb3fb249311eef9f601626b); ?>
<?php endif; ?>
                                <small class="text-muted d-block mt-2">Rekomendasi ukuran: 200x50px (PNG
                                    Transparan)</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase">Nama Website</label>
                                <input type="text" wire:model="website_name" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase">Nomor WhatsApp (CS)</label>
                                <input type="text" wire:model="whatsapp_number" class="form-control"
                                    placeholder="628xxx">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold small text-uppercase">Deskripsi Meta (SEO)</label>
                                <textarea wire:model="website_description" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold small text-uppercase">Teks Footer</label>
                                <input type="text" wire:model="footer_text" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Tripay Settings -->
                    <div class="tab-pane fade" id="tripay" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase">Merchant Code</label>
                                <input type="text" wire:model="tripay_merchant_code" class="form-control"
                                    placeholder="Txxxx">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase">Mode Environment</label>
                                <select wire:model="tripay_mode" class="form-select">
                                    <option value="sandbox">Sandbox (Testing)</option>
                                    <option value="production">Production (Live)</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small text-uppercase">API Key</label>
                                <input type="password" wire:model="tripay_api_key" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small text-uppercase">Private Key</label>
                                <input type="password" wire:model="tripay_private_key" class="form-control">
                            </div>
                        </div>
                        <div class="alert alert-info mt-4 border-0 bg-opacity-10 mb-0">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Dapatkan kredensial di dashboard Tripay Anda pada menu <strong>Akun > Pengaturan
                                API</strong>.
                        </div>
                    </div>

                    <!-- Midtrans Settings -->
                    <div class="tab-pane fade" id="midtrans" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase">Merchant ID</label>
                                <input type="text" wire:model="midtrans_merchant_id" class="form-control"
                                    placeholder="Gxxxx">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase">Mode Environment</label>
                                <select wire:model="midtrans_mode" class="form-select">
                                    <option value="sandbox">Sandbox (Testing)</option>
                                    <option value="production">Production (Live)</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small text-uppercase">Client Key</label>
                                <input type="text" wire:model="midtrans_client_key" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small text-uppercase">Server Key</label>
                                <input type="password" wire:model="midtrans_server_key" class="form-control">
                            </div>
                        </div>
                        <div class="alert alert-info mt-4 border-0 bg-opacity-10 mb-0">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Dapatkan kredensial di dashboard Midtrans Anda pada menu <strong>Settings > Access
                                Keys</strong>.
                        </div>
                    </div>

                    <!-- WhaCenter Settings -->
                    <div class="tab-pane fade" id="whatsapp" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-5">
                                <div class="card border-0 bg-lighter rounded-4 p-4 h-100">
                                    <h6 class="fw-bold text-dark mb-3">Konfigurasi Perangkat</h6>
                                    <label class="form-label fw-bold extra-small text-muted text-uppercase ls-1">Device
                                        ID WhaCenter</label>
                                    <div class="input-group bg-white rounded-3 overflow-hidden border border-light">
                                        <span class="input-group-text bg-transparent border-0 pe-2">
                                            <i class="bi bi-phone text-primary"></i>
                                        </span>
                                        <input type="text" wire:model="whacenter_device_id"
                                            class="form-control border-0 py-3 shadow-none"
                                            placeholder="Masukan Device ID Aktif">
                                    </div>
                                    <div class="mt-3 p-3 rounded-3 bg-white border border-light shadow-micro">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <div class="bg-success bg-opacity-10 py-1 px-2 rounded">
                                                <i class="bi bi-check2 text-success small"></i>
                                            </div>
                                            <span class="small fw-bold">Tips Koneksi</span>
                                        </div>
                                        <p class="extra-small text-muted mb-0">Pastikan perangkat Anda sudah terhubung
                                            (Scan QR) di dashboard WhaCenter agar pesan dapat terkirim otomatis.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div
                                    class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 border border-light">
                                    <div class="row g-0 h-100" x-data="{
                                        currentTpl: 'created',
                                        insertPlaceholder(slug, placeholder) {
                                            let el = document.getElementById('tpl_' + slug);
                                            let start = el.selectionStart;
                                            let end = el.selectionEnd;
                                            let text = el.value;
                                            let before = text.substring(0, start);
                                            let after = text.substring(end, text.length);
                                            el.value = before + placeholder + after;
                                            el.selectionStart = el.selectionEnd = start + placeholder.length;
                                            el.focus();
                                            // Trigger Livewire update
                                            el.dispatchEvent(new Event('input'));
                                        }
                                    }">
                                        <div class="col-md-4 bg-light bg-opacity-50 border-end border-light">
                                            <div class="p-3 border-bottom border-light bg-white">
                                                <h6 class="fw-bold mb-0 small"><i
                                                        class="bi bi-chat-quote-fill me-2 text-primary"></i>Template
                                                </h6>
                                            </div>
                                            <div class="list-group list-group-flush py-2">
                                                <button type="button" @click="currentTpl = 'created'"
                                                    :class="currentTpl === 'created' ? 'active-tpl' : 'text-muted'"
                                                    class="list-group-item list-group-item-action border-0 py-3 px-4 small fw-bold transition-all d-flex align-items-center justify-content-between bg-transparent">
                                                    Donasi Dibuat
                                                    <i class="bi bi-chevron-right extra-small"
                                                        x-show="currentTpl === 'created'"></i>
                                                </button>
                                                <button type="button" @click="currentTpl = 'confirmed'"
                                                    :class="currentTpl === 'confirmed' ? 'active-tpl' : 'text-muted'"
                                                    class="list-group-item list-group-item-action border-0 py-3 px-4 small fw-bold transition-all d-flex align-items-center justify-content-between bg-transparent">
                                                    Donasi Berhasil
                                                    <i class="bi bi-chevron-right extra-small"
                                                        x-show="currentTpl === 'confirmed'"></i>
                                                </button>
                                                <button type="button" @click="currentTpl = 'rejected'"
                                                    :class="currentTpl === 'rejected' ? 'active-tpl' : 'text-muted'"
                                                    class="list-group-item list-group-item-action border-0 py-3 px-4 small fw-bold transition-all d-flex align-items-center justify-content-between bg-transparent">
                                                    Dibatalkan
                                                    <i class="bi bi-chevron-right extra-small"
                                                        x-show="currentTpl === 'rejected'"></i>
                                                </button>
                                                <button type="button" @click="currentTpl = 'logs'"
                                                    :class="currentTpl === 'logs' ? 'active-tpl' : 'text-muted'"
                                                    class="list-group-item list-group-item-action border-0 py-3 px-4 small fw-bold transition-all d-flex align-items-center justify-content-between bg-transparent">
                                                    <span class="text-info"><i
                                                            class="bi bi-clock-history me-2"></i>Log Terakhir</span>
                                                    <i class="bi bi-chevron-right extra-small"
                                                        x-show="currentTpl === 'logs'"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-8 p-4 bg-white bd-tpl-area">
                                            
                                            <div x-show="currentTpl === 'created'" x-cloak>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span
                                                        class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill extra-small fw-bold border border-primary border-opacity-25">Trigger:
                                                        Pilih Pembayaran</span>
                                                </div>
                                                <textarea id="tpl_created" wire:model="template_donation_created"
                                                    class="form-control font-monospace border-light bg-light bg-opacity-25 p-3 rounded-3" rows="12"
                                                    style="font-size: 13px; resize: none;"></textarea>
                                                <div class="mt-4 pt-3 border-top border-light">
                                                    <p class="extra-small fw-bold text-muted text-uppercase ls-1 mb-2">
                                                        Klik untuk Sisipkan:</p>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <button type="button"
                                                            @click.prevent="insertPlaceholder('created', '{donor_name}')"
                                                            class="btn btn-sm btn-white border border-light shadow-micro extra-small fw-bold px-2 py-1">Name</button>
                                                        <button type="button"
                                                            @click.prevent="insertPlaceholder('created', '{campaign_title}')"
                                                            class="btn btn-sm btn-white border border-light shadow-micro extra-small fw-bold px-2 py-1">Program</button>
                                                        <button type="button"
                                                            @click.prevent="insertPlaceholder('created', '{amount}')"
                                                            class="btn btn-sm btn-white border border-light shadow-micro extra-small fw-bold px-2 py-1">Amount</button>
                                                        <button type="button"
                                                            @click.prevent="insertPlaceholder('created', '{payment_code}')"
                                                            class="btn btn-sm btn-white border border-light shadow-micro extra-small fw-bold px-2 py-1">Rek/VA</button>
                                                    </div>
                                                </div>
                                            </div>

                                            
                                            <div x-show="currentTpl === 'confirmed'" x-cloak>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span
                                                        class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill extra-small fw-bold border border-success border-opacity-25">Trigger:
                                                        Berhasil Verifikasi</span>
                                                </div>
                                                <textarea id="tpl_confirmed" wire:model="template_donation_confirmed"
                                                    class="form-control font-monospace border-light bg-light bg-opacity-25 p-3 rounded-3" rows="12"
                                                    style="font-size: 13px; resize: none;"></textarea>
                                                <div class="mt-4 pt-3 border-top border-light">
                                                    <p class="extra-small fw-bold text-muted text-uppercase ls-1 mb-2">
                                                        Klik untuk Sisipkan:</p>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <button type="button"
                                                            @click.prevent="insertPlaceholder('confirmed', '{donor_name}')"
                                                            class="btn btn-sm btn-white border border-light shadow-micro extra-small fw-bold px-2 py-1">Name</button>
                                                        <button type="button"
                                                            @click.prevent="insertPlaceholder('confirmed', '{campaign_title}')"
                                                            class="btn btn-sm btn-white border border-light shadow-micro extra-small fw-bold px-2 py-1">Program</button>
                                                        <button type="button"
                                                            @click.prevent="insertPlaceholder('confirmed', '{amount}')"
                                                            class="btn btn-sm btn-white border border-light shadow-micro extra-small fw-bold px-2 py-1">Amount</button>
                                                    </div>
                                                </div>
                                            </div>

                                            
                                            <div x-show="currentTpl === 'rejected'" x-cloak>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span
                                                        class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill extra-small fw-bold border border-danger border-opacity-25">Trigger:
                                                        Batal/Expired</span>
                                                </div>
                                                <textarea id="tpl_rejected" wire:model="template_donation_rejected"
                                                    class="form-control font-monospace border-light bg-light bg-opacity-25 p-3 rounded-3" rows="12"
                                                    style="font-size: 13px; resize: none;"></textarea>
                                                <div class="mt-4 pt-3 border-top border-light">
                                                    <p class="extra-small fw-bold text-muted text-uppercase ls-1 mb-2">
                                                        Klik untuk Sisipkan:</p>
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <button type="button"
                                                            @click.prevent="insertPlaceholder('rejected', '{donor_name}')"
                                                            class="btn btn-sm btn-white border border-light shadow-micro extra-small fw-bold px-2 py-1">Name</button>
                                                        <button type="button"
                                                            @click.prevent="insertPlaceholder('rejected', '{campaign_title}')"
                                                            class="btn btn-sm btn-white border border-light shadow-micro extra-small fw-bold px-2 py-1">Program</button>
                                                    </div>
                                                </div>
                                            </div>

                                            
                                            <div x-show="currentTpl === 'logs'" x-cloak>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <span
                                                        class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill extra-small fw-bold border border-info border-opacity-25">Daftar
                                                        10 Pesan Terakhir</span>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-sm extra-small">
                                                        <thead>
                                                            <tr class="text-muted text-uppercase ls-1">
                                                                <th>Waktu</th>
                                                                <th>Tujuan</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                                <tr class="align-middle">
                                                                    <td class="text-nowrap py-2">
                                                                        <?php echo e($log->created_at->format('H:i')); ?></td>
                                                                    <td class="fw-bold">
                                                                        <?php echo e(Str::limit($log->recipient, 12)); ?></td>
                                                                    <td>
                                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->status === 'success'): ?>
                                                                            <span class="text-success fw-bold"><i
                                                                                    class="bi bi-check-circle-fill me-1"></i>Ok</span>
                                                                        <?php else: ?>
                                                                            <span class="text-danger fw-bold"
                                                                                title="<?php echo e($log->error_message); ?>"><i
                                                                                    class="bi bi-x-circle-fill me-1"></i>Fail</span>
                                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                    </td>
                                                                </tr>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                                                <tr>
                                                                    <td colspan="3"
                                                                        class="text-center py-5 text-muted">
                                                                        <i
                                                                            class="bi bi-inbox fs-2 d-block mb-2 opacity-25"></i>
                                                                        Belum ada riwayat pengiriman
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="card-footer bg-white border-top p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="text-muted small">
                    <i class="bi bi-shield-check me-1"></i> Data sensitif disimpan dengan aman di database.
                </div>
                <button type="submit" class="btn btn-primary px-5 fw-bold" wire:loading.attr="disabled">
                    <span wire:loading.remove><i class="bi bi-save me-2"></i> Simpan Semua Pengaturan</span>
                    <span wire:loading>
                        <div class="spinner-border spinner-border-sm me-2 text-white"></div>Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>

    <div class="card border-0  mt-4 border-start border-4 border-info">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold mb-1">Informasi Sistem</h6>
                <p class="small text-muted mb-0">Versi: 1.0.0-beta | Lisensi: Enterprise Edition | Support:
                    support@inisiatif.org</p>
            </div>
            <i class="bi bi-cpu fs-2 text-info opacity-25"></i>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/ecfb6f37.blade.php ENDPATH**/ ?>