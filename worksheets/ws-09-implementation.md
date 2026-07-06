# WS-09: Implementation & Environment

> **Bab 9 — Implementasi Riset & Kontrol Lingkungan**

---

## Ringkasan Materi

### Implementasi Riset ≠ Coding Biasa

Tujuan implementasi riset bukan membuat software yang berfungsi, melainkan membangun **instrumen pengukuran yang konsisten**. Setiap modul harus di-mapping ke variabel (dari Bab 6), parameter harus config-driven, dan logging aktif dari hari pertama.

> **Mengapa reproducibility penting?** Sains dibangun di atas prinsip verifikasi — temuan harus bisa dikonfirmasi oleh peneliti lain. _Replicability crisis_ yang terjadi di banyak paper riset ML/AI disebabkan oleh environment tidak terdokumentasi: orang lain tidak bisa reproduksi, hasil diragukan, kepercayaan terhadap temuan hilang. Prinsip: **dokumentasi environment = snapshot kredibilitas riset Anda.**

### Reproducible Implementation Model

```
Design → Implementation → Environment Setup → Execution Consistency → Reproducibility → Trustworthy Result
```

Setiap transisi memiliki syarat:
- Design → Implementation: kode sesuai mapping variabel-ke-komponen
- Implementation → Environment: versi, dependency, seed, path, OS eksplisit
- Environment → Consistency: seed terkunci, urutan deterministik
- Consistency → Reproducibility: dokumentasi lengkap
- Reproducibility → Trust: siapa pun ikuti dokumentasi → hasil sama/serupa

### Repeatability vs Reproducibility

| Level | Peneliti | Environment | Hasil |
|-------|---------|-------------|-------|
| **Repeatability** | Sama | Sama | Sama persis |
| **Reproducibility** | Berbeda | Berbeda (ikuti docs) | Sama/serupa |

Capai **repeatability** dulu, baru **reproducibility**.

### Engineering vs Research Perspective

| Aspek | Engineering | Research |
|-------|-----------|---------|
| Tujuan | Sistem berfungsi untuk user | Instrumen pengukuran konsisten |
| Dependency | Update ke terbaru | Lock di versi spesifik |
| Testing | Unit, integration, E2E | Repeatability test (run ulang → sama?) |
| Dokumentasi | User guide, API docs | Environment spec, execution steps, expected output |
| Config | Default masuk akal | Setiap parameter eksplisit & adjustable |

### Jebakan Kognitif

1. Menunda environment setup → bug sulit dilacak
2. Tidak pakai version control → hasil tidak bisa direkonstruksi
3. Menolak Docker/container → "di laptop saya bisa" saat review
   - **Docker** = teknologi container yang "membungkus" aplikasi beserta seluruh dependency-nya dalam satu unit terisolasi. Hasilnya: kode berjalan identik di laptop, server, maupun reviewer lain. Intro singkat: `docker run -v $(pwd):/workspace environment-image python run_experiment.py`
4. 3× hasil sama ≠ repeatable (bisa cache/state tersimpan)

### Dependency Locking

Mengandalkan "install library terbaru" berbahaya: versi berbeda = perilaku berbeda = hasil tidak reproducible. Praktik:
- **Python**: buat `requirements.txt` dengan versi eksplisit: `scikit-learn==1.3.2`, lalu kunci dengan `pip freeze > requirements.txt`
- **Conda**: gunakan `conda env export > environment.yml` untuk snapshot lengkap
- **Node.js/R/Julia**: gunakan `package-lock.json` / `renv.lock` / `Project.toml` — semua fungsi serupa: lock versi + hash

### Istilah Penting

- **Environment Specification** — Deskripsi lengkap: hardware, OS, runtime, library + versi, config, seed
- **Dependency** — Komponen eksternal yang harus di-lock versinya
- **Config-driven** — Parameter dieksternalisasi ke file konfigurasi, bukan hardcode

