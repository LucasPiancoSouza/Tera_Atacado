//fornecedor.js//
'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('#suppliers-table tbody');
    const searchInput = document.getElementById('supplier-search');
    const supplierCount = document.getElementById('supplier-count');
    const emptyState = document.getElementById('supplier-empty');
    const supplierForm = document.getElementById('supplier-form');
    const feedbackEl = document.getElementById('supplier-feedback');
    const editForm = document.getElementById('formEditarFornecedor');
    const editModal = document.getElementById('modalEditarFornecedor');
    const editFeedback = document.getElementById('edit-supplier-feedback');
    const actionModal = document.getElementById('supplier-action-modal');
    const actionIcon = document.getElementById('supplier-action-icon');
    const actionTitle = document.getElementById('supplier-action-title');
    const actionMessage = document.getElementById('supplier-action-message');
    const actionCancel = document.getElementById('supplier-action-cancel');
    const actionConfirm = document.getElementById('supplier-action-confirm');
    const popupSucesso = document.getElementById('popup-sucesso');
    const popupSucessoTitulo = document.getElementById('popup-sucesso-titulo');
    const popupSucessoMensagem = document.getElementById('popup-sucesso-mensagem');

    let fornecedoresData = [];
    let temporizadorPopupSucesso = null;
    let elementoAnteriorModal = null;
    let resolverModalAcao = null;
    let elementoAnteriorAcao = null;

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
        return fetch('../Back_end/processamentos/listar_fornecedor.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na rede ao carregar fornecedores');
                }
                return response.json();
            })
            .then(data => {
                fornecedoresData = Array.isArray(data) ? data : [];
                renderizarTabela(fornecedoresData);
                return true;
            })
            .catch(error => {
                console.error('Erro ao carregar fornecedores:', error);
                return false;
            });
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
                <td>
                    <div class="module-actions">
                        <button class="icon-button" type="button" data-action="edit-supplier" data-id="${escapeHtml(forn.id_fornecedor)}" title="Editar fornecedor" aria-label="Editar fornecedor ${escapeHtml(forn.nome_fornecedor)}">✎ <span>Editar</span></button>
                        <button class="icon-button icon-button--danger" type="button" data-action="delete-supplier" data-id="${escapeHtml(forn.id_fornecedor)}" data-nome="${escapeHtml(forn.nome_fornecedor)}" title="Excluir fornecedor" aria-label="Excluir fornecedor ${escapeHtml(forn.nome_fornecedor)}">× <span>Excluir</span></button>
                    </div>
                </td>
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

    function abrirModalEdicao(id) {
        if (!editForm || !editModal) {
            console.error('O formulário de edição de fornecedor não foi encontrado.');
            return;
        }

        const fornecedor = fornecedoresData.find(
            item => String(item.id_fornecedor) === String(id)
        );

        if (fornecedor) {
            preencherFormularioEdicao(fornecedor);
            return;
        }

        editModal.hidden = false;
        if (editFeedback) {
            editFeedback.hidden = false;
            editFeedback.textContent = 'Carregando os dados do fornecedor...';
            editFeedback.style.color = '';
        }

        carregarFornecedores().then(carregado => {
            const fornecedorAtualizado = fornecedoresData.find(
                item => String(item.id_fornecedor) === String(id)
            );

            if (!carregado || !fornecedorAtualizado) {
                if (editFeedback) {
                    editFeedback.textContent = 'Não foi possível carregar os dados deste fornecedor.';
                    editFeedback.style.color = '#c62828';
                }
                return;
            }

            preencherFormularioEdicao(fornecedorAtualizado);
        });
    }

    function preencherFormularioEdicao(fornecedor) {
        const campos = [
            'id_fornecedor',
            'nome_fornecedor',
            'empresa',
            'cnpj',
            'telefone',
            'email',
            'cep',
            'rua_numero',
            'cidade',
            'estado'
        ];

        campos.forEach(campo => {
            const input = editForm.elements.namedItem(campo);
            if (input) input.value = fornecedor[campo] || '';
        });

        if (editFeedback) {
            editFeedback.hidden = true;
            editFeedback.textContent = '';
        }

        editModal.hidden = false;
        editForm.elements.namedItem('nome_fornecedor')?.focus();
    }

    function fecharModalEdicao() {
        if (!editModal || editModal.hidden) return;

        editModal.hidden = true;
        editForm?.reset();
        if (editFeedback) {
            editFeedback.hidden = true;
            editFeedback.textContent = '';
        }
        if (elementoAnteriorModal && elementoAnteriorModal.isConnected) {
            elementoAnteriorModal.focus();
        }
        elementoAnteriorModal = null;
    }

    function abrirModalAcao({ titulo, mensagem, tipo = 'warning', confirmar = true, textoConfirmar = 'Confirmar' }) {
        if (!actionModal || !actionIcon || !actionTitle || !actionMessage || !actionConfirm || !actionCancel) {
            console.error('O modal de ações do fornecedor não foi encontrado.');
            return Promise.resolve(false);
        }

        const simbolos = { warning: '!', error: '×', info: 'i' };
        actionModal.classList.remove('category-modal--warning', 'category-modal--error', 'category-modal--info');
        actionModal.classList.add(`category-modal--${tipo}`);
        actionIcon.textContent = simbolos[tipo] || simbolos.info;
        actionTitle.textContent = titulo;
        actionMessage.textContent = mensagem;
        actionCancel.hidden = !confirmar;
        actionConfirm.textContent = confirmar ? textoConfirmar : 'Fechar';
        actionConfirm.classList.toggle('category-modal__confirm--danger', confirmar);
        actionModal.querySelector('.category-modal__dialog').setAttribute(
            'role',
            confirmar ? 'dialog' : 'alertdialog'
        );

        elementoAnteriorAcao = document.activeElement;
        actionModal.hidden = false;

        return new Promise(resolve => {
            resolverModalAcao = resolve;
            requestAnimationFrame(() => actionConfirm.focus());
        });
    }

    function fecharModalAcao(confirmado = false) {
        if (!actionModal || actionModal.hidden) return;

        actionModal.hidden = true;
        const resolver = resolverModalAcao;
        resolverModalAcao = null;
        if (resolver) resolver(confirmado);
        if (elementoAnteriorAcao && elementoAnteriorAcao.isConnected) {
            elementoAnteriorAcao.focus();
        }
        elementoAnteriorAcao = null;
    }

    actionModal?.addEventListener('click', function (e) {
        if (e.target.closest('[data-supplier-modal-close]')) {
            fecharModalAcao(false);
        }
    });

    actionCancel?.addEventListener('click', function () {
        fecharModalAcao(false);
    });

    actionConfirm?.addEventListener('click', function () {
        fecharModalAcao(actionCancel && !actionCancel.hidden);
    });

    tableBody?.addEventListener('click', function (e) {
        const button = e.target.closest('[data-action="edit-supplier"], [data-action="delete-supplier"]');
        if (!button) return;

        if (button.dataset.action === 'edit-supplier') {
            elementoAnteriorModal = button;
            abrirModalEdicao(button.dataset.id);
            return;
        }

        excluirFornecedor(button);
    });

    editModal?.addEventListener('click', function (e) {
        if (e.target.closest('[data-action="close-edit-modal"]')) {
            fecharModalEdicao();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && editModal && !editModal.hidden) {
            fecharModalEdicao();
        }
        if (e.key === 'Escape' && actionModal && !actionModal.hidden) {
            fecharModalAcao(false);
        }
    });

    editForm?.addEventListener('submit', async function (e) {
        e.preventDefault();
        const botaoSalvar = editForm.querySelector('button[type="submit"]');
        if (botaoSalvar) botaoSalvar.disabled = true;

        try {
            const response = await fetch('../Back_end/processamentos/editar_fornecedor.php', {
                method: 'POST',
                body: new FormData(editForm)
            });
            const resultado = await response.json();

            if (editFeedback) {
                editFeedback.hidden = false;
                editFeedback.textContent =
                    resultado.mensagem || resultado.erro || 'Não foi possível salvar as alterações.';
                editFeedback.style.color = resultado.sucesso ? '#2e7d32' : '#c62828';
            }

            if (resultado.sucesso) {
                fecharModalEdicao();
                mostrarPopupSucesso(
                    'Fornecedor atualizado!',
                    resultado.mensagem || 'Os dados do fornecedor foram atualizados.'
                );
                const carregado = await carregarFornecedores();
                if (!carregado && feedbackEl) {
                    feedbackEl.hidden = false;
                    feedbackEl.textContent = 'As alterações foram salvas, mas não foi possível atualizar a lista.';
                    feedbackEl.style.color = '#c62828';
                }
            }
        } catch (error) {
            console.error('Erro ao editar fornecedor:', error);
            if (editFeedback) {
                editFeedback.hidden = false;
                editFeedback.textContent = 'Erro de comunicação ao salvar as alterações.';
                editFeedback.style.color = '#c62828';
            }
        } finally {
            if (botaoSalvar) botaoSalvar.disabled = false;
        }
    });

    async function excluirFornecedor(botao) {
        const id = botao.dataset.id;
        const nome = botao.dataset.nome || 'este fornecedor';
        elementoAnteriorAcao = botao;

        const confirmado = await abrirModalAcao({
            titulo: 'Excluir fornecedor?',
            mensagem: `Tem certeza de que deseja excluir “${nome}”? Esta ação não poderá ser desfeita.`,
            tipo: 'warning',
            textoConfirmar: 'Excluir fornecedor'
        });

        if (!confirmado) return;

        const dados = new FormData();
        dados.append('id_fornecedor', id);
        botao.disabled = true;

        try {
            const response = await fetch('../Back_end/processamentos/deletar_fornecedor.php', {
                method: 'POST',
                body: dados
            });
            const resultado = await response.json();

            if (!response.ok || !resultado.sucesso) {
                await abrirModalAcao({
                    titulo: 'Não foi possível excluir',
                    mensagem: resultado.erro || 'Ocorreu um erro ao excluir o fornecedor.',
                    tipo: 'error',
                    confirmar: false
                });
                return;
            }

            mostrarPopupSucesso(
                'Fornecedor excluído!',
                resultado.mensagem || 'O fornecedor foi excluído com sucesso.'
            );
            const carregado = await carregarFornecedores();
            if (!carregado && feedbackEl) {
                feedbackEl.hidden = false;
                feedbackEl.textContent = 'O fornecedor foi excluído, mas não foi possível atualizar a lista.';
                feedbackEl.style.color = '#c62828';
            }
        } catch (error) {
            console.error('Erro ao excluir fornecedor:', error);
            await abrirModalAcao({
                titulo: 'Falha na conexão',
                mensagem: 'Não foi possível excluir o fornecedor. Verifique a conexão e tente novamente.',
                tipo: 'error',
                confirmar: false
            });
        } finally {
            if (botao.isConnected) botao.disabled = false;
        }
    }

    // Inicialização
    carregarFornecedores();
});