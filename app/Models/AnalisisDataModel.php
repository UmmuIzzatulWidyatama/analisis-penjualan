<?php

namespace App\Models;

use CodeIgniter\Model;

class AnalisisDataModel extends Model
{
    protected $table = 'analisis_data'; // Nama tabel
    protected $primaryKey = 'id'; // Primary key
    protected $allowedFields = ['id','start_date','end_date','description']; // Kolom yang dapat diisi
}
