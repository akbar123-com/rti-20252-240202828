# 00-outline

Outline, peta sumber data, dan daftar klaim kunci untuk draf manuskrip ilmiah **Tahap 5**.

---

## 1. Peta Sumber Data & Keselarasan Berkas

Dokumen ini berfungsi sebagai peta kendali untuk memastikan seluruh data statistik yang ditulis pada naskah bersumber dari data empiris yang valid (bukan dari folder contoh/template):

* **Sumber Data Mentah:** [`../04-data/data RTI EXEL.xlsx`](../04-data/data%20RTI%20EXEL.xlsx) 35 pasangan run/replikasi pengujian MySQL vs PostgreSQL, sensor detak jantung MAX30102.
* **Kamus Data:** [`../04-data/data-dictionary.md`](../04-data/data-dictionary.md)
* **Validasi Data:** [`../04-data/ringkasan-validasi.md`](../04-data/ringkasan-validasi.md)
* **Sumber Output Statistik:** `../06-output/SPSS RTI FIKS FINALL (Paired-Samples T Test).spv`, `../06-output/SPSS RTI FINAL (mean dan std).spv`, dan aplikasi web analisis kustom (`../05-kode/web-analysis/`).
* **Ringkasan Hasil Terkonsolidasi:** [`../06-output/hasilkesimpulan.md`](../06-output/hasilkesimpulan.md)
* **Arsitektur & Skema Sistem:** [`../03-teori/Arsitektur dan skema.md`](../03-teori/Arsitektur%20dan%20skema.md)
* **Landasan Teori Statistik:** [`../03-teori/Landasan teori statistik.md`](../03-teori/Landasan%20teori%20statistik.md)
* **Tinjauan Pustaka & Gap:** [`../03-teori/Tinjauan pustaka.md`](../03-teori/Tinjauan%20pustaka.md), [`../02-literatur/matriks-literatur.md`](../02-literatur/matriks-literatur.md)
* **Proposal (rumusan masalah, tujuan, batasan):** [`../01-proposal/proposal-penelitian.md`](../01-proposal/proposal-penelitian.md)
* **Target Publikasi:** Sinta 2 (Jurnal RESTI/Telematika) atau Scopus Q3–Q4.

---

## 2. Struktur Outline Manuskrip (Template IMRAD)

### Judul Penelitian
*Analisis Perbandingan Performa Database MySQL dan PostgreSQL pada Sistem Pemantauan Data Sensor Detak Jantung Frekuensi Tinggi*

### Abstrak (Abstract)
Ringkasan latar belakang kebutuhan database real-time untuk IoT medis, metode eksperimen (paired design, 35 run replikasi, sensor MAX30102 + ESP32), hasil uji statistik (Paired Samples T-Test untuk Latency, Wilcoxon Signed-Rank untuk RAM), dan kesimpulan. Tersedia dalam versi Bahasa Indonesia dan English.

### 1. Pendahuluan
* **Latar Belakang:** kebutuhan sistem pemantauan kesehatan IoT real-time yang andal; peran krusial pemilihan RDBMS (MySQL vs PostgreSQL) di sisi backend.
* **Rumusan Masalah:** belum ada studi yang menguji langsung ketahanan MySQL vs PostgreSQL terhadap aliran data sensor fisik berfrekuensi tinggi (bukan data statis).
* **Tujuan & Kontribusi:** mengukur *insert latency*, beban RAM, dan keandalan (data loss) kedua database secara empiris pada kondisi *continuous streaming* nyata.

### 2. Tinjauan Pustaka
* Landasan teori: sensor MAX30102 & *continuous streaming*, arsitektur RDBMS (MySQL vs PostgreSQL), konsep *insert latency* & *bottleneck*.
* *Related work*: 5 studi terdahulu (IoT medis vs benchmark database statis) dan pemetaan *research gap*.
* Landasan teori statistik: Paired Samples T-Test & Wilcoxon Signed-Rank Test.

