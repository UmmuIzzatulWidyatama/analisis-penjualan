<?php

namespace Config;

//login
$routes->get('/login', 'AuthController::login');
$routes->post('/authenticate', 'AuthController::authenticate');
$routes->get('/logout', 'AuthController::logout');
//rule
$routes->get('/rule', 'RuleController::index');
$routes->get('/rule/detail/(:num)', 'RuleController::detail/$1'); // show form edit
$routes->post('/rule/update/(:num)', 'RuleController::update/$1'); // proses update
//tipe produk
$routes->get('/tipe-produk', 'TipeProdukController::index'); //list page tipe produk
$routes->get('/tipe-produk/add', 'TipeProdukController::add'); //show form add produk
$routes->post('/tipe-produk/save', 'TipeProdukController::save'); // simpan data produk
$routes->get('/tipe-produk/delete/(:num)', 'TipeProdukController::delete/$1'); //delete produk
$routes->get('/tipe-produk/edit/(:num)', 'TipeProdukController::edit/$1'); // tampilkan form edit
$routes->post('/tipe-produk/update/(:num)', 'TipeProdukController::update/$1'); // proses update 
//bulk upload produk
$routes->get('/tipe-produk/showUploadBulk', 'TipeProdukController::showUploadBulk'); // tampilkan halaman upload bulk produk
$routes->get('/tipe-produk/downloadTemplate', 'TipeProdukController::downloadTemplate'); // download template upload bulk produk
$routes->post('/tipe-produk/uploadBulk', 'TipeProdukController::uploadBulk'); // upload bulk template excel
$routes->post('/tipe-produk/saveBulk', 'TipeProdukController::saveBulk'); //simpan bulk data ke database
//transaksi
$routes->get('/transaksi', 'TransaksiController::index'); //list page transaksi
$routes->get('/transaksi/add', 'TransaksiController::add'); //show form add transaksi
$routes->post('/transaksi/save', 'TransaksiController::save'); //simpan data transaksi
$routes->get('/transaksi/delete/(:num)', 'TransaksiController::delete/$1'); //delete produk
$routes->get('/transaksi/detail/(:num)', 'TransaksiController::detail/$1'); // tampilkan form detail               
//bulk upload transaksi
$routes->get('/transaksi/showUploadBulk', 'TransaksiController::showUploadBulk'); // tampilkan halaman upload bulk transaksi
$routes->get('/transaksi/downloadTemplate', 'TransaksiController::downloadTemplate'); // download template upload bulk transaksi
$routes->post('/transaksi/uploadBulk', 'TransaksiController::uploadBulk'); // upload bulk template excel
$routes->post('/transaksi/saveBulk', 'TransaksiController::saveBulk'); //simpan bulk data ke database
//analisis data
$routes->get('analisis-data', 'AnalisisDataController::index'); //list page analisis info
$routes->get('analisis-data/add', 'AnalisisDataController::add'); //analisis information
$routes->post('analisis-data/save', 'AnalisisDataController::save'); // Menyimpan data baru
$routes->get('/analisis-data/itemset1', 'ItemsetController::itemset1'); // itemset 1 
$routes->get('/analisis-data/itemset2', 'ItemsetController::itemset2'); // itemset 2
$routes->get('/analisis-data/itemset3', 'ItemsetController::itemset3'); // itemset 2
$routes->get('/analisis-data/asosiasi', 'ItemsetController::asosiasi'); //asosiasi
$routes->get('/analisis-data/lift', 'ItemsetController::lift'); //lift ratio
$routes->get('/analisis-data/kesimpulan', 'ItemsetController::kesimpulan'); //kesimpulan
$routes->get('/analisis-data/delete/(:num)', 'AnalisisDataController::delete/$1'); //delete analisis data
$routes->get('/analisis-data/download/(:num)', 'AnalisisDataController::download/$1'); // download file to pdf               
