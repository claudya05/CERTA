<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetModel extends Model
{
    protected $table            = 'm_asset';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'site_id', 'category_id', 'name', 'input_type', 'sort_order', 'is_active',
    ];
    protected $useTimestamps    = true;
    protected $returnType       = 'array';

    /**
     * Ambil semua aset aktif, dikelompokkan per kategori.
     * Dipakai di Form CERTA (render tabel dinamis) & Data Pengecekan (grouping card).
     *
     * @return array<string, array>
     */
    public function getGroupedByCategory(): array
    {
        $rows = $this->select('m_asset.*, m_asset_category.name as category_name, m_site.name as site_name')
            ->join('m_asset_category', 'm_asset_category.id = m_asset.category_id')
            ->join('m_site', 'm_site.id = m_asset.site_id')
            ->where('m_asset.is_active', 1)
            ->orderBy('m_asset_category.sort_order', 'ASC')
            ->orderBy('m_asset.sort_order', 'ASC')
            ->findAll();

        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row['category_name']][] = $row;
        }

        return $grouped;
    }

    /** 
     * Opsi radio button sesuai input_type, dipakai di view Form CERTA. 
     */
    public static function getOptions(string $inputType): array
    {
        return match ($inputType) {
            'status_3', 'normal_standby_off' => ['Normal', 'Standby', 'Off'],
            'status_2', 'normal_off'         => ['Normal', 'Off'],
            'normal_tidak_normal'            => ['Normal', 'Tidak Normal'],
            'percentage'                     => ['0%', '25%', '75%', '100%'],
            default                          => ['Normal', 'Off'],
        };
    }

    /** 
     * Nilai yang dianggap "bermasalah" untuk keperluan badge / laporan summary. 
     */
    public static function isBermasalah(string $inputType, string $kondisi): bool
    {
        if ($inputType === 'percentage') {
            return in_array(trim($kondisi), ['0', '0%'], true);
        }

        return in_array($kondisi, ['Off', 'Tidak Normal'], true);
    }
}