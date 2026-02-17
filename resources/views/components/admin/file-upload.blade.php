@props([
    'model',
    'id' => null,
    'label' => 'Unggah Gambar',
    'accept' => 'image/*',
    'preview' => null,
    'description' => 'PNG, JPG atau WebP',
])

@php
    $id = $id ?? $model;
@endphp

<div class="file-upload-wrapper" x-data="{
    isDropping: false,
    isUploading: false,
    progress: 0,
    focused: false
}" x-on:livewire-upload-start="isUploading = true"
    x-on:livewire-upload-finish="isUploading = false; progress = 0" x-on:livewire-upload-error="isUploading = false"
    x-on:livewire-upload-progress="progress = $event.detail.progress">

    <label for="{{ $id }}" class="file-upload-dropzone"
        :class="{ 'is-dropping': isDropping, 'is-focused': focused }" @dragover.prevent="isDropping = true"
        @dragleave.prevent="isDropping = false" @drop.prevent="isDropping = false" @focusin="focused = true"
        @focusout="focused = false">

        <input type="file" id="{{ $id }}" wire:model="{{ $model }}" class="file-upload-input"
            accept="{{ $accept }}" @change="focused = false">

        {{-- Main Container --}}
        <div class="file-upload-content w-100 h-100 position-relative">

            {{-- Preview Image --}}
            @if ($preview)
                <div class="preview-layer">
                    <img src="{{ $preview }}" class="img-preview-main">
                    <div class="edit-overlay">
                        <div class="glass-pill">
                            <i class="bi bi-camera-fill me-2"></i> Ganti Foto
                        </div>
                    </div>
                </div>
            @else
                {{-- Empty Placeholder --}}
                <div class="placeholder-layer d-flex flex-column align-items-center justify-content-center">
                    <div class="upload-icon-circle mb-2">
                        <i class="bi bi-image"></i>
                    </div>
                    <span class="fw-bold text-dark small">{{ $label }}</span>
                    <span class="text-muted extra-small mt-1">{{ $description }}</span>
                </div>
            @endif

            {{-- Loading Overlay (Smooth Fade) --}}
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

    @error($model)
        <div class="text-danger extra-small mt-2 px-1 fw-semibold d-flex align-items-center">
            <i class="bi bi-exclamation-circle-fill me-1"></i> {{ $message }}
        </div>
    @enderror
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
