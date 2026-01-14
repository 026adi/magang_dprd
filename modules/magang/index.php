<?php
require_once '../../includes/layout.php';
render_header("Data Anak Magang");

// --- 1. KONFIGURASI PAGINATION ---
$limit = 15; // Batas maksimal baris per halaman
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// --- 2. LOGIKA FILTER & PENCARIAN ---
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : "";
$status  = isset($_GET['status']) ? $_GET['status'] : "";
$urutan  = isset($_GET['urutan']) ? $_GET['urutan'] : "terbaru";

// Siapkan kondisi WHERE
$whereClause = [];

if (!empty($keyword)) {
    $safe_keyword = mysqli_real_escape_string($koneksi, $keyword);
    $whereClause[] = "(nama_lengkap LIKE '%$safe_keyword%' OR 
                       nim_nis LIKE '%$safe_keyword%' OR 
                       universitas_instansi LIKE '%$safe_keyword%' OR 
                       jurusan LIKE '%$safe_keyword%')";
}

if (!empty($status)) {
    $safe_status = mysqli_real_escape_string($koneksi, $status);
    $whereClause[] = "status = '$safe_status'";
}

$sql_where = "";
if (count($whereClause) > 0) {
    $sql_where = "WHERE " . implode(" AND ", $whereClause);
}

// --- 3. HITUNG TOTAL DATA (Untuk Navigasi Halaman) ---
// Kita butuh tahu total data SEBELUM dipotong LIMIT untuk tahu berapa total halamannya
$query_count = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM anak_magang $sql_where");
$data_count  = mysqli_fetch_assoc($query_count);
$total_data  = $data_count['total'];
$total_pages = ceil($total_data / $limit);

// Atur Pengurutan
switch ($urutan) {
    case 'terlama':
        $sql_order = "ORDER BY id_magang ASC";
        break;
    case 'az':
        $sql_order = "ORDER BY nama_lengkap ASC";
        break;
    case 'za':
        $sql_order = "ORDER BY nama_lengkap DESC";
        break;
    default:
        $sql_order = "ORDER BY id_magang DESC";
        break;
}

// --- 4. QUERY UTAMA DENGAN LIMIT (Untuk Data Tabel) ---
$query_str = "SELECT anak_magang.*, bagian.nama_bagian 
              FROM anak_magang 
              LEFT JOIN bagian ON anak_magang.id_bagian = bagian.id_bagian 
              $sql_where 
              $sql_order 
              LIMIT $limit OFFSET $offset"; // Tambahkan LIMIT dan OFFSET disini

$query = mysqli_query($koneksi, $query_str);
$jumlah_data_tampil = mysqli_num_rows($query);

// String parameter URL untuk navigasi (agar filter tidak hilang saat pindah page)
$url_params = "&keyword=$keyword&status=$status&urutan=$urutan";
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Monitoring Anak Magang</h1>
</div>

