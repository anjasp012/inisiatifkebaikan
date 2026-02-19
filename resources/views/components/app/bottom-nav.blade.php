<nav class="bottom-nav">
    <a href="{{ route('home') }}" wire:navigate class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
        <i class="bi {{ request()->routeIs('home') ? 'bi-heart-fill' : 'bi-heart' }}"></i>
        <span>Donasi</span>
    </a>

    <a href="{{ Auth::check() && Auth::user()->role === 'fundraiser' ? route('fundraiser.dashboard') : route('fundraiser.daftar') }}"
        wire:navigate
        class="nav-item {{ request()->is('fundraiser*') || request()->routeIs('fundraiser.*') ? 'active' : '' }}">
        <i
            class="bi {{ request()->is('fundraiser*') || request()->routeIs('fundraiser.*') ? 'bi-plus-circle-fill' : 'bi-plus-circle' }}"></i>
        <span>Galang Dana</span>
    </a>

    <a href="{{ route('donation.index') }}" wire:navigate
        class="nav-item {{ request()->is('donasi') || request()->routeIs('donation.index') ? 'active' : '' }}">
        <i
            class="bi {{ request()->is('donasi') || request()->routeIs('donation.index') ? 'bi-clock-fill' : 'bi-clock' }}"></i>
        <span>Donasi Saya</span>
    </a>

    <a href="{{ route('distribution.index') }}" wire:navigate
        class="nav-item {{ request()->is('laporan*') || request()->routeIs('distribution.*') ? 'active' : '' }}">
        <i
            class="bi {{ request()->is('laporan*') || request()->routeIs('distribution.*') ? 'bi-file-earmark-bar-graph-fill' : 'bi-file-earmark-bar-graph' }}"></i>
        <span>Laporan</span>
    </a>

    <a href="{{ Auth::check() ? (Auth::user()->role === 'admin' ? route('admin.dashboard') : route('account.index')) : route('login') }}"
        wire:navigate
        class="nav-item {{ request()->is('akun*') || request()->routeIs('account.*') || request()->routeIs('login') || request()->routeIs('register') || request()->routeIs('verification') ? 'active' : '' }}">
        <i
            class="bi {{ request()->is('akun*') || request()->routeIs('account.*') || request()->routeIs('login') || request()->routeIs('register') || request()->routeIs('verification') ? 'bi-person-fill' : 'bi-person' }}"></i>
        <span>{{ Auth::check() ? 'Akun' : 'Masuk' }}</span>
    </a>
</nav>
