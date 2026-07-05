# Tinjauan Pustaka dan Landasan Teori

**Judul Penelitian:** Analisis Perbandingan Performa Database MySQL dan PostgreSQL pada Sistem Pemantauan Data Sensor Detak Jantung Frekuensi Tinggi

---

## 1. State of the Art (Penelitian Terdahulu)

Riset di bidang Internet of Things (IoT) kesehatan dan performa basis data relasional selama ini berjalan pada dua jalur yang terpisah. Di satu sisi, penelitian yang berfokus pada perangkat IoT medis umumnya hanya menguji keberhasilan akuisisi dan transmisi data dari sisi perangkat keras. Salsabila et al. (2022) misalnya membuktikan bahwa pengiriman data detak jantung (Beat Per Minute) melalui protokol pesan instan (XMPP) pada Raspberry Pi mampu mencapai latensi jaringan yang sangat rendah dengan packet loss 0%. Sejalan dengan itu, Lim et al. (2023) mengembangkan alat pantau jantung real-time berbasis mikrokontroler Seeed XIAO dan sensor MAX86150 yang terbukti akurat (error < 0,31%) serta memiliki waktu refresh 30–40% lebih cepat dibanding oximeter konvensional. Kedua riset ini konsisten menempatkan fokus evaluasi pada sisi sensor dan jalur transmisi, tanpa menyentuh sama sekali kapasitas database di ujung sistem sebagai tempat data akhirnya disimpan.

Di sisi lain, penelitian yang secara khusus mengevaluasi performa RDBMS justru dilakukan dengan pendekatan yang berlawanan: menggunakan dataset statis yang diimpor sekaligus. Ahsa et al. (2023) menguji responsivitas empat fungsi kueri (CRUD) pada MySQL dan PostgreSQL menggunakan data aplikasi Google Playstore hingga 250.000 baris, dan menyimpulkan PostgreSQL unggul dengan waktu respons yang konsisten di bawah 4 detik. Putra et al. (2022) memperkuat temuan ini melalui pengujian CRUD, SUM, dan COUNT pada MySQL, PostgreSQL, dan MongoDB menggunakan dataset permainan catur dari Kaggle (20.058 baris), di mana PostgreSQL kembali mencatat waktu respons tercepat (2,5 detik) dibandingkan MySQL (21,6 detik). Dachi dan Suhada (2025) menambah bukti serupa melalui simulasi data akademik mahasiswa, dan menyimpulkan PostgreSQL lebih tangguh untuk data kompleks berskala besar, sementara MySQL lebih praktis untuk kebutuhan proyek ringan.

Pola yang konsisten muncul dari kelima studi di atas: riset IoT medis berhenti di titik sensor berhasil membaca dan mengirim data, sedangkan riset performa database berhenti di titik pengujian dengan data yang sudah "diam" (statis, diimpor sekaligus). Belum ada satu pun studi yang menyatukan kedua sisi ini — menguji langsung ketahanan MySQL dan PostgreSQL ketika dihadapkan pada aliran data nyata yang datang satu per satu, tanpa jeda, dari perangkat sensor fisik berfrekuensi tinggi. Penelitian ini diposisikan untuk menutup celah tersebut.

## 2. Research Gap

Berdasarkan pemetaan literatur pada bagian 1, teridentifikasi empat jenis celah penelitian (research gap) yang saling menguatkan:

| Jenis Gap | Uraian |
|---|---|
| **Performance Gap** | Performa MySQL dan PostgreSQL selama ini hanya teruji pada beban data statis yang sudah tersimpan di memori. Belum diketahui bagaimana ketahanan keduanya saat dibanjiri data secara beruntun tiap milidetik dari perangkat sensor fisik. |
| **Method Gap** | Pengujian performa database umumnya memakai perangkat *stress-testing* (mis. sysbench/pgbench) yang menyuntikkan data secara serentak di server lokal, bukan menguji kecepatan respons saat menerima data yang mengalir tanpa henti (*streaming*) dari mikrokontroler nyata. |
| **Data Gap** | Dataset yang dipakai riset-riset sebelumnya (Ahsa, Putra) selalu berupa file statis (CSV/Excel). Belum ada pengujian dengan data berkarakteristik *continuous streaming* / *time-series* dari sensor fisik secara real-time. |
| **Context Gap** | Evaluasi database selama ini difokuskan pada konteks aplikasi web/desktop biasa, belum pada konteks monitoring IoT medis, di mana keterlambatan respons sekecil apa pun berisiko menghilangkan data vital pasien. |

**Gap utama yang diangkat:** belum ada penelitian yang menguji batas performa waktu simpan (*insert latency*) dan stabilitas beban memori antara MySQL dan PostgreSQL ketika dihadapkan langsung pada aliran data *continuous streaming* dari sensor detak jantung MAX30102 berfrekuensi tinggi. Gap ini penting karena keandalan sistem pemantauan kesehatan bergantung pada kemampuan database menahan tekanan data yang datang terus-menerus — jika gagal, hasilnya bukan sekadar performa buruk, melainkan hilangnya rekam data medis (*data loss*) yang bisa berakibat fatal.

## 3. Landasan Teori

### 3.1 Aliran Data Sensor Detak Jantung (MAX30102)

MAX30102 adalah modul sensor terintegrasi berbasis prinsip *photoplethysmography* (PPG), yang mendeteksi volume aliran darah menggunakan pantulan cahaya LED merah dan inframerah pada permukaan kulit. Dalam implementasi IoT kesehatan, sensor ini digabungkan dengan mikrokontroler ESP32 yang membaca dan mengirimkan nilai PPG secara terus-menerus melalui jaringan Wi-Fi lokal. Karakteristik utama data ini adalah *time-series continuous streaming* — paket data kecil dikirim satu per satu dalam orde milidetik tanpa jeda, berbeda dari data batch yang dikirim sekaligus dalam satu waktu.

