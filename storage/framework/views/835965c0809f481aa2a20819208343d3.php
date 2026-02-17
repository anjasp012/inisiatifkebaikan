<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo e($title ?? config('app.name')); ?></title>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
</head>

<body class="app-layout">
    <div class="app-wrapper">
        <?php echo e($slot); ?>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH C:\laragon\www\inisiatif\resources\views/layouts/app.blade.php ENDPATH**/ ?>