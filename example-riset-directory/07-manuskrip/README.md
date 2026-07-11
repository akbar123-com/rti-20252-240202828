# 07-manuskrip

Draf naskah ilmiah — **Tahap 5**, target publikasi Sinta 5/4 atau Scopus Q3-Q4.

**Judul:** *Analisis Perbandingan Performa Database MySQL dan PostgreSQL pada Sistem Pemantauan Data Sensor Detak Jantung Frekuensi Tinggi*

## Naskah konsolidasi

- [naskah-jurnal.md](naskah-jurnal.md) — naskah lengkap dalam template jurnal standar (Judul/Penulis, Abstrak ID+EN, §1 Pendahuluan – §5 Kesimpulan, Daftar Pustaka)

## Struktur naskah per bagian (sumber/draf kerja)

- [00-outline.md](00-outline.md) — outline, peta sumber, dan daftar klaim kunci yang harus konsisten
- [01-abstrak.md](01-abstrak.md) — Abstrak (ID & EN)
- [02-pendahuluan.md](02-pendahuluan.md) — Pendahuluan (latar belakang, rumusan masalah, tujuan, kontribusi, batasan)
- [03-tinjauan-pustaka.md](03-tinjauan-pustaka.md) — Tinjauan Pustaka (sensor MAX30102, RDBMS, *insert latency*, *related work*, landasan statistik; lihat [`../02-literatur/`](../02-literatur/) dan [`../03-teori/`](../03-teori/))
- [04-metodologi.md](04-metodologi.md) — Metodologi (desain eksperimen berpasangan, arsitektur sistem ESP32+MySQL/PostgreSQL, teknik analisis)
- [05-hasil-analisis.md](05-hasil-analisis.md) — Hasil & Analisis (mengacu pada [`../06-output/hasilkesimpulan.md`](../06-output/hasilkesimpulan.md))
- [06-kesimpulan.md](06-kesimpulan.md) — Kesimpulan, Keterbatasan & Saran Penelitian Lanjutan
- [07-daftar-pustaka.md](07-daftar-pustaka.md) — Daftar Pustaka (6 referensi, format IEEE — 4 telah terverifikasi, 2 masih perlu dilengkapi peneliti)

> `naskah-jurnal.md` adalah gabungan final dari bagian-bagian di atas. Pemindahan ke template jurnal tujuan (margin, sitasi, kolom spesifik) serta konversi ke `.docx` dilakukan oleh peneliti (atau minta bantuan lanjutan bila diperlukan).

## Acuan Data (bukan folder contoh/template)

Naskah ini disusun berdasarkan sumber-sumber berikut, **bukan** dari `09-docs/` atau `08-laporan/laporan-penelitian_example.md` yang masih berisi sisa konten contoh topik lain:

- [`../01-proposal/proposal-penelitian.md`](../01-proposal/proposal-penelitian.md)
- [`../02-literatur/matriks-literatur.md`](../02-literatur/matriks-literatur.md)
- [`../03-teori/Arsitektur dan skema.md`](../03-teori/Arsitektur%20dan%20skema.md), [`../03-teori/Landasan teori statistik.md`](../03-teori/Landasan%20teori%20statistik.md), [`../03-teori/Tinjauan pustaka.md`](../03-teori/Tinjauan%20pustaka.md)
- [`../04-data/data-dictionary.md`](../04-data/data-dictionary.md), [`../04-data/ringkasan-validasi.md`](../04-data/ringkasan-validasi.md)
- [`../06-output/hasilkesimpulan.md`](../06-output/hasilkesimpulan.md)
- [`../../worksheets/ws-15-scientific-writing.md`](../../worksheets/ws-15-scientific-writing.md) (outline IMRAD awal & *consistency matrix*)

## Status Sebelum Submission

- [x] Draf isi naskah §Abstrak – §Kesimpulan selesai, angka konsisten dengan `06-output/hasilkesimpulan.md`
- [ ] Lengkapi referensi [4] dan [5] pada `07-daftar-pustaka.md` (belum terverifikasi penuh)
- [ ] Lengkapi metadata penulis, afiliasi, dan NIM pada `naskah-jurnal.md`
- [ ] Tetapkan bahasa final naskah (Indonesia untuk Sinta 2 / Inggris untuk Scopus)
- [ ] Pemindahan ke template jurnal tujuan (margin, format sitasi, kolom)
- [ ] Uji statistik formal pada variabel *data loss* (disarankan pada §5.3 Kesimpulan) sebelum mengklaim keandalan salah satu database