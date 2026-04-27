<section id="tentang">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-5 reveal">
        <div class="about-img-wrap">
          <img src="<?php echo htmlspecialchars($aboutImagePath); ?>" alt="Kawasan Fajar Lestari" />
          <div class="about-img-badge">
            <div class="num"><?php echo htmlspecialchars($aboutBadgeNum); ?></div>
            <div class="label"><?php echo htmlspecialchars($aboutBadgeLabel); ?></div>
          </div>
        </div>
      </div>

      <div class="col-lg-7 reveal reveal-delay-2">
        <div class="section-label"><?php echo htmlspecialchars($aboutSectionLabel); ?></div>
        <h2 class="section-title"><?php echo nl2br(htmlspecialchars($aboutTitle)); ?></h2>
        <div class="divider-line"></div>

        <p style="color:#666;line-height:1.85;margin-bottom:32px;font-size:0.93rem;">
          <?php echo htmlspecialchars($aboutDescription); ?>
        </p>

        <div class="about-feature">
          <div class="about-feature-icon"><i class="<?php echo htmlspecialchars($feature1Icon); ?>"></i></div>
          <div>
            <h6><?php echo htmlspecialchars($feature1Title); ?></h6>
            <p><?php echo htmlspecialchars($feature1Desc); ?></p>
          </div>
        </div>

        <div class="about-feature">
          <div class="about-feature-icon"><i class="<?php echo htmlspecialchars($feature2Icon); ?>"></i></div>
          <div>
            <h6><?php echo htmlspecialchars($feature2Title); ?></h6>
            <p><?php echo htmlspecialchars($feature2Desc); ?></p>
          </div>
        </div>

        <div class="about-feature">
          <div class="about-feature-icon"><i class="<?php echo htmlspecialchars($feature3Icon); ?>"></i></div>
          <div>
            <h6><?php echo htmlspecialchars($feature3Title); ?></h6>
            <p><?php echo htmlspecialchars($feature3Desc); ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>