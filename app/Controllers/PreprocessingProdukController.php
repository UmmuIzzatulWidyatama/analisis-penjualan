<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PreprocessingProdukController extends BaseController
{
    public function showPreprocessing()
    {
        $session = session();

        // Cek apakah pengguna sudah login
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }
         
        return view('preprocessing-produk');
    }

    public function uploadPreprocessing()
    {
        helper(['form', 'url']);

        if ($this->request->getMethod() == 'post' && $this->request->getFile('file')->isValid()) {
            $file = $this->request->getFile('file');
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Ambil header
            $header = array_map('strtolower', $rows[0]);
            $indexKode = array_search('kode item', $header);
            $indexNama = array_search('nama produk', $header);

            if ($indexKode === false || $indexNama === false) {
                return redirect()->back()->with('error', 'Format kolom tidak sesuai. Pastikan ada kolom Kode Item dan Nama Produk.');
            }

            $processed = [];
            $seen = [];

            for ($i = 1; $i < count($rows); $i++) {
                $kode = trim($rows[$i][$indexKode] ?? '');
                $nama = trim($rows[$i][$indexNama] ?? '');

                if ($kode === '' || $nama === '') {
                    continue;
                }

                if (!in_array($kode, $seen)) {
                    $seen[] = $kode;
                    $processed[] = [$kode, $nama];
                }
            }

            // Buat spreadsheet baru
            $output = new Spreadsheet();
            $sheetOut = $output->getActiveSheet();
            $sheetOut->setCellValue('A1', 'Kode Item');
            $sheetOut->setCellValue('B1', 'Nama Produk');

            $rowNum = 2;
            foreach ($processed as $row) {
                $sheetOut->setCellValue("A$rowNum", $row[0]);
                $sheetOut->setCellValue("B$rowNum", $row[1]);
                $rowNum++;
            }

            // Output sebagai file download
            $filename = 'preprocessed_produk.xlsx';
            $writer = new Xlsx($output);

            // Headers untuk mendownload file
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=\"$filename\"");
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit();
        }

        return view('preprocessing-produk');
    }


}
