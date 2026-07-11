# Tahap 1 Perancangan Arsitektur & Skema Variabel

**Status:** Selesai

---

## 1. Komponen Eksperimen dan Pengujian

1. **Artefak Database (MySQL vs PostgreSQL)**  dua *database engine* yang diuji sebagai Variabel Independen. MySQL merepresentasikan *engine* ringan yang dioptimalkan untuk operasi tulis sederhana, sedangkan PostgreSQL merepresentasikan *engine* dengan validasi dan kontrol konkurensi (MVCC) yang lebih ketat.
2. **Sumber Data Fisik (ESP32 + Sensor MAX30102)** perangkat pengirim data tunggal yang membaca detak jantung secara *continuous streaming* dan mengirimkannya lewat Wi-Fi lokal, memastikan kedua database menerima karakteristik beban data yang identik.
3. **Instrumen Pengukur Waktu (Skrip PHP Native + `microtime()`)**  mencatat waktu tepat sebelum dan sesudah operasi `INSERT` untuk menghitung *insert latency* secara presisi dalam satuan milidetik, terpisah dari proses simpan data itu sendiri agar tidak menambah *overhead* pengukuran.
4. **Resource Monitor (Task Manager, Windows 11)** memantau persentase beban CPU dan RAM server secara manual selama pengujian berlangsung.
5. **Pipeline Analisis (Excel, IBM SPSS Statistics, Aplikasi Web Analisis Kustom)** tempat tabulasi master data primer (`data RTI EXEL.xlsx`) untuk mengeksekusi uji parametrik/non-parametrik, dengan aplikasi web PHP kustom sebagai *cross-check* independen dari hasil SPSS.

## 2. Alur Pelaksanaan Eksperimen (Protokol Kontrol)

```
Subjek Masuk (N=35 Orang) → Rekam Detak Jantung via MAX30102 → Simpan 1.000 Baris Data di ESP32
  │
  ▼ (Setiap Subjek Diuji Berpasangan pada Kedua Database)
Database Diaktifkan (MySQL atau PostgreSQL, database pembanding dimatikan)
  │
  ├─ t-awal: skrip PHP mencatat microtime() sebelum operasi INSERT
  │     │
  │     ▼
  ├─ Fase Transmisi: ESP32 menembakkan 1.000 baris data secara terus-menerus tanpa jeda
  │     │ (Berpotensi terjadi bottleneck antrean pada web server/Apache jika beban melampaui kapasitas)
  │     ▼
  └─ t-akhir: skrip PHP mencatat microtime() setelah data tersimpan → hitung insert latency (ms)
        │
        ▼
Task Manager mencatat %RAM selama proses berjalan → Rekap hasil ke dashboard → Pindahkan ke Excel

Catatan:
Sebelum berpindah ke database pembanding, cache dibersihkan dan seluruh proses latar belakang laptop
(Windows Update, Antivirus) dimatikan agar beban CPU/RAM yang tercatat murni berasal dari proses
database, bukan proses lain. Jaringan Wi-Fi yang dipakai ESP32 dan server harus sama persis.

Mekanisme Validitas Data: Jika XAMPP/database tiba-tiba berhenti (crash) saat dihantam data, atau
koneksi Wi-Fi ESP32 terputus di tengah jalan, run pengujian pada subjek tersebut dianggap gugur dan
wajib diulang setelah sistem/koneksi pulih agar tidak merusak distribusi data.
```

## 3. Skema Penataan Data dan Variabel (Master Spreadsheet / SPSS)

Berikut skema struktur penataan kolom data primer yang dirancang pada file master `data RTI EXEL.xlsx` sebelum diimpor ke variabel IBM SPSS Statistics:

