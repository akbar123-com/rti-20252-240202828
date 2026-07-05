# Landasan Teori & Metodologi Analisis Statistik Komparatif

Landasan teori metrik performa dan metodologi uji statistik — hasil **Tahap 1**.

## Isi Dokumen
- Landasan Teori Metrik Performa Database (*Insert Latency* & Beban Server)
- Konsep Dasar Pengujian *Independent Samples T-Test*
- Perumusan Hipotesis Penelitian
- Aturan Pengambilan Keputusan (Kriteria Signifikansi)

---

## 1. Landasan Teori Metrik Performa Database

Dalam mengevaluasi performa sistem manajemen basis data relasional (MySQL dan PostgreSQL) pada beban aliran data sensor berfrekuensi tinggi, dua metrik utama digunakan untuk merepresentasikan konsep efisiensi dan ketahanan sistem:

* **Insert Latency (Waktu Simpan):** Diukur dalam satuan milidetik (ms), yaitu durasi sejak perintah `INSERT` dikirim ke database engine hingga data berhasil ditulis dan dikonfirmasi tersimpan. Semakin kecil nilainya, semakin cepat dan efisien database tersebut merespons.
* **Beban Server (CPU/RAM Usage):** Diukur dalam persentase (%), merepresentasikan seberapa berat sumber daya komputasi yang ditarik oleh masing-masing database engine saat menahan hantaman data secara terus-menerus. Beban yang tinggi mengindikasikan potensi *bottleneck* yang dapat berujung pada penolakan data (*data loss*) jika dibiarkan melebihi kapasitas server.

Kedua metrik ini bersifat *ratio scale* (memiliki nol absolut dan jarak antar nilai bermakna), sehingga memenuhi syarat untuk dianalisis menggunakan uji beda rata-rata parametrik seperti *Independent Samples T-Test*.

---

## 2. Landasan Teori Independent Samples T-Test

*Independent Samples T-Test* (Uji T Sampel Bebas/Tidak Berpasangan) adalah analisis statistik parametrik yang digunakan untuk membandingkan rata-rata (*mean*) dari dua kelompok yang **berbeda dan tidak saling berhubungan**. Uji ini dipilih — bukan *Paired Samples T-Test* — karena setiap run pengujian pada MySQL dan run pengujian pada PostgreSQL berasal dari sesi eksekusi yang terpisah (dua kelompok data independen), bukan dua pengukuran berulang pada satu subjek yang sama.

Dalam eksperimen ini, uji dilakukan untuk membandingkan rata-rata **Insert Latency MySQL** dengan rata-rata **Insert Latency PostgreSQL** (begitu pula untuk metrik Beban Server), masing-masing dari $n = 35$ run replikasi sesuai batasan masalah pada proposal.

### Model Matematika & Rumus Uji T Sampel Bebas

Sebelum uji-t dijalankan, kesetaraan varians kedua kelompok diperiksa terlebih dahulu menggunakan **Levene's Test**, untuk menentukan rumus mana yang dipakai:

**a. Jika varians kedua kelompok setara (*equal variance assumed*):**

$$t = \frac{\bar{X}_1 - \bar{X}_2}{S_p \sqrt{\frac{1}{n_1} + \frac{1}{n_2}}}, \quad S_p = \sqrt{\frac{(n_1-1)S_1^2 + (n_2-1)S_2^2}{n_1+n_2-2}}$$

**b. Jika varians kedua kelompok tidak setara (*equal variance not assumed* / Welch's t-test):**

$$t = \frac{\bar{X}_1 - \bar{X}_2}{\sqrt{\frac{S_1^2}{n_1} + \frac{S_2^2}{n_2}}}$$

**Keterangan:**
* $\bar{X}_1, \bar{X}_2$: Rata-rata Insert Latency (atau Beban Server) MySQL dan PostgreSQL
* $S_1^2, S_2^2$: Varians masing-masing kelompok
* $n_1, n_2$: Jumlah sampel/run tiap kelompok ($n_1 = n_2 = 35$)
* $S_p$: Varians gabungan (*pooled variance*), dipakai jika varians setara
* $\text{df}$ (*Degree of Freedom*): $n_1 + n_2 - 2$ (equal variance) atau dihitung via Welch–Satterthwaite (unequal variance)

---

## 3. Perumusan Hipotesis Penelitian

Pengujian hipotesis dilakukan untuk mengetahui secara valid apakah terdapat perbedaan performa yang signifikan antara MySQL dan PostgreSQL saat menangani aliran data sensor MAX30102 berfrekuensi tinggi:

* **$H_0$ (Hipotesis Nol):** Tidak terdapat perbedaan rata-rata *insert latency* dan beban server yang signifikan antara MySQL dan PostgreSQL ($\mu_1 = \mu_2$).
* **$H_1$ (Hipotesis Alternatif):** Terdapat perbedaan rata-rata *insert latency* dan beban server yang signifikan antara MySQL dan PostgreSQL ($\mu_1 \neq \mu_2$), dengan selisih rata-rata minimal 10%.

---

## 4. Kriteria Pengambilan Keputusan (Signifikansi)

Analisis data dilakukan dengan menggunakan alat bantu **IBM SPSS Statistics**. Sebelum diolah, data mentah perlu melalui *preprocessing* (pembersihan karakter satuan seperti "ms"/"%" pada kolom metrik) karena SPSS mewajibkan format numerik murni untuk menjalankan Uji T-Test (lihat WS-13). Pengambilan keputusan untuk menolak atau menerima $H_0$ didasarkan pada nilai signifikansi p-value (*Sig. 2-tailed*) pada output *Independent Samples Test*, dengan ketentuan:

> * Jika nilai $\text{Sig. (2-tailed)} < 0.05$ **dan** selisih rata-rata ≥ 10%, maka **$H_0$ Ditolak** dan **$H_1$ Diterima**. (Perbedaan performa bersifat signifikan secara statistik dan bermakna secara praktis).
> * Jika nilai $\text{Sig. (2-tailed)} \ge 0.05$, maka **$H_0$ Diterima** dan **$H_1$ Ditolak**. (Perbedaan performa hanya terjadi karena faktor kebetulan/variasi acak, bukan karena arsitektur database).

**Effect Size (Cohen's d):** Selain p-value, dilaporkan juga *effect size* Cohen's d untuk mengukur seberapa besar (bukan cuma "signifikan atau tidak") perbedaan performa kedua database — penting karena signifikansi statistik saja tidak selalu berarti signifikansi praktis (lihat WS-14).

| Nilai d | Interpretasi |
|---|---|
| ~0.2 | Small effect |
| ~0.5 | Medium effect |
| ~0.8 | Large effect |
| > 1.2 | Huge effect |

**Langkah pemeriksaan sebelum uji-t (asumsi):**
1. **Uji Normalitas** (mis. Shapiro-Wilk) — memastikan data insert latency & beban server tiap kelompok berdistribusi normal.
2. **Uji Homogenitas Varians** (Levene's Test) — menentukan rumus t-test yang dipakai (pooled vs Welch).
3. Jika asumsi normalitas tidak terpenuhi, gunakan uji non-parametrik alternatif (**Mann-Whitney U Test**) sebagai cadangan.

---

## Referensi Internal

- Definisi variabel & metrik: lihat [`tinjauan-pustaka.md`](tinjauan-pustaka.md) bagian 4.
- Rantai operasionalisasi RQ → analisis: lihat WS-04 Latihan 3.
- Hipotesis awal (versi naratif): lihat WS-04 Latihan 2.