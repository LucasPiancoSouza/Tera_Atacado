'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('movement-search');
    const typeFilter = document.getElementById('movement-type-filter');
    const clearButton = document.getElementById('clear-movement-filters');
    const exportButton = document.getElementById('export-movements');
    const table = document.getElementById('movements-table');
    const emptyState = document.getElementById('movements-empty');
    const visibleCount = document.getElementById('movements-visible-count');

    if (!table) return;

    const rows = Array.from(table.querySelectorAll('tbody tr'));

    function applyFilters() {
        const query = searchInput.value.trim().toLocaleLowerCase('pt-BR');
        const type = typeFilter.value;
        let visible = 0;

        rows.forEach(function (row) {
            const matchesText = row.textContent.toLocaleLowerCase('pt-BR').includes(query);
            const matchesType = !type || row.dataset.type === type;
            const show = matchesText && matchesType;

            row.hidden = !show;
            if (show) visible += 1;
        });

        emptyState.hidden = visible !== 0;
        visibleCount.textContent = `Exibindo ${visible} ${visible === 1 ? 'movimentação' : 'movimentações'}`;
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
        link.download = 'movimentacoes_tera_atacado.csv';
        document.body.appendChild(link);
        link.click();
        link.remove();
        URL.revokeObjectURL(url);
    }

    searchInput.addEventListener('input', applyFilters);
    typeFilter.addEventListener('change', applyFilters);

    clearButton.addEventListener('click', function () {
        searchInput.value = '';
        typeFilter.value = '';
        applyFilters();
        searchInput.focus();
    });

    exportButton.addEventListener('click', downloadVisibleRows);

    document.getElementById('metric-mov-total').textContent = rows.length;
    document.getElementById('metric-mov-entries').textContent = rows.filter(row => row.dataset.type === 'entrada').length;
    document.getElementById('metric-mov-exits').textContent = rows.filter(row => row.dataset.type === 'saida').length;
    applyFilters();
});
