@props(['model', 'id' => null, 'label' => null, 'placeholder' => '0', 'defer' => false])

@php
    $id = $id ?? $model;
@endphp

<div class="mb-3" x-data="{
    instance: null,
    value: @entangle($model){{ $defer ? '.defer' : '' }},
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

        // Sync from input to Alpine/Livewire
        this.$refs.rupiahInput.addEventListener('autoNumeric:rawValueModified', (e) => {
            this.value = e.detail.newRawValue;
        });

        // Watch for changes from Livewire to update AutoNumeric
        this.$watch('value', (newValue) => {
            if (newValue !== this.instance.getRawValue()) {
                this.instance.set(newValue);
            }
        });
    }
}" wire:ignore>

    @if ($label)
        <label for="{{ $id }}" class="form-label">{{ $label }}</label>
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
