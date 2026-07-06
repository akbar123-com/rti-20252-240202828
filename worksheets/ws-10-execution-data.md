# WS-10: Experiment Execution & Data Collection

> **Bab 10 — Eksekusi Eksperimen & Pengumpulan Data**

---

## Ringkasan Materi

### Experiment Execution Pipeline

```
Design → Execution Plan → Controlled Execution → Data Collection → Data Logging → Dataset for Analysis
```

### Multiple Run = Non-Negotiable

Single run **tidak pernah cukup** untuk klaim ilmiah. Minimum 5-10 run per skenario dengan seed berbeda. Multiple run menghasilkan:
- Mean, std, confidence interval
- Distribusi hasil → uji statistik
- Variabilitas → error bar di grafik

### Execution Plan

Setiap eksperimen harus memiliki plan sebelum eksekusi:
- Daftar skenario
- Jumlah run per skenario
- Random seed per run (pre-determined!)
- Urutan eksekusi (randomisasi/counterbalancing)
- Pre-execution checklist

### Data Logging Komprehensif

Setiap run menghasilkan log terstruktur:
1. **Identitas** — Run ID, timestamp, skenario
2. **Konfigurasi** — Semua parameter, seed, code version
3. **Hasil** — Semua metrik, output detail
4. **Metadata** — Waktu eksekusi, resource usage, warning/error

Format: CSV/JSON/database — **bukan stdout yang di-copy-paste**.

### Engineering vs Research Execution

| Aspek | Engineering | Research |
|-------|-----------|---------|
| Run | Sekali (deploy) | Multiple (min 5-10, seed berbeda) |
| Logging | Error log, access log | Semua parameter, metrik, metadata |
| Anomali | Bug → fix → redeploy | Investigasi → dokumentasi → analisis |
| Urutan | Tidak penting | Bisa bias — perlu randomisasi |

### Anomali = Dokumentasi, Bukan Hapus

Run gagal/anomali tidak boleh dihapus tanpa dokumentasi. Bisa jadi:
- **Bug** → fix & re-run (dokumentasikan!)
- **Batas kemampuan metode** → DNF = temuan
- **Data yang bias** jika hanya simpan run "berhasil"

### Jebakan Kognitif

1. "Satu angka cukup" → tanpa distribusi, tidak bisa diuji
2. "Seed tidak penting" → bahkan algoritma deterministik bisa dipengaruhi library stokastik
3. "Run gagal langsung hapus" → kehilangan temuan potensial
4. "Semua run harus hari ini" → thermal throttling, fatigue

---

## Template A.10 — Execution Plan & Data Log

```
EXECUTION PLAN

| Run # | Skenario | Seed | Parameter | Status | Waktu | Output File |
|-------|----------|------|-----------|--------|-------|-------------|
| 1     |          |      |           |        |       |             |
| 2     |          |      |           |        |       |             |
| 3     |          |      |           |        |       |             |
| ...   |          |      |           |        |       |             |

Jumlah runs per skenario : ____
Total runs               : ____

DATA LOG (per run):
  Run ID    : ____________________
  Timestamp : ____________________
  Skenario  : ____________________
  Input     : ____________________
  Output    : ____________________
  Anomali   : ____________________
  Catatan   : ____________________
```

---

## Latihan 1 — Execution Plan

Susun execution plan untuk eksperimen Anda. Tentukan skenario, jumlah run, dan seed sebelum eksekusi.

