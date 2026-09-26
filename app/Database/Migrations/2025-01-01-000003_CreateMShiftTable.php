<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tabel master shift (Pagi 07:00, Siang 14:00, Malam 23:00).
 * jam_mulai dipakai juga sebagai acuan modal notifikasi pengecekan rutin.
 */
class CreateMShiftTable extends Migration
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
                'constraint' => 20,
            ],
            'jam_mulai' => [
                'type' => 'TIME',
            ],
            'sort_order' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('m_shift');
    }

    public function down()
    {
        $this->forge->dropTable('m_shift');
    }
}
