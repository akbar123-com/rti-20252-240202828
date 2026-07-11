# Tahap 3  Pengujian Beban & Eksekusi Data (35 Run Berpasangan)

**Status:** Selesai  matriks pengujian 35 subjek (masing-masing diuji berpasangan ke MySQL **dan** PostgreSQL, total 70 sesi run) telah selesai dijalankan. Data primer mentah diarsipkan di folder `04-data/` pada file utama `data RTI EXEL.xlsx`.

**Bergantung pada:** [tahap-2-implementasi-gateway.md](tahap-2-implementasi-gateway.md)
**File Terkait:** [rencana-penelitian.md](rencana-penelitian.md)

---

## Tujuan

Menyusun rencana eksekusi dan menjalankan pengujian lapangan untuk membandingkan efisiensi MySQL sebagai kondisi praktis vs PostgreSQL sebagai kondisi pembanding, dengan mengirimkan data detak jantung nyata dari 35 subjek secara *continuous streaming* ke kedua database secara bergantian.

## Deliverable

- [x] Firmware ESP32 siap pakai untuk merekam & mengirim 1.000 baris data sensor per subjek per database
- [x] Skema *execution plan* 70 run (35 subjek × 2 database) dengan status *Planned → Executed*
- [x] Protokol penanganan anomali (*run gagal, hasil ekstrem, koneksi putus, inkonsistensi antar-run*)
- [x] Luaran angka *insert latency* (ms) dan beban RAM (%) yang siap dipindahkan ke `data RTI EXEL.xlsx`
- [x] Pengisian matriks sampel penuh N=35 subjek (2 database × 35 subjek = 70 total sesi run)

---

## Matriks Pengumpulan Sampel

| Dimensi Kontrol | Nilai Pengelompokan |
|---|---|
| **Jenis Database (Variabel Independen)** | MySQL 8.0, PostgreSQL 15.x/16.x |
| **Jumlah Subjek** | 35 orang, masing-masing merekam data detak jantungnya sendiri lewat MAX30102 |
| **Beban per Run** | Target 1.000 baris data per subjek per database |

Total run: **35 subjek × 2 database = 70 sesi run**, menghasilkan target 70.000 baris data (35.000 baris per database), dikontrol penuh lewat dashboard `index.php` dan tombol reset antar-sesi.

---

## Protokol Pelaksanaan Sesi

Untuk setiap subjek nomor urut 1 sampai 35, pada tiap database:

1. Set `koneksi.php` ke database yang akan diuji (`MySQL` atau `PostgreSQL`), pastikan database pembanding tidak aktif.
2. Bersihkan cache dan matikan seluruh proses latar belakang laptop server (Windows Update, Antivirus).
3. Buka dashboard `index.php` di browser untuk memantau progres.
4. Tempelkan jari subjek ke sensor MAX30102 hingga LED menyala penuh, lalu tekan tombol BOOT di ESP32 untuk mulai mengirim 1.000 baris data.
5. Pantau beban RAM lewat Task Manager selama transmisi berlangsung.
6. Setelah 1.000 baris masuk (dashboard menampilkan status selesai), catat rata-rata *insert latency* dan jumlah baris tersimpan.
7. Klik tombol reset data pada dashboard, lalu ulangi langkah 1–6 untuk database pembanding pada subjek yang sama.

---

## Protokol Penanganan Anomali

| Jenis Anomali | Contoh | Tindakan |
|---|---|---|
| Run gagal (*crash*) | XAMPP/MySQL tiba-tiba berhenti bekerja saat dihantam ribuan data | Dokumentasikan *error*, restart layanan XAMPP, ulangi run dari awal |
| Hasil ekstrem | Waktu simpan mendadak sangat lambat, tembus di atas 1 detik per baris | Cek apakah ada Windows Update/Antivirus berjalan di latar belakang, matikan, ulangi pengujian |
| Transmisi terputus | Pengiriman data dari ESP32 berhenti di tengah jalan (baru masuk sebagian data) | Cek koneksi Wi-Fi lokal, putuskan perangkat lain yang numpang konek ke router, reset ESP32, ulangi |
| Inkonsistensi antar-run | Hasil pengujian pada satu subjek berbeda jauh (lebih lambat) dibanding subjek lain | Catat sebagai temuan (kemungkinan *thermal throttling*), beri jeda pendinginan laptop, tes ulang |

**Prinsip yang dipakai:** *Detect → Investigate → Document → Decide* — anomali tidak langsung dihapus dari dataset, melainkan diinvestigasi dan didokumentasikan dulu.

---

## Hasil Pengumpulan Data Penuh (N=35 Subjek)

Pengujian lapangan terhadap 35 subjek diselesaikan secara bertahap, dengan hasil:

- Dari target 35.000 baris per database (70.000 baris total), tercatat **34.965 baris untuk MySQL** (missing 35 baris) dan **34.959 baris untuk PostgreSQL** (missing 41 baris)  *completeness* keseluruhan **99,89%** (69.924/70.000).
- Data yang hilang **tidak diulang atau dibuang**, melainkan didokumentasikan sebagai temuan riset karena menjadi bukti nyata perbedaan tingkat keandalan kedua database saat menangani beban puncak.
- Satu *outlier* signifikan ditemukan pada data *loss* MySQL: **Subjek 6 kehilangan 15 baris data** dalam satu sesi, jauh melampaui batas atas IQR (2,5) dari sebaran data *loss* subjek lain — diinvestigasi dan disimpulkan sebagai bukti *bottleneck* antrean web server pada beban puncak, bukan *error* sensor.
- Seluruh data hasil rekaman dipindahkan dari dashboard ke spreadsheet master `data RTI EXEL.xlsx`, siap dijadikan input untuk analisis statistik pada Tahap 4.

---

## Catatan Lingkungan Pengujian

- Pengujian dilakukan pada jaringan Wi-Fi publik yang sama untuk ESP32 dan laptop server (bukan jaringan terisolasi), merepresentasikan kondisi implementasi IoT nyata sesuai batasan masalah proposal.
- Standarisasi perangkat server dikunci pada satu unit (Intel Core i3 Gen 12/13, RAM 8/16 GB DDR5, Windows 11) untuk menghindari bias spesifikasi antar-sesi.
- Refleksi penting dari pengalaman sebelumnya: melaporkan hasil dari *single run* sangat berisiko bias (misalnya kebetulan laptop baru dinyalakan sehingga masih sangat cepat, atau kebetulan ada delay Wi-Fi). Oleh karena itu, penelitian ini memakai **35 subjek berbeda** (bukan 1 subjek diulang 35 kali) agar database dipaksa menangani variasi data detak jantung yang natural dari banyak sumber, sehingga hasil rata-ratanya memenuhi syarat kecukupan sampel untuk uji statistik parametrik/non-parametrik.