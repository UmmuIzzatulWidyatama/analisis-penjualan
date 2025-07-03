<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PreprocessingTransaksiController extends BaseController
{
    public function showPreprocessing()
    {
        $session = session();

        // Cek apakah pengguna sudah login
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }
         
        return view('preprocessing-transaksi');
    }

    public function uploadPreprocessing()
    {
        helper(['form', 'url']);

        $file = $this->request->getFile('file');

        if ($file && $file->isValid()) {
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet()->toArray();

            // Ambil header dan cari indeks kolom
            $header = array_map('strtolower', $sheet[0]);
            $idxNomor   = array_search('nomor transaksi', $header);
            $idxTanggal = array_search('tanggal transaksi', $header);
            $idxKode    = array_search('kode item', $header);

            if ($idxNomor === false || $idxTanggal === false || $idxKode === false) {
                return redirect()->back()->with('error', 'Kolom tidak sesuai.');
            }

            $result = [];

            for ($i = 1; $i < count($sheet); $i++) {
                $row = $sheet[$i];
                $nomor   = trim($row[$idxNomor] ?? '');
                $tanggal = trim($row[$idxTanggal] ?? '');
                $kode    = trim($row[$idxKode] ?? '');

                if ($nomor === '' || $tanggal === '' || $kode === '') continue;

                $result[] = [$nomor, $tanggal, $kode];
            }

            // Buat file hasil preprocessing
            $output = new Spreadsheet();
            $sheetOut = $output->getActiveSheet();

            $sheetOut->setCellValue('A1', 'Nomor Transaksi');
            $sheetOut->setCellValue('B1', 'Tanggal Transaksi');
            $sheetOut->setCellValue('C1', 'Kode Item');

            $rowNum = 2;
            foreach ($result as $row) {
                $sheetOut->setCellValue("A$rowNum", $row[0]);

                $dateFormatted = date('Y-m-d', strtotime($row[1]));
                $sheetOut->setCellValueExplicit("B$rowNum", $dateFormatted, DataType::TYPE_STRING);
                $sheetOut->getStyle("B$rowNum")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2);

                $sheetOut->setCellValue("C$rowNum", $row[2]);
                $rowNum++;
            }

            // Siapkan file untuk di-download
            $filename = 'hasil_preprocessing_transaksi.xlsx';
            $writer = new Xlsx($output);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit();
        }

        return redirect()->back()->with('error', 'File tidak valid.');
    }


}
