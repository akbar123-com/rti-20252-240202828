# Laporan Penelitian

**Judul:** Analisis Perbandingan Performa Database MySQL dan PostgreSQL pada Sistem Pemantauan Data Sensor Detak Jantung Frekuensi Tinggi

**Peneliti:** [Akhmad akbar syarifudin]
**Target Publikasi:** Sinta 4/5  atau Scopus Q3–Q4
**Status Penelitian:** Tahap 1–4 selesai Tahap 5 (draf naskah jurnal) sudah tersusun lengkap ([../07-manuskrip/](../07-manuskrip/))

---

## 1. Ringkasan Eksekutif

Penelitian ini merancang, mengimplementasikan, dan mengevaluasi secara empiris performa dua sistem manajemen basis data relasional, yaitu MySQL dan PostgreSQL, dalam menangani aliran data sensor detak jantung MAX30102 yang dikirim terus-menerus (*continuous streaming*) melalui mikrokontroler ESP32. Evaluasi dilakukan lewat eksperimen terkontrol dengan desain berpasangan (*paired design*): setiap subjek pengujian menghasilkan sepasang pengamatan MySQL dan PostgreSQL pada kondisi jaringan dan beban yang identik. Pengujian dilakukan sebanyak 35 run replikasi untuk tiap database, masing-masing menembakkan target 1.000 baris data per sesi, dengan metrik utama *insert latency* (ms) dan beban RAM server (%). Data dianalisis menggunakan IBM SPSS Statistics: Paired Samples T-Test untuk *latency* dan Wilcoxon Signed-Rank Test untuk RAM.

**Temuan utama:**

