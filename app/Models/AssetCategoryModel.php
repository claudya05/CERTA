<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetCategoryModel extends Model
{
    protected $table            = 'm_asset_category';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['name', 'icon', 'sort_order'];
    protected $useTimestamps    = false;
    protected $returnType       = 'array';
    protected $orderColumn      = 'sort_order';

    public function getOrdered(): array
    {
        return $this->orderBy('sort_order', 'ASC')->findAll();
    }
}
