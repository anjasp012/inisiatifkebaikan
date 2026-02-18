<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Distribution;
use App\Models\Campaign;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\WithFileUploads;

new #[Layout('layouts.admin')] #[Title('Riwayat Distribusi')] class extends Component {
    use WithPagination, WithFileUploads;
    protected $paginationTheme = 'bootstrap';

    public $campaign_id;
    public $amount;
    public $recipient_name;
    public $distribution_date;
    public $description;
    public $file_path;

    public $search;
    public $selectedDistribution;

    public function destroy(Distribution $distribution): void
    {
        if ($distribution->file_path && file_exists(public_path('storage/' . $distribution->file_path))) {
            unlink(public_path('storage/' . $distribution->file_path));
        }

        $distribution->delete();

        $this->dispatch('toast', type: 'success', message: 'Laporan penyaluran berhasil dihapus ✅');
    }

    public function showDistribution(int $id)
    {
        $this->selectedDistribution = Distribution::find($id);
        $this->dispatch('open-modal', id: 'modalDetail');
    }

    public function store()
    {
        $this->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'amount' => 'required|numeric',
            'recipient_name' => 'required|string',
            'distribution_date' => 'required|date',
            'description' => 'required|string',
            'file_path' => 'required|image|max:2048',
        ]);

        $path = $this->file_path->store('distributions', 'public');

        Distribution::create([
            'campaign_id' => $this->campaign_id,
            'amount' => $this->amount,
            'recipient_name' => $this->recipient_name,
            'distribution_date' => $this->distribution_date,
            'description' => $this->description,
            'file_path' => $path,
        ]);

        $this->reset(['campaign_id', 'amount', 'recipient_name', 'distribution_date', 'description', 'file_path']);
        $this->dispatch('toast', type: 'success', message: 'Laporan penyaluran berhasil ditambahkan ✅');
    }

    #[Computed]
    public function distributions()
    {
        return Distribution::with('campaign')
            ->when($this->search, function ($q) {
                $q->whereHas('campaign', function ($c) {
                    $c->where('title', 'like', '%' . $this->search . '%');
                })->orWhere('recipient_name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('distribution_date', 'desc')
            ->paginate(10);
    }

    #[Computed]
    public function campaigns()
    {
        return Campaign::where('status', 'active')->get();
    }
};
?>

<div>
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card card-dashboard  mb-4">
                <div class="card-body border-bottom">
                    <h6 class="fw-bold mb-0">Input Penyaluran Dana</h6>
                </div>
                <div class="card-body bg-light bg-opacity-50">
                    <form wire:submit.prevent="store">
                        <div class="mb-3 @error('campaign_id') is-invalid-tomselect @enderror">
                            <label class="form-label small fw-bold text-uppercase opacity-75">Program Campaign</label>
                            <div wire:ignore>
                                <select class="form-select @error('campaign_id') is-invalid @enderror"
                                    x-init="new TomSelect($el, {
                                        placeholder: 'Pilih atau cari Campaign...',
                                        allowEmptyOption: false,
                                        onDropdownOpen: function() {
                                            this.clear(true);
                                        },
                                        onChange: (value) => {
                                            $wire.set('campaign_id', value);
                                        }
                                    })">
                                    <option value="">-- Pilih Campaign --</option>
                                    @foreach ($this->campaigns as $campaign)
                                        <option value="{{ $campaign->id }}">{{ $campaign->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('campaign_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <x-admin.input-rupiah model="amount" label="Nominal Disalurkan" placeholder="0" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase opacity-75">Penerima Manfaat</label>
                            <input type="text" wire:model="recipient_name"
                                class="form-control rounded-3 @error('recipient_name') is-invalid @enderror"
                                placeholder="Contoh: Warga Terdampak Banjir">
                            @error('recipient_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <x-admin.input-calendar model="distribution_date" label="Tanggal Penyaluran" />
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label small fw-bold text-uppercase opacity-75">Kabar Terbaru</label>
                            <x-admin.text-editor model="description" id="description" />
                            @error('description')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <x-admin.file-upload model="file_path" label="Foto Dokumentasi" :preview="$file_path ? $file_path->temporaryUrl() : null" />
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary text-white fw-bold px-4 shadow-sm"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove><i class="bi bi-send-fill me-2"></i> Publikasikan Kabar</span>
                                <span wire:loading>
                                    <div class="spinner-border spinner-border-sm me-2" role="status"></div> Mengirim...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card card-dashboard">
                <div class="card-body border-bottom">
                    <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center gap-3">
                        <div>
                            <h5 class="fw-bold mb-1">Riwayat Penyaluran</h5>
                            <p class="text-muted small mb-0">Daftar kabar terbaru penyaluran dana ke penerima manfaat.
                            </p>
                        </div>

                        <div class="position-relative">
                            <input type="text" class="form-control ps-5" placeholder="Cari penyaluran..."
                                wire:model.live.debounce.300ms="search" style="min-width: 250px;">
                            <i
                                class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-uppercase x-small fw-bold text-muted">
                                <th class="ps-4" style="width: 50px;">No</th>
                                <th>Program & Penerima</th>
                                <th class="text-end">Nominal</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($this->distributions as $index => $dist)
                                <tr wire:key="dist-row-{{ $dist->id }}">
                                    <td class="ps-4 text-center text-muted small">
                                        {{ $this->distributions->firstItem() + $index }}
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark small">
                                            {{ $dist->campaign->title ?? 'Campaign Terhapus' }}</div>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            @if ($dist->campaign && $dist->campaign->category)
                                                <span
                                                    class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 border border-primary border-opacity-10"
                                                    style="font-size: 9px; font-weight: 600;">
                                                    <i class="bi bi-tag-fill me-1"></i>
                                                    {{ $dist->campaign->category->name }}
                                                </span>
                                            @endif
                                            <div class="text-muted x-small">Penerima: {{ $dist->recipient_name }}</div>
                                        </div>
                                    </td>
                                    <td class="text-end fw-bold text-primary">
                                        Rp {{ number_format($dist->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center small">
                                        <div class="fw-medium">
                                            {{ Carbon\Carbon::parse($dist->distribution_date)->translatedFormat('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            <button type="button" wire:key="show-{{ $dist->id }}"
                                                wire:click="showDistribution({{ $dist->id }})"
                                                class="btn btn-sm btn-info text-white" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button wire:click="destroy({{ $dist->id }})"
                                                wire:confirm="Anda yakin ingin menghapus data ini?"
                                                class="btn btn-sm btn-danger text-white" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                                            Belum ada kabar penyaluran dana.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                        <div class="text-muted small">
                            Menampilkan <strong>{{ $this->distributions->firstItem() }}</strong> -
                            <strong>{{ $this->distributions->lastItem() }}</strong> dari
                            <strong>{{ $this->distributions->total() }}</strong> penyaluran
                        </div>
                        <div>
                            {{ $this->distributions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                @if ($selectedDistribution)
                    <div wire:key="modal-content-{{ $selectedDistribution->id }}">
                        <div class="modal-header border-bottom p-4">
                            <h5 class="modal-title fw-bold">Detail Penyaluran Dana</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="row g-4">
                                <div class="col-md-5">
                                    <label class="form-label x-small fw-bold text-uppercase text-muted">Foto
                                        Dokumentasi</label>
                                    <img src="{{ $selectedDistribution->file_url }}"
                                        class="img-fluid rounded shadow-sm border" alt="Dokumentasi">
                                </div>
                                <div class="col-md-7">
                                    <label class="form-label x-small fw-bold text-uppercase text-muted">Nama
                                        Campaign</label>
                                    <h6 class="fw-bold mb-1 text-primary">
                                        {{ $selectedDistribution->campaign->title ?? 'Campaign Terhapus' }}
                                    </h6>
                                    @if ($selectedDistribution->campaign && $selectedDistribution->campaign->category)
                                        <span
                                            class="badge bg-primary bg-opacity-10 text-primary x-small border border-primary border-opacity-10">
                                            {{ $selectedDistribution->campaign->category->name }}
                                        </span>
                                    @endif
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-6">
                                        <label
                                            class="form-label x-small fw-bold text-uppercase text-muted">Penerima</label>
                                        <div class="fw-bold">{{ $selectedDistribution->recipient_name }}</div>
                                    </div>
                                    <div class="col-6">
                                        <label
                                            class="form-label x-small fw-bold text-uppercase text-muted">Tanggal</label>
                                        <div class="fw-bold">
                                            {{ Carbon\Carbon::parse($selectedDistribution->distribution_date)->translatedFormat('d M Y') }}
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label x-small fw-bold text-uppercase text-muted">Total
                                            Disalurkan</label>
                                        <h5 class="fw-bold text-primary mb-0">
                                            Rp {{ number_format($selectedDistribution->amount, 0, ',', '.') }}
                                        </h5>
                                    </div>
                                </div>

                                <label class="form-label x-small fw-bold text-uppercase text-muted">Keterangan /
                                    Kabar</label>
                                <div class="p-3 bg-light rounded border small ck-content">
                                    {!! $selectedDistribution->description !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .x-small {
        font-size: 0.7rem;
    }

    .object-fit-cover {
        object-fit: cover;
    }

    /* Validation Style for TomSelect */
    .is-invalid-tomselect .ts-control {
        border-color: #dc3545 !important;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5zM6 8.2h.01'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
</style>

@push('scripts')
    <script>
        window.addEventListener('open-modal', event => {
            const payload = event.detail;
            const modalId = typeof payload === 'object' ? payload.id : payload;

            if (modalId) {
                const modalElement = document.getElementById(modalId);
                if (modalElement) {
                    const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
                    modal.show();
                }
            }
        });
    </script>
@endpush
