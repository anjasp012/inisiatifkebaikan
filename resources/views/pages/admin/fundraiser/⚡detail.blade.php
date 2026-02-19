<?php

use Livewire\Attributes\Layout;
use App\Models\Fundraiser;
use Livewire\Component;

new #[Layout('layouts.admin')] class extends Component {
    public Fundraiser $fundraiser;

    public function mount(Fundraiser $fundraiser)
    {
        $this->fundraiser = $fundraiser->load('user', 'campaigns');
    }

    public function approve()
    {
        $this->fundraiser->update(['status' => 'approved']);
        $this->fundraiser->user->update(['role' => 'fundraiser']);

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Fundraiser berhasil disetujui ✅',
        ]);

        $this->redirectRoute('admin.fundraiser', navigate: true);
    }

    public function reject()
    {
        $this->fundraiser->update(['status' => 'rejected']);

        session()->flash('toast', [
            'type' => 'error',
            'message' => 'Fundraiser ditolak ❌',
        ]);

        $this->redirectRoute('admin.fundraiser', navigate: true);
    }
};

?>

<div>
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Detail Fundraiser</h5>
                    <p class="text-muted small mb-0">Informasi lengkap mengenai mitra fundraiser.</p>
                </div>
                <a href="{{ route('admin.fundraiser') }}" wire:navigate class="btn btn-light border">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">

            <div class="row">
                <!-- Left Column: Basic Info -->
                <div class="col-md-6">
                    <div class="card bg-light border-0 mb-3">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted extra-small fw-bold mb-3">Informasi Dasar</h6>

                            <div class="mb-3 text-center">
                                <img src="{{ $fundraiser->logo_url }}" class="rounded " width="120" alt="Logo">
                            </div>

                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted" width="150">Nama Yayasan</td>
                                    <td class="fw-bold">{{ $fundraiser->foundation_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">User</td>
                                    <td class="fw-bold">{{ $fundraiser->user->name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Email</td>
                                    <td class="fw-bold">{{ $fundraiser->user->email }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Alamat Kantor</td>
                                    <td class="fw-bold">{{ $fundraiser->office_address ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Status</td>
                                    <td>
                                        @if ($fundraiser->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($fundraiser->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Terdaftar</td>
                                    <td class="fw-bold">{{ $fundraiser->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Bank Info -->
                    <div class="card border-primary border-2 mb-3">
                        <div class="card-body">
                            <h6 class="text-uppercase text-primary extra-small fw-bold mb-3">Informasi Rekening</h6>
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="text-muted" width="150">Nama Bank</td>
                                    <td class="fw-bold">{{ $fundraiser->bank_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Nama Pemilik</td>
                                    <td class="fw-bold">{{ $fundraiser->bank_account_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">No. Rekening</td>
                                    <td class="fw-bold">{{ $fundraiser->bank_account_number ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Documents -->
                <div class="col-md-6">
                    <div class="card bg-light border-0 mb-3">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted extra-small fw-bold mb-3">Dokumen Legal</h6>

                            <!-- Izin Lembaga -->
                            <div class="mb-3">
                                <label class="form-label fw-bold extra-small text-uppercase text-muted mb-1">Izin
                                    Lembaga</label>
                                @if ($fundraiser->permit_doc)
                                    <div>
                                        <a href="{{ $fundraiser->permit_doc_url }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-file-earmark-pdf"></i> Lihat Dokumen
                                        </a>
                                    </div>
                                @else
                                    <p class="text-muted small">Belum upload</p>
                                @endif
                            </div>

                            <!-- SK Kumham -->
                            <div class="mb-3">
                                <label class="form-label fw-bold extra-small text-uppercase text-muted mb-1">SK
                                    Kumham</label>
                                @if ($fundraiser->legal_doc)
                                    <div>
                                        <a href="{{ $fundraiser->legal_doc_url }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-file-earmark-pdf"></i> Lihat Dokumen
                                        </a>
                                    </div>
                                @else
                                    <p class="text-muted small">Belum upload</p>
                                @endif
                            </div>

                            <!-- Akta Notaris -->
                            <div class="mb-3">
                                <label class="form-label fw-bold extra-small text-uppercase text-muted mb-1">Akta
                                    Notaris</label>
                                @if ($fundraiser->notary_doc)
                                    <div>
                                        <a href="{{ $fundraiser->notary_doc_url }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-file-earmark-pdf"></i> Lihat Dokumen
                                        </a>
                                    </div>
                                @else
                                    <p class="text-muted small">Belum upload</p>
                                @endif
                            </div>

                            <!-- NPWP -->
                            <div class="mb-3">
                                <label
                                    class="form-label fw-bold extra-small text-uppercase text-muted mb-1">NPWP</label>
                                @if ($fundraiser->tax_id)
                                    <div>
                                        <a href="{{ $fundraiser->tax_id_url }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-file-earmark-pdf"></i> Lihat Dokumen
                                        </a>
                                    </div>
                                @else
                                    <p class="text-muted small">Belum upload</p>
                                @endif
                            </div>

                            <!-- Office Photo -->
                            <div class="mb-3">
                                <label class="form-label fw-bold extra-small text-uppercase text-muted mb-1">Foto
                                    Kantor</label>
                                @if ($fundraiser->office_image)
                                    <div>
                                        <a href="{{ $fundraiser->office_image_url }}" target="_blank">
                                            <img src="{{ $fundraiser->office_image_url }}" class="img-fluid rounded"
                                                alt="Foto Kantor">
                                        </a>
                                    </div>
                                @else
                                    <p class="text-muted small">Belum upload</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campaigns List -->
            <div class="card bg-light border-0 mt-3">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted extra-small fw-bold mb-3">Campaign yang Dibuat</h6>
                    @if ($fundraiser->campaigns->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Target</th>
                                        <th>Terkumpul</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($fundraiser->campaigns as $campaign)
                                        <tr>
                                            <td>{{ $campaign->title }}</td>
                                            <td>Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($campaign->collected_amount, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($campaign->status == 'active')
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span
                                                        class="badge bg-secondary">{{ ucfirst($campaign->status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">Belum ada campaign</p>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            @if ($fundraiser->status == 'pending')
                <div class="mt-4 d-flex gap-2 justify-content-end">
                    <button wire:click="approve" wire:confirm="Setujui fundraiser ini?" class="btn btn-success">
                        <i class="bi bi-check-circle me-2"></i> Approve Fundraiser
                    </button>
                    <button wire:click="reject" wire:confirm="Tolak fundraiser ini?" class="btn btn-danger">
                        <i class="bi bi-x-circle me-2"></i> Reject Fundraiser
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
