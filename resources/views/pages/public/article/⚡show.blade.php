<?php

use App\Models\Article;
use Livewire\Component;
use Illuminate\Support\Facades\View;

new class extends Component {
    public Article $article;

    public function mount(Article $article)
    {
        if (!$article->is_published) {
            abort(404);
        }

        $this->article = $article;

        // SEO
        View::share('seoData', $this->article->getDynamicSEOData());

        // Record View
        views($this->article)
            ->cooldown(now()->addHours(2))
            ->record();
    }
};
?>

<div>
    <x-app.navbar-secondary title="Detail Artikel" />

    <section class="detail-image-section">
        <img src="{{ $article->thumbnail_url }}" alt="{{ $article->title }}">
    </section>

    <section class="detail-info-section">
        <div class="container-fluid">
            <div class="detail-badges">
                <span class="detail-badge">{{ strtoupper($article->category ?? 'BERITA') }}</span>
                <span class="detail-badge">
                    <i class="bi bi-calendar-event me-1"></i>
                    {{ $article->created_at->translatedFormat('d F Y') }}
                </span>
            </div>

            <h1 class="detail-title mb-3">{{ $article->title }}</h1>

            <div class="d-flex align-items-center gap-2 mb-4">
                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center text-primary fw-bold"
                    style="width: 32px; height: 32px; font-size: 12px;">
                    {{ substr($article->author->name ?? 'Admin', 0, 1) }}
                </div>
                <div class="small">
                    <div class="fw-bold text-dark">{{ $article->author->name ?? 'Tim Inisiatif Kebaikan' }}</div>
                    <div class="text-muted" style="font-size: 10px;">Penulis</div>
                </div>
            </div>
        </div>
    </section>

    <section class="detail-content-section pb-5">
        <div class="container-fluid">
            <div class="detail-content__story ck-content small text-muted lh-lg">
                {!! $article->content !!}
            </div>

            <hr class="my-5 opacity-50">

            <div class="share-article mb-5">
                <h6 class="fw-bold mb-3 small">Bagikan Artikel:</h6>
                <div class="d-flex gap-2">
                    <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . url()->current()) }}"
                        target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3">
                        <i class="bi bi-whatsapp me-1"></i> WhatsApp
                    </a>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(url()->current()) }}"
                        target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                        <i class="bi bi-twitter-x me-1"></i> Twitter
                    </a>
                </div>
            </div>
        </div>
    </section>

    <x-app.bottom-nav />
</div>
