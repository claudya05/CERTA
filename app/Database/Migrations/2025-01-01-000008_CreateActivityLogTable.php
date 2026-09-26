<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Log aktivitas untuk widget "Aktivitas Terbaru" di Dashboard.
 * Diisi otomatis (via Model Event / Controller) setiap kali checklist disimpan.
 */
class CreateActivityLogTable extends Migration
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
                'null'       => true,
            ],
            'nama_petugas' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'aktivitas' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'comment'    => "contoh: 'melakukan checklist shift malam Data Center 1'",
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('checklist_id');
        $this->forge->addForeignKey('checklist_id', 'checklist', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('activity_log');
    }

    public function down()
    {
        $this->forge->dropTable('activity_log');
    }
}
