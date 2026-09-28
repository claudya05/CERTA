document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('btnTampilkanGrafik');
    const assetSelect = document.getElementById('filterAsset');
    const dateFrom = document.getElementById('filterDateFrom');
    const dateTo = document.getElementById('filterDateTo');
    const canvas = document.getElementById('chartTren');

    let trendChart = null;

    function loadChart() {
        if (!assetSelect || !assetSelect.value) return;

        const params = new URLSearchParams({
            asset_id: assetSelect.value,
            date_from: dateFrom.value,
            date_to: dateTo.value,
        });

        fetch(CHART_DATA_URL + '?' + params.toString())
            .then((res) => res.json())
            .then((data) => renderChart(data))
            .catch((err) => console.error('Gagal memuat data grafik:', err));
    }

    function renderChart(data) {
        if (!data || !canvas) return;

        const ctx = canvas.getContext('2d');
        const deviceType = data.device_type;

        // Gradient Biru-Ungu Transparan sesuai gambar referensi
        const gradient = ctx.createLinearGradient(0, 0, 0, 350);
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.45)');
        gradient.addColorStop(0.6, 'rgba(168, 85, 247, 0.2)');
        gradient.addColorStop(1, 'rgba(255, 255, 255, 0.0)');

        let yConfig = {};
        let tooltipCallback = null;

        // SETTING SUMBU Y SESUAI ASET TERPILIH
        if (deviceType === 'pac') {
            // PAC 1 & PAC 2 (ON, Standby, OFF)
            yConfig = {
                min: 0,
                max: 100,
                ticks: {
                    stepSize: 50,
                    callback: function (v) {
                        if (v === 100) return 'ON';
                        if (v === 50) return 'Standby';
                        if (v === 0) return 'OFF';
                        return '';
                    },
                    font: { weight: 'bold', size: 12 }
                }
            };
            tooltipCallback = (v) => (v === 100 ? 'ON' : v === 50 ? 'Standby' : 'OFF');

        } else if (deviceType === 'baterai') {
            // Baterai Data Center & Baterai GUBA (Normal, Tidak Normal)
            yConfig = {
                min: 0,
                max: 100,
                ticks: {
                    stepSize: 100,
                    callback: function (v) {
                        if (v === 100) return 'Normal';
                        if (v === 0) return 'Tidak Normal';
                        return '';
                    },
                    font: { weight: 'bold', size: 12 }
                }
            };
            tooltipCallback = (v) => (v === 100 ? 'Normal' : 'Tidak Normal');

        } else if (deviceType === 'solar') {
            // Solar Genset PLN & PLTU (0%, 25%, 75%, 100%)
            yConfig = {
                min: 0,
                max: 100,
                ticks: {
                    stepSize: 25,
                    callback: function (v) {
                        if (v === 0 || v === 25 || v === 75 || v === 100) return v + '%';
                        return '';
                    },
                    font: { weight: 'bold', size: 12 }
                }
            };
            tooltipCallback = (v) => v + '%';

        } else {
            // MCB, Panel, UPS, AC (ON, OFF / Normal, OFF)
            yConfig = {
                min: 0,
                max: 100,
                ticks: {
                    stepSize: 100,
                    callback: function (v) {
                        if (v === 100) return 'ON / Normal';
                        if (v === 0) return 'OFF';
                        return '';
                    },
                    font: { weight: 'bold', size: 12 }
                }
            };
            tooltipCallback = (v) => (v === 100 ? 'ON / Normal' : 'OFF');
        }

        // Background Putih untuk PDF Export
        const whiteBackgroundPlugin = {
            id: 'customCanvasBackgroundColor',
            beforeDraw: (chart) => {
                const { ctx, width, height } = chart;
                ctx.save();
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, width, height);
                ctx.restore();
            }
        };

        if (trendChart) trendChart.destroy();

        trendChart = new Chart(canvas, {
            type: 'line',
            data: {
                labels: data.labels || [], // Berisi Tanggal + Jam Input Pengecekan
                datasets: [{
                    label: data.asset_name || 'Kondisi',
                    data: data.values || [],
                    borderColor: '#2563eb', // Warna Garis Biru
                    borderWidth: 2,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.2, // Kelengkungan garis
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#1d4ed8',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 1
                }]
            },
            plugins: [whiteBackgroundPlugin],
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: yConfig,
                    x: {
                        ticks: {
                            maxRotation: 75, // Miring vertikal seperti di gambar
                            minRotation: 75,
                            autoSkip: false,  // Menampilkan seluruh tanggal inputan tanpa terlewati
                            font: { size: 11 }
                        },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const val = context.parsed.y;
                                const text = tooltipCallback ? tooltipCallback(val) : val;
                                return `${context.dataset.label}: ${text}`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Load grafik otomatis saat pertama dibuka
    loadChart();

    // Event Triggers
    if (btn) btn.addEventListener('click', loadChart);
    if (assetSelect) assetSelect.addEventListener('change', loadChart);

    // Export PDF
    const btnExport = document.getElementById('btnExportPdf');
    if (btnExport) {
        btnExport.addEventListener('click', function () {
            if (!canvas || !trendChart) {
                alert("Grafik belum siap untuk diekspor!");
                return;
            }
            document.getElementById('inputChartImage').value = canvas.toDataURL('image/png');
            document.getElementById('inputAssetId').value = assetSelect.value;
            document.getElementById('inputDateFrom').value = dateFrom.value;
            document.getElementById('inputDateTo').value = dateTo.value;
            document.getElementById('formExportPdf').submit();
        });
    }
});