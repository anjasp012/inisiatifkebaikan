<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Donation;
use App\Models\PaymentProof;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts.admin')] #[Title('Detail Donasi')] class extends Component {
    use WithFileUploads;

    public Donation $donation;
    public $editAmount;
    public $newProofs = [];

    public function updatedNewProofs()
    {
        $this->validate([
            'newProofs.*' => 'image|max:5120',
        ]);

        foreach ($this->newProofs as $proof) {
            $path = $proof->store('payment-proofs', 'public');

            PaymentProof::create([
                'donation_id' => $this->donation->id,
                'file_path' => $path,
            ]);
        }

        $this->newProofs = [];
        $this->donation->load('paymentProofs');

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Bukti tambahan berhasil ditambahkan! ✅',
        ]);
    }

    public function mount(Donation $donation)
    {
        $this->donation = $donation;
        $this->editAmount = $donation->amount;
    }

    public function approve()
    {
        // Update amount if changed
        if ($this->editAmount != $this->donation->amount) {
            $this->donation->amount = $this->editAmount;
        }

        $this->donation->status = 'success';
        $this->donation->paid_at = now();
        $this->donation->save();

        // Update campaign collected amount
        $this->donation->campaign->increment('collected_amount', $this->donation->amount);

        // Send WA Notification via Whacenter
        $waService = new \App\Services\WhacenterService();
        $message = "Assalamu'alaikum {$this->donation->donor_name},\n\nTerima kasih! Donasi Anda sebesar *Rp " . number_format($this->donation->amount, 0, ',', '.') . "* untuk program *{$this->donation->campaign->title}* telah kami terima.\n\nSemoga menjadi amal jariyah yang terus mengalir pahalanya. Aamiin.\n\nSalam,\n*Inisiatif Kebaikan*";

        $waService->sendMessage($this->donation->donor_phone, $message);

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Donasi berhasil disetujui (Success) ✅',
        ]);

        $this->redirectRoute('admin.donasi.detail', $this->donation, navigate: true);
    }

    public function reject()
    {
        $this->donation->update(['status' => 'failed']);

        session()->flash('toast', [
            'type' => 'error',
            'message' => 'Donasi ditolak (Failed) ❌',
        ]);

        $this->redirectRoute('admin.donasi.detail', $this->donation, navigate: true);
    }

    public function revert()
    {
        if ($this->donation->status === 'success') {
            $this->donation->campaign->decrement('collected_amount', $this->donation->amount);
        }

        $this->donation->update([
            'status' => 'pending',
            'paid_at' => null,
        ]);

        session()->flash('toast', [
            'type' => 'warning',
            'message' => 'Status donasi dikembalikan ke Pending ⚠️',
        ]);

        $this->redirectRoute('admin.donasi.detail', $this->donation, navigate: true);
    }

    public function getWaLogsProperty()
    {
        return \App\Models\NotificationLog::where('donation_id', $this->donation->id)->latest()->get();
    }
};

?>

