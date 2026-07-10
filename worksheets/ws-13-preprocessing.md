# WS-13: Data Preprocessing

> **Bab 13 — Preprocessing & Persiapan Data untuk Analisis**

---

## Ringkasan Materi

### Data Refinement Pipeline

```
Raw Data → Cleaning → Transformation → Normalization → Processed Data → Analysis Ready
```

Setiap tahap memiliki tujuan berbeda. **Preprocessing bukan langkah teknis biasa** — setiap keputusan preprocessing adalah keputusan riset yang bisa mengubah kesimpulan.

### Empat Prinsip Preprocessing

| Prinsip | Deskripsi |
|---------|----------|
| **Consistency** | Metode sama untuk data yang sama |
| **Transparency** | Setiap langkah terdokumentasi |
| **Reproducibility** | Orang lain bisa mengulang dengan hasil sama |
| **Minimal Distortion** | Ubah sesedikit mungkin; jika normalisasi tidak perlu, jangan lakukan |

### Cleaning Triad

| Masalah | Strategi | Risiko |
|---------|---------|--------|
| **Missing values** | | |
| — Listwise deletion | Missing < 5%, random | Data loss |
| — Mean/median imputation | Sedikit missing, dist. normal | Mengurangi variabilitas |
| — Model-based imputation | Banyak missing, pola sistematis | Introduces dependency |
| — Flag & separate | Missing karena alasan substantif | Kompleksitas analisis |
| **Duplikat** | Identifikasi → verifikasi → hapus | False positive (data mirip ≠ duplikat) |
| **Error format** | Standardisasi tipe, encoding | Kehilangan informasi saat konversi |

### Normalisasi — Kapan & Metode Mana

| Metode | Formula | Output | Sensitif Outlier? |
|--------|---------|--------|-------------------|
| Min-max | (x-min)/(max-min) | [0, 1] | Ya |
| Z-score | (x-mean)/std | Unbounded | Lebih robust |
| Robust scaling | (x-median)/IQR | Unbounded | Paling robust |

**Kunci:** Parameter normalisasi harus dihitung dari **training set saja** — bukan seluruh data. Pelanggaran = **data leakage**.

### Data Leakage Prevention

Data leakage terjadi ketika informasi dari test set "bocor" ke preprocessing:
- Normalisasi parameter dari seluruh dataset ← **SALAH**
- Cross-validation dilakukan sebelum split ← **SALAH**
- Feature selection menggunakan label test set ← **SALAH**

### Jebakan Kognitif

1. "Preprocessing cuma teknis — tidak perlu detail" → bisa ubah kesimpulan
2. "Lebih banyak preprocessing = lebih bersih = lebih baik" → over-processing distorsi data
3. "Normalisasi selalu diperlukan" → belum tentu, tergantung metode analisis
4. "Imputation sama untuk semua situasi" → strategi harus sesuai konteks

---

## Template A.13 — Preprocessing Documentation Log

```
PREPROCESSING LOG

Dataset           : ____________________
Jumlah data awal  : ____________________

Cleaning:
| Masalah | Jumlah Kasus | Penanganan | Justifikasi |
|---------|-------------|------------|-------------|
| Missing |             |            |             |
| Duplikat|             |            |             |
| Error   |             |            |             |

Transformation:
| Transformasi | Variabel | Detail | Alasan |
|-------------|----------|--------|--------|
|             |          |        |        |

Normalization:
  Metode    : ____________________
  Alasan    : ____________________
  Parameter : (dihitung dari: training set / seluruh data)

Leakage Check:
  [ ] Parameter normalisasi dari training set saja
  [ ] Tidak ada informasi test set dalam preprocessing
  [ ] Cross-validation dilakukan setelah split

Jumlah data akhir : ____________________
Script tersedia   : [ ] Ya → path: ____ | [ ] Belum
```

---

## Latihan 1 — Cleaning Plan

Periksa dataset Anda (atau dataset contoh) dan dokumentasikan masalah yang ditemukan.

| Masalah | Jumlah Kasus | Penanganan | Justifikasi |
|---------|-------------|------------|-------------|
| Terdapat string teks ("ms" dan "%") pada kolom metrik numerik. | 70 dari 70 baris (100%) | String removal (Penghapusan karakter teks) | Baik SPSS maupun aplikasi web analisis custom (yang menjalankan Paired-Samples T-Test) mewajibkan format Numeric murni; kolom bertipe string tidak bisa diproses uji statistik. |
| Format pemisah desimal bawaan sistem (titik .) tidak terbaca. | 70 dari 70 baris (100%) | Find and Replace (Mengubah . menjadi ,) | Menyesuaikan regional settings OS agar tidak terjadi error "no valid cases". |
| Nilai Outlier pada metrik Data Hilang MySQL (Run 6 / FAREL = 15 baris).|1 dari 70 baris (1.4%) | Retain (Dipertahankan / Tidak dihapus) | Merupakan true anomaly (bukti batas toleransi bottleneck server), bukan error sensor.|


