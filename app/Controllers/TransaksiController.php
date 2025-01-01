<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\TipeProdukModel;

class TransaksiController extends BaseController
{
    public function index()
    {
        $session = session();

        // Cek apakah pengguna sudah login
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $transactionModel = new TransactionModel();
        $detailModel = new TransactionDetailModel();
        $productTypeModel = new TipeProdukModel();

        // Ambil semua data transaksi
        $transactions = $transactionModel->findAll();

        $data = [];
        foreach ($transactions as $transaction) {
            // Ambil detail transaksi berdasarkan transaction_id
            $details = $detailModel->where('transaction_id', $transaction['id'])->findAll();
            
            // Ambil nama produk berdasarkan product_type_id
            $productNames = [];
            foreach ($details as $detail) {
                $product = $productTypeModel->find($detail['product_type_id']);
                if ($product) {
                    $productNames[] = $product['name'];
                }
            }

            // Gabungkan data transaksi dan daftar produk
            $data[] = [
                'id' => $transaction['id'],
                'sale_date' => $transaction['sale_date'],
                'products' => implode(', ', $productNames), // Gabungkan nama produk dengan koma
            ];
        }

        return view('transaksi', ['transactions' => $data]); // Kirim data ke view
    }
}
