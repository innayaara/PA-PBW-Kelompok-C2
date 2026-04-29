<?php
require_once __DIR__ . '/../AuthController.php';
require_once __DIR__ . '/../WisataController.php';

$authController = new AuthController();
$authController->requireLogin('../../panel-pengelola/login.php?error=unauthorized');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $wisataController = new WisataController();
    $result = $wisataController->createWisata($_POST);

    if ($result['success']) {
        header("Location: ../../panel-pengelola/wisata.php?success=added");
        exit();
    }

    header("Location: ../../panel-pengelola/tambah_wisata.php?error=" . urlencode($result['error']));
    exit();
}

header("Location: ../../panel-pengelola/wisata.php");
exit();
?>
