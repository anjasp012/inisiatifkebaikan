<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        <?php echo e(isset($title) ? $title . ' | Admin ' . config('app.name', 'Laravel') : 'Admin | ' . config('app.name', 'Laravel')); ?>

    </title>

    <link rel="shortcut icon" href="<?php echo e(asset('assets/images/logo-dashboard.png')); ?>" type="image/png">

    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Tom Select CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

    <!-- CKEditor 5 Superbuild -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/super-build/ckeditor.js"></script>

    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_orange.css">
    <style>
        :root {
            --ck-primary-color: #dc5207;
        }

        .ck-editor__editable {
            min-height: 300px;
            border-radius: 0 0 8px 8px !important;
        }

        .ck.ck-editor__top .ck-sticky-panel .ck-toolbar {
            border-radius: 8px 8px 0 0 !important;
            border-bottom: 1px solid #dee2e6 !important;
        }

        .ck.ck-editor__main>.ck-editor__editable.ck-focused {
            border-color: var(--ck-primary-color) !important;
            box-shadow: 0 0 0 0.25rem rgba(220, 82, 7, 0.25) !important;
        }

        .ck.ck-toolbar {
            background: #f8f9fa !important;
        }

        .ck.ck-button:hover {
            background: rgba(220, 82, 7, 0.1) !important;
        }

        .ck.ck-button.ck-on {
            background: #dc5207 !important;
            color: white !important;
        }

        .ck.ck-button.ck-on:hover {
            background: #be4606 !important;
        }

        .ck-content {
            font-size: 0.95rem;
        }

        .ck-content img {
            max-width: 100%;
            height: auto !important;
            aspect-ratio: auto !important;
            object-fit: contain;
        }
    </style>

    <!-- Styles / Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>
</head>

<body class="admin-layout" x-data="{ sidebarOpen: false }">
    <div class="admin-wrapper" @click.outside="sidebarOpen = false">
        <?php if (isset($component)) { $__componentOriginalbebe114f3ccde4b38d7462a3136be045 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbebe114f3ccde4b38d7462a3136be045 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbebe114f3ccde4b38d7462a3136be045)): ?>
<?php $attributes = $__attributesOriginalbebe114f3ccde4b38d7462a3136be045; ?>
<?php unset($__attributesOriginalbebe114f3ccde4b38d7462a3136be045); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbebe114f3ccde4b38d7462a3136be045)): ?>
<?php $component = $__componentOriginalbebe114f3ccde4b38d7462a3136be045; ?>
<?php unset($__componentOriginalbebe114f3ccde4b38d7462a3136be045); ?>
<?php endif; ?>
        <main>
            <nav class="navbar navbar-expand-lg navbar-inisiatif-admin">
                <div class="container-fluid px-3 px-sm-4">
                    <div class="d-flex align-items-center gap-3">
                        <button class="btn btn-light bg-light border-0 navbar-toggler" type="button"
                            x-on:click="sidebarOpen = ! sidebarOpen">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="navbar-brand d-none d-sm-block">
                            <div class="title" x-data="{ time: '<?php echo e(now()->format('H:i:s')); ?>' }" x-init="setInterval(() => { time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }) }, 1000)">
                                <span x-text="time"></span>
                            </div>
                            <span class="desc">
                                <?php echo e(now()->locale('id')->translatedFormat('l, d F Y')); ?>

                            </span>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <div>
                                <small class="d-block text-end"><?php echo e(Auth::user()->name); ?></small>
                                <strong class="d-block text-end"><?php echo e(Auth::user()->role); ?></strong>
                            </div>
                            <span
                                class="bg-primary text-white"><?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo e(route('admin.settings')); ?>" wire:navigate>
                                    <i class="bi bi-gear me-2"></i>
                                    Pengaturan
                                </a></li>
                            <li><button class="dropdown-item" x-on:click="$dispatch('doLogout')">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Keluar
                                </button></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="container-fluid p-3 p-sm-4">
                <?php echo e($slot); ?>

            </div>
        </main>
    </div>

    <?php if (isset($component)) { $__componentOriginal30b09ba64d8f9e6b0023e860875d7bb6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal30b09ba64d8f9e6b0023e860875d7bb6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.toast','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.toast'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal30b09ba64d8f9e6b0023e860875d7bb6)): ?>
<?php $attributes = $__attributesOriginal30b09ba64d8f9e6b0023e860875d7bb6; ?>
<?php unset($__attributesOriginal30b09ba64d8f9e6b0023e860875d7bb6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal30b09ba64d8f9e6b0023e860875d7bb6)): ?>
<?php $component = $__componentOriginal30b09ba64d8f9e6b0023e860875d7bb6; ?>
<?php unset($__componentOriginal30b09ba64d8f9e6b0023e860875d7bb6); ?>
<?php endif; ?>

    <div :class="sidebarOpen ? 'sidebar-overlay' : ''"></div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 500,
            once: true,
        });
    </script>
    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

    <!-- AutoNumeric JS -->
    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.8.1"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH C:\laragon\www\inisiatif\resources\views/layouts/admin.blade.php ENDPATH**/ ?>