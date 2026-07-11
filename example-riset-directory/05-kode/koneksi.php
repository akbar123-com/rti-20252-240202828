<?php

$db_engine = "MySQL";

if ($db_engine == "MySQL") {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "riset_db";

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Koneksi MySQL Gagal: " . $e->getMessage());
    }
} elseif ($db_engine == "PostgreSQL") {
    $host = "127.0.0.1";
    $user = "postgres";
    $pass = "11111111"; // Sesuaikan dengan password pgAdmin kamu
    $db   = "riset_db";

    try {
        $conn = new PDO("pgsql:host=$host;port=5432;dbname=$db;", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Koneksi PostgreSQL Gagal: " . $e->getMessage());
    }
} else {
    
    die("Konfigurasi salah: \$db_engine harus \"MySQL\" atau \"PostgreSQL\", ditemukan: \"$db_engine\"");
}
