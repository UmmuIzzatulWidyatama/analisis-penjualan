<?php

namespace App\Models;

use CodeIgniter\Model;

class TipeProdukModel extends Model
{
    protected $table = 'product_types'; // Nama tabel
    protected $primaryKey = 'id'; // Primary key
    protected $allowedFields = ['name']; // Kolom yang dapat diisi
}
