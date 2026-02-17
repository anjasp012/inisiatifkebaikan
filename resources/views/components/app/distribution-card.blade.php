@props(['distribution'])

<a href="#" class="card distribution-card" wire:navigate>
    <div class="position-relative">
        <img src="{{ $distribution->proof_url }}" class="card-img-top" alt="{{ $distribution->campaign->title }}">
        <span class="badge distribution-card-date">
            <i class="bi bi-calendar-event"></i>
            {{ $distribution->distribution_date->translatedFormat('d F Y') }}
        </span>
    </div>

    <div class="card-body">
        <h6 class="card-title">{{ $distribution->description }}</h6>
    </div>
</a>
