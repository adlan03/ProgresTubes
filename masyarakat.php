<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/family_service.php';

/* --- Ambil setting & data dummy --- */
$setting = fetch_settings($mysqli);
$harga  = setting_value($setting, 'harga');
$berasV = setting_value($setting, 'beras');
$jagungV = setting_value($setting, 'jagung');

// Versi tanpa database: gunakan data contoh agar tampilan tetap hidup.
$families = fetch_all_families($mysqli);
$aggregate = calculate_overall_totals($families, $setting);
$totalFamilies = count($families);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>My Zakat</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/public.css">
</head>

<body>
    <nav class="navbar">
        <div class="container navbar-inner">
            <div class="brand"><span class="dot"></span> My Zakat</div>
            <div class="nav-links">
                <a href="#hero">Beranda</a>
                <a href="#stats">Statistik</a>
                <a href="#data">Data</a>
            </div>
            <?php if (!empty($_SESSION['username'])): ?>
                <a class="cta-nav" href="logout.php">Keluar</a>
            <?php else: ?>
                <a class="cta-nav" href="login.php">Masuk Admin</a>
            <?php endif; ?>
        </div>
    </nav>

    <section class="hero" id="hero">
        <div class="container hero-grid">
            <div>
                <div class="hero-badge">Zakat Lebih Transparant</div>
                <h1>SELAMAT DATANG DI PENCATATAN ZAKAT.</h1>
                <p>Ikuti perkembangan zakat keluarga dan perhatikan transparansi dari pembayaran Zakat di Desa Barugae</p>
                <div class="hero-actions">
                    <button class="btn-primary" onclick="document.getElementById('data').scrollIntoView({behavior:'smooth'});">Lihat Data</button>
                </div>
                <p style="margin-top:18px;color:#4c5b55;">Saat ini tercatat <strong><?= $totalFamilies; ?></strong> keluarga dengan pemantauan real time.</p>
            </div>
            <div class="hero-card">
                <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80" alt="Ilustrasi komunitas berzakat yang harmonis">
            </div>
        </div>
    </section>

    <section id="stats">
        <div class="container">
            <h2>Statistik Zakat Ringkas</h2>
            <p class="muted">Total real-time pencatatan zakat setiap penginputan otomatis terhitung.</p>
            <div class="stats-grid" aria-label="Ringkasan statistik zakat">
                <div class="stat-card">
                    <p class="stat-title">Total Uang</p>
                    <p class="stat-value">Rp <?= format_rupiah($aggregate['uang']); ?></p>
                </div>
                <div class="stat-card">
                    <p class="stat-title">Total Beras</p>
                    <p class="stat-value"><?= number_format($aggregate['beras'], 1); ?> kg</p>
                </div>
                <div class="stat-card">
                    <p class="stat-title">Total Jagung</p>
                    <p class="stat-value"><?= number_format($aggregate['jagung'], 1); ?> kg</p>
                </div>
                <div class="stat-card">
                    <p class="stat-title">Total Infaq</p>
                    <p class="stat-value">Rp <?= format_rupiah($aggregate['infaq']); ?></p>
                </div>
            </div>
        </div>
    </section>

    <section id="data">
        <div class="container">
            <div class="card table-card">
                <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
                    <div>
                        <h2 style="margin:0;">Data Infaq &amp; Zakat</h2>
                        <p class="muted" style="margin:6px 0 0;">Database nonaktif, tabel menampilkan data simulasi.</p>
                    </div>
                    <?php if (!empty($_SESSION['username'])): ?>
                        <div style="color:#4c5b55;">Login sebagai <strong><?= htmlspecialchars($_SESSION['username']); ?></strong></div>
                    <?php endif; ?>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Kepala Keluarga</th>
                                <th>Jumlah Anggota</th>
                                <th>Uang (Rp)</th>
                                <th>Beras (kg)</th>
                                <th>Jagung (kg)</th>
                                <th>Infaq (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($families)): ?>
                                <tr>
                                    <td colspan="6">Belum ada data yang dapat ditampilkan.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($families as $row): ?>
                                    <?php
                                    $memberCount = count($row['anggota'] ?? []);
                                    $familyTotals = calculate_family_totals($row, $setting);
                                    ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($row['kepala']); ?></strong><br>
                                            <small>(+ <?= max(0, $memberCount - 1); ?> anggota)</small>
                                        </td>
                                        <td><?= $memberCount; ?></td>
                                        <td><?= format_rupiah((float)$familyTotals['uang']); ?></td>
                                        <td><?= number_format((float)$familyTotals['beras'], 1); ?></td>
                                        <td><?= number_format((float)$familyTotals['jagung'], 1); ?></td>
                                        <td><?= format_rupiah((float)$familyTotals['infaq']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container footer-grid">
            <div class="footer-brand">My Zakat</div>
            <div>
                <strong></strong>
                <ul class="breakpoints">
                    <li>Desa Barugae Dusun Waepejje </li>
                </ul>
            </div>
            <div>
                <strong>Kontak sekertaris</strong>
                <p style="margin:6px 0 0; color:#c0d5cd;">Email: adlankhalid10@gmail.com. <br> WA: 081245434516</p>
            </div>
        </div>
    </footer>

</body>

</html>
