# Scoring and anti-cheating
- Choice scoring compares selected answer text to questions.correct_answer case-insensitively and awards the question's FLOAT points.
- Writing answers are initially stored with no score and รอตรวจ; teacher can grade manually or via AI.
- Admin getResults contains an auto-repair/recalculation pass that syncs current question correct answers/points into stored answers JSON and recalculates choice scores.
- AI grading uses Gemini 2.0 Flash when a Gemini API key is configured; it requests a JSON score/explanation, caps score to 0..maxPoints, retries HTTP 429, and falls back to text similarity if unavailable/invalid/no key.
- Anti-cheating is server-backed: suspicious activity logs are counted from error_logs by student_email + exam_id. submitExam derives cheating_count from server logs rather than trusting client count.
- submitCheat can immediately create a zero-score result with cheating_flag=YES; registration blocks a student for that exam if any cheating result exists.
- Frontend anti-cheating behavior is implemented in student/exam.php (context-menu/copy/shortcut/focus/visibility controls); inspect that view before changing the client security flow.
