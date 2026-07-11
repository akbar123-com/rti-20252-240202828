# 02-pendahuluan

Draf bab pendahuluan naskah ilmiah — **Tahap 5**.

---

## 1.1 Latar Belakang

Perkembangan *Internet of Things* (IoT) di bidang kesehatan (*healthcare*) menuntut arsitektur sistem yang mampu memproses data secara *real-time*. Salah satu komponen penting dalam sistem pemantauan kesehatan adalah sensor detak jantung seperti MAX30102, yang menghasilkan aliran data berfrekuensi tinggi dan harus segera dikirim serta disimpan ke dalam basis data oleh mikrokontroler agar dapat divisualisasikan oleh tenaga medis secara langsung.

Dalam arsitektur server semacam ini, pemilihan sistem manajemen basis data relasional (RDBMS) memegang peranan yang krusial. MySQL dikenal memiliki kecepatan tulis (*write latency*) yang efisien, sementara PostgreSQL memiliki reputasi ketat dalam validasi dan integritas data berskala *enterprise*. Sebagian besar literatur yang ada saat ini hanya menguji kedua database tersebut pada beban data statis atau umum, belum pada konteks aliran data sensor fisik yang datang tanpa jeda. Padahal, pada konteks medis, hantaman paket data sensor secara terus-menerus dapat memicu antrean proses (*bottleneck*) pada disk I/O maupun memori server.

Keterlambatan waktu simpan (*latency*) mungkin masih dapat ditoleransi dalam skala milidetik, namun apabila *bottleneck* tersebut berujung pada penolakan sistem yang menyebabkan hilangnya rekam medis (*data loss*), hal ini dapat berakibat fatal bagi keselamatan pasien. Oleh karena itu, penelitian ini dirancang untuk mengevaluasi secara komprehensif tidak hanya aspek kecepatan (*insert latency*) dan efisiensi beban RAM, tetapi juga menyoroti batas toleransi keandalan kedua database saat diuji di bawah tekanan data frekuensi tinggi secara langsung dari perangkat sensor fisik.

## 1.2 Rumusan Masalah

Berdasarkan pemaparan latar belakang di atas, rumusan masalah dalam penelitian ini dirumuskan sebagai berikut:

1. Bagaimana perbandingan waktu simpan (*insert latency*) antara arsitektur database MySQL dan PostgreSQL dalam menangani operasi *insert* aliran data dari sensor MAX30102?
2. Bagaimana perbandingan efisiensi beban RAM pada server saat kedua database diuji menggunakan parameter eksekusi yang sama?
3. Bagaimana tingkat keandalan dan stabilitas kedua database dalam menjaga integritas paket data (ada tidaknya anomali *data loss*) saat menghadapi antrean transmisi frekuensi tinggi pada beban puncak?

## 1.3 Tujuan Penelitian

1. Mengukur dan membandingkan *latency* kecepatan penyimpanan data antara MySQL dan PostgreSQL secara empiris menggunakan uji beda rata-rata berpasangan.
2. Menganalisis tingkat efisiensi penggunaan memori (RAM) dari kedua database selama proses hantaman data sensor berlangsung.
3. Mengidentifikasi batas kemampuan sistem (*bottleneck*) untuk mengevaluasi apakah kecepatan database berbanding lurus dengan keandalannya terhadap risiko kehilangan data (*data loss*).

## 1.4 Kontribusi Penelitian

Literatur yang ada selama ini terbagi pada dua jalur terpisah: riset IoT medis yang hanya berfokus pada keberhasilan akuisisi sensor tanpa menguji sisi database, dan riset performa RDBMS yang hanya diuji dengan dataset statis yang diimpor sekaligus (lihat Tinjauan Pustaka §2.4). Penelitian ini berkontribusi dengan menyatukan kedua sisi tersebut — menguji langsung ketahanan MySQL dan PostgreSQL ketika dihadapkan pada aliran data nyata dari perangkat sensor fisik berfrekuensi tinggi (*continuous streaming*), bukan data buatan/simulasi. Kontribusi ini bersifat *comparison* empiris yang menghasilkan bukti kuantitatif (bukan sekadar rekomendasi umum) mengenai kondisi di mana masing-masing database unggul, sekaligus mengidentifikasi trade-off antara kecepatan dan keandalan yang belum diungkap oleh studi-studi terdahulu.

## 1.5 Batasan Masalah

Untuk menjaga ruang lingkup penelitian tetap fokus, ditetapkan batasan masalah sebagai berikut:

1. Pengujian transmisi data dilakukan menggunakan koneksi internet publik (Wi-Fi), sehingga latensi jaringan (*network latency*) merupakan faktor alami yang turut memengaruhi performa keseluruhan sistem, merepresentasikan kondisi implementasi IoT di dunia nyata — bukan diperlakukan sebagai *confounding variable* yang perlu dihilangkan.
2. Perangkat keras yang digunakan sebagai *client* pengirim data adalah mikrokontroler ESP32 dan modul sensor MAX30102.
3. Skenario eksperimen dibatasi pada operasi *write/insert* data secara kontinu, dengan jumlah sampel pengujian sebanyak 35 *run* (replikasi) untuk setiap database guna memastikan validitas pengujian statistik.
4. Metrik utama yang dianalisis adalah *insert latency* dan beban RAM; metrik beban *disk* dan jumlah *data loss* dicatat sebagai data pendukung namun belum diuji signifikansi statistiknya secara formal pada penelitian ini (lihat Kesimpulan §5.2).