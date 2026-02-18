<?php

use App\Models\Article;
use Livewire\Component;
use Livewire\Attributes\Computed;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Facades\View;

new class extends Component {
    public int $perPage = 5;

    public function mount()
    {
        $seoData = new SEOData(title: 'Artikel Terbaru | Inisiatif Kebaikan', description: 'Berita terbaru, kisah inspiratif, dan kabar kebaikan dari seluruh nusantara.', image: asset('assets/images/og-image.jpg'));

        View::share('seoData', $seoData);
    }

    public function loadMore()
    {
        $this->perPage += 5;
    }

    #[Computed]
    public function articles()
    {
        return Article::where('is_published', true)->latest()->limit($this->perPage)->get();
    }

    #[Computed]
    public function hasMore()
    {
        return Article::where('is_published', true)->count() > $this->perPage;
    }
};
?>

<div>
    <x-app.navbar-secondary title="Artikel & Berita" />

    <section class="article-list-section">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 class="section-title">Artikel Terbaru</h2>
            </div>

            <div class="row g-3">
                @forelse ($this->articles as $article)
                    <div class="col-12">
                        <x-app.article-card :article="$article" />
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-journal-text text-primary empty-state-icon"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Belum ada artikel</h6>
                        <p class="text-muted small mb-4 px-4">
                            Belum ada artikel atau berita yang dipublikasikan saat ini.
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
