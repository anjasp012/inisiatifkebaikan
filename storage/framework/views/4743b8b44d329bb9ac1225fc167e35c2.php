<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header" id="toastHeader">
            <strong class="me-auto">Notifikasi</strong>
            <button type="button" class="btn btn-transparent text-white p-0" data-bs-dismiss="toast"
                aria-label="Close">
                <i class="fs-5 bi bi-x"></i>
            </button>
        </div>
        <div class="toast-body" id="toastBody">
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('toast')): ?>
        <script>
            var toastEl = document.getElementById('liveToast');
            var toastHeader = document.getElementById('toastHeader');
            var toastBody = document.getElementById('toastBody');
            var {
                type,
                message
            } = <?php echo json_encode(session('toast'), 15, 512) ?>;

            // Map standard types to high-visibility colors
            var bgClass = 'bg-' + type;
            if (type === 'error') bgClass = 'bg-danger';
            if (type === 'success') bgClass = 'bg-success';

            toastHeader.className = 'toast-header ' + bgClass + ' text-white border-0';
            toastEl.className = 'toast ' + bgClass + ' text-white border-0 shadow-lg';
            toastBody.textContent = message;

            var toast = new bootstrap.Toast(toastEl, {
                autohide: true,
                delay: 5000
            });
            toast.show();
        </script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\laragon\www\inisiatif\resources\views/components/admin/toast.blade.php ENDPATH**/ ?>