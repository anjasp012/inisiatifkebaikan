<?php

use App\Models\Distribution;
use Livewire\Component;
use Livewire\Attributes\Computed;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Facades\View;

new class extends Component {
    public int $perPage = 5;

    public function mount()
    {
        $seoData = new SEOData(title: 'Laporan Penyaluran | Inisiatif Kebaikan', description: 'Daftar rincian penyaluran bantuan dan donasi dari para muhsinin untuk para penerima manfaat.', image: asset('assets/images/og-image.jpg'));

        View::share('seoData', $seoData);
    }

    public function loadMore()
    {
        $this->perPage += 5;
    }

    #[Computed]
    public function distributions()
    {
        return Distribution::with('campaign')->orderBy('distribution_date', 'desc')->limit($this->perPage)->get();
    }

    #[Computed]
    public function hasMore()
    {
        return Distribution::count() > $this->perPage;
    }
};
?>

<div>
    <x-app.navbar-secondary title="Laporan Penyaluran" />

    <section class="distribution-index-page">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 class="section-title">Riwayat Penyaluran</h2>
            </div>

            <div class="row g-3">
                @forelse ($this->distributions as $distribution)
                    <div class="col-12">
                        <x-app.distribution-card :distribution="$distribution" />
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-file-earmark-text text-primary empty-state-icon"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Belum ada laporan</h6>
                        <p class="text-muted small mb-4 px-4">
                            Belum ada laporan penyaluran yang dipublikasikan saat ini.
                        </p>
                    </div>
                @endforelse
            </div>

            @if ($this->hasMore)
                <div x-intersect="$wire.loadMore()" class="text-center py-4 mb-5">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            @else
                <div class="py-5"></div>
            @endif
        </div>
    </section>

    <x-app.bottom-nav />
</div>
