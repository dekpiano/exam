<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateExamAttempts extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'CHAR', 'constraint' => 36],
            'exam_id' => ['type' => 'VARCHAR', 'constraint' => 36],
            'student_email' => ['type' => 'VARCHAR', 'constraint' => 255],
            'exam_round' => ['type' => 'VARCHAR', 'constraint' => 100, 'default' => '1'],
            'attempt_number' => ['type' => 'INT', 'default' => 1],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'in_progress'],
            'questions_json' => ['type' => 'LONGTEXT'],
            'started_at' => ['type' => 'DATETIME'],
            'submitted_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['exam_id', 'student_email', 'status']);
        $this->forge->createTable('exam_attempts', true);
    }

    public function down()
    {
        $this->forge->dropTable('exam_attempts', true);
    }
}