<?php if (isset($_GET['pesan'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Data berhasil di<?= $_GET['pesan']; ?>!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm mb-4 bg-light">
    <div class="card-body p-3">
        <form action="" method="GET">
            <input type="hidden" name="page" value="1">

            <div class="row g-2 align-items-center">
                <div class="col-md-4 col-12">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="keyword" class="form-control" placeholder="Cari nama / instansi / jurusan..." value="<?= $keyword; ?>">
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="Aktif" <?= $status == 'Aktif' ? 'selected' : ''; ?>>Aktif</option>
                        <option value="Menunggu" <?= $status == 'Menunggu' ? 'selected' : ''; ?>>Menunggu</option>
                        <option value="Selesai" <?= $status == 'Selesai' ? 'selected' : ''; ?>>Selesai</option>
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <select name="urutan" class="form-select">
                        <option value="terbaru" <?= $urutan == 'terbaru' ? 'selected' : ''; ?>>Terbaru</option>
                        <option value="terlama" <?= $urutan == 'terlama' ? 'selected' : ''; ?>>Terlama</option>
                        <option value="az" <?= $urutan == 'az' ? 'selected' : ''; ?>>Nama (A-Z)</option>
                        <option value="za" <?= $urutan == 'za' ? 'selected' : ''; ?>>Nama (Z-A)</option>
                    </select>
                </div>
                <div class="col-md-2 col-12 d-grid d-md-block">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Cari</button>
                    <a href="index.php" class="btn btn-outline-secondary" title="Reset Filter"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
                <div class="col-md-2 col-12 text-md-end text-start mt-2 mt-md-0">
                    <a href="tambah.php" class="btn btn-primary w-100"><i class="bi bi-plus-lg"></i> Tambah Baru</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>Instansi</th>
                        <th>Jurusan</th>
                        <th>Rentang Waktu</th>
                        <th>Penempatan</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($jumlah_data_tampil > 0): ?>
                        <?php $no = $offset + 1;
                        while ($row = mysqli_fetch_assoc($query)): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td>
                                    <strong><?= $row['nama_lengkap']; ?></strong><br>
                                    <small class="text-muted"><?= $row['nim_nis']; ?></small>
                                </td>
                                <td><?= $row['universitas_instansi']; ?></td>
                                <td><?= $row['jurusan']; ?></td>
                                <td>
                                    <small class="d-block text-nowrap">
                                        <i class="bi bi-calendar-event me-1 text-muted"></i>
                                        <?= date('d M Y', strtotime($row['tgl_mulai'])); ?>
                                    </small>
                                    <small class="d-block text-nowrap text-muted">
                                        s.d <?= date('d M Y', strtotime($row['tgl_selesai'])); ?>
                                    </small>
                                </td>
                                <td><span class="badge bg-info text-dark"><?= $row['nama_bagian'] ?? 'Belum Diatur'; ?></span></td>
                                <td>
                                    <?php
                                    if ($row['status'] == 'Aktif') {
                                        $badge_class = 'bg-success';
                                        $icon_status = '<i class="bi bi-check-circle me-1"></i>';
                                    } elseif ($row['status'] == 'Menunggu') {
                                        $badge_class = 'bg-warning text-dark';
                                        $icon_status = '<i class="bi bi-hourglass-split me-1"></i>';
                                    } else {
                                        $badge_class = 'bg-secondary';
                                        $icon_status = '<i class="bi bi-flag-fill me-1"></i>';
                                    }
                                    ?>
                                    <span class="badge <?= $badge_class; ?> p-2"><?= $icon_status . $row['status']; ?></span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="detail.php?id=<?= $row['id_magang']; ?>" class="btn btn-sm btn-outline-primary" title="Detail"><i class="bi bi-eye"></i></a>
                                        <a href="edit.php?id=<?= $row['id_magang']; ?>" class="btn btn-sm btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                        <a href="proses.php?aksi=hapus&id=<?= $row['id_magang']; ?>" class="btn btn-sm btn-outline-danger btn-hapus" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-search display-6 d-block mb-3"></i>
                                <h5>Data tidak ditemukan</h5>
                                <p>Coba ubah kata kunci pencarian atau reset filter.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($total_pages > 1): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-end mb-0">

                    <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=1<?= $url_params; ?>" title="Halaman Pertama">First</a>
                    </li>
                    <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?= $page - 1; ?><?= $url_params; ?>">Prev</a>
                    </li>

                    <li class="page-item disabled">
                        <span class="page-link text-dark">
                            Halaman <?= $page; ?> dari <?= $total_pages; ?> <small class="text-muted">(Total <?= $total_data; ?> Data)</small>
                        </span>
                    </li>

                    <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?= $page + 1; ?><?= $url_params; ?>">Next</a>
                    </li>
                    <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?= $total_pages; ?><?= $url_params; ?>" title="Halaman Terakhir">Last</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>

    </div>
</div>

<?php render_footer(); ?>