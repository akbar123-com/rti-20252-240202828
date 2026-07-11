# Tahap 2 Implementasi Sistem (ESP32, Skrip PHP, Switch Database)

**Status:** Selesai
**Acuan variabel:** [tahap-1-arsitektur-dan-skema-database.md](tahap-1-arsitektur-dan-skema-database.md)
**Jenis Dokumen:** Firmware & Source Code Implementasi

---

## Tujuan

Mengimplementasikan sistem pengujian nyata yang mendukung dua skenario database untuk mengukur efisiensi *insert latency* dan beban RAM:

- **Skenario MySQL**  *baseline* praktis, mengukur kecepatan simpan data pada *engine* yang dioptimalkan untuk operasi tulis sederhana.
- **Skenario PostgreSQL** kondisi pembanding, mengukur dampak mekanisme validasi dan kontrol konkurensi (MVCC) yang lebih ketat terhadap kecepatan simpan data.

## Deliverable

- [x] **Firmware ESP32 (`sketch_jun11c_RTI.ino`)** membaca sensor MAX30102 dan mengirim data lewat Wi-Fi ke server secara *continuous streaming*.
- [x] **Skrip Switch Koneksi (`koneksi.php`)**  mengatur `$db_engine` untuk memilih MySQL atau PostgreSQL tanpa mengubah kode di sisi ESP32 maupun skrip lain.
- [x] **Skrip Logger & Pengukur Latency (`insert.php`)** endpoint penerima data dari ESP32, mencatat `microtime()` sebelum/sesudah `INSERT`, menghitung *insert latency* dalam milidetik.
- [x] **Skrip Pembaca Resource (`get_metrics.php`)** membaca beban CPU/RAM/Disk server (Windows) secara terpisah dari proses pengukuran latency agar tidak mengganggu presisi.
- [x] **Dashboard Monitoring (`index.php`)** menampilkan progres pengujian *real-time* dan tombol reset data antar-sesi.
- [x] **Aplikasi Web Analisis Kustom (`web-analysis/`)** aplikasi PHP terpisah (`index.php`, `lib/Stats.php`, `lib/XlsxReader.php`) untuk mengunggah `data RTI EXEL.xlsx` dan menghitung ulang Paired Samples T-Test secara independen dari SPSS.

---

## Hasil Verifikasi End-to-End

Sistem ini telah diverifikasi secara manual sebelum dipakai untuk pengambilan data penuh:

- **Kondisi Skenario MySQL**: `koneksi.php` di-set ke MySQL → ESP32 menembakkan data → `insert.php` mencatat waktu simpan tiap baris → dashboard menampilkan rata-rata *insert latency* yang konsisten rendah.
- **Kondisi Skenario PostgreSQL**: `koneksi.php` di-set ke PostgreSQL (tanpa mengubah firmware ESP32 sama sekali  membuktikan variabel independen berhasil diisolasi dari komponen client) → alur identik → dashboard menampilkan rata-rata *insert latency* yang lebih tinggi.
- **Validasi Sesi Gugur (Fail-closed)**: Jika XAMPP/database tiba-tiba berhenti bekerja (*stop working*) saat dihantam data, atau koneksi Wi-Fi ESP32 terputus di tengah jalan → run pengujian dihentikan → data pada sesi tersebut ditandai gugur → pengujian diulang dari kondisi *cold start* setelah sistem/jaringan pulih.

### Bug Ditemukan & Diperbaiki

| File | Masalah | Perbaikan |
|---|---|---|
| `sketch_jun11c_RTI.ino` | **Bug kritis:** `serverName = "http:///RTI/insert.php"`  alamat IP kosong (tiga garis miring). ESP32 tidak akan pernah berhasil mengirim data ke server dengan alamat ini. | Diberi placeholder jelas `http://192.168.1.100/RTI/insert.php` disertai catatan cara mengecek IP asli laptop lewat `ipconfig` sebelum upload ke ESP32. |
| `koneksi.php` | Tidak ada penanganan jika `$db_engine` salah ketik (bukan persis `"MySQL"` atau `"PostgreSQL"`)  `$conn` jadi tidak terdefinisi dan `insert.php` gagal dengan pesan *error* yang membingungkan. | Ditambahkan blok `else { die(...) }` agar *error*-nya jelas sejak awal. |
| `insert.php`, `index.php`, `get_metrics.php` | Tidak ada bug, logika sudah benar (stopwatch presisi mikrodetik, pembacaan resource terpisah dari pengukuran latency). | Ditambahkan komentar penjelas saja, logika tidak diubah. |

---

## Catatan Lingkungan Eksperimen

- Selama pengujian berlangsung, jaringan Wi-Fi yang dipakai ESP32 dan laptop server harus **sama persis**  pengujian memakai jaringan Wi-Fi publik (bukan jaringan terisolasi) agar kondisi mendekati implementasi IoT nyata, sesuai batasan masalah proposal.
- Seluruh aplikasi/proses latar belakang di laptop server (Windows Update, Antivirus, aplikasi lain) wajib ditutup total sebelum tiap sesi run, agar alokasi CPU/RAM yang tercatat murni berasal dari proses database yang sedang diuji, bukan proses lain.
- Cache dibersihkan setiap kali berpindah dari satu database ke database pembanding, untuk memastikan setiap sesi dimulai dari kondisi memori yang setara.
- File log runtime (`log_mysql.csv`, `log_postgresql.csv`) dihasilkan otomatis oleh `insert.php` saat pengujian berjalan dan dipindahkan ke `04-data/` setelah satu sesi selesai — bukan disimpan permanen di folder kode.