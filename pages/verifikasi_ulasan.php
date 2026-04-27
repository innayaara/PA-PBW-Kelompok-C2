<?php
require_once '../config/koneksi.php';

$token = $_GET['token'] ?? '';
$message = '';
$status = 'error';

if (!empty($token)) {
    $stmt = $conn->prepare("SELECT id, status FROM ulasan WHERE token_verifikasi = ? LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $ulasan = $result->fetch_assoc();
        
        if ($ulasan['status'] === 'unverified') {
            $update = $conn->prepare("UPDATE ulasan SET status = 'pending', token_verifikasi = NULL WHERE id = ?");
            $update->bind_param("i", $ulasan['id']);
            if ($update->execute()) {
                $status = 'success';
                $message = 'Email berhasil diverifikasi! Ulasan Anda sekarang sedang menunggu persetujuan admin.';
            } else {
                $message = 'Gagal memverifikasi ulasan. Silakan coba lagi.';
            }
        } else {
            $status = 'info';
            $message = 'Tautan ini sudah tidak berlaku atau ulasan sudah diverifikasi sebelumnya.';
        }
    } else {
        $message = 'Token verifikasi tidak valid.';
    }
} else {
    $message = 'Token verifikasi tidak ditemukan.';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Ulasan - Bukit Fajar Lestari</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); text-align: center; max-width: 400px; width: 90%; }
        .icon { font-size: 50px; margin-bottom: 20px; }
        .icon.success { color: #10b981; }
        .icon.error { color: #ef4444; }
        .icon.info { color: #3b82f6; }
        h1 { font-size: 1.5rem; color: #1a1a1a; margin-bottom: 10px; }
        p { color: #666; font-size: 0.95rem; line-height: 1.5; margin-bottom: 25px; }
        .btn { display: inline-block; padding: 12px 25px; background-color: #1b3d28; color: white; text-decoration: none; border-radius: 8px; font-weight: 500; transition: background 0.3s; }
        .btn:hover { background-color: #142d1e; }
    </style>
</head>
<body>
    <div class="card">
        <?php if ($status === 'success'): ?>
            <i class="fas fa-check-circle icon success"></i>
            <h1>Verifikasi Berhasil</h1>
        <?php elseif ($status === 'info'): ?>
            <i class="fas fa-info-circle icon info"></i>
            <h1>Sudah Diverifikasi</h1>
        <?php else: ?>
            <i class="fas fa-times-circle icon error"></i>
            <h1>Verifikasi Gagal</h1>
        <?php endif; ?>
        
        <p><?php echo htmlspecialchars($message); ?></p>
        <a href="../index.php" class="btn">Kembali ke Beranda</a>
    </div>
</body>
</html>
