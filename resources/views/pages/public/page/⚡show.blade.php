<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Page;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Facades\View;

new #[Layout('layouts.app')] class extends Component {
    public ?Page $page = null;

    public function mount($page = null)
    {
        // Handle both Page model (from route binding) and string (from custom params)
        if (is_string($page)) {
            $this->page = Page::where('slug', $page)->first();
        } else {
            $this->page = $page;
        }

        if (!$this->page || (!$this->page->is_active && !auth('admin')->check())) {
            abort(404);
        }

        $seoData = new SEOData(title: $this->page->title . ' | ' . \App\Models\Setting::get('website_name', 'Inisiatif Kebaikan'), description: Str::limit(strip_tags($this->page->content), 160));

        View::share('seoData', $seoData);
    }
};
?>

<div>
    <x-app.navbar-secondary :title="$page->title" />

    <section class="static-page-section py-4">
        <div class="container-fluid">
            <div class="prose">
                {!! $page->content !!}
            </div>
        </div>
    </section>


    <footer class="main-footer">
        <div class="container-fluid">
            <!-- Social Media -->
            <div class="d-flex gap-2 mb-4">
                <a href="https://www.facebook.com/inisiatifkebaikan" target="_blank" class="social-btn"><i
                        class="bi bi-facebook"></i></a>
                <a href="https://www.instagram.com/inisiatifkebaikanorg/" target="_blank" class="social-btn"><i
                        class="bi bi-instagram"></i></a>
                <a href="https://x.com/inisiatifbaik" target="_blank" class="social-btn"><i
                        class="bi bi-twitter-x"></i></a>
                <a href="https://www.tiktok.com/@inisiatifkebaikanorg" target="_blank" class="social-btn"><i
                        class="bi bi-tiktok"></i></a>
                <a href="https://www.linkedin.com/in/inisiatif-kebaikan-579b332b2" target="_blank" class="social-btn"><i
                        class="bi bi-linkedin"></i></a>
            </div>

            <!-- Links -->
            <div class="row mb-4">
                <div class="col-6">
                    <h6 class="footer-heading">Tentang</h6>
                    <ul class="footer-links">
                        <li><a href="{{ route('page.show', 'tentang-kami') }}" wire:navigate>Tentang Kami</a></li>
                        <li><a href="{{ route('page.show', 'syarat-ketentuan') }}" wire:navigate>Syarat &
                                Ketentuan</a></li>
                        <li><a href="{{ route('page.show', 'kebijakan-privasi') }}" wire:navigate>Kebijakan
                                Privasi</a></li>
                    </ul>
                </div>
                <div class="col-6">
                    <h6 class="footer-heading">Dukungan</h6>
                    <ul class="footer-links">
                        <li><a href="{{ route('page.show', 'pusat-bantuan') }}" wire:navigate>Pusat Bantuan</a></li>
                        <li><a href="{{ route('fundraiser.daftar') }}" wire:navigate>Daftar Mitra</a></li>
                        <li><a href="{{ route('page.show', 'hubungi-kami') }}" wire:navigate>Hubungi Kami</a></li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="copyright text-center">
                <p>{!! \App\Models\Setting::get(
                    'footer_text',
                    '&copy; ' .
                        date('Y') .
                        ' ' .
                        \App\Models\Setting::get('website_name', 'Inisiatif Kebaikan') .
                        '. All rights reserved.',
                ) !!}</p>
            </div>
        </div>
    </footer>
    <x-app.bottom-nav />

    <style>
        .prose h1,
        .prose h2,
        .prose h3,
        .prose h4 {
            font-weight: 800;
            color: #1a202c;
            margin-bottom: 1rem;
        }

        .prose h3 {
            font-size: 1.25rem;
        }

        .prose p {
            margin-bottom: 1rem;
            color: #4a5568;
            line-height: 1.7;
        }

        .prose ul,
        .prose ol {
            margin-bottom: 1rem;
            padding-left: 1.5rem;
            color: #4a5568;
        }

        .prose li {
            margin-bottom: 0.5rem;
        }

        .prose img {
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            margin: 1rem 0;
        }

        .min-vh-50 {
            min-height: 50vh;
        }
    </style>
</div>
