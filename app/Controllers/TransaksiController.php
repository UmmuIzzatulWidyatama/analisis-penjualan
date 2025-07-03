<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\TipeProdukModel;

use PhpOffice\PhpSpreadsheet\IOFactory;

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
                'id'              => $transaction['id'],
                'sale_date'       => $transaction['sale_date'],
                'nomor_transaksi' => $transaction['nomor_transaksi'], // tambahkan ini
                'products'        => implode(', ', $productNames),
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
        $nomor_transaksi = $this->request->getPost('nomor_transaksi');
        $product_type_ids = $this->request->getPost('product_type_ids'); // array of product_type_id

        // Validasi input
        if (empty($sale_date) || empty($product_type_ids)) {
            return redirect()->back()->withInput()->with('error', 'Tanggal dan produk harus diisi.');
        }

        $db->transStart();

        // Simpan transaksi utama
        $transactionModel->insert([
            'sale_date' => $sale_date,
            'nomor_transaksi' => $nomor_transaksi
        ]);

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

    public function showUploadBulk()
    {
        return view('transaksi-upload-bulk', ['results' => []]);
    }

    public function uploadBulk()
    {
        if (ob_get_length()) {
            ob_clean(); // clear all output buffer
        }
        $file = $this->request->getFile('file');
        $results = [];

        if ($file && $file->isValid()) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
            $productModel = new \App\Models\TipeProdukModel();
            $transactionModel = new \App\Models\TransactionModel();

            $existingCombinations = [];
            foreach ($transactionModel->findAll() as $tx) {
                if (!empty($tx['nomor_transaksi'])) {
                    $existingCombinations[$tx['nomor_transaksi']] = true;
                }
            }

            foreach ($rows as $index => $row) {
                if ($index === 0) continue; // skip header
                if (empty($row[0]) && empty($row[1]) && empty($row[2])) continue; // skip empty rows

                $nomorTransaksi = trim($row[0]); // A - Nomor Transaksi
                $tanggalCell    = $sheet->getCellByColumnAndRow(2, $index + 1); // B - Tanggal Transaksi
                $kodeCell       = $sheet->getCellByColumnAndRow(3, $index + 1); // C - Kode Item

                $kodeItem = trim((string) $kodeCell->getFormattedValue());
                $kodeItem = str_replace(',', '', $kodeItem); // handle koma jika ada

                $isValid        = true;
                $statusMessage  = 'Siap disimpan';
                $saleDateFormatted = '';

                // Format Tanggal
                $saleDateRaw = trim($tanggalCell->getFormattedValue());
                $parsedDate = \DateTime::createFromFormat('d/m/Y', $saleDateRaw);
                if (!$parsedDate) {
                    $parsedDate = \DateTime::createFromFormat('Y-m-d', $saleDateRaw);
                }
                if ($parsedDate) {
                    $saleDateFormatted = $parsedDate->format('Y-m-d');
                } else {
                    $isValid = false;
                    $statusMessage = 'Format tanggal tidak valid';
                }

                // Validasi kosong
                if (empty($nomorTransaksi)) {
                    $isValid = false;
                    $statusMessage = 'Nomor transaksi kosong';
                }

                // Validasi duplikat
                if (isset($existingCombinations[$nomorTransaksi])) {
                    $isValid = false;
                    $statusMessage = 'Nomor transaksi sudah ada di database';
                }

                // Validasi kode item
                $product = $productModel->where('kode_item', $kodeItem)->first();
                if (!$product) {
                    $isValid = false;
                    $statusMessage = 'Kode item tidak ditemukan';
                }

                $results[] = [
                    'nomor_transaksi' => $nomorTransaksi,
                    'sale_date'       => $saleDateFormatted,
                    'kode_item'       => $kodeItem,
                    'status'          => $statusMessage,
                    'is_valid'        => $isValid,
                ];
            }

            session()->set('bulk_transaksi_data', $results);
        }

        return view('transaksi-upload-bulk', ['results' => $results]);
    }

    public function saveBulk()
    {
        if (ob_get_length()) {
            ob_clean(); // clear all output buffer
        }
        $db = \Config\Database::connect();
        $transactionModel = new \App\Models\TransactionModel();
        $transactionDetailModel = new \App\Models\TransactionDetailModel();
        $productModel = new \App\Models\TipeProdukModel();

        // Ambil dari session, bukan dari POST
        $bulkData = session()->get('bulk_transaksi_data');

        if (!$bulkData || count($bulkData) === 0) {
            return redirect()->back()->with('error', 'Tidak ada data yang tersedia untuk disimpan.');
        }

        $db->transStart();
        $transaksiMap = [];

        foreach ($bulkData as $row) {
            // Hanya proses data yang valid
            if (empty($row['is_valid']) || $row['is_valid'] === false) {
                continue;
            }

            $nomor_transaksi = $row['nomor_transaksi'];
            $sale_date = $row['sale_date'];
            $kode_item = $row['kode_item'];

            $product = $productModel->where('kode_item', $kode_item)->first();
            if (!$product) {
                log_message('error', "Kode item tidak ditemukan saat simpan: " . $kode_item);
                continue;
            }

            if (!isset($transaksiMap[$nomor_transaksi])) {
                $transactionModel->insert([
                    'sale_date' => $sale_date,
                    'nomor_transaksi' => $nomor_transaksi,
                ]);
                $transaksiMap[$nomor_transaksi] = $transactionModel->getInsertID();
            }

            $transactionDetailModel->insert([
                'transaction_id' => $transaksiMap[$nomor_transaksi],
                'product_type_id' => $product['id'],
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menyimpan data bulk.');
        }

        session()->remove('bulk_transaksi_data'); // bersihkan setelah simpan
        return redirect()->to('/transaksi')->with('success', 'Data transaksi berhasil disimpan.');
    }


}
