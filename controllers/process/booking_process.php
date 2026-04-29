<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../helpers/security_helper.php';
require_once __DIR__ . '/../../models/BookingModel.php';
require_once __DIR__ . '/../../models/WisataModel.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../index.php");
    exit();
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    header("Location: ../../index.php?section=booking&error=csrf#booking");
    exit();
}

if (!simple_rate_limit('booking_submit_attempt', 5, 60)) {
    header("Location: ../../index.php?section=booking&error=too_many_requests#booking");
    exit();
}

$bookingModel = new BookingModel($conn);
$wisataModel  = new WisataModel($conn);

$nama      = trim($_POST['nama'] ?? '');
$whatsapp  = trim($_POST['whatsapp'] ?? '');
$tanggal   = trim($_POST['tanggal'] ?? '');
$jumlah    = isset($_POST['jumlah']) ? (int) $_POST['jumlah'] : 0;
$destinasi = trim($_POST['destinasi'] ?? '');
$catatan   = trim($_POST['catatan'] ?? '');

$whatsappDigits = normalize_whatsapp($whatsapp);

if (!valid_nama_pengunjung($nama)) {
    header('Location: ../../index.php?section=booking&error=invalid_name#booking');
    exit();
}

if (!valid_whatsapp($whatsappDigits)) {
    header('Location: ../../index.php?section=booking&error=invalid_whatsapp#booking');
    exit();
}

if ($jumlah < 1 || $jumlah > 50) {
    header('Location: ../../index.php?section=booking&error=invalid_jumlah#booking');
    exit();
}

if (empty($tanggal) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
    header('Location: ../../index.php?section=booking&error=invalid_tanggal#booking');
    exit();
}

if (strtotime($tanggal) < strtotime(date('Y-m-d'))) {
    header('Location: ../../index.php?section=booking&error=past_date#booking');
    exit();
}

$wisata = $wisataModel->getByName($destinasi);

if (!$wisata || $wisata['status'] !== 'aktif') {
    header('Location: ../../index.php?section=booking&error=invalid_destinasi#booking');
    exit();
}

$destEscaped = mysqli_real_escape_string($conn, $destinasi);
$tanggalEscaped = mysqli_real_escape_string($conn, $tanggal);

$resL = mysqli_query($conn, "
    SELECT id 
    FROM wisata_libur 
    WHERE destinasi = '$destEscaped' 
    AND tanggal = '$tanggalEscaped' 
    LIMIT 1
");

if ($resL && mysqli_num_rows($resL) > 0) {
    header("Location: ../../index.php?error=is_holiday#booking");
    exit();
}

$kuotaHarian = (int) ($wisata['kuota_harian'] ?? 0);

if ($kuotaHarian > 0) {
    $terpakai = $bookingModel->getTicketCountByDate($destinasi, $tanggal);

    if (($terpakai + $jumlah) > $kuotaHarian) {
        $sisa = max(0, $kuotaHarian - $terpakai);
        header("Location: ../../index.php?error=quota_full&left=" . $sisa . "#booking");
        exit();
    }
}

$dayNumber = (int) date('N', strtotime($tanggal));
$isWeekend = $dayNumber >= 6;

$harga_satuan = $isWeekend ? (float) $wisata['harga_weekend'] : (float) $wisata['harga_weekday'];
$total_harga  = $harga_satuan * $jumlah;
$jenis_hari   = $isWeekend ? 'Weekend' : 'Weekday';

$kode_booking = $bookingModel->generateBookingCode($whatsappDigits);
$access_token = bin2hex(random_bytes(32));

$data = [
    'kode_booking' => $kode_booking,
    'access_token' => $access_token,
    'nama' => $nama,
    'whatsapp' => $whatsappDigits,
    'tanggal' => $tanggal,
    'jumlah' => $jumlah,
    'destinasi' => $destinasi,
    'catatan' => $catatan,
    'harga_satuan' => $harga_satuan,
    'total_harga' => $total_harga,
    'jenis_hari' => $jenis_hari
];

if ($bookingModel->createBooking($data)) {
    $_SESSION['eticket_access'][$kode_booking] = $access_token;
    reset_rate_limit('booking_submit_attempt');

    header(
        "Location: ../../pages/eticket.php?kode=" . urlencode($kode_booking) .
        "&token=" . urlencode($access_token)
    );
    exit();
}

echo "Error: " . mysqli_error($conn);
mysqli_close($conn);
?>