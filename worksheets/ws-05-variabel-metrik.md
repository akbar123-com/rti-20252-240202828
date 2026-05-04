# WS-05: Variabel & Metrik

> **Bab 5 — Metric, Measurement & Data**

---

## Ringkasan Materi

### Measurement Alignment Model

Setiap pengukuran yang valid harus bisa ditelusuri melalui rantai ini tanpa lompatan logis:

```
Problem → Concept → Variable → Metric → Data → Result
```

### Operationalization = Keputusan Desain

Menerjemahkan konsep abstrak menjadi variabel terukur bukan proses mekanis. "Code quality" yang diukur via SonarQube code smells membawa asumsi implisit. Setiap operasionalisasi harus didokumentasikan dan dijustifikasi.

### Empat Tipe Data (NOIR)

| Tipe | Ciri | Contoh | Operasi Valid |
|------|------|--------|---------------|
| **Nominal** | Kategori, tanpa urutan | Jenis algoritma (RF, SVM, CNN) | Modus, chi-square |
| **Ordinal** | Urutan, interval tidak sama | Skala Likert (1-5) | Median, Spearman |
| **Interval** | Jarak bermakna, tanpa nol absolut | Suhu Celsius | Mean, Pearson, t-test |
| **Ratio** | Jarak bermakna + nol absolut | Waktu eksekusi (ms) | Semua operasi |

Tipe data menentukan uji statistik yang valid. Kebanyakan metrik performa TI = ratio; persepsi pengguna = ordinal.

### Kriteria Pemilihan Metrik

- **Representative** — Mewakili konsep yang diteliti
- **Sensitive** — Cukup peka menangkap perbedaan bermakna (hindari ceiling effect)
- **Feasible** — Bisa dikumpulkan dalam batasan waktu dan biaya

### Pre-registration

Metrik harus ditentukan **sebelum** eksperimen. Memilih metrik setelah melihat data = **p-hacking**. Metrik tambahan yang ditemukan kemudian dilaporkan sebagai *exploratory*, bukan *confirmatory*.

### Primary vs Secondary Metric

- **Primary Metric** — Langsung terikat ke hipotesis, menentukan kesimpulan
- **Secondary Metric** — Pendukung, dilaporkan di samping primary; statusnya suplementer

### Research vs Engineering

| Aspek | Engineering | Research |
|-------|------------|----------|
| Pemilihan metrik | Berdasarkan kebiasaan/tool yang ada | Berdasarkan construct validity |
| Anomali | Dihapus untuk laporan bersih | Diinvestigasi — bisa jadi temuan |
| Kapan dipilih | Setelah sistem jadi (monitoring) | Sebelum eksperimen (by design) |

### Istilah Penting

- **Operationalization** — Transformasi konsep abstrak menjadi variabel terukur
- **Construct Validity** — Sejauh mana pengukuran benar-benar mengukur konsep yang dimaksud
- **Measurement Scale** — Klasifikasi data (NOIR) yang menentukan analisis valid
- **Multi-metric Evaluation** — Menggunakan beberapa metrik untuk menangkap konsep kompleks

---

## Template A.5 — Definisi Variabel, Metrik & Justifikasi

```
VARIABLE & METRIC DEFINITION

Research Question: ____________________

| Variabel | Tipe | Konsep | Metrik | Skala | Satuan | Cara Mengukur | Justifikasi |
|----------|------|--------|--------|-------|--------|---------------|-------------|
|          | IV   |        |        |       |        |               |             |
|          | DV   |        |        |       |        |               |             |
|          | CV   |        |        |       |        |               |             |

Alignment Check:
  RQ → Concept → Variable → Metric → Data → Result
  [ ] Setiap langkah terdokumentasi
  [ ] Tidak ada "lompatan logis"
  [ ] Metrik mengukur apa yang dimaksud (construct validity)
```

---

## Latihan 1 — Operationalization Chain

Gunakan RQ dari WS-04. Definisikan variabel dan metriknya.

**RQ:** Bagaimana perbandingan kecepatan waktu simpan (insert latency) dan beban memori server antara database MySQL dan PostgreSQL ketika digunakan untuk menangani aliran data berfrekuensi tinggi secara terus menerus (continuous streaming) dari sensor detak jantung MAX30102?

| Variabel | Tipe | Konsep Abstrak | Metrik Konkret | Skala (NOIR) | Satuan |
|----------|------|---------------|----------------|-------------|--------|
| Jenis database | IV | Arsitektur penyimpanan data relasional | Kategori pengujian yaitu MySQL vs PostgreSQL | normal | - |
| Waktu simpan (Insert latency) & Beban Server | DV | Performa dan efisiensi server| Catatan waktu yang dibutuhkan untuk menyimpan data, serta persentase pemakaian CPU/RAM | rasio | Milidetik (ms) & Persen (%) |
| Frekuensi data masuk & Kondisi perangkat | CV | Lingkungan pengujian yang dibuat sama | Aliran data dari sensor MAX30102 dan ESP32 disetting konstan (misal 100 data/detik) di jaringan WiFi yang sama| rasio | Data per detik (Hz / SPS)|

**Apakah ada lompatan logis dalam rantai?** [ ] Ya / [x] Tidak
> Jika ya, di mana? ____________________________________

---

## Latihan 2 — Evaluasi Metrik

Evaluasi metrik DV yang dipilih di Latihan 1 menggunakan 3 kriteria.

