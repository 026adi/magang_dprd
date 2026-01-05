<?php
session_start();
include_once __DIR__ . '/../config/koneksi.php';

/**
 * Fungsi Header & Sidebar (Bagian Atas)
 */
function render_header($judul = "Sistem Magang DPRD") {
    // Proteksi: Cek apakah session login ada
    if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
        header("location:" . base_url('login.php'));
        exit;
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $judul; ?> - DPRD Kota Jogja</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        :root { --dprd-blue: #1a2a6c; }
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        
        /* Navbar */
        .navbar-custom { background: var(--dprd-blue); color: white; }
        
        /* Sidebar */
        .sidebar { min-height: 100vh; background: white; border-right: 1px solid #dee2e6; }
        .nav-link { color: #333; padding: 12px 20px; border-bottom: 1px solid #f1f1f1; }
        .nav-link:hover { background: #f8f9fa; color: var(--dprd-blue); }
        .nav-link.active { background: #e9ecef; color: var(--dprd-blue); font-weight: bold; }
        
        /* Content Area */
        main { padding-top: 20px; }
        .card { border: none; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold ms-2" href="<?= base_url('dashboard.php'); ?>">
            <i class="bi bi-building"></i> SIMAG DPRD JOGJA
        </a>
        <div class="text-white me-3 d-none d-md-block small">
            <i class="bi bi-person-circle"></i> <?= $_SESSION['nama_lengkap']; ?>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse shadow-sm">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('dashboard.php'); ?>">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('modules/magang/index.php'); ?>">
                            <i class="bi bi-people me-2"></i> Data Anak Magang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('modules/bagian/index.php'); ?>">
                            <i class="bi bi-diagram-3 me-2"></i> Kelola Bagian
                        </a>
                    </li>
                    <li class="nav-item mt-4">
                        <a class="nav-link text-danger fw-bold" href="<?= base_url('logout.php'); ?>" onclick="return confirm('Yakin ingin keluar?')">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
<?php
}

/**
 * Fungsi Footer (Bagian Bawah)
 */
function render_footer() {
?>
        </main>
    </div>
</div>

<footer class="footer mt-auto py-3 bg-white border-top text-center mt-5">
    <div class="container">
        <span class="text-muted small">&copy; 2026 Sekretariat DPRD Kota Yogyakarta</span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/script.js'); ?>"></script>

</body>
</html>
<?php
}
?>