<?php
require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../controllers/LiburController.php';

$authController = new AuthController();
$authController->requireLogin('../panel-pengelola/login.php?error=unauthorized');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $liburController = new LiburController();
    $result = $liburController->destroy($id);

    if ($result) {
        header("Location: ../../panel-pengelola/hari_libur.php?success=deleted");
    } else {
        header("Location: ../../panel-pengelola/hari_libur.php?error=failed");
    }
} else {
    header("Location: ../../panel-pengelola/hari_libur.php");
}
exit();
