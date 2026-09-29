<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ScopeStudentIdentityByExam extends Migration
{
    public function up()
    {
        if ($this->db->query("SHOW TABLES LIKE 'students'")->getNumRows() === 0) {
            return;
        }

        $indexes = $this->db->query('SHOW INDEX FROM students')->getResultArray();
        $grouped = [];
        foreach ($indexes as $index) {
            $name = (string) $index['Key_name'];
            $grouped[$name]['unique'] = (int) $index['Non_unique'] === 0;
            $grouped[$name]['columns'][(int) $index['Seq_in_index']] = strtolower((string) $index['Column_name']);
        }

        // Student email and student code identify a student only within one exam.
        // Remove legacy global unique constraints regardless of their index names.
        foreach ($grouped as $name => $index) {
            $columns = $index['columns'] ?? [];
            ksort($columns);
            $columns = array_values($columns);
            $hasExamScope = in_array('exam_id', $columns, true);
            $hasStudentIdentity = in_array('email', $columns, true) || in_array('student_code', $columns, true);

            if (!empty($index['unique']) && !$hasExamScope && $hasStudentIdentity && strtoupper($name) !== 'PRIMARY') {
                $this->db->query('ALTER TABLE students DROP INDEX ' . $this->db->escapeIdentifiers($name));
            }
        }

        $indexes = $this->db->query('SHOW INDEX FROM students')->getResultArray();
        $hasExamEmailIndex = false;
        $hasExamCodeIndex = false;
        foreach ($indexes as $index) {
            if (strtolower((string) $index['Column_name']) !== 'exam_id' || (int) $index['Seq_in_index'] !== 1) {
                continue;
            }

            $indexRows = array_values(array_filter(
                $indexes,
                static fn(array $row): bool => $row['Key_name'] === $index['Key_name']
            ));
            usort($indexRows, static fn(array $a, array $b): int => (int) $a['Seq_in_index'] <=> (int) $b['Seq_in_index']);
            $columns = array_map(static fn(array $row): string => strtolower((string) $row['Column_name']), $indexRows);

            if (($columns[1] ?? '') === 'email') {
                $hasExamEmailIndex = true;
            }
            if (($columns[1] ?? '') === 'student_code') {
                $hasExamCodeIndex = true;
            }
        }

        if (!$hasExamEmailIndex) {
            $this->db->query('CREATE INDEX idx_students_exam_email ON students (exam_id, email)');
        }
        if (!$hasExamCodeIndex) {
            $this->db->query('CREATE INDEX idx_students_exam_code ON students (exam_id, student_code)');
        }
    }

    public function down()
    {
        // Deliberately non-destructive: removed legacy constraints cannot be restored safely.
    }
}
