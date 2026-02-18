<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!-- Meta SEO -->
    {!! seo($seoData ?? null) !!}

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

    <style>
        .ck-content img {
            max-width: 100%;
            height: auto !important;
            aspect-ratio: auto !important;
            object-fit: contain;
        }

        /* Responsive images for campaign descriptions, articles, etc. */
        .campaign-description img,
        .distribution-content img,
        .article-content img {
            max-width: 100%;
            height: auto !important;
        }
    </style>
</head>

<body class="app-layout">
    <div class="app-wrapper">
        {{ $slot }}
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    @stack('scripts')
</body>

</html>
