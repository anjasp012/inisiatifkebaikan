<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use App\Models\Bank;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts.admin')] class extends Component {
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;

    protected $queryString = ['search'];

    #[Computed]
    public function banks()
    {
        return Bank::withCount('donations')
            ->when($this->search, function ($query) {
                $query->where('bank_name', 'like', '%' . $this->search . '%')->orWhere('account_number', 'like', '%' . $this->search . '%');
            })
            ->orderBy('type', 'asc')
            ->orderBy('bank_name', 'asc')
            ->paginate(10);
    }

    public function destroy(Bank $bank): void
    {
        if ($bank->logo && file_exists(public_path('storage/' . $bank->logo))) {
            unlink(public_path('storage/' . $bank->logo));
        }

        $bank->delete();

        $this->dispatch('toast', type: 'success', message: 'Bank berhasil dihapus ✅');
    }

    public function toggleStatus(Bank $bank): void
    {
        $bank->update(['is_active' => !$bank->is_active]);
        $this->dispatch('toast', type: 'success', message: 'Status bank berhasil diperbarui ✅');
    }

    public function syncTripay()
    {
        $apiKey = \App\Models\Setting::get('tripay_api_key');
        $mode = \App\Models\Setting::get('tripay_mode', 'sandbox');

        if (!$apiKey) {
            $this->dispatch('toast', type: 'error', message: 'API Key Tripay belum diatur di Pengaturan ❌');
            return;
        }

        $url = $mode === 'production' ? 'https://tripay.co.id/api/merchant/payment-channel' : 'https://tripay.co.id/api-sandbox/merchant/payment-channel';

        try {
            $response = \Illuminate\Support\Facades\Http::withToken($apiKey)->get($url);

            if ($response->successful()) {
                $channels = $response->json()['data'];

                // Update data yang sudah ada (tanpa ubah status aktif) atau tambah baru jika belum ada
                foreach ($channels as $channel) {
                    $bank = Bank::firstOrNew(['bank_code' => $channel['code'], 'type' => 'tripay']);
                    if (!$bank->exists) {
                        $bank->is_active = $channel['active'];
                    }
                    $bank->bank_name = $channel['name'];
                    $bank->logo = $channel['icon_url'];

                    // Auto-assign method for Tripay based on their group
                    $group = strtolower($channel['group'] ?? '');
                    if (Str::contains($group, 'virtual account')) {
                        $bank->method = 'va';
                    } elseif (Str::contains($group, 'wallet')) {
                        $bank->method = 'ewallet';
                    } elseif (Str::contains($group, 'convenience store')) {
                        $bank->method = 'retail';
                    } else {
                        $bank->method = 'other';
                    }

                    $bank->save();
                }

                $this->dispatch('toast', type: 'success', message: 'Sinkronisasi Tripay berhasil ✅');
            } else {
                $this->dispatch('toast', type: 'error', message: 'Gagal koneksi ke Tripay: ' . ($response->json()['message'] ?? 'Unknown Error'));
            }
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function syncMidtrans()
    {
        $serverKey = \App\Models\Setting::get('midtrans_server_key');

        if (!$serverKey) {
            $this->dispatch('toast', type: 'error', message: 'Server Key Midtrans belum diatur di Pengaturan ❌');
            return;
        }

        $channels = [
            ['code' => 'bca_va', 'name' => 'BCA Virtual Account', 'logo' => 'https://static.midtrans.com/v2/payment-methods/bca.png', 'method' => 'va'],
            ['code' => 'bni_va', 'name' => 'BNI Virtual Account', 'logo' => 'https://static.midtrans.com/v2/payment-methods/bni.png', 'method' => 'va'],
            ['code' => 'bri_va', 'name' => 'BRI Virtual Account', 'logo' => 'https://static.midtrans.com/v2/payment-methods/bri.png', 'method' => 'va'],
            ['code' => 'mandiri_va', 'name' => 'Mandiri Virtual Account', 'logo' => 'https://static.midtrans.com/v2/payment-methods/mandiri.png', 'method' => 'va'],
            ['code' => 'permata_va', 'name' => 'Permata Virtual Account', 'logo' => 'https://static.midtrans.com/v2/payment-methods/permata.png', 'method' => 'va'],
            ['code' => 'gopay', 'name' => 'Gopay', 'logo' => 'https://static.midtrans.com/v2/payment-methods/gopay.png', 'method' => 'ewallet'],
            ['code' => 'shopeepay', 'name' => 'ShopeePay', 'logo' => 'https://static.midtrans.com/v2/payment-methods/shopeepay.png', 'method' => 'ewallet'],
            ['code' => 'qris', 'name' => 'QRIS', 'logo' => 'https://static.midtrans.com/v2/payment-methods/qris.png', 'method' => 'qris'],
        ];

        try {
            foreach ($channels as $channel) {
                $bank = Bank::firstOrNew(['bank_code' => $channel['code'], 'type' => 'midtrans']);
                if (!$bank->exists) {
                    $bank->is_active = true;
                }
                $bank->bank_name = $channel['name'];
                $bank->logo = $channel['logo'];
                $bank->method = $channel['method'];
                $bank->save();
            }

            $this->dispatch('toast', type: 'success', message: 'Sinkronisasi Midtrans berhasil ✅');
        } catch (\Exception $e) {
            $this->dispatch('toast', type: 'error', message: 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
};
?>

<div>
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Manajemen Bank & Pembayaran</h5>
                    <p class="text-muted small mb-0">Kelola rekening bank manual dan integrasi payment gateway.</p>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <div class="dropdown">
                        <button class="btn btn-info text-white dropdown-toggle shadow-sm d-flex align-items-center gap-2"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false" wire:loading.attr="disabled">
                            <i class="bi bi-arrow-repeat"></i>
                            <span>Sync Gateway</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 mt-2"
                            style="border-radius: 12px;">
                            <li>
                                <button class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2 mb-1"
                                    type="button" wire:click="syncTripay" wire:loading.attr="disabled">
                                    <div class="bg-info bg-opacity-10 text-info rounded p-1"
                                        style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-credit-card"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold small">Sync Tripay</div>
                                        <div class="text-muted" style="font-size: 10px;">Update data from Tripay API
                                        </div>
                                    </div>
                                </button>
                            </li>
                            <li>
                                <button class="dropdown-item rounded-3 py-2 d-flex align-items-center gap-2"
                                    type="button" wire:click="syncMidtrans" wire:loading.attr="disabled">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded p-1"
                                        style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-shield-check"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold small">Sync Midtrans</div>
                                        <div class="text-muted" style="font-size: 10px;">Update common Midtrans channels
                                        </div>
                                    </div>
                                </button>
                            </li>
                        </ul>
                    </div>
                    <a href="{{ route('admin.bank.tambah') }}" wire:navigate class="btn btn-primary text-white">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Manual
                    </a>
                    <div class="position-relative">
                        <input type="text" class="form-control ps-5" placeholder="Cari bank..."
                            wire:model.live.debounce.250ms="search" style="min-width: 250px;">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">NO</th>
                        <th style="width: 100px;">LOGO</th>
                        <th>NAMA BANK</th>
                        <th>NOMOR REKENING</th>
                        <th>ATAS NAMA</th>
                        <th class="text-center">TIPE</th>
                        <th class="text-center">METODE</th>
                        <th class="text-center">DIGUNAKAN</th>
                        <th class="text-center">STATUS</th>
                        <th class="text-end pe-3">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->banks as $no => $bank)
                        <tr>
                            <td class="text-center">{{ $this->banks->firstItem() + $no }}</td>
                            <td>
                                <img src="{{ $bank->logo_url }}" width="60px" class="rounded border"
                                    alt="{{ $bank->bank_name }}">
                            </td>
                            <td>
                                <div class="fw-bold">{{ $bank->bank_name }}</div>
                                <div class="x-small text-muted">{{ $bank->bank_code }}</div>
                            </td>
                            <td>{{ $bank->account_number ?? '-' }}</td>
                            <td>{{ $bank->account_name ?? '-' }}</td>
                            <td class="text-center">
                                @if ($bank->type == 'manual')
                                    <span
                                        class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 px-2 py-1">Manual</span>
                                @elseif($bank->type == 'tripay')
                                    <span
                                        class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-10 px-2 py-1 text-uppercase">Tripay</span>
                                @else
                                    <span
                                        class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 px-2 py-1 text-uppercase">Midtrans</span>
                                @endif
                            </td>
                            <td class="text-center text-uppercase">
                                <span class="badge bg-light text-dark border x-small">{{ $bank->method ?: '-' }}</span>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold">{{ number_format($bank->donations_count) }}</span>
                                <div class="x-small text-muted">Donasi</div>
                            </td>
                            <td class="text-center">
                                <div class="d-inline-flex align-items-center">
                                    @php
                                        $isActive = $bank->is_active;
                                        $statusColor = $isActive ? 'success' : 'secondary';
                                    @endphp
                                    <label
                                        class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} py-2 px-3 border border-{{ $statusColor }} border-opacity-10 d-flex align-items-center gap-2 cursor-pointer"
                                        for="statusSwitch{{ $bank->id }}">
                                        <div class="form-check form-switch p-0 m-0" style="min-height: auto;">
                                            <input class="form-check-input cursor-pointer m-0" type="checkbox"
                                                role="switch" wire:click="toggleStatus({{ $bank->id }})"
                                                id="statusSwitch{{ $bank->id }}" @checked($isActive)
                                                style="width: 1.8em; height: 1em;">
                                        </div>
                                        <span class="x-small fw-bold text-uppercase" style="letter-spacing: 0.5px;">
                                            {{ $isActive ? 'Aktif' : 'Tersembunyi' }}
                                        </span>
                                    </label>
                                </div>
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admin.bank.ubah', $bank) }}" wire:navigate
                                        class="btn btn-sm btn-warning text-white" title="Ubah"><i
                                            class="bi bi-pencil"></i></a>
                                    <button wire:click="destroy({{ $bank->id }})"
                                        wire:confirm="Anda yakin menghapus bank ini?"
                                        class="btn btn-sm btn-danger text-white" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                    <div class="text-muted small">
                        Menampilkan <strong>{{ $this->banks->firstItem() }}</strong> -
                        <strong>{{ $this->banks->lastItem() }}</strong> dari
                        <strong>{{ $this->banks->total() }}</strong> bank
                    </div>
                    <div>
                        {{ $this->banks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
