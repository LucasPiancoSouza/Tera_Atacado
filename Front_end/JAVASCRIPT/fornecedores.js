//fornecedor.js//
'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('#suppliers-table tbody');
    const searchInput = document.getElementById('supplier-search');
    const supplierCount = document.getElementById('supplier-count');
    const emptyState = document.getElementById('supplier-empty');
    const supplierForm = document.getElementById('supplier-form');
    const feedbackEl = document.getElementById('supplier-feedback');
    const popupSucesso = document.getElementById('popup-sucesso');
    const popupSucessoTitulo = document.getElementById('popup-sucesso-titulo');
    const popupSucessoMensagem = document.getElementById('popup-sucesso-mensagem');

    let fornecedoresData = [];
    let temporizadorPopupSucesso = null;

    function mostrarPopupSucesso(titulo, mensagem) {
        if (!popupSucesso || !popupSucessoTitulo || !popupSucessoMensagem) {
            console.error('O pop-up de sucesso não foi encontrado na página.');
            return;
        }

        popupSucessoTitulo.textContent = titulo;
        popupSucessoMensagem.textContent = mensagem;
        popupSucesso.classList.add('mostrar');
        window.clearTimeout(temporizadorPopupSucesso);
        temporizadorPopupSucesso = window.setTimeout(() => {
            popupSucesso.classList.remove('mostrar');
        }, 2000);
    }

    // --- 1. CARREGAR FORNECEDORES VIA FETCH ---
    function carregarFornecedores() {
        fetch('../Back_end/processamentos/listar_fornecedor.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na rede ao carregar fornecedores');
                }
                return response.json();
            })
            .then(data => {
                fornecedoresData = Array.isArray(data) ? data : [];
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
                <td>${escapeHtml(forn.cnpj || '-')}</td>
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

    // --- 4. INTEGRAÇÃO COM A API VIACEP (Com permissão de alteração manual) ---
    const inputCep = document.getElementById('supplier-cep');
    const inputRua = document.getElementById('supplier-rua');
    const inputCidade = document.getElementById('supplier-cidade');
    const inputEstado = document.getElementById('supplier-estado');

    if (inputCep) {
        inputCep.addEventListener('blur', function () {
            let cepLimpo = inputCep.value.replace(/\D/g, '');

            if (cepLimpo.length === 8) {
                fetch(`https://viacep.com.br/ws/${cepLimpo}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            if (inputRua) inputRua.value = data.logradouro || '';
                            if (inputCidade) inputCidade.value = data.localidade || '';
                            if (inputEstado) inputEstado.value = data.uf || '';
                        } else {
                            alert('CEP não encontrado. Pode preencher os campos manualmente.');
                        }
                    })
                    .catch(error => console.error('Erro ao consultar o ViaCEP:', error));
            }
        });
    }

    // --- 5. SUBMISSÃO DO FORMULÁRIO DE CADASTRO ---
    if (supplierForm) {
        supplierForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(supplierForm);

            fetch('../Back_end/processamentos/cadastrar_fornecedor.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(resultado => {
                if (!resultado.sucesso && feedbackEl) {
                    feedbackEl.hidden = false;
                    feedbackEl.textContent = resultado.mensagem || resultado.erro;
                    feedbackEl.style.color = '#c62828';
                }

                if (resultado.sucesso) {
                    if (feedbackEl) feedbackEl.hidden = true;
                    supplierForm.reset();
                    mostrarPopupSucesso(
                        'Fornecedor cadastrado!',
                        resultado.mensagem || 'O fornecedor foi cadastrado com sucesso.'
                    );
                    carregarFornecedores(); // Atualiza a tabela dinamicamente
                }
            })
            .catch(error => {
                console.error('Erro ao cadastrar fornecedor:', error);
                if (feedbackEl) {
                    feedbackEl.hidden = false;
                    feedbackEl.textContent = 'Erro de comunicação com o servidor.';
                    feedbackEl.style.color = '#c62828';
                }
            });
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