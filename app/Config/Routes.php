<?php

use CodeIgniter\Router\RouteCollection;
$routes->get('/', 'Dashboard::index');

// Dashboard
$routes->get('dashboard', 'Dashboard::index');

// Data Pengecekan
$routes->get('data-pengecekan', 'DataPengecekan::index');
$routes->get('data-pengecekan/export', 'DataPengecekan::exportCsv');
$routes->get('data-pengecekan/delete/(:num)', 'DataPengecekan::delete/$1');

// Analisis Data (Historis)
$routes->get('analisis-data', 'AnalisisData::index');
$routes->get('analisis-data/chart-data', 'AnalisisData::getChartData');
$routes->get('analisis-data/export-pdf', 'AnalisisData::exportPdf');
// Route untuk halaman Analisis Data
$routes->get('analisis-data', 'AnalisisData::index');
// Route AJAX untuk mengambil data grafik
$routes->get('analisis-data/chart-data', 'AnalisisData::chartData');
// Route POST untuk Menerima Gambar Base64 Grafik & Menghasilkan PDF
$routes->post('analisis-data/export-pdf', 'AnalisisData::exportPdf');


// Form CERTA
$routes->get('form-certa', 'FormCerta::index');
$routes->post('form-certa/simpan', 'FormCerta::simpan');
$routes->get('form-certa/success', 'FormCerta::success');
