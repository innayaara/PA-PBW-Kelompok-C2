<?php
require_once __DIR__ . '/../AuthController.php';
require_once __DIR__ . '/../GaleriController.php';

$authController = new AuthController();
$authController->requireLogin('../../panel-pengelola/login.php?error=unauthorized');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $galeriController = new GaleriController();
    $result = $galeriController->createGaleri($_POST);

    if ($result['success']) {
        header("Location: ../../panel-pengelola/galeri.php?success=added");
        exit();
    }

    header("Location: ../../panel-pengelola/tambah_galeri.php?error=" . urlencode($result['error']));
    exit();
}

header("Location: ../../panel-pengelola/galeri.php");
exit();
?>
