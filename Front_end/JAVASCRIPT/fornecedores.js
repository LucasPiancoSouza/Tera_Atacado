'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('#suppliers-table tbody');
    const searchInput = document.getElementById('supplier-search');
    const supplierCount = document.getElementById('supplier-count');
    const emptyState = document.getElementById('supplier-empty');

    let fornecedoresData = [];

    // --- 1. CARREGAR FORNECEDORES VIA FETCH ---
    function carregarFornecedores() {
        fetch('../Back_end/processamentos/listar_fornecedor.php')
            .then(response => response.json())
            .then(data => {
                fornecedoresData = data;
                renderizarTabela(fornecedoresData);
            })
            .catch(error => console.error('Erro ao carregar fornecedores:', error));
    }

    // --- 2. RENDERIZAR TABELA ---
    function renderizarTabela(dados) {
        if (!tableBody) return;
        tableBody.innerHTML = '';

        if (!dados || dados.length === 0) {
            if (emptyState) emptyState.hidden = false;
            if (supplierCount) supplierCount.textContent = '0 fornecedores';
            return;
        }

        if (emptyState) emptyState.hidden = true;
        if (supplierCount) supplierCount.textContent = `${dados.length} ${dados.length === 1 ? 'fornecedor' : 'fornecedores'}`;

        dados.forEach(forn => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><strong>${escapeHtml(forn.nome_fornecedor)}</strong></td>
                <td>${escapeHtml(forn.empresa || '-')}</td>
                <td>${escapeHtml(forn.cnpj)}</td>
                <td>${escapeHtml(forn.telefone || '-')}</td>
                <td>${escapeHtml(forn.email || '-')}</td>
                <td>${escapeHtml(forn.cep || '-')}</td>
                <td>${escapeHtml(forn.estado || '-')}</td>
                <td>${escapeHtml(forn.cidade || '-')}</td>
                <td>${escapeHtml(forn.rua_numero || '-')}</td>
            `;
            tableBody.appendChild(tr);
        });
    }

    // --- 3. FILTRO DE PESQUISA ---
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const termo = searchInput.value.toLowerCase().trim();
            const filtrados = fornecedoresData.filter(forn => 
                forn.nome_fornecedor.toLowerCase().includes(termo) ||
                (forn.empresa && forn.empresa.toLowerCase().includes(termo)) ||
                forn.cnpj.toLowerCase().includes(termo) ||
                (forn.email && forn.email.toLowerCase().includes(termo))
            );
            renderizarTabela(filtrados);
        });
    }

    function escapeHtml(text) {
        if (!text) return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.toString().replace(/[&<>"']/g, m => map[m]);
    }

    // Inicialização
    carregarFornecedores();
}); 
