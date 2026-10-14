<?php
// Konfigurasi database aplikasi.

$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'indomaret_rpl4';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
    mysqli_set_charset($conn, 'utf8mb4');
} catch (mysqli_sql_exception $exception) {
    error_log('Koneksi database Indomaret gagal: ' . $exception->getMessage());
    http_response_code(500);
    exit('Koneksi database gagal. Pastikan MySQL Laragon aktif dan konfigurasi database sudah benar succes berhasil.');
}
