<section id="hero">
  <div class="hero-bg" style="background: url('<?php echo htmlspecialchars($heroImagePath); ?>') center/cover no-repeat;"></div>
  <div class="hero-overlay"></div>

  <div class="container hero-content">
    <div class="row justify-content-center">
      <div class="col-lg-10 col-xl-9 text-center">
        <div class="hero-eyebrow">
          Ekowisata Alam · Tenggarong Seberang
        </div>

        <h1 class="hero-title">
          Bukit <span class="accent">Fajar</span> Lestari
        </h1>

        <p class="hero-sub">
          Nikmati panorama alam dari ketinggian, area camping, kebun alpukat, dan suasana hijau yang cocok untuk melepas penat bersama keluarga maupun komunitas.
        </p>

        <div class="hero-actions">
          <a href="#booking" class="btn-primary-cta">
            <i class="fas fa-ticket-alt"></i> Pesan Tiket
          </a>
          <a href="#wisata" class="btn-outline-cta">Lihat Wisata</a>
        </div>
      </div>
    </div>
  </div>

  <div class="hero-stats container">
    <div class="d-flex flex-wrap justify-content-center">
      <div class="hero-stat-item">
        <div class="hero-stat-num"><?php echo htmlspecialchars($heroStat1Num); ?></div>
        <div class="hero-stat-label"><?php echo htmlspecialchars($heroStat1Label); ?></div>
      </div>

      <div class="hero-stat-item">
        <div class="hero-stat-num"><?php echo htmlspecialchars($heroStat2Num); ?></div>
        <div class="hero-stat-label"><?php echo htmlspecialchars($heroStat2Label); ?></div>
      </div>

      <div class="hero-stat-item">
        <div class="hero-stat-num"><?php echo htmlspecialchars($heroStat3Num); ?></div>
        <div class="hero-stat-label"><?php echo htmlspecialchars($heroStat3Label); ?></div>
      </div>

      <div class="hero-stat-item">
        <div class="hero-stat-num"><?php echo htmlspecialchars($heroStat4Num); ?></div>
        <div class="hero-stat-label"><?php echo htmlspecialchars($heroStat4Label); ?></div>
      </div>
    </div>
  </div>

  <div class="hero-scroll">
    <div class="scroll-line"></div>
    <span>Scroll</span>
  </div>
</section>