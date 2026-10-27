'use strict';

const paginaAtual = window.location.pathname.split('/').pop().toLowerCase();

document.querySelectorAll('.sidebar a.menu-item[href]').forEach(link => {
    if (link.getAttribute('href').startsWith('#')) {
        link.classList.remove('active');
        link.removeAttribute('aria-current');
        return;
    }

    const paginaDoLink = new URL(link.href, window.location.href)
        .pathname
        .split('/')
        .pop()
        .toLowerCase();
    const estaAtiva = paginaDoLink === paginaAtual;

    link.classList.toggle('active', estaAtiva);

    if (estaAtiva) {
        link.setAttribute('aria-current', 'page');
    } else {
        link.removeAttribute('aria-current');
    }
});
