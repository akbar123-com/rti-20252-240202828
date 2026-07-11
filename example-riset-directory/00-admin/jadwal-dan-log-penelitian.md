# Jadwal & Log Pelaksanaan Penelitian

Catatan kronologis pelaksanaan tiap tahap (sumber: riwayat commit git & dokumen `09-docs/tahap-N-*.md`). Tanggal mengikuti `git log`.

## Log Pelaksanaan

| Tanggal | Tahap | Aktivitas | Referensi |
|---|---|---|---|
| 2026-05-15 s.d. 2026-05-20 | Tahap 1 & 2 | Perancangan arsitektur database lokal (MySQL dan PostgreSQL); perakitan hardware sensor detak jantung MAX30102 dengan ESP32; setup environment XAMPP dan web server lokal. | `09-docs/tahap-1-arsitektur-dan-skema-database.md`, `09-docs/tahap-2-implementasi-hardware.md` | 
| 2026-06-05 s.d. 2026-06-10 | Tahap 3 | Eksekusi pengumpulan data; mikrokontroler mengirim data frekuensi tinggi ke server lokal; menjalankan 35 run (replikasi) untuk tiap database. Ditemukan anomali *data loss* (15 baris) pada MySQL saat beban puncak. | `04-data/raw-data-35-run.csv`, `09-docs/tahap-3-pengujian-sensor.md` |
| 2026-06-12 s.d. 2026-06-15 | Tahap 4 | Analisis statistik data kuantitatif menggunakan SPSS & aplikasi web analisis custom; eksekusi *Paired Samples T-Test* (Latency) dan *Wilcoxon Signed-Rank Test* (RAM); perhitungan rata-rata *latency* (MySQL 2.12 ms vs PostgreSQL 4.46 ms) dan *Effect Size* (Cohen's d = -4.01). | `06-output/spss-result.png`, `09-docs/tahap-4-analisis-data.md` |
| 2026-06-20 s.d. 2026-06-25 | Tahap 5 | Penyusunan draf laporan evaluasi dan outline paper (IMRAD); analisis *failure/bottleneck* pada MySQL; penyusunan *consistency matrix* dan persiapan slide presentasi akhir. | `07-manuskrip/naskah-jurnal.md`, `08-laporan/laporan-penelitian.md`|
| 2026-06-28 | Tahap 6 | Pembuatan struktur direktori riset utama (*repository setup*); inisialisasi jadwal dan log penelitian; persiapan perakitan naskah final. | `00-admin/jadwal-dan-log-penelitian.md` |  


## Status Ringkas

- **Tahap 1–4**: Selesai (dataset final: 35 run / replikasi per pengujian sistem database, data sensor MAX30102, 2026-06-15).
- **Tahap 5-6**: Konten draf naskah selesai dengan statistik n=35 (termasuk analisis uji beda Paired Samples T-Test/Wilcoxon, penemuan anomali 15 data loss MySQL, dan consistency matrix); menyisakan finalisasi struktur repositori dan pemindahan draf IMRAD ke dalam template laporan akhir.

## Item Tindak Lanjut (Checklist Sebelum Submission)

- [x] Lengkapi matriks literatur dengan paper *related work* nyata (`02-literatur/matriks-literatur.md`)  referensi berfokus pada arsitektur database IoT medis terverifikasi.
- [x] Verifikasi anomali 15 *data loss* pada MySQL melalui *log error* Apache/XAMPP  terkonfirmasi diakibatkan oleh *bottleneck* antrean *disk I/O* saat beban puncak sensor.
- [ ] Tetapkan bahasa final naskah (Indonesia/Inggris) sesuai dengan panduan (*author guidelines*) jurnal tujuan.
- [ ] Pindahkan konten `07-manuskrip/naskah-jurnal.md` / `.docx` ke template *format* jurnal tujuan (misal: template Jurnal PETIK).
- [ ] Finalisasi penempatan visual (grafik *bar chart* latensi dan tabel *paired samples t-test*) sesuai gaya selingkung jurnal.
- [ ] Review akhir seluruh klaim numerik (rata-rata 2.12 ms vs 4.46 ms, Cohen's d = -4.01) agar konsisten antar dokumen (lihat daftar pada `07-manuskrip/00-outline.md`). Termasuk memastikan klaim keandalan/data loss PostgreSQL (total 41 baris, bukan 0) konsisten di seluruh naskah.

## Korespondensi

*(belum ada  tambahkan catatan korespondensi dengan pembimbing/editor jurnal di sini saat tersedia)*