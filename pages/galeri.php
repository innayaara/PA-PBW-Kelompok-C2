<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/GaleriModel.php';

$galeriModel  = new GaleriModel($conn);
$kategoriAktif = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';

$galeriList   = $galeriModel->getAllActiveGaleri($kategoriAktif);
$kategoriList = $galeriModel->getActiveCategories();
$totalFoto    = count($galeriList);

require_once __DIR__ . '/../views/public/pages/galeri_page.php';
