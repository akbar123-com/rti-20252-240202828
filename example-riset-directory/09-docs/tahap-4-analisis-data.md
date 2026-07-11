# Tahap 4 Ekstraksi Data & Analisis SPSS

**Status:** Selesai data mentah telah divalidasi, dibersihkan, dan seluruh prosedur pengujian hipotesis (Paired Samples T-Test & Wilcoxon Signed-Rank Test) telah dieksekusi menggunakan IBM SPSS Statistics, diverifikasi silang lewat aplikasi web analisis kustom. Hasil tabel dan citra keluaran diarsipkan di folder `06-output/`.

**Bergantung pada:** [tahap-3-pengujian-k6.md](tahap-3-pengujian-k6.md)
**File Terkait:** [rencana-penelitian.md](rencana-penelitian.md)

---

## Tujuan

Mengolah data mentah hasil eksperimen lapangan (`04-data/data RTI EXEL.xlsx`)  yang memuat rekapitulasi *insert latency* dan beban RAM dari 35 subjek berpasangan menjadi bentuk statistik deskriptif, pemenuhan uji asumsi normalitas, serta pengujian hipotesis inferensial untuk draf naskah jurnal di Tahap 5.

## Deliverable

- [x] Validasi kelengkapan data (*completeness check*): 34.965/35.000 baris MySQL, 34.959/35.000 baris PostgreSQL tercatat.
- [x] Pembersihan data (*preprocessing*): penghapusan karakter satuan ("ms", "%") dan penyeragaman pemisah desimal pada 70 dari 70 baris data (100%), tanpa menghapus baris apa pun.
- [x] Perhitungan statistik deskriptif (*mean*, standar deviasi, minimum, maksimum) per database untuk metrik *insert latency* dan beban RAM.
- [x] Perhitungan korelasi Pearson berpasangan antar-kedua database untuk tiap metrik.
- [x] Pelaksanaan Uji Normalitas (Shapiro-Wilk) pada selisih (*difference score*) tiap pasangan pengamatan, untuk menentukan uji beda yang dipakai.
- [x] Eksekusi **Paired Samples T-Test** untuk metrik *insert latency* (selisih berdistribusi normal).
- [x] Eksekusi **Wilcoxon Signed-Rank Test** untuk metrik beban RAM (selisih tidak berdistribusi normal).
- [x] Verifikasi silang seluruh hasil uji-t lewat aplikasi web analisis kustom (PHP, `05-kode/web-analysis/`), dibandingkan langsung dengan output SPSS.
- [x] Penyusunan ringkasan tabel hasil SPSS ke format rapi untuk disalin ke bab Hasil & Analisis naskah jurnal.

---

## Prosedur Analisis yang Diimplementasikan

### Alur Ekstraksi Data (`data RTI EXEL.xlsx` → SPSS)

1. **Tahap Tabulasi**: Mengimpor spreadsheet data mentah 35 subjek berpasangan ke *Data View* SPSS.
2. **Tahap Definisi Variabel**: Mengatur komponen pada *Variable View* (tipe *Numeric* dengan susunan 4 angka desimal untuk kolom `MYSQL_LATENSI`, `PG_LATENSI`, `MYSQL_RAM`, `PG_RAM`).
3. **Pengecekan Asumsi**: Menjalankan uji normalitas Shapiro-Wilk pada selisih (*difference score*) tiap pasangan pengamatan (bukan pada data mentah masing-masing kelompok), karena syarat normalitas uji-t berpasangan berlaku pada variabel selisihnya.
4. **Pengujian Utama**:
   - *Insert Latency* → selisih normal (W=0,948; p=0,098) → *Analyze → Compare Means → Paired-Samples T-Test*.
   - *Beban RAM* → selisih tidak normal (W=0,871; p=0,0007) → *Analyze → Nonparametric Tests → Related Samples (Wilcoxon Signed-Rank Test)*.

---

## Hasil Analisis Statistik

### 1. Statistik Deskriptif (N=35)

| Metrik | Database | Mean | Std. Deviation | Minimum | Maksimum |
|---|---|---:|---:|---:|---:|
| Insert Latency (ms) | MySQL | 2,1240 | 0,3598 | 1,5123 | 2,7412 |
| Insert Latency (ms) | PostgreSQL | 4,4580 | 0,5549 | 3,5491 | 5,4120 |
| Beban RAM (%) | MySQL | 86,6500 | 2,6118 | 81,38 | 91,10 |
| Beban RAM (%) | PostgreSQL | 86,2534 | 1,8648 | 80,09 | 88,95 |

