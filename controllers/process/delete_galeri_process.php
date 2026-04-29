<?php
require_once __DIR__ . '/../AuthController.php';
require_once __DIR__ . '/../GaleriController.php';

$authController = new AuthController();
$authController->requireLogin('../../panel-pengelola/login.php?error=unauthorized');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    $galeriController = new GaleriController();
    $galeriController->deleteGaleri($id);

    header("Location: ../../panel-pengelola/galeri.php?success=deleted");
    exit();
}

header("Location: ../../panel-pengelola/galeri.php");
exit();
?>
