'use strict';

(function () {
    const sidebar = document.getElementById('sidebar');
    const mobileMenu = document.getElementById('mobileMenu');

    if (mobileMenu && sidebar) {
        mobileMenu.addEventListener('click', function () {
            sidebar.classList.toggle('open');
        });
        document.addEventListener('click', function (event) {
            if (window.innerWidth <= 680 && sidebar.classList.contains('open') &&
                !sidebar.contains(event.target) && !mobileMenu.contains(event.target)) {
                sidebar.classList.remove('open');
            }
        });
    }

    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            const message = form.getAttribute('data-confirm') || 'Lanjutkan tindakan ini?';
            if (!window.confirm(message)) event.preventDefault();
        });
    });

    const productSelect = document.getElementById('product_id');
    const stockHint = document.getElementById('productStockHint');
    const typeInputs = document.querySelectorAll('input[name="type"]');

    function updateStockHint() {
        if (!productSelect || !stockHint) return;
        const option = productSelect.options[productSelect.selectedIndex];
        if (!option || !option.value) {
            stockHint.textContent = 'Saldo terkini akan ditampilkan setelah produk dipilih.';
            return;
        }
        const stock = option.dataset.stock || '0';
        const unit = option.dataset.unit || 'unit';
        stockHint.textContent = 'Saldo saat ini: ' + stock + ' ' + unit + '.';
    }

    if (productSelect) {
        productSelect.addEventListener('change', updateStockHint);
        updateStockHint();
    }

    typeInputs.forEach(function (input) {
        input.addEventListener('change', function () {
            document.querySelectorAll('.movement-type-card').forEach(function (card) {
                card.classList.toggle('selected', card.querySelector('input').checked);
            });
        });
    });

    // Menjaga satuan serta saldo yang ditampilkan tetap bersumber dari pilihan produk.
    const skuInput = document.getElementById('sku');
    if (skuInput) {
        skuInput.addEventListener('input', function () {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9_-]/g, '');
        });
    }
})();