| Run # | Skenario | Seed | Parameter Kunci | Status |
|-------|----------|------|----------------|--------|
| 1 | MySQL (Subjek 1) | N/A | engine=InnoDB, beban=1.000 data | Planned |
| 2 | MySQL (Subjek 2)  | N/A | engine=InnoDB, beban=1.000 data | Planned|
| 3 | MySQL (Subjek 3) | N/A | engine=InnoDB, beban=1.000 data | Planned |
| 4 | MySQL (Subjek 4)  | N/A | engine=InnoDB, beban=1.000 data | Planned|
| 5 | MySQL (Subjek 5) | N/A | engine=InnoDB, beban=1.000 data | Planned |
| 6 | MySQL (Subjek 6)  | N/A | engine=InnoDB, beban=1.000 data | Planned|
| 7 | MySQL (Subjek 7) | N/A | engine=InnoDB, beban=1.000 data | Planned |
| 8 | MySQL (Subjek 8)  | N/A | engine=InnoDB, beban=1.000 data | Planned|
| 9 | MySQL (Subjek 9) | N/A | engine=InnoDB, beban=1.000 data | Planned |
| 10 | MySQL (Subjek 10)  | N/A | engine=InnoDB, beban=1.000 data | Planned|
| 11 | MySQL (Subjek 11) | N/A | engine=InnoDB, beban=1.000 data | Planned |
| 12 | MySQL (Subjek 12)  | N/A | engine=InnoDB, beban=1.000 data | Planned|
| 13 | MySQL (Subjek 13) | N/A | engine=InnoDB, beban=1.000 data | Planned |
| 14 | MySQL (Subjek 14)  | N/A | engine=InnoDB, beban=1.000 data | Planned|
| 15 | MySQL (Subjek 15) | N/A | engine=InnoDB, beban=1.000 data | Planned |
| 16 | MySQL (Subjek 16)  | N/A | engine=InnoDB, beban=1.000 data | Planned|
| 17 | MySQL (Subjek 17) | N/A | engine=InnoDB, beban=1.000 data | Planned |
| 18 | MySQL (Subjek 18)  | N/A | engine=InnoDB, beban=1.000 data | Planned|
| 19 | MySQL (Subjek 19) | N/A | engine=InnoDB, beban=1.000 data | Planned |
| 20 | MySQL (Subjek 20)  | N/A | engine=InnoDB, beban=1.000 data | Planned|
| 21 | MySQL (Subjek 21) | N/A | engine=InnoDB, beban=1.000 data | Planned |
| 22 | MySQL (Subjek 22)  | N/A | engine=InnoDB, beban=1.000 data | Planned|
|23| MySQL (Subjek 23) | N/A | engine=InnoDB, beban=1.000 data | Planned |
| 24 | MySQL (Subjek 24)  | N/A | engine=InnoDB, beban=1.000 data | Planned|
| 25 | MySQL (Subjek 25) | N/A | engine=InnoDB, beban=1.000 data | Planned |
| 26 | MySQL (Subjek 26)  | N/A | engine=InnoDB, beban=1.000 data | Planned|
| 27 | MySQL (Subjek 27) | N/A | engine=InnoDB, beban=1.000 data | Planned |
| 28 | MySQL (Subjek 28)  | N/A | engine=InnoDB, beban=1.000 data | Planned|
| 29 | MySQL (Subjek 29) | N/A | engine=InnoDB, beban=1.000 data | Planned |
| 30 | MySQL (Subjek 30)  | N/A | engine=InnoDB, beban=1.000 data | Planned|
| 31 | MySQL (Subjek 31) | N/A | engine=InnoDB, beban=1.000 data | Planned |
| 32 | MySQL (Subjek 32)  | N/A | engine=InnoDB, beban=1.000 data | Planned|
| 33 | MySQL (Subjek 33) | N/A | engine=InnoDB, beban=1.000 data | Planned |
| 34 | MySQL (Subjek 34)  | N/A | engine=InnoDB, beban=1.000 data | Planned|
| 35 | MySQL (Subjek 35) | N/A | engine=InnoDB, beban=1.000 data | Planned |
| 36 | MySQL (Subjek 36)  | N/A | engine=InnoDB, beban=1.000 data | Planned|
| 37 | MySQL (Subjek 37) | N/A | engine=InnoDB, beban=1.000 data | Planned |
| 38 | MySQL (Subjek 38)  | N/A | engine=InnoDB, beban=1.000 data | Planned|
| 39 | MySQL (Subjek 39) | N/A | engine=InnoDB, beban=1.000 data | Planned |
| 40 | MySQL (Subjek 40)  | N/A | engine=InnoDB, beban=1.000 data | Planned|
| 41| PostgreSQL (Subjek 1) | N/A | engine=PostgreSQL, beban=1.000 data| Planned |
| 42| PostgreSQL (Subjek 2) | N/A| engine=PostgreSQL, beban=1.000 data| Planned |
| 43 | PostgreSQL (Subjek 3) | N/A | engine=PostgreSQL, beban=1.000 data| Planned |
| 44 | PostgreSQL (Subjek 4) | N/A| engine=PostgreSQL, beban=1.000 data| Planned |
| 45 | PostgreSQL (Subjek 5) | N/A | engine=PostgreSQL, beban=1.000 data| Planned |
| 46 | PostgreSQL (Subjek 6) | N/A| engine=PostgreSQL, beban=1.000 data| Planned |
| 47 | PostgreSQL (Subjek 7) | N/A | engine=PostgreSQL, beban=1.000 data| Planned |
| 48 | PostgreSQL (Subjek 8) | N/A| engine=PostgreSQL, beban=1.000 data| Planned |
| 49 | PostgreSQL (Subjek 9) | N/A | engine=PostgreSQL, beban=1.000 data| Planned |
| 50 | PostgreSQL (Subjek 10) | N/A| engine=PostgreSQL, beban=1.000 data| Planned |
| 51 | PostgreSQL (Subjek 11) | N/A | engine=PostgreSQL, beban=1.000 data| Planned |
| 52 | PostgreSQL (Subjek 12) | N/A| engine=PostgreSQL, beban=1.000 data| Planned |
| 53 | PostgreSQL (Subjek 13) | N/A | engine=PostgreSQL, beban=1.000 data| Planned |
| 54 | PostgreSQL (Subjek 14) | N/A| engine=PostgreSQL, beban=1.000 data| Planned |
| 55 | PostgreSQL (Subjek 15) | N/A | engine=PostgreSQL, beban=1.000 data| Planned |
| 56 | PostgreSQL (Subjek 16) | N/A| engine=PostgreSQL, beban=1.000 data| Planned |
| 57 | PostgreSQL (Subjek 17) | N/A | engine=PostgreSQL, beban=1.000 data| Planned |
| 58 | PostgreSQL (Subjek 18) | N/A| engine=PostgreSQL, beban=1.000 data| Planned |
| 59 | PostgreSQL (Subjek 19) | N/A | engine=PostgreSQL, beban=1.000 data| Planned |
| 60 | PostgreSQL (Subjek 20) | N/A| engine=PostgreSQL, beban=1.000 data| Planned |
| 61 | PostgreSQL (Subjek 21) | N/A | engine=PostgreSQL, beban=1.000 data| Planned |
| 62 | PostgreSQL (Subjek 22) | N/A| engine=PostgreSQL, beban=1.000 data| Planned |
|  63| PostgreSQL (Subjek 23) | N/A | engine=PostgreSQL, beban=1.000 data| Planned |
| 64 | PostgreSQL (Subjek 24) | N/A| engine=PostgreSQL, beban=1.000 data| Planned |
| 65 | PostgreSQL (Subjek 25) | N/A | engine=PostgreSQL, beban=1.000 data| Planned |
| 66 | PostgreSQL (Subjek 26) | N/A| engine=PostgreSQL, beban=1.000 data| Planned |
| 67 | PostgreSQL (Subjek 27) | N/A | engine=PostgreSQL, beban=1.000 data| Planned |
| 68 | PostgreSQL (Subjek 28) | N/A| engine=PostgreSQL, beban=1.000 data| Planned |
| 69 | PostgreSQL (Subjek 29) | N/A | engine=PostgreSQL, beban=1.000 data| Planned |
| 70 | PostgreSQL (Subjek 30) | N/A| engine=PostgreSQL, beban=1.000 data| Planned |
|


