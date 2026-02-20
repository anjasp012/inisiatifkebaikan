@props(['campaign'])

@php
    $progress =
        $campaign->target_amount > 0 ? min(($campaign->collected_amount / $campaign->target_amount) * 100, 100) : 0;
    $daysLeft = max(floor(now()->diffInDays($campaign->end_date, false)), 0);
@endphp

<a href="{{ route('campaign.show', $campaign->slug) }}" class="card campaign-card" wire:navigate>
    <div class="position-relative">
        <img src="{{ $campaign->thumbnail_url }}" class="card-img-top" alt="{{ $campaign->title }}">
        @if ($campaign->is_emergency)
            <span class="badge campaign-card-badge campaign-card-badge--urgent">
                <i class="bi bi-lightning-fill"></i> Darurat
            </span>
        @elseif ($campaign->category)
            <span class="badge campaign-card-badge">
                {{ $campaign->category->name }}
            </span>
        @endif
    </div>

    <div class="card-body">
        <div class="campaign-card-organizer">
            <span>{{ $campaign->fundraiser?->foundation_name ?? \App\Models\Setting::get('website_name', 'Inisiatif Kebaikan') }}</span>
            <i class="bi bi-patch-check-fill"></i>
        </div>

        <h6 class="card-title">{{ $campaign->title }}</h6>

        <div class="campaign-card-footer">
            <div class="campaign-card-progress">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%"></div>
                </div>
            </div>

            <div class="campaign-card-stats">
                <div>
                    <div class="campaign-card-label">Terkumpul</div>
                    <div class="campaign-card-amount">Rp{{ number_format($campaign->collected_amount, 0, ',', '.') }}
                    </div>
                </div>
                <div class="text-end">
                    <div class="campaign-card-label">Sisa hari</div>
                    <div class="campaign-card-days-value">{{ $daysLeft }}</div>
                </div>
            </div>
        </div>
    </div>
</a>
