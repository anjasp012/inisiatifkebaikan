<aside class="col-8 sidebar" :class="sidebarOpen ? 'sidebar-open' : ''">
    <a href="#" wire:navigate class="sidebar-brand">
        <img loading="lazy" src="{{ asset('assets/images/logo-dashboard.png') }}" alt="logo">
    </a>
    <ul class="sidebar-items p-3">
        <li class="sidebar-label px-3 text-uppercase fw-bold mb-2">Utama</li>
        <x-admin.sidebar-item href="{{ route('admin.dashboard') }}">
            <i class="bi bi-speedometer2"></i>
            Dashboard
        </x-admin.sidebar-item>

        <li class="sidebar-label px-3 text-uppercase fw-bold mb-2 mt-3">Program</li>
        <x-admin.sidebar-item href="{{ route('admin.kategori-campaign') }}">
            <i class="bi bi-tags"></i>
            Kategori Campaign
        </x-admin.sidebar-item>
        <x-admin.sidebar-item href="{{ route('admin.campaign') }}">
            <i class="bi bi-megaphone"></i>
            Manajemen Campaign
        </x-admin.sidebar-item>

        <x-admin.sidebar-item href="{{ route('admin.distribusi') }}">
            <i class="bi bi-box2-heart"></i>
            Kabar Penyaluran
        </x-admin.sidebar-item>

        <li class="sidebar-label px-3 text-uppercase fw-bold mb-2 mt-3">Keuangan</li>
        <x-admin.sidebar-item href="{{ route('admin.donasi') }}">
            <i class="bi bi-cash-coin"></i>
            Manajemen Donasi
            @php
                $pendingDonations = \App\Models\Donation::where('status', 'pending')->count();
            @endphp
            @if ($pendingDonations > 0)
                <span class="badge rounded-pill bg-danger ms-auto">
                    {{ $pendingDonations > 9 ? '9+' : $pendingDonations }}
                </span>
            @endif
        </x-admin.sidebar-item>
        <x-admin.sidebar-item href="{{ route('admin.pencairan') }}">
            <i class="bi bi-wallet2"></i>
            Withdrawal
            @php
                $pendingWithdrawals = \App\Models\Withdrawal::where('status', 'pending')->count();
            @endphp
            @if ($pendingWithdrawals > 0)
                <span class="badge rounded-pill bg-danger ms-auto">
                    {{ $pendingWithdrawals > 9 ? '9+' : $pendingWithdrawals }}
                </span>
            @endif
        </x-admin.sidebar-item>
        <x-admin.sidebar-item href="{{ route('admin.bank') }}">
            <i class="bi bi-bank"></i>
            Manajemen Bank
        </x-admin.sidebar-item>

        <li class="sidebar-label px-3 text-uppercase fw-bold mb-2 mt-3">Stakeholder</li>
        <x-admin.sidebar-item href="{{ route('admin.fundraiser') }}">
            <i class="bi bi-people"></i>
            Mitra Fundraiser
            @php
                $pendingFundraisers = \App\Models\Fundraiser::where('status', 'pending')->count();
            @endphp
            @if ($pendingFundraisers > 0)
                <span class="badge rounded-pill bg-danger ms-auto">
                    {{ $pendingFundraisers > 9 ? '9+' : $pendingFundraisers }}
                </span>
            @endif
        </x-admin.sidebar-item>
        <x-admin.sidebar-item href="{{ route('admin.donatur') }}">
            <i class="bi bi-person-heart"></i>
            Data User
        </x-admin.sidebar-item>

        <li class="sidebar-label px-3 text-uppercase fw-bold mb-2 mt-3">Konten & Sistem</li>
        <x-admin.sidebar-item href="{{ route('admin.artikel') }}">
            <i class="bi bi-newspaper"></i>
            Manajemen Artikel
        </x-admin.sidebar-item>
        <x-admin.sidebar-item href="{{ route('admin.page') }}">
            <i class="bi bi-file-earmark-text"></i>
            Manajemen Halaman
        </x-admin.sidebar-item>
        <x-admin.sidebar-item href="{{ route('admin.settings') }}">
            <i class="bi bi-gear"></i>
            Pengaturan
        </x-admin.sidebar-item>
    </ul>
    <div class="sidebar-footer">
        <ul class="sidebar-items p-3 mt-auto">
            <livewire:auth.logout />
        </ul>
    </div>
</aside>
