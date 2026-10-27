'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('#categories-table tbody');
    const searchInput = document.getElementById('category-search');
    const categoryForm = document.getElementById('category-form');
    const categoryCount = document.getElementById('category-count');
    const emptyState = document.getElementById('category-empty');
    const categoryModal = document.getElementById('category-modal');
    const modalIcon = document.getElementById('category-modal-icon');
    const modalTitle = document.getElementById('category-modal-title');
    const modalMessage = document.getElementById('category-modal-message');
    const modalField = document.getElementById('category-modal-field');
    const modalInput = document.getElementById('category-modal-input');
    const modalCancel = document.getElementById('category-modal-cancel');
    const modalConfirm = document.getElementById('category-modal-confirm');
    const popupSucesso = document.getElementById('popup-sucesso');
    const popupSucessoTitulo = document.getElementById('popup-sucesso-titulo');
    const popupSucessoMensagem = document.getElementById('popup-sucesso-mensagem');

    let categoriasData = [];
    let resolverPopup = null;
    let elementoAnterior = null;
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

    function abrirPopup({ titulo, mensagem, tipo = 'info', textoConfirmar = 'Fechar', textoCancelar = '', campo = false, valorCampo = '' }) {
        if (!categoryModal) {
            console.error('O modal de categorias não foi encontrado na página.');
            return Promise.resolve({ confirmado: false, valor: '' });
        }

        const simbolos = { info: 'i', success: '✓', warning: '!', error: '×' };
        categoryModal.classList.remove('category-modal--info', 'category-modal--success', 'category-modal--warning', 'category-modal--error');
        categoryModal.classList.add(`category-modal--${tipo}`);
        modalIcon.textContent = simbolos[tipo] || simbolos.info;
        modalTitle.textContent = titulo;
        modalMessage.textContent = mensagem;
        modalField.hidden = !campo;
        modalInput.value = valorCampo;
        modalCancel.hidden = !textoCancelar;
        modalCancel.textContent = textoCancelar;
        modalConfirm.textContent = textoConfirmar;
        modalConfirm.classList.toggle('category-modal__confirm--danger', tipo === 'warning');

        elementoAnterior = document.activeElement;
        categoryModal.hidden = false;

        return new Promise(resolve => {
            resolverPopup = resolve;
            requestAnimationFrame(() => {
                if (campo) {
                    modalInput.focus();
                    modalInput.select();
                } else {
                    modalConfirm.focus();
                }
            });
        });
    }

    function fecharPopup(resultado) {
        if (!categoryModal || categoryModal.hidden) return;

        categoryModal.hidden = true;
        const resolver = resolverPopup;
        resolverPopup = null;
        if (resolver) resolver(resultado);

        if (elementoAnterior && elementoAnterior.isConnected) elementoAnterior.focus();
        elementoAnterior = null;
    }

    categoryModal?.addEventListener('click', function (e) {
        if (e.target.closest('[data-modal-cancel]')) {
            fecharPopup({ confirmado: false, valor: '' });
        }
    });

    modalCancel?.addEventListener('click', function () {
        fecharPopup({ confirmado: false, valor: '' });
    });

    modalConfirm?.addEventListener('click', function () {
        let valor = '';
        if (!modalField.hidden) {
            valor = modalInput.value.trim();
            if (!valor) {
                modalInput.setCustomValidity('Digite o nome da categoria.');
                modalInput.reportValidity();
                return;
            }
            modalInput.setCustomValidity('');
        }
        fecharPopup({ confirmado: true, valor });
    });

    modalInput?.addEventListener('input', function () {
        modalInput.setCustomValidity('');
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && categoryModal && !categoryModal.hidden) {
            fecharPopup({ confirmado: false, valor: '' });
        }
    });

    // Carrega as categorias cadastradas no backend.
    function carregarCategorias() {
        return fetch('../Back_end/processamentos/listar_categorias.php')
            .then(response => {
                if (!response.ok) throw new Error(`Falha ao carregar categorias (${response.status}).`);
                return response.json();
            })
            .then(data => {
                categoriasData = data;
                renderizarTabela(categoriasData);
            })
            .catch(error => console.error('Erro ao carregar categorias:', error));
    }

    function renderizarTabela(dados) {
        if (!tableBody) return;
        tableBody.innerHTML = '';

        if (!dados || dados.length === 0) {
            if (emptyState) emptyState.hidden = false;
            if (categoryCount) categoryCount.textContent = '0 categorias';
            return;
        }

        if (emptyState) emptyState.hidden = true;
        if (categoryCount) categoryCount.textContent = `${dados.length} ${dados.length === 1 ? 'categoria' : 'categorias'}`;

        dados.forEach(cat => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><strong>${escapeHtml(cat.nome_categoria)}</strong></td>
                <td>${formatarData(cat.data_criacao)}</td>
                <td>
                    <div class="module-actions">
                        <button type="button" class="icon-button" data-action="edit-category" data-id="${escapeHtml(cat.id_categoria)}" data-nome="${escapeHtml(cat.nome_categoria)}" title="Editar" aria-label="Editar categoria ${escapeHtml(cat.nome_categoria)}">✎</button>
                        <button type="button" class="icon-button icon-button--danger" data-action="delete-category" data-id="${escapeHtml(cat.id_categoria)}" data-nome="${escapeHtml(cat.nome_categoria)}" title="Excluir" aria-label="Excluir categoria ${escapeHtml(cat.nome_categoria)}">×</button>
                    </div>
                </td>
            `;
            tableBody.appendChild(tr);
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const termo = searchInput.value.toLowerCase().trim();
            const filtradas = categoriasData.filter(cat =>
                String(cat.nome_categoria ?? '').toLowerCase().includes(termo)
            );
            renderizarTabela(filtradas);
        });
    }

    if (categoryForm) {
        categoryForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(categoryForm);

            try {
                const response = await fetch('/Back_end/processamentos/cadastrar_categoria.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.sucesso) {
                    categoryForm.reset();
                    mostrarPopupSucesso(
                        'Categoria cadastrada!',
                        result.mensagem || 'A categoria foi cadastrada com sucesso.'
                    );
                    await carregarCategorias();
                } else {
                    await abrirPopup({
                        titulo: 'Não foi possível cadastrar',
                        mensagem: result.erro || 'Ocorreu um erro ao cadastrar a categoria.',
                        tipo: 'error'
                    });
                }
            } catch (error) {
                console.error('Erro ao cadastrar categoria:', error);
                await abrirPopup({
                    titulo: 'Falha na conexão',
                    mensagem: 'Não foi possível concluir o cadastro. Verifique a conexão e tente novamente.',
                    tipo: 'error'
                });
            }
        });
    }

    document.addEventListener('click', async function (e) {
        const deleteButton = e.target.closest('[data-action="delete-category"]');
        if (deleteButton) {
            const id = deleteButton.getAttribute('data-id');
            const nome = deleteButton.getAttribute('data-nome') || 'esta categoria';
            const confirmacao = await abrirPopup({
                titulo: 'Excluir categoria?',
                mensagem: `Tem certeza de que deseja excluir “${nome}”? Esta ação não poderá ser desfeita.`,
                tipo: 'warning',
                textoConfirmar: 'Excluir categoria'
            });

            if (!confirmacao.confirmado) return;

            const formData = new FormData();
            formData.append('id_categoria', id);

            try {
                const response = await fetch('/Back_end/processamentos/deletar_categoria.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.sucesso) {
                    mostrarPopupSucesso(
                        'Categoria excluída!',
                        result.mensagem || 'A categoria foi excluída com sucesso.'
                    );
                    await carregarCategorias();
                } else {
                    await abrirPopup({
                        titulo: 'Não foi possível excluir',
                        mensagem: result.erro || 'Ocorreu um erro ao excluir a categoria.',
                        tipo: 'error'
                    });
                }
            } catch (error) {
                console.error('Erro ao excluir categoria:', error);
                await abrirPopup({
                    titulo: 'Falha na conexão',
                    mensagem: 'Não foi possível excluir a categoria. Verifique a conexão e tente novamente.',
                    tipo: 'error'
                });
            }
            return;
        }

        const editButton = e.target.closest('[data-action="edit-category"]');
        if (!editButton) return;

        const id = editButton.getAttribute('data-id');
        const nomeAtual = editButton.getAttribute('data-nome') || '';
        const edicao = await abrirPopup({
            titulo: 'Editar categoria',
            mensagem: 'Atualize o nome da categoria no campo abaixo.',
            tipo: 'info',
            textoConfirmar: 'Salvar alterações',
            campo: true,
            valorCampo: nomeAtual
        });

        if (!edicao.confirmado) return;

        const formData = new FormData();
        formData.append('id_categoria', id);
        formData.append('nome_categoria', edicao.valor);

        try {
            const response = await fetch('/Back_end/processamentos/editar_categoria.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.sucesso) {
                mostrarPopupSucesso(
                    'Categoria atualizada!',
                    result.mensagem || 'O nome da categoria foi atualizado com sucesso.'
                );
                await carregarCategorias();
            } else {
                await abrirPopup({
                    titulo: 'Não foi possível atualizar',
                    mensagem: result.erro || 'Ocorreu um erro ao editar a categoria.',
                    tipo: 'error'
                });
            }
        } catch (error) {
            console.error('Erro ao editar categoria:', error);
            await abrirPopup({
                titulo: 'Falha na conexão',
                mensagem: 'Não foi possível atualizar a categoria. Verifique a conexão e tente novamente.',
                tipo: 'error'
            });
        }
    });

    function escapeHtml(text) {
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return String(text ?? '').replace(/[&<>"']/g, character => map[character]);
    }

    function formatarData(data) {
        if (!data) return '-';

        const partes = String(data).slice(0, 10).split('-');
        if (partes.length !== 3) return escapeHtml(String(data));

        const [ano, mes, dia] = partes;
        return `${escapeHtml(dia)}/${escapeHtml(mes)}/${escapeHtml(ano)}`;
    }

    carregarCategorias();
});
