<?php

namespace App\Models;

use CodeIgniter\Model;

class ChecklistDetailModel extends Model
{
    protected $table            = 'checklist_detail';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    
    protected $allowedFields    = ['checklist_id', 'asset_id', 'kondisi', 'is_bermasalah'];
    
    // Konfigurasi Timestamps (Hanya created_at)
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = ''; // Matikan pencarian kolom updated_at

    protected $returnType       = 'array';

    /** Detail per aset untuk 1 checklist, di-join dengan nama & kategori aset. */
    public function getByChecklist(int $checklistId): array
    {
        return $this->select('checklist_detail.*, m_asset.name as asset_name, m_asset.input_type, m_asset_category.name as category_name')
            ->join('m_asset', 'm_asset.id = checklist_detail.asset_id')
            ->join('m_asset_category', 'm_asset_category.id = m_asset.category_id')
            ->where('checklist_detail.checklist_id', $checklistId)
            ->orderBy('m_asset_category.sort_order', 'ASC')
            ->orderBy('m_asset.sort_order', 'ASC')
            ->findAll();
    }

    /** Daftar aset bermasalah TERKINI (dari checklist paling terakhir per aset). */
    public function getLatestProblems(): array
    {
        // Ambil id checklist_detail terbaru per asset_id, lalu filter yang bermasalah.
        $sub = $this->db->table('checklist_detail cd1')
            ->select('MAX(cd1.id) as max_id')
            ->groupBy('cd1.asset_id');

        return $this->select('checklist_detail.*, m_asset.name as asset_name')
            ->join('m_asset', 'm_asset.id = checklist_detail.asset_id')
            ->whereIn('checklist_detail.id', $sub)
            ->where('checklist_detail.is_bermasalah', 1)
            ->findAll();
    }

    /** Riwayat kondisi 1 aset dalam rentang tanggal, untuk Grafik Tren Analisis Data. */
    public function getHistoryByAsset(int $assetId, string $dateFrom, string $dateTo): array
    {
        return $this->select('checklist_detail.kondisi, checklist.tanggal, checklist.jam_pengecekan')
            ->join('checklist', 'checklist.id = checklist_detail.checklist_id')
            ->where('checklist_detail.asset_id', $assetId)
            ->where('checklist.tanggal >=', $dateFrom)
            ->where('checklist.tanggal <=', $dateTo)
            ->orderBy('checklist.tanggal', 'ASC')
            ->orderBy('checklist.jam_pengecekan', 'ASC')
            ->findAll();
    }

    /** Ringkasan status ON/OFF/Standby seluruh aset (untuk donut chart Dashboard). */
    public function getStatusSummary(): array
    {
        $sub = $this->db->table('checklist_detail cd1')
            ->select('MAX(cd1.id) as max_id')
            ->join('m_asset a1', 'a1.id = cd1.asset_id')
            ->where('a1.input_type', 'status_3')
            ->groupBy('cd1.asset_id');

        $rows = $this->whereIn('id', $sub)->findAll();

        $summary = ['On' => 0, 'Standby' => 0, 'Off' => 0];
        foreach ($rows as $row) {
            $key = $row['kondisi'] === 'Normal' ? 'On' : $row['kondisi'];
            if (isset($summary[$key])) {
                $summary[$key]++;
            }
        }

        return $summary;
    }
}