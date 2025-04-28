<?php

namespace App\Controllers;

use App\Models\TipeProdukModel;

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

        // Validasi sederhana
        if (empty($name)) {
            return redirect()->back()->withInput()->with('error', 'Nama Produk wajib diisi.');
        }

        $model->insert([
            'name' => $name
        ]);

        return redirect()->to('/tipe-produk')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function delete($id)
    {
        $model = new \App\Models\TipeProdukModel();

        $product = $model->find($id);

        if (!$product) {
            return redirect()->to('/tipe-produk')->with('error', 'Produk tidak ditemukan.');
        }

        $model->delete($id);

        return redirect()->to('/tipe-produk')->with('success', 'Produk berhasil dihapus.');
    }

}
