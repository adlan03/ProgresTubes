<?php
// config.php — konfigurasi sederhana tanpa database

// ====== PATH & URL BASE ======
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__);
}

if (!defined('BASE_URL')) {
    // Deteksi otomatis dari request; override secara manual bila perlu.
    $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    $scheme  = $isHttps ? 'https' : 'http';

    // Ketika berjalan di CLI (misal artisan/cron), fallback ke localhost.
    if (isset($_SERVER['HTTP_HOST'])) {
        $host   = $_SERVER['HTTP_HOST'];
        $base   = trim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/\\');
        $prefix = $base === '' ? '' : $base . '/';
        define('BASE_URL', $scheme . '://' . $host . '/' . $prefix);
    } else {
        // Ubah nilai berikut sesuai direktori root proyek saat lokal
        define('BASE_URL', 'http://localhost/');
    }
}

// ====== KONEKSI DATABASE ======
// Aplikasi kini berjalan tanpa database. Variabel $mysqli diset ke null
// agar file lain yang meng-include config tetap menemukan variabel ini
// tanpa memanggil fungsi mysqli apa pun.
$mysqli = null;
