# WS-03: Literature Mapping & Gap

> **Bab 3 — Literature Review, Research Gap & Baseline**

---

## Ringkasan Materi

### Literature Review = Positioning, Bukan Ringkasan

Literature review bukan merangkum paper satu per satu. Pendekatan yang benar adalah **concept-centric** — organisasi berdasarkan tema, metode, atau variabel. Tujuan: menemukan **pola, kontradiksi, dan gap**.

### Empat Jenis Research Gap

| Jenis Gap | Deskripsi | Contoh |
|-----------|----------|--------|
| **Performance Gap** | Performa belum memadai | Akurasi deteksi hanya 78% pada kasus tertentu |
| **Method Gap** | Pendekatan belum diterapkan | Belum ada yang pakai transformer untuk task ini |
| **Data Gap** | Dataset terbatas/tidak representatif | Semua studi pakai dataset sintetis |
| **Context Gap** | Belum diuji pada konteks berbeda | Belum ada evaluasi di negara berkembang |

Gap terkuat = kombinasi 2+ jenis.

### Systematic Search Strategy

1. **Database**: IEEE Xplore, ACM DL, Scopus, Google Scholar
2. **Boolean query** yang terdokumentasi eksplisit
3. **Snowballing**: backward (telusuri referensi) + forward (cari yang mengutip)
4. Klaim "belum ada penelitian" harus didukung **bukti pencarian**

### Baseline Selection — 3 Kriteria

| Kriteria | Pertanyaan |
|----------|-----------|
| **Relevan** | Apakah menyelesaikan masalah yang sama? |
| **Representatif** | Apakah mewakili common practice? |
| **State-of-the-Art** | Apakah terbaru/terbaik? |

Membandingkan deep learning 2024 dengan decision tree sederhana tanpa justifikasi = **straw man comparison** (perbandingan tidak jujur).

### Research vs Engineering

| Aspek | Engineering | Research |
|-------|------------|----------|
| Tujuan baca literatur | Mencari solusi yang sudah ada | Memahami apa yang belum terjawab |
| Cara membaca paper | Tutorial, how-to | Metode, limitasi, gap |
| Baseline | Framework terpopuler | State-of-the-art yang rigorous |
| Dokumentasi pencarian | Tidak diperlukan | Wajib (reproducible) |

### Istilah Penting

- **Concept-centric** — Organisasi literatur berdasarkan konsep/metode, bukan per penulis
- **Snowballing** — Backward (telusuri referensi) + Forward (cari yang mengutip paper kunci)
- **Research Position** — Pernyataan eksplisit posisi riset terhadap studi sebelumnya
- **Straw man comparison** — Memilih baseline lemah agar metode sendiri terlihat lebih baik

---

## Template A.3 — Literature Mapping & Gap Identification

```
LITERATURE MAPPING

Topik      : ____________________
Database   : ____________________
Query      : ____________________
Tahun      : ____________________
Hasil awal : ____ paper → Screening → ____ paper final

Literature Matrix (concept-centric):

| Study | Tahun | Method | Data | Result | Limitation |
|-------|-------|--------|------|--------|------------|
|       |       |        |      |        |            |

Pola yang ditemukan:
  Metode dominan     : ____________________
  Dataset umum       : ____________________
  Limitasi berulang  : ____________________

GAP IDENTIFICATION

Gap 1: [Jenis: performance / method / data / context]
  Deskripsi    : ____________________
  Bukti        : ____________________
  Signifikansi : ____________________

Gap 2: [Jenis: ____]
  Deskripsi    : ____________________
  Bukti        : ____________________
  Signifikansi : ____________________

Baseline Selection:
| Baseline | Relevansi | Representatif | Source |
|----------|-----------|---------------|--------|
|          |           |               |        |
```

---

## Latihan 1 — Concept-Centric Literature Table

Gunakan topik riset dari WS-02. Cari minimal 5 paper relevan menggunakan Google Scholar atau database lain.

