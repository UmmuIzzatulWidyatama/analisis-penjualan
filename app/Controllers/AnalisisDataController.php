<?php

namespace App\Controllers;

use App\Models\AnalisisDataModel;
use App\Models\RuleModel;
use App\Models\ItemsetModel;

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
        
        // Hitung jumlah hari analisis
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $interval = $start->diff($end);
        $totalTransaksi = $interval->days + 1;

        $ruleModel = new RuleModel();
        $supportRule = $ruleModel->where('name', 'Minimum Support')->first();
        $confidenceRule = $ruleModel->where('name', 'Minimum Confidence')->first();
        $minSupport = $supportRule ? (int)$supportRule['value'] : 10;
        $minConfidence = $confidenceRule ? (int)$confidenceRule['value'] : 10;

        $session = session();
        $model = new AnalisisDataModel();

        // Cek apakah sudah pernah simpan (pakai session analisis_id)
        if (!$session->get('analisis_id')) {
            $data = [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'description' => $description,
                'minimum_support' => $minSupport,
                'minimum_confidence' => $minConfidence,
                'transaction_count' => $totalTransaksi
            ];

            $model->insert($data);
            $errors = $model->errors();

            if (!empty($errors)) {
                dd([
                    'insert_error' => $errors,
                    'data' => $data
                ]);
            }

            $analisisId = $model->insertID();
            $session->set('analisis_id', $analisisId);
        } else {
            $analisisId = $session->get('analisis_id');
            
            // Pastikan ID yang disimpan sebelumnya masih valid di database
            $existing = $model->find($analisisId);
            if (!$existing) {
                // Insert ulang karena data tidak ada
                $analisisData = [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'description' => $description,
                    'minimum_support' => $minSupport,
                    'minimum_confidence' => $minConfidence,
                    'transaction_count' => $totalTransaksi
                ];
        
                $model->insert($analisisData);
                $analisisId = $model->insertID();
                $session->set('analisis_id', $analisisId);
            }
        }

        // Simpan info input ke session (boleh overwrite)
        $session->set([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'description' => $description
        ]);

        
        $itemsetModel = new \App\Models\ItemsetModel();
        $itemset1Model = new \App\Models\Itemset1Model();

        $itemFrequencies = $itemsetModel->getItemFrequency($startDate, $endDate);

        $itemsetData = [];

        foreach ($itemFrequencies as $item) {
            $supportPercent = round(($item['frequency'] / $totalTransaksi) * 100, 0);

            $itemsetData[] = [
                'analisis_data_id' => $analisisId,
                'product_type_id' => $item['product_type_id'],
                'support_count' => $item['frequency'],
                'support_percent' => $supportPercent,
                'is_below_threshold' => $supportPercent < $minSupport ? 1 : 0
            ];
        }
        $itemset2Model = new \App\Models\Itemset2Model();

        // Hapus data sebelumnya agar tidak duplikat
        $itemset1Model->where('analisis_data_id', $analisisId)->delete();
        $itemset2Model->where('analisis_data_id', $analisisId)->delete();
        
        if (!empty($itemsetData)) {
            $itemset1Model->insertBatch($itemsetData);
        }

        // Ambil semua itemset 1 yang lolos minimum support
        $filteredItemset1 = array_filter($itemFrequencies, function ($item) use ($minSupport, $totalTransaksi) {
            $support = ($item['frequency'] / $totalTransaksi) * 100;
            return $support >= $minSupport;
        });
        
        // Ambil kombinasi 2 item dari itemset1 yang lolos
        $itemCombinations = [];
        $itemIds = array_column($filteredItemset1, 'product_type_id');
        
        for ($i = 0; $i < count($itemIds); $i++) {
            for ($j = $i + 1; $j < count($itemIds); $j++) {
                $itemCombinations[] = [$itemIds[$i], $itemIds[$j]];
            }
        }
        
        // Hitung frekuensi kombinasi item 
        $itemset2Data = [];
        foreach ($itemCombinations as $pair) {
            $count = $itemsetModel->countTransactionWithItems($pair[0], $pair[1], $startDate, $endDate);
            $supportPercent = round($count / $totalTransaksi * 100, 2);
        
            $itemset2Data[] = [
                'analisis_data_id' => $analisisId,
                'product_type_id_1' => $pair[0],
                'product_type_id_2' => $pair[1],
                'support_count' => $count,
                'support_percent' => $supportPercent,
                'is_below_threshold' => $supportPercent < $minSupport ? 1 : 0
            ];
        }

        if (!empty($itemset2Data)) {
            $itemset2Model->insertBatch($itemset2Data);
        }
        
         // Redirect ke halaman itemset 1
         return redirect()->to('/analisis-data/itemset1');

    }
}
