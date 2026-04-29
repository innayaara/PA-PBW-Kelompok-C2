/**
 * Lightbox — Vanilla JS
 * Supports: click-to-open, zoom (scroll + buttons + double-click),
 *           keyboard nav, arrow nav, thumbnail strip, swipe (touch).
 */
(function () {
  'use strict';

  let items   = [];   // array of { src, title, cat }
  let current = 0;
  let zoomLevel = 1;
  const ZOOM_STEP = 0.3;
  const ZOOM_MAX  = 4;
  const ZOOM_MIN  = 1;

  // touch/drag state
  let touchStartX = 0;
  let isDragging  = false;

  /* --- DOM refs (populated on DOMContentLoaded) --- */
  let backdrop, lbImg, lbPrev, lbNext, lbClose;
  let lbCounter, lbTitle, lbCat, lbThumbsEl, lbLoader;

  function init() {
    backdrop   = document.getElementById('lb-backdrop');
    lbImg      = document.getElementById('lb-img');
    lbPrev     = document.getElementById('lb-prev');
    lbNext     = document.getElementById('lb-next');
    lbClose    = document.getElementById('lb-close');
    lbCounter  = document.getElementById('lb-counter');
    lbTitle    = document.getElementById('lb-title');
    lbCat      = document.getElementById('lb-cat');
    lbThumbsEl = document.getElementById('lb-thumbs');
    lbLoader   = document.getElementById('lb-loader');

    if (!backdrop) return;

    /* Collect all gallery items on the page */
    document.querySelectorAll('[data-lb]').forEach((el, idx) => {
      items.push({
        src  : el.dataset.src,
        title: el.dataset.title || '',
        cat  : el.dataset.cat  || '',
      });
      el.addEventListener('click', () => open(idx));
    });

    /* Controls */
    lbClose.addEventListener('click', close);
    lbPrev.addEventListener('click', () => go(current - 1));
    lbNext.addEventListener('click', () => go(current + 1));

    /* Zoom buttons */
    document.getElementById('lb-zoom-in')?.addEventListener('click', () => adjustZoom(ZOOM_STEP));
    document.getElementById('lb-zoom-out')?.addEventListener('click', () => adjustZoom(-ZOOM_STEP));
    document.getElementById('lb-zoom-reset')?.addEventListener('click', () => setZoom(1));

    /* Double-click / double-tap to toggle zoom */
    lbImg.addEventListener('dblclick', () => {
      zoomLevel > 1 ? setZoom(1) : setZoom(2);
    });

    /* Mouse-wheel zoom */
    backdrop.addEventListener('wheel', (e) => {
      if (!backdrop.classList.contains('open')) return;
      e.preventDefault();
      adjustZoom(e.deltaY < 0 ? ZOOM_STEP : -ZOOM_STEP);
    }, { passive: false });

    /* Click backdrop to close (not clicking image) */
    backdrop.addEventListener('click', (e) => {
      if (e.target === backdrop) close();
    });

    /* Keyboard */
    document.addEventListener('keydown', (e) => {
      if (!backdrop.classList.contains('open')) return;
      if (e.key === 'Escape')      close();
      if (e.key === 'ArrowRight')  go(current + 1);
      if (e.key === 'ArrowLeft')   go(current - 1);
      if (e.key === '+')           adjustZoom(ZOOM_STEP);
      if (e.key === '-')           adjustZoom(-ZOOM_STEP);
    });

    /* Touch swipe */
    backdrop.addEventListener('touchstart', (e) => {
      touchStartX = e.touches[0].clientX;
      isDragging  = true;
    }, { passive: true });

    backdrop.addEventListener('touchend', (e) => {
      if (!isDragging) return;
      const diff = touchStartX - e.changedTouches[0].clientX;
      if (Math.abs(diff) > 50) {
        diff > 0 ? go(current + 1) : go(current - 1);
      }
      isDragging = false;
    }, { passive: true });
  }

  function open(idx) {
    current = idx;
    backdrop.classList.add('open');
    document.body.style.overflow = 'hidden';
    buildThumbs();
    render();
  }

  function close() {
    backdrop.classList.remove('open');
    document.body.style.overflow = '';
    setZoom(1);
  }

  function go(idx) {
    if (idx < 0 || idx >= items.length) return;
    current = idx;
    setZoom(1);
    render();
  }

  function render() {
    const item = items[current];

    /* show loader */
    lbLoader && (lbLoader.style.display = 'flex');
    lbImg.style.opacity = '0';

    lbImg.onload = () => {
      lbLoader && (lbLoader.style.display = 'none');
      lbImg.style.opacity = '1';
    };
    lbImg.src = item.src;

    /* caption */
    lbTitle.textContent = item.title;
    lbCat.textContent   = item.cat;

    /* counter */
    lbCounter.innerHTML = `<span>${current + 1}</span> / ${items.length}`;

    /* nav visibility */
    lbPrev.classList.toggle('hidden', current === 0);
    lbNext.classList.toggle('hidden', current === items.length - 1);

    /* active thumb */
    document.querySelectorAll('.lb-thumb').forEach((t, i) => {
      t.classList.toggle('active', i === current);
    });

    /* scroll active thumb into view */
    const activeThumb = lbThumbsEl?.querySelector('.lb-thumb.active');
    activeThumb?.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
  }

  function buildThumbs() {
    if (!lbThumbsEl) return;
    lbThumbsEl.innerHTML = '';
    items.forEach((item, idx) => {
      const img = document.createElement('img');
      img.src   = item.src;
      img.alt   = item.title;
      img.className = 'lb-thumb' + (idx === current ? ' active' : '');
      img.addEventListener('click', () => { setZoom(1); go(idx); });
      lbThumbsEl.appendChild(img);
    });
  }

  function setZoom(level) {
    zoomLevel = Math.min(ZOOM_MAX, Math.max(ZOOM_MIN, level));
    lbImg.style.transform = `scale(${zoomLevel})`;
    lbImg.classList.toggle('zoomed', zoomLevel > 1);
  }

  function adjustZoom(delta) {
    setZoom(zoomLevel + delta);
  }

  document.addEventListener('DOMContentLoaded', init);
})();
