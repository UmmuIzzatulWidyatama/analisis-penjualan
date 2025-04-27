<?php

namespace Config;

use App\Controllers\Login;
use App\Controllers\Dashboard;

$routes->get('/login', 'AuthController::login');
$routes->post('/authenticate', 'AuthController::authenticate');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/rule', 'RuleController::index');
$routes->get('/tipe-produk', 'TipeProdukController::index');
$routes->get('/transaksi', 'TransaksiController::index');
$routes->get('analisis-data', 'AnalisisDataController::index'); //list page analisis info
$routes->get('analisis-data/add', 'AnalisisDataController::add'); //analisis information
$routes->get('/analisis-data/itemset1', 'ItemsetController::itemset1'); // itemset 1
$routes->get('/analisis-data/itemset2', 'ItemsetController::itemset2'); // itemset 2
$routes->get('/analisis-data/itemset3', 'ItemsetController::itemset3'); // itemset 2
$routes->get('/asosiasi', 'AsosiasiController::index'); //asosiasi
$routes->post('analisis-data/save', 'AnalisisDataController::save'); // Menyimpan data baru



