<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['prayer']));

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

foreach (array_filter((['prayer']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="card prayer-card">
    <div class="card-body">
        <div class="prayer-card-header">
            <div class="prayer-card-avatar">
                <i class="bi bi-person-fill"></i>
            </div>
            <div>
                <div class="prayer-card-name"><?php echo e($prayer->donor_name); ?></div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($prayer->campaign): ?>
                    <div class="prayer-card-campaign"><?php echo e($prayer->campaign->title); ?></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <p class="prayer-card-message">"<?php echo e($prayer->message); ?>"</p>

        <div class="prayer-card-footer">
            <button class="prayer-card-amin" x-data="{
                count: <?php echo e($prayer->amin_count); ?>,
                done: localStorage.getItem('amin_<?php echo e($prayer->id); ?>') === '1'
            }"
                x-on:click="
                    if(!done) {
                        count++;
                        done = true;
                        localStorage.setItem('amin_<?php echo e($prayer->id); ?>', '1');
                        fetch('/api/prayer/<?php echo e($prayer->id); ?>/amin', {
                            method: 'POST',
                            headers: {'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'}
                        })
                    }
                "
                :class="{ 'is-done': done }">
                🤲 Amin (<span x-text="count"></span>)
            </button>
            <span class="prayer-card-time"><?php echo e($prayer->created_at->diffForHumans()); ?></span>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\inisiatif\resources\views/components/app/prayer-card.blade.php ENDPATH**/ ?>