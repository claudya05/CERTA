<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="text-center py-5">
    <i class="bi bi-check-circle-fill text-success" style="font-size:4rem;"></i>
    <h5 class="fw-bold mt-3">Data Pengecekan Berhasil Disimpan!</h5>
    <p class="text-muted">Terima kasih telah mengisi form CERTA dengan lengkap.</p>

    <div class="alert alert-light border d-inline-block mt-4 text-start">
        <i class="bi bi-info-circle me-1"></i>
        Data yang telah disimpan dapat dilihat kembali pada menu Data Pengecekan.
    </div>

    <div class="mt-4">
        <a href="<?= base_url('data-pengecekan') ?>" class="btn btn-outline-primary">Lihat Data Pengecekan</a>
        <a href="<?= base_url('form-certa') ?>" class="btn btn-primary">Isi Form Baru</a>
    </div>
</div>

<?= $this->endSection() ?>
