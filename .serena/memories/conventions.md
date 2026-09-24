# Project conventions
- CodeIgniter MVC; controllers own much of the application logic and API responses.
- UUID-like string IDs are generated for exams/questions/attempts; student/result IDs are auto-increment integers.
- Model fields mirror database columns and use $allowedFields.
- Exam statuses are Waiting, Started, Paused, Finished; join policies include anytime and lobby_first; exam modes include classic and pokemon.
- Question types are choice and writing.
- Scores/points are intentionally FLOAT; AdminController performs schema repair to convert question points and result score/total_questions to FLOAT.
- Keep Thai user-facing text in views/API messages; JSON responses generally use {success, message, ...}.
