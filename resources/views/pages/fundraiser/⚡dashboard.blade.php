<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Fundraiser;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.app')] class extends Component {
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
        // Refresh fundraiser data (important for polling status changes)
        $this->fundraiser = Fundraiser::where('user_id', Auth::id())->first();

        if (!$this->fundraiser) {
            return;
        }

        // Campaign statistics
        $this->totalCampaigns = Campaign::where('fundraiser_id', $this->fundraiser->id)->count();
        $this->activeCampaigns = Campaign::where('fundraiser_id', $this->fundraiser->id)->where('status', 'active')->count();

        // Donation statistics (only success)
        $campaignIds = Campaign::where('fundraiser_id', $this->fundraiser->id)->pluck('id');

        $this->totalDonations = Donation::whereIn('campaign_id', $campaignIds)->where('status', 'success')->count();
        $this->totalAmount = Donation::whereIn('campaign_id', $campaignIds)->where('status', 'success')->sum('amount');

        // Recent donations
        $this->recentDonations = Donation::whereIn('campaign_id', $campaignIds)->with('campaign')->where('status', 'success')->latest()->limit(20)->get();
    }
}; ?>

<div wire:poll.10s="loadStatistics">
    {{-- Top Header Section --}}
    <div class="bg-primary text-white p-4 pb-5 shadow-sm position-relative mb-5" style="border-radius: 0 0 2rem 2rem;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-0">Dashboard Mitra</h5>
                <small class="opacity-75">Halo, {{ Auth::user()->name }}!</small>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <div class="position-relative">
                <img src="{{ $fundraiser->logo_url }}" width="60" height="60"
                    class="rounded-circle bg-white shadow-soft object-fit-cover border border-2 border-white">
                @if ($fundraiser->status == 'approved')
                    <div class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white d-flex align-items-center justify-content-center"
                        style="width: 20px; height: 20px;">
                        <i class="bi bi-check-lg text-white extra-small"></i>
                    </div>
                @endif
            </div>
            <div class="overflow-hidden">
                <h6 class="fw-bold mb-1 text-truncate">{{ $fundraiser->foundation_name }}</h6>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-white text-primary extra-small rounded-pill px-2 py-1 fw-bold shadow-none">
                        {{ $fundraiser->status == 'approved' ? 'Terverifikasi' : 'Proses Verifikasi' }}
                    </span>
                    <small class="opacity-75 extra-small ls-sm fw-bold">ID: #{{ $fundraiser->id }}</small>
                </div>
            </div>
        </div>

        {{-- Floating Stats Row --}}
        <div class="row g-3 px-3 position-absolute start-0 end-0" style="bottom: -40px;">
            <div class="col-6">
                <div class="card border-0 shadow-soft rounded-3 h-100">
                    <div class="card-body p-3">
                        <small class="text-muted d-block mb-1 extra-small fw-bold text-uppercase ls-sm">Total
                            Donasi</small>
                        <h6 class="fw-bold text-primary mb-0">Rp{{ number_format($totalAmount, 0, ',', '.') }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card border-0 shadow-soft rounded-3 h-100">
                    <div class="card-body p-3">
                        <small class="text-muted d-block mb-1 extra-small fw-bold text-uppercase ls-sm">Campaign</small>
                        <h6 class="fw-bold text-dark mb-0">{{ $totalCampaigns }} <small
                                class="text-muted fw-normal x-small">({{ $activeCampaigns }} Aktif)</small></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        {{-- Status Alert --}}
        @if ($fundraiser->status !== 'approved')
            <div
                class="card border-0 shadow-sm rounded-3 bg-warning bg-opacity-10 mb-4 animate__animated animate__pulse animate__infinite">
                <div class="card-body p-4 text-center">
                    <div class="bg-warning bg-opacity-20 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width: 60px; height: 60px;">
                        <i class="bi bi-clock-history fs-2"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-2">Menunggu Verifikasi Admin</h6>
                    <p class="text-muted extra-small mb-0">Akun Anda sedang dalam proses peninjauan oleh tim admin.
                        Fitur galang dana akan tersedia setelah akun Anda diverifikasi.</p>
                </div>
            </div>
        @elseif(session('success'))
            <div class="alert alert-success border-0 rounded-3 shadow-sm mb-4 d-flex align-items-center gap-3">
                <i class="bi bi-check-circle-fill"></i>
                <small class="fw-medium">{{ session('success') }}</small>
            </div>
        @endif

        {{-- Quick Menu --}}
        <div class="mb-4 @if ($fundraiser->status !== 'approved') opacity-50 pointer-events-none @endif"
            @if ($fundraiser->status !== 'approved') style="filter: grayscale(1); cursor: not-allowed;" @endif>
            <h6 class="fw-bold text-dark mb-3 ps-1 small text-uppercase ls-sm">Menu Utama</h6>
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-0">
                    <div class="row g-0 py-3 text-center">
                        <div class="col-4 border-end">
                            <a href="{{ route('fundraiser.campaign.buat') }}" wire:navigate
                                class="text-decoration-none text-dark group-hover">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center shadow-micro transition-200"
                                    style="width: 48px; height: 48px;">
                                    <i class="bi bi-plus-lg fs-5"></i>
                                </div>
                                <span class="extra-small fw-bold d-block">Buat Baru</span>
                            </a>
                        </div>
                        <div class="col-4 border-end">
                            <a href="{{ route('fundraiser.galang-dana.index') }}" wire:navigate
                                class="text-decoration-none text-dark group-hover">
                                <div class="bg-info bg-opacity-10 text-info rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center shadow-micro transition-200"
                                    style="width: 48px; height: 48px;">
                                    <i class="bi bi-stack fs-5"></i>
                                </div>
                                <span class="extra-small fw-bold d-block">Manage</span>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="{{ route('fundraiser.pencairan.index') }}" wire:navigate
                                class="text-decoration-none text-dark group-hover">
                                <div class="bg-success bg-opacity-10 text-success rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center shadow-micro transition-200"
                                    style="width: 48px; height: 48px;">
                                    <i class="bi bi-wallet2 fs-5"></i>
                                </div>
                                <span class="extra-small fw-bold d-block">Pencairan</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Donations Section --}}
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 ps-1">
                <h6 class="fw-bold text-dark mb-0 small text-uppercase ls-sm">Donasi Masuk Terbaru</h6>
            </div>

            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-5">
                @forelse($recentDonations as $donation)
                    <div class="p-3 border-bottom d-flex align-items-center gap-3">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-person-heart text-danger"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold extra-small mb-1 text-dark">{{ $donation->donor_name }}</h6>
                                <span
                                    class="fw-bold text-success small">+{{ number_format($donation->amount, 0, ',', '.') }}</span>
                            </div>
                            <small
                                class="text-muted extra-small text-truncate d-block mb-1 opacity-75">{{ $donation->campaign->title }}</small>
                            <small
                                class="text-muted extra-small opacity-50">{{ $donation->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                @empty
                    <div class="p-5 text-center">
                        <i class="bi bi-emoji-frown text-muted fs-1 mb-3 opacity-25"></i>
                        <p class="text-muted small mb-0">Belum ada donasi masuk.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <x-app.bottom-nav />
</div>

<style>
    .group-hover:hover .bg-opacity-10 {
        background-color: rgba(var(--bs-primary-rgb), 0.2) !important;
        transform: translateY(-2px);
    }
</style>
