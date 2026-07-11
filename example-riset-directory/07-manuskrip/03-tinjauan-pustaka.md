# 03-tinjauan-pustaka

Draf bab tinjauan pustaka naskah ilmiah **Tahap 5**.

---

## 2.1 Aliran Data Sensor Detak Jantung (MAX30102) dan IoT Kesehatan

MAX30102 adalah modul sensor terintegrasi berbasis prinsip *photoplethysmography* (PPG), yang mendeteksi volume aliran darah menggunakan pantulan cahaya LED merah dan inframerah pada permukaan kulit. Dalam implementasi IoT kesehatan, sensor ini umumnya digabungkan dengan mikrokontroler pada penelitian ini ESP32 yang membaca dan mengirimkan nilai PPG secara terus menerus melalui jaringan Wi-Fi lokal. Karakteristik utama data hasil pembacaan sensor ini adalah *time-series continuous streaming*  paket data kecil dikirim satu per satu dalam orde milidetik tanpa jeda, berbeda dari data *batch* yang diimpor sekaligus dalam satu waktu karakteristik inilah yang menjadi inti perbedaan konteks pengujian pada penelitian ini dibandingkan studi studi performa database terdahulu.

## 2.2 Sistem Manajemen Basis Data Relasional (RDBMS): MySQL vs PostgreSQL

RDBMS menyimpan data dalam struktur tabel yang saling berelasi. Dua RDBMS *open source* yang dibandingkan dalam penelitian ini memiliki filosofi desain yang berbeda:

* **MySQL** basis data *open-source* yang ringan dan populer, sering dijadikan pilihan *default* oleh pengembang IoT karena kemudahan *setup* dan performa baca/tulis yang cepat untuk beban standar.
* **PostgreSQL** basis data *open-source* tingkat lanjut yang patuh ketat terhadap standar SQL, dirancang untuk menangani kueri kompleks dan beban konkuren dengan stabilitas integritas data setingkat *enterprise*.

Perbedaan filosofi arsitektur inilah yang mendasari hipotesis bahwa kedua sistem berpotensi menunjukkan karakteristik performa yang berbeda ketika dihadapkan pada beban *insert* kontinu berfrekuensi tinggi.

## 2.3 Insert Latency dan Bottleneck

*Insert latency* (waktu simpan) adalah durasi yang dibutuhkan *database engine* sejak menerima perintah `INSERT` hingga data berhasil ditulis dan dikonfirmasi tersimpan, diukur dalam satuan milidetik (ms). Ketika frekuensi kedatangan data dari sensor melampaui kecepatan I/O *disk* atau kapasitas *buffer* memori database, terjadi antrean pemrosesan yang disebut *bottleneck*. Apabila kondisi ini berlangsung terus-menerus, server dapat menolak paket data baru inilah yang berpotensi menyebabkan hilangnya rekam data medis (*data loss*), risiko yang menjadi salah satu fokus evaluasi pada penelitian ini. Selain *insert latency*, beban server (CPU/RAM) turut diukur sebagai indikator seberapa berat sumber daya komputasi yang ditarik oleh masing masing *database engine* saat menahan hantaman data secara terus menerus.

## 2.4 Penelitian Terdahulu (Related Work) dan Celah Penelitian

Riset di bidang IoT kesehatan dan performa basis data relasional selama ini berjalan pada dua jalur yang terpisah. Di satu sisi, penelitian yang berfokus pada perangkat IoT medis umumnya hanya menguji keberhasilan akuisisi dan transmisi data dari sisi perangkat keras. Salsabila dkk. [1] membuktikan bahwa pengiriman data detak jantung (*Beat Per Minute*) melalui protokol pesan instan (XMPP) pada Raspberry Pi mampu mencapai latensi jaringan rata-rata 342,857 ms dengan *packet loss* 0%. Sejalan dengan itu, Lim dkk. [4] mengembangkan alat pantau jantung *real-time* berbasis mikrokontroler Seeed XIAO dan sensor MAX86150 yang terbukti akurat (*error* < 0,31%) serta memiliki waktu *refresh* 30–40% lebih cepat dibanding oksimeter konvensional. Kedua riset ini konsisten menempatkan fokus evaluasi pada sisi sensor dan jalur transmisi, tanpa menyentuh kapasitas database di ujung sistem sebagai tempat data akhirnya disimpan.

Di sisi lain, penelitian yang secara khusus mengevaluasi performa RDBMS justru dilakukan dengan pendekatan yang berlawanan: menggunakan dataset statis yang diimpor sekaligus. Ahsa dkk. [2] menguji responsivitas empat fungsi kueri (CRUD) pada MySQL dan PostgreSQL menggunakan data aplikasi Google Playstore hingga 250.000 baris, dan menyimpulkan PostgreSQL unggul dengan waktu respons konsisten di bawah 4 detik. Putra dkk. [3] memperkuat temuan ini melalui pengujian kueri CRUD, SUM, dan COUNT pada MySQL, PostgreSQL, dan MongoDB menggunakan dataset permainan catur dari Kaggle (20.058 baris), di mana PostgreSQL kembali mencatat waktu respons tercepat (2,5 detik) dibandingkan MySQL (21,6 detik). Dachi dan Suhada [5] menambah bukti serupa melalui simulasi data akademik mahasiswa, dan menyimpulkan PostgreSQL lebih tangguh untuk data kompleks berskala besar, sementara MySQL lebih praktis untuk kebutuhan proyek ringan. Pada ranah yang berdekatan namun berbeda kelas database, Eyada dkk. [6] mengevaluasi performa MySQL versus MongoDB (basis data NoSQL) pada beban data IoT di lingkungan *cloud*, dan menemukan MongoDB unggul saat beban sensor meningkat pesat namun perbandingan ini berada di luar konteks sesama RDBMS relasional yang menjadi fokus penelitian ini.