**Total skenario:** 2
**Run per skenario:** 35
**Total run keseluruhan:** 70

---

## Latihan 2 — Data Log Terstruktur

Desain format data log untuk eksperimen Anda. Tentukan field apa saja yang akan dicatat.

**Identitas:**
| Field | Contoh |
|-------|--------|
| Run ID | run-mysql-01 |
| subjek ID | Subjek-01 |
| Timestemp| 2026-06-11T10:00:00|

**Konfigurasi:**
| Field | Contoh |
|-------|--------|
| database engine | MySQL 8.0|
| beban data| 10.000 baris |

**Hasil:**
| Metrik | Tipe Data | Range Valid |
|--------|----------|-------------|
| Insert Latency  | float| 0.0 – 1000.0 (ms) |
| Beban CPU Server| float| 0.0 – 100.0 (%)|
| Beban RAM Server |float | 0.0 – 100.0 (%)|

**Format output:** [X] CSV / [ ] JSON / [ ] Database / [ ] Lainnya: ____

---

## Latihan 3 — Anomaly Protocol

Rencanakan bagaimana menangani anomali. Untuk setiap jenis, tentukan langkah yang diambil.

| Jenis Anomali | Contoh | Tindakan |
|---------------|--------|----------|
| Run gagal (crash) | XAMPP/MySQL tiba-tiba mati (stop working) saat dihantam ribuan data.|  Dokumentasi log error, restart laptop dan layanan XAMPP, lalu ulang run dari awal.|
| Hasil ekstrem | Waktu simpan mendadak sangat lambat tembus di atas 1 detik per baris data.|Cek apakah ada Windows Update atau Antivirus yang berjalan di background. Matikan proses tersebut, lalu ulangi pengujian. |
| Waktu eksekusi anomali | Pengiriman data dari ESP32 berhenti di tengah jalan (misal baru masuk 5.000 data).| Cek koneksi Wi-Fi lokal. Putuskan perangkat lain yang numpang konek ke router, reset ESP32, lalu ulangi.|
| Inkonsistensi dengan run lain | Hasil pengujian pada Subjek ke-15 berbeda jauh (lebih lambat) dibandingkan subjek sebelumnya.| Catat sebagai temuan (kemungkinan laptop mengalami thermal throttling/kepanasan). Beri jeda pendinginan laptop 15 menit, lalu tes ulang. |

