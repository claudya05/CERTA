<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'CERTA') ?> | Cek Rutin Data Center</title>

    <link rel="icon" type="image/x-icon" href="<?= base_url('certa.ico') ?>">
    <script src="<?= base_url('assets/js/app.js') ?>"></script>

    <!-- Bootstrap 5 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom Style -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    

    <?= $this->renderSection('extra_css') ?>
</head>
<body>

<div class="certa-wrapper">

    <?= $this->include('partials/sidebar') ?>

    <div class="certa-content">

        <?= $this->include('partials/header') ?>

        <main class="certa-main p-3 p-md-4">

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>

        </main>
    </div>
</div>

<!-- Modal Notifikasi Pengecekan Rutin (global, dikontrol via JS di dashboard.js) -->
<?= $this->include('partials/modal_notifikasi') ?>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<!-- Ganti link Chart.js lama Anda dengan ini -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>   
<?= $this->renderSection('extra_js') ?>

</body>
</html>
