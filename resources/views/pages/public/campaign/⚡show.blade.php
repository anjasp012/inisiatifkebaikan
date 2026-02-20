<?php

use App\Models\Campaign;
use App\Models\Donation;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\View;

new class extends Component {
    public Campaign $campaign;
    public string $activeTab = 'cerita';
    public string $donorSort = 'terbaru';
    public int $updatePerPage = 4;
    public int $prayerPerPage = 4;
    public int $donorPerPage = 10;

    public function mount(Campaign $campaign)
    {
        $this->campaign = $campaign->load(['category', 'fundraiser']);
        views($this->campaign)
            ->cooldown(now()->addHours(2))
            ->record();

        // Pass model directly for granular SEO
        View::share('seoData', $this->campaign);
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

        return $query->take($this->donorPerPage)->get();
    }

    public function loadMoreDonors()
    {
        $this->donorPerPage += 10;
    }

    #[Computed]
    public function campaignUpdates()
    {
        $updates = $this->campaign->updates()->get()->map(
            fn($u) => (object) [
                'id' => 'upd-' . $u->id,
                'title' => $u->title,
                'content' => $u->content,
                'published_at' => $u->published_at ?? $u->created_at,
                'image_url' => $u->image_url,
                'type' => 'update',
            ],
        );

        $distributions = $this->campaign->distributions()->get()->map(
            fn($d) => (object) [
                'id' => 'dist-' . $d->id,
                'title' => 'Laporan Penyaluran: Rp ' . number_format($d->amount, 0, ',', '.'),
                'content' => $d->description,
                'published_at' => \Carbon\Carbon::parse($d->distribution_date),
                'image_url' => $d->file_url,
                'type' => 'distribution',
                'amount' => $d->amount,
                'recipient' => $d->recipient_name,
            ],
        );

        return $updates->concat($distributions)->sortByDesc('published_at')->values()->take($this->updatePerPage);
    }

    public function loadMoreUpdates()
    {
        $this->updatePerPage += 4;
    }

    #[Computed]
    public function prayers()
    {
        return $this->campaign->prayers()->latest()->take($this->prayerPerPage)->get();
    }

    public function loadMorePrayers()
    {
        $this->prayerPerPage += 4;
    }

    public function switchTab(string $tab)
    {
        $this->activeTab = $tab;
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
            @if ($campaign->fundraiser)
                <a href="{{ route('fundraiser.profile', $campaign->fundraiser->slug) }}" wire:navigate
                    class="detail-organizer">
                    <div class="detail-organizer__avatar">
                        <img src="{{ $campaign->fundraiser->logo_url }}"
                            alt="{{ $campaign->fundraiser->foundation_name }}">
                    </div>
                    <div class="detail-organizer__info">
                        <span class="detail-organizer__name">{{ $campaign->fundraiser->foundation_name }}</span>
                        <span class="detail-organizer__verified">
                            Terverifikasi <i class="bi bi-patch-check-fill"></i>
                        </span>
                    </div>
                    <i class="bi bi-chevron-right detail-organizer__arrow"></i>
                </a>
            @else
                <div class="detail-organizer">
                    <div class="detail-organizer__avatar">
                        <span class="detail-organizer__initials">IK</span>
                    </div>
                    <div class="detail-organizer__info">
                        <span class="detail-organizer__name">Inisiatif Kebaikan</span>
                        <span class="detail-organizer__verified">
                            Terverifikasi <i class="bi bi-patch-check-fill"></i>
                        </span>
                    </div>
                </div>
            @endif
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
                <div class="detail-content__story ck-content">
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
                                    <div
                                        class="d-flex flex-column align-items-center justify-content-center bg-primary rounded-3 px-2 py-2 shadow-sm avatar-md">
                                        <span class="fw-bold text-white lh-1 fs-5">
                                            {{ optional($update->published_at)->format('d') ?? $update->created_at->format('d') }}
                                        </span>
                                        <span class="fw-medium text-white-50 lh-1 text-uppercase extra-small">
                                            {{ optional($update->published_at)->translatedFormat('M') ?? $update->created_at->translatedFormat('M') }}
                                        </span>
                                    </div>

                                    {{-- Title & Meta --}}
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold text-dark mb-1">
                                            {{ $update->title }}</h6>
                                        <div class="d-flex align-items-center gap-2">
                                            @if (($update->type ?? '') === 'distribution')
                                                <span
                                                    class="badge bg-success bg-opacity-10 text-success extra-small fw-bold border border-success border-opacity-10">
                                                    PENYALURAN
                                                </span>
                                            @endif
                                            <span class="fw-bold text-black small">
                                                <i class="bi bi-clock me-1 text-primary"></i>
                                                {{ $update->published_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Toggle Icon --}}
                                    <div class="text-secondary transition-transform" :class="open ? 'rotate-180' : ''">
                                        <i class="bi bi-chevron-down"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- Body --}}
                            <div x-show="open" x-collapse class="border-top border-light bg-light bg-opacity-10">
                                <div class="card-body p-3 p-md-4">
                                    <div class="text-dark mb-3 ck-content small" style="line-height: 1.6;">
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

                    @if ($this->campaign->updates()->count() + $this->campaign->distributions()->count() > $this->updatePerPage)
                        <div wire:intersect="loadMoreUpdates" class="text-center py-3">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    @endif
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
                    @if ($campaign->is_optimized)
                        <div class="d-flex align-items-center gap-3 p-3 mb-3 rounded-3 border-0"
                            style="background-color: rgba(73, 163, 227, 0.08); color: #49a3e3;">
                            <i class="bi bi-megaphone-fill fs-4"></i>
                            <div class="small fw-bold lh-sm">Program ini sedang dalam optimasi & promosi intensif untuk
                                mempercepat pencapaian target.</div>
                        </div>
                    @endif
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

                    @if ($this->donorCount > $this->donorPerPage)
                        <div wire:intersect="loadMoreDonors" class="text-center py-3">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    @endif
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

                    @if ($this->campaign->prayers()->count() > $this->prayerPerPage)
                        <div wire:intersect="loadMorePrayers" class="text-center py-3">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    @endif
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

    @push('scripts')
        <script>
            fbq('track', 'ViewContent');
        </script>
    @endpush
</div>
