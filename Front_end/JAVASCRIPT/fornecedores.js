'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const supplierTable = document.getElementById('suppliers-table');
    if (!supplierTable) return;

    const body = supplierTable.querySelector('tbody');
    const search = document.getElementById('supplier-search');
    const count = document.getElementById('supplier-count');
    const empty = document.getElementById('supplier-empty');
    const form = document.getElementById('supplier-form');

    // O PHP processa o cadastro e renderiza novamente a lista.
    if (form) form.method = 'post';

    function filterSuppliers() {
        const query = (search?.value || '').trim().toLocaleLowerCase('pt-BR');
        let visible = 0;

        Array.from(body?.rows || []).forEach(function (row) {
            const show = row.textContent.toLocaleLowerCase('pt-BR').includes(query);
            row.hidden = !show;
            if (show) visible += 1;
        });

        if (count) count.textContent = `${visible} ${visible === 1 ? 'fornecedor' : 'fornecedores'}`;
        if (empty) empty.hidden = visible !== 0;
    }

    search?.addEventListener('input', filterSuppliers);
    filterSuppliers();
});
