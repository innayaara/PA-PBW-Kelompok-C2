<?php
require_once __DIR__ . '/../config/koneksi.php';
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
        $kode = isset($_GET['kode']) ? trim($_GET['kode']) : '';
        $from = isset($_GET['from']) ? trim($_GET['from']) : '';

        if (empty($kode)) {
            header("Location: ../index.php");
            exit();
        }

        $backLink = '../index.php';
        $backText = 'Kembali ke Beranda';

        if ($from === 'admin-booking') {
            $backLink = '../panel-pengelola/booking.php';
            $backText = 'Kembali ke Data Booking';
        } elseif ($from === 'riwayat') {
            $backLink = 'riwayat.php';
            $backText = 'Kembali ke Riwayat';
        }

        $data = $this->bookingModel->getBookingByCode($kode);

        if (!$data) {
            die("Data booking tidak ditemukan.");
        }

        $formattedStatus = $this->formatStatusEticket($data['status']);

        require_once __DIR__ . '/../views/public/pages/eticket.php';
    }

    public function showRiwayat()
    {
        $data = null;
        $formattedStatus = '';
        $error = isset($_GET['error']) ? $_GET['error'] : '';
        $kodeInput = isset($_GET['kode_input']) ? htmlspecialchars($_GET['kode_input']) : '';

        if (isset($_GET['kode']) && !empty($_GET['kode'])) {
            $kode = trim($_GET['kode']);
            $data = $this->bookingModel->getBookingByCode($kode);

            if (!$data) {
                $error = 'notfound';
            } else {
                $formattedStatus = $this->formatStatusRiwayat($data['status']);
            }
        }

        require_once __DIR__ . '/../views/public/pages/riwayat.php';
    }

    private function formatStatusEticket($status)
    {
        switch ($status) {
            case 'confirmed':
                return 'Confirmed';
            case 'cancelled':
                return 'Cancelled';
            default:
                return 'Pending';
        }
    }

    private function formatStatusRiwayat($status)
    {
        switch ($status) {
            case 'confirmed':
                return 'Dikonfirmasi';
            case 'cancelled':
                return 'Dibatalkan';
            default:
                return 'Pending';
        }
    }
}
?>