### 3. Metodologi
* **Desain Penelitian:** eksperimen terkontrol, desain berpasangan (*paired/within-condition*), n = 35 run replikasi per database.
* **Arsitektur Sistem:** ESP32 + MAX30102 → Wi-Fi lokal (jaringan publik) → skrip PHP native (XAMPP/Apache) → MySQL 8.0 / PostgreSQL 15–16 (bergantian).
* **Variabel:** IV = jenis database; DV = *insert latency* (ms) & beban RAM (%); CV = frekuensi kirim data, jaringan, environment server.
* **Analisis:** uji normalitas selisih (Shapiro-Wilk) → Paired Samples T-Test (jika normal) / Wilcoxon Signed-Rank Test (jika tidak normal); Cohen's d untuk effect size.

### 4. Hasil dan Analisis
* **Statistik Deskriptif:** Latency MySQL 2.1240 ms vs PostgreSQL 4.4580 ms; RAM MySQL 86.6500% vs PostgreSQL 86.2534%.
* **Korelasi Berpasangan:** Latency r=0.249 (p=0.150, tidak signifikan); RAM r=0.440 (p=0.008, signifikan).
* **Uji Hipotesis:** Latency — Paired T-Test, t(34)=-23.7493, p<0.001, Cohen's d=-4.0144 (signifikan, efek sangat besar). RAM Wilcoxon Signed-Rank, z=0.9009, p=0.368 (tidak signifikan).
* **Data Loss (temuan tambahan):** MySQL 35 baris hilang (1 lonjakan ekstrem 15 baris/run), PostgreSQL 41 baris hilang (lebih sering, 20 dari 35 run mengalami loss).

### 5. Kesimpulan dan Saran
* MySQL terbukti signifikan lebih cepat pada *insert latency*; tidak ada perbedaan signifikan pada beban RAM; klaim keandalan (data loss) tidak dapat disimpulkan tanpa uji statistik lanjutan.
* Saran: pengujian statistik formal pada variabel data loss, replikasi dengan N lebih besar, variasi kondisi jaringan, serta perbandingan dengan database NoSQL.

---

## 3. Daftar Klaim Kunci (Key Claims) yang Harus Konsisten

1. **Jumlah sampel:** n = 35 pasangan run replikasi per database (target 1.000 baris/sesi/run).
2. **Rata-rata Latency:** MySQL = **2.1240 ms** (SD 0.3598), PostgreSQL = **4.4580 ms** (SD 0.5549).
3. **Selisih Latency:** Mean Paired Differences = **-2.3340 ms**, SD selisih = 0.5814.
4. **Uji Latency:** t(34) = **-23.7493**, Sig. (2-tailed) = **0.000**, Cohen's d = **-4.0144** (efek *huge*). Uji normalitas selisih: Shapiro-Wilk W=0.948, p=0.098 (normal) → Paired T-Test dipakai.
5. **Rata-rata RAM:** MySQL = **86.6500%** (SD 2.6118), PostgreSQL = **86.2534%** (SD 1.8648).
6. **Uji RAM:** normalitas selisih tidak terpenuhi (Shapiro-Wilk W=0.871, p=0.0007) → **Wilcoxon Signed-Rank Test** dipakai: z = 0.9009, Asymp. Sig. (2-tailed) = **0.368** (tidak signifikan).
7. **Korelasi berpasangan:** Latency r=0.249 (p=0.150); RAM r=0.440 (p=0.008).
8. **Data loss:** total MySQL = **35 baris** (dari 34.965/35.000 target; 23 dari 35 run tanpa loss, 1 outlier ekstrem 15 baris/run); total PostgreSQL = **41 baris** (dari 34.959/35.000 target; hanya 15 dari 35 run tanpa loss). **Data loss belum diuji signifikansinya secara statistik** — tidak boleh diklaim sebagai "PostgreSQL lebih andal/tidak andal" tanpa uji lanjutan.
9. **Perangkat & environment:** ESP32 + sensor MAX30102, Wi-Fi jaringan publik (bukan terisolasi), server Intel Core i3 Gen 12/13, RAM 8/16 GB DDR5, Windows 11, PHP 8.x native via XAMPP/Apache, MySQL 8.0, PostgreSQL 15.x/16.x.
10. **Threshold keputusan:** H1 diterima jika Sig. (2-tailed) < 0,05 **dan** selisih rata-rata ≥ 10%.