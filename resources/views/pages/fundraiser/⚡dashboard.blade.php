<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Fundraiser;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.mobile')] class extends Component {
    public $fundraiser;
    public $totalCampaigns = 0;
    public $activeCampaigns = 0;
    public $totalDonations = 0;
    public $totalAmount = 0;
    public $recentDonations = [];

    public function mount()
    {
        $this->fundraiser = Fundraiser::where('user_id', Auth::id())->first();

        if (!$this->fundraiser) {
            session()->flash('error', 'Anda belum terdaftar sebagai fundraiser');
            $this->redirectRoute('fundraiser.daftar', navigate: true);
            return;
        }

        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        // Campaign statistics
        $this->totalCampaigns = Campaign::where('fundraiser_id', $this->fundraiser->id)->count();
        $this->activeCampaigns = Campaign::where('fundraiser_id', $this->fundraiser->id)->where('status', 'active')->count();

        // Donation statistics (only success)
        $campaignIds = Campaign::where('fundraiser_id', $this->fundraiser->id)->pluck('id');

        $this->totalDonations = Donation::whereIn('campaign_id', $campaignIds)->where('status', 'success')->count();

        $this->totalAmount = Donation::whereIn('campaign_id', $campaignIds)->where('status', 'success')->sum('amount');

        // Recent donations
        $this->recentDonations = Donation::whereIn('campaign_id', $campaignIds)->with('campaign')->where('status', 'success')->latest()->limit(10)->get();
    }
}; ?>

<div class="d-flex flex-column min-vh-100 bg-light">
    {{-- Header --}}
    <div class="bg-primary text-white p-4 pb-5 rounded-bottom-4 shadow-sm position-relative">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Dashboard</h5>
            <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                <i class="bi bi-bell text-white"></i>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <img src="{{ $fundraiser->logo_url }}" width="50" height="50"
                class="rounded-circle bg-white border border-2 border-white object-fit-cover shadow-sm">
            <div>
                <h6 class="fw-bold mb-0">{{ Str::limit($fundraiser->foundation_name, 20) }}</h6>
                @if ($fundraiser->status == 'approved')
                    <span class="badge bg-white text-primary text-uppercase x-small py-1 px-2 rounded-pill"><i
                            class="bi bi-check-circle-fill me-1"></i> Terverifikasi</span>
                @else
                    <span class="badge bg-warning text-dark text-uppercase x-small py-1 px-2 rounded-pill"><i
                            class="bi bi-clock-fill me-1"></i> {{ $fundraiser->status }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="px-3" style="margin-top: -30px;">
        {{-- Stats Grid --}}
        <div class="row g-2 mb-3">
            <div class="col-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-3">
                        <small class="text-muted d-block mb-1">Total Donasi</small>
                        <h6 class="fw-bold text-primary mb-0">Rp {{ number_format($totalAmount, 0, ',', '.') }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-3">
                        <small class="text-muted d-block mb-1">Campaign</small>
                        <h6 class="fw-bold text-dark mb-0">{{ $totalCampaigns }} <small
                                class="text-muted fw-normal">({{ $activeCampaigns }} Aktif)</small></h6>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <h6 class="fw-bold mb-2 ps-1">Menu Cepat</h6>
        <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden">
            <div class="card-body p-0">
                <div class="d-flex justify-content-around py-3">
                    <a href="{{ route('fundraiser.campaign.buat') }}"
                        class="text-center text-decoration-none text-dark w-100 border-end">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center"
                            style="width: 45px; height: 45px;">
                            <i class="bi bi-plus-lg fs-5"></i>
                        </div>
                        <small class="fw-medium x-small">Buat Campaign</small>
                    </a>
                    <a href="{{ route('fundraiser.campaign') }}"
                        class="text-center text-decoration-none text-dark w-100 border-end">
                        <div class="bg-info bg-opacity-10 text-info rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center"
                            style="width: 45px; height: 45px;">
                            <i class="bi bi-list-task fs-5"></i>
                        </div>
                        <small class="fw-medium x-small">Kelola</small>
                    </a>
                    <a href="#" class="text-center text-decoration-none text-dark w-100">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center"
                            style="width: 45px; height: 45px;">
                            <i class="bi bi-wallet2 fs-5"></i>
                        </div>
                        <small class="fw-medium x-small">Pencairan</small>
                    </a>
                </div>
            </div>
        </div>

        {{-- Recent Donations --}}
        <div class="d-flex justify-content-between align-items-center mb-2 ps-1">
            <h6 class="fw-bold mb-0">Donasi Terbaru</h6>
            <a href="#" class="text-primary text-decoration-none x-small fw-bold">Lihat Semua</a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
            @forelse($recentDonations as $donation)
                <div class="p-3 border-bottom d-flex align-items-center">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 40px; height: 40px;">
                        <i class="bi bi-heart-fill text-danger small"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <h6 class="fw-bold small mb-0">{{ $donation->donor_name }}</h6>
                            <span
                                class="text-success fw-bold x-small">+{{ number_format($donation->amount, 0, ',', '.') }}</span>
                        </div>
                        <small class="text-muted x-small text-truncate d-block"
                            style="max-width: 200px;">{{ $donation->campaign->title }}</small>
                        <small class="text-muted x-small">{{ $donation->created_at->diffForHumans() }}</small>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-muted small">
                    Belum ada donasi masuk.
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .x-small {
        font-size: 0.75rem;
    }
</style>
