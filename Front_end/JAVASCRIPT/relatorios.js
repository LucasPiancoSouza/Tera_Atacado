'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const reportForm = document.getElementById('report-form');
    if (!reportForm) return;

    // Os filtros e o tipo de relatório são enviados ao PHP via GET.
    reportForm.method = 'get';

    const reportTypeInput = document.getElementById('report-type-selection') || document.createElement('input');
    reportTypeInput.type = 'hidden';
    reportTypeInput.id = 'report-type-selection';
    reportTypeInput.name = 'relatorio';
    if (reportTypeInput.form !== reportForm) reportForm.appendChild(reportTypeInput);

    const cards = Array.from(document.querySelectorAll('[data-report]'));
    const defaultType = cards[0]?.dataset.report || '';
    reportTypeInput.defaultValue = defaultType;
    const feedback = document.getElementById('report-feedback');
    const results = document.getElementById('report-results');
    const table = document.getElementById('report-results-table');
    const previewButton = document.getElementById('report-preview-button');
    const exportButton = document.getElementById('report-export-csv');
    const printButton = document.getElementById('report-print');

    function selectReport(type) {
        reportTypeInput.value = type;
        cards.forEach(function (card) {
            const selected = card.dataset.report === type;
            card.classList.toggle('is-selected', selected);
            card.setAttribute('aria-pressed', String(selected));

            const badge = card.querySelector('.report-type-card__select');
            if (badge) badge.textContent = selected ? 'Selecionado' : 'Selecionar';
        });
    }

    function showFeedback(message) {
        if (!feedback) return;
        feedback.hidden = false;
        feedback.textContent = message;
    }

    function getServerRows() {
        return table ? Array.from(table.querySelectorAll('tbody tr')) : [];
    }

    function exportServerRows() {
        const rows = getServerRows();
        if (!table || rows.length === 0) {
            showFeedback('Não há dados do PHP para exportar. Gere um relatório primeiro.');
            return;
        }

        const headers = Array.from(table.querySelectorAll('thead th')).map(cell => cell.textContent.trim());
        const records = rows.map(row => Array.from(row.cells).map(cell => cell.textContent.trim().replace(/\s+/g, ' ')));
        const csv = [headers, ...records].map(record =>
            record.map(value => `"${value.replace(/"/g, '""')}"`).join(';')
        ).join('\r\n');

        const blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        const reportType = reportTypeInput.value || 'relatorio';
        link.href = url;
        link.download = `relatorio_${reportType}.csv`;
        document.body.appendChild(link);
        link.click();
        link.remove();
        URL.revokeObjectURL(url);
    }

    cards.forEach(card => card.addEventListener('click', () => selectReport(card.dataset.report)));

    reportForm.addEventListener('submit', function (event) {
        const start = document.getElementById('report-start')?.value;
        const end = document.getElementById('report-end')?.value;
        if (start && end && start > end) {
            event.preventDefault();
            showFeedback('A data inicial deve ser anterior ou igual à data final.');
            return;
        }
        if (feedback) feedback.hidden = true;
    });

    previewButton?.addEventListener('click', function () {
        if (cards.length > 0 && !reportTypeInput.value) selectReport(cards[0].dataset.report);
        reportForm.requestSubmit();
    });

    exportButton?.addEventListener('click', exportServerRows);

    printButton?.addEventListener('click', function () {
        const rows = getServerRows();
        if (rows.length === 0) {
            showFeedback('Não há dados do PHP para imprimir. Gere um relatório primeiro.');
            return;
        }
        if (results) results.hidden = false;
        window.print();
    });

    reportForm.addEventListener('reset', function () {
        selectReport(defaultType);
        if (feedback) feedback.hidden = true;
    });

    const initialType = new URLSearchParams(window.location.search).get('relatorio');
    const selectedType = cards.some(card => card.dataset.report === initialType)
        ? initialType
        : defaultType;
    selectReport(selectedType);

    if (getServerRows().length > 0 && results) results.hidden = false;
});
