# Database model/evolution
- Current models expect tables: exams, questions, students, exam_results, error_logs, settings, plus exam_attempts.
- schema.sql is an older/base schema and does not fully describe the later exams/exam_id fields used by current models.
- AdminController::checkDatabaseSchema() runs on every AdminController construction and conditionally adds later columns: exams.exam_round/exam_duration/started_at/join_policy/anti_cheating/max_strikes/exam_mode; exam_results.exam_round/student_code; students.student_code; error_logs.exam_id; and converts questions.points + exam_results.score/total_questions to FLOAT.
- Migration 2026-07-19-000001_CreateExamAttempts.php creates exam_attempts with id, exam_id, student_email, exam_round, status, questions_json, started_at, submitted_at, updated_at.
- When making schema changes, reconcile schema.sql, migrations, models and runtime schema-repair logic rather than trusting schema.sql alone.
