<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Galeri Foto — Bukit Fajar Lestari</title>
  <meta name="description" content="Lihat koleksi foto wisata alam Bukit Fajar Lestari — panorama, sunrise, camping, jalur hutan tropis, dan keindahan alam Tenggarong." />

  <!-- CSS Plugins -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400&family=DM+Sans:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,700;0,900;1,700&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

  <!-- Custom CSS -->
  <link href="../assets/css/style.css" rel="stylesheet" />
  <link href="../assets/css/galeri-page.css" rel="stylesheet" />
</head>
<body style="background: var(--forest-deep);">

<!-- ===== NAVBAR ===== -->
<nav id="navbar" class="navbar navbar-expand-lg">
  <div class="container">
    <a class="nav-brand" href="../index.php">
      Bukit <span>Fajar</span> Lestari
      <small>Ekowisata · Tenggarong</small>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navMenu">
      <ul class="navbar-nav align-items-center gap-1">
        <li class="nav-item"><a class="nav-link" href="../index.php#tentang">Tentang</a></li>
        <li class="nav-item"><a class="nav-link" href="../index.php#wisata">Wisata</a></li>
        <li class="nav-item"><a class="nav-link active" style="color:var(--dawn) !important;" href="galeri.php">Galeri</a></li>
        <li class="nav-item"><a class="nav-link" href="../index.php#kontak">Kontak</a></li>
        <li class="nav-item"><a class="nav-link" href="riwayat.php">Cek Booking</a></li>
        <li class="nav-item ms-2"><a class="nav-link btn-nav" href="../index.php#booking">Pesan Tiket</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- ===== HERO ===== -->
<section class="galeri-page-hero">
  <div class="container">
    <div class="row align-items-end">
      <div class="col-lg-8">
        <div class="reveal mb-4">
            <a href="../index.php" class="btn-outline-cta py-2 px-3" style="font-size: 0.7rem;">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Beranda
            </a>
        </div>
        <div class="galeri-hero-label reveal">Dokumentasi Alam</div>
        <h1 class="galeri-hero-title reveal reveal-delay-1">
          Galeri <span>Foto</span><br>Bukit Fajar Lestari
        </h1>
        <p class="galeri-hero-sub reveal reveal-delay-2">
          Koleksi foto keindahan alam Bukit Fajar Lestari — dari panorama, sunrise, jalur hutan tropis, hingga momen seru pengunjung.
        </p>
      </div>
      <div class="col-lg-4 text-lg-end mt-4 mt-lg-0 reveal reveal-delay-3">
        <div class="galeri-hero-count"><?php echo $totalFoto; ?></div>
        <div class="galeri-hero-count-label">Total Foto Tersedia</div>
      </div>
    </div>
  </div>
</section>

<!-- ===== FILTER BAR ===== -->
<div class="galeri-filter-bar">
  <div class="container">
    <div class="galeri-filter-inner">
      <a href="galeri.php"
         class="galeri-filter-btn <?php echo empty($kategoriAktif) ? 'active' : ''; ?>">
        Semua
      </a>
      <?php foreach ($kategoriList as $kat): ?>
        <a href="galeri.php?kategori=<?php echo urlencode($kat); ?>"
           class="galeri-filter-btn <?php echo ($kategoriAktif === $kat) ? 'active' : ''; ?>">
          <?php echo htmlspecialchars($kat); ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- ===== GALLERY MASONRY GRID ===== -->
<section class="galeri-page-section">
  <div class="container">
    <?php if (!empty($galeriList)): ?>
      <div class="galeri-masonry" id="galeri-masonry">
        <?php foreach ($galeriList as $idx => $row): ?>
          <?php $src = '../assets/images/galeri/' . htmlspecialchars($row['gambar']); ?>
          <div class="galeri-item reveal"
               data-lb
               data-src="<?php echo $src; ?>"
               data-title="<?php echo htmlspecialchars($row['judul_foto']); ?>"
               data-cat="<?php echo htmlspecialchars($row['kategori'] ?: 'Wisata Alam'); ?>">
            <img src="<?php echo $src; ?>"
                 alt="<?php echo htmlspecialchars($row['judul_foto']); ?>"
                 loading="lazy" />
            <div class="galeri-item-overlay">
              <div class="galeri-item-cat"><?php echo htmlspecialchars($row['kategori'] ?: 'Wisata Alam'); ?></div>
              <div class="galeri-item-title"><?php echo htmlspecialchars($row['judul_foto']); ?></div>
            </div>
            <div class="galeri-item-zoom"><i class="fas fa-expand-alt"></i></div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="galeri-empty">
        <i class="fas fa-images"></i>
        <p>Belum ada foto yang tersedia<?php echo $kategoriAktif ? ' untuk kategori <strong>' . htmlspecialchars($kategoriAktif) . '</strong>' : ''; ?>.</p>
        <?php if ($kategoriAktif): ?>
          <a href="galeri.php" class="galeri-filter-btn active mt-3">Lihat Semua Foto</a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- ===== LIGHTBOX ===== -->
<div class="lb-backdrop" id="lb-backdrop">
  <!-- Top bar -->
  <div class="lb-counter" id="lb-counter"><span>1</span> / 1</div>
  <button class="lb-close" id="lb-close" aria-label="Tutup"><i class="fas fa-times"></i></button>

  <!-- Nav arrows -->
  <button class="lb-nav lb-prev" id="lb-prev" aria-label="Sebelumnya"><i class="fas fa-chevron-left"></i></button>
  <button class="lb-nav lb-next" id="lb-next" aria-label="Berikutnya"><i class="fas fa-chevron-right"></i></button>

  <!-- Image wrap -->
  <div class="lb-image-wrap">
    <div class="lb-loader" id="lb-loader" style="display:none;">
      <i class="fas fa-circle-notch fa-spin"></i>
    </div>
    <img class="lb-img" id="lb-img" src="" alt="" />

    <!-- Zoom controls -->
    <div class="lb-zoom-bar">
      <button class="lb-zoom-btn" id="lb-zoom-out" title="Perkecil"><i class="fas fa-search-minus"></i></button>
      <button class="lb-zoom-btn" id="lb-zoom-reset" title="Reset Zoom"><i class="fas fa-compress-alt"></i></button>
      <button class="lb-zoom-btn" id="lb-zoom-in" title="Perbesar"><i class="fas fa-search-plus"></i></button>
    </div>
  </div>

  <!-- Caption -->
  <div class="lb-caption">
    <div class="lb-caption-title" id="lb-title"></div>
    <div class="lb-caption-cat" id="lb-cat"></div>
  </div>

  <!-- Thumbnail strip -->
  <div class="lb-thumbs" id="lb-thumbs"></div>
</div>

<!-- ===== FOOTER ===== -->
<footer id="footer">
  <div class="container">
    <div class="footer-bottom" style="border-top: 1px solid rgba(255,255,255,0.06); padding-top: 28px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
      <p style="font-size:0.78rem; color:rgba(255,255,255,0.3); margin:0;">© 2024 Bukit Fajar Lestari · KTH & Pokdarwis Fajar Lestari · Tenggarong, Kaltim</p>
      <a href="../index.php" style="font-size:0.78rem; color:var(--dawn); text-decoration:none;">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Beranda
      </a>
    </div>
  </div>
</footer>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/lightbox.js"></script>
</body>
</html>
