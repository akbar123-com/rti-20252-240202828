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

**Topik riset:** Pengembangan sistem Parkir Pintar berbasis pengawasan CCTV terintegrasi dengan media pelaporan insiden dan pencocokan kendaraan untuk keamanan kampus.
**Query pencarian:** Smart parking security system, parkir pintar, Sistem pelaporan kehilangan kendaraan parkir
**Database:** Google Scholar

| # | Study | Tahun | Method | Dataset | Result | Limitasi |
|---|-------|-------|--------|---------|--------|----------|
| 1 | Salsabila et al. | 2022 | IoT, Raspberry Pi, Sensor AD8232, Protokol XMPP. | Data Beat Per Minute (BPM). | Sistem berhasil mengirim pesan data dengan delay jaringan rata-rata 342,857 ms dan packet loss 0. | Sistem mengandalkan protokol pesan instan (XMPP) tanpa menguji kapasitas database relasional untuk penyimpanan riwayat data jangka panjang. |
| 2 | Ahsa et al. |2023 | Pengujian responsivitas 4 fungsi query (Select, Insert, Update, Delete).| Data aplikasi Google Playstore hingga 250.000 baris. | PostgreSQL lebih unggul karena memberikan waktu respon yang lebih cepat dan tidak pernah melebihi 4 detik pada setiap pengujian. | Pengujian hanya menggunakan beban dataset statis, belum menguji skenario aliran transmisi data (streaming) beruntun dari mikrokontroler. |
| 3 | Putra et al. | 2022 | Perbandingan performa respon waktu kueri CRUD, SUM, dan COUNT pada MySQL, PostgreSQL, dan MongoDB. | Dataset permainan catur dari Kaggle sebanyak 20.058 baris data. | PostgreSQL mencatatkan total waktu respon tercepat (2,5 detik) dibandingkan MySQL (21,6 detik). |  Hanya menguji kinerja kueri pada satu tabel tanpa relasi, belum mensimulasikan aliran data sensor berfrekuensi tinggi. |
| 4 | Lim et al. | 2023 | Pengembangan alat pantau jantung real-time pakai mikrokontroler Seeed XIAO dan transmisi Bluetooth nirkabel. | Alat ini sangat akurat (tingkat eror < 0,31%) dan waktu refresh-nya 30%-40% lebih cepat dari oximeter biasa | Monitoring status slot secara real-time via web. | Riset cuma fokus di sisi alat dan pengiriman datanya saja, belum menyentuh masalah performa penyimpanan di sisi database server.|
| 5 | Dachi & Suhada | 2025 | Eksperimen langsung membangun database sederhana untuk membandingkan operasi CRUD pada MySQL dan PostgreSQL. | Simulasi data akademik mahasiswa (NIM, Nama, Alamat). | PostgreSQL terbukti lebih tangguh buat data yang kompleks dan skala besar, sedangkan MySQL lebih praktis buat proyekan kecil. | Pengujian menggunakan data standar, belum mencoba dengan data dari sensor dengan frekwensi tinggi yang masuk tiap mili detik ke database. |

**Pola yang terlihat — Metode dominan:** Pendekatan dominan dalam riset IoT kesehatan saat ini (seperti penggunaan sensor MAX30102) adalah hanya berfokus agar alat berhasil membaca detak jantung, lalu datanya langsung dilempar ke MySQL bawaan tanpa mengevaluasi beban server-nya. Di sisi lain, riset yang khusus membandingkan performa antara MySQL dan PostgreSQL kebanyakan hanya memakai simulasi data buatan (dummy) di dalam komputer lokal, tanpa melibatkan perangkat keras mikrokontroler.
**Limitasi yang berulang:** Limitasi yang terus kejadian pada berbagai penelitian adalah terpisahnya pengujian perangkat fisik (IoT) dengan pengujian backend (Database). Belum ada penelitian yang menguji secara langsung kinerja waktu simpan (insert time) antara MySQL dan PostgreSQL saat harus menghadapi "serangan" data nyata berfrekuensi sangat tinggi (ratusan data per detik) yang dihasilkan oleh pembacaan biologis sensor MAX30102 dari perangkat ESP32. Ketiadaan pengujian ekstrem inilah yang menjadi ruang kosong (gap) utama dalam riset ini.

---

## Latihan 2 — Gap Identification

Berdasarkan tabel di Latihan 1, identifikasi gap.

| Jenis Gap | Ditemukan? | Gap Statement |
|-----------|-----------|---------------|
| Performance Gap | [x] Ya / [ ] Tidak | Kinerja sistem kamera (CCTV) pada penelitian yang sudah ada (seperti Jurnal 5) masih sebatas merekam foto di pintu masuk/keluar saja. Akibatnya, waktu respons petugas keamanan dalam memilah dan mencari bukti rekaman secara manual saat terjadi laporan insiden (perusakan/kehilangan) di dalam area parkir menjadi sangat lambat dan tidak efisien. |
| Method Gap | [x] Ya / [ ] Tidak | Metode smart parking yang sudah ada hanya berfokus pada manajemen keluar masuk kendaraan dan pencarian slot kosong. Belum ada metode yang mengintegrasikan pengawasan CCTV secara ketat dengan sistem pelaporan insiden mandiri bagi pengguna. |
| Data Gap | [x] Ya / [ ] Tidak | Data yang disimpan oleh sistem parkir saat ini umumnya hanya berupa log transaksi (waktu dan foto gerbang). Belum ada sistem yang mampu menyatukan "data log/rekaman CCTV" dengan "data formulir laporan insiden dari pengguna" menjadi satu kesatuan alat bukti digital yang kuat dan terstruktur.|
| Context Gap | [x] Ya / [ ] Tidak | Implementasi kamera pada riset sebelumnya kebanyakan difokuskan untuk efisiensi transaksi pos jaga di perusahaan atau hanya simulasi miniatur. Belum diterapkan dalam konteks keamanan investigatif untuk menyelesaikan kasus nyata seperti perusakan atau pencurian kendaraan di area kampus. |

