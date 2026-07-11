# Hasil Olahan Data & Analisis Statistik

Analisis data hasil pengujian komparatif **Latency** dan **RAM Usage** antara MySQL dan PostgreSQL pada sistem monitoring sensor detak jantung (MAX30102), menggunakan metode **Paired Samples T-Test** (dengan Wilcoxon Signed-Rank Test sebagai alternatif non-parametrik bila asumsi normalitas selisih tidak terpenuhi).

Dihasilkan dari data mentah `04-data/data_RTI_EXEL.xlsx` (35 run/replikasi berpasangan per database), diproses melalui SPSS dan aplikasi web analisis custom (`web-analysis/index.php`).

## tables/

| File | Isi |
|---|---|
| `descriptive_stats.csv` | Statistik deskriptif (mean, std, min, max) Latency & RAM per database (MySQL vs PostgreSQL), atas 35 replikasi |

---

## 1. Analisis Deskriptif (Descriptive Statistics)

| Metrik | Database | N | Mean | Std. Deviation | Min | Max |
|---|---|---|---|---|---|---|
| Latency (ms) | MySQL | 35 | 2.1240 | 0.3598 | 1.5123 | 2.7412 |
| Latency (ms) | PostgreSQL | 35 | 4.4580 | 0.5549 | 3.5491 | 5.4120 |
| RAM Usage (%) | MySQL | 35 | 86.6500 | 2.6118 | 81.38 | 91.10 |
| RAM Usage (%) | PostgreSQL | 35 | 86.2534 | 1.8648 | 80.09 | 88.95 |

**Interpretasi:**
Secara deskriptif, MySQL rata-rata menyelesaikan operasi simpan data **2.3340 ms** lebih cepat dibandingkan PostgreSQL. Pada metrik RAM, kedua database menunjukkan beban rata-rata yang relatif setara (selisih hanya 0.3966%), jauh lebih kecil dibandingkan selisih pada metrik Latency.

---

## 2. Analisis Hubungan (Paired Samples Correlations)

> **Catatan validasi:** Nilai r dan Sig. (2-tailed) di tabel ini dihitung independen dari data mentah (`04-data/data_RTI_EXEL.xlsx`) menggunakan formula Pearson correlation, **bukan** hasil langsung dari SPSS atau aplikasi web analisis (kedua tool tersebut belum pernah menghasilkan tabel "Paired Samples Correlations" untuk data ini). Untuk validasi resmi, jalankan **Analyze > Compare Means > Paired-Samples T Test** di SPSS (bukan Explore) — tabel Correlations akan otomatis muncul, atau gunakan **Analyze > Correlate > Bivariate**.

| Metrik | N | Koefisien Korelasi (r) | Sig. (2-tailed) |
|---|---|---|---|
| Latency (MySQL vs PostgreSQL) | 35 | 0.249 | 0.150 |
| RAM (MySQL vs PostgreSQL) | 35 | 0.440 | 0.008 |

**Interpretasi:**
Korelasi Latency antar kedua sistem lemah dan **tidak signifikan** (r=0.249, p=0.150 > 0.05) performa latency MySQL pada suatu run tidak dapat memprediksi performa latency PostgreSQL pada run yang sama. Korelasi RAM tergolong sedang dan **signifikan** (r=0.440, p=0.008 < 0.05), menunjukkan kedua database cenderung mengalami kenaikan/penurunan beban RAM bersamaan, kemungkinan dipengaruhi kondisi resource host yang sama saat pengujian.

---

## 3. Uji Hipotesis Perbedaan (Paired Samples Test)

### 3.1 Latency

Uji normalitas selisih (Shapiro-Wilk): W=0.948, p=0.098 (**Normal**) → **Paired Samples T-Test dipakai**.

| Mean Selisih | Sd Selisih | df | t | Sig. (2-tailed) | Cohen's d |
|---|---|---|---|---|---|
| -2.3340 | 0.5814 | 34 | -23.7493 | 0.000000 | -4.0144 |

**Keputusan:** Sig. (2-tailed) = 0.000 < 0.05 → **H0 ditolak, H1 diterima**. Terdapat perbedaan Latency yang sangat signifikan antara MySQL dan PostgreSQL, dengan effect size *huge* (|d| = 4.01).

### 3.2 RAM Usage

Uji normalitas selisih (Shapiro-Wilk): W=0.871, p=0.0007 (**Tidak Normal**) → **Wilcoxon Signed-Rank Test dipakai** (Paired T-Test hanya sebagai pembanding, tidak dipakai sebagai keputusan akhir).

| | Median A (MySQL) | Median B (PostgreSQL) | W Statistic | z | Asymp. Sig. (2-tailed) |
|---|---|---|---|---|---|
| Wilcoxon | 86.60 | 86.45 | 259.5 | 0.9009 | 0.367650 |

*(Pembanding Paired T-Test: Mean Selisih=0.3966, Sd Selisih=2.4530, df=34, t=0.9565, Sig.=0.345596, Cohen's d=0.1617 tidak dipakai karena asumsi normalitas selisih dilanggar)*

**Keputusan:** Asymp. Sig. (2-tailed) = 0.368 > 0.05 → **H0 diterima, H1 ditolak**. Tidak terdapat perbedaan RAM Usage yang signifikan antara MySQL dan PostgreSQL.

---

## 4. Kesimpulan Akhir Penelitian

Berdasarkan rangkaian uji Paired Samples T-Test dan Wilcoxon Signed-Rank Test di atas, dapat disimpulkan:

1. **Latency**: Terdapat perbedaan rata-rata waktu simpan yang **sangat signifikan** antara MySQL dan PostgreSQL (p<0.001, Cohen's d=-4.01, efek sangat besar). MySQL terbukti secara nyata lebih cepat (2.12 ms) dibandingkan PostgreSQL (4.46 ms).
2. **RAM Usage**: **Tidak terdapat** perbedaan signifikan (p=0.368) antara kedua database — klaim salah satu sistem "lebih ringan RAM-nya" tidak didukung data.
3. Dari sisi kecepatan (efficiency), **MySQL terbukti lebih unggul** untuk kebutuhan insert data sensor real-time frekuensi tinggi. Namun keunggulan ini **tidak disertai** keunggulan pada beban RAM, sehingga klaim performa MySQL harus dibatasi secara spesifik pada metrik Latency saja, bukan digeneralisasi ke seluruh aspek resource usage.