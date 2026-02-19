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
        $title = $this->category ? 'Program Kebaikan: ' . ucwords(str_replace('-', ' ', $this->category)) : 'Daftar Program Kebaikan - Wahdah Inisiatif';

        $seoData = new SEOData(title: $title, description: 'Temukan dan bantu saudara kita yang membutuhkan melalui berbagai program kebaikan terpercaya.');

        View::share('seoData', $seoData);
    }

    #[Url]
    public $category = '';

    #[Url]
    public $search = '';

    public function updated($property)
    {
        if ($property === 'search' || $property === 'category') {
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
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
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
};
?>

<div class="bg-white min-vh-100">
    <x-app.navbar-main />

    <section class="campaign-index-page">
        <div class="container-fluid">
            <!-- Header & Filter -->
            <div class="row align-items-center mb-4 g-3">
                <div class="col-md-6">
                    <h2 class="fw-bold mb-1">Daftar Program Kebaikan</h2>
                    <p class="text-muted mb-0">Mari berkontribusi untuk mereka yang membutuhkan.</p>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search"
                            class="form-control bg-light border-start-0" placeholder="Cari program kebaikan...">
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div class="d-flex flex-wrap gap-2 mb-4">
                <button wire:click="setCategory('')"
                    class="btn btn-sm rounded-pill {{ $category == '' ? 'btn-primary' : 'btn-outline-secondary border-0 bg-light' }}">
                    Semua
                </button>
                @foreach ($this->categories() as $cat)
                    <button wire:click="setCategory('{{ $cat->slug }}')"
                        class="btn btn-sm rounded-pill {{ $category == $cat->slug ? 'btn-primary' : 'btn-outline-secondary border-0 bg-light' }}">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>

            <!-- Grid -->
            <div class="row g-3" wire:loading.class="opacity-50">
                @forelse ($this->campaigns() as $campaign)
                    <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                        <x-app.campaign-card :campaign="$campaign" />
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-3 text-muted opacity-50">
                            <i class="bi bi-search display-1"></i>
                        </div>
                        <h5 class="fw-bold text-muted">Tidak ditemukan</h5>
                        <p class="text-muted small">Coba kata kunci lain atau kategori berbeda.</p>
                        <button wire:click="$set('search', ''); $set('category', '')"
                            class="btn btn-outline-primary btn-sm rounded-pill mt-2">
                            Reset Filter
                        </button>
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
