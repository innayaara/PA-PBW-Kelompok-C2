  <!-- JS Plugins -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

  <!-- Custom JS -->
  <script src="assets/js/script.js"></script>
  <script src="assets/js/booking-vue.js"></script>
  <script src="assets/js/lightbox.js"></script>

  <!-- Lightbox HTML -->
  <div class="lb-backdrop" id="lb-backdrop">
    <div class="lb-counter" id="lb-counter"><span>1</span> / 1</div>
    <button class="lb-close" id="lb-close" aria-label="Tutup"><i class="fas fa-times"></i></button>
    <button class="lb-nav lb-prev" id="lb-prev" aria-label="Sebelumnya"><i class="fas fa-chevron-left"></i></button>
    <button class="lb-nav lb-next" id="lb-next" aria-label="Berikutnya"><i class="fas fa-chevron-right"></i></button>
    <div class="lb-image-wrap">
      <div class="lb-loader" id="lb-loader" style="display:none;"><i class="fas fa-circle-notch fa-spin"></i></div>
      <img class="lb-img" id="lb-img" src="" alt="" />
      <div class="lb-zoom-bar">
        <button class="lb-zoom-btn" id="lb-zoom-out" title="Perkecil"><i class="fas fa-search-minus"></i></button>
        <button class="lb-zoom-btn" id="lb-zoom-reset" title="Reset"><i class="fas fa-compress-alt"></i></button>
        <button class="lb-zoom-btn" id="lb-zoom-in" title="Perbesar"><i class="fas fa-search-plus"></i></button>
      </div>
    </div>
    <div class="lb-caption">
      <div class="lb-caption-title" id="lb-title"></div>
      <div class="lb-caption-cat" id="lb-cat"></div>
    </div>
    <div class="lb-thumbs" id="lb-thumbs"></div>
  </div>
</body>
</html>