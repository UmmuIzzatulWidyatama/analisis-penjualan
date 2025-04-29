<?php

namespace App\Models;

use CodeIgniter\Model;

class Itemset3Model extends Model
{
    protected $table = 'itemset_3';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'analisis_data_id',
        'product_type_id_1',
        'product_type_id_2',
        'product_type_id_3',
        'support_count',
        'support_percent',
        'is_below_threshold'
    ];
}