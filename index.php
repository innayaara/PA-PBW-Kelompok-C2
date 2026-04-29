<?php
require_once __DIR__ . '/helpers/security_helper.php';
require_once __DIR__ . '/controllers/HomeController.php';

$controller = new HomeController();
$controller->index();
?>