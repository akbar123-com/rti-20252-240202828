# WS-11: Data Validation & Integrity

> **Bab 11 — Validasi Data & Integritas**

---

## Ringkasan Materi

### Data Trust Model

```
Raw Data → Data Cleaning → Consistency Check → Validation Process → Trusted Data
```

Data mentah belum bisa dipercaya. Harus melewati pipeline validasi sebelum siap untuk analisis statistik.

### Empat Pilar Data Quality

| Pilar | Deskripsi | Contoh Pelanggaran |
|-------|----------|-------------------|
| **Accuracy** | Nilai dalam range masuk akal | Akurasi = 1.5 (di luar [0,1]) |
| **Consistency** | Format seragam di semua run | Run 1: CSV, Run 2: JSON |
| **Completeness** | Tidak ada data hilang dari plan | 97 dari 100 run tercatat |
| **Validity** | Data sesuai desain eksperimen | Parameter baseline tercampur treatment |

### Proses Validasi Progresif

1. **Format validation** — Tipe file, header, kolom
2. **Range validation** — Nilai dalam batas logis
3. **Consistency validation** — Format seragam antar-run
4. **Logic validation** — Data cocok dengan desain eksperimen

Jika gagal di langkah awal → tidak perlu lanjut.

### Anomaly Detection — 3 Jenis

| Jenis | Deskripsi | Deteksi |
|-------|----------|---------|
| **Statistical outlier** | Nilai di luar distribusi normal | IQR: < Q1-1.5×IQR atau > Q3+1.5×IQR |
| **Contextual anomaly** | Normal absolut, abnormal dalam konteks | Run 1-10: ~91%, Run 11-20: ~88% |
| **Pattern anomaly** | Pola sistematis (bukan random) | Performa menurun berurutan |

**Prinsip:** Detect → Investigate → Document → Decide — **JANGAN langsung hapus.**

### Engineering vs Research Validation

| Aspek | Engineering | Research |
|-------|-----------|---------|
| Tujuan | Data sesuai spesifikasi bisnis | Data layak untuk analisis statistik |
| Missing data | Impute / set default | Investigasi penyebab → dokumentasi |
| Outlier | Bug → fix | Mungkin temuan → investigasi |
| Dokumentasi | Minimal (log error) | Komprehensif (anomali + keputusan) |

### Jebakan Kognitif

1. "Logging otomatis ≠ data benar" → bisa ada bug di logger
2. "Outlier = hapus" → bisa jadi temuan penting
3. "Dataset kecil tidak perlu validasi" → justru lebih rentan
4. "Mean normal = data benar" → [94, 95, 93, **44**, 94] → mean 84% terlihat wajar

---

## Template A.11 — Data Validation Checklist

```
DATA VALIDATION CHECKLIST

Completeness:
  [ ] Semua skenario tercakup
  [ ] Jumlah run sesuai rencana
  [ ] Tidak ada file output hilang
  Missing: ____ dari ____ data points

Format Consistency:
  [ ] Semua file format sama (CSV/JSON/...)
  [ ] Header konsisten
  [ ] Tipe data konsisten (numerik tetap numerik)

Range & Logic:
  [ ] Nilai dalam range masuk akal
  [ ] Tidak ada waktu negatif
  [ ] Metrik 0–100%, tidak di luar range
  Anomali ditemukan: ____________________

Cross-Validation:
  [ ] Run identik → hasil mendekati
  [ ] Trend konsisten dengan ekspektasi teori

Keputusan:
  [ ] Data siap analisis
  [ ] Perlu cleaning
  [ ] Perlu re-run (skenario: ____)
```

---

## Latihan 1 — Completeness Check

Verifikasi apakah semua data yang direncanakan sudah terkumpul.

