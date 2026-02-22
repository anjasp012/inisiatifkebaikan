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
    <div class="row g-4">
        {{-- Header & Stats --}}
        <div class="col-12">
            <div class="card card-dashboard border-0 mb-0">
                <div class="card-body border-bottom">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <h5 class="fw-bold mb-1">Detail Campaign</h5>
                            <p class="text-muted small mb-0">{{ $campaign->title }}</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.campaign') }}" wire:navigate class="btn btn-light border">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                            <a href="{{ route('admin.campaign.ubah', $campaign->slug) }}" wire:navigate
                                class="btn btn-primary text-white fw-bold">
                                <i class="bi bi-pencil-square me-1"></i> Edit Campaign
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="p-4 bg-light bg-opacity-50">
                        <div class="row g-4 align-items-center">
                            <div class="col-md-7">
                                <h6 class="text-uppercase text-muted extra-small fw-bold mb-2 ls-sm">DANA TERHIMPUN</h6>
                                <div class="display-6 fw-bold text-primary mb-3">
                                    Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    @if ($campaign->status == 'active')
                                        <span class="badge rounded-pill px-3 py-2 bg-success text-white">
                                            <i class="bi bi-check-circle-fill me-1"></i> Status: Aktif
                                        </span>
                                    @elseif($campaign->status == 'pending')
                                        <span class="badge rounded-pill px-3 py-2 bg-warning text-dark">
                                            <i class="bi bi-clock-fill me-1"></i> Status: Pending
                                        </span>
                                    @elseif($campaign->status == 'hidden')
                                        <span class="badge rounded-pill px-3 py-2 bg-secondary text-white">
                                            <i class="bi bi-eye-slash-fill me-1"></i> Status: Disembunyikan
                                        </span>
                                    @else
                                        <span class="badge rounded-pill px-3 py-2 bg-danger text-white">
                                            <i class="bi bi-x-circle-fill me-1"></i> Status:
                                            {{ ucfirst($campaign->status) }}
                                        </span>
                                    @endif

                                    <span class="badge rounded-pill px-3 py-2 bg-light text-dark border">
                                        <i class="bi bi-tag-fill me-1 text-primary"></i>
                                        {{ $campaign->category->name ?? 'Tanpa Kategori' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="p-3 bg-white rounded-4 border shadow-sm">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="small text-muted fw-bold">Progress Capaian</span>
                                        <span class="small fw-bold text-primary">
                                            @if ($campaign->target_amount > 0)
                                                {{ number_format(($campaign->collected_amount / $campaign->target_amount) * 100, 1) }}%
                                            @else
                                                100%
                                            @endif
                                        </span>
                                    </div>
                                    <div class="progress mb-2" style="height: 10px;">
                                        @php $percent = $campaign->target_amount > 0 ? min(100, ($campaign->collected_amount / $campaign->target_amount) * 100) : 100; @endphp
                                        <div class="progress-bar bg-primary" role="progressbar"
                                            style="width: {{ $percent }}%"></div>
                                    </div>
                                    <div class="d-flex justify-content-between extra-small text-muted">
                                        <span>Target: Rp
                                            {{ number_format($campaign->target_amount, 0, ',', '.') }}</span>
                                        <span>{{ number_format($campaign->donations_count ?? $campaign->donations()->where('status', 'success')->count(), 0, ',', '.') }}
                                            Donatur</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="col-lg-8">
            <div class="card card-dashboard border-0 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4 d-flex align-items-center">
                        <i class="bi bi-info-circle-fill me-2 text-primary"></i> Informasi Detail
                    </h6>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="text-muted extra-small fw-bold text-uppercase mb-2 d-block">Judul
                                Program</label>
                            <div class="p-3 bg-light rounded-3 fw-bold">{{ $campaign->title }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted extra-small fw-bold text-uppercase mb-2 d-block">Fundraiser /
                                Mitra</label>
                            <div class="p-3 bg-light rounded-3">
                                <div class="fw-bold">
                                    {{ $campaign->fundraiser ? $campaign->fundraiser->foundation_name : 'Inisiatif Kebaikan (Internal)' }}
                                </div>
                                <div class="small text-muted">
                                    {{ $campaign->fundraiser ? $campaign->fundraiser->user->email : 'admin@inisiatifkebaikan.org' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted extra-small fw-bold text-uppercase mb-2 d-block">Lokasi
                                Penempatan</label>
                            <div class="p-3 bg-light rounded-3">
                                <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                {{ $campaign->location ?? 'Nasional / Seluruh Indonesia' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted extra-small fw-bold text-uppercase mb-2 d-block">Periode
                                Campaign</label>
                            <div class="p-3 bg-light rounded-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <small class="text-muted d-block">Mulai</small>
                                    <span
                                        class="fw-bold">{{ $campaign->start_date ? $campaign->start_date->format('d M Y') : '-' }}</span>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block">Berakhir</small>
                                    <span
                                        class="fw-bold">{{ $campaign->end_date ? $campaign->end_date->format('d M Y') : 'Tanpa Batas' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 border-dashed">

                    <h6 class="fw-bold mb-4">Deskripsi Lengkap</h6>
                    <div class="campaign-description p-3 border rounded-4 bg-white shadow-sm"
                        style="max-height: 500px; overflow-y: auto;">
                        {!! $campaign->description !!}
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Thumbnail Card --}}
            <div class="card card-dashboard border-0 mb-4 overflow-hidden">
                <div class="position-relative">
                    <img src="{{ $campaign->thumbnail_url }}" class="card-img-top w-100"
                        style="height: 200px; object-fit: cover;" alt="{{ $campaign->title }}">
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-white text-dark shadow-sm">
                            <i class="bi bi-image me-1"></i> Thumbnail
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 small text-uppercase text-muted ls-sm">PROPERTI & VISIBILITAS</h6>
                    <div class="list-group list-group-flush border rounded-3 overflow-hidden">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="small text-muted">Prioritas</span>
                            @if ($campaign->is_priority)
                                <span
                                    class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Ya</span>
                            @else
                                <span class="badge bg-light text-muted border rounded-pill">Tidak</span>
                            @endif
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="small text-muted">Optimasi Admin</span>
                            @if ($campaign->is_optimized)
                                <span
                                    class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">Ya</span>
                            @else
                                <span class="badge bg-light text-muted border rounded-pill">Tidak</span>
                            @endif
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="small text-muted">Tampil di Slider</span>
                            @if ($campaign->is_slider)
                                <span
                                    class="badge bg-info-subtle text-info border border-info-subtle rounded-pill">Ya</span>
                            @else
                                <span class="badge bg-light text-muted border rounded-pill">Tidak</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Stats Sidebar --}}
            <div class="card card-dashboard border-0 border-start border-4 border-primary">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 extra-small text-uppercase text-muted">
                        <i class="bi bi-graph-up-arrow me-1"></i> Statistik Penarikan
                    </h6>
                    @php
                        $totalWithdrawn = \App\Models\Withdrawal::where('campaign_id', $campaign->id)
                            ->where('status', 'success')
                            ->sum('amount');
                        $available = $campaign->collected_amount - $totalWithdrawn;
                    @endphp
                    <div class="mb-3">
                        <small class="text-muted d-block">Dana Belum Dicairkan</small>
                        <h4 class="fw-bold text-success mb-0">Rp {{ number_format($available, 0, ',', '.') }}</h4>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted d-block">Total Pernah Dicairkan</small>
                        <h6 class="fw-bold text-dark">Rp {{ number_format($totalWithdrawn, 0, ',', '.') }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
