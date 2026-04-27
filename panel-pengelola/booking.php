<?php
require_once __DIR__ . '/components/session_check.php';
require_once __DIR__ . '/../controllers/BookingController.php';

$activePage = 'booking';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';

$bookingController = new BookingController();
$bookingList = $bookingController->getFilteredBookings($search, $status);

$success = isset($_GET['success']) ? $_GET['success'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Booking — Admin Bukit Fajar Lestari</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin-layout.css">
    <link rel="stylesheet" href="../assets/css/admin-booking.css">
    
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
</head>
<body class="admin-layout-page">

<div class="admin-layout">
    <?php require_once 'components/sidebar.php'; ?>

    <main class="admin-main" id="booking-admin-app">
        <div class="admin-page-header">
            <h1 class="admin-page-title">Data Booking</h1>
            <p class="admin-page-subtitle">Kelola data pemesanan tiket pengunjung.</p>
        </div>

        <?php if ($success === 'updated'): ?>
            <div class="alert alert-success">Status booking berhasil diperbarui.</div>
        <?php elseif ($success === 'deleted'): ?>
            <div class="alert alert-success">Data booking berhasil dihapus.</div>
        <?php endif; ?>

        <div class="filter-card mb-4">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="filter-label">Cari Booking</label>
                    <input
                        type="text"
                        class="form-control filter-input"
                        placeholder="Cari kode booking, nama, atau WhatsApp"
                        v-model="search"
                    >
                </div>

                <div class="col-md-4">
                    <label class="filter-label">Filter Status</label>
                    <select class="form-select filter-input" v-model="status">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="table-card">
            <div class="table-responsive">
                <table class="table booking-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>WhatsApp</th>
                            <th>Tanggal</th>
                            <th>Destinasi</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th style="min-width: 220px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in filteredBookings" :key="item.id">
                            <td><strong>{{ item.kode_booking }}</strong></td>
                            <td>{{ item.nama_lengkap }}</td>
                            <td>{{ item.whatsapp }}</td>
                            <td>{{ formatDate(item.tanggal_kunjungan) }}</td>
                            <td>{{ item.destinasi }}</td>
                            <td>{{ item.jumlah_pengunjung }}</td>
                            <td>Rp {{ formatNumber(item.total_harga) }}</td>
                            <td>
                                <span :class="'status-badge status-' + item.status">
                                    {{ item.status.charAt(0).toUpperCase() + item.status.slice(1) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-2">
                                    <a :href="'../pages/eticket.php?kode=' + encodeURIComponent(item.kode_booking) + '&from=admin-booking'" class="btn btn-sm btn-admin-ticket">
                                        <i class="fas fa-ticket-alt me-1"></i>Lihat E-Ticket
                                    </a>

                                    <form action="../controllers/process/update_booking_status.php" method="POST" class="d-flex gap-2">
                                        <input type="hidden" name="id" :value="item.id">

                                        <select name="status" class="form-select form-select-sm" :value="item.status">
                                            <option value="pending">Pending</option>
                                            <option value="confirmed">Confirmed</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>

                                        <button type="submit" class="btn btn-sm btn-admin-main">
                                            Update
                                        </button>
                                    </form>

                                    <form action="../controllers/process/delete_booking_process.php" method="POST" onsubmit="return confirm('Yakin ingin menghapus booking ini?');">
                                        <input type="hidden" name="id" :value="item.id">

                                        <button type="submit" class="btn btn-sm btn-admin-delete w-100">
                                            <i class="fas fa-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="filteredBookings.length === 0">
                            <td colspan="9" class="text-center py-4 text-muted">
                                Data booking tidak ditemukan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script id="booking-data" type="application/json">
    <?php echo json_encode($bookingList); ?>
</script>

<script src="../assets/js/admin-live-search.js"></script>

</body>
</html>