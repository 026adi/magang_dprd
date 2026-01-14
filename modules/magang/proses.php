<?php
include_once '../../config/koneksi.php';

// ==========================================
// 1. LOGIKA SIMPAN DATA (INSERT)
// ==========================================
if (isset($_POST['simpan'])) {
    $nama      = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $nim       = $_POST['nim_nis'];
    $univ      = $_POST['universitas_instansi'];
    // [BARU] Ambil Data Fakultas
    $fakultas  = $_POST['fakultas']; 
    $jurusan   = $_POST['jurusan'];
    $no_hp     = $_POST['no_hp']; 
    $tgl_awal  = $_POST['tgl_mulai'];
    $tgl_akhir = $_POST['tgl_selesai'];
    
    // Pisahkan value mentah dan format SQL
    $post_id_bagian = $_POST['id_bagian']; 
    $sql_id_bagian  = !empty($post_id_bagian) ? "'" . $post_id_bagian . "'" : "NULL";

    // PROSES SURAT
    if (!empty($_FILES['surat_magang']['name'])) {
        $surat_name = $_FILES['surat_magang']['name'];
        $surat_ext  = strtolower(pathinfo($surat_name, PATHINFO_EXTENSION));
        $surat_baru = "SURAT_" . time() . "_" . rand(10,99) . "." . $surat_ext;
        move_uploaded_file($_FILES['surat_magang']['tmp_name'], "../../assets/uploads/surat/" . $surat_baru);
    } else {
        $surat_baru = ""; 
    }

    // QUERY INSERT (Sudah ditambahkan kolom 'fakultas')
    $query = "INSERT INTO anak_magang 
              (nama_lengkap, nim_nis, universitas_instansi, fakultas, jurusan, no_hp, tgl_mulai, tgl_selesai, id_bagian, surat_magang) 
              VALUES 
              ('$nama', '$nim', '$univ', '$fakultas', '$jurusan', '$no_hp', '$tgl_awal', '$tgl_akhir', $sql_id_bagian, '$surat_baru')";
    
    if (mysqli_query($koneksi, $query)) {
        
        // [LOGIKA RIWAYAT] - Penempatan Awal
        $new_id_magang = mysqli_insert_id($koneksi);

        if (!empty($post_id_bagian)) {
            $tgl_skrg = date('Y-m-d');
            mysqli_query($koneksi, "INSERT INTO riwayat_penempatan (id_magang, id_bagian, tgl_pindah, keterangan) 
                                    VALUES ('$new_id_magang', '$post_id_bagian', '$tgl_skrg', 'Penempatan Awal')");
        }

        header("location:index.php?pesan=sukses");
    } else {
        echo "Error: " . mysqli_error($koneksi); 
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
    // [BARU] Ambil Data Fakultas
    $fakultas  = $_POST['fakultas'];
    $jurusan   = $_POST['jurusan'];
    $no_hp     = $_POST['no_hp'];
    $tgl_awal  = $_POST['tgl_mulai'];
    $tgl_akhir = $_POST['tgl_selesai'];
    
    $post_id_bagian = $_POST['id_bagian'];
    $sql_id_bagian  = !empty($post_id_bagian) ? "'" . $post_id_bagian . "'" : "NULL";

    // Ambil data lama 
    $cek_data  = mysqli_query($koneksi, "SELECT * FROM anak_magang WHERE id_magang='$id'");
    $old       = mysqli_fetch_assoc($cek_data);
    $old_id_bagian = $old['id_bagian'];

    // Update Surat
    if (!empty($_FILES['surat_magang']['name'])) {
        $surat_baru = "SURAT_" . time() . "_" . $_FILES['surat_magang']['name'];
        move_uploaded_file($_FILES['surat_magang']['tmp_name'], "../../assets/uploads/surat/" . $surat_baru);
        if ($old['surat_magang'] != "" && file_exists("../../assets/uploads/surat/" . $old['surat_magang'])) {
            unlink("../../assets/uploads/surat/" . $old['surat_magang']);
        }
    } else {
        $surat_baru = $old['surat_magang']; 
    }

    // QUERY UPDATE (Sudah ditambahkan update 'fakultas')
    $query = "UPDATE anak_magang SET 
              nama_lengkap='$nama', nim_nis='$nim', universitas_instansi='$univ', fakultas='$fakultas',
              jurusan='$jurusan', no_hp='$no_hp', tgl_mulai='$tgl_awal', tgl_selesai='$tgl_akhir', 
              id_bagian=$sql_id_bagian, surat_magang='$surat_baru' 
              WHERE id_magang='$id'";

    if (mysqli_query($koneksi, $query)) {

        // [LOGIKA RIWAYAT] - Mutasi / Pindah
        if (!empty($post_id_bagian) && $post_id_bagian != $old_id_bagian) {
            $tgl_skrg = date('Y-m-d');
            mysqli_query($koneksi, "INSERT INTO riwayat_penempatan (id_magang, id_bagian, tgl_pindah, keterangan) 
                                    VALUES ('$id', '$post_id_bagian', '$tgl_skrg', 'Mutasi / Pindah Bagian')");
        }

        header("location:index.php?pesan=update");
    } else {
        header("location:edit.php?id=$id&pesan=gagal");
    }
}

// ==========================================
// 3. LOGIKA HAPUS DATA
// ==========================================
else if (isset($_GET['aksi']) && $_GET['aksi'] == "hapus") {
    $id = $_GET['id'];
    $cek_data = mysqli_query($koneksi, "SELECT * FROM anak_magang WHERE id_magang='$id'");
    $data     = mysqli_fetch_assoc($cek_data);

    if ($data['surat_magang'] != "" && file_exists("../../assets/uploads/surat/" . $data['surat_magang'])) {
        unlink("../../assets/uploads/surat/" . $data['surat_magang']);
    }

    mysqli_query($koneksi, "DELETE FROM anak_magang WHERE id_magang='$id'");
    header("location:index.php?pesan=hapus");
}
?>