document.addEventListener('livewire:initialized', () => {
    Livewire.on('toast', ({ type, message }) => {
        var toastDispatch = document.getElementById('liveToast');
        var toastDispatchHeader = document.getElementById('toastHeader');
        var toastDispatchBody = document.getElementById('toastBody');

        // Map standard types to high-visibility colors
        var bgClass = 'bg-' + type;
        if (type === 'error') bgClass = 'bg-danger';
        if (type === 'success') bgClass = 'bg-success';

        // Reset and apply classes
        toastDispatch.className = 'toast ' + bgClass + ' text-white shadow-lg';
        toastDispatchHeader.className = 'toast-header ' + bgClass + ' text-white';

        toastDispatchBody.textContent = message;

        var toast = new bootstrap.Toast(toastDispatch, {
            autohide: true,
            delay: 5000
        });
        toast.show();
    });
});
