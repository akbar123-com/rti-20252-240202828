# 04-data

Data mentah hasil pengujian *Insert Latency* & Beban Server (MySQL vs PostgreSQL) — output dari **Tahap 3** (Implementasi & Eksekusi Eksperimen), input untuk **Tahap 4** (Analisis Statistik).

## Isi

- [`raw/data RTI EXEL.xlsx`](raw/data_RTI_mysql_postgresql.xlsx) — data mentah hasil eksekusi, format asli (Excel)
- [`raw/SPSS RTI FINAL.spv`](raw/SPSS RTI FINAL.spv) 
- [`data-dictionary.md`](data-dictionary.md) — deskripsi tiap kolom/variabel dalam dataset
- [`ringkasan-validasi.md`](ringkasan-validasi.md) — ringkasan hasil validasi & anomali data (rujukan: WS-11)

## Desain Pengambilan Data

- **Jenis desain:** *paired* — setiap subjek (orang) diuji pada MySQL **dan** PostgreSQL secara berpasangan, dengan stream data sensor MAX30102 yang identik per subjek.
- **N = 35 pasangan run** (35 subjek, masing-masing menghasilkan 1 pasang pengamatan MySQL–PostgreSQL).
- **Beban per run:** ±10.000 baris data per subjek per database (lihat WS-10, Latihan 2).
- **Sumber data:** aliran data sensor MAX30102 (detak jantung) dari ESP32, dikirim ke MySQL dan PostgreSQL untuk diukur waktu simpan (*insert latency*) dan beban servernya (RAM, disk, packet loss).

## Metrik yang Dicatat

Delapan kolom metrik tersedia di dataset mentah, namun **hanya 2 metrik yang dipakai** pada analisis statistik tahap ini sesuai batasan masalah proposal:

| Kolom | Dipakai? | Keterangan |
|---|---|---|
| `MYSQL_LATENSI` / `PG_LATENSI` | ✅ Dipakai | Insert Latency (ms) — metrik utama 1 |
| `MYSQL_RAM` / `PG_RAM` | ✅ Dipakai | Beban RAM server (%) — metrik utama 2 |
| `MYSQL_DISK` / `PG_DISK` | ⏳ Dicatat, tidak dianalisis | Beban disk (%) — di luar cakupan analisis saat ini |
| `MYSQL_LOSS` / `PG_LOSS` | ⏳ Dicatat, tidak dianalisis | Baris data hilang/gagal tersimpan — di luar cakupan analisis saat ini |

## Catatan

- Data di folder ini bersifat **mentah (raw)** dan belum melalui preprocessing (lihat WS-13 untuk aturan yang berlaku sebelum data dianalisis).
- Hasil olahan (uji normalitas, Paired Samples T-Test, statistik deskriptif, grafik) disimpan di `../06-output/`.
- Landasan teori metode statistik yang dipakai untuk mengolah data ini ada di `../03-teori/` (lihat *Landasan Teori & Metodologi Analisis Statistik Komparatif* — versi Paired Samples T-Test).
