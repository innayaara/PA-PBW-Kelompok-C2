<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/GaleriModel.php';
require_once __DIR__ . '/../helpers/image_helper.php';

class GaleriController
{
    private $conn;
    private $galeriModel;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
        $this->galeriModel = new GaleriModel($this->conn);
    }

    public function getFilteredGaleri($search = '', $status = '')
    {
        return $this->galeriModel->getFilteredGaleri($search, $status);
    }

    public function getGaleriById($id)
    {
        return $this->galeriModel->getById($id);
    }

    public function createGaleri($postData)
    {
        $wisata_id     = isset($postData['wisata_id']) && $postData['wisata_id'] !== '' ? (int) $postData['wisata_id'] : null;
        $judul_foto    = isset($postData['judul_foto']) ? trim($postData['judul_foto']) : '';
        $deskripsi     = isset($postData['deskripsi']) ? trim($postData['deskripsi']) : '';
        $kategori      = isset($postData['kategori']) ? trim($postData['kategori']) : '';
        $status        = isset($postData['status']) ? trim($postData['status']) : 'aktif';
        $cropped_image = isset($postData['cropped_image']) ? trim($postData['cropped_image']) : '';

        if (empty($judul_foto) || empty($cropped_image)) {
            return ['success' => false, 'error' => 'empty'];
        }

        $uploadDir = __DIR__ . '/../assets/images/galeri/';
        $imageResult = saveBase64Image($cropped_image, $uploadDir, 'galeri-');

        if (!$imageResult['success']) {
            return ['success' => false, 'error' => $this->mapImageError($imageResult['error'])];
        }

        $data = [
            'wisata_id'  => $wisata_id,
            'judul_foto' => $judul_foto,
            'deskripsi'  => $deskripsi,
            'gambar'     => $imageResult['file_name'],
            'kategori'   => $kategori,
            'status'     => $status
        ];

        if ($this->galeriModel->insertGaleri($data)) {
            return ['success' => true];
        }

        deleteImageFile($imageResult['file_path']);
        return ['success' => false, 'error' => 'failed'];
    }

    public function updateGaleri($postData)
    {
        $id            = isset($postData['id']) ? (int) $postData['id'] : 0;
        $wisata_id     = isset($postData['wisata_id']) && $postData['wisata_id'] !== '' ? (int) $postData['wisata_id'] : null;
        $judul_foto    = isset($postData['judul_foto']) ? trim($postData['judul_foto']) : '';
        $deskripsi     = isset($postData['deskripsi']) ? trim($postData['deskripsi']) : '';
        $kategori      = isset($postData['kategori']) ? trim($postData['kategori']) : '';
        $status        = isset($postData['status']) ? trim($postData['status']) : 'aktif';
        $gambar_lama   = isset($postData['gambar_lama']) ? trim($postData['gambar_lama']) : '';
        $cropped_image = isset($postData['cropped_image']) ? trim($postData['cropped_image']) : '';

        if ($id <= 0 || empty($judul_foto)) {
            return ['success' => false, 'error' => 'empty', 'id' => $id];
        }

        $gambar_baru = $gambar_lama;
        $newFilePath = '';
        $uploadDir = __DIR__ . '/../assets/images/galeri/';

        if (!empty($cropped_image)) {
            $imageResult = saveBase64Image($cropped_image, $uploadDir, 'galeri-');

            if (!$imageResult['success']) {
                return ['success' => false, 'error' => $this->mapImageError($imageResult['error']), 'id' => $id];
            }

            $gambar_baru = $imageResult['file_name'];
            $newFilePath = $imageResult['file_path'];
        }

        $data = [
            'wisata_id'  => $wisata_id,
            'judul_foto' => $judul_foto,
            'deskripsi'  => $deskripsi,
            'gambar'     => $gambar_baru,
            'kategori'   => $kategori,
            'status'     => $status
        ];

        if ($this->galeriModel->updateGaleri($id, $data)) {
            if (!empty($cropped_image) && $gambar_baru !== $gambar_lama && !empty($gambar_lama)) {
                deleteImageFile($uploadDir . $gambar_lama);
            }

            return ['success' => true];
        }

        if (!empty($newFilePath)) {
            deleteImageFile($newFilePath);
        }

        return ['success' => false, 'error' => 'failed', 'id' => $id];
    }

    public function deleteGaleri($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            return false;
        }

        $data = $this->galeriModel->getById($id);
        if (!$data) {
            return false;
        }

        $deleted = $this->galeriModel->deleteGaleri($id);

        if ($deleted && !empty($data['gambar'])) {
            deleteImageFile(__DIR__ . '/../assets/images/galeri/' . $data['gambar']);
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
