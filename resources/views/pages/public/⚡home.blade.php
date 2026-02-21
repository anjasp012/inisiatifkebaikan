<?php

use App\Models\Campaign;
use App\Models\CampaignCategory;
use App\Models\Distribution;
use App\Models\Article;
use App\Models\Donation;
use Livewire\Component;
use Livewire\Attributes\Computed;

use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Facades\View;

new class extends Component {
    public ?int $recommendedCategory = null;

    public function mount()
    {
        $seoData = new SEOData(title: \App\Models\Setting::get('website_name', 'Inisiatif Kebaikan'), description: \App\Models\Setting::get('website_description', 'Platform donasi dan penghimpunan dana sosial terpercaya.'), image: asset('assets/images/og-image.jpg'));

        View::share('seoData', $seoData);
    }

    #[Computed]
    public function priorityBannerCampaign()
    {
        return Campaign::query()->where('is_priority', true)->inRandomOrder()->first();
    }

    #[Computed]
    public function campaignSliders()
    {
        return Campaign::query()->where('is_slider', true)->orderBy('created_at', 'desc')->limit(3)->get();
    }
    #[Computed]
    public function categories()
    {
        return CampaignCategory::withCount('campaigns')->orderBy('campaigns_count', 'desc')->limit(7)->get();
    }

    #[Computed]
    public function recommendedCategories()
    {
        return CampaignCategory::whereHas('campaigns')->withMax('campaigns', 'created_at')->orderByDesc('campaigns_max_created_at')->limit(4)->get();
    }
    #[Computed]
    public function campaignUrgent()
    {
        return Campaign::query()->where('is_emergency', true)->orderBy('created_at', 'desc')->limit(3)->get();
    }
    #[Computed]
    public function campaignInisiatif()
    {
        return Campaign::query()->where('is_inisiatif', true)->orderBy('created_at', 'desc')->limit(3)->get();
    }
    #[Computed]
    public function campaignChoice()
    {
        return Campaign::query()->where('status', 'active')->where('is_optimized', true)->orderBy('created_at', 'desc')->limit(6)->get();
    }

    #[Computed]
    public function distributions()
    {
        return Distribution::orderBy('created_at', 'desc')->limit(2)->get();
    }
    #[Computed]
    public function articles()
    {
        return Article::orderBy('created_at', 'desc')->where('is_published', true)->limit(2)->get();
    }
    #[Computed]
    public function prayers()
    {
        return Donation::whereNotNull('message')->where('status', 'success')->latest()->limit(6)->get();
    }
    #[Computed]
    public function recommendedCampaigns()
    {
        $query = Campaign::query();

        if ($this->recommendedCategory) {
            $query->where('category_id', $this->recommendedCategory);
        }

        return $query->latest()->limit(6)->get();
    }

    #[Computed]
    public function recommendedMoreLink()
    {
        if (!$this->recommendedCategory) {
            return route('campaign.index');
        }

        $category = CampaignCategory::find($this->recommendedCategory);
        return route('campaign.index', ['category' => $category?->slug]);
    }

    public function aminPrayer(int $prayerId): void
    {
        Donation::where('id', $prayerId)->increment('amin_count');
        unset($this->prayers);
    }

    public function filterRecommended(?int $categoryId): void
    {
        $this->recommendedCategory = $categoryId;
        unset($this->recommendedCampaigns);
    }
};
?>

