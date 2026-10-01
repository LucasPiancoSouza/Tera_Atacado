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

// formulario.addEventListener('submit', (event) => {
//     event.preventDefault();

//     window.location.href = "tela_principal.html";
// });