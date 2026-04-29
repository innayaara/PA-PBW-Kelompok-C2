<?php
require_once __DIR__ . '/../config/koneksi.php';

class AuthController
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
        $this->startSession();
    }

    private function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function redirectIfLoggedIn($redirect = 'index.php')
    {
        if (isset($_SESSION['admin_id'])) {
            header("Location: $redirect");
            exit();
        }
    }

    public function requireLogin($redirect = 'login.php?error=unauthorized')
    {
        if (!isset($_SESSION['admin_id'])) {
            header("Location: $redirect");
            exit();
        }

        // Session timeout 10 menit
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > 600) {
            session_unset();
            session_destroy();
            header("Location: $redirect");
            exit();
        }

        $_SESSION['LAST_ACTIVITY'] = time();

        // Regenerate session ID tiap 10 menit
        if (!isset($_SESSION['CREATED'])) {
            $_SESSION['CREATED'] = time();
        } elseif (time() - $_SESSION['CREATED'] > 600) {
            session_regenerate_id(true);
            $_SESSION['CREATED'] = time();
        }
    }

    public function authenticate($username, $password)
    {
        $username = trim($username);
        $password = trim($password);

        $stmt = $this->conn->prepare("SELECT * FROM admin WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $admin = $result->fetch_assoc();

            if (password_verify($password, $admin['password'])) {
                return $admin;
            }
        }

        return false;
    }

    public function setAdminSession($admin)
    {
        session_regenerate_id(true);

        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_nama'] = $admin['nama'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['LAST_ACTIVITY'] = time();
        $_SESSION['CREATED'] = time();
    }

    public function logout($redirect = 'login.php')
    {
        session_unset();
        session_destroy();

        header("Location: $redirect");
        exit();
    }
}
?>
