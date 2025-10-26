/**
 * Gestion des graphiques Chart.js
 */

// Configuration globale Chart.js
Chart.defaults.font.family = 'Inter';
Chart.defaults.font.size = 12;
Chart.defaults.plugins.legend.display = true;
Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(0, 0, 0, 0.8)';
Chart.defaults.plugins.tooltip.cornerRadius = 8;

const chartColors = {
    primary: '#0d6efd',
    success: '#198754',
    danger: '#dc3545',
    warning: '#ffc107',
    info: '#0dcaf0',
    secondary: '#6c757d'
};

export function initCharts() {
    initDashboardCharts();
    initFinancialCharts();
    initProgressCharts();
}

/**
 * Graphiques de dashboard
 */
function initDashboardCharts() {
    // Graphique des adhésions par mois
    const adherentsChart = document.getElementById('adherentsChart');
    if (adherentsChart) {
        createLineChart(adherentsChart, {
            url: '/api/dashboard/adherents-par-mois',
            title: 'Évolution des adhésions',
            color: chartColors.primary
        });
    }
    
    // Graphique des statuts de documents
    const documentsChart = document.getElementById('documentsChart');
    if (documentsChart) {
        createDoughnutChart(documentsChart, {
            url: '/api/dashboard/statuts-documents',
            title: 'Statuts des documents'
        });
    }
    
    // Graphique des types de bénéficiaires
    const beneficiairesChart = document.getElementById('beneficiairesChart');
    if (beneficiairesChart) {
        createPieChart(beneficiairesChart, {
            url: '/api/dashboard/types-beneficiaires',
            title: 'Types de bénéficiaires'
        });
    }
}

/**
 * Graphiques financiers
 */
function initFinancialCharts() {
    // Graphique des épargnes par mois
    const epargneChart = document.getElementById('epargneChart');
    if (epargneChart) {
        createAreaChart(epargneChart, {
            url: '/api/dashboard/evolution-epargne',
            title: 'Évolution de l\'épargne',
            color: chartColors.success
        });
    }
    
    // Graphique des crédits
    const creditsChart = document.getElementById('creditsChart');
    if (creditsChart) {
        createBarChart(creditsChart, {
            url: '/api/dashboard/evolution-credits',
            title: 'Évolution des crédits'
        });
    }
    
    // Comparaison épargne vs crédits
    const comparisonChart = document.getElementById('comparisonChart');
    if (comparisonChart) {
        createComparisionChart(comparisonChart);
    }
}

/**
 * Barres de progression animées
 */
function initProgressCharts() {
    const progressBars = document.querySelectorAll('[data-progress-chart]');
    
    progressBars.forEach(bar => {
        const percentage = parseInt(bar.dataset.percentage);
        const color = bar.dataset.color || chartColors.primary;
        
        animateProgressBar(bar, percentage, color);
    });
}

/**
 * Création d'un graphique en ligne
 */
async function createLineChart(canvas, options) {
    const data = await fetchChartData(options.url);
    
    new Chart(canvas, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: options.title,
                data: data.values,
                borderColor: options.color,
                backgroundColor: options.color + '20',
                borderWidth: 3,
                fill: false,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: options.title
                },
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if (options.format === 'money') {
                                return window.SIF.format.money(value);
                            }
                            return value;
                        }
                    }
                }
            },
            elements: {
                point: {
                    radius: 4,
                    hoverRadius: 8
                }
            }
        }
    });
}

/**
 * Création d'un graphique en aires
 */
async function createAreaChart(canvas, options) {
    const data = await fetchChartData(options.url);
    
    new Chart(canvas, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: options.title,
                data: data.values,
                borderColor: options.color,
                backgroundColor: options.color + '30',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: options.title
                },
                filler: {
                    propagate: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return window.SIF.format.money(value);
                        }
                    }
                }
            }
        }
    });
}

/**
 * Création d'un graphique en barres
 */
async function createBarChart(canvas, options) {
    const data = await fetchChartData(options.url);
    
    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Montant',
                data: data.montants,
                backgroundColor: chartColors.warning + '80',
                borderColor: chartColors.warning,
                borderWidth: 1
            }, {
                label: 'Nombre',
                data: data.nombres,
                backgroundColor: chartColors.info + '80',
                borderColor: chartColors.info,
                borderWidth: 1,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: options.title
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    ticks: {
                        callback: function(value) {
                            return window.SIF.format.money(value);
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    }
                }
            }
        }
    });
}

/**
 * Création d'un graphique en doughnut
 */
async function createDoughnutChart(canvas, options) {
    const data = await fetchChartData(options.url);
    
    new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels: data.labels,
            datasets: [{
                data: data.values,
                backgroundColor: [
                    chartColors.success + '80',
                    chartColors.warning + '80',
                    chartColors.danger + '80',
                    chartColors.info + '80',
                    chartColors.secondary + '80'
                ],
                borderColor: [
                    chartColors.success,
                    chartColors.warning,
                    chartColors.danger,
                    chartColors.info,
                    chartColors.secondary
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: options.title
                },
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

/**
 * Création d'un graphique de comparaison
 */
async function createComparisionChart(canvas) {
    const epargneData = await fetchChartData('/api/dashboard/evolution-epargne');
    const creditsData = await fetchChartData('/api/dashboard/evolution-credits');
    
    new Chart(canvas, {
        type: 'line',
        data: {
            labels: epargneData.labels,
            datasets: [{
                label: 'Épargne',
                data: epargneData.values,
                borderColor: chartColors.success,
                backgroundColor: chartColors.success + '20',
                borderWidth: 3,
                fill: false
            }, {
                label: 'Crédits',
                data: creditsData.montants,
                borderColor: chartColors.warning,
                backgroundColor: chartColors.warning + '20',
                borderWidth: 3,
                fill: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Comparaison Épargne vs Crédits'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return window.SIF.format.money(value);
                        }
                    }
                }
            }
        }
    });
}

/**
 * Animation des barres de progression
 */
function animateProgressBar(bar, targetPercentage, color) {
    const progressFill = bar.querySelector('.progress-bar');
    const percentageText = bar.querySelector('[data-percentage-text]');
    
    let currentPercentage = 0;
    const increment = targetPercentage / 50; // Animation sur 50 frames
    
    const animate = () => {
        currentPercentage += increment;
        
        if (currentPercentage >= targetPercentage) {
            currentPercentage = targetPercentage;
        }
        
        progressFill.style.width = currentPercentage + '%';
        progressFill.style.backgroundColor = color;
        
        if (percentageText) {
            percentageText.textContent = Math.round(currentPercentage) + '%';
        }
        
        if (currentPercentage < targetPercentage) {
            requestAnimationFrame(animate);
        }
    };
    
    // Délai avant animation pour effet visuel
    setTimeout(animate, 500);
}

/**
 * Récupérer les données de graphique
 */
async function fetchChartData(url) {
    try {
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        
        return await response.json();
    } catch (error) {
        console.error('Erreur lors du chargement des données du graphique:', error);
        return { labels: [], values: [] };
    }
}

/**
 * Rafraîchir tous les graphiques
 */
export function refreshAllCharts() {
    // Détruire tous les graphiques existants
    Chart.helpers.each(Chart.instances, (instance) => {
        instance.destroy();
    });
    
    // Réinitialiser
    initCharts();
}

// Initialisation automatique
document.addEventListener('DOMContentLoaded', initCharts);