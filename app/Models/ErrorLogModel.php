<?php

namespace App\Models;

use CodeIgniter\Model;

class ErrorLogModel extends Model
{
    protected $table            = 'error_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'timestamp', 'error_type', 'error_message', 'student_email', 'user_agent', 'screen_resolution', 'exam_id'
    ];
}
