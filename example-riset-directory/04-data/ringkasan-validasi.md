# Ringkasan Validasi Data

Ringkasan hasil validasi data mentah sebelum digunakan untuk analisis statistik. Detail lengkap proses & template validasi ada di WS-11.

## Completeness

| Skenario | Baris Direncanakan | Baris Tercatat | Missing | Alasan |
|---|---|---|---|---|
| Pengujian MySQL (35 subjek) | 35.000 | 34.965 | 35 | Bottleneck/antrean macet pada web server (Apache) saat menerima hantaman data frekuensi tinggi |
| Pengujian PostgreSQL (35 subjek) | 35.000 | 34.959 | 41 | Overload resource memori (RAM) server, menyebabkan timeout/packet loss |

**Total expected:** 70.000 baris data sensor · **Total actual:** 69.924 · **Missing:** 76 (0.11%)

**Keputusan:** Data missing diterima apa adanya sebagai temuan riset (bukan bug yang perlu diulang), karena mengindikasikan perbedaan reliabilitas kedua database saat menangani beban tinggi — bukan menghilangkan salah satu subjek/pasangan dari 35 pasangan run yang dianalisis.

## Anomali Terdeteksi

| Run | Nilai Loss | Status | Investigasi | Keputusan |
|---|---|---|---|---|
| Subjek ke-4 (MySQL) | 15 baris hilang | Outlier (metode IQR, batas atas 2.5) | Bottleneck antrean koneksi mendadak pada web server saat hantaman data ESP32 tanpa jeda | Dipertahankan sebagai bukti batas toleransi sistem, bukan dihapus |

## Kesimpulan Validasi

- **Completeness:** 99.89% data sensor mentah terkumpul → 35 pasangan run tetap lengkap untuk dianalisis
- **Format:** Konsisten (numerik murni setelah preprocessing WS-13, tanpa satuan "ms"/"%" tersisa di sel)
- **Range check:** Nilai metrik latensi & RAM berada dalam rentang wajar; anomali loss pada 1 run terdokumentasi
- **Logic check:** Parameter pengujian sesuai desain (beban ±1000 baris/subjek, kondisi identik antar pasangan)
- **Status akhir:** ✅ Data siap dianalisis (Paired Samples T-Test / Wilcoxon Signed Rank Test)
