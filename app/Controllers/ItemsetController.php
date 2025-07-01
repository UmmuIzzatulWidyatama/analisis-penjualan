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
            ->orderBy('support_percent', 'DESC')
            ->findAll();

        // Ambil nilai minimum support dari analisis_data
        $analisis = $analisisModel->find($analisisId);
        if (!$analisis) {
            return redirect()->to('/analisis-data')->with('error', 'Data analisis tidak ditemukan.');
        }
        $data['minSupport'] = $analisis['minimum_support'];
        $data['transactionCount'] = $analisis['transaction_count'];

        // Ambil 3 item dengan support tertinggi
        $topItems = array_slice($data['itemsets'], 0, 3);

        $itemNames = array_map(function ($item) {
            return $item['item_name'];
        }, $topItems);

        // Deskripsi
        if (count($itemNames) >= 3) {
            $deskripsi = "Itemset 1 menunjukkan produk individual yang sering muncul dalam transaksi. Produk seperti {$itemNames[0]}, {$itemNames[1]}, dan {$itemNames[2]} tergolong sering dibeli, sedangkan baris yang diberi berwarna merah tidak lolos threshold minimum support.";
        } elseif (count($itemNames) === 2) {
            $deskripsi = "Itemset 1 menunjukkan produk individual yang sering muncul dalam transaksi. Produk seperti {$itemNames[0]} dan {$itemNames[1]} tergolong sering dibeli.";
        } elseif (count($itemNames) === 1) {
            $deskripsi = "Itemset 1 menunjukkan produk individual yang sering muncul dalam transaksi. Produk {$itemNames[0]} tergolong sering dibeli.";
        } else {
            $deskripsi = "Belum ada produk yang memenuhi minimum support.";
        }

        $data['deskripsi'] = $deskripsi;

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
            ->select("CONCAT('{', pt1.name, ', ', pt2.name, '}') as item_name, 
                    itemset_2.support_count, 
                    itemset_2.support_percent")
            ->join('product_types pt1', 'pt1.id = itemset_2.product_type_id_1')
            ->join('product_types pt2', 'pt2.id = itemset_2.product_type_id_2')
            ->where('itemset_2.analisis_data_id', $analisisId)
            ->orderBy('support_percent', 'DESC')
            ->findAll();

        $data['deskripsi'] = "Itemset 2 menunjukkan pasangan produk yang sering dibeli bersama. Kombinasi yang lolos minimum support relevan untuk dianalisis lebih lanjut.";


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
            ->select("CONCAT('{', pt1.name, ', ', pt2.name, ', ', pt3.name, '}') as item_name, 
                    itemset_3.support_count, 
                    itemset_3.support_percent")
            ->join('product_types pt1', 'pt1.id = itemset_3.product_type_id_1')
            ->join('product_types pt2', 'pt2.id = itemset_3.product_type_id_2')
            ->join('product_types pt3', 'pt3.id = itemset_3.product_type_id_3')
            ->where('itemset_3.analisis_data_id', $analisisId)
            ->orderBy('support_percent', 'DESC')
            ->findAll();

        $data['deskripsi'] = "Itemset 3 menampilkan kombinasi tiga produk yang sering dibeli bersama. Kombinasi yang lolos minimum support, menunjukkan potensi tinggi untuk penempatan produk yang berdekatan.";

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
                    'from_item_name' => '{' . $from1 . '}',
                    'to_item_name' => '{' . $to . '}',
                    'confidence_percent' => $rule['confidence_percent'],
                    'is_below_confidence_threshold' => $rule['is_below_confidence_threshold']
                ];
            } else {
                $from2 = $nameMap[$rule['from_item_2']] ?? null;
                if (!$from2) continue;

                $rules3[] = [
                    'from_item_name' => '{' . $from1 . ', ' . $from2 . '}',
                    'to_item_name' => '{' . $to . '}',
                    'confidence_percent' => $rule['confidence_percent'],
                    'is_below_confidence_threshold' => $rule['is_below_confidence_threshold']
                ];
            }
        }

        // Urutkan rules2 berdasarkan confidence_percent DESC
        usort($rules2, function ($a, $b) {
            return $b['confidence_percent'] <=> $a['confidence_percent'];
        });

        // Deskripsi asosiasi 2 item
        if (!empty($rules2)) {
            $topRule2 = $rules2[0]; // ambil aturan dengan confidence tertinggi

            $deskripsi2 = "Aturan asosiasi 2 item menunjukkan hubungan dua produk yang sering dibeli bersama. ".
                        "Pada kasus ini, {$topRule2['from_item_name']} → {$topRule2['to_item_name']} memiliki confidence " .
                        number_format($topRule2['confidence_percent'], 2) . "%, artinya setiap membeli " .
                        trim($topRule2['from_item_name'], '{}') . " juga membeli " .
                        trim($topRule2['to_item_name'], '{}') . ".";
        } else {
            $deskripsi2 = "Belum terdapat aturan asosiasi 2 item yang lolos minimum confidence.";
        }

        // Urutkan rules3 berdasarkan confidence_percent DESC
        usort($rules3, function ($a, $b) {
            return $b['confidence_percent'] <=> $a['confidence_percent'];
        });

        // Deskripsi asosiasi 3 item
        if (!empty($rules3)) {
            $topRule3 = $rules3[0]; // aturan 3 item dengan confidence tertinggi

            $deskripsi3 = "Aturan 3 item menunjukkan produk ketiga yang kemungkinan besar dibeli jika dua produk lainnya sudah dibeli. ".
                        "Pada kasus ini, {$topRule3['from_item_name']} → {$topRule3['to_item_name']} dengan confidence " .
                        number_format($topRule3['confidence_percent'], 2) . "% artinya setiap pembelian yang mencakup " .
                        trim($topRule3['from_item_name'], '{}'). " juga selalu mencakup " .
                        trim($topRule3['to_item_name'], '{}') . ".";
        } else {
            $deskripsi3 = "Belum terdapat aturan asosiasi 3 item yang lolos minimum confidence.";
        }

        return view('analisis-data-add-asosiasi', [
            'minSupport' => $minSupport,
            'minConfidence' => $minConfidence,
            'rules2' => $rules2,
            'rules3' => $rules3,
            'deskripsi2' => $deskripsi2,
            'deskripsi3' => $deskripsi3
        ]);
    }

    public function lift()
    {
        $session = session();
        $analisisId = $session->get('analisis_id');

        if (!$analisisId) {
            return redirect()->to('/analisis-data')->with('error', 'Data analisis tidak ditemukan.');
        }

        $analisisModel = new \App\Models\AnalisisDataModel();
        $associationModel = new \App\Models\AssociationRuleModel();
        $itemset1Model = new \App\Models\Itemset1Model();
        $productModel = new \App\Models\TipeProdukModel();

        $analisis = $analisisModel->find($analisisId);
        $transactionCount = $analisis['transaction_count'];
        $minConfidence = $analisis['minimum_confidence'];

        $rules = $associationModel
            ->where('analisis_data_id', $analisisId)
            ->where('confidence_percent >=', $minConfidence)
            ->findAll();

        $products = $productModel->findAll();
        $productNames = array_column($products, 'name', 'id');

        $lifts = [];

        foreach ($rules as $rule) {
            $fromItems = [$rule['from_item']];
            if (!empty($rule['from_item_2']) && $rule['from_item_2'] != 0) {
                $fromItems[] = $rule['from_item_2'];
            }

            $toItem = $rule['to_item'];

            $supportTo = $itemset1Model
                ->where('analisis_data_id', $analisisId)
                ->where('product_type_id', $toItem)
                ->first();

            if (!$supportTo || $supportTo['support_count'] == 0) {
                continue;
            }

            $supportToValue = $supportTo['support_count'] / $transactionCount;

            $lift = $supportToValue > 0 ? $rule['confidence_percent'] / 100 / $supportToValue : 0;

            $fromText = implode(' & ', array_map(fn($id) => $productNames[$id] ?? 'Unknown', $fromItems));
            $toText = $productNames[$toItem] ?? 'Unknown';

            $lifts[] = [
                'rule' => '{' . $fromText . '} → {' . $toText . '}',
                'lift' => number_format($lift, 2)
            ];
        }

        // Ambil rule dengan lift tertinggi untuk deskripsi
        $deskripsi = "Lift ratio menunjukkan kekuatan hubungan antar produk. Nilai > 1 berarti produk saling berkaitan secara positif.";

        if (!empty($lifts)) {
            // Ubah string lift jadi float agar bisa diurutkan
            usort($lifts, function ($a, $b) {
                return (float)$b['lift'] <=> (float)$a['lift'];
            });

            $top = $lifts[0];
            $deskripsi .= " Pada kasus ini, {$top['rule']} dengan lift {$top['lift']} artinya " .
                        trim(explode('→', $top['rule'])[1], ' {}') . " " .
                        (number_format(((float)$top['lift'] - 1) * 100, 0)) . "% lebih mungkin dibeli jika konsumen sudah membeli " .
                        trim(explode('→', $top['rule'])[0], ' {}') . ".";
        }

        return view('analisis-data-add-lift', [
            'lifts' => $lifts,
            'deskripsi' => $deskripsi
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
        $itemset1Model = new \App\Models\Itemset1Model();

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
                if (isset($rule['from_item_2']) && $rule['from_item_2'] != 0) {
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
        $tempRules2 = [];
        foreach ($topRules2 as $rule) {
            $from = $productTypes[$rule['from_item']] ?? 'Unknown';
            $to = $productTypes[$rule['to_item']] ?? 'Unknown';

            $rule['from_item_name'] = '{' . $from . '}';
            $rule['to_item_name'] = '{' . $to . '}';
            $tempRules2[] = $rule;
        }
        $topRules2 = $tempRules2;

        $tempRules3 = [];
        foreach ($topRules3 as $rule) {
            $from1 = $productTypes[$rule['from_item']] ?? 'Unknown';
            $from2 = $productTypes[$rule['from_item_2']] ?? 'Unknown';
            $to = $productTypes[$rule['to_item']] ?? 'Unknown';

            $rule['from_item_name'] = '{' . $from1 . ', ' . $from2 . '}';
            $rule['to_item_name'] = '{' . $to . '}';
            $tempRules3[] = $rule;
        }
        $topRules3 = $tempRules3;

        if ($bestRule) {
            $from1 = $productTypes[$bestRule['from_item']] ?? 'Unknown';
            $to = $productTypes[$bestRule['to_item']] ?? 'Unknown';

            if (isset($bestRule['from_item_2']) && $bestRule['from_item_2'] != 0) {
                $from2 = $productTypes[$bestRule['from_item_2']] ?? 'Unknown';
                $bestRule['from_item_name'] = '{' . $from1 . ', ' . $from2 . '}';
            } else {
                $bestRule['from_item_name'] = '{' . $from1 . '}';
            }

            $bestRule['to_item_name'] = '{' . $to . '}';
        }

        // Hitung Lift Ratio hanya untuk rule yang lolos minimum confidence
        $liftResults = [];
        $transactionCount = $analisisData['transaction_count'];

        $validRules = $associationModel
            ->where('analisis_data_id', $analisisId)
            ->where('confidence_percent >=', $analisisData['minimum_confidence'])
            ->findAll();

        foreach ($validRules as $rule) {
            $fromItems = [$rule['from_item']];
            if (!empty($rule['from_item_2']) && $rule['from_item_2'] != 0) {
                $fromItems[] = $rule['from_item_2'];
            }

            $toItem = $rule['to_item'];

            $supportTo = $itemset1Model
                ->where('analisis_data_id', $analisisId)
                ->where('product_type_id', $toItem)
                ->first();

            if (!$supportTo || $supportTo['support_count'] == 0) {
                continue;
            }

            $supportToValue = $supportTo['support_count'] / $transactionCount;
            $lift = $supportToValue > 0 ? $rule['confidence_percent'] / 100 / $supportToValue : 0;

            $fromText = implode(' & ', array_map(fn($id) => $productTypes[$id] ?? 'Unknown', $fromItems));
            $toText = $productTypes[$toItem] ?? 'Unknown';

            $liftResults[] = [
                'rule' => '{' . $fromText . '} → {' . $toText . '}',
                'lift' => number_format($lift, 2)
            ];
        }

        return view('analisis-data-add-kesimpulan', [
            'analisisData' => $analisisData,
            'topRules2' => $topRules2,
            'topRules3' => $topRules3,
            'bestRule' => $bestRule,
            'liftResults' => $liftResults
        ]);
    }

}
