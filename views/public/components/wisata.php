<section id="wisata">
  <div class="container">
    <div class="row justify-content-between align-items-end mb-5">
      <div class="col-lg-7 reveal">
        <div class="section-label">Destinasi</div>
        <h2 class="section-title">Jelajahi Wisata<br>Alam Terbaik</h2>
      </div>
      <div class="col-lg-4 text-lg-end reveal reveal-delay-2">
        <a href="#booking" style="color:var(--dawn);font-size:0.8rem;letter-spacing:2px;text-transform:uppercase;text-decoration:none;">
          Pesan Destinasi <i class="fas fa-arrow-right ms-2"></i>
        </a>
      </div>
    </div>

    <div class="row g-4">
      <?php if (!empty($wisataList)): ?>
        <?php
          $delay = 1;
          foreach ($wisataList as $row):
            $thumbnailPath = !empty($row['thumbnail'])
              ? 'assets/images/galeri/' . $row['thumbnail']
              : 'assets/images/galeri/default.jpg';
        ?>
          <div class="col-lg-4 col-md-6 reveal reveal-delay-<?php echo $delay; ?>">
            <div class="wisata-card">
              <img src="<?php echo htmlspecialchars($thumbnailPath); ?>" alt="<?php echo htmlspecialchars($row['nama_wisata']); ?>" />
              <div class="wisata-overlay"></div>

              <?php if (!empty($row['kategori']) && strtolower($row['kategori']) === 'panorama'): ?>
                <span class="wisata-tag">Populer</span>
              <?php endif; ?>

              <div class="wisata-content">
                <div class="wisata-cat"><?php echo htmlspecialchars($row['kategori'] ?: 'Wisata Alam'); ?></div>
                <div class="wisata-name"><?php echo htmlspecialchars($row['nama_wisata']); ?></div>
                <div class="wisata-info">
                  <span><i class="fas fa-clock"></i> <?php echo htmlspecialchars($row['jam_buka'] ?: 'Jam operasional'); ?></span>
                  <span><i class="fas fa-money-bill-wave"></i> Rp <?php echo number_format($row['harga_weekday'], 0, ',', '.'); ?></span>
                </div>
              </div>
            </div>
          </div>
        <?php
          $delay++;
          if ($delay > 3) $delay = 1;
          endforeach;
        ?>
      <?php else: ?>
        <div class="col-12 text-center">
          <p style="color:#666;font-size:0.95rem;">Belum ada data wisata yang ditampilkan.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>