<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../helpers/security_helper.php';
require_once __DIR__ . '/../../models/BookingModel.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../pages/riwayat.php");
    exit();
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    header("Location: ../../pages/riwayat.php?error=csrf");
    exit();
}

if (!simple_rate_limit('riwayat_check_attempt', 8, 300)) {
    header("Location: ../../pages/riwayat.php?error=too_many_requests");
    exit();
}

$bookingModel = new BookingModel($conn);

$kode_booking = trim($_POST['kode_booking'] ?? '');
$whatsapp     = normalize_whatsapp($_POST['whatsapp'] ?? '');

if (empty($kode_booking) || empty($whatsapp)) {
    header("Location: ../../pages/riwayat.php?error=empty");
    exit();
}

if (!valid_whatsapp($whatsapp)) {
    header("Location: ../../pages/riwayat.php?error=invalid_whatsapp&kode_input=" . urlencode($kode_booking));
    exit();
}

$data = $bookingModel->getBookingByCodeAndWhatsapp($kode_booking, $whatsapp);

if ($data) {
    if (empty($data['access_token'])) {
        $newToken = bin2hex(random_bytes(32));
        $bookingModel->updateAccessToken($data['id'], $newToken);
        $data['access_token'] = $newToken;
    }

    $_SESSION['eticket_access'][$data['kode_booking']] = $data['access_token'];
    reset_rate_limit('riwayat_check_attempt');

    header(
        "Location: ../../pages/riwayat.php?kode=" . urlencode($data['kode_booking']) .
        "&token=" . urlencode($data['access_token'])
    );
    exit();
}

header("Location: ../../pages/riwayat.php?error=notfound&kode_input=" . urlencode($kode_booking));
exit();
?>