<div>
    <x-app.navbar-main />
    <section class="hero-section">
        <div class="container-fluid">
            <div class="owl-carousel owl-theme owl-hero" wire:ignore>
                @foreach ($this->campaignSliders() as $campaign)
                    <div class="item">
                        <div class="hero-slide">
                            <div class="hero-image">
                                <img src="{{ $campaign->thumbnail_url }}" alt="">
                                <div class="hero-gradient"></div>
                            </div>
                            <div class="hero-content">
                                <div class="hero-tag">
                                    {{ $campaign->category->name }}
                                </div>
                                <h1>{{ $campaign->title }}</h1>
                                <p>{{ Str::limit(strip_tags($campaign->description), 80) }}</p>
                                <div class="d-flex gap-4 align-items-center">
                                    <a href="{{ route('campaign.show', $campaign->slug) }}" wire:navigate
                                        class="btn btn-sm btn-primary text-nowrap py-2 px-4 rounded-pill fw-semibold">Donasi
                                        Sekarang <i class="bi bi-arrow-right ms-2"></i></a>
                                    <div class="hero-info">Target: <br> Rp
                                        {{ number_format($campaign->target_amount, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="category-section">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-4" bis_skin_checked="1">
                <h2 class="section-title">Mau berbuat baik apa hari ini?</h2>
            </div>
            <div class="row g-2">
                @foreach ($this->categories() as $category)
                    <div class="col-3">
                        <a wire:navigate href="{{ route('campaign.index', ['category' => $category->slug]) }}"
                            class="category-item">
                            <div class="category-icon">
                                <i class="{{ $category->icon }}"></i>
                            </div>
                            <span>{{ $category->name }}</span>
                        </a>
                    </div>
                @endforeach
                <div class="col-3">
                    <a wire:navigate href="{{ route('category.index') }}" class="category-item">
                        <div class="category-icon">
                            <i class="bi bi-three-dots"></i>
                        </div>
                        <span>Lainnya</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="urgent-campaigns-section">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-4" bis_skin_checked="1">
                <h2 class="section-title">Kebutuhan Darurat &amp; Mendesak</h2>
                <a class="link-more" href="{{ route('campaign.index', ['filter' => 'darurat']) }}" wire:navigate>
                    Lihat Semua
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
            <div class="owl-carousel owl-theme owl-inisiatif" wire:ignore>
                @foreach ($this->campaignUrgent() as $campaign)
                    <div class="item">
                        <x-app.campaign-card :campaign="$campaign" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="priority-banner-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <x-app.priority-card :campaign="$this->priorityBannerCampaign()" />
                </div>
            </div>
        </div>
    </section>

    <section class="inisiatif-campaigns-section">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-4" bis_skin_checked="1">
                <h2 class="section-title">Program Inisiatif Pilihan</h2>
                <a class="link-more" href="{{ route('campaign.index', ['filter' => 'inisiatif']) }}" wire:navigate>
                    Lihat Semua
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
            <div class="owl-carousel owl-theme owl-inisiatif" wire:ignore>
                @foreach ($this->campaignInisiatif() as $campaign)
                    <div class="item">
                        <x-app.campaign-card :campaign="$campaign" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <section class="choice-campaigns-section">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-4" bis_skin_checked="1">
                <h2 class="section-title">Program Kebaikan</h2>
                <a class="link-more" href="{{ route('campaign.index', ['filter' => 'kebaikan']) }}" wire:navigate>
                    Lihat Semua
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
            <div class="row g-3">
                @foreach ($this->campaignChoice() as $campaign)
                    <div class="col-12">
                        <x-app.campaign-card :campaign="$campaign" />
                    </div>
                @endforeach
            </div>

        </div>
    </section>
    <section class="distribution-section">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-4" bis_skin_checked="1">
                <h2 class="section-title">Laporan Penyaluran</h2>
                <a class="link-more" href="{{ route('distribution.index') }}" wire:navigate>
                    Lihat Semua
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
            <div class="row g-3">
                @foreach ($this->distributions() as $distribution)
                    <div class="col-6">
                        <x-app.distribution-card :distribution="$distribution" />
                    </div>
                @endforeach
            </div>

        </div>
    </section>
    <section class="article-section">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-4" bis_skin_checked="1">
                <h2 class="section-title">Artikel Terbaru</h2>
                <a class="link-more" href="{{ route('article.index') }}" wire:navigate>
                    Lihat Semua
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
            <div class="row g-3">
                @foreach ($this->articles() as $article)
                    <div class="col-12">
                        <x-app.article-card :article="$article" />
                    </div>
                @endforeach
            </div>

        </div>
    </section>
    <section class="prayers-section">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-4" bis_skin_checked="1">
                <h2 class="section-title">Harapan & Doa Sahabat Inisiatif</h2>
                <a class="link-more" href="" wire:navigate>
                    Lihat Semua
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
            <div class="owl-carousel owl-theme owl-prayer" wire:ignore>
                @foreach ($this->prayers() as $prayer)
                    <div class="item">
                        <x-app.prayer-card :prayer="$prayer" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <section class="recommended-campaigns-section">
        <div class="container-fluid">
            <div class="mb-3">
                <h2 class="section-title">Rekomendasi Kebaikan Untukmu</h2>
            </div>

            <div class="recommended-tabs mb-3">
                <button wire:click="filterRecommended(null)"
                    class="recommended-tab {{ !$recommendedCategory ? 'active' : '' }}">
                    Semua
                </button>
                @foreach ($this->recommendedCategories() as $cat)
                    <button wire:click="filterRecommended({{ $cat->id }})"
                        class="recommended-tab {{ $recommendedCategory == $cat->id ? 'active' : '' }}">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>

            <div class="row g-3" wire:loading.class="opacity-50">
                @forelse ($this->recommendedCampaigns() as $campaign)
                    <div class="col-12">
                        <x-app.campaign-card :campaign="$campaign" />
                    </div>
                @empty
                    <div class="col-12 text-center py-4">
                        <p class="text-muted small">Belum ada campaign di kategori ini.</p>
                    </div>
                @endforelse
                <div class="col-12">
                    <a href="{{ $this->recommendedMoreLink }}" wire:navigate
                        class="btn btn-sm py-2 btn-outline-primary fw-bold w-100 rounded-pill mb-4">Lihat
                        Lebih
                        Banyak</a>
                </div>
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
                <a href="https://www.linkedin.com/in/inisiatif-kebaikan-579b332b2" target="_blank"
                    class="social-btn"><i class="bi bi-linkedin"></i></a>
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
</div>

@push('scripts')
    <script>
        $('.owl-hero').owlCarousel({
            loop: true,
            margin: 12,
            nav: false,
            dots: false,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            smartSpeed: 600,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                1000: {
                    items: 1
                }
            }
        });
        $('.owl-inisiatif').owlCarousel({
            loop: true,
            margin: 12,
            nav: false,
            dots: false,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplayHoverPause: true,
            smartSpeed: 600,
            responsive: {
                0: {
                    items: 2.1
                },
                600: {
                    items: 2.1
                },
                1000: {
                    items: 2.1
                }
            }
        });
        $('.owl-prayer').owlCarousel({
            loop: true,
            margin: 12,
            nav: false,
            dots: false,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            smartSpeed: 600,
            responsive: {
                0: {
                    items: 1.7
                },
                600: {
                    items: 1.7
                },
                1000: {
                    items: 1.7
                }
            }
        });
        // Drag to scroll for recommended tabs
        const scrollContainer = document.querySelector('.recommended-tabs');
        if (scrollContainer) {
            let isDown = false;
            let startDate;
            let scrollLeft;

            scrollContainer.addEventListener('mousedown', (e) => {
                isDown = true;
                scrollContainer.classList.add('active');
                startDate = e.pageX - scrollContainer.offsetLeft;
                scrollLeft = scrollContainer.scrollLeft;
            });
            scrollContainer.addEventListener('mouseleave', () => {
                isDown = false;
                scrollContainer.classList.remove('active');
            });
            scrollContainer.addEventListener('mouseup', () => {
                isDown = false;
                scrollContainer.classList.remove('active');
            });
            scrollContainer.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - scrollContainer.offsetLeft;
                const walk = (x - startDate) * 2; //scroll-fast
                scrollContainer.scrollLeft = scrollLeft - walk;
            });
        }
    </script>
@endpush
