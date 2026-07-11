# 06-kesimpulan

Draf bab kesimpulan dan saran naskah ilmiah **Tahap 5**.

---

## 5.1 Kesimpulan

Berdasarkan hasil eksperimen terkontrol berpasangan dan analisis statistik inferensial yang telah dilakukan terhadap 35 pasangan *run* replikasi pengujian MySQL dan PostgreSQL pada sistem pemantauan sensor detak jantung MAX30102, penelitian ini menarik tiga kesimpulan utama yang menjawab rumusan masalah:

1. **Insert Latency:** Terdapat perbedaan rata-rata waktu simpan yang **sangat signifikan** secara statistik antara MySQL dan PostgreSQL (t(34)=-23,7493; Sig. 2-tailed=0,000; Cohen's d=-4,0144, efek sangat besar). MySQL terbukti secara nyata lebih cepat (2,1240 ms) dibandingkan PostgreSQL (4,4580 ms) dalam menangani aliran data sensor berfrekuensi tinggi.
2. **Beban RAM:** **Tidak terdapat** perbedaan beban RAM yang signifikan secara statistik antara kedua database (Wilcoxon Signed-Rank, z=0,9009; Asymp. Sig.=0,368). Klaim bahwa salah satu sistem "lebih ringan RAM-nya" tidak didukung oleh data penelitian ini.
3. **Keandalan (Data Loss):** MySQL mencatat total 35 baris data hilang (23 dari 35 *run* bersih, satu lonjakan ekstrem 15 baris akibat *bottleneck* antrean), sedangkan PostgreSQL mencatat 41 baris hilang secara lebih tersebar (hanya 15 dari 35 *run* bersih). Karena perbedaan ini belum diuji signifikansinya secara statistik formal, penelitian ini **tidak menyimpulkan** salah satu database lebih andal dari yang lain berdasarkan metrik ini.

Dari sisi kecepatan (*efficiency*), **MySQL terbukti unggul secara empiris** untuk kebutuhan *insert* data sensor medis *real-time* frekuensi tinggi. Namun, keunggulan ini **tidak disertai** keunggulan pada beban RAM maupun bukti keandalan yang lebih baik, sehingga klaim performa MySQL pada penelitian ini harus dibatasi secara spesifik pada metrik *insert latency* saja, bukan digeneralisasi ke seluruh aspek *resource usage* dan keandalan sistem.

## 5.2 Keterbatasan Penelitian

Beberapa keterbatasan perlu diungkapkan secara transparan agar interpretasi hasil tidak melampaui bukti yang tersedia:

* Pengujian dilakukan pada satu unit perangkat server (Intel Core i3 Gen 12/13, Windows 11) dan satu kondisi jaringan Wi-Fi publik, sehingga hasil belum tentu tergeneralisasi pada spesifikasi *hardware* atau kondisi jaringan yang berbeda.
* Pemantauan beban RAM dilakukan secara manual melalui Task Manager, bukan melalui *automated resource monitoring* (mis. Prometheus/Grafana), sehingga berpotensi terhadap presisi pencatatan waktu-nyata yang terbatas.
* Variabel *data loss* dicatat sebagai metrik sekunder deskriptif dan **belum diuji signifikansinya secara statistik formal** — sehingga interpretasi mengenai keandalan kedua database masih bersifat awal (*preliminary*).
* Jumlah replikasi (n=35 *run* per database) sudah memenuhi syarat minimum uji parametrik, namun ukuran sampel yang lebih besar akan meningkatkan presisi estimasi, khususnya untuk mendeteksi *loss event* yang bersifat *rare* (jarang terjadi).

## 5.3 Saran Penelitian Lanjutan

Untuk menyempurnakan batasan dan memperluas cakupan temuan dalam penelitian ini, beberapa saran diajukan untuk agenda riset mendatang:

* **Uji Statistik Formal pada Data Loss:** Penelitian selanjutnya disarankan menerapkan uji non-parametrik (mis. Wilcoxon Signed-Rank atau McNemar untuk data *count*/kategorikal) khusus pada variabel *data loss*, agar klaim keandalan antar-database dapat diuji secara inferensial, bukan sekadar deskriptif.
* **Perluasan Kondisi Jaringan dan Beban:** Riset lanjutan dapat menguji performa kedua database pada variasi kondisi jaringan (mis. jaringan seluler 4G/5G, jaringan lokal terisolasi) dan variasi frekuensi pengiriman data (di atas 100 sampel/detik) untuk memetakan titik *bottleneck* secara lebih presisi.
* **Automasi Pemantauan Resource:** Menggantikan pemantauan manual Task Manager dengan sistem pemantauan otomatis (*container metrics*, Prometheus, atau *profiler* bawaan database) untuk meningkatkan presisi dan resolusi temporal data beban CPU/RAM.
* **Perbandingan dengan Basis Data NoSQL:** Mengingat literatur terdahulu [6] menunjukkan keunggulan MongoDB pada beban sensor IoT yang meningkat pesat, penelitian lanjutan dapat memperluas perbandingan ke basis data NoSQL guna memetakan trade-off relasional vs non-relasional pada konteks sensor medis yang sama.
* **Replikasi dengan Skala Data Lebih Besar:** Menambah jumlah *run* replikasi dan/atau volume baris per sesi (di atas 1.000 baris) untuk menguji konsistensi temuan pada skala beban yang lebih realistis terhadap kondisi produksi.