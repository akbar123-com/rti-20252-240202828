# WS-04: Research Question & Hypothesis

> **Bab 4 — Research Question, Contribution & Hypothesis**

---

## Ringkasan Materi

### RQ Bukan Pertanyaan Biasa

Research Question yang baik secara implisit mengandung cetak biru eksperimen: subjek, baseline, metrik, domain, dataset.

| Kualitas | Contoh |
|----------|--------|
| **Buruk** | "Bagaimana pengaruh deep learning terhadap deteksi malware?" |
| **Baik** | "Apakah CNN menghasilkan F1-Score lebih tinggi dari RF pada CIC-MalMem-2022?" |

Perbedaan: RQ yang baik menyebutkan **metode spesifik**, **metrik terukur**, **baseline**, dan **dataset**.

### Tiga Jenis RQ

| Jenis | Pola | Kebutuhan |
|-------|------|-----------|
| **Comparison** | A vs B → mana lebih baik? | ≥ 2 metode, metrik sama |
| **Improvement** | A' vs A → modifikasi lebih baik? | Pre/post, bukti perbaikan |
| **Exploratory** | Faktor X₁...Xₙ → pengaruh terhadap Y? | Multi-variabel, korelasi/regresi |

### Contribution Statement

Tiga jenis kontribusi: **Improvement** (metode terbukti lebih baik), **Comparison** (perbandingan sistematis yang belum ada), **Novel Approach** (pendekatan baru). Kontribusi harus terhubung langsung dengan gap — kontribusi tanpa gap = klaim tanpa justifikasi.

### Hypothesis H₀ / H₁

- **H₀** (Null) = Tidak ada perbedaan signifikan — asumsi default, harus dibuktikan salah
- **H₁** (Alternative) = Ada perbedaan signifikan — diterima hanya jika H₀ ditolak
- Harus **falsifiable**, mengandung **metrik terukur**, dirumuskan **SEBELUM eksperimen**

### Rantai Operasionalisasi

```
RQ → Variable → Metric → Data → Analysis
```

Jika rantai ini tidak lengkap, RQ belum mature. Bi-directional: RQ yang tidak bisa jadi hipotesis testable harus direvisi mundur.

### Research vs Engineering

| Aspek | Engineering | Research |
|-------|------------|----------|
| Tujuan pertanyaan | Apa yang harus dibangun? | Apa yang harus dibuktikan? |
| Bentuk jawaban | Sistem yang berfungsi | Bukti empiris terukur |
| Sukses diukur oleh | User satisfaction, uptime | Signifikansi statistik, effect size |
| Jika gagal | Debug dan perbaiki | Laporkan, analisis mengapa |

### Istilah Penting

- **Research Question (RQ)** — Pertanyaan spesifik: variabel terukur + metrik + konteks
- **Contribution Statement** — Apa yang diketahui setelah riset selesai yang sebelumnya belum ada
- **H₀ / H₁** — Null vs Alternative Hypothesis
- **Falsifiability** — Kondisi hipotesis ditolak harus bisa didefinisikan sebelum eksperimen
- **Operationalization** — Proses mewujudkan konsep abstrak menjadi variabel terukur

---

## Template A.4 — RQ-Contribution-Hypothesis

```
RQ-CONTRIBUTION-HYPOTHESIS

Gap Statement  : ____________________

Research Question:
  Tipe         : [ ] Comparison  [ ] Improvement  [ ] Exploratory
  Formulasi    : ____________________
  Variabel IV  : ____________________
  Variabel DV  : ____________________
  Metrik       : ____________________
  Dataset      : ____________________
  Baseline     : ____________________

Quality Check RQ:
  [ ] Variabel spesifik
  [ ] Metrik jelas
  [ ] Baseline ada
  [ ] Konteks disebutkan
  [ ] Memerlukan eksperimen (bukan hanya survei literatur)

Contribution Statement:
  Apa yang baru diketahui : ____________________
  Jenis kontribusi        : [ ] Improvement  [ ] Comparison  [ ] Novel approach
  Gap yang diisi          : ____________________

Hypothesis Pair:
  H₀ : ____________________
  H₁ : ____________________
  Threshold              : ____________________
  Justifikasi threshold  : ____________________
```

---

## Latihan 1 — Dari Gap ke RQ

Gunakan gap yang ditemukan di WS-03. Transformasikan menjadi Research Question.

**Gap dari WS-03:** 
Belum ada sistem yang mengintegrasikan pengawasan visual (CCTV) dan pencatatan posisi kendaraan secara ketat dengan media pelaporan insiden berbasis web  untuk menghasilkan  bukti digital yang cepat dan valid.

**RQ versi pertama (tulis bebas):**
> Bagaimana pengaruh penggunaan sistem parkir yang terintegrasi dengan  pelaporan berbasis web terhadap kecepatan petugas keamanan ketika mencari bukti kendaraan di sekitarnya saat terjadi perusakan fisik atau kejahatan di area parkir dengan bukti digital, dibandingkan dengan metode pencarian CCTV manual ?

