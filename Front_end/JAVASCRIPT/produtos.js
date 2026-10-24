'use strict';

document.addEventListener('DOMContentLoaded', function () {
    // --- CADASTRO DE NOVO PRODUTO COM ALERTA E ATUALIZAÇÃO AUTOMÁTICA ---
    const formProduto = document.getElementById('produto-form') || document.querySelector('.product-form');

    if (formProduto) {
        formProduto.addEventListener('submit', function (e) {
            e.preventDefault(); // Evita o recarregamento bruto da página

            const formData = new FormData(formProduto);

            fetch('/Back_end/processamentos/cadastro_produto.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.sucesso) {
                    alert(result.mensagem || 'Produto cadastrado com sucesso!');
                    formProduto.reset(); // Limpa os campos
                    
                    // Recarrega a página para atualizar a tabela gerada pelo PHP
                    window.location.reload(); 
                } else {
                    alert(result.erro || 'Erro ao cadastrar produto.');
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                alert('Erro de conexão ao tentar cadastrar o produto.');
            });
        });
    }
});