**Jumlah data sebelum cleaning:** 70 baris (gabungan 35 run MySQL dan 35 run PostgreSQL).
**Jumlah data setelah cleaning:** 70 baris 
**Persentase data yang hilang/berubah:** 0% data dihapus (Semua baris dipertahankan, hanya 100% mengalami perubahan format / re-formatting).

---

## Latihan 2 — Normalisasi Decision

Tentukan apakah data Anda perlu normalisasi, dan jika ya, metode apa yang tepat.

| Variabel | Range Asli | Distribusi | Outlier? | Metode Normalisasi | Alasan |
|----------|-----------|-----------|----------|-------------------|--------|
| Waktu Simpan (Latency) |1.5 – 5.5 ms | Mendekati Normal | Tidak | Min-Max Scaler (atau Z-Score) | Skala data kecil dan stabil, menjaga proporsi tanpa mengubah distribusi. |
|Beban RAM |80 – 92 % | Left-skewed (Condong ke atas) | Tidak| Min-Max Scaler |Mengubah format persentase puluhan (80-92) menjadi seragam ke skala 0 hingga 1. |
| Data Loss (Hilang)|0 – 15 baris| Sangat Right-skewed| Ya (15) |Robust Scaling | Memiliki outlier yang valid (bottleneck). Robust scaling kebal terhadap efek tarikan outlier ekstrem ini.|

**Apakah normalisasi diperlukan?** [x] Ya / [ ] Tidak
**Justifikasi:**
> Rentang nilai antar variabel sangat timpang (Latency berskala satuan 1-5, sedangkan RAM berskala puluhan 80-100). Jika data ini kelak diolah menggunakan algoritma Machine Learning (seperti untuk memprediksi beban server), perbedaan skala ini akan membuat model bias dan menganggap RAM lebih penting daripada Latency hanya karena angkanya lebih besar. Normalisasi wajib dilakukan untuk menyamakan bobot semua parameter. (Catatan akademis: Namun untuk sekadar Uji T-Test di SPSS, normalisasi ini bersifat opsional karena T-Test tidak membandingkan skala antar-variabel secara menyilang).

**Leakage check:**
- [v] Parameter dihitung dari training set saja
- [v] Normalisasi diterapkan setelah train-test split

---

## Latihan 3 — Preprocessing Report

Buat ringkasan preprocessing lengkap — dokumentasi yang cukup bagi orang lain untuk mereplikasi.

```
PREPROCESSING SUMMARY

1. Dataset: Perbandingan Performa Database IoT (MySQL vs PostgreSQL) dengan Sensor MAX30102
2. Data awal: 70 records, 5 features
3. Cleaning:
   - Missing values: 0 kasus, metode: Tidak ada (N/A)
   - Duplikat: 0 kasus, tindakan: Tidak ada (N/A)
   - Error: 70 kasus, tindakan: Penghapusan string ("ms" dan "%") serta replace pemisah desimal (titik ke koma).
4. Transformation: Konversi tipe data dari String menjadi Numeric murni.
5. Normalisasi: Min-Max Scaler & Robust Scaling (metode), parameter dari Training Set
6. Data akhir: 70 records, 5 features
7. Leakage check: [x] Lulus / [ ] Ada masalah
```

---

## Refleksi

> Apakah Anda pernah melakukan normalisasi "karena biasa dilakukan" tanpa mempertimbangkan apakah benar-benar diperlukan? Apa risiko over-preprocessing?

> dulu saya sering mikir kalau semua data itu wajib dinormalisasi atau dibuang nilai ekstremnya cuma gara-gara ngikutin kebiasaan atau tutorial di internet. Ternyata, terlalu berlebihan memoles data (over-preprocessing) itu risikonya lumayan fatal. Pertama, makna asli datanya malah jadi susah dipahami untuk presentasi, misalnya kecepatan latency 4,43 ms malah berubah wujud jadi angka rasio 0,8 yang abstrak. Kedua, dan ini yang paling bahaya, kita bisa tanpa sadar menghilangkan fakta penting di lapangan. Contohnya di pengujian saya kemarin ada anomali 15 data yang hilang beruntun gara-gara server MySQL mengalami kemacetan antrean (bottleneck). Kalau angka yang nyleneh itu saya paksa hapus cuma demi bikin distribusi datanya kelihatan mulus dan normal, itu sama saja memanipulasi hasil uji. Padahal, angka eror tersebut justru temuan penting buat membuktikan di mana titik batas kemampuan maksimal dari database yang sedang diteliti.