**Prinsip:** Detect → Investigate → Document → Decide

---

## Refleksi

> Pernahkah Anda melaporkan hasil riset/tugas dari single run? Apa risikonya? Bagaimana multiple run mengubah kepercayaan terhadap hasil?

**Pengalaman sebelumnya:**
> Pernah, saat mengerjakan tugas kuliah biasa. Biasanya kalau program sudah jalan dan datanya masuk ke database sekali saja (single run), langsung di-screenshot dan dilaporkan sebagai hasil final. Risikonya sangat tinggi karena angka performa yang didapat mungkin sedang bias (misalnya kebetulan laptop baru dinyalakan sehingga masih sangat cepat, atau kebetulan ada delay Wi-Fi sehingga terlihat sangat lambat). Kita tidak benar-benar tahu performa aslinya seperti apa.
**Yang akan dilakukan berbeda:**
> Untuk eksperimen ini, saya akan melakukan multiple run menggunakan sampel detak jantung dari 35 orang yang berbeda, di mana setiap orang akan direkam 1.000 aliran datanya untuk diuji ke MySQL dan PostgreSQL. Pengulangan ke 35 subjek ini akan mengubah kepercayaan terhadap hasil secara drastis, karena database dipaksa menangani variasi data yang natural dari banyak sumber. Hasil rata-rata dari 70 kali pengujian total ini akan memenuhi syarat kecukupan sampel untuk uji statistik (Independent Sample T-Test), sehingga kesimpulannya dijamin valid dan bebas dari bias kebetulan.
