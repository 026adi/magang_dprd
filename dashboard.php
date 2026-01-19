<?php 
require_once 'includes/layout.php'; 
render_header("Dashboard"); 

// ==========================================
// 1. QUERY STATISTIK (CARD ATAS)
// ==========================================
// Hitung Menunggu
$m_menunggu = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM anak_magang WHERE status='Menunggu'"));

// Hitung Aktif
$m_aktif    = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM anak_magang WHERE status='Aktif'"));

// Hitung Selesai (Opsional jika ingin ditampilkan)
$m_selesai  = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM anak_magang WHERE status='Selesai'"));

// Hitung Total Bagian
$t_bagian   = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM bagian"));


// ==========================================
// 2. QUERY TABEL "SELESAI MINGGU INI"
// ==========================================
// Logic: Cari yang statusnya 'Aktif' DAN tanggal selesainya ada di minggu kalender ini (Senin-Minggu)
$query_alert = mysqli_query($koneksi, "SELECT m.*, b.nama_bagian 
                                       FROM anak_magang m
                                       LEFT JOIN bagian b ON m.id_bagian = b.id_bagian
                                       WHERE m.status = 'Aktif' 
                                       AND YEARWEEK(m.tgl_selesai, 1) = YEARWEEK(CURDATE(), 1)
                                       ORDER BY m.tgl_selesai ASC");
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Selamat Datang, <?= isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : 'Admin'; ?></h1>
</div>

<div class="row">
    
    <div class="col-md-4 mb-3">
        <div class="card bg-warning text-dark h-100 shadow-sm position-relative hover-scale border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 fw-bold opacity-75">Akan Masuk</h6>
                        <h2 class="mb-0 fw-bold"><?= $m_menunggu; ?></h2>
                        <small class="fw-semibold">Menunggu Jadwal</small>
                    </div>
                    <i class="bi bi-hourglass-split fs-1 opacity-50"></i>
                </div>
                <a href="modules/magang/index.php?status=Menunggu" class="stretched-link"></a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card bg-primary text-white h-100 shadow-sm position-relative hover-scale border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 fw-bold opacity-75">Magang Aktif</h6>
                        <h2 class="mb-0 fw-bold"><?= $m_aktif; ?></h2>
                        <small class="fw-semibold">Sedang Berjalan</small>
                    </div>
                    <i class="bi bi-people-fill fs-1 opacity-50"></i>
                </div>
                <a href="modules/magang/index.php?status=Aktif" class="stretched-link"></a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card bg-dark text-white h-100 shadow-sm position-relative hover-scale border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 fw-bold opacity-75">Total Bagian</h6>
                        <h2 class="mb-0 fw-bold"><?= $t_bagian; ?></h2>
                        <small class="fw-semibold">Divisi Tersedia</small>
                    </div>
                    <i class="bi bi-diagram-3-fill fs-1 opacity-50"></i>
                </div>
                <a href="modules/bagian/index.php" class="stretched-link"></a>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mt-4 border-0">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
        <h5 class="m-0 fw-bold text-danger">
            <i class="bi bi-bell-fill me-2"></i> Akan Selesai Minggu Ini
        </h5>
        <span class="badge bg-danger ms-2"><?= mysqli_num_rows($query_alert); ?> Peserta</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Nama Peserta</th>
                        <th>Instansi / Kampus</th>
                        <th>Bagian</th>
                        <th>Tanggal Selesai</th>
                        <th class="text-center">Sisa Waktu</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($query_alert) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($query_alert)): 
                            // Hitung Sisa Hari
                            $tgl_selesai = new DateTime($row['tgl_selesai']);
                            $hari_ini    = new DateTime();
                            // Reset jam agar hitungan hari akurat
                            $hari_ini->setTime(0,0,0);
                            $tgl_selesai->setTime(0,0,0);
                            
                            $selisih     = $hari_ini->diff($tgl_selesai);
                            $sisa_hari   = (int)$selisih->format('%r%a'); // %r = tanda +/-, %a = total hari
                        ?>
                        <tr>
                            <td class="ps-4">
                                <strong><?= $row['nama_lengkap']; ?></strong><br>
                                <small class="text-muted">NIM: <?= $row['nim_nis'] ?? '-'; ?></small>
                            </td>
                            <td><?= $row['universitas_instansi']; ?></td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-dark">
                                    <?= $row['nama_bagian'] ?? '-'; ?>
                                </span>
                            </td>
                            <td class="fw-bold text-danger">
                                <?= date('d M Y', strtotime($row['tgl_selesai'])); ?>
                            </td>
                            <td class="text-center">
                                <?php if ($sisa_hari == 0): ?>
                                    <span class="badge bg-danger blink-badge">HARI INI</span>
                                <?php elseif ($sisa_hari > 0): ?>
                                    <span class="badge bg-warning text-dark"><?= $sisa_hari; ?> Hari Lagi</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Lewat <?= abs($sisa_hari); ?> Hari</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="modules/magang/detail.php?id=<?= $row['id_magang']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="opacity-50 mb-3">
                                    <i class="bi bi-calendar-check fs-1 text-secondary"></i>
                                </div>
                                <h6 class="text-muted fw-bold">Aman!</h6>
                                <p class="text-muted mb-0 small">Tidak ada peserta magang yang selesai minggu ini.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white small text-muted">
        * Menampilkan peserta aktif yang jadwalnya berakhir pada minggu kalender ini (Senin - Minggu).
    </div>
</div>

<style>
    .hover-scale {
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
    }
    .hover-scale:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    /* Animasi Kedip untuk yang selesai Hari Ini */
    @keyframes blink { 50% { opacity: 0.5; } }
    .blink-badge { animation: blink 1.5s linear infinite; }
</style>

<?php render_footer(); ?>