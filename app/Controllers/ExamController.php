<?php

namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\StudentModel;
use App\Models\QuestionModel;
use App\Models\ExamResultModel;
use App\Models\ErrorLogModel;
use App\Models\ExamModel;
use CodeIgniter\API\ResponseTrait;

class ExamController extends BaseController
{
    use ResponseTrait;

    protected $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        $examModel = new ExamModel();
        $exams = $examModel->orderBy('created_at', 'DESC')->findAll();

        $settingModel = new SettingModel();
        $settings = $settingModel->getSettings();

        $data = [
            'websiteName' => $settings['Website Name'] ?? 'ระบบข้อสอบออนไลน์',
            'logoUrl' => $settings['Logo URL'] ?? '',
            'exams' => $exams
        ];

        return view('student/register', $data);
    }

    public function lobby()
    {
        $email = $this->session->get('student_email');
        $examId = $this->session->get('student_exam_id');
        if (!$email || !$examId) {
            return redirect()->to('/');
        }

        $examModel = new ExamModel();
        $exam = $examModel->find($examId);
        if (!$exam) {
            return redirect()->to('/');
        }

        $settingModel = new SettingModel();
        $settings = $settingModel->getSettings();

        $data = [
            'studentName' => $this->session->get('student_name'),
            'studentEmail' => $email,
            'studentId' => $this->session->get('student_number'),
            'studentRoom' => $this->session->get('student_room'),
            'websiteName' => $settings['Website Name'] ?? 'ระบบข้อสอบออนไลน์',
            'logoUrl' => $settings['Logo URL'] ?? '',
            'teacherName' => $exam['teacher_name'],
            'examType' => $exam['exam_type'],
            'subjectName' => $exam['subject_name'],
            'subjectCode' => $exam['subject_code'],
            'examStatus' => $exam['exam_status'],
            'maxAttempts' => (int) $exam['max_attempts'],
            'examId' => $examId,
        ];

        return view('student/lobby', $data);
    }

    public function exam()
    {
        $email = $this->session->get('student_email');
        $examId = $this->session->get('student_exam_id');
        if (!$email || !$examId) {
            return redirect()->to('/');
        }

        $examModel = new ExamModel();
        $exam = $examModel->find($examId);
        if (!$exam) {
            return redirect()->to('/');
        }

        // If exam is in Waiting status, redirect to lobby
        if ($exam['exam_status'] === 'Waiting') {
            return redirect()->to('/lobby');
        }

        // If exam is Finished, redirect to index
        if ($exam['exam_status'] === 'Finished') {
            return redirect()->to('/');
        }

        $resultModel = new ExamResultModel();
        $attempts = $resultModel->where('email', $email)->where('exam_id', $examId)->countAllResults();
        $maxAttempts = (int) $exam['max_attempts'];

        if ($attempts >= $maxAttempts) {
            return redirect()->to('/result');
        }

        $remainingSeconds = 0;
        if ((int) $exam['exam_duration'] > 0) {
            $durationSeconds = (int) $exam['exam_duration'] * 60;
            if (!empty($exam['started_at'])) {
                $elapsedSeconds = time() - strtotime($exam['started_at']);
                $remainingSeconds = $durationSeconds - $elapsedSeconds;
                if ($remainingSeconds <= 0) {
                    return redirect()->to('/result');
                }
            } else {
                $remainingSeconds = $durationSeconds;
            }
        }

        $settingModel = new SettingModel();
        $settings = $settingModel->getSettings();

        $data = [
            'studentName' => $this->session->get('student_name'),
            'studentEmail' => $email,
            'studentId' => $this->session->get('student_number'),
            'studentRoom' => $this->session->get('student_room'),
            'websiteName' => $settings['Website Name'] ?? 'ระบบข้อสอบออนไลน์',
            'logoUrl' => $settings['Logo URL'] ?? '',
            'teacherName' => $exam['teacher_name'],
            'examType' => $exam['exam_type'] . ' (' . $exam['subject_name'] . ')',
            'timePerQuestion' => (int) $exam['time_limit_choice'],
            'writingTimePerQuestion' => (int) $exam['time_limit_writing'],
            'numberOfQuestions' => (int) $exam['num_questions'],
            'maxStrikes' => isset($exam['max_strikes']) ? (int) $exam['max_strikes'] : (int) ($settings['Max Cheating Strikes'] ?? 3),
            'examDuration' => (int) ($exam['exam_duration'] ?? 0),
            'examDurationSeconds' => $remainingSeconds,
            'antiCheating' => isset($exam['anti_cheating']) ? (int) $exam['anti_cheating'] : 1,
            'examId' => $examId,
        ];

        return view('student/exam', $data);
    }

    public function result()
    {
        $email = $this->session->get('student_email');
        $examId = $this->session->get('student_exam_id');
        if (!$email || !$examId) {
            return redirect()->to('/');
        }

        $examModel = new ExamModel();
        $exam = $examModel->find($examId);
        if (!$exam) {
            return redirect()->to('/');
        }

        $settingModel = new SettingModel();
        $settings = $settingModel->getSettings();

        $resultModel = new ExamResultModel();
        $lastResult = $resultModel->where('email', $email)
            ->where('exam_id', $examId)
            ->orderBy('submitted_at', 'DESC')
            ->first();

        $data = [
            'studentName' => $this->session->get('student_name'),
            'studentEmail' => $email,
            'websiteName' => $settings['Website Name'] ?? 'ระบบข้อสอบออนไลน์',
            'logoUrl' => $settings['Logo URL'] ?? '',
            'teacherName' => $exam['teacher_name'],
            'examType' => $exam['exam_type'] . ' (' . $exam['subject_name'] . ')',
            'lastResult' => $lastResult,
            'passPercent' => (int) $exam['passing_percentage'],
        ];

        return view('student/result', $data);
    }

    // --- Student APIs ---

    public function register()
    {
        $name = trim($this->request->getPost('name') ?? '');
        $studentNumber = trim($this->request->getPost('studentId') ?? '');
        $room = trim($this->request->getPost('room') ?? $this->request->getPost('studentRoom') ?? '');
        $email = trim(strtolower($this->request->getPost('email') ?? ''));
        $examId = trim($this->request->getPost('examId') ?? '');
        $studentCode = trim($this->request->getPost('studentCode') ?? '');

        if (empty($name) || empty($studentNumber) || empty($room) || empty($email) || empty($examId) || empty($studentCode)) {
            return $this->respond(['success' => false, 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน'], 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->respond(['success' => false, 'message' => 'รูปแบบอีเมลไม่ถูกต้อง'], 400);
        }

        $examModel = new ExamModel();
        $exam = $examModel->find($examId);
        if (!$exam) {
            return $this->respond(['success' => false, 'message' => 'ไม่พบวิชาสอบนี้'], 404);
        }

        if ($exam['exam_status'] === 'Finished') {
            return $this->respond(['success' => false, 'message' => 'การสอบวิชานี้เสร็จสิ้นแล้ว'], 400);
        }

        $studentModel = new StudentModel();
        $resultModel = new ExamResultModel();

        if ($exam['exam_status'] === 'Waiting' && ($exam['join_policy'] ?? 'anytime') === 'anytime') {
            // Exam is in Waiting state, allow student to enter lobby
        } else if ($exam['exam_status'] === 'Started' && ($exam['join_policy'] ?? 'anytime') === 'lobby_first') {
            $inLobby = $studentModel->where('email', $email)->where('exam_id', $examId)->first();
            if (!$inLobby) {
                return $this->respond([
                    'success' => false,
                    'message' => 'ไม่สามารถเข้าสอบได้เนื่องจากรายวิชานี้กำหนดให้นักเรียนต้องเข้าห้องพักคอย (Lobby) สแตนด์บายไว้ก่อนที่การสอบจะเริ่มขึ้นเท่านั้น'
                ], 400);
            }
        }

        // Check if student is blocked due to cheating in this exam
        $cheatingAttempts = $resultModel->where('email', $email)
            ->where('exam_id', $examId)
            ->where('cheating_flag', 'YES')
            ->countAllResults();

        if ($cheatingAttempts > 0) {
            return $this->respond([
                'success' => false,
                'message' => 'นักเรียนถูกบล็อกในการสอบวิชานี้เนื่องจากมีประวัติการโกง กรุณาติดต่อครูผู้สอน'
            ], 403);
        }

        // Check if attempts exceeded
        $attempts = $resultModel->where('email', $email)->where('exam_id', $examId)->countAllResults();
        $maxAttempts = (int) $exam['max_attempts'];
        if ($attempts >= $maxAttempts) {
            return $this->respond([
                'success' => false,
                'message' => 'เองสอบวิชานี้ครบสิทธิ์เรียบร้อยแล้วน้า (' . $maxAttempts . ' ครั้ง)  รอลุ้นคะแนนกันนะ 💖✨'
            ], 403);
        }

        // Prevent duplicate student code in the same exam
        $dupCode = $studentModel->where('student_code', $studentCode)->where('exam_id', $examId)->where('email !=', $email)->first();
        if ($dupCode) {
            return $this->respond(['success' => false, 'message' => 'เลขประจำตัวนักเรียนนี้ถูกใช้ลงทะเบียนสอบโดยบุคคลอื่นแล้ว'], 400);
        }

        // Register student or update details for this exam
        $existing = $studentModel->where('email', $email)->first();
        if ($existing) {
            $studentModel->update($existing['id'], [
                'exam_id' => $examId,
                'name' => $name,
                'student_code' => $studentCode,
                'student_number' => $studentNumber,
                'room' => $room,
                'last_active' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $studentModel->insert([
                'exam_id' => $examId,
                'name' => $name,
                'student_code' => $studentCode,
                'student_number' => $studentNumber,
                'room' => $room,
                'email' => $email,
                'pos_x' => rand(50, 400),
                'pos_y' => rand(50, 400),
            ]);
        }

        // Save session
        $this->session->set([
            'student_name' => $name,
            'student_code' => $studentCode,
            'student_email' => $email,
            'student_number' => $studentNumber,
            'student_room' => $room,
            'student_exam_id' => $examId
        ]);

        return $this->respond([
            'success' => true,
            'message' => 'ลงทะเบียนสำเร็จ',
            'exam_status' => $exam['exam_status'],
            'join_policy' => $exam['join_policy'] ?? 'anytime'
        ]);
    }

    public function syncLobby()
    {
        $email = $this->session->get('student_email');
        $examId = $this->session->get('student_exam_id');
        if (!$email || !$examId) {
            return $this->respond(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $x = (int) $this->request->getPost('x');
        $y = (int) $this->request->getPost('y');
        $score = (int) $this->request->getPost('score');

        $studentModel = new StudentModel();

        // Update self position and score
        $student = $studentModel->where('email', $email)->where('exam_id', $examId)->first();
        if (!$student) {
            return $this->respond([
                'success' => false,
                'redirect' => true,
                'message' => 'Lobby reset or student removed'
            ]);
        }

        $studentModel->update($student['id'], [
            'pos_x' => $x,
            'pos_y' => $y,
            'lobby_score' => $score,
            'last_active' => date('Y-m-d H:i:s')
        ]);

        // Get all active students in the last 2 minutes in the same exam
        $timeThreshold = date('Y-m-d H:i:s', time() - 120);
        $activeStudents = $studentModel->where('last_active >=', $timeThreshold)
            ->where('exam_id', $examId)
            ->findAll();

        $players = [];
        $leaderboard = [];

        foreach ($activeStudents as $std) {
            $isSelf = ($std['email'] === $email);
            if (!$isSelf) {
                $players[] = [
                    'name' => $std['name'],
                    'email' => $std['email'],
                    'x' => (int) $std['pos_x'],
                    'y' => (int) $std['pos_y'],
                    'score' => (int) $std['lobby_score']
                ];
            }
            $leaderboard[] = [
                'name' => $std['name'],
                'score' => (int) $std['lobby_score'],
                'isSelf' => $isSelf
            ];
        }

        // Sort leaderboard by score DESC
        usort($leaderboard, function ($a, $b) {
            return $b['score'] - $a['score'];
        });

        $settingModel = new SettingModel();
        $settingsData = $settingModel->getSettings();
        $lobbyMode = $settingsData['Lobby Mode'] ?? 'game';

        return $this->respond([
            'success' => true,
            'players' => $players,
            'leaderboard' => $leaderboard,
            'lobby_mode' => $lobbyMode
        ]);
    }

    public function status()
    {
        $examId = $this->request->getGet('examId') ?: $this->session->get('student_exam_id');
        if (!$examId) {
            return $this->respond(['examStatus' => 'Waiting']);
        }
        $examModel = new ExamModel();
        $exam = $examModel->find($examId);
        return $this->respond(['examStatus' => $exam ? $exam['exam_status'] : 'Waiting']);
    }

    public function startExam()
    {
        $email = $this->session->get('student_email');
        $examId = $this->session->get('student_exam_id');
        if (!$email || !$examId) {
            return $this->respond(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $examModel = new ExamModel();
        $exam = $examModel->find($examId);
        if (!$exam) {
            return $this->respond(['success' => false, 'message' => 'Exam not found'], 404);
        }

        $resultModel = new ExamResultModel();
        $attempts = $resultModel->where('email', $email)->where('exam_id', $examId)->countAllResults();
        $maxAttempts = (int) $exam['max_attempts'];

        if ($attempts >= $maxAttempts) {
            return $this->respond(['success' => false, 'message' => 'คุณส่งข้อสอบชุดนี้ไปแล้ว'], 400);
        }

        $questionModel = new QuestionModel();
        $allQuestions = $questionModel->where('exam_id', $examId)->findAll();

        // Separate choice and writing questions
        $choices = [];
        $writings = [];

        foreach ($allQuestions as $q) {
            if ($q['type'] === 'writing') {
                $writings[] = $q;
            } else {
                $choices[] = $q;
            }
        }

        // Shuffle choices and slice to standard count
        shuffle($choices);
        $numQuestionsLimit = (int) $exam['num_questions'];
        $selectedChoices = array_slice($choices, 0, $numQuestionsLimit);

        // Shuffle writings
        shuffle($writings);

        // Combine
        $selectedQuestions = array_merge($selectedChoices, $writings);

        $clientQuestions = [];
        foreach ($selectedQuestions as $q) {
            $options = [];
            if ($q['type'] === 'choice') {
                $options = [$q['option_a'], $q['option_b'], $q['option_c'], $q['option_d']];
                shuffle($options);
            }

            $clientQuestions[] = [
                'id' => $q['id'],
                'question' => $q['question_text'],
                'options' => $options,
                'type' => $q['type'],
                'points' => (int) $q['points'],
                'image_url' => $q['image_url'] ?? '',
            ];
        }

        return $this->respond(['success' => true, 'questions' => $clientQuestions]);
    }

    public function submitExam()
    {
        $email = $this->session->get('student_email');
        $examId = $this->session->get('student_exam_id');
        if (!$email || !$examId) {
            return $this->respond(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $examModel = new ExamModel();
        $exam = $examModel->find($examId);
        if (!$exam) {
            return $this->respond(['success' => false, 'message' => 'Exam not found'], 404);
        }

        $answers = $this->request->getPost('answers'); // JSON String or Array
        if (is_string($answers)) {
            $answers = json_decode($answers, true);
        }
        $timeSpent = (int) $this->request->getPost('totalTimeSpent');

        // Count cheating strikes from server-side logs instead of trusting client input
        $errorLogModel = new ErrorLogModel();
        $cheatingCount = $errorLogModel->where('student_email', $email)
            ->where('exam_id', $examId)
            ->where('error_type', 'SUSPICIOUS_ACTIVITY')
            ->countAllResults();
        $maxStrikes = isset($exam['max_strikes']) && (int) $exam['max_strikes'] > 0
            ? (int) $exam['max_strikes']
            : 3;
        $cheatingFlag = ($cheatingCount >= $maxStrikes) ? 'YES' : 'NO';

        $resultModel = new ExamResultModel();
        
        $attempts = $resultModel->where('email', $email)->where('exam_id', $examId)->countAllResults();
        $maxAttempts = (int) $exam['max_attempts'];
        
        if ($attempts >= $maxAttempts) {
            return $this->respond(['success' => false, 'message' => 'คุณส่งข้อสอบครบตามสิทธิ์ที่กำหนดแล้ว'], 400);
        }
        
        $attemptNumber = $attempts + 1;

        $questionModel = new QuestionModel();

        $score = 0;
        $totalQuestions = 0;
        $detailedAnswers = [];

        foreach ($answers as $ans) {
            $qId = $ans['questionId'];
            $selected = trim($ans['selectedOption'] ?? '');

            $q = $questionModel->find($qId);
            if (!$q)
                continue;

            $totalQuestions += (float) $q['points'];
            $isCorrect = false;
            $aiFeedback = '';

            if ($q['type'] === 'writing') {
                $writingEarned = null; // null = not graded
                $aiFeedback = '';

                if (empty($selected)) {
                    $aiFeedback = 'ไม่ได้ตอบคำถาม';
                } else {
                    $aiFeedback = 'รอครูผู้สอนตรวจให้คะแนน';
                }

                $detailedAnswers[] = [
                    'questionId' => $qId,
                    'question' => $q['question_text'],
                    'type' => $q['type'],
                    'points' => (float) $q['points'],
                    'selected' => $selected,
                    'correct' => $q['correct_answer'],
                    'isCorrect' => 'รอตรวจ',
                    'aiFeedback' => $aiFeedback,
                ];
            } else {
                $sel = trim($selected);
                $corr = trim($q['correct_answer']);
                $isCorrect = (strcasecmp($sel, $corr) === 0);

                if ($isCorrect) {
                    $score += (float) $q['points'];
                }

                $detailedAnswers[] = [
                    'questionId' => $qId,
                    'question' => $q['question_text'],
                    'type' => $q['type'],
                    'points' => (float) $q['points'],
                    'selected' => $selected,
                    'correct' => $q['correct_answer'],
                    'isCorrect' => $isCorrect ? 'ถูกต้อง' : 'ผิด',
                    'aiFeedback' => $aiFeedback,
                ];
            }
        }

        $resultModel->insert([
            'exam_id' => $examId,
            'email' => $email,
            'name' => $this->session->get('student_name'),
            'student_code' => $this->session->get('student_code'),
            'student_number' => $this->session->get('student_number'),
            'room' => $this->session->get('student_room'),
            'exam_type' => $exam['exam_type'] . ' (' . $exam['subject_name'] . ')',
            'academic_year' => $exam['academic_year'] ?? '',
            'semester' => $exam['semester'] ?? '',
            'score' => $score,
            'total_questions' => $totalQuestions,
            'total_time_spent' => $timeSpent,
            'attempt_number' => $attemptNumber,
            'cheating_flag' => $cheatingFlag,
            'cheating_count' => $cheatingCount,
            'cheating_reason' => $cheatingCount > 0 ? "TAB_SWITCH_ATTEMPT" : NULL,
            'answers_json' => json_encode($detailedAnswers, JSON_UNESCAPED_UNICODE),
            'exam_round' => $exam['exam_round'] ?? '1',
        ]);

        return $this->respond([
            'success' => true,
            'score' => $score,
            'total' => $totalQuestions,
            'message' => $cheatingCount > 0 ? "สอบเสร็จสิ้น (มีการบันทึกพฤติกรรมระหว่างสอบ)" : "ส่งคำตอบเรียบร้อย!"
        ]);
    }

    public function submitCheat()
    {
        $email = $this->session->get('student_email');
        $examId = $this->session->get('student_exam_id');
        if (!$email || !$examId) {
            return $this->respond(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $examModel = new ExamModel();
        $exam = $examModel->find($examId);
        if (!$exam) {
            return $this->respond(['success' => false, 'message' => 'Exam not found'], 404);
        }

        $reason = $this->request->getPost('cheatingReason') ?? 'TAB_SWITCH_VIOLATION';
        $timeSpent = (int) $this->request->getPost('totalTimeSpent');

        $settingModel = new SettingModel();
        $settings = $settingModel->getSettings();

        $resultModel = new ExamResultModel();
        
        $attempts = $resultModel->where('email', $email)->where('exam_id', $examId)->countAllResults();
        $maxAttempts = (int) $exam['max_attempts'];
        
        if ($attempts >= $maxAttempts) {
            return $this->respond(['success' => false, 'message' => 'คุณส่งข้อสอบครบตามสิทธิ์ที่กำหนดแล้ว'], 400);
        }
        
        $attemptNumber = $attempts + 1;

        $resultModel->insert([
            'exam_id' => $examId,
            'email' => $email,
            'name' => $this->session->get('student_name'),
            'student_code' => $this->session->get('student_code'),
            'student_number' => $this->session->get('student_number'),
            'room' => $this->session->get('student_room'),
            'exam_type' => $exam['exam_type'] . ' (' . $exam['subject_name'] . ')',
            'academic_year' => $exam['academic_year'] ?? '',
            'semester' => $exam['semester'] ?? '',
            'score' => 0,
            'total_questions' => 0,
            'total_time_spent' => $timeSpent,
            'attempt_number' => $attemptNumber,
            'cheating_flag' => 'YES',
            'cheating_count' => isset($exam['max_strikes']) && (int) $exam['max_strikes'] > 0
                ? (int) $exam['max_strikes']
                : (int) ($settings['Max Cheating Strikes'] ?? 3),
            'cheating_reason' => $reason,
            'answers_json' => json_encode([], JSON_UNESCAPED_UNICODE),
            'exam_round' => $exam['exam_round'] ?? '1',
        ]);

        return $this->respond(['success' => true, 'message' => 'บันทึกการโกงเรียบร้อย คะแนนสอบเป็น 0']);
    }

    public function logError()
    {
        $type = $this->request->getPost('type') ?? 'UNKNOWN';
        $message = $this->request->getPost('message') ?? '';
        $email = $this->session->get('student_email') ?? '';
        $userAgent = $this->request->getUserAgent()->getAgentString();
        $resolution = $this->request->getPost('screenResolution') ?? '';
        $examId = $this->request->getPost('exam_id') ?? $this->session->get('student_exam_id') ?? '';

        $errorLogModel = new ErrorLogModel();
        $errorLogModel->insert([
            'error_type' => $type,
            'error_message' => $message,
            'student_email' => $email,
            'user_agent' => $userAgent,
            'screen_resolution' => $resolution,
            'exam_id' => $examId ?: null,
        ]);

        return $this->respond(['success' => true]);
    }

    private function gradeWithGemini($apiKey, $question, $referenceAnswer, $studentAnswer)
    {
        $cleanKey = trim($apiKey);
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . $cleanKey;

        $prompt = "คุณคือครูผู้ช่วยตรวจข้อสอบ\n" .
            "โจทย์: \"{$question}\"\n" .
            "เฉลยแนวทาง: \"{$referenceAnswer}\"\n" .
            "คำตอบของนักเรียน: \"{$studentAnswer}\"\n\n" .
            "กติกา:\n" .
            "- ตรวจสอบ \"เนื้อหา\" เป็นหลัก\n" .
            "- หากความหมายตรงกับเฉลย ให้ถือว่า ถูก (isCorrect: true)\n\n" .
            "ตอบกลับเป็น JSON เท่านั้น:\n" .
            "{\n" .
            "  \"isCorrect\": true หรือ false,\n" .
            "  \"reason\": \"อธิบายสั้นๆ\"\n" .
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

            log_message('warning', "Gemini API rate limited (429), retry {$attempt}/{$maxRetries}");
        }

        if ($httpCode !== 200) {
            // Fallback to text similarity when AI fails
            $fallback = $this->gradeWithTextSimilarity($referenceAnswer, $studentAnswer, 1.0);
            return [
                'isCorrect' => $fallback['isCorrect'],
                'reason' => "AI Error (HTTP {$httpCode}): " . $fallback['reason']
            ];
        }

        $result = json_decode($response, true);
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            $aiText = $result['candidates'][0]['content']['parts'][0]['text'];
            $aiText = preg_replace('/```json/', '', $aiText);
            $aiText = preg_replace('/```/', '', $aiText);
            $aiText = trim($aiText);

            $aiResponse = json_decode($aiText, true);
            return [
                'isCorrect' => ($aiResponse['isCorrect'] ?? false) === true,
                'reason' => $aiResponse['reason'] ?? "ตรวจโดย AI สำเร็จ"
            ];
        }

        // Fallback to text similarity when AI response format is invalid
        $fallback = $this->gradeWithTextSimilarity($referenceAnswer, $studentAnswer, 1.0);
        return [
            'isCorrect' => $fallback['isCorrect'],
            'reason' => "AI Response Invalid: " . $fallback['reason']
        ];
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

        // Normalize whitespace and lowercase
        $studentNorm = mb_strtolower(preg_replace('/\s+/u', ' ', $student));
        $referenceNorm = mb_strtolower(preg_replace('/\s+/u', ' ', $reference));

        // 1. Exact match → full score
        if ($studentNorm === $referenceNorm) {
            return [
                'score' => (float) $maxPoints,
                'isCorrect' => true,
                'reason' => '✅ คำตอบตรงกับเฉลยทุกประการ'
            ];
        }

        // 2. Keyword/Phrase matching (Lenient)
        $phrases = preg_split('/[\|,;;\n]+/u', $reference, -1, PREG_SPLIT_NO_EMPTY);
        $phrases = array_map('trim', $phrases);
        $phrases = array_filter($phrases, fn($p) => mb_strlen($p) >= 2);
        $phrases = array_values($phrases);

        $matchedCount = 0;
        $totalCount = count($phrases);

        if ($totalCount <= 1) {
            // Single answer — split by spaces for word matching
            $words = preg_split('/[\s]+/u', $referenceNorm, -1, PREG_SPLIT_NO_EMPTY);
            $words = array_filter($words, fn($w) => mb_strlen($w) >= 2);
            $words = array_values($words);
            $totalCount = count($words);

            foreach ($words as $word) {
                $word = mb_strtolower($word);
                // Check if word in student answer OR student answer inside the reference word (lenient match)
                if (mb_strpos($studentNorm, $word) !== false || mb_strpos($word, $studentNorm) !== false) {
                    $matchedCount++;
                }
            }
        } else {
            // Multiple keywords/phrases
            foreach ($phrases as $phrase) {
                $phraseClean = mb_strtolower(trim($phrase));
                if (mb_strpos($studentNorm, $phraseClean) !== false || mb_strpos($phraseClean, $studentNorm) !== false) {
                    $matchedCount++;
                }
            }
        }

        $keywordScore = $totalCount > 0 ? ($matchedCount / $totalCount) : 0;

        // 3. similar_text() — PHP built-in character-level similarity
        similar_text($studentNorm, $referenceNorm, $similarPercent);
        $textSimScore = $similarPercent / 100;

        // 4. N-gram overlap (3-char n-grams)
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
            // If reference is very short (e.g. 2 chars), fallback to simple substring check
            $ngramScore = (mb_strpos($referenceNorm, $studentNorm) !== false || mb_strpos($studentNorm, $referenceNorm) !== false) ? 1.0 : 0.0;
        }

        // 5. Weighted combination (Lenient Weights: 30% Keyword, 40% SimText, 30% N-gram)
        $combinedScore = ($keywordScore * 0.30) + ($textSimScore * 0.40) + ($ngramScore * 0.30);

        // If there is any similarity but not exact, guarantee at least 50% score
        if ($combinedScore > 0.05) {
            // Map the score range (0.05 to 1.0) to (0.5 to 1.0) so the minimum is 50%
            $combinedScore = 0.5 + ($combinedScore * 0.5);
        } else {
            $combinedScore = 0.0;
        }

        // Scale to max points and round to nearest 0.5
        $finalScore = round($combinedScore * $maxPoints * 2) / 2;
        $finalScore = max(0.0, min((float) $maxPoints, $finalScore));

        $reason = sprintf(
            "🔤 ตรวจอัตโนมัติด้วยทฤษฎีความเหมือน 3 แบบ:\n" .
            "1. การจับคู่คีย์เวิร์ด/วลีสำคัญ (น้ำหนัก 30%%): %.1f%%\n" .
            "2. ความเหมือนระดับตัวอักษร (น้ำหนัก 40%%): %.1f%%\n" .
            "3. ความเหมือนระดับกลุ่มคำเรียงติดกัน (N-gram) (น้ำหนัก 30%%): %.1f%%\n" .
            "👉 ค่าความเหมือนเฉลี่ยรวม: %.1f%% (แปลงเป็นคะแนน %.1f/%g)",
            $keywordScore * 100,
            $similarPercent,
            $ngramScore * 100,
            $combinedScore * 100,
            $finalScore,
            $maxPoints
        );

        return [
            'score' => $finalScore,
            'isCorrect' => $finalScore >= ($maxPoints * 0.5),
            'reason' => $reason
        ];
    }
}
