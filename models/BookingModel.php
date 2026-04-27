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
        return "BFL-" . strtoupper(substr(md5(time() . $whatsapp . uniqid()), 0, 6));
    }

    public function createBooking($data)
    {
        $kode_booking = mysqli_real_escape_string($this->conn, $data['kode_booking']);
        $nama         = mysqli_real_escape_string($this->conn, $data['nama']);
        $whatsapp     = mysqli_real_escape_string($this->conn, $data['whatsapp']);
        $tanggal      = mysqli_real_escape_string($this->conn, $data['tanggal']);
        $jumlah       = (int) $data['jumlah'];
        $destinasi    = mysqli_real_escape_string($this->conn, $data['destinasi']);
        $catatan      = mysqli_real_escape_string($this->conn, $data['catatan']);
        $harga_satuan = (float) $data['harga_satuan'];
        $total_harga  = (float) $data['total_harga'];
        $jenis_hari   = mysqli_real_escape_string($this->conn, $data['jenis_hari']);

        $sql = "INSERT INTO bookings (
                    kode_booking,
                    nama_lengkap, 
                    whatsapp, 
                    tanggal_kunjungan, 
                    jumlah_pengunjung, 
                    destinasi, 
                    catatan, 
                    harga_satuan, 
                    total_harga, 
                    jenis_hari
                ) VALUES (
                    '$kode_booking',
                    '$nama', 
                    '$whatsapp', 
                    '$tanggal', 
                    $jumlah, 
                    '$destinasi', 
                    '$catatan', 
                    $harga_satuan, 
                    $total_harga, 
                    '$jenis_hari'
                )";

        return mysqli_query($this->conn, $sql);
    }

    public function getBookingByCode($kode)
    {
        $stmt = $this->conn->prepare("SELECT * FROM bookings WHERE kode_booking = ? LIMIT 1");
        $stmt->bind_param("s", $kode);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }

    public function checkBooking($kode_booking, $whatsapp = '')
    {
        if (!empty($whatsapp)) {
            $stmt = $this->conn->prepare("SELECT id FROM bookings WHERE kode_booking = ? AND whatsapp = ? LIMIT 1");
            $stmt->bind_param("ss", $kode_booking, $whatsapp);
        } else {
            $stmt = $this->conn->prepare("SELECT id FROM bookings WHERE kode_booking = ? LIMIT 1");
            $stmt->bind_param("s", $kode_booking);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        return ($result && $result->num_rows > 0);
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
        $destinasiEscaped = mysqli_real_escape_string($this->conn, $destinasi);
        $tanggalEscaped   = mysqli_real_escape_string($this->conn, $tanggal);

        $query = "SELECT SUM(jumlah_pengunjung) AS total 
                  FROM bookings 
                  WHERE destinasi = '$destinasiEscaped' 
                  AND tanggal_kunjungan = '$tanggalEscaped' 
                  AND status IN ('confirmed', 'pending')";

        $result = mysqli_query($this->conn, $query);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return (int) ($row['total'] ?? 0);
        }

        return 0;
    }

    public function getMonthlyBookings($destinasi, $month)
    {
        $destinasiEscaped = mysqli_real_escape_string($this->conn, $destinasi);
        $monthEscaped     = mysqli_real_escape_string($this->conn, $month);

        $query = "SELECT tanggal_kunjungan, SUM(jumlah_pengunjung) AS total 
                  FROM bookings 
                  WHERE destinasi = '$destinasiEscaped' 
                  AND tanggal_kunjungan LIKE '$monthEscaped-%' 
                  AND status IN ('confirmed', 'pending')
                  GROUP BY tanggal_kunjungan";

        $result = mysqli_query($this->conn, $query);
        $data = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[$row['tanggal_kunjungan']] = (int) $row['total'];
            }
        }

        return $data;
    }
}
?>