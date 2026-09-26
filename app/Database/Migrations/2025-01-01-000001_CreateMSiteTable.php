<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tabel master lokasi (fixed: Data Center & GUBA).
 * Tidak ada CRUD UI untuk tabel ini, hanya diseed sekali.
 */
class CreateMSiteTable extends Migration
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
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('m_site');
    }

    public function down()
    {
        $this->forge->dropTable('m_site');
    }
}
