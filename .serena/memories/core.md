# Online Exam System source map
- CodeIgniter 4 app at D:\SkjSystem\exam; MVC under app/.
- Student flow: registration -> lobby -> exam -> result; routes/API defined in app/Config/Routes.php.
- Teacher flow: /teacher -> dashboard; question bank, live monitor, results/logs, settings/manual are dashboard-driven with AdminController APIs.
- Main domain models: ExamModel (exams), QuestionModel (questions), StudentModel (students), ExamAttemptModel (exam_attempts), ExamResultModel (exam_results), SettingModel (settings), ErrorLogModel (error_logs).
- Key architecture details are in mem:tech_stack, mem:architecture/exam-flow, mem:architecture/database, and mem:architecture/scoring-security.
