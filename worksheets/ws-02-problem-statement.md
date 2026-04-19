# WS-02: Problem Statement

> **Bab 2 — Problem Formulation & System Context**

---

## Ringkasan Materi

### Problem Formation Model

Masalah riset melewati 5 tahap transformasi. Melompat langsung dari Reality ke Variable adalah kesalahan paling umum.

```
Reality → Observed Issue (Symptom) → Diagnosed Problem (Root Cause)
→ Researchable Problem (Scoped) → Measurable Variable (Operationalized)
```

### Topic ≠ Problem ≠ Research Problem

| Level | Contoh | Status |
|-------|--------|--------|
| **Topik** | Keamanan IoT | Terlalu luas, tidak bisa diuji |
| **Problem** | MQTT tidak terenkripsi | Spesifik tapi belum riset |
| **Research Problem** | Belum ada studi membandingkan overhead TLS 1.3 vs DTLS pada MQTT di IoT RAM < 64KB | Bisa dirancang eksperimennya |

### Symptom vs Root Cause

Apa yang diamati (gejala) ≠ mengapa terjadi (akar masalah). Gunakan **5 Whys** atau **Fishbone Diagram** untuk menggali.

Contoh: "User meninggalkan checkout" (symptom) → "Waktu loading > 8 detik karena API call sequential" (root cause).

### System Thinking

Setiap masalah riset TI harus terikat pada komponen sistem: **Input → Process → Output → Outcome → Constraints → Stakeholders**.

### Problem Quality Check

Masalah riset yang layak harus memenuhi 5 kriteria:
- **Clarity** — Satu orang membaca akan paham
- **Measurability** — Ada metrik kuantitatif
- **Relevance** — Penting untuk domain
- **Testability** — Bisa gagal (falsifiable)
- **Impact** — Ada kontribusi jika terjawab

### Research vs Engineering

| Aspek | Engineering | Research |
|-------|------------|----------|
| Tujuan | Menyelesaikan masalah (*solve*) | Memahami dan membuktikan (*understand & prove*) |
| Masalah | Bug, error, fitur belum ada | Gap dalam pengetahuan |
| Scope | Selesaikan semua yang perlu | Batasi agar bisa dibuktikan |
| Output | Working system | Evidence, paper, replicable findings |

### Istilah Penting

- **Problem Statement** — Formulasi tertulis: konteks sistem + gap + dampak + justifikasi
- **System Context** — Deskripsi lengkap: input, proses, output, outcome, constraints, stakeholders
- **Problem Drift** — Masalah "bermutasi" dari pendahuluan ke metodologi karena statement awal tidak presisi
- **Solution-First Thinking** — Memulai dari solusi tanpa masalah yang jelas — berbahaya dalam riset
- **Operational Definition** — Definisi variabel yang cukup jelas agar peneliti lain bisa mengukur hal yang sama

---

## Template A.2 — Problem Statement Builder

```
PROBLEM STATEMENT BUILDER

Domain & Konteks
  Domain   : ____________________
  Konteks  : ____________________

System Context
  Input       : ____________________
  Process     : ____________________
  Output      : ____________________
  Outcome     : ____________________
  Constraints : ____________________
  Stakeholders: ____________________

Fenomena → Problem
  Fenomena yang diamati             : ____________________
  Gejala (symptom) yang terukur     : ____________________
  Masalah yang didiagnosis          : ____________________
  Masalah riset (researchable)      : ____________________
  Variabel yang terukur             : ____________________

Problem Quality Check
  [ ] Clarity — Apakah satu orang membaca akan paham?
  [ ] Measurability — Apakah ada metrik kuantitatif?
  [ ] Relevance — Apakah penting untuk domain?
  [ ] Testability — Apakah bisa gagal?
  [ ] Impact — Apakah ada kontribusi jika terjawab?

Problem Statement (1 paragraf):
  ____________________
```

---

## Latihan 1 — Dari Topik ke Masalah Riset

Pilih satu topik di bidang TI yang diminati. Transformasikan melalui 5 tahap Problem Formation Model.

**Topik awal:** Manajemen Keamanan, pemantauan, pelaporan serta pencocokan kendaraan pada Area Parkir Kampus (Sistem Parkir Pintar).

| Tahap | Hasil |
|-------|-------|
| Reality | Terjadi insiden keamanan di UPB (sepeda motor rusak atau body motor pecah) tepatnya tanggal 16 Oktober 2025 dialami adek kelas saya bernama ayu dan kendaraan mahasiswa saat  masuk dan keluar kampus  yang belum tercatat di area parkir kampus. |
| Observed Issue (Symptom) | Petugas sulit dalam  membuktikan suatu tindakan kejahatan seperti perusakan atau pencurian kendaraan. pertugas keamanan juga kesulitan dalam  mencocokannya antara kendaraan yang keluar dengan identitas pemiliknya jadi tidak ada gerbang keamanan atau portal otomatis di dalamnya. pihak kampus juga tidak memiliki bukti yang kuat saat terjadi kerusakan atau kehilangan tersebut. |
| Diagnosed Problem (Root Cause) | Tidak adanya sistem pencatatan digital yang terhubung dengan pemantauan visual dan pencocokan identitas otomatis, sehingga seluruh proses pengawasan bergantung sepenuhnya pada ingatan manual dan pemeriksaan fisik yang terbatas. |
| Researchable Problem | Bagaimana merancang sistem manajemen parkir yang mengintegrasikan monitoring visual dan pencocokan identitas otomatis (Smart Parking) untuk menyediakan rekam jejak digital yang valid guna meminimalkan risiko keamanan dan kerugian fisik kendaraan di UPB serta media pelaporan yang mudah digunakan mahasiswa|
| Measurable Variable | 1. Persentase akurasi pencatatan data masuk-keluar (%). 2. Waktu rata-rata pengambilan bukti digital oleh petugas saat terjadi laporan insiden (menit). 3. Jumlah laporan kerusakan kendaraan yang tidak teridentifikasi pelakunya (angka). 4. Waktu rata rata yang dibutuhkan mahasiswa untuk menyelesaikan pembuatan laporan insiden melalui aplikasi (detik/menit). 5. Skor kepuasan pengguna atau kemudahan penggunaan aplikasi (menggunakan skala 1-100 dari kuesioner usability). |

