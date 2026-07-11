<?php


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