MySQL rata-rata menyimpan data **2,3340 ms lebih cepat** dibanding PostgreSQL; selisih beban RAM kedua database jauh lebih kecil, hanya 0,3966%.

### 2. Korelasi Sampel Berpasangan

| Pasangan Metrik | N | Correlation (r) | Sig. |
|---|---:|---:|---:|
| Latency | 35 | 0,249 | 0,150 |
| RAM | 35 | 0,440 | 0,008 |

### 3. Hasil Pengujian Hipotesis Insert Latency (Paired Samples T-Test)

| Parameter Uji | Nilai |
|---|---:|
| Mean Paired Differences | -2,3340 ms |
| Std. Deviation Differences | 0,5814 |
| Degree of Freedom (df) | 34 |
| Nilai t-hitung | -23,7493 |
| Sig. (2-tailed) | 0,000 |
| Cohen's d | -4,0144 |

Hasil menunjukkan Sig. (2-tailed) = 0,000 < 0,05, sehingga H0 ditolak dan Ha diterima. Terdapat perbedaan *insert latency* yang sangat signifikan secara statistik antara MySQL dan PostgreSQL, dengan *effect size* tergolong sangat besar.

### 4. Hasil Pengujian Hipotesis  Beban RAM (Wilcoxon Signed-Rank Test)

| Parameter Uji | Nilai |
|---|---:|
| Median MySQL / PostgreSQL | 86,60 / 86,45 |
| Wilcoxon W | 259,5 |
| Nilai z | 0,9009 |
| Asymp. Sig. (2-tailed) | 0,367650 |
| *Pembanding:* Paired T-Test (t, tidak dipakai) | 0,9565 (Sig.=0,345596) |

Nilai Asymp. Sig. = 0,368 > 0,05, sehingga H0 diterima. Tidak terdapat perbedaan beban RAM yang signifikan secara statistik antara MySQL dan PostgreSQL pada kondisi pengujian ini.

### 5. Evaluasi Temuan Tambahan: Data Loss

| Skenario | Direncanakan | Tercatat | Hilang | Subjek Tanpa Loss |
|---|---:|---:|---:|---:|
| MySQL (35 subjek) | 35.000 | 34.965 | 35 | 23 dari 35 |
| PostgreSQL (35 subjek) | 35.000 | 34.959 | 41 | 15 dari 35 |

MySQL jarang kehilangan data namun rentan satu lonjakan ekstrem (Subjek 6, 15 baris hilang akibat *bottleneck* antrean web server), sedangkan PostgreSQL lebih sering kehilangan data namun dalam jumlah kecil per insiden, tersebar di lebih banyak subjek. Perbedaan ini **belum diuji signifikansinya secara statistik formal**, sehingga dilaporkan sebagai temuan deskriptif, bukan kesimpulan.

---

## Catatan Penting untuk Penyusunan Tahap 5

- **Keterbatasan Penelitian**: keunggulan MySQL bersifat *metric-specific*  signifikan pada *insert latency*, tidak signifikan pada RAM, dan belum teruji pada *data loss*. Hasil ini menjadi argumen utama pada bab Hasil & Analisis di draf naskah jurnal, dengan penekanan bahwa klaim performa tidak boleh digeneralisasi ke semua metrik sekaligus.
- **Verifikasi Ganda**: seluruh hasil uji-t dari SPSS telah dicocokkan dengan hasil hitung aplikasi web analisis kustom (lihat `HASIL UJI PROGRAM PY SAYA A/B (Paired Samples T-Test).png` di `06-output/`)  angkanya konsisten, memperkuat validitas hasil.
- **Visualisasi & Dokumentasi**: seluruh tangkapan layar hasil SPSS (`HASIL SPSS DATA MENTAH(mean dan std).png`, `HASIL UJI SPSS(Paired Samples T-Test).png`) dan berkas `.spv` SPSS diarsipkan di `06-output/`, siap disisipkan ke naskah paper. Ringkasan naratif lengkap ada di [`../06-output/hasilkesimpulan.md`](../06-output/hasilkesimpulan.md).