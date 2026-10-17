'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('exit-form');
    if (!form) return;

    // O formulário é enviado ao PHP; o servidor valida estoque e movimentação.
    form.method = 'post';

    const dateInput = document.getElementById('exit-date');
    if (dateInput && !dateInput.value) {
        const today = new Date();
        dateInput.value = [
            today.getFullYear(),
            String(today.getMonth() + 1).padStart(2, '0'),
            String(today.getDate()).padStart(2, '0')
        ].join('-');
    }
});
