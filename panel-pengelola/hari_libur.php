<?php
require_once __DIR__ . '/components/session_check.php';
require_once __DIR__ . '/../controllers/LiburController.php';
require_once __DIR__ . '/../controllers/WisataController.php';

$activePage = 'hari_libur';

$liburController = new LiburController();
$wisataController = new WisataController();

$listLibur = $liburController->index();
$listWisata = $wisataController->getFilteredWisata('', 'aktif');

$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Hari Libur — Admin Bukit Fajar Lestari</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin-layout.css">
    <style>
        .libur-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 24px;
            margin-bottom: 24px;
        }
        .table-libur th { font-weight: 600; color: var(--forest); }
        .badge-libur {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
</head>
<body class="admin-layout-page">

<div class="admin-layout">
    <?php require_once 'components/sidebar.php'; ?>

    <main class="admin-main">
        <div class="admin-page-header">
            <h1 class="admin-page-title">Kelola Hari Libur / Tutup</h1>
            <p class="admin-page-subtitle">Atur tanggal di mana destinasi wisata tertentu ditutup untuk kunjungan.</p>
        </div>

        <?php if ($success === 'added'): ?>
            <div class="alert alert-success">Jadwal libur berhasil ditambahkan.</div>
        <?php elseif ($success === 'deleted'): ?>
            <div class="alert alert-success">Jadwal libur berhasil dihapus.</div>
        <?php elseif ($error === 'failed'): ?>
            <div class="alert alert-danger">Gagal memproses permintaan.</div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-4">
                <div class="libur-card">
                    <h5 class="mb-4">Tambah Jadwal Libur</h5>
                    <form action="../controllers/process/tambah_libur_process.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Destinasi Wisata</label>
                            <select name="destinasi" class="form-select" required>
                                <option value="" disabled selected>Pilih Wisata</option>
                                <?php foreach ($listWisata as $w): ?>
                                    <option value="<?php echo htmlspecialchars($w['nama_wisata']); ?>">
                                        <?php echo htmlspecialchars($w['nama_wisata']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Keterangan (Opsional)</label>
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Contoh: Perbaikan fasilitas"></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 py-2">
                            <i class="fas fa-calendar-plus me-2"></i>Tandai Libur
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="libur-card">
                    <h5 class="mb-4">Daftar Tanggal Libur</h5>
                    <div class="table-responsive">
                        <table class="table table-libur align-middle">
                            <thead>
                                <tr>
                                    <th>Destinasi</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($listLibur)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Belum ada jadwal libur.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($listLibur as $l): ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($l['destinasi']); ?></strong></td>
                                            <td><?php echo date('d M Y', strtotime($l['tanggal'])); ?></td>
                                            <td><small class="text-muted"><?php echo htmlspecialchars($l['keterangan'] ?: '-'); ?></small></td>
                                            <td>
                                                <form action="../controllers/process/hapus_libur_process.php" method="POST" onsubmit="return confirm('Hapus jadwal libur ini?');">
                                                    <input type="hidden" name="id" value="<?php echo $l['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

</body>
</html>
