<?php

namespace Config;

use App\Controllers\Login;
use App\Controllers\Dashboard;

$routes->get('/login', 'AuthController::login');
$routes->post('/authenticate', 'AuthController::authenticate');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/rule', 'RuleController::index');
$routes->get('/rule/detail/(:num)', 'RuleController::detail/$1'); // show form edit
$routes->post('/rule/update/(:num)', 'RuleController::update/$1'); // proses update
$routes->get('/tipe-produk', 'TipeProdukController::index');
$routes->get('/tipe-produk/add', 'TipeProdukController::add'); //show form add produk
$routes->post('/tipe-produk/save', 'TipeProdukController::save'); // simpan data produk
$routes->get('/tipe-produk/delete/(:num)', 'TipeProdukController::delete/$1');
$routes->get('/transaksi', 'TransaksiController::index');
$routes->get('analisis-data', 'AnalisisDataController::index'); //list page analisis info
$routes->get('analisis-data/add', 'AnalisisDataController::add'); //analisis information
$routes->post('analisis-data/save', 'AnalisisDataController::save'); // Menyimpan data baru
$routes->get('/analisis-data/itemset1', 'ItemsetController::itemset1'); // itemset 1
$routes->get('/analisis-data/itemset2', 'ItemsetController::itemset2'); // itemset 2
$routes->get('/analisis-data/itemset3', 'ItemsetController::itemset3'); // itemset 2
$routes->get('/analisis-data/asosiasi', 'ItemsetController::asosiasi'); //asosiasi
$routes->get('/analisis-data/kesimpulan', 'ItemsetController::kesimpulan'); //kesimpulan 




