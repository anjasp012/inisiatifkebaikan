<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'model',
    'id' => null,
    'label' => null,
    'placeholder' => 'Pilih Tanggal',
    'type' => 'single', // single, range, datetime
    'format' => 'd-m-Y',
    'enableTime' => false,
]));

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

foreach (array_filter(([
    'model',
    'id' => null,
    'label' => null,
    'placeholder' => 'Pilih Tanggal',
    'type' => 'single', // single, range, datetime
    'format' => 'd-m-Y',
    'enableTime' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $id = $id ?? $model;
?>

<div class="mb-3" x-data="{
    value: <?php if ((object) ($model) instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e($model->value()); ?>')<?php echo e($model->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e($model); ?>')<?php endif; ?>,
    instance: null,
    init() {
        this.instance = flatpickr(this.$refs.dateInput, {
            locale: 'id',
            dateFormat: '<?php echo e($enableTime ? 'Y-m-d H:i' : 'Y-m-d'); ?>',
            altInput: true,
            altFormat: '<?php echo e($enableTime ? 'd F Y - H:i' : 'd F Y'); ?>',
            enableTime: <?php echo e($enableTime ? 'true' : 'false'); ?>,
            mode: '<?php echo e($type); ?>',
            defaultDate: this.value,
            onChange: (selectedDates, dateStr) => {
                this.value = dateStr;
            }
        });

        this.$watch('value', (newValue) => {
            this.instance.setDate(newValue, false);
        });
    }
}" wire:ignore>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($label): ?>
        <label for="<?php echo e($id); ?>" class="form-label"><?php echo e($label); ?></label>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="input-group">
        <span class="input-group-text bg-white border-end-0">
            <i class="bi bi-calendar3 text-primary"></i>
        </span>
        <input type="text" x-ref="dateInput" id="<?php echo e($id); ?>"
            class="form-control border-start-0 ps-0 <?php $__errorArgs = [$model];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            placeholder="<?php echo e($placeholder); ?>" autocomplete="off">
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = [$model];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="text-danger extra-small mt-1">
            <i class="bi bi-exclamation-circle me-1"></i> <?php echo e($message); ?>

        </div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\inisiatif\resources\views/components/admin/input-calendar.blade.php ENDPATH**/ ?>