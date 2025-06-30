<?php

namespace App\Controllers;

use App\Models\AnalisisDataModel;
use App\Models\ItemsetModel;
use App\Models\Itemset1Model;
use App\Models\Itemset2Model;
use App\Models\Itemset3Model;
use App\Models\AssociationRuleModel;
use App\Models\TipeProdukModel;

class ItemsetController extends BaseController
{
    public function index()
    {
        $session = session();

        // Cek apakah pengguna sudah login
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        return redirect()->to('/analisis/itemset1');
    }

    public function itemset1()
    {
        $session = session();

        // Pastikan analisis_id sudah ada di session
        $analisisId = $session->get('analisis_id');
        if (!$analisisId) {
            return redirect()->to('/analisis-data')->with('error', 'Data analisis tidak ditemukan.');
        }

        $itemset1Model = new Itemset1Model();
        $analisisModel = new AnalisisDataModel();

        // Ambil data itemseAt_1 berdasarkan analisis_id
        $data['itemsets'] = $itemset1Model
            ->select('itemset_1.*, pt.name as item_name')
            ->join('product_types pt', 'pt.id = itemset_1.product_type_id')
            ->where('analisis_data_id', $analisisId)
            ->orderBy('support_count', 'DESC')
            ->findAll();

        // Ambil nilai minimum support dari analisis_data
        $analisis = $analisisModel->find($analisisId);
        if (!$analisis) {
            return redirect()->to('/analisis-data')->with('error', 'Data analisis tidak ditemukan.');
        }
        $data['minSupport'] = $analisis['minimum_support'];
        $data['transactionCount'] = $analisis['transaction_count'];

        return view('analisis-data-add-itemset1', $data); 
    }

