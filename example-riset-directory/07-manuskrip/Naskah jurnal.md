# Analisis Perbandingan Performa Database MySQL dan PostgreSQL pada Sistem Pemantauan Data Sensor Detak Jantung Frekuensi Tinggi

**Performance Comparison Analysis of MySQL and PostgreSQL Databases in a High-Frequency Heart-Rate Sensor Monitoring System**

**Penulis:** [Akhmad akbar syarifudin] ¹
**Afiliasi:** ¹ Program Studi ilmu komputer, Universitas Putra Bangsa (UPB) Kebumen
**Korespondensi:** [akhmadakbarsyarifudin@gmail.com]
---

## Abstrak

**Abstrak** — Sistem pemantauan kesehatan berbasis *Internet of Things* (IoT) menuntut arsitektur backend yang mampu memproses aliran data sensor secara *real-time* tanpa kehilangan rekam data. Penelitian ini bertujuan mengevaluasi secara empiris performa dua sistem manajemen basis data relasional (RDBMS) populer, yaitu MySQL dan PostgreSQL, dalam menangani aliran data sensor detak jantung MAX30102 berfrekuensi tinggi yang dikirim secara *continuous streaming* melalui mikrokontroler ESP32. Eksperimen dirancang menggunakan desain berpasangan (*paired design*) dengan 35 run replikasi untuk setiap database pada kondisi jaringan dan beban yang identik, di mana setiap run menghasilkan sepasang pengukuran *insert latency* (ms) dan beban RAM server (%). Data dianalisis menggunakan IBM SPSS Statistics: *Paired Samples T-Test* untuk metrik Latency (setelah uji normalitas selisih Shapiro-Wilk terpenuhi, W=0,948, p=0,098) dan *Wilcoxon Signed-Rank Test* untuk metrik RAM (karena selisihnya tidak berdistribusi normal, W=0,871, p=0,0007). Hasil analisis deskriptif menunjukkan rata-rata *insert latency* MySQL sebesar 2,1240 ms (SD=0,3598), jauh lebih cepat dibandingkan PostgreSQL sebesar 4,4580 ms (SD=0,5549). Uji hipotesis membuktikan perbedaan tersebut sangat signifikan secara statistik (t(34)=-23,7493; Sig. 2-tailed=0,000; Cohen's d=-4,0144, efek sangat besar). Sebaliknya, pada metrik beban RAM (MySQL=86,6500%; PostgreSQL=86,2534%), uji Wilcoxon tidak menemukan perbedaan yang signifikan (z=0,9009 Asymp. Sig.=0,368). Temuan tambahan mencatat anomali *data loss*: MySQL kehilangan 35 baris data (23 dari 35 run tanpa kehilangan, satu lonjakan ekstrem 15 baris pada satu run akibat *bottleneck* antrean), sedangkan PostgreSQL kehilangan 41 baris data secara lebih tersebar (hanya 15 dari 35 run yang bersih). Penelitian ini menyimpulkan bahwa MySQL secara empiris lebih unggul dari sisi kecepatan simpan data untuk kebutuhan sensor medis frekuensi tinggi, namun keunggulan ini tidak disertai perbedaan signifikan pada beban RAM, sehingga klaim performa MySQL harus dibatasi secara spesifik pada metrik *latency*.

**Kata Kunci:** MySQL, PostgreSQL, *Insert Latency*, Internet of Things, Sensor Detak Jantung, *Paired Samples T-Test*.

**Abstract** — Internet of Things (IoT)-based health monitoring systems require a backend architecture capable of processing real-time sensor data streams without record loss. This study empirically evaluates the performance of two popular relational database management systems (RDBMS), MySQL and PostgreSQL, in handling a high-frequency, continuously streamed heart-rate sensor data flow from a MAX30102 sensor transmitted via an ESP32 microcontroller. The experiment employed a paired design with 35 replication runs for each database under identical network and load conditions, where each run produced a paired measurement of insert latency (ms) and server RAM load (%). Data were analyzed using IBM SPSS Statistics: a Paired Samples T-Test for the Latency metric (after the Shapiro-Wilk normality test on the difference scores was satisfied, W= 0.948, p=0.098) and a Wilcoxon Signed-Rank Test for the RAM metric (since its difference scores were not normally distributed, W= 0.871, p= 0.0007). Descriptive analysis showed that MySQL's average insert latency was 2.1240 ms (SD=0.3598), substantially faster than PostgreSQL's 4.4580 ms (SD=0.5549). Hypothesis testing confirmed this difference was highly statistically significant (t(34)= -23.7493; Sig. 2-tailed=0.000; Cohen's d=-4.0144, a huge effect size). Conversely, for RAM load (MySQL=86.6500%; PostgreSQL= 86.2534%), the Wilcoxon test found no significant difference (z=0.9009; Asymp. Sig.=0.368). This study concludes that MySQL is empirically superior in write speed for high-frequency medical sensor needs, but this advantage must be limited specifically to the latency metric.

**Keywords:** MySQL, PostgreSQL, Insert Latency, Internet of Things, Heart-Rate Sensor, Paired Samples T-Test.

---

## 1. Pendahuluan

### 1.1 Latar Belakang

Perkembangan *Internet of Things* (IoT) di bidang kesehatan (*healthcare*) menuntut arsitektur sistem yang mampu memproses data secara *real-time*. Salah satu komponen penting dalam sistem pemantauan kesehatan adalah sensor detak jantung seperti MAX30102, yang menghasilkan aliran data berfrekuensi tinggi dan harus segera dikirim serta disimpan ke dalam basis data oleh mikrokontroler agar dapat divisualisasikan oleh tenaga medis secara langsung.

Dalam arsitektur server semacam ini, pemilihan sistem manajemen basis data relasional (RDBMS) memegang peranan yang krusial. MySQL dikenal memiliki kecepatan tulis (*write latency*) yang efisien, sementara PostgreSQL memiliki reputasi ketat dalam validasi dan integritas data berskala *enterprise*. Sebagian besar literatur yang ada saat ini hanya menguji kedua database tersebut pada beban data statis atau umum, belum pada konteks aliran data sensor fisik yang datang tanpa jeda. Padahal, pada konteks medis, hantaman paket data sensor secara terus-menerus dapat memicu antrean proses (*bottleneck*) pada disk I/O maupun memori server.

Keterlambatan waktu simpan (*latency*) mungkin masih dapat ditoleransi dalam skala milidetik, namun apabila *bottleneck* tersebut berujung pada penolakan sistem yang menyebabkan hilangnya rekam medis (*data loss*), hal ini dapat berakibat fatal bagi keselamatan pasien. Oleh karena itu, penelitian ini dirancang untuk mengevaluasi secara komprehensif tidak hanya aspek kecepatan (*insert latency*) dan efisiensi beban RAM, tetapi juga menyoroti batas toleransi keandalan kedua database saat diuji di bawah tekanan data frekuensi tinggi secara langsung dari perangkat sensor fisik.

### 1.2 Rumusan Masalah

1. Bagaimana perbandingan waktu simpan (*insert latency*) antara arsitektur database MySQL dan PostgreSQL dalam menangani operasi *insert* aliran data dari sensor MAX30102?
2. Bagaimana perbandingan efisiensi beban RAM pada server saat kedua database diuji menggunakan parameter eksekusi yang sama?
3. Bagaimana tingkat keandalan dan stabilitas kedua database dalam menjaga integritas paket data (ada tidaknya anomali *data loss*) saat menghadapi antrean transmisi frekuensi tinggi pada beban puncak?

### 1.3 Tujuan Penelitian

1. Mengukur dan membandingkan *latency* kecepatan penyimpanan data antara MySQL dan PostgreSQL secara empiris menggunakan uji beda rata-rata berpasangan.
2. Menganalisis tingkat efisiensi penggunaan memori (RAM) dari kedua database selama proses hantaman data sensor berlangsung.
3. Mengidentifikasi batas kemampuan sistem (*bottleneck*) untuk mengevaluasi apakah kecepatan database berbanding lurus dengan keandalannya terhadap risiko kehilangan data (*data loss*).

### 1.4 Kontribusi Penelitian

Literatur yang ada selama ini terbagi pada dua jalur terpisah: riset IoT medis yang hanya berfokus pada keberhasilan akuisisi sensor tanpa menguji sisi database, dan riset performa RDBMS yang hanya diuji dengan dataset statis yang diimpor sekaligus (lihat §2.4). Penelitian ini berkontribusi dengan menyatukan kedua sisi tersebut menguji langsung ketahanan MySQL dan PostgreSQL ketika dihadapkan pada aliran data nyata dari perangkat sensor fisik berfrekuensi tinggi (*continuous streaming*), bukan data buatan/simulasi.

### 1.5 Batasan Masalah

1. Pengujian transmisi data dilakukan menggunakan koneksi internet publik (Wi-Fi), sehingga latensi jaringan merupakan faktor alami yang merepresentasikan kondisi implementasi IoT di dunia nyata bukan diperlakukan sebagai *confounding variable* yang perlu dihilangkan.
2. Perangkat keras *client* pengirim data adalah mikrokontroler ESP32 dan modul sensor MAX30102.
3. Skenario eksperimen dibatasi pada operasi *write/insert* data secara kontinu, dengan 35 *run* (replikasi) untuk setiap database.
4. Metrik utama yang dianalisis adalah *insert latency* dan beban RAM; metrik *data loss* dicatat sebagai data pendukung namun belum diuji signifikansi statistiknya secara formal (lihat §5.2).

---

## 2. Tinjauan Pustaka

### 2.1 Aliran Data Sensor Detak Jantung (MAX30102) dan IoT Kesehatan

MAX30102 adalah modul sensor terintegrasi berbasis prinsip *photoplethysmography* (PPG), yang mendeteksi volume aliran darah menggunakan pantulan cahaya LED merah dan inframerah pada permukaan kulit. Dalam implementasi ini, sensor digabungkan dengan mikrokontroler ESP32 yang membaca dan mengirimkan nilai PPG secara terus-menerus melalui jaringan Wi-Fi lokal. Karakteristik data ini adalah *time-series continuous streaming* paket data kecil dikirim satu per satu dalam orde milidetik tanpa jeda, berbeda dari data *batch* yang diimpor sekaligus.

### 2.2 Sistem Manajemen Basis Data Relasional (RDBMS): MySQL vs PostgreSQL

* **MySQL** basis data *open-source* yang ringan dan populer, sering dijadikan pilihan *default* oleh pengembang IoT karena kemudahan *setup* dan performa baca/tulis yang cepat untuk beban standar.
* **PostgreSQL** basis data *open-source* tingkat lanjut yang patuh ketat terhadap standar SQL, dirancang untuk menangani kueri kompleks dan beban konkuren dengan stabilitas integritas data setingkat *enterprise*.

### 2.3 Insert Latency dan Bottleneck

*Insert latency* adalah durasi yang dibutuhkan *database engine* sejak menerima perintah `INSERT` hingga data berhasil ditulis dan dikonfirmasi tersimpan (ms). Ketika frekuensi kedatangan data melampaui kecepatan I/O *disk* atau kapasitas *buffer* memori, terjadi antrean pemrosesan (*bottleneck*) yang berpotensi menyebabkan *data loss* — risiko yang menjadi salah satu fokus evaluasi penelitian ini.

### 2.4 Penelitian Terdahulu (Related Work) dan Celah Penelitian

Riset di bidang IoT kesehatan dan performa basis data relasional selama ini berjalan pada dua jalur terpisah. Salsabila dkk. [1] membuktikan pengiriman data detak jantung melalui protokol XMPP pada Raspberry Pi mencapai latensi rata-rata 342,857 ms dengan *packet loss* 0%, namun tanpa menguji kapasitas database di *backend*. Ahsana dkk. [2] menguji responsivitas kueri CRUD pada MySQL dan PostgreSQL menggunakan data statis Google Playstore (250.000 baris), menyimpulkan PostgreSQL unggul (< 4 detik), namun beban data bersifat statis. Putra dan Purwaningrum [3] memperkuat temuan ini melalui pengujian kueri CRUD/SUM/COUNT pada MySQL, PostgreSQL, dan MongoDB menggunakan dataset catur Kaggle (20.058 baris), dengan PostgreSQL tercepat (2,5 detik vs MySQL 21,6 detik) juga pada data statis. Studi lain [4] mengembangkan alat pantau jantung *real-time* berbasis mikrokontroler dan sensor PPG yang akurat namun tidak membahas sisi penyimpanan database, sementara studi eksperimen MySQL–PostgreSQL lain [5] menggunakan data simulasi akademik, bukan data sensor fisik. Pada ranah yang berdekatan, Eyada dkk. [6] mengevaluasi MySQL vs MongoDB (NoSQL) pada beban IoT di *cloud* dan menemukan MongoDB unggul saat beban meningkat — namun perbandingan ini di luar konteks sesama RDBMS relasional.

Pola yang konsisten: riset IoT medis berhenti di titik sensor berhasil membaca dan mengirim data, sedangkan riset performa database berhenti di titik pengujian dengan data statis. **Gap utama:** belum ada penelitian yang menguji batas performa *insert latency* dan stabilitas beban memori antara MySQL dan PostgreSQL ketika dihadapkan langsung pada aliran data *continuous streaming* dari sensor detak jantung MAX30102 berfrekuensi tinggi.

### 2.5 Landasan Teori Statistik: Paired Samples T-Test

*Paired Samples T-Test* membandingkan rata-rata dua pengukuran dari subjek/unit yang sama pada dua kondisi berbeda. Uji ini dipilih karena setiap *run* replikasi menghasilkan sepasang pengamatan yang saling berhubungan (MySQL dan PostgreSQL diuji pada kondisi identik). Dasar uji ini adalah selisih tiap pasangan (dᵢ = X1ᵢ − X2ᵢ):

```
t = d̄ / (Sd / √n)      df = n − 1
```

Apabila asumsi normalitas selisih (Shapiro-Wilk) tidak terpenuhi, digunakan **Wilcoxon Signed-Rank Test** sebagai alternatif non-parametrik. *Effect size* dilaporkan melalui Cohen's d (d = d̄/Sd).

---

## 3. Metodologi

### 3.1 Desain Penelitian dan Unit Analisis

Penelitian ini menggunakan eksperimen terkontrol (*Controlled Experiment*) dengan rancangan berpasangan (*Paired/Within-Condition Design*), n = 35 *run* replikasi per database. Variabel independen (IV) adalah **jenis database relasional**, dengan PostgreSQL sebagai kondisi pembanding terhadap MySQL sebagai *baseline*.

### 3.2 Arsitektur Sistem Pengujian

Sistem terdiri atas: (1) ESP32 + sensor MAX30102 sebagai *client* pengirim; (2) web server lokal (XAMPP/Apache); (3) skrip PHP native sebagai *logger* yang mencatat *timestamp* via `microtime(true)` sebelum/sesudah `INSERT` (4) database *engine* (MySQL 8.0 / PostgreSQL 15.x–16.x, dipertukarkan via konfigurasi); (5) Task Manager (Windows 11) untuk pemantauan RAM manual.

```
Sensor MAX30102 → ESP32 (Wi-Fi lokal, continuous streaming)
  → Skrip PHP Native (catat timestamp mulai)
    → [DB aktif?] → MySQL 8.0  atau  PostgreSQL 15/16
      → catat timestamp selesai → hitung insert_latency (ms)
        → Task Manager: catat %RAM → Rekap 1.000 baris/sesi
```

**Environment:** Intel Core i3 Gen 12/13, RAM 8/16 GB DDR5, Windows 11, PHP 8.x native (XAMPP/Apache).

**Skema tabel** (identik di kedua *engine*): `log_jantung(id, nilai_sensor, waktu)`. *Insert latency* dan beban RAM sengaja tidak disimpan sebagai kolom database, agar pencatatan metrik tidak menambah beban *insert* itu sendiri.

### 3.3 Variabel dan Prosedur

* **IV:** jenis database (MySQL vs PostgreSQL). **DV:** *insert latency* (ms), beban RAM (%). **CV:** frekuensi kirim data (dikunci konstan), jaringan, *environment* server. **Metrik sekunder:** jumlah baris hilang (*data loss*).

Prosedur tiap *run*: (1) hubungkan ESP32 dan server ke jaringan Wi-Fi publik yang sama; (2) aktifkan database yang diuji, matikan pembanding; (3) jalankan skrip *backend* dan aktifkan ESP32; (4) pantau RAM via Task Manager; (5) setelah 1.000 baris masuk, catat rata-rata *insert latency* dan jumlah baris tersimpan, lalu ulangi untuk database pembanding pada *run* berikutnya. Prosedur diulang 35 kali per database.

### 3.4 Teknik Analisis Data

1. **Pra-pemrosesan:** pembersihan karakter satuan pada kolom metrik.
2. **Deskriptif:** *mean* dan SD *insert latency* & RAM per database.
3. **Uji normalitas selisih** (Shapiro-Wilk).
4. **Uji hipotesis:** Paired Samples T-Test (bila selisih normal) atau Wilcoxon Signed-Rank Test (bila tidak normal), α = 0,05.
5. **Effect size:** Cohen's d.

**Kriteria keputusan:** H0 ditolak jika Sig. (2-tailed) < 0,05 **dan** selisih rata-rata ≥ 10%.

---

## 4. Hasil dan Analisis

### 4.1 Statistik Deskriptif

**Tabel 1.** Statistik Deskriptif Paired Samples (N = 35)

| Metrik | Database | N | Mean | SD | Min | Max |
|---|---|---:|---:|---:|---:|---:|
| Insert Latency (ms) | MySQL | 35 | 2,1240 | 0,3598 | 1,5123 | 2,7412 |
| Insert Latency (ms) | PostgreSQL | 35 | 4,4580 | 0,5549 | 3,5491 | 5,4120 |
| Beban RAM (%) | MySQL | 35 | 86,6500 | 2,6118 | 81,38 | 91,10 |
| Beban RAM (%) | PostgreSQL | 35 | 86,2534 | 1,8648 | 80,09 | 88,95 |

MySQL rata-rata **2,3340 ms** lebih cepat dari PostgreSQL selisih beban RAM jauh lebih kecil (0,3966%).

### 4.2 Korelasi Sampel Berpasangan

**Tabel 2.** Paired Samples Correlations

| Pasangan Metrik | N | r | Sig. |
|---|---:|---:|---:|
| Latency | 35 | 0,249 | 0,150 |
| RAM | 35 | 0,440 | 0,008 |

Korelasi *latency* lemah dan tidak signifikan; korelasi RAM sedang dan signifikan  mengindikasikan beban RAM lebih dipengaruhi kondisi *host* bersama.

### 4.3 Pengujian Hipotesis

**Insert Latency** Shapiro-Wilk W=0,948, p=0,098 (normal) → Paired T-Test:

**Tabel 3.** Hasil Paired Samples T-Test Insert Latency

| Parameter | Nilai |
|---|---:|
| Mean Selisih | -2,3340 ms |
| SD Selisih | 0,5814 |
| df | 34 |
| t | -23,7493 |
| Sig. (2-tailed) | 0,000000 |
| Cohen's d | -4,0144 |

**H0 ditolak, H1 diterima** perbedaan sangat signifikan, efek *huge*.

**Beban RAM** Shapiro-Wilk W=0,871, p=0,0007 (tidak normal) → Wilcoxon Signed-Rank Test:

**Tabel 4.** Hasil Uji Beban RAM

| Uji | Nilai | Sig. |
|---|---:|---:|
| Wilcoxon (z) | 0,9009 | 0,367650 |
| *Pembanding* Paired T-Test (t) | 0,9565 | 0,345596 *(tidak dipakai)* |

**H0 diterima, H1 ditolak** tidak ada perbedaan signifikan.

### 4.4 Temuan Tambahan: Data Loss

**Tabel 5.** Ringkasan Data Loss

| Skenario | Direncanakan | Tercatat | Hilang | Run Tanpa Loss |
|---|---:|---:|---:|---:|
| MySQL | 35.000 | 34.965 | 35 | 23/35 |
| PostgreSQL | 35.000 | 34.959 | 41 | 15/35 |

Perbedaan *data loss* belum diuji signifikansinya secara statistik formal dilaporkan sebagai temuan deskriptif.

### 4.5 Pembahasan

Keunggulan MySQL pada *insert latency* konsisten dengan reputasinya sebagai *engine* yang dioptimalkan untuk operasi tulis sederhana, sementara PostgreSQL membawa *overhead* tambahan dari mekanisme *concurrency control* (MVCC) yang lebih ketat. Tidak adanya perbedaan signifikan pada RAM membantah asumsi bahwa kecepatan MySQL disertai efisiensi memori lebih baik fluktuasi RAM lebih dipengaruhi kondisi *resource host* bersama. Pada *data loss*, pola yang muncul berlawanan dari asumsi umum: PostgreSQL yang lebih lambat justru kehilangan data pada proporsi *run* lebih besar, sementara MySQL lebih sering bersih namun rentan lonjakan ekstrem. Karena belum diuji signifikansinya, klaim keandalan salah satu database tidak dapat ditarik dari temuan ini saja. Secara umum, keunggulan performa database bersifat *metric-specific* dan tidak dapat digeneralisasi lintas metrik tanpa pengujian statistik terpisah.

---

## 5. Kesimpulan dan Saran

### 5.1 Kesimpulan

1. **Insert Latency:** perbedaan sangat signifikan (t(34)= -23,7493; p=0,000; d=-4,0144); MySQL (2,1240 ms) lebih cepat dari PostgreSQL (4,4580 ms).
2. **Beban RAM:** tidak ada perbedaan signifikan (z=0,9009; p=0,368).
3. **Keandalan (Data Loss):** MySQL 35 baris hilang vs PostgreSQL 41 baris hilang belum diuji signifikansinya, sehingga tidak disimpulkan salah satu lebih andal.

MySQL unggul secara empiris untuk kecepatan *insert* data sensor medis *real-time*, namun keunggulan ini terbatas pada metrik *latency* saja.

### 5.2 Keterbatasan

* Pengujian pada satu spesifikasi *hardware* dan satu kondisi jaringan Wi-Fi publik.
* Pemantauan RAM manual (Task Manager), bukan *automated monitoring*.
* Variabel *data loss* belum diuji signifikansinya secara statistik formal.
* n=35 *run* memenuhi syarat minimum uji parametrik, namun sampel lebih besar akan meningkatkan presisi deteksi *loss event* yang jarang terjadi.

### 5.3 Saran Penelitian Lanjutan

* Uji statistik formal (non-parametrik) pada variabel *data loss*.
* Perluasan kondisi jaringan (4G/5G, jaringan terisolasi) dan variasi frekuensi data.
* Automasi pemantauan *resource* (Prometheus/*container metrics*).
* Perbandingan dengan basis data NoSQL (mis. MongoDB) mengacu pada [6].
* Replikasi dengan skala data dan jumlah *run* lebih besar.

---

## Daftar Pustaka

[1] D. Salsabila, A. T. Hanuranto, and A. I. Irawan, "Sistem Monitoring Denyut Jantung Berbasis IoT Menggunakan Protokol XMPP," *JITEL (Jurnal Ilmiah Telekomunikasi, Elektronika, dan Listrik Tenaga)*, vol. 2, no. 2, pp. 171–178, 2022.

[2] S. H. Ahsana, M. B. Syahputra, A. F. F. Putri, and A. A. Prasetyo, "Analisis Perbandingan Performa antara MySQL dan PostgreSQL," *Prosiding Seminar Nasional Teknologi dan Sistem Informasi (SITASI)*, 2023.

[3] R. H. W. Y. Putra and O. Purwaningrum, "Perbandingan Performa Respon Waktu Kueri MySQL, PostgreSQL, dan MongoDB," *Jurnal Sistem Informasi dan Bisnis Cerdas (SIBC)*, vol. 15, no. 1, pp. 39–48, 2022, doi: 10.33005/sibc.v15i1.2749.

[4] *[Lim et al.]*, "Development of a Wireless Real-Time Computation System for Fast and Accurate Heart Rate Monitoring using Photoplethysmography (PPG) Signals," IEEE, [detail perlu diverifikasi — lihat [07-daftar-pustaka.md](07-daftar-pustaka.md)].

[5] Dachi and Suhada, "Eksperimen Perbandingan Operasi CRUD pada MySQL dan PostgreSQL," 2025 [detail perlu diverifikasi — lihat [07-daftar-pustaka.md](07-daftar-pustaka.md)].

[6] M. M. Eyada, W. Saber, M. M. El Genidy, and F. Amer, "Performance Evaluation of IoT Data Management Using MongoDB Versus MySQL Databases in Different Cloud Environments," *IEEE Access*, vol. 8, pp. 110656–110668, 2020, doi: 10.1109/ACCESS.2020.3002164.

---