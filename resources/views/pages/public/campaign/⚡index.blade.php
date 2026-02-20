<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\Campaign;
use App\Models\CampaignCategory;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Facades\View;

new class extends Component {
    use WithPagination;

    public function mount()
    {
        $title = 'Daftar Program Kebaikan - Wahdah Inisiatif';
        if ($this->category) {
            $title = 'Kategori: ' . ucwords(str_replace('-', ' ', $this->category));
        } elseif ($this->filter === 'inisiatif') {
            $title = 'Program Inisiatif Pilihan - Wahdah Inisiatif';
        } elseif ($this->filter === 'kebaikan') {
            $title = 'Program Kebaikan Murni - Wahdah Inisiatif';
        } elseif ($this->filter === 'darurat') {
            $title = 'Kebutuhan Darurat & Mendesak - Wahdah Inisiatif';
        }

        $seoData = new SEOData(title: $title, description: 'Temukan dan bantu saudara kita yang membutuhkan melalui berbagai program kebaikan terpercaya.');

        View::share('seoData', $seoData);
    }

    #[Url]
    public $category = '';

    #[Url]
    public $filter = '';

    public function updated($property)
    {
        if (in_array($property, ['category', 'filter'])) {
            $this->resetPage();
        }
    }

    #[Computed]
    public function campaigns()
    {
        return Campaign::query()
            ->where('status', 'active')
            ->when($this->category, function ($query) {
                $query->whereHas('category', function ($q) {
                    $q->where('slug', $this->category);
                });
            })
            ->when($this->filter === 'inisiatif', function ($query) {
                $query->where('is_inisiatif', true);
            })
            ->when($this->filter === 'kebaikan', function ($query) {
                $query->where('is_optimized', true);
            })
            ->when($this->filter === 'darurat', function ($query) {
                $query->where('is_emergency', true);
            })
            ->latest()
            ->paginate(12);
    }

    #[Computed]
    public function categories()
    {
        return CampaignCategory::orderBy('name')->get();
    }

    public function setCategory($slug)
    {
        $this->category = $slug === $this->category ? '' : $slug;
    }

    #[Computed]
    public function pageTitle()
    {
        if ($this->filter === 'inisiatif') {
            return 'Program Inisiatif Pilihan';
        }
        if ($this->filter === 'kebaikan') {
            return 'Program Kebaikan Murni';
        }
        if ($this->filter === 'darurat') {
            return 'Kebutuhan Darurat & Mendesak';
        }
        if ($this->category) {
            return ucwords(str_replace('-', ' ', $this->category));
        }
        return 'Daftar Program Kebaikan';
    }
};
?>

<div class="bg-white min-vh-100">
    <x-app.navbar-secondary :title="$this->pageTitle" />

    <section class="campaign-index-page category-section pb-5 mb-5">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-4" bis_skin_checked="1">
                <h2 class="section-title">{{ $this->pageTitle }}</h2>
            </div>
            <!-- Grid -->
            <div class="row g-3" wire:loading.class="opacity-50">
                @forelse ($this->campaigns() as $campaign)
                    <div class="col-12">
                        <x-app.campaign-card :campaign="$campaign" />
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-3 text-muted opacity-50">
                            <i class="bi bi-box-seam display-1"></i>
                        </div>
                        <h5 class="fw-bold text-muted">Belum ada program</h5>
                        <p class="text-muted small">Saat ini belum ada program untuk kategori ini.</p>
                        <a href="{{ route('campaign.index') }}" wire:navigate
                            class="btn btn-outline-primary btn-sm rounded-pill mt-2">
                            Lihat Semua Program
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-center">
                {{ $this->campaigns()->links() }}
            </div>
        </div>
    </section>

    <x-app.bottom-nav />
</div>
