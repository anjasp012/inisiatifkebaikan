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
        /* CKEditor Content Fix */
        .ck-content img {
            max-width: 100% !important;
            height: auto !important;
            aspect-ratio: auto !important;
            object-fit: contain !important;
        }

        /* Handle CKEditor Figure Resizing */
        .ck-content figure.image {
            max-width: 100% !important;
            height: auto !important;
            margin: 1.5rem auto !important;
            display: block !important;
        }

        .ck-content figure.image img {
            display: block;
            margin: 0 auto;
        }

        /* General content image responsiveness */
        .campaign-description img,
        .distribution-content img,
        .article-content img {
            max-width: 100% !important;
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