**Topik riset:** Analisis Perbandingan Kinerja Database (MySQL vs PostgreSQL) untuk Penyimpanan Data Sensor Detak Jantung (MAX30102) Berfrekuensi Tinggi.
**Query pencarian:** Perbandingan kinerja MySQL dan PostgreSQL IoT, database performance for heart rate sensor, penyimpanan data sensor MAX30102, real-time database streaming.
**Database:** Google Scholar

| # | Study | Tahun | Method | Dataset | Result | Limitasi |
|---|-------|-------|--------|---------|--------|----------|
| 1 | Salsabila et al. | 2022 | IoT, Raspberry Pi, Sensor AD8232, Protokol XMPP. | Data Beat Per Minute (BPM). | Sistem berhasil mengirim pesan data dengan delay jaringan rata rata 342,857 ms dan packet loss 0. | Sistem mengunakan protokol pesan instan (XMPP) tanpa menguji kapasitas database relasional untuk penyimpanan riwayat data jangka panjang. |
| 2 | Ahsa et al. |2023 | Pengujian responsivitas 4 fungsi query (Select, Insert, Update, Delete).| Data aplikasi Google Playstore hingga 250.000 baris. | PostgreSQL lebih unggul karena memberikan waktu respon yang lebih cepat dan tidak pernah melebihi 4 detik pada setiap pengujian. | Pengujian hanya menggunakan beban dataset statis, belum menguji dengan aliran data (streaming) beruntun dari sensor mikrokontroler langsung. |
| 3 | Putra et al. | 2022 | Perbandingan performa respon waktu kueri CRUD, SUM, dan COUNT pada MySQL, PostgreSQL, dan MongoDB. | Dataset permainan catur dari Kaggle sebanyak 20.058 baris data. | PostgreSQL mencatat total waktu respon tercepat (2,5 detik) dibandingkan MySQL (21,6 detik). |  Hanya menguji kinerja kueri pada satu tabel tanpa relasi, belum menerapkan percobaanpaada  data sensor berfrekuensi tinggi. |
| 4 | Lim et al. | 2023 | Pengembangan alat pantau jantung real time memakai mikrokontroler Seeed XIAO dan transmisi Bluetooth nirkabel. | Alat ini sangat akurat (tingkat eror < 0,31%) dan waktu refresh-nya 30%-40% lebih cepat dari oximeter biasa | pengawasan status slot secara real-time via web. | Riset jurnal ini cuma fokus di sisi alat dan pengiriman data saja, belum membahas masalah performa penyimpanan di sisi database server terutama data berfrekwensi tinggi.|
| 5 | Dachi & Suhada | 2025 | Eksperimen langsung membangun database sederhana untuk membandingkan operasi CRUD pada MySQL dan PostgreSQL. | Simulasi data akademik mahasiswa (NIM, Nama, Alamat). | PostgreSQL terbukti lebih tangguh untuk data yang kompleks dan skala besar, sedangkan MySQL lebih praktis buat proyekan kecil. | Pengujian menggunakan data biasa, belum mencoba dengan pengujian data terus menerus yang masuk dari sensor dengan frekwensi tinggi ke database setiap mili detik. |

**Pola yang terlihat — Metode dominan:** Pendekatan dominan dalam riset IoT detak jantung saat ini (seperti penggunaan sensor MAX30102) adalah hanya berfokus agar alat berhasil membaca detak jantung saja , lalu datanya dimasukan ke MySQL bawaan tanpa membandingkan atau menghitung beban server-nya. Di sisi lain, riset yang khusus membandingkan performa antara MySQL dan PostgreSQL di jurnal kebanyakan hanya memakai simulasi data buatan atau data dummy di dalam komputer lokal, tanpa menggunakan perangkat keras seperti mikrokontroler dan sensor.
**Limitasi yang berulang:** Kelemahan yang terus berulang dari penelitian-penelitian di atas adalah terpisahnya pengujian perangkat fisik (IoT) dengan pengujian backend (database). Riset riset tersebut seolah berjalan sendiri sendiri.belum ada penelitian yang menguji langsung performa waktu simpan (insert time) antara MySQL dan PostgreSQL saat diuji dengan  data nyata berfrekuensi sangat tinggi (ratusan data per detik). Padahal, data sinyal biologis yang dihasilkan oleh sensor MAX30102 melalui perangkat ESP32 ini masuk secara terus menerus tanpa henti. jadi tidak adanya pengujian integratif yang ekstrem ini yang menjadi ruang kosong (gap) utama dalam riset ini.
---

