<?php
require_once '../config/koneksi.php';
require_once '../controllers/AuthController.php';

$authController = new AuthController();
$authController->requireLogin('login.php?error=unauthorized');

$activePage = 'ulasan';

// Ambil data ulasan
$query = "SELECT * FROM ulasan ORDER BY 
          FIELD(status, 'pending', 'approved', 'rejected', 'unverified'),
          tanggal DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Ulasan - Admin Bukit Fajar Lestari</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin-layout.css">
    <style>
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .status-pending { background-color: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
        .status-approved { background-color: #d1fae5; color: #059669; border: 1px solid #a7f3d0; }
        .status-rejected { background-color: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
        .status-unverified { background-color: #f3f4f6; color: #6b7280; border: 1px solid #e5e7eb; }
        
        .rating-stars { color: #fbbf24; }
        .action-buttons { display: flex; gap: 5px; }
        .btn-approve { background: #10b981; color: white; border: none; padding: 6px 12px; border-radius: 5px; cursor: pointer; text-decoration: none; font-size: 0.85rem; }
        .btn-reject { background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 5px; cursor: pointer; text-decoration: none; font-size: 0.85rem; }
        .btn-delete { background: #b91c1c; color: white; border: none; padding: 6px 12px; border-radius: 5px; cursor: pointer; text-decoration: none; font-size: 0.85rem; }
    </style>
</head>
<body class="admin-layout-page">

    <div class="admin-layout">
        <?php include 'components/sidebar.php'; ?>

        <main class="admin-main">
            <div class="admin-page-header">
                <h1 class="admin-page-title">Kelola Data Ulasan</h1>
                <p class="admin-page-subtitle">Kelola ulasan dari pengunjung yang masuk.</p>
            </div>

            <div class="admin-content">
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">
                        <?php 
                        if ($_GET['success'] == 'approved') echo "Ulasan berhasil disetujui.";
                        elseif ($_GET['success'] == 'rejected') echo "Ulasan ditolak.";
                        elseif ($_GET['success'] == 'deleted') echo "Ulasan berhasil dihapus permanen.";
                        ?>
                    </div>
                <?php endif; ?>

                <div class="table-card">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Pengulas</th>
                                <th>Rating</th>
                                <th>Komentar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result && $result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo date('d M Y H:i', strtotime($row['tanggal'])); ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($row['nama']); ?></strong><br>
                                            <small><?php echo htmlspecialchars($row['email']); ?></small>
                                        </td>
                                        <td>
                                            <div class="rating-stars">
                                                <?php for($i=1; $i<=5; $i++): ?>
                                                    <i class="fas fa-star" style="color: <?php echo $i <= $row['rating'] ? '#fbbf24' : '#e5e7eb'; ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                        </td>
                                        <td><?php echo nl2br(htmlspecialchars($row['komentar'])); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo $row['status']; ?>">
                                                <?php 
                                                if ($row['status'] == 'unverified') echo '<i class="fas fa-envelope"></i> Belum Verifikasi';
                                                elseif ($row['status'] == 'pending') echo '<i class="fas fa-clock"></i> Menunggu';
                                                elseif ($row['status'] == 'approved') echo '<i class="fas fa-check"></i> Disetujui';
                                                elseif ($row['status'] == 'rejected') echo '<i class="fas fa-times"></i> Ditolak';
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <?php if ($row['status'] == 'pending'): ?>
                                                    <a href="../controllers/process/update_ulasan_status.php?id=<?php echo $row['id']; ?>&action=approve" class="btn-approve"><i class="fas fa-check"></i> Terima</a>
                                                    <a href="../controllers/process/update_ulasan_status.php?id=<?php echo $row['id']; ?>&action=reject" class="btn-reject"><i class="fas fa-times"></i> Tolak</a>
                                                <?php elseif ($row['status'] == 'unverified'): ?>
                                                    <small class="text-muted" style="margin-top: 5px; margin-right: 5px;">Tunggu Verifikasi</small>
                                                <?php elseif ($row['status'] == 'approved'): ?>
                                                    <a href="../controllers/process/update_ulasan_status.php?id=<?php echo $row['id']; ?>&action=reject" class="btn-reject"><i class="fas fa-times"></i> Cabut</a>
                                                <?php elseif ($row['status'] == 'rejected'): ?>
                                                    <a href="../controllers/process/update_ulasan_status.php?id=<?php echo $row['id']; ?>&action=approve" class="btn-approve"><i class="fas fa-check"></i> Pulihkan</a>
                                                <?php endif; ?>
                                                <a href="../controllers/process/hapus_ulasan.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus ulasan ini secara permanen?')"><i class="fas fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada ulasan masuk.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
