<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../models/BookingModel.php';
require_once __DIR__ . '/../../models/WisataModel.php';
require_once __DIR__ . '/../../models/LiburModel.php';

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

$destinasi = isset($_GET['destinasi']) ? trim($_GET['destinasi']) : '';
$month     = isset($_GET['month']) ? trim($_GET['month']) : date('Y-m');

if (empty($destinasi)) {
    echo json_encode(['status' => 'error', 'message' => 'Destinasi wajib diisi']);
    exit();
}

$bookingModel = new BookingModel($conn);
$wisataModel  = new WisataModel($conn);
$liburModel   = new LiburModel($conn);

// 1. Get Quota for this wisata
$wisata = $wisataModel->getByName($destinasi);
if (!$wisata) {
    echo json_encode(['status' => 'error', 'message' => 'Wisata tidak ditemukan']);
    exit();
}

$max_quota = (int)$wisata['kuota_harian'];

// 2. Get monthly bookings
$bookings = $bookingModel->getMonthlyBookings($destinasi, $month);

// 3. Get monthly holidays
$holidays = $liburModel->getLiburByMonth($destinasi, $month);

echo json_encode([
    'status' => 'success',
    'data' => [
        'max_quota' => $max_quota,
        'bookings'  => $bookings,
        'holidays'  => $holidays
    ]
]);
