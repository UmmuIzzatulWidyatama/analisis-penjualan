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
}
