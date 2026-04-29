<!-- ===== GALERI ===== -->
<section id="galeri">
  <div class="container">
    <div class="row justify-content-between align-items-end mb-5">
      <div class="col-lg-7 reveal">
        <div class="section-label justify-content-start">Pokdarwis Fajar Lestari</div>
        <h2 class="section-title light">Galeri Wisata Alam</h2>
        <p style="color:rgba(255,255,255,0.45);max-width:420px;margin:14px 0 0;font-size:0.85rem;line-height:1.8;">
          Keindahan Bukit Fajar Lestari melalui lensa pengunjung.
        </p>
      </div>
      <div class="col-lg-4 text-lg-end reveal reveal-delay-2">
        <a href="pages/galeri.php" style="color:var(--dawn);font-size:0.8rem;letter-spacing:2px;text-transform:uppercase;text-decoration:none;">
          Lihat Semua Foto <i class="fas fa-arrow-right ms-2"></i>
        </a>
      </div>
    </div>

    <div class="gallery-grid reveal">
      <?php if (!empty($galeriList)): ?>
        <?php
          $index = 0;
          foreach ($galeriList as $row):
            $isTall     = in_array($index, [0, 3]);
            $gambarPath = 'assets/images/galeri/' . $row['gambar'];
        ?>
          <div class="gallery-item <?php echo $isTall ? 'tall' : ''; ?>"
               data-lb
               data-src="<?php echo htmlspecialchars($gambarPath); ?>"
               data-title="<?php echo htmlspecialchars($row['judul_foto']); ?>"
               data-cat="<?php echo htmlspecialchars($row['kategori'] ?: 'Wisata Alam'); ?>">
            <img
              src="<?php echo htmlspecialchars($gambarPath); ?>"
              alt="<?php echo htmlspecialchars($row['judul_foto']); ?>"
            />
            <div class="gallery-item-overlay">
              <i class="fas fa-expand-alt"></i>
            </div>
          </div>
        <?php
          $index++;
          endforeach;
        ?>
      <?php else: ?>
        <div style="color:rgba(255,255,255,0.55);font-size:0.95rem;text-align:center;padding:40px 0;">
          Belum ada galeri yang ditampilkan.
        </div>
      <?php endif; ?>
    </div>

    <!-- CTA bawah -->
    <?php if (!empty($galeriList)): ?>
    <div class="text-center mt-5 reveal">
      <a href="pages/galeri.php" class="btn-primary-cta">
        <i class="fas fa-images"></i> Lihat Semua Koleksi Foto
      </a>
    </div>
    <?php endif; ?>
  </div>
</section>