<!-- ===== TESTIMONI ===== -->
<section id="ulasan" style="position: relative;">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <div class="section-label" style="justify-content:center;">Testimoni</div>
      <h2 class="section-title">Kata Mereka yang<br>Sudah Berkunjung</h2>
      <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#ulasanModal" style="border-radius: 30px; padding: 10px 25px; background-color: #1b3d28; border-color: #1b3d28;"><i class="fas fa-pencil-alt"></i> Tulis Ulasan Anda</button>
    </div>
    
    <?php if (isset($_GET['success']) && $_GET['success'] == 'review_submitted'): ?>
      <div class="alert alert-success text-center mb-4">
        Terima kasih! Ulasan Anda berhasil dikirim dan akan ditinjau oleh admin sebelum ditampilkan.
      </div>
    <?php elseif (isset($_GET['error'])): ?>
      <?php
        $errorMsg = '';
        switch($_GET['error']) {
          case 'invalid_name': $errorMsg = "Nama tidak valid! Hanya gunakan huruf dan spasi."; break;
          case 'invalid_email': $errorMsg = "Email tidak valid!"; break;
          case 'invalid_rating': $errorMsg = "Rating tidak valid!"; break;
          case 'invalid_message': $errorMsg = "Pesan/Komentar tidak valid atau mengandung karakter dilarang."; break;
          case 'csrf': $errorMsg = "Sesi tidak valid! Silakan muat ulang halaman dan coba lagi."; break;
          case 'too_many_requests': $errorMsg = "Terlalu banyak percobaan! Silakan coba lagi nanti."; break;
          case 'bot_detected': $errorMsg = "Aktivitas mencurigakan terdeteksi."; break;
          default: $errorMsg = "Terjadi kesalahan. Silakan periksa kembali form Anda.";
        }
      ?>
      <div class="alert alert-danger text-center mb-4">
        <i class="fas fa-exclamation-triangle me-2"></i> <?php echo $errorMsg; ?>
      </div>
    <?php endif; ?>

    <div class="row g-4 justify-content-center">
      <?php if (!empty($ulasanList)): ?>
        <?php foreach ($ulasanList as $idx => $ulasan): ?>
          <div class="col-lg-4 col-md-6 reveal reveal-delay-<?php echo ($idx % 3) + 1; ?>">
            <div class="testi-card">
              <div class="testi-stars">
                <?php for($i=1; $i<=5; $i++): ?>
                    <i class="fas fa-star" style="color: <?php echo $i <= $ulasan['rating'] ? '#fbbf24' : '#e5e7eb'; ?>"></i>
                <?php endfor; ?>
              </div>
              <p class="testi-text">"<?php echo e($ulasan['komentar']); ?>"</p>
              <div class="testi-author">
                <div class="avatar-placeholder" style="width: 50px; height: 50px; border-radius: 50%; background-color: #e2e8f0; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #64748b; font-size: 1.2rem; margin-right: 15px;">
                  <?php echo strtoupper(substr($ulasan['nama'], 0, 1)); ?>
                </div>
                <div>
                  <div class="testi-name"><?php echo e($ulasan['nama']); ?></div>
                  <div class="testi-from"><?php echo date('d M Y', strtotime($ulasan['tanggal'])); ?></div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center text-muted">
          <p>Belum ada ulasan yang ditampilkan. Jadilah yang pertama!</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Modal Ulasan -->
<div class="modal fade" id="ulasanModal" tabindex="-1" aria-labelledby="ulasanModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 15px; border: none;">
      <div class="modal-header" style="background-color: #1b3d28; color: white; border-top-left-radius: 15px; border-top-right-radius: 15px;">
        <h5 class="modal-title" id="ulasanModalLabel"><i class="fas fa-star" style="color: #fbbf24;"></i> Tulis Ulasan</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="controllers/process/submit_ulasan.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
        <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" name="nama" required placeholder="Nama Anda">
          </div>
          <div class="mb-3">
            <label class="form-label">Email Valid <small class="text-danger">(Wajib untuk verifikasi)</small></label>
            <input type="email" class="form-control" name="email" required placeholder="email@contoh.com">
          </div>
          <div class="mb-3">
            <label class="form-label">Rating</label>
            <select class="form-select" name="rating" required>
              <option value="5">⭐⭐⭐⭐⭐ (Sangat Bagus)</option>
              <option value="4">⭐⭐⭐⭐ (Bagus)</option>
              <option value="3">⭐⭐⭐ (Biasa)</option>
              <option value="2">⭐⭐ (Kurang)</option>
              <option value="1">⭐ (Sangat Kurang)</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Komentar / Pengalaman Anda</label>
            <textarea class="form-control" name="komentar" rows="4" required placeholder="Ceritakan pengalaman Anda di sini..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" style="background-color: #1b3d28; border-color: #1b3d28;">Kirim Ulasan</button>
        </div>
      </form>
    </div>
  </div>
</div>