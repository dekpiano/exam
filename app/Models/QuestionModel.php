<?php

namespace App\Models;

use CodeIgniter\Model;

class QuestionModel extends Model
{
    protected $table            = 'questions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id', 'exam_id', 'question_text', 'option_a', 'option_b', 'option_c', 'option_d',
        'correct_answer', 'type', 'points', 'image_url'
    ];
}
