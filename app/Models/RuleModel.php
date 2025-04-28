<?php

namespace App\Models;

use CodeIgniter\Model;

class RuleModel extends Model
{
    protected $table = 'rules'; // Nama tabel
    protected $primaryKey = 'id'; // Primary key
    protected $allowedFields = ['name', 'value']; // Kolom yang dapat diisi
} 