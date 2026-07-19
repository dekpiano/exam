<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table            = 'students';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'exam_id', 'name', 'student_code', 'student_number', 'room', 'email', 'pos_x', 'pos_y', 'last_active', 'lobby_score'
    ];
}
