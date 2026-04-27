<?php
require_once __DIR__ . '/../AuthController.php';

$authController = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($username) || empty($password)) {
        header("Location: ../../panel-pengelola/login.php?error=empty");
        exit();
    }

    $admin = $authController->authenticate($username, $password);

    if ($admin) {
        $authController->setAdminSession($admin);

        header("Location: ../../panel-pengelola/index.php");
        exit();
    } else {
        header("Location: ../../panel-pengelola/login.php?error=invalid");
        exit();
    }
} else {
    header("Location: ../../panel-pengelola/login.php");
    exit();
}
?>
