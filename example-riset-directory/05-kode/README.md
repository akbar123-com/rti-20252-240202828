# 05-kode

Source code implementasi **Tahap 2** (Setup Sistem: ESP32 + Server) dan **Tahap 3** (Eksekusi Pengujian MySQL vs PostgreSQL).

## Struktur

```
05-kode/
├── esp32/
│   └── sketch_jun11c_RTI.ino     # Firmware ESP32 — baca sensor MAX30102, kirim ke server
└── server-xampp/
    └── RTI/                      # Folder ini di-copy ke htdocs/ XAMPP
        ├── koneksi.php           # Konfigurasi koneksi MySQL / PostgreSQL (ganti $db_engine di sini)
        ├── insert.php            # Endpoint terima data dari ESP32, ukur Insert Latency, tulis CSV
        ├── get_metrics.php       # Baca beban CPU/RAM/Disk sistem (Windows)
        └── index.php             # Dashboard monitoring real-time hasil pengujian
```

## Perbaikan yang Dilakukan

| File | Masalah | Perbaikan |
|---|---|---|
| `esp32/sketch_jun11c_RTI.ino` | **Bug kritis:** `serverName = "http:///RTI/insert.php"` — alamat IP kosong (tiga garis miring). ESP32 tidak akan pernah berhasil mengirim data ke server dengan alamat ini. | Diberi placeholder jelas `http://192.168.1.100/RTI/insert.php` + komentar cara cek IP asli laptop kamu lewat `ipconfig`. **Kamu wajib ganti `192.168.1.100` dengan IP asli laptopmu sebelum upload ke ESP32.** |
| `server-xampp/RTI/koneksi.php` | Tidak ada penanganan jika `$db_engine` salah ketik (bukan persis `"MySQL"` atau `"PostgreSQL"`) — `$conn` jadi tidak terdefinisi dan `insert.php` gagal dengan pesan error yang membingungkan. | Ditambahkan blok `else { die(...) }` supaya errornya jelas sejak awal. |
| `server-xampp/RTI/insert.php` | Tidak ada bug, logika sudah benar (stopwatch presisi mikrodetik, log CSV per-engine otomatis). | Ditambahkan komentar penjelas saja, logika tidak diubah. |
| `server-xampp/RTI/index.php` | Tidak ada bug, dashboard & logic reset sudah benar. | Ditambahkan komentar penjelas saja. |
| `server-xampp/RTI/get_metrics.php` | Tidak ada bug, pembacaan CPU/RAM/Disk sudah benar dan berjalan terpisah dari pengukuran latency (tidak mengganggu presisi). | Ditambahkan komentar penjelas saja. |

## Cara Menjalankan (Urutan Tiap Subjek/Run)

1. Buka `server-xampp/RTI/koneksi.php`, set `$db_engine = "MySQL";` (atau `"PostgreSQL"`)
2. Copy folder `server-xampp/RTI/` ke `C:\xampp\htdocs\RTI\`
3. Pastikan MySQL/PostgreSQL & Apache aktif di XAMPP Control Panel
4. Upload `esp32/sketch_jun11c_RTI.ino` ke ESP32 (IP di `serverName` sudah disesuaikan dengan laptopmu)
5. Buka `http://localhost/RTI/index.php` di browser buat mantau progres real-time
6. Tempelkan jari ke sensor MAX30102 sampai LED nyala penuh (1000 data terekam)
7. Tekan tombol BOOT di ESP32 untuk mulai kirim data ke server
8. Setelah selesai (dashboard menampilkan "✅ Selesai Diuji!"), catat angka Latency, RAM, dan Disk ke `04-data/raw/`
9. Klik **"Reset Data CSV & DB"** di dashboard sebelum mulai subjek berikutnya
10. Ulangi untuk subjek berikutnya, gantian antara `$db_engine = "MySQL"` dan `"PostgreSQL"` sesuai desain paired

## File Log Runtime (Bukan Bagian dari Source Code)

`log_mysql.csv` dan `log_postgresql.csv` akan otomatis dibuat oleh `insert.php` saat pengujian berjalan — **jangan disimpan di folder `05-kode/`**. Setelah satu sesi pengujian selesai, pindahkan/salin isinya ke `../04-data/raw/` sebagai data mentah untuk dianalisis.

**Catatan:** ditemukan file `log_eksperimen.csv` (kosong) di upload kamu yang tidak direferensikan di kode manapun kemungkinan sisa dari versi sebelumnya. Aman untuk dihapus, tidak memengaruhi jalannya sistem.

## Acuan

- Landasan teori metode statistik: [`../03-teori/`](../03-teori/)
- Data mentah hasil eksekusi: [`../04-data/`](../04-data/)
- Skrip analisis statistik (Python): [`analysis/`](analysis/) — lihat pekerjaan sebelumnya