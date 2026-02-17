<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Facades\View;

new #[Layout('layouts.app')] class extends Component {
    use WithFileUploads;

    public $name;
    public $email;
    public $phone;
    public $avatar;
    public $new_avatar;

    public function mount()
    {
        $user = Auth::user();
        if (!$user) {
            $this->redirect(route('login'), navigate: true);
            return;
        }

        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->avatar = $user->avatar_url;

        $seoData = new SEOData(title: 'Edit Profil | Inisiatif Kebaikan', robots: 'noindex, nofollow');
        View::share('seoData', $seoData);
    }

    public function updateProfile()
    {
        $user = Auth::user();

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'new_avatar' => 'nullable|image|max:1024', // 1MB Max
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ];

        if ($this->new_avatar) {
            $path = $this->new_avatar->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $user->update($data);

        session()->flash('success', 'Profil berhasil diperbarui.');
        $this->redirect(route('account.index'), navigate: true);
    }
};
?>

<div class="bg-gray-50 min-vh-100 pb-5">
    <x-app.navbar-secondary title="Edit Profil" :route="route('account.index')" />

    <div class="container-fluid py-4">
        <!-- Avatar Upload -->
        <div class="text-center mb-4">
            <div class="position-relative d-inline-block">
                @if ($new_avatar)
                    <img src="{{ $new_avatar->temporaryUrl() }}" class="rounded-circle object-fit-cover shadow-sm"
                        style="width: 100px; height: 100px;">
                @else
                    <img src="{{ $avatar }}" class="rounded-circle object-fit-cover shadow-sm"
                        style="width: 100px; height: 100px;">
                @endif
                <label for="avatar-upload"
                    class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center cursor-pointer shadow-sm"
                    style="width: 32px; height: 32px;">
                    <i class="bi bi-camera-fill small" wire:loading.remove wire:target="new_avatar"></i>
                    <div class="spinner-border spinner-border-sm text-white" style="width: 1rem; height: 1rem;"
                        role="status" wire:loading wire:target="new_avatar">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </label>
                <input type="file" id="avatar-upload" wire:model="new_avatar" class="d-none" accept="image/*">
            </div>
            @error('new_avatar')
                <span class="d-block text-danger small mt-2">{{ $message }}</span>
            @enderror
        </div>

        <form wire:submit.prevent="updateProfile">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nama Lengkap</label>
                        <input type="text" wire:model="name"
                            class="form-control rounded-3 py-2 fs-6 @error('name') is-invalid @enderror">
                        @error('name')
                            <span class="text-danger extra-small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Email</label>
                        <input type="email" wire:model="email"
                            class="form-control rounded-3 py-2 fs-6 @error('email') is-invalid @enderror">
                        @error('email')
                            <span class="text-danger extra-small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nomor WhatsApp</label>
                        <input type="text" wire:model="phone"
                            class="form-control rounded-3 py-2 fs-6 @error('phone') is-invalid @enderror">
                        @error('phone')
                            <span class="text-danger extra-small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <button type="submit" class="detail-cta__button">
                <span wire:loading.remove>Simpan Perubahan</span>
                <span wire:loading>Menyimpan...</span>
            </button>
        </form>
    </div>
</div>
