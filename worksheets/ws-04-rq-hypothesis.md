# WS-04: Research Question & Hypothesis

> **Bab 4 — Research Question, Contribution & Hypothesis**

---

## Ringkasan Materi

### RQ Bukan Pertanyaan Biasa

Research Question yang baik secara implisit mengandung cetak biru eksperimen: subjek, baseline, metrik, domain, dataset.

| Kualitas | Contoh |
|----------|--------|
| **Buruk** | "Bagaimana pengaruh deep learning terhadap deteksi malware?" |
| **Baik** | "Apakah CNN menghasilkan F1-Score lebih tinggi dari RF pada CIC-MalMem-2022?" |

Perbedaan: RQ yang baik menyebutkan **metode spesifik**, **metrik terukur**, **baseline**, dan **dataset**.

### Tiga Jenis RQ

| Jenis | Pola | Kebutuhan |
|-------|------|-----------|
| **Comparison** | A vs B → mana lebih baik? | ≥ 2 metode, metrik sama |
| **Improvement** | A' vs A → modifikasi lebih baik? | Pre/post, bukti perbaikan |
| **Exploratory** | Faktor X₁...Xₙ → pengaruh terhadap Y? | Multi-variabel, korelasi/regresi |

### Contribution Statement

Tiga jenis kontribusi: **Improvement** (metode terbukti lebih baik), **Comparison** (perbandingan sistematis yang belum ada), **Novel Approach** (pendekatan baru). Kontribusi harus terhubung langsung dengan gap — kontribusi tanpa gap = klaim tanpa justifikasi.

### Hypothesis H₀ / H₁

- **H₀** (Null) = Tidak ada perbedaan signifikan — asumsi default, harus dibuktikan salah
- **H₁** (Alternative) = Ada perbedaan signifikan — diterima hanya jika H₀ ditolak
- Harus **falsifiable**, mengandung **metrik terukur**, dirumuskan **SEBELUM eksperimen**

### Rantai Operasionalisasi

```
RQ → Variable → Metric → Data → Analysis
```

Jika rantai ini tidak lengkap, RQ belum mature. Bi-directional: RQ yang tidak bisa jadi hipotesis testable harus direvisi mundur.

### Research vs Engineering

| Aspek | Engineering | Research |
|-------|------------|----------|
| Tujuan pertanyaan | Apa yang harus dibangun? | Apa yang harus dibuktikan? |
| Bentuk jawaban | Sistem yang berfungsi | Bukti empiris terukur |
| Sukses diukur oleh | User satisfaction, uptime | Signifikansi statistik, effect size |
| Jika gagal | Debug dan perbaiki | Laporkan, analisis mengapa |

### Istilah Penting

- **Research Question (RQ)** — Pertanyaan spesifik: variabel terukur + metrik + konteks
- **Contribution Statement** — Apa yang diketahui setelah riset selesai yang sebelumnya belum ada
- **H₀ / H₁** — Null vs Alternative Hypothesis
- **Falsifiability** — Kondisi hipotesis ditolak harus bisa didefinisikan sebelum eksperimen
- **Operationalization** — Proses mewujudkan konsep abstrak menjadi variabel terukur

---

## Template A.4 — RQ-Contribution-Hypothesis

```
RQ-CONTRIBUTION-HYPOTHESIS

Gap Statement  : ____________________

Research Question:
  Tipe         : [ ] Comparison  [ ] Improvement  [ ] Exploratory
  Formulasi    : ____________________
  Variabel IV  : ____________________
  Variabel DV  : ____________________
  Metrik       : ____________________
  Dataset      : ____________________
  Baseline     : ____________________

Quality Check RQ:
  [ ] Variabel spesifik
  [ ] Metrik jelas
  [ ] Baseline ada
  [ ] Konteks disebutkan
  [ ] Memerlukan eksperimen (bukan hanya survei literatur)

Contribution Statement:
  Apa yang baru diketahui : ____________________
  Jenis kontribusi        : [ ] Improvement  [ ] Comparison  [ ] Novel approach
  Gap yang diisi          : ____________________

Hypothesis Pair:
  H₀ : ____________________
  H₁ : ____________________
  Threshold              : ____________________
  Justifikasi threshold  : ____________________
```

---

## Latihan 1 — Dari Gap ke RQ

Gunakan gap yang ditemukan di WS-03. Transformasikan menjadi Research Question.

**Gap dari WS-03:** 
Belum ada pengujian langsung mengenai batas kecepatan simpan (insert time) dan ketahanan server antara database MySQL dan PostgreSQL saat harus menahan beban aliran data berfrekuensi tinggi yang masuk terus menerus (continuous streaming) dari sensor detak jantung MAX30102.

**RQ versi pertama (tulis bebas):**
> Bagaimana perbandingan kecepatan waktu simpan dan beban memori server antara database MySQL dan PostgreSQL ketika digunakan untuk menerima aliran data secara terus menerus dari sensor detak jantung MAX30102?

**Evaluasi RQ:**

