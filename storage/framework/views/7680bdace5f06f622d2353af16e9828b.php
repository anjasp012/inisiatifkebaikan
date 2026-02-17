<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['article']));

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

foreach (array_filter((['article']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<a href="#" class="card article-card" wire:navigate>
    <div class="position-relative">
        <img src="<?php echo e($article->thumbnail_url); ?>" class="card-img-top" alt="<?php echo e($article->title); ?>">
        <span class="badge article-card-badge">
            <?php echo e($article->category); ?>

        </span>
    </div>

    <div class="card-body">
        <div class="article-card-date">
            <i class="bi bi-clock"></i> <?php echo e($article->created_at->diffForHumans()); ?>

        </div>

        <h6 class="card-title"><?php echo e($article->title); ?></h6>

        <p class="article-card-excerpt"><?php echo e(Str::limit(strip_tags($article->content), 100)); ?></p>
    </div>
</a>
<?php /**PATH C:\laragon\www\inisiatif\resources\views/components/app/article-card.blade.php ENDPATH**/ ?>