<?php
if (!isset($data)) die("Data booking tidak tersedia.");

$verifyUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/pages/riwayat.php?kode=' . urlencode($data['kode_booking']);
$qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=' . urlencode($verifyUrl) . '&color=1a3a26&bgcolor=f0f7f2';
$tanggal = date('d F Y', strtotime($data['tanggal_kunjungan']));
$hari = ['Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
$hariStr = $hari[date('l', strtotime($data['tanggal_kunjungan']))] ?? '';

$statusClass = 'pending'; $statusIcon = 'fa-clock'; $statusText = 'Menunggu Konfirmasi';
if ($data['status'] === 'confirmed') { $statusClass = 'confirmed'; $statusIcon = 'fa-circle-check'; $statusText = 'Tiket Valid'; }
elseif ($data['status'] === 'cancelled') { $statusClass = 'cancelled'; $statusIcon = 'fa-circle-xmark'; $statusText = 'Dibatalkan'; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-Ticket <?php echo htmlspecialchars($data['kode_booking']); ?> — Bukit Fajar Lestari</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'DM Sans', sans-serif;
      background: #091510;
      min-height: 100vh;
      padding: 30px 16px 60px;
      background-image: radial-gradient(ellipse at 20% 50%, rgba(26,58,38,0.4) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 20%, rgba(20,45,30,0.3) 0%, transparent 50%);
    }

    /* ─── NAV ─── */
    .nav-bar {
      max-width: 560px; margin: 0 auto 24px;
      display: flex; justify-content: space-between; align-items: center;
    }
    .nav-bar a {
      color: rgba(255,255,255,0.55); text-decoration: none;
      font-size: 0.82rem; display: flex; align-items: center; gap: 7px;
      transition: color .2s;
    }
    .nav-bar a:hover { color: #e8a83a; }

    /* ─── TICKET WRAPPER ─── */
    .ticket {
      max-width: 560px; margin: 0 auto;
      background: #fff; border-radius: 22px;
      overflow: hidden;
      box-shadow: 0 40px 100px rgba(0,0,0,.7), 0 0 0 1px rgba(255,255,255,.05);
    }

    /* ─── HEADER ─── */
    .t-header {
      background: linear-gradient(160deg, #1b3d28 0%, #0d2318 100%);
      padding: 30px 32px 26px;
      position: relative; overflow: hidden;
    }
    .t-header::before {
      content: '';
      position: absolute; top: -40px; right: -40px;
      width: 180px; height: 180px;
      background: radial-gradient(circle, rgba(232,168,58,.12) 0%, transparent 70%);
    }
    .t-header-top {
      display: flex; justify-content: space-between; align-items: flex-start;
    }
    .t-brand { }
    .t-brand-eyebrow {
      font-size: 0.6rem; letter-spacing: 2.5px; text-transform: uppercase;
      color: rgba(255,255,255,.4); margin-bottom: 6px;
    }
    .t-brand-name {
      font-family: 'Playfair Display', serif;
      font-size: 1.5rem; color: #fff; line-height: 1;
    }
    .t-brand-name span { color: #e8a83a; }
    .t-brand-sub { font-size: 0.7rem; color: rgba(255,255,255,.35); margin-top: 4px; }

    .status-pill {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 6px 14px; border-radius: 30px; font-size: 0.72rem; font-weight: 600;
    }
    .status-pill.pending  { background: rgba(255,193,7,.12); color: #ffd54f; border: 1px solid rgba(255,193,7,.25); }
    .status-pill.confirmed{ background: rgba(52,211,90,.12);  color: #6ee7a0; border: 1px solid rgba(52,211,90,.25); }
    .status-pill.cancelled{ background: rgba(239,68,68,.12);  color: #fca5a5; border: 1px solid rgba(239,68,68,.25); }

    .t-header-dest {
      margin-top: 20px;
      background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.08);
      border-radius: 10px; padding: 12px 16px;
      display: flex; align-items: center; gap: 12px;
    }
    .t-dest-icon {
      width: 38px; height: 38px; border-radius: 8px;
      background: rgba(232,168,58,.15); border: 1px solid rgba(232,168,58,.2);
      display: flex; align-items: center; justify-content: center;
      color: #e8a83a; font-size: 1rem; flex-shrink: 0;
    }
    .t-dest-label { font-size: 0.62rem; color: rgba(255,255,255,.4); letter-spacing: 1px; text-transform: uppercase; }
    .t-dest-value { font-size: 0.95rem; font-weight: 600; color: #fff; margin-top: 2px; }

    /* ─── PERFORATION ─── */
    .perforation {
      display: flex; align-items: center;
      background: #fff;
    }
    .perf-circle {
      width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0;
      background: #091510;
    }
    .perf-line {
      flex: 1;
      border-top: 2px dashed #e8e8e8;
    }

    /* ─── BODY ─── */
    .t-body { padding: 24px 32px; }

    .info-grid {
      display: grid; grid-template-columns: 1fr 1fr;
      gap: 0; border: 1px solid #f0f0f0; border-radius: 12px;
      overflow: hidden; margin-bottom: 24px;
    }
    .info-cell {
      padding: 14px 18px;
      border-right: 1px solid #f0f0f0;
      border-bottom: 1px solid #f0f0f0;
    }
    .info-cell:nth-child(2n) { border-right: none; }
    .info-cell:nth-last-child(-n+2) { border-bottom: none; }
    .info-cell-label {
      font-size: 0.6rem; text-transform: uppercase; letter-spacing: 1.5px;
      color: #bbb; margin-bottom: 5px;
    }
    .info-cell-value { font-size: 0.9rem; font-weight: 600; color: #1a1a1a; }

    /* ─── QR SECTION ─── */
    .qr-section {
      background: linear-gradient(135deg, #f0f7f2, #e8f4ec);
      border: 1px solid #d4ead9; border-radius: 14px;
      padding: 20px; display: flex; gap: 20px; align-items: center;
      margin-bottom: 20px;
    }
    .qr-img-wrap {
      background: #fff; border-radius: 10px; padding: 8px;
      box-shadow: 0 4px 12px rgba(26,58,38,.12); flex-shrink: 0;
    }
    .qr-img-wrap img { display: block; border-radius: 6px; }
    .qr-right {}
    .qr-kode-label { font-size: 0.6rem; color: #888; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 6px; }
    .qr-kode {
      font-family: 'Courier New', monospace;
      font-size: 1.35rem; font-weight: 700; letter-spacing: 3px; color: #1a3a26;
    }
    .qr-hints { margin-top: 12px; display: flex; flex-direction: column; gap: 5px; }
    .qr-hint { font-size: 0.72rem; color: #6a9a75; display: flex; align-items: flex-start; gap: 7px; }
    .qr-hint i { margin-top: 1px; flex-shrink: 0; }

    /* ─── TOTAL ─── */
    .total-row {
      background: #1b3d28; border-radius: 12px;
      padding: 16px 20px; display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 16px;
    }
    .total-left {}
    .total-label { font-size: 0.65rem; color: rgba(255,255,255,.5); text-transform: uppercase; letter-spacing: 1px; }
    .total-sub { font-size: 0.72rem; color: rgba(255,255,255,.35); margin-top: 3px; }
    .total-amount { font-size: 1.5rem; font-weight: 700; color: #e8a83a; }

    /* ─── NOTE ─── */
    .note-box {
      background: #fffbf0; border: 1px solid #fde68a;
      border-radius: 10px; padding: 12px 16px;
      display: flex; gap: 10px; font-size: 0.78rem; color: #78520a;
    }
    .note-box i { color: #d97706; margin-top: 1px; flex-shrink: 0; }

    /* ─── FOOTER ─── */
    .t-footer {
      background: #f9fafb; border-top: 1px solid #f0f0f0;
      padding: 16px 32px; text-align: center;
      font-size: 0.72rem; color: #bbb; line-height: 1.7;
    }

    /* ─── ACTION BUTTONS ─── */
    .actions {
      max-width: 560px; margin: 20px auto 0;
      display: flex; gap: 12px;
    }
    .btn-dl, .btn-wa {
      flex: 1; padding: 14px 20px;
      border-radius: 12px; font-size: 0.88rem; font-weight: 600;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      text-decoration: none; cursor: pointer; border: none;
      transition: all .25s;
    }
    .btn-dl { background: #e8a83a; color: #1a1a1a; }
    .btn-dl:hover { background: #f5bb50; transform: translateY(-2px); }
    .btn-wa { background: #22c55e; color: #fff; }
    .btn-wa:hover { background: #16a34a; transform: translateY(-2px); }

    /* ─── PRINT ─── */
    @media print {
      body { background: #fff; padding: 0; }
      .nav-bar, .actions { display: none !important; }
      .ticket { box-shadow: none; border: 1px solid #ddd; }
      .perf-circle { background: #fff; border: 2px solid #ddd; }
    }

    @media (max-width: 500px) {
      .qr-section { flex-direction: column; text-align: center; }
      .qr-hints { align-items: center; }
      .actions { flex-direction: column; }
      .t-header-top { flex-direction: column; gap: 12px; }
    }
  </style>
</head>
<body>

<div class="nav-bar">
  <a href="<?php echo htmlspecialchars($backLink); ?>"><i class="fas fa-arrow-left"></i> <?php echo htmlspecialchars($backText); ?></a>
  <a href="javascript:window.print()"><i class="fas fa-print"></i> Cetak</a>
</div>

<div class="ticket" id="ticket-card">
  <!-- HEADER -->
  <div class="t-header">
    <div class="t-header-top">
      <div class="t-brand">
        <div class="t-brand-eyebrow"><i class="fas fa-leaf"></i> &nbsp;E-Ticket Resmi</div>
        <div class="t-brand-name">Bukit <span>Fajar</span> Lestari</div>
        <div class="t-brand-sub">Kawasan Ekowisata · Tenggarong, Kalimantan Timur</div>
      </div>
      <div class="status-pill <?php echo $statusClass; ?>">
        <i class="fas <?php echo $statusIcon; ?>"></i> <?php echo $statusText; ?>
      </div>
    </div>
    <div class="t-header-dest">
      <div class="t-dest-icon"><i class="fas fa-map-marker-alt"></i></div>
      <div>
        <div class="t-dest-label">Destinasi Wisata</div>
        <div class="t-dest-value"><?php echo htmlspecialchars($data['destinasi']); ?></div>
      </div>
      <div style="margin-left:auto;text-align:right;">
        <div class="t-dest-label">Tanggal Kunjungan</div>
        <div class="t-dest-value"><?php echo $hariStr . ', ' . $tanggal; ?></div>
      </div>
    </div>
  </div>

  <!-- PERFORATION -->
  <div class="perforation">
    <div class="perf-circle"></div>
    <div class="perf-line"></div>
    <div class="perf-circle"></div>
  </div>

  <!-- BODY -->
  <div class="t-body">
    <div class="info-grid">
      <div class="info-cell">
        <div class="info-cell-label">Nama Pengunjung</div>
        <div class="info-cell-value"><?php echo htmlspecialchars($data['nama_lengkap']); ?></div>
      </div>
      <div class="info-cell">
        <div class="info-cell-label">No. WhatsApp</div>
        <div class="info-cell-value"><?php echo htmlspecialchars($data['whatsapp']); ?></div>
      </div>
      <div class="info-cell">
        <div class="info-cell-label">Jumlah Pengunjung</div>
        <div class="info-cell-value"><?php echo (int)$data['jumlah_pengunjung']; ?> Orang</div>
      </div>
      <div class="info-cell">
        <div class="info-cell-label">Jenis Hari</div>
        <div class="info-cell-value"><?php echo htmlspecialchars($data['jenis_hari'] ?? '-'); ?></div>
      </div>
    </div>

    <!-- QR + KODE -->
    <div class="qr-section">
      <div class="qr-img-wrap">
        <img src="<?php echo $qrUrl; ?>" width="130" height="130" alt="QR Code Tiket">
      </div>
      <div class="qr-right">
        <div class="qr-kode-label">Kode Booking</div>
        <div class="qr-kode"><?php echo htmlspecialchars($data['kode_booking']); ?></div>
        <div class="qr-hints">
          <div class="qr-hint"><i class="fas fa-camera"></i><span>Scan QR pakai kamera HP untuk verifikasi otomatis</span></div>
          <div class="qr-hint"><i class="fas fa-keyboard"></i><span>Atau tunjukkan kode booking ke petugas loket</span></div>
        </div>
      </div>
    </div>

    <!-- TOTAL -->
    <div class="total-row">
      <div class="total-left">
        <div class="total-label">Total Pembayaran</div>
        <div class="total-sub"><?php echo (int)$data['jumlah_pengunjung']; ?> orang × Rp <?php echo number_format($data['harga_satuan'], 0, ',', '.'); ?> (<?php echo htmlspecialchars($data['jenis_hari'] ?? '-'); ?>)</div>
      </div>
      <div class="total-amount">Rp <?php echo number_format($data['total_harga'], 0, ',', '.'); ?></div>
    </div>

    <!-- NOTE -->
    <div class="note-box">
      <i class="fas fa-circle-info"></i>
      <span>Bayar <strong>tunai di loket</strong> saat tiba di lokasi. Tiket ini berlaku untuk <strong><?php echo (int)$data['jumlah_pengunjung']; ?> orang</strong> pada tanggal yang tertera dan tidak dapat dipindahtangankan.</span>
    </div>
  </div>

  <!-- FOOTER -->
  <div class="t-footer">
    <i class="fas fa-leaf" style="color:#6a9a75;margin-right:5px;"></i>
    Terima kasih telah berkontribusi melestarikan alam bersama KTH & Pokdarwis Fajar Lestari.<br>
    Selamat menikmati keindahan alam Tenggarong, Kalimantan Timur! 🌿
  </div>
</div>

<!-- ACTION BUTTONS -->
<div class="actions">
  <button class="btn-dl" onclick="downloadTicket(this)">
    <i class="fas fa-download"></i> Download Tiket (PNG)
  </button>
  <a class="btn-wa" href="https://wa.me/?text=<?php echo urlencode('🌿 E-Ticket Bukit Fajar Lestari' . "\n" . 'Kode: ' . $data['kode_booking'] . "\n" . 'Tanggal: ' . $hariStr . ', ' . $tanggal . "\n" . 'Destinasi: ' . $data['destinasi'] . "\n" . 'Pengunjung: ' . (int)$data['jumlah_pengunjung'] . ' orang' . "\n\n" . 'Link Tiket: ' . $verifyUrl); ?>" target="_blank">
    <i class="fab fa-whatsapp"></i> Bagikan via WhatsApp
  </a>
</div>

<script>
function downloadTicket(btn) {
  const orig = btn.innerHTML;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyiapkan...';
  btn.disabled = true;
  html2canvas(document.getElementById('ticket-card'), {
    scale: 3, useCORS: true, backgroundColor: '#ffffff', logging: false
  }).then(canvas => {
    const a = document.createElement('a');
    a.download = 'eticket-<?php echo htmlspecialchars($data['kode_booking']); ?>.png';
    a.href = canvas.toDataURL('image/png');
    a.click();
    btn.innerHTML = '<i class="fas fa-check"></i> Berhasil!';
    setTimeout(() => { btn.innerHTML = orig; btn.disabled = false; }, 2500);
  });
}
</script>
</body>
</html>