- MySQL terbukti **signifikan lebih cepat** dalam *insert latency* dibanding PostgreSQL rata-rata 2,1240 ms berbanding 4,4580 ms (t(34)=-23,7493; Sig. 2-tailed=0,000; Cohen's d=-4,0144, efek sangat besar).
- **Tidak ada perbedaan signifikan** pada beban RAM antara kedua database (86,6500% vs 86,2534%; Wilcoxon z=0,9009; Asymp. Sig.=0,368).
- Ditemukan **selisih data loss**: MySQL kehilangan 35 baris data (23 dari 35 run bersih, satu lonjakan ekstrem 15 baris akibat *bottleneck* antrean), sedangkan PostgreSQL kehilangan 41 baris secara lebih tersebar (hanya 15 dari 35 run bersih). Perbedaan ini **belum diuji signifikansinya secara statistik**, sehingga dilaporkan sebagai temuan deskriptif, bukan kesimpulan inferensial.
- **Trade-off penting:** keunggulan performa MySQL bersifat *metric-specific* unggul pada *latency*, tapi tidak pada RAM maupun bukti keandalan (*data loss*) sehingga klaim "MySQL lebih baik" tidak bisa digeneralisasi ke semua aspek sistem.

Seluruh kode program, data mentah, hasil olahan SPSS, dan draf naskah tersedia di repository ini (lihat §7 Lampiran untuk peta artefak).

---

## 2. Latar Belakang dan Rumusan Masalah

### 2.1 Latar Belakang

Sistem pemantauan kesehatan berbasis *Internet of Things* (IoT) menuntut backend yang mampu memproses data secara *real-time* tanpa kehilangan rekam data. Sensor detak jantung seperti MAX30102 menghasilkan aliran data berfrekuensi tinggi yang harus segera disimpan agar bisa dipantau langsung. Pada implementasi seperti ini, pemilihan sistem manajemen basis data relasional (RDBMS) sangat menentukan: kalau database-nya lambat menampung data yang datang beruntun, data bisa tertahan bahkan hilang dan pada konteks medis, kehilangan data semacam ini bisa berakibat fatal. Uraian lengkap distorsi paradigma awal dan pemetaan masalah tersedia di [`../worksheets/ws-01-distorsi-paradigma.md`](../worksheets/ws-01-distorsi-paradigma.md) dan [`../worksheets/ws-02-problem-statement.md`](../worksheets/ws-02-problem-statement.md).

### 2.2 Rumusan Masalah

1. Bagaimana perbandingan waktu simpan (*insert latency*) antara MySQL dan PostgreSQL dalam menangani operasi *insert* aliran data dari sensor MAX30102?
2. Bagaimana perbandingan efisiensi beban RAM pada server saat kedua database diuji pada parameter eksekusi yang sama?
3. Bagaimana tingkat keandalan dan stabilitas kedua database dalam menjaga integritas paket data saat menghadapi antrean transmisi frekuensi tinggi pada beban puncak?

Penelusuran *research gap* dari literatur terdahulu (studi IoT medis yang berhenti di titik sensor berhasil mengirim data, dan studi performa RDBMS yang hanya diuji dengan data statis) ada di [`../worksheets/ws-03-literature-gap.md`](../worksheets/ws-03-literature-gap.md) dan [`../02-literatur/matriks-literatur.md`](../02-literatur/matriks-literatur.md). Rumusan hipotesis formalnya ada di [`../worksheets/ws-04-rq-hypothesis.md`](../worksheets/ws-04-rq-hypothesis.md).

### 2.3 Tujuan Penelitian

Detail tujuan & kontribusi: lihat [`../01-proposal/proposal-penelitian.md`](../01-proposal/proposal-penelitian.md) dan [`../07-manuskrip/02-pendahuluan.md`](../07-manuskrip/02-pendahuluan.md).

---

## 3. Metodologi dan Pelaksanaan

Penelitian dilaksanakan dalam 5 tahap, mengikuti alur worksheet (WS-01 s.d. WS-16). Bagian ini merangkum implementasi dan verifikasi tiap tahap; detail teknis lengkap ada pada worksheet yang dirujuk.

### 3.1 Tahap 1 — Perancangan Arsitektur & Pemetaan Variabel

**Status: Selesai.** Variabel penelitian dipetakan ke komponen sistem: Variabel Independen (jenis database MySQL atau PostgreSQL) dipetakan ke *database engine/server backend*; Variabel Dependen (*insert latency* & beban RAM) dipetakan ke log waktu di skrip program dan Resource Monitor komputer; Variabel Kontrol (frekuensi data masuk) dipetakan ke skrip pengirim data/mikrokontroler ESP32 yang dikunci pada kecepatan tetap. Empat prinsip desain eksperimental (*traceability, modularity, controllability, measurability*) dievaluasi terpenuhi, dengan *controllability* (menjaga kestabilan jaringan Wi-Fi dan proses latar belakang laptop) diidentifikasi sebagai tantangan paling sulit.

Detail & diagram: [`../worksheets/ws-05-variabel-metrik.md`](../worksheets/ws-05-variabel-metrik.md), [`../worksheets/ws-06-system-experiment.md`](../worksheets/ws-06-system-experiment.md), [`../03-teori/Arsitektur dan skema.md`](../03-teori/Arsitektur%20dan%20skema.md).

### 3.2 Tahap 2 — Implementasi Sistem (ESP32, Skrip PHP, Database)

**Status: Selesai.** Sistem diimplementasikan dengan tiga komponen utama: firmware ESP32 yang membaca sensor MAX30102 dan mengirim data lewat Wi-Fi (`sketch_jun11c_RTI.ino`), skrip PHP native yang menerima dan mencatat waktu simpan data (`insert.php`, `koneksi.php` untuk *switch* MySQL/PostgreSQL, `get_metrics.php` untuk baca beban CPU/RAM), serta dashboard monitoring (`index.php`). Environment dikunci pada Intel Core i3 Gen 12/13, RAM 8/16 GB DDR5, Windows 11, PHP 8.x native tanpa framework berat (agar metrik waktu simpan murni tanpa delay tambahan), MySQL 8.0, dan PostgreSQL 15.x/16.x.

**Verifikasi end-to-end** (pengujian manual, kedua database):
- *MySQL*: skrip menyala → data ESP32 diterima → `insert.php` mencatat waktu sebelum/sesudah simpan → rata-rata latency tercatat dalam milidetik pada dashboard.
- *PostgreSQL*: alur identik, database di-*switch* lewat `koneksi.php` tanpa mengubah kode di sisi ESP32 (variabel independen berhasil diisolasi dari komponen lain).
- *Mitigasi bias*: sebelum tiap sesi, cache dibersihkan dan proses latar belakang laptop (update Windows, antivirus) dimatikan agar beban CPU yang tercatat murni berasal dari proses database, bukan proses lain.

Catatan lingkungan: pengujian dilakukan pada jaringan Wi-Fi lokal yang sama untuk ESP32 dan server, dengan akses internet publik aktif (bukan jaringan terisolasi) latensi jaringan dianggap faktor alami yang merepresentasikan kondisi implementasi IoT di dunia nyata.

Detail: [`../worksheets/ws-07-experiment-design.md`](../worksheets/ws-07-experiment-design.md), [`../worksheets/ws-08-proposal-integration.md`](../worksheets/ws-08-proposal-integration.md), [`../worksheets/ws-09-implementation.md`](../worksheets/ws-09-implementation.md); kode: [`../05-kode/`](../05-kode/).

### 3.3 Tahap 3 — Eksekusi Pengujian & Pengumpulan Data

**Status: Selesai  matrix 35 run berpasangan telah dijalankan.** Setiap run menembakkan target 1.000 baris data sensor per database, dengan prosedur: aktifkan database yang diuji (pastikan pembanding mati) → jalankan skrip backend & aktifkan ESP32 → pantau RAM lewat Task Manager → setelah 1.000 baris masuk, catat rata-rata latency dan jumlah baris tersimpan → bersihkan cache → ulangi untuk database pembanding.

**Iterasi desain penting**: percobaan awal tanpa pembersihan cache antar-run menghasilkan variasi data yang tidak stabil akibat sisa memori sistem tidak layak untuk analisis ilmiah. Solusinya, pembersihan cache dan penghentian proses latar belakang diwajibkan sebelum tiap sesi berjalan. Data hasil rekaman dipindahkan ke spreadsheet master `data RTI EXEL.xlsx`.

**Hasil pengumpulan data:** dari target 35.000 baris per skenario (70.000 baris total), tercatat 34.965 baris untuk MySQL (missing 35) dan 34.959 baris untuk PostgreSQL (missing 41)  total *completeness* 99,89% (69.924/70.000). Data yang hilang **tidak diulang atau dibuang**, melainkan diperlakukan sebagai temuan riset karena menjadi bukti nyata perbedaan tingkat keandalan kedua database saat menangani beban puncak.

Detail: [`../worksheets/ws-10-execution-data.md`](../worksheets/ws-10-execution-data.md); data: [`../04-data/data RTI EXEL.xlsx`](../04-data/data%20RTI%20EXEL.xlsx).

### 3.4 Tahap 4 — Validasi & Analisis Data (SPSS)

**Status: Selesai.** Data mentah divalidasi dulu sebelum dianalisis (*completeness*, *consistency*, *range*, dan *logic check*  lihat WS-11), lalu diproses lewat IBM SPSS Statistics dengan alur berikut:

| Proses | Fungsi |
|---|---|
| Uji Normalitas Shapiro-Wilk | Menentukan uji beda yang dipakai (parametrik atau non-parametrik) per metrik |
| Statistik Deskriptif | Menghitung Mean, SD, Min, Max latency & RAM untuk MySQL dan PostgreSQL |
| Paired Correlations | Menghitung korelasi antar-pasangan pengamatan MySQL–PostgreSQL |
| Paired Samples T-Test | Uji beda latency (setelah normalitas terpenuhi, W=0,948; p=0,098) |
| Wilcoxon Signed-Rank Test | Uji beda RAM (karena selisih tidak normal, W=0,871; p=0,0007) |

Satu anomali/*outlier* ditemukan lewat metode IQR pada data *loss* MySQL (satu run kehilangan 15 baris, jauh melampaui batas atas IQR sebesar 2,5) — diinvestigasi dan didokumentasikan sebagai bukti batas toleransi *bottleneck* sistem, bukan dihapus dari dataset.

Selain lewat SPSS, uji Paired Samples T-Test juga dihitung ulang secara independen memakai skrip Python buatan sendiri sebagai *cross-check* untuk memastikan angka t-hitung dan signifikansi yang keluar dari SPSS bukan salah baca menu/kesalahan input, melainkan memang konsisten dengan perhitungan manual/pemrograman.

Output: tabel statistik deskriptif, tabel korelasi, tabel hasil uji, berkas citra hasil SPSS (`HASIL SPSS DATA MENTAH(mean dan std).png`, `HASIL UJI SPSS(Paired Samples T-Test).png`), serta hasil verifikasi silang dari skrip Python tadi (`HASIL UJI PROGRAM PY SAYA A/B (Paired Samples T-Test).png`). Detail & hasil: [`../worksheets/ws-11-data-validation.md`](../worksheets/ws-11-data-validation.md), [`../worksheets/ws-13-preprocessing.md`](../worksheets/ws-13-preprocessing.md), [`../worksheets/ws-14-analysis-interpretation.md`](../worksheets/ws-14-analysis-interpretation.md), [`../06-output/hasilkesimpulan.md`](../06-output/hasilkesimpulan.md).

### 3.5 Tahap 5 — Draf Naskah Jurnal

**Status: Draf lengkap tersusun.** Draf konten per bagian naskah (Abstrak, Pendahuluan, Tinjauan Pustaka, Metodologi, Hasil & Analisis, Kesimpulan, Daftar Pustaka) sudah disusun lengkap di folder [`../07-manuskrip/`](../07-manuskrip/), termasuk naskah gabungan [`../07-manuskrip/naskah-jurnal.md`](../07-manuskrip/naskah-jurnal.md) dan versi sesuai template jurnal tujuan (`naskah-jurnal-TIIJ.docx`). Enam referensi pendukung sudah diverifikasi ulang ke sumber aslinya — lihat [`../07-manuskrip/07-daftar-pustaka.md`](../07-manuskrip/07-daftar-pustaka.md). Detail proses penulisan ilmiah: [`../worksheets/ws-15-scientific-writing.md`](../worksheets/ws-15-scientific-writing.md).

---

## 4. Hasil Penelitian

Ringkasan hasil (detail lengkap & interpretasi: [`../07-manuskrip/05-hasil-analisis.md`](../07-manuskrip/05-hasil-analisis.md) dan [`../06-output/hasilkesimpulan.md`](../06-output/hasilkesimpulan.md)).

### 4.1 Statistik Deskriptif

| Metrik | Database | N | Mean | SD | Min | Max |
|---|---|---|---|---|---|---|
| Insert Latency (ms) | MySQL | 35 | 2,1240 | 0,3598 | 1,5123 | 2,7412 |
| Insert Latency (ms) | PostgreSQL | 35 | 4,4580 | 0,5549 | 3,5491 | 5,4120 |
| Beban RAM (%) | MySQL | 35 | 86,6500 | 2,6118 | 81,38 | 91,10 |
| Beban RAM (%) | PostgreSQL | 35 | 86,2534 | 1,8648 | 80,09 | 88,95 |

### 4.2 Analisis Korelasi Sampel Berpasangan

| Pasangan Metrik | N | Correlation (r) | Sig. |
|---|---|---|---|
| Latency (MySQL & PostgreSQL) | 35 | 0,249 | 0,150 |
| RAM (MySQL & PostgreSQL) | 35 | 0,440 | 0,008 |

### 4.3 Hasil Uji Beda

| Metrik | Uji | Statistik | df | Sig. | Effect Size |
|---|---|---|---|---|---|
| Insert Latency | Paired Samples T-Test | t = -23,7493 | 34 | 0,000 | Cohen's d = -4,0144 |
| Beban RAM | Wilcoxon Signed-Rank | z = 0,9009 | — | 0,368 | — |

### 4.4 Figure / Citra Output

| File | Isi |
|---|---|
| [`HASIL SPSS DATA MENTAH(mean dan std).png`](../06-output/HASIL%20SPSS%20DATA%20MENTAH(mean%20dan%20std).png) | Tangkapan layar output SPSS statistik deskriptif mentah |
| [`HASIL UJI SPSS(Paired Samples T-Test).png`](../06-output/HASIL%20UJI%20SPSS(Paired%20Samples%20T-Test%20).png) | Tangkapan layar output resmi Paired Samples T-Test dari SPSS |
| [`HASIL UJI PROGRAM PY SAYA A/B (Paired Samples T-Test).png`](../06-output/) | Verifikasi silang hasil uji-t memakai program Python mandiri (cross-check independen dari SPSS) |

### 4.5 Interpretasi Singkat

1. MySQL terbukti signifikan lebih cepat dalam *insert latency* dengan ukuran efek yang tergolong sangat besar (|d| = 4,01) sejalan dengan reputasinya sebagai *engine* yang dioptimalkan untuk operasi tulis sederhana.
2. Tidak ada perbedaan signifikan pada beban RAM membantah asumsi bahwa kecepatan MySQL otomatis diikuti efisiensi memori yang lebih baik.
3. Korelasi RAM yang signifikan (r=0,440) antar kedua database mengindikasikan fluktuasi RAM lebih dipengaruhi kondisi *resource host* bersama, bukan karakteristik internal masing-masing *engine*.
4. **Trade-off ditemukan pada data loss**: PostgreSQL yang lebih lambat justru kehilangan data pada lebih banyak run (20 dari 35), sedangkan MySQL yang lebih cepat justru lebih sering bersih (23 dari 35 run) namun rentan pada satu lonjakan ekstrem. Karena belum diuji signifikansinya, pola ini dilaporkan sebagai temuan awal, bukan kesimpulan final soal keandalan.

---

## 5. Kendala dan Catatan Lingkungan

- **Kestabilan lingkungan pengujian (*controllability*)** menjadi tantangan paling sulit dipenuhi dari 4 prinsip desain eksperimental diatasi dengan mengunci jaringan Wi-Fi yang sama untuk ESP32 dan server, serta mematikan seluruh aplikasi/proses latar belakang lain di laptop server (update Windows, antivirus) agar beban CPU/RAM yang tercatat murni berasal dari proses database.
- **Repeatability belum sempurna 100%** hasil milidetik antar-run tidak akan identik persis karena faktor *thermal throttling* prosesor, sisa cache yang belum terhapus sempurna oleh sistem operasi, dan sedikit *jitter* dari rambatan sinyal Wi-Fi lokal, meski tren rata-rata statistiknya tetap konsisten.
- **Anomali *data loss* pada satu run MySQL** (15 baris hilang dalam satu sesi, jauh di atas batas IQR) diinvestigasi dan disimpulkan sebagai bukti *bottleneck* antrean web server pada beban puncak, bukan dihapus dari dataset sesuai prinsip *detect → investigate → document → decide*.
- **Level reproduksi saat ini masih *repeatability*, bukan *reproducibility* penuh** eksperimen bisa diulang oleh peneliti sendiri di lingkungan yang sama, namun orang lain belum bisa mereproduksinya secara mandiri karena diagram wiring fisik sensor-ke-ESP32 dan source code lengkap belum diunggah ke repository publik (lihat §7 untuk peta artefak yang sudah tersedia di repo internal ini).
- **Ditemukan bug pada `esp32/sketch_jun11c_RTI.ino`** (alamat `serverName` sempat kosong tanpa IP) sudah diperbaiki dengan placeholder IP dan catatan cara mengecek IP laptop lewat `ipconfig` sebelum upload ke ESP32. Lihat [`../05-kode/README.md`](../05-kode/README.md) untuk detail perbaikan.

---

## 6. Kesimpulan dan Saran

Ringkasan kesimpulan & saran penelitian lanjutan: lihat [`../07-manuskrip/06-kesimpulan.md`](../07-manuskrip/06-kesimpulan.md).

Inti kesimpulan: MySQL terbukti unggul secara empiris dari sisi kecepatan penyimpanan data (*insert latency*) untuk kebutuhan sensor medis frekuensi tinggi, dengan perbedaan yang sangat signifikan dan ukuran efek yang besar. Namun, keunggulan ini **tidak berlaku menyeluruh** tidak ada perbedaan signifikan pada beban RAM, dan pola *data loss* yang ditemukan justru menunjukkan trade-off yang belum bisa disimpulkan secara statistik. Klaim performa database pada penelitian ini bersifat *metric-specific*, bukan generalisasi mutlak salah satu database "lebih baik" dari yang lain. Penelitian lanjutan disarankan menguji signifikansi *data loss* secara formal, memperluas kondisi jaringan, mengotomasi pemantauan *resource*, dan menambah perbandingan dengan database NoSQL.

---

## 7. Lampiran — Peta Artefak Penelitian

| Folder | Isi | Status |
|---|---|---|
| [01-proposal/](../01-proposal/) | Proposal penelitian perbandingan database sensor IoT | Selesai |
| [02-literatur/](../02-literatur/) | Matriks literatur dan pencarian *research gap* | Selesai |
| [03-teori/](../03-teori/) | Arsitektur sistem, skema database, landasan teori statistik | Selesai |
| [04-data/](../04-data/) | Data primer 35 run berpasangan (`data RTI EXEL.xlsx`) + kamus data & ringkasan validasi | Selesai |
| [05-kode/](../05-kode/) | Firmware ESP32, skrip PHP native, dashboard monitoring, aplikasi web analisis | Selesai |
| [06-output/](../06-output/) | Output SPSS (.spv), tangkapan layar hasil uji, ringkasan hasil (`hasilkesimpulan.md`) | Selesai |
| [07-manuskrip/](../07-manuskrip/) | Draf naskah artikel jurnal ilmiah (Abstrak s.d. Daftar Pustaka) + versi template TIIJ | Selesai |
| [08-laporan/](../08-laporan/) | Dokumen laporan resmi hasil penelitian institusi (Berkas Ini) | Selesai |
| [worksheets/](../../worksheets/) | Log pengerjaan WS-01 s.d. WS-16 (dasar seluruh isi laporan ini) | Selesai |



**Cara reproduksi penuh:**

```bash
# Tahap 2: Set koneksi database & upload firmware
# 1) Buka 05-kode/koneksi.php, set $db_engine = "MySQL" (atau "PostgreSQL")
# 2) Upload 05-kode/sketch_jun11c_RTI.ino ke ESP32 (sesuaikan IP server di serverName)

# Tahap 3: Jalankan pengujian per subjek/run
# 1) Nyalakan service database yang diuji di XAMPP, pastikan database pembanding mati
# 2) Buka 05-kode/index.php di browser untuk memantau progres real-time
# 3) Tekan tombol BOOT di ESP32 untuk mulai kirim 1.000 baris data
# 4) Catat rata-rata latency & RAM dari dashboard, lalu reset & ulangi untuk database pembanding

# Tahap 4: Jalankan verifikasi uji statistik
# 1) Buka IBM SPSS Statistics -> Impor data RTI EXEL.xlsx -> Jalankan Paired Samples T-Test / Wilcoxon Signed-Rank Test
# 2) Jalankan verifikasi mandiri lewat aplikasi web analisis buatan sendiri (cross-check independen dari SPSS):
#    - Pastikan Apache/XAMPP aktif, lalu buka: http://localhost/web-analysis/index.php
#    - Upload file data RTI EXEL.xlsx ke aplikasi web tersebut
#    - Pilih variabel yang akan dihitung (Latency / RAM), lalu jalankan sampai hasil muncul
#    - Bandingkan hasilnya dengan output SPSS untuk memastikan angka t-hitung & signifikansi konsisten
#    (lihat HASIL UJI PROGRAM PY SAYA A/B (Paired Samples T-Test).png di 06-output/)
```