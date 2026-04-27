<?php

class WisataModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Untuk homepage / public
    public function getActiveWisata($limit = 6)
    {
        $limit = (int) $limit;

        $query = "SELECT * FROM wisata 
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

    // Untuk admin
    public function getFilteredWisata($search = '', $status = '')
    {
        $sql = "SELECT * FROM wisata WHERE 1=1";

        if (!empty($search)) {
            $searchEscaped = mysqli_real_escape_string($this->conn, $search);
            $sql .= " AND (
                nama_wisata LIKE '%$searchEscaped%' OR
                kategori LIKE '%$searchEscaped%' OR
                lokasi LIKE '%$searchEscaped%'
            )";
        }

        if (!empty($status) && in_array($status, ['aktif', 'nonaktif'], true)) {
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

    public function getByName($nama)
    {
        $namaEscaped = mysqli_real_escape_string($this->conn, $nama);
        $query = "SELECT * FROM wisata WHERE nama_wisata = '$namaEscaped' LIMIT 1";
        $result = mysqli_query($this->conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }

        return null;
    }

    public function getById($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return null;
        }

        $query = "SELECT * FROM wisata WHERE id = $id LIMIT 1";
        $result = mysqli_query($this->conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }

        return null;
    }

    public function generateUniqueSlug($text, $excludeId = 0)
    {
        $slug = $this->createSlug($text);
        $slugBase = $slug;
        $counter = 1;
        $excludeId = (int) $excludeId;

        while (true) {
            $slugEscaped = mysqli_real_escape_string($this->conn, $slug);

            if ($excludeId > 0) {
                $checkQuery = "SELECT id FROM wisata WHERE slug = '$slugEscaped' AND id != $excludeId LIMIT 1";
            } else {
                $checkQuery = "SELECT id FROM wisata WHERE slug = '$slugEscaped' LIMIT 1";
            }

            $checkResult = mysqli_query($this->conn, $checkQuery);

            if ($checkResult && mysqli_num_rows($checkResult) === 0) {
                break;
            }

            $slug = $slugBase . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function insertWisata($data)
    {
        $nama_wisata   = mysqli_real_escape_string($this->conn, $data['nama_wisata']);
        $slug          = mysqli_real_escape_string($this->conn, $data['slug']);
        $kategori      = mysqli_real_escape_string($this->conn, $data['kategori']);
        $lokasi        = mysqli_real_escape_string($this->conn, $data['lokasi']);
        $jam_buka      = mysqli_real_escape_string($this->conn, $data['jam_buka']);
        $harga_weekday = (float) $data['harga_weekday'];
        $harga_weekend = (float) $data['harga_weekend'];
        $status        = mysqli_real_escape_string($this->conn, $data['status']);
        $deskripsi     = mysqli_real_escape_string($this->conn, $data['deskripsi']);
        $fasilitas     = mysqli_real_escape_string($this->conn, $data['fasilitas']);
        $thumbnail     = mysqli_real_escape_string($this->conn, $data['thumbnail']);

        $query = "INSERT INTO wisata (
                    nama_wisata,
                    slug,
                    deskripsi,
                    lokasi,
                    jam_buka,
                    harga_weekday,
                    harga_weekend,
                    fasilitas,
                    kategori,
                    thumbnail,
                    status,
                    kuota_harian
                  ) VALUES (
                    '$nama_wisata',
                    '$slug',
                    '$deskripsi',
                    '$lokasi',
                    '$jam_buka',
                    $harga_weekday,
                    $harga_weekend,
                    '$fasilitas',
                    '$kategori',
                    '$thumbnail',
                    '$status',
                    " . (int) $data['kuota_harian'] . "
                  )";

        return mysqli_query($this->conn, $query);
    }

    public function updateWisata($id, $data)
    {
        $id            = (int) $id;
        $nama_wisata   = mysqli_real_escape_string($this->conn, $data['nama_wisata']);
        $slug          = mysqli_real_escape_string($this->conn, $data['slug']);
        $kategori      = mysqli_real_escape_string($this->conn, $data['kategori']);
        $lokasi        = mysqli_real_escape_string($this->conn, $data['lokasi']);
        $jam_buka      = mysqli_real_escape_string($this->conn, $data['jam_buka']);
        $harga_weekday = (float) $data['harga_weekday'];
        $harga_weekend = (float) $data['harga_weekend'];
        $status        = mysqli_real_escape_string($this->conn, $data['status']);
        $deskripsi     = mysqli_real_escape_string($this->conn, $data['deskripsi']);
        $fasilitas     = mysqli_real_escape_string($this->conn, $data['fasilitas']);
        $thumbnail     = mysqli_real_escape_string($this->conn, $data['thumbnail']);

        $query = "UPDATE wisata SET
                    nama_wisata = '$nama_wisata',
                    slug = '$slug',
                    deskripsi = '$deskripsi',
                    lokasi = '$lokasi',
                    jam_buka = '$jam_buka',
                    harga_weekday = $harga_weekday,
                    harga_weekend = $harga_weekend,
                    fasilitas = '$fasilitas',
                    kategori = '$kategori',
                    thumbnail = '$thumbnail',
                    status = '$status',
                    kuota_harian = " . (int) $data['kuota_harian'] . "
                  WHERE id = $id";

        return mysqli_query($this->conn, $query);
    }

    public function deleteWisata($id)
    {
        $id = (int) $id;
        return mysqli_query($this->conn, "DELETE FROM wisata WHERE id = $id");
    }

    private function createSlug($text)
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
        $text = trim($text, '-');
        return $text;
    }
}
?>