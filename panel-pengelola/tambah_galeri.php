<?php
require_once __DIR__ . '/components/session_check.php';
require_once __DIR__ . '/../controllers/WisataController.php';

$activePage = 'galeri';
$error = isset($_GET['error']) ? $_GET['error'] : '';

$wisataController = new WisataController();
$wisataAktifList = $wisataController->getFilteredWisata('', 'aktif');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Galeri — Admin Bukit Fajar Lestari</title>

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
                <h1 class="admin-page-title">Tambah Galeri</h1>
                <p class="admin-page-subtitle">Tambahkan foto galeri wisata baru.</p>
            </div>
            <a href="galeri.php" class="btn btn-galeri-outline">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <?php if ($error === 'empty'): ?>
            <div class="alert alert-warning">Judul foto dan hasil crop gambar wajib diisi.</div>
        <?php elseif ($error === 'failed'): ?>
            <div class="alert alert-danger">Data galeri gagal disimpan. Silakan coba lagi.</div>
        <?php elseif ($error === 'invalid_type'): ?>
            <div class="alert alert-danger">Format file tidak didukung. Gunakan JPG, JPEG, PNG, atau WEBP.</div>
        <?php elseif ($error === 'upload_failed'): ?>
            <div class="alert alert-danger">Proses gambar gagal. Silakan coba lagi.</div>
        <?php elseif ($error === 'too_large'): ?>
            <div class="alert alert-danger">Ukuran gambar terlalu besar. Maksimal 5MB.</div>
        <?php endif; ?>

        <div class="galeri-form-card">

            <form id="galeriForm" action="../controllers/process/tambah_galeri_process.php" method="POST" class="row g-4" @submit="onFormSubmit">
                <div class="col-md-6">
                    <label class="galeri-form-label">Judul Foto</label>
                    <input type="text" name="judul_foto" class="form-control galeri-form-input" 
                        placeholder="Contoh: Panorama Pagi Bukit Fajar" required
                        v-model="formData.judul_foto"
                        @input="validateField('judul_foto', {required: true, minLength: 5})">
                    <small class="text-danger" v-if="errors.judul_foto">{{ errors.judul_foto }}</small>
                </div>


                <div class="col-md-6">
                    <label class="galeri-form-label">Kategori</label>
                    <input type="text" name="kategori" class="form-control galeri-form-input" placeholder="Contoh: Panorama / Sunrise / Camping">
                </div>

                <div class="col-md-6">
                    <label class="galeri-form-label">Terkait Wisata</label>
                    <select name="wisata_id" class="form-select galeri-form-input">
                        <option value="">Galeri Umum</option>
                        <?php foreach ($wisataAktifList as $wisata): ?>
                            <option value="<?php echo (int)$wisata['id']; ?>">
                                <?php echo htmlspecialchars($wisata['nama_wisata']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="galeri-form-label">Status</label>
                    <select name="status" class="form-select galeri-form-input">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>

                <div class="col-12 js-crop-group" data-id="galeri" data-aspect-ratio="4/3" data-required="true" data-label="gambar galeri">
                    <input type="hidden" name="cropped_image" :value="groups['galeri']?.croppedData">

                    <label class="galeri-form-label">Pilih Gambar</label>
                    <input type="file" class="form-control galeri-form-input" accept=".jpg,.jpeg,.png,.webp,image/*" @change="handleFileChange('galeri', $event)" required>
                    <small class="text-muted d-block mt-2">Format: JPG, JPEG, PNG, WEBP. Maksimal 5MB. Setelah pilih gambar, lakukan crop dulu.</small>

                    <div class="crop-preview-box mt-3" v-if="groups['galeri']?.previewUrl">
                        <div class="crop-preview-label">Preview Hasil Crop</div>
                        <img :src="groups['galeri']?.previewUrl" class="crop-preview-image" alt="Preview hasil crop">
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-galeri-outline" @click="openCropModal('galeri')" :disabled="!groups['galeri']?.hasFile">
                            <i class="fas fa-crop me-2"></i>Crop Gambar
                        </button>
                    </div>
                </div>

                <div class="col-12">
                    <label class="galeri-form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="form-control galeri-form-input" placeholder="Jelaskan foto galeri ini..."></textarea>
                </div>

                <div class="col-12 d-flex gap-3 flex-wrap">
                    <button type="submit" class="btn btn-galeri-main" :disabled="!isFormValid">
                        <i class="fas fa-save me-2"></i>Simpan Galeri
                    </button>
                    <a href="galeri.php" class="btn btn-galeri-outline">Batal</a>
                </div>

            </form>
        </div>

    </main>
</div>

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