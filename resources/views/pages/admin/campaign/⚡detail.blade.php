<?php

use App\Models\Campaign;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts.admin')] class extends Component {
    public Campaign $campaign;

    public function mount(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }
}; ?>

<div>
    <div class="row mb-4 align-items-center">
        <div class="col-sm">
            <h1 class="h3 mb-0 text-gray-800">Detail Campaign</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 px-0 bg-transparent">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" wire:navigate>Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.campaign') }}" wire:navigate>Campaign</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail</li>
                </ol>
            </nav>
        </div>
        <div class="col-sm-auto mt-3 mt-sm-0">
            <a href="{{ route('admin.campaign') }}" wire:navigate class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <a href="{{ route('admin.campaign.ubah', $campaign->slug) }}" wire:navigate class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i> Edit Campaign
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <img src="{{ Str::startsWith($campaign->image, ['http://', 'https://']) ? $campaign->image : asset('storage/' . $campaign->image) }}"
                    class="card-img-top" alt="{{ $campaign->title }}">
                <div class="card-body">
                    <h5 class="card-title fw-bold">{{ $campaign->title }}</h5>
                    <p class="text-muted small mb-3">Oleh:
                        {{ $campaign->fundraiser ? $campaign->fundraiser->foundation_name : 'Inisiatif Kebaikan' }}</p>

                    <div class="d-flex justify-content-between mb-1">
                        <span class="small fw-bold text-primary">Rp
                            {{ number_format($campaign->collected_amount, 0, ',', '.') }}</span>
                        <span class="small text-muted">Target: @if ($campaign->target_amount)
                                Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}
                            @else
                                Tidak Terbatas
                            @endif
                        </span>
                    </div>

                    @if ($campaign->target_amount)
                        <div class="progress mb-3" style="height: 8px;">
                            @php $percent = min(100, ($campaign->collected_amount / max(1, $campaign->target_amount)) * 100); @endphp
                            <div class="progress-bar bg-primary" role="progressbar"
                                style="width: {{ $percent }}%"></div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mb-0 mt-3 border-top pt-3">
                        <span class="small">Status</span>
                        @if ($campaign->status === 'active')
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Aktif</span>
                        @elseif ($campaign->status === 'completed')
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">Selesai</span>
                        @elseif ($campaign->status === 'hidden')
                            <span
                                class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">Disembunyikan</span>
                        @elseif ($campaign->status === 'pending')
                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">Pending</span>
                        @elseif ($campaign->status === 'rejected')
                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Ditolak</span>
                        @else
                            <span
                                class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">{{ ucfirst($campaign->status) }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-4 border-bottom pb-2">Informasi Campaign</h5>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Kategori</div>
                        <div class="col-sm-8 fw-semibold">{{ $campaign->category->name ?? '-' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Lokasi / Penempatan</div>
                        <div class="col-sm-8">{{ $campaign->location ?? '-' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Tanggal Mulai</div>
                        <div class="col-sm-8">
                            {{ $campaign->start_date ? $campaign->start_date->format('d M Y') : '-' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Tanggal Selesai</div>
                        <div class="col-sm-8">
                            {{ $campaign->end_date ? $campaign->end_date->format('d M Y') : 'Tidak Terbatas' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-muted">Visibilitas</div>
                        <div class="col-sm-8">
                            @if ($campaign->is_priority)
                                <span
                                    class="badge bg-info bg-opacity-10 text-info rounded-pill px-2 me-1">Prioritas</span>
                            @endif
                            @if ($campaign->is_optimized)
                                <span
                                    class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 me-1">Optimasi</span>
                            @endif
                            @if ($campaign->is_slider)
                                <span
                                    class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">Slider</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-bold mb-4 border-bottom pb-2">Deskripsi</h5>
                    <div class="campaign-description">
                        {!! $campaign->description !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
