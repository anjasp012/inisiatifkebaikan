<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ isset($title) ? $title . ' | ' . config('app.name', 'Laravel') : config('app.name', 'Laravel') }}</title>
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">

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
    </style>

    <!-- Styles / Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="admin-layout" x-data="{ sidebarOpen: false }">
    <div class="admin-wrapper" @click.outside="sidebarOpen = false">
        <x-admin.sidebar />
        <main>
            <nav class="navbar navbar-expand-lg navbar-inisiatif-admin">
                <div class="container-fluid px-3 px-sm-4">
                    <div class="d-flex align-items-center gap-3">
                        <button class="btn btn-light bg-light border-0 navbar-toggler" type="button"
                            x-on:click="sidebarOpen = ! sidebarOpen">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="navbar-brand d-none d-sm-block">
                            <div class="title" x-data="{ time: '{{ now()->format('H:i:s') }}' }" x-init="setInterval(() => { time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }) }, 1000)">
                                <span x-text="time"></span>
                            </div>
                            <span class="desc">
                                {{ now()->locale('id')->translatedFormat('l, d F Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <div>
                                <small class="d-block text-end">{{ Auth::user()->name }}</small>
                                <strong class="d-block text-end">{{ Auth::user()->role }}</strong>
                            </div>
                            <span
                                class="bg-primary text-white">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('admin.settings') }}" wire:navigate>
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
                {{ $slot }}
            </div>
        </main>
    </div>

    <x-admin.toast />

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

    @stack('scripts')
</body>

</html>
