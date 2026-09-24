const passwordInput = document.querySelector('#senha');
const passwordToggle = document.querySelector('.password-toggle');

if (passwordInput && passwordToggle) {
    passwordToggle.addEventListener('click', () => {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        passwordToggle.textContent = isPassword ? 'Ocultar' : 'Mostrar';
        passwordToggle.setAttribute('aria-pressed', String(isPassword));
        passwordToggle.setAttribute(
            'aria-label',
            isPassword ? 'Ocultar senha' : 'Mostrar senha'
        );
    });
}
