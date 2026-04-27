<?php
require_once __DIR__ . '/components/session_check.php';
require_once __DIR__ . '/../controllers/BookingController.php';

$activePage = 'dashboard';

$bookingController = new BookingController();
$dashboardStats = $bookingController->getDashboardStats();

$totalBooking   = $dashboardStats['totalBooking'];
$totalPending   = $dashboardStats['totalPending'];
$totalConfirmed = $dashboardStats['totalConfirmed'];
$totalCancelled = $dashboardStats['totalCancelled'];

$adminNama = isset($_SESSION['admin_nama']) ? $_SESSION['admin_nama'] : 'Admin';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin — Bukit Fajar Lestari</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin-layout.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body class="admin-layout-page admin-dashboard-page">

<div class="admin-layout">
    <?php require_once 'components/sidebar.php'; ?>

    <main class="admin-main">
        <div class="admin-page-header">
            <h1 class="admin-page-title">Dashboard Admin</h1>
            <p class="admin-page-subtitle">
                Selamat datang, <strong><?php echo htmlspecialchars($adminNama); ?></strong>
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-xl-3">
                <div class="dashboard-card total-card">
                    <div class="card-icon"><i class="fas fa-ticket-alt"></i></div>
                    <div class="card-info">
                        <div class="card-label">Total Booking</div>
                        <div class="card-value"><?php echo $totalBooking; ?></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="dashboard-card pending-card">
                    <div class="card-icon"><i class="fas fa-clock"></i></div>
                    <div class="card-info">
                        <div class="card-label">Pending</div>
                        <div class="card-value"><?php echo $totalPending; ?></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="dashboard-card confirmed-card">
                    <div class="card-icon"><i class="fas fa-circle-check"></i></div>
                    <div class="card-info">
                        <div class="card-label">Confirmed</div>
                        <div class="card-value"><?php echo $totalConfirmed; ?></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="dashboard-card cancelled-card">
                    <div class="card-icon"><i class="fas fa-circle-xmark"></i></div>
                    <div class="card-info">
                        <div class="card-label">Cancelled</div>
                        <div class="card-value"><?php echo $totalCancelled; ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-panel mt-5">
            <h5 class="mb-3">Aksi Cepat</h5>
            <div class="d-flex flex-wrap gap-3">
                <a href="booking.php" class="btn btn-dashboard-action">
                    <i class="fas fa-table me-2"></i>Lihat Data Booking
                </a>
                <a href="../index.php" class="btn btn-dashboard-outline">
                    <i class="fas fa-globe me-2"></i>Lihat Website
                </a>
            </div>
        </div>
    </main>
</div>

</body>
</html>