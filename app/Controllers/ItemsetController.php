<?php

namespace App\Controllers;

use App\Models\AnalisisDataModel;
use App\Models\ItemsetModel;
use App\Models\Itemset1Model;

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
    }}
