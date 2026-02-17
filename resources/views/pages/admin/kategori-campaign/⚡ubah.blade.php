<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use App\Models\CampaignCategory;

new #[Layout('layouts.admin')] class extends Component {
    use WithFileUploads;

    public CampaignCategory $campaignCategory;

    public $icon;
    public $iconType = 'select'; // 'upload' or 'select'
    public $selectedIcon = '';
    public $bootstrapIcons = [];
    public $searchIcon = '';

    public string $name = '';

    public function mount(CampaignCategory $campaignCategory)
    {
        $this->campaignCategory = $campaignCategory;
        $this->name = $campaignCategory->name;

        // Load Bootstrap Icons
        $jsonPath = resource_path('js/bootstrap-icons.json');
        if (file_exists($jsonPath)) {
            $icons = json_decode(file_get_contents($jsonPath), true);
            $this->bootstrapIcons = array_keys($icons);
        }

        // Determine icon type
        if ($campaignCategory->is_bootstrap_icon) {
            $this->iconType = 'select';
            $this->selectedIcon = str_replace('bi-', '', $campaignCategory->icon);
        } else {
            $this->iconType = 'upload';
        }
    }

    public function updatedIconType()
    {
        $this->resetErrorBag(['icon', 'selectedIcon']);
    }

    public function selectIcon($icon)
    {
        $this->selectedIcon = $icon;
    }

    public function store(): void
    {
        $rules = [
            'name' => 'required|string|max:100',
        ];

        if ($this->iconType === 'upload') {
            $rules['icon'] = 'nullable|image|max:2048';
        } else {
            $rules['selectedIcon'] = 'required|string';
        }

        $messages = [
            'icon.image' => 'Icon harus berupa gambar.',
            'selectedIcon.required' => 'Silakan pilih icon.',
            'name.required' => 'Nama kategori campaign wajib diisi.',
            'name.max' => 'Nama maksimal 100 karakter.',
        ];

        $validated = $this->validate($rules, $messages);

        $finalIcon = $this->campaignCategory->icon;

        if ($this->iconType === 'upload') {
            if ($this->icon) {
                // Delete old if it was an upload
                if (!$this->campaignCategory->is_bootstrap_icon && $this->campaignCategory->icon && file_exists(public_path('storage/' . $this->campaignCategory->icon))) {
                    unlink(public_path('storage/' . $this->campaignCategory->icon));
                }
                $finalIcon = $this->icon->store('campaign-category', 'public');
            }
        } else {
            $finalIcon = 'bi-' . $this->selectedIcon;
            // If switching from upload to icon, delete old file
            if (!$this->campaignCategory->is_bootstrap_icon && $this->campaignCategory->icon && file_exists(public_path('storage/' . $this->campaignCategory->icon))) {
                unlink(public_path('storage/' . $this->campaignCategory->icon));
            }
        }

        $this->campaignCategory->update([
            'icon' => $finalIcon,
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Kategori campaign berhasil diubah ✅',
        ]);
        $this->redirectRoute('admin.kategori-campaign', navigate: true);
    }

    public function getFilteredIconsProperty()
    {
        if (empty($this->searchIcon)) {
            return array_slice($this->bootstrapIcons, 0, 100);
        }

        return array_filter($this->bootstrapIcons, function ($icon) {
            return str_contains($icon, $this->searchIcon);
        });
    }
};
?>

<div>
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Ubah Kategori Campaign</h5>
                    <p class="text-muted small mb-0">Edit informasi kategori campaign yang sudah ada.</p>
                </div>
                <a href="{{ route('admin.kategori-campaign') }}" wire:navigate class="btn btn-light border">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <form wire:submit="store">
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <label class="form-label d-block">Tipe Icon</label>
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="iconType" id="typeSelect" value="select"
                                wire:model.live="iconType">
                            <label class="btn btn-outline-primary" for="typeSelect">
                                <i class="bi bi-grid me-1"></i> Pilih Icon
                            </label>

                            <input type="radio" class="btn-check" name="iconType" id="typeUpload" value="upload"
                                wire:model.live="iconType">
                            <label class="btn btn-outline-primary" for="typeUpload">
                                <i class="bi bi-upload me-1"></i> Upload Gambar
                            </label>
                        </div>
                    </div>

                    @if ($iconType === 'upload')
                        <div class="col-md-12">
                            <x-admin.file-upload model="icon" label="Upload Icon" :preview="$icon
                                ? $icon->temporaryUrl()
                                : ($campaignCategory->icon_url
                                    ? $campaignCategory->icon_url
                                    : null)" />
                            <div class="form-text">Format: JPG, PNG. Maks: 2MB.</div>
                        </div>
                    @else
                        <div class="col-md-12">
                            <label class="form-label">Pilih Icon Bootstrap</label>
                            <input type="text" class="form-control mb-3"
                                placeholder="Cari icon (contoh: heart, user, money)..."
                                wire:model.live.debounce.300ms="searchIcon">

                            <div class="border rounded p-3 bg-light" style="max-height: 300px; overflow-y: auto;">
                                <div class="row row-cols-auto g-2 justify-content-center justify-content-md-start">
                                    @foreach ($this->filteredIcons as $biIcon)
                                        <div class="col">
                                            <button type="button"
                                                class="btn btn-outline-secondary d-flex align-items-center justify-content-center {{ $selectedIcon === $biIcon ? 'active bg-primary text-white' : '' }}"
                                                style="width: 50px; height: 50px;"
                                                wire:click="selectIcon('{{ $biIcon }}')">
                                                <i class="bi bi-{{ $biIcon }} fs-4"></i>
                                            </button>
                                        </div>
                                    @endforeach

                                    @if (empty($this->filteredIcons))
                                        <div class="col-12 text-center text-muted py-4">
                                            Icon tidak ditemukan
                                        </div>
                                    @elseif(empty($searchIcon) && count($bootstrapIcons) > 100)
                                        <div class="col-12 text-center text-muted py-2 small">
                                            Menampilkan 100 icon pertama. Gunakan pencarian untuk hasil lainnya.
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @error('selectedIcon')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror

                            @if ($selectedIcon)
                                <div class="mt-2">
                                    <span class="text-muted small">Icon terpilih: </span>
                                    <span class="badge bg-primary"><i class="bi bi-{{ $selectedIcon }} me-1"></i>
                                        {{ $selectedIcon }}</span>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="col-md-12">
                        <label for="name" class="form-label">Nama Kategori Campaign</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name"
                            id="name" placeholder="Masukan nama kategori campaign">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
                    <a href="{{ route('admin.kategori-campaign') }}" class="btn btn-light border px-4 fw-semibold"
                        wire:navigate>Batal</a>
                    <button type="submit" class="btn btn-primary text-white fw-semibold px-4"
                        wire:loading.attr="disabled" wire:target="store">
                        <span wire:loading.remove wire:target="store">
                            Simpan Perubahan <i class="bi bi-floppy-fill ms-2"></i>
                        </span>
                        <span wire:loading wire:target="store">
                            <div class="spinner-border spinner-border-sm" role="status"></div>
                        </span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
