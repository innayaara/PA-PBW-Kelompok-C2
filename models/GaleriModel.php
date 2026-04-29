<?php

class GaleriModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getActiveGaleri($limit = 6)
    {
        $limit = (int) $limit;

        $query = "SELECT * FROM galeri 
                  WHERE status = 'aktif' 
                  ORDER BY created_at DESC 
                  LIMIT {$limit}";

        $result = mysqli_query($this->conn, $query);
        $data = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function getAllActiveGaleri($kategori = '')
    {
        $sql = "SELECT galeri.*, wisata.nama_wisata 
                FROM galeri 
                LEFT JOIN wisata ON galeri.wisata_id = wisata.id
                WHERE galeri.status = 'aktif'";

        if (!empty($kategori)) {
            $kategoriEscaped = mysqli_real_escape_string($this->conn, $kategori);
            $sql .= " AND galeri.kategori = '$kategoriEscaped'";
        }

        $sql .= " ORDER BY galeri.created_at DESC";

        $result = mysqli_query($this->conn, $sql);
        $data = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function getActiveCategories()
    {
        $query = "SELECT DISTINCT kategori FROM galeri 
                  WHERE status = 'aktif' AND kategori IS NOT NULL AND kategori != ''
                  ORDER BY kategori ASC";

        $result = mysqli_query($this->conn, $query);
        $data = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row['kategori'];
            }
        }

        return $data;
    }

    public function getFilteredGaleri($search = '', $status = '')
    {
        $sql = "SELECT galeri.*, wisata.nama_wisata 
                FROM galeri 
                LEFT JOIN wisata ON galeri.wisata_id = wisata.id
                WHERE 1=1";

        if (!empty($search)) {
            $searchEscaped = mysqli_real_escape_string($this->conn, $search);
            $sql .= " AND (
                galeri.judul_foto LIKE '%$searchEscaped%' OR
                galeri.kategori LIKE '%$searchEscaped%' OR
                wisata.nama_wisata LIKE '%$searchEscaped%'
            )";
        }

        if (!empty($status) && in_array($status, ['aktif', 'nonaktif'], true)) {
            $statusEscaped = mysqli_real_escape_string($this->conn, $status);
            $sql .= " AND galeri.status = '$statusEscaped'";
        }

        $sql .= " ORDER BY galeri.created_at DESC";

        $result = mysqli_query($this->conn, $sql);
        $data = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function getById($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return null;
        }

        $query = "SELECT * FROM galeri WHERE id = $id LIMIT 1";
        $result = mysqli_query($this->conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }

        return null;
    }

    public function insertGaleri($data)
    {
        $wisata_id  = isset($data['wisata_id']) && $data['wisata_id'] !== null ? (int) $data['wisata_id'] : null;
        $judul_foto = mysqli_real_escape_string($this->conn, $data['judul_foto']);
        $deskripsi  = mysqli_real_escape_string($this->conn, $data['deskripsi']);
        $gambar     = mysqli_real_escape_string($this->conn, $data['gambar']);
        $kategori   = mysqli_real_escape_string($this->conn, $data['kategori']);
        $status     = mysqli_real_escape_string($this->conn, $data['status']);

        $wisataValue = is_null($wisata_id) ? "NULL" : $wisata_id;

        $query = "INSERT INTO galeri (
                    wisata_id,
                    judul_foto,
                    deskripsi,
                    gambar,
                    kategori,
                    status
                  ) VALUES (
                    $wisataValue,
                    '$judul_foto',
                    '$deskripsi',
                    '$gambar',
                    '$kategori',
                    '$status'
                  )";

        return mysqli_query($this->conn, $query);
    }

    public function updateGaleri($id, $data)
    {
        $id         = (int) $id;
        $wisata_id  = isset($data['wisata_id']) && $data['wisata_id'] !== null ? (int) $data['wisata_id'] : null;
        $judul_foto = mysqli_real_escape_string($this->conn, $data['judul_foto']);
        $deskripsi  = mysqli_real_escape_string($this->conn, $data['deskripsi']);
        $gambar     = mysqli_real_escape_string($this->conn, $data['gambar']);
        $kategori   = mysqli_real_escape_string($this->conn, $data['kategori']);
        $status     = mysqli_real_escape_string($this->conn, $data['status']);

        $wisataValue = is_null($wisata_id) ? "NULL" : $wisata_id;

        $query = "UPDATE galeri SET
                    wisata_id = $wisataValue,
                    judul_foto = '$judul_foto',
                    deskripsi = '$deskripsi',
                    gambar = '$gambar',
                    kategori = '$kategori',
                    status = '$status'
                  WHERE id = $id";

        return mysqli_query($this->conn, $query);
    }

    public function deleteGaleri($id)
    {
        $id = (int) $id;
        return mysqli_query($this->conn, "DELETE FROM galeri WHERE id = $id");
    }
}
?>