<div>
    <div class="row g-4">
        <!-- Main Stats & Actions -->
        <div class="col-lg-8">
            <div class="card border-0  overflow-hidden mb-4">
                <div class="card-body border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <h5 class="mb-1 fw-bold">Detail Transaksi</h5>
                                <p class="text-muted small mb-0">ID: {{ $donation->transaction_id }}</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.donasi') }}" wire:navigate class="btn btn-light border">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="p-4" style="background: linear-gradient(to right, #f8f9fa, #ffffff);">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <h6 class="text-uppercase text-muted small fw-bold mb-2">NOMINAL DONASI</h6>
                                <div class="display-5 fw-bold text-primary mb-3">
                                    Rp {{ number_format($donation->amount, 0, ',', '.') }}
                                </div>
                                <div class="d-flex gap-2 mb-3">
                                    @if ($donation->status == 'success')
                                        <span class="badge rounded-pill px-3 py-2 bg-success text-white">
                                            <i class="bi bi-check-circle-fill me-1"></i> Status: Sukses
                                        </span>
                                    @elseif($donation->status == 'pending')
                                        <span class="badge rounded-pill px-3 py-2 bg-warning text-dark">
                                            <i class="bi bi-clock-fill me-1"></i> Status: Pending
                                        </span>
                                    @else
                                        <span class="badge rounded-pill px-3 py-2 bg-danger text-white">
                                            <i class="bi bi-x-circle-fill me-1"></i> Status: Gagal
                                        </span>
                                    @endif

                                    <span class="badge rounded-pill px-3 py-2 bg-light text-dark border">
                                        @if ($donation->bank)
                                            {{ $donation->bank->type == 'manual' ? 'Transfer Manual' : 'Otomatis (' . ucfirst($donation->bank->type) . ')' }}
                                        @else
                                            {{ str_replace('_', ' ', $donation->payment_method) }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-5 text-md-end">
                                <div class="p-3 bg-white rounded-4  border border-primary border-opacity-10">
                                    <small class="text-muted d-block mb-1">Metode Pembayaran</small>
                                    <h6 class="fw-bold mb-2 text-primary text-uppercase">
                                        {{ $donation->bank ? $donation->bank->bank_name : str_replace('_', ' ', $donation->payment_method) }}
                                    </h6>
                                    <div class="bg-light p-2 rounded small font-monospace text-break">
                                        {{ $donation->bank ? ($donation->bank->account_number ? $donation->bank->account_number . ' a/n ' . $donation->bank->account_name : $donation->bank->bank_code) : $donation->payment_code }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border-top">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3 d-flex align-items-center">
                                    <i class="bi bi-person-circle me-2 text-primary"></i> Data Donatur
                                </h6>
                                <div class="list-group list-group-flush border rounded-3 overflow-hidden">
                                    <div
                                        class="list-group-item d-flex justify-content-between align-items-center bg-light bg-opacity-50">
                                        <span class="text-muted small">Nama</span>
                                        <span class="fw-semibold">{{ $donation->donor_name }}
                                            {!! $donation->is_anonymous ? '<small class="text-muted">(Anonim)</small>' : '' !!}</span>
                                    </div>
                                    @if ($donation->donor_email)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <span class="text-muted small">Email</span>
                                            <span class="fw-semibold">{{ $donation->donor_email }}</span>
                                        </div>
                                    @endif
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="text-muted small">No. WhatsApp</span>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $donation->donor_phone) }}"
                                            target="_blank"
                                            class="fw-semibold text-decoration-none text-success d-flex align-items-center">
                                            {{ $donation->donor_phone }} <i class="bi bi-whatsapp ms-2"></i>
                                        </a>
                                    </div>
                                    <div
                                        class="list-group-item d-flex justify-content-between align-items-center bg-light bg-opacity-50">
                                        <span class="text-muted small">Terdaftar</span>
                                        <span
                                            class="fw-semibold">{{ $donation->user_id ? $donation->user->name : 'Non-User (Guest)' }}</span>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="text-muted small">Waktu Order</span>
                                        <span
                                            class="fw-semibold">{{ $donation->created_at->translatedFormat('d M Y, H:i') }}</span>
                                    </div>
                                    @if ($donation->paid_at)
                                        <div
                                            class="list-group-item d-flex justify-content-between align-items-center bg-success bg-opacity-10">
                                            <span class="text-success small fw-bold">Waktu Bayar</span>
                                            <span
                                                class="fw-bold text-success">{{ $donation->paid_at->translatedFormat('d M Y, H:i') }}</span>
                                        </div>
                                    @endif
                                </div>

                                @if ($donation->message)
                                    <div class="mt-4 p-3 bg-light border-start border-4 border-primary rounded-3">
                                        <h6 class="fw-bold small text-muted text-uppercase mb-2">Pesan / Doa Donatur
                                        </h6>
                                        <p class="mb-0 fst-italic">"{{ $donation->message }}"</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3 d-flex align-items-center">
                                    <i class="bi bi-megaphone-fill me-2 text-primary"></i> Campaign Terkait
                                </h6>
                                <div class="card border rounded-3 p-3">
                                    <div class="d-flex gap-3 mb-3">
                                        @if ($donation->campaign->thumbnail)
                                            <img src="{{ $donation->campaign->thumbnail_url }}" class="rounded "
                                                style="width: 80px; height: 80px; object-fit: cover;">
                                        @endif
                                        <div class="overflow-hidden">
                                            <div class="fw-bold text-truncate">{{ $donation->campaign->title }}</div>
                                            <div class="small text-muted mb-1">
                                                {{ $donation->campaign->category->name ?? 'Zakat & Wakaf' }}</div>
                                            <div wire:ignore class="progress" style="height: 6px;">
                                                @php $percent = min(100, ($donation->campaign->collected_amount / max(1, $donation->campaign->target_amount)) * 100); @endphp
                                                <div class="progress-bar bg-primary"
                                                    style="width: {{ $percent }}%">
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between mt-1 small">
                                                <span class="fw-bold text-primary">Rp
                                                    {{ number_format($donation->campaign->collected_amount, 0, ',', '.') }}</span>
                                                <span class="text-muted">Target:
                                                    {{ number_format($donation->campaign->target_amount, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-grid">
                                        <a href="{{ route('admin.campaign.ubah', $donation->campaign_id) }}"
                                            wire:navigate class="btn btn-outline-primary btn-sm">
                                            Lihat Campaign <i class="bi bi-box-arrow-up-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (
                $donation->payment_method == 'manual' ||
                    $donation->payment_method == 'manual_transfer' ||
                    $donation->paymentProofs->isNotEmpty())
                <div class="card border-0  mb-4">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold d-flex align-items-center">
                            <i class="bi bi-images me-2 text-primary"></i> Bukti Transfer
                            <span
                                class="badge bg-primary bg-opacity-10 text-primary ms-2">{{ $donation->paymentProofs->count() }}
                                File</span>
                        </h6>
                        <div>
                            <input type="file" wire:model="newProofs" multiple class="d-none" id="addProofInput"
                                accept="image/*">
                            <label for="addProofInput"
                                class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold cursor-pointer mb-0">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Bukti admin
                            </label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div wire:loading wire:target="newProofs" class="text-primary small mb-3">
                            <div class="spinner-border spinner-border-sm me-2"></div> Sedang mengunggah...
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="bg-light">
                                    <tr class="x-small text-muted text-uppercase fw-bold">
                                        <th class="ps-3 py-2">No</th>
                                        <th class="py-2">Bukti</th>
                                        <th class="py-2">Waktu Upload</th>
                                        <th class="text-end pe-3 py-2">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($donation->paymentProofs as $proof)
                                        <tr>
                                            <td class="ps-3 small text-muted">{{ $loop->iteration }}</td>
                                            <td>
                                                <img src="{{ $proof->file_url }}" class="rounded border shadow-sm"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            </td>
                                            <td class="small">
                                                {{ $proof->created_at->translatedFormat('d M Y, H:i') }}
                                            </td>
                                            <td class="text-end pe-3">
                                                <a href="{{ $proof->file_url }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary py-0 px-2"
                                                    style="font-size: 0.75rem;">Lihat</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-3 small text-muted">Belum ada
                                                bukti</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar Actions -->
        <div class="col-lg-4">
            @if ($donation->status == 'pending')
                <div class="card border-0 mb-4 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3 text-primary">Tindakan Admin</h5>
                        <p class="small text-muted mb-4">Verifikasi pembayaran ini sesuai dengan bukti transfer yang
                            diunggah donatur.</p>

                        <div class="bg-light p-3 rounded-3 text-dark mb-4 border">
                            <label class="form-label fw-bold small text-primary mb-2">SESUAIKAN NOMINAL
                                (OPSIONAL)</label>



                            <div class="input-group">
                                <span class="input-group-text bg-white border-0">Rp</span>
                                <input type="number" wire:model="editAmount"
                                    class="form-control border-0 bg-white fw-bold" placeholder="Check bukti bayar...">
                            </div>
                            <small class="text-muted mt-2 d-block" style="font-size: 0.75rem;">
                                Ubah nominal jika jumlah yang ditransfer berbeda.
                            </small>
                        </div>

                        <div class="d-grid gap-2">
                            <button wire:click="approve" wire:confirm="Anda yakin menyetujui donasi ini?"
                                class="btn btn-success text-white py-2 fw-bold">
                                <i class="bi bi-check-circle-fill me-2"></i> Setujui Donasi
                            </button>
                            <button wire:click="reject" wire:confirm="Anda yakin menolak donasi ini?"
                                class="btn btn-outline-danger py-2 fw-bold">
                                <i class="bi bi-x-circle-fill me-2"></i> Tolak Donasi
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="card border-0  mb-4">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center {{ $donation->status == 'success' ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }} rounded-3 mb-3"
                                style="width: 72px; height: 72px;">
                                <i
                                    class="bi {{ $donation->status == 'success' ? 'bi-check-lg text-success' : 'bi-x-lg text-danger' }} fs-1"></i>
                            </div>
                            <h5 class="fw-bold">Donasi Telah Diproses</h5>
                            <p class="text-muted small">Status transaksi ini adalah
                                <strong>{{ strtoupper($donation->status) }}</strong>.
                            </p>
                        </div>

                        <div class="d-grid gap-2">
                            <button wire:click="revert"
                                wire:confirm="PENTING: Status akan kembali ke Pending dan Saldo Campaign akan dikurangi. Lanjutkan?"
                                class="btn btn-outline-warning btn-sm">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Revert ke Pending
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card border-0 border-start border-4 border-info">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 small opacity-75 text-uppercase">Log Notifikasi WA</h6>
                    @forelse($this->waLogs as $log)
                        <div class="d-flex align-items-start mb-2">
                            <i
                                class="bi bi-chat-dots-fill me-2 {{ $log->status === 'success' ? 'text-success' : 'text-danger' }} mt-1"></i>
                            <div>
                                <div
                                    class="small fw-bold {{ $log->status === 'success' ? 'text-success' : 'text-danger' }}">
                                    {{ $log->status === 'success' ? 'Berhasil Terkirim' : 'Gagal Kirim' }}
                                </div>
                                <div class="extra-small text-muted">{{ $log->created_at->format('d/m H:i') }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-chat-dots me-2"></i>
                            <span class="small">Belum ada notifikasi terkirim</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
