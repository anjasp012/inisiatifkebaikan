<nav class="bottom-nav">
    <a href="{{ route('home') }}" wire:navigate wire:current="active" class="nav-item">
        <i class="bi {{ request()->routeIs('home') ? 'bi-heart-fill' : 'bi-heart' }}"></i>
        <span>Donasi</span>
    </a>

    <a href="#" class="nav-item">
        <i class="bi bi-plus-circle"></i>
        <span>Galang Dana</span>
    </a>

    <a href="{{ route('donasi-saya') }}" wire:navigate wire:current="active" class="nav-item">
        <i class="bi {{ request()->routeIs('donasi-saya') ? 'bi-list-ul' : 'bi-list-ul' }}"></i>
        <span>Donasi Saya</span>
    </a>

    <a href="{{ route('distribution.index') }}" wire:navigate wire:current="active" class="nav-item">
        <i
            class="bi {{ request()->routeIs('distribution.index') ? 'bi-file-earmark-bar-graph-fill' : 'bi-file-earmark-bar-graph' }}"></i>
        <span>Laporan</span>
    </a>

    <a href="{{ Auth::check() ? (Auth::user()->role === 'admin' ? route('admin.dashboard') : route('account.index')) : route('login') }}"
        wire:navigate wire:current="active" class="nav-item">
        <i
            class="bi {{ request()->routeIs('account.index') || request()->routeIs('login') ? 'bi-person-fill' : 'bi-person' }}"></i>
        <span>{{ Auth::check() ? 'Akun' : 'Masuk' }}</span>
    </a>
</nav>
