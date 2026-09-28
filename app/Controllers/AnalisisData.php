<?php

namespace App\Controllers;

use App\Models\AssetModel;
use App\Models\ChecklistDetailModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class AnalisisData extends BaseController
{
    public function index()
    {
        $assetModel = new AssetModel();

        $data = [
            'title'  => 'Analisis Data Historis',
            'assets' => $assetModel->orderBy('category_id', 'ASC')->orderBy('sort_order', 'ASC')->findAll(),
        ];

        return view('analisis_data/index', $data);
    }

    /**
     * Endpoint Data AJAX untuk Chart.js
     */
    public function chartData()
    {
        $assetId  = (int) $this->request->getGet('asset_id');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo   = $this->request->getGet('date_to');

        if (!$dateFrom) $dateFrom = date('Y-m-d', strtotime('-30 days'));
        if (!$dateTo)   $dateTo   = date('Y-m-d');

        $assetModel  = new AssetModel();
        $detailModel = new ChecklistDetailModel();

        $asset = $assetModel->find($assetId);

        // Query JOIN disesuaikan dengan nama tabel DB Anda: checklist_detail & checklist
        $history = $detailModel->select('checklist_detail.*, checklist.tanggal, checklist.jam_pengecekan, checklist.created_at')
            ->join('checklist', 'checklist.id = checklist_detail.checklist_id')
            ->where('checklist_detail.asset_id', $assetId)
            ->where('checklist.tanggal >=', $dateFrom)
            ->where('checklist.tanggal <=', $dateTo)
            ->orderBy('checklist.tanggal', 'ASC')
            ->orderBy('checklist.jam_pengecekan', 'ASC')
            ->orderBy('checklist.created_at', 'ASC')
            ->findAll();

        $labels = [];
        $values = [];

        // Deteksi Tipe Perangkat
        $assetName  = $asset['name'] ?? $asset['asset_name'] ?? 'Aset';
        $assetLower = strtolower($assetName);
        
        $deviceType = 'binary_on_off';

        if (str_contains($assetLower, 'pac')) {
            $deviceType = 'pac';
        } elseif (str_contains($assetLower, 'solar')) {
            $deviceType = 'solar';
        } elseif (str_contains($assetLower, 'baterai') && !str_contains($assetLower, 'ac')) {
            $deviceType = 'baterai';
        }

        foreach ($history as $row) {
            // Label Sumbu X (Tanggal & Jam Input)
            $tglRaw = $row['tanggal'] ?? $row['created_at'] ?? '';
            $tgl    = !empty($tglRaw) ? date('d/m/Y', strtotime($tglRaw)) : '';
            
            $jamRaw = $row['jam_pengecekan'] ?? (!empty($row['created_at']) ? date('H:i:s', strtotime($row['created_at'])) : '');
            $jam    = !empty($jamRaw) ? ' ' . date('H:i', strtotime($jamRaw)) : '';

            $labels[] = $tgl . $jam;

            // Mapping Nilai Grafik
            $kondisi  = trim((string)($row['kondisi'] ?? ''));
            $valClean = str_replace('%', '', $kondisi);

            if (is_numeric($valClean)) {
                $values[] = (float) $valClean;
            } else {
                $valLower = strtolower($kondisi);
                if (in_array($valLower, ['normal', 'on', 'baik'])) {
                    $values[] = 100;
                } elseif ($valLower === 'standby') {
                    $values[] = 50;
                } else {
                    $values[] = 0;
                }
            }
        }

        return $this->response->setJSON([
            'asset_name'  => $assetName,
            'device_type' => $deviceType,
            'labels'      => $labels,
            'values'      => $values,
        ]);
    }

    public function getChartData()
    {
        return $this->chartData();
    }

    /**
     * Export Grafik ke PDF
     */
    public function exportPdf()
    {
        $chartImage = $this->request->getPost('chart_image');
        $assetId    = $this->request->getPost('asset_id');
        $dateFrom   = $this->request->getPost('date_from');
        $dateTo     = $this->request->getPost('date_to');

        $assetModel = new AssetModel();
        $asset      = $assetModel->find($assetId);
        $assetName  = $asset ? ($asset['name'] ?? $asset['asset_name'] ?? 'Aset') : 'Aset';

        $data = [
            'chartImage' => $chartImage,
            'assetName'  => $assetName,
            'dateFrom'   => $dateFrom ? date('d/m/Y', strtotime($dateFrom)) : '-',
            'dateTo'     => $dateTo ? date('d/m/Y', strtotime($dateTo)) : '-',
        ];

        $html = view('pdf/export_grafik', $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'Grafik_Analisis_' . str_replace(' ', '_', $assetName) . '_' . date('Ymd') . '.pdf';
        $dompdf->stream($filename, ['Attachment' => 1]);
        exit;
    }
}