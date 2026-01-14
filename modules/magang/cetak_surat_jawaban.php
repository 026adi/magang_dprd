<?php
include_once '../../config/koneksi.php';

// Ambil ID dari URL
$id = $_GET['id'];

// Ambil Data Anak Magang & Bagian
// Pastikan kolom 'fakultas' sudah ditambahkan di database (ALTER TABLE)
$query = mysqli_query($koneksi, "SELECT anak_magang.*, bagian.nama_bagian 
                                 FROM anak_magang 
                                 LEFT JOIN bagian ON anak_magang.id_bagian = bagian.id_bagian 
                                 WHERE id_magang = '$id'");
$d = mysqli_fetch_assoc($query);

// Fungsi Format Tanggal Indonesia
function tgl_indo($tanggal){
    $bulan = array (
        1 =>   'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Jawaban Magang - <?= $d['nama_lengkap']; ?></title>
    <style>
        /* Reset dan Font Standar Surat */
        body { 
            font-family: "Times New Roman", Times, serif; 
            font-size: 12pt; 
            background-color: #FAFAFA; 
            margin: 0; 
            padding: 0; 
        }

        /* Konfigurasi Kertas A4 */
        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: 10mm auto;
            border: 1px solid #D3D3D3;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        /* Settingan saat di-Print */
        @media print {
            body { background: white; }
            .page { margin: 0; border: none; width: auto; height: auto; box-shadow: none; padding: 0; }
            .no-print { display: none; }
            @page { size: A4; margin: 2.5cm 2cm 2cm 2.5cm; } /* Margin atas-kiri-bawah-kanan */
        }

        /* --- KOP SURAT --- */
        .header { text-align: center; margin-bottom: 5px; }
        .header h3 { margin: 0; font-size: 14pt; font-weight: normal; text-transform: uppercase; letter-spacing: 1px; }
        .header h2 { margin: 0; font-size: 18pt; font-weight: bold; text-transform: uppercase; margin-top: 5px; }
        .header p { margin: 0; font-size: 10pt; line-height: 1.3; }
        
        /* Garis Kop Surat */
        .garis-kop {
            border-top: 4px solid black;
            border-bottom: 1px solid black;
            height: 2px;
            margin-top: 10px;
            margin-bottom: 25px;
        }

        /* --- ISI SURAT --- */
        .tgl-surat { float: right; margin-bottom: 10px; }
        
        .tabel-info { width: 100%; margin-bottom: 20px; }
        .tabel-info td { vertical-align: top; }

        .isi-surat { text-align: justify; line-height: 1.5; margin-bottom: 20px; }
        .isi-surat p { margin-bottom: 10px; }

        /* Tabel Data Mahasiswa */
        .tabel-siswa { width: 100%; margin-left: 20px; margin-bottom: 20px; }
        .tabel-siswa td { padding: 2px 5px; vertical-align: top; }

        /* --- TANDA TANGAN --- */
        .ttd-container { 
            float: right; 
            width: 300px; 
            text-align: left; 
            margin-top: 30px; 
        }
        .ttd-jabatan { margin-bottom: 70px; } 
        .ttd-nama { font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>

    <div class="no-print" style="text-align: center; padding: 20px; background: #333;">
        <button onclick="window.print()" style="padding: 10px 20px; font-weight: bold; cursor: pointer; font-size: 16px;">🖨️ Cetak Surat / Simpan PDF</button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer; font-size: 16px;">❌ Tutup Tab</button>
    </div>

    <div class="page">
        <div class="header">
            <h3>PEMERINTAH KOTA YOGYAKARTA</h3>
            <h2>SEKRETARIAT DPRD</h2>
            <p>Jl. IPDA Tut Harsono No. 43 Yogyakarta Kode Pos 55165</p>
            <p>Telp. (0274) 540650 Fax. (0274) 540651</p>
            <p>EMAIL: dprd@jogjakota.go.id</p>
            <p>HOTLINE SMS: 08122780001 HOTLINE EMAIL: upik@jogjakota.go.id</p>
            <p>WEBSITE: www.setwan.jogjakota.go.id</p>
        </div>
        <div class="garis-kop"></div>

        <div class="tgl-surat">
            Yogyakarta, <?= tgl_indo(date('Y-m-d')); ?>
        </div>
        <div style="clear: both;"></div>

        <table class="tabel-info" style="width: 60%;">
            <tr>
                <td width="80px">Kepada</td>
                <td width="10px">:</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3">
                    Yth. <?= !empty($d['fakultas']) ? "Dekan " . $d['fakultas'] : "Kepala Sekolah"; ?> <br>
                    <strong><?= $d['universitas_instansi']; ?></strong><br>
                    Di Tempat
                </td>
            </tr>
        </table>

        <table class="tabel-info" style="width: 100%; margin-top: 20px;">
            <tr>
                <td width="80px">No.</td>
                <td width="10px">:</td>
                <td>500.15.6 / .....</td> 
            </tr>
            <tr>
                <td>Lamp.</td>
                <td>:</td>
                <td>-</td>
            </tr>
            <tr>
                <td>Hal.</td>
                <td>:</td>
                <td><strong>Jawaban Izin Magang</strong></td>
            </tr>
        </table>

        <div class="isi-surat">
            <p>
                Menindaklanjuti Surat dari <strong><?= !empty($d['fakultas']) ? "Dekan " . $d['fakultas'] : "Kepala Sekolah"; ?> <?= $d['universitas_instansi']; ?></strong> 
                perihal izin kerja praktik / magang, bersama ini kami sampaikan Sekretariat DPRD Kota Yogyakarta <strong>bersedia menerima</strong> mahasiswa/siswa 
                sebagaimana tersebut di bawah ini untuk melaksanakan magang di Sekretariat DPRD Kota Yogyakarta 
                terhitung mulai tanggal <strong><?= tgl_indo($d['tgl_mulai']); ?> s.d <?= tgl_indo($d['tgl_selesai']); ?></strong>.
            </p>
            <p>Adapun data peserta tersebut adalah:</p>

            <table class="tabel-siswa">
                <tr>
                    <td width="150px">Nama</td>
                    <td>: <strong><?= $d['nama_lengkap']; ?></strong></td>
                </tr>
                <tr>
                    <td>NIM / NIS</td>
                    <td>: <?= $d['nim_nis']; ?></td>
                </tr>
                <tr>
                    <td>Program Studi</td>
                    <td>: <?= $d['jurusan']; ?></td>
                </tr>
                 <tr>
                    <td>Penempatan</td>
                    <td>: <?= $d['nama_bagian'] ?? 'Sekretariat DPRD'; ?></td>
                </tr>
            </table>

            <p>Demikian atas perhatiannya kami ucapkan terima kasih.</p>
        </div>

        <div class="ttd-container">
            <div class="ttd-jabatan">Sekretaris DPRD,</div>
            <br><br><br><br> <div class="ttd-nama">Antonius Bambang Agung Adrijanto, S.I.P.</div>
            <div>NIP. 19710630 199603 1 003</div>
        </div>
    </div>

</body>
</html>