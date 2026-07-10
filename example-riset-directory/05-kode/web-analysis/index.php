<?php
/**
 * index.php
 * ---------
 * UI web untuk analisis Paired Samples T-Test / Wilcoxon Signed Rank Test.
 * Alur: Upload file (xlsx/csv) -> Pilih & pasangkan variabel -> Lihat hasil.
 *
 * Tidak butuh database — semua pemrosesan dilakukan langsung dari file yang
 * diupload (disimpan sementara di folder uploads/). Riwayat analisis OPSIONAL
 * bisa disimpan ke MySQL, lihat db/schema.sql & db/save_history.php.
 */

require 'lib/XlsxReader.php';
require 'lib/Stats.php';

session_start();

$step = $_POST['step'] ?? 'upload';
$error = null;

// ==================== STEP 1: UPLOAD FILE ====================
if ($step === 'upload' && isset($_FILES['datafile']) && $_FILES['datafile']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['datafile']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['xlsx', 'csv'])) {
        $error = "Format file harus .xlsx atau .csv";
    } else {
        $uploadDir = __DIR__ . '/uploads';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $savedName = uniqid('data_') . '.' . $ext;
        $savedPath = $uploadDir . '/' . $savedName;

        if (move_uploaded_file($_FILES['datafile']['tmp_name'], $savedPath)) {
            try {
                $table = XlsxReader::readAsTable($savedPath);
                $_SESSION['uploaded_file'] = $savedName;
                $_SESSION['header'] = $table['header'];
                $_SESSION['row_count'] = count($table['data']);
                $step = 'select';
            } catch (Exception $e) {
                $error = "Gagal membaca file: " . $e->getMessage();
            }
        } else {
            $error = "Gagal menyimpan file yang diupload.";
        }
    }
} elseif ($step === 'upload') {
    $step = 'upload'; // tampilkan form upload
}

// ==================== STEP 3: ANALISIS ====================
$results = [];
if ($step === 'analyze' && isset($_POST['pairs'])) {
    $savedName = $_SESSION['uploaded_file'] ?? null;
    if (!$savedName || !file_exists(__DIR__ . '/uploads/' . $savedName)) {
        $error = "Sesi habis atau file tidak ditemukan. Silakan upload ulang.";
        $step = 'upload';
    } else {
        $table = XlsxReader::readAsTable(__DIR__ . '/uploads/' . $savedName);
        $data = $table['data'];

        foreach ($_POST['pairs'] as $pair) {
            $colA = $pair['col_a'];
            $colB = $pair['col_b'];
            $label = trim($pair['label']) !== '' ? trim($pair['label']) : "$colA vs $colB";

            $x1 = [];
            $x2 = [];
            foreach ($data as $row) {
                if (is_numeric($row[$colA]) && is_numeric($row[$colB])) {
                    $x1[] = (float) $row[$colA];
                    $x2[] = (float) $row[$colB];
                }
            }

            if (count($x1) < 3) {
                $results[] = ['label' => $label, 'error' => 'Data numerik valid kurang dari 3 pasang, tidak bisa dianalisis.'];
                continue;
            }

            $diff = [];
            foreach ($x1 as $i => $v) $diff[] = $v - $x2[$i];

            $normality = Stats::shapiroWilk($diff);
            $ttest = Stats::pairedTTest($x1, $x2);
            $wilcoxon = Stats::wilcoxonSignedRank($x1, $x2);

            $useNormal = $normality['p'] !== null && $normality['p'] > 0.05;
            $recommended = $useNormal ? 'paired_ttest' : 'wilcoxon';

            $pctDiff = abs($ttest['mean_diff']) / $ttest['mean_x1'] * 100;
            $sigUsed = $useNormal ? $ttest['p'] : $wilcoxon['p'];
            $h0Rejected = ($sigUsed < 0.05) && ($pctDiff >= 10.0);

            $results[] = [
                'label' => $label, 'col_a' => $colA, 'col_b' => $colB,
                'n' => count($x1), 'normality' => $normality, 'ttest' => $ttest,
                'wilcoxon' => $wilcoxon, 'recommended' => $recommended,
                'pct_diff' => $pctDiff, 'h0_rejected' => $h0Rejected,
            ];
        }
    }
}

