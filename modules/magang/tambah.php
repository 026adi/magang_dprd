<?php
require_once '../../includes/layout.php';
render_header("Tambah Anak Magang");

// Query Bagian
$query_bagian = mysqli_query($koneksi, "SELECT * FROM bagian ORDER BY kategori ASC, nama_bagian ASC");
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tambah Anak Magang</h1>
    <a href="index.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="proses.php" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 border-end">
                    <h5 class="mb-3 text-primary">Data Diri</h5>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" required placeholder="Nama sesuai KTP/KTM">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIM / NIS</label>
                        <input type="text" name="nim_nis" class="form-control" required placeholder="Nomor Induk Mahasiswa/Siswa">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Universitas / Sekolah</label>
                        <input type="text" name="universitas_instansi" class="form-control" required placeholder="Contoh: UGM / SMK 2 Jogja">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fakultas <small class="text-muted">(Opsional untuk SMK)</small></label>
                        <input type="text" name="fakultas" class="form-control" placeholder="Contoh: Fakultas Teknik / Fakultas Ekonomi">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jurusan</label>
                        <input type="text" name="jurusan" class="form-control" required placeholder="Contoh: Teknik Informatika">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. HP / WhatsApp</label>
                        <input type="text" name="no_hp" class="form-control" required placeholder="Contoh: 081234567890">
                    </div>
                </div>

                <div class="col-md-6">
                    <h5 class="mb-3 text-primary">Penempatan & Berkas</h5>

                    <div class="mb-3">
                        <label class="form-label">Bagian / Divisi <small class="text-muted">(Opsional)</small></label>
                        <select name="id_bagian" class="form-select">
                            <option value="">-- Belum Ditentukan --</option>
                            <?php
                            $current_kategori = "";
                            while ($b = mysqli_fetch_assoc($query_bagian)) {
                                if ($b['kategori'] != $current_kategori) {
                                    if ($current_kategori != "") echo "</optgroup>";
                                    echo "<optgroup label='" . $b['kategori'] . "'>";
                                    $current_kategori = $b['kategori'];
                                }
                                echo "<option value='" . $b['id_bagian'] . "'>" . $b['nama_bagian'] . "</option>";
                            }
                            echo "</optgroup>";
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Surat Magang <small class="text-muted">(Opsional)</small></label>
                        <input type="file" name="surat_magang" class="form-control" accept="application/pdf">
                    </div>

                    <div class="row">
                        <div class="col">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tgl_mulai" class="form-control" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tgl_selesai" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <div class="text-end">
                <button type="reset" class="btn btn-light me-2">Reset</button>
                <button type="submit" name="simpan" class="btn btn-primary px-4">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<?php render_footer(); ?>