<?php
require_once __DIR__ . '/../helpers/security_helper.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/PengaturanTampilanModel.php';

$pengaturanModel = new PengaturanTampilanModel($conn);
$setting = $pengaturanModel->getFirstSetting();

$aboutImage        = !empty($setting['about_image']) ? $setting['about_image'] : 'about-default.jpg';
$aboutBadgeNum     = !empty($setting['about_badge_num']) ? $setting['about_badge_num'] : '850';
$aboutBadgeLabel   = !empty($setting['about_badge_label']) ? $setting['about_badge_label'] : 'Hektare Kawasan';
$aboutSectionLabel = !empty($setting['about_section_label']) ? $setting['about_section_label'] : 'Tentang Kawasan';
$aboutTitle        = !empty($setting['about_title']) ? $setting['about_title'] : 'Kawasan Ekowisata Bukit Fajar Lestari';
$aboutDescription  = !empty($setting['about_description']) ? $setting['about_description'] : 'Terletak di Tenggarong Seberang, Kutai Kartanegara, Bukit Fajar Lestari adalah kawasan hutan yang dijaga dan dikembangkan bersama oleh dua kelompok masyarakat lokal. Kawasan ini menawarkan panorama alam yang autentik, udara segar, dan pengalaman ekowisata yang berkesan.';

$feature1Icon  = !empty($setting['feature_1_icon']) ? $setting['feature_1_icon'] : 'fas fa-leaf';
$feature1Title = !empty($setting['feature_1_title']) ? $setting['feature_1_title'] : 'Pelestarian Alam Aktif';
$feature1Desc  = !empty($setting['feature_1_desc']) ? $setting['feature_1_desc'] : 'Program penghijauan dan budidaya tanaman lokal yang dikelola berkelanjutan oleh KTH Fajar Lestari.';

$feature2Icon  = !empty($setting['feature_2_icon']) ? $setting['feature_2_icon'] : 'fas fa-mountain';
$feature2Title = !empty($setting['feature_2_title']) ? $setting['feature_2_title'] : 'Destinasi Wisata Alam';
$feature2Desc  = !empty($setting['feature_2_desc']) ? $setting['feature_2_desc'] : 'Panorama perbukitan, kebun alpukat, area perkemahan, dan spot foto alam tersedia untuk pengunjung.';

$feature3Icon  = !empty($setting['feature_3_icon']) ? $setting['feature_3_icon'] : 'fas fa-users';
$feature3Title = !empty($setting['feature_3_title']) ? $setting['feature_3_title'] : 'Pengelolaan Berbasis Komunitas';
$feature3Desc  = !empty($setting['feature_3_desc']) ? $setting['feature_3_desc'] : 'Dikelola oleh masyarakat lokal yang menjaga kelestarian kawasan dan pengalaman wisata pengunjung.';

