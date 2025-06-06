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

    public function showUploadBulk()
    {
        return view('transaksi-upload-bulk', ['results' => []]);
    }

    public function downloadTemplate()
    {
        return $this->response->download(WRITEPATH . 'uploads/template-upload-transaksi.xlsx', null);
    }

    public function uploadBulk()
    {
        $file = $this->request->getFile('file');
        $results = [];

        if ($file && $file->isValid()) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
            $productModel = new \App\Models\TipeProdukModel();
            $transactionModel = new \App\Models\TransactionModel();
            $transactionDetailModel = new \App\Models\TransactionDetailModel();

            $existingCombinations = [];
            $fileCombinations = [];

            // Ambil kombinasi transaksi+produk yang sudah ada di database
            foreach ($transactionModel->findAll() as $tx) {
                $details = $transactionDetailModel->where('transaction_id', $tx['id'])->findAll();
                foreach ($details as $detail) {
                    $product = $productModel->find($detail['product_type_id']);
                    if ($product) {
                        $key = $tx['sale_date'] . '|' . $product['name'];
                        $existingCombinations[$key] = true;
                    }
                }
            }

            foreach ($rows as $index => $row) {
                if ($index === 0) continue; // skip header

                $rowNumber = $row[0];
                $productName = trim($row[2]);
                $isValid = true;
                $statusMessage = 'Siap disimpan';
                $saleDateFormatted = '';

                $cell = $sheet->getCellByColumnAndRow(2, $index + 1); // kolom B
                $dataType = $cell->getDataType();

                if ($dataType === \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC) {
                    $phpDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($cell->getValue());
                    $saleDateFormatted = $phpDate->format('Y-m-d');
                } else {
                    $saleDateRaw = trim($cell->getFormattedValue());
                    $parsedDate = \DateTime::createFromFormat('d/m/Y', $saleDateRaw);
                    if ($parsedDate) {
                        $saleDateFormatted = $parsedDate->format('Y-m-d');
                    } else {
                        $isValid = false;
                        $statusMessage = 'Format tanggal tidak valid';
                    }
                }

                // Check tanggal minimal & maksimal
                if ($isValid) {
                    $dateCheck = new \DateTime($saleDateFormatted);
                    $minDate = new \DateTime('2000-01-01');
                    $maxDate = new \DateTime(); // current date
                    if ($dateCheck < $minDate || $dateCheck > $maxDate) {
                        $isValid = false;
                        $statusMessage = 'Tanggal penjualan tidak valid';
                    }
                }

                // Check produk aktif
                $product = $productModel->where('name', $productName)->first();
                if (!$product) {
                    $isValid = false;
                    $statusMessage = 'Nama produk tidak ditemukan';
                } elseif (isset($product['is_active']) && !$product['is_active']) {
                    $isValid = false;
                    $statusMessage = 'Produk tidak aktif';
                }

                // Check duplikat di file
                $fileKey = $saleDateFormatted . '|' . $productName;
                if (isset($fileCombinations[$fileKey])) {
                    $isValid = false;
                    $statusMessage = 'Duplikat di file upload';
                } else {
                    $fileCombinations[$fileKey] = true;
                }

                // Check duplikat di database
                if (isset($existingCombinations[$fileKey])) {
                    $isValid = false;
                    $statusMessage = 'Data sudah ada di database';
                }

                $results[] = [
                    'row'            => $rowNumber,
                    'sale_date'      => $saleDateFormatted,
                    'product_name'   => $productName,
                    'product_type_id'=> $product['id'] ?? null,
                    'status'         => $statusMessage,
                    'is_valid'       => $isValid,
                ];
            }

            session()->set('bulk_transaksi_data', $results);
        }

        return view('transaksi-upload-bulk', ['results' => $results]);
    }

    
    public function saveBulk()
    {
        $db = \Config\Database::connect();
        $transactionModel = new \App\Models\TransactionModel();
        $transactionDetailModel = new \App\Models\TransactionDetailModel();
        $productModel = new \App\Models\TipeProdukModel();

        $sale_dates = $this->request->getPost('sale_dates');  // array of dates
        $product_names = $this->request->getPost('product_names');  // array of product names

        if (empty($sale_dates) || empty($product_names)) {
            return redirect()->back()->with('error', 'Tidak ada data valid untuk disimpan.');
        }

        $db->transStart();

        foreach ($sale_dates as $index => $sale_date) {
            $product_name = $product_names[$index];

            // Cari ID produk dari nama
            $product = $productModel->where('name', $product_name)->first();
            if (!$product) continue; // skip jika produk tidak ditemukan

            // Simpan transaksi utama
            $transactionModel->insert(['sale_date' => $sale_date]);
            $transaction_id = $transactionModel->getInsertID();

            // Simpan detail produk
            $transactionDetailModel->insert([
                'transaction_id' => $transaction_id,
                'product_type_id' => $product['id']
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal menyimpan data bulk.');
        }

        return redirect()->to('/transaksi')->with('success', 'Data bulk transaksi berhasil disimpan.');
    }

}
