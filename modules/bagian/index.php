<?php 
require_once '../../includes/layout.php'; 
render_header("Kelola Bagian"); 

// 1. Logika Tambah Bagian
if (isset($_POST['simpan_bagian'])) {
    $nama_bagian = mysqli_real_escape_string($koneksi, $_POST['nama_bagian']);
    mysqli_query($koneksi, "INSERT INTO bagian (nama_bagian) VALUES ('$nama_bagian')");
    echo "<script>window.location='index.php?pesan=simpan';</script>";
}

// 2. Logika Hapus Bagian
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM bagian WHERE id_bagian = '$id'");
    echo "<script>window.location='index.php?pesan=hapus';</script>";
}

// 3. Ambil Data Bagian
$query = mysqli_query($koneksi, "SELECT * FROM bagian ORDER BY nama_bagian ASC");
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen Bagian / Divisi</h1>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">Tambah Bagian Baru</h6>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Bagian</label>
                        <input type="text" name="nama_bagian" class="form-control" placeholder="Contoh: Bagian Keuangan" required>
                    </div>
                    <button type="submit" name="simpan_bagian" class="btn btn-primary w-100">
                        <i class="bi bi-plus-circle me-1"></i> Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th width="10%">No</th>
                            <th>Nama Bagian / Divisi</th>
                            <th width="20%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1; 
                        while($row = mysqli_fetch_assoc($query)): 
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $row['nama_bagian']; ?></td>
                            <td class="text-center">
                                <a href="index.php?hapus=<?= $row['id_bagian']; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Hapus bagian ini? Data anak magang di bagian ini akan kehilangan relasinya.')">
                                    <i class="bi bi-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php render_footer(); ?>