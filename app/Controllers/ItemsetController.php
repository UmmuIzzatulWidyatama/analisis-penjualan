<?php

namespace App\Controllers;

use App\Models\ItemsetModel;

class ItemsetController extends BaseController
{
    public function index()
    {
        $session = session();

        // Cek apakah pengguna sudah login
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        return view('itemset');
    }

    public function itemset1()
    {
        $model = new ItemsetModel();
        $data['itemsets'] = $model->getItemset1();
        $data['minSupport'] = $model->getMinimumSupport();
        return view('analisis-data-add-itemset1', $data);
    }

}
