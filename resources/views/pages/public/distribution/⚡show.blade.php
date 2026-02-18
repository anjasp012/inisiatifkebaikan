<?php

use App\Models\Distribution;
use Livewire\Component;
use Illuminate\Support\Facades\View;

new class extends Component {
    public Distribution $distribution;

    public function mount(Distribution $distribution)
    {
        $this->distribution = $distribution->load('campaign');

        View::share('seoData', $this->distribution->getDynamicSEOData());
    }
};
?>

<div>
    <x-app.navbar-secondary title="Detail Laporan" />

    <section class="detail-image-section">
        <img src="{{ $distribution->file_url }}" alt="Dokumentasi Penyaluran">
    </section>

    <section class="detail-info-section">
        <div class="container-fluid">
            <div class="detail-badges">
                @if ($distribution->campaign->category)
                    <span class="detail-badge">{{ strtoupper($distribution->campaign->category->name) }}</span>
                @endif
                <span class="detail-badge" style="background: rgba(0,0,0,0.05); color: #666;">
                    <i class="bi bi-calendar-event me-1"></i>
                    {{ Carbon\Carbon::parse($distribution->distribution_date)->translatedFormat('d F Y') }}
                </span>
            </div>

            <h1 class="detail-title">{{ $distribution->recipient_name }}</h1>

            <div class="p-3 mb-4 rounded-4" style="background: #f8f9fa; border: 1px solid #eee;">
                <div class="small text-muted mb-1"
                    style="font-size: 11px; font-weight: 600; text-uppercase; letter-spacing: 0.5px;">Dana Disalurkan
                </div>
                <h4 class="fw-bold text-primary mb-0">Rp {{ number_format($distribution->amount, 0, ',', '.') }}</h4>
            </div>

            <div class="d-flex align-items-center gap-2 p-3 rounded-4 mb-4"
                style="background: #fff9f0; border: 1px solid #ffeeba;">
                <i class="bi bi-info-circle text-warning"></i>
                <div class="small">
                    Bantuan disalurkan melalui program: <br>
                    <a href="{{ route('campaign.show', $distribution->campaign->slug) }}" wire:navigate
                        class="fw-bold text-dark text-decoration-none">
                        {{ $distribution->campaign->title }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="detail-content-section pb-5">
        <div class="container-fluid">
            <h6 class="fw-bold mb-3">Kabar Penyaluran</h6>
            <div class="detail-content__story ck-content small text-muted lh-lg">
                {!! $distribution->description !!}
            </div>

            <div class="card border-0 p-4 rounded-4 mt-5"
                style="background: linear-gradient(135deg, #f0f7ff, #e0efff);">
                <h6 class="fw-bold text-primary mb-2">Terima Kasih Muhsinin!</h6>
                <p class="small text-muted mb-4">Bantuan Anda telah sampai kepada yang berhak. Teruslah membersamai kami
                    dalam menebar inisiatif kebaikan.</p>
                <a href="{{ route('campaign.show', $distribution->campaign->slug) }}" wire:navigate
                    class="btn btn-primary fw-bold rounded-pill w-100 py-2">
                    Donasi Lagi <i class="bi bi-heart-fill ms-2" style="font-size: 10px;"></i>
                </a>
            </div>
        </div>
    </section>

    <x-app.bottom-nav />
</div>