### 3.2 Arsitektur Sistem: Input–Process–Output

Mengacu pada pemetaan konteks sistem pada tahap perumusan masalah, alur kerja sistem yang diteliti dapat digambarkan sebagai berikut:

- **Input** — nilai PPG mentah dari sensor MAX30102, dikirim oleh ESP32 pada frekuensi tetap (misalnya 100 sampel per detik) melalui Wi-Fi lokal.
- **Process** — server menerima data tersebut dan mengeksekusi perintah `INSERT` secara berurutan; MySQL dan PostgreSQL diuji secara bergantian pada kondisi jaringan dan beban yang identik.
- **Output** — catatan waktu simpan (*insert latency*) tiap baris data, jumlah baris yang berhasil tersimpan tanpa hilang, serta rekaman pemakaian CPU/RAM server selama pengujian berlangsung.

Arsitektur ini dirancang modular: sisi pengirim (ESP32 + sensor) berdiri terpisah dari sisi penerima (database engine), sehingga jenis database (variabel independen) dapat ditukar tanpa mengubah kode di sisi perangkat keras.

### 3.3 Sistem Manajemen Basis Data Relasional (RDBMS)

RDBMS menyimpan data dalam struktur tabel yang saling berelasi. Dua RDBMS yang dibandingkan dalam penelitian ini:

- **MySQL** — basis data open-source yang ringan, populer, dan sering dijadikan pilihan default oleh pengembang IoT karena kemudahan setup dan performa baca/tulis yang cepat untuk beban standar.
- **PostgreSQL** — basis data open-source tingkat lanjut yang patuh ketat terhadap standar SQL, dirancang untuk menangani kueri kompleks dan beban konkuren dengan stabilitas integritas data setingkat enterprise.

### 3.4 Insert Latency dan Bottleneck

*Insert latency* (waktu simpan) adalah durasi yang dibutuhkan database sejak menerima perintah `INSERT` hingga data berhasil ditulis ke disk. Ketika frekuensi kedatangan data dari sensor melampaui kecepatan I/O disk atau kapasitas buffer memori database, terjadi antrean pemrosesan yang disebut *bottleneck*. Jika kondisi ini berlangsung terus-menerus, server dapat menolak paket data baru — inilah yang berpotensi menyebabkan hilangnya rekam data medis (*data loss*), risiko yang menjadi inti dari gap penelitian ini.

## 4. Definisi Operasional Variabel

Mengacu pada rantai operasionalisasi RQ → Variable → Metric → Data → Analysis, berikut definisi operasional variabel penelitian:

| Variabel | Tipe | Konsep Abstrak | Metrik Konkret | Skala | Satuan |
|---|---|---|---|---|---|
| Jenis database | Independen (IV) | Arsitektur penyimpanan data relasional | Kategori pengujian: MySQL vs PostgreSQL | Nominal | – |
| Waktu simpan & beban server | Dependen (DV) | Performa dan efisiensi server | Waktu eksekusi `INSERT` per baris data; persentase pemakaian CPU/RAM | Rasio | Milidetik (ms) & Persen (%) |
| Frekuensi data & kondisi perangkat | Kontrol (CV) | Kesetaraan lingkungan pengujian | Laju pengiriman data sensor dikunci konstan (mis. 100 sampel/detik) pada jaringan Wi-Fi yang sama untuk kedua database | Rasio | Data per detik (SPS) |

**Metrik sekunder:** persentase data yang berhasil tersimpan tanpa hilang (*success rate* / *packet loss*), ditambahkan karena kecepatan simpan yang tinggi tidak bermakna jika ternyata dicapai dengan mengorbankan keutuhan data.

**Research Question:**
> Bagaimana perbandingan kecepatan waktu simpan (*insert latency*) dan beban memori server antara database MySQL dan PostgreSQL ketika digunakan untuk menangani aliran data berfrekuensi tinggi secara terus-menerus (*continuous streaming*) dari sensor detak jantung MAX30102?

**Hipotesis:**
- **H₀:** Tidak ada perbedaan performa yang signifikan antara MySQL dan PostgreSQL dalam hal *insert latency* dan beban memori saat menerima aliran data sensor MAX30102 berfrekuensi tinggi.
- **H₁:** Terdapat perbedaan performa yang signifikan antara MySQL dan PostgreSQL, dengan salah satu database mencatatkan *insert latency* lebih cepat dan beban server lebih efisien (selisih rata-rata minimal 10%, p-value < 0,05).

---

## Daftar Pustaka (sementara)

1. Salsabila, dkk. (2022). *Pengiriman data Beat Per Minute menggunakan protokol XMPP pada Raspberry Pi.*
2. Ahsa, dkk. (2023). *Pengujian responsivitas kueri CRUD pada MySQL dan PostgreSQL.*
3. Putra, dkk. (2022). *Perbandingan performa kueri CRUD, SUM, dan COUNT pada MySQL, PostgreSQL, dan MongoDB.*
4. Lim, dkk. (2023). *Pengembangan alat pantau jantung real-time berbasis Seeed XIAO dan MAX86150.*
5. Dachi & Suhada. (2025). *Eksperimen perbandingan operasi CRUD pada MySQL dan PostgreSQL.*

> Catatan: lengkapi entri di atas dengan nama depan lengkap, nama jurnal/prosiding, volume, dan halaman sebelum dipindahkan ke `02-literatur/daftar-pustaka.bib` — data lengkap bisa diambil dari sumber yang kamu kutip di WS-03.