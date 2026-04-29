<?php
require_once __DIR__ . '/../AuthController.php';
require_once __DIR__ . '/../../helpers/security_helper.php';
require_once __DIR__ . '/../../config/koneksi.php';

$authController = new AuthController();
$authController->requireLogin('../../panel-pengelola/login.php?error=unauthorized');

$message = '';
$messageType = 'info';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        die('CSRF token tidak valid.');
    }

    $check = mysqli_query($conn, "SHOW COLUMNS FROM wisata LIKE 'kuota_harian'");

    if ($check && mysqli_num_rows($check) > 0) {
        $message = "Kolom kuota_harian sudah ada. Tidak perlu migrasi ulang.";
        $messageType = "info";
    } else {
        $sql = "ALTER TABLE wisata ADD COLUMN kuota_harian INT DEFAULT 0 AFTER status";

        if (mysqli_query($conn, $sql)) {
            $message = "Kolom kuota_harian berhasil ditambahkan ke tabel wisata.";
            $messageType = "success";
        } else {
            $message = "Migrasi gagal. Silakan cek database atau jalankan manual melalui phpMyAdmin.";
            $messageType = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Migrate Quota</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 32px;
            background: #f8fafc;
            color: #1f2937;
        }

        .card {
            max-width: 720px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
        }

        .alert {
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .info {
            background: #dbeafe;
            color: #1e40af;
        }

        button {
            padding: 10px 16px;
            border: 0;
            border-radius: 8px;
            background: #1a3a26;
            color: white;
            cursor: pointer;
            font-weight: 600;
        }

        a {
            color: #1a3a26;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Migrate Quota</h1>
        <p>Halaman ini hanya bisa dijalankan oleh admin yang sudah login.</p>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo e($messageType); ?>">
                <?php echo e($message); ?>
            </div>
        <?php endif; ?>

        <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST'): ?>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                <button type="submit">Jalankan Migrasi Kuota</button>
            </form>
        <?php endif; ?>

        <hr>
        <p><a href="../../panel-pengelola/wisata.php">Kembali ke Admin</a></p>
    </div>
</body>
</html>