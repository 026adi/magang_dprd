<?php
include_once '../../config/koneksi.php';

$id = $_GET['id'];
$query = mysqli_query($koneksi, "
    SELECT anak_magang.*, bagian.nama_bagian
    FROM anak_magang
    LEFT JOIN bagian ON anak_magang.id_bagian = bagian.id_bagian
    WHERE id_magang = '$id'
");
$d = mysqli_fetch_assoc($query);

function tgl_indo($tanggal)
{
    $bulan = [
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];
    $p = explode('-', $tanggal);
    return $p[2] . ' ' . $bulan[(int)$p[1]] . ' ' . $p[0];
}

function img64($path)
{
    if (!file_exists($path)) return '';
    $type = pathinfo($path, PATHINFO_EXTENSION);
    return 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($path));
}

$logo   = img64('../../assets/img/logo.jpeg');
$aksara = img64('../../assets/img/aksara.jpeg');
$segoro = img64('../../assets/img/segoro.jpeg');

header("Content-Type: application/vnd.ms-word");
header("Content-Disposition: attachment; filename=Surat_Jawaban_Magang.doc");
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
    </style>
</head>

<body>

    <!-- ================= HEADER WORD ================= -->
    <div style="mso-element:header" id="h1">
        <table>
            <tr>
                <td width="0%" align="center">
                    <img src="<?= $logo ?>" width="85">
                </td>
                <td width="100%" align="center">
                    <p style="font-size:14pt;font-weight:bold;">PEMERINTAH KOTA YOGYAKARTA</p>
                    <p style="font-size:18pt;font-weight:bold;">SEKRETARIAT DPRD</p>
                    <img src="<?= $aksara ?>" height="20"><br>
                    <p style="font-size:10pt;line-height:1.2;">
                        Jl. Ipda Tut Harsono No. 43 Yogyakarta Kode Pos 55165<br>
                        Telp. (0274) 540650 Fax. (0274) 540651<br>
                        EMAIL : dprd@jogjakota.go.id<br>
                        WEBSITE : www.setwan.jogjakota.go.id
                    </p>
                </td>
            </tr>
        </table>
        <hr style="border:3px double #000;">
    </div>
    <!-- =============================================== -->

    <div class="Section1">

        <!-- ================= ISI SURAT ================= -->
        <table style="margin-top:20px;">
            <tr>
                <td width="60%"></td>
                <td width="40%">
                    <p>Yogyakarta, <?= tgl_indo(date('Y-m-d')); ?></p><br>
                    <p>Kepada</p>
                    <p>
                        Yth. Wakil Dekan Fakultas Teknik Industri<br>
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

        <p class="indent" style="margin-top:12px; text-align:justify;">
            Menindaklanjuti Surat dari Dekan Akademik <?= $d['universitas_instansi']; ?>,
            Tanggal ____________,
            Nomor Surat ____________,
            perihal permohonan izin magang, bersama ini kami sampaikan bahwa
            Sekretariat DPRD Kota Yogyakarta bersedia menerima mahasiswa sebagaimana tersebut di bawah ini
            untuk melaksanakan magang di Sekretariat DPRD Kota Yogyakarta
            terhitung mulai tanggal <?= tgl_indo($d['tgl_mulai']); ?> s.d. <?= tgl_indo($d['tgl_selesai']); ?>.
        </p>

        <p class="indent" style="margin-top:6px;">
            Adapun mahasiswa tersebut adalah:
        </p>


        <table style="margin-left:40px;margin-top:8px;">
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

        <!-- JARAK LEBIH LEGA KE TTD -->
        <p class="indent" style="margin-top:25px; margin-bottom:30px;">
            Demikian atas perhatian dan kerjasamanya kami ucapkan terima kasih.
        </p>

        <table>
            <tr>
                <td width="50%"></td>
                <td width="50%" class="center">
                    <p style="margin:0;">Sekretaris DPRD Kota Yogyakarta</p>
                    <p style="margin:0;">Bagian Administrasi Umum</p>
                </td>
            </tr>

            <!-- SPACER ROW: pasti di-respect Word -->
            <tr>
                <td></td>
                <td style="height:100px;"></td> <!-- ubah 40px sesuai kebutuhan -->
            </tr>

            <tr>
                <td></td>
                <td class="center">
                    <p style="margin:0; text-decoration:underline;">
                        A.BAMBANG AGUNG ADRIJANTO, S.I.P
                    </p>
                    <p style="margin:0;">NIP. 19710630 199603 1 003</p>
                </td>
            </tr>
        </table> <!-- AKHIR TTD -->

        <p style="line-height:60px; margin:0;">&nbsp;</p>

    </div>

    <div id="f1">
        <!-- ================= FOOTER WORD ================= -->
        <table>
            <tr>
                <td width="0%" align="center">
                    <img src="<?= $segoro ?>" width="65">
                </td>
                <td width="100%" align="center">
                    <p style="font-size:9pt;font-weight:bold;">SEGOROAMARTO</p>
                    <p style="font-size:8pt;line-height:1.2;">
                        SEMANGAT GOTONG ROYONG AGAWE MAJUNE NGAYOGYAKARTO<br>
                        KEMANDIRIAN - KEDISIPLINAN - KEPEDULIAN - KEBERSAMAAN
                    </p>
                </td>
            </tr>
        </table>
    </div>
    <!-- =============================================== -->

</body>

</html>