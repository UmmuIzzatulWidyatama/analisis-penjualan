<?php

namespace App\Models;

use CodeIgniter\Model;

class AssociationRuleModel extends Model
{
    protected $table = 'association_rules';
    protected $allowedFields = [
        'analisis_data_id',
        'from_item',
        'from_item_2',
        'to_item',
        'support_percent',
        'confidence_percent',
        'is_below_confidence_threshold'
    ];
}