| Nama Variabel | Tipe Data | Atribut / Keterangan | Tujuan Analisis |
|---|---|---|---|
| `ID_Subjek` | NUMERIC (Nominal) | Angka urut identitas subjek (1 sampai 35) | Identifikasi sampel berpasangan |
| `MYSQL_LATENSI` / `PG_LATENSI` | NUMERIC (Scale) | Insert latency dalam satuan milidetik | Metrik utama 1 — kecepatan simpan data |
| `MYSQL_RAM` / `PG_RAM` | NUMERIC (Scale) | Beban RAM server dalam satuan persen (%) | Metrik utama 2 — efisiensi beban server |
| `MYSQL_DISK` / `PG_DISK` | NUMERIC (Scale) | Beban disk server dalam satuan persen (%) | Dicatat, di luar cakupan analisis batasan masalah proposal |
| `MYSQL_LOSS` / `PG_LOSS` | NUMERIC (Scale) | Jumlah baris data hilang/gagal tersimpan | Temuan tambahan deskriptif, belum diuji signifikansinya |

**Skema tabel database** (identik di kedua *engine* agar hasil dapat dibandingkan langsung):

```sql
-- MySQL
CREATE TABLE log_jantung (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    nilai_sensor FLOAT    NOT NULL,
    waktu        DATETIME NOT NULL
);

-- PostgreSQL
CREATE TABLE log_jantung (
    id           SERIAL PRIMARY KEY,
    nilai_sensor REAL      NOT NULL,
    waktu        TIMESTAMP NOT NULL
);
```

*Insert latency* dan beban RAM sengaja tidak disimpan sebagai kolom di dalam tabel  keduanya dicatat di luar database (skrip PHP dan Task Manager) agar proses pencatatan metrik tidak ikut menambah beban *insert* yang sedang diukur.

## 4. Skema Penetapan Matriks Eksperimen

| Komponen Eksperimen | Tipe Variabel | Nilai / Batasan Pengukuran | Target Output |
|---|---|---|---|
| **Jenis Database** | Variabel Independen | Kondisi 1: MySQL 8.0<br>Kondisi 2: PostgreSQL 15.x/16.x | Menjadi stimulus perbedaan performa *insert* & beban server |
| **Insert Latency & Beban RAM** | Variabel Dependen | Nilai riil waktu simpan (ms) dan persentase beban RAM (%) | Metrik utama efisiensi & efisiensi server |
| **Frekuensi Data Masuk** | Variabel Kontrol | Dikunci konstan (target 1.000 baris/subjek/database) | Memastikan beban yang identik di kedua database |

## 5. Keputusan Teknis dan Operasional (Final)

1. **Mode Eksperimen**: Menggunakan rancangan eksperimen berpasangan (*Paired/Within-Condition Design*) pada N=35 subjek. Setiap subjek merekam data detak jantungnya sendiri lewat MAX30102, lalu stream data yang sama diujikan bergantian ke MySQL dan PostgreSQL agar perbandingan performa benar-benar *apple-to-apple*.
2. **Standarisasi Perangkat**: Server dikunci pada satu unit  Intel Core i3 Gen 12/13, RAM 8/16 GB DDR5, Windows 11  agar tidak ada bias spesifikasi perangkat antar-sesi pengujian.
3. **Lingkungan Pengujian**: Menggunakan jaringan Wi-Fi publik (bukan jaringan terisolasi) untuk ESP32 dan server, sehingga latensi jaringan dianggap sebagai faktor alami yang merepresentasikan kondisi implementasi IoT di dunia nyata  sesuai batasan masalah pada proposal.
4. **Software Analisis**: Menggunakan **IBM SPSS Statistics** sebagai pipeline komputasi utama (*Analyze → Compare Means → Paired-Samples T-Test*, atau *Nonparametric Tests → Related Samples* untuk Wilcoxon), diverifikasi silang lewat aplikasi web analisis kustom berbasis PHP.
5. **Skenario Tugas**: Skenario tunggal yang diseragamkan  "Kirimkan 1.000 baris data detak jantung secara terus-menerus (*continuous streaming*) dari ESP32 ke database yang sedang aktif diuji, catat rata-rata *insert latency* dan beban RAM." Tidak ada variasi tambahan selama satu sesi run berlangsung.