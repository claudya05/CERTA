<?php

namespace App\Controllers;

use App\Models\ActivityLogModel;
use App\Models\AssetModel;
use App\Models\ChecklistDetailModel;
use App\Models\ChecklistModel;
use App\Models\ChecklistPhotoModel;
use App\Models\ShiftModel;

class FormCerta extends BaseController
{
    public function index()
    {
        $assetModel = new AssetModel();
        $shiftModel = new ShiftModel();

        $data = [
            'title'          => 'Form CERTA',
            'groupedAssets'  => $assetModel->getGroupedByCategory(),
            'shifts'         => $shiftModel->getOrdered(),
            'currentShift'   => $shiftModel->getCurrentShift(),
        ];

        return view('form_certa/index', $data);
    }

    public function simpan()
{
    // Ubah aturan validasi kondisi menjadi 'kondisi.*'
    $rules = [
        'nama_petugas'   => 'required|min_length[3]',
        'tanggal'        => 'required|valid_date',
        'shift_id'       => 'required|integer',
        'jam_pengecekan' => 'required',
        'kondisi.*'      => 'required', // Tambahkan .* agar memvalidasi tiap item array
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $checklistModel = new ChecklistModel();
    $detailModel    = new ChecklistDetailModel();
    $photoModel     = new ChecklistPhotoModel();
    $activityModel  = new ActivityLogModel();
    $assetModel     = new AssetModel();
    $shiftModel     = new ShiftModel();

    $db = \Config\Database::connect();
    
    try {
        $db->transException(true)->transStart();

        // 1. Simpan header checklist
       $checklistModel->insert([
            'nama_petugas'   => $this->request->getPost('nama_petugas'),
            'tanggal'        => $this->request->getPost('tanggal'),
            'shift_id'       => $this->request->getPost('shift_id'),
            'jam_pengecekan' => $this->request->getPost('jam_pengecekan'),
            'catatan'        => $this->request->getPost('catatan'),
            'status_selesai' => 1,
        ]);

        // Ambil ID secara pasti menggunakan getInsertID()
        $checklistId = $checklistModel->getInsertID();

        if (!$checklistId) {
            throw new \Exception('Gagal mendapatkan ID checklist.');
        }

        // 2. Simpan detail per aset
        $kondisiInput = $this->request->getPost('kondisi') ?? [];
        $assetsById   = array_column($assetModel->findAll(), null, 'id');

        foreach ($kondisiInput as $assetId => $kondisi) {
            $asset = $assetsById[$assetId] ?? null;
            if (!$asset) {
                continue;
            }

            $detailModel->insert([
                'checklist_id'  => $checklistId,
                'asset_id'      => $assetId,
                'kondisi'       => $kondisi,
                'is_bermasalah' => AssetModel::isBermasalah($asset['input_type'], $kondisi) ? 1 : 0,
            ]);
        }

        // 3. Simpan bukti foto & pastikan direktori tujuan tersedia
        $uploadDir = FCPATH . 'uploads/bukti_foto';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $files = $this->request->getFiles();
        if (!empty($files['bukti_foto'])) {
            foreach ($files['bukti_foto'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move($uploadDir, $newName);

                    $photoModel->insert([
                        'checklist_id'  => $checklistId,
                        'file_path'     => 'uploads/bukti_foto/' . $newName,
                        'original_name' => $file->getClientName(),
                    ]);
                }
            }
        }

        // 4. Catat activity log
        $shift = $shiftModel->find($this->request->getPost('shift_id'));
        $activityModel->insert([
            'checklist_id' => $checklistId,
            'nama_petugas' => $this->request->getPost('nama_petugas'),
            'aktivitas'    => 'melakukan checklist shift ' . strtolower($shift['name'] ?? '') . ' Data Center 1',
        ]);

        $db->transComplete();

        return redirect()->to('/form-certa/success');

    } catch (\Exception $e) {
        log_message('error', 'Gagal simpan CERTA: ' . $e->getMessage());
        return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
    }
}

    public function success()
    {
        return view('form_certa/success', ['title' => 'Form CERTA']);
    }
}
