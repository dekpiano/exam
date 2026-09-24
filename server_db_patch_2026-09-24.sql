-- Online Exam System
-- Server DB patch: 2026-09-24
-- Safe for an existing installation. Existing columns/tables are not overwritten.
-- Tested against the schema currently used by the project.

SET NAMES utf8mb4;

-- =========================================================
-- 1) exams
-- =========================================================
ALTER TABLE `exams`
    ADD COLUMN IF NOT EXISTS `exam_round` VARCHAR(100) NOT NULL DEFAULT '1',
    ADD COLUMN IF NOT EXISTS `exam_duration` INT NOT NULL DEFAULT 0,
    ADD COLUMN IF NOT EXISTS `started_at` DATETIME NULL,
    ADD COLUMN IF NOT EXISTS `join_policy` VARCHAR(100) NOT NULL DEFAULT 'anytime',
    ADD COLUMN IF NOT EXISTS `anti_cheating` TINYINT NOT NULL DEFAULT 1,
    ADD COLUMN IF NOT EXISTS `max_strikes` INT NOT NULL DEFAULT 3,
    ADD COLUMN IF NOT EXISTS `exam_mode` VARCHAR(50) NOT NULL DEFAULT 'classic';

-- =========================================================
-- 2) questions
-- =========================================================
ALTER TABLE `questions`
    ADD COLUMN IF NOT EXISTS `exam_id` VARCHAR(36) NULL,
    ADD COLUMN IF NOT EXISTS `points` FLOAT NOT NULL DEFAULT 1;

-- =========================================================
-- 3) students
-- =========================================================
ALTER TABLE `students`
    ADD COLUMN IF NOT EXISTS `exam_id` VARCHAR(36) NULL,
    ADD COLUMN IF NOT EXISTS `student_code` VARCHAR(50) DEFAULT '',
    ADD COLUMN IF NOT EXISTS `lobby_score` FLOAT DEFAULT 0;

-- =========================================================
-- 4) exam_results
-- =========================================================
ALTER TABLE `exam_results`
    ADD COLUMN IF NOT EXISTS `exam_id` VARCHAR(36) NULL,
    ADD COLUMN IF NOT EXISTS `student_code` VARCHAR(50) DEFAULT '',
    ADD COLUMN IF NOT EXISTS `exam_round` VARCHAR(100) DEFAULT '1',
    ADD COLUMN IF NOT EXISTS `score` FLOAT DEFAULT 0,
    ADD COLUMN IF NOT EXISTS `total_questions` FLOAT DEFAULT 0;

-- =========================================================
-- 5) error_logs
-- =========================================================
ALTER TABLE `error_logs`
    ADD COLUMN IF NOT EXISTS `exam_id` VARCHAR(36) NULL;

-- =========================================================
-- 6) exam_attempts
--    This table is required for multiple-attempt / round tracking.
-- =========================================================
CREATE TABLE IF NOT EXISTS `exam_attempts` (
    `id` CHAR(36) NOT NULL,
    `exam_id` VARCHAR(36) NOT NULL,
    `student_email` VARCHAR(255) NOT NULL,
    `exam_round` VARCHAR(100) NOT NULL DEFAULT '1',
    `attempt_number` INT NOT NULL DEFAULT 1,
    `status` VARCHAR(20) NOT NULL DEFAULT 'in_progress',
    `questions_json` LONGTEXT NOT NULL,
    `started_at` DATETIME NOT NULL,
    `submitted_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    KEY `idx_attempt_exam_student_status` (`exam_id`, `student_email`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- If exam_attempts already existed before this patch, ensure attempt_number exists.
ALTER TABLE `exam_attempts`
    ADD COLUMN IF NOT EXISTS `attempt_number` INT NOT NULL DEFAULT 1;

-- =========================================================
-- 7) Recommended indexes
--    Run these only if the indexes do not already exist.
--    The existing project migration already creates the main
--    students exam/email index, so duplicate-index errors are
--    intentionally avoided here.
-- =========================================================

-- Optional manual checks:
-- SHOW COLUMNS FROM exams;
-- SHOW COLUMNS FROM questions;
-- SHOW COLUMNS FROM students;
-- SHOW COLUMNS FROM exam_results;
-- SHOW COLUMNS FROM error_logs;
-- SHOW COLUMNS FROM exam_attempts;
