@props(['article'])

<a href="{{ route('article.show', $article->slug) }}" class="card article-card" wire:navigate>
    <div class="position-relative">
        <img src="{{ $article->thumbnail_url }}" class="card-img-top" alt="{{ $article->title }}">
        <span class="badge article-card-badge">
            {{ $article->category }}
        </span>
    </div>

    <div class="card-body">
        <div class="article-card-date">
            <i class="bi bi-clock"></i> {{ $article->created_at->diffForHumans() }}
        </div>

        <h6 class="card-title">{{ $article->title }}</h6>

        <p class="article-card-excerpt">{{ Str::limit(strip_tags($article->content), 100) }}</p>
    </div>
</a>
