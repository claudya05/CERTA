<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= base_url('form-certa/simpan') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="certa-panel mb-3">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label small text-muted">Nama Petugas</label>
                <input type="text" name="nama_petugas" class="form-control" placeholder="Masukkan nama petugas" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">Hari/Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">Shift</label>
                <select name="shift_id" class="form-select" required>
                    <?php foreach ($shifts as $sh): ?>
                        <option value="<?= $sh['id'] ?>" <?= ($currentShift['id'] ?? null) == $sh['id'] ? 'selected' : '' ?>>
                            <?= esc($sh['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">Jam Pengecekan</label>
                <input type="time" name="jam_pengecekan" class="form-control" value="<?= date('H:i') ?>" required>
            </div>
        </div>
    </div>

    <div class="certa-panel mb-3">
        <table class="table table-borderless align-middle mb-0">
            <thead>
                <tr class="text-muted small">
                    <th style="width:40px">No</th>
                    <th>Nama Perangkat</th>
                    <th>Kondisi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach ($groupedAssets as $categoryName => $items): ?>
                    <tr class="table-light">
                        <td colspan="3" class="fw-bold small"><?= strtoupper(esc($categoryName)) ?></td>
                    </tr>
                    <?php foreach ($items as $asset): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($asset['name']) ?></td>
                            <td>
                                <?php foreach (\App\Models\AssetModel::getOptions($asset['input_type']) as $i => $opt): ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio"
                                               name="kondisi[<?= $asset['id'] ?>]"
                                               id="kondisi_<?= $asset['id'] ?>_<?= $i ?>"
                                               value="<?= $opt ?>" <?= $i === 0 ? 'checked' : '' ?> required>
                                        <label class="form-check-label small" for="kondisi_<?= $asset['id'] ?>_<?= $i ?>">
                                            <?= $asset['input_type'] === 'percentage' ? $opt . '%' : $opt ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="certa-panel h-100">
                <label class="form-label small text-muted fw-bold"><i class="bi bi-journal-text"></i> Catatan & Temuan</label>
                <textarea name="catatan" class="form-control" rows="4" placeholder="Masukkan catatan atau temuan selama pengecekan..."></textarea>
            </div>
        </div>
        <div class="col-md-6">
            <div class="certa-panel h-100">
                <label class="form-label small text-muted fw-bold"><i class="bi bi-camera"></i> Bukti Foto Pengecekan</label>
                <label for="buktiFotoInput" class="certa-upload-box d-block text-center">
                    <i class="bi bi-cloud-arrow-up fs-2 d-block mb-1"></i>
                    Klik untuk upload foto
                    <div class="small text-muted">Format: JPG, PNG</div>
                </label>
                <input type="file" id="buktiFotoInput" name="bukti_foto[]" accept="image/jpeg,image/png" multiple class="d-none">
            </div>
        </div>
    </div>

    <div class="text-end mt-3">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-save"></i> Simpan Pengecekan
        </button>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('extra_js') ?>
<script src="<?= base_url('assets/js/form-certa.js') ?>"></script>
<?= $this->endSection() ?>
