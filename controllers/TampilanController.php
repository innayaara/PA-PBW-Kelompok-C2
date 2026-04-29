<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/PengaturanTampilanModel.php';
require_once __DIR__ . '/../helpers/image_helper.php';

class TampilanController
{
    private $conn;
    private $pengaturanModel;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
        $this->pengaturanModel = new PengaturanTampilanModel($this->conn);
    }

    public function getFirstSetting()
    {
        return $this->pengaturanModel->getFirstSetting();
    }

    public function updateSetting($postData)
    {
        $id = isset($postData['id']) ? (int) $postData['id'] : 0;

        if ($id <= 0) {
            return ['success' => false, 'error' => 'failed'];
        }

        $uploadDir = __DIR__ . '/../assets/images/galeri/';

        $heroImageLama  = isset($postData['hero_image_lama']) ? trim($postData['hero_image_lama']) : '';
        $aboutImageLama = isset($postData['about_image_lama']) ? trim($postData['about_image_lama']) : '';

        $heroCroppedImage  = isset($postData['hero_cropped_image']) ? trim($postData['hero_cropped_image']) : '';
        $aboutCroppedImage = isset($postData['about_cropped_image']) ? trim($postData['about_cropped_image']) : '';

        $heroImageBaru  = $heroImageLama;
        $aboutImageBaru = $aboutImageLama;

        $heroNewFilePath = '';
        $aboutNewFilePath = '';

        if (!empty($heroCroppedImage)) {
            $heroUpload = saveBase64Image($heroCroppedImage, $uploadDir, 'hero-');
            if (!$heroUpload['success']) {
                return ['success' => false, 'error' => $this->mapImageError($heroUpload['error'])];
            }
            $heroImageBaru = $heroUpload['file_name'];
            $heroNewFilePath = $heroUpload['file_path'];
        }

        if (!empty($aboutCroppedImage)) {
            $aboutUpload = saveBase64Image($aboutCroppedImage, $uploadDir, 'about-');
            if (!$aboutUpload['success']) {
                if (!empty($heroNewFilePath)) {
                    deleteImageFile($heroNewFilePath);
                }
                return ['success' => false, 'error' => $this->mapImageError($aboutUpload['error'])];
            }
            $aboutImageBaru = $aboutUpload['file_name'];
            $aboutNewFilePath = $aboutUpload['file_path'];
        }

        $fields = [
            'hero_eyebrow', 'hero_title_main', 'hero_title_accent', 'hero_title_bottom', 'hero_subtitle',
            'hero_stat_1_num', 'hero_stat_1_label', 'hero_stat_2_num', 'hero_stat_2_label',
            'hero_stat_3_num', 'hero_stat_3_label', 'hero_stat_4_num', 'hero_stat_4_label',
            'about_badge_num', 'about_badge_label', 'about_section_label', 'about_title', 'about_description',
            'feature_1_icon', 'feature_1_title', 'feature_1_desc',
            'feature_2_icon', 'feature_2_title', 'feature_2_desc',
            'feature_3_icon', 'feature_3_title', 'feature_3_desc'
        ];

        $data = [];
        foreach ($fields as $field) {
            $data[$field] = trim($postData[$field] ?? '');
        }

        $data['hero_image'] = $heroImageBaru;
        $data['about_image'] = $aboutImageBaru;

        if ($this->pengaturanModel->updateSetting($id, $data)) {
            if (!empty($heroCroppedImage) && $heroImageBaru !== $heroImageLama && !empty($heroImageLama)) {
                deleteImageFile($uploadDir . $heroImageLama);
            }

            if (!empty($aboutCroppedImage) && $aboutImageBaru !== $aboutImageLama && !empty($aboutImageLama)) {
                deleteImageFile($uploadDir . $aboutImageLama);
            }

            return ['success' => true];
        }

        if (!empty($heroNewFilePath)) {
            deleteImageFile($heroNewFilePath);
        }

        if (!empty($aboutNewFilePath)) {
            deleteImageFile($aboutNewFilePath);
        }

        return ['success' => false, 'error' => 'failed'];
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