---

## Template A.9 — Dokumentasi Setup Eksperimen

```
EXPERIMENT SETUP DOCUMENTATION

Hardware:
  CPU     : ____________________
  RAM     : ____________________
  GPU     : ____________________
  Storage : ____________________

Software:
  OS        : ____________________
  Runtime   : ____________________
  Framework : ____________________

Dependencies:
| Library | Version | Sumber | Hash/Checksum |
|---------|---------|--------|---------------|
|         |         |        |               |
|         |         |        |               |

Konfigurasi:
  Config file     : ____________________
  Random seed     : ____________________
  Hyperparameters : ____________________

Reproducibility Check:
  [ ] Dependency terdokumentasi (requirements.txt / lock file)
  [ ] Seed ditetapkan di semua level (Python, NumPy, framework)
  [ ] Config di version control
  [ ] README instruksi reproduksi lengkap
```

---

## Latihan 1 — Environment Specification

Dokumentasikan environment untuk eksperimen Anda (boleh environment saat ini atau yang direncanakan).

| Komponen | Spesifikasi |
|----------|------------|
| CPU | Intel Core i3 (Gen 12/13), Minimal 4 Core |
| RAM |  8 GB atau 16 GB DDR5|
| GPU | CPU-only (Tidak pakai GPU karena pengujian murni fokus pada tarikan RAM dan prosesor database)|
| OS |  Windows 10 / Windows 11 (Untuk memantau beban menggunakan Task Manager)|
| Runtime | PHP 8.x (Untuk menjalankan skrip penangkap data / data ingestion)|
| Framework | Native PHP (Sengaja tidak memakai framework berat agar metrik waktu simpan benar-benar murni tanpa ada delay tambahan) |
| Random Seed | Tidak ada / N/A (Karena riset ini menggunakan data riil langsung dari sensor fisik MAX30102, bukan data acak buatan komputer)|

**Dependencies (minimal 5):**

| Library | Version | Alasan Dibutuhkan |
|---------|---------|-------------------|
| MYSQL| 8.0 | Sebagai sistem database engine standar bawaan (baseline) yang akan diuji kemampuannya. |
| PostgreSQL | 15.x / 16.x | Sebagai sistem database engine pembanding (intervensi) yang diklaim lebih kuat menahan beban.|
|XAMPP (Apache) | 8.2| Sebagai web server lokal terisolasi untuk menjalankan skrip backend tanpa koneksi internet.|
| PHP PDO (Ekstensi)| Bawaan PHP| Untuk membuat koneksi yang stabil dari kodingan skrip secara langsung ke database MySQL maupun PostgreSQL.|
| ESP32 | 2.x| Untuk menulis program dan menanamkan kodingan pengirim aliran data ke dalam mikrokontroler ESP32. |

---

## Latihan 2 — Repeatability Test Plan

Rancang tes repeatability sederhana: jalankan kode yang sama 3× di environment yang sama.

| Run | Seed | Metrik Utama | Hasil Sama? |
|-----|------|-------------|-------------|
| 1 | Data ESP32 dikunci | Insert Latency (ms) & Beban RAM (%) | — |
| 2 | Data ESP32 dikunci| Insert Latency (ms) & Beban RAM (%)| [ ] Ya / [X] Tidak |
| 3 | Data ESP32 dikunci| Insert Latency (ms) & Beban RAM (%)| [ ] Ya / [X] Tidak |

**Jika hasil berbeda, kemungkinan penyebab:**
> Hasil angka milidetiknya tidak akan 100% sama persis karena fluktuasi suhu pada prosesor laptop i3 (thermal throttling) jika sudah mulai panas , adanya sisa memori (cache) yang belum terhapus sempurna oleh sistem operasi Windows meskipun sudah di-restart, atau sedikit delay dari rambatan sinyal Wi-Fi lokal. Namun, secara rata-rata statistik, tren performanya akan tetap konsisten.

