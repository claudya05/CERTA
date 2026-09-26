<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tabel master aset/perangkat yang dicek rutin.
 * input_type menentukan jenis pilihan radio button di Form CERTA:
 *  - status_3   : Normal / Standby / Off   (mis. PAC, UPS, MCB, Panel Genset)
 *  - status_2   : Normal / Tidak Normal    (mis. Baterai)
 *  - percentage : 0% / 25% / 75% / 100%    (mis. Solar Genset)
 */
class CreateMAssetTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'site_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'category_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'input_type' => [
                'type'       => "ENUM('status_3','status_2','percentage')",
                'default'    => 'status_3',
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('site_id');
        $this->forge->addKey('category_id');
        $this->forge->addForeignKey('site_id', 'm_site', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('category_id', 'm_asset_category', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('m_asset');
    }

    public function down()
    {
        $this->forge->dropTable('m_asset');
    }
}
