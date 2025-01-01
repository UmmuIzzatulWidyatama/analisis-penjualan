<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions'; // Nama tabel
    protected $primaryKey = 'id'; // Primary key
    protected $allowedFields = ['id', 'sale_date']; // Kolom yang dapat diakses
}
