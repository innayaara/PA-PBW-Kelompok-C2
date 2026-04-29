<?php
require_once __DIR__ . '/components/session_check.php';
require_once __DIR__ . '/../controllers/GaleriController.php';
require_once __DIR__ . '/../controllers/WisataController.php';

$activePage = 'galeri';
$error = isset($_GET['error']) ? $_GET['error'] : '';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: galeri.php");
    exit();
}

$galeriController = new GaleriController();
$data = $galeriController->getGaleriById($id);

if (!$data) {
    header("Location: galeri.php");
    exit();
}

$wisataController = new WisataController();
$wisataAktifList = $wisataController->getFilteredWisata('', 'aktif');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Galeri — Admin Bukit Fajar Lestari</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin-layout.css">
    <link rel="stylesheet" href="../assets/css/admin-galeri.css">
</head>
<body class="admin-layout-page" id="crop-app">


<div class="admin-layout">


    <?php require_once 'components/sidebar.php'; ?>

    <main class="admin-main">


        <div class="admin-page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="admin-page-title">Edit Galeri</h1>
                <p class="admin-page-subtitle">Perbarui data foto galeri wisata.</p>
            </div>
            <a href="galeri.php" class="btn btn-galeri-outline">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <?php if ($error === 'empty'): ?>
            <div class="alert alert-warning">Judul foto wajib diisi.</div>
        <?php elseif ($error === 'failed'): ?>
            <div class="alert alert-danger">Data galeri gagal diperbarui. Silakan coba lagi.</div>
        <?php elseif ($error === 'invalid_type'): ?>
            <div class="alert alert-danger">Format file tidak didukung. Gunakan JPG, JPEG, PNG, atau WEBP.</div>
        <?php elseif ($error === 'upload_failed'): ?>
            <div class="alert alert-danger">Proses gambar gagal. Silakan coba lagi.</div>
        <?php elseif ($error === 'too_large'): ?>
            <div class="alert alert-danger">Ukuran gambar terlalu besar. Maksimal 5MB.</div>
        <?php endif; ?>

        <div class="galeri-form-card">

            <form id="editGaleriForm" action="../controllers/process/update_galeri_process.php" method="POST" class="row g-4" @submit="onFormSubmit">
                <input type="hidden" name="id" value="<?php echo (int)$data['id']; ?>">
                <input type="hidden" name="gambar_lama" value="<?php echo htmlspecialchars($data['gambar']); ?>">

                <div class="col-md-6">
                    <label class="galeri-form-label">Judul Foto</label>
                    <input type="text" name="judul_foto" class="form-control galeri-form-input" 
                        value="<?php echo htmlspecialchars($data['judul_foto']); ?>" required
                        v-model="formData.judul_foto"
                        @input="validateField('judul_foto', {required: true, minLength: 5})">
                    <small class="text-danger" v-if="errors.judul_foto">{{ errors.judul_foto }}</small>
                </div>


                <div class="col-md-6">
                    <label class="galeri-form-label">Kategori</label>
                    <input type="text" name="kategori" class="form-control galeri-form-input" value="<?php echo htmlspecialchars($data['kategori']); ?>">
                </div>

                <div class="col-md-6">
                    <label class="galeri-form-label">Terkait Wisata</label>
                    <select name="wisata_id" class="form-select galeri-form-input">
                        <option value="">Galeri Umum</option>
                        <?php foreach ($wisataAktifList as $wisata): ?>
                            <option value="<?php echo (int)$wisata['id']; ?>" <?php echo ((string)$data['wisata_id'] === (string)$wisata['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($wisata['nama_wisata']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="galeri-form-label">Status</label>
                    <select name="status" class="form-select galeri-form-input">
                        <option value="aktif" <?php echo ($data['status'] === 'aktif') ? 'selected' : ''; ?>>Aktif</option>
                        <option value="nonaktif" <?php echo ($data['status'] === 'nonaktif') ? 'selected' : ''; ?>>Nonaktif</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="galeri-form-label">Gambar Saat Ini</label>
                    <?php if (!empty($data['gambar'])): ?>
                        <div class="current-image-box">
                            <img src="../assets/images/galeri/<?php echo htmlspecialchars($data['gambar']); ?>" alt="Gambar Galeri" class="current-image-preview">
                            <div class="current-image-name"><?php echo htmlspecialchars($data['gambar']); ?></div>
                        </div>
                    <?php else: ?>
                        <div class="text-muted">Belum ada gambar.</div>
                    <?php endif; ?>
                </div>

                <div class="col-12 js-crop-group" data-id="galeri" data-aspect-ratio="4/3" data-required="false" data-label="gambar galeri">
                    <input type="hidden" name="cropped_image" :value="groups['galeri']?.croppedData">

                    <label class="galeri-form-label">Pilih Gambar Baru (opsional)</label>
                    <input type="file" class="form-control galeri-form-input" accept=".jpg,.jpeg,.png,.webp,image/*" @change="handleFileChange('galeri', $event)">
                    <small class="text-muted d-block mt-2">Kosongkan jika tidak ingin mengganti gambar. Kalau pilih gambar baru, lakukan crop dulu.</small>

                    <div class="crop-preview-box mt-3" v-if="groups['galeri']?.previewUrl">
                        <div class="crop-preview-label">Preview Hasil Crop Baru</div>
                        <img :src="groups['galeri']?.previewUrl" class="crop-preview-image" alt="Preview hasil crop">
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-galeri-outline" @click="openCropModal('galeri')" :disabled="!groups['galeri']?.hasFile">
                            <i class="fas fa-crop me-2"></i>Crop Gambar Baru
                        </button>
                    </div>
                </div>

                <div class="col-12">
                    <label class="galeri-form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="form-control galeri-form-input" placeholder="Jelaskan foto galeri ini..."><?php echo htmlspecialchars($data['deskripsi']); ?></textarea>
                </div>

                <div class="col-12 d-flex gap-3 flex-wrap">
                    <button type="submit" class="btn btn-galeri-main" :disabled="!isFormValid">
                        <i class="fas fa-save me-2"></i>Update Galeri
                    </button>
                    <a href="galeri.php" class="btn btn-galeri-outline">Batal</a>
                </div>
            </form>
        </div>

    </main>
</div>

<script id="initial-form-data" type="application/json">
    <?php echo json_encode([
        'judul_foto' => $data['judul_foto']
    ]); ?>
</script>


<div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content crop-modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Crop Gambar Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="cropper-container-wrap">
          <img id="imageToCrop" :src="imageToCropSrc" alt="Crop target">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-galeri-outline" data-bs-dismiss="modal">Batal</button>
        <button type="button" id="useCropBtn" class="btn btn-galeri-main" @click="applyCrop">
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