**Gap utama yang dipilih:** Belum ada sistem yang mengintegrasikan pengawasan visual (CCTV) dan pencatatan posisi kendaraan secara ketat dengan media pelaporan insiden berbasis web  untuk menghasilkan  bukti digital yang cepat dan valid.
**Mengapa gap ini penting (bukan sekadar "belum ada yang meneliti")?**
> Kesenjangan (gap)  sangat penting untuk diselesaikan karena menyangkut keamanan dan kerugian material di lapangan. Sistem parkir pintar yang sudah ada terlalu fokus pada kemudahan akses atau pencarian slot kosong, namun mengabaikan aspek pengusutan setelah kendaraan terparkir. Saat terjadi kasus perusakan fisik kendaraan, palang otomatis saja tidak cukup. Pihak keamanan kampus membutuhkan sebuah sistem yang responsif; di mana saat mahasiswa mengirimkan laporan insiden melalui aplikasi, sistem dapat langsung mengaitkannya dengan rekaman pengawasan dan data identitas yang cocok, sehingga penyelidikan kasus kejahatan atau kerusakan dapat dilakukan dengan cepat, akurat, dan memiliki bukti digital yang kuat.

---

## Latihan 3 — Baseline Selection

Pilih 2 baseline dari literatur yang sudah dibaca.

| # | Baseline | Mengapa Relevan | Mengapa Representatif | Apakah SOTA? | Sumber |
|---|----------|----------------|----------------------|-------------|--------|
| 1 | Sistem CCTV/IP Camera biasa untuk buka gerbang | Sama-sama menggunakan kamera untuk keamanan area parkir. | Mewakili sistem standar yang memang paling banyak dipakai di lapangan/industri saat ini. | Bukan, tapi ini standar umum (common practice). | Arizona et al., 2026 |
| 2 | Kamera pendeteksi kendaraan dengan AI (YOLO v11) | Sama-sama mencoba mengenali kendaraan secara otomatis pakai kamera. | Mewakili tren riset parkir masa kini yang lagi ramai pakai AI/Computer Vision. | Ya, YOLO v11 termasuk teknologi yang paling baru dan canggih saat ini. | Damaji et al., 2025 |

**Apakah pemilihan baseline ini bisa dianggap straw man?** [ ] Ya / [x] Tidak
> Justifikasi: Tidak, karena dua sistem yang dijadikan perbandingan di atas bukanlah sistem jadul atau abal-abal yang sengaja dicari kelemahannya. Jurnal Arizona (2026) mewakili sistem yang memang dipakai di dunia nyata, dan Jurnal Damaji (2025) menggunakan teknologi AI yang sangat canggih saat ini. Perbandingan ini sangat adil. Tujuannya adalah untuk membuktikan bahwa secanggih apa pun sistem kamera mereka, selama tidak ada fitur terintegrasi untuk melaporkan kerusakan atau kehilangan motor, penanganan masalah keamanan di kampus akan tetap lambat.

---

## Refleksi

> Apa perbedaan antara "belum ada yang meneliti ini" (klaim tanpa bukti) dengan research gap yang valid? Bagaimana cara membuktikan bahwa sebuah gap benar-benar ada?

**Jawaban:**
> Klaim "belum ada yang meneliti" biasanya cuma sekadar asumsi karena mahasiswanya kurang banyak dalam baca jurnal, atau sengaja mengada ada menggabungkan topik yang sebenarnya tidak nyambung dan tidak terlalu penting.
> Sedangkan research gap yang valid adalah masalah nyata yang benar benar kita temukan setelah kita membaca dan membedah berbagai jurnal. Gap ini muncul karena sistem yang ada saat ini ternyata belum bisa menyelesaikan masalah di lapangan secara tuntas. misalnya, sistem parkir yang ada belum bisa membantu pengusutan jika ada motor mahasiswa yang dirusak.
> Cara membuktikan gap itu benar benar ada adalah dengan membuat tabel perbandingan literatur seperti yang ada di Latihan 1 sebelumnya. Dari tabel tersebut, kita bisa memberikan bukti nyata misalkan Lihat, dari 5 jurnal terbaru tentang parkir pintar yang saya cari di googe scolar , terbukti semuanya hanya fokus mengurus gerbang otomatis dan sama sekali belum ada yang membuat sistem pelaporan insiden.Pola seperti itu yang menjadi bukti kuat  gapnya nyata dan perlu diteliti.
