<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Fundraiser;
use App\Models\Campaign;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

new #[Layout('layouts.app')] class extends Component {
    public $fundraiser;
    public $campaigns = [];

    public function mount()
    {
        $this->fundraiser = Fundraiser::where('user_id', Auth::id())->first();

        if (!$this->fundraiser) {
            session()->flash('error', 'Anda belum terdaftar sebagai fundraiser');
            $this->redirectRoute('fundraiser.daftar', navigate: true);
            return;
        }

        if ($this->fundraiser->status !== 'approved') {
            $this->redirectRoute('fundraiser.dashboard', navigate: true);
            return;
        }

        $this->loadCampaigns();
    }

    public function loadCampaigns()
    {
        $this->campaigns = Campaign::where('fundraiser_id', $this->fundraiser->id)->latest()->get();
    }
}; ?>

<div>
    <x-app.navbar-secondary title="Galang Dana Saya" :route="route('fundraiser.dashboard')" />

    <section class="fundraiser-index-page py-4">
        <div class="container-fluid">
            {{-- Header/Quota Card --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">Buat galang dana</h6>
                        <small class="text-muted extra-small">Kuota aktif:
                            <span
                                class="fw-bold text-dark">{{ 20 - $fundraiser->campaigns()->where('status', 'active')->count() }}</span>
                        </small>
                    </div>

                    <a href="{{ route('fundraiser.campaign.buat') }}" wire:navigate
                        class="btn btn-outline-primary w-100 py-3 fw-bold mb-3 border-2 border-dashed shadow-none">
                        <i class="bi bi-plus-lg me-1"></i> Buat galang dana baru
                    </a>

                    <div
                        class="alert alert-info border-0 bg-info bg-opacity-10 d-flex align-items-center gap-2 py-3 px-3 mb-0 rounded-3">
                        <i class="bi bi-info-circle-fill text-info fs-5"></i>
                        <p class="mb-0 x-small text-dark fw-medium">Ingin galang danamu lebih sukses? <a href="#"
                                class="text-primary text-decoration-none fw-bold">Lihat panduan</a></p>
                    </div>
                </div>
            </div>

            {{-- Campaign List --}}
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold text-dark mb-0">Kelola Galang Dana</h6>
                <span class="badge bg-light text-dark fw-bold border">{{ count($campaigns) }} Program</span>
            </div>

            {{-- Campaign List --}}
            <div class="d-flex flex-column gap-3 mb-4">
                @forelse($campaigns as $campaign)
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                        <div class="card-body p-3">
                            <div class="d-flex gap-3">
                                <img src="{{ $campaign->thumbnail_url }}"
                                    class="rounded-3 object-fit-cover bg-light flex-shrink-0" width="85"
                                    height="85" alt="{{ $campaign->title }}">
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <span
                                            class="badge {{ $campaign->status === 'active' ? 'bg-success' : ($campaign->status === 'pending' ? 'bg-warning' : 'bg-secondary') }} bg-opacity-10 {{ $campaign->status === 'active' ? 'text-success' : ($campaign->status === 'pending' ? 'text-warning' : 'text-secondary') }} extra-small rounded-pill px-2 shadow-none border-0">
                                            {{ ucfirst($campaign->status) }}
                                        </span>
                                        <small
                                            class="text-muted extra-small">{{ $campaign->created_at->format('d M Y') }}</small>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-2 text-truncate-2 small line-height-base">
                                        {{ $campaign->title }}</h6>

                                    <div class="progress rounded-pill bg-light mb-1" style="height: 4px;">
                                        <div class="progress-bar bg-primary rounded-pill transition-all"
                                            style="width: {{ $campaign->target_amount > 0 ? min(100, ($campaign->collected_amount / $campaign->target_amount) * 100) : 0 }}%">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <div class="lh-1">
                                            <span class="text-muted extra-small d-block mb-1">Terkumpul</span>
                                            <span
                                                class="fw-bold text-dark small">Rp{{ number_format($campaign->collected_amount, 0, ',', '.') }}</span>
                                        </div>
                                        <a href="{{ route('fundraiser.galang-dana.kelola', $campaign->slug) }}"
                                            wire:navigate
                                            class="btn btn-primary btn-sm rounded-pill px-3 py-2 extra-small fw-bold shadow-sm">
                                            Kelola
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div class="bg-white rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 80px; height: 80px;">
                            <i class="bi bi-folder2-open fs-2 text-muted opacity-50"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Belum Ada Galang Dana</h6>
                        <p class="text-muted small">Mulai buat galang dana pertamamu sekarang.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <x-app.bottom-nav />
</div>

<style>
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-height-base {
        line-height: 1.4;
    }

    .border-dashed {
        border-style: dashed !important;
    }
</style>
