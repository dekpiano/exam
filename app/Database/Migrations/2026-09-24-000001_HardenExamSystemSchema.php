<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class HardenExamSystemSchema extends Migration
{
    public function up()
    {
        $db = $this->db;

        if (!$db->query("SHOW TABLES LIKE 'exams'")->getNumRows() > 0) {
            $db->query("CREATE TABLE exams (
                id VARCHAR(36) PRIMARY KEY,
                subject_name VARCHAR(255) NOT NULL,
                subject_code VARCHAR(100) NOT NULL,
                learning_area VARCHAR(255) NULL,
                teacher_name VARCHAR(255) NULL,
                teacher_email VARCHAR(255) NULL,
                academic_year VARCHAR(50) NULL,
                semester VARCHAR(50) NULL,
                exam_type VARCHAR(255) NOT NULL,
                exam_status VARCHAR(30) NOT NULL DEFAULT 'Waiting',
                started_at DATETIME NULL,
                max_attempts INT NOT NULL DEFAULT 1,
                passing_percentage INT NOT NULL DEFAULT 50,
                time_limit_choice INT NOT NULL DEFAULT 60,
                time_limit_writing INT NOT NULL DEFAULT 300,
                exam_duration INT NOT NULL DEFAULT 0,
                join_policy VARCHAR(100) NOT NULL DEFAULT 'anytime',
                anti_cheating TINYINT NOT NULL DEFAULT 1,
                max_strikes INT NOT NULL DEFAULT 3,
                num_questions INT NOT NULL DEFAULT 20,
                exam_round VARCHAR(100) NOT NULL DEFAULT '1',
                exam_mode VARCHAR(50) NOT NULL DEFAULT 'classic',
                created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_exams_teacher_email (teacher_email),
                INDEX idx_exams_status (exam_status)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        } else {
            $this->addColumnIfMissing('exams', 'exam_round', "VARCHAR(100) DEFAULT '1'");
            $this->addColumnIfMissing('exams', 'exam_duration', "INT DEFAULT 0");
            $this->addColumnIfMissing('exams', 'started_at', "DATETIME NULL");
            $this->addColumnIfMissing('exams', 'join_policy', "VARCHAR(100) DEFAULT 'anytime'");
            $this->addColumnIfMissing('exams', 'anti_cheating', "TINYINT DEFAULT 1");
            $this->addColumnIfMissing('exams', 'max_strikes', "INT DEFAULT 3");
            $this->addColumnIfMissing('exams', 'exam_mode', "VARCHAR(50) DEFAULT 'classic'");
        }

        $this->addColumnIfMissing('questions', 'exam_id', "VARCHAR(36) NULL");
        $this->addColumnIfMissing('questions', 'points', "FLOAT NOT NULL DEFAULT 1");
        $this->addColumnIfMissing('students', 'exam_id', "VARCHAR(36) NULL");
        $this->addColumnIfMissing('students', 'student_code', "VARCHAR(50) DEFAULT ''");
        $this->addColumnIfMissing('students', 'lobby_score', "FLOAT DEFAULT 0");
        $this->addColumnIfMissing('exam_results', 'exam_id', "VARCHAR(36) NULL");
        $this->addColumnIfMissing('exam_results', 'student_code', "VARCHAR(50) DEFAULT ''");
        $this->addColumnIfMissing('exam_results', 'exam_round', "VARCHAR(100) DEFAULT '1'");
        $this->addColumnIfMissing('exam_results', 'score', "FLOAT DEFAULT 0");
        $this->addColumnIfMissing('exam_results', 'total_questions', "FLOAT DEFAULT 0");
        $this->addColumnIfMissing('error_logs', 'exam_id', "VARCHAR(36) NULL");

        if (!$db->query("SHOW TABLES LIKE 'exam_attempts'")->getNumRows() > 0) {
            $db->query("CREATE TABLE exam_attempts (
                id CHAR(36) PRIMARY KEY,
                exam_id VARCHAR(36) NOT NULL,
                student_email VARCHAR(255) NOT NULL,
                exam_round VARCHAR(100) NOT NULL DEFAULT '1',
                attempt_number INT NOT NULL DEFAULT 1,
                status VARCHAR(20) NOT NULL DEFAULT 'in_progress',
                questions_json LONGTEXT NOT NULL,
                started_at DATETIME NOT NULL,
                submitted_at DATETIME NULL,
                updated_at DATETIME NULL,
                INDEX idx_attempt_exam_student_status (exam_id, student_email, status)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        } else {
            $this->addColumnIfMissing('exam_attempts', 'attempt_number', "INT DEFAULT 1");
        }

        if ($db->query("SHOW TABLES LIKE 'students'")->getNumRows() > 0) {
            try {
                $indexes = $db->query("SHOW INDEX FROM students WHERE Key_name = 'email' AND Non_unique = 0")->getResultArray();
                if (!empty($indexes)) {
                    $db->query("ALTER TABLE students DROP INDEX email");
                }
            } catch (\Throwable $e) {
            }
            try { $db->query("CREATE INDEX idx_students_exam_email ON students (exam_id, email)"); } catch (\Throwable $e) {}
        }
    }

    private function addColumnIfMissing(string $table, string $column, string $definition): void
    {
        if (!$this->db->query("SHOW COLUMNS FROM " . $table . " LIKE '" . $column . "'")->getNumRows() > 0) {
            $sql = "ALTER TABLE " . $table . " ADD COLUMN " . $column . " " . $definition;
            $this->db->query($sql);
        }
    }

    public function down()
    {
        // Deliberately non-destructive.
    }
}
