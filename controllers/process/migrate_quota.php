<?php
require_once __DIR__ . '/../../config/koneksi.php';

// SQL to add kuota_harian column
$sql = "ALTER TABLE wisata ADD COLUMN kuota_harian INT DEFAULT 0 AFTER status";

if (mysqli_query($conn, $sql)) {
    echo "<h1>Migration Success!</h1>";
    echo "<p>Kolom 'kuota_harian' berhasil ditambahkan ke tabel 'wisata'.</p>";
    echo "<a href='../../panel-pengelola/wisata.php'>Kembali ke Admin</a>";
} else {
    echo "<h1>Migration Failed!</h1>";
    echo "<p>Error: " . mysqli_error($conn) . "</p>";
    echo "<p>Mungkin kolom sudah ada atau ada kesalahan konfigurasi database.</p>";
    echo "<a href='../../panel-pengelola/wisata.php'>Kembali ke Admin</a>";
}

mysqli_close($conn);
?>
