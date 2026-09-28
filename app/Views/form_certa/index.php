<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
    .form-header-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #1e293b;
    }
    .form-header-sub {
        font-size: 0.875rem;
        color: #64748b;
    }

    /* Meta Cards (Petugas, Tanggal, Shift, Jam) */
    .meta-card {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 8px 12px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .meta-label {
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }
    .meta-input-box {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .meta-input-box i {
        font-size: 1.25rem;
        color: #64748b;
    }
    .meta-input-box .form-control,
    .meta-input-box .form-select {
        border: none !important;
        padding: 0 !important;
        font-weight: 600;
        color: #334155;
        box-shadow: none !important;
        background: transparent !important;
        font-size: 14px;
    }

    /* Tabel Perangkat & Kondisi */
    .certa-table-wrapper {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        overflow: hidden;
        background: #ffffff;
    }
    .certa-table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: collapse;
    }
    .certa-table th {
        background-color: #ffffff;
        color: #1e293b;
        font-weight: 700;
        font-size: 14px;
        padding: 12px 16px;
        border-bottom: 1px solid #cbd5e1;
    }
    .certa-table td {
        padding: 10px 16px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
        color: #334155;
        font-size: 14px;
    }
    .certa-table th:nth-child(1), .certa-table td:nth-child(1),
    .certa-table th:nth-child(2), .certa-table td:nth-child(2) {
        border-right: 1px solid #cbd5e1;
    }
    .certa-table tr:last-child td {
        border-bottom: none;
    }

    /* Style Radio Button Dot */
    .certa-radio-inline {
        display: inline-flex;
        align-items: center;
        margin-right: 1.5rem;
        cursor: pointer;
    }
    .certa-radio-inline .form-check-input {
        width: 1.25em;
        height: 1.25em;
        margin-top: 0;
        margin-right: 8px;
        cursor: pointer;
        border: 2px solid #475569;
        background-color: #ffffff;
        appearance: none;
        -webkit-appearance: none;
        border-radius: 50%;
        display: inline-block;
        position: relative;
    }
    .certa-radio-inline .form-check-input:checked {
        border-color: #1e293b;
        background-color: #ffffff;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='2.5' fill='%231e293b'/%3e%3c/svg%3e");
        background-position: center;
        background-repeat: no-repeat;
    }
    .certa-radio-inline .form-check-label {
        font-size: 13.5px;
        font-weight: 500;
        color: #334155;
        cursor: pointer;
    }

    /* Bottom Cards */
    .certa-box-panel {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: #ffffff;
        padding: 16px;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .certa-panel-header {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 12px;
        margin-bottom: 12px;
    }
    .textarea-container {
        position: relative;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    .textarea-container textarea {
        width: 100%;
        flex-grow: 1;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 10px;
        font-size: 13.5px;
        resize: none;
        outline: none;
    }
    .textarea-container textarea:focus {
        border-color: #1e293b;
        box-shadow: 0 0 0 2px rgba(30, 41, 59, 0.1);
    }
    .char-limit {
        position: absolute;
        bottom: 8px;
        right: 12px;
        font-size: 11px;
        color: #64748b;
        background: #ffffff;
        padding: 0 4px;
    }
    .upload-zone {
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 24px 16px;
        text-align: center;
        cursor: pointer;
        background: #ffffff;
        transition: background 0.2s ease;
        margin-top: auto;
        margin-bottom: auto;
    }
    .upload-zone:hover {
        background: #f8fafc;
        border-color: #94a3b8;
    }
    .btn-simpan-certa {
        border: 1px solid #1e293b;
        background: #ffffff;
        color: #1e293b;
        font-weight: 700;
        border-radius: 8px;
        padding: 8px 20px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        transition: all 0.2s ease;
    }
    .btn-simpan-certa:hover {
        background: #1e293b;
        color: #ffffff;
    }
</style>

<!-- Header Title -->
<div class="mb-3">
    <h5 class="form-header-title mb-1">Form CERTA</h5>
    <div class="form-header-sub">Input hasil pengecekan kondisi perangkat data center</div>
</div>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <ul class="mb-0 ps-3">
            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                <li><?= esc($err) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form action="<?= base_url('form-certa/simpan') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <!-- Baris 1: Form Header -->
    <div class="row g-3 mb-3">
        <div class="col-12 col-md-3">
            <div class="meta-card">
                <div class="meta-label">Nama Petugas</div>
                <div class="meta-input-box">
                    <i class="bi bi-person"></i>
                    <input type="text" name="nama_petugas" class="form-control" placeholder="Nama Petugas" value="<?= old('nama_petugas') ?>" required>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <div class="meta-card">
                <div class="meta-label">Hari /Tanggal</div>
                <div class="meta-input-box">
                    <i class="bi bi-calendar3"></i>
                    <input type="date" name="tanggal" class="form-control" value="<?= old('tanggal', date('Y-m-d')) ?>" required>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <div class="meta-card">
                <div class="meta-label">Shift</div>
                <div class="meta-input-box">
                    <i class="bi bi-brightness-high"></i>
                    <select name="shift_id" class="form-select" required>
                        <?php foreach ($shifts as$sh): ?>
                            <option value="<?= $sh['id'] ?>" <?= old('shift_id', $currentShift['id'] ?? null) == $sh['id'] ? 'selected' : '' ?>>
                                <?= strtoupper(esc($sh['name'])) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Jam Pengecekan: Diubah menjadi Teks Manual -->
        <div class="col-12 col-md-3">
            <div class="meta-card">
                <div class="meta-label">Jam Pengecekan</div>
                <div class="meta-input-box">
                    <i class="bi bi-clock"></i>
                    <input type="text" name="jam_pengecekan" class="form-control" placeholder="HH:MM" value="<?= old('jam_pengecekan', date('H:i')) ?>" required>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris 2: Tabel Perangkat -->
    <div class="certa-table-wrapper mb-3">
        <table class="certa-table">
            <thead>
                <tr>
                    <th style="width: 65px;" class="text-center">No</th>
                    <th style="width: 320px;">Nama Perangkat</th>
                    <th>Kondisi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach ($groupedAssets as $categoryName =>$items): ?>
                    <?php foreach ($items as$asset): ?>
                        <?php
                            $assetName = trim($asset['name']);
                            $nameLower = strtolower($assetName);
                            
                            // Opsi Kondisi
                            if (str_contains($nameLower, 'solar genset')) {
                                $options = ['0\%', '25\%', '75\%', '100\%'];                             } elseif (str_contains($nameLower, 'baterai') && !str_contains($nameLower, 'ac ')) {$options = ['Normal', 'Tidak Normal'];
                            } elseif (str_contains($nameLower, 'pac')) {$options = ['Normal', 'Standby', 'Off'];
                            } else {
                                $options = ['Normal', 'Off'];
                            }
                        ?>
                        <tr>
                            <td class="text-center fw-semibold"><?= $no++ ?></td>
                            <td class="fw-semibold text-dark"><?= esc($assetName) ?></td>
                            <td>
                                <?php foreach ($options as $i =>$opt): ?>
                                    <?php 
                                        $cleanOpt = str_replace('\\', '',$opt); 
                                    ?>
                                    <div class="certa-radio-inline">
                                        <input class="form-check-input" type="radio"
                                               name="kondisi[<?= $asset['id'] ?>]"
                                               id="kondisi_<?= $asset['id'] ?>_<?=$i ?>"
                                               value="<?= esc($cleanOpt) ?>" 
                                               <?= old("kondisi.{$asset['id']}") === $cleanOpt ? 'checked' : '' ?> 
                                               required>
                                        <label class="form-check-label" for="kondisi_<?= $asset['id'] ?>_<?=$i ?>">
                                            <?= esc($cleanOpt) ?>
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

    <!-- Baris 3: Catatan & Foto -->
    <div class="row g-3 mb-3">
        <div class="col-12 col-md-6">
            <div class="certa-box-panel">
                <div class="certa-panel-header">
                    <i class="bi bi-clipboard-data fs-5"></i>
                    <span>Catatan & Temuan</span>
                </div>
                <div class="text-muted small mb-2">Masukkan catatan atau temuan selama pengecekkan...</div>
                
                <div class="textarea-container">
                    <textarea name="catatan" id="catatanInput" rows="4" maxlength="500" placeholder=""><?= old('catatan') ?></textarea>
                    <div class="char-limit" id="charCounter">0/500</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="certa-box-panel">
                <div class="certa-panel-header">
                    <i class="bi bi-camera fs-5"></i>
                    <span>Bukti Foto Pengecekan</span>
                </div>
                
                <label for="buktiFotoInput" class="upload-zone my-auto">
                    <i class="bi bi-cloud-arrow-up display-6 d-block mb-2 text-dark"></i>
                    <span class="fw-bold text-dark d-block mb-1 fs-6">Klik untuk upload foto</span>
                    <span class="text-muted small">Format : JPG, PNG</span>
                </label>
                <input type="file" id="buktiFotoInput" name="bukti_foto[]" accept="image/jpeg,image/png" multiple class="d-none">
                
                <div id="fileSelectedInfo" class="text-center text-success small mt-2 fw-semibold" style="display: none;"></div>
            </div>
        </div>
    </div>

    <!-- Baris 4: Tombol Simpan -->
    <div class="d-flex justify-content-end mb-4">
        <button type="submit" class="btn-simpan-certa">
            <i class="bi bi-floppy fs-5"></i> Simpan Pengecekan
        </button>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('extra_js') ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const textarea = document.getElementById('catatanInput');
        const counter = document.getElementById('charCounter');
        
        if (textarea && counter) {
            const updateCount = () => {
                counter.textContent = `${textarea.value.length}/500`;
            };
            textarea.addEventListener('input', updateCount);
            updateCount();
        }

        const fileInput = document.getElementById('buktiFotoInput');
        const fileInfo = document.getElementById('fileSelectedInfo');
        
        if (fileInput && fileInfo) {
            fileInput.addEventListener('change', function () {
                if (this.files && this.files.length > 0) {
                    fileInfo.style.display = 'block';
                    fileInfo.textContent = `✓ ${this.files.length} foto terpilih`;
                } else {
                    fileInfo.style.display = 'none';
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>