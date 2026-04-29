<?php
require_once __DIR__ . '/../AuthController.php';
require_once __DIR__ . '/../WisataController.php';

$authController = new AuthController();
$authController->requireLogin('../../panel-pengelola/login.php?error=unauthorized');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $wisataController = new WisataController();
    $result = $wisataController->updateWisata($_POST);

    if ($result['success']) {
        header("Location: ../../panel-pengelola/wisata.php?success=updated");
        exit();
    }

    $id = isset($result['id']) ? (int) $result['id'] : 0;
    header("Location: ../../panel-pengelola/edit_wisata.php?id=$id&error=" . urlencode($result['error']));
    exit();
}

header("Location: ../../panel-pengelola/wisata.php");
exit();
?>