| Kriteria | Skor (1-5) | Justifikasi |
|----------|-----------|-------------|
| Representative | 5 | Angka kecepatan waktu simpan (milidetik) secara langsung menunjukkan seberapa cepat database merespons data, sedangkan pemakaian CPU/RAM (%) menunjukkan seberapa berat beban sistem yang sebenarnya. |
| Sensitive | 5 | Satuan milidetik (ms) bisa mencatat perbedaan sepersekian detik sekalipun saat terjadi lagging. Persentase pemakaian CPU/RAM juga akan langsung naik turun meskipun hanya ada sedikit tambahan beban aliran data dari sensor. |
| Feasible | 5 | Sangat mungkin dan mudah diukur karena Waktu simpan bisa dicatat otomatis menggunakan fungsi timestamp di dalam kode log sistem, dan beban RAM/CPU bisa dipantau langsung lewat fitur bawaan komputer (seperti Task Manager atau Resource Monitor) saat simulasi berjalan. |

**Apakah perlu secondary metric?** [x] Ya / [ ] Tidak
> Jika ya, apa dan mengapa? Jika ya, apa dan mengapa? Persentase data yang berhasil tersimpan tanpa hilang (Success Rate atau Packet Loss). Alasannya, bisa saja sebuah database mencatatkan waktu simpan yang cepat, tapi ternyata hal itu terjadi karena sistemnya membuang/melewatkan banyak data saat kewalahan. Jadi butuh metrik tambahan ini untuk memastikan kecepatan tersebut tidak mengorbankan keutuhan data.

**Contoh kasus ceiling effect untuk metrik ini:**
> Kasus ceiling effect (efek mentok) bisa terjadi jika frekuensi tembakan data dari ESP32 diatur terlalu lambat atau jumlahnya terlalu sedikit (misalnya hanya 5 data per detik). Akibatnya, baik MySQL maupun PostgreSQL akan sama sama dengan gampang mencapai Success Rate 100% dan waktu simpan yang mentok sangat cepat (misal selalu stabil di angka 1 ms).  sistemnya tidak merasa terbebani, sehingga kita tidak bisa melihat batasan maksimal performanya dan tidak tahu mana yang sebenarnya lebih tangguh saat diberi beban yang berat.

---

## Latihan 3 — Data Quality Check

Bayangkan data yang akan dikumpulkan dari eksperimen. Evaluasi 4 dimensi kualitas data.

| Dimensi | Pertanyaan | Jawaban | Strategi Mitigasi |
|---------|-----------|---------|------------------|
| Completeness | Apakah semua data point terkumpul? | Belum tentu 100%  Karena pengiriman dilakukan lewat jaringan WiFi lokal, sangat mungkin ada data yang hilang  di tengah jalan, atau database gagal menyimpan saat mulai kelebihan beban (bottleneck).| Menambahkan sistem nomor urut (ID sequence) pada setiap baris data yang dikirim dari ESP32. Jika saat dicek di database ada nomor yang melompat, kita langsung tahu ada data yang hilang.|
| Consistency | Apakah ada kontradiksi internal? | Bisa saja terjadi  Contohnya, laporan waktu simpan (insert latency) tercatat sangat cepat, tapi anehnya pemakaian CPU/RAM server malah mentok di 100%. | Mencatat timestamp (waktu kejadian) secara presisi hingga hitungan milidetik. Nanti catatan waktu dari log database akan dicocokkan ulang dengan grafik riwayat Resource Monitor / Task Manager di server|
| Validity | Apakah benar benar mengukur yang dimaksud? | Ya Kecepatan waktu simpan (ms) dan persentase penggunaan memori  adalah ukuran yang paling nyata dan valid untuk menilai ketangguhan mesin database.| Menjalankan server dalam kondisi terisolasi Semua aplikasi luar (seperti browser, game, atau antivirus) wajib dimatikan selama simulasi, agar beban CPU murni berasal dari MySQL/PostgreSQL.|
| Representativeness | Apakah sampel mewakili populasi target?| Ya, asalkan cara dan durasi pengujiannya benar benar meniru kondisi asli operasional alat medis di lapangan. | Melakukan simulasi pengiriman aliran data (streaming) secara terus menerus dalam durasi yang cukup lama (misalnya 30-60 menit nonstop), bukan sekadar mengirim sedikit data lalu berhenti. |

---

## Refleksi

> Mengapa memilih metrik setelah melihat data dianggap p-hacking? Apa bedanya dengan eksplorasi data yang sah?

**Jawaban:**
> Memilih metrik setelah melihat hasil data disebut p-hacking karena membuka celah untuk bersikap curang atau pilih kasih. Misalnya, setelah eksperimen selesai, ternyata MySQL punya waktu simpan yang lebih lambat dari PostgreSQL. Kalau tiba tiba mengganti metrik dan hanya menonjolkan data pemakaian RAM (karena kebetulan MySQL lebih irit RAM), berarti kita sengaja menyembunyikan fakta lambatnya MySQL agar eksperimen kita terlihat berhasil.

>Bedanya dengan eksplorasi data yang sah terletak pada komitmen di awal  Dalam eksplorasi yang sah, kita sudah menetapkan aturan, metrik , dan metode pengujian sebelum eksperimen dimulai. Apa pun hasil akhirnya nanti entah hipotesisnya terbukti benar atau malah meleset sama sekali data tersebut tetap dianalisis dan dilaporkan secara jujur sesuai dengan metrik awal.
