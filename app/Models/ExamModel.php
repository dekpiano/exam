<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamModel extends Model
{
    protected $table            = 'exams';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id', 'subject_name', 'subject_code', 'learning_area', 'teacher_name', 'teacher_email',
        'academic_year', 'semester',
        'exam_type', 'exam_status', 'started_at', 'max_attempts', 'passing_percentage',
        'time_limit_choice', 'time_limit_writing', 'exam_duration', 'join_policy', 'anti_cheating', 'max_strikes', 'num_questions', 'exam_round', 'exam_mode', 'created_at'
    ];
}
