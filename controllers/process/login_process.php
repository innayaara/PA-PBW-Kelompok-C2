<?php
require_once __DIR__ . '/../AuthController.php';
require_once __DIR__ . '/../../helpers/security_helper.php';

$authController = new AuthController();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../panel-pengelola/login.php");
    exit();
}

if (!simple_rate_limit('admin_login_attempt', 5, 300)) {
    header("Location: ../../panel-pengelola/login.php?error=too_many_attempts");
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($username) || empty($password)) {
    header("Location: ../../panel-pengelola/login.php?error=empty");
    exit();
}

$admin = $authController->authenticate($username, $password);

if ($admin) {
    reset_rate_limit('admin_login_attempt');
    $authController->setAdminSession($admin);

    header("Location: ../../panel-pengelola/index.php");
    exit();
}

header("Location: ../../panel-pengelola/login.php?error=invalid");
exit();
?>