<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamAttemptModel extends Model
{
    protected $table            = 'exam_attempts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id', 'exam_id', 'student_email', 'exam_round', 'status',
        'questions_json', 'started_at', 'submitted_at', 'updated_at'
    ];
}
