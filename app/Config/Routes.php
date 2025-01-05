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
$routes->get('/itemset', 'ItemsetController::index');
$routes->get('/asosiasi', 'AsosiasiController::index');
$routes->get('analisis-data', 'AnalisisDataController::index');
$routes->get('analisis-data/add', 'AnalisisDataController::add');
$routes->post('analisis-data/save', 'AnalisisDataController::save'); // Menyimpan data baru