**Apakah terjebak solution-first thinking?** [ ] Ya / [x] Tidak
> Sudah tepat, karena bermula dari fenomena kerusakan fisik kendaraan di lapangan

---

## Latihan 2 — System Context Decomposition

Gambarkan konteks sistem dari masalah riset di Latihan 1.

| Komponen | Deskripsi |
|----------|----------|
| Input | Data identitas mahasiswa (ID/KTM), data plat nomor kendaraan, dan tangkapan gambar/video dari kamera monitoring.serrta pelaporan dari pengguna baik keamanan,fasilitas atau tentang sistem parkir pintarnya |
| Process | Pencocokan otomatis antara identitas pemilik dengan kendaraan yang masuk, penyimpanan log riwayat aktivitas parkir, dan verifikasi status keluar dan masuk serta pengelolaan pelaporan yang dibuat oleh pengguna di fitur pelaporan pada parkir pintar |
| Output | Log aktivitas digital (waktu masuk atau keluar), status validasi gerbang (Open/Closed)portal terbuka atau tertutup , dan rekam jejak bukti jika terjadi insiden, serta rekam jejak laporan yang dikirimkan pengguna. |
| Outcome | Terciptanya lingkungan parkir UPB yang lebih aman, adanya pertanggungjawaban data yang jelas, dan peningkatan kepercayaan mahasiswa terhadap keamanan kampus. |
| Constraints | Kondisi cahaya di area parkir saat malam hari yang mempengaruhi kualitas visual kamera, serta keterbatasan anggaran untuk infrastruktur fisik gerbang otomatis. |
| Stakeholders | Mahasiswa UPB, Petugas Keamanan (Satpam), Manajemen Kampus/Bagian Sarana Prasarana. |

**Komponen mana yang paling relevan dengan masalah riset?** Process dan Output (Karena inti masalahnya adalah kegagalan proses pencatatan dan tiadanya output berupa bukti digital)

---

## Latihan 3 — Problem Quality Check

Evaluasi problem statement yang sudah dibuat menggunakan 5 kriteria.

| Kriteria | Skor (1-5) | Justifikasi |
|----------|-----------|-------------|
| Clarity | 5 | Sangat jelas karena merujuk pada insiden fisik nyata di UPB dan membutuhkan bukti untuk memperkuat hukum. |
| measurability | 4 | ketepatan pencatatan dan waktu respon sistem sangat bisa diukur secara kuantitatif. |
| Relevance | 5 | Sangat relevan bagi UPB untuk mencegah kerugian materiil mahasiswa lebih lanjut (tidak diharapkan) |
| Testability | 5 | Bisa diuji dengan simulasi insiden dan membandingkan kemudahan pencarian bukti data.|
| Impact | 5 | Memberikan rasa aman langsung bagi ribuan mahasiswa pengguna kendaraan di kampus.|

**Skor total:** 24 / 25

**Problem statement versi final (1 paragraf):**
> Manajemen parkir di Universitas Putra Bangsa saat ini masih berjalan secara manual tanpa sistem pencatatan digital yang terintegrasi, yang berdampak pada ketidakmampuan pihak keamanan dalam mengidentifikasi pelaku perusakan fisik kendaraan mahasiswa. tidak adanya pencocokan data antara identitas pemilik kendaraan dengan data log masukdan keluar secara real time menyebabkan hilangnya bukti kuat yang dibutuhkan untuk menindaklanjuti laporan kerusakan atau kejahatan tersebut. Oleh karena itu, riset ini berfokus pada pengembangan sistem "Parkir Pintar" yang menghubungkan pengawasan visual dan pencocokan otomatis secara digital guna meningkatkan akurasi data serta menyediakan rekam jejak digital yang handal sebagai solusi pengamanan area parkir kampus yang lebih aman dan evisien.

---

## Refleksi

> Bandingkan "masalah" yang biasa ditemui saat coding (bug, error) dengan masalah riset. Apa perbedaan fundamental dalam cara mendefinisikan dan mendekati keduanya?

**Jawaban:**
Inti perbedaannya ada di tujuan dan bukti. Kalau kita ketemu error pas coding, fokus kita cuma satu yaitu gimana caranya errornya hilang dan aplikasinya bisa jalan lancar. Tapi kalau masalah riset, pendekatannya beda karena kita berangkat dari masalah asli di lapangan. Dalam riset, sekadar bikin aplikasinya "nyala dan bisa dipakai" itu nggak cukup. Kita diwajibkan untuk membuktikan pakai data dan angka nyata, apakah aplikasi Parkir Pintar yang kita buat ini beneran efektif ngatasin masalah keamanan motor di UPB, atau jangan jangan cuma bagus di layar komputer aja.
