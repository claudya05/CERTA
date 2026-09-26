<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-2 align-items-end mb-3">
    <div class="col-md-3">
        <label class="small text-muted mb-1">Pilih Aset:</label>
        <select id="filterAsset" class="form-select form-select-sm">
            <?php foreach ($assets as $a): ?>
                <option value="<?= $a['id'] ?>" data-type="<?= $a['input_type'] ?>"><?= esc($a['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <label class="small text-muted mb-1">Pilih Tanggal:</label>
        <input type="date" id="filterDateFrom" class="form-control form-control-sm" value="<?= date('Y-m-01') ?>">
    </div>
    <div class="col-md-3">
        <label class="small text-muted mb-1">s/d Tanggal:</label>
        <input type="date" id="filterDateTo" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>">
    </div>
    
    <!-- Wadah Tombol Dijadikan Satu Kolom Sejajar (Flexbox) -->
    <div class="col-md-auto d-flex gap-2">
        <button id="btnTampilkanGrafik" class="btn btn-primary btn-sm px-3" title="Tampilkan Grafik">
            <i class="bi bi-bar-chart-fill me-1"></i> Tampilkan
        </button>
        <button type="button" id="btnExportPdf" class="btn btn-danger btn-sm px-3">
            <i class="bi bi-file-earmark-pdf-fill me-1"></i> PDF
        </button>
    </div>
</div>

<div class="certa-panel">
    <div class="certa-panel-title-bar mb-3">
        <i class="bi bi-bar-chart-line-fill me-1"></i> Grafik Tren
    </div>
    <div style="position: relative; height: 350px; width: 100%;">
        <canvas id="chartTren"></canvas>
    </div>
</div>

<!-- FORM HIDDEN UNTUK MENGIRIM GAMBAR GRAFIK KE CONTROLLER -->
<form id="formExportPdf" action="<?= base_url('analisis-data/export-pdf') ?>" method="POST" target="_blank">
    <?= csrf_field() ?>
    <input type="hidden" name="chart_image" id="inputChartImage">
    <input type="hidden" name="asset_id" id="inputAssetId">
    <input type="hidden" name="date_from" id="inputDateFrom">
    <input type="hidden" name="date_to" id="inputDateTo">
</form>

<?= $this->endSection() ?>

<?= $this->section('extra_js') ?>
<script>
    const CHART_DATA_URL = "<?= base_url('analisis-data/chart-data') ?>";
</script>
<script src="<?= base_url('assets/js/analisis-data.js') ?>"></script>
<script>
    // Handler untuk tombol Export PDF
    document.getElementById('btnExportPdf').addEventListener('click', function () {
        const canvas = document.getElementById('chartTren');
        if (!canvas) return;

        // Ambil data gambar dalam format Base64 PNG dari Canvas Chart
        const chartBase64 = canvas.toDataURL('image/png');

        // Isi data ke form hidden
        document.getElementById('inputChartImage').value = chartBase64;
        document.getElementById('inputAssetId').value = document.getElementById('filterAsset').value;
        document.getElementById('inputDateFrom').value = document.getElementById('filterDateFrom').value;
        document.getElementById('inputDateTo').value = document.getElementById('filterDateTo').value;

        // Submit form
        document.getElementById('formExportPdf').submit();
    });
</script>
<?= $this->endSection() ?>