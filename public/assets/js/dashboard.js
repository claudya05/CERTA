document.addEventListener('DOMContentLoaded', function () {

    // ===== Toggle sidebar (mobile) =====
    const burgerBtn = document.getElementById('certaBurgerBtn');
    const sidebar = document.getElementById('certaSidebar');
    const overlay = document.getElementById('certaSidebarOverlay');

    // ===== Jam realtime di header =====
    const clockEl = document.getElementById('certaClock');
    if (clockEl) {
        const updateClock = () => {
            const now = new Date();
            clockEl.textContent = now.toTimeString().slice(0, 5);
        };
        updateClock();
        setInterval(updateClock, 1000 * 30);
    }

    // ===== Isi modal "Daftar Aset Bermasalah" dari <template> =====
    const tpl = document.getElementById('tplAsetBermasalah');
    const modalList = document.getElementById('modalAsetBermasalahList');
    if (tpl && modalList) {
        modalList.innerHTML = tpl.innerHTML;
    }

    // ===== Toast notifikasi jadwal pengecekan =====
    const SHIFT_TIMES = ['07:00', '14:00', '23:00'];
    const toast = document.getElementById('certaToastNotif');
    const toastTime = document.getElementById('certaToastTime');
    const toastClose = document.getElementById('certaToastClose');

    function checkShiftNotification() {
        const now = new Date();
        const hhmm = now.toTimeString().slice(0, 5);
        if (SHIFT_TIMES.includes(hhmm) && toast && toastTime) {
            toastTime.textContent = 'Pukul ' + hhmm;
            toast.style.display = 'block';
        }
    }
    if (toastClose && toast) {
        toastClose.addEventListener('click', () => (toast.style.display = 'none'));
    }
    checkShiftNotification();
    setInterval(checkShiftNotification, 1000 * 30);

    // ===== Donut Chart: Status Peralatan (ON/OFF/Standby) =====
    const donutCanvas = document.getElementById('chartStatusPeralatan');
    if (donutCanvas && typeof CERTA_STATUS_PERALATAN !== 'undefined') {
        new Chart(donutCanvas, {
            type: 'doughnut',
            data: {
                labels: ['On', 'Off', 'Standby'],
                datasets: [{
                    data: [
                        CERTA_STATUS_PERALATAN.On || 0,
                        CERTA_STATUS_PERALATAN.Off || 0,
                        CERTA_STATUS_PERALATAN.Standby || 0,
                    ],
                    backgroundColor: ['#1e8e5a', '#d63838', '#4aa8e0'],
                    borderWidth: 0,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: { 
                    legend: { 
                        position: 'bottom', 
                        labels: { boxWidth: 10 } 
                    } 
                },
            },
        });
    }

    // ===== Gauge Chart (dibuat dari doughnut setengah lingkaran) =====
    function renderGauge(canvasId, value) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;

        new Chart(canvas, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [value, 100 - value],
                    backgroundColor: [gaugeColor(value), '#e9ecef'],
                    borderWidth: 0,
                    circumference: 180,
                    rotation: 270,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: { 
                    legend: { display: false }, 
                    tooltip: { enabled: false } 
                },
            },
            plugins: [{
                id: 'gaugeText',
                afterDraw(chart) {
                    const { ctx, chartArea } = chart;
                    const xCenter = (chartArea.left + chartArea.right) / 2;
                    const yCenter = chartArea.bottom - 10;

                    ctx.save();
                    ctx.font = 'bold 20px sans-serif';
                    ctx.fillStyle = '#333';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(value + '%', xCenter, yCenter);
                    ctx.restore();
                },
            }],
        });
    }

    function gaugeColor(value) {
        if (value <= 25) return '#d63838';
        if (value <= 50) return '#e0a83a';
        if (value <= 75) return '#c8d64a';
        return '#1e8e5a';
    }

    if (typeof CERTA_SOLAR_PLN !== 'undefined') renderGauge('gaugeSolarPln', CERTA_SOLAR_PLN);
    if (typeof CERTA_SOLAR_PLTU !== 'undefined') renderGauge('gaugeSolarPltu', CERTA_SOLAR_PLTU);
});