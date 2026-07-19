<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamResultModel extends Model
{
    protected $table            = 'exam_results';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'exam_id', 'email', 'name', 'student_code', 'student_number', 'room', 'exam_type', 'academic_year', 'semester', 'score',
        'total_questions', 'total_time_spent', 'attempt_number',
        'cheating_flag', 'cheating_count', 'cheating_reason', 'answers_json', 'exam_round', 'submitted_at'
    ];
}
