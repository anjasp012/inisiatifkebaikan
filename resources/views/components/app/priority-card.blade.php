@props(['campaign'])

@php
    $progress =
        $campaign->@target_amount > 0 ? min(($campaign->collected_amount / $campaign->target_amount) * 100, 100) : 0;
    $daysLeft = max(floor(now()->diffInDays($campaign->end_date, false)), 0);
    $donorCount = $campaign->donations()->where('status', 'success')->count();
@endphp

@if ($campaign)
    <div class="priority-card card border-0 rounded-2 overflow-hidden shadow-sm" style="background-color: #F0F9FF;">
        <div class="position-relative">
            <img src="{{ $campaign->thumbnail_url }}" class="w-100 object-fit-cover" style="aspect-ratio: 19/10;"
                alt="{{ $campaign->title }}">
        </div>

        <div class="card-body p-3">
            <div class="d-inline-block text-white px-2 py-1 rounded-pill fw-bold mb-2"
                style="font-size: 10px; background-color: #D95D0F;">
                Prioritas Kebaikan Hari Ini
            </div>

            <h6 class="fw-bold text-dark font-jakarta mb-2" style="line-height: 1.4; font-size: 15px;">
                <a href="{{ route('campaign.show', $campaign->slug) }}" wire:navigate
                    class="text-decoration-none text-dark">
                    {{ Str::limit($campaign->title, 55) }}
                </a>
            </h6>

            <p class="text-muted small mb-3" style="line-height: 1.5; font-size: 11px;">
                {{ Str::limit($campaign->description, 75) }}
            </p>

            <div class="row g-2 mb-3">
                <div class="col-6">
                    <div class="bg-white p-2 rounded-3 h-100 border-0 shadow-sm text-center">
                        <div class="fw-bold" style="font-size: 13px; color: #D95D0F;">
                            Rp {{ number_format($campaign->collected_amount / 1000000, 1, ',', '.') }}M
                        </div>
                        <div class="text-muted" style="font-size: 10px;">terkumpul</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="bg-white p-2 rounded-3 h-100 border-0 shadow-sm text-center">
                        <div class="fw-bold" style="font-size: 13px; color: #D95D0F;">{{ $donorCount }}</div>
                        <div class="text-muted" style="font-size: 10px;">donatur</div>
                    </div>
                </div>
            </div>

            <a href="{{ route('campaign.show', $campaign->slug) }}" wire:navigate
                class="btn btn-primary w-100 py-2.5 rounded-3 fw-bold border-0 shadow-sm"
                style="background-color: #F85A8E; font-size: 13px;">
                Mulai Berbagi Sekarang
            </a>
        </div>
    </div>
@endif

<style>
    .font-jakarta {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .shadow-sm {
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04) !important;
    }
</style>
