<?php

namespace App\Controllers;

use App\Models\AnalisisDataModel;
use App\Models\AssociationRuleModel;
use App\Models\RuleModel;
use App\Models\Itemset1Model;
use App\Models\Itemset2Model;
use App\Models\Itemset3Model;
use App\Models\TipeProdukModel;
use CodeIgniter\Controller;


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

    public function delete($id)
    {
        $analisisModel = new AnalisisDataModel();
        $itemset1Model = new Itemset1Model();
        $itemset2Model = new Itemset2Model();
        $itemset3Model = new Itemset3Model();
        $associationRuleModel = new AssociationRuleModel();

        $analisis = $analisisModel->find($id);

        if (!$analisis) {
            return redirect()->to('/analisis-data')->with('error', 'Data analisis tidak ditemukan.');
        }

        // Hapus data anak terlebih dahulu
        $itemset1Model->where('analisis_data_id', $id)->delete();
        $itemset2Model->where('analisis_data_id', $id)->delete();
        $itemset3Model->where('analisis_data_id', $id)->delete();
        $associationRuleModel->where('analisis_data_id', $id)->delete();

        // Setelah anak terhapus, hapus parent
        $analisisModel->delete($id);

        return redirect()->to('/analisis-data')->with('success', 'Data analisis berhasil dihapus.');
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

        // Ambil itemset 3 yang lolos support
        $filteredItemset3 = array_filter($itemset3Data, function ($item) use ($minSupport) {
            return $item['support_percent'] >= $minSupport;
        });

        foreach ($filteredItemset3 as $itemset3) {
            // Support A∪B∪C
            $supportABC = $itemset3['support_percent'];

            // Kombinasi aturan dari A, B, C
            $combinations = [
                ['from' => [$itemset3['product_type_id_1'], $itemset3['product_type_id_2']], 'to' => $itemset3['product_type_id_3']],
                ['from' => [$itemset3['product_type_id_1'], $itemset3['product_type_id_3']], 'to' => $itemset3['product_type_id_2']],
                ['from' => [$itemset3['product_type_id_2'], $itemset3['product_type_id_3']], 'to' => $itemset3['product_type_id_1']],
            ];

            foreach ($combinations as $combo) {
                // Cari support(A∪B) dari itemset2
                $supportAB = 0;
                foreach ($itemset2Data as $item2) {
                    $ids = [$item2['product_type_id_1'], $item2['product_type_id_2']];
                    sort($ids);
                    $fromIds = $combo['from'];
                    sort($fromIds);

                    if ($ids === $fromIds) {
                        $supportAB = $item2['support_percent'];
                        break;
                    }
                }

                $confidence = $supportAB > 0 ? round($supportABC / $supportAB * 100, 2) : 0;

                $associationData[] = [
                    'analisis_data_id' => $analisisId,
                    'from_item' => $combo['from'][0],
                    'from_item_2' => $combo['from'][1],
                    'to_item' => $combo['to'],
                    'support_percent' => $supportABC,
                    'confidence_percent' => $confidence,
                    'is_below_confidence_threshold' => $confidence < $minConfidence ? 1 : 0
                ];
            }
        }

        foreach ($associationData as &$data) {
            if (!isset($data['from_item_2'])) {
                $data['from_item_2'] = null;
            }
        }
        unset($data); // aman dari referensi

        $associationData = array_filter($associationData, function($value) {
            return !empty($value['from_item']) && !empty($value['to_item']);
        });
        

        // Insert semua aturan asosiasi (2 dan 3 itemset)
        if (!empty($associationData)) {
            $associationModel->insertBatch($associationData);
        }

         // Redirect ke halaman itemset 1
         return redirect()->to('/analisis-data/itemset1');

    }

    public function detail($id)
    {
        $analisisModel = new AnalisisDataModel();
        $itemset1Model = new Itemset1Model();
        $itemset2Model = new Itemset2Model();
        $itemset3Model = new Itemset3Model();
        $associationModel = new AssociationRuleModel();
        $productModel = new TipeProdukModel();

        $analisis = $analisisModel->find($id);
        if (!$analisis) {
            return redirect()->to('/analisis-data')->with('error', 'Data analisis tidak ditemukan.');
        }

        // Itemset 1
        $itemset1 = $itemset1Model->where('analisis_data_id', $id)->findAll();
        foreach ($itemset1 as &$row) {
            $product = $productModel->find($row['product_type_id']);
            $row['product_name'] = $product['name'] ?? 'Unknown';
            $row['is_below_threshold'] = $row['is_below_threshold'] ?? 0;
        }
        unset($row);

        // Itemset 2
        $itemset2 = $itemset2Model->where('analisis_data_id', $id)->findAll();
        foreach ($itemset2 as &$row) {
            $p1 = $productModel->find($row['product_type_id_1']);
            $p2 = $productModel->find($row['product_type_id_2']);
            $row['product_names'] = ($p1['name'] ?? '?') . ' & ' . ($p2['name'] ?? '?');
            $row['is_below_threshold'] = $row['is_below_threshold'] ?? 0;
        }
        unset($row);

        // Itemset 3
        $itemset3 = $itemset3Model->where('analisis_data_id', $id)->findAll();
        foreach ($itemset3 as &$row) {
            $p1 = $productModel->find($row['product_type_id_1']);
            $p2 = $productModel->find($row['product_type_id_2']);
            $p3 = $productModel->find($row['product_type_id_3']);
            $row['item'] = implode(' & ', array_filter([
                $p1['name'] ?? null,
                $p2['name'] ?? null,
                $p3['name'] ?? null
            ]));
            $row['is_below_threshold'] = $row['is_below_threshold'] ?? 0;
        }
        unset($row);

        // Association Rules
        $associationRules = $associationModel->where('analisis_data_id', $id)->findAll();
        $association2 = [];
        $association3 = [];

        foreach ($associationRules as $rule) {
            if ($rule['from_item_2'] == null) {
                $from = $productModel->find($rule['from_item'])['name'] ?? '?';
                $to   = $productModel->find($rule['to_item'])['name'] ?? '?';
                $association2[] = [
                    'rule' => "$from → $to",
                    'confidence' => $rule['confidence_percent']
                ];
            } else {
                $f1 = $productModel->find($rule['from_item'])['name'] ?? '?';
                $f2 = $productModel->find($rule['from_item_2'])['name'] ?? '?';
                $to = $productModel->find($rule['to_item'])['name'] ?? '?';
                $association3[] = [
                    'rule' => "$f1 & $f2 → $to",
                    'confidence' => $rule['confidence_percent']
                ];
            }
        }

        // Rekomendasi confidence tertinggi dari association2
        $bestRule = null;
        foreach ($association2 as $assoc) {
            if ($bestRule === null || $assoc['confidence'] > $bestRule['confidence']) {
                $bestRule = $assoc;
            }
        }

        return view('analisis-data-detail', [
            'analisis' => $analisis,
            'itemset1' => $itemset1,
            'itemset2' => $itemset2,
            'itemset3' => $itemset3,
            'association2' => $association2,
            'association3' => $association3,
            'recommendation' => $bestRule
        ]);
    }


}