## Latihan 2 — Gap Identification

Berdasarkan tabel di Latihan 1, identifikasi gap.

| Jenis Gap | Ditemukan? | Gap Statement |
|-----------|-----------|---------------|
| Performance Gap | [x] Ya / [ ] Tidak | Kemampuan database pada penelitian yang sudah ada  baru diuji menggunakan data statis yang sudah tersimpan di dalam memori komputer. Belum diketahui bagaimana performa dan ketahanan server database tersebut jika dimasuki oleh spam data  secara beruntun tiap milidetik dari alat sensor fisik dari mikroprosesor. |
| Method Gap | [x] Ya / [ ] Tidak | Metode penilaian penggunaan database pada riset sebelumnya umumnya menggunakan perangkat lunak stress-testing (seperti sysbench atau pgbench) yang memasukkan data secara bersamaan di dalam server lokal atau insert data. Belum ada pengujian yang mengukur kecepatan respons database saat menerima data yang masuk tanpa henti (streaming). Artinya, database belum pernah diuji secara langsung untuk menampung data yang diinputkan satu per satu secara terus menerus dari alat fisik seperti ESP32 lewat jaringan nirkabel|
| Data Gap | [x] Ya / [ ] Tidak |Dataset yang digunakan untuk menguji performa database pada riset sebelumnya seperti Jurnal Ahsa dan Putra selalu berupa data statis (file CSV). Belum ada percobaan pengujian yang menggunakan data berkarakteristik continuous streaming atau time-series yang berasal dari sensor fisik secara real-time. Perbedaan bentuk data ini sangat penting, karena cara database memproses data yang dimasukkan secara borongan sangat jauh berbeda dengan saat harus menerima jutaan data kecil yang masuk satu per satu tiap milidetik melalui jaringan nirkabel.|
| Context Gap | [x] Ya / [ ] Tidak | penerapan pengujian database peneltian sebelumnya kebanyakan difokuskan untuk efisiensi aplikasi web atau desktop biasa. Belum diterapkan dalam konteks monitoring IoT yang real-time, di mana keterlambatan atau lambatnya respons sistem walau hanya sepersekian detik bisa sangat fatal, karena bisa membuat data penting kondisi pasien hilang dan tidak terekam.|

**Gap utama yang dipilih:** Belum ada penelitian yang menguji batas performa waktu simpan (insert latency) dan stabilitas memori antara MySQL dan PostgreSQL ketika dihadapkan langsung pada aliran data continuous streaming (data beruntun tiada henti) yang dihasilkan oleh perangkat keras sensor medis (MAX30102) berfrekuensi tinggi.

**Mengapa gap ini penting (bukan sekadar "belum ada yang meneliti")?**
> hal ini sangat penting diselesaikan karena menyangkut keandalan sistem pemantauan kesehatan di dunia nyata. Sesuai dengan fungsinya, sensor MAX30102 membaca dan mengirimkan data detak jantung dengan frekuensi yang sangat tinggi. Artinya, sensor ini tidak menyimpan data lalu mengirimkannya secara borongan sekaligus, melainkan menembakkan data-data kecil secara beruntun setiap milidetik tanpa henti. riset yang sudah ada hanya menguji database dengan data biasa yang diimpor dari komputer. jika kita asal memilih database antara MySQL atau PostgreSQL tanpa pengujian khusus dengan aliran data real-time, kita tidak akan tahu apakah server tersebut sanggup menahan data dari sensor yang terus menerus. Jika database tidak kuat menerima kecepatan proses simpan (insert) dari sensor MAX30102, sistem akan langsung macet (lag), kelebihan beban (bottleneck), atau bahkan mati mendadak (crash).grafik riwayat detak jantung pasien bisa terputus dan hilang. penelitian ini wajib dilakukan untuk membuktikan mengenai database mana yang kinerjanya paling cepat dan tahan dalam menyimpan dan menerima data berfrekuensi tinggi dari sensor MAX30102.
---

