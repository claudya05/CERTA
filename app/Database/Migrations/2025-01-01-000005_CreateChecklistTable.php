<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tabel header pengecekan (1 baris = 1 kali submit Form CERTA).
 * Karena tidak ada login, nama_petugas diinput manual (free text) di form.
 */
class CreateChecklistTable extends Migration
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
            'nama_petugas' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'shift_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'jam_pengecekan' => [
                'type' => 'TIME',
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status_selesai' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1=Selesai, 0=Belum Selesai (untuk card Status Shift Hari Ini)',
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
        $this->forge->addKey('shift_id');
        $this->forge->addKey('tanggal');
        $this->forge->addForeignKey('shift_id', 'm_shift', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('checklist');
    }

    public function down()
    {
        $this->forge->dropTable('checklist');
    }
}
