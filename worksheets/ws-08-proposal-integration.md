# WS-08: Proposal Integration (UTS)

> **Bab 8 — Proposal & Checkpoint**

---

## Ringkasan Materi

### Proposal = Satu Argumen Utuh

Proposal riset bukan kumpulan bab yang independen. Ia adalah **satu argumen** yang mengalir dari masalah ke rencana solusi. Jika satu koneksi putus, seluruh proposal kehilangan koherensi.

### Integration Map — 6 Koneksi Kritis

```
Problem (Bab 2) → Gap (Bab 3) → RQ & H (Bab 4) → Metrik (Bab 5) → Sistem (Bab 6) → Eksperimen (Bab 7)
```

| Koneksi | Pertanyaan Verifikasi |
|---------|----------------------|
| Problem → Gap | Apakah gap muncul dari analisis literatur terhadap masalah? |
| Gap → RQ | Apakah RQ langsung menjawab gap yang teridentifikasi? |
| RQ → Metrik | Apakah setiap variabel di RQ punya metrik terdefinisi? |
| Metrik → Sistem | Apakah setiap metrik bisa diukur oleh komponen sistem? |
| Sistem → Eksperimen | Apakah desain eksperimen menggunakan sistem sebagai instrumen? |

### Koherensi Vertikal + Horizontal

- **Vertikal** — Alur logis atas-ke-bawah (problem → experiment). Setiap section menjawab pertanyaan yang diangkat section sebelumnya dan memunculkan pertanyaan baru.
- **Horizontal** — Konsistensi terminologi (nama variabel di RQ = di hipotesis = di metrik = di desain)

**Operasionalisasi Red Thread** (benang merah):
```
Bab 2 (Problem) → | memperkenalkan masalah X + evidensi |
                          ↓ menimbulkan pertanyaan: "apa akar gap-nya?"
Bab 3 (Gap)     → | menjawab pertanyaan tadi + membuka "lalu apa yang perlu diteliti?" |
                          ↓
Bab 4 (RQ/H)    → | menjawab gap dengan pertanyaan spesifik + prediksi terukur |
                          ↓
Bab 5-7 (Method)→ | menjawab RQ melalui desain eksperimen yang tepat |
```
Jika ada lompatan (section B tidak menjawab pertanyaan section A), red thread putus.

### Jebakan Kognitif

| Jebakan | Deskripsi |
|---------|----------|
| "Selling" Introduction | Menulis promosi, bukan menyajikan data dan gap |
| Copy-paste Methodology | Menyalin deskripsi tekstbook tanpa menyesuaikan ke RQ |
| Optimistic Timeline | Meremehkan waktu implementasi; selalu tambah buffer 30-50% |
| No Possibility of Failure | Mengimplikasikan hasil pasti sukses — proposal jujur mengakui H₀ mungkin tidak ditolak |

### Struktur Proposal

1. **Pendahuluan** — Latar belakang + problem statement (Bab 1-2)
2. **Tinjauan Pustaka** — Literature review + gap + baseline (Bab 3)
3. **RQ / Kontribusi / Hipotesis** — (Bab 4)
4. **Metodologi** — Metrik + sistem + desain eksperimen (Bab 5-7)
5. **Timeline & Output**

### Istilah Penting

- **Integration Map** — Diagram 6 koneksi kritis antar komponen proposal
- **Vertical Coherence** — Alur logis atas-ke-bawah
- **Horizontal Coherence** — Konsistensi terminologi di semua bagian
- **Checkpoint** — Titik self-assessment sebelum transisi dari desain ke eksekusi

---

## Template A.8 — Integration Checklist

