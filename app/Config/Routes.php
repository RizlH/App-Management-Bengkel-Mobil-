<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// Auth Routes (tidak perlu login)
$routes->group('auth', function($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::attemptLogin');
    $routes->get('logout', 'Auth::logout');
    
    // ==================== TAMBAHKAN ROUTE INI ====================
    // Register Routes
    $routes->get('register', 'Auth::register');
    $routes->post('register', 'Auth::attemptRegister');
    
    // Forgot Password Routes
    $routes->get('forgot-password', 'Auth::forgotPassword');
    $routes->post('forgot-password', 'Auth::processForgotPassword');
    $routes->get('reset-password/(:any)', 'Auth::resetPassword/$1');
    $routes->post('reset-password', 'Auth::processResetPassword');
    // ==================== END TAMBAHAN ====================
});

// Protected Routes (perlu login)
$routes->group('', ['filter' => 'auth'], function($routes) {
    // Dashboard
    $routes->get('/', 'Dashboard::index');
    $routes->get('dashboard', 'Dashboard::index');
    
    // Master Data - Pelanggan
    $routes->get('pelanggan', 'Pelanggan::index');
    $routes->get('pelanggan/create', 'Pelanggan::create');
    $routes->post('pelanggan/store', 'Pelanggan::store');
    $routes->get('pelanggan/show/(:num)', 'Pelanggan::show/$1');
    $routes->get('pelanggan/edit/(:num)', 'Pelanggan::edit/$1');
    $routes->post('pelanggan/update/(:num)', 'Pelanggan::update/$1');
    $routes->get('pelanggan/delete/(:num)', 'Pelanggan::delete/$1');
    
    // Master Data - Kendaraan
    $routes->get('kendaraan', 'Kendaraan::index');
    $routes->get('kendaraan/create', 'Kendaraan::create');
    $routes->post('kendaraan/store', 'Kendaraan::store');
    $routes->get('kendaraan/show/(:num)', 'Kendaraan::show/$1');
    $routes->get('kendaraan/edit/(:num)', 'Kendaraan::edit/$1');
    $routes->post('kendaraan/update/(:num)', 'Kendaraan::update/$1');
    $routes->get('kendaraan/delete/(:num)', 'Kendaraan::delete/$1');
    
    // Master Data - Mekanik
    $routes->get('mekanik', 'Mekanik::index');
    $routes->get('mekanik/create', 'Mekanik::create');
    $routes->post('mekanik/store', 'Mekanik::store');
    $routes->get('mekanik/edit/(:num)', 'Mekanik::edit/$1');
    $routes->post('mekanik/update/(:num)', 'Mekanik::update/$1');
    $routes->get('mekanik/delete/(:num)', 'Mekanik::delete/$1');
    
    // Master Data - Sparepart
    $routes->get('sparepart', 'Sparepart::index');
    $routes->get('sparepart/create', 'Sparepart::create');
    $routes->post('sparepart/store', 'Sparepart::store');
    $routes->get('sparepart/edit/(:num)', 'Sparepart::edit/$1');
    $routes->post('sparepart/update/(:num)', 'Sparepart::update/$1');
    $routes->get('sparepart/delete/(:num)', 'Sparepart::delete/$1');
    
    // Master Data - Jasa Servis
    $routes->get('jasa-servis', 'JasaServis::index');
    $routes->get('jasa-servis/create', 'JasaServis::create');
    $routes->post('jasa-servis/store', 'JasaServis::store');
    $routes->get('jasa-servis/edit/(:num)', 'JasaServis::edit/$1');
    $routes->post('jasa-servis/update/(:num)', 'JasaServis::update/$1');
    $routes->get('jasa-servis/delete/(:num)', 'JasaServis::delete/$1');
    
    // Transaksi - Penerimaan Servis
    $routes->get('penerimaan-servis', 'PenerimaanServis::index');
    $routes->get('penerimaan-servis/create', 'PenerimaanServis::create');
    $routes->post('penerimaan-servis/store', 'PenerimaanServis::store');
    $routes->get('penerimaan-servis/show/(:num)', 'PenerimaanServis::show/$1');
    $routes->get('penerimaan-servis/edit/(:num)', 'PenerimaanServis::edit/$1');
    $routes->post('penerimaan-servis/update/(:num)', 'PenerimaanServis::update/$1');
    $routes->get('penerimaan-servis/delete/(:num)', 'PenerimaanServis::delete/$1');
    // AJAX untuk get kendaraan by pelanggan
    $routes->get('penerimaan-servis/get-kendaraan/(:num)', 'PenerimaanServis::getKendaraanByPelanggan/$1');
    
    // Transaksi - Work Order
    $routes->get('work-order', 'WorkOrder::index');
    $routes->get('work-order/create', 'WorkOrder::create');
    $routes->post('work-order/store', 'WorkOrder::store');
    $routes->get('work-order/show/(:num)', 'WorkOrder::show/$1');
    $routes->post('work-order/update-status/(:num)', 'WorkOrder::updateStatus/$1');
    $routes->get('work-order/delete/(:num)', 'WorkOrder::delete/$1');
    
    // Transaksi - Penjualan Sparepart
    $routes->get('penjualan-sparepart', 'PenjualanSparepart::index');
    $routes->get('penjualan-sparepart/create', 'PenjualanSparepart::create');
    $routes->post('penjualan-sparepart/store', 'PenjualanSparepart::store');
    $routes->get('penjualan-sparepart/show/(:num)', 'PenjualanSparepart::show/$1');
    $routes->get('penjualan-sparepart/delete/(:num)', 'PenjualanSparepart::delete/$1');
    $routes->get('penjualan-sparepart/laporan', 'PenjualanSparepart::laporan');
    
    // Transaksi - Pembayaran
    $routes->get('pembayaran', 'Pembayaran::index');
    $routes->get('pembayaran/create', 'Pembayaran::create');
    $routes->post('pembayaran/store', 'Pembayaran::store');
    $routes->get('pembayaran/show/(:num)', 'Pembayaran::show/$1');
    $routes->get('pembayaran/cetak-invoice/(:num)', 'Pembayaran::cetakInvoice/$1');
    $routes->get('pembayaran/delete/(:num)', 'Pembayaran::delete/$1');
    
    // ==================== LAPORAN ROUTES ====================
    $routes->get('laporan', 'Laporan::index');
    $routes->get('laporan/export-excel', 'Laporan::exportExcel');
    $routes->get('laporan/export-pdf', 'Laporan::exportPdf');
    
    // Tambahkan juga route alternatif untuk compatibility
    $routes->get('laporan/exportExcel', 'Laporan::exportExcel');
    $routes->get('laporan/exportPdf', 'Laporan::exportPdf');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}