Pola yang konsisten muncul dari studi-studi di atas riset IoT medis berhenti di titik sensor berhasil membaca dan mengirim data, sedangkan riset performa database berhenti di titik pengujian dengan data yang sudah "diam" (statis, diimpor sekaligus). Belum ada satu pun studi yang menyatukan kedua sisi ini menguji langsung ketahanan MySQL dan PostgreSQL ketika dihadapkan pada aliran data nyata yang datang satu per satu, tanpa jeda, dari perangkat sensor fisik berfrekuensi tinggi. Penelitian ini diposisikan untuk menutup celah tersebut.

### Tabel 1. Ringkasan Peta Literatur

| Peneliti (Tahun) | Metode & Konteks | Hasil Utama | Celah yang Ditemukan |
|---|---|---|---|
| Salsabila dkk. (2022) [1] | IoT, Raspberry Pi, sensor AD8232, protokol XMPP | Latensi rata-rata 342,857 ms, *packet loss* 0% | Belum menguji kapasitas database relasional di *backend* |
| Ahsa dkk. (2023) [2] | Uji CRUD pada MySQL & PostgreSQL, data statis Google Playstore (250.000 baris) | PostgreSQL lebih unggul (< 4 detik) | Beban data statis, belum ada *streaming* dari sensor |
| Putra dkk. (2022) [3] | Uji CRUD/SUM/COUNT pada MySQL, PostgreSQL, MongoDB, data catur Kaggle (20.058 baris) | PostgreSQL tercepat (2,5 detik vs MySQL 21,6 detik) | Data statis, belum ada beban perangkat fisik IoT |
| Lim dkk. (2023) [4] | Alat pantau jantung *real-time*, Seeed XIAO + MAX86150 | Akurasi tinggi (*error* < 0,31%), *refresh* 30–40% lebih cepat | Belum membahas performa penyimpanan di sisi server |
| Dachi & Suhada (2025) [5] | Eksperimen MySQL vs PostgreSQL, data simulasi akademik | PostgreSQL tangguh untuk data kompleks, MySQL praktis untuk proyek ringan | Data simulasi, belum ada data kontinu sensor medis |
| Eyada dkk. (2020) [6] | MySQL vs MongoDB pada IoT *cloud* (data sensor cuaca) | MongoDB unggul saat beban sensor meningkat | Perbandingan relasional vs NoSQL, bukan sesama RDBMS |

**Gap utama yang diangkat:** belum ada penelitian yang menguji batas performa waktu simpan (*insert latency*) dan stabilitas beban memori antara MySQL dan PostgreSQL ketika dihadapkan langsung pada aliran data *continuous streaming* dari sensor detak jantung MAX30102 berfrekuensi tinggi.

## 2.5 Landasan Teori Statistik: Paired Samples T-Test

*Paired Samples T-Test* (Uji T Sampel Berpasangan) adalah analisis statistik parametrik yang digunakan untuk membandingkan rata-rata (*mean*) dari dua pengukuran yang berasal dari subjek/unit percobaan yang sama pada dua kondisi berbeda. Uji ini dipilih bukan *Independent Samples T-Test* karena setiap *run* replikasi ke-*i* menghasilkan sepasang pengukuran yang saling berhubungan nilai *insert latency* (dan beban RAM) MySQL serta PostgreSQL yang diambil pada kondisi pengujian identik (*stream* data sensor yang sama, urutan replikasi yang sama, lingkungan eksekusi yang sama).

Dasar dari uji ini adalah menghitung selisih tiap pasangan pengamatan (*difference score*, dᵢ = X1ᵢ − X2ᵢ), kemudian menguji apakah rata-rata selisih tersebut berbeda signifikan dari nol:

```
t = d̄ / (Sd / √n)      df = n − 1
```

di mana d̄ adalah rata-rata selisih, Sd adalah standar deviasi selisih, dan n = 35 pasangan *run* replikasi. Apabila asumsi normalitas pada selisih (diuji dengan Shapiro-Wilk) tidak terpenuhi, digunakan alternatif non-parametrik **Wilcoxon Signed-Rank Test**, yang menguji median selisih berpasangan tanpa mengasumsikan distribusi normal. Selain nilai signifikansi (*p-value*), dilaporkan pula *effect size* Cohen's d (d = d̄/Sd) untuk mengukur besaran praktis perbedaan performa, bukan sekadar signifikan/tidak signifikan secara statistik.