```
PROPOSAL INTEGRATION CHECKLIST

Koneksi Vertikal (Flow Atas-Bawah):
  [ ] Problem → Gap: masalah terdokumentasi di literatur
  [ ] Gap → RQ: pertanyaan menjawab gap spesifik
  [ ] RQ → Hypothesis: hipotesis memprediksi jawaban
  [ ] Hypothesis → Metric: metrik mengukur variabel dalam hipotesis
  [ ] Metric → System: komponen sistem menghasilkan/mengukur metrik
  [ ] System → Experiment: desain eksperimen menggunakan sistem

Koneksi Horizontal (Konsistensi):
  [ ] Istilah sama di semua bagian
  [ ] Variabel di RQ = variabel di hipotesis = metrik di desain
  [ ] Scope tidak berubah dari masalah ke eksperimen

Cognitive Trap Checklist:
  [ ] Tidak ada paragraf "promosi" di pendahuluan (hanya data & gap)
  [ ] Metodologi disesuaikan ke RQ, bukan copy-paste textbook
  [ ] Timeline sudah ditambah buffer 30-50% dari estimasi awal
  [ ] Proposal mengakui kemungkinan H0 tidak ditolak (honest uncertainty)
  [ ] Tidak ada klaim "pasti berhasil" atau "meningkatkan signifikan"

Rubrik Self-Assessment:
| Kriteria     | 1 (Lemah)                                        | 2 (Cukup)                                     | 3 (Baik)                                           | Skor |
|------------- |--------------------------------------------------|-----------------------------------------------|----------------------------------------------------|------|
| Koherensi    | >2 koneksi vertikal terputus                     | 1-2 koneksi lemah, argumen masih bisa diikuti | Semua 6 koneksi terhubung, red thread jelas        |      |
| Specificity  | Variabel/metrik masih abstrak, tidak ada angka   | Sebagian metrik terdefinisi numerik           | Semua metrik + threshold + unit pengukuran jelas   |      |
| Feasibility  | Timeline >6 bulan tanpa memperhitungkan sumber   | Timeline 3-6 bulan dengan asumsi tertentu     | Timeline 1-3 bulan realistis dengan rencana detail |      |
| Rigor        | Baseline tidak jelas atau straw man              | 1-2 baseline dengan justifikasi partial       | 2+ baseline SOTA + justifikasi pemilihan lengkap   |      |
```

---

## Latihan 1 — Kompilasi Proposal Mini

Kumpulkan hasil dari WS-02 sampai WS-07 menjadi satu ringkasan proposal.

| Komponen | Sumber | Isi (1-2 kalimat) |
|----------|--------|-------------------|
| Problem Statement | WS-02 | Pengiriman data detak jantung yang sangat cepat dari alat IoT sering membuat server lokal macet (bottleneck) dan berisiko menghilangkan data rekam medis pasien. Hal ini sering terjadi karena developer asal menggunakan MySQL bawaan tanpa menguji batas kekuatannya terlebih dahulu. |
| Gap | WS-03 | Penelitian yang membandingkan performa MySQL dan PostgreSQL kebanyakan hanya menggunakan simulasi data mati (statis) di dalam komputer. Belum ada riset yang menguji batas kekuatan kedua database tersebut menggunakan aliran data beruntun (continuous streaming) secara langsung dari sensor fisik medis.|
| RQ | WS-04 | Bagaimana perbandingan kecepatan waktu simpan (insert latency) dan tarikan beban memori server antara database MySQL dan PostgreSQL ketika dihajar aliran data berfrekuensi tinggi secara terus-menerus dari sensor MAX30102?|
| Hipotesis | WS-04 | Terdapat perbedaan performa yang nyata, di mana PostgreSQL diprediksi memiliki waktu simpan yang lebih cepat dan beban server yang lebih efisien (minimal unggul 10%) dibandingkan MySQL saat menampung aliran data sensor secara real-time. |
| Variabel & Metrik | WS-05 | Variabel bebas (IV) adalah jenis database yang digunakan (MySQL lawan PostgreSQL). Variabel terikatnya (DV) adalah efisiensi kerja server yang diukur menggunakan metrik kecepatan waktu simpan dalam milidetik (ms) dan persentase (%) pemakaian CPU/RAM server. |
| Sistem | WS-06 | Sistem yang digunakan adalah arsitektur IoT medis yang memisahkan alat pengirim data (sensor MAX30102 dan ESP32) dengan komputer server lokal tempat database engine berjalan. |
| Desain Eksperimen | WS-07 | Eksperimen komparatif ini dilakukan di jaringan Wi-Fi lokal tanpa internet, dengan cara menembakkan 10.000 data ke MySQL terlebih dahulu, lalu server dibersihkan (restart). Setelah itu, langkah dan beban data yang sama persis diulang untuk menguji PostgreSQL agar perbandingannya adil.|

---

## Latihan 2 — Integration Checklist

Verifikasi 6 koneksi kritis. Isi dengan merujuk tabel di Latihan 1.

