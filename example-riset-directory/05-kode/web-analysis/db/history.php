<?php
/**
 * history.php
 * -----------
 * Lihat semua riwayat analisis yang pernah disimpan ke database.
 */

require 'connect.php';

$rows = [];
$dbError = null;

if ($pdo === null) {
    $dbError = $dbConnectError ?? 'Database belum terhubung.';
} else {
    try {
        $stmt = $pdo->query("SELECT * FROM analisis_riwayat ORDER BY dibuat_pada DESC");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $dbError = "Tabel belum ada. Import db/schema.sql dulu di phpMyAdmin. (" . $e->getMessage() . ")";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Riwayat Analisis — RTI</title>
<style>
    body { font-family: Segoe UI, Arial, sans-serif; max-width: 1100px; margin: 30px auto; padding: 0 20px; background: #f7f8fa; color: #222; }
    table { border-collapse: collapse; width: 100%; background: #fff; }
    th, td { border: 1px solid #ddd; padding: 8px 10px; text-align: left; font-size: 13px; }
    th { background: #4C72B0; color: white; }
    .error { background: #fdecea; color: #b71c1c; padding: 12px; border-radius: 6px; }
    .sig-yes { color: #1b7a1b; font-weight: bold; }
    .sig-no { color: #999; }
    a { color: #4C72B0; }
</style>
</head>
<body>
<h1>🗂️ Riwayat Analisis</h1>
<p><a href="../index.php">← Kembali ke tool analisis</a></p>

<?php if ($dbError): ?>
    <div class="error">⚠️ <?= htmlspecialchars($dbError) ?></div>
<?php elseif (empty($rows)): ?>
    <p>Belum ada riwayat analisis yang disimpan.</p>
<?php else: ?>
    <table>
        <tr>
            <th>Tanggal</th><th>File</th><th>Variabel</th><th>N</th>
            <th>Uji Dipakai</th><th>Statistik</th><th>Sig.</th><th>Selisih %</th><th>Kesimpulan</th>
        </tr>
        <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= htmlspecialchars($r['dibuat_pada']) ?></td>
            <td><?= htmlspecialchars($r['nama_file']) ?></td>
            <td><?= htmlspecialchars($r['label']) ?> (<?= htmlspecialchars($r['kolom_a']) ?> vs <?= htmlspecialchars($r['kolom_b']) ?>)</td>
            <td><?= htmlspecialchars($r['n']) ?></td>
            <td><?= $r['uji_dipakai'] === 'paired_ttest' ? 'Paired T-Test' : 'Wilcoxon' ?></td>
            <td><?= htmlspecialchars($r['statistik_uji']) ?></td>
            <td class="<?= $r['sig_p_value'] < 0.05 ? 'sig-yes' : 'sig-no' ?>"><?= htmlspecialchars($r['sig_p_value']) ?></td>
            <td><?= htmlspecialchars($r['selisih_persen']) ?>%</td>
            <td><?= $r['h0_ditolak'] ? '<span class="sig-yes">H0 Ditolak</span>' : '<span class="sig-no">H0 Diterima</span>' ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

</body>
</html>