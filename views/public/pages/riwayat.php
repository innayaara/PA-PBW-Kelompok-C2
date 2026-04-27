<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pemesanan — Bukit Fajar Lestari</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/riwayat.css">
</head>
<body class="riwayat-page">

    <div class="container riwayat-wrap">
        <a href="../index.php" class="riwayat-back-link">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
        </a>

        <div class="riwayat-search-card">
            <div class="riwayat-card-header">
                <h1>Riwayat Pemesanan</h1>
                <p>Cek kembali detail booking dan buka ulang e-ticket Anda.</p>
            </div>

            <div class="riwayat-card-body">
                <?php if ($error === 'empty'): ?>
                    <div class="riwayat-alert riwayat-alert-error">
                        Kode booking wajib diisi.
                    </div>
                <?php elseif ($error === 'notfound'): ?>
                    <div class="riwayat-alert riwayat-alert-error">
                        Data pemesanan tidak ditemukan. Pastikan kode booking yang Anda masukkan benar.
                    </div>
                <?php endif; ?>

                <form action="../controllers/process/riwayat_process.php" method="POST" class="row g-3">
                    <div class="col-md-7">
                        <div class="riwayat-label">Kode Booking</div>
                        <input
                            type="text"
                            name="kode_booking"
                            class="riwayat-input"
                            placeholder="Contoh: BFL-ABC123"
                            value="<?php echo $kodeInput; ?>"
                            required
                        >
                    </div>

                    <div class="col-md-5">
                        <div class="riwayat-label">No. WhatsApp (opsional)</div>
                        <input
                            type="text"
                            name="whatsapp"
                            class="riwayat-input"
                            placeholder="+62 812 xxxx xxxx"
                        >
                    </div>

                    <div class="col-12">
                        <button type="submit" class="riwayat-btn-main">
                            <i class="fas fa-magnifying-glass me-2"></i>Cari Pemesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($data): ?>
            <div class="riwayat-result-card">
                <div class="riwayat-card-header">
                    <h2>Detail Pemesanan</h2>
                    <p>Berikut data booking yang berhasil ditemukan.</p>
                </div>

                <div class="riwayat-card-body">
                    <div class="riwayat-booking-code">
                        <span>KODE BOOKING</span>
                        <strong><?php echo htmlspecialchars($data['kode_booking']); ?></strong>
                    </div>

                    <div class="riwayat-detail-grid">
                        <div class="riwayat-detail-item">
                            <div class="riwayat-detail-label">Nama Lengkap</div>
                            <div class="riwayat-detail-value"><?php echo htmlspecialchars($data['nama_lengkap']); ?></div>
                        </div>

                        <div class="riwayat-detail-item">
                            <div class="riwayat-detail-label">WhatsApp</div>
                            <div class="riwayat-detail-value"><?php echo htmlspecialchars($data['whatsapp']); ?></div>
                        </div>

                        <div class="riwayat-detail-item">
                            <div class="riwayat-detail-label">Tanggal Kunjungan</div>
                            <div class="riwayat-detail-value"><?php echo date('d M Y', strtotime($data['tanggal_kunjungan'])); ?></div>
                        </div>

                        <div class="riwayat-detail-item">
                            <div class="riwayat-detail-label">Jumlah Pengunjung</div>
                            <div class="riwayat-detail-value"><?php echo (int) $data['jumlah_pengunjung']; ?> Orang</div>
                        </div>

                        <div class="riwayat-detail-item">
                            <div class="riwayat-detail-label">Destinasi</div>
                            <div class="riwayat-detail-value"><?php echo htmlspecialchars($data['destinasi']); ?></div>
                        </div>

                        <div class="riwayat-detail-item">
                            <div class="riwayat-detail-label">Jenis Hari</div>
                            <div class="riwayat-detail-value"><?php echo ucfirst(htmlspecialchars($data['jenis_hari'])); ?></div>
                        </div>

                        <div class="riwayat-detail-item">
                            <div class="riwayat-detail-label">Total Harga</div>
                            <div class="riwayat-detail-value">Rp <?php echo number_format($data['total_harga'], 0, ',', '.'); ?></div>
                        </div>

                        <div class="riwayat-detail-item">
                            <div class="riwayat-detail-label">Status</div>
                            <div class="riwayat-detail-value">
                                <span class="riwayat-status riwayat-status-<?php echo htmlspecialchars($data['status']); ?>">
                                    <?php echo htmlspecialchars($formattedStatus); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($data['catatan'])): ?>
                        <div class="riwayat-note-box">
                            <div class="riwayat-detail-label">Catatan</div>
                            <div class="riwayat-note-text">
                                <?php echo nl2br(htmlspecialchars($data['catatan'])); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="riwayat-action-btns">
                        <a href="eticket.php?kode=<?php echo urlencode($data['kode_booking']); ?>&from=riwayat" class="riwayat-btn-ticket">
                            <i class="fas fa-ticket-alt me-2"></i>Lihat E-Ticket
                        </a>

                        <a href="javascript:window.print()" class="riwayat-btn-outline">
                            <i class="fas fa-print me-2"></i>Cetak Halaman
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>