<?php
require_once __DIR__ . '/../AuthController.php';
require_once __DIR__ . '/../WisataController.php';

$authController = new AuthController();
$authController->requireLogin('../../panel-pengelola/login.php?error=unauthorized');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    $wisataController = new WisataController();
    $wisataController->deleteWisata($id);

    header("Location: ../../panel-pengelola/wisata.php?success=deleted");
    exit();
}

header("Location: ../../panel-pengelola/wisata.php");
exit();
?>
