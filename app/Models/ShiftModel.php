<?php

namespace App\Models;

use CodeIgniter\Model;

class ShiftModel extends Model
{
    protected $table            = 'm_shift';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['name', 'jam_mulai', 'sort_order'];
    protected $useTimestamps    = false;
    protected $returnType       = 'array';

    public function getOrdered(): array
    {
        return $this->orderBy('sort_order', 'ASC')->findAll();
    }

    /**
     * Cari shift yang jam_mulai-nya paling mendekati waktu sekarang.
     * Dipakai untuk menentukan shift default saat modal notifikasi muncul.
     */
    public function getCurrentShift(?string $time = null): ?array
    {
        $time = $time ?? date('H:i:s');

        return $this->where('jam_mulai <=', $time)
                    ->orderBy('jam_mulai', 'DESC')
                    ->first() ?? $this->orderBy('jam_mulai', 'DESC')->first();
    }
}