$aboutImagePath = '../assets/images/galeri/' . $aboutImage;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Tentang Bukit Fajar Lestari</title>
  <meta name="description" content="Tentang Bukit Fajar Lestari, kawasan ekowisata alam berbasis komunitas di Tenggarong Seberang, Kutai Kartanegara." />

  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400&family=DM+Sans:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,700;0,900;1,700&display=swap" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

  <link href="../assets/css/style.css" rel="stylesheet" />
  <link href="../assets/css/galeri-page.css" rel="stylesheet" />

  <style>
    body {
      background: var(--cream);
    }

    .about-page-hero {
      padding: 155px 0 90px;
      background: linear-gradient(145deg, rgba(15,36,32,0.98), rgba(30,61,53,0.96));
      color: var(--white);
      position: relative;
      overflow: hidden;
    }

    .about-page-hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background:
        radial-gradient(circle at 78% 18%, rgba(232,155,58,0.16), transparent 30%),
        radial-gradient(circle at 12% 88%, rgba(127,163,138,0.12), transparent 32%);
      pointer-events: none;
    }

    .about-page-hero .container {
      position: relative;
      z-index: 2;
    }

    .about-back-link {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      color: var(--dawn);
      text-decoration: none;
      font-size: 0.78rem;
      letter-spacing: 2px;
      text-transform: uppercase;
      font-weight: 700;
      margin-bottom: 30px;
      transition: all 0.3s ease;
    }

    .about-back-link:hover {
      color: var(--dawn-light);
      transform: translateX(-4px);
    }

    .about-page-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: clamp(2.8rem, 6vw, 5.2rem);
      font-weight: 700;
      line-height: 1.05;
      max-width: 980px;
      margin: 18px auto 22px;
    }

    .about-page-subtitle {
      max-width: 760px;
      margin: 0 auto;
      color: rgba(255,255,255,0.72);
      line-height: 1.8;
      font-size: 1rem;
    }

    .about-page-main {
      padding: 100px 0;
      background: var(--cream);
    }

    .about-page-img {
      position: relative;
      height: 560px;
      border-radius: 6px;
      overflow: hidden;
      background: rgba(47,93,80,0.08);
    }

    .about-page-img img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .about-page-badge {
      position: absolute;
      left: 26px;
      bottom: 26px;
      background: var(--dawn);
      color: var(--white);
      padding: 20px 28px;
      box-shadow: 0 16px 45px rgba(0,0,0,0.25);
    }

    .about-page-badge .num {
      font-family: 'Cormorant Garamond', serif;
      font-size: 2.7rem;
      font-weight: 700;
      line-height: 1;
    }

    .about-page-badge .label {
      font-size: 0.68rem;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: rgba(255,255,255,0.82);
    }

    .about-info-card {
      height: 100%;
      background: var(--white);
      border: 1px solid rgba(47,93,80,0.11);
      border-radius: 6px;
      padding: 36px;
      box-shadow: 0 18px 50px rgba(47,93,80,0.08);
    }

    .about-social-section {
      padding: 90px 0;
      background: var(--forest-deep);
      color: var(--white);
    }

    .about-social-card {
      height: 100%;
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.10);
      border-radius: 6px;
      padding: 36px;
    }

    .about-social-btn {
      display: inline-flex;
      align-items: center;
      gap: 12px;
      padding: 14px 24px;
      background: var(--dawn);
      color: var(--white);
      text-decoration: none;
      border-radius: 3px;
      font-weight: 700;
      transition: all 0.3s ease;
    }

    .about-social-btn:hover {
      background: var(--dawn-dark);
      color: var(--white);
      transform: translateY(-2px);
    }

    .about-page-actions {
      margin-top: 34px;
      display: flex;
      gap: 14px;
      flex-wrap: wrap;
    }

    @media (max-width: 991px) {
      .about-page-img {
        height: 430px;
      }

      .about-info-card,
      .about-social-card {
        padding: 28px;
      }
    }
  </style>
</head>

