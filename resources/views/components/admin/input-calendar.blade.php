@props([
    'model',
    'id' => null,
    'label' => null,
    'placeholder' => 'Pilih Tanggal',
    'type' => 'single', // single, range, datetime
    'format' => 'd-m-Y',
    'enableTime' => false,
])

@php
    $id = $id ?? $model;
@endphp

<div class="mb-3" x-data="{
    value: @entangle($model),
    instance: null,
    init() {
        this.instance = flatpickr(this.$refs.dateInput, {
            locale: 'id',
            dateFormat: '{{ $enableTime ? 'Y-m-d H:i' : 'Y-m-d' }}',
            altInput: true,
            altFormat: '{{ $enableTime ? 'd F Y - H:i' : 'd F Y' }}',
            enableTime: {{ $enableTime ? 'true' : 'false' }},
            mode: '{{ $type }}',
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

    @if ($label)
        <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    @endif

    <div class="input-group">
        <span class="input-group-text bg-white border-end-0">
            <i class="bi bi-calendar3 text-primary"></i>
        </span>
        <input type="text" x-ref="dateInput" id="{{ $id }}"
            class="form-control border-start-0 ps-0 @error($model) is-invalid @enderror"
            placeholder="{{ $placeholder }}" autocomplete="off">
    </div>

    @error($model)
        <div class="text-danger extra-small mt-1">
            <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
        </div>
    @enderror
</div>
