<?php
if (!isset($activePage)) {
    $activePage = '';
}
?>

<div class="admin-sidebar">
    <div class="admin-sidebar-brand">
        <h2>Bukit <span>Fajar</span> Lestari</h2>
        <p>Panel Admin</p>
    </div>

    <div class="admin-sidebar-menu">
        <div class="sidebar-label">Menu Utama</div>
        <a href="index.php" class="admin-sidebar-link <?php echo ($activePage === 'dashboard') ? 'active' : ''; ?>">
            <i class="fas fa-chart-pie"></i>
            <span>Dashboard</span>
        </a>

        <a href="booking.php" class="admin-sidebar-link <?php echo ($activePage === 'booking') ? 'active' : ''; ?>">
            <i class="fas fa-ticket-alt"></i>
            <span>Data Booking</span>
        </a>

        <div class="sidebar-label">Kelola Konten</div>
        <a href="wisata.php" class="admin-sidebar-link <?php echo ($activePage === 'wisata') ? 'active' : ''; ?>">
            <i class="fas fa-mountain-sun"></i>
            <span>Data Wisata</span>
        </a>

        <a href="galeri.php" class="admin-sidebar-link <?php echo ($activePage === 'galeri') ? 'active' : ''; ?>">
            <i class="fas fa-image"></i>
            <span>Data Galeri</span>
        </a>

        <a href="hari_libur.php" class="admin-sidebar-link <?php echo ($activePage === 'hari_libur') ? 'active' : ''; ?>">
            <i class="fas fa-calendar-times"></i>
            <span>Kelola Hari Libur</span>
        </a>

        <a href="ulasan.php" class="admin-sidebar-link <?php echo ($activePage === 'ulasan') ? 'active' : ''; ?>">
            <i class="fas fa-star"></i>
            <span>Data Ulasan</span>
        </a>

        <div class="sidebar-label">Sistem</div>
        <a href="pengaturan_tampilan.php" class="admin-sidebar-link <?php echo ($activePage === 'pengaturan_tampilan') ? 'active' : ''; ?>">
            <i class="fas fa-sliders"></i>
            <span>Pengaturan Tampilan</span>
        </a>

        <a href="../index.php" class="admin-sidebar-link">
            <i class="fas fa-globe"></i>
            <span>Lihat Website</span>
        </a>
    </div>

    <div class="admin-sidebar-footer">
        <a href="logout.php" class="admin-sidebar-link logout-link">
            <i class="fas fa-right-from-bracket"></i>
            <span>Logout</span>
        </a>
    </div>
</div>