<?php
use App\Models\Campaign;
use App\Models\Donation;
use Livewire\Component;
use Livewire\Attributes\Computed;
?>

<div>
    <?php if (isset($component)) { $__componentOriginal87a36371cd3c2919bc8ca9c86f23c76e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal87a36371cd3c2919bc8ca9c86f23c76e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.navbar-secondary','data' => ['title' => 'Detail Program']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.navbar-secondary'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Detail Program']); ?>
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

    
    <section class="detail-image-section">
        <img src="<?php echo e($campaign->thumbnail_url); ?>" alt="<?php echo e($campaign->title); ?>">
    </section>

    
    <section class="detail-info-section">
        <div class="container-fluid">
            
            <div class="detail-badges">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($campaign->is_emergency): ?>
                    <span class="detail-badge detail-badge--urgent">DARURAT</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($campaign->category): ?>
                    <span class="detail-badge"><?php echo e(strtoupper($campaign->category->name)); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <h1 class="detail-title"><?php echo e($campaign->title); ?></h1>

            
            <div class="detail-progress">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: <?php echo e($this->progress); ?>%"></div>
                </div>
            </div>

            
            <div class="detail-stats">
                <div class="detail-stats__left">
                    <span class="detail-stats__amount">Rp
                        <?php echo e(number_format($campaign->collected_amount, 0, ',', '.')); ?></span>
                    <span class="detail-stats__target">terkumpul dari Rp
                        <?php echo e(number_format($campaign->target_amount, 0, ',', '.')); ?></span>
                </div>
                <div class="detail-stats__right">
                    <span class="detail-stats__inline"><strong><?php echo e($this->daysLeft); ?> Hari</strong>
                        <small>tersisa</small></span>
                    <span class="detail-stats__inline"><strong><?php echo e($this->donorCount); ?></strong>
                        <small>Donatur</small></span>
                </div>
            </div>
        </div>
    </section>

    
    <section class="detail-organizer-section">
        <div class="container-fluid">
            <a href="#" class="detail-organizer">
                <div class="detail-organizer__avatar">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($campaign->fundraiser): ?>
                        <img src="<?php echo e($campaign->fundraiser->logo_url); ?>"
                            alt="<?php echo e($campaign->fundraiser->foundation_name); ?>">
                    <?php else: ?>
                        <span class="detail-organizer__initials">IK</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="detail-organizer__info">
                    <span
                        class="detail-organizer__name"><?php echo e($campaign->fundraiser?->foundation_name ?? 'Inisiatif Kebaikan'); ?></span>
                    <span class="detail-organizer__verified">
                        Terverifikasi <i class="bi bi-patch-check-fill"></i>
                    </span>
                </div>
                <i class="bi bi-chevron-right detail-organizer__arrow"></i>
            </a>
        </div>
    </section>

    
    <section class="detail-tabs-section">
        <div class="detail-tabs">
            <button wire:click="switchTab('cerita')"
                class="detail-tabs__item <?php echo e($activeTab === 'cerita' ? 'active' : ''); ?>">
                Cerita
            </button>
            <button wire:click="switchTab('update')"
                class="detail-tabs__item <?php echo e($activeTab === 'update' ? 'active' : ''); ?>">
                Update
            </button>
            <button wire:click="switchTab('donatur')"
                class="detail-tabs__item <?php echo e($activeTab === 'donatur' ? 'active' : ''); ?>">
                Donatur
            </button>
            <button wire:click="switchTab('doa')" class="detail-tabs__item <?php echo e($activeTab === 'doa' ? 'active' : ''); ?>">
                Doa
            </button>
        </div>
    </section>

    
    <section class="detail-content-section">
        <div class="container-fluid">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeTab === 'cerita'): ?>
                <div class="detail-content__story">
                    <?php echo $campaign->description; ?>

                </div>
            <?php elseif($activeTab === 'update'): ?>
                <div class="update-list pb-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->campaignUpdates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $update): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="card border mb-3 rounded-3 overflow-hidden" <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processElementKey('update-{{ $update->id }}', get_defined_vars()); ?>wire:key="update-<?php echo e($update->id); ?>"
                            x-data="{ open: <?php echo e($index === 0 ? 'true' : 'false'); ?> }">

                            
                            <div class="card-header bg-white border-0 p-3 cursor-pointer" @click="open = !open"
                                role="button">
                                <div class="d-flex align-items-center gap-3">
                                    
                                    <div class="d-flex flex-column align-items-center justify-content-center bg-primary rounded-3 px-2 py-2 shadow-sm"
                                        style="width: 50px; height: 50px;">
                                        <span class="fw-bold text-white lh-1" style="font-size: 18px;">
                                            <?php echo e(optional($update->published_at)->format('d') ?? $update->created_at->format('d')); ?>

                                        </span>
                                        <span class="fw-medium text-white-50 lh-1 text-uppercase"
                                            style="font-size: 10px;">
                                            <?php echo e(optional($update->published_at)->translatedFormat('M') ?? $update->created_at->translatedFormat('M')); ?>

                                        </span>
                                    </div>

                                    
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold text-dark mb-1" style="font-size: 15px;">
                                            <?php echo e($update->title); ?></h6>
                                        <span class="fw-bold text-black" style="font-size: 13px;">
                                            <i class="bi bi-clock me-1 text-primary"></i>
                                            <?php echo e(optional($update->published_at)->diffForHumans() ?? $update->created_at->diffForHumans()); ?>

                                        </span>
                                    </div>

                                    
                                    <div class="text-secondary transition-all duration-300"
                                        :style="open ? 'transform: rotate(180deg)' : ''">
                                        <i class="bi bi-chevron-down"></i>
                                    </div>
                                </div>
                            </div>

                            
                            <div x-show="open" x-collapse class="border-top border-light bg-light bg-opacity-10">
                                <div class="card-body p-3 p-md-4">
                                    <div class="text-dark mb-3" style="font-size: 14px; line-height: 1.6;">
                                        <?php echo $update->content; ?>

                                    </div>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($update->image_url): ?>
                                        <div class="rounded-3 overflow-hidden border border-light">
                                            <img src="<?php echo e($update->image_url); ?>" alt="<?php echo e($update->title); ?>"
                                                class="img-fluid w-100 object-fit-cover">
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <div class="detail-content__empty text-center py-5">
                            <div class="mb-3 text-muted opacity-50">
                                <i class="bi bi-newspaper display-1"></i>
                            </div>
                            <p class="text-muted fw-medium">Belum ada update terbaru untuk program ini.</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php elseif($activeTab === 'donatur'): ?>
                
                <div class="donor-filter">
                    <button wire:click="switchDonorSort('terbaru')"
                        class="donor-filter__item <?php echo e($donorSort === 'terbaru' ? 'active' : ''); ?>">
                        Terbaru
                    </button>
                    <button wire:click="switchDonorSort('terbesar')"
                        class="donor-filter__item <?php echo e($donorSort === 'terbesar' ? 'active' : ''); ?>">
                        Terbesar
                    </button>
                </div>

                <div class="detail-content__donors" wire:loading.class="opacity-50">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->donors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $donation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="donor-item">
                            <div class="donor-item__avatar">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div class="donor-item__info">
                                <span class="donor-item__name">
                                    <?php echo e($donation->is_anonymous ? 'Hamba Allah' : $donation->donor_name ?? 'Anonim'); ?>

                                </span>
                                <span class="donor-item__amount">
                                    Rp <?php echo e(number_format($donation->amount, 0, ',', '.')); ?>

                                </span>
                            </div>
                            <span class="donor-item__date">
                                <?php echo e($donation->paid_at?->diffForHumans() ?? $donation->created_at->diffForHumans()); ?>

                            </span>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <div class="detail-content__empty">
                            <i class="bi bi-people"></i>
                            <p>Belum ada donatur untuk program ini.</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php elseif($activeTab === 'doa'): ?>
                <div class="row g-3 prayer-list">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->prayers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prayer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="col-12" <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processElementKey('prayer-{{ $prayer->id }}', get_defined_vars()); ?>wire:key="prayer-<?php echo e($prayer->id); ?>">
                            <?php if (isset($component)) { $__componentOriginalf643c5c6d6ac5c8778b56630fbdbd5d8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf643c5c6d6ac5c8778b56630fbdbd5d8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.prayer-card','data' => ['prayer' => $prayer]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.prayer-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['prayer' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($prayer)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf643c5c6d6ac5c8778b56630fbdbd5d8)): ?>
<?php $attributes = $__attributesOriginalf643c5c6d6ac5c8778b56630fbdbd5d8; ?>
<?php unset($__attributesOriginalf643c5c6d6ac5c8778b56630fbdbd5d8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf643c5c6d6ac5c8778b56630fbdbd5d8)): ?>
<?php $component = $__componentOriginalf643c5c6d6ac5c8778b56630fbdbd5d8; ?>
<?php unset($__componentOriginalf643c5c6d6ac5c8778b56630fbdbd5d8); ?>
<?php endif; ?>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <div class="detail-content__empty text-center py-5">
                            <div class="mb-3 text-muted opacity-50">
                                <i class="bi bi-chat-heart display-1"></i>
                            </div>
                            <p class="text-muted fw-medium">Belum ada doa untuk program ini.</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </section>

    
    <div class="detail-cta">
        <a href="<?php echo e(route('donation.amount', $campaign->slug)); ?>" wire:navigate class="detail-cta__button">
            Donasi Sekarang <i class="bi bi-arrow-right"></i>
        </a>
    </div>

        <?php
        $__scriptKey = '3763537437-0';
        ob_start();
    ?>
        <script>
            $wire.on('scroll-top', () => {
                const tabs = document.querySelector('.detail-tabs-section');
                if (tabs) {
                    tabs.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        </script>
        <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?>
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/377b04b9.blade.php ENDPATH**/ ?>