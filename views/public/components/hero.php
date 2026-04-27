<section id="hero">
  <div class="hero-bg" style="background: url('<?php echo htmlspecialchars($heroImagePath); ?>') center/cover no-repeat;"></div>
  <div class="hero-overlay"></div>

  <div class="container hero-content">
    <div class="row">
      <div class="col-lg-9 col-xl-8">
        <div class="hero-eyebrow"><?php echo htmlspecialchars($heroEyebrow); ?></div>

        <h1 class="hero-title">
          <?php echo htmlspecialchars($heroTitleMain); ?><br>
          <span class="accent"><?php echo htmlspecialchars($heroTitleAccent); ?></span><br>
          <?php echo htmlspecialchars($heroTitleBottom); ?>
        </h1>

        <p class="hero-sub"><?php echo htmlspecialchars($heroSubtitle); ?></p>

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
    <div class="d-flex flex-wrap">
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