**Evaluasi RQ:**

| Komponen | Ada? | Isi |
|----------|------|-----|
| Metode spesifik | Ya — Pencatatan riwayat lokasi parkir | Mencatat riwayat letak parkir motor yang langsung terhubung dengan fitur lapor di aplikasi. |
| Metrik terukur | Ya — Waktu pencarian pelaku | Menghitung berapa lama waktu (menit/detik) yang dibutuhkan untuk menemukan data bukti pendukung.|
| Baseline | Ya — Pencarian manual | Petugas keamanan mencari bukti dengan mengecek ulang rekaman CCTV biasa satu per satu. |
| Dataset/konteks | Ya — Kasus motor rusak di kampus | Uji coba sistem menggunakan skenario ada motor yang disenggol atau dirusak di area parkir Universitas Putra Bangsa. |

**Tipe RQ:** [ ] Comparison / [x] Improvement / [ ] Exploratory

**RQ versi revisi (setelah evaluasi):**
> Bagaimana penerapan sistem pencatatan riwayat lokasi parkir yang terhubung ke fitur pelaporan dapat mempercepat petugas keamanan dalam menemukan data motor terduga pelaku (yang parkir di sebelah korban), jika dibandingkan dengan pencarian CCTV secara manual

---

## Latihan 2 — Hypothesis Pair

Rumuskan pasangan hipotesis dari RQ di Latihan 1.

| Komponen | Isi |
|----------|-----|
| H₀ | Penerapan sistem pencatatan riwayat lokasi parkir tidak memberikan perbedaan waktu yang signifikan (atau lebih lambat) dalam melacak data kendaraan di sekitar lokasi perusakan, jika dibandingkan dengan pencarian rekaman CCTV manual |
| H₁ | Penerapan sistem pencatatan riwayat lokasi parkir secara signifikan lebih cepat dalam melacak data kendaraan di sekitar lokasi perusakan, jika dibandingkan dengan pencarian rekaman CCTV manual.|
| Metrik | Lama waktu pencarian data kendaraan (diukur dalam menit/detik).|
| Threshold | Rata-rata waktu pelacakan menggunakan sistem baru minimal 50% lebih cepat daripada rata-rata waktu metode manual (dan terbukti signifikan secara statistik, misal p-value < 0.05).|
| Justifikasi threshold | Penurunan waktu 50% adlah batas logis yang membuktikan pemrosesan query database  parkir pintar memberikan solusi lebih mudah dan nyata bagi pihak keamanan kampus, bukan sekadar selisih waktu karena kebetulan.|

**Apakah hipotesis ini falsifiable?** [x] Ya / [ ] Tidak
> Bagaimana cara membuktikannya salah? 
di akhir tugas saaat melakukan simulasi pengujian pencarian data pada berbagai skenario kasus Simulasi Terkontrol (Roleplay/Skenario Buatan). Jika dari hasilnya ternyata rata rata waktu yang dibutuhkan sistem untuk mencari data posisi motor sama lamanya, atau malah lebih lama dari petugas keamanan yang mencari manual lewat playback CCTV, maka H₁ otomatis ditolak (hipotesis terbukti salah).

---

## Latihan 3 — Rantai Operasionalisasi

Lengkapi rantai dari RQ hingga metode analisis.

| Tahap | Isi |
|-------|-----|
| RQ | Bagaimana penerapan sistem pencatatan riwayat lokasi parkir yang terhubung ke fitur pelaporan dapat mempercepat petugas keamanan dalam melacak daftar kendaraan di sekitar lokasi kejadian, jika dibandingkan dengan pencarian rekaman CCTV secara manual saat terjadi insiden perusakan fisik. |
| Variable (IV) | Metode pencarian data (Sistem Riwayat Lokasi lawan Pencarian CCTV Manual) |
| Variable (DV) | Lama waktu pencarian data kendaraan di sekitar lokasi kejadian |
| Metric | Satuan waktu (dalam menit dan detik) yang dihitung mulai dari laporan diterima hingga data kendaraan ditemukan|
| Data source | Catatan waktu hasil simulasi skenario perusakan motor di area parkir Universitas Putra Bangsa|
| Analysis method | Uji perbandingan rata rata waktu untuk melihat selisih kecepatan secara statistik|

**Apakah rantai lengkap?** [x] Ya / [ ] Tidak
> Jika tidak, tahap mana yang perlu direvisi? ______________

---

## Refleksi

> Ambil satu judul skripsi/paper yang pernah dibaca. Coba ekstrak RQ-nya. Apakah RQ tersebut memenuhi semua komponen (metode, metrik, baseline, konteks)? Jika tidak, apa yang hilang?

**Judul:** _____________________________________________
**RQ yang diekstrak:** __________________________________
**Komponen yang hilang:** _______________________________
