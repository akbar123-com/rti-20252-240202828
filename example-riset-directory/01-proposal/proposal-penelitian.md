# Proposal Penelitian

**Judul:** Analisis Perbandingan Performa Database MySQL dan PostgreSQL pada Sistem Pemantauan Data Sensor Detak Jantung Frekuensi Tinggi

---

## 1. Latar Belakang
Perkembangan Internet of Things (IoT) di bidang kesehatan medis (healthcare) menuntut arsitektur sistem yang mampu memproses data secara real-time. Salah satu hal penting dalam sistem ini adalah sensor detak jantung  seperti MAX30102, yang menghasilkan aliran data berfrekuensi tinggi. Data ini harus segera dikirim dan disimpan ke dalam pangkalan data (database) oleh mikrokontroler untuk divisualisasikan oleh tenaga medis.

Dalam arsitektur server, pemilihan sistem manajemen basis data relasional (RDBMS) memegang peranan penting. MySQL dikenal memiliki kecepatan tulis (write latency) yang sangat efisien, sementara PostgreSQL memiliki reputasi ketat dalam validasi dan integritas data berskala enterprise. Sebagian besar literatur saat ini hanya menguji kedua database pada beban data umum. Namun, pada konteks medis, sistem dihadapkan pada masalah penting yaitu hantaman paket data sensor secara terus-menerus tanpa jeda dapat memicu antrean proses (bottleneck) pada disk I/O. 

Keterlambatan waktu simpan (latency) mungkin bisa ditoleransi dalam skala milidetik, namun jika bottleneck tersebut berujung pada penolakan sistem yang menyebabkan hilangnya rekam medis (data loss), hal tersebut bisa berakibat fatal. Oleh karena itu, penelitian ini dirancang untuk mengevaluasi secara komprehensif tidak hanya aspek kecepatan dan efisiensi RAM, tetapi juga menyoroti batas toleransi keandalan (integritas data) antara MySQL dan PostgreSQL saat diuji di bawah tekanan data frekuensi tinggi secara ekstrem.

## 2. Rumusan Masalah
Berdasarkan pemaparan latar belakang di atas, rumusan masalah dalam penelitian ini adalah:
1. Bagaimana perbandingan waktu simpan (latency) antara arsitektur database MySQL dan PostgreSQL dalam menangani operasi insert aliran data dari sensor MAX30102?
2. Bagaimana perbandingan efisiensi beban RAM  pada server saat kedua database diuji menggunakan parameter eksekusi yang sama?
3. Bagaimana tingkat keandalan dan stabilitas kedua database dalam menjaga integritas paket data (ada tidaknya anomali data loss) saat menghadapi antrean transmisi frekuensi tinggi pada beban puncak?

## 3. Tujuan Penelitian
1. Mengukur dan membandingkan latency kecepatan penulisan atau simpan data antara MySQL dan PostgreSQL secara empiris.
2. Menganalisis tingkat efisiensi penggunaan memori (RAM) dari kedua database selama proses hantaman data berlangsung.
3. Mengidentifikasi batas maksimal kemampuan sistem (bottleneck) untuk mengevaluasi apakah kecepatan database berbanding lurus dengan keamanannya terhadap risiko kehilangan data (data loss).

## 4. Batasan Masalah
Untuk menjaga agar ruang lingkup penelitian tetap fokus, ditetapkan batasan masalah sebagai berikut:
1. Pengujian transmisi data dilakukan menggunakan koneksi internet publik (Wi-Fi), sehingga latensi pengiriman (network latency) merupakan faktor alami yang turut memengaruhi performa keseluruhan sistem, merepresentasikan kondisi implementasi IoT di dunia nyata.
2. Perangkat keras yang digunakan sebagai client pengirim data adalah mikrokontroler ESP32 dan modul sensor MAX30102.
3. Skenario eksperimen dibatasi pada operasi write/insert data secara kontinu dengan jumlah sampel pengujian sebanyak 35 run (replikasi) untuk setiap database guna memastikan validitas pengujian statistik.