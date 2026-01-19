<?php
include_once '../../config/koneksi.php';

$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT anak_magang.*, bagian.nama_bagian 
FROM anak_magang 
LEFT JOIN bagian ON anak_magang.id_bagian = bagian.id_bagian 
WHERE id_magang = '$id'");
$d = mysqli_fetch_assoc($query);

function tgl_indo($tanggal)
{
    $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $p = explode('-', $tanggal);
    return $p[2] . ' ' . $bulan[(int)$p[1]] . ' ' . $p[0];
}

header("Content-Type: application/vnd.ms-word");
header("Content-Disposition: attachment; filename=Surat_Jawaban.doc");

function img64($path)
{
    if (!file_exists($path)) return '';
    $type = pathinfo($path, PATHINFO_EXTENSION);
    return 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($path));
}

$logo   = img64('../../assets/img/logo.jpeg');
$aksara = img64('../../assets/img/aksara.jpeg');
$segoro = img64('../../assets/img/segoro.jpeg');
?>

<html xmlns:o="urn:schemas-microsoft-com:office:office"
    xmlns:w="urn:schemas-microsoft-com:office:word"
    xmlns="http://www.w3.org/TR/REC-html40">

<head>
    <meta charset="utf-8">
    <style>
        @page Section1 {
            size: 21.59cm 33.02cm;
            margin: 2.5cm 2.5cm 3cm 3cm;
            mso-header: h1;
            mso-footer: f1;
        }

        div.Section1 {
            page: Section1;
        }

        body {
            font-family: "Times New Roman";
            font-size: 12pt;
            line-height: 1.35;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        p {
            margin: 0;
        }

        .indent {
            text-indent: 1.25cm;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .kop h3 {
            font-size: 14pt;
            margin: 0;
        }

        .kop h2 {
            font-size: 18pt;
            margin: 0;
        }

        .kop p {
            font-size: 10pt;
            line-height: 1.2;
        }

        .garis {
            border-bottom: 3px double #000;
            margin-top: 6px;
        }

        .slogan-title {
            font-size: 9pt;
            font-weight: bold;
        }

        .slogan-desc {
            font-size: 8pt;
            line-height: 1.2;
        }
    </style>
</head>

<body>
    <div class="Section1">

        <!-- ================= HEADER ================= -->
        <div style="mso-element:header" id="h1">
            <table class="kop">
                <tr>
                    <td width="15%" align="center">
                        <img src="<?= $logo ?>" width="85">
                    </td>
                    <td width="85%" class="center">
                        <h3>PEMERINTAH KOTA YOGYAKARTA</h3>
                        <h2>SEKRETARIAT DPRD</h2>
                        <img src="<?= $aksara ?>" height="20"><br>
                        <p>
                            Jl. Ipda Tut Harsono No. 43 Yogyakarta Kode Pos 55165<br>
                            Telp. (0274) 540650 Fax. (0274) 540651<br>
                            EMAIL : dprd@jogjakota.go.id<br>
                            HOTLINE SMS : 08122780001 HOTLINE EMAIL : upik@jogjakota.go.id<br>
                            WEBSITE : www.setwan.jogjakota.go.id
                        </p>
                    </td>
                </tr>
            </table>
            <div class="garis"></div>
        </div>
        <!-- ========================================= -->

        <!-- ISI SURAT -->
        <table style="margin-top:20px;">
            <tr>
                <td width="55%"></td>
                <td width="45%">
                    <p>Yogyakarta, <?= tgl_indo(date('Y-m-d')); ?></p><br>
                    <p>Kepada</p>
                    <p>
                        Yth. Dekan Fakultas Teknik Industri<br>
                        <?= $d['universitas_instansi']; ?><br>
                        di Yogyakarta
                    </p>
                </td>
            </tr>
        </table>

        <table style="margin-top:10px;">
            <tr>
                <td width="10%">No.</td>
                <td width="2%">:</td>
                <td>500.15.6 / ....</td>
            </tr>
            <tr>
                <td>Lamp.</td>
                <td>:</td>
                <td>-</td>
            </tr>
            <tr>
                <td>Hal.</td>
                <td>:</td>
                <td class="bold">Jawaban Izin Magang</td>
            </tr>
        </table>

        <p class="indent" style="margin-top:10px;">
            Menindaklanjuti Surat dari <b><?= $d['universitas_instansi']; ?></b> perihal permohonan izin magang,
            bersama ini kami sampaikan bahwa:
        </p>

        <p class="indent">
            Sekretariat DPRD Kota Yogyakarta <b>BERSEDIA MENERIMA</b> mahasiswa untuk melaksanakan magang
            terhitung mulai tanggal <b><?= tgl_indo($d['tgl_mulai']); ?> s.d. <?= tgl_indo($d['tgl_selesai']); ?></b>.
            Adapun data peserta sebagai berikut:
        </p>

        <table style="margin-left:40px; margin-top:8px;">
            <tr>
                <td width="25%">Nama</td>
                <td width="2%">:</td>
                <td><b><?= $d['nama_lengkap']; ?></b></td>
            </tr>
            <tr>
                <td>NIM / NIS</td>
                <td>:</td>
                <td><?= $d['nim_nis']; ?></td>
            </tr>
            <tr>
                <td>Jurusan</td>
                <td>:</td>
                <td><?= $d['jurusan']; ?></td>
            </tr>
            <tr>
                <td>Penempatan</td>
                <td>:</td>
                <td><?= $d['nama_bagian']; ?></td>
            </tr>
        </table>

        <p class="indent" style="margin-top:10px;">
            Demikian atas perhatian dan kerjasamanya kami ucapkan terima kasih.
        </p>

        <table style="margin-top:30px;">
            <tr>
                <td width="50%"></td>
                <td width="50%" class="center">
                    <p>Plt. Sekretaris DPRD Kota Yogyakarta</p>
                    <p>Bagian Administrasi Umum</p><br><br><br>
                    <p class="bold"><u>ANTONIUS BAMBANG AGUNG ADRIJANTO, S.I.P</u></p>
                    <p>NIP. 19710630 199603 1 003</p>
                </td>
            </tr>
        </table>

        <!-- ================= FOOTER ================= -->
        <div style="mso-element:footer" id="f1">
            <table>
                <tr>
                    <td width="15%" align="center">
                        <img src="<?= $segoro ?>" width="60">
                    </td>
                    <td width="85%" class="center">
                        <div class="slogan-title">SEGOROAMARTO</div>
                        <p class="slogan-desc">
                            SEMANGAT GOTONG ROYONG AGAWE MAJUNE<br>
                            NGAYOGYAKARTO KEMANDIRIAN - KEDISIPLINAN -<br>
                            KEPEDULIAN - KEBERSAMAAN
                        </p>
                    </td>
                </tr>
            </table>
        </div>
        <!-- ========================================= -->

    </div>
</body>

</html>