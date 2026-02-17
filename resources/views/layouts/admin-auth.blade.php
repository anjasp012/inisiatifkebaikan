<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        {{ isset($title) ? $title . ' | ' . config('app.name', 'Laravel') : 'Admin Login ' . config('app.name', 'Laravel') }}
    </title>
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">

    <!-- Styles / Scripts -->
    @vite(['resources/sass/app.scss'])
</head>

<body class="bg-light">
    {{ $slot }}
</body>

</html>
