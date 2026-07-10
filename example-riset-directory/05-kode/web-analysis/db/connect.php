<?php
/**
 * connect.php
 * -----------
 * Koneksi MySQL untuk fitur riwayat analisis (OPSIONAL).
 * Sesuaikan $host/$user/$pass/$db kalau beda dengan setup XAMPP kamu.
 * Kalau kamu sudah punya koneksi.php dari project RTI sebelumnya, database
 * "riset_db" bisa dipakai bareng (tabelnya beda: log_jantung vs analisis_riwayat).
 */

$host = "localhost";
$user = "root";
$pass = "";
$db   = "riset_db";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Jangan hentikan tool utama kalau DB belum di-setup — fitur riwayat
    // memang opsional, analisis utama tetap jalan tanpa database.
    $pdo = null;
    $dbConnectError = $e->getMessage();
}