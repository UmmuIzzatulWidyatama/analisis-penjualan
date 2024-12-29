<?php

namespace App\Controllers;

use App\Models\RuleModel;

class RuleController extends BaseController
{
    public function index()
    {
        $session = session();

        // Cek apakah pengguna sudah login
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $model = new RuleModel();
        $data['rules'] = $model->findAll(); // Ambil semua data dari tabel rules
        return view('rule', $data); // Kirim data ke view
    }
}
