<?php
require_once __DIR__ . '/components/session_check.php';
require_once __DIR__ . '/../controllers/WisataController.php';

$activePage = 'wisata';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';

$wisataController = new WisataController();
$wisataList = $wisataController->getFilteredWisata($search, $status);

$success = isset($_GET['success']) ? $_GET['success'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Wisata — Admin Bukit Fajar Lestari</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin-layout.css">
    <link rel="stylesheet" href="../assets/css/admin-wisata.css">
    
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
</head>
<body class="admin-layout-page">

<div class="admin-layout">
    <?php require_once 'components/sidebar.php'; ?>

    <main class="admin-main" id="wisata-admin-app">
        <div class="admin-page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="admin-page-title">Data Wisata</h1>
                <p class="admin-page-subtitle">Kelola daftar destinasi wisata Bukit Fajar Lestari.</p>
            </div>

            <a href="tambah_wisata.php" class="btn btn-wisata-main">
                <i class="fas fa-plus me-2"></i>Tambah Wisata
            </a>
        </div>

        <?php if ($success === 'added'): ?>
            <div class="alert alert-success">Data wisata berhasil ditambahkan.</div>
        <?php elseif ($success === 'updated'): ?>
            <div class="alert alert-success">Data wisata berhasil diperbarui.</div>
        <?php elseif ($success === 'deleted'): ?>
            <div class="alert alert-success">Data wisata berhasil dihapus.</div>
        <?php endif; ?>

        <div class="wisata-filter-card mb-4">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="wisata-filter-label">Cari Wisata</label>
                    <input
                        type="text"
                        class="form-control wisata-filter-input"
                        placeholder="Cari nama wisata, kategori, atau lokasi"
                        v-model="search"
                    >
                </div>

                <div class="col-md-4">
                    <label class="wisata-filter-label">Filter Status</label>
                    <select class="form-select wisata-filter-input" v-model="status">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="wisata-table-card">
            <div class="table-responsive">
                <table class="table wisata-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nama Wisata</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Jam Buka</th>
                            <th>Weekday</th>
                            <th>Weekend</th>
                            <th>Thumbnail</th>
                            <th>Kuota</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in filteredWisata" :key="item.id">
                            <td>
                                <div class="wisata-name">{{ item.nama_wisata }}</div>
                                <div class="wisata-desc">
                                    {{ truncate(item.deskripsi, 80) }}
                                </div>
                            </td>
                            <td>{{ item.kategori || '-' }}</td>
                            <td>{{ item.lokasi || '-' }}</td>
                            <td>{{ item.jam_buka || '-' }}</td>
                            <td>Rp {{ formatNumber(item.harga_weekday) }}</td>
                            <td>Rp {{ formatNumber(item.harga_weekend) }}</td>
                            <td>
                                <div v-if="item.thumbnail">
                                    <img :src="'../assets/images/galeri/' + item.thumbnail" alt="Thumbnail Wisata" class="wisata-thumb-preview">
                                    <div class="wisata-thumb-text mt-2">{{ item.thumbnail }}</div>
                                </div>
                                <span v-else class="text-muted">Belum ada thumbnail.</span>
                            </td>
                            <td>
                                <strong>{{ item.kuota_harian }}</strong>
                                <small class="text-muted d-block">Tiket/Hari</small>
                            </td>
                            <td>
                                <span :class="'wisata-status wisata-status-' + item.status">
                                    {{ item.status.charAt(0).toUpperCase() + item.status.slice(1) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-2">
                                    <a :href="'edit_wisata.php?id=' + item.id" class="btn btn-sm btn-wisata-edit">
                                        <i class="fas fa-pen-to-square me-1"></i>Edit
                                    </a>

                                    <form action="../controllers/process/delete_wisata_process.php" method="POST" onsubmit="return confirm('Yakin ingin menghapus data wisata ini?');">
                                        <input type="hidden" name="id" :value="item.id">
                                        <button type="submit" class="btn btn-sm btn-wisata-delete w-100">
                                            <i class="fas fa-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="filteredWisata.length === 0">
                            <td colspan="10" class="text-center py-4 text-muted">
                                Data wisata tidak ditemukan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script id="wisata-data" type="application/json">
    <?php echo json_encode($wisataList); ?>
</script>

<script src="../assets/js/admin-live-search.js"></script>

</body>
</html>