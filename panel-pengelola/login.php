<?php
require_once __DIR__ . '/../controllers/AuthController.php';

$authController = new AuthController();
$authController->redirectIfLoggedIn('index.php');

$error = isset($_GET['error']) ? $_GET['error'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Bukit Fajar Lestari</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body class="admin-login-page">

<div class="container">
    <div class="row justify-content-center align-items-center login-wrapper">
        <div class="col-lg-10">
            <div class="mb-4">
                <a href="../index.php" class="back-home">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
                </a>
            </div>

            <div class="card login-card">
                <div class="row g-0">
                    <div class="col-lg-6">
                        <div class="p-4 p-md-5">
                            <div class="mb-4">
                                <div class="login-brand">Bukit <span>Fajar</span> Lestari</div>
                                <div class="login-subtitle">Login admin untuk mengelola data booking wisata.</div>
                            </div>

                            <?php if ($error === 'empty'): ?>
                                <div class="alert alert-warning">Username dan password wajib diisi.</div>
                            <?php elseif ($error === 'invalid'): ?>
                                <div class="alert alert-danger">Username atau password salah.</div>
                            <?php elseif ($error === 'unauthorized'): ?>
                                <div class="alert alert-warning">Silakan login terlebih dahulu.</div>
                            <?php elseif ($error === 'too_many_attempts'): ?>
                                <div class="alert alert-danger">Terlalu banyak percobaan login. Silakan coba lagi nanti.</div>
                            <?php endif; ?>

                            <form action="../controllers/process/login_process.php" method="POST">
                                <div class="mb-3">
                                    <label class="login-label">Username</label>
                                    <input type="text" name="username" class="form-control login-input" placeholder="Masukkan username admin" required>
                                </div>

                                <div class="mb-4">
                                    <label class="login-label">Password</label>
                                    <input type="password" name="password" class="form-control login-input" placeholder="Masukkan password" required>
                                </div>

                                <button type="submit" class="btn btn-login-custom w-100">
                                    <i class="fas fa-right-to-bracket me-2"></i>Login Admin
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="login-side d-flex flex-column justify-content-center">
                            <h2>Panel Pengelola Wisata</h2>
                            <p>
                                Halaman ini digunakan admin untuk mengelola pemesanan tiket, memantau data pengunjung,
                                dan mengatur informasi website Bukit Fajar Lestari.
                            </p>

                            <ul>
                                <li><i class="fas fa-ticket-alt"></i> Kelola data booking pengunjung</li>
                                <li><i class="fas fa-chart-line"></i> Lihat ringkasan pemesanan</li>
                                <li><i class="fas fa-image"></i> Kelola galeri wisata</li>
                                <li><i class="fas fa-comments"></i> Moderasi testimoni</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>