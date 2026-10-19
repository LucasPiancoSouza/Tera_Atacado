'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('exit-form');
    if (!form) return;

    // O formulário é enviado ao PHP; o servidor valida estoque e movimentação.
    form.method = 'post';

    const dateInput = document.getElementById('exit-date');
    if (dateInput && !dateInput.value) {
        const today = new Date();
        dateInput.value = [
            today.getFullYear(),
            String(today.getMonth() + 1).padStart(2, '0'),
            String(today.getDate()).padStart(2, '0')
        ].join('-');
    }
});
'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('exit-form');

    if (!form) return;

    const productInput = document.getElementById('exit-product');
    const locationInput = document.getElementById('exit-location');
    const quantityInput = document.getElementById('exit-quantity');
    const reasonInput = document.getElementById('exit-reason');
    const observationInput = document.getElementById('exit-observation');
    const feedback = document.getElementById('exit-feedback');

    const previewProduct = document.getElementById('exit-preview-product');
    const previewCurrent = document.getElementById('exit-preview-current');
    const previewChange = document.getElementById('exit-preview-change');
    const previewNew = document.getElementById('exit-preview-new');

    let estoqueAtual = 0;
    let consultaAtual = 0;
    let consultaValida = false;

    function mostrarMensagem(mensagem) {
        if (!feedback) return;

        feedback.textContent = mensagem;
        feedback.hidden = !mensagem;
    }

    function atualizarPrevia() {
        const quantidade = Number.parseInt(quantityInput.value, 10) || 0;

        if (previewProduct) {
            previewProduct.textContent =
                productInput.selectedOptions[0]?.textContent.trim() ||
                'Selecione um produto';
        }

        if (previewCurrent) {
            previewCurrent.textContent = consultaValida
                ? `${estoqueAtual} unidades`
                : '—';
        }

        if (previewChange) {
            previewChange.textContent = `−${quantidade} unidades`;
        }

        if (previewNew) {
            previewNew.textContent = consultaValida
                ? `${estoqueAtual - quantidade} unidades`
                : '—';
        }
    }

    async function consultarEstoque() {
        const idProduto = productInput.value;
        const idLocalizacao = locationInput.value;
        const numeroConsulta = ++consultaAtual;

        estoqueAtual = 0;
        consultaValida = false;

        mostrarMensagem('');

        if (!idProduto || !idLocalizacao) {
            atualizarPrevia();
            return;
        }

        if (previewCurrent) {
            previewCurrent.textContent = 'Consultando...';
        }

        try {
            const parametros = new URLSearchParams({
                id_produto: idProduto,
                id_localizacao: idLocalizacao
            });

            const resposta = await fetch(
                `../Back_end/processamentos/consultar_estoque.php?${parametros}`
            );

            const dados = await resposta.json();

            if (!resposta.ok) {
                throw new Error(
                    dados.erro || 'Não foi possível consultar o estoque.'
                );
            }

            // Ignora respostas antigas se a seleção mudar durante a consulta.
            if (numeroConsulta !== consultaAtual) return;

            const quantidadeAtual = Number(dados.estoque_atual);

            if (
                !Number.isInteger(quantidadeAtual) ||
                quantidadeAtual < 0
            ) {
                throw new Error('A consulta retornou um estoque inválido.');
            }

            estoqueAtual = quantidadeAtual;
            consultaValida = true;

            atualizarPrevia();

            if (Number.parseInt(quantityInput.value, 10) > estoqueAtual) {
                mostrarMensagem('A quantidade informada é maior que o estoque disponível.');
            }
        } catch (erro) {
            if (numeroConsulta !== consultaAtual) return;

            console.error('Erro ao consultar estoque:', erro);

            if (previewCurrent) {
                previewCurrent.textContent = 'Erro na consulta';
            }

            if (previewNew) {
                previewNew.textContent = '—';
            }

            mostrarMensagem('Não foi possível consultar o estoque. Tente novamente.');
        }
    }

    productInput.addEventListener('change', consultarEstoque);
    locationInput.addEventListener('change', consultarEstoque);

    quantityInput.addEventListener('input', function () {
        atualizarPrevia();

        const quantidade = Number.parseInt(quantityInput.value, 10) || 0;

        if (consultaValida && quantidade > estoqueAtual) {
            mostrarMensagem('A quantidade informada é maior que o estoque disponível.');
        } else {
            mostrarMensagem('');
        }
    });

    form.addEventListener('submit', function (evento) {
        const quantidade = Number.parseInt(quantityInput.value, 10);

        if (!consultaValida) {
            evento.preventDefault();
            mostrarMensagem('Selecione o produto e o local e aguarde a consulta do estoque.');
            return;
        }

        if (!Number.isInteger(quantidade) || quantidade <= 0) {
            evento.preventDefault();
            mostrarMensagem('Informe uma quantidade válida.');
            return;
        }

        if (quantidade > estoqueAtual) {
            evento.preventDefault();
            mostrarMensagem('Estoque insuficiente para registrar essa saída.');
        }
    });

    form.addEventListener('reset', function () {
        consultaAtual++;
        estoqueAtual = 0;
        consultaValida = false;

        setTimeout(function () {
            mostrarMensagem('');
            atualizarPrevia();
        }, 0);
    });

    atualizarPrevia();
});
const popup = document.getElementById("popup-sucesso");

const parametros = new URLSearchParams(window.location.search);

if (parametros.get("sucesso") === "1" && popup) {
    popup.classList.add("mostrar");

    setTimeout(() => {
        popup.classList.remove("mostrar");
    }, 2000);
}
const parametrosUrl = new URLSearchParams(window.location.search);

const mensagensErro = {
    dados_invalidos: 'Confira os dados informados.',
    produto_local_nao_encontrado:
        'Esse produto não possui registro de estoque no local selecionado.',
    estoque_insuficiente:
        'Estoque insuficiente para realizar essa saída.',
    falha:
        'Não foi possível registrar a saída. Consulte o log do servidor.'
};

if (parametrosUrl.get('sucesso') === '1') {
    mostrarMensagem('Saída registrada com sucesso!');
} else {
    const erro = parametrosUrl.get('erro');

    if (erro && mensagensErro[erro]) {
        mostrarMensagem(mensagensErro[erro]);
    }
}