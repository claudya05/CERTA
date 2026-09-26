document.addEventListener('DOMContentLoaded', () => {
    // 1. Logika Toggle Sidebar Mobile (Hamburger)
    const burgerBtn = document.getElementById('certaBurgerBtn');
    const sidebar = document.getElementById('certaSidebar');
    const overlay = document.getElementById('certaSidebarOverlay');

    if (burgerBtn && sidebar && overlay) {
        burgerBtn.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }

    // 2. Logika Jam & Tanggal Real-time Header
    function updateClock() {
        const now = new Date();
        const clockEl = document.getElementById('certaClock');
        const dateEl  = document.getElementById('certaDate');

        if (clockEl) {
            clockEl.textContent = now.toLocaleTimeString('id-ID', { hour12: false });
        }
        if (dateEl) {
            dateEl.textContent = now.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' });
        }
    }

    updateClock();
    setInterval(updateClock, 1000);
});