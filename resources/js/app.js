// Chart.js initialization for TaniWeather Dashboard & Analytics

document.addEventListener('DOMContentLoaded', function() {
    const tempChartEl = document.getElementById('tempChart');
    const rainChartEl = document.getElementById('rainChart');
    const windChartEl = document.getElementById('windChart');

    // Check if weather data is available (passed via window.weatherData)
    if (typeof window.weatherData !== 'undefined' && window.weatherData && window.weatherData.daily) {
        const daily = window.weatherData.daily;
        const labels = daily.time.map(d => {
            const dt = new Date(d + 'T00:00:00+08:00');
            return dt.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short' });
        });

        // 1. Temperature Chart (Suhu Maks vs Min)
        if (tempChartEl && typeof Chart !== 'undefined') {
            new Chart(tempChartEl, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Suhu Maksimum (°C)',
                            data: daily.temperature_2m_max,
                            borderColor: '#2563EB',
                            backgroundColor: 'rgba(37, 99, 235, 0.12)',
                            fill: true,
                            tension: 0.35,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#2563EB',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                        },
                        {
                            label: 'Suhu Minimum (°C)',
                            data: daily.temperature_2m_min,
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.10)',
                            fill: true,
                            tension: 0.35,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#10B981',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 16,
                                font: { size: 12, family: "'Instrument Sans', sans-serif", weight: '500' }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#0F172A',
                            padding: 12,
                            titleFont: { size: 13, family: "'Instrument Sans', sans-serif" },
                            bodyFont: { size: 12, family: "'Instrument Sans', sans-serif" },
                            cornerRadius: 10,
                            displayColors: true,
                            callbacks: {
                                label: ctx => `${ctx.dataset.label}: ${ctx.raw}°C`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            grid: { color: 'rgba(226, 232, 240, 0.6)' },
                            ticks: {
                                callback: v => v + '°C',
                                font: { size: 11, family: "'Instrument Sans', sans-serif" }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11, family: "'Instrument Sans', sans-serif" } }
                        }
                    },
                    interaction: { intersect: false, mode: 'index' }
                }
            });
        }

        // 2. Rainfall Chart (Curah Hujan Harian)
        if (rainChartEl && typeof Chart !== 'undefined') {
            new Chart(rainChartEl, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Curah Hujan (mm)',
                        data: daily.precipitation_sum,
                        backgroundColor: daily.precipitation_sum.map(v => {
                            if (v > 10) return '#1D4ED8';
                            if (v > 3) return '#3B82F6';
                            if (v > 0) return '#60A5FA';
                            return '#CBD5E1';
                        }),
                        borderRadius: 8,
                        maxBarThickness: 42,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0F172A',
                            padding: 12,
                            cornerRadius: 10,
                            callbacks: {
                                label: ctx => `Curah Hujan: ${ctx.raw} mm`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(226, 232, 240, 0.6)' },
                            ticks: {
                                callback: v => v + ' mm',
                                font: { size: 11, family: "'Instrument Sans', sans-serif" }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11, family: "'Instrument Sans', sans-serif" } }
                        }
                    }
                }
            });
        }

        // 3. Wind Speed Chart (Kecepatan Angin Maksimum untuk Analitik)
        if (windChartEl && typeof Chart !== 'undefined') {
            const windData = daily.wind_speed_10m_max || daily.wind_speed_10m || [10, 12, 15, 9, 14, 11, 13];
            new Chart(windChartEl, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Kecepatan Angin (km/jam)',
                        data: windData,
                        borderColor: '#0D9488',
                        backgroundColor: 'rgba(13, 148, 136, 0.12)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 4,
                        pointBackgroundColor: '#0D9488',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0F172A',
                            padding: 12,
                            cornerRadius: 10,
                            callbacks: {
                                label: ctx => `Kecepatan Angin: ${ctx.raw} km/jam`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(226, 232, 240, 0.6)' },
                            ticks: {
                                callback: v => v + ' km/j',
                                font: { size: 11, family: "'Instrument Sans', sans-serif" }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11, family: "'Instrument Sans', sans-serif" } }
                        }
                    }
                }
            });
        }
    }
});