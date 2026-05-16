# WS-07: Experimental Design & Validity

> **Bab 7 — Experimental Design & Validity**

---

## Ringkasan Materi

### Correlation ≠ Causality

Kausalitas membutuhkan 3 syarat:
1. **Covariance** — X dan Y bergerak bersama
2. **Temporal precedence** — X berubah sebelum Y
3. **Elimination of alternatives** — Tidak ada faktor lain yang menjelaskan Y

Controlled experiment adalah satu-satunya metode yang bisa membuktikan kausalitas.

### Empat Jenis Validitas

| Jenis | Pertanyaan | Ancaman Umum |
|-------|-----------|-------------|
| **Internal** | Apakah hubungan IV→DV nyata? | Confounding variable, selection bias |
| **External** | Apakah bisa digeneralisasi? | Dataset terlalu spesifik |
| **Construct** | Apakah mengukur konsep yang benar? | Metrik tidak sesuai |
| **Conclusion** | Apakah kesimpulan statistik valid? | Sample size kecil, uji salah |

Internal dan external validity sering berkonflik: semakin terkontrol (internal kuat) → semakin artificial (external lemah).

### Tiga Tipe Eksperimen dalam Riset TI

| Tipe | Deskripsi | Kapan Digunakan |
|------|----------|----------------|
| **Comparison Study** | Metode A vs B pada kondisi identik | Membandingkan pendekatan berbeda |
| **Ablation Study** | Full system → lepas komponen satu per satu | Mengukur kontribusi tiap komponen |
| **Parameter Study** | Variasikan satu parameter, amati dampak | Uji sensitifitas/robustness |

### Fairness dalam Perbandingan

Perbandingan yang adil = **kondisi identik** untuk semua metode: dataset sama, preprocessing sama, tuning effort sebanding, environment sama, metrik sama.

Contoh tidak adil: Transformer (30 fitur tambahan + Bayesian optimization) vs RF (default params) → hasilnya misleading.

### Threats to Validity = Diidentifikasi Sebelum Eksperimen

Ancaman validitas harus diidentifikasi **sebelum** eksperimen dan mitigasinya dirancang sebagai bagian dari desain — bukan ditulis sebagai boilerplate setelah selesai.

### Research vs Engineering

| Aspek | Engineering | Research |
|-------|------------|----------|
| Tujuan testing | Memastikan sistem memenuhi requirement | Membuktikan hubungan kausal antar variabel |
| Baseline | Versi sebelumnya (last release) | Metode tervalidasi dari literatur |
| Kegagalan | Bug → fix → release | H₀ tidak ditolak → tetap kontribusi ilmiah |
| Sukses | 100% test pass | Evidence valid — mendukung atau menolak hipotesis |

### Istilah Penting

- **Causality** — Hubungan sebab-akibat (covariance + temporal + elimination)
- **Controlled Experiment** — Ubah satu variabel, kontrol sisanya, amati efek
- **Fairness** — Semua metode diuji pada kondisi yang benar-benar identik
- **Threats to Validity** — Faktor yang bisa melemahkan kesimpulan jika tidak dimitigasi
- **Conclusion Validity** — Validitas statistik: power, sample size, uji yang tepat

---

## Template A.7 — Desain Eksperimen Lengkap

```
EXPERIMENT DESIGN

Research Question : ____________________
Hypothesis        : ____________________
Tipe Eksperimen   : [ ] Comparison  [ ] Ablation  [ ] Parameter

Kondisi Eksperimen:
| Kondisi | Deskripsi | IV Value | CV Settings |
|---------|-----------|----------|-------------|
| Control |           |          |             |
| Treatment |         |          |             |

Fairness Checklist:
  [ ] Dataset identik untuk semua kondisi
  [ ] Preprocessing setara
  [ ] Tuning effort setara
  [ ] Environment identik
  [ ] Metrik evaluasi sama

Threat Analysis:
| Threat Type | Ancaman Spesifik | Mitigasi |
|-------------|-----------------|----------|
| Internal    |                 |          |
| External    |                 |          |
| Construct   |                 |          |
| Conclusion  |                 |          |

Statistical Plan:
  Uji statistik   : ____________________
  Justifikasi      : ____________________
  Alpha            : ____________________
  Effect size min  : ____________________
```

---

## Latihan 1 — Desain Eksperimen

Susun desain eksperimen berdasarkan RQ, variabel, dan sistem dari WS-04 sampai WS-06.

**RQ:** Bagaimana perbandingan kecepatan waktu simpan (insert latency) dan beban memori server antara database MySQL dan PostgreSQL ketika digunakan untuk menangani aliran data berfrekuensi tinggi secara terus menerus (continuous streaming) dari sensor detak jantung MAX30102?
**Tipe eksperimen:** [x] Comparison / [ ] Ablation / [ ] Parameter

| Kondisi | Deskripsi | IV Value | CV Settings |
|---------|-----------|----------|-------------|
| Control | Pengujian performa database baseline (standar bawaan) | Menggunakan Engine MySQL | Jaringan WiFi lokal yang tidak ada akses internet, mengirim 10.000 data dari memori array ESP32, dikunci dengan 100 data/detik untuk spam datanya dari esp 32 itu. |
| Treatment | Pengujian performa database pembanding | Menggunakan Engine PostgreSQL | Jaringan WiFi lokal yang tidak ada akses internet, mengirim 10.000 data dari memori array ESP32, dikunci dengan 100 data/detik untuk spam datanya dari esp 32 itu. |

