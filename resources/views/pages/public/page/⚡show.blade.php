<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Page;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Facades\View;

new #[Layout('layouts.app')] class extends Component {
    public Page $page;

    public function mount(Page $page)
    {
        if (!$page->is_active && !auth('admin')->check()) {
            abort(404);
        }

        $this->page = $page;

        $seoData = new SEOData(title: $this->page->title . ' | ' . \App\Models\Setting::get('website_name', 'Inisiatif Kebaikan'), description: Str::limit(strip_tags($this->page->content), 160));

        View::share('seoData', $seoData);
    }
};
?>

<div>
    <x-app.navbar-secondary :title="$page->title" />

    <section class="static-page-section">
        <div class="container-fluid">
            {!! $page->content !!}
        </div>
    </section>

    <x-app.bottom-nav />
</div>

<style>
    .prose img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
    }

    .prose p {
        margin-bottom: 1rem;
        color: #4b5563;
        line-height: 1.6;
    }

    .min-vh-50 {
        min-height: 50vh;
    }
</style>
