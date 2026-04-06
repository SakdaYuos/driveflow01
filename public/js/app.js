// DriveFlow — Main JavaScript
document.addEventListener('DOMContentLoaded', function () {

    // ── Auto-dismiss alerts ──────────────────────────────────────────────────
    document.querySelectorAll('.alert[data-auto-dismiss]').forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-8px)';
            alert.style.transition = 'all 0.4s ease';
            setTimeout(() => alert.remove(), 400);
        }, 4000);
    });

    // ── Price Calculator (Admin Booking Form) ────────────────────────────────
    const pickupInput  = document.getElementById('pickup_date');
    const returnInput  = document.getElementById('return_date');
    const carSelect    = document.getElementById('car_id');
    const pricePreview = document.getElementById('price-preview');
    const priceVal     = document.getElementById('price-val');
    const priceDetail  = document.getElementById('price-detail');

    function calcTotal() {
        if (!pickupInput || !returnInput || !carSelect) return;
        const pickup = new Date(pickupInput.value);
        const ret    = new Date(returnInput.value);
        const option = carSelect.options[carSelect.selectedIndex];
        const price  = parseFloat(option?.dataset?.price || 0);
        if (pickupInput.value && returnInput.value && ret > pickup) {
            const days  = Math.ceil((ret - pickup) / (1000 * 60 * 60 * 24));
            const total = days * price;
            if (pricePreview) pricePreview.style.display = 'flex';
            if (priceVal)     priceVal.textContent  = '$' + total.toFixed(0);
            if (priceDetail)  priceDetail.textContent = days + ' day' + (days > 1 ? 's' : '') + ' × $' + price.toFixed(0) + '/day';
        } else {
            if (pricePreview) pricePreview.style.display = 'none';
        }
    }

    if (pickupInput)  pickupInput.addEventListener('change', calcTotal);
    if (returnInput)  returnInput.addEventListener('change', calcTotal);
    if (carSelect)    carSelect.addEventListener('change', calcTotal);
    calcTotal();

    // ── Car Image Preview ────────────────────────────────────────────────────
    const imageInput       = document.getElementById('image');
    const imagePreview     = document.getElementById('image-preview');
    const imagePlaceholder = document.getElementById('image-placeholder');

    if (imageInput) {
        imageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file && imagePreview) {
                const reader = new FileReader();
                reader.onload = e => {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                    if (imagePlaceholder) imagePlaceholder.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // ── Confirm Delete ───────────────────────────────────────────────────────
    document.querySelectorAll('[data-confirm-delete]').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const form    = document.getElementById(this.dataset.confirmDelete);
            const message = this.dataset.message || 'Are you sure you want to delete this?';
            if (confirm(message)) { form.submit(); }
        });
    });

    // ── Toast Notifications ──────────────────────────────────────────────────
    window.showToast = function (msg, type = 'success') {
        let container = document.querySelector('.toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container';
            document.body.appendChild(container);
        }
        const icons = { success: 'fa-circle-check', error: 'fa-circle-xmark', warning: 'fa-triangle-exclamation' };
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `<i class="fa ${icons[type] || icons.success}"></i> ${msg}`;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            toast.style.transition = 'all 0.3s';
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    };

    // Show flash toasts from server-side session
    const flashSuccess = document.querySelector('[data-flash-success]');
    const flashError   = document.querySelector('[data-flash-error]');
    if (flashSuccess) showToast(flashSuccess.dataset.flashSuccess, 'success');
    if (flashError)   showToast(flashError.dataset.flashError, 'error');
});
