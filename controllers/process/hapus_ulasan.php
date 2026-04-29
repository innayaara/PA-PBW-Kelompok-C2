<?php
require_once '../../config/koneksi.php';
require_once '../AuthController.php';

$authController = new AuthController();
$authController->requireLogin('../../panel-pengelola/login.php?error=unauthorized');

require_once '../../helpers/security_helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        header("Location: ../../panel-pengelola/ulasan.php?error=csrf");
        exit;
    }

    $id = (int)$_POST['id'];
    
    $stmt = $conn->prepare("DELETE FROM ulasan WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: ../../panel-pengelola/ulasan.php?success=deleted");
    } else {
        header("Location: ../../panel-pengelola/ulasan.php?error=delete_failed");
    }
} else {
    header("Location: ../../panel-pengelola/ulasan.php");
}
?>
