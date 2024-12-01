<?php
// db.php
$host = '127.0.0.1'; // Alamat host database
$dbname = 'pemesanan'; // Nama database
$username = 'root'; // Username database
$password = ''; // Password database (kosongkan jika tidak ada password)

try {
    // Membuat koneksi PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set mode error PDO menjadi exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
    die(); // Jika koneksi gagal, hentikan eksekusi
}
?>
