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

    public function detail($id)
    {
        $session = session();

        // Cek apakah pengguna sudah login
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $model = new RuleModel();
        $data['rule'] = $model->find($id); // Ambil detail data dari tabel rules

        if (!$data['rule']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Rule dengan ID $id tidak ditemukan.");
        }
        return view('ubah-rule', $data); // Kirim data ke view
    }

    public function update($id)
    {
        $model = new RuleModel();

        $minimumValue = $this->request->getPost('minimum_value');

        // Validasi sederhana
        if (!is_numeric($minimumValue) || $minimumValue < 0 || $minimumValue > 100) {
            return redirect()->back()->withInput()->with('error', 'Minimum value harus antara 0 - 100');
        }

        // Update hanya field 'value' karena itu yang ada di database
        $model->update($id, [
            'value' => $minimumValue
        ]);

        return redirect()->to('/rule')->with('success', 'Rule berhasil diperbarui');
    }

}
