<?php

namespace App\Models;

use CodeIgniter\Model;

class AnalisisDataModel extends Model
{
    protected $table = 'analisis_data';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'start_date',
        'end_date',
        'description',
        'minimum_support',
        'minimum_confidence',
        'transaction_count'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
