# 05-hasil-analisis

Draf bab hasil dan analisis naskah ilmiah — **Tahap 5**.

---

## 4.1 Statistik Deskriptif

Berdasarkan hasil pengumpulan data primer dari 35 pasangan *run* replikasi (target 1.000 baris/*run*, total 70.000 baris data sensor terjadwal), analisis statistik deskriptif dilakukan untuk melihat profil pemusatan data *insert latency* dan beban RAM pada masing-masing database. Data mentah tercatat pada `04-data/data RTI EXEL.xlsx` dan diproses menggunakan IBM SPSS Statistics serta aplikasi web analisis kustom (`05-kode/web-analysis/`).

### Tabel 1. Statistik Deskriptif Paired Samples (N = 35)

| Metrik | Database | N | Mean | Std. Deviation | Minimum | Maximum |
|---|---|---:|---:|---:|---:|---:|
| Insert Latency (ms) | MySQL | 35 | 2,1240 | 0,3598 | 1,5123 | 2,7412 |
| Insert Latency (ms) | PostgreSQL | 35 | 4,4580 | 0,5549 | 3,5491 | 5,4120 |
| Beban RAM (%) | MySQL | 35 | 86,6500 | 2,6118 | 81,38 | 91,10 |
| Beban RAM (%) | PostgreSQL | 35 | 86,2534 | 1,8648 | 80,09 | 88,95 |

Secara deskriptif, MySQL rata-rata menyelesaikan operasi simpan data **2,3340 ms** lebih cepat dibandingkan PostgreSQL. Pada metrik beban RAM, kedua database menunjukkan beban rata-rata yang relatif setara (selisih hanya 0,3966%), jauh lebih kecil dibandingkan selisih pada metrik *latency*.

## 4.2 Analisis Korelasi Sampel Berpasangan

Sebelum pengujian hipotesis komparatif, dilakukan analisis korelasi Pearson untuk melihat kekuatan hubungan antara nilai kedua database pada *run* yang sama.

### Tabel 2. Paired Samples Correlations

| Pasangan Metrik | N | Koefisien Korelasi (r) | Sig. (2-tailed) |
|---|---:|---:|---:|
| Latency (MySQL vs PostgreSQL) | 35 | 0,249 | 0,150 |
| RAM (MySQL vs PostgreSQL) | 35 | 0,440 | 0,008 |

Korelasi *latency* antar kedua sistem tergolong lemah dan **tidak signifikan** (r=0,249, p=0,150 > 0,05) — performa *latency* MySQL pada suatu *run* tidak dapat memprediksi performa *latency* PostgreSQL pada *run* yang sama, mengindikasikan bahwa faktor penentu kecepatan *insert* pada kedua *engine* bersifat independen satu sama lain. Sebaliknya, korelasi RAM tergolong sedang dan **signifikan** (r=0,440, p=0,008 < 0,05), menunjukkan kedua database cenderung mengalami kenaikan/penurunan beban RAM secara bersamaan — kemungkinan besar dipengaruhi oleh kondisi *resource* host (server) yang sama saat pengujian berlangsung, bukan oleh karakteristik internal masing-masing *engine*.

## 4.3 Pengujian Hipotesis Perbedaan (Paired Samples Test)

### 4.3.1 Insert Latency

Uji normalitas selisih (Shapiro-Wilk) menunjukkan W=0,948, p=0,098 (**normal**, p>0,05), sehingga **Paired Samples T-Test** digunakan sebagai uji utama.

### Tabel 3. Hasil Uji Paired Samples T-Test — Insert Latency

| Parameter Uji | Nilai |
|---|---:|
| Mean Selisih (MySQL − PostgreSQL) | -2,3340 ms |
| Std. Deviation Selisih | 0,5814 |
| df | 34 |
| t-hitung | -23,7493 |
| Sig. (2-tailed) | 0,000000 |
| Cohen's d | -4,0144 |

Nilai Sig. (2-tailed) = 0,000 < 0,05 **dan** selisih rata-rata mencapai ±110% (jauh melampaui ambang batas 10% yang ditetapkan), sehingga **H0 ditolak, H1 diterima**. Terdapat perbedaan *insert latency* yang sangat signifikan secara statistik antara MySQL dan PostgreSQL, dengan *effect size* tergolong *huge* (|d| = 4,01).

### 4.3.2 Beban RAM

Uji normalitas selisih (Shapiro-Wilk) menunjukkan W=0,871, p=0,0007 (**tidak normal**, p<0,05), sehingga digunakan **Wilcoxon Signed-Rank Test** sebagai uji utama (Paired T-Test tetap dijalankan sebagai pembanding, namun tidak dipakai sebagai dasar keputusan akhir).

### Tabel 4. Hasil Uji — Beban RAM

| Uji | Statistik | Nilai | Sig. |
|---|---|---:|---:|
| Wilcoxon Signed-Rank | Median MySQL / PostgreSQL | 86,60 / 86,45 | — |
| Wilcoxon Signed-Rank | W = 259,5 ; z = 0,9009 | — | 0,367650 (Asymp. Sig., 2-tailed) |
| *Pembanding:* Paired T-Test | Mean Selisih = 0,3966 ; SD = 2,4530 ; df = 34 ; t = 0,9565 | — | 0,345596 *(tidak dipakai — asumsi normalitas dilanggar)* |

Nilai Asymp. Sig. (2-tailed) = 0,368 > 0,05, sehingga **H0 diterima, H1 ditolak**. Tidak terdapat perbedaan beban RAM yang signifikan secara statistik antara MySQL dan PostgreSQL pada kondisi pengujian ini.

## 4.4 Temuan Tambahan: Anomali Data Loss

Di luar dua metrik utama, tercatat anomali jumlah baris data yang gagal tersimpan (*data loss*) pada masing-masing skenario pengujian:

### Tabel 5. Ringkasan Data Loss

| Skenario | Baris Direncanakan | Baris Tercatat | Baris Hilang | Run Tanpa Loss | Catatan |
|---|---:|---:|---:|---:|---|
| MySQL (35 *run*) | 35.000 | 34.965 | 35 | 23 dari 35 | Satu lonjakan ekstrem 15 baris pada satu *run* akibat *bottleneck* antrean web server |
| PostgreSQL (35 *run*) | 35.000 | 34.959 | 41 | 15 dari 35 | Kehilangan tersebar pada lebih banyak *run*, diduga akibat *overload resource* memori |

Total baris data yang hilang dari 70.000 baris terjadwal adalah 76 baris (0,11%). **Perbedaan jumlah *data loss* ini belum diuji signifikansinya secara statistik formal** (mis. uji Wilcoxon/Mann-Whitney pada variabel *loss*) dalam penelitian ini, sehingga pola yang teramati — MySQL jarang kehilangan data namun rentan lonjakan ekstrem, sedangkan PostgreSQL lebih sering kehilangan data namun dalam jumlah kecil per insiden — dilaporkan sebagai temuan deskriptif, bukan klaim inferensial (lihat pembahasan §4.5 dan keterbatasan pada Kesimpulan §5.2).

## 4.5 Pembahasan

Hasil pengujian menunjukkan pola yang tidak seragam di antara ketiga metrik yang diamati, dan pola ini penting untuk dimaknai secara hati-hati agar tidak melahirkan generalisasi yang berlebihan.

Pada metrik **insert latency**, keunggulan MySQL terbukti sangat signifikan secara statistik dengan *effect size* yang tergolong sangat besar. Temuan ini konsisten dengan reputasi MySQL sebagai *engine* yang dioptimalkan untuk operasi tulis sederhana dan cepat, sementara PostgreSQL — yang dirancang dengan mekanisme validasi dan *concurrency control* (MVCC) yang lebih ketat — secara arsitektural memang membawa *overhead* tambahan pada setiap operasi `INSERT` tunggal.

Pada metrik **beban RAM**, tidak ditemukan perbedaan yang signifikan. Temuan ini penting karena membantah asumsi awal bahwa keunggulan kecepatan MySQL turut disertai efisiensi memori yang lebih baik. Signifikansi korelasi RAM (r=0,440, p=0,008) yang justru muncul antar kedua database mengindikasikan bahwa fluktuasi beban RAM lebih banyak dipengaruhi oleh kondisi *resource host* bersama (server yang sama menjalankan kedua *engine* secara bergantian) dibandingkan oleh karakteristik arsitektur *engine* itu sendiri.

Pada temuan **data loss**, pola yang muncul justru berlawanan dari asumsi umum "cepat tapi tidak stabil vs lambat tapi stabil": PostgreSQL yang lebih lambat justru mengalami *data loss* pada proporsi *run* yang lebih besar (20 dari 35 *run*), sementara MySQL yang lebih cepat justru lebih sering bersih dari *loss* (23 dari 35 *run*) namun mengalami satu lonjakan ekstrem yang mengindikasikan kerentanan terhadap *bottleneck* pada beban puncak. Karena belum diuji signifikansinya secara statistik, penelitian ini tidak menyimpulkan bahwa salah satu database "lebih andal" dari yang lain berdasarkan metrik ini saja.

Secara keseluruhan, hasil ini menegaskan bahwa klaim keunggulan performa database bersifat *metric-specific* — keunggulan pada satu metrik (*latency*) tidak dapat digeneralisasi secara otomatis ke metrik lain (RAM, keandalan) tanpa pengujian statistik terpisah untuk masing-masing metrik tersebut.