| Komponen | Ada? | Isi |
|----------|------|-----|
| Metode spesifik | Ya — Pengiriman aliran data terus-menerus (streaming) | Mengetes database dengan cara menerima data yang dikirim satu per satu tanpa henti langsung dari alat sensor (MAX30102 dan ESP32), bukan memasukkan kumpulan data secara borongan sekaligus. |
| Metrik terukur | Ya — Waktu simpan data dan beban server | Mengukur berapa milidetik (ms) waktu yang dibutuhkan untuk menyimpan tiap baris data, serta melihat seberapa berat pemakaian memori (RAM/CPU) di server.|
| Baseline | Ya — Pengujian data mati (statis) | Performa database saat diuji menggunakan metode konvensional, yaitu memakai data file biasa (seperti CSV) yang diimpor sekaligus secara borongan. |
| Dataset/konteks | Ya — Data detak jantung (time-series) medis | Uji coba database menggunakan angka denyut jantung nyata yang dihasilkan secara real time oleh sensor MAX30102. |

**Tipe RQ:** [x] Comparison / [] Improvement / [ ] Exploratory

**RQ versi revisi (setelah evaluasi):**
> Bagaimana perbandingan kecepatan waktu simpan (insert latency) dan beban memori server antara database MySQL dan PostgreSQL ketika digunakan untuk menangani aliran data berfrekuensi tinggi secara terus menerus (continuous streaming) dari sensor detak jantung MAX30102?
---

## Latihan 2 — Hypothesis Pair

Rumuskan pasangan hipotesis dari RQ di Latihan 1.

| Komponen | Isi |
|----------|-----|
| H₀ | Tidak ada perbedaan performa yang signifikan antara MySQL dan PostgreSQL dalam hal kecepatan waktu simpan (insert latency) dan beban memori saat menerima aliran data berfrekuensi tinggi secara terus-menerus dari sensor MAX30102. |
| H₁ | Terdapat perbedaan performa yang signifikan antara MySQL dan PostgreSQL, salah satu database terbukti mencatatkan waktu simpan (insert latency) yang lebih cepat dan beban server yang lebih efisien dalam menampung aliran data sensor MAX30102.|
| Metrik | Kecepatan waktu simpan tiap baris data yang diukur dalam milidetik/ms dan beban server yang diukur dari persentase pemakaian RAM/CPU.|
| Threshold | Terdapat selisih rata-rata kecepatan waktu simpan dan beban server minimal 10% antara kedua database, dan selisih tersebut terbukti signifikan secara statistik (misalnya melalui uji beda rata-rata dengan p-value < 0.05).|
| Justifikasi threshold | Selisih performa minimal 10% adalah standar yang logis untuk memastikan keunggulan salah satu database murni karena efisiensi arsitekturnya. Batas 10% juga dipakai untuk memastikan selisih angka tersebut bukan karena gangguan teknis , misalnya akibat sinyal WiFi, ESP32 yang tiba tiba naik atau turun, dan server yang sedang sibuk menjalankan program lain.|

**Apakah hipotesis ini falsifiable?** [x] Ya / [ ] Tidak
> Bagaimana cara membuktikannya salah? 
Di akhir penelitian, saat melakukan simulasi mengirimkan aliran data sensor MAX30102 secara terus menerus lewat ESP32 ke kedua database. Jika dari hasil pengujian ternyata rata rata insert latency dan pemakaian RAM/CPU antara MySQL dan PostgreSQL sama saja, atau selisihnya sangat tipis sehingga secara statistik dianggap tidak ada bedanya, maka H₁ otomatis ditolak (hipotesis terbukti salah) dan H₀ yang diterima.

---

## Latihan 3 — Rantai Operasionalisasi

Lengkapi rantai dari RQ hingga metode analisis.

| Tahap | Isi |
|-------|-----|
| RQ | Bagaimana perbandingan kecepatan waktu simpan (insert latency) dan beban memori server antara database MySQL dan PostgreSQL ketika digunakan untuk menangani aliran data berfrekuensi tinggi yang masuk terus menerus dari sensor detak jantung MAX30102? |
| Variable (IV) | Jenis database relasional yang digunakan (MySQL lawan PostgreSQL). |
| Variable (DV) | Kecepatan waktu simpan data (insert time) dan beban kerja server saat menangani aliran data. |
| Metric | Satuan waktu dalam milidetik (ms) untuk menghitung seberapa cepat tiap baris data tersimpan, dan persentase (%) untuk melihat seberapa besar pemakaian RAM dan CPU di server.|
| Data source | Catatan log atau rekaman sistem hasil simulasi penyimpanan data secara langsung (streaming) menggunakan sensor detak jantung MAX30102 dan alat ESP32 ke dalam masing masing database|
| Analysis method | Uji perbandingan rata rata (menggunakan Independent Sample T Test) untuk melihat mana yang lebih cepat dan ringan secara statistik.|

**Apakah rantai lengkap?** [x] Ya / [ ] Tidak
> Jika tidak, tahap mana yang perlu direvisi? ______________

---

## Refleksi

> Ambil satu judul skripsi/paper yang pernah dibaca. Coba ekstrak RQ-nya. Apakah RQ tersebut memenuhi semua komponen (metode, metrik, baseline, konteks)? Jika tidak, apa yang hilang?

**Judul:** _____________________________________________
**RQ yang diekstrak:** __________________________________
**Komponen yang hilang:** _______________________________
