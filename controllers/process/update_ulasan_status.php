<?php
require_once '../../config/koneksi.php';
require_once '../AuthController.php';

$authController = new AuthController();
$authController->requireLogin('../../panel-pengelola/login.php?error=unauthorized');

require_once '../../helpers/security_helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['action'])) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        header("Location: ../../panel-pengelola/ulasan.php?error=csrf");
        exit;
    }

    $id = (int)$_POST['id'];
    $action = $_POST['action'];
    
    $status = '';
    if ($action === 'approve') {
        $status = 'approved';
    } elseif ($action === 'reject') {
        $status = 'rejected';
    } else {
        header("Location: ../../panel-pengelola/ulasan.php");
        exit;
    }
    
    $stmt = $conn->prepare("UPDATE ulasan SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    
    if ($stmt->execute()) {
        header("Location: ../../panel-pengelola/ulasan.php?success=" . $status);
    } else {
        header("Location: ../../panel-pengelola/ulasan.php?error=update_failed");
    }
} else {
    header("Location: ../../panel-pengelola/ulasan.php");
}
?>
