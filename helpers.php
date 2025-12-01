<?php
/**
 * Helpers umum untuk menjaga keterbacaan kode.
 */

declare(strict_types=1);

const DEFAULT_SETTINGS = [
    'harga' => 35000.0,
    'beras' => 3.5,
    'jagung' => 2.0,
    'locked' => 0,
];

const INFAQ_VALUE = 15000;

/**
 * Pastikan pengguna sudah login sebelum melanjutkan.
 */
function require_login(): void
{
    if (!empty($_SESSION['username'])) {
        return;
    }

    echo "<script>alert('Anda harus login terlebih dahulu');</script>";
    echo "<meta http-equiv='refresh' content='0;url=login.php'>";
    exit;
}

/**
 * Ambil setting global tanpa database (gunakan nilai default).
 */
function fetch_settings($db = null): array
{
    return DEFAULT_SETTINGS;
}

/**
 * Redirect sederhana dengan optional query string.
 */
function redirect_to(string $path, ?string $query = null): never
{
    $location = $path;
    if (!empty($query)) {
        $location .= (strpos($path, '?') === false ? '?' : '&') . $query;
    }
    header("Location: {$location}");
    exit;
}

/**
 * Format angka ke rupiah.
 */
function format_rupiah(float $value): string
{
    return number_format($value, 0, ',', '.');
}

/**
 * Ambil nilai kunci dari array setting dengan fallback default.
 */
function setting_value(array $settings, string $key): float
{
    return isset($settings[$key]) ? (float)$settings[$key] : (float)DEFAULT_SETTINGS[$key];
}
