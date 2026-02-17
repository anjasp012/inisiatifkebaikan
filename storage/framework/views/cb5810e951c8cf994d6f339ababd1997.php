<?php
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;
?>

<div>
    <div class="card card-dashboard">
        <div class="card-body border-bottom">
            <div class="d-flex flex-column flex-lg-row justify-content-lg-between align-items-lg-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">Manajemen Artikel</h5>
                    <p class="text-muted small mb-0">Kelola berita, cerita inspirasi, dan edukasi.</p>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="<?php echo e(route('admin.artikel.tambah')); ?>" wire:navigate class="btn btn-primary text-white">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Artikel
                    </a>
                    <div wire:ignore class="d-inline-block" style="min-width: 200px;">
                        <select x-data="{
                            tom: null,
                            init() {
                                this.tom = new TomSelect(this.$el, {
                                    placeholder: 'Semua Kategori',
                                    allowEmptyOption: false,
                                    maxOptions: 50,
                                    onChange: (value) => {
                                        $wire.set('category', value);
                                    }
                                });
                            }
                        }" class="form-select">
                            <option value="all">Semua Kategori</option>
                            <option value="Berita">Berita</option>
                            <option value="Inspirasi">Inspirasi</option>
                            <option value="Edukasi">Edukasi</option>
                        </select>
                    </div>
                    <div class="position-relative">
                        <input type="text" class="form-control ps-5" placeholder="Cari artikel..."
                            wire:model.live.debounce.250ms="search" style="min-width: 250px;">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center">NO</th>
                        <th>THUMBNAIL</th>
                        <th>JUDUL ARTIKEL</th>
                        <th>PENULIS</th>
                        <th>VIEWS</th>
                        <th>STATUS</th>
                        <th>DIBUAT</th>
                        <th class="text-end">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $this->articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no => $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr>
                            <td class="text-center"><?php echo e($this->articles->firstItem() + $no); ?></td>
                            <td>
                                <img loading="lazy" src="<?php echo e($article->thumbnail_url); ?>" width="100px" class="rounded-1"
                                    alt="<?php echo e($article->title); ?>">
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo e($article->title); ?></div>
                                <div class="mt-1">
                                    <span
                                        class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 border border-primary border-opacity-10"
                                        style="font-size: 10px; font-weight: 600;">
                                        <i class="bi bi-tag-fill me-1"></i>
                                        <?php echo e($article->category); ?>

                                    </span>
                                </div>
                            </td>
                            <td><?php echo e($article->author->name ?? '-'); ?></td>
                            <td><?php echo e(number_format($article->views_count)); ?></td>
                            <td>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->is_published): ?>
                                    <span
                                        class="badge bg-success bg-opacity-10 text-success px-3 py-2 border border-success border-opacity-10">
                                        <i class="bi bi-check-circle-fill me-1"></i> Published
                                    </span>
                                <?php else: ?>
                                    <span
                                        class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 border border-secondary border-opacity-10">
                                        <i class="bi bi-pencil-fill me-1"></i> Draft
                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td><?php echo e($article->created_at->diffForHumans()); ?></td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="<?php echo e(route('admin.artikel.ubah', $article)); ?>" wire:navigate
                                        class="btn btn-sm btn-warning text-white" title="Ubah"><i
                                            class="bi bi-pencil"></i></a>
                                    <button wire:click="destroy(<?php echo e($article->id); ?>)"
                                        wire:confirm="Anda yakin menghapus artikel ini?"
                                        class="btn btn-sm btn-danger text-white" title="Hapus">
                                        <span wire:loading.remove wire:target="destroy(<?php echo e($article->id); ?>)">
                                            <i class="bi bi-trash"></i>
                                        </span>
                                        <span wire:loading wire:target="destroy(<?php echo e($article->id); ?>)">
                                            <div class="spinner-border spinner-border-sm" role="status"></div>
                                        </span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                    <div class="text-muted small">
                        Menampilkan <strong><?php echo e($this->articles->firstItem()); ?></strong> -
                        <strong><?php echo e($this->articles->lastItem()); ?></strong> dari
                        <strong><?php echo e($this->articles->total()); ?></strong> artikel
                    </div>
                    <div>
                        <?php echo e($this->articles->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\inisiatif\storage\framework/views/livewire/views/f765cbc3.blade.php ENDPATH**/ ?>