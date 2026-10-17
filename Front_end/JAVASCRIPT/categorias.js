'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const categoryTable = document.getElementById('categories-table');
    if (!categoryTable) return;

    const body = categoryTable.querySelector('tbody');
    const search = document.getElementById('category-search');
    const count = document.getElementById('category-count');
    const empty = document.getElementById('category-empty');
    const form = document.getElementById('category-form');

    // O PHP processa o cadastro e renderiza novamente a lista.
    if (form) form.method = 'post';

    function filterCategories() {
        const query = (search?.value || '').trim().toLocaleLowerCase('pt-BR');
        let visible = 0;

        Array.from(body?.rows || []).forEach(function (row) {
            const show = row.textContent.toLocaleLowerCase('pt-BR').includes(query);
            row.hidden = !show;
            if (show) visible += 1;
        });

        if (count) count.textContent = `${visible} ${visible === 1 ? 'categoria' : 'categorias'}`;
        if (empty) empty.hidden = visible !== 0;
    }

    search?.addEventListener('input', filterCategories);
    filterCategories();
});
