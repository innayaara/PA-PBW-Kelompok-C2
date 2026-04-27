<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/PengaturanTampilanModel.php';
require_once __DIR__ . '/../models/WisataModel.php';
require_once __DIR__ . '/../models/GaleriModel.php';

class HomeController
{
    public function index()
    {
        global $conn;

        $pengaturanModel = new PengaturanTampilanModel($conn);
        $wisataModel     = new WisataModel($conn);
        $galeriModel     = new GaleriModel($conn);

        $setting = $pengaturanModel->getFirstSetting();

        // HERO
        $heroImage       = !empty($setting['hero_image']) ? $setting['hero_image'] : 'hero-default.jpg';
        $heroEyebrow     = !empty($setting['hero_eyebrow']) ? $setting['hero_eyebrow'] : 'Ekowisata Alam · Tenggarong, Kalimantan Timur';
        $heroTitleMain   = !empty($setting['hero_title_main']) ? $setting['hero_title_main'] : 'Temukan';
        $heroTitleAccent = !empty($setting['hero_title_accent']) ? $setting['hero_title_accent'] : 'Ketenangan';
        $heroTitleBottom = !empty($setting['hero_title_bottom']) ? $setting['hero_title_bottom'] : 'di Bukit Fajar';
        $heroSubtitle    = !empty($setting['hero_subtitle']) ? $setting['hero_subtitle'] : 'Kawasan ekowisata alam yang dikelola bersama oleh KTH dan Pokdarwis Fajar Lestari.';
        $heroStat1Num    = !empty($setting['hero_stat_1_num']) ? $setting['hero_stat_1_num'] : '12+';
        $heroStat1Label  = !empty($setting['hero_stat_1_label']) ? $setting['hero_stat_1_label'] : 'Destinasi Wisata';
        $heroStat2Num    = !empty($setting['hero_stat_2_num']) ? $setting['hero_stat_2_num'] : '850';
        $heroStat2Label  = !empty($setting['hero_stat_2_label']) ? $setting['hero_stat_2_label'] : 'Ha Kawasan';
        $heroStat3Num    = !empty($setting['hero_stat_3_num']) ? $setting['hero_stat_3_num'] : '4.9';
        $heroStat3Label  = !empty($setting['hero_stat_3_label']) ? $setting['hero_stat_3_label'] : 'Rating Pengunjung';
        $heroStat4Num    = !empty($setting['hero_stat_4_num']) ? $setting['hero_stat_4_num'] : '2019';
        $heroStat4Label  = !empty($setting['hero_stat_4_label']) ? $setting['hero_stat_4_label'] : 'Tahun Berdiri';
        $heroImagePath   = 'assets/images/galeri/' . $heroImage;

        // ABOUT
        $aboutImage        = !empty($setting['about_image']) ? $setting['about_image'] : 'about-default.jpg';
        $aboutBadgeNum     = !empty($setting['about_badge_num']) ? $setting['about_badge_num'] : '850';
        $aboutBadgeLabel   = !empty($setting['about_badge_label']) ? $setting['about_badge_label'] : 'Hektare Kawasan';
        $aboutSectionLabel = !empty($setting['about_section_label']) ? $setting['about_section_label'] : 'Tentang Kawasan';
        $aboutTitle        = !empty($setting['about_title']) ? $setting['about_title'] : 'Kawasan Ekowisata Bukit Fajar Lestari';
        $aboutDescription  = !empty($setting['about_description']) ? $setting['about_description'] : 'Bukit Fajar Lestari adalah kawasan ekowisata yang menawarkan panorama alam, udara segar, dan pengalaman wisata yang autentik.';

        $feature1Icon  = !empty($setting['feature_1_icon']) ? $setting['feature_1_icon'] : 'fas fa-leaf';
        $feature1Title = !empty($setting['feature_1_title']) ? $setting['feature_1_title'] : 'Pelestarian Alam Aktif';
        $feature1Desc  = !empty($setting['feature_1_desc']) ? $setting['feature_1_desc'] : 'Program penghijauan dan budidaya tanaman lokal yang dikelola berkelanjutan.';

        $feature2Icon  = !empty($setting['feature_2_icon']) ? $setting['feature_2_icon'] : 'fas fa-mountain';
        $feature2Title = !empty($setting['feature_2_title']) ? $setting['feature_2_title'] : 'Destinasi Wisata Alam';
        $feature2Desc  = !empty($setting['feature_2_desc']) ? $setting['feature_2_desc'] : 'Berbagai jalur trekking, spot foto, dan area perkemahan tersedia untuk pengunjung.';

        $feature3Icon  = !empty($setting['feature_3_icon']) ? $setting['feature_3_icon'] : 'fas fa-users';
        $feature3Title = !empty($setting['feature_3_title']) ? $setting['feature_3_title'] : 'Pengelolaan Berbasis Komunitas';
        $feature3Desc  = !empty($setting['feature_3_desc']) ? $setting['feature_3_desc'] : 'Dikelola oleh masyarakat lokal yang menjaga kelestarian dan pengalaman wisata terbaik.';

        $aboutImagePath = 'assets/images/galeri/' . $aboutImage;

        // DATA WISATA & GALERI
        $wisataList = $wisataModel->getActiveWisata(6);
        $galeriList = $galeriModel->getActiveGaleri(6);

        // DATA ULASAN (TESTIMONI)
        $ulasanResult = $conn->query("SELECT * FROM ulasan WHERE status = 'approved' ORDER BY tanggal DESC LIMIT 6");
        $ulasanList = [];
        if ($ulasanResult && $ulasanResult->num_rows > 0) {
            while ($row = $ulasanResult->fetch_assoc()) {
                $ulasanList[] = $row;
            }
        }

        require_once __DIR__ . '/../views/public/components/header.php';
        require_once __DIR__ . '/../views/public/components/navbar.php';
        require_once __DIR__ . '/../views/public/components/hero.php';
        require_once __DIR__ . '/../views/public/components/about.php';
        require_once __DIR__ . '/../views/public/components/mitra.php';
        require_once __DIR__ . '/../views/public/components/wisata.php';
        require_once __DIR__ . '/../views/public/components/galeri.php';
        require_once __DIR__ . '/../views/public/components/booking.php';
        require_once __DIR__ . '/../views/public/components/testimoni.php';
        require_once __DIR__ . '/../views/public/components/kontak.php';
        require_once __DIR__ . '/../views/public/components/footer.php';
        require_once __DIR__ . '/../views/public/components/scripts.php';
    }
}
?>
