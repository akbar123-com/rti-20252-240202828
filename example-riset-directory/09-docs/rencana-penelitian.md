# Rencana Penelitian: Perbandingan Performa Database MySQL dan PostgreSQL pada Sensor Detak Jantung

## 1. Ringkasan

| Item | Keterangan |
|---|---|
| **Judul** | Analisis Perbandingan Performa Database MySQL dan PostgreSQL pada Sistem Pemantauan Data Sensor Detak Jantung Frekuensi Tinggi |
| **Target Publikasi** | Sinta 2 (Jurnal RESTI/Telematika) atau Scopus Q3–Q4 |
| **Stack & Alat** | ESP32, Sensor MAX30102, PHP 8.x Native (XAMPP/Apache), MySQL 8.0, PostgreSQL 15.x/16.x, IBM SPSS Statistics, Aplikasi Web Analisis Kustom (PHP) |
| **Masalah** | Sistem pemantauan detak jantung IoT butuh database yang mampu menampung aliran data sensor frekuensi tinggi tanpa kehilangan rekam data; literatur terdahulu hanya menguji MySQL/PostgreSQL dengan data statis, belum dengan aliran data sensor fisik nyata (*continuous streaming*) |
| **Solusi** | Eksperimen berpasangan (*paired design*) 35 run replikasi per database + kontrol lingkungan ketat (jaringan, proses latar belakang, cache) + analisis Paired Samples T-Test / Wilcoxon Signed-Rank Test |

---

## 2. Alur Kerja (Roadmap)

Setiap tahap memiliki file rencana detail tersendiri agar dokumentasi repositori berjalan rapi dan terstruktur:

- [x] **Tahap 1**  [Perancangan Arsitektur & Skema Variabel](tahap-1-arsitektur-dan-skema-database.md)  *Selesai*
- [x] **Tahap 2**  [Implementasi Sistem (ESP32, Skrip PHP, Switch Database)](tahap-2-implementasi-gateway.md)  *Selesai*
- [x] **Tahap 3**  [Pengujian Beban & Eksekusi Data (35 Run Berpasangan)](tahap-3-pengujian-k6.md) *Selesai*
- [x] **Tahap 4**  [Ekstraksi Data & Analisis SPSS](tahap-4-analisis-data.md) *Selesai*
- [x] **Tahap 5**  [Draf Naskah Jurnal](tahap-5-draf-paper.md) *Draf lengkap, siap submit*

---

## 3. Catatan Pengembangan

Dokumen ini berfungsi sebagai indeks utama untuk menavigasi seluruh rangkaian penelitian perbandingan performa database ini. Detail teknis operasional, firmware ESP32, skrip PHP, hingga hasil perhitungan statistik deskriptif maupun inferensial (Paired Samples T-Test & Wilcoxon Signed-Rank Test) dicatat secara berkala pada masing-masing file `tahap-N-*.md` terkait, sejalan dengan pengerjaan worksheet WS-01 s.d. WS-16 di [`../worksheets/`](../../worksheets/) dan naskah ilmiah di [`../07-manuskrip/`](../07-manuskrip/).