**Checklist kontrol yang sudah diterapkan:**
- [ ] Random seed di-set di semua level
- [X] Tidak ada background process yang mengganggu
- [X] Cache dibersihkan antar-run
- [X] Config file yang sama untuk semua run

---

## Latihan 3 — README Eksperimen

Tulis README minimum untuk eksperimen Anda (6 komponen wajib).

```
# Judul Eksperimen: Analisis Perbandingan Kecepatan Waktu Simpan (Insert Latency) MySQL vs PostgreSQL menggunakan Aliran Data Sensor MAX30102

## 1. Environment
- CPU: Intel Core i3 (Gen 12/13)
- RAM: 8 GB / 16 GB DDR5
- GPU: CPU-only (Intel UHD Graphics)
- OS: Windows 11
- Runtime: PHP 8.x (Native)
- Database: MySQL 8.0 & PostgreSQL 15.x/16.x

## 2. Installation
1. Install XAMPP untuk menjalankan web server (Apache) dan MySQL.
2. Install PostgreSQL secara terpisah.
3. Buat database dan import skema tabel (struktur tabel kembar untuk kedua database).
4. Pindahkan folder skrip PHP penangkap data ke dalam folder `htdocs` di XAMPP.
5. Buka Arduino IDE, lalu upload source code (.ino) ke dalam mikrokontroler ESP32.

## 3. Data
- Sumber: Perangkat keras sensor fisik MAX30102 via mikrokontroler ESP32 (Continuous streaming / dikirim beruntun secara real-time).
- Format: Angka detak jantung (BPM) dan Timestamp milidetik.
- Ukuran: Target 10.000 baris rekaman data untuk setiap sesi pengujian database.

## 4. Execution
1. Pastikan laptop terhubung ke jaringan Wi-Fi lokal yang sama dengan ESP32 (tanpa internet).
2. Nyalakan service MySQL di XAMPP (pastikan PostgreSQL mati).
3. Jalankan skrip backend PHP di browser, lalu nyalakan alat ESP32 untuk mulai menembakkan data.
4. Pantau persentase RAM/CPU melalui Task Manager.
5. Setelah 10.000 data masuk, catat hasil milidetik rata-ratanya, matikan MySQL, bersihkan cache, nyalakan PostgreSQL, lalu ulangi langkah 3 dan 4.

## 5. Configuration
- `koneksi.php`: File untuk mengatur switch koneksi (Host, DB Name, User, Password) ke MySQL atau PostgreSQL.
- `esp32_sender.ino`: Konfigurasi SSID Wi-Fi lokal, IP Address laptop server, dan delay kecepatan tembakan data.

## 6. Expected Output
- Tampilan web akan memunculkan teks hasil berupa angka rata-rata waktu simpan (insert latency) dalam hitungan milidetik (ms).
- Pada komputer server (Task Manager), terlihat fluktuasi grafik persentase beban pemakaian RAM dan CPU selama proses data masuk.

```

---

## Refleksi

> Apakah eksperimen Anda saat ini bisa direproduksi oleh orang lain tanpa bantuan Anda? Komponen apa yang masih hilang?

**Level saat ini:** [X] Repeatability / [ ] Reproducibility / [ ] Belum keduanya
**Komponen yang belum terdokumentasi:**
> Eksperimen ini baru di tahap Repeatability (bisa diulang oleh saya sendiri di lingkungan yang sama). Orang lain belum bisa langsung mereproduksinya secara sempurna (Reproducibility) karena masih ada komponen fisik dan kode yang belum dilampirkan secara publik. Komponen yang masih hilang antara lain: diagram wiring (jalur kabel) fisik antara sensor MAX30102 dengan ESP32, rincian struktur tabel SQL-nya, serta source code lengkap (skrip PHP dan kode Arduino) yang belum diunggah ke repository publik seperti GitHub.
