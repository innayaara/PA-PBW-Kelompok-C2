<?php
require_once __DIR__ . '/../AuthController.php';
require_once __DIR__ . '/../BookingController.php';

$authController = new AuthController();
$authController->requireLogin('../../panel-pengelola/login.php?error=unauthorized');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    if ($id > 0) {
        $bookingController = new BookingController();
        $bookingController->deleteBooking($id);
    }

    header("Location: ../../panel-pengelola/booking.php?success=deleted");
    exit();
}

header("Location: ../../panel-pengelola/booking.php");
exit();
?>