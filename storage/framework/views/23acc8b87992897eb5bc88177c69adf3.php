<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'model',
    'id' => null,
    'label' => 'Unggah Gambar',
    'accept' => 'image/*',
    'preview' => null,
    'description' => 'PNG, JPG atau WebP',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'model',
    'id' => null,
    'label' => 'Unggah Gambar',
    'accept' => 'image/*',
    'preview' => null,
    'description' => 'PNG, JPG atau WebP',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $id = $id ?? $model;
?>

<div class="file-upload-wrapper" x-data="{
    isDropping: false,
    isUploading: false,
    progress: 0,
    focused: false
}" x-on:livewire-upload-start="isUploading = true"
    x-on:livewire-upload-finish="isUploading = false; progress = 0" x-on:livewire-upload-error="isUploading = false"
    x-on:livewire-upload-progress="progress = $event.detail.progress">

    <label for="<?php echo e($id); ?>" class="file-upload-dropzone"
        :class="{ 'is-dropping': isDropping, 'is-focused': focused }" @dragover.prevent="isDropping = true"
        @dragleave.prevent="isDropping = false" @drop.prevent="isDropping = false" @focusin="focused = true"
        @focusout="focused = false">

        <input type="file" id="<?php echo e($id); ?>" wire:model="<?php echo e($model); ?>" class="file-upload-input"
            accept="<?php echo e($accept); ?>" @change="focused = false">

        
        <div class="file-upload-content w-100 h-100 position-relative">

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($preview): ?>
                <div class="preview-layer">
                    <img src="<?php echo e($preview); ?>" class="img-preview-main">
                    <div class="edit-overlay">
                        <div class="glass-pill">
                            <i class="bi bi-camera-fill me-2"></i> Ganti Foto
                        </div>
                    </div>
                </div>
            <?php else: ?>
                
                <div class="placeholder-layer d-flex flex-column align-items-center justify-content-center">
                    <div class="upload-icon-circle mb-2">
                        <i class="bi bi-image"></i>
                    </div>
                    <span class="fw-bold text-dark small"><?php echo e($label); ?></span>
                    <span class="text-muted extra-small mt-1"><?php echo e($description); ?></span>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <div class="upload-loading-overlay" x-show="isUploading" x-transition.opacity>
                <div class="loading-card">
                    <div class="spinner-border text-primary mb-2" role="status" style="width: 2rem; height: 2rem;">
                    </div>
                    <div class="progress-pill">
                        <div class="progress-bar-fill" :style="`width: ${progress}%`"></div>
                        <span class="progress-label" x-text="`${progress}%`"></span>
                    </div>
                </div>
            </div>
        </div>
    </label>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = [$model];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="text-danger extra-small mt-2 px-1 fw-semibold d-flex align-items-center">
            <i class="bi bi-exclamation-circle-fill me-1"></i> <?php echo e($message); ?>

        </div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

<style>
    .file-upload-dropzone {
        display: block;
        width: 100%;
        height: 160px;
        background: #ffffff;
        border: 2px dashed #e2e8f0;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        position: relative;
        overflow: hidden;
    }

    .file-upload-dropzone:hover {
        border-color: #dc5207;
        background: #fdfaf9;
    }

    .file-upload-input {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        border-width: 0;
    }

    /* Layers */
    .preview-layer,
    .placeholder-layer {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
    }

    .img-preview-main {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .edit-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .file-upload-dropzone:hover .edit-overlay {
        opacity: 1;
    }

    .glass-pill {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(4px);
        padding: 6px 16px;
        border-radius: 50px;
        color: #1e293b;
        font-size: 0.75rem;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }

    .file-upload-dropzone:hover .glass-pill {
        transform: translateY(0);
    }

    .upload-icon-circle {
        width: 48px;
        height: 48px;
        background: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: #94a3b8;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }

    .file-upload-dropzone:hover .upload-icon-circle {
        color: #dc5207;
        border-color: #dc5207;
        transform: scale(1.1);
    }

    /* Loading Overlay */
    .upload-loading-overlay {
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(2px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 20;
    }

    .loading-card {
        background: white;
        padding: 16px;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 140px;
        border: 1px solid #f1f5f9;
    }

    .progress-pill {
        width: 100%;
        height: 6px;
        background: #f1f5f9;
        border-radius: 10px;
        position: relative;
        overflow: hidden;
        margin-top: 8px;
    }

    .progress-bar-fill {
        height: 100%;
        background: #dc5207;
        transition: width 0.3s ease;
    }

    .progress-label {
        font-size: 0.7rem;
        font-weight: 800;
        color: #dc5207;
        margin-top: 4px;
    }

    .extra-small {
        font-size: 0.65rem;
    }

    .is-dropping {
        border-color: #dc5207 !important;
        background: rgba(220, 82, 7, 0.05) !important;
    }
</style>
<?php /**PATH C:\laragon\www\inisiatif\resources\views/components/admin/file-upload.blade.php ENDPATH**/ ?>