| Koneksi | Status | Bukti |
|---------|--------|-------|
| Problem → Gap | ✅ | Masalah bottleneck akibat data sensor berfrekuensi tinggi terhubung jelas dengan gap bahwa 5 jurnal sebelumnya hanya menguji database menggunakan data statis di komputer, belum ada yang memakai aliran data real-time dari alat fisik. |
| Gap → RQ | ✅  | RQ langsung menanyakan perbandingan kecepatan waktu simpan dan tarikan beban server saat menerima aliran data terus-menerus (continuous streaming), sangat pas untuk menutup gap tersebut.|
| RQ → Hypothesis | ✅ | Hipotesis (H1) secara spesifik menjawab RQ dengan memprediksi PostgreSQL akan lebih unggul (waktu simpan lebih cepat dan RAM/CPU lebih efisien minimal 10%).|
| Hypothesis → Metric | ✅ | Kecepatan dikonversi menjadi metrik konkret berupa insert latency dalam satuan milidetik (ms), dan efisiensi diukur dari persentase (%) pemakaian RAM dan CPU server.|
| Metric → System | ✅| Sistem dibuat modular (ESP32 sebagai pengirim, laptop sebagai server), sehingga skrip backend bisa presisi mencatat log milidetik, dan Task Manager komputer bisa memantau tarikan % CPU/RAM dengan akurat.|
| System → Experiment |✅ | Desain eksperimen menggunakan skenario terisolasi secara bergantian pada sistem tersebut (tembak 10.000 data ke MySQL, bersihkan/ restart server, lalu tembak 10.000 data ke PostgreSQL) untuk menjaga keadilan alat uji.|

**Koneksi mana yang paling lemah?** rQ-> hipotesis karena  dalam pengujian mungkin meenyatakan 10 % lebih baik itu sulit karena mungkin selisih waktu yang akan dihasilkan akan sangat kecil satuan milidetik dan ada faktor faktor yang mungkin terjadi misal sensor yang tidak berfungsi, jari yang tidak menempel dan lainnya.
**Bagaimana cara memperkuatnya?**
> melakukan uji t test dengan exel dan memastikan semua alat berjalan dengan baik serta saat pengujian penempelan jari benar benar diperhatikan 

**Konsistensi horizontal — apakah istilah dan scope konsisten?** [❌] Ya / [ ] Tidak
> Jika tidak, di bagian mana terjadi inkonsistensi? _________

---

## Latihan 3 — Rubrik Self-Assessment

Evaluasi proposal mini menggunakan rubrik.

| Kriteria | Skor (1-3) | Justifikasi |
|----------|-----------|-------------|
| Koherensi | 3 |  Rantai logikanya sangat nyambung dari masalah awal (bottleneck akibat data berfrekuensi tinggi) , gap penelitian yang menyatakan belum ada uji pakai aliran data real-time , sampai ke desain eksperimen yang pas untuk menjawab hal tersebut.|
| Specificity | 3 | Metrik yang digunakan sudah sangat spesifik dan terdefinisi numerik, yaitu insert latency dalam satuan milidetik (ms) serta persentase (%) penggunaan RAM dan CPU server.|
| Feasibility | 3 | Eksperimen sangat masuk akal dan mudah dijalankan karena hanya menggunakan alat yang sudah ada (laptop server, mikrokontroler ESP32, sensor MAX30102) dan diuji di lingkungan Wi-Fi lokal tanpa butuh biaya server cloud mahal.|
| Rigor | 3 | Pengujiannya dirancang sangat adil dan ketat, contohnya dengan kewajiban membersihkan cache dan me-restart server sebelum mengganti pengujian dari MySQL ke PostgreSQL , serta mengisolasi jaringan agar hasilnya murni.|

**Skor total:** 12/ 12

**Apakah proposal siap untuk fase eksekusi?** [❌] Ya / [ ] Belum
> Jika belum, apa yang perlu diperbaiki? __________________

---

## Refleksi

> Dari seluruh proses WS-01 sampai WS-08, bagian mana yang paling mudah dan paling sulit? Mengapa? Apa yang akan dilakukan berbeda jika mengulang dari awal?

**Bagian termudah:** 
> Menentukan variabel dan metrik, karena tujuannya dari awal sudah jelas ingin menguji mana yang paling cepat (diukur pakai milidetik) dan paling ringan (diukur pakai % tarikan memori).

**Bagian tersulit:** 
> Mengidentifikasi Research Gap (celah penelitian), karena butuh waktu lama untuk membaca dan membandingkan banyak jurnal sebelumnya demi membuktikan kalau pengujian database menggunakan data spam/streaming dari alat fisik (bukan sekadar data statis) memang benar-benar belum ada yang meneliti.

**Yang akan dilakukan berbeda:**
> Lebih rapi dalam menyusun rancangan arsitektur kodingan penangkap datanya (data ingestion) dari awal, supaya nanti pas proses eksekusi tidak perlu banyak perombakan format timestamp saat datanya mulai masuk beruntun.