# PA-PBW

# 🌿 Bukit Fajar Lestari — Sistem Informasi Wisata Berbasis Web

<p align="center">
  <b>Platform digital untuk promosi wisata, dan layanan pemesanan tiket secara online.</b>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-Native-blue?logo=php" />
  <img src="https://img.shields.io/badge/MySQL-Database-orange?logo=mysql" />
  <img src="https://img.shields.io/badge/Platform-Web-brightgreen?logo=googlechrome" />
  <img src="https://img.shields.io/badge/Status-Active-success" />
</p>

---

## 🌍 Deskripsi Aplikasi

**Bukit Fajar Lestari** adalah sistem informasi wisata berbasis web yang dikembangkan untuk mendukung promosi digital serta pengelolaan data wisata secara terpusat. Website ini dirancang sebagai solusi atas keterbatasan akses informasi terkait destinasi wisata Bukit Fajar Lestari yang berada di Tenggarong, Kutai Kartanegara. Melalui platform ini, masyarakat dapat memperoleh informasi lengkap mengenai objek wisata, fasilitas, galeri, testimoni, serta melakukan pemesanan tiket secara online.

Dengan adanya website ini, Bukit Fajar Lestari dapat memperluas jangkauan promosi, meningkatkan eksposur wisata, dan mendukung transformasi digital sektor pariwisata lokal.

---

## ✨ Fitur Website

### 👥 Pengunjung
| Fitur | Keterangan |
|---|---|
| Informasi Wisata | Menampilkan detail objek wisata, fasilitas, dan daya tarik |
| Galeri Foto | Dokumentasi visual destinasi wisata |
| Testimoni | Ulasan dan pengalaman pengunjung |
| Pesan Tiket | Pemesanan tiket secara online |
| Cek Booking | Memeriksa status pemesanan tiket |
| Kontak | Informasi komunikasi dan lokasi |

---

### 🔧 Admin
| Fitur | Keterangan |
|---|---|
| Login Admin | Sistem autentikasi untuk pengelola |
| Dashboard | Ringkasan data pengelolaan website |
| Kelola Wisata | CRUD data objek wisata |
| Kelola Galeri | Menambah / menghapus dokumentasi |
| Kelola Booking | Memantau dan mengatur data pemesanan |
| Kelola Konten | Update informasi website secara real-time |

---

## 🧩 Struktur Sistem

Aplikasi ini dibangun menggunakan arsitektur **MVC (Model-View-Controller)** menggunakan PHP Native. 

### Front-End
- HTML5  
- CSS3  
- JavaScript (dengan Vanilla JS & Vue.js ringan)
- Bootstrap (untuk panel admin)

### Back-End
- PHP Native (Versi 8.x)

### Database
- MySQL  

## 🛡️ Fitur Keamanan yang Diterapkan

Proyek ini tidak hanya mementingkan fungsionalitas, tetapi juga telah dibekali dengan patch keamanan untuk mencegah eksploitasi umum:
- **Proteksi CSRF (Cross-Site Request Forgery):** Setiap pengiriman form divalidasi menggunakan token CSRF.
- **Rate Limiting:** Mencegah serangan *Brute Force* pada halaman login admin dan *Spamming* pada halaman pengecekan riwayat E-Ticket.
- **Validasi Input Terpusat:** Menggunakan `htmlspecialchars` untuk mencegah **XSS (Cross-Site Scripting)**, serta pembatasan karakter ketat pada input spesifik (seperti No. WhatsApp dan Nama).
- **Akses Token E-Ticket:** Halaman E-ticket dan Riwayat Booking diamankan menggunakan kombinasi Kode Booking dan Token unik yang di-generate server, mencegah akses data pengunjung oleh pihak yang tidak bertanggung jawab.
- **Validasi Transaksi Backend:** Harga akhir tiket dihitung ulang di server berdasarkan *rules* database (Weekday/Weekend), bukan mempercayai input POST dari klien.

