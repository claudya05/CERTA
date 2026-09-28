<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<form method="get" class="d-flex flex-wrap gap-2 align-items-center mb-3">
    <label class="small text-muted mb-0">Menampilkan hasil untuk:</label>
    <input type="date" name="date_from" value="<?= esc($dateFrom) ?>" class="form-control form-control-sm w-auto">
    <span class="small">s/d</span>
    <input type="date" name="date_to" value="<?= esc($dateTo) ?>" class="form-control form-control-sm w-auto">
    <select name="limit" class="form-select form-select-sm w-auto">
        <?php foreach ([5, 10, 15, 25, 50] as $opt): ?>
            <option value="<?= $opt ?>" <?= $limit == $opt ? 'selected' : '' ?>><?= $opt ?></option>
        <?php endforeach; ?>
    </select>
    <div class="input-group input-group-sm w-auto">
        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
        <input type="text" name="teknisi" value="<?= esc($teknisi) ?>" class="form-control" placeholder="Cari Teknisi">
    </div>
    <button type="submit" class="btn btn-sm btn-outline-secondary">Terapkan</button>

    <a href="<?= base_url('data-pengecekan/export') ?>?<?= http_build_query(request()->getGet()) ?>"
       class="btn btn-sm btn-success ms-auto">
        <i class="bi bi-download"></i> Export Excel
    </a>
</form>

<div class="row g-3">
    <?php if (empty($checklists)): ?>
        <div class="col-12">
            <div class="alert alert-light border text-center">Tidak ada data pengecekan pada rentang tanggal ini.</div>
        </div>
    <?php endif; ?>

    <?php foreach ($checklists as $c): ?>
        <div class="col-md-6 col-lg-4">
            <div class="certa-panel h-100 d-flex flex-column justify-content-between">
                <div>
                    <!-- Header Card -->
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="fw-bold mb-0"><?= esc($c['nama_petugas']) ?></h6>
                            <small class="text-muted">
                                <?= format_tanggal_indo($c['tanggal']) ?> &middot; Pukul <?= substr($c['jam_pengecekan'], 0, 5) ?> WIB
                            </small>
                        </div>
                        <div class="d-flex gap-1">
                            <?php if (!empty($c['photos'])): ?>
                                <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal"
                                        data-bs-target="#photoModal<?= $c['id'] ?>">
                                    <i class="bi bi-camera-fill text-primary"></i>
                                </button>
                            <?php endif; ?>
                            <a href="<?= base_url('data-pengecekan/delete/' . $c['id']) ?>"
                               class="btn btn-sm btn-light"
                               onclick="return confirm('Hapus data pengecekan ini?');">
                                <i class="bi bi-trash-fill text-danger"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Detail Per Kategori & Aset -->
                    <?php foreach ($c['grouped_detail'] as $categoryName => $items): ?>
                        <div class="certa-detail-group">
                            <div class="certa-detail-group-title"><?= strtoupper(esc($categoryName)) ?></div>
                            <?php foreach ($items as $item): ?>
                                <div class="certa-detail-row py-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><?= esc($item['asset_name']) ?></span>
                                        <span class="<?= status_badge_class($item['kondisi']) ?>">
                                            <?= kondisi_display($item['input_type'], $item['kondisi']) ?>
                                        </span>
                                    </div>
                                    
                                    <!-- 1. CATATAN DETAIL PER ASET (jika ada di tabel checklist_detail) -->
                                    <?php if (!empty($item['catatan'])): ?>
                                        <small class="text-muted d-block fst-italic" style="font-size: 11px; margin-top: -2px;">
                                            <i class="bi bi-chat-left-text me-1"></i><?= esc($item['catatan']) ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- 2. CATATAN UTAMA PENGECEKAN (jika ada di tabel checklist) -->
                <?php if (!empty($c['catatan'])): ?>
                    <div class="mt-3 p-2 bg-light rounded border-start border-3 border-info">
                        <small class="text-muted d-block fw-bold mb-1" style="font-size: 10px; letter-spacing: 0.5px;">CATATAN UTAMA:</small>
                        <small class="text-dark d-block" style="font-size: 12px; line-height: 1.4;">
                            <?= nl2br(esc($c['catatan'])) ?>
                        </small>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <!-- Modal preview foto -->
        <?php if (!empty($c['photos'])): ?>
            <div class="modal fade" id="photoModal<?= $c['id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-body p-0 text-center bg-dark">
                            <?php foreach ($c['photos'] as $p): ?>
                                <img src="<?= base_url($p['file_path']) ?>" class="img-fluid mb-2 rounded" alt="<?= esc($p['original_name']) ?>">
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?> 
    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>