<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers.php';

require_login();

$setting = fetch_settings($mysqli);
$type = $_GET['type'] ?? 'summary';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Export <?= htmlspecialchars($type); ?> Dinonaktifkan</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>
    <div class="container" style="padding:40px 20px;">
        <div class="card" style="max-width:640px;margin:0 auto;">
            <h2>Export Tidak Tersedia</h2>
            <p>Fitur ekspor ke Excel/CSV dinonaktifkan karena aplikasi berjalan tanpa koneksi database.</p>
            <p>Pengaturan yang digunakan saat ini:</p>
            <ul>
                <li>Harga uang per anggota: Rp <?= format_rupiah(setting_value($setting, 'harga')); ?></li>
                <li>Beras per anggota: <?= setting_value($setting, 'beras'); ?> kg</li>
                <li>Jagung per anggota: <?= setting_value($setting, 'jagung'); ?> kg</li>
            </ul>
            <p><a class="button" href="index.php?page=lihat_data">← Kembali</a></p>
        </div>
    </div>
</body>
</html>
