<?php
include('db.php');


// Definisikan harga untuk tiap tipe kamar
$harga_kamar = [
    'Standar' => 250000,
    'Deluxe' => 500000,
    'Family' => 700000
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pemesan = $_POST['nama_pemesan'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $nomor_identitas = $_POST['nomor_identitas'];
    $tipe_kamar = $_POST['tipe_kamar'];
    $harga = $harga_kamar[$tipe_kamar];
    $tanggal_pesan = $_POST['tanggal_pesan'];  // Ambil tanggal yang dipilih
    $durasi = $_POST['durasi'];
    $termasuk_sarapan = isset($_POST['termasuk_sarapan']) ? 1 : 0;

    // Validasi durasi menginap
    if ($durasi <= 0) {
        echo "Durasi menginap harus lebih dari 0 hari.";
        exit;
    }

    // Hitung total bayar
    $total_bayar = $harga * $durasi;
    $diskon = 0;

    // Jika durasi menginap lebih dari 3 hari, berikan diskon 10%
    if ($durasi > 3) {
        $diskon = $total_bayar * 0.10; // Diskon 10%
        $total_bayar -= $diskon;
    }

    // Tambahkan biaya sarapan
    if ($termasuk_sarapan) {
        $total_bayar += 80000 * $durasi;
    }

    // Menyimpan data ke database
    try {
        $stmt = $pdo->prepare("INSERT INTO pemesanan_kamar 
            (nama_pemesan, jenis_kelamin, nomor_identitas, tipe_kamar, harga, tanggal_pesan, durasi, termasuk_sarapan, total_pembayaran)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([ 
            $nama_pemesan, 
            $jenis_kelamin, 
            $nomor_identitas, 
            $tipe_kamar, 
            $harga, 
            $tanggal_pesan, 
            $durasi, 
            $termasuk_sarapan, 
            $total_bayar
        ]);

        // Redirect ke halaman ringkasan pemesanan dan sertakan tanggal pemesanan dalam query string
        header("Location: hasil.php?nama_pemesan=$nama_pemesan&jenis_kelamin=$jenis_kelamin&nomor_identitas=$nomor_identitas&tipe_kamar=$tipe_kamar&tanggal_pesan=$tanggal_pesan&durasi=$durasi&diskon=$diskon&total_bayar=$total_bayar");
        exit();
    } catch (PDOException $e) {
        echo "Gagal menyimpan data: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rzn hotel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Form Pemesanan Kamar</h2>
        <form action="" method="POST">
            <label>Nama Pemesan:</label>
            <input type="text" name="nama_pemesan" required><br>
            
            <label>Jenis Kelamin:</label>
            <select name="jenis_kelamin">
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select><br>

            <label>Nomor Identitas :</label>
            <input type="text" name="nomor_identitas" pattern="\d{16}" required><br>

            <label>Tipe Kamar:</label>
            <select name="tipe_kamar" id="tipe_kamar" onchange="updateHarga()">
                <option value="Standar">Standar</option>
                <option value="Deluxe">Deluxe</option>
                <option value="Family">Family</option>
            </select><br>

            <label>Harga:</label>
            <input type="text" name="harga" id="harga" readonly><br>

            <label>Tanggal Pemesanan:</label>
            <input type="date" name="tanggal_pesan" id="tanggal_pesan" required><br>

            <label>Durasi Menginap :</label>
            <input type="number" placeholder="berapa hari anda ingin menginap ..." name="durasi" id="durasi" min="1" oninput="updateTotal()" required><br>

            <label>Termasuk Sarapan:</label>
            <input type="checkbox" name="termasuk_sarapan" id="termasuk_sarapan" onchange="updateTotal()"><br>

            <label>Total Bayar:</label>
            <input type="text" name="total_bayar" id="total_bayar" readonly><br>

            <button type="submit">Pesan Kamar</button>
            <button type="button" onclick="cancelBooking()">Cancel</button>
        </form>
    </div>
</body>
</html>

<script>
// Data harga kamar
const hargaKamar = {
    "Standar": 250000,
    "Deluxe": 500000,
    "Family": 700000
};

// Ambil tanggal hari ini
const today = new Date();
const yyyy = today.getFullYear();
const mm = String(today.getMonth() + 1).padStart(2, '0'); // Format bulan menjadi 2 digit
const dd = String(today.getDate()).padStart(2, '0'); // Format tanggal menjadi 2 digit

// Format tanggal minimum (hari ini)
const minDate = `${yyyy}-${mm}-${dd}`;

// Tetapkan atribut 'min' pada elemen input tanggal
document.addEventListener('DOMContentLoaded', () => {
    const tanggalPesanInput = document.getElementById('tanggal_pesan');
    if (tanggalPesanInput) {
        tanggalPesanInput.setAttribute('min', minDate);
    }
});

// Update harga kamar
function updateHarga() {
    const tipeKamar = document.getElementById('tipe_kamar').value;
    const harga = hargaKamar[tipeKamar];
    document.getElementById('harga').value = harga;
    updateTotal(); // Hitung ulang total bayar
}

// Update total bayar
function updateTotal() {
    const harga = parseInt(document.getElementById('harga').value) || 0;
    const durasi = parseInt(document.getElementById('durasi').value) || 0;
    const termasukSarapan = document.getElementById('termasuk_sarapan').checked;

    let total = harga * durasi;

    // Tambahkan diskon jika durasi lebih dari 3 hari
    if (durasi > 3) {
        total -= total * 0.10; // Diskon 10%
    }

    // Tambahkan biaya sarapan jika dipilih
    if (termasukSarapan) {
        total += 80000 * durasi;
    }

    // Tampilkan hasil total bayar
    document.getElementById('total_bayar').value = 'Rp ' + total.toLocaleString();
}

// Redirect untuk membatalkan pemesanan
function cancelBooking() {
    window.location.href = "index.php";
}

// Inisialisasi harga saat halaman dimuat
window.onload = function () {
    updateHarga();
};
</script>