| Skenario | Run Direncanakan | Run Tercatat | Missing | Alasan |
|----------|-----------------|-------------|---------|--------|
| Pengujian MySQL (35 Sampel) | 35.000 |  34.965 | 35 | Bottleneck / antrean macet pada web server (Apache) saat menerima hantaman data frekuensi tinggi. |
| Pengujian PostgreSQL (35 Sampel) | 35.000 | 34.959 | 41 | Overload resource memori (RAM) server yang lebih berat, menyebabkan timeout atau packet loss. |


**Total expected:** 70.000 | **Total actual:** 69.924 | **Missing:** 76

**Keputusan untuk data missing:**
> Diterima dan dibiarkan apa adanya sebagai temuan riset (Research Finding). Data yang hilang tidak diulang/dibuang karena justru menjadi bukti nyata (gap) adanya perbedaan tingkat keandalan (reliability) dan batas performa antara MySQL dan PostgreSQL saat menangani aliran data sensor berkecepatan tinggi.

---

## Latihan 2 — Anomaly Investigation

Periksa data Anda untuk anomali. Gunakan metode IQR atau z-score.

**Dataset sampel (atau data Anda sendiri):**

| Run | Data Hilang / Loss (Baris) |
|-----|-------------|
| 1 | 0 |
| 2 | 0 |
| 3 | 1 |
| 4 | 15 |
| 5 | 0 |

**Deteksi outlier:**
> urutkan data dari terkecil ke terbesar terlebih dahulu  0, 0, 0, 1, 15
- Q1 = 0 | Q3 = 1 | IQR = 1
- Batas bawah (Q1 - 1.5×IQR) = 0 - 1.5(1) = -1.5
- Batas atas (Q3 + 1.5×IQR) = 1 + 1.5(1) = 2.5
- Outlier terdeteksi: 15 (Karena angka 15 jauh melampaui batas atas 2.5)

**Investigasi (untuk setiap outlier):**

| Outlier | Nilai | Kemungkinan Penyebab | Keputusan |
|---------|-------|---------------------|-----------|
| Run 4 | 15 | Terjadi bottleneck (kemacetan antrean koneksi) parah secara tiba-tiba di web server XAMPP akibat hantaman data ESP32 yang beruntun tanpa jeda.| Re-run dengan cooling interval (jeda waktu) untuk melihat apakah server bisa pulih, ATAU jadikan sebagai bukti riset batas toleransi database. |

---

## Latihan 3 — Validation Report

Buat laporan validasi ringkas untuk dataset eksperimen Anda.

**1. Completeness:** 99.89% data terkumpul (69.924/70000*100)
**2. Format:** [x] Konsisten / [ ] Ada inkonsistensi: ____
**3. Range check (anomali):** Terdapat 1 nilai outlier (15 baris data hilang) pada pengujian MySQL Run 4.
**4. Logic check:** [x] Parameter sesuai plan / [ ] Ada ketidaksesuaian: ____

**Kesimpulan:** [x] Data siap analisis / [ ] Perlu tindakan: ____

---

## Refleksi

> Apa perbedaan antara "data yang benar" dan "data yang dipercaya"? Mengapa proses validasi formal diperlukan meskipun data dikumpulkan secara otomatis?

> Data yang benar adalah data yang secara teknis sukses direkam dan ditulis oleh sistem ke dalam database tidak error format atau corrupt. Sedangkan Data yang dipercaya adalah data yang tidak hanya benar secara format, tetapi juga logis, konsisten, merepresentasikan kondisi lapangan secara faktual, dan bisa dipertanggungjawabkan asal-usul anomalinya.
> Proses validasi formal tetap mutlak diperlukan meskipun pengumpulan data dilakukan secara otomatis oleh skrip atau sistem (seperti web dan ESP32), karena sistem otomatis tidak bisa mendeteksi kewajaran. Sistem otomatis bisa saja mengalami bottleneck, timeout, atau antrean macet yang menyebabkan data hilang secara tak kasat mata (seperti hilangnya 15 baris pada run ke-4). Validasi formal memastikan peneliti menyadari anomali teknis tersebut, mencari penyebabnya, dan tidak mengambil kesimpulan mentah mentah bahwa mesin  selalu otomatis sempurna.