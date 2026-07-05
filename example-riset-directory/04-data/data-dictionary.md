# Data Dictionary — `data_RTI_mysql_postgresql`

Deskripsi kolom pada dataset mentah hasil pengujian MySQL vs PostgreSQL (35 pasangan run/subjek).

| Kolom | Tipe | Satuan | Range Valid | Deskripsi |
|---|---|---|---|---|
| `NO` | integer | – | 1–35 | Nomor urut subjek/run |
| `NAMA` | string | – | – | Identitas/label subjek pengujian (anonim, nama panggilan) |
| `MYSQL_LATENSI` | float | ms | 0.0 – 1000.0 | Insert Latency saat data disimpan ke MySQL |
| `MYSQL_RAM` | float | % | 0.0 – 100.0 | Beban RAM server saat pengujian pada MySQL |
| `MYSQL_DISK` | float | % | 0.0 – 100.0 | Beban disk server saat pengujian pada MySQL *(dicatat, belum dianalisis)* |
| `MYSQL_LOSS` | integer | baris | ≥ 0 | Jumlah baris data hilang/gagal tersimpan pada MySQL *(dicatat, belum dianalisis)* |
| `PG_LATENSI` | float | ms | 0.0 – 1000.0 | Insert Latency saat data disimpan ke PostgreSQL |
| `PG_RAM` | float | % | 0.0 – 100.0 | Beban RAM server saat pengujian pada PostgreSQL |
| `PG_DISK` | float | % | 0.0 – 100.0 | Beban disk server saat pengujian pada PostgreSQL *(dicatat, belum dianalisis)* |
| `PG_LOSS` | integer | baris | ≥ 0 | Jumlah baris data hilang/gagal tersimpan pada PostgreSQL *(dicatat, belum dianalisis)* |

## Struktur Berpasangan (Paired)

Setiap baris (`NO`) merepresentasikan **satu subjek** yang datanya diuji pada **kedua** database secara berpasangan pada kondisi yang identik (stream sensor yang sama). Untuk analisis statistik (Paired Samples T-Test), selisih dihitung per baris:

```
diff_latensi = MYSQL_LATENSI - PG_LATENSI
diff_ram     = MYSQL_RAM - PG_RAM
```

## Ringkasan Statistik Mentah (N = 35)

Diambil dari output SPSS Descriptive Statistics (lihat `../06-output/` untuk detail lengkap):

| Variabel | N | Minimum | Maximum | Mean | Std. Deviation |
|---|---|---|---|---|---|
| MYSQL_LATENSI | 35 | 1.5123 | 2.7412 | 2.123977 | 0.3597544 |
| MYSQL_RAM | 35 | 81.38 | 91.10 | 86.6500 | 2.61181 |
| PG_LATENSI | 35 | 3.5491 | 5.4120 | 4.458014 | 0.5548553 |
| PG_RAM | 35 | 80.09 | 88.95 | 86.2534 | 1.86481 |
