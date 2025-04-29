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
//transaksi
$routes->get('/transaksi', 'TransaksiController::index'); //list page transaksi
$routes->get('/transaksi/add', 'TransaksiController::add'); //show form add transaksi
$routes->post('/transaksi/save', 'TransaksiController::save'); //simpan data transaksi
$routes->get('/transaksi/delete/(:num)', 'TransaksiController::delete/$1'); //delete produk
$routes->get('/transaksi/detail/(:num)', 'TransaksiController::detail/$1'); // tampilkan form detail               
//analisis data
$routes->get('analisis-data', 'AnalisisDataController::index'); //list page analisis info
$routes->get('analisis-data/add', 'AnalisisDataController::add'); //analisis information
$routes->post('analisis-data/save', 'AnalisisDataController::save'); // Menyimpan data baru
$routes->get('/analisis-data/itemset1', 'ItemsetController::itemset1'); // itemset 1
$routes->get('/analisis-data/itemset2', 'ItemsetController::itemset2'); // itemset 2
$routes->get('/analisis-data/itemset3', 'ItemsetController::itemset3'); // itemset 2
$routes->get('/analisis-data/asosiasi', 'ItemsetController::asosiasi'); //asosiasi
$routes->get('/analisis-data/kesimpulan', 'ItemsetController::kesimpulan'); //kesimpulan
$routes->get('/analisis-data/delete/(:num)', 'AnalisisDataController::delete/$1'); //delete analisis data
$routes->get('/analisis-data/detail/(:num)', 'AnalisisDataController::detail/$1'); // tampilkan form detail               
