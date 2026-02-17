<?php

use App\Models\Campaign;
use App\Models\Donation;

use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component {
    public Campaign $campaign;
    public string $activeTab = 'cerita';
    public string $donorSort = 'terbaru';

    public function mount(Campaign $campaign)
    {
        $this->campaign = $campaign->load(['category', 'fundraiser']);
        views($this->campaign)->record();
    }

    #[Computed]
    public function progress()
    {
        return $this->campaign->target_amount > 0 ? min(($this->campaign->collected_amount / $this->campaign->target_amount) * 100, 100) : 0;
    }

    #[Computed]
    public function daysLeft()
    {
        return max(floor(now()->diffInDays($this->campaign->end_date, false)), 0);
    }

    #[Computed]
    public function donorCount()
    {
        return $this->campaign->donations()->where('status', 'success')->count();
    }

    #[Computed]
    public function donors()
    {
        $query = $this->campaign->donations()->where('status', 'success');

        if ($this->donorSort === 'terbesar') {
            $query->orderByDesc('amount');
        } else {
            $query->latest();
        }

        return $query->limit(10)->get();
    }

    #[Computed]
    public function campaignUpdates()
    {
        return $this->campaign->updates()->get();
    }

    #[Computed]
    public function prayers()
    {
        return $this->campaign->prayers()->latest()->get();
    }

    public function switchTab(string $tab)
    {
        $this->activeTab = $tab;
        $this->dispatch('scroll-top');
    }

    public function switchDonorSort(string $sort)
    {
        $this->donorSort = $sort;
        unset($this->donors);
    }
};
?>

