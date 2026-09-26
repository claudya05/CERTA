<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Bukti foto pengecekan (JPG/PNG), 1 checklist bisa punya banyak foto.
 */
class CreateChecklistPhotoTable extends Migration
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
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'comment'    => 'path relatif di dalam writable/uploads/bukti_foto/',
            ],
            'original_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('checklist_id');
        $this->forge->addForeignKey('checklist_id', 'checklist', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('checklist_photo');
    }

    public function down()
    {
        $this->forge->dropTable('checklist_photo');
    }
}