---

## 🛠️ Teknologi yang Digunakan

| Teknologi | Kegunaan |
|---|---|
| **PHP** | Logika aplikasi server-side dan API |
| **MySQL** | Penyimpanan data relasional |
| **HTML/CSS** | Struktur dan desain antarmuka pengguna |
| **JavaScript / Vue.js** | Interaksi dinamis dan validasi form di sisi klien |
| **Bootstrap** | Framework CSS untuk mempercepat pembuatan antarmuka Admin |
| **PHPMailer** | Library untuk pengiriman email notifikasi otomatis |
| **XAMPP / Laragon** | Local server development |
| **InfinityFree** | Hosting deployment |

---

## 🚀 Panduan Instalasi (Localhost)

Berikut adalah langkah-langkah untuk menjalankan aplikasi ini secara lokal:

1. **Clone Repository**
   ```bash
   git clone https://github.com/username/PA-PBW-Kelompok-C2.git
   ```
2. **Pindahkan ke Server Lokal**
   Pindahkan folder project ke direktori `htdocs` (jika menggunakan XAMPP) atau `www` (jika menggunakan Laragon).
3. **Impor Database**
   - Buka phpMyAdmin (biasanya di `http://localhost/phpmyadmin`)
   - Buat database baru dengan nama `bukit_fajar_lestari`
   - Pilih menu **Import** dan unggah file `bukit_fajar_lestari.sql` yang ada di root folder project.
4. **Konfigurasi Koneksi**
   Pastikan konfigurasi di `config/koneksi.php` sudah sesuai dengan kredensial database lokal Anda (biasanya user: `root` dan password kosong).
5. **Jalankan Aplikasi**
   Buka browser dan akses URL: `http://localhost/PA-PBW-Kelompok-C2` (atau sesuai nama folder Anda).

---

## 🔐 Kredensial Login Admin (Demo)

Gunakan kredensial berikut untuk mencoba fitur admin (Panel Pengelola):

- **URL:** `http://localhost/.../panel-pengelola/login.php`
- **Username:** `admin` (sesuaikan dengan isi database)
- **Password:** `admin123` (sesuaikan dengan isi database)

---

## 👥 Anggota Kelompok (C2)

| NIM | Nama | Peran |
|---|---|---|
| `NIM_1` | Nama Anggota 1 | Fullstack / Backend |
| `NIM_2` | Nama Anggota 2 | Frontend / UI/UX |
| `NIM_3` | Nama Anggota 3 | Database / Tester |

> **Catatan:** Silakan sesuaikan nama, NIM, dan peran di atas dengan anggota kelompok aslinya.

---

## 📸 Tampilan Antarmuka (Screenshots)

*(Opsional: Tambahkan screenshot aplikasi kamu di sini agar README terlihat lebih profesional)*

<details>
<summary><b>Klik untuk melihat Screenshot</b></summary>
<br>

### Halaman Utama
*(Contoh cara memasang gambar: ganti link di bawah dengan gambar asli yang di-upload ke GitHub atau folder repo)*  
`<img src="assets/images/screenshot-home.png" width="600">`

### Panel Admin (Dashboard)
`<img src="assets/images/screenshot-admin.png" width="600">`

</details>

---

## 📁 Struktur Direktori

```text
bukit-fajar-lestari/
├── assets/         # File statis (CSS, JS, Images)
├── config/         # Konfigurasi database (koneksi.php)
├── controllers/    # Logika aplikasi (Controller - MVC)
│   └── process/    # Pemrosesan form (POST requests)
├── helpers/        # Fungsi bantuan & library external (PHPMailer)
├── models/         # Interaksi dan query database (Model - MVC)
├── pages/          # Halaman fungsional khusus (E-ticket, Riwayat)
├── panel-pengelola/# Halaman Panel Admin (Dashboard & CRUD)
└── views/          # Komponen UI / Tampilan utama (View - MVC)
```