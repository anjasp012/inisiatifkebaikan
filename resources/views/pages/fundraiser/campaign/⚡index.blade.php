<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Fundraiser;
use App\Models\Campaign;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.mobile')] class extends Component {
    use WithPagination;

    // Use simple pagination for mobile to avoid giant pagination blocks
    protected $paginationTheme = 'simple-bootstrap';

    public $fundraiser;
    public $search = '';

    public function mount()
    {
        $this->fundraiser = Fundraiser::where('user_id', Auth::id())->first();

        if (!$this->fundraiser || $this->fundraiser->status !== 'approved') {
            session()->flash('error', 'Akses ditolak');
            $this->redirectRoute('fundraiser.dashboard', navigate: true);
            return;
        }
    }

    #[Computed]
    public function campaigns()
    {
        return Campaign::where('fundraiser_id', $this->fundraiser->id)
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->withSum(
                [
                    'donations' => function ($q) {
                        $q->where('status', 'success');
                    },
                ],
                'amount',
            )
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }
}; ?>

<div class="d-flex flex-column min-vh-100 bg-light pb-5">
    <div class="p-3 border-bottom d-flex align-items-center gap-2 bg-white sticky-top shadow-sm z-2">
        <a href="{{ route('fundraiser.dashboard') }}" class="btn btn-light btn-sm rounded-circle" wire:navigate>
            <i class="bi bi-arrow-left"></i>
        </a>
        <h6 class="fw-bold mb-0 flex-grow-1">Kelola Campaign</h6>
        <a href="{{ route('fundraiser.campaign.buat') }}" class="btn btn-primary btn-sm rounded-pill fw-bold text-nowrap"
            wire:navigate>
            <i class="bi bi-plus-lg me-1"></i> Baru
        </a>
    </div>

    <div class="px-3 py-3">
        {{-- Search Bar --}}
        <div class="position-relative mb-3">
            <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" class="form-control rounded-pill ps-5 bg-white border-0 py-2 shadow-sm"
                placeholder="Cari campaign..." wire:model.live.debounce.250ms="search">
        </div>

        <div class="d-flex flex-column gap-3">
            @forelse($this->campaigns as $campaign)
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-3">
                        <div class="d-flex gap-3 mb-3">
                            <img src="{{ $campaign->thumbnail_url }}" class="rounded-3 object-fit-cover"
                                style="width: 80px; height: 80px;">
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1 line-clamp-2" style="font-size: 0.9rem;">{{ $campaign->title }}
                                </h6>
                                <span
                                    class="badge {{ $campaign->status == 'active' ? 'bg-success' : 'bg-secondary' }} bg-opacity-10 {{ $campaign->status == 'active' ? 'text-success' : 'text-secondary' }} rounded-pill x-small px-2 py-1">
                                    {{ ucfirst($campaign->status) }}
                                </span>
                            </div>
                        </div>

                        {{-- Progress --}}
                        <div class="mb-2">
                            @php
                                $progress =
                                    $campaign->target_amount > 0
                                        ? min(100, ($campaign->donations_sum_amount / $campaign->target_amount) * 100)
                                        : 0;
                            @endphp
                            <div class="d-flex justify-content-between x-small mb-1">
                                <span class="text-muted">Terkumpul</span>
                                <span class="fw-bold">{{ number_format($progress, 0) }}%</span>
                            </div>
                            <div class="progress rounded-pill bg-light" style="height: 6px;">
                                <div class="progress-bar rounded-pill bg-primary" style="width: {{ $progress }}%">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top">
                            <div class="d-flex flex-column">
                                <span class="x-small text-muted">Total Donasi</span>
                                <span class="fw-bold text-dark small">Rp
                                    {{ number_format($campaign->donations_sum_amount ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('fundraiser.campaign.ubah', $campaign) }}"
                                class="btn btn-outline-primary btn-sm rounded-pill px-3 py-1 x-small fw-bold"
                                wire:navigate>
                                Kelola
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                    Belum ada campaign.<br>Mulai buat kebaikan sekarang!
                </div>
            @endforelse

            <div class="mt-2">
                {{ $this->campaigns->links() }} {{-- Standard links, adjust theme if needed --}}
            </div>
        </div>
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .x-small {
        font-size: 0.75rem;
    }
</style>
