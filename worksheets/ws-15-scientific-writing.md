# WS-15: Scientific Writing

> **Bab 15 — Penulisan Ilmiah**

---

## Ringkasan Materi

### Scientific Argument Flow

```
Problem → Gap → RQ → Method → Result → Analysis → Conclusion → Contribution
```

Paper ilmiah adalah **satu argumen utuh** dari masalah ke kontribusi. Setiap node harus terhubung logis ke node sebelum dan sesudahnya.

### Struktur IMRAD

| Section | Peran | Pertanyaan Kunci |
|---------|-------|-----------------|
| **Introduction** | Motivasi + frame | Why is this needed? |
| **Method** | Deskripsi (reproducible) | How was it done? |
| **Results** | Laporan objektif | What was found? |
| **Discussion** | Interpretasi + refleksi | What does it mean? |
| **Conclusion** | Ringkasan + kontribusi | So what? |

### Logical Flow — "Red Thread"

Setiap paragraf menjawab satu pertanyaan dan memicu pertanyaan berikutnya. Alur logis ini harus terasa di tiga level:
1. **Antar-kalimat** dalam paragraf
2. **Antar-paragraf** dalam section
3. **Antar-section** dalam paper

### Internal Consistency

Setiap elemen yang dijanjikan di Introduction harus hadir di Discussion/Conclusion.

**Consistency Matrix:**
```
           Intro  Method  Result  Discuss  Conclude
RQ1          ✓      ✓       ✓       ✓        ✓
RQ2          ✓      ✓       ✓       ✗ ←      ✓
Metrik-X     ✗      ✗       ✓ ←     ✗        ✗
```
**Masalah:** RQ2 dibahas di semua bagian kecuali Discussion. Metrik-X muncul di Result tapi tidak diperkenalkan di Method.

### Writing Quality Triad

| Kualitas | Deskripsi | Contoh Buruk → Baik |
|----------|----------|---------------------|
| **Clarity** | Dipahami sekali baca | "Performa meningkat" → "Accuracy meningkat dari 85.3% ke 89.7%" |
| **Precision** | Istilah eksak, tanpa ambiguitas | "signifikan" → "signifikan secara statistik (p=0.003, d=1.2)" |
| **Conciseness** | Setiap kata menambah informasi | Hapus kalimat redundan, filler words |

### Urutan Penulisan yang Disarankan

1. **Method & Results** — paling stabil, tulis pertama
2. **Discussion** — interpretasi berdasarkan hasil
3. **Introduction** — frame sesuai temuan aktual
4. **Abstract & Conclusion** — terakhir

### Target Jumlah Kata

| Section | Target |
|---------|--------|
| Introduction | 500–700 |
| Related Work | 700–1000 |
| Method | 800–1200 |
| Results | 500–800 |
| Discussion | 600–900 |
| Conclusion | 200–400 |

### Jebakan Kognitif

1. "Lebih panjang = lebih lengkap" → conciseness lebih berharga
2. "Introduction harus ditulis pertama" → justru ditulis terakhir
3. "Jargon teknis = lebih ilmiah" → clarity lebih penting
4. "Discussion = ringkasan Results" → Discussion = interpretasi + konteks

---

## Template A.15 — Paper Structure Checklist

```
PAPER STRUCTURE CHECKLIST

Title   : ____________________
Target  : [ ] Jurnal  [ ] Konferensi  [ ] Laporan

Section Check:
  [ ] Abstract — masalah, metode, hasil utama, kontribusi (max 250 kata)
  [ ] Introduction — konteks → gap → RQ → kontribusi → struktur paper
  [ ] Related Work — concept-centric, gap positioning
  [ ] Method — reproducible: desain, variabel, metrik, setup, prosedur
  [ ] Results — tabel + grafik + observasi (tanpa interpretasi)
  [ ] Discussion — interpretasi, perbandingan, implikasi, limitation
  [ ] Conclusion — jawaban RQ, kontribusi, future work

Consistency Matrix:
  [ ] RQ di Introduction = RQ di Method = RQ di Conclusion
  [ ] Variabel di Method = variabel di Results
  [ ] Klaim di Discussion didukung data di Results
  [ ] Limitasi di Discussion di-address di Conclusion/Future Work

Writing Quality:
  [ ] Clarity — mudah dipahami tanpa re-read
  [ ] Precision — tidak ada istilah ambigu
  [ ] Conciseness — tidak ada kalimat redundan
```