<div>
    <x-app.navbar-secondary title="Detail Program" />

    {{-- Campaign Image --}}
    <section class="detail-image-section">
        <img src="{{ $campaign->thumbnail_url }}" alt="{{ $campaign->title }}">
    </section>

    {{-- Campaign Info --}}
    <section class="detail-info-section">
        <div class="container-fluid">
            {{-- Badges below image --}}
            <div class="detail-badges">
                @if ($campaign->is_emergency)
                    <span class="detail-badge detail-badge--urgent">DARURAT</span>
                @endif
                @if ($campaign->category)
                    <span class="detail-badge">{{ strtoupper($campaign->category->name) }}</span>
                @endif
            </div>

            <h1 class="detail-title">{{ $campaign->title }}</h1>

            {{-- Progress bar --}}
            <div class="detail-progress">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: {{ $this->progress }}%"></div>
                </div>
            </div>

            {{-- Stats row --}}
            <div class="detail-stats">
                <div class="detail-stats__left">
                    <span class="detail-stats__amount">Rp
                        {{ number_format($campaign->collected_amount, 0, ',', '.') }}</span>
                    <span class="detail-stats__target">terkumpul dari Rp
                        {{ number_format($campaign->target_amount, 0, ',', '.') }}</span>
                </div>
                <div class="detail-stats__right">
                    <span class="detail-stats__inline"><strong>{{ $this->daysLeft }} Hari</strong>
                        <small>tersisa</small></span>
                    <span class="detail-stats__inline"><strong>{{ $this->donorCount }}</strong>
                        <small>Donatur</small></span>
                </div>
            </div>
        </div>
    </section>

    {{-- Organizer --}}
    <section class="detail-organizer-section">
        <div class="container-fluid">
            <a href="#" class="detail-organizer">
                <div class="detail-organizer__avatar">
                    @if ($campaign->fundraiser)
                        <img src="{{ $campaign->fundraiser->logo_url }}"
                            alt="{{ $campaign->fundraiser->foundation_name }}">
                    @else
                        <span class="detail-organizer__initials">IK</span>
                    @endif
                </div>
                <div class="detail-organizer__info">
                    <span
                        class="detail-organizer__name">{{ $campaign->fundraiser?->foundation_name ?? 'Inisiatif Kebaikan' }}</span>
                    <span class="detail-organizer__verified">
                        Terverifikasi <i class="bi bi-patch-check-fill"></i>
                    </span>
                </div>
                <i class="bi bi-chevron-right detail-organizer__arrow"></i>
            </a>
        </div>
    </section>

    {{-- Tabs --}}
    <section class="detail-tabs-section">
        <div class="detail-tabs">
            <button wire:click="switchTab('cerita')"
                class="detail-tabs__item {{ $activeTab === 'cerita' ? 'active' : '' }}">
                Cerita
            </button>
            <button wire:click="switchTab('update')"
                class="detail-tabs__item {{ $activeTab === 'update' ? 'active' : '' }}">
                Update
            </button>
            <button wire:click="switchTab('donatur')"
                class="detail-tabs__item {{ $activeTab === 'donatur' ? 'active' : '' }}">
                Donatur
            </button>
            <button wire:click="switchTab('doa')" class="detail-tabs__item {{ $activeTab === 'doa' ? 'active' : '' }}">
                Doa
            </button>
        </div>
    </section>

    {{-- Tab Content --}}
    <section class="detail-content-section">
        <div class="container-fluid">
            @if ($activeTab === 'cerita')
                <div class="detail-content__story">
                    {!! $campaign->description !!}
                </div>
            @elseif ($activeTab === 'update')
                <div class="update-list pb-4">
                    @forelse ($this->campaignUpdates as $index => $update)
                        <div class="card border mb-3 rounded-3 overflow-hidden" wire:key="update-{{ $update->id }}"
                            x-data="{ open: {{ $index === 0 ? 'true' : 'false' }} }">

                            {{-- Header --}}
                            <div class="card-header bg-white border-0 p-3 cursor-pointer" @click="open = !open"
                                role="button">
                                <div class="d-flex align-items-center gap-3">
                                    {{-- Date Badge --}}
                                    <div class="d-flex flex-column align-items-center justify-content-center bg-primary rounded-3 px-2 py-2 shadow-sm"
                                        style="width: 50px; height: 50px;">
                                        <span class="fw-bold text-white lh-1" style="font-size: 18px;">
                                            {{ optional($update->published_at)->format('d') ?? $update->created_at->format('d') }}
                                        </span>
                                        <span class="fw-medium text-white-50 lh-1 text-uppercase"
                                            style="font-size: 10px;">
                                            {{ optional($update->published_at)->translatedFormat('M') ?? $update->created_at->translatedFormat('M') }}
                                        </span>
                                    </div>

                                    {{-- Title & Meta --}}
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold text-dark mb-1" style="font-size: 15px;">
                                            {{ $update->title }}</h6>
                                        <span class="fw-bold text-black" style="font-size: 13px;">
                                            <i class="bi bi-clock me-1 text-primary"></i>
                                            {{ optional($update->published_at)->diffForHumans() ?? $update->created_at->diffForHumans() }}
                                        </span>
                                    </div>

                                    {{-- Toggle Icon --}}
                                    <div class="text-secondary transition-all duration-300"
                                        :style="open ? 'transform: rotate(180deg)' : ''">
                                        <i class="bi bi-chevron-down"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- Body --}}
                            <div x-show="open" x-collapse class="border-top border-light bg-light bg-opacity-10">
                                <div class="card-body p-3 p-md-4">
                                    <div class="text-dark mb-3" style="font-size: 14px; line-height: 1.6;">
                                        {!! $update->content !!}
                                    </div>

                                    @if ($update->image_url)
                                        <div class="rounded-3 overflow-hidden border border-light">
                                            <img src="{{ $update->image_url }}" alt="{{ $update->title }}"
                                                class="img-fluid w-100 object-fit-cover">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="detail-content__empty text-center py-5">
                            <div class="mb-3 text-muted opacity-50">
                                <i class="bi bi-newspaper display-1"></i>
                            </div>
                            <p class="text-muted fw-medium">Belum ada update terbaru untuk program ini.</p>
                        </div>
                    @endforelse
                </div>
            @elseif ($activeTab === 'donatur')
                {{-- Sub-filter tabs --}}
                <div class="donor-filter">
                    <button wire:click="switchDonorSort('terbaru')"
                        class="donor-filter__item {{ $donorSort === 'terbaru' ? 'active' : '' }}">
                        Terbaru
                    </button>
                    <button wire:click="switchDonorSort('terbesar')"
                        class="donor-filter__item {{ $donorSort === 'terbesar' ? 'active' : '' }}">
                        Terbesar
                    </button>
                </div>

                <div class="detail-content__donors" wire:loading.class="opacity-50">
                    @forelse ($this->donors as $donation)
                        <div class="donor-item">
                            <div class="donor-item__avatar">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div class="donor-item__info">
                                <span class="donor-item__name">
                                    {{ $donation->is_anonymous ? 'Hamba Allah' : $donation->donor_name ?? 'Anonim' }}
                                </span>
                                <span class="donor-item__amount">
                                    Rp {{ number_format($donation->amount, 0, ',', '.') }}
                                </span>
                            </div>
                            <span class="donor-item__date">
                                {{ $donation->paid_at?->diffForHumans() ?? $donation->created_at->diffForHumans() }}
                            </span>
                        </div>
                    @empty
                        <div class="detail-content__empty">
                            <i class="bi bi-people"></i>
                            <p>Belum ada donatur untuk program ini.</p>
                        </div>
                    @endforelse
                </div>
            @elseif ($activeTab === 'doa')
                <div class="row g-3 prayer-list">
                    @forelse ($this->prayers as $prayer)
                        <div class="col-12" wire:key="prayer-{{ $prayer->id }}">
                            <x-app.prayer-card :prayer="$prayer" />
                        </div>
                    @empty
                        <div class="detail-content__empty text-center py-5">
                            <div class="mb-3 text-muted opacity-50">
                                <i class="bi bi-chat-heart display-1"></i>
                            </div>
                            <p class="text-muted fw-medium">Belum ada doa untuk program ini.</p>
                        </div>
                    @endforelse
                </div>
            @endif
        </div>
    </section>

    {{-- Sticky CTA --}}
    <div class="detail-cta">
        <a href="{{ route('donation.amount', $campaign->slug) }}" wire:navigate class="detail-cta__button">
            Donasi Sekarang <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    @script
        <script>
            $wire.on('scroll-top', () => {
                const tabs = document.querySelector('.detail-tabs-section');
                if (tabs) {
                    tabs.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        </script>
    @endscript
</div>