---

## Latihan 2 — Fairness Checklist

Evaluasi apakah desain eksperimen di Latihan 1 sudah fair.

| Kriteria | Status | Detail |
|----------|--------|--------|
| Dataset identik | ✅ | Sama sama dimasuki atau diberi beban  kumpulan angka detak jantung yang sama persis (sudah dikunci di dalam array ESP32) yang sebelumnya diambil langsung dengan sensor detak jantung MAX30102. |
| Preprocessing setara | ✅ |  Data yang Dikirim dari ESP32 langsung ditangkap dan di-INSERTkan ke tabel database, tanpa menggunakan rumus atau filter tambahan yang membedakan insert atau penyimpanan ke MySQL ataupun PostgreSQL. |
| Tuning effort setara | ✅ | Kedua database (MySQL dan PostgreSQL) diinstal dan dijalankan tetap menggunakan setelan bawaan pabrik . Tidak ada pengaturan tuning atau pembesaran kapasitas cache yang menguntungkan salah satu database. |
| Environment identik | ✅ | Pengujian dengan cara bergantian dengan satu laptop yang sama, menggunakan WiFi tidak terhubung ke internet yang sama, dan semua aplikasi latar belakang di laptop dimatikan. |
| Metrik evaluasi sama | ✅ | Penilaian sama sama menggunakan dua tolak ukur baku yaitu waktu simpan (insert latency) dalam satuan milidetik, dan beban server dalam bentuk persentase (%) CPU dan RAM dari Task Manager laptop yang digunakan.|

**Ada yang tidak fair?** [ ] Ya / [x] Tidak
> Jika ya, bagaimana cara memperbaikinya? ________________

---

## Latihan 3 — Threat Analysis

Identifikasi ancaman validitas untuk desain eksperimen ini.

| Threat Type | Ancaman Spesifik | Mitigasi |
|-------------|-----------------|----------|
| Internal | Terjadi laptop kepanasan atau Antivirus/Update mendadak jalan saat salah satu database sedang diuji, sehingga CPU menjadi lebih pelan atau lambat. | Mematikan koneksi internet agar tidak ada update sistem yang tiba tiba berjalan, menutup semua aplikasi di latar belakang, dan memberikan jeda istirahat  pada laptop  (pendinginan)  di antara  pengujian MySQL dan PostgreSQL. |
| External | Hasil performa kecepatan database ini mungkin  tidak berlaku jika diterapkan pada server cloud sungguhan (seperti AWS/VPS) yang arsitektur hardwarenya berbeda dari laptop lokal pengujian.| Menyatakan dengan tegas di bagian Batasan Masalah pada Bab 1 bahwa kesimpulan eksperimen ini spesifik berlaku untuk skala server lokal (laptop) dan jaringan LAN.|
| Construct | Salah mengukur waktu. Waktu kirim sinyal WiFi dari ESP32 ke leptop ikut terhitung dalam insert latency, padahal hanya ingin mengukur kecepatan mesin databasenya saja.| Membuat kode penghitung waktu (start) yaitu tepat saat perintah simpan ke database mulai dijalankan. Tujuannya agar waktu delay dari jaringan WiFi tidak ikut terhitung, sehingga waktu yang tercatat benar benar kecepatan kerja mesin database saja|
| Conclusion | Durasi pengiriman data terlalu sebentar (misal hanya 1 menit), sehingga selisih waktu antara MySQL dan PostgreSQL yang diperoleh bisa jadi hanya faktor kebetulan.| Menembakkan data dalam jumlah yang sangat masif, misalnya 50.000 baris data secara terus menerus . Dengan jumlah data yang besar ini, hasil selisih waktu antara MySQL dan PostgreSQL bisa diukur keakuratannya menggunakan uji statistik (Independent Sample T-Test) untuk membuktikan bahwa perbedaan yang muncul memang nyata, bukan sekadar kebetulan saja.|

**Ancaman mana yang paling sulit dimitigasi?** External Threat (Ancaman Eksternal)
**Mengapa?**
> Karena pengujian ini sangat bergantung pada keterbatasan spesifikasi perangkat keras (laptop) yang digunakan untuk menguji. Sangat sulit dan memakan biaya mahal untuk bisa mensimulasikan atau mengujinya di lingkungan skala industri dunia nyata (seperti server cloud dengan beban traffic ribuan pengguna).

---

## Refleksi

> Sebuah paper melaporkan "metode kami mengalahkan semua baseline." Apa 3 pertanyaan pertama yang harus diajukan untuk mengevaluasi klaim ini?

**Jawaban:**
1. Apakah tuning effortnya setara? Jangan jangan sistem pembanding (baseline) sengaja tidak dioptimalkan atau dibiarkan menggunakan setting pabrik yang buruk, sementara metode barunya dimaksimalkan habis habisan supaya terlihat lebih cepat.

2. Apakah dataset dan kondisi lingkungannya 100% sama? Harus dipastikan kedua metode diuji menggunakan beban data yang persis sama di atas spesifikasi hardware (CPU/RAM) yang sama juga.

3. Apakah kemenangan hasilnya signifikan secara statistik? Apakah metode tersebut terbukti selalu lebih cepat setelah diulang puluhan kali dalam pengujian berikutnya, atau itu hanya selisih angka sekian milidetik yang terjadi karena faktor kebetulan saja ?
