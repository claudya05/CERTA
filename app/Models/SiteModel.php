<?php

namespace App\Models;

use CodeIgniter\Model;

class SiteModel extends Model
{
    protected $table            = 'm_site';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['name'];
    protected $useTimestamps    = false;
    protected $returnType       = 'array';
}
