<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Detail hasil pengecekan per-aset untuk setiap submit checklist.
 * kondisi menyimpan nilai mentah sesuai input_type aset terkait:
 *  - status_3/status_2 -> 'Normal' | 'Standby' | 'Off' | 'Tidak Normal'
 *  - percentage        -> '0' | '25' | '75' | '100'
 * is_bermasalah dihitung & disimpan saat insert agar query dashboard cepat
 * (tidak perlu CASE WHEN berat di setiap query "Aset Bermasalah").
 */
class CreateChecklistDetailTable extends Migration
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
            'checklist_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'asset_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'kondisi' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'is_bermasalah' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('checklist_id');
        $this->forge->addKey('asset_id');
        $this->forge->addForeignKey('checklist_id', 'checklist', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('asset_id', 'm_asset', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('checklist_detail');
    }

    public function down()
    {
        $this->forge->dropTable('checklist_detail');
    }
}
