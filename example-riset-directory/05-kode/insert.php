<?php

require 'koneksi.php';

if (isset($_POST['nilai_sensor'])) {
    $nilai = (int) $_POST['nilai_sensor'];

    // 1. MULAI STOPWATCH (presisi mikrodetik)
    $start_time = microtime(true);

    try {
        // 2. EKSEKUSI KUERI KE DATABASE
        $stmt = $conn->prepare("INSERT INTO log_jantung (nilai_sensor) VALUES (:nilai)");
        $stmt->bindParam(':nilai', $nilai);
        $stmt->execute();

        // 3. MATIKAN STOPWATCH
        $end_time = microtime(true);

        // 4. HITUNG KECEPATAN (ubah ke satuan milidetik / ms)
        $insert_latency = ($end_time - $start_time) * 1000;

        // 5. TULIS LOG KE FILE CSV SECARA TERPISAH
        
        if ($db_engine == "MySQL") {
            $nama_file_log = "log_mysql.csv";
        } else {
            $nama_file_log = "log_postgresql.csv";
        }

        $log_file = fopen($nama_file_log, "a");
        $waktu_sekarang = date("Y-m-d H:i:s");

        // Format CSV: Waktu, Engine DB, Nilai Sensor, Insert Latency (ms)
        fputcsv($log_file, [$waktu_sekarang, $db_engine, $nilai, round($insert_latency, 4)]);
        fclose($log_file);

        // Feedback ke ESP32 (ditampilkan di Serial Monitor kalau perlu debug)
        echo "Sukses: " . round($insert_latency, 2) . " ms";

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Data sensor tidak ditemukan.";
}
