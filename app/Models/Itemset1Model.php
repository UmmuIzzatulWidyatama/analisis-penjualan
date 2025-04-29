<?php

namespace App\Models;

use CodeIgniter\Model;

class Itemset1Model extends Model
{
    protected $table = 'itemset_1';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'analisis_data_id',
        'product_type_id',
        'support_count',
        'support_percent',
        'is_below_threshold'
    ];
}
