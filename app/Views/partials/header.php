<script>
@keyframes ring {
    0% { transform: rotate(0); }
    10% { transform: rotate(15deg); }
    20% { transform: rotate(-10deg); }
    30% { transform: rotate(10deg); }
    40% { transform: rotate(-5deg); }
    50% { transform: rotate(0); }
    100% { transform: rotate(0); }
}

.bell-ring {
    animation: ring 1.5s infinite;
    color: #dc3545 !important;
}
</script>

<header class="certa-header d-flex align-items-center justify-content-between px-3 px-md-4">
    <div class="d-flex align-items-center gap-3">
        <button class="btn certa-burger d-lg-none" type="button" id="certaBurgerBtn" aria-label="Toggle sidebar">
            <i class="bi bi-list fs-3"></i>
        </button>
        <h5 class="mb-0 fw-bold text-dark-navy"><?= esc($title ?? 'Dashboard') ?></h5>
    </div>

    <div class="d-flex align-items-center gap-3">
        <!-- Tanggal & Jam Real-time -->
        <div class="text-end">
            <small class="d-block text-muted fw-medium" id="certaDate"><?= date('d M Y') ?></small>
            <span class="certa-clock fw-semibold" id="certaClock"><?= date('H:i:s') ?></span>
        </div>

        <!-- Tombol Lonceng Notifikasi -->
        <div class="position-relative">
            <button class="btn btn-light rounded-circle certa-bell-btn" type="button" id="certaBellBtn" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell-fill"></i>
            </button>
            <!-- Indikator Merah (Muncul saat waktu pengecekan) -->
            <span class="certa-bell-dot position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle d-none" id="certaBellDot">
                <span class="visually-hidden">Notifikasi Baru</span>
            </span>

            <!-- Menu Dropdown Pesan Notifikasi -->
            <ul class="dropdown-menu dropdown-menu-end shadow p-3" id="certaNotifMenu" style="width: 280px;">
                <li class="fw-bold mb-2 pb-1 border-bottom">Notifikasi Sistem</li>
                <li id="certaNotifContent" class="small text-muted">
                    Tidak ada jadwal pengecekan saat ini.
                </li>
            </ul>
        </div>
    </div>
</header> 

<script>
// Jam target pengecekan shift Data Center
const SHIFT_HOURS = [7, 14, 23]; 

function checkShiftNotification() {
    const now = new Date();
    const currentHour = now.getHours();
    
    const bellBtn = document.getElementById('certaBellBtn');
    const bellIcon = bellBtn ? bellBtn.querySelector('i') : null;
    const bellDot = document.getElementById('certaBellDot');
    const notifContent = document.getElementById('certaNotifContent');

    // Cek apakah jam saat ini masuk ke dalam jam jadwal pengecekan
    if (SHIFT_HOURS.includes(currentHour)) {
        // Tampilkan indikator & jalankan animasi lonceng
        if (bellDot) bellDot.classList.remove('d-none');
        if (bellIcon) bellIcon.classList.add('bell-ring');
        
        let shiftName = '';
        if (currentHour === 7) shiftName = 'Pagi (Shift 1)';
        else if (currentHour === 14) shiftName = 'Siang (Shift 2)';
        else if (currentHour === 23) shiftName = 'Malam (Shift 3)';

        if (notifContent) {
            notifContent.innerHTML = `
                <div class="alert alert-warning p-2 mb-0">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    <strong>Waktunya Pengecekan!</strong><br>
                    Harap lakukan checklist Data Center untuk <strong>Shift ${shiftName}</strong>.
                    <a href="<?= base_url('form-certa') ?>" class="btn btn-sm btn-primary w-100 mt-2">Isi Form Sekarang</a>
                </div>
            `;
        }
    } else {
        // Sembunyikan notifikasi jika bukan jam pengecekan
        if (bellDot) bellDot.classList.add('d-none');
        if (bellIcon) bellIcon.classList.remove('bell-ring');
        if (notifContent) {
            notifContent.innerHTML = '<span class="text-muted small">Tidak ada jadwal pengecekan saat ini.</span>';
        }
    }
}

// Jalankan fungsi bersamaan dengan clock timer
document.addEventListener('DOMContentLoaded', () => {
    checkShiftNotification();
    setInterval(checkShiftNotification, 10000); // Perbarui status notifikasi tiap 10 detik
});
</script>

<script>
function updateCertaClock() {
    const now = new Date();
    
    // Format Jam (HH:mm:ss)
    const hours   = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    
    const clockElement = document.getElementById('certaClock');
    if (clockElement) {
        clockElement.textContent = `${hours}:${minutes}:${seconds}`;
    }

    // Format Tanggal Bahasa Indonesia (Senin, 13 Sep 2026)
    const options = { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric' };
    const dateString = now.toLocaleDateString('id-ID', options);
    
    const dateElement = document.getElementById('certaDate');
    if (dateElement) {
        dateElement.textContent = dateString;
    }
}

// Jalankan saat halaman siap dan perbarui setiap 1 detik
document.addEventListener('DOMContentLoaded', () => {
    updateCertaClock();
    setInterval(updateCertaClock, 1000);
});
</script>