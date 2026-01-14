<?php 
require_once 'includes/layout.php'; 
render_header("Dashboard"); 

// Statistik Singkat
// 1. Hitung yang statusnya MENUNGGU
$m_menunggu = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM anak_magang WHERE status='Menunggu'"));

// 2. Hitung yang statusnya AKTIF
$m_aktif    = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM anak_magang WHERE status='Aktif'"));

// 3. Hitung yang statusnya SELESAI
$m_selesai  = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM anak_magang WHERE status='Selesai'"));

// 4. Hitung Total Bagian
$t_bagian   = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM bagian"));
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Selamat Datang, <?= $_SESSION['nama_lengkap']; ?></h1>
</div>

<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-dark h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 fw-bold">Akan Masuk</h6>
                        <h2 class="mb-0 fw-bold"><?= $m_menunggu; ?></h2>
                        <small style="font-size: 0.8rem;">Menunggu Jadwal</small>
                    </div>
                    <i class="bi bi-hourglass-split fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1">Magang Aktif</h6>
                        <h2 class="mb-0 fw-bold"><?= $m_aktif; ?></h2>
                        <small style="font-size: 0.8rem;">Sedang Berjalan</small>
                    </div>
                    <i class="bi bi-people-fill fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1">Selesai Magang</h6>
                        <h2 class="mb-0 fw-bold"><?= $m_selesai; ?></h2>
                        <small style="font-size: 0.8rem;">Alumni</small>
                    </div>
                    <i class="bi bi-person-check-fill fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card bg-dark text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1">Total Bagian</h6>
                        <h2 class="mb-0 fw-bold"><?= $t_bagian; ?></h2>
                        <small style="font-size: 0.8rem;">Divisi Tersedia</small>
                    </div>
                    <i class="bi bi-diagram-3-fill fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mt-4">
    <div class="card-body p-5 text-center">
        <i class="bi bi-info-circle fs-1 text-primary"></i>
        <h3 class="mt-3">Sistem Pengelolaan Anak Magang DPRD</h3>
        <p class="text-muted">Web ini dirancang untuk mempermudah distribusi anak magang ke berbagai bagian secara digital.</p>
        <a href="modules/magang/index.php" class="btn btn-outline-primary mt-2">Mulai Kelola Data</a>
    </div>
</div>

<?php render_footer(); ?>