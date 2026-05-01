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

**Topik awal:** Analisis Perbandingan Kinerja Database MySQL vs PostgreSQL untuk Penyimpanan Data Sensor Jantung (MAX30102) Berfrekuensi Tinggi dari mikrokontroler ESP32.

| Tahap | Hasil |
|-------|-------|
| Reality | saat membuat projek IoT, salah satunya di bidang kesehatan seperti monitor detak jantung memerlukan pengiriman data yang sangat cepat. Sensor seperti MAX30102 mampu membaca puluhan hingga ratusan data per detik. Saat perancangan sistem, pengembang atau mahasiswa biasanya langsung menggunakan MySQL sebagai database bawaan tanpa ngecek dulu apakah database itu kuat nahan beban datanya. |
| Observed Issue (Symptom) | ketika ESP32 ngirim ratusan data detak jantung secara beruntun tiap detik secara real-time, server lokal sering mengalami bottleneck atau ngeleg. Proses simpannya jadi melambat dan sering terjadi data loss (data sensor hilang/gagal tersimpan), Akibatnya, grafik detak jantungnya jadi putus-putus atau datanya nggak valid. |
| Diagnosed Problem (Root Cause) | diagnosa masalahnya adalah tidak ada pengujian beban terlebih dahulu Tidak adanya percobaan dan analisi antara kecepatan baca sensor dengan kemampuan baca database dalam menangani loss atau leg pada insert berfrekuensi tinggi. Penggunaan MySQL terkesan dipaksa digunakan tanpa membandingkannya dengan PostgreSQL.Orang asal pakai MySQL tanpa mikirin apakah kecepatan sensornya seimbang sama kecepatan databasenya. Padahal, ada pilihan database lain seperti PostgreSQL yang biasanya lebih kuat buat nanganin data yang masuk secara massal dan cepat. |
| Researchable Problem | Bagaimana perbandingan kecepatan waktu simpan (insert time) dan kestabilan penyimpanan data antara arsitektur MySQL dan PostgreSQL saat menerima kiriman data berfrekuensi tinggi seperti detak jantung secara terus terusan dari ESP32|
| Measurable Variable | 1. Kecepatan rata-rata waktu simpan data / Insert Time (dalam milidetik). 2. Persentase data yang sukses masuk ke database tanpa hilang / Success Rate (%). 3. Kenaikan beban pemakaian CPU dan RAM di laptop/server pas pengujian (%). |

**Apakah terjebak solution-first thinking?** [ ] Ya / [x] Tidak
> Sudah tepat, karena riset ini berawal dari masalah pas praktek server lokal sering ngelag dan datanya banyak yang hilang saat dijejali pengiriman data sensor yang terlalu cepet. pada riset ini tidak memaksa untuk harus memakai PostgreSQL tapi Riset ini justru membandingkan kinerjanya dengan MySQL, agar mendapatkan jawaban yang tepat database mana yang  paling sesuai untuk memproses data frekwensi tinggi tanpa ada error atau loss data.

---

## Latihan 2 — System Context Decomposition

Gambarkan konteks sistem dari masalah riset di Latihan 1.

| Komponen | Deskripsi |
|----------|----------|
| Input | Data asli detak jantung berupa nilai Photoplethysmogram (PPG)  dari jari tangan yang dibaca oleh sensor MAX30102 kemudian dikirim sangat cepat oleh ESP32 dengan frekuensi tinggi (misal 100 Samples Per Second) melalui jaringan Wi-Fi lokal. |
| Process | Server menerima data itu, terus menjalankan perintah simpan ke database (INSERT) secara terus menerus, dan pemrosesan antrean beban traffic data masaltadi dikerjakan oleh engine MySQL dan PostgreSQL secara bergantian dalam kondisi jaringan yang sama. |
| Output | Catatan seberapa cepat dataitu tersimpan  (insert time dalam milidetik), total baris data yang berhasil masuk ke tabel tanpa hilang, serta rekaman metrik penggunaan resource server (CPU/RAM) selama proses prngujian data tadi. |
| Outcome | adanya panduan berupa angka nyata buat membantu mahasiswa IT dalam milih database, mana yang paling anti ngelag dan tidak gampang menghilangkan data atau data loss kalau akan bikin sistem IoT dengan tipe berkecepatan tinggi.|
| Constraints | Spek hardware komputer yang dipakai buat server terbatas (RAM/Prosesor beda beda), sinyal WiFi yang kadang naik turun, sama batas maksimal kecepatan baca dari sensor MAX30102 itu sendiri. |
| Stakeholders | Mahasiswa IT yang lagi bikin project, Developer IoT, anak Backend, dan Admin Sistem (SysAdmin).|

