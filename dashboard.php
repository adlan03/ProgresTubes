<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ob_start();

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/family_service.php';

require_login();

$infoMessage = null;

// Semua aksi tulis dinonaktifkan karena tidak ada database.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $infoMessage = 'Fitur penyimpanan dinonaktifkan karena tidak ada database.';
}

/* ----- load setting untuk UI & JS ----- */
$setting = fetch_settings($mysqli);
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Dashboard - Input Keluarga (Tanpa Database)</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>

<body>
    <header>Dashboard</header>

    <div class="container">
        <aside>
            <ul class="menu">
                <li><a href="index.php" class="active">Dashboard</a></li>
                <li><a href="index.php?page=lihat_data">Lihat / Edit Data</a></li>
                <li><a href="logout.php">Keluar</a></li>
            </ul>
        </aside>

        <section class="main">
            <h2>Input Data Keluarga</h2>

            <?php if ($infoMessage): ?>
                <div class="card" style="background:#fff3cd;border:1px solid #ffeeba;color:#856404;">
                    <?= htmlspecialchars($infoMessage); ?>
                </div>
            <?php endif; ?>

            <p class="muted">Database tidak terhubung. Form di bawah hanya untuk tampilan dan tidak akan menyimpan data.</p>

            <!-- setting -->
            <form method="post" class="card setting-form">
                <h4>Harga & Barang (tetap)</h4>
                <label>Harga Uang per anggota (Rp)
                    <input type="number" name="harga" step="100" value="<?= htmlspecialchars((string)$setting['harga']) ?>" readonly>
                </label>
                <label>Beras (kg per anggota)
                    <input type="number" name="beras" step="0.1" value="<?= htmlspecialchars((string)$setting['beras']) ?>" readonly>
                </label>
                <label>Jagung (kg per anggota)
                    <input type="number" name="jagung" step="0.1" value="<?= htmlspecialchars((string)$setting['jagung']) ?>" readonly>
                </label>
                <div class="row">
                    <button type="submit" name="save_setting" disabled>Simpan Setting</button>
                    <button type="submit" name="lock" disabled>🔒 Kunci</button>
                </div>
            </form>

            <hr>

            <!-- form keluarga -->
            <form method="post" id="formKeluarga" class="card">
                <table id="tabelKeluarga">
                    <thead>
                        <tr>
                            <th>Nama Anggota</th>
                            <th>JK</th>
                            <th>Uang<br><input type="checkbox" id="checkAllUang"></th>
                            <th>Beras<br><input type="checkbox" id="checkAllBeras"></th>
                            <th>Jagung<br><input type="checkbox" id="checkAllJagung"></th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="nama[]" required></td>
                            <td>
                                <label><input type="radio" name="jk[0]" value="L">L</label>
                                <label><input type="radio" name="jk[0]" value="P">P</label>
                            </td>
                            <td><input type="checkbox" class="uang" name="uang[0]"></td>
                            <td><input type="checkbox" class="beras" name="beras[0]"></td>
                            <td><input type="checkbox" class="jagung" name="jagung[0]"></td>
                            <td><button type="button" class="btn-del" disabled>Hapus</button></td>
                        </tr>
                    </tbody>
                </table>

                <div class="row">
                    <button type="button" id="tambah">+ Tambah Anggota</button>
                    <label style="margin-left:12px;">
                        <input type="checkbox" id="infaq" name="infaq"> Tambahkan Infaq (Rp 15.000)
                    </label>
                </div>

                <div class="totals card-small">
                    <p id="totalUang">Total Uang: Rp 0</p>
                    <p id="totalBeras">Total Beras: 0 kg</p>
                    <p id="totalJagung">Total Jagung: 0 kg</p>
                    <p id="totalInfaq">Total Infaq: Rp 0</p>
                </div>

                <div class="calc card-small">
                    <label>Uang Diterima (Rp): <input type="number" id="uangDiterima" step="500"></label>
                    <label>Kembalian: <input type="text" id="kembalian" readonly></label>
                </div>

                <div class="row">
                    <button type="submit" name="simpan">💾 Simpan Data (Dinonaktifkan)</button>
                </div>
            </form>

        </section>
    </div>

    <footer>&copy; 2024 Sistem Infaq Keluarga</footer>

    <script>
        const SETTING = {
            harga: <?= json_encode((float)$setting['harga']) ?>,
            beras: <?= json_encode((float)$setting['beras']) ?>,
            jagung: <?= json_encode((float)$setting['jagung']) ?>,
            infaqValue: 15000
        };
    </script>
    <script src="<?= BASE_URL ?>assets/js/keluarga.js"></script>
</body>

</html>
