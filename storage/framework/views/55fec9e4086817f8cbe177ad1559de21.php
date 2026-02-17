<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['distribution']));

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

foreach (array_filter((['distribution']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<a href="#" class="card distribution-card" wire:navigate>
    <div class="position-relative">
        <img src="<?php echo e($distribution->proof_url); ?>" class="card-img-top" alt="<?php echo e($distribution->campaign->title); ?>">
        <span class="badge distribution-card-date">
            <i class="bi bi-calendar-event"></i>
            <?php echo e($distribution->distribution_date->translatedFormat('d F Y')); ?>

        </span>
    </div>

    <div class="card-body">
        <h6 class="card-title"><?php echo e($distribution->description); ?></h6>
    </div>
</a>
<?php /**PATH C:\laragon\www\inisiatif\resources\views/components/app/distribution-card.blade.php ENDPATH**/ ?>