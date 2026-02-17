<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['campaign']));

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

foreach (array_filter((['campaign']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $progress =
        $campaign->target_amount > 0 ? min(($campaign->collected_amount / $campaign->target_amount) * 100, 100) : 0;
    $daysLeft = max(floor(now()->diffInDays($campaign->end_date, false)), 0);
?>

<a href="<?php echo e(route('campaign.show', $campaign->slug)); ?>" class="card campaign-card" wire:navigate>
    <div class="position-relative">
        <img src="<?php echo e($campaign->thumbnail_url); ?>" class="card-img-top" alt="<?php echo e($campaign->title); ?>">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($campaign->is_emergency): ?>
            <span class="badge campaign-card-badge campaign-card-badge--urgent">
                <i class="bi bi-lightning-fill"></i> Darurat
            </span>
        <?php elseif($campaign->category): ?>
            <span class="badge campaign-card-badge">
                <?php echo e($campaign->category->name); ?>

            </span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div class="card-body">
        <div class="campaign-card-organizer">
            <span><?php echo e($campaign->fundraiser?->foundation_name ?? 'Inisiatif Kebaikan'); ?></span>
            <i class="bi bi-patch-check-fill"></i>
        </div>

        <h6 class="card-title"><?php echo e($campaign->title); ?></h6>

        <div class="campaign-card-footer">
            <div class="campaign-card-progress">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: <?php echo e($progress); ?>%"></div>
                </div>
            </div>

            <div class="campaign-card-stats">
                <div>
                    <div class="campaign-card-label">Terkumpul</div>
                    <div class="campaign-card-amount">Rp<?php echo e(number_format($campaign->collected_amount, 0, ',', '.')); ?>

                    </div>
                </div>
                <div class="text-end">
                    <div class="campaign-card-label">Sisa hari</div>
                    <div class="campaign-card-days-value"><?php echo e($daysLeft); ?></div>
                </div>
            </div>
        </div>
    </div>
</a>
<?php /**PATH C:\laragon\www\inisiatif\resources\views/components/app/campaign-card.blade.php ENDPATH**/ ?>