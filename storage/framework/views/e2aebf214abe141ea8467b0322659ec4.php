<nav class="navbar navbar-inisiatif" x-data="{ searchOpen: false }" x-init="$watch('searchOpen', value => document.body.style.overflow = value ? 'hidden' : '')">
    <div class="container-fluid">
        <div class="w-100 d-flex align-items-center justify-content-between">
            <a class="navbar-brand" href="#">
                <img src="<?php echo e(asset('assets/images/logo.png')); ?>" alt="Bootstrap" height="32">
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
                    <?php if (isset($component)) { $__componentOriginal87a36371cd3c2919bc8ca9c86f23c76e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal87a36371cd3c2919bc8ca9c86f23c76e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.navbar-secondary','data' => ['title' => 'Cari Program','closeSearch' => true,'class' => 'flex-row-reverse']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.navbar-secondary'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Cari Program','close-search' => true,'class' => 'flex-row-reverse']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal87a36371cd3c2919bc8ca9c86f23c76e)): ?>
<?php $attributes = $__attributesOriginal87a36371cd3c2919bc8ca9c86f23c76e; ?>
<?php unset($__attributesOriginal87a36371cd3c2919bc8ca9c86f23c76e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal87a36371cd3c2919bc8ca9c86f23c76e)): ?>
<?php $component = $__componentOriginal87a36371cd3c2919bc8ca9c86f23c76e; ?>
<?php unset($__componentOriginal87a36371cd3c2919bc8ca9c86f23c76e); ?>
<?php endif; ?>
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
<?php /**PATH C:\laragon\www\inisiatif\resources\views/components/app/navbar-main.blade.php ENDPATH**/ ?>