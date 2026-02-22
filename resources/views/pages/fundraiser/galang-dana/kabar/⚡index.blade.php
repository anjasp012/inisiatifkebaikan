<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Campaign;
use App\Models\CampaignUpdate;
use App\Models\Fundraiser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

new #[Layout('layouts.app')] class extends Component {
    use WithFileUploads;

    public Campaign $campaign;
    public $title;
    public $content;
    public $image;
    public $updates = [];

    public function mount(Campaign $campaign)
    {
        $this->campaign = $campaign;

        // Security check
        $fundraiser = Fundraiser::where('user_id', Auth::id())->first();
        if (!$fundraiser || $fundraiser->id !== $this->campaign->fundraiser_id) {
            abort(403);
        }

        $this->loadUpdates();
    }

    public function loadUpdates()
    {
        $this->updates = $this->campaign->updates()->latest()->get();
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $update = new CampaignUpdate();
        $update->campaign_id = $this->campaign->id;
        $update->title = $this->title;
        $update->content = $this->content;
        $update->published_at = now(); // Auto publish for now

        if ($this->image) {
            $update->image = $this->image->store('campaign-updates', 'public');
        }

        $update->save();

        $this->reset(['title', 'content', 'image']);
        $this->loadUpdates();

        session()->flash('success', 'Kabar terbaru berhasil diposting!');
    }

    public function delete($id)
    {
        $update = CampaignUpdate::where('campaign_id', $this->campaign->id)->findOrFail($id);
        if ($update->image) {
            Storage::disk('public')->delete($update->image);
        }
        $update->delete();
        $this->loadUpdates();
        session()->flash('success', 'Kabar berhasil dihapus.');
    }
}; ?>

<div>
    <x-app.navbar-secondary title="Tulis Kabar" :route="route('fundraiser.galang-dana.kelola', $campaign->slug)" />

    <section class="py-4">
        <div class="container-fluid">
            {{-- Form Input --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-3">
                    <h6 class="fw-bold small mb-3">Tulis Kabar Baru</h6>

                    @if (session('success'))
                        <div class="alert alert-success py-2 px-3 small rounded-3 mb-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form wire:submit="save">
                        <div class="mb-3">
                            <label class="form-label extra-small text-muted mb-1">Judul Kabar</label>
                            <input type="text" wire:model="title" class="form-control form-control-sm rounded-3"
                                placeholder="Contoh: Penyaluran Bantuan Tahap 1">
                            @error('title')
                                <span class="text-danger extra-small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label extra-small text-muted mb-1">Isi Kabar</label>
                            <textarea wire:model="content" class="form-control form-control-sm rounded-3" rows="4"
                                placeholder="Ceritakan detail kegiatan..."></textarea>
                            @error('content')
                                <span class="text-danger extra-small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label extra-small text-muted mb-1">Foto Kegiatan (Opsional)</label>
                            <input type="file" wire:model="image" class="form-control form-control-sm rounded-3">
                            @error('image')
                                <span class="text-danger extra-small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-sm rounded-pill fw-bold">
                                <span wire:loading.remove wire:target="save">Posting Kabar</span>
                                <span wire:loading wire:target="save">Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- List Updates --}}
            <h6 class="fw-bold small mb-3 text-uppercase ls-sm">Riwayat Kabar</h6>

            <div class="space-y-3">
                @forelse ($updates as $update)
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold small mb-0">{{ $update->title }}</h6>
                                <div class="dropdown">
                                    <button class="btn btn-link p-0 text-muted" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                                        <li><a class="dropdown-item small text-danger" href="#"
                                                wire:click.prevent="delete({{ $update->id }})">Hapus</a></li>
                                    </ul>
                                </div>
                            </div>
                            <p class="text-muted extra-small mb-2">{{ Str::limit($update->content, 100) }}</p>

                            @if ($update->image)
                                <img src="{{ $update->image_url }}" class="rounded-3 w-100 object-fit-cover mb-2"
                                    height="120" alt="Update Image">
                            @endif

                            <small
                                class="text-muted extra-small d-block">{{ $update->created_at->format('d M Y, H:i') }}</small>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <img src="https://img.freepik.com/free-vector/empty-concept-illustration_114360-1188.jpg"
                            width="120" class="mb-3 opacity-50" style="filter: grayscale(100%)">
                        <p class="text-muted small mb-0">Belum ada kabar terbaru.</p>
                    </div>
                @endforelse
            </div>

            {{-- padding bottom to avoid protected by bottom bar --}}
            <div class="mb-5 pb-5"></div>
        </div>
    </section>

    <x-app.bottom-nav />
</div>
