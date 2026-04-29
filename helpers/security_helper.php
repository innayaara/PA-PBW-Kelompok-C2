<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

function csrf_token()
{
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token)
{
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function normalize_whatsapp($whatsapp)
{
    return preg_replace('/[^0-9]/', '', (string) $whatsapp);
}

function valid_whatsapp($whatsapp)
{
    $digits = normalize_whatsapp($whatsapp);
    return strlen($digits) >= 9 && strlen($digits) <= 15;
}

function valid_nama_pengunjung($nama)
{
    $nama = trim($nama);

    if (strlen($nama) < 2 || strlen($nama) > 100) {
        return false;
    }

    return preg_match("/^[a-zA-ZÀ-ÿ\s'.-]+$/u", $nama);
}

function simple_rate_limit($key, $maxAttempts, $seconds)
{
    if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
        session_start();
    }

    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = [
            'count' => 0,
            'start' => time()
        ];
    }

    if ((time() - $_SESSION[$key]['start']) > $seconds) {
        $_SESSION[$key] = [
            'count' => 0,
            'start' => time()
        ];
    }

    $_SESSION[$key]['count']++;

    return $_SESSION[$key]['count'] <= $maxAttempts;
}

function reset_rate_limit($key)
{
    unset($_SESSION[$key]);
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}