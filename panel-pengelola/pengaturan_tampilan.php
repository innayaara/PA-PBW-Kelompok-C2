<?php
require_once __DIR__ . '/components/session_check.php';
require_once __DIR__ . '/../controllers/TampilanController.php';

$activePage = 'pengaturan_tampilan';
$error = isset($_GET['error']) ? $_GET['error'] : '';
$success = isset($_GET['success']) ? $_GET['success'] : '';

$tampilanController = new TampilanController();
$data = $tampilanController->getFirstSetting();

if (!$data) {
    die("Data pengaturan tampilan belum tersedia.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Tampilan — Admin Bukit Fajar Lestari</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin-layout.css">
    <link rel="stylesheet" href="../assets/css/admin-pengaturan.css">
</head>
<body class="admin-layout-page" id="crop-app">


<div class="admin-layout">


    <?php require_once 'components/sidebar.php'; ?>

    <main class="admin-main">


        <div class="admin-page-header">
            <h1 class="admin-page-title">Pengaturan Tampilan</h1>
            <p class="admin-page-subtitle">Atur isi hero dan about di homepage publik.</p>
        </div>

        <?php if ($success === 'updated'): ?>
            <div class="alert alert-success">Pengaturan tampilan berhasil diperbarui.</div>
        <?php endif; ?>

        <?php if ($error === 'failed'): ?>
            <div class="alert alert-danger">Pengaturan gagal disimpan. Silakan coba lagi.</div>
        <?php elseif ($error === 'invalid_type'): ?>
            <div class="alert alert-danger">Format gambar tidak didukung. Gunakan JPG, JPEG, PNG, atau WEBP.</div>
        <?php elseif ($error === 'upload_failed'): ?>
            <div class="alert alert-danger">Upload gambar gagal. Silakan coba lagi.</div>
        <?php elseif ($error === 'too_large'): ?>
            <div class="alert alert-danger">Ukuran gambar terlalu besar. Maksimal 5MB.</div>
        <?php endif; ?>

        <form action="../controllers/process/update_pengaturan_tampilan_process.php" method="POST" @submit="onFormSubmit">

            <input type="hidden" name="id" value="<?php echo (int)$data['id']; ?>">
            <input type="hidden" name="hero_image_lama" value="<?php echo htmlspecialchars($data['hero_image']); ?>">
            <input type="hidden" name="about_image_lama" value="<?php echo htmlspecialchars($data['about_image']); ?>">

            <div class="setting-card mb-4">
                <h4 class="setting-card-title">Hero Section</h4>

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="setting-label">Hero Eyebrow</label>
                        <input type="text" name="hero_eyebrow" class="form-control setting-input" 
                            value="<?php echo htmlspecialchars($data['hero_eyebrow']); ?>"
                            v-model="formData.hero_eyebrow"
                            @input="validateField('hero_eyebrow', {required: true, minLength: 3})">
                        <small class="text-danger" v-if="errors.hero_eyebrow">{{ errors.hero_eyebrow }}</small>
                    </div>


                    <div class="col-md-4">
                        <label class="setting-label">Judul Baris 1</label>
                        <input type="text" name="hero_title_main" class="form-control setting-input" value="<?php echo htmlspecialchars($data['hero_title_main']); ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="setting-label">Judul Accent</label>
                        <input type="text" name="hero_title_accent" class="form-control setting-input" value="<?php echo htmlspecialchars($data['hero_title_accent']); ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="setting-label">Judul Baris 3</label>
                        <input type="text" name="hero_title_bottom" class="form-control setting-input" value="<?php echo htmlspecialchars($data['hero_title_bottom']); ?>">
                    </div>

                    <div class="col-12">
                        <label class="setting-label">Subtitle Hero</label>
                        <textarea name="hero_subtitle" rows="3" class="form-control setting-input"><?php echo htmlspecialchars($data['hero_subtitle']); ?></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="setting-label">Hero Image Saat Ini</label>
                        <?php if (!empty($data['hero_image'])): ?>
                            <div class="current-image-box">
                                <img src="../assets/images/galeri/<?php echo htmlspecialchars($data['hero_image']); ?>" alt="Hero Image" class="current-image-preview">
                                <div class="current-image-name"><?php echo htmlspecialchars($data['hero_image']); ?></div>
                            </div>
                        <?php else: ?>
                            <div class="text-muted">Belum ada gambar hero.</div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6 js-crop-group" data-id="hero" data-aspect-ratio="16/9" data-required="false" data-label="hero image">
                        <input type="hidden" name="hero_cropped_image" :value="groups['hero']?.croppedData">

                        <label class="setting-label">Ganti Hero Image</label>
                        <input type="file" class="form-control setting-input" accept=".jpg,.jpeg,.png,.webp,image/*" @change="handleFileChange('hero', $event)">
                        <small class="text-muted d-block mt-2">Kosongkan jika tidak ingin mengganti gambar. Rasio hero: 16:9. Maksimal 5MB.</small>

                        <div class="crop-preview-box mt-3" v-if="groups['hero']?.previewUrl">
                            <div class="crop-preview-label">Preview Hero Crop</div>
                            <img :src="groups['hero']?.previewUrl" class="crop-preview-image" alt="Preview hero crop">
                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn btn-setting-outline" @click="openCropModal('hero')" :disabled="!groups['hero']?.hasFile">
                                <i class="fas fa-crop me-2"></i>Crop Hero Image
                            </button>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="setting-label">Stat 1 Angka</label>
                        <input type="text" name="hero_stat_1_num" class="form-control setting-input" value="<?php echo htmlspecialchars($data['hero_stat_1_num']); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="setting-label">Stat 1 Label</label>
                        <input type="text" name="hero_stat_1_label" class="form-control setting-input" value="<?php echo htmlspecialchars($data['hero_stat_1_label']); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="setting-label">Stat 2 Angka</label>
                        <input type="text" name="hero_stat_2_num" class="form-control setting-input" value="<?php echo htmlspecialchars($data['hero_stat_2_num']); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="setting-label">Stat 2 Label</label>
                        <input type="text" name="hero_stat_2_label" class="form-control setting-input" value="<?php echo htmlspecialchars($data['hero_stat_2_label']); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="setting-label">Stat 3 Angka</label>
                        <input type="text" name="hero_stat_3_num" class="form-control setting-input" value="<?php echo htmlspecialchars($data['hero_stat_3_num']); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="setting-label">Stat 3 Label</label>
                        <input type="text" name="hero_stat_3_label" class="form-control setting-input" value="<?php echo htmlspecialchars($data['hero_stat_3_label']); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="setting-label">Stat 4 Angka</label>
                        <input type="text" name="hero_stat_4_num" class="form-control setting-input" value="<?php echo htmlspecialchars($data['hero_stat_4_num']); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="setting-label">Stat 4 Label</label>
                        <input type="text" name="hero_stat_4_label" class="form-control setting-input" value="<?php echo htmlspecialchars($data['hero_stat_4_label']); ?>">
                    </div>
                </div>
            </div>

            <div class="setting-card mb-4">
                <h4 class="setting-card-title">About Section</h4>

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="setting-label">About Label</label>
                        <input type="text" name="about_section_label" class="form-control setting-input" value="<?php echo htmlspecialchars($data['about_section_label']); ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="setting-label">Badge Angka</label>
                        <input type="text" name="about_badge_num" class="form-control setting-input" value="<?php echo htmlspecialchars($data['about_badge_num']); ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="setting-label">Badge Label</label>
                        <input type="text" name="about_badge_label" class="form-control setting-input" value="<?php echo htmlspecialchars($data['about_badge_label']); ?>">
                    </div>

                    <div class="col-12">
                        <label class="setting-label">Judul About</label>
                        <input type="text" name="about_title" class="form-control setting-input" value="<?php echo htmlspecialchars($data['about_title']); ?>">
                    </div>

                    <div class="col-12">
                        <label class="setting-label">Deskripsi About</label>
                        <textarea name="about_description" rows="4" class="form-control setting-input"><?php echo htmlspecialchars($data['about_description']); ?></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="setting-label">About Image Saat Ini</label>
                        <?php if (!empty($data['about_image'])): ?>
                            <div class="current-image-box">
                                <img src="../assets/images/galeri/<?php echo htmlspecialchars($data['about_image']); ?>" alt="About Image" class="current-image-preview">
                                <div class="current-image-name"><?php echo htmlspecialchars($data['about_image']); ?></div>
                            </div>
                        <?php else: ?>
                            <div class="text-muted">Belum ada gambar about.</div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6 js-crop-group" data-id="about" data-aspect-ratio="4/5" data-required="false" data-label="about image">
                        <input type="hidden" name="about_cropped_image" :value="groups['about']?.croppedData">

                        <label class="setting-label">Ganti About Image</label>
                        <input type="file" class="form-control setting-input" accept=".jpg,.jpeg,.png,.webp,image/*" @change="handleFileChange('about', $event)">
                        <small class="text-muted d-block mt-2">Kosongkan jika tidak ingin mengganti gambar. Rasio about: 4:5. Maksimal 5MB.</small>

                        <div class="crop-preview-box mt-3" v-if="groups['about']?.previewUrl">
                            <div class="crop-preview-label">Preview About Crop</div>
                            <img :src="groups['about']?.previewUrl" class="crop-preview-image" alt="Preview about crop">
                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn btn-setting-outline" @click="openCropModal('about')" :disabled="!groups['about']?.hasFile">
                                <i class="fas fa-crop me-2"></i>Crop About Image
                            </button>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="setting-label">Feature 1 Icon</label>
                        <input type="text" name="feature_1_icon" class="form-control setting-input" value="<?php echo htmlspecialchars($data['feature_1_icon']); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="setting-label">Feature 1 Title</label>
                        <input type="text" name="feature_1_title" class="form-control setting-input" value="<?php echo htmlspecialchars($data['feature_1_title']); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="setting-label">Feature 1 Desc</label>
                        <input type="text" name="feature_1_desc" class="form-control setting-input" value="<?php echo htmlspecialchars($data['feature_1_desc']); ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="setting-label">Feature 2 Icon</label>
                        <input type="text" name="feature_2_icon" class="form-control setting-input" value="<?php echo htmlspecialchars($data['feature_2_icon']); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="setting-label">Feature 2 Title</label>
                        <input type="text" name="feature_2_title" class="form-control setting-input" value="<?php echo htmlspecialchars($data['feature_2_title']); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="setting-label">Feature 2 Desc</label>
                        <input type="text" name="feature_2_desc" class="form-control setting-input" value="<?php echo htmlspecialchars($data['feature_2_desc']); ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="setting-label">Feature 3 Icon</label>
                        <input type="text" name="feature_3_icon" class="form-control setting-input" value="<?php echo htmlspecialchars($data['feature_3_icon']); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="setting-label">Feature 3 Title</label>
                        <input type="text" name="feature_3_title" class="form-control setting-input" value="<?php echo htmlspecialchars($data['feature_3_title']); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="setting-label">Feature 3 Desc</label>
                        <input type="text" name="feature_3_desc" class="form-control setting-input" value="<?php echo htmlspecialchars($data['feature_3_desc']); ?>">
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 flex-wrap">
                <button type="submit" class="btn btn-setting-main" :disabled="!isFormValid">
                    <i class="fas fa-save me-2"></i>Simpan Pengaturan
                </button>
            </div>
        </form>

    </main>
</div>

<script id="initial-form-data" type="application/json">
    <?php echo json_encode([
        'hero_eyebrow' => $data['hero_eyebrow']
    ]); ?>
</script>


<div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content crop-modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Crop Gambar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="cropper-container-wrap">
          <img id="imageToCrop" :src="imageToCropSrc" alt="Crop target">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-setting-outline" data-bs-dismiss="modal">Batal</button>
        <button type="button" id="useCropBtn" class="btn btn-setting-main" @click="applyCrop">
          <i class="fas fa-check me-2"></i>Gunakan Hasil Crop
        </button>
      </div>

    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/3.3.4/vue.global.prod.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script src="../assets/js/crop-vue.js"></script>

</body>
</html>