**Komponen mana yang paling relevan dengan masalah riset?** Process dan Output. (Karena inti riset ini adalah membedah bagaimana Proses engine kedua database tersebut dalam menangani beban aliran data dengan frekwensi tinggi, dan menilainya lewat Output  berupa angka kecepatan pasti dan persentase keberhasilan dalam penyimpanan data tanpa loss atau hilang).

---

## Latihan 3 — Problem Quality Check

Evaluasi problem statement yang sudah dibuat menggunakan 5 kriteria.

| Kriteria | Skor (1-5) | Justifikasi |
|----------|-----------|-------------|
| Clarity | 5 | Sangat jelas, karena kita langsung ngebandingin dua database untuk menangani satu masalah nyata  yaitu (beban data detak jantung yang cepat dan dengan frekwensi yang tinggi).|
| measurability | 5 | Gampang banget diukur pakai angka pasti, yaitu pakai kecepatan waktu simpan (milidetik) dan persentase data yang sukses tersimpan. |
| Relevance | 5 | Pas banget sama tren alat IoT kesehatan (wearable) sekarang, yang mewajibkan keandalan backend agar tidak ada data medis penting yang hilang. |
| Testability | 5 | Gampang diuji secara langsung pakai perangkat keras nyata (ESP32 & sensor) di laptop sendiri, tanpa butuh skenario rekayasa atau kejadian acak.|
| Impact | 5 | Memberikan jawaban pasti untuk mahasiswa IT atau developer saat milih database, agar database server yang dipakai mereka tidak lambat dan tidak adanya data hilang saat pengiriman data. |

**Skor total:** 25 / 25

**Problem statement versi final (1 paragraf):**
> saat membuat perangkat IoT berfrekuensi tinggi, seperti alat pemantau detak jantung yang menggunakan sensor fisik MAX30102 dan mikrokontroler ESP32, pengiriman data yang terus-terusan sering kali menyebabkan bottleneck (keterlambatan pemrosesan) pada local server. Kebanyakan pengembang asal pakai MySQL sebagai database bawaan tanpa ngecek batas kemampuannya, padahal ini bahaya karena data detak jantung yang penting bisa hilang atau gagal tersimpan. Karena jarang ada perbandingan langsung dengan alternatif database yang lebih kuat buat data besar seperti PostgreSQL, penentuan database sering kali jadi kurang tepat. Oleh karena itu, riset ini berfokus buat menganalisis perbandingan kecepatan waktu simpan (insert time) dan persentase keberhasilan penyimpanan antara MySQL dan PostgreSQL. Tujuannya buat nyari tahu database mana yang paling stabil dan aman dari risiko hilang data kalau dipakai untuk sistem sensor berkecepatan tinggi.

---

## Refleksi

> Bandingkan "masalah" yang biasa ditemui saat coding (bug, error) dengan masalah riset. Apa perbedaan fundamental dalam cara mendefinisikan dan mendekati keduanya?

**Jawaban:**
Perbedaan utamanya ada di tujuan dan cara kita ngebuktiinnya. Kalau kita ngadepin error pas lagi ngoding, fokus kita murni teknis aja: gimana caranya error itu hilang, kodenya bener, dan alatnya bisa jalan.

Namun, jika masalah riset, pendekatannya jauh lebih dalam dan analitis. Dalam riset, sekadar membuat sensor MAX30102 berhasil menyimpan data ke database itu tidak cukup. dalam riset diwajibkan untuk menguji batas maksimal sistem dan membuktikannya menggunakan data berupa angka nyata. Kita harus bisa menjawab dan membuktikan ketika sistem dihujani ratusan data biologis per detik,  database mana (MySQL atau PostgreSQL) yang secara nyata memiliki waktu simpan tercepat dan paling aman dari risiko kehilangan data (data loss),bukan sekadar aplikasinya bebas dari bug dan berjalan saja.
