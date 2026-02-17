<?php
use App\Models\Campaign;
use App\Models\CampaignCategory;
use App\Models\Distribution;
use App\Models\Article;
use App\Models\Donation;
use Livewire\Component;
use Livewire\Attributes\Computed;
?>

<div>
    <?php if (isset($component)) { $__componentOriginal1e0c70208ea19ba38279ca83d1c697e1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1e0c70208ea19ba38279ca83d1c697e1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.navbar-main','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.navbar-main'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1e0c70208ea19ba38279ca83d1c697e1)): ?>
<?php $attributes = $__attributesOriginal1e0c70208ea19ba38279ca83d1c697e1; ?>
<?php unset($__attributesOriginal1e0c70208ea19ba38279ca83d1c697e1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1e0c70208ea19ba38279ca83d1c697e1)): ?>
<?php $component = $__componentOriginal1e0c70208ea19ba38279ca83d1c697e1; ?>
<?php unset($__componentOriginal1e0c70208ea19ba38279ca83d1c697e1); ?>
<?php endif; ?>
    <section class="hero-section">
        <div class="container-fluid">
            <div class="owl-carousel owl-theme owl-hero" wire:ignore>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->campaignSliders(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <div class="item">
                        <div class="hero-slide">
                            <div class="hero-image">
                                <img src="<?php echo e($campaign->thumbnail_url); ?>" alt="">
                                <div class="hero-gradient"></div>
                            </div>
                            <div class="hero-content">
                                <div class="hero-tag">
                                    <?php echo e($campaign->category->name); ?>

                                </div>
                                <h1><?php echo e($campaign->title); ?></h1>
                                <p><?php echo e($campaign->description); ?></p>
                                <div class="d-flex gap-4 align-items-center">
                                    <a href="<?php echo e(route('donation.amount', $campaign->slug)); ?>"
                                        class="btn btn-sm btn-primary text-nowrap py-2 px-4 rounded-pill fw-semibold">Donasi
                                        Sekarang <i class="bi bi-arrow-right ms-2"></i></a>
                                    <div class="hero-info">Target: <br> Rp
                                        <?php echo e(number_format($campaign->target_amount, 0, ',', '.')); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </div>
    </section>

    <section class="category-section">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-4" bis_skin_checked="1">
                <h2 class="section-title">Mau berbuat baik apa hari ini?</h2>
            </div>
            <div class="row g-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->categories(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <div class="col-3">
                        <a href="<?php echo e(route('campaign.index', ['category' => $category->slug])); ?>" class="category-item">
                            <div class="category-icon">
                                <i class="<?php echo e($category->icon); ?>"></i>
                            </div>
                            <span><?php echo e($category->name); ?></span>
                        </a>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <div class="col-3">
                    <a href="<?php echo e(route('category.index')); ?>" class="category-item">
                        <div class="category-icon">
                            <i class="bi bi-three-dots"></i>
                        </div>
                        <span>Lainnya</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="urgent-campaigns-section">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-4" bis_skin_checked="1">
                <h2 class="section-title">Kebutuhan Darurat &amp; Mendesak</h2>
                <a class="link-more" href="">
                    Lihat Semua
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
            <div class="owl-carousel owl-theme owl-inisiatif" wire:ignore>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->campaignUrgent(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <div class="item">
                        <?php if (isset($component)) { $__componentOriginalabd5299e7c64663b70bcc905a2f57b0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalabd5299e7c64663b70bcc905a2f57b0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.campaign-card','data' => ['campaign' => $campaign]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.campaign-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['campaign' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($campaign)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalabd5299e7c64663b70bcc905a2f57b0a)): ?>
<?php $attributes = $__attributesOriginalabd5299e7c64663b70bcc905a2f57b0a; ?>
<?php unset($__attributesOriginalabd5299e7c64663b70bcc905a2f57b0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalabd5299e7c64663b70bcc905a2f57b0a)): ?>
<?php $component = $__componentOriginalabd5299e7c64663b70bcc905a2f57b0a; ?>
<?php unset($__componentOriginalabd5299e7c64663b70bcc905a2f57b0a); ?>
<?php endif; ?>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </div>
    </section>

    <section class="priority-banner-section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <?php if (isset($component)) { $__componentOriginalf58ec291aec56abd1af30c1f877ed9c7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf58ec291aec56abd1af30c1f877ed9c7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.priority-card','data' => ['campaign' => $this->priorityBannerCampaign()]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.priority-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['campaign' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($this->priorityBannerCampaign())]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf58ec291aec56abd1af30c1f877ed9c7)): ?>
<?php $attributes = $__attributesOriginalf58ec291aec56abd1af30c1f877ed9c7; ?>
<?php unset($__attributesOriginalf58ec291aec56abd1af30c1f877ed9c7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf58ec291aec56abd1af30c1f877ed9c7)): ?>
<?php $component = $__componentOriginalf58ec291aec56abd1af30c1f877ed9c7; ?>
<?php unset($__componentOriginalf58ec291aec56abd1af30c1f877ed9c7); ?>
<?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="inisiatif-campaigns-section">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-4" bis_skin_checked="1">
                <h2 class="section-title">Program Inisiatif Pilihan</h2>
                <a class="link-more" href="">
                    Lihat Semua
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
            <div class="owl-carousel owl-theme owl-inisiatif" wire:ignore>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->campaignInisiatif(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <div class="item">
                        <?php if (isset($component)) { $__componentOriginalabd5299e7c64663b70bcc905a2f57b0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalabd5299e7c64663b70bcc905a2f57b0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.campaign-card','data' => ['campaign' => $campaign]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.campaign-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['campaign' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($campaign)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalabd5299e7c64663b70bcc905a2f57b0a)): ?>
<?php $attributes = $__attributesOriginalabd5299e7c64663b70bcc905a2f57b0a; ?>
<?php unset($__attributesOriginalabd5299e7c64663b70bcc905a2f57b0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalabd5299e7c64663b70bcc905a2f57b0a)): ?>
<?php $component = $__componentOriginalabd5299e7c64663b70bcc905a2f57b0a; ?>
<?php unset($__componentOriginalabd5299e7c64663b70bcc905a2f57b0a); ?>
<?php endif; ?>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </div>
    </section>
    <section class="choice-campaigns-section">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-4" bis_skin_checked="1">
                <h2 class="section-title">Program Kebaikan</h2>
                <a class="link-more" href="">
                    Lihat Semua
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
            <div class="row g-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->campaignChoice(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <div class="col-12">
                        <?php if (isset($component)) { $__componentOriginalabd5299e7c64663b70bcc905a2f57b0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalabd5299e7c64663b70bcc905a2f57b0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.campaign-card','data' => ['campaign' => $campaign]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.campaign-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['campaign' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($campaign)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalabd5299e7c64663b70bcc905a2f57b0a)): ?>
<?php $attributes = $__attributesOriginalabd5299e7c64663b70bcc905a2f57b0a; ?>
<?php unset($__attributesOriginalabd5299e7c64663b70bcc905a2f57b0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalabd5299e7c64663b70bcc905a2f57b0a)): ?>
<?php $component = $__componentOriginalabd5299e7c64663b70bcc905a2f57b0a; ?>
<?php unset($__componentOriginalabd5299e7c64663b70bcc905a2f57b0a); ?>
<?php endif; ?>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>

        </div>
    </section>
    <section class="distribution-section">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-4" bis_skin_checked="1">
                <h2 class="section-title">Laporan Penyaluran</h2>
                <a class="link-more" href="">
                    Lihat Semua
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
            <div class="row g-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->distributions(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $distribution): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <div class="col-6">
                        <?php if (isset($component)) { $__componentOriginal71500e2e03c38ffd1ed514e4161d23ba = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal71500e2e03c38ffd1ed514e4161d23ba = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.distribution-card','data' => ['distribution' => $distribution]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.distribution-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['distribution' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($distribution)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal71500e2e03c38ffd1ed514e4161d23ba)): ?>
<?php $attributes = $__attributesOriginal71500e2e03c38ffd1ed514e4161d23ba; ?>
<?php unset($__attributesOriginal71500e2e03c38ffd1ed514e4161d23ba); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal71500e2e03c38ffd1ed514e4161d23ba)): ?>
<?php $component = $__componentOriginal71500e2e03c38ffd1ed514e4161d23ba; ?>
<?php unset($__componentOriginal71500e2e03c38ffd1ed514e4161d23ba); ?>
<?php endif; ?>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>

        </div>
    </section>
    <section class="article-section">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-4" bis_skin_checked="1">
                <h2 class="section-title">Artikel Terbaru</h2>
                <a class="link-more" href="">
                    Lihat Semua
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
            <div class="row g-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->articles(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <div class="col-12">
                        <?php if (isset($component)) { $__componentOriginal52075d1dc04dfba3ed8ff0a7c3efd3e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal52075d1dc04dfba3ed8ff0a7c3efd3e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.article-card','data' => ['article' => $article]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.article-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['article' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($article)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal52075d1dc04dfba3ed8ff0a7c3efd3e2)): ?>
<?php $attributes = $__attributesOriginal52075d1dc04dfba3ed8ff0a7c3efd3e2; ?>
<?php unset($__attributesOriginal52075d1dc04dfba3ed8ff0a7c3efd3e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal52075d1dc04dfba3ed8ff0a7c3efd3e2)): ?>
<?php $component = $__componentOriginal52075d1dc04dfba3ed8ff0a7c3efd3e2; ?>
<?php unset($__componentOriginal52075d1dc04dfba3ed8ff0a7c3efd3e2); ?>
<?php endif; ?>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>

        </div>
    </section>
    <section class="prayers-section">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between mb-4" bis_skin_checked="1">
                <h2 class="section-title">Harapan & Doa Sahabat Inisiatif</h2>
                <a class="link-more" href="">
                    Lihat Semua
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
            <div class="owl-carousel owl-theme owl-prayer" wire:ignore>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->prayers(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prayer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <div class="item">
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
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </div>
    </section>
    <section class="recommended-campaigns-section">
        <div class="container-fluid">
            <div class="mb-3">
                <h2 class="section-title">Rekomendasi Kebaikan Untukmu</h2>
            </div>

            <div class="recommended-tabs mb-3">
                <button wire:click="filterRecommended(null)"
                    class="recommended-tab <?php echo e(!$recommendedCategory ? 'active' : ''); ?>">
                    Semua
                </button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->recommendedCategories(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <button wire:click="filterRecommended(<?php echo e($cat->id); ?>)"
                        class="recommended-tab <?php echo e($recommendedCategory == $cat->id ? 'active' : ''); ?>">
                        <?php echo e($cat->name); ?>

                    </button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>

            <div class="row g-3" wire:loading.class="opacity-50">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->recommendedCampaigns(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaign): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <div class="col-12">
                        <?php if (isset($component)) { $__componentOriginalabd5299e7c64663b70bcc905a2f57b0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalabd5299e7c64663b70bcc905a2f57b0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.campaign-card','data' => ['campaign' => $campaign]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.campaign-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['campaign' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($campaign)]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalabd5299e7c64663b70bcc905a2f57b0a)): ?>
<?php $attributes = $__attributesOriginalabd5299e7c64663b70bcc905a2f57b0a; ?>
<?php unset($__attributesOriginalabd5299e7c64663b70bcc905a2f57b0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalabd5299e7c64663b70bcc905a2f57b0a)): ?>
<?php $component = $__componentOriginalabd5299e7c64663b70bcc905a2f57b0a; ?>
<?php unset($__componentOriginalabd5299e7c64663b70bcc905a2f57b0a); ?>
<?php endif; ?>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <div class="col-12 text-center py-4">
                        <p class="text-muted small">Belum ada campaign di kategori ini.</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="col-12">
                    <a href=""
                        class="btn btn-sm py-2 btn-outline-primary fw-bold w-100 rounded-pill mb-4">Lihat
                        Lebih
                        Banyak</a>
                </div>
            </div>

        </div>
    </section>

    <footer class="main-footer">
        <div class="container-fluid">
            <!-- Social Media -->
            <div class="d-flex gap-2 mb-4">
                <a href="#" class="social-btn"><i class="bi bi-facebook"></i></a>
                <a href="#" class="social-btn"><i class="bi bi-instagram"></i></a>
                <a href="#" class="social-btn"><i class="bi bi-twitter-x"></i></a>
                <a href="#" class="social-btn"><i class="bi bi-tiktok"></i></a>
                <a href="#" class="social-btn"><i class="bi bi-linkedin"></i></a>
            </div>

            <!-- Links -->
            <div class="row mb-4">
                <div class="col-6">
                    <h6 class="footer-heading">Tentang</h6>
                    <ul class="footer-links">
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                    </ul>
                </div>
                <div class="col-6">
                    <h6 class="footer-heading">Dukungan</h6>
                    <ul class="footer-links">
                        <li><a href="#">Pusat Bantuan</a></li>
                        <li><a href="#">Daftar Mitra</a></li>
                        <li><a href="#">Hubungi Kami</a></li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="copyright text-center">
                <p>&copy; 2026 Inisiatif Kebaikan. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <?php if (isset($component)) { $__componentOriginald5197275cb73aa1d2b33640fa04b6785 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald5197275cb73aa1d2b33640fa04b6785 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.bottom-nav','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.bottom-nav'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald5197275cb73aa1d2b33640fa04b6785)): ?>
<?php $attributes = $__attributesOriginald5197275cb73aa1d2b33640fa04b6785; ?>
<?php unset($__attributesOriginald5197275cb73aa1d2b33640fa04b6785); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald5197275cb73aa1d2b33640fa04b6785)): ?>
<?php $component = $__componentOriginald5197275cb73aa1d2b33640fa04b6785; ?>
<?php unset($__componentOriginald5197275cb73aa1d2b33640fa04b6785); ?>
<?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
    
<?php $__env->stopPush(); ?><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/1c773092.blade.php ENDPATH**/ ?>