'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('entry-form');
    if (!form) return;

    const productInput = document.getElementById('entry-product');
    const locationInput = document.getElementById('entry-location');
    const quantityInput = document.getElementById('entry-quantity');
    const dateInput = document.getElementById('entry-date');
    const observationInput = document.getElementById('entry-observation');
    const feedback = document.getElementById('entry-feedback');
    const clearButton = document.getElementById('entry-clear');

    // O formulário é enviado ao PHP; o servidor valida e grava a movimentação.
    form.method = 'post';

    if (dateInput && !dateInput.value) {
        const today = new Date();
        dateInput.value = [
            today.getFullYear(),
            String(today.getMonth() + 1).padStart(2, '0'),
            String(today.getDate()).padStart(2, '0')
        ].join('-');
    }

    clearButton?.addEventListener('click', function () {
        if (productInput) productInput.selectedIndex = -1;
        if (locationInput) locationInput.value = '';
        if (quantityInput) quantityInput.value = '';
        if (dateInput) dateInput.value = '';
        if (observationInput) observationInput.value = '';
        if (feedback) {
            feedback.hidden = true;
            feedback.textContent = '';
        }
        productInput?.focus();
    });
});
