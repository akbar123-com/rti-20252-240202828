<?php


require 'connect.php';

header('Content-Type: application/json');

if ($pdo === null) {
    echo json_encode(['success' => false, 'message' => 'Database belum terhubung. Import db/schema.sql dulu di phpMyAdmin. Detail: ' . ($dbConnectError ?? '')]);
    exit;
}

$required = ['nama_file', 'label', 'kolom_a', 'kolom_b', 'n', 'uji_dipakai', 'sig_p_value', 'h0_ditolak'];
foreach ($required as $field) {
    if (!isset($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => "Field '$field' tidak ditemukan."]);
        exit;
    }
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO analisis_riwayat
            (nama_file, label, kolom_a, kolom_b, n, mean_a, mean_b,
             shapiro_w, shapiro_p, uji_dipakai, statistik_uji, sig_p_value,
             selisih_persen, cohen_d, h0_ditolak)
        VALUES
            (:nama_file, :label, :kolom_a, :kolom_b, :n, :mean_a, :mean_b,
             :shapiro_w, :shapiro_p, :uji_dipakai, :statistik_uji, :sig_p_value,
             :selisih_persen, :cohen_d, :h0_ditolak)
    ");

    $stmt->execute([
        ':nama_file' => $_POST['nama_file'],
        ':label' => $_POST['label'],
        ':kolom_a' => $_POST['kolom_a'],
        ':kolom_b' => $_POST['kolom_b'],
        ':n' => $_POST['n'],
        ':mean_a' => $_POST['mean_a'] ?? null,
        ':mean_b' => $_POST['mean_b'] ?? null,
        ':shapiro_w' => $_POST['shapiro_w'] ?? null,
        ':shapiro_p' => $_POST['shapiro_p'] ?? null,
        ':uji_dipakai' => $_POST['uji_dipakai'],
        ':statistik_uji' => $_POST['statistik_uji'] ?? null,
        ':sig_p_value' => $_POST['sig_p_value'],
        ':selisih_persen' => $_POST['selisih_persen'] ?? null,
        ':cohen_d' => $_POST['cohen_d'] ?? null,
        ':h0_ditolak' => $_POST['h0_ditolak'] === '1' ? 1 : 0,
    ]);

    echo json_encode(['success' => true, 'message' => 'Tersimpan ke riwayat.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Gagal simpan: ' . $e->getMessage()]);
}