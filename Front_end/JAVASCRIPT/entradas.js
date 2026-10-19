'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('entry-form');
    if (!form) return;

    const productInput = document.getElementById('entry-product');
    const locationInput = document.getElementById('entry-location');
    const quantityInput = document.getElementById('entry-quantity');
    const dateInput = document.getElementById('entry-date');
    const observationInput = document.getElementById('entry-observation');
    const feedback = document.getElementById('entry-feedback');
    const clearButton = document.getElementById('entry-clear');
    
const previewProduct = document.getElementById('entry-preview-product');
const previewCurrent = document.getElementById('entry-preview-current');
const previewChange = document.getElementById('entry-preview-change');
const previewNew = document.getElementById('entry-preview-new');

let estoqueAtual = 0;
let consultaAtual = 0;

function atualizarPrevia() {
    const quantidade = Number.parseInt(quantityInput.value, 10) || 0;

    if (previewCurrent) {
        previewCurrent.textContent = `${estoqueAtual} unidades`;
    }

    if (previewChange) {
        previewChange.textContent = `+${quantidade} unidades`;
    }

    if (previewNew) {
        previewNew.textContent = `${estoqueAtual + quantidade} unidades`;
    }
}

async function consultarEstoque() {
    const idProduto = productInput.value;
    const idLocalizacao = locationInput.value;
    const numeroConsulta = ++consultaAtual;

    estoqueAtual = 0;

    if (previewProduct) {
        previewProduct.textContent =
            productInput.selectedOptions[0]?.textContent.trim() ||
            'Selecione um produto';
    }

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
            throw new Error(dados.erro || 'Falha na consulta');
        }

        // Ignora respostas antigas se o usuário trocar a seleção rapidamente.
        if (numeroConsulta !== consultaAtual) return;

        estoqueAtual = dados.estoque_atual;
        atualizarPrevia();
    } catch (erro) {
        if (numeroConsulta !== consultaAtual) return;

        if (previewCurrent) {
            previewCurrent.textContent = 'Erro na consulta';
        }

        if (previewNew) {
            previewNew.textContent = '—';
        }

        console.error('Erro ao consultar estoque:', erro);
    }
}

productInput.addEventListener('change', consultarEstoque);
locationInput.addEventListener('change', consultarEstoque);
quantityInput.addEventListener('input', atualizarPrevia);


    // O formulário é enviado ao PHP; o servidor valida e grava a movimentação.
    form.method = 'post';

    if (dateInput && !dateInput.value) {
        const today = new Date();
        dateInput.value = [
            today.getFullYear(),
            String(today.getMonth() + 1).padStart(2, '0'),
            String(today.getDate()).padStart(2, '0')
        ].join('-');
    }

    clearButton?.addEventListener('click', function () {
        if (productInput) productInput.selectedIndex = -1;
        if (locationInput) locationInput.value = '';
        if (quantityInput) quantityInput.value = '';
        if (dateInput) dateInput.value = '';
        if (observationInput) observationInput.value = '';
        if (feedback) {
            feedback.hidden = true;
            feedback.textContent = '';
        }
        estoqueAtual = 0;
        atualizarPrevia();

        if (previewProduct) {
            previewProduct.textContent = 'Selecione um produto';
        }
        productInput?.focus();
    });
});
console.log("Arquivo entradas.js carregado");

const popup = document.getElementById("popup-sucesso");
console.log("Pop-up encontrado:", popup);

const parametros = new URLSearchParams(window.location.search);
console.log("Parâmetro sucesso:", parametros.get("sucesso"));

if (parametros.get("sucesso") === "1" && popup) {
    popup.classList.add("mostrar");

    setTimeout(() => {
        popup.classList.remove("mostrar");
    }, 2000);
}