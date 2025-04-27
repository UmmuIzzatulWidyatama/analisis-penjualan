<?php

namespace App\Controllers;

use App\Models\AnalisisDataModel;
use App\Models\ItemsetModel;
use App\Models\Itemset1Model;
use App\Models\Itemset2Model;
use App\Models\Itemset3Model;

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

        $minSupport = $analisis['minimum_support'];
        $itemsets = $itemset2Model
            ->select("CONCAT(pt1.name, ' & ', pt2.name) as item_name, 
                        itemset_2.support_count, 
                        itemset_2.support_percent")
            ->join('product_types pt1', 'pt1.id = itemset_2.product_type_id_1')
            ->join('product_types pt2', 'pt2.id = itemset_2.product_type_id_2')
            ->where('itemset_2.analisis_data_id', $analisisId)
            ->findAll();

        return view('analisis-data-add-itemset2', [
            'itemsets' => $itemsets,
            'minSupport' => $minSupport
        ]);
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

        $minSupport = $analisis['minimum_support'];
        $itemsets = $itemset3Model
            ->select("CONCAT(pt1.name, ' & ', pt2.name, ' & ', pt3.name) as item_name, 
                        itemset_3.support_count, 
                        itemset_3.support_percent")
            ->join('product_types pt1', 'pt1.id = itemset_3.product_type_id_1')
            ->join('product_types pt2', 'pt2.id = itemset_3.product_type_id_2')
            ->join('product_types pt3', 'pt3.id = itemset_3.product_type_id_3')
            ->where('itemset_3.analisis_data_id', $analisisId)
            ->findAll();

        return view('analisis-data-add-itemset3', [
            'itemsets' => $itemsets,
            'minSupport' => $minSupport
        ]);
    }

}
