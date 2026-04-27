<?php
require_once __DIR__ . '/components/session_check.php';
require_once __DIR__ . '/../controllers/WisataController.php';

$activePage = 'wisata';
$error = isset($_GET['error']) ? $_GET['error'] : '';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: wisata.php");
    exit();
}

$wisataController = new WisataController();
$data = $wisataController->getWisataById($id);

if (!$data) {
    header("Location: wisata.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Wisata — Admin Bukit Fajar Lestari</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin-layout.css">
    <link rel="stylesheet" href="../assets/css/admin-wisata.css">
</head>
<body class="admin-layout-page" id="crop-app">


<div class="admin-layout">


    <?php require_once 'components/sidebar.php'; ?>

    <main class="admin-main">


        <div class="admin-page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="admin-page-title">Edit Wisata</h1>
                <p class="admin-page-subtitle">Perbarui data destinasi wisata.</p>
            </div>
            <a href="wisata.php" class="btn btn-wisata-outline">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <?php if ($error === 'empty'): ?>
            <div class="alert alert-warning">Nama wisata, deskripsi, harga weekday, dan harga weekend wajib diisi.</div>
        <?php elseif ($error === 'failed'): ?>
            <div class="alert alert-danger">Data wisata gagal diperbarui. Silakan coba lagi.</div>
        <?php elseif ($error === 'invalid_type'): ?>
            <div class="alert alert-danger">Format file tidak didukung. Gunakan JPG, JPEG, PNG, atau WEBP.</div>
        <?php elseif ($error === 'upload_failed'): ?>
            <div class="alert alert-danger">Proses thumbnail gagal. Silakan coba lagi.</div>
        <?php elseif ($error === 'too_large'): ?>
            <div class="alert alert-danger">Ukuran thumbnail terlalu besar. Maksimal 5MB.</div>
        <?php endif; ?>

        <div class="wisata-form-card">

            <form action="../controllers/process/update_wisata_process.php" method="POST" class="row g-4" @submit="onFormSubmit">
                <input type="hidden" name="id" value="<?php echo (int) $data['id']; ?>">
                <input type="hidden" name="thumbnail_lama" value="<?php echo htmlspecialchars($data['thumbnail']); ?>">

                <div class="col-md-6">
                    <label class="wisata-form-label">Nama Wisata</label>
                    <input type="text" name="nama_wisata" class="form-control wisata-form-input" 
                        value="<?php echo htmlspecialchars($data['nama_wisata']); ?>" required
                        v-model="formData.nama_wisata"
                        @input="validateField('nama_wisata', {required: true, minLength: 5})">
                    <small class="text-danger" v-if="errors.nama_wisata">{{ errors.nama_wisata }}</small>
                </div>

                <div class="col-md-6">
                    <label class="wisata-form-label">Kategori</label>
                    <input type="text" name="kategori" class="form-control wisata-form-input" value="<?php echo htmlspecialchars($data['kategori']); ?>">
                </div>

                <div class="col-md-6">
                    <label class="wisata-form-label">Lokasi</label>
                    <input type="text" name="lokasi" class="form-control wisata-form-input" value="<?php echo htmlspecialchars($data['lokasi']); ?>">
                </div>

                <div class="col-md-6">
                    <label class="wisata-form-label">Jam Buka</label>
                    <input type="text" name="jam_buka" class="form-control wisata-form-input" value="<?php echo htmlspecialchars($data['jam_buka']); ?>">
                </div>

                <div class="col-md-6">
                    <label class="wisata-form-label">Harga Weekday</label>
                    <input type="number" name="harga_weekday" class="form-control wisata-form-input" 
                        value="<?php echo (int) $data['harga_weekday']; ?>" min="0" required
                        v-model="formData.harga_weekday"
                        @input="validateField('harga_weekday', {required: true, minNum: 0})">
                    <small class="text-danger" v-if="errors.harga_weekday">{{ errors.harga_weekday }}</small>
                </div>

                <div class="col-md-6">
                    <label class="wisata-form-label">Harga Weekend</label>
                    <input type="number" name="harga_weekend" class="form-control wisata-form-input" 
                        value="<?php echo (int) $data['harga_weekend']; ?>" min="0" required
                        v-model="formData.harga_weekend"
                        @input="validateField('harga_weekend', {required: true, minNum: 0})">
                    <small class="text-danger" v-if="errors.harga_weekend">{{ errors.harga_weekend }}</small>
                </div>

                <div class="col-md-6">
                    <label class="wisata-form-label">Status</label>
                    <select name="status" class="form-select wisata-form-input">
                        <option value="aktif" <?php echo ($data['status'] === 'aktif') ? 'selected' : ''; ?>>Aktif</option>
                        <option value="nonaktif" <?php echo ($data['status'] === 'nonaktif') ? 'selected' : ''; ?>>Nonaktif</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="wisata-form-label">Kuota Harian (Tiket)</label>
                    <input type="number" name="kuota_harian" class="form-control wisata-form-input" 
                        value="<?php echo (int)$data['kuota_harian']; ?>" min="0" required
                        v-model="formData.kuota_harian"
                        @input="validateField('kuota_harian', {required: true, minNum: 0})">
                    <small class="text-danger" v-if="errors.kuota_harian">{{ errors.kuota_harian }}</small>
                    <small class="text-muted">Set 0 untuk tidak terbatas.</small>
                </div>

                <div class="col-12">
                    <label class="wisata-form-label">Thumbnail Saat Ini</label>
                    <?php if (!empty($data['thumbnail'])): ?>
                        <div class="current-image-box">
                            <img src="../assets/images/galeri/<?php echo htmlspecialchars($data['thumbnail']); ?>" alt="Thumbnail Wisata" class="current-image-preview">
                            <div class="current-image-name"><?php echo htmlspecialchars($data['thumbnail']); ?></div>
                        </div>
                    <?php else: ?>
                        <div class="text-muted">Belum ada thumbnail.</div>
                    <?php endif; ?>
                </div>

                <div class="col-12 js-crop-group" data-id="thumbnail" data-aspect-ratio="4/3" data-required="false" data-label="thumbnail wisata">
                    <input type="hidden" name="cropped_image" :value="groups['thumbnail']?.croppedData">

                    <label class="wisata-form-label">Pilih Thumbnail Baru (opsional)</label>
                    <input type="file" class="form-control wisata-form-input" accept=".jpg,.jpeg,.png,.webp,image/*" @change="handleFileChange('thumbnail', $event)">
                    <small class="text-muted d-block mt-2">Kosongkan jika tidak ingin mengganti thumbnail. Kalau pilih gambar baru, lakukan crop dulu.</small>

                    <div class="crop-preview-box mt-3" v-if="groups['thumbnail']?.previewUrl">
                        <div class="crop-preview-label">Preview Hasil Crop Baru</div>
                        <img :src="groups['thumbnail']?.previewUrl" class="crop-preview-image" alt="Preview hasil crop">
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-wisata-outline" @click="openCropModal('thumbnail')" :disabled="!groups['thumbnail']?.hasFile">
                            <i class="fas fa-crop me-2"></i>Crop Thumbnail Baru
                        </button>
                    </div>
                </div>

                <div class="col-12">
                    <label class="wisata-form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="form-control wisata-form-input" required><?php echo htmlspecialchars($data['deskripsi']); ?></textarea>
                </div>

                <div class="col-12">
                    <label class="wisata-form-label">Fasilitas</label>
                    <textarea name="fasilitas" rows="3" class="form-control wisata-form-input"><?php echo htmlspecialchars($data['fasilitas']); ?></textarea>
                </div>

                <div class="col-12 d-flex gap-3 flex-wrap">
                    <button type="submit" class="btn btn-wisata-main" :disabled="!isFormValid">
                        <i class="fas fa-save me-2"></i>Update Wisata
                    </button>
                    <a href="wisata.php" class="btn btn-wisata-outline">Batal</a>
                </div>
            </form>
        </div>

    </main>
</div>

<script id="initial-form-data" type="application/json">
    <?php echo json_encode([
        'nama_wisata' => $data['nama_wisata'],
        'harga_weekday' => $data['harga_weekday'],
        'harga_weekend' => $data['harga_weekend'],
        'kuota_harian' => $data['kuota_harian']
    ]); ?>
</script>


<div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content crop-modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Crop Thumbnail Wisata</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="cropper-container-wrap">
          <img id="imageToCrop" :src="imageToCropSrc" alt="Crop target">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-wisata-outline" data-bs-dismiss="modal">Batal</button>
        <button type="button" id="useCropBtn" class="btn btn-wisata-main" @click="applyCrop">
          <i class="fas fa-check me-2"></i>Gunakan Hasil Crop
        </button>
      </div>

    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/3.3.4/vue.global.prod.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script src="../assets/js/crop-vue.js"></script>

</body>
</html>