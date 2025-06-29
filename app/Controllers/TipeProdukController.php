<?php

namespace App\Controllers;

use App\Models\TipeProdukModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class TipeProdukController extends BaseController
{
    public function index()
    {
        $session = session();

        // Cek apakah pengguna sudah login
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Anda harus login terlebih dahulu.');
        } 

        $model = new TipeProdukModel();
        $data['product_types'] = $model->findAll(); // Ambil semua data dari tabel product_types
        return view('tipe-produk', $data); // Kirim data ke view
    } 

    public function add()
    {
        $session = session();

        // Cek apakah pengguna sudah login
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Anda harus login terlebih dahulu.');
        } 

        return view('tambah-tipe-produk'); // Menampilkan form tambah data
    }
    
    public function save()
    {
        $model = new \App\Models\TipeProdukModel();

        $name = $this->request->getPost('name');
        $kodeItem = $this->request->getPost('kode_item');

        // Validasi sederhana
        if (empty($name)) {
            return redirect()->back()->withInput()->with('error', 'Nama Produk wajib diisi.');
        }

         // Validasi nama unik
        $existing = $model->where('name', $name)->first();
        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'Nama Produk sudah ada, gunakan nama lain.');
        }

        $model->insert([
            'name' => $name,
            'kode_item' => $kodeItem,
        ]);

        return redirect()->to('/tipe-produk')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function delete($id)
    {
        $model = new \App\Models\TipeProdukModel();
        $db = \Config\Database::connect();

        // 1. Cek apakah data produk ada
        $product = $model->find($id);
        if (!$product) {
            return redirect()->to('/tipe-produk')->with('error', 'Produk tidak ditemukan.');
        }

        // 2. Cek apakah produk sedang digunakan di tabel lain (misalnya itemset_1)
        $isUsed = $db->table('itemset_1')->where('product_type_id', $id)->countAllResults();

        if ($isUsed > 0) {
            return redirect()->to('/tipe-produk')->with('error', 'Produk tidak bisa dihapus karena sudah digunakan dalam data lain.');
        }

        // 3. Hapus data jika aman
        $model->delete($id);
        return redirect()->to('/tipe-produk')->with('success', 'Produk berhasil dihapus.');
    }

    public function edit($id)
    {
        $model = new \App\Models\TipeProdukModel();
        $product = $model->find($id);

        if (!$product) {
            return redirect()->to('/tipe-produk')->with('error', 'Produk tidak ditemukan.');
        }

        return view('edit-tipe-produk', ['product' => $product]);
    }

    public function update($id)
    {
        $model = new \App\Models\TipeProdukModel();

        $name = $this->request->getPost('name');
        $kodeItem = $this->request->getPost('kode_item');

        if (empty($name)) {
            return redirect()->back()->withInput()->with('error', 'Nama Produk wajib diisi.');
        }

        // Validasi nama unik kecuali data yang sedang diedit
        $existing = $model->where('name', $name)->where('id !=', $id)->first();
        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'Nama Produk sudah ada, gunakan nama lain.');
        }

        $model->update($id, [
            'name' => $name,
            'kode_item' => $kodeItem
        ]);

        return redirect()->to('/tipe-produk')->with('success', 'Produk berhasil diperbarui.');
    }

    public function showUploadBulk()
    {
        return view('tipe-produk-upload-bulk');
    }

    public function uploadBulk()
    {
        $file = $this->request->getFile('file');
        $results = [];

        if ($file && $file->isValid()) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet()->toArray();
            $model = new TipeProdukModel();

            $kodeItemInFile = [];

            foreach ($sheet as $index => $row) {
                if ($index === 0) continue; // Skip header
                if (empty(array_filter($row))) continue;

                $kodeItem = trim($row[0] ?? '');
                $name     = trim($row[1] ?? '');
                $status   = 'Siap disimpan';

                if (empty($kodeItem)) {
                    $status = 'Kode item tidak boleh kosong';
                } elseif (in_array($kodeItem, $kodeItemInFile)) {
                    $status = 'Kode item duplikat di file';
                } else {
                    $exists = $model
                        ->groupStart()
                            ->where('name', $name)
                            ->orWhere('kode_item', $kodeItem)
                        ->groupEnd()
                        ->first();

                    if ($exists) {
                        $status = 'Data sudah ada di database';
                    }
                }

                $kodeItemInFile[] = $kodeItem;

                $results[] = [
                    'row'       => $index + 1,
                    'kode_item' => $kodeItem,
                    'name'      => $name,
                    'status'    => $status,
                ];
            }
        }

        return view('tipe-produk-upload-bulk', ['results' => $results]);
    }

    public function saveBulk()
    {
        $names = $this->request->getPost('names');
        $kode_items = $this->request->getPost('kode_items');
        $model = new TipeProdukModel();

        foreach ($names as $i => $name) {
            $kode_item = $kode_items[$i];

            if (!empty($name) && !empty($kode_item)) {
                $model->insert([
                    'name' => $name,
                    'kode_item' => $kode_item
                ]);
            }
        }

        return redirect()->to('/tipe-produk')->with('message', 'Berhasil menyimpan data!');
    }

    public function downloadTemplate()
    {
        return $this->response->download(WRITEPATH . 'uploads/template-upload-produk.xlsx', null);
    }

}
