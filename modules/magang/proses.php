<?php
include_once '../../config/koneksi.php';

// ==========================================
// 1. LOGIKA SIMPAN DATA (INSERT)
// ==========================================
if (isset($_POST['simpan'])) {
    $nama      = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $nim       = $_POST['nim_nis'];
    $univ      = $_POST['universitas_instansi'];
    $jurusan   = $_POST['jurusan'];
    $no_hp     = $_POST['no_hp']; // <-- TAMBAHAN: Tangkap No HP
    $id_bagian = $_POST['id_bagian'];
    $tgl_awal  = $_POST['tgl_mulai'];
    $tgl_akhir = $_POST['tgl_selesai'];

    // Proses Nama File Foto
    $foto_name = $_FILES['foto']['name'];
    $foto_tmp  = $_FILES['foto']['tmp_name'];
    $foto_ext  = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));
    $foto_baru = "FOTO_" . time() . "_" . rand(10,99) . "." . $foto_ext;
    $path_foto = "../../assets/uploads/foto/" . $foto_baru;

    // Proses Nama File Surat (PDF)
    $surat_name = $_FILES['surat_magang']['name'];
    $surat_tmp  = $_FILES['surat_magang']['tmp_name'];
    $surat_ext  = strtolower(pathinfo($surat_name, PATHINFO_EXTENSION));
    $surat_baru = "SURAT_" . time() . "_" . rand(10,99) . "." . $surat_ext;
    $path_surat = "../../assets/uploads/surat/" . $surat_baru;

    if (move_uploaded_file($foto_tmp, $path_foto) && move_uploaded_file($surat_tmp, $path_surat)) {
        // Query Insert (Tambahkan no_hp)
        $query = "INSERT INTO anak_magang 
                  (nama_lengkap, nim_nis, universitas_instansi, jurusan, no_hp, tgl_mulai, tgl_selesai, id_bagian, foto, surat_magang) 
                  VALUES 
                  ('$nama', '$nim', '$univ', '$jurusan', '$no_hp', '$tgl_awal', '$tgl_akhir', '$id_bagian', '$foto_baru', '$surat_baru')";
        
        if (mysqli_query($koneksi, $query)) {
            header("location:index.php?pesan=sukses");
        } else {
            header("location:tambah.php?pesan=gagal_db");
        }
    } else {
        header("location:tambah.php?pesan=gagal_upload");
    }
}

// ==========================================
// 2. LOGIKA UPDATE DATA
// ==========================================
else if (isset($_POST['update'])) {
    $id        = $_POST['id_magang'];
    $nama      = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $nim       = $_POST['nim_nis'];
    $univ      = $_POST['universitas_instansi'];
    $jurusan   = $_POST['jurusan'];
    $no_hp     = $_POST['no_hp']; // <-- TAMBAHAN: Update No HP juga
    $id_bagian = $_POST['id_bagian'];
    $tgl_awal  = $_POST['tgl_mulai'];
    $tgl_akhir = $_POST['tgl_selesai'];

    // Ambil data lama untuk cek nama file lama
    $cek_data  = mysqli_query($koneksi, "SELECT * FROM anak_magang WHERE id_magang='$id'");
    $old       = mysqli_fetch_assoc($cek_data);

    // Cek apakah ada upload FOTO baru
    if ($_FILES['foto']['name'] != "") {
        $foto_baru = "FOTO_" . time() . "_" . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "../../assets/uploads/foto/" . $foto_baru);
        if (file_exists("../../assets/uploads/foto/" . $old['foto'])) {
            unlink("../../assets/uploads/foto/" . $old['foto']);
        }
    } else {
        $foto_baru = $old['foto']; 
    }

    // Cek apakah ada upload SURAT baru
    if ($_FILES['surat_magang']['name'] != "") {
        $surat_baru = "SURAT_" . time() . "_" . $_FILES['surat_magang']['name'];
        move_uploaded_file($_FILES['surat_magang']['tmp_name'], "../../assets/uploads/surat/" . $surat_baru);
        if (file_exists("../../assets/uploads/surat/" . $old['surat_magang'])) {
            unlink("../../assets/uploads/surat/" . $old['surat_magang']);
        }
    } else {
        $surat_baru = $old['surat_magang']; 
    }

    // Query Update (Tambahkan no_hp)
    $query = "UPDATE anak_magang SET 
              nama_lengkap='$nama', nim_nis='$nim', universitas_instansi='$univ', 
              jurusan='$jurusan', no_hp='$no_hp', tgl_mulai='$tgl_awal', tgl_selesai='$tgl_akhir', 
              id_bagian='$id_bagian', foto='$foto_baru', surat_magang='$surat_baru' 
              WHERE id_magang='$id'";

    if (mysqli_query($koneksi, $query)) {
        header("location:index.php?pesan=update");
    } else {
        header("location:edit.php?id=$id&pesan=gagal");
    }
}

// ==========================================
// 3. LOGIKA HAPUS DATA (DELETE)
// ==========================================
else if (isset($_GET['aksi']) && $_GET['aksi'] == "hapus") {
    $id = $_GET['id'];
    
    // Ambil nama file agar bisa dihapus dari folder assets
    $cek_data = mysqli_query($koneksi, "SELECT * FROM anak_magang WHERE id_magang='$id'");
    $data     = mysqli_fetch_assoc($cek_data);

    if (file_exists("../../assets/uploads/foto/" . $data['foto'])) {
        unlink("../../assets/uploads/foto/" . $data['foto']);
    }
    if (file_exists("../../assets/uploads/surat/" . $data['surat_magang'])) {
        unlink("../../assets/uploads/surat/" . $data['surat_magang']);
    }

    $query = "DELETE FROM anak_magang WHERE id_magang='$id'";
    if (mysqli_query($koneksi, $query)) {
        header("location:index.php?pesan=hapus");
    }
}
?>