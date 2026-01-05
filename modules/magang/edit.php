<?php 
require_once '../../includes/layout.php'; 
render_header("Edit Anak Magang"); 

// Ambil ID dari URL
$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM anak_magang WHERE id_magang = '$id'");
$data = mysqli_fetch_assoc($query);

// Jika data tidak ditemukan
if (mysqli_num_rows($query) < 1) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
}

$query_bagian = mysqli_query($koneksi, "SELECT * FROM bagian");
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Data Anak Magang</h1>
    <a href="index.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="proses.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_magang" value="<?= $data['id_magang']; ?>">
            
            <div class="row">
                <div class="col-md-6 border-end">
                    <h5 class="mb-3 text-primary">Data Diri</h5>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" value="<?= $data['nama_lengkap']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIM / NIS</label>
                        <input type="text" name="nim_nis" class="form-control" value="<?= $data['nim_nis']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Universitas / Sekolah</label>
                        <input type="text" name="universitas_instansi" class="form-control" value="<?= $data['universitas_instansi']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jurusan</label>
                        <input type="text" name="jurusan" class="form-control" value="<?= $data['jurusan']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. HP / WhatsApp</label>
                        <input type="text" name="no_hp" class="form-control" value="<?= $data['no_hp']; ?>" required placeholder="Contoh: 08123456789">
                    </div>
                </div>

                <div class="col-md-6">
                    <h5 class="mb-3 text-primary">Penempatan & Berkas</h5>
                    <div class="mb-3">
                        <label class="form-label">Bagian / Divisi</label>
                        <select name="id_bagian" class="form-select" required>
                            <?php while($b = mysqli_fetch_assoc($query_bagian)): ?>
                                <option value="<?= $b['id_bagian']; ?>" <?= ($b['id_bagian'] == $data['id_bagian']) ? 'selected' : ''; ?>>
                                    <?= $b['nama_bagian']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ganti Foto Profil (Kosongkan jika tidak diganti)</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <div class="mt-2">
                            <small class="text-muted d-block mb-1">Foto saat ini:</small>
                            <img src="../../assets/uploads/foto/<?= $data['foto']; ?>" width="80" class="img-thumbnail rounded">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ganti Surat Magang (PDF - Kosongkan jika tidak diganti)</label>
                        <input type="file" name="surat_magang" class="form-control" accept="application/pdf">
                        <div class="mt-2">
                            <small class="text-muted">File saat ini: </small>
                            <a href="../../assets/uploads/surat/<?= $data['surat_magang']; ?>" target="_blank" class="text-decoration-none">
                                <i class="bi bi-file-pdf text-danger"></i> <?= $data['surat_magang']; ?>
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tgl_mulai" class="form-control" value="<?= $data['tgl_mulai']; ?>" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tgl_selesai" class="form-control" value="<?= $data['tgl_selesai']; ?>" required>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <div class="text-end">
                <button type="submit" name="update" class="btn btn-warning px-4 text-white">
                    <i class="bi bi-save me-1"></i> Update Data
                </button>
            </div>
        </form>
    </div>
</div>

<?php render_footer(); ?>