<?php
require_once __DIR__ . '/components/session_check.php';
require_once __DIR__ . '/../controllers/GaleriController.php';

$activePage = 'galeri';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';

$galeriController = new GaleriController();
$galeriList = $galeriController->getFilteredGaleri($search, $status);

$success = isset($_GET['success']) ? $_GET['success'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Galeri — Admin Bukit Fajar Lestari</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin-layout.css">
    <link rel="stylesheet" href="../assets/css/admin-galeri.css">
    
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
</head>
<body class="admin-layout-page">

<div class="admin-layout">
    <?php require_once 'components/sidebar.php'; ?>

    <main class="admin-main" id="galeri-admin-app">
        <div class="admin-page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="admin-page-title">Data Galeri</h1>
                <p class="admin-page-subtitle">Kelola foto galeri wisata Bukit Fajar Lestari.</p>
            </div>

            <a href="tambah_galeri.php" class="btn btn-galeri-main">
                <i class="fas fa-plus me-2"></i>Tambah Galeri
            </a>
        </div>

        <?php if ($success === 'added'): ?>
            <div class="alert alert-success">Data galeri berhasil ditambahkan.</div>
        <?php elseif ($success === 'updated'): ?>
            <div class="alert alert-success">Data galeri berhasil diperbarui.</div>
        <?php elseif ($success === 'deleted'): ?>
            <div class="alert alert-success">Data galeri berhasil dihapus.</div>
        <?php endif; ?>

        <div class="galeri-filter-card mb-4">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="galeri-filter-label">Cari Galeri</label>
                    <input
                        type="text"
                        class="form-control galeri-filter-input"
                        placeholder="Cari judul foto, kategori, atau wisata"
                        v-model="search"
                    >
                </div>

                <div class="col-md-4">
                    <label class="galeri-filter-label">Filter Status</label>
                    <select class="form-select galeri-filter-input" v-model="status">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="galeri-table-card">
            <div class="table-responsive">
                <table class="table galeri-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Judul Foto</th>
                            <th>Wisata</th>
                            <th>Kategori</th>
                            <th>Preview</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in filteredGaleri" :key="item.id">
                            <td>
                                <div class="galeri-title">{{ item.judul_foto }}</div>
                                <div class="galeri-desc">
                                    {{ truncate(item.deskripsi, 70) }}
                                </div>
                            </td>
                            <td>{{ item.nama_wisata || 'Galeri Umum' }}</td>
                            <td>{{ item.kategori || '-' }}</td>
                            <td>
                                <div v-if="item.gambar">
                                    <img :src="'../assets/images/galeri/' + item.gambar" alt="Galeri" class="galeri-thumb-preview">
                                    <div class="galeri-file mt-2">{{ item.gambar }}</div>
                                </div>
                                <span v-else class="text-muted">-</span>
                            </td>
                            <td>
                                <span :class="'galeri-status galeri-status-' + item.status">
                                    {{ item.status.charAt(0).toUpperCase() + item.status.slice(1) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-2">
                                    <a :href="'edit_galeri.php?id=' + item.id" class="btn btn-sm btn-galeri-edit">
                                        <i class="fas fa-pen-to-square me-1"></i>Edit
                                    </a>

                                    <form action="../controllers/process/delete_galeri_process.php" method="POST" onsubmit="return confirm('Yakin ingin menghapus data galeri ini?');">
                                        <input type="hidden" name="id" :value="item.id">
                                        <button type="submit" class="btn btn-sm btn-galeri-delete w-100">
                                            <i class="fas fa-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="filteredGaleri.length === 0">
                            <td colspan="6" class="text-center py-4 text-muted">
                                Data galeri tidak ditemukan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script id="galeri-data" type="application/json">
    <?php echo json_encode($galeriList); ?>
</script>

<script src="../assets/js/admin-live-search.js"></script>

</body>
</html>