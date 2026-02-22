<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Campaign;
use App\Models\Withdrawal;
use App\Models\Fundraiser;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

new #[Layout('layouts.app')] class extends Component {
    use WithFileUploads;

    public Campaign $campaign;
    public $withdrawals = [];
    public $amount;
    public $notes;
    public $proof;

    public function mount(Campaign $campaign)
    {
        $this->campaign = $campaign;

        // Security check
        $fundraiser = Fundraiser::where('user_id', Auth::id())->first();
        if (!$fundraiser || $fundraiser->id !== $this->campaign->fundraiser_id) {
            abort(403);
        }

        $this->loadWithdrawals();
    }

    public function loadWithdrawals()
    {
        $this->withdrawals = Withdrawal::where('campaign_id', $this->campaign->id)->latest()->get();
    }

    #[Livewire\Attributes\Computed]
    public function maxAmount()
    {
        $alreadyWithdrawn = Withdrawal::where('campaign_id', $this->campaign->id)->where('status', '!=', 'rejected')->sum('amount');

        return max(0, $this->campaign->collected_amount - $alreadyWithdrawn);
    }

    public function requestWithdrawal()
    {
        $this->validate([
            'amount' => 'required|numeric|min:10000|max:' . $this->maxAmount,
            'notes' => 'required|string|max:255',
            'proof' => 'required|image|max:2048',
        ]);

        $withdrawal = new Withdrawal();
        $withdrawal->campaign_id = $this->campaign->id;
        $withdrawal->fundraiser_id = $this->campaign->fundraiser_id;
        $withdrawal->amount = $this->amount;
        $withdrawal->net_amount = 0; // Admin will calculate fees later
        $withdrawal->status = 'pending';
        $withdrawal->notes = $this->notes;

        if ($this->proof) {
            $withdrawal->file_path = $this->proof->store('withdrawals', 'public');
        }

        $withdrawal->save();

        session()->flash('success', 'Permintaan pencairan dana berhasil dikirim! ✅');
        $this->reset(['amount', 'notes', 'proof']);
        $this->loadWithdrawals();
    }
}; ?>

<div>
    <x-app.navbar-secondary title="Cairkan Dana" :route="route('fundraiser.galang-dana.kelola', $campaign->slug)" />

    <section class="py-4">
        <div class="container-fluid">
            {{-- Balance Card --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4 bg-primary text-white">
                <div class="card-body p-3 text-center">
                    <h6 class="extra-small opacity-75 mb-1">Saldo Dapat Dicairkan</h6>
                    <h5 class="fw-bold mb-0">Rp {{ number_format($this->maxAmount, 0, ',', '.') }}</h5>
                    <small class="d-block mt-2 opacity-50 extra-small">*Biaya admin & platform akan dipotong saat
                        pencairan disetujui.</small>
                </div>
            </div>

            {{-- Form Request --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-3">
                    <h6 class="fw-bold small mb-3">Ajukan Pencairan</h6>

                    @if (session('success'))
                        <div class="alert alert-success py-2 px-3 small rounded-3 mb-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form wire:submit="requestWithdrawal">
                        <div class="mb-3">
                            <label class="form-label extra-small text-muted mb-1">Nominal Pencairan</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-end-0">Rp</span>
                                <input type="number" wire:model="amount"
                                    class="form-control border-start-0 ps-0 fw-bold" placeholder="Min. 10.000">
                            </div>
                            <div class="extra-small text-muted mt-1 px-1 d-flex justify-content-between">
                                <span>Min: Rp 10rb</span>
                                <span>Maks: Rp {{ number_format($this->maxAmount, 0, ',', '.') }}</span>
                            </div>
                            @error('amount')
                                <span class="text-danger extra-small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label extra-small text-muted mb-1">Keterangan Penggunaan</label>
                            <textarea wire:model="notes" class="form-control form-control-sm rounded-3" rows="3"
                                placeholder="Dana akan digunakan untuk..."></textarea>
                            @error('notes')
                                <span class="text-danger extra-small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label extra-small text-muted mb-1">Dokumen Pendukung
                                (RAB/Lampiran)</label>
                            <input type="file" wire:model="proof" class="form-control form-control-sm rounded-3">
                            @error('proof')
                                <span class="text-danger extra-small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-sm rounded-pill fw-bold">
                                <span wire:loading.remove wire:target="requestWithdrawal">Ajukan Pencairan</span>
                                <span wire:loading wire:target="requestWithdrawal">Memproses...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- History --}}
            <h6 class="fw-bold small mb-3 text-uppercase ls-sm">Riwayat Pencairan</h6>

            <div class="space-y-3">
                @forelse ($withdrawals as $withdrawal)
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="fw-bold small mb-0">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                                </h6>
                                <p class="text-muted extra-small mb-0">{{ $withdrawal->created_at->format('d M Y') }}
                                </p>
                                <small class="text-muted extra-small">{{ Str::limit($withdrawal->notes, 30) }}</small>
                            </div>
                            <div>
                                @if ($withdrawal->status == 'pending')
                                    <span class="badge bg-warning text-dark rounded-pill extra-small">Menunggu</span>
                                @elseif ($withdrawal->status == 'success')
                                    <span class="badge bg-success rounded-pill extra-small">Berhasil</span>
                                @else
                                    <span class="badge bg-danger rounded-pill extra-small">Ditolak</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <img src="https://img.freepik.com/free-vector/empty-concept-illustration_114360-1188.jpg"
                            width="120" class="mb-3 opacity-50" style="filter: grayscale(100%)">
                        <p class="text-muted small mb-0">Belum ada riwayat pencairan.</p>
                    </div>
                @endforelse
            </div>

            {{-- padding bottom to avoid protected by bottom bar --}}
            <div class="mb-5 pb-5"></div>
        </div>
    </section>

    <x-app.bottom-nav />
</div>
