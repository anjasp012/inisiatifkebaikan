<?php

use Livewire\Component;
use App\Models\Donation;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;

use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Facades\View;

new class extends Component {
    public array $localHistory = [];

    public function mount()
    {
        $seoData = new SEOData(title: 'Donasi Saya | Inisiatif Kebaikan', robots: 'noindex, nofollow');

        View::share('seoData', $seoData);
    }

    public function syncHistory(array $history)
    {
        if (Auth::check() && !empty($history)) {
            Donation::whereIn('transaction_id', $history)
                ->whereNull('user_id')
                ->update(['user_id' => Auth::id()]);

            $this->dispatch('history-synced');
        }
    }

    public function setLocalHistory(array $history)
    {
        $this->localHistory = $history;
    }

    #[Computed]
    public function donations()
    {
        $query = Donation::with('campaign');

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } elseif (!empty($this->localHistory)) {
            $query->whereIn('transaction_id', $this->localHistory);
        } else {
            return collect();
        }

        return $query->latest()->get();
    }
};
?>

<div x-data="{
    history: JSON.parse(localStorage.getItem('donation_history') || '[]')
}" x-init="if ({{ Auth::check() ? 'true' : 'false' }}) {
    $wire.syncHistory(history);
} else {
    $wire.setLocalHistory(history);
}"
    @history-synced.window="localStorage.removeItem('donation_history'); history = [];">
    <x-app.navbar-secondary title="Donasi Saya" />

    <section class="donasi-saya-section py-4">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 class="section-title">Riwayat Donasi</h2>
            </div>

            @guest
                @if (!empty($this->localHistory))
                    <div class="alert alert-primary border-0 rounded-4 mb-4 small">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        History ini sementara. <a href="{{ route('login') }}" class="fw-bold text-decoration-none">Login</a>
                        untuk simpan permanen.
                    </div>
                @endif
            @endguest

            <div class="donation-list">
                @forelse ($this->donations as $donation)
                    <a href="{{ route('donation.instruction', $donation->transaction_id) }}" wire:navigate
                        class="card border-secondary mb-3 text-decoration-none overflow-hidden">
                        <div class="card-body p-3">
                            <div class="d-flex gap-3">
                                <div class="rounded-3 overflow-hidden avatar-md" style="flex-shrink: 0;">
                                    <img src="{{ $donation->campaign->thumbnail_url }}"
                                        class="w-100 h-100 object-fit-cover" alt="{{ $donation->campaign->title }}">
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h6 class="fw-bold text-dark mb-1 text-truncate small">
                                        {{ $donation->campaign->title }}</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-primary fw-bold small">Rp
                                            {{ number_format($donation->amount, 0, ',', '.') }}</span>
                                        <span
                                            class="badge {{ $donation->status === 'success' ? 'bg-success' : ($donation->status === 'failed' ? 'bg-danger' : 'bg-warning') }} px-2 py-1 extra-small">
                                            {{ ucfirst($donation->status) }}
                                        </span>
                                    </div>
                                    <div class="text-muted extra-small mt-1">
                                        {{ $donation->created_at->translatedFormat('d M Y, H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-gift text-primary empty-state-icon"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Belum ada donasi</h6>
                        <p class="text-muted small mb-4 px-4">
                            Anda belum memiliki riwayat donasi. Semua program kebaikan menanti bantuan Anda.
                        </p>
                        <a href="/" wire:navigate
                            class="btn btn-primary px-4 py-2 rounded-pill fw-bold shadow-sm small">
                            Cari Program Donasi
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <x-app.bottom-nav />
</div>
