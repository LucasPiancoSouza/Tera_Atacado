const senha = document.querySelector('#senha');
const botao = document.querySelector('.password-toggle');
// const formulario = document.querySelector('.login-form');

botao.addEventListener('click', () => {
    if (senha.type === 'password') {
        senha.type = 'text';
        botao.textContent = 'Ocultar';
    } else {
        senha.type = 'password';
        botao.textContent = 'Mostrar';
    }
});

const cpf = document.querySelector('#usuario');

cpf.addEventListener('input', () => {

    let valor = cpf.value;

    valor = valor.replace(/\D/g, '');

    valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
    valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
    valor = valor.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

    cpf.value = valor;
});
