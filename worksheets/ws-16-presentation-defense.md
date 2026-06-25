# WS-16: Presentation & Defense (UAS)

> **Bab 16 — Presentasi & Pertahanan Ilmiah**

---

## Ringkasan Materi

### Scientific Defense Model

```
Research Work → Presentation → Questioning → Defense → Evaluation → Acceptance
```

### Presentasi ≠ Ringkasan Paper

| Paper | Presentasi |
|-------|-----------|
| Dibaca (self-paced) | Didengar (presenter-paced) |
| Detail lengkap | Ide kunci + highlight |
| Tabel numerik detail | Grafik visual + angka kunci |
| Pembaca bisa re-read | Audiens dengar sekali |

**Prinsip:** Presentasi membutuhkan **reformulasi**, bukan kompresi. Medium berbeda = pendekatan berbeda.

### Claim-Evidence-Reasoning (CER)

Setiap jawaban defense harus memiliki:
1. **Claim** — Pernyataan yang dijawab
2. **Evidence** — Data/fakta pendukung
3. **Reasoning** — Logika yang menghubungkan evidence ke claim

**Contoh:**
| Pertanyaan | Bad Answer | Good Answer (CER) |
|-----------|-----------|-------------------|
| "Kenapa hanya 3 dataset?" | "Tiga sudah cukup" | "3 dataset mewakili variasi: small-clean, medium-clean, medium-noisy [E]. Generalisasi perlu validasi lanjut — listed as limitation [R]" |
| "Hasil DS-3 menurun?" | "Itu outlier" | "Ya, karena distribusi heavy-tail melanggar asumsi Gaussian [E]. Ini menunjukkan boundary condition metode [R]" |
| "Effect size?" | "p=0.003, jadi signifikan" | "Cohen's d=1.2 (large effect) [E] — bukan hanya signifikan tapi substansial [R]" |

### Slide Design — One Slide, One Message

**Optimal 9-Slide Plan (15 menit):**

| # | Slide | Waktu | Pesan |
|---|-------|-------|-------|
| 1 | Title + context | 1 min | Apa ini tentang apa |
| 2 | Problem + motivation | 2 min | Mengapa penting |
| 3 | Gap + RQ | 1.5 min | Apa yang belum terjawab |
| 4 | Method overview | 2 min | Bagaimana dijawab (diagram) |
| 5 | Key result — tabel | 2 min | Temuan utama |
| 6 | Key result — grafik | 2 min | Pola visual |
| 7 | Interpretation + failure | 2 min | Apa artinya |
| 8 | Limitation + future | 1.5 min | Batasan & arah |
| 9 | Conclusion + contribution | 1 min | Closing message |

### Anticipatory Defense

Prediksi pertanyaan berdasarkan kategori:

| Kategori | Contoh Pertanyaan |
|---------|------------------|
| Problem | "Mengapa masalah ini penting?" |
| Gap | "Bagaimana dengan studi X yang sudah menjawab ini?" |
| Method | "Mengapa metode ini, bukan Y?" |
| Results | "Bagaimana menjelaskan anomali di DS-3?" |
| Generalization | "Apakah bisa diterapkan di domain lain?" |

### Tiga Prinsip Jawaban

1. **Direct** — Jawab dulu, elaborasi kemudian
2. **Data-based** — Tunjuk evidence spesifik
3. **Honest** — Akui limitasi jika memang ada

### Jebakan Kognitif

1. "Presentasi = semua yang ada di paper" → terlalu padat
2. "Slide cantik = presentasi bagus" → konten > estetika
3. "Tidak bisa jawab = gagal" → "I don't know, but..." menunjukkan kejujuran
4. "Tidak perlu latihan — saya paham riset saya" → latihan = menemukan celah

---

## Template A.16 — Defense Preparation Sheet

```
DEFENSE PREPARATION

Slide Deck Plan:
  Total slides   : ____ (target: 10-12 konten + title/closing)
  Time per slide : ~2 min
  Total time     : ____ menit

Slide Outline:
| # | Pesan Utama | Visual | Waktu |
|---|-------------|--------|-------|
| 1 | Title       |        | 30s   |
| 2 | Problem     |        | 2min  |
| 3 | Gap + RQ    |        | 2min  |
| ..|             |        |       |

Anticipatory Defense Matrix:
| Kategori | Pertanyaan Potensial | Jawaban (CER) |
|----------|---------------------|---------------|
| Problem  |                     |               |
| Gap      |                     |               |
| Method   |                     |               |
| Results  |                     |               |
| Generalization |               |               |

Latihan:
  Latihan 1: [tanggal] — [catatan timing & feedback]
  Latihan 2: [tanggal] — [catatan timing & feedback]
  Latihan 3: [tanggal] — [catatan timing & feedback]
```

