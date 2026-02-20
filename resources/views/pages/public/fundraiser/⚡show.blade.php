<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Fundraiser;
use App\Models\Campaign;
use Livewire\Attributes\Computed;

new #[Layout('layouts.app')] class extends Component {
    public Fundraiser $fundraiser;

    public function mount(Fundraiser $fundraiser)
    {
        $this->fundraiser = $fundraiser->load('user');
    }

    #[Computed]
    public function campaigns()
    {
        return $this->fundraiser->campaigns()->where('status', 'active')->latest()->get();
    }
}; ?>

<div>
    <x-app.navbar-secondary title="Profil Penggalang" />

    {{-- Fundraiser Header --}}
    <section class="fundraiser-profile-header py-4 bg-white border-bottom">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-3">
                <div class="fundraiser-profile-logo bg-light rounded-circle overflow-hidden shadow-micro"
                    style="width: 72px; height: 72px;">
                    <img src="{{ $fundraiser->logo_url }}" alt="{{ $fundraiser->foundation_name }}"
                        class="w-100 h-100 object-fit-cover">
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <h5 class="fw-bold text-dark mb-1 text-truncate">{{ $fundraiser->foundation_name }}</h5>
                    <div class="d-flex align-items-center gap-2">
                        <span class="extra-small fw-bold px-2 py-1 rounded-pill d-inline-flex align-items-center gap-1"
                            style="background: rgba(73, 163, 227, 0.1); color: #49a3e3 !important;">
                            Mitra Terverifikasi <i class="bi bi-patch-check-fill"
                                style="color: #49a3e3 !important;"></i>
                        </span>
                        <small class="text-muted extra-small">Mulai aktif
                            {{ $fundraiser->created_at->translatedFormat('d F Y') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- About Section --}}
    <section class="fundraiser-profile-about py-4 bg-white mb-3">
        <div class="container-fluid">
            <h6 class="fw-bold text-dark mb-3 small text-uppercase ls-sm">Tentang Penggalang</h6>
            <div x-data="{ expanded: false }" class="position-relative">
                <div class="text-muted small lh-lg overflow-hidden" :class="expanded ? '' : 'max-h-120'">
                    {!! nl2br(e($fundraiser->about ?? 'Belum ada informasi profil.')) !!}
                </div>

                @if (strlen($fundraiser->about ?? '') > 150)
                    <div x-show="!expanded"
                        class="position-absolute bottom-0 start-0 end-0 h-40 bg-gradient-white-to-t"></div>
                    <div class="text-center mt-3">
                        <button @click="expanded = !expanded"
                            class="btn btn-outline-primary btn-sm rounded-pill px-4 fw-bold extra-small transition-200 shadow-micro">
                            <span x-show="!expanded">Baca Selengkapnya <i class="bi bi-chevron-down ms-1"></i></span>
                            <span x-show="expanded" x-cloak>Tutup <i class="bi bi-chevron-up ms-1"></i></span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Campaigns List --}}
    <section class="fundraiser-profile-campaigns py-5 mb-5" style="background-color: #fafbfb;">
        <div class="container-fluid">
            <h6 class="fw-bold text-dark mb-3 small text-uppercase ls-sm">Galang Dana Didukung</h6>

            <div class="d-flex flex-column gap-3">
                @forelse($this->campaigns as $campaign)
                    @php
                        $progress =
                            $campaign->target_amount > 0
                                ? min(($campaign->collected_amount / $campaign->target_amount) * 100, 100)
                                : 0;
                        $daysLeft = max(floor(now()->diffInDays($campaign->end_date, false)), 0);
                    @endphp
                    <a href="{{ route('campaign.show', $campaign->slug) }}" class="card campaign-card"
                        style="height: auto;" wire:navigate>
                        <div class="card-body p-2 p-md-3">
                            <div class="d-flex gap-3">
                                <div class="position-relative flex-shrink-0"
                                    style="width: 105px; height: 105px; aspect-ratio: 1/1;">
                                    <img src="{{ $campaign->thumbnail_url }}" class="card-img-top rounded-2"
                                        style="height: 100%;" alt="{{ $campaign->title }}">
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
                                <div class="flex-grow-1 overflow-hidden d-flex flex-column">
                                    <div class="campaign-card-organizer mb-1">
                                        <span>{{ $fundraiser->foundation_name }}</span>
                                        <i class="bi bi-patch-check-fill"></i>
                                    </div>

                                    <h6 class="card-title mb-2"
                                        style="min-height: auto; -webkit-line-clamp: 2; line-height: 1.3; font-size: 13px;">
                                        {{ $campaign->title }}</h6>

                                    <div class="campaign-card-footer mt-auto">
                                        <div class="campaign-card-progress mb-2">
                                            <div class="progress" style="height: 4px;">
                                                <div class="progress-bar" role="progressbar"
                                                    style="width: {{ $progress }}%"></div>
                                            </div>
                                        </div>

                                        <div class="campaign-card-stats">
                                            <div>
                                                <div class="campaign-card-label" style="font-size: 9px;">Terkumpul</div>
                                                <div class="campaign-card-amount" style="font-size: 12px;">Rp
                                                    {{ number_format($campaign->collected_amount, 0, ',', '.') }}</div>
                                            </div>
                                            <div class="text-end">
                                                <div class="campaign-card-label" style="font-size: 9px;">Sisa hari</div>
                                                <div class="campaign-card-days-value" style="font-size: 12px;">
                                                    {{ $daysLeft }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-folder2-open display-4 text-muted opacity-25"></i>
                        <p class="text-muted small mt-2">Belum ada campaign aktif.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <x-app.bottom-nav />
</div>

<style>
    .max-h-120 {
        max-height: 120px;
    }

    .h-40 {
        height: 60px;
    }

    .bg-gradient-white-to-t {
        background: linear-gradient(to top, rgba(255, 255, 255, 1), rgba(255, 255, 255, 0));
    }

    .ls-sm {
        letter-spacing: 0.05em;
    }

    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-height-sm {
        line-height: 1.3;
    }

    .x-small {
        font-size: 0.75rem;
    }
</style>
