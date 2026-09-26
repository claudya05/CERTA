<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row g-3 mb-3">
    <div class="col-6 col-lg-3">
        <div class="certa-card certa-card-dark">
            <div class="certa-card-label">TOTAL LAPORAN</div>
            <div class="d-flex justify-content-between align-items-center">
                <span class="certa-card-number"><?= $totalLaporan ?></span>
                <i class="bi bi-clipboard-check fs-2"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="certa-card">
            <div class="certa-card-label text-success">PENGECEKAN HARI INI</div>
            <div class="d-flex justify-content-between align-items-center">
                <span class="certa-card-number"><?= $pengecekanHariIni ?></span>
                <i class="bi bi-calendar-check text-success fs-2"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="certa-card <?= count($asetBermasalah) > 0 ? 'certa-card-danger' : '' ?>" role="button"
             data-bs-toggle="modal" data-bs-target="#modalAsetBermasalah">
            <div class="certa-card-label <?= count($asetBermasalah) > 0 ? 'text-white' : 'text-danger' ?>">ASET BERMASALAH</div>
            <div class="d-flex justify-content-between align-items-center">
                <span class="certa-card-number"><?= count($asetBermasalah) ?></span>
                <i class="bi bi-exclamation-triangle fs-2"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="certa-card">
            <div class="certa-card-label">FILE DATA TANGGAL:</div>
            <input type="date" class="form-control form-control-sm mt-1" value="<?= date('Y-m-d') ?>">
        </div>
    </div>
</div>

<!-- BARIS 2: STATUS SHIFT, STATUS PERALATAN, STATUS TERKINI -->
<div class="row g-3 mb-3">
    <!-- Kolom 1 -->
    <div class="col-lg-4">
        <div class="certa-panel h-100">
            <h6 class="certa-panel-title">STATUS SHIFT HARI INI</h6>
            <?php foreach ($statusShift as $s): ?>
                <div class="d-flex align-items-center justify-content-between certa-shift-row">
                    <div class="d-flex align-items-center gap-2">
                        <span class="certa-shift-icon <?= $s['selesai'] ? 'bg-success' : 'bg-danger' ?>">
                            <i class="bi <?= $s['selesai'] ? 'bi-check-lg' : 'bi-x-lg' ?>"></i>
                        </span>
                        <div>
                            <div class="fw-semibold"><?= esc($s['shift']['name']) ?> (<?= substr($s['shift']['jam_mulai'], 0, 5) ?>)</div>
                            <small class="<?= $s['selesai'] ? 'text-success' : 'text-danger' ?>">
                                <?= $s['selesai'] ? 'Selesai' : 'Belum Selesai' ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Kolom 2 -->
    <div class="col-lg-4">
        <div class="certa-panel h-100">
            <h6 class="certa-panel-title">STATUS PERALATAN (ON/OFF/STANDBY)</h6>
            <div style="position: relative; height: 200px; width: 100%;">
                <canvas id="chartStatusPeralatan"></canvas>
            </div>
        </div>
    </div>

    <!-- Kolom 3 -->
   <div class="col-lg-4">
    <div class="certa-panel h-100 d-flex flex-column">
        <h6 class="certa-panel-title">STATUS TERKINI</h6>
        
        <!-- flex-grow-1 membuat pembungkus ini mengisi sisa ruang ke bawah -->
        <div class="row g-2 text-center flex-grow-1">
            <div class="col-6 d-flex flex-column">
                <small class="text-muted d-block mb-1">UPS A DATA CENTER</small>
                <?php 
                    $statusA = strtoupper($upsA ?? '-');
                    $bgClassA = match($statusA) {
                        'ON', 'NORMAL' => 'bg-success',
                        'OFF' => 'bg-danger',
                        default => 'bg-secondary'
                    };
                ?>
                <!-- h-100 & d-flex agar kotak mengisi full tinggi col dan teks berada tepat di tengah -->
                <div class="certa-status-box <?= $bgClassA ?> h-100 d-flex align-items-center justify-content-center flex-column p-3">
                    <span class="fs-4 fw-bold">UPS A</span>
                    <span class="badge bg-light text-dark fs-6 mt-1"><?= $statusA ?></span>
                </div>
            </div>

            <div class="col-6 d-flex flex-column">
                <small class="text-muted d-block mb-1">UPS B DATA CENTER</small>
                <?php 
                    $statusB = strtoupper($upsB ?? '-');
                    $bgClassB = match($statusB) {
                        'ON', 'NORMAL' => 'bg-success',
                        'OFF' => 'bg-danger',
                        default => 'bg-secondary'
                    };
                ?>
                <!-- h-100 & d-flex agar kotak mengisi full tinggi col dan teks berada tepat di tengah -->
                <div class="certa-status-box <?= $bgClassB ?> h-100 d-flex align-items-center justify-content-center flex-column p-3">
                    <span class="fs-4 fw-bold">UPS B</span>
                    <span class="badge bg-light text-dark fs-6 mt-1"><?= $statusB ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- BARIS 3: AKTIVITAS, GAUGE PLN, GAUGE PLTU -->
<div class="row g-3">
    <div class="col-lg-4">
    <div class="certa-panel h-100">
        <h6 class="certa-panel-title">AKTIVITAS TERBARU</h6>
        
        <?php foreach (array_slice($aktivitasTerbaru, 0, 3) as $a): ?>
            <div class="certa-activity-row">
                <span class="certa-dot"></span>
                <div>
                    <span class="fw-semibold"><?= esc($a['nama_petugas']) ?></span> <?= esc($a['aktivitas']) ?>
                </div>
                <small class="text-muted ms-auto"><?= date('H:i', strtotime($a['created_at'])) ?></small>
            </div>
        <?php endforeach; ?>
        
        <a href="<?= base_url('data-pengecekan') ?>" class="d-block mt-2 small">Lihat Semua Aktivitas &rarr;</a>
    </div>
</div>
    
    <div class="col-lg-4">
        <div class="certa-panel h-100 text-center">
            <h6 class="certa-panel-title text-start">SOLAR GENSET PLN</h6>
            <div style="position: relative; height: 160px; width: 100%;">
                <canvas id="gaugeSolarPln"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="certa-panel h-100 text-center">
            <h6 class="certa-panel-title text-start">SOLAR GENSET PLTU</h6>
            <div style="position: relative; height: 160px; width: 100%;">
                <canvas id="gaugeSolarPltu"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Data untuk modal "Daftar Aset Bermasalah" -->
<template id="tplAsetBermasalah">
<?php if (empty($asetBermasalah)): ?>
    <div class="alert alert-light border text-center mb-0">Tidak ada aset yang bermasalah saat ini.</div>
<?php else: ?>
    <?php foreach ($asetBermasalah as $item): ?>
        <div class="border rounded p-2 mb-2 bg-light"><?= esc($item['asset_name']) ?></div>
    <?php endforeach; ?>
<?php endif; ?>
</template>

<?= $this->endSection() ?>

<?= $this->section('extra_js') ?>
<script>
    const CERTA_STATUS_PERALATAN = <?= json_encode($statusPeralatan) ?>;
    const CERTA_SOLAR_PLN = <?= (int) ($solarPln ?? 0) ?>;
    const CERTA_SOLAR_PLTU = <?= (int) ($solarPltu ?? 0) ?>;
</script>
<script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
<?= $this->endSection() ?>