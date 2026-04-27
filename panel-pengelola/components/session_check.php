<?php
require_once __DIR__ . '/../../controllers/AuthController.php';

$authController = new AuthController();
$authController->requireLogin('login.php?error=unauthorized');
?>
