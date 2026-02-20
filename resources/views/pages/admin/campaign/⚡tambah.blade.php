<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use App\Models\Campaign;
use App\Models\CampaignCategory;

new #[Layout('layouts.admin')] #[Title('Tambah Program')] class extends Component {
    use WithFileUploads;

    public $thumbnail;
    public string $title = '';
    public $category_id;
    public string $description = '';
    public $target_amount;
    public $start_date;
    public $end_date;

    public bool $is_emergency = false;
    public bool $is_priority = false;
    public bool $is_optimized = false;
    public bool $is_inisiatif = false;

    public $categories = [];

    public function mount()
    {
        $this->categories = CampaignCategory::all();
    }

    public function store(): void
    {
        $rules = [
            'thumbnail' => 'required|image|max:2048',
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:campaign_categories,id',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:1000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ];

        $messages = [
            'thumbnail.required' => 'Thumbnail wajib diisi.',
            'thumbnail.image' => 'Thumbnail harus berupa gambar.',
            'title.required' => 'Judul campaign wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'description.required' => 'Deskripsi wajib diisi.',
            'target_amount.required' => 'Target donasi wajib diisi.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.required' => 'Tanggal selesai wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
        ];

        $this->validate($rules, $messages);

        // simpan file
        $thumbnailPath = $this->thumbnail->store('campaigns', 'public');

        Campaign::create([
            'thumbnail' => $thumbnailPath,
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'category_id' => $this->category_id,
            'description' => $this->description,
            'target_amount' => $this->target_amount,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'is_emergency' => $this->is_emergency,
            'is_priority' => $this->is_priority,
            'is_optimized' => $this->is_optimized,
            'is_inisiatif' => $this->is_inisiatif,
            'collected_amount' => 0,
        ]);

        session()->flash('toast', [
            'type' => 'success',
            'message' => 'Campaign berhasil ditambahkan ✅',
        ]);
        $this->redirectRoute('admin.campaign', navigate: true);
    }
};
?>

<div>
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Tambah Campaign Baru</h5>
                    <p class="text-muted small mb-0">Buat program kebaikan untuk mulai menggalang donasi.</p>
                </div>
                <a href="{{ route('admin.campaign') }}" wire:navigate class="btn btn-light border">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <form wire:submit="store">
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <x-admin.file-upload model="thumbnail" label="Thumbnail Campaign" :preview="$thumbnail ? $thumbnail->temporaryUrl() : null" />
                    </div>

                    <div class="col-md-6">
                        <label for="title" class="form-label">Judul Campaign</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                            wire:model="title" id="title" placeholder="Masukan judul campaign">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="category_id" class="form-label">Kategori</label>
                        <div class="@error('category_id') is-invalid-tomselect @enderror">
                            <div wire:ignore>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                                    x-data="{
                                        tom: null,
                                        init() {
                                            this.tom = new TomSelect(this.$el, {
                                                placeholder: 'Pilih atau cari Kategori...',
                                                allowEmptyOption: false,
                                                maxOptions: 50,
                                                onDropdownOpen: function() {
                                                    this.clear(true);
                                                },
                                                onChange: (value) => {
                                                    $wire.set('category_id', value || null);
                                                }
                                            });
                                        }
                                    }">
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label for="description" class="form-label">Deskripsi</label>
                        <x-admin.text-editor model="description" id="description" />
                        @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <x-admin.input-rupiah model="target_amount" label="Target Donasi"
                            placeholder="Masukan target donasi" />
                    </div>

                    <div class="col-md-4">
                        <x-admin.input-calendar model="start_date" label="Tanggal Mulai" />
                    </div>

                    <div class="col-md-4">
                        <x-admin.input-calendar model="end_date" label="Tanggal Selesai" />
                    </div>

                    <div class="col-md-12">
                        <div class="card p-3 bg-light border-0">
                            <h6 class="mb-3 fw-bold">Opsi Klasifikasi Campaign</h6>
                            <div class="d-flex flex-wrap gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="is_emergency"
                                        id="is_emergency">
                                    <label class="form-check-label fw-semibold" for="is_emergency text-danger">
                                        Darurat & Mendesak
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="is_priority"
                                        id="is_priority">
                                    <label class="form-check-label fw-semibold" for="is_priority">
                                        Prioritas Kebaikan Hari ini
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="is_inisiatif"
                                        id="is_inisiatif">
                                    <label class="form-check-label fw-semibold text-success" for="is_inisiatif">
                                        Program Inisiatif Pilihan
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="is_optimized"
                                        id="is_optimized">
                                    <label class="form-check-label fw-semibold text-primary" for="is_optimized">
                                        Program Kebaikan (Optimasi)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
                    <a href="{{ route('admin.campaign') }}" class="btn btn-light border px-4 fw-semibold"
                        wire:navigate>Batal</a>
                    <button class="btn btn-primary text-white fw-semibold px-4" wire:loading.attr="disabled"
                        wire:target="store">
                        <span wire:loading.remove wire:target="store">
                            Simpan <i class="bi bi-floppy-fill ms-2"></i>
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
