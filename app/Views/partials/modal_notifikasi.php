<!-- Toast notifikasi "Pukul 07:00 Waktunya Pengecekan Ruangan Data Center!" -->
<div class="certa-toast-notif position-fixed" id="certaToastNotif" style="display:none;">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <div class="fw-bold" id="certaToastTime">Pukul 07:00</div>
            <div>Waktunya Pengecekan Ruangan Data Center!</div>
        </div>
        <button type="button" class="btn-close btn-close-white ms-3" id="certaToastClose" aria-label="Close"></button>
    </div>
</div>

<!-- Modal Daftar Aset Bermasalah (dipanggil dari card "Aset Bermasalah" di Dashboard) -->
<div class="modal fade" id="modalAsetBermasalah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-exclamation-triangle-fill text-danger me-1"></i>
                    DAFTAR ASET BERMASALAH (SAAT INI)
                </h6>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">
                    Berdasarkan data terakhir pada: <?= date('d/m/Y H:i') ?>
                </p>
                <div id="modalAsetBermasalahList">
                    <!-- diisi dari controller Dashboard::index() lewat view dashboard/index.php -->
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-end">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">TUTUP</button>
            </div>
        </div>
    </div>
</div>
