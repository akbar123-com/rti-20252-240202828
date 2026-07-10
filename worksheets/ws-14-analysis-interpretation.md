# WS-14: Analysis, Interpretation & Failure Analysis

> **Bab 14 — Analisis Data, Interpretasi & Failure Analysis**

---

## Ringkasan Materi

### Data → Knowledge Model

```
Data → Analysis → Interpretation → Explanation → Knowledge
```

Tiga level yang berbeda:
- **Analysis** — "Apa yang terjadi?" (deskriptif + inferensial)
- **Interpretation** — "Apa artinya?" (konteks RQ + literatur)
- **Failure Analysis** — "Mengapa tidak berhasil?" (boundary conditions)

### Beyond p-value

**Statistical significance ≠ practical significance.** Selalu laporkan:
1. p-value (signifikansi statistik)
2. Effect size (besarnya efek)
3. Confidence interval (rentang ketidakpastian)

| Effect Size (Cohen's d) | Interpretasi |
|-------------------------|-------------|
| < 0.2 | Small |
| 0.2 – 0.8 | Medium |
| > 0.8 | Large |

### Pemilihan Uji Statistik

| Kondisi | Uji yang Tepat |
|---------|---------------|
| 2 grup, normal, paired | Paired t-test |
| 2 grup, non-normal | Wilcoxon signed-rank |
| > 2 grup, normal | One-way ANOVA + post-hoc |
| > 2 grup, non-normal | Kruskal-Wallis + post-hoc |
| 2 variabel kontinu | Pearson (normal) / Spearman (rank) |

### Failure Analysis as Contribution

Hipotesis yang ditolak adalah **temuan yang berharga**:

| Dataset | New (F1) | Baseline (F1) | p-value | Cohen's d |
|---------|---------|--------------|---------|-----------|
| DS-1 (small, clean) | 94.2±1.1 | 89.3±1.5 | <0.001 | **3.7** |
| DS-4 (medium, noisy) | 78.3±3.2 | 82.1±2.8 | 0.008 | **-1.3** |
| DS-5 (large, noisy) | 71.6±4.1 | 80.5±3.0 | <0.001 | **-2.5** |

**Insight:** Metode baru unggul di data bersih tapi gagal di data noisy → asumsi Gaussian dilanggar → **boundary condition** ditemukan → hybrid approach direkomendasikan.

**Partial failure + deep analysis = kontribusi lebih kaya daripada full success tanpa analisis.**

### Limitation Types

| Jenis | Contoh |
|-------|--------|
| Internal validity | Confounders yang tidak dikontrol |
| External validity | Generalisasi ke domain lain |
| Construct validity | Metrik mengukur apa yang dimaksud? |
| Statistical limitation | Sample size, asumsi distribusi |

### Jebakan Kognitif

1. "Signifikan statistik = penting secara praktis" → cek effect size
2. "Hipotesis tidak didukung → cari sudut baru" → p-hacking
3. "Kegagalan tidak perlu dilaporkan detail" → missed insight
4. "Limitasi cukup disebutkan, tidak perlu dianalisis" → kedalaman hilang

---

## Template A.14 — Analysis & Interpretation Report

```
ANALYSIS & INTERPRETATION

1. Statistik Deskriptif:
   | Skenario | Mean | Std | Median | Min | Max | n |
   |----------|------|-----|--------|-----|-----|---|
   |          |      |     |        |     |     |   |

2. Uji Hipotesis:
   Uji yang digunakan  : ____________________
   Justifikasi          : ____________________
   Hasil: p = ____, effect size (d/r/η²) = ____
   CI 95%               : [____, ____]

3. Keputusan:
   [ ] H₀ ditolak → H₁ diterima
   [ ] H₀ tidak ditolak

4. Interpretasi:
   Hubungan ke RQ       : ____________________
   Practical significance: ____________________
   Perbandingan literatur: ____________________

5. Limitation:
   | Jenis | Ancaman | Dampak | Mitigasi |
   |-------|---------|--------|----------|
   |       |         |        |          |

6. Failure Analysis (jika H₀ tidak ditolak):
   Penyebab potensial  : ____________________
   Boundary condition   : ____________________
   Insight              : ____________________
```

---

## Latihan 1 — Pemilihan Uji Statistik

Tentukan uji statistik yang tepat untuk eksperimen Anda.

| Pertanyaan | Jawaban |
|-----------|---------|
| Berapa grup yang dibandingkan? | 2 (MySQL dan PostgreSQL)|
| Apakah data berpasangan (paired)? | Ya (Berpasangan). Setiap baris data merepresentasikan satu run/percobaan yang sama, di mana MySQL dan PostgreSQL diukur pada kondisi sensor & waktu yang identik dalam satu siklus pengujian — bukan dua sampel acak yang independen. |
| Apakah distribusi normal? (uji normalitas) | Bervariasi per metrik, diuji pada variabel **selisih** (bukan data mentah), karena syarat paired test ada di distribusi selisihnya. Latency: Ya, normal (Shapiro-Wilk selisih, W=0,948; p=0,098 > 0,05). RAM: Tidak normal (Shapiro-Wilk selisih, W=0,871; p<0,001). |
| **Uji yang dipilih:** | Paired Samples T-Test (untuk Latency) & Wilcoxon Signed-Rank Test (untuk RAM) |
| **Justifikasi:** | Karena data berpasangan (paired), uji yang tepat bukan Independent T-Test melainkan uji berpasangan. Untuk Latency, selisih antar-pasangan terdistribusi normal sehingga Paired Samples T-Test dipakai. Untuk RAM, selisihnya tidak normal sehingga alternatif non-parametrik Wilcoxon Signed-Rank Test yang dipakai agar tidak melanggar asumsi. |

**Effect size yang akan dilaporkan:** [x] Cohen's d / [ ] Eta-squared / [ ] Lainnya: ____

---

## Latihan 2 — Interpretasi Hasil

Gunakan data berikut (atau data riil Anda) untuk berlatih interpretasi.

**Data:**
| Model | Accuracy (mean ± std) | n |
|-------|----------------------|---|
| MySQL | 2.12 ± 0.36 ms | 35 |
| PostgreSQL | 4.46 ± 0.55 ms | 35 |

p < 0.001, Cohen's d = -4.01 (magnitude 4.01), CI 95% = [2.13, 2.53] ms (selisih rata-rata PostgreSQL lebih lambat dari MySQL)

| Aspek | Interpretasi |
|-------|-------------|
| Signifikansi statistik | p < 0.001 signifikan pada a = 0.05 Terdapat perbedaan kecepatan waktu simpan (latency) yang sangat meyakinkan secara statistik antara MySQL dan PostgreSQL (Paired Samples T-Test, t(34) = -23,75). |
| Effect size | d = -4,01 (magnitude 4,01) → Huge effect (Efek sangat besar, jauh di atas ambang 0,8). Perbedaan performa kedua database sangat mencolok dan absolut, bukan diakibatkan oleh variasi acak atau kebetulan. |
| Practical significance | Berdampak kritis di dunia nyata. Selisih keterlambatan PostgreSQL yang mencapai sekitar 2,33 ms per baris data (CI 95%: 2,13–2,53 ms) mungkin terlihat sepele. Namun, jika dihadapkan pada aliran data sensor detak jantung frekuensi tinggi secara terus-menerus, delay ini akan menumpuk dan menyebabkan bottleneck sistem.|
| Hubungan ke RQ | Menjawab Rumusan Masalah. Pengujian membuktikan secara empiris bahwa MySQL jauh lebih ringan dan optimal untuk menangani operasi insert data sensor real-time berkecepatan tinggi dibandingkan PostgreSQL. |
| Perbandingan literatur | Sejalan dengan literatur. Hasil ini menguatkan teori bahwa database relasional yang berfokus pada kecepatan baca-tulis sederhana (seperti MySQL) lebih tangguh untuk sistem IoT, dibandingkan database berskala enterprise (PostgreSQL) yang memakan waktu lebih lama karena proses validasi data yang ketat. |

---

## Latihan 3 — Failure Analysis

Latih kemampuan failure analysis: hipotesis TIDAK didukung. Apa yang bisa dipelajari?

**Skenario:** Metode baru Anda mendapat F1 = 83.2%, baseline = 84.7%. p = 0.12 (tidak signifikan).

| Pertanyaan | Jawaban |
|-----------|---------|
| Apakah ini "gagal"? | Bukan gagal total. Ini adalah negative result yang valid. Hipotesis yang tidak terdukung secara statistik (p > 0.05) tetap memberikan informasi penting bahwa metode baru tersebut tidak lebih superior dari metode lama (baseline). |
| Kemungkinan penyebab? | Metode baru mungkin terlalu kompleks (overfitting pada data tertentu) atau penambahan arsitekturnya justru memicu noise yang membuat akurasinya merosot di bawah baseline yang lebih sederhana. |
| Boundary condition? | Metode baru ini mungkin tidak cocok untuk dataset umum. Kemungkinan metode ini baru akan mengungguli baseline jika diuji pada kondisi data spesifik (misal: data dengan tingkat noise ekstrem atau dataset yang jauh lebih masif). |
| Insight yang bisa diambil? | Kesederhanaan terkadang lebih baik (Occam's razor). Kompleksitas komputasi yang tinggi tidak selalu berbanding lurus dengan peningkatan performa. |
| Apakah layak dilaporkan? Mengapa? | Sangat layak. Melaporkan hasil negatif ini akan mencegah terjadinya publication bias. Peneliti lain di masa depan jadi tahu batasan metode ini dan tidak membuang waktu mengulangi eksperimen buntu yang sama.  |

**Limitation terkait:**
| Jenis | Ancaman | Dampak |
|-------|---------|--------|
| Statistical | Jumlah sampel data uji (N) terlalu kecil. | Power test rendah, sehingga uji statistik gagal mendeteksi perbedaan nyata yang mungkin ada (terjadi Type II Error).|
|Methodological |Pemilihan metrik F1 mungkin kurang sensitif untuk distribusi kelas data yang sangat timpang (class imbalance). |Penilaian terhadap performa asli metode baru menjadi bias atau tidak terukur secara proporsional.|
| Environmental |Perbedaan latensi jaringan atau fluktuasi beban CPU saat menjalankan metode baru vs baseline. |Hasil skor menjadi tidak stabil dan perbandingan menjadi kurang adil (apple-to-apple). |

---

## Refleksi

> Apakah "failure" dalam riset benar-benar gagal, atau justru kontribusi? Bagaimana failure analysis mengubah cara Anda melihat hasil negatif?

> Dalam dunia riset akademik, hipotesis yang ditolak bukanlah sebuah aib atau "kegagalan", melainkan sebuah kontribusi ilmiah yang sama berharganya dengan penemuan positif. Selama eksperimen dirancang dengan metodologi yang ketat, jujur, dan parameternya jelas (seperti saat menguji batas bottleneck arsitektur server secara terisolasi), hasil yang buruk atau tidak signifikan tetaplah sebuah fakta. Failure analysis sangat mengubah cara pandang saya; saya menjadi sadar bahwa membuktikan sebuah sistem "tidak mampu" atau sebuah metode "tidak bekerja" adalah langkah krusial untuk mencegah ilmuwan lain masuk ke lubang yang sama, sekaligus mendorong komunitas untuk mencari alternatif solusi yang jauh lebih baik. Riset yang baik adalah riset yang transparan, bukan riset yang datanya dipaksakan sempurna.