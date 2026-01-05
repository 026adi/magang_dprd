<?php 
require_once '../../includes/layout.php'; 
render_header("Data Anak Magang"); 

// Query untuk mengambil data magang dan menggabungkan dengan tabel bagian
$query = mysqli_query($koneksi, "SELECT anak_magang.*, bagian.nama_bagian 
                                 FROM anak_magang 
                                 LEFT JOIN bagian ON anak_magang.id_bagian = bagian.id_bagian 
                                 ORDER BY id_magang DESC");
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Monitoring Anak Magang</h1>
    <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Data</a>
</div>

<?php if(isset($_GET['pesan'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Data berhasil di<?= $_GET['pesan']; ?>!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama Lengkap</th>
                        <th>Instansi & Jurusan</th>
                        <th>Penempatan</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while($row = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td>
                            <img src="../../assets/uploads/foto/<?= $row['foto']; ?>" class="rounded-circle" width="45" height="45" style="object-fit: cover;">
                        </td>
                        <td>
                            <strong><?= $row['nama_lengkap']; ?></strong><br>
                            <small class="text-muted"><?= $row['nim_nis']; ?></small>
                        </td>
                        <td>
                            <?= $row['universitas_instansi']; ?><br>
                            <small class="text-primary"><?= $row['jurusan']; ?></small>
                        </td>
                        <td><span class="badge bg-info text-dark"><?= $row['nama_bagian'] ?? 'Belum Diatur'; ?></span></td>
                        <td>
                            <span class="badge <?= ($row['status'] == 'Aktif') ? 'bg-success' : 'bg-secondary'; ?>">
                                <?= $row['status']; ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="detail.php?id=<?= $row['id_magang']; ?>" class="btn btn-sm btn-outline-primary" title="Detail"><i class="bi bi-eye"></i></a>
                                <a href="edit.php?id=<?= $row['id_magang']; ?>" class="btn btn-sm btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                <a href="proses.php?aksi=hapus&id=<?= $row['id_magang']; ?>" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Hapus data ini beserta berkasnya?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php render_footer(); ?>