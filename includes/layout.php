<?php
session_start();
include_once __DIR__ . '/../config/koneksi.php';

/**
 * Fungsi Header & Sidebar (Bagian Atas)
 */
function render_header($judul = "Sistem Magang DPRD") {
    // 1. Cek Session Login
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
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">

    <style>
        /* CSS untuk Running Text & Tanggal */
        .running-text {
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }
        .date-display {
            font-family: 'Poppins', sans-serif;
            font-size: 0.85rem;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        /* CSS Fix untuk Sidebar */
        .sidebar .nav-link {
            white-space: nowrap !important;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 15px; 
        }
        
        .navbar-custom {
            background-color: #1a237e !important;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold ms-2" href="<?= base_url('dashboard.php'); ?>">
            <i class="bi bi-building"></i> SIMAG DPRD JOGJA
        </a>

        <div class="d-none d-md-block flex-grow-1 mx-3 overflow-hidden text-white border-start border-white border-opacity-25 px-2">
            <marquee behavior="scroll" direction="left" scrollamount="6" class="running-text pt-1">
                <i class="bi bi-info-circle-fill me-2 text-warning"></i>
                Halo, <strong><?= $_SESSION['nama_lengkap']; ?></strong>! Selamat datang di Sistem Informasi Manajemen Anak Magang Sekretariat DPRD Kota Yogyakarta.
            </marquee>
        </div>

        <div class="text-warning me-3 d-none d-lg-block date-display border-end border-white border-opacity-25 pe-3" id="liveDate"></div>

        <div class="text-white me-3 d-none d-md-block small">
            <i class="bi bi-person-circle"></i> <?= $_SESSION['nama_lengkap']; ?>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-3 d-md-block bg-light sidebar collapse shadow-sm">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('dashboard.php'); ?>">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-nowrap" href="<?= base_url('modules/magang/index.php'); ?>">
                            <i class="bi bi-people me-2"></i> Data Anak Magang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-nowrap" href="<?= base_url('modules/bagian/index.php'); ?>">
                            <i class="bi bi-diagram-3 me-2"></i> Kelola Bagian
                        </a>
                    </li>
                    <li class="nav-item mt-4">
                        <a class="nav-link text-danger fw-bold btn-logout" href="<?= base_url('logout.php'); ?>">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 min-vh-100 d-flex flex-column">
            
            <div class="flex-grow-1 pt-4">
<?php
}

/**
 * Fungsi Footer (Bagian Bawah & Script JS)
 */
function render_footer() {
?>
            </div> 
            
            <footer class="py-3 bg-white border-top text-center mt-auto">
                <span class="text-muted small">&copy; 2026 Sekretariat DPRD Kota Yogyakarta</span>
            </footer>

        </main> 
    </div> 
</div> 

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/script.js'); ?>"></script>

<script>
    function updateDate() {
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        const now = new Date();
        const dateString = `${days[now.getDay()]}, ${String(now.getDate()).padStart(2, '0')} ${months[now.getMonth()]} ${now.getFullYear()}`;
        
        const dateEl = document.getElementById('liveDate');
        if(dateEl) dateEl.innerText = dateString;
    }
    updateDate();
    setInterval(updateDate, 60000); 
</script>

<script>
    document.addEventListener('click', function(e) {
        
        // 1. HANDLER TOMBOL HAPUS (.btn-hapus)
        const targetHapus = e.target.closest('.btn-hapus');
        if (targetHapus) {
            e.preventDefault();
            const href = targetHapus.getAttribute('href');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        }

        // 2. HANDLER TOMBOL LOGOUT (.btn-logout)
        const targetLogout = e.target.closest('.btn-logout');
        if (targetLogout) {
            e.preventDefault();
            const href = targetLogout.getAttribute('href');

            Swal.fire({
                title: 'Yakin ingin keluar?',
                text: "Anda harus login kembali untuk mengakses sistem.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33', // Merah
                cancelButtonColor: '#6c757d', // Abu-abu
                confirmButtonText: 'Ya, Keluar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        }
    });
</script>

</body>
</html>
<?php
}
?>