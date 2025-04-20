<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemsetModel extends Model
{

    public function getItemFrequency($startDate, $endDate)
    {
        return $this->db->table('transaction_details td')
            ->select('td.product_type_id, COUNT(*) as frequency')
            ->join('transactions t', 't.id = td.transaction_id')
            ->where('t.sale_date >=', $startDate)
            ->where('t.sale_date <=', $endDate)
            ->groupBy('td.product_type_id')
            ->get()
            ->getResultArray();
    }

    public function getItemset1()
    {
        $builder = $this->db->table('transaction_details td');
        $builder->select('pt.name AS item_name, COUNT(td.product_type_id) AS frequency, 
                          ROUND(COUNT(td.product_type_id) * 100.0 / total.total_transactions, 2) AS support', false);
        $builder->join('product_types pt', 'pt.id = td.product_type_id');
        $builder->join(
            '(SELECT COUNT(DISTINCT transaction_id) AS total_transactions FROM transaction_details) total',
            '1 = 1',
            'CROSS'
        );

        $builder->groupBy('pt.name, total.total_transactions');
        $builder->orderBy('frequency', 'DESC');

        return $builder->get()->getResultArray();
        
    }

    public function getMinimumSupport()
    {
        $row = $this->db->table('rules')
                    ->where('name', 'Minimum Support')
                    ->get()
                    ->getRowArray();

        if (!$row) {
            // Bisa kasih default value atau bahkan lempar exception/log
            return 0;
        }

        return $row['value'];
    }


}
