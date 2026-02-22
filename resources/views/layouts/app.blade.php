<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Facebook Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '956852468822918');

        // Track PageView on every navigation (including initial load)
        document.addEventListener('livewire:navigated', function() {
            fbq('track', 'PageView');
        });
    </script>
    <noscript>
        <img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=956852468822918&ev=PageView&noscript=1" />
    </noscript>
    <!-- End Facebook Pixel Code -->


    <!-- Meta SEO -->
    {!! seo($seoData ?? null) !!}
    <link rel="shortcut icon" href="{{ asset('assets/images/logo-dashboard.png') }}" type="image/png">

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
            min-height: 200px;
            border-radius: 0 0 12px 12px !important;
            border: 0 !important;
            background-color: #f8fafc !important;
        }

        .ck.ck-editor__top .ck-sticky-panel .ck-toolbar {
            border-radius: 12px 12px 0 0 !important;
            border: 0 !important;
            border-bottom: 1px solid #e2e8f0 !important;
            background-color: #f1f5f9 !important;
        }

        .ck.ck-editor__main>.ck-editor__editable.ck-focused {
            background-color: #fff !important;
            box-shadow: 0 0 0 4px rgba(220, 82, 7, 0.05) !important;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

</head>

<body class="app-layout">
    <div class="app-wrapper">
        {{ $slot }}
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

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
