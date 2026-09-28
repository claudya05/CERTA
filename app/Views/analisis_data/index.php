<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
    .card-chart-container {
        border: 1px solid #dce1e7;
        border-radius: 8px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .chart-header-bar {
        background-color: #0c2340;
        color: #ffffff;
        padding: 10px 16px;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
</style>

<!-- FORM FILTER UTAMA -->
<div class="row g-3 align-items-end mb-4">
    <div class="col-md-4">
        <label class="form-label small text-secondary mb-1 fw-bold">Pilih Aset:</label>
        <select id="filterAsset" class="form-select form-select-sm">
            <?php foreach ($assets as $a): ?>
                <option value="<?= $a['id'] ?>"><?= esc($a['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="col-md-5">
        <label class="form-label small text-secondary mb-1 fw-bold">Pilih Tanggal:</label>
        <div class="d-flex align-items-center gap-2">
            <input type="date" id="filterDateFrom" class="form-control form-control-sm" value="<?= date('Y-m-d', strtotime('-30 days')) ?>">
            <span class="small text-muted">s/d</span>
            <input type="date" id="filterDateTo" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>">
        </div>
    </div>
    
    <div class="col-md-3 d-flex gap-2 justify-content-md-end">
        <button id="btnTampilkanGrafik" type="button" class="btn btn-sm btn-primary px-3" style="background-color: #3182ce; border: none;">
            <i class="bi bi-graph-up me-1"></i> Tampilkan Grafik
        </button>
        <button type="button" id="btnExportPdf" class="btn btn-sm btn-danger px-3" style="background-color: #e53e3e; border: none;">
            <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
        </button>
    </div>
</div>

<!-- CONTAINER GRAFIK -->
<div class="card-chart-container">
    <div class="chart-header-bar">
        <i class="bi bi-bar-chart-line-fill"></i>
        <span>Grafik Tren</span>
    </div>
    <div class="p-3" style="position: relative; height: 420px; width: 100%;">
        <canvas id="chartTren"></canvas>
    </div>
</div>

<!-- FORM HIDDEN EXPORT PDF -->
<form id="formExportPdf" action="<?= base_url('analisis-data/export-pdf') ?>" method="POST" target="_blank">
    <?= csrf_field() ?>
    <input type="hidden" name="chart_image" id="inputChartImage">
    <input type="hidden" name="asset_id" id="inputAssetId">
    <input type="hidden" name="date_from" id="inputDateFrom">
    <input type="hidden" name="date_to" id="inputDateTo">
</form>

<?= $this->endSection() ?>

<?= $this->section('extra_js') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const CHART_DATA_URL = "<?= base_url('analisis-data/chart-data') ?>";
</script>
<script src="<?= base_url('assets/js/analisis-data.js') ?>"></script>
<?= $this->endSection() ?>