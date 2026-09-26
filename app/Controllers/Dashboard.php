<?php

namespace App\Controllers;

use App\Models\AssetModel;
use App\Models\ChecklistDetailModel;
use App\Models\ChecklistModel;
use App\Models\ShiftModel;
use App\Models\ActivityLogModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $checklistModel = new ChecklistModel();
        $detailModel    = new ChecklistDetailModel();
        $shiftModel     = new ShiftModel();
        $activityModel  = new ActivityLogModel();
        $assetModel     = new AssetModel();

        $today = date('Y-m-d');

        // Card: Total Laporan, Pengecekan Hari Ini
        $totalLaporan     = $checklistModel->countAll();
        $pengecekanHariIni = $checklistModel->countByDate($today);

        // Card: Aset Bermasalah (+ isi modal "Daftar Aset Bermasalah")
        $asetBermasalah = $detailModel->getLatestProblems();

        // Card: Status Shift Hari Ini (Pagi/Siang/Malam - Selesai/Belum Selesai)
        $allShifts     = $shiftModel->getOrdered();
        $checklistHariIni = $checklistModel->getShiftStatusToday($today);
        $statusShift = array_map(function ($shift) use ($checklistHariIni) {
            $found = null;
            foreach ($checklistHariIni as $c) {
                if ($c['shift_id'] == $shift['id']) {
                    $found = $c;
                    break;
                }
            }
            return [
                'shift'   => $shift,
                'selesai' => $found !== null,
            ];
        }, $allShifts);

        // Donut Chart: Status Peralatan ON/OFF/Standby
        $statusPeralatan = $detailModel->getStatusSummary();

        // Card Status Terkini: kondisi terakhir UPS A & UPS B Data Center
        $upsA = $this->getLatestKondisi($detailModel, 'UPS A Data Center');
        $upsB = $this->getLatestKondisi($detailModel, 'UPS B Data Center');

        // Fuel Gauge: Solar Genset PLN & PLTU (nilai % terakhir)
        $solarPln  = $this->getLatestKondisi($detailModel, 'Solar Genset PLN');
        $solarPltu = $this->getLatestKondisi($detailModel, 'Solar Genset PLTU');

        // Aktivitas Terbaru
        $aktivitasTerbaru = $activityModel->getRecent(3);

        $data = [
            'title'             => 'Dashboard',
            'totalLaporan'      => $totalLaporan,
            'pengecekanHariIni' => $pengecekanHariIni,
            'asetBermasalah'    => $asetBermasalah,
            'statusShift'       => $statusShift,
            'statusPeralatan'   => $statusPeralatan,
            'upsA'              => $upsA,
            'upsB'              => $upsB,
            'solarPln'          => $solarPln,
            'solarPltu'         => $solarPltu,
            'aktivitasTerbaru'  => $aktivitasTerbaru,
        ];

        return view('dashboard/index', $data);
    }

    /** Ambil kondisi terakhir 1 aset berdasarkan nama (helper internal untuk widget kecil). */
    private function getLatestKondisi(ChecklistDetailModel $detailModel, string $assetName): ?string
    {
        $row = $detailModel->select('checklist_detail.kondisi')
            ->join('m_asset', 'm_asset.id = checklist_detail.asset_id')
            ->where('m_asset.name', $assetName)
            ->orderBy('checklist_detail.id', 'DESC')
            ->first();

        return $row['kondisi'] ?? null;
    }
}
