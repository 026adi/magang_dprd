<?php 
require_once '../../includes/layout.php'; 
render_header("Kelola Bagian"); 

// 1. Logika Tambah Bagian
if (isset($_POST['simpan_bagian'])) {
    $kategori    = $_POST['kategori']; 
    $nama_bagian = mysqli_real_escape_string($koneksi, $_POST['nama_bagian']);
    
    mysqli_query($koneksi, "INSERT INTO bagian (kategori, nama_bagian) VALUES ('$kategori', '$nama_bagian')");
    echo "<script>window.location='index.php?pesan=simpan';</script>";
}

// 2. Logika Hapus Bagian
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM bagian WHERE id_bagian = '$id'");
    echo "<script>window.location='index.php?pesan=hapus';</script>";
}

// 3. Ambil Data Bagian
$query = mysqli_query($koneksi, "SELECT * FROM bagian ORDER BY kategori ASC, nama_bagian ASC");
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen Bagian / Divisi</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg"></i> Tambah Sub Bagian
    </button>
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
                    <?php 
                    $no = 1; 
                    if(mysqli_num_rows($query) > 0):
                        while($row = mysqli_fetch_assoc($query)): 
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><span class="badge bg-secondary"><?= $row['kategori']; ?></span></td>
                        <td><strong><?= $row['nama_bagian']; ?></strong></td>
                        <td class="text-center">
                            <a href="index.php?hapus=<?= $row['id_bagian']; ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Hapus bagian ini?')">
                                <i class="bi bi-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                    <?php 
                        endwhile; 
                    else:
                    ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">Belum ada data bagian.</td>
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
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori Utama</label>
                        <select name="kategori" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Bagian Umum">Bagian Umum</option>
                            <option value="Bagian Persidangan">Bagian Persidangan</option>
                            <option value="Bagian Humas">Bagian Humas</option>
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
                    <button type="submit" name="simpan_bagian" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php render_footer(); ?>