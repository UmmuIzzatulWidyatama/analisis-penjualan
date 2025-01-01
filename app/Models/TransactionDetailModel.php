<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionDetailModel extends Model
{
    protected $table = 'transaction_details'; // Nama tabel
    protected $primaryKey = 'id'; // Primary key
    protected $allowedFields = ['id', 'transaction_id', 'product_type_id']; // Kolom yang dapat diakses
}
