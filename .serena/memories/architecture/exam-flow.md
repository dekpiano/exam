# Exam lifecycle
- Student index loads all exams and settings, then renders student/register.
- Registration validates name, student number, room, email, exam ID and student code; checks exam status, join policy, prior cheating, max attempts and duplicate student code; writes/updates students and session state.
- Lobby requires session student email/exam ID and shows exam metadata; client polls exam status.
- startExam checks attempt count, loads questions for the exam, randomly selects up to num_questions choice questions, shuffles writing questions, shuffles choice options, then returns client-safe question data.
- Exam page redirects Waiting users to lobby and Finished users to index; optional whole-exam duration is calculated from exam.started_at + exam_duration.
- submitExam grades choice answers immediately, leaves writing answers as รอตรวจ, records detailed answers JSON and cheating count, and inserts exam_results.
- result page is driven from stored exam result data.
- ExamAttemptModel/migration exists to persist an attempt snapshot, but the current ExamController flow still primarily counts exam_results and startExam builds a fresh randomized set; inspect this before changing attempt/randomization behavior.
