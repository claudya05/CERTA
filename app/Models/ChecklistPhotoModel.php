<?php

namespace App\Models;

use CodeIgniter\Model;

class ChecklistPhotoModel extends Model
{
    protected $table            = 'checklist_photo';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['checklist_id', 'file_path', 'original_name'];
    protected $useTimestamps    = false;
    protected $onlyUpdated      = false;
    protected $returnType       = 'array';

    public function getByChecklist(int $checklistId): array
    {
        return $this->where('checklist_id', $checklistId)->findAll();
    }
}
