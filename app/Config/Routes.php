<?php

namespace Config;

use App\Controllers\Login;
use App\Controllers\Dashboard;

$routes->get('/login', 'AuthController::login');
$routes->post('/authenticate', 'AuthController::authenticate');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/dashboard', 'DashboardController::index');
