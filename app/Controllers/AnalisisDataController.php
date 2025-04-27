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
        session()->remove(['analisis_id','start_date', 'end_date', 'description']);
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
        $itemset2Model = new \App\Models\Itemset2Model();
        $itemset3Model = new \App\Models\Itemset3Model();
        $associationModel = new \App\Models\AssociationRuleModel();

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
        
        // Hapus data sebelumnya agar tidak duplikat
        $itemset1Model->where('analisis_data_id', $analisisId)->delete();
        $itemset2Model->where('analisis_data_id', $analisisId)->delete();
        $itemset3Model->where('analisis_data_id', $analisisId)->delete();
        $associationModel->where('analisis_data_id', $analisisId)->delete();
        
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
        
        // Ambil itemset 2 yang lolos minimum support
        $filteredItemset2 = array_filter($itemset2Data, function ($item) use ($minSupport) {
            return $item['support_percent'] >= $minSupport;
        });

        $validItemIds = [];

        foreach ($filteredItemset2 as $itemset2) {
            $validItemIds[] = $itemset2['product_type_id_1'];
            $validItemIds[] = $itemset2['product_type_id_2'];
        }

        $validItemIds = array_unique($validItemIds);

        // Cek apakah cukup item untuk kombinasi 3
        if (count($validItemIds) < 3) {
            // Bisa pakai log, flashdata, atau dd() untuk info dev
            session()->setFlashdata('info', 'Tidak mungkin buat kombinasi 3, skip proses itemset 3.');

            // Skip proses itemset 3, lanjut redirect
            return redirect()->to('/analisis-data/itemset1');
        }

        // Buat kombinasi 3 item dari filtered itemset 1
        $itemset3Combinations = [];
        $validItemIds = array_values($validItemIds); // reset index array

        for ($i = 0; $i < count($validItemIds); $i++) {
            for ($j = $i + 1; $j < count($validItemIds); $j++) {
                for ($k = $j + 1; $k < count($validItemIds); $k++) {
                    $itemset3Combinations[] = [$validItemIds[$i], $validItemIds[$j], $validItemIds[$k]];
                }
            }
        }

        // Hitung frekuensi kombinasi itemset 3
        $itemset3Data = [];
        foreach ($itemset3Combinations as $trio) {
            $count = $itemsetModel->countTransactionWith3Items($trio[0], $trio[1], $trio[2], $startDate, $endDate);
            $supportPercent = round($count / $totalTransaksi * 100, 2);

            $itemset3Data[] = [
                'analisis_data_id' => $analisisId,
                'product_type_id_1' => $trio[0],
                'product_type_id_2' => $trio[1],
                'product_type_id_3' => $trio[2],
                'support_count' => $count,
                'support_percent' => $supportPercent,
                'is_below_threshold' => $supportPercent < $minSupport ? 1 : 0
            ];
        }

        if (!empty($itemset3Data)) {
            $itemset3Model->insertBatch($itemset3Data);
        }

        //Perhitungan Asosiasi Itemset 2
        $associationData = [];

        foreach ($filteredItemset2 as $itemset2) {
            // Support A U B
            $supportAB = $itemset2['support_percent'];

            // Ambil support A dari itemset1
            $supportA = 0;
            foreach ($itemsetData as $item1) {
                if ($item1['product_type_id'] == $itemset2['product_type_id_1']) {
                    $supportA = $item1['support_percent'];
                    break;
                }
            }

            // Confidence A -> B
            $confidenceAB = $supportA > 0 ? round($supportAB / $supportA * 100, 2) : 0;

            $associationData[] = [
                'analisis_data_id' => $analisisId,
                'from_item' => $itemset2['product_type_id_1'],
                'to_item' => $itemset2['product_type_id_2'],
                'support_percent' => $supportAB,
                'confidence_percent' => $confidenceAB,
                'is_below_confidence_threshold' => $confidenceAB < $minConfidence ? 1 : 0
            ];

            // Confidence B -> A
            $supportB = 0;
            foreach ($itemsetData as $item1) {
                if ($item1['product_type_id'] == $itemset2['product_type_id_2']) {
                    $supportB = $item1['support_percent'];
                    break;
                }
            }

            $confidenceBA = $supportB > 0 ? round($supportAB / $supportB * 100, 2) : 0;

            $associationData[] = [
                'analisis_data_id' => $analisisId,
                'from_item' => $itemset2['product_type_id_2'],
                'to_item' => $itemset2['product_type_id_1'],
                'support_percent' => $supportAB,
                'confidence_percent' => $confidenceBA,
                'is_below_confidence_threshold' => $confidenceBA < $minConfidence ? 1 : 0
            ];
        }

        if (!empty($associationData)) {
            $associationModel->insertBatch($associationData);
        }

         // Redirect ke halaman itemset 1
         return redirect()->to('/analisis-data/itemset1');

    }
}
