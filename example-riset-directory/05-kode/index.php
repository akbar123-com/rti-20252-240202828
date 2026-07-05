<?php
// index.php
// Dashboard real-time untuk memantau progres pengujian MySQL vs PostgreSQL.
// Tidak ada bug ditemukan di file ini — logikanya sudah benar.

// FITUR HAPUS DATA OTOMATIS & TRUNCATE DATABASE (Tombol Reset)
if (isset($_POST['reset_mysql'])) {
    if (file_exists('log_mysql.csv')) { unlink('log_mysql.csv'); }
    try {
        $conn_my = new PDO("mysql:host=127.0.0.1;dbname=riset_db", "root", "");
        $conn_my->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn_my->exec("TRUNCATE TABLE log_jantung");
        echo "<script>alert('SUKSES! File CSV dihapus dan Database MySQL kembali ke 0!'); window.location.href='index.php';</script>";
    } catch(PDOException $e) {
        echo "<script>alert('Gagal mereset MySQL: " . addslashes($e->getMessage()) . "'); window.location.href='index.php';</script>";
    }
}

if (isset($_POST['reset_pg'])) {
    if (file_exists('log_postgresql.csv')) { unlink('log_postgresql.csv'); }
    try {
        $conn_pg = new PDO("pgsql:host=127.0.0.1;port=5432;dbname=riset_db;", "postgres", "11111111");
        $conn_pg->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn_pg->exec("TRUNCATE TABLE public.log_jantung RESTART IDENTITY");
        echo "<script>alert('SUKSES! File CSV dihapus dan Database PostgreSQL kembali ke 0!'); window.location.href='index.php';</script>";
    } catch(PDOException $e) {
        echo "<script>alert('Gagal mereset PostgreSQL: " . addslashes($e->getMessage()) . "'); window.location.href='index.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Monitor Riset Perbandingan Database</title>
    <style>
        body { font-family: sans-serif; margin: 20px; color: #333; }
        .container { display: flex; gap: 20px; }
        .box { border: 1px solid #ccc; padding: 20px; width: 50%; border-radius: 8px; background-color: #fff; }
        .metrics-box { background-color: #f9f9f9; padding: 15px; margin-bottom: 20px; border-radius: 8px; border: 1px solid #ddd; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .highlight { font-weight: bold; color: red; font-size: 1.2em; }
        
        .btn-cetak { padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; margin-bottom: 20px; }
        .btn-cetak:hover { background-color: #218838; }
        
        .btn-reset { padding: 5px 10px; background-color: #dc3545; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 12px; float: right; }
        .btn-reset:hover { background-color: #c82333; }

        .input-param { width: 60px; padding: 4px; border: 1px solid #ccc; border-radius: 4px; text-align: center; font-weight: bold; color: #0056b3;}

        @media print {
            .btn-cetak, .btn-reset, .metrics-box { display: none !important; }
            body { background-color: white; margin: 0; }
            .box { border: 1px solid #000; box-shadow: none; break-inside: avoid; width: 100%; margin-bottom: 20px; }
            .container { display: block; } 
            input[type="text"] { border: none; background: transparent; padding: 0; font-size: 1em; color: #000; outline: none; }
            @page { size: A4; margin: 2cm; }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>
<body>

    <h2>Dashboard Laporan Pengujian Database</h2>
    <button class="btn-cetak" id="btn-foto" onclick="ambilGambar()">📸 Simpan Laporan sebagai Gambar</button>

    <div class="metrics-box" id="kotak-hardware">
        <h3>Layar Pantau Hardware (Real-time)</h3>
        <p style="font-size: 0.9em; color: #555;">*Sistem akan otomatis mengunci dan merekam angka puncak (Peak Load) saat proses pengujian berlangsung.</p>
        <p>Penggunaan CPU: <span id="cpu_usage" class="highlight">Loading...</span> %</p>
        <p>Penggunaan Memory (RAM): <span id="mem_usage" class="highlight">Loading...</span> %</p>
        <p>Beban Penyimpanan (Disk): <span id="disk_usage" class="highlight">Loading...</span> %</p>
    </div>

    <div class="container">
        <div class="box">
            <form method="POST" style="display:inline;">
                <button type="submit" name="reset_mysql" class="btn-reset" onclick="sessionStorage.removeItem('peak_ram_my'); sessionStorage.removeItem('peak_disk_my'); sessionStorage.removeItem('done_my'); return confirm('Hapus file CSV dan bersihkan Database MySQL sekarang?')">Reset Data CSV & DB</button>
            </form>
            <h3>Hasil Pengujian MySQL</h3>
            
            <?php
            $file_mysql = 'log_mysql.csv';
            $total_masuk_mysql = 0; $total_waktu_mysql = 0;

            if (file_exists($file_mysql)) {
                if (($handle = fopen($file_mysql, "r")) !== FALSE) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $total_masuk_mysql++;
                        if(isset($row[3]) && is_numeric($row[3])) { $total_waktu_mysql += (float)$row[3]; }
                    }
                    fclose($handle);
                }
            }
            
            $rata_rata_mysql = ($total_masuk_mysql > 0) ? round($total_waktu_mysql / $total_masuk_mysql, 4) : 0;
            $selisih_mysql = 1000 - $total_masuk_mysql;
            
            if ($selisih_mysql > 0) { $status_mysql = "<b style='color:red;'>" . $selisih_mysql . " baris (Gagal/Hilang)</b>"; } 
            elseif ($selisih_mysql < 0) { $status_mysql = "<b style='color:orange;'>Kelebihan " . abs($selisih_mysql) . " baris (Klik Reset!)</b>"; } 
            else { $status_mysql = "<b style='color:green;'>0 baris (Sempurna)</b>"; }
            ?>
            <p>Target Tembakan: <b>1000 baris</b></p>
            <p>Data Berhasil Masuk: <b><?= $total_masuk_mysql ?> baris</b></p>
            <p>Status Data Hilang: <?= $status_mysql ?></p>
            <hr style="border: 0.5px solid #eee; margin: 15px 0;">
            
            <p><b>Parameter Proposal:</b> <span id="indikator_my" style="font-size: 0.9em; font-weight: bold;"></span></p>
            <p>Beban RAM Puncak (Peak): <input type="text" id="ram_my" class="input-param" placeholder="..."> %</p>
            <p>Beban Disk Puncak (Peak): <input type="text" id="disk_my" class="input-param" placeholder="..."> %</p>
            <br>
            <p>Rata-rata Waktu Simpan (Latency):</p>
            <p class="highlight" style="font-size: 1.8em;"><?= $rata_rata_mysql ?> ms</p>
        </div>

        <div class="box">
            <form method="POST" style="display:inline;">
                <button type="submit" name="reset_pg" class="btn-reset" onclick="sessionStorage.removeItem('peak_ram_pg'); sessionStorage.removeItem('peak_disk_pg'); sessionStorage.removeItem('done_pg'); return confirm('Hapus file CSV dan bersihkan Database PostgreSQL sekarang?')">Reset Data CSV & DB</button>
            </form>
            <h3>Hasil Pengujian PostgreSQL</h3>
            
            <?php
            $file_pg = 'log_postgresql.csv';
            $total_masuk_pg = 0; $total_waktu_pg = 0;

            if (file_exists($file_pg)) {
                if (($handle = fopen($file_pg, "r")) !== FALSE) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $total_masuk_pg++;
                        if(isset($row[3]) && is_numeric($row[3])) { $total_waktu_pg += (float)$row[3]; }
                    }
                    fclose($handle);
                }
            }
            
            $rata_rata_pg = ($total_masuk_pg > 0) ? round($total_waktu_pg / $total_masuk_pg, 4) : 0;
            $selisih_pg = 1000 - $total_masuk_pg;
            
            if ($selisih_pg > 0) { $status_pg = "<b style='color:red;'>" . $selisih_pg . " baris (Gagal/Hilang)</b>"; } 
            elseif ($selisih_pg < 0) { $status_pg = "<b style='color:orange;'>Kelebihan " . abs($selisih_pg) . " baris (Klik Reset!)</b>"; } 
            else { $status_pg = "<b style='color:green;'>0 baris (Sempurna)</b>"; }
            ?>
            <p>Target Tembakan: <b>1000 baris</b></p>
            <p>Data Berhasil Masuk: <b><?= $total_masuk_pg ?> baris</b></p>
            <p>Status Data Hilang: <?= $status_pg ?></p>
            <hr style="border: 0.5px solid #eee; margin: 15px 0;">
            
            <p><b>Parameter Proposal:</b> <span id="indikator_pg" style="font-size: 0.9em; font-weight: bold;"></span></p>
            <p>Beban RAM Puncak (Peak): <input type="text" id="ram_pg" class="input-param" placeholder="..."> %</p>
            <p>Beban Disk Puncak (Peak): <input type="text" id="disk_pg" class="input-param" placeholder="..."> %</p>
            <br>
            <p>Rata-rata Waktu Simpan (Latency):</p>
            <p class="highlight" style="font-size: 1.8em;"><?= $rata_rata_pg ?> ms</p>
        </div>
    </div>

    <script>
        let peakRamMy = 0, peakDiskMy = 0;
        let peakRamPg = 0, peakDiskPg = 0;
        let statusMy = "idle";
        let statusPg = "idle";
        
        let prevRowsMy = 0, idleCountMy = 0;
        let prevRowsPg = 0, idleCountPg = 0;

        $(document).ready(function() {
            // Membaca status memori browser (Gembok)
            if (sessionStorage.getItem('done_my')) {
                statusMy = "done";
                $('#indikator_my').text(" ✅ Selesai Diuji!").css("color", "green");
                $('#ram_my').val(sessionStorage.getItem('peak_ram_my') || "0");
                $('#disk_my').val(sessionStorage.getItem('peak_disk_my') || "0");
            }
            if (sessionStorage.getItem('done_pg')) {
                statusPg = "done";
                $('#indikator_pg').text(" ✅ Selesai Diuji!").css("color", "green");
                $('#ram_pg').val(sessionStorage.getItem('peak_ram_pg') || "0");
                $('#disk_pg').val(sessionStorage.getItem('peak_disk_pg') || "0");
            }
        });

        function checkTestStatus() {
            // --- PENGECEKAN ALIRAN DATA MYSQL ---
            if (statusMy !== "done" && statusMy !== "finishing") {
                $.ajax({
                    url: 'log_mysql.csv', cache: false, timeout: 5000,
                    success: function(data) {
                        let text = data.trim();
                        let rows = text === "" ? 0 : text.split('\n').length;
                        
                        if (rows > 0) {
                            if (statusMy === "idle") {
                                statusMy = "testing";
                                $('#indikator_my').text(" ⏳ Merekam otomatis...").css("color", "blue");
                            }
                            
                            if (statusMy === "testing") {
                                if (rows === prevRowsMy) { idleCountMy++; } 
                                else { idleCountMy = 0; prevRowsMy = rows; }

                                // Jika data sudah 1000 atau berhenti bertambah (Macet)
                                if (rows >= 1000 || idleCountMy >= 6) {
                                    statusMy = "finishing"; 
                                    $('#indikator_my').text(" ⏳ Menunggu Metrik Masuk...").css("color", "orange");
                                    
                                    // SCRIPT PINTAR: Terus tunggu sampai RAM tidak 0
                                    let tungguMy = setInterval(function() {
                                        if (peakRamMy > 0) {
                                            clearInterval(tungguMy);
                                            statusMy = "done";
                                            sessionStorage.setItem('done_my', 'true');
                                            sessionStorage.setItem('peak_ram_my', peakRamMy);
                                            sessionStorage.setItem('peak_disk_my', peakDiskMy);
                                            $('#indikator_my').text(" ✅ Mengunci Data...").css("color", "green");
                                            setTimeout(function() { window.location.reload(); }, 1000);
                                        }
                                    }, 1000);
                                    
                                    // Pengaman: Maksimal tunggu 15 detik
                                    setTimeout(function() {
                                        if (statusMy !== "done") {
                                            clearInterval(tungguMy);
                                            statusMy = "done";
                                            sessionStorage.setItem('done_my', 'true');
                                            sessionStorage.setItem('peak_ram_my', peakRamMy);
                                            sessionStorage.setItem('peak_disk_my', peakDiskMy);
                                            setTimeout(function() { window.location.reload(); }, 1000);
                                        }
                                    }, 15000);
                                }
                            }
                        }
                    }
                });
            }

            // --- PENGECEKAN ALIRAN DATA POSTGRESQL ---
            if (statusPg !== "done" && statusPg !== "finishing") {
                $.ajax({
                    url: 'log_postgresql.csv', cache: false, timeout: 5000,
                    success: function(data) {
                        let text = data.trim();
                        let rows = text === "" ? 0 : text.split('\n').length;
                        
                        if (rows > 0) {
                            if (statusPg === "idle") {
                                statusPg = "testing";
                                $('#indikator_pg').text(" ⏳ Merekam otomatis...").css("color", "blue");
                            }
                            
                            if (statusPg === "testing") {
                                if (rows === prevRowsPg) { idleCountPg++; } 
                                else { idleCountPg = 0; prevRowsPg = rows; }

                                // Jika data sudah 1000 atau berhenti bertambah (Macet)
                                if (rows >= 1000 || idleCountPg >= 6) {
                                    statusPg = "finishing"; 
                                    $('#indikator_pg').text(" ⏳ Menunggu Metrik Masuk...").css("color", "orange");
                                    
                                    // SCRIPT PINTAR: Terus tunggu sampai RAM tidak 0
                                    let tungguPg = setInterval(function() {
                                        if (peakRamPg > 0) { // <--- INI KUNCI UTAMANYA!
                                            clearInterval(tungguPg);
                                            statusPg = "done";
                                            sessionStorage.setItem('done_pg', 'true');
                                            sessionStorage.setItem('peak_ram_pg', peakRamPg);
                                            sessionStorage.setItem('peak_disk_pg', peakDiskPg);
                                            $('#indikator_pg').text(" ✅ Mengunci Data...").css("color", "green");
                                            setTimeout(function() { window.location.reload(); }, 1000);
                                        }
                                    }, 1000);
                                    
                                    // Pengaman: Maksimal tunggu 15 detik
                                    setTimeout(function() {
                                        if (statusPg !== "done") {
                                            clearInterval(tungguPg);
                                            statusPg = "done";
                                            sessionStorage.setItem('done_pg', 'true');
                                            sessionStorage.setItem('peak_ram_pg', peakRamPg);
                                            sessionStorage.setItem('peak_disk_pg', peakDiskPg);
                                            setTimeout(function() { window.location.reload(); }, 1000);
                                        }
                                    }, 15000);
                                }
                            }
                        }
                    }
                });
            }
        }

        function updateMetrics() {
            $.ajax({
                url: 'get_metrics.php', 
                dataType: 'json', 
                success: function(data) {
                    let curRam = parseFloat(data.memory) || 0;
                    let curDisk = parseFloat(data.disk) || 0;
                    
                    $('#cpu_usage').text(data.cpu !== null ? data.cpu : "0");
                    $('#mem_usage').text(curRam !== 0 ? curRam : "Loading...");
                    $('#disk_usage').text(curDisk);

                    // Merekam angka puncak secara rahasia di latar belakang
                    if (statusMy === "testing" || statusMy === "finishing") {
                        if (curRam > peakRamMy) peakRamMy = curRam;
                        if (curDisk > peakDiskMy) peakDiskMy = curDisk;
                    }
                    if (statusPg === "testing" || statusPg === "finishing") {
                        if (curRam > peakRamPg) peakRamPg = curRam;
                        if (curDisk > peakDiskPg) peakDiskPg = curDisk;
                    }
                },
                error: function() { }
            });
        }
        
        // Timer deteksi: Setengah detik untuk memantau data, 2 detik untuk hardware
        setInterval(checkTestStatus, 500); 
        setInterval(updateMetrics, 2000); 

        // Fungsi Fitur Screenshot
        function ambilGambar() {
            let namaFile = prompt("Masukkan nama untuk file gambar laporan ini:", "Hasil_Pengujian_Database");
            if (namaFile != null && namaFile !== "") {
                document.getElementById('btn-foto').style.display = 'none';
                document.getElementById('kotak-hardware').style.display = 'none';
                let tombolReset = document.querySelectorAll('.btn-reset');
                tombolReset.forEach(btn => btn.style.display = 'none');

                html2canvas(document.body).then(canvas => {
                    let link = document.createElement('a');
                    link.download = namaFile + '.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                    
                    document.getElementById('btn-foto').style.display = 'block';
                    document.getElementById('kotak-hardware').style.display = 'block';
                    tombolReset.forEach(btn => btn.style.display = 'block');
                });
            }
        }
    </script>
</body>
</html>