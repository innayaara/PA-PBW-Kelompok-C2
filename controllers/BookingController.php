<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../helpers/security_helper.php';
require_once __DIR__ . '/../models/BookingModel.php';

class BookingController
{
    private $conn;
    private $bookingModel;

    public function __construct()
    {
        global $conn;

        $this->conn = $conn;
        $this->bookingModel = new BookingModel($this->conn);
    }

    public function getDashboardStats()
    {
        return [
            'totalBooking'   => $this->bookingModel->countAllBookings(),
            'totalPending'   => $this->bookingModel->countBookingsByStatus('pending'),
            'totalConfirmed' => $this->bookingModel->countBookingsByStatus('confirmed'),
            'totalCancelled' => $this->bookingModel->countBookingsByStatus('cancelled'),
        ];
    }

    public function getFilteredBookings($search = '', $status = '')
    {
        return $this->bookingModel->getFilteredBookings($search, $status);
    }

    public function updateBookingStatus($id, $status)
    {
        return $this->bookingModel->updateBookingStatus($id, $status);
    }

    public function deleteBooking($id)
    {
        return $this->bookingModel->deleteBooking($id);
    }

    public function showEticket()
    {
        $kode = trim($_GET['kode'] ?? '');
        $token = trim($_GET['token'] ?? '');

        if (empty($kode) || empty($token)) {
            die("Akses ditolak. Silakan cek booking melalui halaman riwayat.");
        }

        $data = $this->bookingModel->getBookingByCodeAndToken($kode, $token);

        if (!$data) {
            die("Data booking tidak valid.");
        }

        $isAdmin = isset($_SESSION['admin_id']);

        if (
            !$isAdmin &&
            (
                !isset($_SESSION['eticket_access'][$kode]) ||
                !hash_equals($_SESSION['eticket_access'][$kode], $token)
            )
        ) {
            die("Akses ditolak. Silakan verifikasi kode booking dan WhatsApp melalui halaman riwayat.");
        }

        $from = $_GET['from'] ?? '';

        if ($from === 'admin-booking') {
            $backLink = '../panel-pengelola/booking.php';
            $backText = 'Kembali ke Data Booking';
        } elseif ($from === 'riwayat') {
            $backLink = 'riwayat.php?kode=' . urlencode($kode) . '&token=' . urlencode($token);
            $backText = 'Kembali ke Riwayat';
        } else {
            $backLink = '../index.php';
            $backText = 'Kembali ke Beranda';
        }

        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

        $verifyUrl = $scheme . '://' . $_SERVER['HTTP_HOST']
            . '/pages/riwayat.php?kode=' . urlencode($data['kode_booking'])
            . '&token=' . urlencode($data['access_token']);

        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data='
            . urlencode($verifyUrl)
            . '&color=1a3a26&bgcolor=f0f7f2';

        $tanggal = date('d F Y', strtotime($data['tanggal_kunjungan']));

        $hari = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        $hariStr = $hari[date('l', strtotime($data['tanggal_kunjungan']))] ?? '';

        $statusClass = 'pending';
        $statusIcon = 'fa-clock';
        $statusText = 'Menunggu Konfirmasi';

        if ($data['status'] === 'confirmed') {
            $statusClass = 'confirmed';
            $statusIcon = 'fa-circle-check';
            $statusText = 'Tiket Valid';
        } elseif ($data['status'] === 'cancelled') {
            $statusClass = 'cancelled';
            $statusIcon = 'fa-circle-xmark';
            $statusText = 'Dibatalkan';
        }

        require_once __DIR__ . '/../views/public/pages/eticket.php';
    }

    public function showRiwayat()
    {
        $error = $_GET['error'] ?? '';
        $kodeInput = e($_GET['kode_input'] ?? '');

        $kode = trim($_GET['kode'] ?? '');
        $token = trim($_GET['token'] ?? '');

        $data = null;
        $formattedStatus = '';

        if (!empty($kode) && !empty($token)) {
            $data = $this->bookingModel->getBookingByCodeAndToken($kode, $token);

            if ($data) {
                $_SESSION['eticket_access'][$data['kode_booking']] = $data['access_token'];

                $statusMap = [
                    'pending' => 'Menunggu Konfirmasi',
                    'confirmed' => 'Dikonfirmasi',
                    'cancelled' => 'Dibatalkan'
                ];

                $formattedStatus = $statusMap[$data['status']] ?? $data['status'];
            } else {
                $error = 'notfound';
            }
        }

        require_once __DIR__ . '/../views/public/pages/riwayat.php';
    }
}
?>