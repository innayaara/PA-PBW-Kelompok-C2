<?php
require_once '../../config/koneksi.php';
require_once '../AuthController.php';

$authController = new AuthController();
$authController->requireLogin('../../panel-pengelola/login.php?error=unauthorized');

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];
    
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
