<?php 
require_once '../../includes/layout.php'; 
render_header("Detail Anak Magang"); 

$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT anak_magang.*, bagian.nama_bagian 
                                 FROM anak_magang 
                                 LEFT JOIN bagian ON anak_magang.id_bagian = bagian.id_bagian 
                                 WHERE id_magang = '$id'");
$d = mysqli_fetch_assoc($query);
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detail Informasi Magang</h1>
    <a href="index.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm text-center p-3">
            <img src="../../assets/uploads/foto/<?= $d['foto']; ?>" class="img-fluid rounded mb-3 shadow-sm" alt="Foto Profil">
            <h4 class="fw-bold mb-0"><?= $d['nama_lengkap']; ?></h4>
            <p class="text-muted"><?= $d['nim_nis']; ?></p>
            <hr>
            <div class="d-grid shadow-sm">
                <a href="../../assets/uploads/surat/<?= $d['surat_magang']; ?>" target="_blank" class="btn btn-danger">
                    <i class="bi bi-file-earmark-pdf"></i> Lihat Surat Magang
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 text-primary fw-bold"><i class="bi bi-info-circle me-2"></i> Data Lengkap</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="30%">Universitas / Instansi</th>
                        <td>: <?= $d['universitas_instansi']; ?></td>
                    </tr>
                    <tr>
                        <th>Jurusan</th>
                        <td>: <?= $d['jurusan']; ?></td>
                    </tr>
                    <tr>
                        <th>Bagian Penempatan</th>
                        <td>: <span class="badge bg-primary"><?= $d['nama_bagian']; ?></span></td>
                    </tr>
                    <tr>
                        <th>Periode Magang</th>
                        <td>: <?= date('d M Y', strtotime($d['tgl_mulai'])); ?> s/d <?= date('d M Y', strtotime($d['tgl_selesai'])); ?></td>
                    </tr>
                    <tr>
                        <th>No. WhatsApp</th>
                        <td>: <?= $d['no_hp'] ?: '-'; ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>: <?= $d['status']; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?php render_footer(); ?>