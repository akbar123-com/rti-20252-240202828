# 04-metodologi

Draf bab metodologi penelitian naskah ilmiah **Tahap 5**.

---

## 3.1 Desain Penelitian dan Unit Analisis

Penelitian ini menggunakan pendekatan kuantitatif dengan metode eksperimen terkontrol (*Controlled Experiment*) menggunakan rancangan berpasangan (*Paired/Within-Condition Design*). Desain ini dipilih karena setiap *run* replikasi menghasilkan sepasang pengamatan pada kondisi jaringan, frekuensi kirim data, dan lingkungan server yang identik satu database aktif diuji, sementara yang lain dimatikan, kemudian dipertukarkan pada *run* pembanding.

Unit analisis dalam penelitian ini adalah *run* pengujian (bukan responden manusia): setiap *run* merepresentasikan satu sesi pengiriman 1.000 baris data sensor secara *continuous streaming* ke satu database yang sedang aktif. Variabel independen (IV) penelitian ini adalah **jenis database relasional yang digunakan**, dengan PostgreSQL diposisikan sebagai kondisi pembanding terhadap MySQL sebagai kondisi *baseline* dari sisi kepraktisan implementasi IoT.

## 3.2 Arsitektur Sistem Pengujian

Sistem pengujian terdiri atas lima komponen utama:

1. **ESP32 + Sensor MAX30102 (Client/Pengirim Data)** membaca nilai detak jantung dari sensor, lalu mengirimkannya secara *continuous streaming* melalui Wi-Fi lokal ke server. Firmware ditulis dan diunggah menggunakan Arduino IDE.
2. **Web Server Lokal (XAMPP/Apache)** menjalankan skrip *backend* penangkap data.
3. **Skrip Penerima/Logger (PHP Native)** menerima paket data dari ESP32, mencatat *timestamp* sebelum dan sesudah eksekusi `INSERT` menggunakan `microtime(true)` untuk menghitung *insert latency*, lalu menuliskannya ke database yang sedang aktif diuji. Koneksi database ditentukan lewat konfigurasi terpusat (*configuration-driven*), bukan *hardcode* di banyak tempat, agar pengujian dapat diulang secara konsisten.
4. **Database Engine (dipertukarkan via konfigurasi)** MySQL 8.0 dan PostgreSQL 15.x/16.x diuji bergantian (satu aktif, satu dimatikan) pada kondisi jaringan dan beban yang identik.
5. **Resource Monitor (Task Manager, Windows 11)** memantau pemakaian CPU/RAM server secara manual selama pengujian berlangsung.

### Alur Data Pengujian

```
Sensor MAX30102 → ESP32 (kirim via Wi-Fi lokal, continuous streaming)
  → Skrip PHP Native (XAMPP/Apache, catat timestamp mulai)
    → [DB aktif?] → MySQL 8.0  atau  PostgreSQL 15/16
      → catat timestamp selesai → hitung insert_latency (ms)
        → Task Manager catat %RAM
          → Rekap 1.000 baris per sesi pengujian
```

### Spesifikasi Environment

| Komponen | Spesifikasi |
|---|---|
| CPU | Intel Core i3 (Gen 12/13) |
| RAM | 8 GB / 16 GB DDR5 |
| Sistem Operasi | Windows 11 |
| Runtime Backend | PHP 8.x (Native) via XAMPP/Apache |
| Database | MySQL 8.0 & PostgreSQL 15.x/16.x |
| Firmware | Arduino IDE (ESP32) |

### Skema Tabel Database

Struktur tabel dibuat identik di kedua *engine* agar hasil pengukuran dapat dibandingkan langsung:

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

*Insert latency* dan beban RAM sengaja tidak disimpan sebagai kolom di dalam tabel keduanya dicatat di luar database (di skrip PHP dan Task Manager) agar proses pencatatan metrik tidak menambah beban *insert* itu sendiri.

