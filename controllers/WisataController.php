<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/WisataModel.php';
require_once __DIR__ . '/../helpers/image_helper.php';

class WisataController
{
    private $conn;
    private $wisataModel;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
        $this->wisataModel = new WisataModel($this->conn);
    }

    public function getFilteredWisata($search = '', $status = '')
    {
        return $this->wisataModel->getFilteredWisata($search, $status);
    }

    public function getWisataById($id)
    {
        return $this->wisataModel->getById($id);
    }

    public function createWisata($postData)
    {
        $nama_wisata   = isset($postData['nama_wisata']) ? trim($postData['nama_wisata']) : '';
        $kategori      = isset($postData['kategori']) ? trim($postData['kategori']) : '';
        $lokasi        = isset($postData['lokasi']) ? trim($postData['lokasi']) : '';
        $jam_buka      = isset($postData['jam_buka']) ? trim($postData['jam_buka']) : '';
        $harga_weekday = isset($postData['harga_weekday']) ? (float) $postData['harga_weekday'] : 0;
        $harga_weekend = isset($postData['harga_weekend']) ? (float) $postData['harga_weekend'] : 0;
        $status        = isset($postData['status']) ? trim($postData['status']) : 'aktif';
        $deskripsi     = isset($postData['deskripsi']) ? trim($postData['deskripsi']) : '';
        $fasilitas     = isset($postData['fasilitas']) ? trim($postData['fasilitas']) : '';
        $cropped_image = isset($postData['cropped_image']) ? trim($postData['cropped_image']) : '';
        $kuota_harian  = isset($postData['kuota_harian']) ? (int) $postData['kuota_harian'] : 0;

        if (empty($nama_wisata) || empty($deskripsi) || $harga_weekday < 0 || $harga_weekend < 0 || empty($cropped_image)) {
            return ['success' => false, 'error' => 'empty'];
        }

        $uploadDir = __DIR__ . '/../assets/images/galeri/';
        $imageResult = saveBase64Image($cropped_image, $uploadDir, 'wisata-');

        if (!$imageResult['success']) {
            return ['success' => false, 'error' => $this->mapImageError($imageResult['error'])];
        }

        $slug = $this->wisataModel->generateUniqueSlug($nama_wisata);

        $data = [
            'nama_wisata'   => $nama_wisata,
            'slug'          => $slug,
            'kategori'      => $kategori,
            'lokasi'        => $lokasi,
            'jam_buka'      => $jam_buka,
            'harga_weekday' => $harga_weekday,
            'harga_weekend' => $harga_weekend,
            'status'        => $status,
            'deskripsi'     => $deskripsi,
            'fasilitas'     => $fasilitas,
            'thumbnail'     => $imageResult['file_name'],
            'kuota_harian'  => $kuota_harian
        ];

        if ($this->wisataModel->insertWisata($data)) {
            return ['success' => true];
        }

        deleteImageFile($imageResult['file_path']);
        return ['success' => false, 'error' => 'failed'];
    }

    public function updateWisata($postData)
    {
        $id             = isset($postData['id']) ? (int) $postData['id'] : 0;
        $nama_wisata    = isset($postData['nama_wisata']) ? trim($postData['nama_wisata']) : '';
        $kategori       = isset($postData['kategori']) ? trim($postData['kategori']) : '';
        $lokasi         = isset($postData['lokasi']) ? trim($postData['lokasi']) : '';
        $jam_buka       = isset($postData['jam_buka']) ? trim($postData['jam_buka']) : '';
        $harga_weekday  = isset($postData['harga_weekday']) ? (float) $postData['harga_weekday'] : 0;
        $harga_weekend  = isset($postData['harga_weekend']) ? (float) $postData['harga_weekend'] : 0;
        $status         = isset($postData['status']) ? trim($postData['status']) : 'aktif';
        $deskripsi      = isset($postData['deskripsi']) ? trim($postData['deskripsi']) : '';
        $fasilitas      = isset($postData['fasilitas']) ? trim($postData['fasilitas']) : '';
        $thumbnail_lama = isset($postData['thumbnail_lama']) ? trim($postData['thumbnail_lama']) : '';
        $cropped_image  = isset($postData['cropped_image']) ? trim($postData['cropped_image']) : '';
        $kuota_harian   = isset($postData['kuota_harian']) ? (int) $postData['kuota_harian'] : 0;

        if ($id <= 0 || empty($nama_wisata) || empty($deskripsi) || $harga_weekday < 0 || $harga_weekend < 0) {
            return ['success' => false, 'error' => 'empty', 'id' => $id];
        }

        $thumbnail_baru = $thumbnail_lama;
        $newFilePath = '';
        $uploadDir = __DIR__ . '/../assets/images/galeri/';

        if (!empty($cropped_image)) {
            $imageResult = saveBase64Image($cropped_image, $uploadDir, 'wisata-');

            if (!$imageResult['success']) {
                return ['success' => false, 'error' => $this->mapImageError($imageResult['error']), 'id' => $id];
            }

            $thumbnail_baru = $imageResult['file_name'];
            $newFilePath = $imageResult['file_path'];
        }

        $slug = $this->wisataModel->generateUniqueSlug($nama_wisata, $id);

        $data = [
            'nama_wisata'   => $nama_wisata,
            'slug'          => $slug,
            'kategori'      => $kategori,
            'lokasi'        => $lokasi,
            'jam_buka'      => $jam_buka,
            'harga_weekday' => $harga_weekday,
            'harga_weekend' => $harga_weekend,
            'status'        => $status,
            'deskripsi'     => $deskripsi,
            'fasilitas'     => $fasilitas,
            'thumbnail'     => $thumbnail_baru,
            'kuota_harian'  => $kuota_harian
        ];

        if ($this->wisataModel->updateWisata($id, $data)) {
            if (!empty($cropped_image) && $thumbnail_baru !== $thumbnail_lama && !empty($thumbnail_lama)) {
                deleteImageFile($uploadDir . $thumbnail_lama);
            }

            return ['success' => true];
        }

        if (!empty($newFilePath)) {
            deleteImageFile($newFilePath);
        }

        return ['success' => false, 'error' => 'failed', 'id' => $id];
    }

    public function deleteWisata($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return false;
        }

        $data = $this->wisataModel->getById($id);
        if (!$data) {
            return false;
        }

        $deleted = $this->wisataModel->deleteWisata($id);

        if ($deleted && !empty($data['thumbnail'])) {
            deleteImageFile(__DIR__ . '/../assets/images/galeri/' . $data['thumbnail']);
        }

        return $deleted;
    }

    private function mapImageError($errorCode)
    {
        switch ($errorCode) {
            case 'invalid_type':
                return 'invalid_type';
            case 'too_large':
                return 'too_large';
            case 'upload_failed':
                return 'upload_failed';
            default:
                return 'failed';
        }
    }
}
?>
