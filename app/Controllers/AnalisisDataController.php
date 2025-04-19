<?php

namespace App\Controllers;

use App\Models\AnalisisDataModel;

class AnalisisDataController extends BaseController
{
    public function index()
    {
        $session = session();

        // Cek apakah pengguna sudah login
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $model = new AnalisisDataModel();
        $data['analisis_data'] = $model->findAll(); // Ambil semua data dari tabel rules
        return view('analisis-data', $data); // Kirim data ke view
    }

    public function add()
    {
        return view('analisis-data-add'); // Menampilkan form tambah data
    }

    // public function save()
    // {
    //     $model = new AnalisisDataModel();

    //     // Validasi input
    //     $validation = $this->validate([
    //         'start_date' => 'required|valid_date',
    //         'end_date' => 'required|valid_date',
    //         'description' => 'required|min_length[3]',
    //     ]);

    //     if (!$validation) {
    //         return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    //     }

    //     // Simpan data ke database
    //     $model->save([
    //         'start_date' => $this->request->getPost('start_date'),
    //         'end_date' => $this->request->getPost('end_date'),
    //         'description' => $this->request->getPost('description'),
    //     ]);

    //     return redirect()->to('/analisis-data')->with('success', 'Data berhasil ditambahkan.');
    // }

    public function save()
    {
    $startDate = $this->request->getPost('start_date');
    $endDate = $this->request->getPost('end_date');
    $description = $this->request->getPost('description');

    session()->set([
        'start_date' => $startDate,
        'end_date' => $endDate,
        'description' => $description
    ]);
    
    // Redirect ke halaman itemset 1
    return redirect()->to('/analisis/itemset1');
    }

}
