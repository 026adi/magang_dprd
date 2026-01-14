<?php 
require_once '../../includes/layout.php'; 
render_header("Detail Anak Magang"); 

$id = $_GET['id'];

// 1. Ambil Data Magang
// (Query SELECT * sudah otomatis mengambil kolom 'fakultas' yang baru ditambahkan)
$query = mysqli_query($koneksi, "SELECT anak_magang.*, bagian.nama_bagian 
                                 FROM anak_magang 
                                 LEFT JOIN bagian ON anak_magang.id_bagian = bagian.id_bagian 
                                 WHERE id_magang = '$id'");
$d = mysqli_fetch_assoc($query);

// 2. Ambil Data Riwayat Penempatan
$query_riwayat = mysqli_query($koneksi, "SELECT riwayat_penempatan.*, bagian.nama_bagian 
                                         FROM riwayat_penempatan 
                                         JOIN bagian ON riwayat_penempatan.id_bagian = bagian.id_bagian 
                                         WHERE id_magang = '$id' 
                                         ORDER BY tgl_pindah DESC");
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detail Informasi Magang</h1>
    <div>
        <a href="cetak_surat_jawaban.php?id=<?= $id; ?>" target="_blank" class="btn btn-warning btn-sm me-2">
            <i class="bi bi-printer-fill"></i> Cetak Surat Jawaban
        </a>

        <button type="button" class="btn btn-info text-white btn-sm me-2" data-bs-toggle="modal" data-bs-target="#modalRiwayat">
            <i class="bi bi-clock-history"></i> Lihat Riwayat Mutasi
        </button>
        
        <a href="index.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 text-primary fw-bold"><i class="bi bi-person-lines-fill me-2"></i> Data Lengkap Peserta</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-striped align-middle">
                    <tr>
                        <th width="25%">Nama Lengkap</th>
                        <td class="fw-bold fs-5">: <?= $d['nama_lengkap']; ?></td>
                    </tr>
                    <tr>
                        <th>NIM / NIS</th>
                        <td>: <?= $d['nim_nis']; ?></td>
                    </tr>
                    <tr>
                        <th>Universitas / Instansi</th>
                        <td>: <?= $d['universitas_instansi']; ?></td>
                    </tr>
                    
                    <tr>
                        <th>Fakultas</th>
                        <td>: <?= !empty($d['fakultas']) ? $d['fakultas'] : '-'; ?></td>
                    </tr>
                    <tr>
                        <th>Jurusan</th>
                        <td>: <?= $d['jurusan']; ?></td>
                    </tr>
                    <tr>
                        <th>Bagian Saat Ini</th>
                        <td>: <span class="badge bg-primary fs-6"><?= $d['nama_bagian'] ?? 'Belum ditentukan'; ?></span></td>
                    </tr>
                    <tr>
                        <th>Periode Magang</th>
                        <td>: <?= date('d M Y', strtotime($d['tgl_mulai'])); ?> s/d <?= date('d M Y', strtotime($d['tgl_selesai'])); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>: <?= $d['status']; ?></td>
                    </tr>
                    <tr>
                        <th>Surat Magang</th>
                        <td>: 
                            <?php if(!empty($d['surat_magang'])): ?>
                                <a href="../../assets/uploads/surat/<?= $d['surat_magang']; ?>" target="_blank" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-file-earmark-pdf me-1"></i> Lihat Surat
                                </a>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Tidak ada berkas</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRiwayat" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="exampleModalLabel"><i class="bi bi-clock-history"></i> Riwayat Perpindahan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php if(mysqli_num_rows($query_riwayat) > 0): ?>
            <ul class="list-group list-group-flush">
                <?php while($rw = mysqli_fetch_assoc($query_riwayat)): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold text-primary"><?= $rw['nama_bagian']; ?></div>
                        <small class="text-muted"><?= $rw['keterangan']; ?></small>
                    </div>
                    <span class="badge bg-secondary rounded-pill">
                        <?= date('d M Y', strtotime($rw['tgl_pindah'])); ?>
                    </span>
                </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <div class="text-center py-3 text-muted">Belum ada data riwayat perpindahan.</div>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<?php render_footer(); ?>