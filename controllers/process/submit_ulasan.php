<?php
require_once '../../config/koneksi.php';
require_once '../../helpers/PHPMailer/src/Exception.php';
require_once '../../helpers/PHPMailer/src/PHPMailer.php';
require_once '../../helpers/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $rating = (int)$_POST['rating'];
    $komentar = trim($_POST['komentar']);
    
    if (empty($nama) || empty($email) || empty($komentar) || $rating < 1 || $rating > 5) {
        header("Location: ../../index.php?error=invalid_input#ulasan");
        exit;
    }

    $token = bin2hex(random_bytes(32));
    $status = 'unverified';

    $stmt = $conn->prepare("INSERT INTO ulasan (nama, email, rating, komentar, status, token_verifikasi) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisss", $nama, $email, $rating, $komentar, $status, $token);
    
    if ($stmt->execute()) {
        $mail = new PHPMailer(true);

        try {
            // Pengaturan SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            // TODO: Ganti dengan Email Gmail kamu
            $mail->Username   = 'emailkamu@gmail.com'; 
            // TODO: Ganti dengan App Password Gmail kamu
            $mail->Password   = 'APP_PASSWORD_GMAIL_KAMU'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom('emailkamu@gmail.com', 'Bukit Fajar Lestari');
            $mail->addAddress($email, $nama);

            $mail->isHTML(true);
            $mail->Subject = 'Verifikasi Ulasan Anda - Bukit Fajar Lestari';
            
            $verifyLink = "http://" . $_SERVER['HTTP_HOST'] . "/pages/verifikasi_ulasan.php?token=" . $token;
            
            $mail->Body = "
                <h3>Halo $nama,</h3>
                <p>Terima kasih telah memberikan ulasan untuk Bukit Fajar Lestari.</p>
                <p>Agar ulasan Anda dapat kami proses, mohon konfirmasi alamat email Anda dengan mengklik tautan di bawah ini:</p>
                <br>
                <a href='$verifyLink' style='background-color:#1b3d28;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;'>Verifikasi Ulasan Saya</a>
                <br><br>
                <p>Jika tautan di atas tidak berfungsi, salin dan tempel URL berikut di browser Anda:</p>
                <p>$verifyLink</p>
                <br>
                <p>Salam hangat,<br>Tim Bukit Fajar Lestari</p>
            ";

            // CEK: Jika email belum dikonfigurasi, bypass pengiriman email agar tidak error di lokal
            if ($mail->Username === 'emailkamu@gmail.com') {
                // Otomatis ubah status jadi pending (seolah-olah sudah diklik link verifikasinya)
                $stmt_update = $conn->prepare("UPDATE ulasan SET status = 'pending' WHERE token_verifikasi = ?");
                $stmt_update->bind_param("s", $token);
                $stmt_update->execute();
                
                // Catat link verifikasi ke error_log jika sewaktu-waktu dibutuhkan untuk testing
                error_log("Link Verifikasi Ulasan (Bypass): " . $verifyLink);
            } else {
                $mail->send();
            }

            header("Location: ../../index.php?success=review_submitted#ulasan");
        } catch (Exception $e) {
            // Jika gagal mengirim email sungguhan, tampilkan pesan error
            header("Location: ../../index.php?error=email_failed#ulasan");
        }
    } else {
        header("Location: ../../index.php?error=db_error#ulasan");
    }
} else {
    header("Location: ../../index.php");
}
?>
