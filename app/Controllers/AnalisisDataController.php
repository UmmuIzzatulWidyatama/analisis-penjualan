<?php

namespace App\Controllers;

use App\Models\AnalisisDataModel;
use App\Models\RuleModel;

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
        session()->remove(['start_date', 'end_date', 'description']);
        return view('analisis-data', $data); // Kirim data ke view
    }

    public function add()
    {
        $session = session();
        $data = [
            'start_date'  => $session->get('start_date'),
            'end_date'    => $session->get('end_date'),
            'description' => $session->get('description')
        ];
        return view('analisis-data-add', $data); // Menampilkan form tambah data
    }

    public function save()
    {
    $startDate = $this->request->getPost('start_date');
    $endDate = $this->request->getPost('end_date');
    $description = $this->request->getPost('description');

    $ruleModel = new RuleModel();

    $supportRule = $ruleModel->where('name', 'Minimum Support')->first();
    $confidenceRule = $ruleModel->where('name', 'Minimum Confidence')->first();

    $minSupport = $supportRule ? (int)$supportRule['value'] : 30;
    $minConfidence = $confidenceRule ? (int)$confidenceRule['value'] : 60;

    $session = session();
    $model = new AnalisisDataModel();

    // Cek apakah sudah pernah simpan (pakai session analisis_id)
    if (!$session->get('analisis_id')) {
        $data = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'description' => $description,
            'minimum_support' => $minSupport,
            'minimum_confidence' => $minConfidence
        ];
    
        $inserted = $model->insert($data); 
    
        if (!$inserted) {
            log_message('error', 'Insert gagal: ' . json_encode($model->errors()));
            dd($model->errors());
        }
        
        $analisisId = $model->insertID(); 
        $session->set('analisis_id', $analisisId);
    }
    
    

    // Simpan info input ke session (boleh overwrite)
    $session->set([
        'start_date' => $startDate,
        'end_date' => $endDate,
        'description' => $description
    ]);
    
    // Redirect ke halaman itemset 1
    return redirect()->to('/analisis/itemset1');
    }

}
