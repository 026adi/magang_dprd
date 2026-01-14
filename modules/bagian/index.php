<?php
require_once '../../includes/layout.php';

// ==============================================================================
// 1. LOGIKA PROSES DATA (SIMPAN & HAPUS) - Ditaruh paling atas
// ==============================================================================

// A. LOGIKA SIMPAN (Dari Modal)
if (isset($_POST['simpan'])) {
    $kategori    = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $nama_bagian = mysqli_real_escape_string($koneksi, $_POST['nama_bagian']);

    // Cek Duplikasi
    $cek = mysqli_query($koneksi, "SELECT * FROM bagian WHERE kategori='$kategori' AND nama_bagian='$nama_bagian'");
    
    if (mysqli_num_rows($cek) > 0) {
        // Refresh halaman dengan pesan gagal
        echo "<script>window.location='index.php?pesan=gagal';</script>";
    } else {
        $query = "INSERT INTO bagian (kategori, nama_bagian) VALUES ('$kategori', '$nama_bagian')";
        if (mysqli_query($koneksi, $query)) {
            echo "<script>window.location='index.php?pesan=sukses';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
        }
    }
}

// B. LOGIKA HAPUS (Dari Tombol Tabel)
if (isset($_GET['aksi']) && $_GET['aksi'] == "hapus") {
    $id = $_GET['id'];

    // Cek Foreign Key (Apakah sedang dipakai anak magang?)
    $cek_pakai = mysqli_query($koneksi, "SELECT * FROM anak_magang WHERE id_bagian='$id'");
    
    if (mysqli_num_rows($cek_pakai) > 0) {
        echo "<script>window.location='index.php?pesan=terpakai';</script>";
    } else {
        // Hapus
        mysqli_query($koneksi, "DELETE FROM riwayat_penempatan WHERE id_bagian='$id'");
        $query = "DELETE FROM bagian WHERE id_bagian='$id'";
        
        if (mysqli_query($koneksi, $query)) {
            echo "<script>window.location='index.php?pesan=hapus';</script>";
        }
    }
}

// ==============================================================================
// 2. TAMPILAN HALAMAN (VIEW)
// ==============================================================================

render_header("Manajemen Bagian / Divisi");

// --- Ambil Data Kategori untuk Filter (Berdasarkan data yg ada) ---
$query_kategori = mysqli_query($koneksi, "SELECT DISTINCT kategori FROM bagian ORDER BY kategori ASC");
$list_kategori = [];
while ($row = mysqli_fetch_assoc($query_kategori)) {
    $list_kategori[] = $row['kategori'];
}

// --- Logika Filter ---
$keyword    = isset($_GET['keyword']) ? $_GET['keyword'] : "";
$f_kategori = isset($_GET['kategori']) ? $_GET['kategori'] : "";
$urutan     = isset($_GET['urutan']) ? $_GET['urutan'] : "az";

$whereClause = [];
if (!empty($keyword)) {
    $safe_keyword = mysqli_real_escape_string($koneksi, $keyword);
    $whereClause[] = "(nama_bagian LIKE '%$safe_keyword%' OR kategori LIKE '%$safe_keyword%')";
}
if (!empty($f_kategori)) {
    $safe_kategori = mysqli_real_escape_string($koneksi, $f_kategori);
    $whereClause[] = "kategori = '$safe_kategori'";
}

$sql_where = "";
if (count($whereClause) > 0) {
    $sql_where = "WHERE " . implode(" AND ", $whereClause);
}

switch ($urutan) {
    case 'za': $sql_order = "ORDER BY kategori DESC, nama_bagian DESC"; break;
    case 'terbaru': $sql_order = "ORDER BY id_bagian DESC"; break;
    default: $sql_order = "ORDER BY kategori ASC, nama_bagian ASC"; break;
}

$query_main = mysqli_query($koneksi, "SELECT * FROM bagian $sql_where $sql_order");
$jumlah_data = mysqli_num_rows($query_main);
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen Bagian / Divisi</h1>
</div>

<?php if (isset($_GET['pesan'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php
        if ($_GET['pesan'] == 'sukses') echo "Data berhasil disimpan!";
        elseif ($_GET['pesan'] == 'hapus') echo "Data berhasil dihapus!";
        elseif ($_GET['pesan'] == 'gagal') echo "Gagal! Data bagian tersebut mungkin sudah ada.";
        elseif ($_GET['pesan'] == 'terpakai') echo "Gagal Hapus! Bagian sedang digunakan oleh data Anak Magang.";
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm mb-4 bg-light">
    <div class="card-body p-3">
        <form action="" method="GET">
            <div class="row g-2 align-items-center">
                <div class="col-md-4 col-12">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="keyword" class="form-control" placeholder="Cari bagian / kategori..." value="<?= $keyword; ?>">
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <select name="kategori" class="form-select">
                        <option value="">- Semua Kategori -</option>
                        <?php foreach ($list_kategori as $cat): ?>
                            <option value="<?= $cat; ?>" <?= ($f_kategori == $cat) ? 'selected' : ''; ?>><?= $cat; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <select name="urutan" class="form-select">
                        <option value="az" <?= $urutan == 'az' ? 'selected' : ''; ?>>Urut A-Z</option>
                        <option value="za" <?= $urutan == 'za' ? 'selected' : ''; ?>>Urut Z-A</option>
                        <option value="terbaru" <?= $urutan == 'terbaru' ? 'selected' : ''; ?>>Terbaru</option>
                    </select>
                </div>
                <div class="col-md-1 col-6 d-grid">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                </div>
                <div class="col-md-1 col-6 d-grid">
                    <a href="index.php" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
                <div class="col-md-1 col-12 d-grid">
                    <button type="button" class="btn btn-success text-white" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">No</th>
                        <th width="30%">Kategori Utama</th>
                        <th>Sub Bagian</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; if ($jumlah_data > 0): while ($row = mysqli_fetch_assoc($query_main)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><span class="badge bg-secondary"><?= $row['kategori']; ?></span></td>
                            <td><strong><?= $row['nama_bagian']; ?></strong></td>
                            <td class="text-center">
                                <a href="index.php?aksi=hapus&id=<?= $row['id_bagian']; ?>" class="btn btn-sm btn-danger btn-hapus">
                                    <i class="bi bi-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Data tidak ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalLabel"><i class="bi bi-plus-circle me-2"></i>Tambah Sub Bagian</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori Utama</label>
                        <select name="kategori" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Bagian Umum">Bagian Umum</option>
                            <option value="Bagian Persidangan">Bagian Persidangan</option>
                            <option value="Bagian FPP">Bagian FPP</option>
                            <option value="Fraksi">Fraksi</option>
                        </select>
                        <div class="form-text">Pilih induk organisasi bagian ini.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Sub Bagian</label>
                        <input type="text" name="nama_bagian" class="form-control" placeholder="Contoh: Protokol / Keuangan" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php render_footer(); ?>