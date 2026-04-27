<?php
/**
 * Script untuk membuat folder yang hilang dan memastikan path image benar.
 */

$folders = [
    __DIR__ . '/../../assets/images',
    __DIR__ . '/../../assets/images/galeri'
];

echo "<h1>Image Directory Fix</h1>";
echo "<ul>";

foreach ($folders as $folder) {
    if (!is_dir($folder)) {
        if (mkdir($folder, 0777, true)) {
            echo "<li><span style='color:green;'>SUCCESS:</span> Folder <code>$folder</code> berhasil dibuat.</li>";
        } else {
            echo "<li><span style='color:red;'>FAILED:</span> Gagal membuat folder <code>$folder</code>. Mohon buat manual.</li>";
        }
    } else {
        echo "<li><span style='color:blue;'>INFO:</span> Folder <code>$folder</code> sudah ada.</li>";
    }
}

echo "</ul>";

$testFile = __DIR__ . '/../../assets/images/galeri/test.txt';
if (file_put_contents($testFile, "test") !== false) {
    echo "<p style='color:green;'>Sistem memiliki izin tulis (write permission) ke folder galeri.</p>";
    unlink($testFile);
} else {
    echo "<p style='color:red;'>Sistem TIDAK memiliki izin tulis ke folder galeri. Mohon cek permission folder assets.</p>";
}

echo "<hr>";
echo "<p>Setelah folder dibuat, silakan <strong>unggah ulang</strong> foto melalui Admin Panel agar file benar-benar tersimpan ke folder tersebut.</p>";
echo "<a href='../../panel-pengelola/wisata.php'>Kembali ke Admin</a>";
?>