function fmt($v, $dec = 4) {
    if ($v === null) return '-';
    return number_format($v, $dec);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Analisis Statistik Paired T-Test / Wilcoxon — RTI</title>
<style>
    body { font-family: Segoe UI, Arial, sans-serif; max-width: 900px; margin: 30px auto; padding: 0 20px; background: #f7f8fa; color: #222; }
    h1 { font-size: 22px; }
    h2 { font-size: 18px; border-bottom: 2px solid #4C72B0; padding-bottom: 6px; margin-top: 32px; }
    .card { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
    table { border-collapse: collapse; width: 100%; margin: 10px 0; }
    th, td { border: 1px solid #ddd; padding: 8px 10px; text-align: left; font-size: 14px; }
    th { background: #4C72B0; color: white; }
    .pair-row { display: flex; gap: 10px; align-items: center; margin-bottom: 10px; }
    select, input[type=text] { padding: 6px; border-radius: 4px; border: 1px solid #ccc; }
    button, input[type=submit] { background: #4C72B0; color: white; border: none; padding: 10px 18px; border-radius: 5px; cursor: pointer; font-size: 14px; }
    button:hover, input[type=submit]:hover { background: #3a5a8f; }
    .btn-remove { background: #DD5555; padding: 6px 10px; }
    .btn-remove:hover { background: #b23e3e; }
    .error { background: #fdecea; color: #b71c1c; padding: 12px; border-radius: 6px; margin-bottom: 15px; }
    .sig-yes { color: #1b7a1b; font-weight: bold; }
    .sig-no { color: #999; }
    .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 12px; color: white; }
    .badge-normal { background: #4C72B0; }
    .badge-nonnormal { background: #DD8452; }
    small { color: #666; }
</style>
</head>
<body>

<h1>📊 Analisis Statistik: Paired Samples T-Test / Wilcoxon Signed Rank Test</h1>
<p><small>Upload data hasil pengujian → pilih variabel yang mau dibandingkan → lihat hasil uji normalitas, Paired T-Test, dan Wilcoxon otomatis.</small></p>

<?php if ($error): ?>
    <div class="error">⚠️ <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($step === 'upload'): ?>
    <div class="card">
        <h2>1. Upload Data</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="step" value="upload">
            <p>Pilih file <strong>.xlsx</strong> atau <strong>.csv</strong> (baris pertama harus nama kolom/header):</p>
            <input type="file" name="datafile" accept=".xlsx,.csv" required>
            <br><br>
            <input type="submit" value="Upload & Lanjut">
        </form>
    </div>

<?php elseif ($step === 'select'): ?>
    <div class="card">
        <h2>2. Pilih & Pasangkan Variabel</h2>
        <p><small>File <strong><?= htmlspecialchars($_SESSION['uploaded_file']) ?></strong> berhasil dibaca —
        <?= count($_SESSION['header']) ?> kolom, <?= $_SESSION['row_count'] ?> baris data.</small></p>
        <p>Kolom yang tidak relevan (contoh: <code>NO</code>, <code>NAMA</code>) cukup dibiarkan tidak dipasangkan — tidak perlu dihapus dari file.</p>

        <form method="post" id="analyzeForm">
            <input type="hidden" name="step" value="analyze">
            <div id="pairsContainer"></div>
            <button type="button" onclick="addPair()">+ Tambah Pasangan Variabel</button>
            <br><br>
            <input type="submit" value="Jalankan Analisis">
        </form>
    </div>

    <script>
        const columns = <?= json_encode($_SESSION['header']) ?>;
        let pairIndex = 0;

        function addPair() {
            const container = document.getElementById('pairsContainer');
            const div = document.createElement('div');
            div.className = 'pair-row';

            let optionsA = columns.map(c => `<option value="${c}">${c}</option>`).join('');

            div.innerHTML = `
                <span>Bandingkan:</span>
                <select name="pairs[${pairIndex}][col_a]" required>${optionsA}</select>
                <span>vs</span>
                <select name="pairs[${pairIndex}][col_b]" required>${optionsA}</select>
                <input type="text" name="pairs[${pairIndex}][label]" placeholder="Label (misal: Insert Latency)">
                <button type="button" class="btn-remove" onclick="this.parentElement.remove()">Hapus</button>
            `;
            container.appendChild(div);
            pairIndex++;
        }
        // otomatis tambah 1 baris saat halaman dibuka
        addPair();
    </script>

<?php elseif ($step === 'analyze'): ?>
    <div class="card">
        <h2>3. Hasil Analisis</h2>
        <a href="index.php">← Upload file baru</a>
    </div>

    <?php foreach ($results as $r): ?>
        <?php if (isset($r['error'])): ?>
            <div class="card"><h3><?= htmlspecialchars($r['label']) ?></h3><p class="error"><?= htmlspecialchars($r['error']) ?></p></div>
            <?php continue; ?>
        <?php endif; ?>

        <div class="card">
            <h3><?= htmlspecialchars($r['label']) ?> <small>(<?= htmlspecialchars($r['col_a']) ?> vs <?= htmlspecialchars($r['col_b']) ?>, N=<?= $r['n'] ?>)</small></h3>

            <h4>Statistik Deskriptif</h4>
            <table>
                <tr><th>Variabel</th><th>Mean</th></tr>
                <tr><td><?= htmlspecialchars($r['col_a']) ?></td><td><?= fmt($r['ttest']['mean_x1']) ?></td></tr>
                <tr><td><?= htmlspecialchars($r['col_b']) ?></td><td><?= fmt($r['ttest']['mean_x2']) ?></td></tr>
            </table>

            <h4>Uji Normalitas Selisih (Shapiro-Wilk)</h4>
            <?php if ($r['normality']['note']): ?>
                <p><em><?= htmlspecialchars($r['normality']['note']) ?></em></p>
            <?php else: ?>
                <table>
                    <tr><th>W</th><th>Sig. (p-value)</th><th>Distribusi</th></tr>
                    <tr>
                        <td><?= fmt($r['normality']['W']) ?></td>
                        <td><?= fmt($r['normality']['p'], 6) ?></td>
                        <td>
                            <?php if ($r['normality']['p'] > 0.05): ?>
                                <span class="badge badge-normal">Normal</span>
                            <?php else: ?>
                                <span class="badge badge-nonnormal">Tidak Normal</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            <?php endif; ?>

            <h4>Paired Samples T-Test <?= $r['recommended'] === 'paired_ttest' ? '✅ (dipakai)' : '(pembanding)' ?></h4>
            <table>
                <tr><th>Mean Selisih</th><th>Sd Selisih</th><th>df</th><th>t</th><th>Sig. (2-tailed)</th><th>Cohen's d</th></tr>
                <tr>
                    <td><?= fmt($r['ttest']['mean_diff']) ?></td>
                    <td><?= fmt($r['ttest']['sd_diff']) ?></td>
                    <td><?= $r['ttest']['df'] ?></td>
                    <td><?= fmt($r['ttest']['t']) ?></td>
                    <td class="<?= $r['ttest']['p'] < 0.05 ? 'sig-yes' : 'sig-no' ?>"><?= fmt($r['ttest']['p'], 6) ?></td>
                    <td><?= fmt($r['ttest']['cohen_d']) ?></td>
                </tr>
            </table>

            <h4>Wilcoxon Signed Rank Test <?= $r['recommended'] === 'wilcoxon' ? '✅ (dipakai)' : '(pembanding)' ?></h4>
            <table>
                <tr><th>Median A</th><th>Median B</th><th>W Statistic</th><th>z</th><th>Asymp. Sig. (2-tailed)</th></tr>
                <tr>
                    <td><?= fmt($r['wilcoxon']['median_x1'], 2) ?></td>
                    <td><?= fmt($r['wilcoxon']['median_x2'], 2) ?></td>
                    <td><?= fmt($r['wilcoxon']['W_stat'], 1) ?></td>
                    <td><?= fmt($r['wilcoxon']['z']) ?></td>
                    <td class="<?= $r['wilcoxon']['p'] < 0.05 ? 'sig-yes' : 'sig-no' ?>"><?= fmt($r['wilcoxon']['p'], 6) ?></td>
                </tr>
            </table>

            <h4>📌 Kesimpulan</h4>
            <p>
                Selisih rata-rata: <strong><?= fmt($r['pct_diff'], 2) ?>%</strong> —
                <?php if ($r['h0_rejected']): ?>
                    <strong class="sig-yes">H0 Ditolak, H1 Diterima.</strong> Terdapat perbedaan signifikan antara
                    <?= htmlspecialchars($r['col_a']) ?> dan <?= htmlspecialchars($r['col_b']) ?>
                    (uji yang dipakai: <?= $r['recommended'] === 'paired_ttest' ? 'Paired Samples T-Test' : 'Wilcoxon Signed Rank Test' ?>).
                <?php else: ?>
                    <strong class="sig-no">H0 Diterima, H1 Ditolak.</strong> Tidak ada perbedaan signifikan antara
                    <?= htmlspecialchars($r['col_a']) ?> dan <?= htmlspecialchars($r['col_b']) ?>.
                <?php endif; ?>
            </p>

            <button type="button" class="save-history-btn" data-payload='<?= htmlspecialchars(json_encode([
                'nama_file' => $_SESSION['uploaded_file'] ?? '',
                'label' => $r['label'],
                'kolom_a' => $r['col_a'],
                'kolom_b' => $r['col_b'],
                'n' => $r['n'],
                'mean_a' => $r['ttest']['mean_x1'],
                'mean_b' => $r['ttest']['mean_x2'],
                'shapiro_w' => $r['normality']['W'],
                'shapiro_p' => $r['normality']['p'],
                'uji_dipakai' => $r['recommended'],
                'statistik_uji' => $r['recommended'] === 'paired_ttest' ? $r['ttest']['t'] : $r['wilcoxon']['z'],
                'sig_p_value' => $r['recommended'] === 'paired_ttest' ? $r['ttest']['p'] : $r['wilcoxon']['p'],
                'selisih_persen' => $r['pct_diff'],
                'cohen_d' => $r['recommended'] === 'paired_ttest' ? $r['ttest']['cohen_d'] : null,
                'h0_ditolak' => $r['h0_rejected'] ? '1' : '0',
            ]), ENT_QUOTES) ?>'>💾 Simpan ke Riwayat Database</button>
            <span class="save-status"></span>
        </div>
    <?php endforeach; ?>

    <p><a href="db/history.php">🗂️ Lihat semua riwayat analisis tersimpan</a></p>

    <script>
        document.querySelectorAll('.save-history-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const payload = JSON.parse(btn.dataset.payload);
                const statusEl = btn.nextElementSibling;
                statusEl.textContent = ' Menyimpan...';

                const formData = new FormData();
                for (const key in payload) {
                    formData.append(key, payload[key] === null ? '' : payload[key]);
                }

                fetch('db/save_history.php', { method: 'POST', body: formData })
                    .then(res => res.json())
                    .then(data => {
                        statusEl.textContent = ' ' + (data.success ? '✅ ' : '⚠️ ') + data.message;
                    })
                    .catch(err => {
                        statusEl.textContent = ' ⚠️ Gagal terhubung ke server.';
                    });
            });
        });
    </script>

<?php endif; ?>

<p style="text-align:center; margin-top: 40px;"><small>Selalu silangkan hasil tool ini dengan output SPSS asli sebelum dipakai di laporan/skripsi.</small></p>

</body>
</html>