---

## Latihan 1 — Slide Outline

Rencanakan presentasi 15 menit untuk riset Anda.

| # | Pesan Utama | Visual yang Digunakan | Waktu |
|---|-------------|----------------------|-------|
| 1 | Judul + Konteks: Optimasi Database IoT untuk Sensor Medis MAX30102. |  Title slide, foto alat/sensor.| 1 min |
| 2 |Problem: Kebutuhan kecepatan real-time vs stabilitas integritas data medis.  | Ilustrasi alur data dari sensor ke DB. | 2 min |
| 3 | Gap + RQ: Database mana yang paling tangguh menahan beban frekuensi tinggi? | Tabel perbandingan spektrum sistem. | 1.5 min |
| 4 |Method: Arsitektur pengujian 35 run (Sensor ke ESP32 ke Server Lokal). | Diagram blok arsitektur sistem. | 2 min|
| 5 | Result 1: MySQL lebih cepat secara signifikan ($2.12$ ms vs $4.46$ ms). |Bar chart perbandingan latensi. | 2 min|
| 6 |Result 2: Temuan Anomali — Bottleneck MySQL memicu 15 data loss. | Box plot / Tabel deteksi anomali. |2 min |
| 7 | Discussion: Trade-off antara kecepatan (MySQL) vs Keandalan (PostgreSQL).|Infografis pro & kontra tiap DB. |1.5 min |
| 8 |Conclusion: Rekomendasi penggunaan MySQL dengan sistem queue handling. |Poin-poin kesimpulan & saran. | 2 min|
| 9 |Sesi Tanya Jawab & Penutup. |Slide Q&A. | 1 min|

**Total waktu estimasi:** 15 menit

---

## Latihan 2 — Anticipatory Defense

Prediksi 5 pertanyaan yang mungkin diajukan penguji, lalu siapkan jawaban CER.

| # | Kategori | Pertanyaan | Claim | Evidence | Reasoning |
|---|----------|-----------|-------|----------|-----------|
| 1 | Problem | Mengapa repot menguji database lokal (MySQL/PostgreSQL) dan tidak langsung memakai Cloud IoT (seperti Firebase)? | Pengujian lokal diperlukan untuk mengisolasi variabel performa murni dari mesin database itu sendiri. | Cloud memiliki variabel latensi internet (ISP) yang tidak bisa dikontrol dan berfluktuasi. | Menggunakan server lokal memastikan latensi 2.12 ms murni berasal dari proses komputasi mesin, bukan karena sinyal Wi-Fi yang jelek. |
| 2 | Method | Mengapa Anda hanya melakukan 35 run per pengujian? Apakah itu cukup merepresentasikan performa server? | Jumlah sampel 35 sudah memenuhi standar validitas statistik parametrik. | Standar deviasi sangat kecil (MySQL 0.36, PostgreSQL 0.55) dan N > 30 (Central Limit Theorem) | Karena varians datanya sangat rendah dan konsisten, penambahan jumlah run hingga 100 sekalipun tidak akan mengubah kesimpulan uji beda rata-rata (T-Test) secara signifikan. |
| 3 | Result|Terkait hilangnya 15 data di MySQL, dari mana Anda yakin itu kelemahan database dan bukan alat sensornya yang rusak/mati? | Kehilangan data murni terjadi akibat bottleneck di antrean server, bukan kegagalan perangkat keras.|Log serial pada ESP32/MAX30102 tetap mencatat pengiriman sukses, namun database menolak insert pada beban puncak. | Kecepatan sensor melebihi kapasitas operasional MySQL dalam menangani I/O disk pada detik tersebut, sehingga paket data di- drop oleh sistem.|
| 4 | Discussion| Jika PostgreSQL terbukti lebih lambat, apakah artinya arsitektur ini gagal dan tidak layak dipakai untuk IoT Medis?|Lambat tidak berarti gagal; PostgreSQL justru menawarkan stabilitas absolut. |Hasil eksperimen menunjukkan $0$ kasus data loss pada PostgreSQL di seluruh 35 run. | Dalam konteks medis kritikal, integritas data (tidak ada rekam jantung yang hilang) jauh lebih krusial dibandingkan selisih kecepatan $2$ milidetik. |
| 5 |Conclusion |Berdasarkan kelebihan dan kekurangan ini, apa solusi paling aman jika sistem ini akan benar-benar dipasang di Rumah Sakit besok? |Dibutuhkan pendekatan hybrid menggunakan sistem antrean (queueing). | MySQL cepat tapi rentan loss; PostgreSQL aman tapi lambat.| Menggunakan MySQL sebagai database utama untuk UI yang real-time, namun ditambahkan message broker (seperti MQTT/Redis) di depannya sebagai penahan (buffer) agar data tidak hilang saat bottleneck.|

