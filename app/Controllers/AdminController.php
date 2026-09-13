<?php

namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\QuestionModel;
use App\Models\StudentModel;
use App\Models\ExamResultModel;
use App\Models\ErrorLogModel;
use CodeIgniter\API\ResponseTrait;

class AdminController extends BaseController
{
    use ResponseTrait;

    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->checkDatabaseSchema();
    }

    private function checkDatabaseSchema()
    {
        try {
            $db = \Config\Database::connect();
            if (!$db->fieldExists('exam_round', 'exams')) {
                $db->query("ALTER TABLE exams ADD COLUMN exam_round VARCHAR(100) DEFAULT '1' COMMENT 'รอบการสอบปัจจุบัน' AFTER num_questions");
            }
            if (!$db->fieldExists('exam_round', 'exam_results')) {
                $db->query("ALTER TABLE exam_results ADD COLUMN exam_round VARCHAR(100) DEFAULT '1' COMMENT 'รอบการสอบที่บันทึกผล' AFTER attempt_number");
            }
            if (!$db->fieldExists('student_code', 'students')) {
                $db->query("ALTER TABLE students ADD COLUMN student_code VARCHAR(50) DEFAULT '' COMMENT 'เลขประจำตัวนักเรียน' AFTER name");
            }
            if (!$db->fieldExists('student_code', 'exam_results')) {
                $db->query("ALTER TABLE exam_results ADD COLUMN student_code VARCHAR(50) DEFAULT '' COMMENT 'เลขประจำตัวนักเรียน' AFTER name");
            }
            if (!$db->fieldExists('exam_duration', 'exams')) {
                $db->query("ALTER TABLE exams ADD COLUMN exam_duration INT DEFAULT 0 COMMENT 'เวลาทำข้อสอบทั้งหมด (นาที) 0 = ไม่จำกัด' AFTER time_limit_writing");
            }
            if (!$db->fieldExists('started_at', 'exams')) {
                $db->query("ALTER TABLE exams ADD COLUMN started_at DATETIME NULL COMMENT 'เวลาที่กดเริ่มการสอบ' AFTER exam_status");
            }
            if (!$db->fieldExists('join_policy', 'exams')) {
                $db->query("ALTER TABLE exams ADD COLUMN join_policy VARCHAR(100) DEFAULT 'anytime' COMMENT 'นโยบายการเข้าร่วมสอบ: anytime = เริ่มตอนไหนก็ได้, lobby_first = ต้องรอในห้องพักคอยก่อนเริ่ม' AFTER exam_duration");
            }
            if (!$db->fieldExists('anti_cheating', 'exams')) {
                $db->query("ALTER TABLE exams ADD COLUMN anti_cheating TINYINT DEFAULT 1 COMMENT 'ระบบสลับหน้าจอ/จับทุจริต: 1 = เปิดใช้งาน, 0 = ปิดใช้งาน' AFTER join_policy");
            }
            if (!$db->fieldExists('max_strikes', 'exams')) {
                $db->query("ALTER TABLE exams ADD COLUMN max_strikes INT DEFAULT 3 COMMENT 'จำนวนครั้งสลับหน้าจอสูงสุดที่อนุญาตให้ทำได้ก่อนระงับสอบ' AFTER anti_cheating");
            }
            if (!$db->fieldExists('exam_id', 'error_logs')) {
                $db->query("ALTER TABLE error_logs ADD COLUMN exam_id VARCHAR(36) NULL COMMENT 'รหัสการสอบที่เกิดเหตุการณ์' AFTER id");
            }
        } catch (\Exception $e) {
            // Log or ignore schema check error
        }
    }

    public function index()
    {
        if ($this->session->get('admin_logged_in')) {
            return redirect()->to('/teacher/dashboard');
        }

        $settingModel = new SettingModel();
        $settings = $settingModel->getSettings();

        $data = [
            'websiteName' => $settings['Website Name'] ?? 'ระบบข้อสอบออนไลน์',
            'logoUrl'     => $settings['Logo URL'] ?? '',
            'googleClientId' => $settings['Google Client ID'] ?? '',
        ];

        return view('admin/login', $data);
    }

    public function dashboard()
    {
        if (!$this->session->get('admin_logged_in')) {
            return redirect()->to('/teacher');
        }

        $settingModel = new SettingModel();
        $settings = $settingModel->getSettings();

        $data = [
            'websiteName' => $settings['Website Name'] ?? 'ระบบข้อสอบออนไลน์',
            'logoUrl'     => $settings['Logo URL'] ?? '',
            'teacherName' => $this->session->get('teacher_name') ?? ($settings['Teacher Name'] ?? 'ผู้ดูแลระบบ'),
            'teacherEmail' => $this->session->get('teacher_email') ?? '',
            'teacherLearning' => $this->session->get('teacher_learning') ?? '',
            'examType'    => $settings['Exam Type'] ?? 'ข้อสอบกลางภาค',
        ];

        return view('admin/dashboard', $data);
    }

    public function questions()
    {
        if (!$this->session->get('admin_logged_in')) {
            return redirect()->to('/teacher');
        }

        $examId = $this->request->getGet('exam_id');
        if (empty($examId)) {
            return redirect()->to('/teacher/dashboard');
        }

        $settingModel = new SettingModel();
        $settings = $settingModel->getSettings();

        $data = [
            'websiteName' => $settings['Website Name'] ?? 'ระบบข้อสอบออนไลน์',
            'logoUrl'     => $settings['Logo URL'] ?? '',
            'teacherName' => $this->session->get('teacher_name') ?? ($settings['Teacher Name'] ?? 'ผู้ดูแลระบบ'),
            'teacherEmail' => $this->session->get('teacher_email') ?? '',
            'teacherLearning' => $this->session->get('teacher_learning') ?? '',
            'examType'    => $settings['Exam Type'] ?? 'ข้อสอบกลางภาค',
            'activeExamId' => $examId,
            'currentTab'   => 'questions',
            'isInWorkspace' => true,
        ];

        return view('admin/dashboard', $data);
    }

    public function monitor()
    {
        if (!$this->session->get('admin_logged_in')) {
            return redirect()->to('/teacher');
        }

        $examId = $this->request->getGet('exam_id');
        if (empty($examId)) {
            return redirect()->to('/teacher/dashboard');
        }

        $settingModel = new SettingModel();
        $settings = $settingModel->getSettings();

        $data = [
            'websiteName' => $settings['Website Name'] ?? 'ระบบข้อสอบออนไลน์',
            'logoUrl'     => $settings['Logo URL'] ?? '',
            'teacherName' => $this->session->get('teacher_name') ?? ($settings['Teacher Name'] ?? 'ผู้ดูแลระบบ'),
            'teacherEmail' => $this->session->get('teacher_email') ?? '',
            'teacherLearning' => $this->session->get('teacher_learning') ?? '',
            'examType'    => $settings['Exam Type'] ?? 'ข้อสอบกลางภาค',
            'activeExamId' => $examId,
            'currentTab'   => 'monitor',
            'isInWorkspace' => true,
        ];

        return view('admin/dashboard', $data);
    }

    public function results()
    {
        if (!$this->session->get('admin_logged_in')) {
            return redirect()->to('/teacher');
        }

        $examId = $this->request->getGet('exam_id');
        if (empty($examId)) {
            return redirect()->to('/teacher/dashboard');
        }

        $settingModel = new SettingModel();
        $settings = $settingModel->getSettings();

        $data = [
            'websiteName' => $settings['Website Name'] ?? 'ระบบข้อสอบออนไลน์',
            'logoUrl'     => $settings['Logo URL'] ?? '',
            'teacherName' => $this->session->get('teacher_name') ?? ($settings['Teacher Name'] ?? 'ผู้ดูแลระบบ'),
            'teacherEmail' => $this->session->get('teacher_email') ?? '',
            'teacherLearning' => $this->session->get('teacher_learning') ?? '',
            'examType'    => $settings['Exam Type'] ?? 'ข้อสอบกลางภาค',
            'activeExamId' => $examId,
            'currentTab'   => 'results',
            'isInWorkspace' => true,
        ];

        return view('admin/dashboard', $data);
    }

    public function logs()
    {
        if (!$this->session->get('admin_logged_in')) {
            return redirect()->to('/teacher');
        }

        $examId = $this->request->getGet('exam_id') ?: '';
        $settingModel = new SettingModel();
        $settings = $settingModel->getSettings();

        $data = [
            'websiteName' => $settings['Website Name'] ?? 'ระบบข้อสอบออนไลน์',
            'logoUrl'     => $settings['Logo URL'] ?? '',
            'teacherName' => $this->session->get('teacher_name') ?? ($settings['Teacher Name'] ?? 'ผู้ดูแลระบบ'),
            'teacherEmail' => $this->session->get('teacher_email') ?? '',
            'teacherLearning' => $this->session->get('teacher_learning') ?? '',
            'examType'    => $settings['Exam Type'] ?? 'ข้อสอบกลางภาค',
            'activeExamId' => $examId,
            'currentTab'   => 'logs',
            'isInWorkspace' => !empty($examId),
        ];

        return view('admin/dashboard', $data);
    }

    public function settings()
    {
        if (!$this->session->get('admin_logged_in')) {
            return redirect()->to('/teacher');
        }

        $settingModel = new SettingModel();
        $settings = $settingModel->getSettings();

        $data = [
            'websiteName' => $settings['Website Name'] ?? 'ระบบข้อสอบออนไลน์',
            'logoUrl'     => $settings['Logo URL'] ?? '',
            'teacherName' => $this->session->get('teacher_name') ?? ($settings['Teacher Name'] ?? 'ผู้ดูแลระบบ'),
            'teacherEmail' => $this->session->get('teacher_email') ?? '',
            'teacherLearning' => $this->session->get('teacher_learning') ?? '',
            'examType'    => $settings['Exam Type'] ?? 'ข้อสอบกลางภาค',
            'activeExamId' => 'global',
            'currentTab'   => 'settings',
            'isInWorkspace' => true,
        ];

        return view('admin/dashboard', $data);
    }

    public function examSettings()
    {
        if (!$this->session->get('admin_logged_in')) {
            return redirect()->to('/teacher');
        }

        $examId = $this->request->getGet('exam_id');
        if (empty($examId)) {
            return redirect()->to('/teacher/dashboard');
        }

        $settingModel = new SettingModel();
        $settings = $settingModel->getSettings();

        $data = [
            'websiteName' => $settings['Website Name'] ?? 'ระบบข้อสอบออนไลน์',
            'logoUrl'     => $settings['Logo URL'] ?? '',
            'teacherName' => $this->session->get('teacher_name') ?? ($settings['Teacher Name'] ?? 'ผู้ดูแลระบบ'),
            'teacherEmail' => $this->session->get('teacher_email') ?? '',
            'teacherLearning' => $this->session->get('teacher_learning') ?? '',
            'examType'    => $settings['Exam Type'] ?? 'ข้อสอบกลางภาค',
            'activeExamId' => $examId,
            'currentTab'   => 'exam-settings',
            'isInWorkspace' => true,
        ];

        return view('admin/dashboard', $data);
    }

    // --- Admin APIs ---

    public function login()
    {
        $password = $this->request->getPost('password') ?? '';

        $settingModel = new SettingModel();
        $settings = $settingModel->getSettings();
        $storedPassword = $settings['Admin Password'] ?? 'admin1234';

        if (trim($password) === trim($storedPassword)) {
            $this->session->set('admin_logged_in', true);
            $this->session->set('teacher_name', $settings['Teacher Name'] ?? 'ผู้ดูแลระบบ');
            return $this->respond(['success' => true, 'message' => 'เข้าสู่ระบบสำเร็จ']);
        }

        return $this->respond(['success' => false, 'message' => 'รหัสผ่านไม่ถูกต้อง'], 401);
    }

    public function googleLogin()
    {
        $idToken = $this->request->getPost('credential') ?? '';
        if (empty($idToken)) {
            return $this->respond(['success' => false, 'message' => 'ไม่พบข้อมูล Google Token'], 400);
        }

        // Verify ID Token with Google API via cURL
        $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . urlencode($idToken);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return $this->respond(['success' => false, 'message' => 'การตรวจสอบ Google Token ล้มเหลว'], 401);
        }

        $tokenInfo = json_decode($response, true);
        $email = strtolower($tokenInfo['email'] ?? '');
        $emailVerified = $tokenInfo['email_verified'] ?? 'false';

        if (empty($email) || $emailVerified !== 'true') {
            return $this->respond(['success' => false, 'message' => 'อีเมลยังไม่ได้รับการยืนยันจาก Google'], 400);
        }

        // Validate token audience against the configured Google Client ID
        $settingModel = new SettingModel();
        $gsSettings = $settingModel->getSettings();
        $expectedClientId = trim($gsSettings['Google Client ID'] ?? '');

        if ($expectedClientId === '') {
            return $this->respond(['success' => false, 'message' => 'ระบบยังไม่ได้ตั้งค่า Google Client ID กรุณาติดต่อผู้ดูแลระบบ'], 500);
        }

        $aud = $tokenInfo['aud'] ?? '';
        if (!hash_equals($expectedClientId, (string) $aud)) {
            return $this->respond(['success' => false, 'message' => 'Google Token ไม่ถูกต้อง (audience mismatch)'], 401);
        }

        // Reject expired tokens and unexpected issuers
        if ((int) ($tokenInfo['exp'] ?? 0) < time()) {
            return $this->respond(['success' => false, 'message' => 'Google Token หมดอายุแล้ว กรุณาเข้าสู่ระบบใหม่'], 401);
        }

        $iss = $tokenInfo['iss'] ?? '';
        if (!in_array($iss, ['accounts.google.com', 'https://accounts.google.com'], true)) {
            return $this->respond(['success' => false, 'message' => 'ผู้ออกโทเคน (issuer) ไม่ถูกต้อง'], 401);
        }

        if (substr($email, -10) !== '@skj.ac.th') {
            return $this->respond(['success' => false, 'message' => 'อนุญาตเฉพาะอีเมลโรงเรียน (@skj.ac.th) เท่านั้น'], 400);
        }

        // Query cross-database: skjacth_personnel.tb_personnel
        $db = \Config\Database::connect();
        $query = $db->query("SELECT * FROM skjacth_personnel.tb_personnel WHERE LOWER(pers_username) = ? LIMIT 1", [$email]);
        $teacher = $query->getRowArray();

        if (!$teacher) {
            return $this->respond([
                'success' => false, 
                'message' => "ไม่พบข้อมูลบัญชีบุคลากรที่ตรงกับอีเมล: {$email} ในระบบ"
            ], 403);
        }

        // Successful login
        $displayName = $teacher['pers_prefix'] . $teacher['pers_firstname'] . ' ' . $teacher['pers_lastname'];

        // Resolve learning area code (e.g. lear_003) to Thai name from skjacth_skj.tb_learning
        $learningName = '';
        $learCode = $teacher['pers_learning'] ?? '';
        if (!empty($learCode)) {
            $learQuery = $db->query("SELECT lear_namethai FROM skjacth_skj.tb_learning WHERE lear_id = ? LIMIT 1", [$learCode]);
            $learRow = $learQuery->getRowArray();
            if ($learRow) {
                $learningName = trim($learRow['lear_namethai']);
            }
        }

        $this->session->set([
            'admin_logged_in' => true,
            'teacher_name'    => $displayName,
            'teacher_email'   => $email,
            'teacher_img'     => $teacher['pers_img'] ?? '',
            'teacher_learning'=> $learningName,
        ]);

        return $this->respond(['success' => true, 'message' => 'เข้าสู่ระบบบุคลากรสำเร็จ']);
    }

    public function logout()
    {
        $this->session->remove('admin_logged_in');
        $this->session->remove('teacher_name');
        $this->session->remove('teacher_email');
        $this->session->remove('teacher_img');
        return $this->respond(['success' => true, 'message' => 'ออกจากระบบสำเร็จ']);
    }

    private function checkAuth()
    {
        if (!$this->session->get('admin_logged_in')) {
            throw new \Exception('Unauthorized', 401);
        }
    }

    /**
     * Delete an uploaded image from both the new (public/uploads) and
     * legacy (root uploads) storage locations.
     */
    private static function deleteUploadedImage(string $url): bool
    {
        $name = basename($url);
        if ($name === '' || $name === '.' || $name === '..') {
            return false;
        }

        $deleted = false;
        foreach ([ROOTPATH . 'public/uploads/', ROOTPATH . 'uploads/'] as $dir) {
            $path = $dir . $name;
            if (is_file($path)) {
                @unlink($path);
                $deleted = !is_file($path);
            }
        }
        return $deleted;
    }

    public function getSettings()
    {
        try {
            $this->checkAuth();
            $settingModel = new SettingModel();
            return $this->respond(['success' => true, 'settings' => $settingModel->getSettings()]);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function saveSettings()
    {
        try {
            $this->checkAuth();
            $settings = $this->request->getPost('settings');
            if (is_string($settings)) {
                $settings = json_decode($settings, true);
            }

            if (empty($settings)) {
                return $this->respond(['success' => false, 'message' => 'ข้อมูลการตั้งค่าว่างเปล่า'], 400);
            }

            $settingModel = new SettingModel();
            $settingModel->updateSettings($settings);

            // If exam status is set to Waiting, clear the student lobby
            if (isset($settings['Exam Status']) && $settings['Exam Status'] === 'Waiting') {
                $studentModel = new StudentModel();
                $studentModel->truncate(); // Clear lobby list
            }

            return $this->respond(['success' => true, 'message' => 'บันทึกการตั้งค่าเรียบร้อยแล้ว']);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function getExams()
    {
        try {
            $this->checkAuth();
            $examModel = new \App\Models\ExamModel();
            
            $isSuperAdmin = empty($this->session->get('teacher_email'));
            $teacherEmail = $this->session->get('teacher_email');

            $db = \Config\Database::connect();
            
            $sql = "SELECT e.*, 
                    (SELECT COUNT(*) FROM questions WHERE exam_id = e.id COLLATE utf8mb4_general_ci AND type = 'choice') as choice_count,
                    (SELECT COUNT(*) FROM questions WHERE exam_id = e.id COLLATE utf8mb4_general_ci AND type = 'writing') as writing_count
                    FROM exams e";

            if (!$isSuperAdmin && !empty($teacherEmail)) {
                $query = $db->query($sql . " WHERE e.teacher_email = ? OR e.teacher_email IS NULL ORDER BY e.subject_code ASC", [$teacherEmail]);
                $exams = $query->getResultArray();
            } else {
                $query = $db->query($sql . " ORDER BY e.subject_code ASC");
                $exams = $query->getResultArray();
            }
            return $this->respond(['success' => true, 'exams' => $exams]);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveExam()
    {
        try {
            $this->checkAuth();
            $id = $this->request->getPost('id') ?: null;
            $subjectCode = $this->request->getPost('subject_code') ?? '';
            $subjectName = $this->request->getPost('subject_name') ?? '';
            $academicYear = $this->request->getPost('academic_year') ?? '';
            $semester = $this->request->getPost('semester') ?? '';
            $learningArea = $this->request->getPost('learning_area') ?? 'ทั่วไป';
            $teacherName = $this->request->getPost('teacher_name') ?? '';
            $examType = $this->request->getPost('exam_type') ?? 'สอบกลางภาค';
            $examStatus = $this->request->getPost('exam_status') ?? 'Waiting';
            $maxAttempts = (int)($this->request->getPost('max_attempts') ?? 1);
            $passingPercentage = (int)($this->request->getPost('passing_percentage') ?? 50);
            $timeLimitChoice = (int)($this->request->getPost('time_limit_choice') ?? 60);
            $timeLimitWriting = (int)($this->request->getPost('time_limit_writing') ?? 300);
            $numQuestions = (int)($this->request->getPost('num_questions') ?? 20);
            $examRound = $this->request->getPost('exam_round') ?: '1';

            $examDuration = (int)($this->request->getPost('exam_duration') ?? 0);

            if (empty($subjectCode) || empty($subjectName)) {
                return $this->respond(['success' => false, 'message' => 'กรุณากรอกรหัสวิชาและชื่อวิชา'], 400);
            }

            $examModel = new \App\Models\ExamModel();

            $isSuperAdmin = empty($this->session->get('teacher_email'));
            $teacherEmail = $this->session->get('teacher_email');

            if (!$isSuperAdmin && !empty($teacherEmail)) {
                // If it is an edit, check that the exam belongs to this teacher
                if (!empty($id)) {
                    $existingExam = $examModel->find($id);
                    if ($existingExam && !empty($existingExam['teacher_email']) && $existingExam['teacher_email'] !== $teacherEmail) {
                        return $this->respond(['success' => false, 'message' => 'คุณไม่มีสิทธิ์แก้ไขรายวิชานี้'], 403);
                    }
                }
                $teacherEmailToSave = $teacherEmail;
                $teacherNameToSave = $this->session->get('teacher_name') ?: $teacherName;
            } else {
                $teacherEmailToSave = $this->request->getPost('teacher_email') ?: null;
                $teacherNameToSave = $teacherName;
            }

            $antiCheating = $this->request->getPost('anti_cheating') !== null ? (int)$this->request->getPost('anti_cheating') : 1;
            $maxStrikes = $this->request->getPost('max_strikes') !== null ? (int)$this->request->getPost('max_strikes') : 3;

            if (empty($id)) {
                $id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                    mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                    mt_rand(0, 0xffff),
                    mt_rand(0, 0x0fff) | 0x4000,
                    mt_rand(0, 0x3fff) | 0x8000,
                    mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
                );
            }

            $data = [
                'id' => $id,
                'subject_code' => $subjectCode,
                'subject_name' => $subjectName,
                'academic_year' => $academicYear,
                'semester' => $semester,
                'learning_area' => $learningArea,
                'teacher_name' => $teacherNameToSave,
                'teacher_email' => $teacherEmailToSave,
                'exam_type' => $examType,
                'exam_status' => $examStatus,
                'max_attempts' => $maxAttempts,
                'passing_percentage' => $passingPercentage,
                'time_limit_choice' => $timeLimitChoice,
                'time_limit_writing' => $timeLimitWriting,
                'exam_duration' => $examDuration,
                'join_policy' => $this->request->getPost('join_policy') ?: 'anytime',
                'anti_cheating' => $antiCheating,
                'max_strikes' => $maxStrikes,
                'num_questions' => $numQuestions,
                'exam_round' => $examRound
            ];

            if ($examStatus === 'Started') {
                $existing = $examModel->find($id);
                if (!$existing || empty($existing['started_at'])) {
                    $data['started_at'] = date('Y-m-d H:i:s');
                } else {
                    $data['started_at'] = $existing['started_at'];
                }
            } else if ($examStatus === 'Waiting') {
                $data['started_at'] = null;
            }

            // If exam status is set to Waiting, clear the student lobby for this exam
            if ($examStatus === 'Waiting') {
                $studentModel = new StudentModel();
                $studentModel->where('exam_id', $id)->delete();
            }

            $examModel->save($data);
            return $this->respond(['success' => true, 'message' => 'บันทึกรายวิชาเรียบร้อยแล้ว']);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateExamStatus()
    {
        try {
            $this->checkAuth();
            $id = $this->request->getPost('id');
            $status = $this->request->getPost('status');

            if (empty($id) || empty($status)) {
                return $this->respond(['success' => false, 'message' => 'Missing parameters'], 400);
            }

            $examModel = new \App\Models\ExamModel();
            $exam = $examModel->find($id);
            if (!$exam) {
                return $this->respond(['success' => false, 'message' => 'ไม่พบวิชาสอบนี้'], 404);
            }

            $updateData = ['exam_status' => $status];
            if ($status === 'Started') {
                // Preserve the original start time on resume (Paused -> Started)
                if (empty($exam['started_at'])) {
                    $updateData['started_at'] = date('Y-m-d H:i:s');
                }
            } else if ($status === 'Waiting') {
                $updateData['started_at'] = null;
            }
            $examModel->update($id, $updateData);

            // Clear students queue if status is Waiting
            if ($status === 'Waiting') {
                $studentModel = new StudentModel();
                $studentModel->where('exam_id', $id)->delete();
            }

            return $this->respond(['success' => true, 'message' => 'ปรับปรุงสถานะสอบแล้ว']);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateJoinPolicy()
    {
        try {
            $this->checkAuth();
            $id = $this->request->getPost('id');
            $policy = $this->request->getPost('join_policy');

            if (empty($id) || empty($policy)) {
                return $this->respond(['success' => false, 'message' => 'Missing parameters'], 400);
            }

            $examModel = new \App\Models\ExamModel();
            $exam = $examModel->find($id);
            if (!$exam) {
                return $this->respond(['success' => false, 'message' => 'ไม่พบวิชาสอบนี้'], 404);
            }

            $examModel->update($id, ['join_policy' => $policy]);
            return $this->respond(['success' => true, 'message' => 'ปรับปรุงนโยบายการเข้าร่วมสอบแล้ว']);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteExam()
    {
        try {
            $this->checkAuth();
            $id = $this->request->getPost('id');
            if (empty($id)) {
                return $this->respond(['success' => false, 'message' => 'Missing exam ID'], 400);
            }

            $examModel = new \App\Models\ExamModel();
            $examModel->delete($id);

            // Cascade delete related records
            $questionModel = new QuestionModel();
            $questionModel->where('exam_id', $id)->delete();

            $resultModel = new ExamResultModel();
            $resultModel->where('exam_id', $id)->delete();

            $studentModel = new StudentModel();
            $studentModel->where('exam_id', $id)->delete();

            return $this->respond(['success' => true, 'message' => 'ลบรายวิชาและข้อมูลที่เกี่ยวข้องสำเร็จ']);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getQuestions()
    {
        try {
            $this->checkAuth();
            $examId = $this->request->getGet('exam_id');
            $questionModel = new QuestionModel();
            if ($examId) {
                $questions = $questionModel->where('exam_id', $examId)->findAll();
            } else {
                $questions = $questionModel->findAll();
            }
            return $this->respond(['success' => true, 'questions' => $questions]);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function saveQuestion()
    {
        try {
            $this->checkAuth();
            $id = $this->request->getPost('id') ?: null;
            $examId = $this->request->getPost('exam_id');
            $questionText = $this->request->getPost('question') ?? '';
            $type = $this->request->getPost('type') ?? 'choice';
            $points = (int)($this->request->getPost('points') ?? 1);
            $imageUrl = $this->request->getPost('image_url');
            if (empty($imageUrl)) {
                $imageUrl = null;
            }

            if (empty($examId)) {
                return $this->respond(['success' => false, 'message' => 'กรุณาระบุรายวิชาสอบ'], 400);
            }

            $options = $this->request->getPost('options');
            if (is_string($options)) {
                $options = json_decode($options, true);
            }

            $answer = $this->request->getPost('answer') ?? '';

            if (empty($questionText)) {
                return $this->respond(['success' => false, 'message' => 'กรุณากรอกโจทย์คำถาม'], 400);
            }

            $questionModel = new QuestionModel();

            if (empty($id)) {
                $id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                    mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                    mt_rand(0, 0xffff),
                    mt_rand(0, 0x0fff) | 0x4000,
                    mt_rand(0, 0x3fff) | 0x8000,
                    mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
                );
            }

            $data = [
                'id'             => $id,
                'exam_id'        => $examId,
                'question_text'  => $questionText,
                'type'           => $type,
                'points'         => $points,
                'correct_answer' => $answer,
                'image_url'      => $imageUrl,
                'option_a'       => $type === 'choice' ? ($options[0] ?? '') : null,
                'option_b'       => $type === 'choice' ? ($options[1] ?? '') : null,
                'option_c'       => $type === 'choice' ? ($options[2] ?? '') : null,
                'option_d'       => $type === 'choice' ? ($options[3] ?? '') : null,
            ];

            $questionModel->save($data);
            return $this->respond(['success' => true, 'message' => 'บันทึกข้อสอบเรียบร้อย']);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function deleteQuestion()
    {
        try {
            $this->checkAuth();
            $id = $this->request->getPost('id');
            if (empty($id)) {
                return $this->respond(['success' => false, 'message' => 'Missing question ID'], 400);
            }

            $questionModel = new QuestionModel();
            $question = $questionModel->find($id);
            if ($question && !empty($question['image_url'])) {
                self::deleteUploadedImage($question['image_url']);
            }

            $questionModel->delete($id);
            return $this->respond(['success' => true, 'message' => 'ลบข้อสอบเรียบร้อย']);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function clearAllQuestions()
    {
        try {
            $this->checkAuth();
            $examId = $this->request->getPost('exam_id');
            if (empty($examId)) {
                return $this->respond(['success' => false, 'message' => 'Missing exam ID'], 400);
            }

            $questionModel = new QuestionModel();
            $questions = $questionModel->where('exam_id', $examId)->findAll();
            foreach ($questions as $question) {
                if (!empty($question['image_url'])) {
                    self::deleteUploadedImage($question['image_url']);
                }
            }

            $questionModel->where('exam_id', $examId)->delete();
            return $this->respond(['success' => true, 'message' => 'ล้างคลังข้อสอบของรายวิชานี้เรียบร้อยแล้ว']);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function updateQuestionPoints()
    {
        try {
            $this->checkAuth();
            $id = $this->request->getPost('id');
            $points = $this->request->getPost('points');

            if (empty($id) || $points === null || $points === '') {
                return $this->respond(['success' => false, 'message' => 'Missing parameters'], 400);
            }

            $db = \Config\Database::connect();
            $db->query("UPDATE questions SET points = ? WHERE id = ?", [(float)$points, $id]);

            return $this->respond(['success' => true, 'message' => 'อัปเดตคะแนนเรียบร้อย']);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function importQuestions()
    {
        try {
            $this->checkAuth();
            $examId = $this->request->getPost('exam_id');
            $rows = $this->request->getPost('rows');
            $clearExisting = filter_var($this->request->getPost('clearExisting'), FILTER_VALIDATE_BOOLEAN);

            if (empty($examId)) {
                return $this->respond(['success' => false, 'message' => 'Missing exam ID'], 400);
            }

            if (is_string($rows)) {
                $rows = json_decode($rows, true);
            }

            if (empty($rows)) {
                return $this->respond(['success' => false, 'message' => 'ไม่มีข้อมูลให้นำเข้า'], 400);
            }

            $questionModel = new QuestionModel();
            if ($clearExisting) {
                $questionModel->where('exam_id', $examId)->delete();
            }

            $count = 0;
            foreach ($rows as $row) {
                if (count($row) >= 6 && !empty($row[0])) {
                    $id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                        mt_rand(0, 0xffff),
                        mt_rand(0, 0x0fff) | 0x4000,
                        mt_rand(0, 0x3fff) | 0x8000,
                        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
                    );

                    $type = (!empty($row[6]) && trim(strtolower($row[6])) === 'writing') ? 'writing' : 'choice';
                    $points = (!empty($row[7])) ? (int)$row[7] : 1;

                    $questionModel->insert([
                        'id'             => $id,
                        'exam_id'        => $examId,
                        'question_text'  => $row[0],
                        'option_a'       => $type === 'choice' ? ($row[1] ?? '') : null,
                        'option_b'       => $type === 'choice' ? ($row[2] ?? '') : null,
                        'option_c'       => $type === 'choice' ? ($row[3] ?? '') : null,
                        'option_d'       => $type === 'choice' ? ($row[4] ?? '') : null,
                        'correct_answer' => $row[5] ?? '',
                        'type'           => $type,
                        'points'         => $points,
                    ]);
                    $count++;
                }
            }

            return $this->respond(['success' => true, 'count' => $count]);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function uploadImage()
    {
        try {
            $this->checkAuth();
            $base64Data = $this->request->getPost('image') ?? '';
            $fileName = $this->request->getPost('fileName') ?? 'upload_' . time() . '.png';

            if (empty($base64Data)) {
                return $this->respond(['success' => false, 'message' => 'No image data'], 400);
            }

            if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                $data = substr($base64Data, strpos($base64Data, ',') + 1);
                $type = strtolower($type[1]);

                if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png', 'webp'])) {
                    return $this->respond(['success' => false, 'message' => 'Invalid image type'], 400);
                }

                $data = base64_decode($data);
                if ($data === false) {
                    return $this->respond(['success' => false, 'message' => 'Base64 decode failed'], 400);
                }
            } else {
                return $this->respond(['success' => false, 'message' => 'Invalid data URI format'], 400);
            }

            $uploadDir = ROOTPATH . 'public/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }

            // Force a safe image extension from the detected data-URI type and
            // discard any user-supplied extension to prevent executable uploads (.php etc.)
            $baseName = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo(trim($fileName), PATHINFO_FILENAME));
            $baseName = trim($baseName, '_');
            if ($baseName === '') {
                $baseName = 'image';
            }
            $uniqueFileName = time() . '_' . substr($baseName, 0, 80) . '.' . $type;
            $filePath = $uploadDir . $uniqueFileName;

            if (file_put_contents($filePath, $data)) {
                $url = '/uploads/' . $uniqueFileName;
                return $this->respond(['success' => true, 'url' => $url, 'fileName' => $uniqueFileName]);
            }

            return $this->respond(['success' => false, 'message' => 'Failed to save file'], 500);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteImage()
    {
        try {
            $this->checkAuth();
            $url = $this->request->getPost('url') ?? '';
            if (!empty($url)) {
                if (self::deleteUploadedImage($url)) {
                    return $this->respond(['success' => true, 'message' => 'ลบไฟล์รูปภาพสำเร็จ']);
                }
            }
            return $this->respond(['success' => false, 'message' => 'ไม่พบไฟล์รูปภาพ'], 404);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getResults()
    {
        try {
            $this->checkAuth();
            $examId = $this->request->getGet('exam_id');
            $resultModel = new ExamResultModel();
            
            if ($examId) {
                $results = $resultModel->where('exam_id', $examId)->orderBy('submitted_at', 'DESC')->findAll();
            } else {
                $results = $resultModel->orderBy('submitted_at', 'DESC')->findAll();
            }

            // --- Auto-Repair choice scores on the fly for any mismatched imports ---
            if (!empty($results)) {
                $questionModel = new QuestionModel();
                $allQs = $questionModel->findAll();
                $qMap = [];
                foreach ($allQs as $q) {
                    $qMap[$q['id']] = $q;
                }

                foreach ($results as &$res) {
                    $answers = json_decode($res['answers_json'], true);
                    if (!is_array($answers)) continue;

                    $scoreUpdated = false;
                    $recalculatedScore = 0;

                    foreach ($answers as &$ans) {
                        $qId = $ans['questionId'] ?? '';
                        if (empty($qId) || !isset($qMap[$qId])) continue;

                        $q = $qMap[$qId];
                        $selected = trim($ans['selected'] ?? '');

                        // Always sync reference answer with current DB value
                        $currentCorrectAnswer = $q['correct_answer'] ?? '';
                        if (($ans['correct'] ?? '') !== $currentCorrectAnswer) {
                            $ans['correct'] = $currentCorrectAnswer;
                            $scoreUpdated = true;
                        }
                        
                        if ($ans['type'] === 'choice') {
                            $isCorrect = false;
                            $correctAnswerText = trim($q['correct_answer']);
                            $isCorrect = (strcasecmp($selected, $correctAnswerText) === 0);

                            $newStatus = $isCorrect ? 'ถูกต้อง' : 'ผิด';
                            if (($ans['isCorrect'] ?? '') !== $newStatus) {
                                $ans['isCorrect'] = $newStatus;
                                $scoreUpdated = true;
                            }

                            $newPoints = (float)$q['points'];
                            if ((float)($ans['points'] ?? 0) !== $newPoints) {
                                $ans['points'] = $newPoints;
                                $scoreUpdated = true;
                            }

                            if ($isCorrect) {
                                $recalculatedScore += $newPoints;
                            }
                        } else if ($ans['type'] === 'writing') {
                            $newPoints = (float)$q['points'];
                            if ((float)($ans['points'] ?? 0) !== $newPoints) {
                                $ans['points'] = $newPoints;
                                $scoreUpdated = true;
                            }
                            if (($ans['isCorrect'] ?? '') !== 'รอตรวจ') {
                                $recalculatedScore += (float)($ans['isCorrect'] ?? 0);
                            }
                        }
                    }

                    // Only rewrite when answers_json content actually changed,
                    // so manually adjusted scores (updateScoreDirect) are preserved.
                    if ($scoreUpdated) {
                        $res['score'] = $recalculatedScore;
                        $res['answers_json'] = json_encode($answers, JSON_UNESCAPED_UNICODE);

                        $resultModel->update($res['id'], [
                            'score' => $recalculatedScore,
                            'answers_json' => $res['answers_json']
                        ]);
                    }
                }
            }
            // ------------------------------------------------------------------------

            return $this->respond(['success' => true, 'results' => $results]);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function deleteResult()
    {
        try {
            $this->checkAuth();
            $id = $this->request->getVar('id');
            if (empty($id)) {
                return $this->respond(['success' => false, 'message' => 'Missing result ID'], 400);
            }

            $resultModel = new ExamResultModel();
            $result = $resultModel->find($id);
            if ($result) {
                $email = $result['email'];
                $examId = $result['exam_id'];

                // Delete student lobby/registration record
                $studentModel = new StudentModel();
                $studentModel->where('email', $email)->where('exam_id', $examId)->delete();

                // Delete student cheating/error logs
                $errorLogModel = new ErrorLogModel();
                $errorLogModel->where('student_email', $email)->where('exam_id', $examId)->delete();
            }

            $resultModel->delete($id);
            return $this->respond(['success' => true, 'message' => 'ลบผลการสอบและล้างประวัติการลงทะเบียนสำเร็จ']);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function updateScore()
    {
        try {
            $this->checkAuth();
            $id = $this->request->getPost('id');
            $totalScore = (int)$this->request->getPost('totalScore');
            $writingScores = $this->request->getPost('writingScores');

            if (is_string($writingScores)) {
                $writingScores = json_decode($writingScores, true);
            }

            if (empty($id)) {
                return $this->respond(['success' => false, 'message' => 'Missing parameters'], 400);
            }

            $resultModel = new ExamResultModel();
            $result = $resultModel->find($id);

            if (!$result) {
                return $this->respond(['success' => false, 'message' => 'ไม่พบข้อมูลการสอบนี้'], 404);
            }

            $answers = json_decode($result['answers_json'], true);

            foreach ($answers as &$ans) {
                $qId = $ans['questionId'];
                if ($ans['type'] === 'writing' && isset($writingScores[$qId])) {
                    $ans['isCorrect'] = $writingScores[$qId];
                }
            }

            $resultModel->update($id, [
                'score'        => $totalScore,
                'answers_json' => json_encode($answers, JSON_UNESCAPED_UNICODE),
                'cheating_flag' => 'NO',
                'cheating_count' => 0
            ]);

            return $this->respond(['success' => true, 'message' => 'อัปเดตคะแนนสำเร็จ!']);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function gradeResults()
    {
        try {
            $this->checkAuth();
            $id = $this->request->getPost('id');
            $updatesJson = $this->request->getPost('updates');

            if (empty($id) || empty($updatesJson)) {
                return $this->respond(['success' => false, 'message' => 'Missing parameters'], 400);
            }

            $updates = json_decode($updatesJson, true);
            if (!is_array($updates)) {
                return $this->respond(['success' => false, 'message' => 'Invalid updates format'], 400);
            }

            $resultModel = new ExamResultModel();
            $result = $resultModel->find($id);

            if (!$result) {
                return $this->respond(['success' => false, 'message' => 'ไม่พบข้อมูลการสอบนี้'], 404);
            }

            $answers = json_decode($result['answers_json'], true);
            $totalScore = 0;

            foreach ($answers as &$ans) {
                if ($ans['type'] === 'choice') {
                    if (($ans['isCorrect'] ?? false) === true || ($ans['isCorrect'] ?? '') === 'ถูกต้อง') {
                        $totalScore += (float)($ans['points'] ?? 1);
                    }
                } else if ($ans['type'] === 'writing') {
                    // Find if there's an update for this writing question
                    foreach ($updates as $upd) {
                        if ($upd['questionId'] == $ans['questionId']) {
                            $ans['isCorrect'] = (float)$upd['score'];
                            if (isset($upd['aiFeedback']) && !empty($upd['aiFeedback'])) {
                                $ans['aiFeedback'] = $upd['aiFeedback'];
                            } else {
                                $ans['aiFeedback'] = 'ตรวจให้คะแนนโดยครูผู้สอน';
                            }
                            break;
                        }
                    }
                    if (is_numeric($ans['isCorrect'] ?? '')) {
                        $totalScore += (float)$ans['isCorrect'];
                    }
                }
            }

            $resultModel->update($id, [
                'score'        => $totalScore,
                'answers_json' => json_encode($answers, JSON_UNESCAPED_UNICODE)
            ]);

            return $this->respond(['success' => true, 'message' => 'บันทึกคะแนนสำเร็จ!']);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function updateScoreDirect()
    {
        try {
            $this->checkAuth();
            $id = $this->request->getPost('id');
            $score = $this->request->getPost('score');
            $name = $this->request->getPost('name');
            $room = $this->request->getPost('room');
            $student_number = $this->request->getPost('student_number');
            $student_code = $this->request->getPost('student_code');

            if (empty($id) || $score === null) {
                return $this->respond(['success' => false, 'message' => 'Missing parameters'], 400);
            }

            $resultModel = new ExamResultModel();
            $result = $resultModel->find($id);

            if (!$result) {
                return $this->respond(['success' => false, 'message' => 'ไม่พบข้อมูลการสอบนี้'], 404);
            }

            $updateData = [
                'score' => (float)$score,
                'cheating_flag' => 'NO',
                'cheating_count' => 0
            ];
            
            if ($name !== null) $updateData['name'] = trim($name);
            if ($room !== null) $updateData['room'] = trim($room);
            if ($student_number !== null) $updateData['student_number'] = trim($student_number);
            if ($student_code !== null) $updateData['student_code'] = trim($student_code);

            $resultModel->update($id, $updateData);

            return $this->respond(['success' => true, 'message' => 'อัปเดตข้อมูลเรียบร้อยแล้ว']);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getMonitor()
    {
        try {
            $this->checkAuth();
            $examId = $this->request->getGet('exam_id');
            $studentModel = new StudentModel();
            
            $timeThreshold = date('Y-m-d H:i:s', time() - 120);
            $query = $studentModel->where('last_active >=', $timeThreshold);
            if ($examId) {
                $query = $query->where('exam_id', $examId);
            }
            $students = $query->orderBy('name', 'ASC')->findAll();

            $joinPolicy = 'anytime';
            $examStatus = 'Waiting';
            if ($examId) {
                $examModel = new \App\Models\ExamModel();
                $exam = $examModel->find($examId);
                if ($exam) {
                    $examStatus = $exam['exam_status'];
                    $joinPolicy = $exam['join_policy'] ?? 'anytime';
                }
            }
                                     
            $lobbyMode = 'game';
            $settingModel = new \App\Models\SettingModel();
            $settingsData = $settingModel->getSettings();
            if (isset($settingsData['Lobby Mode'])) {
                $lobbyMode = $settingsData['Lobby Mode'];
            }
                                     
            return $this->respond([
                'success' => true, 
                'students' => $students,
                'exam_status' => $examStatus,
                'lobby_mode' => $lobbyMode,
                'join_policy' => $joinPolicy
            ]);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function deleteStudent()
    {
        try {
            $this->checkAuth();
            $email = $this->request->getPost('email');
            $examId = $this->request->getPost('exam_id');
            if (empty($email) || empty($examId)) {
                return $this->respond(['success' => false, 'message' => 'Missing email or exam ID'], 400);
            }

            $studentModel = new StudentModel();
            $studentModel->where('email', $email)->where('exam_id', $examId)->delete();
            return $this->respond(['success' => true, 'message' => 'เอาชื่อนักเรียนออกสำเร็จ']);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function getLogs()
    {
        try {
            $this->checkAuth();
            $examId = $this->request->getGet('exam_id');
            
            $db = \Config\Database::connect();
            $builder = $db->table('error_logs l');
            $builder->select('l.*, e.subject_code, e.subject_name');
            $builder->join('exams e', 'e.id = l.exam_id', 'left');
            
            $isSuperAdmin = empty($this->session->get('teacher_email'));
            $teacherEmail = $this->session->get('teacher_email');

            if (!$isSuperAdmin && !empty($teacherEmail)) {
                $builder->where('e.teacher_email', $teacherEmail);
            }

            if ($examId && $examId !== 'ALL') {
                $builder->where('l.exam_id', $examId);
            }
            
            $builder->orderBy('l.timestamp', 'DESC');
            $logs = $builder->get()->getResultArray();
            
            return $this->respond(['success' => true, 'logs' => $logs]);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function clearLogs()
    {
        try {
            $this->checkAuth();
            $examId = $this->request->getGetPost('exam_id');
            $errorLogModel = new ErrorLogModel();
            
            $isSuperAdmin = empty($this->session->get('teacher_email'));
            $teacherEmail = $this->session->get('teacher_email');

            if ($examId && $examId !== 'ALL') {
                if (!$isSuperAdmin && !empty($teacherEmail)) {
                    $examModel = new \App\Models\ExamModel();
                    $exam = $examModel->find($examId);
                    if ($exam && !empty($exam['teacher_email']) && $exam['teacher_email'] !== $teacherEmail) {
                        return $this->respond(['success' => false, 'message' => 'คุณไม่มีสิทธิ์ลบประวัติของวิชานี้'], 403);
                    }
                }
                $errorLogModel->where('exam_id', $examId)->delete();
                return $this->respond(['success' => true, 'message' => 'ล้างบันทึกความเสี่ยงของรายวิชานี้สำเร็จ']);
            } else {
                if (!$isSuperAdmin && !empty($teacherEmail)) {
                    $db = \Config\Database::connect();
                    $examIdsQuery = $db->table('exams')->select('id')->where('teacher_email', $teacherEmail)->get()->getResultArray();
                    $ids = array_column($examIdsQuery, 'id');
                    if (!empty($ids)) {
                        $errorLogModel->whereIn('exam_id', $ids)->delete();
                    }
                    return $this->respond(['success' => true, 'message' => 'ล้างบันทึกความเสี่ยงของรายวิชาทั้งหมดของคุณสำเร็จ']);
                } else {
                    $errorLogModel->truncate();
                    return $this->respond(['success' => true, 'message' => 'ล้างบันทึกความเสี่ยงทั้งหมดสำเร็จ']);
                }
            }
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public function aiGrade()
    {
        try {
            $this->checkAuth();
            $question = $this->request->getPost('question');
            $studentAnswer = trim($this->request->getPost('studentAnswer') ?? '');
            $correctAnswer = $this->request->getPost('correctAnswer');
            $maxPoints = (float)($this->request->getPost('maxPoints') ?: 1);

            if ($studentAnswer === '' || $studentAnswer === '(ไม่ได้ตอบ)') {
                return $this->respond([
                    'success' => true,
                    'score' => 0.0,
                    'explanation' => 'ไม่ได้ตอบคำถาม'
                ]);
            }

            $settingModel = new \App\Models\SettingModel();
            $settings = $settingModel->getSettings();
            $apiKey = $settings['Gemini API Key'] ?? '';

            if (empty($apiKey)) {
                // Fallback: use text similarity grading (no AI API key)
                $textResult = $this->gradeWithTextSimilarity($correctAnswer, $studentAnswer, $maxPoints);
                return $this->respond([
                    'success' => true,
                    'score' => $textResult['score'],
                    'explanation' => $textResult['reason']
                ]);
            }

            $cleanKey = trim($apiKey);
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . $cleanKey;

            $prompt = "คุณคือครูผู้ช่วยตรวจข้อสอบอัตนัย (ข้อเขียน)\n" .
                      "โจทย์: \"{$question}\"\n" .
                      "เฉลยแนวทาง: \"{$correctAnswer}\"\n" .
                      "คำตอบของนักเรียน: \"{$studentAnswer}\"\n" .
                      "คะแนนเต็มของข้อนี้: {$maxPoints} คะแนน\n\n" .
                      "กติกา:\n" .
                      "- ประเมินความถูกต้องและเนื้อหาของคำตอบนักเรียนเทียบกับเฉลยแนวทางอย่างเป็นธรรม\n" .
                      "- หากเฉลยแนวทางถูกแบ่งออกเป็นหลายๆ บรรทัด ให้ตรวจสอบคำตอบของนักเรียนโดยเปรียบเทียบคำตอบทีละบรรทัดตรงตามลำดับ (Sequential order comparison) หักคะแนนหากเรียงลำดับไม่ถูกต้องหรือมีบรรทัดขาดหายไป\n" .
                      "- ให้คะแนนเป็นตัวเลขทศนิยมระหว่าง 0 ถึง {$maxPoints} (เช่น 0, 0.5, 1, 1.5, 2, ...)\n" .
                      "- ตอบกลับเป็น JSON เท่านั้นในรูปแบบ:\n" .
                      "{\n" .
                      "  \"score\": ตัวเลขคะแนนที่ได้,\n" .
                      "  \"explanation\": \"อธิบายเหตุผลอย่างละเอียด ว่าทำไมถึงได้คะแนนเท่านี้ ขาดเนื้อหาหรือเงื่อนไขส่วนไหนไปบ้างจากเฉลย (ตอบเป็นภาษาไทย)\"\n" .
                      "}";

            $payload = [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ]
            ];

            // Retry with exponential backoff for rate limiting (HTTP 429)
            $maxRetries = 3;
            $retryDelay = 2; // seconds
            $response = '';
            $httpCode = 0;

            for ($attempt = 0; $attempt <= $maxRetries; $attempt++) {
                if ($attempt > 0) {
                    sleep($retryDelay * $attempt); // 2s, 4s, 6s
                }

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                curl_setopt($ch, CURLOPT_TIMEOUT, 20);

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                // If not rate limited, break out of retry loop
                if ($httpCode !== 429) {
                    break;
                }

                log_message('warning', "Gemini AI Grade: rate limited (429), retry {$attempt}/{$maxRetries}");
            }

            if ($httpCode !== 200) {
                // Fallback to text similarity when AI fails
                $textResult = $this->gradeWithTextSimilarity($correctAnswer, $studentAnswer, $maxPoints);
                return $this->respond([
                    'success' => true,
                    'score' => $textResult['score'],
                    'explanation' => "AI Error (HTTP {$httpCode}) — " . $textResult['reason']
                ]);
            }

            $result = json_decode($response, true);
            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                $aiText = $result['candidates'][0]['content']['parts'][0]['text'];
                $aiText = preg_replace('/```json/', '', $aiText);
                $aiText = preg_replace('/```/', '', $aiText);
                $aiText = trim($aiText);

                $aiResponse = json_decode($aiText, true);
                $score = isset($aiResponse['score']) ? (float)$aiResponse['score'] : 0.0;
                $explanation = $aiResponse['explanation'] ?? 'ตรวจโดย AI สำเร็จ';

                // Cap the score between 0 and maxPoints
                if ($score < 0) $score = 0.0;
                if ($score > $maxPoints) $score = $maxPoints;

                return $this->respond([
                    'success' => true,
                    'score' => $score,
                    'explanation' => $explanation
                ]);
            }

            // Fallback to text similarity when AI response format is invalid
            $textResult = $this->gradeWithTextSimilarity($correctAnswer, $studentAnswer, $maxPoints);
            return $this->respond([
                'success' => true,
                'score' => $textResult['score'],
                'explanation' => "AI Response Invalid — " . $textResult['reason']
            ]);
        } catch (\Exception $e) {
            return $this->respond(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Grade essay answers using text similarity algorithms (no AI required)
     * Uses 3 techniques: keyword matching, similar_text(), and n-gram overlap
     */
    private function gradeWithTextSimilarity($referenceAnswer, $studentAnswer, $maxPoints = 1.0)
    {
        $student = trim($studentAnswer);
        $reference = trim($referenceAnswer);

        if (empty($student) || $student === '(ไม่ได้ตอบ)') {
            return ['score' => 0.0, 'isCorrect' => false, 'reason' => 'ไม่ได้ตอบคำถาม'];
        }

        // 1. Calculate overall full-text similarity scores first
        $studentNorm = mb_strtolower(preg_replace('/\s+/u', ' ', $student));
        $referenceNorm = mb_strtolower(preg_replace('/\s+/u', ' ', $reference));

        // Exact match → full score immediately
        if ($studentNorm === $referenceNorm) {
            return [
                'score' => (float) $maxPoints,
                'isCorrect' => true,
                'reason' => '✅ คำตอบตรงกับเฉลยทุกประการ'
            ];
        }

        // Keywords matching
        $phrases = preg_split('/[\|,;;\n]+/u', $reference, -1, PREG_SPLIT_NO_EMPTY);
        $phrases = array_map('trim', $phrases);
        $phrases = array_filter($phrases, fn($p) => mb_strlen($p) >= 2);
        $phrases = array_values($phrases);

        $matchedCount = 0;
        $totalCount = count($phrases);

        if ($totalCount <= 1) {
            $words = preg_split('/[\s]+/u', $referenceNorm, -1, PREG_SPLIT_NO_EMPTY);
            $words = array_filter($words, fn($w) => mb_strlen($w) >= 2);
            $words = array_values($words);
            $totalCount = count($words);
            foreach ($words as $word) {
                $word = mb_strtolower($word);
                if (mb_strpos($studentNorm, $word) !== false || mb_strpos($word, $studentNorm) !== false) {
                    $matchedCount++;
                }
            }
        } else {
            foreach ($phrases as $phrase) {
                $phraseClean = mb_strtolower(trim($phrase));
                if (mb_strpos($studentNorm, $phraseClean) !== false || mb_strpos($phraseClean, $studentNorm) !== false) {
                    $matchedCount++;
                }
            }
        }
        $keywordScore = $totalCount > 0 ? ($matchedCount / $totalCount) : 0;

        // Character similarity
        similar_text($studentNorm, $referenceNorm, $similarPercent);
        $textSimScore = $similarPercent / 100;

        // N-grams
        $ngramSize = 3;
        $refLen = mb_strlen($referenceNorm);
        $ngramScore = 0;
        if ($refLen >= $ngramSize) {
            $refNgrams = [];
            for ($i = 0; $i <= $refLen - $ngramSize; $i++) {
                $refNgrams[] = mb_substr($referenceNorm, $i, $ngramSize);
            }
            $matchedNgrams = 0;
            foreach ($refNgrams as $ng) {
                if (mb_strpos($studentNorm, $ng) !== false || mb_strpos($ng, $studentNorm) !== false) {
                    $matchedNgrams++;
                }
            }
            $ngramScore = count($refNgrams) > 0 ? ($matchedNgrams / count($refNgrams)) : 0;
        } else {
            $ngramScore = (mb_strpos($referenceNorm, $studentNorm) !== false || mb_strpos($studentNorm, $referenceNorm) !== false) ? 1.0 : 0.0;
        }

        $overallScore = ($keywordScore * 0.30) + ($textSimScore * 0.40) + ($ngramScore * 0.30);

        // 2. Line-by-line sequential check (if multi-line reference)
        $refLines = preg_split('/\r\n|\r|\n/', $reference);
        $refLines = array_map('trim', $refLines);
        $refLines = array_filter($refLines, fn($line) => $line !== '');
        $refLines = array_values($refLines);

        $lineScore = 0.0;
        $isLineBasedGradingUsed = false;
        $matchedLinesCount = 0;
        $totalLinesCount = count($refLines);

        if ($totalLinesCount > 1) {
            $studentLines = preg_split('/\r\n|\r|\n/', $student);
            $studentLines = array_map('trim', $studentLines);
            $studentLines = array_filter($studentLines, fn($line) => $line !== '');
            $studentLines = array_values($studentLines);

            $linesMatchedPercentSum = 0;
            for ($i = 0; $i < $totalLinesCount; $i++) {
                if (isset($studentLines[$i])) {
                    $sLineNorm = mb_strtolower(preg_replace('/\s+/u', ' ', $studentLines[$i]));
                    $rLineNorm = mb_strtolower(preg_replace('/\s+/u', ' ', $refLines[$i]));
                    
                    if ($sLineNorm === $rLineNorm) {
                        $matchedLinesCount++;
                        $linesMatchedPercentSum += 1.0;
                    } else {
                        // Check similarity of this line
                        similar_text($sLineNorm, $rLineNorm, $linePercent);
                        if ($linePercent >= 60) {
                            $matchedLinesCount++;
                            $linesMatchedPercentSum += ($linePercent / 100);
                        }
                    }
                }
            }
            $lineScore = $totalLinesCount > 0 ? ($linesMatchedPercentSum / $totalLinesCount) : 0;

            // If line-by-line checking gives a higher score (i.e. they answered in order), prioritize it
            if ($lineScore > $overallScore) {
                $overallScore = $lineScore;
                $isLineBasedGradingUsed = true;
            }
        }

        // Apply lenient multiplier mapping (minimum 50% score for minor matching)
        if ($overallScore > 0.05) {
            $overallScore = 0.5 + ($overallScore * 0.5);
        } else {
            $overallScore = 0.0;
        }

        // Scale to max points and round to nearest 0.5
        $finalScore = round($overallScore * $maxPoints * 2) / 2;
        $finalScore = max(0.0, min((float) $maxPoints, $finalScore));

        if ($isLineBasedGradingUsed) {
            $reason = sprintf(
                "✅ ตรวจจับแบบแยกบรรทัดเรียงลำดับได้คะแนนสูงกว่า: ตรง %d/%d บรรทัด (เฉลี่ยความคล้าย %.1f%%, คะแนน %.1f/%g)",
                $matchedLinesCount,
                $totalLinesCount,
                $overallScore * 100,
                $finalScore,
                $maxPoints
            );
        } else {
            $reason = sprintf(
                "🔤 ตรวจคำตอบแบบภาพรวมได้คะแนนสูงกว่า:\n" .
                "1. การจับคู่คีย์เวิร์ด (น้ำหนัก 30%%): %.1f%%\n" .
                "2. ความเหมือนตัวอักษร (น้ำหนัก 40%%): %.1f%%\n" .
                "3. ความเหมือน N-gram (น้ำหนัก 30%%): %.1f%%\n" .
                "👉 ค่าเฉลี่ยรวม: %.1f%% (ได้คะแนน %.1f/%g)",
                $keywordScore * 100,
                $similarPercent,
                $ngramScore * 100,
                $overallScore * 100,
                $finalScore,
                $maxPoints
            );
        }

        return [
            'score' => $finalScore,
            'isCorrect' => $finalScore >= ($maxPoints * 0.5),
            'reason' => $reason
        ];
    }
}
