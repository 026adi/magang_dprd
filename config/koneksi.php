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
 * Ganti 'magang_dprd' sesuai dengan nama folder kamu di htdocs
 */
if (!function_exists('base_url')) {
    function base_url($path = "") {
        // Sesuaikan nama folder project kamu di sini
        return "http://localhost/magang_dprd/" . $path;
    }
}

// 5. Set timezone agar pencatatan waktu upload & status akurat (WIB)
date_default_timezone_set('Asia/Jakarta');

/**
 * 6. LOGIKA UPDATE STATUS REAL-TIME (OTOMATIS)
 * Fungsi ini akan dijalankan setiap kali halaman web dibuka/direfresh.
 * Ia membandingkan Tanggal Hari Ini vs Tanggal Mulai/Selesai di Database.
 */
function update_status_otomatis($koneksi) {
    $hari_ini = date('Y-m-d');

    // A. Set MENUNGGU: Jika hari ini < tanggal mulai
    $q_menunggu = "UPDATE anak_magang SET status='Menunggu' 
                   WHERE '$hari_ini' < tgl_mulai";
    mysqli_query($koneksi, $q_menunggu);

    // B. Set AKTIF: Jika hari ini >= tanggal mulai DAN <= tanggal selesai
    $q_aktif = "UPDATE anak_magang SET status='Aktif' 
                WHERE '$hari_ini' >= tgl_mulai AND '$hari_ini' <= tgl_selesai";
    mysqli_query($koneksi, $q_aktif);

    // C. Set SELESAI: Jika hari ini > tanggal selesai
    $q_selesai = "UPDATE anak_magang SET status='Selesai' 
                  WHERE '$hari_ini' > tgl_selesai";
    mysqli_query($koneksi, $q_selesai);
}

// Panggil fungsi ini agar langsung dieksekusi
update_status_otomatis($koneksi);
?>