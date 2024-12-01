<?php
// Ambil data dari URL query string
$nama_pemesan = $_GET['nama_pemesan'];
$jenis_kelamin = $_GET['jenis_kelamin'];
$nomor_identitas = $_GET['nomor_identitas'];
$tipe_kamar = $_GET['tipe_kamar'];
$tanggal_pesan = isset($_GET['tanggal_pesan']) ? $_GET['tanggal_pesan'] : "Tidak tersedia"; // Ambil tanggal pesan
$durasi = $_GET['durasi'];
$diskon = isset($_GET['diskon']) ? $_GET['diskon'] : 0;
$total_bayar = $_GET['total_bayar'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ringkasan Pemesanan - Hotel Rzn</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Ringkasan Pemesanan</h2>

        <p><strong>Nama Pemesan:</strong> <?php echo $nama_pemesan; ?></p>
        <p><strong>Jenis Kelamin:</strong> <?php echo $jenis_kelamin; ?></p>
        <p><strong>Nomor Identitas:</strong> <?php echo $nomor_identitas; ?></p>
        <p><strong>Tipe Kamar:</strong> <?php echo $tipe_kamar; ?></p>
        <p><strong>Tanggal Pemesanan:</strong> <?php echo $tanggal_pesan; ?></p>
        <p><strong>Durasi Menginap:</strong> <?php echo $durasi; ?> hari</p>
        <p><strong>Diskon:</strong> Rp <?php echo number_format($diskon, 0, ',', '.'); ?></p>
        <p><strong>Total Pembayaran:</strong> Rp <?php echo number_format($total_bayar, 0, ',', '.'); ?></p>
        <div class="back-button">
            <a href="index.php">
                <button type="button">Kembali</button>
            </a>
        </div>
    </div>
</body>
</html>
