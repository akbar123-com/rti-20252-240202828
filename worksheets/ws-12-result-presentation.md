# WS-12: Result Presentation & Visualization

> **Bab 12 — Penyajian Hasil & Visualisasi**

---

## Ringkasan Materi

### Data → Insight Model

```
Validated Data → Structured Presentation → Visualization → Pattern Recognition → Insight
```

Penyajian **mendahului** analisis. Tabel dan grafik membantu peneliti "melihat" data sebelum menghitung. Langsung ke uji statistik tanpa visualisasi berisiko kesimpulan yang secara teknis benar tapi kontekstual salah (Anscombe's Quartet, 1973).

### Tabel = Presisi, Grafik = Pola

Keduanya **saling melengkapi**:
- Tabel: angka presisi, self-contained (dipahami tanpa teks), sortable
- Grafik: pola visual, tren, perbandingan cepat

### Jenis Grafik Berdasarkan Tujuan

| Tujuan | Jenis Grafik |
|--------|-------------|
| Perbandingan antar-skenario | Bar chart (grouped/stacked) |
| Distribusi per-skenario | Box plot / violin plot |
| Tren temporal | Line chart |
| Korelasi dua variabel | Scatter plot |
| Proporsi (total = 100%) | Pie chart (hati-hati!) |

### Contoh Tabel Hasil yang Baik

| Model | Accuracy (%) | F1-Score (%) | Training Time (min) |
|-------|-------------|-------------|---------------------|
| BERT | 88.4 ± 1.2 | 87.1 ± 1.4 | 45.2 ± 3.1 |
| LSTM | 86.1 ± 1.8 | 84.5 ± 2.0 | 12.8 ± 1.2 |
| SVM | 82.3 ± 0.9 | 80.7 ± 1.1 | 0.3 ± 0.1 |

*N=10 per model. Mean ± std. Diurutkan berdasarkan Accuracy.*

### Visualization Bias — Yang Harus Dihindari

| Bias | Deskripsi | Dampak |
|------|----------|--------|
| Truncated axis | Y tidak dari 0 | Memperbesar perbedaan kecil |
| Inconsistent scale | Dua grafik skala beda | Perbandingan menyesatkan |
| Cherry-picked data | Hanya tampilkan yang "menang" | Selektif, tidak jujur |
| 3D effects | Efek 3D tanpa dimensi data ke-3 | Distorsi tanpa informasi |
| Missing error bar | Tidak ada variabilitas | Menyembunyikan ketidakpastian |

### Engineering vs Research Presentation

| Aspek | Engineering | Research |
|-------|-----------|---------|
| Tujuan grafik | Dashboard monitoring | Mendukung argumen ilmiah |
| Informasi wajib | KPI, threshold | Mean, std, CI, N, p-value |
| Bias handling | Less critical | Wajib dihindari (peer-review) |

---

## Template A.12 — Result Presentation Plan

```
RESULT PRESENTATION PLAN

Research Question : ____________________
Metrik Utama      : ____________________

Tabel Hasil:
| Skenario | Metrik 1 (mean ± std) | Metrik 2 (mean ± std) | n |
|----------|----------------------|----------------------|---|
|          |                      |                      |   |

Visualisasi yang Direncanakan:
| # | Jenis Grafik | Pesan Utama | Metrik |
|---|-------------|-------------|--------|
| 1 |             |             |        |
| 2 |             |             |        |

Bias Check:
  [ ] Y-axis mulai dari 0 (atau dijustifikasi)
  [ ] Error bar/CI ditampilkan
  [ ] Semua data disertakan (tidak cherry-picked)
  [ ] Tidak menggunakan 3D tanpa alasan
```

---

## Latihan 1 — Tabel Hasil

Buat tabel hasil eksperimen Anda (boleh dengan data simulasi jika belum punya data riil).

| Skenario | Waktu Simpan / Latency (mean ± std) | Beban RAM Puncak (mean ± std) | n |
|----------|----------------------|----------------------|---|
| MySQL | 2.12 ± 0.36 ms | 86.65 ± 2.61 % | 35 |
| PostgreSQL | 4.46 ± 0.55 ms | 86.25 ± 1.86 % | 35 |


**Checklist tabel:**
- [V] Self-contained (judul jelas, satuan ada, N tercantum)
- [V] Mean ± std (bukan single number)
- [V] Diurutkan berdasarkan metrik utama
- [V] Format konsisten di semua baris

---

## Latihan 2 — Rencana Visualisasi

Rencanakan 2-3 grafik untuk menyajikan data dari Latihan 1. Setiap grafik = satu pesan.

| # | Jenis Grafik | Pesan | Data yang Digunakan |
|---|-------------|-------|---------------------|
| 1 | Bar chart + error bar | Perbandingan rata-rata kecepatan waktu simpan (latency) antara MySQL dan PostgreSQL. | Rata-rata Latency (mean ± std) dari Latihan 1. |
| 2 | Box plot | Distribusi stabilitas waktu simpan dan deteksi anomali/kemacetan data pada masing-masing database. | Seluruh 35 run data Latency MySQL dan PostgreSQL. |
| 3 | Scatter plot | Cek asumsi trade-off Kecepatan (Latency) vs Beban Server (RAM Puncak) — hasil uji statistik menunjukkan Latency berbeda signifikan (p < 0,001) namun RAM **tidak** berbeda signifikan (p = 0,346), sehingga trade-off tersebut tidak terbukti. | Seluruh run Latency vs RAM dari kedua database. |

---

## Latihan 3 — Bias Detection

Evaluasi visualisasi berikut untuk bias (skenario dari contoh):

**Skenario:** Metode A = 91.2%, Metode B = 90.8%. Bar chart dengan Y-axis mulai dari 90%.

| Pertanyaan | Jawaban |
|-----------|---------|
| Apakah Y-axis menyesatkan? | Contoh: Ya — A terlihat 2x B padahal beda 0.4% |
| Apakah error bar ditampilkan? | Tidak. Akibatnya, audiens tidak tahu apakah selisih 0,4% itu benar-benar terbukti secara statistik atau sekadar kebetulan akibat variansi data uji.|
| Apakah semua kondisi ditampilkan? | Belum tentu. Skenario hanya menyajikan hasil akhir rata-rata akurasi, tetapi menyembunyikan informasi jumlah sampel (N) dan sebaran variansinya. |
| Apa solusinya? | Ubah titik awal sumbu Y (Y-axis) agar dimulai dari 0%, bukan 90%. Kemudian, tambahkan error bar pada puncak batang grafik untuk menunjukkan rentang standar deviasi secara jujur. |

**Evaluasi grafik Anda sendiri dari Latihan 2:**
- [ ] Semua bias check lulus
- [v] Ada yang perlu diperbaiki: Pesan grafik #3 (scatter plot) awalnya menyebut "trade-off" antara Latency dan RAM. Berdasarkan hasil Paired Samples T-Test terbaru, Latency memang berbeda signifikan antara MySQL vs PostgreSQL (Sig. 2-tailed = 0,000; Cohen's d = -4,01), tetapi RAM **tidak** berbeda signifikan (Wilcoxon, Asymp. Sig. 2-tailed = 0,368; H0 diterima). Jadi klaim "trade-off" berpotensi menyesatkan (cherry-picked interpretation) karena hanya satu sisi yang benar-benar terbukti secara statistik — pesan grafik perlu direvisi agar tidak mengimplikasikan hubungan tarik-ulur yang belum terbukti.

---

## Refleksi

> Mengapa tabel dan grafik keduanya diperlukan — tidak cukup salah satu saja? Pernahkah Anda membuat grafik yang (tanpa sengaja) menyesatkan?

> Tabel dan grafik memiliki fungsi kognitif yang saling melengkapi. Tabel mutlak diperlukan untuk menyajikan angka eksak dan presisi matematis tertinggi (misalnya untuk mencatat nilai latency spesifik di angka 4,46 ms atau beban RAM 86,25%). Namun, otak manusia kesulitan memproses pola atau tren hanya dari deretan angka yang panjang. Di situlah Grafik berperan krusial untuk menerjemahkan angka tersebut menjadi wujud visual yang bisa langsung dipahami dalam hitungan detik, menyoroti perbandingan, atau memperlihatkan sekumpulan data macet (bottleneck) yang terpental menjadi anomali.

>Terkait pembuatan grafik yang menyesatkan, hal itu sangat mungkin terjadi tanpa sengaja. Contoh klasiknya adalah memotong pangkal sumbu Y tidak dari angka 0 (misalnya dimulai dari batas bawah nilai metrik). Hal ini membuat selisih sepersekian milidetik antara dua sistem terlihat sebagai jurang perbedaan performa yang sangat masif dan dramatis di mata audiens, padahal kenyataannya perbedaan tersebut sangat tipis jika dilihat dari skala penuh. Oleh karena itu, bias detection sangat penting sebelum hasil riset dipublikasikan.