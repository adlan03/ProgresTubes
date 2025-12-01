<?php
declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

/**
 * Kumpulan fungsi layanan untuk operasi keluarga dan pengaturan.
 * Semua fungsi dibuat no-op agar aplikasi tetap bisa berjalan tanpa database.
 */

function is_settings_locked($db = null): bool
{
    return false;
}

function update_settings($db = null, int $harga = 0, float $beras = 0.0, float $jagung = 0.0): void
{
    // fitur dinonaktifkan
}

function set_setting_lock($db = null, bool $locked = false): void
{
    // fitur dinonaktifkan
}

function collect_members_from_post(array $post): array
{
    $members = [];
    if (empty($post['nama']) || !is_array($post['nama'])) {
        return $members;
    }

    foreach ($post['nama'] as $i => $nama) {
        $nama = trim((string)$nama);
        if ($nama === '') {
            continue;
        }

        $members[] = [
            'nama'   => $nama,
            'jk'     => $post['jk'][$i] ?? '',
            'uang'   => isset($post['uang'][$i]) ? 1 : 0,
            'beras'  => isset($post['beras'][$i]) ? 1 : 0,
            'jagung' => isset($post['jagung'][$i]) ? 1 : 0,
        ];
    }

    return $members;
}

function insert_family($db = null, string $kepala = '', int $infaq = 0): int
{
    return 0;
}

function insert_members($db = null, int $familyId = 0, array $members = []): void
{
    // fitur dinonaktifkan
}

function save_family($db = null, string $kepala = '', int $infaq = 0, array $members = []): void
{
    // fitur dinonaktifkan
}

function fetch_family_members($db = null, int $familyId = 0): array
{
    return [];
}

function fetch_all_families($db = null): array
{
    return [];
}

function delete_family($db = null, int $familyId = 0): void
{
    // fitur dinonaktifkan
}

function reset_all_families($db = null): void
{
    // fitur dinonaktifkan
}

function replace_family($db = null, int $familyId = 0, int $infaq = 0, array $members = []): void
{
    // fitur dinonaktifkan
}

function calculate_family_totals(array $family, array $setting): array
{
    $totals = [
        'uang' => 0.0,
        'beras' => 0.0,
        'jagung' => 0.0,
        'infaq' => (int)($family['infaq'] ?? 0),
    ];

    foreach ($family['anggota'] ?? [] as $member) {
        if (!empty($member['uang'])) {
            $totals['uang'] += setting_value($setting, 'harga');
        }
        if (!empty($member['beras'])) {
            $totals['beras'] += setting_value($setting, 'beras');
        }
        if (!empty($member['jagung'])) {
            $totals['jagung'] += setting_value($setting, 'jagung');
        }
    }

    return $totals;
}

function calculate_overall_totals(array $families, array $setting): array
{
    $overall = ['uang' => 0.0, 'beras' => 0.0, 'jagung' => 0.0, 'infaq' => 0];
    foreach ($families as $family) {
        $familyTotals = calculate_family_totals($family, $setting);
        $overall['uang'] += $familyTotals['uang'];
        $overall['beras'] += $familyTotals['beras'];
        $overall['jagung'] += $familyTotals['jagung'];
        $overall['infaq'] += $familyTotals['infaq'];
    }

    return $overall;
}
