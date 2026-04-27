<!-- ===== BOOKING CTA ===== -->
<section id="booking">
  <div class="booking-glow"></div>
  <div class="container booking-wrap" id="booking-app">
    <div class="row align-items-start g-5">
      <div class="col-lg-5">
        <div style="position: sticky; top: 120px; z-index: 10;">
          <div class="section-label" style="color:var(--dawn);">Pemesanan Online</div>
          <h2 class="section-title light">Pesan Tiket<br>Wisata Sekarang</h2>
          <div class="divider-line"></div>
          <p style="color:rgba(255,255,255,0.6);font-size:0.9rem;line-height:1.85;margin-bottom:32px;">
            Dapatkan e-ticket dengan QR Code langsung di email Anda. Proses cepat, mudah, dan aman. Tiket tersedia untuk kunjungan individu maupun grup.
          </p>
          <div class="price-tag">
            Rp {{ totalHarga }}
            <small>/ {{ jumlah }} orang · {{ isWeekend ? 'Weekend' : 'Weekday' }}</small>
          </div>
          <div style="margin-top:10px;font-size:0.8rem;color:rgba(255,255,255,0.4);">
            Harga per orang: <span style="color:var(--dawn);">Rp {{ unitPrice.toLocaleString('id-ID') }}</span>
          </div>
          <div class="weather-widget">
            <div class="weather-icon"><i class="fas fa-cloud-sun"></i></div>
            <div>
              <div class="weather-temp">28°C</div>
              <div class="weather-desc">Berawan sebagian</div>
              <div class="weather-location">Tenggarong, Kaltim</div>
            </div>
            <div style="margin-left:auto;text-align:right;">
              <div style="font-size:0.75rem;color:rgba(255,255,255,0.4);">Kelembaban</div>
              <div style="font-size:1rem;font-weight:600;color:var(--sage-light);">78%</div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="booking-form-card">
          <?php if (isset($_GET['error']) && $_GET['error'] === 'quota_full'): ?>
            <div class="alert alert-danger mb-4" style="background:rgba(220,53,69,0.1);border:1px solid rgba(220,53,69,0.2);color:#ff8e98;font-size:0.9rem;border-radius:12px;padding:15px;">
              <i class="fas fa-exclamation-triangle me-2"></i>
              <strong>Kuota Penuh!</strong> Maaf, kuota untuk tanggal tersebut sudah habis.
              <?php if (isset($_GET['left'])): ?>(Sisa: <?php echo (int)$_GET['left']; ?>)<?php endif; ?>
            </div>
          <?php elseif (isset($_GET['error']) && $_GET['error'] === 'is_holiday'): ?>
            <div class="alert alert-warning mb-4" style="background:rgba(255,193,7,0.1);border:1px solid rgba(255,193,7,0.2);color:#ffd54f;font-size:0.9rem;border-radius:12px;padding:15px;">
              <i class="fas fa-calendar-times me-2"></i>
              <strong>Wisata Tutup!</strong> Destinasi sedang tutup/libur pada tanggal tersebut. Silakan pilih tanggal lain.
            </div>
          <?php endif; ?>

          <h5 style="color:var(--white);font-family:'Playfair Display',serif;font-size:1.4rem;margin-bottom:28px;">Form Pemesanan Tiket</h5>

          <form ref="bookingForm" action="controllers/process/booking_process.php" method="POST" @submit.prevent="submitBooking" class="row g-3">
            <!-- Hidden fields -->
            <input type="hidden" name="total_harga" :value="totalHargaRaw">
            <input type="hidden" name="harga_satuan" :value="unitPrice">
            <input type="hidden" name="jenis_hari" :value="isWeekend ? 'Weekend' : 'Weekday'">

            <!-- Row 1: Nama & WhatsApp -->
            <div class="col-md-6">
              <div class="form-label-custom">Nama Lengkap</div>
              <input type="text" name="nama" v-model="nama" 
                class="form-control-custom" 
                :class="{ 'is-invalid-custom': errors.nama }"
                placeholder="Masukkan nama lengkap" 
                required />
              <div v-if="errors.nama" class="input-error-msg">
                <i class="fas fa-exclamation-circle me-1"></i> {{ errors.nama }}
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-label-custom">No. WhatsApp</div>
              <input type="tel" name="whatsapp" v-model="whatsapp"
                class="form-control-custom"
                :class="{ 'is-invalid-custom': errors.whatsapp }"
                placeholder="08xxxxxxxxxx"
                inputmode="numeric"
                required />
              <div v-if="errors.whatsapp" class="input-error-msg">
                <i class="fas fa-exclamation-circle me-1"></i> {{ errors.whatsapp }}
              </div>
            </div>

            <!-- Row 2: Destinasi (full width) -->
            <div class="col-12">
              <div class="form-label-custom">Destinasi Wisata</div>
              <select name="destinasi" v-model="destinasi" class="form-control-custom" @change="updatePrice" required>
                <option value="" disabled>Pilih destinasi wisata</option>
                <?php if (!empty($wisataList)): ?>
                  <?php foreach ($wisataList as $w): ?>
                    <option value="<?php echo htmlspecialchars($w['nama_wisata']); ?>">
                      <?php echo htmlspecialchars($w['nama_wisata']); ?>
                    </option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <option value="Bukit Panorama Fajar">Bukit Panorama Fajar</option>
                  <option value="Jalur Hutan Tropis">Jalur Hutan Tropis</option>
                  <option value="Area Perkemahan Lestari">Area Perkemahan Lestari</option>
                <?php endif; ?>
              </select>
            </div>

            <!-- Row 3: Jumlah Pengunjung & Total Harga (berdampingan) -->
            <div class="col-md-6">
              <div class="form-label-custom">Jumlah Pengunjung</div>
              <div class="qty-input-wrap">
                <button type="button" class="qty-btn" @click="jumlah > 1 ? jumlah-- : null">
                  <i class="fas fa-minus"></i>
                </button>
                <input type="number" name="jumlah" v-model.number="jumlah" class="qty-field" min="1" required />
                <button type="button" class="qty-btn" @click="jumlah++">
                  <i class="fas fa-plus"></i>
                </button>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-label-custom">Total Bayar</div>
              <div class="total-summary-card">
                <div class="summary-top">Rp {{ totalHarga }}</div>
                <div class="summary-sub">{{ jumlah }} orang × Rp {{ unitPrice.toLocaleString('id-ID') }}</div>
              </div>
            </div>

            <!-- Row 4: Kalender (muncul setelah destinasi dipilih) -->
            <div class="col-12" v-if="destinasi">
              <div class="form-label-custom">Pilih Tanggal Kunjungan</div>
              <div class="calendar-container position-relative">
                <div class="calendar-loading" v-if="isLoadingAvailability">
                  <div class="spinner-border spinner-border-sm" role="status"></div>
                </div>
                <div class="calendar-header">
                  <button type="button" class="calendar-nav-btn" @click="changeMonth(-1)" :disabled="isLoadingAvailability">
                    <i class="fas fa-chevron-left"></i>
                  </button>
                  <div class="calendar-title">{{ calendarTitle }}</div>
                  <button type="button" class="calendar-nav-btn" @click="changeMonth(1)" :disabled="isLoadingAvailability">
                    <i class="fas fa-chevron-right"></i>
                  </button>
                </div>
                <div class="calendar-grid">
                  <div class="calendar-day-name">Min</div>
                  <div class="calendar-day-name">Sen</div>
                  <div class="calendar-day-name">Sel</div>
                  <div class="calendar-day-name">Rab</div>
                  <div class="calendar-day-name">Kam</div>
                  <div class="calendar-day-name">Jum</div>
                  <div class="calendar-day-name">Sab</div>
                  <div v-for="(d, index) in calendarDays" :key="index"
                       class="calendar-date"
                       :class="[d.status, { 'selected': tanggal === d.date, 'empty': !d.day }]"
                       @click="selectDate(d)">
                    <span class="date-num" v-if="d.day">{{ d.day }}</span>
                    <div class="quota-dot" v-if="d.day && !d.isPast && !d.isHoliday"></div>
                  </div>
                </div>
                <div class="calendar-legend">
                  <div class="legend-item"><div class="legend-box" style="background:var(--sage)"></div><span>Tersedia</span></div>
                  <div class="legend-item"><div class="legend-box" style="background:var(--dawn)"></div><span>Hampir Habis</span></div>
                  <div class="legend-item"><div class="legend-box" style="background:#ff4d5e"></div><span>Penuh</span></div>
                  <div class="legend-item"><div class="legend-box" style="background:#6c757d"></div><span>Tutup</span></div>
                </div>
              </div>

              <input type="hidden" name="tanggal" v-model="tanggal" required />
              <div v-if="tanggal" class="mt-2 mb-1">
                <div style="color:var(--dawn);font-size:0.85rem;font-weight:600;">
                  <i class="fas fa-calendar-check me-2"></i>Terpilih: {{ new Date(tanggal).toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'}) }}
                </div>
                <div v-if="selectedDateHoliday" class="mt-2 p-2" style="background:rgba(220,53,69,0.1);border-radius:6px;color:#ff8e98;font-size:0.78rem;">
                  <i class="fas fa-exclamation-triangle me-1"></i> Maaf, destinasi ini sedang <strong>TUTUP/LIBUR</strong> pada tanggal tersebut.
                </div>
              </div>
            </div>

            <!-- Row 5: Catatan -->
            <div class="col-12">
              <div class="form-label-custom">Catatan Khusus (Opsional)</div>
              <textarea name="catatan" v-model="catatan" class="form-control-custom" rows="3" placeholder="Kebutuhan khusus, pertanyaan, dll..."></textarea>
            </div>

            <!-- Row 6: Submit -->
            <div class="col-12 mt-2">
              <button type="submit" class="btn-primary-cta w-100" style="justify-content:center;padding:16px;">
                <i class="fas fa-ticket-alt"></i> Pesan & Dapatkan E-Ticket
              </button>
              <p style="font-size:0.75rem;color:rgba(255,255,255,0.3);text-align:center;margin-top:14px;">
                <i class="fas fa-lock me-1"></i> Data Anda aman · E-ticket dikirim via WhatsApp & Email
              </p>
            </div>

            <!-- Hidden wisata data for Vue -->
            <script type="application/json" id="wisata-data">
              <?php
                $dataVue = [];
                if (!empty($wisataList)) {
                  foreach ($wisataList as $w) {
                    $dataVue[$w['nama_wisata']] = [
                      'weekday' => (float)$w['harga_weekday'],
                      'weekend' => (float)$w['harga_weekend']
                    ];
                  }
                }
                echo json_encode($dataVue);
              ?>
            </script>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>