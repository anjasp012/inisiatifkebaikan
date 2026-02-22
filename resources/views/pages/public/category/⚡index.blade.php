<?php

use App\Models\CampaignCategory;
use Livewire\Component;
use Livewire\Attributes\Computed;

use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Facades\View;

new class extends Component {
    public function mount()
    {
        $seoData = new SEOData(title: 'Kategori Program Inisiatif Kebaikan', description: 'Temukan berbagai kategori program kebaikan yang sesuai dengan kepedulian Anda.');

        View::share('seoData', $seoData);
    }

    #[Computed]
    public function categories()
    {
        return CampaignCategory::withCount('campaigns')->orderBy('campaigns_count', 'desc')->get();
    }
};
?>

<div>
    <x-app.navbar-secondary title="Kategori Program" />

    <section class="category-section">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-4" bis_skin_checked="1">
                <h2 class="section-title">Mau berbuat baik apa hari ini?</h2>
            </div>
            <div class="row g-2">
                @foreach ($this->categories() as $category)
                    <div class="col-3">
                        <a href="{{ route('campaign.index', ['category' => $category->slug]) }}" class="category-item">
                            <div class="category-icon">
                                @if ($category->is_bootstrap_icon)
                                    <i class="{{ $category->icon }}"></i>
                                @else
                                    <img src="{{ $category->icon_url }}" alt="{{ $category->name }}">
                                @endif
                            </div>
                            <span>{{ $category->name }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <x-app.bottom-nav />
</div>
