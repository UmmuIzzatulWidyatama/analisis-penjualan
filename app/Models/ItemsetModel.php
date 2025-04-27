<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemsetModel extends Model
{
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

    public function countTransactionWithItems($productA, $productB, $startDate, $endDate)
    {
        $db = \Config\Database::connect();

        $query = $db->query("
            SELECT COUNT(DISTINCT td1.transaction_id) AS count
            FROM transaction_details td1
            JOIN transaction_details td2 ON td1.transaction_id = td2.transaction_id
            JOIN transactions t ON td1.transaction_id = t.id
            WHERE td1.product_type_id = ? 
            AND td2.product_type_id = ?
            AND t.sale_date BETWEEN ? AND ?
        ", [$productA, $productB, $startDate, $endDate]);

        $result = $query->getRowArray();
        return $result['count'] ?? 0;
    }

    public function countTransactionWith3Items($productA, $productB, $productC, $startDate, $endDate)
    {
        $db = \Config\Database::connect();

        $query = $db->query("
            SELECT COUNT(DISTINCT td1.transaction_id) AS count
            FROM transaction_details td1
            JOIN transaction_details td2 ON td1.transaction_id = td2.transaction_id
            JOIN transaction_details td3 ON td1.transaction_id = td3.transaction_id
            JOIN transactions t ON td1.transaction_id = t.id
            WHERE td1.product_type_id = ? 
            AND td2.product_type_id = ?
            AND td3.product_type_id = ?
            AND t.sale_date BETWEEN ? AND ?
        ", [$productA, $productB, $productC, $startDate, $endDate]);

        $result = $query->getRowArray();
        return $result['count'] ?? 0;
    }



}
