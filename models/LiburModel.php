<?php

class LiburModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllLibur()
    {
        $sql = "SELECT * FROM wisata_libur ORDER BY tanggal DESC";
        $result = mysqli_query($this->conn, $sql);
        $data = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function addLibur($destinasi, $tanggal, $keterangan)
    {
        $destinasiEscaped  = mysqli_real_escape_string($this->conn, $destinasi);
        $tanggalEscaped    = mysqli_real_escape_string($this->conn, $tanggal);
        $keteranganEscaped = mysqli_real_escape_string($this->conn, $keterangan);

        $sql = "INSERT INTO wisata_libur (destinasi, tanggal, keterangan) 
                VALUES ('$destinasiEscaped', '$tanggalEscaped', '$keteranganEscaped')";
        return mysqli_query($this->conn, $sql);
    }

    public function deleteLibur($id)
    {
        $id = (int) $id;
        return mysqli_query($this->conn, "DELETE FROM wisata_libur WHERE id = $id");
    }

    public function getLiburByMonth($destinasi, $month)
    {
        $destinasiEscaped = mysqli_real_escape_string($this->conn, trim($destinasi));
        $monthEscaped     = mysqli_real_escape_string($this->conn, trim($month));

        // Gunakan TRIM untuk memastikan spasi tersembunyi tidak mengganggu
        $sql = "SELECT tanggal FROM wisata_libur 
                WHERE TRIM(destinasi) = '$destinasiEscaped' 
                AND tanggal LIKE '$monthEscaped-%'";
        
        $result = mysqli_query($this->conn, $sql);
        $dates = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $dates[] = $row['tanggal'];
            }
        }
        return $dates;
    }
}
