-- Database Schema for Online Exam System with Column Comments in Thai

CREATE TABLE IF NOT EXISTS `settings` (
  `key` VARCHAR(100) PRIMARY KEY COMMENT 'รหัสการตั้งค่า เช่น ชื่อเว็บไซต์ สถานะการสอบ',
  `value` TEXT NOT NULL COMMENT 'ค่ารายละเอียดของการตั้งค่า'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางเก็บข้อมูลการตั้งค่าระบบสอบ';

CREATE TABLE IF NOT EXISTS `questions` (
  `id` VARCHAR(36) PRIMARY KEY COMMENT 'รหัสประจำตัวข้อสอบ',
  `question_text` TEXT NOT NULL COMMENT 'โจทย์คำถาม',
  `option_a` VARCHAR(255) NULL COMMENT 'ตัวเลือกข้อ ก',
  `option_b` VARCHAR(255) NULL COMMENT 'ตัวเลือกข้อ ข',
  `option_c` VARCHAR(255) NULL COMMENT 'ตัวเลือกข้อ ค',
  `option_d` VARCHAR(255) NULL COMMENT 'ตัวเลือกข้อ ง',
  `correct_answer` TEXT NOT NULL COMMENT 'เฉลยคำตอบที่ถูกต้องหรือแนวทางการตอบข้อเขียน',
  `type` ENUM('choice', 'writing') DEFAULT 'choice' COMMENT 'ประเภทข้อสอบ ปรนัยเลือกตอบ หรือ อัตนัยเขียนตอบ',
  `points` INT DEFAULT 1 COMMENT 'คะแนนเต็มประจำข้อนี้',
  `image_url` VARCHAR(500) NULL COMMENT 'ลิงก์รูปภาพประกอบโจทย์ข้อสอบ',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'เวลาที่สร้างข้อสอบนี้'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางคลังเก็บข้อสอบทั้งหมด';

CREATE TABLE IF NOT EXISTS `students` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสลำดับรันอัตโนมัติ',
  `name` VARCHAR(255) NOT NULL COMMENT 'ชื่อและนามสกุลของนักเรียน',
  `student_number` VARCHAR(50) NOT NULL COMMENT 'เลขประจำตัวหรือเลขที่ของนักเรียน',
  `room` VARCHAR(50) NOT NULL COMMENT 'ชั้นเรียนหรือห้องเรียน เช่น ม.4/1',
  `email` VARCHAR(255) UNIQUE NOT NULL COMMENT 'ที่อยู่อีเมลล็อกอินเข้าสอบ',
  `pos_x` INT DEFAULT 100 COMMENT 'พิกัดแนวนอนตัวละครในห้องพักคอย',
  `pos_y` INT DEFAULT 100 COMMENT 'พิกัดแนวตั้งตัวละครในห้องพักคอย',
  `last_active` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'วันเวลาที่ติดต่อระบบล่าสุด'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางเก็บข้อมูลนักเรียนและพิกัดในห้องพักคอย';

CREATE TABLE IF NOT EXISTS `exam_results` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสลำดับรันอัตโนมัติ',
  `email` VARCHAR(255) NOT NULL COMMENT 'อีเมลของนักเรียนผู้เข้าสอบ',
  `name` VARCHAR(255) NOT NULL COMMENT 'ชื่อและนามสกุลผู้เข้าสอบ',
  `student_number` VARCHAR(50) NOT NULL COMMENT 'เลขประจำตัวหรือเลขที่ของผู้เข้าสอบ',
  `room` VARCHAR(50) NOT NULL COMMENT 'ชั้นเรียนหรือห้องเรียนของผู้เข้าสอบ',
  `exam_type` VARCHAR(255) NOT NULL COMMENT 'ประเภทของการสอบ เช่น กลางภาค ปลายภาค',
  `academic_year` VARCHAR(50) NULL COMMENT 'ปีการศึกษา',
  `semester` VARCHAR(50) NULL COMMENT 'ภาคเรียน/เทอม',
  `score` INT DEFAULT 0 COMMENT 'คะแนนรวมที่ทำได้จริง',
  `total_questions` INT DEFAULT 0 COMMENT 'คะแนนเต็มรวมทั้งหมดของข้อสอบ',
  `total_time_spent` INT DEFAULT 0 COMMENT 'จำนวนเวลาทั้งหมดที่ใช้ทำข้อสอบเป็นวินาที',
  `attempt_number` INT DEFAULT 1 COMMENT 'รอบที่เข้าสอบของนักเรียนคนนี้',
  `cheating_flag` VARCHAR(50) DEFAULT 'NO' COMMENT 'สถานะการตรวจพบพฤติกรรมทุจริต มีประวัติ หรือ ปกติ',
  `cheating_count` INT DEFAULT 0 COMMENT 'จำนวนครั้งที่ตรวจพบการสลับหน้าจอออกไปที่อื่น',
  `cheating_reason` VARCHAR(255) NULL COMMENT 'สาเหตุรายละเอียดพฤติกรรมเสี่ยงทุจริต',
  `answers_json` LONGTEXT NULL COMMENT 'ประวัติบันทึกการตอบข้อสอบรายข้อรูปแบบข้อความโครงสร้างเจสัน',
  `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'วันเวลาที่ส่งกระดาษคำตอบเข้าระบบ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางเก็บประวัติและผลคะแนนการสอบ';

CREATE TABLE IF NOT EXISTS `error_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'รหัสลำดับรันอัตโนมัติ',
  `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'วันเวลาประทับที่พบเหตุการณ์',
  `error_type` VARCHAR(100) NOT NULL COMMENT 'ประเภทข้อผิดพลาดหรือกิจกรรมที่น่าสงสัย',
  `error_message` TEXT NULL COMMENT 'รายละเอียดบันทึกเหตุการณ์การผิดกฎหรือข้อผิดพลาด',
  `student_email` VARCHAR(255) NULL COMMENT 'อีเมลของนักเรียนที่เกี่ยวข้องกับเหตุการณ์',
  `user_agent` TEXT NULL COMMENT 'ข้อมูลซอฟต์แวร์เบราว์เซอร์และระบบปฏิบัติการของผู้เข้าสอบ',
  `screen_resolution` VARCHAR(50) NULL COMMENT 'ความละเอียดจอภาพของผู้เข้าสอบ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ตารางบันทึกกิจกรรมและข้อผิดพลาดในการสอบ';

-- Insert default settings
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
