<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../models/BookingModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingModel = new BookingModel($conn);

    $kode_booking = isset($_POST['kode_booking']) ? trim($_POST['kode_booking']) : '';
    $whatsapp     = isset($_POST['whatsapp']) ? trim($_POST['whatsapp']) : '';

    if (empty($kode_booking)) {
        header("Location: ../../pages/riwayat.php?error=empty");
        exit();
    }

    if ($bookingModel->checkBooking($kode_booking, $whatsapp)) {
        header("Location: ../../pages/riwayat.php?kode=" . urlencode($kode_booking));
        exit();
    } else {
        header("Location: ../../pages/riwayat.php?error=notfound&kode_input=" . urlencode($kode_booking));
        exit();
    }
} else {
    header("Location: ../../pages/riwayat.php");
    exit();
}
?>
