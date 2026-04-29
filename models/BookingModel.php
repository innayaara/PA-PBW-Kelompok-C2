<?php

class BookingModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function generateBookingCode($whatsapp)
    {
        return "BFL-" . strtoupper(substr(md5(time() . $whatsapp . uniqid('', true)), 0, 6));
    }

    public function createBooking($data)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO bookings (
                kode_booking,
                access_token,
                nama_lengkap,
                whatsapp,
                tanggal_kunjungan,
                jumlah_pengunjung,
                destinasi,
                catatan,
                harga_satuan,
                total_harga,
                jenis_hari
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssssissdds",
            $data['kode_booking'],
            $data['access_token'],
            $data['nama'],
            $data['whatsapp'],
            $data['tanggal'],
            $data['jumlah'],
            $data['destinasi'],
            $data['catatan'],
            $data['harga_satuan'],
            $data['total_harga'],
            $data['jenis_hari']
        );

        return $stmt->execute();
    }

    public function getBookingByCode($kode)
    {
        $stmt = $this->conn->prepare("SELECT * FROM bookings WHERE kode_booking = ? LIMIT 1");
        $stmt->bind_param("s", $kode);
        $stmt->execute();

        $result = $stmt->get_result();

        return ($result && $result->num_rows > 0) ? $result->fetch_assoc() : null;
    }

    public function getBookingByCodeAndToken($kode_booking, $token)
    {
        $stmt = $this->conn->prepare("
            SELECT * FROM bookings 
            WHERE kode_booking = ? 
            AND access_token = ? 
            LIMIT 1
        ");

        $stmt->bind_param("ss", $kode_booking, $token);
        $stmt->execute();

        $result = $stmt->get_result();

        return ($result && $result->num_rows > 0) ? $result->fetch_assoc() : null;
    }

    public function getBookingByCodeAndWhatsapp($kode_booking, $whatsapp)
    {
        $stmt = $this->conn->prepare("
            SELECT * FROM bookings 
            WHERE kode_booking = ?
            AND REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(whatsapp, '+', ''), ' ', ''), '-', ''), '(', ''), ')', '') = ?
            LIMIT 1
        ");

        $stmt->bind_param("ss", $kode_booking, $whatsapp);
        $stmt->execute();

        $result = $stmt->get_result();

        return ($result && $result->num_rows > 0) ? $result->fetch_assoc() : null;
    }

    public function updateAccessToken($id, $token)
    {
        $id = (int) $id;

        if ($id <= 0 || empty($token)) {
            return false;
        }

        $stmt = $this->conn->prepare("UPDATE bookings SET access_token = ? WHERE id = ?");
        $stmt->bind_param("si", $token, $id);

        return $stmt->execute();
    }

    public function checkBooking($kode_booking, $whatsapp)
    {
        return $this->getBookingByCodeAndWhatsapp($kode_booking, $whatsapp) !== null;
    }

    public function getFilteredBookings($search = '', $status = '')
    {
        $sql = "SELECT * FROM bookings WHERE 1=1";

        if (!empty($search)) {
            $searchEscaped = mysqli_real_escape_string($this->conn, $search);
            $sql .= " AND (
                kode_booking LIKE '%$searchEscaped%' OR
                nama_lengkap LIKE '%$searchEscaped%' OR
                whatsapp LIKE '%$searchEscaped%'
            )";
        }

        if (!empty($status) && in_array($status, ['pending', 'confirmed', 'cancelled'], true)) {
            $statusEscaped = mysqli_real_escape_string($this->conn, $status);
            $sql .= " AND status = '$statusEscaped'";
        }

        $sql .= " ORDER BY created_at DESC";

        $result = mysqli_query($this->conn, $sql);
        $data = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function updateBookingStatus($id, $status)
    {
        $id = (int) $id;

        if ($id <= 0 || !in_array($status, ['pending', 'confirmed', 'cancelled'], true)) {
            return false;
        }

        $stmt = $this->conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);

        return $stmt->execute();
    }

    public function deleteBooking($id)
    {
        $id = (int) $id;

        if ($id <= 0) {
            return false;
        }

        $stmt = $this->conn->prepare("DELETE FROM bookings WHERE id = ?");
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    public function countAllBookings()
    {
        return $this->getCount("SELECT COUNT(*) AS total FROM bookings");
    }

    public function countBookingsByStatus($status)
    {
        $status = mysqli_real_escape_string($this->conn, $status);
        return $this->getCount("SELECT COUNT(*) AS total FROM bookings WHERE status = '$status'");
    }

    private function getCount($query)
    {
        $result = mysqli_query($this->conn, $query);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return (int) ($row['total'] ?? 0);
        }

        return 0;
    }

    public function getTicketCountByDate($destinasi, $tanggal)
    {
        $stmt = $this->conn->prepare("
            SELECT SUM(jumlah_pengunjung) AS total 
            FROM bookings 
            WHERE destinasi = ?
            AND tanggal_kunjungan = ?
            AND status IN ('confirmed', 'pending')
        ");

        $stmt->bind_param("ss", $destinasi, $tanggal);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result) {
            $row = $result->fetch_assoc();
            return (int) ($row['total'] ?? 0);
        }

        return 0;
    }

    public function getMonthlyBookings($destinasi, $month)
    {
        $likeMonth = $month . '-%';

        $stmt = $this->conn->prepare("
            SELECT tanggal_kunjungan, SUM(jumlah_pengunjung) AS total 
            FROM bookings 
            WHERE destinasi = ?
            AND tanggal_kunjungan LIKE ?
            AND status IN ('confirmed', 'pending')
            GROUP BY tanggal_kunjungan
        ");

        $stmt->bind_param("ss", $destinasi, $likeMonth);
        $stmt->execute();

        $result = $stmt->get_result();
        $data = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[$row['tanggal_kunjungan']] = (int) $row['total'];
            }
        }

        return $data;
    }
}
?>