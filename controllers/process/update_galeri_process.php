<?php
require_once __DIR__ . '/../AuthController.php';
require_once __DIR__ . '/../GaleriController.php';

$authController = new AuthController();
$authController->requireLogin('../../panel-pengelola/login.php?error=unauthorized');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $galeriController = new GaleriController();
    $result = $galeriController->updateGaleri($_POST);

    if ($result['success']) {
        header("Location: ../../panel-pengelola/galeri.php?success=updated");
        exit();
    }

    $id = isset($result['id']) ? (int) $result['id'] : 0;
    header("Location: ../../panel-pengelola/edit_galeri.php?id=$id&error=" . urlencode($result['error']));
    exit();
}

header("Location: ../../panel-pengelola/galeri.php");
exit();
?>