---

## Latihan 1 — Paper Outline

Buat outline paper untuk riset Anda menggunakan struktur IMRAD.

| Section | Konten Utama (2-3 kalimat) | Target Kata |
|---------|---------------------------|------------|
| Abstract | Sistem pemantauan detak jantung real time menuntut database dengan kinerja tinggi. Studi ini membandingkan performa MySQL dan PostgreSQL dalam menangani aliran data frekuensi tinggi dari sensor MAX30102. Hasil menunjukkan MySQL memiliki latensi lebih unggul, namun sempat mengalami data loss saat beban puncak (bottleneck). | 200-250 |
| Introduction |  Adanya kebutuhan database yang cepat dan stabil untuk merekam data medis IoT. Gap: Belum ada studi spesifik yang menguji batas performa MySQL vs PostgreSQL menggunakan hantaman data kontinu dari sensor MAX30102. RQ: Arsitektur database mana yang lebih optimal dari segi waktu simpan (latency), RAM, dan keandalan? | 500-700 |
| Related Work | Sebagian besar literatur terdahulu hanya berfokus pada akurasi klinis sensor MAX30102 atau performa database pada data IoT umum berskala kecil. Studi ini melengkapi literatur tersebut dengan menguji batas toleransi (stress-test) database menggunakan data berfrekuensi tinggi.| 700-1000 |
| Method | Data detak jantung di-generate menggunakan mikrokontroler dan sensor MAX30102 yang dikirim langsung ke web server lokal. Pengumpulan data dilakukan sebanyak 35 run untuk tiap database, kemudian dianalisis perbedaan rata-ratanya menggunakan Independent Sample T-Test di SPSS. | 800-1200 |
| Results | Uji statistik membuktikan MySQL jauh lebih cepat dengan rata-rata waktu simpan 2.12 ms dibanding PostgreSQL yang mencapai 4.46 ms (p < 0.001) Namun, pada deteksi anomali, MySQL mencatat insiden data loss sebanyak 15 baris pada salah satu run akibat antrean sistem. | 500-800 |
| Discussion | Terdapat trade off yang jelas arsitektur MySQL sangat cepat untuk penulisan data sederhana, namun lebih rentan terhadap bottleneck ekstrem. Sebaliknya, PostgreSQL lebih lambat karena proses validasi relasionalnya yang ketat, namun berpotensi lebih stabil dalam menjaga integritas data tanpa loss.| 600-900 |
| Conclusion | MySQL direkomendasikan untuk arsitektur IoT medis yang memprioritaskan kecepatan real-time. Namun, perancang sistem harus menyiapkan mekanisme penanganan antrean (queue handling) untuk mencegah hilangnya data berharga saat sensor mengirimkan paket data secara masif. | 200-400 |

---

## Latihan 2 — Consistency Matrix

Buat consistency matrix untuk memverifikasi internal consistency paper Anda.

|  | Intro | Method | Result | Discussion | Conclusion |
|--|-------|--------|--------|-----------|-----------|
| RQ1 (Perbandingan Waktu Simpan & RAM) | ✓| ✓| ✓| ✓| ✓|
| RQ2 (Tingkat Keandalan / Data Loss) | X| ~| ✓| ✓|✓ |
| Metrik utama (Latency, RAM, Loss) | ~| ✓|✓ | ✓| ✓|
| Variabel IV (Jenis Database) |✓ | ✓|✓ | ✓| ✓|
| Variabel DV (Waktu Simpan, RAM, Loss) | ~| ✓| ✓ | ✓| ✓|
| Klaim/kontribusi (MySQL cepat tapi rentan) | ✓| ✓| ✓| ✓|✓ |

**Isi setiap sel:** ✓ (ada & konsisten), ✗ (missing), ~ (ada tapi inkonsisten)

**Inkonsistensi yang ditemukan:**
> Terdapat variabel siluman (missing variable) pada desain awal riset. Variabel "Data Loss" (Data Hilang) belum dimasukkan ke dalam Rumusan Masalah (RQ2) secara eksplisit di bagian Introduction. Variabel ini baru muncul secara tiba-tiba di bagian Result karena ditemukannya anomali 15 data yang hilang saat stress-test MySQL, lalu dibahas secara panjang lebar sebagai kelemahan utama di Discussion dan ditarik menjadi kesimpulan di Conclusion. Hal ini membuat alur paper menjadi inkonsisten dari depan ke belakang.

