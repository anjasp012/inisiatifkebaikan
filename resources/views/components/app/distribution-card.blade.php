@props(['distribution'])

<a href="{{ route('distribution.show', $distribution->id) }}" class="card distribution-card" wire:navigate>
    <div class="position-relative">
        <img src="{{ $distribution->file_url }}" class="card-img-top" alt="{{ $distribution->campaign->title }}">
        <span class="badge distribution-card-date">
            <i class="bi bi-calendar-event"></i>
            {{ Carbon\Carbon::parse($distribution->distribution_date)->translatedFormat('d F Y') }}
        </span>
    </div>

    <div class="card-body">
        <h6 class="card-title">{{ Str::limit(strip_tags($distribution->description), 100) }}</h6>
    </div>
</a>
