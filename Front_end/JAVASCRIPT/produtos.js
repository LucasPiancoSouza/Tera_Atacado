document.addEventListener('DOMContentLoaded', function () {

    document.body.insertAdjacentHTML('beforeend', `
        <div class="category-modal" id="product-edit-modal" hidden>
            <div class="category-modal__backdrop" data-product-edit-cancel aria-hidden="true"></div>
            <section class="category-modal__dialog product-edit-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="product-edit-modal-title">
                <button class="category-modal__close" type="button" data-product-edit-cancel aria-label="Fechar">&times;</button>
                <span class="category-modal__icon" aria-hidden="true">✎</span>
                <p class="category-modal__eyebrow">TERA ATACADO</p>
                <h2 class="category-modal__title" id="product-edit-modal-title">Editar produto</h2>
                <p class="category-modal__message">As alterações serão aplicadas somente nesta tela e não serão salvas no sistema.</p>
                <form id="product-edit-form">
                    <div class="product-edit-modal__fields">
                        <div class="category-modal__field">
                            <label for="edit-codigo-produto">Código do produto</label>
                            <input id="edit-codigo-produto" name="codigo_produto" type="text" maxlength="13" required readonly >
                        </div>
                        <div class="category-modal__field">
                            <label for="edit-nome-produto">Nome do produto</label>
                            <input id="edit-nome-produto" name="nome_produto" type="text" maxlength="100" required>
                        </div>
                        <div class="category-modal__field">
                            <label for="edit-preco-produto">Preço (R$)</label>
                            <input id="edit-preco-produto" name="preco" type="number" min="0.01" step="0.01" required>
                        </div>
                        <div class="category-modal__field">
                            <label for="edit-fornecedor-produto">Fornecedor</label>
                            <select id="edit-fornecedor-produto" name="id_fornecedor" required></select>
                        </div>
                        <div class="category-modal__field">
                            <label for="edit-categoria-produto">Categoria</label>
                            <select id="edit-categoria-produto" name="id_categoria" required></select>
                        </div>
                        <div class="category-modal__field">
                            <label for="edit-codigo-barras-produto">Código de barras</label>
                            <input id="edit-codigo-barras-produto" name="codigo_de_barras" type="text" maxlength="128" required readonly >
                        </div>
                        <div class="category-modal__field">
                            <label for="edit-estoque-minimo-produto">Estoque mínimo (unidades)</label>
                            <input id="edit-estoque-minimo-produto" name="estoque_minimo" type="number" min="0" step="1" required>
                        </div>
                    </div>
                    <div class="category-modal__actions">
                        <button class="module-button module-button--secondary" type="button" data-product-edit-cancel>Cancelar</button>
                        <button class="module-button module-button--primary" type="submit">Salvar nesta tela</button>
                    </div>
                </form>
            </section>
        </div>
    `);

    document.body.insertAdjacentHTML('beforeend', `
        <div class="category-modal category-modal--error" id="product-error-modal" hidden>
            <div class="category-modal__backdrop" data-product-error-close aria-hidden="true"></div>
            <section class="category-modal__dialog" role="alertdialog" aria-modal="true" aria-labelledby="product-error-title" aria-describedby="product-error-message">
                <button class="category-modal__close" type="button" data-product-error-close aria-label="Fechar">&times;</button>
                <span class="category-modal__icon" aria-hidden="true">×</span>
                <p class="category-modal__eyebrow">TERA ATACADO</p>
                <h2 class="category-modal__title" id="product-error-title">Não foi possível concluir</h2>
                <p class="category-modal__message" id="product-error-message"></p>
                <div class="category-modal__actions">
                    <button class="module-button module-button--primary" type="button" data-product-error-close>Fechar</button>
                </div>
            </section>
        </div>
    `);

    document.querySelectorAll('#products-table button[title="Editar produto"]').forEach(button => {
        button.dataset.action = 'edit-product';
    });

    const errorModal = document.getElementById('product-error-modal');
    const errorModalTitle = document.getElementById('product-error-title');
    const errorModalMessage = document.getElementById('product-error-message');
    let elementoAnteriorErro = null;

    function mostrarPopupErro(mensagem, titulo = 'Não foi possível concluir') {
        if (!errorModal || !errorModalTitle || !errorModalMessage) {
            console.error('O pop-up de erro de produtos não foi encontrado.');
            return;
        }

        errorModalTitle.textContent = titulo;
        errorModalMessage.textContent = mensagem;
        elementoAnteriorErro = document.activeElement;
        errorModal.hidden = false;
        requestAnimationFrame(() => {
            errorModal.querySelector('[data-product-error-close]:not(.category-modal__backdrop)')?.focus();
        });
    }

    function fecharPopupErro() {
        if (!errorModal || errorModal.hidden) return;

        errorModal.hidden = true;
        if (elementoAnteriorErro && elementoAnteriorErro.isConnected) {
            elementoAnteriorErro.focus();
        }
        elementoAnteriorErro = null;
    }

    errorModal?.addEventListener('click', function (e) {
        if (e.target.closest('[data-product-error-close]')) {
            fecharPopupErro();
        }
    });

    const deleteModal = document.getElementById('product-delete-modal');
    const deleteModalMessage = document.getElementById('product-delete-modal-message');
    const deleteModalCancel = document.getElementById('product-delete-modal-cancel');
    const deleteModalConfirm = document.getElementById('product-delete-modal-confirm');
    const editModal = document.getElementById('product-edit-modal');
    const editForm = document.getElementById('product-edit-form');
    const editCode = document.getElementById('edit-codigo-produto');
    const editName = document.getElementById('edit-nome-produto');
    const editPrice = document.getElementById('edit-preco-produto');
    const editSupplier = document.getElementById('edit-fornecedor-produto');
    const editCategory = document.getElementById('edit-categoria-produto');
    const editBarcode = document.getElementById('edit-codigo-barras-produto');
    const editMinimumStock = document.getElementById('edit-estoque-minimo-produto');
    let resolverConfirmacaoExclusao = null;
    let elementoAnteriorModal = null;
    let linhaProdutoEmEdicao = null;
    let elementoAnteriorEdicao = null;

    function copiarOpcoesSelect(origem, destino) {
        if (!origem || !destino) {
            console.error('Não foi possível carregar as opções de categoria e fornecedor para edição.');
            return;
        }

        destino.replaceChildren(...Array.from(origem.options, option => option.cloneNode(true)));
    }

    const formularioCadastro = document.querySelector('.product-form');
    copiarOpcoesSelect(
        formularioCadastro?.querySelector('select[name="id_fornecedor"]'),
        editSupplier
    );
    copiarOpcoesSelect(
        formularioCadastro?.querySelector('select[name="id_categoria"]'),
        editCategory
    );

    function fecharModalEdicao() {
        if (!editModal || editModal.hidden) return;

        editModal.hidden = true;
        linhaProdutoEmEdicao = null;
        if (elementoAnteriorEdicao && elementoAnteriorEdicao.isConnected) {
            elementoAnteriorEdicao.focus();
        }
        elementoAnteriorEdicao = null;
    }

    editModal?.addEventListener('click', function (e) {
        if (e.target.closest('[data-product-edit-cancel]')) {
            fecharModalEdicao();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && errorModal && !errorModal.hidden) {
            fecharPopupErro();
            return;
        }

        if (e.key === 'Escape' && editModal && !editModal.hidden) {
            fecharModalEdicao();
        }
    });

    editForm?.addEventListener('submit', async function (e) {
    e.preventDefault();

    if (!linhaProdutoEmEdicao) {
        mostrarPopupErro('Nenhum produto foi selecionado para edição.');
        return;
    }

    // Pega o ID real do produto na linha selecionada
    const botaoExcluir = linhaProdutoEmEdicao.querySelector(
        '[data-action="delete-product"]'
    );

    const idProduto = botaoExcluir?.dataset.id;

    if (!idProduto) {
        mostrarPopupErro('Não foi possível identificar o produto.');
        return;
    }

    // Monta os dados que o PHP espera receber
    const dados = new FormData();

    dados.append('id_produto', idProduto);
    dados.append('nome_produto', editName.value.trim());
    dados.append('codigo_produto', editCode.value.trim());
    dados.append('preco', editPrice.value.replace(',', '.'));
    dados.append('codigo_de_barras', editBarcode.value.trim());
    dados.append('id_categoria', editCategory.value);
    dados.append('id_fornecedor', editSupplier.value);
    dados.append('estoque_minimo', editMinimumStock.value);

    try {
        const resposta = await fetch(
            '/Back_end/processamentos/editar_produto.php',
            {
                method: 'POST',
                body: dados
            }
        );

        const resultado = await resposta.json();

        if (!resposta.ok || !resultado.sucesso) {
            mostrarPopupErro(resultado.erro || 'Não foi possível editar o produto.');
            return;
        }

        // Só atualiza a tabela após o PHP confirmar a edição
        const campos = linhaProdutoEmEdicao.cells;
        const nome = editName.value.trim();

        campos[0].textContent = editCode.value.trim();
        campos[1].textContent = nome;

        campos[2].textContent = Number(editPrice.value).toLocaleString(
            'pt-BR',
            {
                style: 'currency',
                currency: 'BRL'
            }
        );

        campos[3].textContent =
            editSupplier.selectedOptions[0]?.textContent.trim() || '';

        campos[4].textContent =
            editCategory.selectedOptions[0]?.textContent.trim() || '';

        campos[5].textContent = editBarcode.value.trim();
        campos[6].textContent = `${editMinimumStock.value} un.`;

        linhaProdutoEmEdicao.dataset.categoriaId = editCategory.value;

        if (botaoExcluir) {
            botaoExcluir.dataset.nome = nome;
            botaoExcluir.setAttribute(
                'aria-label',
                `Excluir produto ${nome}`
            );
        }

        const botaoEditar = linhaProdutoEmEdicao.querySelector(
            '[data-action="edit-product"]'
        );

        if (botaoEditar) {
            botaoEditar.setAttribute(
                'aria-label',
                `Editar produto ${nome}`
            );
        }

        fecharModalEdicao();

        document.getElementById('product-search')?.dispatchEvent(
            new Event('input')
        );

        mostrarPopupSucesso(
            'Produto atualizado!',
            resultado.mensagem || 'Produto atualizado com sucesso.'
        );

    } catch (erro) {
        console.error('Erro ao editar produto:', erro);
        mostrarPopupErro('Erro ao comunicar com o servidor.');
    }
});

    function confirmarExclusaoProduto(nomeProduto) {
        if (!deleteModal || !deleteModalMessage || !deleteModalCancel || !deleteModalConfirm) {
            console.error('O modal de confirmação de exclusão não foi encontrado na página.');
            return Promise.resolve(false);
        }

        deleteModalMessage.textContent = `Tem certeza de que deseja excluir “${nomeProduto}”? Esta ação não poderá ser desfeita.`;
        elementoAnteriorModal = document.activeElement;
        deleteModal.hidden = false;

        return new Promise(resolve => {
            resolverConfirmacaoExclusao = resolve;
            requestAnimationFrame(() => deleteModalCancel.focus());
        });
    }

    function fecharModalExclusao(confirmado) {
        if (!deleteModal || deleteModal.hidden) return;

        deleteModal.hidden = true;
        const resolver = resolverConfirmacaoExclusao;
        resolverConfirmacaoExclusao = null;
        if (resolver) resolver(confirmado);

        if (elementoAnteriorModal && elementoAnteriorModal.isConnected) {
            elementoAnteriorModal.focus();
        }
        elementoAnteriorModal = null;
    }

    deleteModal?.addEventListener('click', function (e) {
        if (e.target.closest('[data-product-modal-cancel]')) {
            fecharModalExclusao(false);
        }
    });

    deleteModalCancel?.addEventListener('click', function () {
        fecharModalExclusao(false);
    });

    deleteModalConfirm?.addEventListener('click', function () {
        fecharModalExclusao(true);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && deleteModal && !deleteModal.hidden) {
            fecharModalExclusao(false);
        }
    });

    const formProduto = document.getElementById('produto-form') ||
    document.querySelector('.product-form');
    const popupSucesso = document.getElementById('popup-sucesso');
    const popupSucessoTitulo = document.getElementById('popup-sucesso-titulo');
    const popupSucessoMensagem = document.getElementById('popup-sucesso-mensagem');
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

    if (formProduto) {
        formProduto.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(formProduto);

            fetch('/Back_end/processamentos/cadastro_produto.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.sucesso) {
                    mostrarPopupSucesso(
                        'Produto cadastrado!',
                        result.mensagem || 'O produto foi cadastrado com sucesso.'
                    );
                    formProduto.reset();
                    window.setTimeout(() => window.location.reload(), 2300);
                } else {
                    mostrarPopupErro(result.erro || 'Erro ao cadastrar produto.');
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                mostrarPopupErro('Erro de conexão ao tentar cadastrar o produto.');
            });
        });
    }

    document.addEventListener('click', async function (e) {
        const editButton = e.target.closest('[data-action="edit-product"]');
        if (editButton) {
            const linha = editButton.closest('tr');
            if (!linha || !editForm || !editModal) {
                mostrarPopupErro('Não foi possível abrir a edição deste produto.');
                return;
            }

            const valores = Array.from(linha.cells, cell => cell.textContent.trim());
            linhaProdutoEmEdicao = linha;
            elementoAnteriorEdicao = editButton;
            editCode.value = valores[0];
            editName.value = valores[1];
            editPrice.value = valores[2]
                .replace(/[^\d,.-]/g, '')
                .replace(/\./g, '')
                .replace(',', '.');
            editBarcode.value = valores[5];
            editMinimumStock.value = valores[6].replace(/[^\d]/g, '');
            editCategory.value = linha.dataset.categoriaId;

            const fornecedor = Array.from(editSupplier.options).find(
                option => option.textContent.trim() === valores[3]
            );
            editSupplier.value = fornecedor ? fornecedor.value : '';

            editModal.hidden = false;
            requestAnimationFrame(() => editCode.focus());
            return;
        }

        const deleteButton = e.target.closest('[data-action="delete-product"]');
        if (!deleteButton) return;

        const idProduto = deleteButton.getAttribute('data-id');
        const nomeProduto = deleteButton.getAttribute('data-nome') || 'este produto';

        if (!await confirmarExclusaoProduto(nomeProduto)) return;

        const dados = new FormData();
        dados.append('id_produto', idProduto);
        deleteButton.disabled = true;

        try {
            const response = await fetch('/Back_end/processamentos/deletar_produto.php', {
                method: 'POST',
                body: dados
            });
            const resultado = await response.json();

            if (!response.ok || !resultado.sucesso) {
                throw new Error(resultado.erro || 'Não foi possível excluir o produto.');
            }

            mostrarPopupSucesso(
                'Produto excluído!',
                resultado.mensagem || 'Produto excluído com sucesso.'
            );
            window.setTimeout(() => window.location.reload(), 2300);
        } catch (error) {
            console.error('Erro ao excluir produto:', error);
            mostrarPopupErro(error.message || 'Não foi possível excluir o produto. Verifique a conexão e tente novamente.');
            deleteButton.disabled = false;
        }
    });

    const filtroCategoria = document.getElementById('category-filter');
    const tabela = document.getElementById('products-table');
    const campoPesquisa = document.getElementById('product-search');
    const id_limpar = document.getElementById('clear-product-filters');

    if (filtroCategoria && tabela && campoPesquisa) {
        const linhas = tabela.querySelectorAll('tbody tr');

        function aplicarFiltros() {
            const categoriaSelecionada = filtroCategoria.value;
            const textoPesquisa = campoPesquisa.value.toLowerCase().trim();

            linhas.forEach(function (linha) {
                const categoriaProduto = linha.dataset.categoriaId;
                const textoProduto = linha.textContent.toLowerCase();

                const correspondeCategoria =
                    categoriaSelecionada === '' ||
                    categoriaProduto === categoriaSelecionada;

                const correspondePesquisa =
                    textoProduto.includes(textoPesquisa);

                if (correspondeCategoria && correspondePesquisa) {
                    linha.style.display = '';
                } else {
                    linha.style.display = 'none';
                }
            });
        }

        campoPesquisa.addEventListener('input', aplicarFiltros);
        filtroCategoria.addEventListener('change', aplicarFiltros);

        if (id_limpar) {
            id_limpar.addEventListener('click', function () {
                filtroCategoria.value = '';
                campoPesquisa.value = '';
                aplicarFiltros();
            });
        }
    }
    const botaoExportar = document.getElementById("export-products");

        if (botaoExportar !== null){
            botaoExportar.addEventListener('click', function () {
                const linhas = tabela.querySelectorAll("tbody tr");
                const linhasCSV = [];
                const cabecalhos = Array.from(
                tabela.querySelectorAll("thead th")
                )
                .slice(0, 7)
                .map(th => th.textContent.trim());
                const cabecalhoCSV = cabecalhos.join(';');
                linhasCSV.unshift(cabecalhoCSV);

                linhas.forEach(function(linha){
                    const celulas  = Array.from(linha.cells).slice(0,7);
                    const textos = celulas.map(
                        celula => celula.textContent.trim()
                    );
                    const linhaCSV = textos.join(";")
                    linhasCSV.push(linhaCSV)
                })
                const conteudoCSV = linhasCSV.join("\n");
                const arquivo = new Blob(['\uFEFF',conteudoCSV],
                    {type: 'text/csv;charset=utf-8;'}
                )
                const url = URL.createObjectURL(arquivo);
                const link = document.createElement('a');
                link.download = "produtos.csv"
                link.href = url
                document.body.appendChild(link);
                link.click();
        });
        }
       
});