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

    public function add()
    {
        $session = session();

        // Cek apakah pengguna sudah login
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $productModel = new \App\Models\TipeProdukModel();
        $data['products'] = $productModel->findAll();

        return view('add-transaksi', $data);
    }

    public function save()
    {
        $db = \Config\Database::connect();
        $transactionModel = new \App\Models\TransactionModel();

        $sale_date = $this->request->getPost('sale_date');
        $product_type_ids = $this->request->getPost('product_type_ids'); // array of product_type_id

        // Validasi input
        if (empty($sale_date) || empty($product_type_ids)) {
            return redirect()->back()->withInput()->with('error', 'Tanggal dan produk harus diisi.');
        }

        $db->transStart();

        // Simpan transaksi utama
        $transactionModel->insert(['sale_date' => $sale_date]);
        $transaction_id = $transactionModel->getInsertID();

        // Simpan detail produk
        foreach ($product_type_ids as $product_type_id) {
            $db->table('transaction_details')->insert([
                'transaction_id' => $transaction_id,
                'product_type_id' => $product_type_id
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan transaksi.');
        }

        return redirect()->to('/transaksi')->with('success', 'Transaksi berhasil disimpan.');
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();
        $transactionModel = new \App\Models\TransactionModel();

        // Cek apakah data transaksi ada
        $transaction = $transactionModel->find($id);
        if (!$transaction) {
            return redirect()->to('/transaksi')->with('error', 'Data transaksi tidak ditemukan.');
        }

        // Hapus data dari tabel detail transaksi terlebih dahulu
        $db->table('transaction_details')->where('transaction_id', $id)->delete();

        // Hapus transaksi utama
        $transactionModel->delete($id);

        return redirect()->to('/transaksi')->with('success', 'Data transaksi berhasil dihapus.');
    }

    public function detail($id)
    {
        $transactionModel = new \App\Models\TransactionModel();
        $detailModel = new \App\Models\TransactionDetailModel();

        $transaction = $transactionModel->find($id);
        if (!$transaction) {
            return redirect()->to('/transaksi')->with('error', 'Transaksi tidak ditemukan.');
        }

        // Join untuk ambil nama produk dari product_types
        $details = $detailModel
            ->where('transaction_id', $id)
            ->join('product_types', 'product_types.id = transaction_details.product_type_id')
            ->select('transaction_details.*, product_types.name as product_name')
            ->findAll();

        return view('detail-transaksi', [
            'transaction' => $transaction,
            'details'     => $details,
        ]);
    }


}
