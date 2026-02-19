<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\User;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Facades\View;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public $search = '';

    public function mount()
    {
        $seoData = new SEOData(title: 'Mitra Penggalang Dana | Inisiatif Kebaikan', description: 'Lihat daftar mitra fundraiser terpercaya kami.');
        View::share('seoData', $seoData);
    }

    #[Computed]
    public function fundraisers()
    {
        return User::where('role', 'fundraiser')
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(12);
    }
};
?>
<div>
    <x-app.navbar-secondary title="Daftar Mitra" />

    <section class="fundraiser-list-section">
        <div class="container-fluid">
            <!-- Search -->
            <div class="mb-4 position-relative">
                <input wire:model.live.debounce.500ms="search" type="search"
                    class="form-control py-3 ps-5 border-0 shadow-sm rounded-pill"
                    placeholder="Cari nama mitra atau yayasan...">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            </div>

            @if ($this->fundraisers->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-person-x display-1 text-muted opacity-25 mb-3 d-block"></i>
                    <h6 class="fw-bold text-muted">Belum ada mitra ditemukan.</h6>
                </div>
            @else
                <div class="row g-3">
                    @foreach ($this->fundraisers as $fundraiser)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden text-center p-3">
                                <div class="mb-3 position-relative d-inline-block">
                                    <img src="{{ $fundraiser->avatar_url }}"
                                        class="rounded-circle object-fit-cover shadow-sm" alt="{{ $fundraiser->name }}"
                                        style="width: 80px; height: 80px;">
                                    @if ($fundraiser->isVerified())
                                        <span
                                            class="position-absolute bottom-0 end-0 bg-primary text-white p-1 rounded-circle border border-2 border-white d-flex align-items-center justify-content-center"
                                            style="width: 24px; height: 24px;">
                                            <i class="bi bi-check extra-small fw-bold"></i>
                                        </span>
                                    @endif
                                </div>
                                <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $fundraiser->name }}</h6>
                                <small class="text-muted d-block mb-2 text-truncate">{{ $fundraiser->email }}</small>

                                <div class="d-grid mt-auto">
                                    <button
                                        class="btn btn-outline-primary btn-sm rounded-pill fw-bold extra-small py-1 disabled">
                                        {{ $fundraiser->created_at->format('M Y') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $this->fundraisers->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </section>

    <x-app.bottom-nav />
</div>
