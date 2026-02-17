<nav class="navbar navbar-inisiatif" x-data="{ searchOpen: false }" x-init="$watch('searchOpen', value => document.body.style.overflow = value ? 'hidden' : '')">
    <div class="container-fluid">
        <div class="w-100 d-flex align-items-center justify-content-between">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Bootstrap" height="32">
            </a>
            <div>
                <button class="btn btn-secondary btn-search"
                    @click="searchOpen = true; $nextTick(() => $refs.searchInput.focus())">
                    <i class="bi bi-search"></i>
                </button>
                <div class="search-backdrop" x-show="searchOpen" x-transition.opacity.duration.300ms
                    @click="searchOpen = false">
                </div>
                <div class="search-overlay" :class="{ 'active': searchOpen }">
                    <x-app.navbar-secondary title="Cari Program" :close-search="true" class="flex-row-reverse" />
                    <div class="container-fluid search-container">
                        <form class="search-form" action="#" method="GET">
                            <div class="search-input-wrapper">
                                <i class="bi bi-search search-icon"></i>
                                <input type="text" class="search-input" x-ref="searchInput" name="q"
                                    placeholder="Cari program donasi, kategori, atau organisasi..." autocomplete="off">
                            </div>
                        </form>
                        <div class="search-suggestions">
                            <p class="suggestions-title">Pencarian Populer</p>
                            <div class="suggestions-tags">
                                <a href="#" class="suggestion-tag">Banjir</a>
                                <a href="#" class="suggestion-tag">Pendidikan</a>
                                <a href="#" class="suggestion-tag">Kesehatan</a>
                                <a href="#" class="suggestion-tag">Zakat</a>
                                <a href="#" class="suggestion-tag">Masjid</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
