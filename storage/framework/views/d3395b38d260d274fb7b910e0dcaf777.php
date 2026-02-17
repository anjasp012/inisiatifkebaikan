<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['model']));

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

foreach (array_filter((['model']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div wire:ignore x-data="{
    value: <?php if ((object) ($model) instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e($model->value()); ?>')<?php echo e($model->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e($model); ?>')<?php endif; ?>,
    init() {
        ClassicEditor
            .create($refs.editor, {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
                    'imageUpload', 'insertTable', 'undo', 'redo'
                ],
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'H1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'H2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'H3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'H4', class: 'ck-heading_heading4' },
                        { model: 'heading5', view: 'h5', title: 'H5', class: 'ck-heading_heading5' },
                        { model: 'heading6', view: 'h6', title: 'H6', class: 'ck-heading_heading6' }
                    ]
                }
            })
            .then(editor => {
                editor.setData(this.value);
                editor.model.document.on('change:data', () => {
                    this.value = editor.getData();
                });

                // Watch for external changes (like when switching between items if using same component)
                $watch('value', (newValue) => {
                    if (newValue !== editor.getData()) {
                        editor.setData(newValue || '');
                    }
                });
            })
            .catch(error => {
                console.error(error);
            });
    }
}" <?php echo e($attributes); ?>>
    <div x-ref="editor"></div>
</div>
<?php /**PATH C:\laragon\www\inisiatif\resources\views/components/admin/text-editor.blade.php ENDPATH**/ ?>