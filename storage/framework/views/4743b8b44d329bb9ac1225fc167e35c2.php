<div x-data="{
    type: 'success',
    message: '',
    show(data) {
        if (!data) return;
        this.type = data.type || 'success';
        this.message = data.message || '';

        const el = document.getElementById('liveToast');
        if (typeof bootstrap !== 'undefined') {
            const bs = bootstrap.Toast.getOrCreateInstance(el);
            bs.show();
        }
    }
}" x-on:toast.window="show($event.detail[0] || $event.detail)"
    class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
    <div id="liveToast" class="toast border-0 shadow-lg"
        :class="{
            'bg-success text-white': type === 'success',
            'bg-danger text-white': type === 'error' || type === 'danger',
            'bg-warning text-dark': type === 'warning',
            'bg-info text-white': type === 'info'
        }"
        role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header border-0 text-white"
            :class="{
                'bg-success': type === 'success',
                'bg-danger': type === 'error' || type === 'danger',
                'bg-warning text-dark': type === 'warning',
                'bg-info': type === 'info'
            }">
            <i class="bi me-2"
                :class="{
                    'bi-check-circle-fill': type === 'success',
                    'bi-exclamation-triangle-fill': type === 'error' || type === 'danger' || type === 'warning',
                    'bi-info-circle-fill': type === 'info'
                }"></i>
            <strong class="me-auto font-uppercase" x-text="type === 'error' ? 'GAGAL' : 'NOTIFIKASI'"></strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
        <div class="toast-body fw-medium" x-text="message">
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\inisiatif\resources\views/components/admin/toast.blade.php ENDPATH**/ ?>