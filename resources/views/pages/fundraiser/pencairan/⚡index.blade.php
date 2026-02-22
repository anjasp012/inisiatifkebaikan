<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Withdrawal;
use App\Models\Fundraiser;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.app')] class extends Component {
    public $withdrawals = [];
    public $fundraiser;

    public function mount()
    {
        $this->fundraiser = Fundraiser::where('user_id', Auth::id())->first();

        if (!$this->fundraiser) {
            abort(403);
        }

        $this->loadWithdrawals();
    }

    public function loadWithdrawals()
    {
        $this->withdrawals = Withdrawal::where('fundraiser_id', $this->fundraiser->id)->with('campaign')->latest()->get();
    }
}; ?>

<div>
    <x-app.navbar-secondary title="Riwayat Pencairan" :route="route('fundraiser.dashboard')" />

    <section class="py-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold small mb-0 text-uppercase ls-sm">Riwayat Pencairan</h6>
                <a href="{{ route('fundraiser.pencairan.buat') }}" wire:navigate
                    class="btn btn-primary btn-sm rounded-pill px-3 fw-bold extra-small shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Ajukan Pencairan
                </a>
            </div>

            <div class="space-y-3">
                @forelse ($withdrawals as $withdrawal)
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h6 class="fw-bold small mb-0 text-primary">Rp
                                    {{ number_format($withdrawal->amount, 0, ',', '.') }}</h6>
                                <div>
                                    @if ($withdrawal->status == 'pending')
                                        <span
                                            class="badge bg-warning text-dark rounded-pill extra-small px-2">Menunggu</span>
                                    @elseif ($withdrawal->status == 'success')
                                        <span class="badge bg-success rounded-pill extra-small px-2">Berhasil</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill extra-small px-2">Ditolak</span>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex flex-column gap-1">
                                <small class="text-dark fw-bold extra-small line-clamp-1">
                                    <i class="bi bi-megaphone me-1 text-muted"></i> {{ $withdrawal->campaign->title }}
                                </small>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span
                                        class="text-muted extra-small">{{ $withdrawal->created_at->format('d M Y, H:i') }}</span>
                                    @if ($withdrawal->net_amount > 0)
                                        <small class="text-success extra-small fw-bold">Net: Rp
                                            {{ number_format($withdrawal->net_amount, 0, ',', '.') }}</small>
                                    @endif
                                </div>
                                @if ($withdrawal->notes)
                                    <div class="mt-2 p-2 bg-light rounded-2 extra-small text-muted italic">
                                        "{{ Str::limit($withdrawal->notes, 100) }}"
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div class="bg-white rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center mb-4"
                            style="width: 80px; height: 80px;">
                            <i class="bi bi-wallet2 fs-2 text-muted opacity-25"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Belum Ada Pencairan</h6>
                        <p class="text-muted small">Riwayat pengajuan pencairan dana Anda akan muncul di sini.</p>
                        <a href="{{ route('fundraiser.galang-dana.index') }}" wire:navigate
                            class="btn btn-primary btn-sm rounded-pill mt-3 px-4 fw-bold">
                            Ajukan Pencairan Sekarang
                        </a>
                    </div>
                @endforelse
            </div>

            <div class="mb-5 pb-5"></div>
        </div>
    </section>

    <x-app.bottom-nav />
</div>

<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
