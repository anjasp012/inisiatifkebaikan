<nav class="navbar navbar-inisiatif">
    <div class="container-fluid">
        <div class="w-100 d-flex align-items-center {{ @$class }} justify-content-between">
            @if (@$closeSearch)
                <button @click="searchOpen = false" class="btn btn-secondary btn-search">
                    <i class="bi bi-x"></i>
                </button>
            @else
                <a href="{{ $route ?? route('home') }}" wire:navigate class="btn btn-secondary btn-search">
                    <i class="bi bi-arrow-left"></i>
                </a>
            @endif
            <span class="fw-semibold">{{ $title }}</span>
        </div>
    </div>
</nav>
