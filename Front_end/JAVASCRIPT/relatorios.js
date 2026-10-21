"use strict";

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("report-form");

    if (!form) return;

    const cards = [...document.querySelectorAll("[data-report]")];

    let reportTypeInput =
        document.getElementById("report-type-selection");

    if (!reportTypeInput) {
        reportTypeInput = document.createElement("input");
        reportTypeInput.type = "hidden";
        reportTypeInput.id = "report-type-selection";
        reportTypeInput.name = "relatorio";
        form.appendChild(reportTypeInput);
    }

    const feedback = document.getElementById("report-feedback");
    const results = document.getElementById("report-results");
    const table = document.getElementById("report-results-table");
    const previewButton = document.getElementById("report-preview-button");
    const exportButton = document.getElementById("report-export-csv");
    const printButton = document.getElementById("report-print");

    const startInput = document.getElementById("report-start");
    const endInput = document.getElementById("report-end");

    const categoryInput = document.getElementById("report-category");
    const productInput = document.getElementById("report-product");
    const movementTypeInput = document.getElementById("report-type");

    const reportNames = {
        produtos: "Produtos",
        entradas: "Entradas",
        saidas: "Saídas",
        "estoque-baixo": "Estoque baixo",
        movimentacoes: "Movimentações"
    };

    const validReportTypes = Object.keys(reportNames);

    form.method = "get";

    if (!form.getAttribute("action")) {
        form.action = window.location.pathname;
    }

    const defaultReport =
        cards.find(card => card.dataset.report === "produtos")?.dataset.report ||
        cards[0]?.dataset.report ||
        "produtos";

    let reportGenerated = false;

    function showFeedback(message, isError = false) {
        if (!feedback) return;

        feedback.textContent = message;
        feedback.hidden = false;
        feedback.classList.toggle("is-error", isError);
    }

    function clearFeedback() {
        if (!feedback) return;

        feedback.textContent = "";
        feedback.hidden = true;
        feedback.classList.remove("is-error");
    }

    function selectReport(type) {
        if (!validReportTypes.includes(type)) {
            type = defaultReport;
        }

        reportTypeInput.value = type;

        cards.forEach(card => {
            const selected = card.dataset.report === type;

            card.classList.toggle("is-selected", selected);
            card.setAttribute("aria-pressed", String(selected));

            const label = card.querySelector(
                ".report-type-card__select"
            );

            if (label) {
                label.textContent = selected
                    ? "Selecionado"
                    : "Selecionar";
            }
        });
    }

    function getTableRows() {
        if (!table) return [];

        const tbody = table.querySelector("tbody");

        if (!tbody) return [];

        return [...tbody.querySelectorAll("tr")].filter(row => {
            return row.querySelectorAll("td").length > 0;
        });
    }

    function hasTableData() {
        return getTableRows().length > 0;
    }

    function validateDates() {
        const start = startInput?.value || "";
        const end = endInput?.value || "";

        if (start && end && start > end) {
            showFeedback(
                "A data inicial não pode ser posterior à data final.",
                true
            );

            startInput?.focus();
            return false;
        }

        return true;
    }

    function getSelectedReport() {
        const type = reportTypeInput.value;

        return validReportTypes.includes(type)
            ? type
            : defaultReport;
    }

    function submitReport() {
        clearFeedback();

        if (!validateDates()) return;

        selectReport(getSelectedReport());
        form.requestSubmit();
    }

    function escapeCSV(value) {
        const text = String(value ?? "").replace(/\s+/g, " ").trim();

        return `"${text.replace(/"/g, '""')}"`;
    }

    function exportCSV() {
        clearFeedback();

        if (!reportGenerated || !hasTableData()) {
            showFeedback(
                "Gere um relatório com registros antes de exportar.",
                true
            );
            return;
        }

        const headerCells = [...table.querySelectorAll("thead th")];

        const headers = headerCells.map(cell =>
            cell.textContent.trim()
        );

        if (headers.length === 0) {
            showFeedback(
                "A tabela não possui cabeçalhos para exportação.",
                true
            );
            return;
        }

        const rows = getTableRows();

        const records = rows.map(row => {
            const cells = [...row.querySelectorAll("td")];

            return headers.map((_, index) =>
                cells[index]?.textContent.trim() || ""
            );
        });

        const csvContent = [
            headers.map(escapeCSV).join(";"),
            ...records.map(record =>
                record.map(escapeCSV).join(";")
            )
        ].join("\r\n");

        const blob = new Blob(
            ["\uFEFF", csvContent],
            { type: "text/csv;charset=utf-8;" }
        );

        const url = URL.createObjectURL(blob);
        const link = document.createElement("a");

        link.href = url;
        link.download =
            `relatorio_${getSelectedReport()}_${getToday()}.csv`;

        document.body.appendChild(link);
        link.click();
        link.remove();

        setTimeout(() => URL.revokeObjectURL(url), 1000);

        showFeedback("Exportação CSV iniciada.");
    }

    function getToday() {
        const date = new Date();

        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, "0");
        const day = String(date.getDate()).padStart(2, "0");

        return `${year}-${month}-${day}`;
    }

    function printReport() {
        clearFeedback();

        if (!reportGenerated || !hasTableData()) {
            showFeedback(
                "Gere um relatório com registros antes de imprimir.",
                true
            );
            return;
        }

        const printWindow = window.open("", "_blank");

        if (!printWindow) {
            showFeedback(
                "A janela de impressão foi bloqueada. Permita pop-ups para este sistema.",
                true
            );
            return;
        }

        const reportTitle =
            `Relatório de ${reportNames[getSelectedReport()]}`;

        const tableCopy = table.cloneNode(true);
        tableCopy.removeAttribute("id");

        const filters = new FormData(form);

        const filterLabels = {
            dataInicial: "Data inicial",
            dataFinal: "Data final",
            categoria: "Categoria",
            produto: "Produto",
            tipo: "Tipo de movimentação"
        };

        const filterText = [...filters.entries()]
            .filter(([key, value]) =>
                key !== "relatorio" && value !== ""
            )
            .map(([key, value]) =>
                `${filterLabels[key] || key}: ${value}`
            )
            .join(" | ");

        const title = document.createElement("h1");
        title.textContent = reportTitle;

        const date = document.createElement("p");
        date.textContent = `Data de emissão: ${getToday()}`;

        const filterParagraph = document.createElement("p");
        filterParagraph.textContent =
            filterText || "Nenhum filtro adicional.";

        const count = document.createElement("p");
        count.textContent =
            `Quantidade de registros: ${getTableRows().length}`;

        printWindow.document.head.innerHTML = `
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Relatório Tera Atacado</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 24px;
                    color: #222;
                }

                h1 {
                    font-size: 22px;
                }

                p {
                    font-size: 12px;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }

                th, td {
                    border: 1px solid #ccc;
                    padding: 8px;
                    text-align: left;
                    font-size: 11px;
                    overflow-wrap: anywhere;
                }

                th {
                    background: #eee;
                }

                tr {
                    break-inside: avoid;
                }

                @media print {
                    body {
                        margin: 10mm;
                    }
                }
            </style>
        `;

        printWindow.document.body.replaceChildren(
            title,
            date,
            filterParagraph,
            count,
            tableCopy
        );

        printWindow.addEventListener("load", () => {
            printWindow.focus();
            printWindow.print();
        });

        printWindow.document.close();

        showFeedback("Relatório preparado para impressão.");
    }

    function clearReportResults() {
        reportGenerated = false;

        if (results) {
            results.hidden = true;
        }

        if (table) {
            table.querySelector("thead")?.replaceChildren();
            table.querySelector("tbody")?.replaceChildren();
        }
    }

    cards.forEach(card => {
        card.addEventListener("click", () => {
            selectReport(card.dataset.report);
            clearFeedback();
        });
    });

    previewButton?.addEventListener("click", submitReport);
    exportButton?.addEventListener("click", exportCSV);
    printButton?.addEventListener("click", printReport);

    form.addEventListener("submit", event => {
        if (!validateDates()) {
            event.preventDefault();
            return;
        }

        selectReport(getSelectedReport());
    });


    form.addEventListener("reset", event => {
        event.preventDefault();

        if (startInput) startInput.value = "";
        if (endInput) endInput.value = "";
        if (categoryInput) categoryInput.value = "";
        if (productInput) productInput.value = "";
        if (movementTypeInput) movementTypeInput.value = "";

        selectReport(defaultReport);
        clearFeedback();
        clearReportResults();

        const cleanUrl = new URL(window.location.href);
        cleanUrl.search = "";
        window.history.replaceState({}, "", cleanUrl);
    });

    const params = new URLSearchParams(window.location.search);
    const requestedReport = params.get("relatorio");

    const initialReport = validReportTypes.includes(requestedReport)
        ? requestedReport
        : defaultReport;

    selectReport(initialReport);

    if (hasTableData() && results && !results.hidden) {
        reportGenerated = true;
    }
});