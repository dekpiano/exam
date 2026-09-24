-- Canonical database schema for the Online Exam System.
-- Runtime migrations remain backward-compatible with older installations.

CREATE TABLE IF NOT EXISTS `settings` (
  `key` VARCHAR(100) PRIMARY KEY,
  `value` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `exams` (
  `id` VARCHAR(36) PRIMARY KEY,
  `subject_name` VARCHAR(255) NOT NULL,
  `subject_code` VARCHAR(100) NOT NULL,
  `learning_area` VARCHAR(255) NULL,
  `teacher_name` VARCHAR(255) NULL,
  `teacher_email` VARCHAR(255) NULL,
  `academic_year` VARCHAR(50) NULL,
  `semester` VARCHAR(50) NULL,
  `exam_type` VARCHAR(255) NOT NULL,
  `exam_status` VARCHAR(30) NOT NULL DEFAULT 'Waiting',
  `started_at` DATETIME NULL,
  `max_attempts` INT NOT NULL DEFAULT 1,
  `passing_percentage` INT NOT NULL DEFAULT 50,
  `time_limit_choice` INT NOT NULL DEFAULT 60,
  `time_limit_writing` INT NOT NULL DEFAULT 300,
  `exam_duration` INT NOT NULL DEFAULT 0,
  `join_policy` VARCHAR(100) NOT NULL DEFAULT 'anytime',
  `anti_cheating` TINYINT NOT NULL DEFAULT 1,
  `max_strikes` INT NOT NULL DEFAULT 3,
  `num_questions` INT NOT NULL DEFAULT 20,
  `exam_round` VARCHAR(100) NOT NULL DEFAULT '1',
  `exam_mode` VARCHAR(50) NOT NULL DEFAULT 'classic',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_exams_teacher_email` (`teacher_email`),
  INDEX `idx_exams_status` (`exam_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `questions` (
  `id` VARCHAR(36) PRIMARY KEY,
  `exam_id` VARCHAR(36) NOT NULL,
  `question_text` TEXT NOT NULL,
  `option_a` VARCHAR(255) NULL,
  `option_b` VARCHAR(255) NULL,
  `option_c` VARCHAR(255) NULL,
  `option_d` VARCHAR(255) NULL,
  `correct_answer` TEXT NOT NULL,
  `type` ENUM('choice','writing') DEFAULT 'choice',
  `points` FLOAT NOT NULL DEFAULT 1,
  `image_url` VARCHAR(500) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_questions_exam_id` (`exam_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `students` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `exam_id` VARCHAR(36) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `student_code` VARCHAR(50) NOT NULL,
  `student_number` VARCHAR(50) NOT NULL,
  `room` VARCHAR(50) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `pos_x` INT DEFAULT 100,
  `pos_y` INT DEFAULT 100,
  `last_active` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `lobby_score` FLOAT DEFAULT 0,
  INDEX `idx_students_exam_email` (`exam_id`,`email`),
  INDEX `idx_students_exam_code` (`exam_id`,`student_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `exam_attempts` (
  `id` CHAR(36) PRIMARY KEY,
  `exam_id` VARCHAR(36) NOT NULL,
  `student_email` VARCHAR(255) NOT NULL,
  `exam_round` VARCHAR(100) NOT NULL DEFAULT '1',
  `attempt_number` INT NOT NULL DEFAULT 1,
  `status` VARCHAR(20) NOT NULL DEFAULT 'in_progress',
  `questions_json` LONGTEXT NOT NULL,
  `started_at` DATETIME NOT NULL,
  `submitted_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  INDEX `idx_attempt_exam_student_status` (`exam_id`,`student_email`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `exam_results` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `exam_id` VARCHAR(36) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `student_code` VARCHAR(50) NULL,
  `student_number` VARCHAR(50) NOT NULL,
  `room` VARCHAR(50) NOT NULL,
  `exam_type` VARCHAR(255) NOT NULL,
  `academic_year` VARCHAR(50) NULL,
  `semester` VARCHAR(50) NULL,
  `score` FLOAT DEFAULT 0,
  `total_questions` FLOAT DEFAULT 0,
  `total_time_spent` INT DEFAULT 0,
  `attempt_number` INT DEFAULT 1,
  `exam_round` VARCHAR(100) DEFAULT '1',
  `cheating_flag` VARCHAR(50) DEFAULT 'NO',
  `cheating_count` INT DEFAULT 0,
  `cheating_reason` VARCHAR(255) NULL,
  `answers_json` LONGTEXT NULL,
  `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_results_exam_email` (`exam_id`,`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `error_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `exam_id` VARCHAR(36) NULL,
  `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `error_type` VARCHAR(100) NOT NULL,
  `error_message` TEXT NULL,
  `student_email` VARCHAR(255) NULL,
  `user_agent` TEXT NULL,
  `screen_resolution` VARCHAR(50) NULL,
  INDEX `idx_logs_exam_student` (`exam_id`,`student_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`key`, `value`) VALUES
('Website Name', 'ระบบข้อสอบออนไลน์'),
('Teacher Name', 'ผู้ดูแลระบบ'),
('Exam Type', 'ข้อสอบกลางภาค'),
('Logo URL', ''),
('Question Time Limit (seconds)', '60'),
('Writing Question Time Limit (seconds)', '180'),
('Number of Questions', '10'),
('Exam Status', 'Waiting'),
('Max Attempts', '1'),
('Passing Percentage', '50'),
('Max Cheating Strikes', '3'),
('Admin Password', 'admin1234'),
('Default Choice Score', '1'),
('Default Writing Score', '5'),
('Gemini API Key', '')
ON DUPLICATE KEY UPDATE `value`=`value`;
