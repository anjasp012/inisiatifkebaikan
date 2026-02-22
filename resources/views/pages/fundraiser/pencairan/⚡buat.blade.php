<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Campaign;
use App\Models\Withdrawal;
use App\Models\Fundraiser;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts.app')] class extends Component {
    public $campaign_id;
    public $fundraiser;

    public function mount()
    {
        $this->fundraiser = Fundraiser::where('user_id', Auth::id())->first();

        if (!$this->fundraiser) {
            abort(403);
        }

        $this->campaign_id = request('campaign_id');
    }

    #[Livewire\Attributes\Computed]
    public function campaigns()
    {
        return Campaign::where('fundraiser_id', $this->fundraiser->id)->where('status', 'active')->get();
    }

    #[Livewire\Attributes\Computed]
    public function selectedCampaign()
    {
        if (!$this->campaign_id) {
            return null;
        }
        return Campaign::find($this->campaign_id);
    }

    #[Livewire\Attributes\Computed]
    public function maxAmount()
    {
        if (!$this->selectedCampaign) {
            return 0;
        }

        $alreadyWithdrawn = Withdrawal::where('campaign_id', $this->selectedCampaign->id)->where('status', '!=', 'rejected')->sum('amount');

        return max(0, $this->selectedCampaign->collected_amount - $alreadyWithdrawn);
    }

    public function requestWithdrawal()
    {
        $this->validate([
            'campaign_id' => 'required|exists:campaigns,id',
        ]);

        if ($this->maxAmount <= 0) {
            $this->addError('campaign_id', 'Saldo program ini kosong atau sudah dicairkan.');
            return;
        }

        $withdrawal = new Withdrawal();
        $withdrawal->campaign_id = $this->campaign_id;
        $withdrawal->fundraiser_id = $this->fundraiser->id;
        $withdrawal->amount = $this->maxAmount; // Cairkan semua saldo tersedia
        $withdrawal->net_amount = 0;
        $withdrawal->status = 'pending';
        $withdrawal->notes = 'Pengajuan pencairan saldo program: ' . $this->selectedCampaign->title;

        $withdrawal->save();

        session()->flash('success', 'Permintaan pencairan dana berhasil dikirim! ✅');
        return $this->redirectRoute('fundraiser.pencairan.index', navigate: true);
    }
}; ?>

<div>
    <x-app.navbar-secondary title="Ajukan Pencairan" :route="route('fundraiser.pencairan.index')" />

    <section class="py-4">
        <div class="container-fluid">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                        style="width: 60px; height: 60px;">
                        <i class="bi bi-wallet2 text-primary fs-3"></i>
                    </div>
                    <h6 class="fw-bold mb-1">Form Pengajuan Pencairan</h6>
                    <p class="text-muted extra-small mb-4">Pilih program untuk mencairkan saldo yang tersedia.</p>

                    <form wire:submit="requestWithdrawal">
                        <div class="mb-4 text-start">
                            <label class="form-label extra-small fw-bold text-muted ps-1">Pilih Program /
                                Campaign</label>
                            <select wire:model.live="campaign_id"
                                class="form-select border-0 bg-light rounded-3 p-3 small shadow-none">
                                <option value="">-- Pilih Program --</option>
                                @foreach ($this->campaigns as $campaign)
                                    <option value="{{ $campaign->id }}">{{ $campaign->title }}</option>
                                @endforeach
                            </select>
                            @error('campaign_id')
                                <span class="text-danger extra-small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        @if ($this->selectedCampaign)
                            <div class="bg-light rounded-4 p-4 mb-4 border border-light shadow-micro">
                                <div class="mb-3">
                                    <span class="extra-small text-muted fw-bold text-uppercase ls-sm d-block mb-1">Saldo
                                        Tersedia</span>
                                    <h3 class="fw-bold text-dark mb-0">Rp
                                        {{ number_format($this->maxAmount, 0, ',', '.') }}</h3>
                                </div>
                                <div class="pt-3 border-top border-2 border-white">
                                    <small class="text-muted extra-small d-block">Seluruh saldo yang tersedia akan
                                        diajukan untuk dicairkan.</small>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit"
                                    class="btn btn-primary btn-lg rounded-pill fw-bold shadow-soft py-3"
                                    @if ($this->maxAmount <= 0) disabled @endif>
                                    <span wire:loading.remove wire:target="requestWithdrawal">Kirim Pengajuan</span>
                                    <span wire:loading wire:target="requestWithdrawal">Memproses...</span>
                                </button>
                            </div>
                        @else
                            <div class="p-5 text-center bg-light bg-opacity-50 rounded-4 border-2 border-dashed">
                                <i class="bi bi-arrow-up-circle fs-3 text-muted opacity-25"></i>
                                <p class="text-muted extra-small mt-2 mb-0">Silakan pilih program untuk melihat
                                    informasi saldo.</p>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <div class="mt-4 mb-5 pb-5 px-2 text-center">
                <p class="text-muted extra-small mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Admin akan meninjau dan menghitung biaya admin/platform sebelum dana ditransfer ke rekening Anda.
                </p>
            </div>
        </div>
    </section>

    <x-app.bottom-nav />
</div>

<style>
    .border-dashed {
        border-style: dashed !important;
        border-color: #e9ecef !important;
    }
</style>
