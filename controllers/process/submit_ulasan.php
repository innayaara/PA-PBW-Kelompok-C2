<?php
require_once '../../config/koneksi.php';
require_once '../../helpers/security_helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        header("Location: ../../index.php?error=csrf#testimoni");
        exit;
    }

    if (!simple_rate_limit('ulasan_submit', 3, 300)) {
        header("Location: ../../index.php?error=too_many_requests#testimoni");
        exit;
    }

    if (!empty($_POST['website'])) {
        header("Location: ../../index.php?success=review_submitted#testimoni");
        exit;
    }

    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $rating = (int)($_POST['rating'] ?? 0);
    $komentar = trim($_POST['komentar'] ?? '');

    if (!valid_nama_pengunjung($nama)) {
        header("Location: ../../index.php?error=invalid_name#testimoni");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../../index.php?error=invalid_email#testimoni");
        exit;
    }

    if ($rating < 1 || $rating > 5) {
        header("Location: ../../index.php?error=invalid_rating#testimoni");
        exit;
    }

    if (strlen($komentar) < 10 || strlen($komentar) > 1000 || $komentar !== strip_tags($komentar)) {
        header("Location: ../../index.php?error=invalid_message#testimoni");
        exit;
    }

    $token = bin2hex(random_bytes(32));
    $status = 'pending'; // Langsung jadikan pending untuk di-approve admin

    $stmt = $conn->prepare("INSERT INTO ulasan (nama, email, rating, komentar, status, token_verifikasi) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisss", $nama, $email, $rating, $komentar, $status, $token);
    
    if ($stmt->execute()) {
        header("Location: ../../index.php?success=review_submitted#testimoni");
    } else {
        header("Location: ../../index.php?error=db_error#testimoni");
    }
} else {
    header("Location: ../../index.php");
}
?>