## Latihan 3 — Baseline Selection

Pilih 2 baseline dari literatur yang sudah dibaca.

| # | Baseline | Mengapa Relevan | Mengapa Representatif | Apakah SOTA? | Sumber |
|---|----------|----------------|----------------------|-------------|--------|
| 1 | Pengujian performa database relasional menggunakan data statis/lokal | Sama sama membandingkan kecepatan waktu respon kueri (khususnya insert) antara MySQL dan PostgreSQL. | Mewakili metode standar yang  sering dipakai oleh akademisi saat ini untuk mengevaluasi kinerja database.| Bukan, ini merupakan standar pengujian konvensional (common practice) | Ahsa et al., 2023 |
| 2 | Sistem monitoring denyut jantung real-time berbasis IoT | Sama sama meneliti sistem pengiriman data secara langsung dari sensor pemantau detak jantung ke perangkat antarmuka. | Mewakili tren riset perangkat IoT medis masa kini yang fokus pada kecepatan pengiriman data (seperti latensi/delay) melalui protokol jaringan. | Ya, penggunaan mikrokontroler (seperti Raspberry Pi) dengan protokol pesan instan ringan (seperti XMPP) termasuk canggih saat ini. | Salsabila et al., 2022 |

**Apakah pemilihan baseline ini bisa dianggap straw man?** [ ] Ya / [x] Tidak
> Tidak, karena dua metode yang dijadikan perbandingan di atas bukanlah metode abal abal yang sengaja dicari kelemahannya. Jurnal Ahsa (2023) mewakili cara pengujian performa database yang sangat valid dan diakui di dunia akademik. Sementara itu, Jurnal Salsabila (2022) menggunakan teknologi Internet of Things (IoT) yang sangat efisien untuk memantau detak jantung di standar riset lokal saat ini.Perbandingan ini sangat adil dan logis. Tujuannya adalah untuk membuktikan bahwa secanggih dan secepat apa pun alat sensor IoT mengirimkan pesan detak jantung, jika tidak dipadukan dengan arsitektur database server  yang tepat dalam menerima spam data tersebut, maka sistem pemantauan medis akan tetap berisiko macet (lag), kelebihan beban (bottleneck), atau kehilangan riwayat data penting pasien.

---

## Refleksi

> Apa perbedaan antara "belum ada yang meneliti ini" (klaim tanpa bukti) dengan research gap yang valid? Bagaimana cara membuktikan bahwa sebuah gap benar-benar ada?

**Jawaban:**
> Klaim "belum ada yang meneliti" itu biasanya cuma tebakan asal karena kurang baca jurnal, atau maksa menggabungkan dua topik yang tidak nyambung biar judulnya kelihatan baru.

>Sebaliknya, research gap yang valid adalah masalah nyata yang baru kita temukan setelah kita membaca dan membandingkan banyak jurnal. Gap ini muncul karena solusi dari riset riset sebelumnya ternyata belum tuntas dalam menyelesaikan masalah di dunia nyata. Contohnya: riset yang ada belum bisa menjamin apakah database bakal aman atau malah crash kalau dispam data detak jantung secara real-time tanpa henti.

>Cara membuktikan gap itu beneran  adalah dengan membuat tabel perbandingan jurnal. Dari tabel tersebut, kita bisa memberikan bukti nyata. contohnya diLihat dari 5 jurnal yang saya cari terbukti riset database cuma diuji menggunakan data mati (statis), sementara riset alat IoT cuma fokus ke sensornya saja tanpa mengetes database penyimpannya.

>Kelemahan atau batasan yang terus berulang di jurnal-jurnal itulah yang menjadi bukti kuat kalau gap penelitian kita itu nyata dan memang butuh diteliti.
