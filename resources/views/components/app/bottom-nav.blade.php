<nav class="bottom-nav">
    <a href="{{ route('home') }}" wire:navigate class="nav-item" wire:current.exact="active">
        <i class="bi bi-heart"></i>
        <span>Donasi</span>
    </a>

    <a href="{{ Auth::check() && Auth::user()->role === 'fundraiser' ? route('fundraiser.dashboard') : route('fundraiser.daftar') }}"
        wire:navigate class="nav-item" wire:current.prefix="active">
        <i class="bi bi-plus-circle"></i>
        <span>Galang Dana</span>
    </a>

    <a href="{{ route('donation.index') }}" wire:navigate class="nav-item" wire:current.prefix="active">
        <i class="bi bi-clock"></i>
        <span>Donasi Saya</span>
    </a>

    <a href="{{ route('distribution.index') }}" wire:navigate class="nav-item" wire:current.prefix="active">
        <i class="bi bi-file-earmark-bar-graph"></i>
        <span>Laporan</span>
    </a>

    <a href="{{ Auth::check() ? (Auth::user()->role === 'admin' ? route('admin.dashboard') : route('account.index')) : route('login') }}"
        wire:navigate class="nav-item" wire:current.prefix="active">
        <i class="bi bi-person"></i>
        <span>{{ Auth::check() ? 'Akun' : 'Masuk' }}</span>
    </a>
</nav>