**Tindakan perbaikan:**
> Melakukan revisi mundur (backtracking) pada bagian Introduction dengan menambahkan RQ2 yang secara spesifik menanyakan tingkat keandalan (faktor Data Loss) saat menghadapi hantaman data frekuensi tinggi. Selain itu, memperbarui bagian Method dengan menambahkan paragraf yang menjelaskan secara teknis bagaimana Data Loss diukur dan direkam selama proses mikrokontroler mengirimkan data sensor MAX30102 ke server lokal.

---

## Latihan 3 — Writing Quality Check

Ambil satu paragraf dari tulisan Anda (atau tulis paragraf baru) dan evaluasi kualitasnya.

**Paragraf asli:**
>Pengujian ini dilakukan untuk melihat performa dari kedua database. Kita memakai sensor detak jantung untuk mengirim data ke server. Hasilnya MySQL lebih bagus dari PostgreSQL karena waktunya lebih cepat. Tapi MySQL ada data yang hilang saat servernya sibuk banget, sedangkan PostgreSQL aman-aman saja.

| Kriteria | Evaluasi | Perbaikan |
|----------|---------|-----------|
| Clarity | Kata "performa", "lebih bagus", dan "sibuk banget" terlalu ambigu, subjektif, dan tidak baku. | Ubah menjadi istilah teknis: "latensi", "waktu simpan", dan "beban puncak antrean". |
| Precision | "Waktunya lebih cepat" dan "data yang hilang" tidak memiliki angka pasti. Nama sensor tidak disebutkan. | Masukkan angka statistik spesifik (2.12 ms vs 4.46 ms, dan 15 baris data hilang), serta nama sensor (MAX30102).|
| Conciseness |Kalimat "Kita memakai sensor detak jantung untuk mengirim data ke server" terlalu bertele-tele dan bergaya bahasa lisan (menggunakan kata "Kita"). | Gabungkan dan padatkan menjadi satu kalimat pengantar berstruktur pasif/akademis yang langsung menuju inti eksperimen.|

**Paragraf setelah perbaikan:**
> Pengujian ini mengevaluasi latensi dan keandalan MySQL dan PostgreSQL dalam menangani aliran data frekuensi tinggi dari sensor MAX30102. Uji statistik menunjukkan MySQL memiliki rata-rata waktu simpan yang secara signifikan lebih cepat (2.12 ms) dibandingkan PostgreSQL (4.46 ms). Meskipun unggul dalam kecepatan, MySQL mengalami data loss sebanyak 15 baris pada saat beban puncak, sementara PostgreSQL terbukti lebih stabil dalam menjaga integritas data tanpa adanya data yang hilang.
---

## Refleksi

> Apa perbedaan antara menulis "tentang" riset dan menulis sebagai "argumen" riset? Bagaimana urutan penulisan (Method → Discussion → Introduction) mengubah kualitas tulisan?

> Menulis tentang riset ibarat hanya membuat laporan jurnal kegiatan sekadar menceritakan langkah-langkah yang dilakukan secara datar. Sebaliknya, menulis sebagai argumen riset berarti setiap kalimat disusun dengan tujuan menyajikan bukti empiris untuk meyakinkan pembaca atau menjawab rumusan masalah (misalnya, menarasikan trade-off antara kecepatan MySQL vs keandalan PostgreSQL dengan angka pasti).

> Terkait urutan penulisan yang dibalik (mulai dari Method & Result dahulu, baru ke Introduction di akhir), hal ini sangat mengubah kualitas tulisan menjadi jauh lebih tajam dan konsisten. Seperti pengalaman pada Latihan Consistency Matrix sebelumnya, temuan tak terduga di lapangan (seperti adanya outlier data loss) sering kali baru terlihat di bagian hasil. Dengan menulis Introduction paling akhir, kita dapat menyelaraskan rumusan masalah agar benar-benar "menjemput" temuan penting tersebut, sehingga tidak ada variabel yang terkesan muncul tiba-tiba secara tidak logis di tengah tulisan.