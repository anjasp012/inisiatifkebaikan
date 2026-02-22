<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Fundraiser;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.app')] class extends Component {
    public Campaign $campaign;
    public $donations;

    public function mount(Campaign $campaign)
    {
        $this->campaign = $campaign;

        // Security check
        $fundraiser = Fundraiser::where('user_id', Auth::id())->first();
        if (!$fundraiser || $fundraiser->id !== $this->campaign->fundraiser_id) {
            abort(403);
        }

        $this->loadDonations();
    }

    public function loadDonations()
    {
        $this->donations = $this->campaign->donations()->where('status', 'success')->latest()->get();
    }
}; ?>

<div>
    <x-app.navbar-secondary title="Donatur Program" :route="route('fundraiser.galang-dana.kelola', $campaign->slug)" />

    <section class="py-4">
        <div class="container-fluid">
            {{-- Summary --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4 bg-primary text-white">
                <div class="card-body p-3 text-center">
                    <h6 class="extra-small opacity-75 mb-1">Total Donasi Terkumpul</h6>
                    <h5 class="fw-bold mb-0">Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</h5>
                    <p class="extra-small opacity-75 mt-1">{{ count($donations) }} Donatur</p>
                </div>
            </div>

            <h6 class="fw-bold small mb-3 text-uppercase ls-sm">Daftar Donatur</h6>

            <div class="space-y-3">
                @forelse ($donations as $donation)
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-3 d-flex align-items-center gap-3">
                            <div
                                class="avatar avatar-md rounded-circle bg-light d-flex align-items-center justify-content-center text-primary fw-bold">
                                {{ Str::substr($donation->donor_name, 0, 1) }}
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold small mb-0">
                                    {{ $donation->is_anonymous ? 'Sahabat Inisiatif' : $donation->donor_name }}</h6>
                                <p class="text-muted extra-small mb-0">{{ $donation->created_at->format('d M Y, H:i') }}
                                </p>
                                @if ($donation->message)
                                    <p class="mt-1 extra-small fst-italic text-muted bg-light p-2 rounded-2 mb-0">
                                        "{{ $donation->message }}"</p>
                                @endif
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-success small">Rp
                                    {{ number_format($donation->amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <img src="https://img.freepik.com/free-vector/empty-concept-illustration_114360-1188.jpg"
                            width="120" class="mb-3 opacity-50" style="filter: grayscale(100%)">
                        <p class="text-muted small mb-0">Belum ada donatur.</p>
                    </div>
                @endforelse
            </div>

            {{-- padding bottom to avoid protected by bottom bar --}}
            <div class="mb-5 pb-5"></div>
        </div>
    </section>

    <x-app.bottom-nav />
</div>
