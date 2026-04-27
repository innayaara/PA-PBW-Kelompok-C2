<?php
require_once __DIR__ . '/../AuthController.php';
require_once __DIR__ . '/../BookingController.php';

$authController = new AuthController();
$authController->requireLogin('../../panel-pengelola/login.php?error=unauthorized');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $status = isset($_POST['status']) ? trim($_POST['status']) : '';

    $allowedStatus = ['pending', 'confirmed', 'cancelled'];

    if ($id > 0 && in_array($status, $allowedStatus, true)) {
        $bookingController = new BookingController();
        $bookingController->updateBookingStatus($id, $status);
    }

    header("Location: ../../panel-pengelola/booking.php?success=updated");
    exit();
} else {
    header("Location: ../../panel-pengelola/booking.php");
    exit();
}
?>
