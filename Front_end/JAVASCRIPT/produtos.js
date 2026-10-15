'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('product-search');
    const categoryFilter = document.getElementById('category-filter');
    const stockFilter = document.getElementById('stock-filter');
    const clearButton = document.getElementById('clear-product-filters');
    const exportButton = document.getElementById('export-products');
    const table = document.getElementById('products-table');
    const emptyState = document.getElementById('products-empty');
    const visibleCount = document.getElementById('products-visible-count');

    if (!table) return;

    const rows = Array.from(table.querySelectorAll('tbody tr'));

    function applyFilters() {
        const query = searchInput.value.trim().toLocaleLowerCase('pt-BR');
        const category = categoryFilter.value;
        const stock = stockFilter.value;
        let visible = 0;

        rows.forEach(function (row) {
            const matchesText = row.textContent.toLocaleLowerCase('pt-BR').includes(query);
            const matchesCategory = !category || row.dataset.category === category;
            const matchesStock = !stock || row.dataset.stock === stock;
            const show = matchesText && matchesCategory && matchesStock;

            row.hidden = !show;
            if (show) visible += 1;
        });

        emptyState.hidden = visible !== 0;
        visibleCount.textContent = `Exibindo ${visible} ${visible === 1 ? 'produto' : 'produtos'}`;
    }

    function downloadVisibleRows() {
        const headers = Array.from(table.querySelectorAll('thead th')).map(function (cell) {
            return cell.textContent.trim();
        });
        const visibleRows = rows.filter(row => !row.hidden);
        const records = visibleRows.map(function (row) {
            return Array.from(row.querySelectorAll('td')).map(function (cell) {
                return cell.textContent.trim().replace(/\s+/g, ' ');
            });
        });
        const csv = [headers, ...records].map(function (record) {
            return record.map(value => `"${value.replace(/"/g, '""')}"`).join(';');
        }).join('\r\n');
        const blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'produtos_tera_atacado.csv';
        document.body.appendChild(link);
        link.click();
        link.remove();
        URL.revokeObjectURL(url);
    }

    searchInput.addEventListener('input', applyFilters);
    categoryFilter.addEventListener('change', applyFilters);
    stockFilter.addEventListener('change', applyFilters);

    clearButton.addEventListener('click', function () {
        searchInput.value = '';
        categoryFilter.value = '';
        stockFilter.value = '';
        applyFilters();
        searchInput.focus();
    });

    exportButton.addEventListener('click', downloadVisibleRows);

    // As métricas refletem os registros demonstrativos desta página.
    document.getElementById('metric-total').textContent = rows.length;
    document.getElementById('metric-low').textContent = rows.filter(row => row.dataset.stock === 'low').length;
    document.getElementById('metric-out').textContent = rows.filter(row => row.dataset.stock === 'out').length;
    applyFilters();
});
