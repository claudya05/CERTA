<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Jalankan dengan: php spark db:seed MasterDataSeeder
 *
 * Mengisi data master (bukan data transaksi) yang dibutuhkan aplikasi
 * agar Dashboard, Data Pengecekan, Analisis Data, dan Form CERTA langsung
 * bisa dipakai tanpa input manual lewat UI (karena tidak ada modul
 * pengelolaan master aset di scope awal ini).
 */
class MasterDataSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // 1. Site (fixed: Data Center & GUBA)
        $this->db->table('m_site')->insertBatch([
            ['id' => 1, 'name' => 'Data Center', 'created_at' => $now],
            ['id' => 2, 'name' => 'GUBA',        'created_at' => $now],
        ]);

        // 2. Kategori aset
        $this->db->table('m_asset_category')->insertBatch([
            ['id' => 1, 'name' => 'Peralatan Utama', 'icon' => 'bi-hdd-rack',  'sort_order' => 1, 'created_at' => $now],
            ['id' => 2, 'name' => 'Power & Genset',  'icon' => 'bi-lightning', 'sort_order' => 2, 'created_at' => $now],
            ['id' => 3, 'name' => 'UPS',             'icon' => 'bi-battery-charging', 'sort_order' => 3, 'created_at' => $now],
            ['id' => 4, 'name' => 'Baterai',         'icon' => 'bi-battery-full', 'sort_order' => 4, 'created_at' => $now],
        ]);

        // 3. Shift
        $this->db->table('m_shift')->insertBatch([
            ['id' => 1, 'name' => 'Pagi',  'jam_mulai' => '07:00:00', 'sort_order' => 1, 'created_at' => $now],
            ['id' => 2, 'name' => 'Siang', 'jam_mulai' => '14:00:00', 'sort_order' => 2, 'created_at' => $now],
            ['id' => 3, 'name' => 'Malam', 'jam_mulai' => '23:00:00', 'sort_order' => 3, 'created_at' => $now],
        ]);

        // 4. Master Aset — 17 item, sesuai daftar "Pilih Aset" pada Analisis Data
        //    site_id: 1 = Data Center, 2 = GUBA
        //    category_id: 1 = Peralatan Utama, 2 = Power & Genset, 3 = UPS, 4 = Baterai
        //    input_type: status_3 (Normal/Standby/Off), status_2 (Normal/Tidak Normal), percentage (0/25/75/100)
        $assets = [
            ['name' => 'PAC 1 Data Center',            'site_id' => 1, 'category_id' => 1, 'input_type' => 'status_3',   'sort_order' => 1],
            ['name' => 'PAC 2 Data Center',            'site_id' => 1, 'category_id' => 1, 'input_type' => 'status_3',   'sort_order' => 2],
            ['name' => 'MCB A Rak Server',              'site_id' => 1, 'category_id' => 1, 'input_type' => 'status_3',   'sort_order' => 3],
            ['name' => 'MCB B Rak Server',              'site_id' => 1, 'category_id' => 1, 'input_type' => 'status_3',   'sort_order' => 4],

            ['name' => 'Panel Genset PLN',              'site_id' => 1, 'category_id' => 2, 'input_type' => 'status_3',   'sort_order' => 5],
            ['name' => 'Solar Genset PLN',               'site_id' => 1, 'category_id' => 2, 'input_type' => 'percentage', 'sort_order' => 6],
            ['name' => 'Panel Genset PLTU',              'site_id' => 1, 'category_id' => 2, 'input_type' => 'status_3',   'sort_order' => 7],
            ['name' => 'Solar Genset PLTU',              'site_id' => 1, 'category_id' => 2, 'input_type' => 'percentage', 'sort_order' => 8],

            ['name' => 'UPS A Data Center',              'site_id' => 1, 'category_id' => 3, 'input_type' => 'status_3',   'sort_order' => 9],
            ['name' => 'AC UPS A Data Center',           'site_id' => 1, 'category_id' => 3, 'input_type' => 'status_3',   'sort_order' => 10],
            ['name' => 'UPS B Data Center',              'site_id' => 1, 'category_id' => 3, 'input_type' => 'status_3',   'sort_order' => 11],
            ['name' => 'AC UPS B Data Center',           'site_id' => 1, 'category_id' => 3, 'input_type' => 'status_3',   'sort_order' => 12],
            ['name' => 'UPS GUBA',                       'site_id' => 2, 'category_id' => 3, 'input_type' => 'status_3',   'sort_order' => 13],

            ['name' => 'AC Ruang Baterai Data Center',   'site_id' => 1, 'category_id' => 4, 'input_type' => 'status_3',   'sort_order' => 14],
            ['name' => 'Baterai Data Center',            'site_id' => 1, 'category_id' => 4, 'input_type' => 'status_2',   'sort_order' => 15],
            ['name' => 'AC Baterai GUBA',                'site_id' => 2, 'category_id' => 4, 'input_type' => 'status_3',   'sort_order' => 16],
            ['name' => 'Baterai GUBA',                   'site_id' => 2, 'category_id' => 4, 'input_type' => 'status_2',   'sort_order' => 17],
        ];

        foreach ($assets as &$asset) {
            $asset['is_active']  = 1;
            $asset['created_at'] = $now;
            $asset['updated_at'] = $now;
        }
        unset($asset);

        $this->db->table('m_asset')->insertBatch($assets);
    }
}