## 3.3 Variabel, Metrik, dan Prosedur Eksperimen

* **Variabel Independen (IV):** Jenis database relasional (MySQL vs PostgreSQL).
* **Variabel Dependen (DV):** *Insert latency* (ms) dan beban RAM server (%).
* **Variabel Kontrol (CV):** frekuensi pengiriman data sensor dikunci konstan (mis. 100 sampel/detik) pada jaringan Wi-Fi yang sama untuk kedua database; target 1.000 baris per sesi/*run*.
* **Metrik Sekunder:** jumlah baris data yang berhasil tersimpan tanpa hilang (*data loss*/*packet loss*), dicatat sebagai temuan tambahan meskipun belum menjadi fokus uji hipotesis utama.

**Prosedur pelaksanaan tiap *run*:**

1. Pastikan laptop server dan ESP32 terhubung ke jaringan Wi-Fi yang sama, dengan akses internet publik aktif (bukan jaringan terisolasi) latensi jaringan dianggap sebagai faktor alami yang merepresentasikan kondisi implementasi IoT di dunia nyata, sesuai batasan masalah penelitian.
2. Nyalakan *service* database yang sedang diuji (mis. MySQL), pastikan database pembanding dalam kondisi mati.
3. Jalankan skrip *backend* PHP, lalu aktifkan ESP32 agar mulai mengirim data.
4. Pantau persentase RAM server melalui Task Manager selama pengujian berjalan.
5. Setelah 1.000 baris data masuk, catat rata-rata *insert latency* dan jumlah baris yang berhasil tersimpan, matikan database yang diuji, bersihkan *cache*, lalu ulangi prosedur untuk database pembanding pada *run* berikutnya.

Prosedur ini diulang sebanyak **35 kali (run)** untuk setiap database, menghasilkan 35 pasangan pengamatan berpasangan yang siap dianalisis secara statistik.

## 3.4 Teknik Analisis Data

Data *insert latency* dan beban RAM yang telah dikumpulkan dari 35 pasangan *run* dianalisis menggunakan statistik inferensial melalui IBM SPSS Statistics, dengan tahapan sebagai berikut:

1. **Pra-pemrosesan (*Preprocessing*):** pembersihan karakter satuan (mis. "ms", "%") pada kolom metrik karena SPSS mewajibkan format numerik murni.
2. **Analisis Deskriptif:** menghitung *mean* dan standar deviasi *insert latency* serta beban RAM untuk masing-masing database.
3. **Uji Normalitas Selisih:** uji Shapiro-Wilk terhadap selisih (*difference score*) tiap pasangan pengamatan, bukan terhadap data mentah masing-masing kelompok, karena syarat normalitas pada uji-t berpasangan berlaku pada variabel selisihnya.
4. **Uji Hipotesis Komparatif:**
   * Jika selisih berdistribusi normal → **Paired Samples T-Test** pada tingkat kepercayaan 95% (α = 0,05).
   * Jika selisih tidak berdistribusi normal → **Wilcoxon Signed-Rank Test** sebagai alternatif non-parametrik.
5. **Effect Size:** dihitung Cohen's d (d = d̄/Sd) untuk uji parametrik guna menilai besaran praktis perbedaan, di luar signifikansi statistik semata.

**Kriteria pengambilan keputusan:** H0 ditolak (H1 diterima) apabila nilai Sig. (2-tailed) < 0,05 **dan** selisih rata-rata antar-database ≥ 10% — kombinasi kriteria ini digunakan agar perbedaan yang dinyatakan signifikan juga bermakna secara praktis, bukan sekadar signifikan secara statistik akibat ukuran sampel.

*Catatan homogenitas varians:* pada desain berpasangan, uji Levene's Test (homogenitas varians dua kelompok independen) tidak diperlukan, karena analisis dilakukan terhadap satu variabel selisih (d), bukan dua kelompok independen yang variansnya perlu dibandingkan.