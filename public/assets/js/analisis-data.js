document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('btnTampilkanGrafik');
    const assetSelect = document.getElementById('filterAsset');
    const dateFrom = document.getElementById('filterDateFrom');
    const dateTo = document.getElementById('filterDateTo');
    const canvas = document.getElementById('chartTren');

    let trendChart = null;

    function loadChart() {
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
        const isPercentage = data.input_type === 'percentage';

        const yConfig = isPercentage
            ? { min: 0, max: 100, ticks: { callback: (v) => v + '%' } }
            : {
                  min: 0,
                  max: 100,
                  ticks: {
                      stepSize: 50,
                      callback: (v) => (v === 0 ? 'OFF' : v === 50 ? 'Standby' : v === 100 ? 'ON' : ''),
                  },
              };

        if (trendChart) trendChart.destroy();

        trendChart = new Chart(canvas, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: data.asset_name,
                    data: data.values,
                    borderColor: '#3b5bdb',
                    backgroundColor: 'rgba(59,91,219,0.12)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                }],
            },
            options: {
                scales: { y: yConfig },
                plugins: { legend: { display: false } },
            },
        });
    }

    if (btn) btn.addEventListener('click', loadChart);
    // Muat grafik pertama kali saat halaman dibuka
    loadChart();
});
