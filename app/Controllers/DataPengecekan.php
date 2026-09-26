<?php

namespace App\Controllers;

use App\Models\ChecklistModel;
use App\Models\ChecklistDetailModel;
use App\Models\ChecklistPhotoModel;

class DataPengecekan extends BaseController
{
    public function index()
    {
        $checklistModel = new ChecklistModel();
        $detailModel    = new ChecklistDetailModel();
        $photoModel     = new ChecklistPhotoModel();

        $dateFrom = $this->request->getGet('date_from') ?? date('Y-m-d');
        $dateTo   = $this->request->getGet('date_to') ?? date('Y-m-d');
        $teknisi  = $this->request->getGet('teknisi');
        $limit    = (int) ($this->request->getGet('limit') ?? 50);

        $checklists = $checklistModel->getFiltered($dateFrom, $dateTo, $teknisi, $limit);

        // Lampirkan detail per-kategori & foto ke tiap checklist (untuk card grouping)
        foreach ($checklists as &$checklist) {
            $details = $detailModel->getByChecklist($checklist['id']);

            $grouped = [];
            foreach ($details as $d) {
                $grouped[$d['category_name']][] = $d;
            }

            $checklist['grouped_detail'] = $grouped;
            $checklist['photos']         = $photoModel->getByChecklist($checklist['id']);
        }
        unset($checklist);

        $data = [
            'title'      => 'Data Pengecekan',
            'checklists' => $checklists,
            'dateFrom'   => $dateFrom,
            'dateTo'     => $dateTo,
            'teknisi'    => $teknisi,
            'limit'      => $limit,
        ];

        return view('data_pengecekan/index', $data);
    }

    /** Export hasil filter saat ini ke Excel (CSV) — ganti dengan PhpSpreadsheet bila perlu format .xlsx penuh. */
        public function exportCsv()
    {
        $checklistModel = new ChecklistModel();
        $detailModel    = new ChecklistDetailModel();

        $dateFrom = $this->request->getGet('date_from') ?? date('Y-m-d');
        $dateTo   = $this->request->getGet('date_to') ?? date('Y-m-d');
        $teknisi  = $this->request->getGet('teknisi');

        $checklists = $checklistModel->getFiltered($dateFrom, $dateTo, $teknisi, 1000);
        $filename  = 'data-pengecekan-' . $dateFrom . '_sd_' . $dateTo . '.csv';

        // Buka temporary memory buffer
        $output = fopen('php://temp', 'r+');

        // Tambahkan Byte Order Mark (BOM) UTF-8 agar karakter/simbol terbaca rapi saat dibuka di Microsoft Excel
        fputs($output, "\xEF\xBB\xBF");

        // Header Kolom CSV
        fputcsv($output, ['Nama Petugas', 'Tanggal', 'Shift', 'Jam Pengecekan', 'Nama Aset', 'Kondisi', 'Catatan']);

        // Isi Baris Data CSV
        foreach ($checklists as $checklist) {
            $details = $detailModel->getByChecklist($checklist['id']);
            foreach ($details as $d) {
                fputcsv($output, [
                    $checklist['nama_petugas'],
                    $checklist['tanggal'],
                    $checklist['shift_name'],
                    $checklist['jam_pengecekan'],
                    $d['asset_name'],
                    kondisi_display($d['input_type'], $d['kondisi']),
                    $checklist['catatan'],
                ]);
            }
        }

        // Reset pointer stream ke awal
        rewind($output);
        $csvData = stream_get_contents($output);
        fclose($output);

        // Kirim response download bawaan CodeIgniter 4
        return $this->response->download($filename, $csvData)->setContentType('text/csv');
    }

    public function delete(int $id)
    {
        $checklistModel = new ChecklistModel();
        $checklistModel->delete($id); // checklist_detail & checklist_photo ikut terhapus via FK CASCADE

        return redirect()->to('/data-pengecekan')->with('success', 'Data pengecekan berhasil dihapus.');
    }
}
