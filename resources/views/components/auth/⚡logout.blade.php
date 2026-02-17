<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

new class extends Component {
    #[On('doLogout')]
    public function store()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        $this->redirectRoute('home', navigate: true);
    }
};
?>

<div>
    <li class="sidebar-item">
        <a wire:prevent wire:click="store" wire:navigate wire:current.exact="active" class="sidebar-link">
            <i class="bi bi-box-arrow-right"></i>
            Logout
        </a>
    </li>
</div>
