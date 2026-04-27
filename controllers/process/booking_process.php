<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../models/BookingModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingModel = new BookingModel($conn);

    $nama      = isset($_POST['nama']) ? $_POST['nama'] : '';
    $whatsapp  = isset($_POST['whatsapp']) ? $_POST['whatsapp'] : '';
    $tanggal   = isset($_POST['tanggal']) ? $_POST['tanggal'] : '';
    $jumlah    = isset($_POST['jumlah']) ? (int) $_POST['jumlah'] : 0;
    $destinasi = isset($_POST['destinasi']) ? $_POST['destinasi'] : '';
    $catatan   = isset($_POST['catatan']) ? $_POST['catatan'] : '';
    $harga_satuan = isset($_POST['harga_satuan']) ? (float) $_POST['harga_satuan'] : 0;
    $total_harga  = isset($_POST['total_harga']) ? (float) $_POST['total_harga'] : 0;
    $jenis_hari   = isset($_POST['jenis_hari']) ? $_POST['jenis_hari'] : '';

    // Bersihkan nomor WA: hanya simpan angka
    $whatsappDigits = preg_replace('/[^0-9]/', '', $whatsapp);
    if (strlen($whatsappDigits) < 9 || strlen($whatsappDigits) > 15) {
        header('Location: ../../index.php?section=booking&error=invalid_whatsapp');
        exit;
    }

    $kode_booking = $bookingModel->generateBookingCode($whatsapp);

    $data = [
        'kode_booking' => $kode_booking,
        'nama' => $nama,
        'whatsapp' => $whatsapp,
        'tanggal' => $tanggal,
        'jumlah' => $jumlah,
        'destinasi' => $destinasi,
        'catatan' => $catatan,
        'harga_satuan' => $harga_satuan,
        'total_harga' => $total_harga,
        'jenis_hari' => $jenis_hari
    ];

    // -- VALIDASI HARI LIBUR --
    $destEscaped = mysqli_real_escape_string($conn, $destinasi);
    $tanggalEscaped = mysqli_real_escape_string($conn, $tanggal);
    $resL = mysqli_query($conn, "SELECT id FROM wisata_libur WHERE destinasi = '$destEscaped' AND tanggal = '$tanggalEscaped' LIMIT 1");
    if (mysqli_num_rows($resL) > 0) {
        header("Location: ../../index.php?error=is_holiday#booking");
        exit();
    }

    // -- VALIDASI KUOTA --
    require_once __DIR__ . '/../../models/WisataModel.php';
    $wisataModel = new WisataModel($conn);
    
    // Cari data wisata untuk ambil kuota_harian
    $destEscaped = mysqli_real_escape_string($conn, $destinasi);
    $resW = mysqli_query($conn, "SELECT kuota_harian FROM wisata WHERE nama_wisata = '$destEscaped' LIMIT 1");
    $dataW = mysqli_fetch_assoc($resW);

    if ($dataW && $dataW['kuota_harian'] > 0) {
        $kuotaHarian = (int)$dataW['kuota_harian'];
        $terpakai = $bookingModel->getTicketCountByDate($destinasi, $tanggal);
        
        if (($terpakai + $jumlah) > $kuotaHarian) {
            $sisa = $kuotaHarian - $terpakai;
            header("Location: ../../index.php?error=quota_full&left=" . $sisa . "#booking");
            exit();
        }
    }
    // --------------------

    if ($bookingModel->createBooking($data)) {
        header("Location: ../../pages/eticket.php?kode=" . urlencode($kode_booking));
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    header("Location: ../../index.php");
    exit();
}
?>
