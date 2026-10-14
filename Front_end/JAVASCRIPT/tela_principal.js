// JavaScript do gráfico de entradas e saídas do Tera Atacado.
// O PHP disponibiliza os dados em window.dadosMovimentacoes.

document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('movimentacoesChart');
    const seletorPeriodo = document.getElementById('periodoMovimentacoes');

    if (!canvas) {
        console.error('Não foi encontrado o canvas #movimentacoesChart.');
        return;
    }

    if (typeof Chart === 'undefined') {
        console.error('Chart.js não foi carregado. Verifique a conexão com a biblioteca Chart.js.');
        return;
    }

    const dados = window.dadosMovimentacoes;

    if (
        !dados ||
        !Array.isArray(dados.labels) ||
        !Array.isArray(dados.entradas) ||
        !Array.isArray(dados.saidas)
    ) {
        console.error('Os dados de movimentação enviados pelo PHP não estão no formato esperado.');
        return;
    }

    // Cria o gráfico com os dados que vieram do PHP.
    new Chart(canvas, {
        type: 'bar',

        data: {
            labels: dados.labels,

            datasets: [
                {
                    label: 'Entradas',
                    data: dados.entradas,
                    backgroundColor: 'rgba(85, 134, 71, 0.80)',
                    borderColor: '#558647',
                    borderWidth: 1,
                    borderRadius: 4
                },
                {
                    label: 'Saídas',
                    data: dados.saidas,
                    backgroundColor: 'rgba(255, 118, 0, 0.75)',
                    borderColor: '#ff7600',
                    borderWidth: 1,
                    borderRadius: 4
                }
            ]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            interaction: {
                mode: 'index',
                intersect: false
            },

            plugins: {
                legend: {
                    position: 'bottom'
                },

                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },

            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },

                y: {
                    beginAtZero: true,

                    ticks: {
                        precision: 0
                    },

                    title: {
                        display: true,
                        text: 'Quantidade'
                    }
                }
            }
        }
    });

    // Mantém o filtro sincronizado com o período processado pelo PHP.
    if (seletorPeriodo) {
        const parametros = new URLSearchParams(window.location.search);
        const periodoAtual = parametros.get('periodo') || '30d';
        const periodosPermitidos = ['7d', '30d', '6m'];

        seletorPeriodo.value = periodosPermitidos.includes(periodoAtual)
            ? periodoAtual
            : '30d';

        seletorPeriodo.addEventListener('change', function () {
            const url = new URL(window.location.href);

            url.searchParams.set('periodo', seletorPeriodo.value);

            window.location.href = url.toString();
        });
    }
});