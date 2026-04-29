<?php
require_once __DIR__ . '/../AuthController.php';
require_once __DIR__ . '/../../helpers/security_helper.php';

$authController = new AuthController();
$authController->requireLogin('../../panel-pengelola/login.php?error=unauthorized');

$folderLabels = [
    'assets/images' => __DIR__ . '/../../assets/images',
    'assets/images/galeri' => __DIR__ . '/../../assets/images/galeri'
];

$messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        die('CSRF token tidak valid.');
    }

    foreach ($folderLabels as $label => $folder) {
        if (!is_dir($folder)) {
            if (mkdir($folder, 0755, true)) {
                $messages[] = [
                    'type' => 'success',
                    'text' => "Folder {$label} berhasil dibuat."
                ];
            } else {
                $messages[] = [
                    'type' => 'danger',
                    'text' => "Gagal membuat folder {$label}. Silakan buat manual."
                ];
            }
        } else {
            $messages[] = [
                'type' => 'info',
                'text' => "Folder {$label} sudah ada."
            ];
        }
    }

    $testFile = __DIR__ . '/../../assets/images/galeri/test.txt';

    if (is_dir(__DIR__ . '/../../assets/images/galeri') && file_put_contents($testFile, 'test') !== false) {
        $messages[] = [
            'type' => 'success',
            'text' => 'Sistem memiliki izin tulis ke folder galeri.'
        ];

        @unlink($testFile);
    } else {
        $messages[] = [
            'type' => 'danger',
            'text' => 'Sistem tidak memiliki izin tulis ke folder galeri. Silakan cek permission folder assets.'
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Image Directory Fix</title>
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
        <h1>Image Directory Fix</h1>
        <p>Halaman ini hanya bisa dijalankan oleh admin yang sudah login.</p>

        <?php if (!empty($messages)): ?>
            <?php foreach ($messages as $message): ?>
                <div class="alert <?php echo e($message['type']); ?>">
                    <?php echo e($message['text']); ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($_SERVER['REQUEST_METHOD'] !== 'POST'): ?>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                <button type="submit">Jalankan Fix Images</button>
            </form>
        <?php endif; ?>

        <hr>
        <p>Setelah folder dibuat, silakan unggah ulang foto melalui Admin Panel.</p>
        <p><a href="../../panel-pengelola/wisata.php">Kembali ke Admin</a></p>
    </div>
</body>
</html>