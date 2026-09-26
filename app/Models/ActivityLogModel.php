<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table            = 'activity_log';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    
    protected $allowedFields    = ['checklist_id', 'nama_petugas', 'aktivitas'];
    
    // Konfigurasi Timestamps (Hanya created_at)
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = ''; // Matikan pencarian kolom updated_at

    protected $returnType       = 'array';

    /** Untuk widget "Aktivitas Terbaru" di Dashboard. */
    public function getRecent(int $limit = 5): array
    {
        return $this->orderBy('created_at', 'DESC')->findAll($limit);
    }
}