---

## Latihan 3 — Simulasi Q&A

Minta teman/kolega mengajukan 3 pertanyaan tentang riset Anda. Catat pertanyaan dan evaluasi jawaban Anda.

| # | Pertanyaan | Jawaban Saya | Evaluasi |
|---|-----------|-------------|---------|
| 1 | "Mengapa Anda ngotot menguji database relasional (SQL) untuk data IoT, padahal NoSQL seperti MongoDB jelas lebih ringan untuk sensor?" |  "Karena sistem rekam medis di rumah sakit mayoritas masih menggunakan struktur relasional. Riset ini bertujuan menguji batas maksimal database legacy tersebut sebelum memutuskan apakah rumah sakit benar-benar wajib migrasi ke NoSQL. Hal ini tercatat sebagai batasan masalah."| [✓] Direct [✓] Data-based [✓] Honest |
| 2 |"Apakah Anda yakin 15 data loss di MySQL itu murni kelemahan database? Bisa saja ESP32 Anda yang putus koneksi Wi-Fi-nya sesaat?" |"Sangat yakin. Serial monitor pada ESP32 mencatat HTTP POST berhasil terkirim 100%, namun log error pada Apache/PHP di server menunjukkan timeout antrean khusus pada mesin MySQL. Jadi paketnya sampai di server, tapi gagal dieksekusi database."| [✓] Direct [✓] Data-based [✓] Honest |
| 3 | "Waktu simpan PostgreSQL 4,46 ms (dua kali lipat lebih lambat). Apakah ini tidak akan membuat visualisasi grafik detak jantung di layar dokter menjadi patah-patah (lagging)?"| "Secara matematis, 4,46 ms masih jauh di bawah ambang batas 16 ms yang dibutuhkan untuk me-render grafik 60 FPS secara mulus. Oleh karena itu, selisih waktu tersebut tidak akan terasa oleh mata manusia, membuat keandalan integritas datanya sangat sepadan."| [✓] Direct [✓] Data-based [✓] Honest |


**Pertanyaan yang paling sulit dijawab:**
> Pertanyaan nomor 2. Menjelaskan kepastian penyebab data loss membutuhkan argumen berlapis karena melibatkan dua sisi (client mikrokontroler dan server lokal). Sangat mudah bagi penguji untuk berasumsi bahwa hilangnya data disebabkan oleh sinyal Wi-Fi yang buruk, bukan karena bottleneck pada mesin database.

**Apa yang perlu disiapkan lebih baik:**
> Menyiapkan screenshot (tangkapan layar) dari log error Apache/MySQL dan serial monitor Arduino IDE, lalu meletakkannya di slide presentasi cadangan (appendix slides). Jika penguji meragukan klaim data loss tersebut, bukti visual teknis tersebut bisa langsung ditampilkan.

---

## Refleksi

> Dari seluruh proses WS-01 sampai WS-16 — dari paradigma riset hingga presentasi — bagian mana yang paling mengubah cara Anda berpikir tentang riset? Apa satu hal yang akan selalu Anda terapkan di riset berikutnya?

**Insight terbesar:**
> Mengubah pandangan saya terhadap "kegagalan" eksperimen. Sebelumnya, saya berasumsi bahwa riset yang sukses adalah riset yang datanya mulus dan sempurna sesuai hipotesis awal. Namun, saat menemukan anomali berupa hilangnya 15 baris data (outlier) akibat bottleneck, saya menyadari bahwa nilai ekstrem tersebut bukanlah "error" yang harus dihapus atau dimanipulasi, melainkan temuan utama (novelty) yang menunjukkan batas maksimal kemampuan sistem di dunia nyata. Kegagalan sistem adalah kontribusi ilmu pengetahuan.

**Yang akan selalu diterapkan:**
> Saya akan selalu menerapkan pembuatan Consistency Matrix (seperti di WS-15) sebelum menyusun draf akhir. Praktik ini memastikan setiap temuan tak terduga yang muncul di bab Hasil (Results) tidak terkesan muncul tiba-tiba sebagai "variabel siluman", melainkan sudah dijemput rapi oleh Rumusan Masalah di bab Pendahuluan (Introduction).
