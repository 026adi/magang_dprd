<?php
// 1. Konfigurasi Database
$host = "localhost";
$user = "root";      // Default XAMPP
$pass = "";          // Default XAMPP kosong
$db   = "db_magang_dprd";

// 2. Membuat Koneksi
$koneksi = mysqli_connect($host, $user, $pass, $db);

// 3. Cek Koneksi
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

/**
 * 4. Fungsi Helper Base URL
 * Fungsi ini agar pemanggilan file CSS/JS/Gambar konsisten
 * Ganti 'web-magang-dprd' sesuai dengan nama folder kamu di htdocs
 */
if (!function_exists('base_url')) {
    function base_url($path = "") {
        return "http://localhost/magang_dprd/" . $path;
    }
}

// 5. Set timezone agar pencatatan waktu upload akurat (WIB)
date_default_timezone_set('Asia/Jakarta');
?>