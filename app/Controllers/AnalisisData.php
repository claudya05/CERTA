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
     * Endpoint AJAX (dipanggil tombol "Tampilkan Grafik") -> JSON untuk Chart.js.
     * GET /analisis-data/chart-data?asset_id=1&date_from=...&date_to=...
     */
    public function getChartData()
    {
        $assetId  = (int) $this->request->getGet('asset_id');
        $dateFrom = $this->request->getGet('date_from') ?? date('Y-m-01');
        $dateTo   = $this->request->getGet('date_to') ?? date('Y-m-d');

        $assetModel  = new AssetModel();
        $detailModel = new ChecklistDetailModel();

        $asset   = $assetModel->find($assetId);
        $history = $detailModel->getHistoryByAsset($assetId, $dateFrom, $dateTo);

        $labels = [];
        $values = [];

        foreach ($history as $row) {
            $labels[] = date('d/m/Y', strtotime($row['tanggal'])) . ' ' . substr($row['jam_pengecekan'], 0, 5);

            // Untuk chart: percentage -> angka 0-100, status_3 -> mapping Off=0/Standby=50/On=100
            if ($asset && $asset['input_type'] === 'percentage') {
                $values[] = (int) $row['kondisi'];
            } else {
                $values[] = match ($row['kondisi']) {
                    'Off', 'Tidak Normal' => 0,
                    'Standby'             => 50,
                    default               => 100, // Normal
                };
            }
        }

        return $this->response->setJSON([
            'asset_name' => $asset['name'] ?? '',
            'input_type' => $asset['input_type'] ?? 'status_3',
            'labels'     => $labels,
            'values'     => $values,
        ]);
    }

    /** Export grafik/tren yang sedang tampil ke PDF — implementasikan dengan library dompdf/mpdf sesuai kebutuhan. */
    public function exportPdf()
{
    // Ambil data POST dari form hidden
    $chartImage = $this->request->getPost('chart_image');
    $assetId    = $this->request->getPost('asset_id');
    $dateFrom   = $this->request->getPost('date_from') ?? date('Y-m-01');
    $dateTo     = $this->request->getPost('date_to') ?? date('Y-m-d');

    // Ambil info nama aset jika ada
    $assetModel = new AssetModel();
    $asset      = $assetModel->find($assetId);
    $assetName  = $asset ? $asset['name'] : 'Semua Aset';

    $data = [
        'chartImage' => $chartImage,
        'assetName'  => $assetName,
        'dateFrom'   => $dateFrom,
        'dateTo'     => $dateTo,
    ];

    // Render tampilan HTML PDF
    $html = view('pdf/export_grafik', $data);

    // Konfigurasi Dompdf
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $options->set('defaultFont', 'Helvetica');

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    $filename = 'grafik-analisis-' . $dateFrom . '_sd_' . $dateTo . '.pdf';
    $dompdf->stream($filename, ['Attachment' => 1]);
    exit;
}
}
