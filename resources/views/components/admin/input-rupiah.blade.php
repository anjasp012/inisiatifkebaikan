@props([
    'model',
    'id' => null,
    'label' => null,
    'placeholder' => '0',
    'defer' => false,
    'live' => false,
    'debounce' => 500,
    'labelClass' => 'form-label',
])

@php
    $id = $id ?? $model;
    $entangleType = $live ? '.live' : ($defer ? '.defer' : '');
@endphp

<div class="mb-3" x-data="{
    instance: null,
    isTyping: false,
    timer: null,
    value: @entangle($model){{ $entangleType }},
    init() {
        this.instance = new AutoNumeric(this.$refs.rupiahInput, {
            digitGroupSeparator: '.',
            decimalCharacter: ',',
            decimalPlaces: 0,
            minimumValue: '0',
            currencySymbol: 'Rp ',
            emptyInputBehavior: 'null',
            unformatOnSubmit: true,
            onInvalidPaste: 'truncate'
        });

        // Set initial value
        if (this.value) {
            this.instance.set(this.value);
        }

        // Sync from input to Alpine/Livewire with debounce
        this.$refs.rupiahInput.addEventListener('autoNumeric:rawValueModified', (e) => {
            this.isTyping = true;
            clearTimeout(this.timer);

            this.timer = setTimeout(() => {
                this.value = e.detail.newRawValue;
                this.isTyping = false;
            }, {{ $debounce }});
        });

        // Watch for changes from Livewire to update AutoNumeric
        this.$watch('value', (newValue) => {
            // Only update AutoNumeric if we're NOT typing (to avoid flickering/cursor issues)
            if (this.instance && !this.isTyping && newValue !== this.instance.getNumericString()) {
                this.instance.set(newValue);
            }
        });
    }
}" wire:ignore>

    @if ($label)
        <label for="{{ $id }}" class="{{ $labelClass }}">{{ $label }}</label>
    @endif

    <div class="input-group">
        <input type="text" x-ref="rupiahInput" id="{{ $id }}"
            class="form-control border-1 @error($model) is-invalid @enderror" placeholder="{{ $placeholder }}"
            autocomplete="off" {{ $attributes }}>
    </div>

    @error($model)
        <div class="text-danger extra-small mt-1">
            <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
        </div>
    @enderror
</div>

<style>
    .extra-small {
        font-size: 0.75rem;
    }
</style>
