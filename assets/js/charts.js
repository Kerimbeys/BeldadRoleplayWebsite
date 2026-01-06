/**
 * MTA:SA UCP - Chart.js Helper Functions
 */

// Chart.js varsayılan ayarları
Chart.defaults.color = '#b8c5d6';
Chart.defaults.font.family = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
Chart.defaults.plugins.legend.labels.color = '#b8c5d6';
Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(26, 31, 58, 0.95)';
Chart.defaults.plugins.tooltip.borderColor = 'rgba(0, 212, 255, 0.5)';
Chart.defaults.plugins.tooltip.borderWidth = 1;
Chart.defaults.plugins.tooltip.titleColor = '#ffffff';
Chart.defaults.plugins.tooltip.bodyColor = '#b8c5d6';

// Renk paleti
const chartColors = {
    primary: 'rgba(0, 212, 255, 0.8)',
    secondary: 'rgba(255, 0, 110, 0.8)',
    success: 'rgba(0, 255, 136, 0.8)',
    warning: 'rgba(255, 170, 0, 0.8)',
    border: {
        primary: 'rgba(0, 212, 255, 1)',
        secondary: 'rgba(255, 0, 110, 1)',
        success: 'rgba(0, 255, 136, 1)',
        warning: 'rgba(255, 170, 0, 1)'
    }
};

// Para dağılımı grafiği
function createMoneyChart(canvasId, cash, bank) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;
    
    return new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Nakit Para', 'Banka Parası'],
            datasets: [{
                data: [cash, bank],
                backgroundColor: [
                    chartColors.primary,
                    chartColors.success
                ],
                borderColor: [
                    chartColors.border.primary,
                    chartColors.border.success
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#b8c5d6',
                        font: { size: 14 },
                        padding: 15
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) label += ': ';
                            label += '$' + context.parsed.toLocaleString('tr-TR');
                            return label;
                        }
                    }
                }
            }
        }
    });
}

// Bar chart oluştur
function createBarChart(canvasId, labels, data, label) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;
    
    return new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                backgroundColor: chartColors.primary,
                borderColor: chartColors.border.primary,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#b8c5d6'
                    },
                    grid: {
                        color: 'rgba(184, 197, 214, 0.1)'
                    }
                },
                x: {
                    ticks: {
                        color: '#b8c5d6'
                    },
                    grid: {
                        color: 'rgba(184, 197, 214, 0.1)'
                    }
                }
            }
        }
    });
}

// Line chart oluştur
function createLineChart(canvasId, labels, data, label) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;
    
    return new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                borderColor: chartColors.primary,
                backgroundColor: 'rgba(0, 212, 255, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    labels: {
                        color: '#b8c5d6'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#b8c5d6'
                    },
                    grid: {
                        color: 'rgba(184, 197, 214, 0.1)'
                    }
                },
                x: {
                    ticks: {
                        color: '#b8c5d6'
                    },
                    grid: {
                        color: 'rgba(184, 197, 214, 0.1)'
                    }
                }
            }
        }
    });
}

