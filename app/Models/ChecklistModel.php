<?php

namespace App\Models;

use CodeIgniter\Model;

class ChecklistModel extends Model
{
    protected $table            = 'checklist';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    
    protected $allowedFields    = [
        'nama_petugas', 
        'tanggal', 
        'shift_id', 
        'jam_pengecekan',
        'catatan', 
        'status_selesai',
    ];

    // Konfigurasi Timestamps (Tabel ini memakai created_at dan updated_at)
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $returnType       = 'array';

    /** Total laporan (card "Total Laporan" di Dashboard). */
    public function countAll(): int
    {
        return $this->countAllResults();
    }

    /** Jumlah pengecekan pada tanggal tertentu (card "Pengecekan Hari Ini"). */
    public function countByDate(string $date): int
    {
        return $this->where('tanggal', $date)->countAllResults();
    }

    /** Status 3 shift pada tanggal tertentu, untuk card "Status Shift Hari Ini". */
    public function getShiftStatusToday(string $date): array
    {
        return $this->select('checklist.*, m_shift.name as shift_name, m_shift.jam_mulai')
            ->join('m_shift', 'm_shift.id = checklist.shift_id')
            ->where('checklist.tanggal', $date)
            ->orderBy('m_shift.sort_order', 'ASC')
            ->findAll();
    }

    /** Filter list untuk halaman Data Pengecekan. */
    public function getFiltered(string $dateFrom, string $dateTo, ?string $teknisi = null, int $limit = 50): array
    {
        $builder = $this->select('checklist.*, m_shift.name as shift_name')
            ->join('m_shift', 'm_shift.id = checklist.shift_id')
            ->where('checklist.tanggal >=', $dateFrom)
            ->where('checklist.tanggal <=', $dateTo);

        if (!empty($teknisi)) {
            $builder->like('checklist.nama_petugas', $teknisi);
        }

        return $builder->orderBy('checklist.created_at', 'DESC')
            ->findAll($limit);
    }
}