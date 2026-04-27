<?php
require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../controllers/LiburController.php';

$authController = new AuthController();
$authController->requireLogin('../panel-pengelola/login.php?error=unauthorized');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $liburController = new LiburController();
    $result = $liburController->store($_POST);

    if ($result) {
        header("Location: ../../panel-pengelola/hari_libur.php?success=added");
    } else {
        header("Location: ../../panel-pengelola/hari_libur.php?error=failed");
    }
} else {
    header("Location: ../../panel-pengelola/hari_libur.php");
}
exit();
