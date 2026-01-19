<?php 
require_once 'includes/layout.php'; 
render_header("Dashboard"); 

// ==========================================
// 1. QUERY STATISTIK (CARD ATAS)
// ==========================================
$m_menunggu = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM anak_magang WHERE status='Menunggu'"));
$m_aktif    = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM anak_magang WHERE status='Aktif'"));
$t_bagian   = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM bagian"));

// ==========================================
// 2. KONFIGURASI PAGINATION & SEARCH
// ==========================================
$batas = 5; 
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

$keyword = isset($_GET['cari']) ? $_GET['cari'] : "";

// Query Dasar: Status Aktif & Selesai Minggu Ini
$base_query = "FROM anak_magang m
               LEFT JOIN bagian b ON m.id_bagian = b.id_bagian
               WHERE m.status = 'Aktif' 
               AND YEARWEEK(m.tgl_selesai, 1) = YEARWEEK(CURDATE(), 1)";

// Jika ada pencarian, tambahkan filter
if (!empty($keyword)) {
    $base_query .= " AND (m.nama_lengkap LIKE '%$keyword%' 
                     OR m.universitas_instansi LIKE '%$keyword%' 
                     OR b.nama_bagian LIKE '%$keyword%')";
}

// Hitung Total Data
$query_total = mysqli_query($koneksi, "SELECT count(*) as total $base_query");
$total_data = mysqli_fetch_assoc($query_total)['total'];
$total_halaman = ceil($total_data / $batas);

// Ambil Data dengan Limit Pagination
$query_alert = mysqli_query($koneksi, "SELECT m.*, b.nama_bagian 
                                       $base_query 
                                       ORDER BY m.tgl_selesai ASC 
                                       LIMIT $halaman_awal, $batas");
$nomor = $halaman_awal + 1;
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
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center mb-2 mb-md-0">
            <h5 class="m-0 fw-bold text-danger">
                <i class="bi bi-bell-fill me-2"></i> Akan Selesai Minggu Ini
            </h5>
            <span class="badge bg-danger ms-2"><?= $total_data; ?> Peserta</span>
            
            <button type="button" class="btn btn-sm btn-light text-secondary ms-2 rounded-circle" onclick="toggleAlertTable()" title="Sembunyikan/Tampilkan">
                <i id="eyeIcon" class="bi bi-eye-fill"></i>
            </button>
        </div>
        
        <form action="" method="GET" class="d-flex">
            <div class="input-group">
                <input type="text" name="cari" class="form-control form-control-sm" placeholder="Cari nama/kampus..." value="<?= $keyword; ?>">
                
                <button class="btn btn-sm btn-outline-primary" type="submit" title="Cari">
                    <i class="bi bi-search"></i>
                </button>
                
                <?php if(!empty($keyword)): ?>
                    <a href="dashboard.php" class="btn btn-sm btn-outline-danger" title="Reset Pencarian">
                        <i class="bi bi-x-lg"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div id="alertContent">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Nama Peserta</th>
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
                                $tgl_selesai = new DateTime($row['tgl_selesai']);
                                $hari_ini    = new DateTime();
                                $hari_ini->setTime(0,0,0);
                                $tgl_selesai->setTime(0,0,0);
                                $selisih     = $hari_ini->diff($tgl_selesai);
                                $sisa_hari   = (int)$selisih->format('%r%a');
                            ?>
                            <tr>
                                <td class="ps-4 text-muted small"><?= $nomor++; ?></td>
                                <td>
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
                                <td colspan="7" class="text-center py-5">
                                    <div class="opacity-50 mb-3">
                                        <i class="bi bi-search fs-1 text-secondary"></i>
                                    </div>
                                    <h6 class="text-muted fw-bold">Data Tidak Ditemukan</h6>
                                    <p class="text-muted mb-0 small">
                                        <?= !empty($keyword) ? "Tidak ada hasil untuk kata kunci '<strong>$keyword</strong>'" : "Tidak ada peserta yang selesai minggu ini."; ?>
                                    </p>
                                    <?php if(!empty($keyword)): ?>
                                        <a href="dashboard.php" class="btn btn-sm btn-outline-danger mt-3">Reset Pencarian</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white d-flex justify-content-between align-items-center py-3">
            <small class="text-muted">
                * Menampilkan peserta aktif yang jadwalnya berakhir minggu ini.
            </small>

            <?php if ($total_halaman > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item <?= ($halaman <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?halaman=<?= $halaman - 1; ?>&cari=<?= $keyword; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php for ($x = 1; $x <= $total_halaman; $x++): ?>
                        <li class="page-item <?= ($halaman == $x) ? 'active' : ''; ?>">
                            <a class="page-link" href="?halaman=<?= $x; ?>&cari=<?= $keyword; ?>"><?= $x; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= ($halaman >= $total_halaman) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?halaman=<?= $halaman + 1; ?>&cari=<?= $keyword; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div> </div>

<style>
    .hover-scale { transition: transform 0.2s, box-shadow 0.2s; cursor: pointer; }
    .hover-scale:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
    @keyframes blink { 50% { opacity: 0.5; } }
    .blink-badge { animation: blink 1.5s linear infinite; }
</style>

<script>
function toggleAlertTable() {
    var content = document.getElementById("alertContent");
    var icon = document.getElementById("eyeIcon");
    
    if (content.style.display === "none") {
        content.style.display = "block";
        icon.classList.remove("bi-eye-slash-fill");
        icon.classList.add("bi-eye-fill");
    } else {
        content.style.display = "none";
        icon.classList.remove("bi-eye-fill");
        icon.classList.add("bi-eye-slash-fill");
    }
}
</script>

<?php render_footer(); ?>