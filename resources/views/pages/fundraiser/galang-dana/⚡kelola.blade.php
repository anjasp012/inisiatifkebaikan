<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Campaign;
use App\Models\Fundraiser;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.app')] class extends Component {
    public $campaign;
    public $fundraiser;

    public function mount(Campaign $campaign)
    {
        $this->campaign = $campaign;
        $this->fundraiser = Fundraiser::where('user_id', Auth::id())->first();

        // Security check
        if (!$this->fundraiser || ($this->fundraiser->status !== 'approved' && $this->campaign->fundraiser_id !== $this->fundraiser->id)) {
            session()->flash('error', 'Akses ditolak');
            $this->redirectRoute('fundraiser.dashboard', navigate: true);
            return;
        }

        if ($this->fundraiser->status !== 'approved') {
            $this->redirectRoute('fundraiser.dashboard', navigate: true);
            return;
        }
    }

    #[Livewire\Attributes\Computed]
    public function recentDonations()
    {
        return $this->campaign->donations()->where('status', 'success')->latest()->limit(20)->get();
    }
}; ?>

<div>
    <x-app.navbar-secondary title="Kelola Galang Dana" :route="route('fundraiser.galang-dana.index')" />

    <section class="fundraiser-kelola-page py-4">
        <div class="container-fluid">
            {{-- Campaign Snapshot Card --}}
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                <div class="card-body p-3">
                    <div class="d-flex gap-3 align-items-center">
                        <img src="{{ $campaign->thumbnail_url }}"
                            class="rounded-3 object-fit-cover bg-light flex-shrink-0" width="80" height="80"
                            alt="{{ $campaign->title }}">
                        <div class="flex-grow-1 overflow-hidden">
                            <h6 class="fw-bold mb-1 text-truncate-2 small line-height-base">{{ $campaign->title }}</h6>
                            <div class="d-flex align-items-center gap-2">
                                <span
                                    class="badge {{ $campaign->status == 'active' ? 'bg-success' : 'bg-secondary' }} bg-opacity-10 {{ $campaign->status == 'active' ? 'text-success' : 'text-secondary' }} rounded-pill extra-small px-2 border-0 shadow-none">
                                    {{ ucfirst($campaign->status) }}
                                </span>
                                <small class="text-muted extra-small">ID: #{{ $campaign->id }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 bg-light rounded-3 p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted extra-small">Dana Terkumpul</small>
                            <small
                                class="text-primary fw-bold extra-small">{{ number_format($campaign->collected_amount, 0, ',', '.') }}
                                <span class="text-muted fw-normal">dari
                                    {{ number_format($campaign->target_amount, 0, ',', '.') }}</span></small>
                        </div>
                        <div class="progress rounded-pill mb-0 bg-white border" style="height: 8px;">
                            <div class="progress-bar bg-primary rounded-pill transition-all" role="progressbar"
                                style="width: {{ $campaign->target_amount > 0 ? min(100, ($campaign->collected_amount / $campaign->target_amount) * 100) : 0 }}%"
                                aria-valuenow="{{ $campaign->collected_amount }}" aria-valuemin="0"
                                aria-valuemax="{{ $campaign->target_amount }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tips Section (Interactive Steps) --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-3">
                    <h6 class="fw-bold small mb-3">Target: Galang dana Anda sukses!</h6>

                    <div class="space-y-3">
                        <div class="d-flex gap-3 align-items-center">
                            <div class="step-bullet">1</div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold small mb-0">Sebarkan ke WhatsApp</h6>
                                <p class="text-muted extra-small mb-0">Bagikan setidaknya ke 5 grup keluarga.</p>
                            </div>
                            <i class="bi bi-chevron-right text-muted extra-small"></i>
                        </div>
                        <div class="d-flex gap-3 align-items-center">
                            <div class="step-bullet bg-secondary bg-opacity-10 text-secondary">2</div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold small mb-0 text-muted">Ajak Partner Berdonasi</h6>
                                <p class="text-muted extra-small mb-0">Minta bantuan admin yayasan.</p>
                            </div>
                            <i class="bi bi-chevron-right text-muted extra-small"></i>
                        </div>
                        <div class="d-flex gap-3 align-items-center">
                            <div class="step-bullet bg-secondary bg-opacity-10 text-secondary">3</div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold small mb-0 text-muted">Tulis Kabar Terbaru</h6>
                                <p class="text-muted extra-small mb-0">Bagikan update secara berkala.</p>
                            </div>
                            <i class="bi bi-chevron-right text-muted extra-small"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Menu Grid --}}
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-3 small text-uppercase ls-sm">Menu Campaign</h6>
                <div class="row g-3">
                    @php
                        $menus = [
                            [
                                'icon' => 'pencil',
                                'label' => 'Edit Konten',
                                'color' => 'primary',
                                'route' => route('fundraiser.campaign.ubah', $campaign->slug),
                            ],
                            ['icon' => 'shield-check', 'label' => 'Verifikasi', 'color' => 'warning', 'route' => '#'],
                            [
                                'icon' => 'newspaper',
                                'label' => 'Tulis Kabar',
                                'color' => 'info',
                                'route' => route('fundraiser.galang-dana.kabar', $campaign->slug),
                            ],
                            [
                                'icon' => 'wallet2',
                                'label' => 'Cairkan Dana',
                                'color' => 'success',
                                'route' => route('fundraiser.galang-dana.pencairan', $campaign->slug),
                            ],
                            ['icon' => 'clock-history', 'label' => 'Riwayat', 'color' => 'secondary', 'route' => '#'],
                            [
                                'icon' => 'people',
                                'label' => 'Donatur',
                                'color' => 'danger',
                                'route' => route('fundraiser.galang-dana.donatur', $campaign->slug),
                            ],
                        ];
                    @endphp

                    @foreach ($menus as $menu)
                        <div class="col-4">
                            <a href="{{ $menu['route'] }}"
                                class="card border-0 shadow-sm rounded-3 text-center h-100 transition-200 text-decoration-none"
                                {{ str_contains($menu['route'], 'http') ? 'wire:navigate' : '' }}>
                                <div class="card-body p-3 d-flex flex-column align-items-center justify-content-center">
                                    <div class="bg-{{ $menu['color'] }} bg-opacity-10 text-{{ $menu['color'] }} rounded-3 mb-2 d-flex align-items-center justify-content-center shadow-micro"
                                        style="width: 44px; height: 44px;">
                                        <i class="bi bi-{{ $menu['icon'] }} fs-5"></i>
                                    </div>
                                    <span class="extra-small fw-bold text-dark lh-sm">{{ $menu['label'] }}</span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>



            {{-- Recent Donations --}}
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-3 small text-uppercase ls-sm">Donatur Terbaru</h6>
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    @forelse($this->recentDonations as $donation)
                        <div class="p-3 border-bottom d-flex align-items-center gap-3">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width: 40px; height: 40px;">
                                <i class="bi bi-person-heart text-danger"></i>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold extra-small mb-1 text-dark">
                                        {{ $donation->is_anonymous ? 'Sahabat Inisiat' : $donation->donor_name }}</h6>
                                    <span
                                        class="fw-bold text-success small">+{{ number_format($donation->amount, 0, ',', '.') }}</span>
                                </div>
                                <small
                                    class="text-muted extra-small opacity-50">{{ $donation->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center">
                            <i class="bi bi-emoji-frown text-muted fs-2 mb-2 opacity-25"></i>
                            <p class="text-muted small mb-0">Belum ada donatur.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Share Card --}}
            <div class="card border-0 shadow-soft rounded-3 overflow-hidden bg-primary text-white">
                <div class="card-body p-4 text-center">
                    <h6 class="fw-bold mb-2">Sebarkan galang dana ini!</h6>
                    <p class="small opacity-75 mb-4">Makin banyak dibagikan, peluang donasi terkumpul makin besar.</p>
                    <div class="d-grid gap-2">
                        <a href="https://wa.me/?text={{ urlencode('Bantu saya: ' . route('campaign.show', $campaign->slug)) }}"
                            target="_blank"
                            class="btn btn-white bg-white text-primary rounded-pill py-3 fw-bold shadow-sm border-0">
                            <i class="bi bi-whatsapp me-2"></i> Bagikan ke WhatsApp
                        </a>
                        <button
                            onclick="navigator.clipboard.writeText('{{ route('campaign.show', $campaign->slug) }}')"
                            class="btn btn-primary border border-white border-opacity-25 rounded-pill py-3 fw-bold">
                            <i class="bi bi-link-45deg me-1"></i> Salin Link Campaign
                        </button>
                    </div>
                </div>
            </div>

            {{-- padding bottom to avoid protected by bottom bar --}}
            <div class="mb-5 pb-5"></div>
        </div>
    </section>

    <x-app.bottom-nav />
</div>

<style>
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-height-base {
        line-height: 1.4;
    }

    .btn-white:hover {
        background-color: #f8f9fa !important;
    }
</style>
