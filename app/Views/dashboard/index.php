<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
    /* Styling khusus untuk menyesuaikan dengan mockup dashboard.png */
    .card-dashboard { border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .bg-dark-blue { background-color: #1e293b; color: white; }
    .text-success-custom { color: #10b981; }
    .border-success-custom { border-color: #10b981 !important; }
    
    /* Shift Box */
    .shift-box { border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; margin-bottom: 12px; }
    .shift-icon { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 12px; color: white; font-size: 14px;}
    
    /* UPS Circle */
    .ups-circle { width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: white; font-size: 3rem; }
    
    /* Aktivitas Timeline */
    .timeline-item { position: relative; padding-left: 20px; padding-bottom: 15px; border-left: 2px solid #e2e8f0; margin-left: 10px; }
    .timeline-item:last-child { border-left: transparent; }
    .timeline-dot { position: absolute; left: -6px; top: 0; width: 10px; height: 10px; border-radius: 50%; }
    .dot-blue { background-color: #3b82f6; }
    .dot-green { background-color: #10b981; }
    .dot-orange { background-color: #f59e0b; }

    /* Solar Bar Gradient */
    .solar-bar-container { width: 50px; height: 150px; background: #e2e8f0; border-radius: 6px; margin: 0 auto; position: relative; overflow: hidden; }
    .solar-bar-fill { position: absolute; bottom: 0; width: 100%; background: linear-gradient(to top, #ef4444, #f59e0b, #10b981); transition: height 0.5s ease; }
    
    /* Table Aset dengan Sticky Header */
    .table-aset th { 
        font-weight: 600; 
        color: #475569; 
        border-bottom-width: 1px; 
        position: sticky; 
        top: 0; 
        background-color: #ffffff; 
        z-index: 1; 
    }
    .table-aset td { vertical-align: middle; }
</style>

<!-- BARIS 1: Top Metrics -->
<div class="row g-3 mb-4">
    <!-- TOTAL LAPORAN -->
    <div class="col-6 col-lg-3">
        <div class="card card-dashboard bg-dark-blue h-100 p-3">
            <div class="fw-bold mb-2" style="font-size: 12px; letter-spacing: 0.5px;">TOTAL LAPORAN</div>
            <div class="d-flex justify-content-between align-items-end h-100">
                <h1 class="display-5 fw-bold mb-0 lh-1"><?= $totalLaporan ?></h1>
                <i class="bi bi-clipboard2-check text-white opacity-75" style="font-size: 2.5rem;"></i>
            </div>
        </div>
    </div>
    
    <!-- PENGECEKAN HARI INI -->
    <div class="col-6 col-lg-3">
        <div class="card card-dashboard border-success-custom h-100 p-3">
            <div class="fw-bold text-success-custom mb-2" style="font-size: 12px; letter-spacing: 0.5px;">PENGECEKAN HARI INI</div>
            <div class="d-flex justify-content-between align-items-end h-100">
                <h1 class="display-5 fw-bold mb-0 lh-1"><?= $pengecekanHariIni ?></h1>
                <i class="bi bi-calendar-check text-success-custom" style="font-size: 2.5rem;"></i>
            </div>
        </div>
    </div>

    <!-- FILE DATA TANGGAL -->
    <div class="col-12 col-lg-4">
        <div class="card card-dashboard h-100 p-3">
            <div class="fw-bold mb-2" style="font-size: 12px;">FILE DATA TANGGAL:</div>
            <input type="date" class="form-control mb-2" value="<?= date('Y-m-d') ?>">
            <div class="form-check form-switch mt-auto">
                <input class="form-check-input" type="checkbox" role="switch" id="refreshOtomatis" checked>
                <label class="form-check-label" style="font-size: 12px;" for="refreshOtomatis">Refresh Otomatis (15d)</label>
            </div>
        </div>
    </div>

    <!-- TOMBOL INPUT PENGECEKAN -->
    <div class="col-12 col-lg-2 d-flex align-items-center justify-content-end">
    <a href="<?= base_url('form-certa') ?>" class="btn text-white fw-bold d-flex align-items-center justify-content-center gap-2 w-100 py-2" style="background-color: #2563eb; border-radius: 8px;">
        <i class="bi bi-plus-lg fs-5"></i>
        <span>INPUT PENGECEKAN</span>
    </a>
</div>
</div>

<!-- BARIS 2: Shift, Peralatan, Terkini -->
<div class="row g-3 mb-4">
    <!-- STATUS SHIFT HARI INI -->
    <div class="col-lg-4">
        <div class="card card-dashboard h-100 p-4">
            <h6 class="fw-bold mb-4">STATUS SHIFT HARI INI</h6>
            <?php foreach ($statusShift as $s): ?>
                <div class="shift-box d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="shift-icon <?= $s['selesai'] ? 'bg-success' : 'bg-danger' ?>">
                            <i class="bi <?= $s['selesai'] ? 'bi-check-lg' : 'bi-x-lg' ?>"></i>
                        </div>
                        <div>
                            <div class="fw-bold">
                                <?= strtoupper(esc($s['shift']['name'])) ?> &nbsp; 
                                <span class="text-muted fw-normal">(<?= substr($s['shift']['jam_mulai'], 0, 5) ?>)</span>
                            </div>
                            <small class="<?= $s['selesai'] ? 'text-success' : 'text-danger' ?> fw-semibold" style="font-size: 11px;">
                                <?= $s['selesai'] ? 'Selesai' : 'Belum Selesai' ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- STATUS PERALATAN -->
    <div class="col-lg-4">
        <div class="card card-dashboard h-100 p-4 text-center">
            <h6 class="fw-bold mb-4">STATUS PERALATAN (ON/OFF/STANBY)</h6>
            <div style="position: relative; height: 220px; width: 100%; display: flex; justify-content: center; align-items: center;">
                <canvas id="chartStatusPeralatan"></canvas>
            </div>
        </div>
    </div>

    <!-- STATUS TERKINI -->
    <div class="col-lg-4">
        <div class="card card-dashboard h-100 p-4">
            <h6 class="fw-bold mb-4">STATUS TERKINI</h6>
            <div class="row text-center h-100 align-items-center">
                <!-- UPS A -->
                <div class="col-6">
                    <div class="mb-3" style="font-size: 11px; font-weight: 600;">UPS A DATA CENTER</div>
                    <?php 
                        $statusA = strtoupper($upsA ?? '-');
                        $isUpA = in_array($statusA, ['ON', 'NORMAL']);
                    ?>
                    <div class="ups-circle <?= $isUpA ? 'bg-success' : 'bg-danger' ?> mb-3 shadow-sm">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                    <div class="d-flex justify-content-center gap-2 align-items-center" style="font-size: 12px; font-weight: 600;">
                        <div style="width: 15px; height: 5px; background: <?= $isUpA ? '#10b981' : '#ef4444' ?>;"></div>
                        <?= $isUpA ? 'On' : 'Off' ?>
                    </div>
                </div>

                <!-- UPS B -->
                <div class="col-6">
                    <div class="mb-3" style="font-size: 11px; font-weight: 600;">UPS B DATA CENTER</div>
                    <?php 
                        $statusB = strtoupper($upsB ?? '-');
                        $isUpB = in_array($statusB, ['ON', 'NORMAL']);
                    ?>
                    <div class="ups-circle <?= $isUpB ? 'bg-success' : 'bg-danger' ?> mb-3 shadow-sm">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                    <div class="d-flex justify-content-center gap-2 align-items-center" style="font-size: 12px; font-weight: 600;">
                        <div style="width: 15px; height: 5px; background: <?= $isUpB ? '#10b981' : '#ef4444' ?>;"></div>
                        <?= $isUpB ? 'On' : 'Off' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BARIS 3: Aktivitas, Aset Bermasalah, Solar Genset -->
<div class="row g-3">
    <!-- AKTIVITAS TERBARU -->
    <div class="col-lg-4">
        <div class="card card-dashboard h-100 p-4">
            <h6 class="fw-bold mb-4">AKTIVITAS TERBARU</h6>
            <div class="mt-2">
                <?php 
                $colors = ['dot-blue', 'dot-green', 'dot-orange'];
                foreach (array_slice($aktivitasTerbaru, 0, 3) as $index => $a): 
                ?>
                    <div class="timeline-item">
                        <div class="timeline-dot <?= $colors[$index % 3] ?>"></div>
                        <div class="d-flex justify-content-between align-items-start">
                            <div style="font-size: 13px;">
                                <span class="fw-bold"><?= esc($a['nama_petugas']) ?></span> <?= esc($a['aktivitas']) ?>
                                <div class="text-muted" style="font-size: 11px;">Data Center</div>
                            </div>
                            <span class="text-muted" style="font-size: 12px;"><?= date('H:i', strtotime($a['created_at'])) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <a href="<?= base_url('data-pengecekan') ?>" class="text-dark fw-bold text-decoration-none mt-auto pt-3 d-flex align-items-center" style="font-size: 12px;">
                Lihat Semua Aktivitas <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>

    <!-- ASET BERMASALAH (DIBUAT SCROLLABLE) -->
    <!-- ASET BERMASALAH -->
<div class="col-lg-4">
    <div class="card card-dashboard h-100 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="fw-bold mb-0">ASET BERMASALAH</h6>
            <a href="<?= base_url('data-pengecekan') ?>" class="text-primary text-decoration-none small fw-bold">
                Lihat Semua <i class="bi bi-chevron-right"></i>
            </a>
        </div>
        <div class="table-responsive" style="max-height: 220px; overflow-y: auto;">
            <table class="table table-borderless table-aset text-center" style="font-size: 13px;">
                <thead style="border-bottom: 2px solid #e2e8f0;">
                    <tr>
                        <th>Waktu</th>
                        <th>Aset</th>
                        <th>Kondisi</th>
                        <th>Nama Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($asetBermasalah)): ?>
                        <tr>
                            <td colspan="4" class="text-muted py-4">Tidak ada aset bermasalah.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($asetBermasalah as $item): ?>
                            <!-- Klik seluruh baris untuk menuju halaman Data Pengecekan (otomatis memfilter teknisi jika ada) -->
                            <tr class="row-clickable" 
                                onclick="window.location.href='<?= base_url('data-pengecekan') ?>?teknisi=<?= urlencode($item['nama_petugas'] ?? '') ?>'"
                                style="border-bottom: 1px solid #f1f5f9;">
                                <td><?= isset($item['created_at']) ? date('H:i', strtotime($item['created_at'])) : '-' ?></td>
                                <td class="fw-semibold text-primary">
                                    <u><?= esc($item['asset_name'] ?? '-') ?></u>
                                </td>
                                <td class="text-danger fw-semibold"><?= esc($item['kondisi'] ?? '-') ?></td>
                                <td><?= esc($item['nama_petugas'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

    <!-- SOLAR GENSET -->
    <div class="col-lg-4">
        <div class="card card-dashboard h-100 p-4">
            <div class="row h-100 text-center">
                <!-- PLN -->
                <div class="col-6 d-flex flex-column">
                    <h6 class="fw-bold mb-4" style="font-size: 13px;">SOLAR GENSET PLN</h6>
                    <div class="d-flex justify-content-center align-items-center h-100 mt-2">
                        <div class="d-flex flex-column justify-content-between me-2 h-100 pb-2" style="font-size: 12px; font-weight: 600;">
                            <span>100%</span>
                            <span>75%</span>
                            <span>25%</span>
                            <span>0%</span>
                        </div>
                        <div class="solar-bar-container">
                            <div class="solar-bar-fill" style="height: <?= (int)($solarPln ?? 0) ?>%;"></div>
                        </div>
                    </div>
                </div>

                <!-- PLTU -->
                <div class="col-6 d-flex flex-column">
                    <h6 class="fw-bold mb-4" style="font-size: 13px;">SOLAR GENSET PLTU</h6>
                    <div class="d-flex justify-content-center align-items-center h-100 mt-2">
                        <div class="d-flex flex-column justify-content-between me-2 h-100 pb-2" style="font-size: 12px; font-weight: 600;">
                            <span>100%</span>
                            <span>75%</span>
                            <span>25%</span>
                            <span>0%</span>
                        </div>
                        <div class="solar-bar-container">
                            <div class="solar-bar-fill" style="height: <?= (int)($solarPltu ?? 0) ?>%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('extra_js') ?>
<script>
    const CERTA_STATUS_PERALATAN = <?= json_encode($statusPeralatan) ?>;
</script>
<script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
<?= $this->endSection() ?>