<body>
  <nav id="navbar" class="navbar navbar-expand-lg scrolled">
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
          <li class="nav-item"><a class="nav-link active" href="tentang.php">Tentang</a></li>
          <li class="nav-item"><a class="nav-link" href="../index.php#mitra">Mitra</a></li>
          <li class="nav-item"><a class="nav-link" href="../index.php#wisata">Wisata</a></li>
          <li class="nav-item"><a class="nav-link" href="galeri.php">Galeri</a></li>
          <li class="nav-item"><a class="nav-link" href="../index.php#ulasan">Ulasan</a></li>
          <li class="nav-item"><a class="nav-link" href="../index.php#kontak">Kontak</a></li>
          <li class="nav-item"><a class="nav-link" href="riwayat.php">Cek Booking</a></li>
          <li class="nav-item ms-2"><a class="nav-link btn-nav" href="../index.php#booking">Pesan Tiket</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <main>
    <section class="about-page-hero">
      <div class="container text-center">
        <a href="../index.php" class="about-back-link">
          <i class="fas fa-arrow-left"></i>
          Kembali ke Beranda
        </a>

        <div class="section-label" style="justify-content:center;">
          Tentang Bukit Fajar Lestari
        </div>

        <h1 class="about-page-title">
          Kawasan Ekowisata Berbasis Alam dan Komunitas
        </h1>

        <p class="about-page-subtitle">
          Bukit Fajar Lestari menghadirkan panorama perbukitan, area perkemahan, kebun alpukat, dan suasana alam yang dikelola bersama masyarakat lokal di Tenggarong Seberang.
        </p>
      </div>
    </section>

    <section class="about-page-main">
      <div class="container">
        <div class="row align-items-center g-5">
          <div class="col-lg-5">
            <div class="about-page-img">
              <img src="<?php echo htmlspecialchars($aboutImagePath); ?>" alt="Kawasan Bukit Fajar Lestari">
              <div class="about-page-badge">
                <div class="num"><?php echo htmlspecialchars($aboutBadgeNum); ?></div>
                <div class="label"><?php echo htmlspecialchars($aboutBadgeLabel); ?></div>
              </div>
            </div>
          </div>

          <div class="col-lg-7">
            <div class="about-info-card">
              <div class="section-label"><?php echo htmlspecialchars($aboutSectionLabel); ?></div>

              <h2 class="section-title">
                <?php echo nl2br(htmlspecialchars($aboutTitle)); ?>
              </h2>

              <div class="divider-line"></div>

              <p style="color:#666;line-height:1.85;margin-bottom:32px;font-size:0.95rem;">
                <?php echo htmlspecialchars($aboutDescription); ?>
              </p>

              <div class="about-feature">
                <div class="about-feature-icon">
                  <i class="<?php echo htmlspecialchars($feature1Icon); ?>"></i>
                </div>
                <div>
                  <h6><?php echo htmlspecialchars($feature1Title); ?></h6>
                  <p><?php echo htmlspecialchars($feature1Desc); ?></p>
                </div>
              </div>

              <div class="about-feature">
                <div class="about-feature-icon">
                  <i class="<?php echo htmlspecialchars($feature2Icon); ?>"></i>
                </div>
                <div>
                  <h6><?php echo htmlspecialchars($feature2Title); ?></h6>
                  <p><?php echo htmlspecialchars($feature2Desc); ?></p>
                </div>
              </div>

              <div class="about-feature">
                <div class="about-feature-icon">
                  <i class="<?php echo htmlspecialchars($feature3Icon); ?>"></i>
                </div>
                <div>
                  <h6><?php echo htmlspecialchars($feature3Title); ?></h6>
                  <p><?php echo htmlspecialchars($feature3Desc); ?></p>
                </div>
              </div>

              <div class="about-page-actions">
                <a href="../index.php#wisata" class="btn-primary-cta">
                  <i class="fas fa-mountain-sun"></i>
                  Lihat Wisata
                </a>

                <a href="../index.php#booking" class="btn-outline-cta" style="color:var(--forest); border-color:rgba(47,93,80,0.35);">
                  Pesan Tiket
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="about-social-section">
      <div class="container">
        <div class="row g-4 align-items-stretch">
          <div class="col-lg-6">
            <div class="about-social-card">
              <div class="section-label">Pengelola</div>
              <h2 class="section-title light" style="font-size:2.5rem;">
                Dikelola Bersama Masyarakat Lokal
              </h2>
              <div class="divider-line"></div>

              <p style="color:rgba(255,255,255,0.68);line-height:1.85;font-size:0.95rem;margin-bottom:0;">
                Bukit Fajar Lestari dikembangkan melalui peran KTH dan Pokdarwis Fajar Lestari. Pengelolaan kawasan dilakukan dengan menjaga kelestarian alam, memperkenalkan potensi lokal, serta menghadirkan pengalaman wisata yang nyaman bagi pengunjung.
              </p>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="about-social-card">
              <div class="section-label">Media Sosial</div>
              <h2 class="section-title light" style="font-size:2.5rem;">
                Ikuti Aktivitas Bukit Fajar Lestari
              </h2>
              <div class="divider-line"></div>

              <p style="color:rgba(255,255,255,0.68);line-height:1.85;font-size:0.95rem;margin-bottom:28px;">
                Lihat dokumentasi kegiatan, suasana wisata, area camping, panorama alam, dan informasi terbaru melalui Instagram resmi Pokdarwis Fajar Lestari.
              </p>

              <a 
                href="https://www.instagram.com/pokdarwis_fajar_lestari" 
                target="_blank" 
                rel="noopener" 
                class="about-social-btn"
              >
                <i class="fab fa-instagram"></i>
                @pokdarwis_fajar_lestari
              </a>

              <div style="margin-top:28px;padding-top:24px;border-top:1px solid rgba(255,255,255,0.12);">
                <div style="font-size:0.75rem;letter-spacing:2px;text-transform:uppercase;color:var(--dawn);margin-bottom:10px;">
                  Lokasi
                </div>

                <div style="color:rgba(255,255,255,0.78);line-height:1.7;">
                  Puncak HR Fajar Lestari<br>
                  Tenggarong Seberang, Kutai Kartanegara, Kalimantan Timur
                </div>

                <a 
                  href="https://www.google.com/maps/search/?api=1&query=Puncak%20HR%20Fajar%20Lestari%20Tenggarong%20Seberang%20Kutai%20Kartanegara%20Kalimantan%20Timur" 
                  target="_blank" 
                  rel="noopener"
                  style="display:inline-block;color:var(--dawn);text-decoration:none;margin-top:12px;font-size:0.9rem;"
                >
                  <i class="fas fa-location-dot me-1"></i> Buka di Google Maps
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer id="footer">
    <div class="container">
      <div class="row g-5">
        <div class="col-lg-5">
          <div class="footer-brand">Bukit <span>Fajar</span> Lestari</div>

          <p class="footer-desc">
            Kawasan ekowisata alam yang dikelola bersama oleh KTH dan Pokdarwis Fajar Lestari di Tenggarong Seberang, Kalimantan Timur. Wisata alam yang autentik dan berkelanjutan.
          </p>

          <div class="footer-socials">
            <a href="https://www.instagram.com/pokdarwis_fajar_lestari" target="_blank" rel="noopener" class="footer-social-btn" title="Instagram Pokdarwis Fajar Lestari">
              <i class="fab fa-instagram"></i>
            </a>

            <a href="https://wa.me/6285179963228" target="_blank" rel="noopener" class="footer-social-btn" title="WhatsApp Pokdarwis">
              <i class="fab fa-whatsapp"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="footer-col-title">Navigasi</div>
          <ul class="footer-links">
            <li><a href="tentang.php">Tentang</a></li>
            <li><a href="../index.php#mitra">Mitra</a></li>
            <li><a href="../index.php#wisata">Wisata</a></li>
            <li><a href="galeri.php">Galeri</a></li>
            <li><a href="../index.php#ulasan">Ulasan</a></li>
            <li><a href="../index.php#kontak">Kontak</a></li>
            <li><a href="../index.php#booking">Pesan Tiket</a></li>
          </ul>
        </div>

        <div class="col-lg-4 col-6">
          <div class="footer-col-title">Destinasi</div>
          <ul class="footer-links">
            <li><a href="../index.php#wisata">Bukit Panorama Fajar</a></li>
            <li><a href="../index.php#wisata">Kebun Alpukat Fajar</a></li>
            <li><a href="../index.php#wisata">Area Perkemahan Lestari</a></li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom">
        <p>© 2024 Bukit Fajar Lestari · KTH & Pokdarwis Fajar Lestari · Tenggarong Seberang, Kaltim</p>
        <p>Dibuat untuk <a href="#">Proyek Akhir Sistem Informasi</a></p>
      </div>
    </div>
  </footer>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/script.js"></script>
</body>
</html>