    public function itemset2()
    {
        $session = session();
        $analisisId = $session->get('analisis_id');

        if (!$analisisId) {
            return redirect()->to('/analisis-data')->with('error', 'Data analisis tidak ditemukan.');
        }

        $analisisModel = new AnalisisDataModel();
        $itemset2Model = new Itemset2Model();

        $analisis = $analisisModel->find($analisisId);
        if (!$analisis) {
            return redirect()->to('/analisis/itemset1')->with('error', 'Data analisis tidak valid.');
        }

        $data['minSupport'] = $analisis['minimum_support'];
        $data['transactionCount'] = $analisis['transaction_count'];
        $data['itemsets'] = $itemset2Model
            ->select("CONCAT(pt1.name, ' & ', pt2.name) as item_name, 
                        itemset_2.support_count, 
                        itemset_2.support_percent")
            ->join('product_types pt1', 'pt1.id = itemset_2.product_type_id_1')
            ->join('product_types pt2', 'pt2.id = itemset_2.product_type_id_2')
            ->where('itemset_2.analisis_data_id', $analisisId)
            ->findAll();

        return view('analisis-data-add-itemset2', $data);
    }

    public function itemset3()
    {
        $session = session();
        $analisisId = $session->get('analisis_id');

        if (!$analisisId) {
            return redirect()->to('/analisis-data')->with('error', 'Data analisis tidak ditemukan.');
        }

        $analisisModel = new AnalisisDataModel();
        $itemset3Model = new Itemset3Model();

        $analisis = $analisisModel->find($analisisId);
        if (!$analisis) {
            return redirect()->to('/analisis/itemset1')->with('error', 'Data analisis tidak valid.');
        }

        $data['minSupport'] = $analisis['minimum_support'];
        $data['transactionCount'] = $analisis['transaction_count'];
        $data['itemsets'] = $itemset3Model
            ->select("CONCAT(pt1.name, ' & ', pt2.name, ' & ', pt3.name) as item_name, 
                        itemset_3.support_count, 
                        itemset_3.support_percent")
            ->join('product_types pt1', 'pt1.id = itemset_3.product_type_id_1')
            ->join('product_types pt2', 'pt2.id = itemset_3.product_type_id_2')
            ->join('product_types pt3', 'pt3.id = itemset_3.product_type_id_3')
            ->where('itemset_3.analisis_data_id', $analisisId)
            ->findAll();

        return view('analisis-data-add-itemset3', $data);
    }

    public function asosiasi()
    {
        $session = session();
        $analisisId = $session->get('analisis_id');

        if (!$analisisId) {
            return redirect()->to('/analisis-data')->with('error', 'Data analisis tidak ditemukan.');
        }

        $analisisModel = new AnalisisDataModel();
        $associationModel = new AssociationRuleModel();
        $productModel = new TipeProdukModel();

        $rules = $associationModel->where('analisis_data_id', $analisisId)->findAll();
        $analisis = $analisisModel->find($analisisId);
        if (!$analisis) {
            return redirect()->to('/analisis/itemset1')->with('error', 'Data analisis tidak valid.');
        }

        $minSupport = $analisis['minimum_support'];
        $minConfidence = $analisis['minimum_confidence'];

        // Buat mapping id produk ke nama produk
        $productNames = $productModel->findAll();
        $nameMap = [];
        foreach ($productNames as $prod) {
            $nameMap[$prod['id']] = $prod['name'];
        }

        $rules2 = [];
        $rules3 = [];

        foreach ($rules as $rule) {
            // Skip jika item tidak valid
            if (
                empty($rule['from_item']) || $rule['from_item'] == 0 ||
                empty($rule['to_item']) || $rule['to_item'] == 0
            ) {
                continue;
            }

            $from1 = $nameMap[$rule['from_item']] ?? null;
            $to = $nameMap[$rule['to_item']] ?? null;

            if (!$from1 || !$to) continue;

            if (empty($rule['from_item_2']) || $rule['from_item_2'] == 0) {
                // Asosiasi 2 item
                $rules2[] = [
                    'from_item_name' => $from1,
                    'to_item_name' => $to,
                    'confidence_percent' => $rule['confidence_percent'],
                    'is_below_confidence_threshold' => $rule['is_below_confidence_threshold']
                ];
            } else {
                $from2 = $nameMap[$rule['from_item_2']] ?? null;
                if (!$from2) continue;

                $rules3[] = [
                    'from_item_name' => $from1 . ' & ' . $from2,
                    'to_item_name' => $to,
                    'confidence_percent' => $rule['confidence_percent'],
                    'is_below_confidence_threshold' => $rule['is_below_confidence_threshold']
                ];
            }
        }

        return view('analisis-data-add-asosiasi', [
            'minSupport' => $minSupport,
            'minConfidence' => $minConfidence,
            'rules2' => $rules2,
            'rules3' => $rules3
        ]);
    }


    public function kesimpulan()
    {
        $session = session();
        $analisisId = $session->get('analisis_id');

        if (!$analisisId) {
            return redirect()->to('/analisis-data')->with('error', 'Tidak ada data analisis yang ditemukan.');
        }

        $analisisModel = new \App\Models\AnalisisDataModel();
        $associationModel = new \App\Models\AssociationRuleModel();
        $productTypeModel = new \App\Models\TipeProdukModel();

        $analisisData = $analisisModel->find($analisisId);

        if (!$analisisData) {
            return redirect()->to('/analisis-data')->with('error', 'Data analisis tidak valid.');
        }

        // Ambil Top 5 Asosiasi 2 Item berdasarkan confidence tertinggi
        $topRules2 = $associationModel
        ->where('analisis_data_id', $analisisId)
        ->groupStart()
            ->where('from_item_2', null)
            ->orWhere('from_item_2', 0)
        ->groupEnd()
        ->orderBy('confidence_percent', 'DESC')
        ->limit(5)
        ->findAll();

        // Ambil Top 5 Asosiasi 3 Item berdasarkan confidence tertinggi
        $topRules3 = $associationModel
        ->where('analisis_data_id', $analisisId)
        ->where('from_item_2 !=', 0)
        ->orderBy('confidence_percent', 'DESC')
        ->limit(5)
        ->findAll();

        // Ambil rule terbaik dari semua asosiasi
        $bestRule = $associationModel
            ->where('analisis_data_id', $analisisId)
            ->orderBy('confidence_percent', 'DESC')
            ->first();

        // Ambil nama produk untuk semua asosiasi
        $productTypeIds = [];

        foreach (array_merge($topRules2, $topRules3, [$bestRule]) as $rule) {
            if ($rule) {
                $productTypeIds[] = $rule['from_item'];
                if (isset($rule['from_item_2'])) {
                    $productTypeIds[] = $rule['from_item_2'];
                }
                $productTypeIds[] = $rule['to_item'];
            }
        }

        $productTypeIds = array_unique($productTypeIds);

        $productTypes = [];
        if (!empty($productTypeIds)) {
            $products = $productTypeModel->whereIn('id', $productTypeIds)->findAll();
            foreach ($products as $p) {
                $productTypes[$p['id']] = $p['name'];
            }
        }

        // Tambahkan nama produk ke rules
        foreach ($topRules2 as &$rule) {
            $rule['from_item_name'] = $productTypes[$rule['from_item']] ?? 'Unknown';
            $rule['to_item_name'] = $productTypes[$rule['to_item']] ?? 'Unknown';
        }
        foreach ($topRules3 as &$rule) {
            $rule['from_item_name'] = $productTypes[$rule['from_item']] ?? 'Unknown';
            $rule['from_item_2_name'] = $productTypes[$rule['from_item_2']] ?? 'Unknown';
            $rule['to_item_name'] = $productTypes[$rule['to_item']] ?? 'Unknown';
        }
        if ($bestRule) {
            $bestRule['from_item_name'] = $productTypes[$bestRule['from_item']] ?? 'Unknown';
            $bestRule['to_item_name'] = $productTypes[$bestRule['to_item']] ?? 'Unknown';
            if (isset($bestRule['from_item_2'])) {
                $bestRule['from_item_2_name'] = $productTypes[$bestRule['from_item_2']] ?? 'Unknown';
            }
        }

        return view('analisis-data-add-kesimpulan', [
            'analisisData' => $analisisData,
            'topRules2' => $topRules2,
            'topRules3' => $topRules3,
            'bestRule' => $bestRule
        ]);
    }

}
