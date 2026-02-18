 <?php
 
 use Livewire\Component;
 use Livewire\Attributes\Layout;
 use Livewire\Attributes\Title;
 use Livewire\WithFileUploads;
 use Illuminate\Support\Str;
 use App\Models\Campaign;
 use App\Models\CampaignCategory;
 
 new #[Layout('layouts.admin')] #[Title('Ubah Program')] class extends Component {
     use WithFileUploads;
 
     public Campaign $campaign;
 
     public $thumbnail;
     public string $title = '';
     public $category_id;
     public string $description = '';
     public $target_amount;
     public $start_date;
     public $end_date;
 
     public bool $is_emergency = false;
     public bool $is_priority = false;
     public bool $is_initiative = false;
     public bool $is_optimized = false;
 
     public $categories = [];
 
     public function mount(Campaign $campaign)
     {
         $this->campaign = $campaign;
         $this->title = $campaign->title;
         $this->category_id = $campaign->category_id;
         $this->description = $campaign->description;
         $this->target_amount = $campaign->target_amount;
         // Ensure dates are in Y-m-d format for input[type=date]
         $this->start_date = \Carbon\Carbon::parse($campaign->start_date)->format('Y-m-d');
         $this->end_date = \Carbon\Carbon::parse($campaign->end_date)->format('Y-m-d');
 
         $this->is_emergency = (bool) $campaign->is_emergency;
         $this->is_priority = (bool) $campaign->is_priority;
         $this->is_initiative = (bool) $campaign->is_initiative;
         $this->is_optimized = (bool) $campaign->is_optimized;
 
         $this->categories = CampaignCategory::all();
     }
 
     public function update(): void
     {
         $rules = [
             'thumbnail' => 'nullable|image|max:2048',
             'title' => 'required|string|max:255',
             'category_id' => 'required|exists:campaign_categories,id',
             'description' => 'required|string',
             'target_amount' => 'required|numeric|min:1000',
             'start_date' => 'required|date',
             'end_date' => 'required|date|after_or_equal:start_date',
         ];
 
         $messages = [
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
 
         if ($this->thumbnail) {
             // Delete old thumbnail if exists
             if ($this->campaign->thumbnail && file_exists(public_path($this->campaign->thumbnail))) {
                 unlink(public_path($this->campaign->thumbnail));
             }
             $thumbnailPath = $this->thumbnail->store('campaigns', 'public');
         } else {
             $thumbnailPath = $this->campaign->thumbnail;
         }
 
         $this->campaign->update([
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
             'is_initiative' => $this->is_initiative,
             'is_optimized' => $this->is_optimized,
         ]);
 
         session()->flash('toast', [
             'type' => 'success',
             'message' => 'Campaign berhasil diperbarui ✅',
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
                     <h5 class="fw-bold mb-1">Ubah Campaign</h5>
                     <p class="text-muted small mb-0">Edit informasi program campaign yang sudah ada.</p>
                 </div>
                 <div class="d-flex gap-2">
                     <a href="{{ route('admin.campaign.updates', $campaign) }}" wire:navigate
                         class="btn btn-info text-white">
                         <i class="bi bi-newspaper me-1"></i> Kelola Update
                     </a>
                     <a href="{{ route('admin.campaign') }}" wire:navigate class="btn btn-light border">
                         <i class="bi bi-arrow-left me-1"></i> Kembali
                     </a>
                 </div>
             </div>
         </div>
         <div class="card-body">
             <form wire:submit="update">
                 <div class="row g-3 mb-3">
                     <div class="col-md-12">
                         <x-admin.file-upload model="thumbnail" label="Thumbnail Campaign" :preview="$thumbnail
                             ? $thumbnail->temporaryUrl()
                             : ($campaign->thumbnail
                                 ? asset('storage/' . $campaign->thumbnail)
                                 : null)" />
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
                                                 placeholder: 'Cari Kategori...',
                                                 allowEmptyOption: true,
                                                 maxOptions: 50,
                                                 onChange: (value) => {
                                                     $wire.set('category_id', value || null);
                                                 }
                                             });
                                         }
                                     }">
                                     <option value="">Pilih Kategori</option>
                                     @foreach ($categories as $category)
                                         <option value="{{ $category->id }}"
                                             {{ $category->id == $category_id ? 'selected' : '' }}>{{ $category->name }}
                                         </option>
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
                             <h6 class="mb-3">Opsi Tambahan</h6>
                             <div class="d-flex flex-wrap gap-4">
                                 <div class="form-check">
                                     <input class="form-check-input" type="checkbox" wire:model="is_emergency"
                                         id="is_emergency">
                                     <label class="form-check-label fw-semibold" for="is_emergency">Darurat &
                                         Mendesak</label>
                                 </div>
                                 <div class="form-check">
                                     <input class="form-check-input" type="checkbox" wire:model="is_priority"
                                         id="is_priority">
                                     <label class="form-check-label fw-semibold" for="is_priority">Prioritas Kebaikan
                                         Hari ini</label>
                                 </div>
                                 <div class="form-check">
                                     <input class="form-check-input" type="checkbox" wire:model="is_optimized"
                                         id="is_optimized">
                                     <label class="form-check-label fw-semibold text-primary" for="is_optimized">
                                         Optimasi (Fee 15%)
                                     </label>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>

                 <div class="d-flex justify-content-end gap-2 pt-3 mt-4 border-top">
                     <a href="{{ route('admin.campaign') }}" class="btn btn-light border px-4 fw-semibold"
                         wire:navigate>Batal</a>
                     <button type="submit" class="btn btn-primary text-white fw-semibold px-4"
                         wire:loading.attr="disabled" wire:target="update">
                         <span wire:loading.remove wire:target="update">
                             Simpan Perubahan <i class="bi bi-floppy-fill ms-2"></i>
                         </span>
                         <span wire:loading wire:target="update">
                             <div class="spinner-border spinner-border-sm" role="status"></div>
                             Menyimpan...
                         </span>
                     </button>
                 </div>
             </form>
         </div>
     </div>
 </div>
