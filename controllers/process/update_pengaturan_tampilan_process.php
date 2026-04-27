<?php
require_once __DIR__ . '/../AuthController.php';
require_once __DIR__ . '/../TampilanController.php';

$authController = new AuthController();
$authController->requireLogin('../../panel-pengelola/login.php?error=unauthorized');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tampilanController = new TampilanController();
    $result = $tampilanController->updateSetting($_POST);

    if ($result['success']) {
        header("Location: ../../panel-pengelola/pengaturan_tampilan.php?success=updated");
        exit();
    }

    header("Location: ../../panel-pengelola/pengaturan_tampilan.php?error=" . urlencode($result['error']));
    exit();
}

header("Location: ../../panel-pengelola/pengaturan_tampilan.php");
exit();
?>
