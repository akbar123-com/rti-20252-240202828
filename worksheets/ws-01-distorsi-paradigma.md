# WS-01: Distorsi & Paradigma

> **Bab 1 — Research Mindset in IT**

---

## Ringkasan Materi

### Research Trust Model

Pengetahuan ilmiah tidak muncul langsung dari kenyataan. Ia melewati **6 tahap transformasi** yang masing-masing rawan distorsi:

```
Reality → Data → Processing → Analysis → Inference → Knowledge
```

Etika mencegah distorsi yang disengaja (fabrikasi, cherry-picking). Validitas mendeteksi distorsi yang tidak disengaja (confounding variable, sampling bias).

### Tiga Jenis Validitas

| Jenis | Pertanyaan | Contoh Ancaman |
|-------|-----------|----------------|
| **Internal Validity** | Apakah hubungan kausal benar ada? | Confounding variable |
| **External Validity** | Apakah bisa digeneralisasi? | Dataset terlalu homogen |
| **Construct Validity** | Apakah mengukur hal yang benar? | Metrik tidak sesuai klaim |

### Paradigma Riset

Mata kuliah ini menggunakan pendekatan **Positivist** (fenomena TI bisa diukur objektif melalui eksperimen terkontrol) diperkuat **Design Science Research** (artefak dibuat sebagai instrumen pengujian hipotesis, bukan tujuan akhir).

### Mode Berpikir Peneliti

**Curious** (mempertanyakan fenomena) → **Critical** (mengevaluasi klaim berdasarkan bukti) → **Systematic** (merancang investigasi terstruktur dan reproducible).

### Research vs Engineering

| Aspek | Engineering | Research |
|-------|------------|----------|
| Tujuan | Membuat sistem yang bekerja | Menghasilkan pengetahuan yang valid |
| Pertanyaan khas | "Bagaimana membuatnya jalan?" | "Apakah klaim ini benar?" |
| Ukuran sukses | Sistem berfungsi, client puas | Hipotesis terjawab, temuan tervalidasi |
| Kegagalan | Harus dihindari | Harus dilaporkan (negative result = kontribusi) |

### Istilah Penting

- **Research Mindset** — Pola pikir yang menuntut bukti dan mempertanyakan asumsi
- **Research Ethics** — Prinsip perilaku: kejujuran, objektivitas, keterbukaan, akuntabilitas
- **HARKing** — Hypothesizing After Results are Known — merumuskan hipotesis setelah melihat data
- **Falsifiability** — Hipotesis harus bisa dibuktikan salah

---

## Template A.1 — Research Mindset Self-Assessment

```
Nama Peneliti    : ____________________
Tanggal          : ____________________

1. Ketika membaca klaim "metode X 95% akurat":
   - Pertanyaan pertama saya: ____________________
   - Data yang dibutuhkan untuk verifikasi: ____________________

2. Posisi paradigma:
   - Pendekatan: [ ] Positivis  [ ] Interpretivis  [ ] Design Science  [ ] Mixed
   - Alasan: ____________________

3. Identifikasi distorsi:
   - Asumsi tersembunyi: ____________________
   - Sumber bias potensial: ____________________
   - Langkah mitigasi: ____________________

4. Komitmen etika:
   - Data yang tidak akan dimanipulasi: ____________________
   - Batasan yang diakui sejak awal: ____________________
```

---

## Latihan 1 — Identifikasi Distorsi

Pilih satu paper riset di bidang TI yang mengklaim "metode X meningkatkan performa." Telusuri setiap tahap Research Trust Model.

**Paper yang dipilih:**

> Judul: Implementasi Teknik Query Optimization Untuk Meningkatkan Performa SQL Server Pada Sistem Informasi Akademik.
> Penulis (Tahun): Salam Patulus Rahmat Tambunan, dkk. (2025) .
> link jurnal : https://ejournal.itn.ac.id/index.php/jati/article/view/13041

| Tahap | Apa yang Dilakukan | Potensi Distorsi |
|-------|-------------------|-----------------|
| Reality → Data | Peneliti tidak menggunakan data asli kampus, melainkan membuat data buatan (dummy data) menggunakan SQL Server Profiler (misal: 10.000 data mahasiswa, 10.000 data KRS). | Data dummy terlalu rapi dan statis. data tersebut tidak menggambarkan kondisi nyata dari data SIAKAD atau SIM asli yang biasanya memiliki keanehan, tidak terstruktur, dan diakses oleh ribuan orang secara bersamaan seing disebut concurrent. |
| Data → Processing | Mengeksekusi 1 kueri spesifik (mencari mahasiswa prodi Teknik Informatika). Pengujian performa dengan durasi dan CPU direkam menggunakan SQL Server Profiler serta proses tersebut hanya diulang sebanyak 5 kali untuk tiap metode.| Sempel terlalu kecil dan sempit. yang diuji hanya 1 kueri SELECT sederhana sebanyak 5 kali tidak mewakili beban kerja sistem database yang aslinya biasanya harus memproses ratusan kueri rumit seperti INSERT saat pengisian KRS mahasiswa secara bersamaan.|
| Processing → Analysis | Menghitung nilai rata-rata  dari durasi eksekusi dan penggunaan CPU dari 5 kali pengujian tersebut, lalu membandingkannya dalam bentuk tabel dan grafik batang.| Mengandalkan nilai rata-rata dari pengujian yang hanya 5 kali dapat berpotensi menyembunyikan outlier (pencilan) atau lonjakan beban server yang ekstrem.|
| Analysis → Inference | menarik kesimpulan bahwa metode Indexing meningkatkan kecepatan 93% dan Stored Procedures 95%. Mereka menyimpulkan bahwa kombinasi ini optimal untuk keseluruhan SIAKAD.| Overclaiming (Klaim berlebih) karena pada jutrnal Menyimpulkan bahwa ini sangat baik untuk seluruh sistem SIAKAD , padahal yang diuji dan dianalisis hanyalah fitur pencarian data pada prodi mahasiswa.|
| Inference → Knowledge | menganggap metode ini sebagai solusi yang mendukung pengelolaan data akademik secara cepat, akurat, dan handal.| Memukul rata bahwa persentase peningkatan (93% dan 95%)  akan berlaku mutlak di institusi lain, tanpa mempertimbangkan perbedaan spesifikasi hardware server, jenis database, atau volume data di kampus lainnya. |

**Distorsi paling besar di tahap:** Reality -> Data dan Data -> Processing.

**Dua distorsi spesifik yang teridentifikasi:**
1. Penggunaan data berupa Dummy Data pada Peneliti di jurnal ini menggunakan data buatan yang tidak mencerminkan beban, skala, dan kompleksitas lalu lintas data pada Sistem Informasi Akademik di instansi atau dunia nyata.
2. Pengujian yang dilakukan Tidak Realistis karena Pengujian performa hanya dieksekusi sebanyak 5 kali menggunakan 1 kueri pencarian (SELECT) sederhana. hal twersebut mengabaikan fakta bahwa sistem akademik asli harus menangani operasi baca tulis (CRUD) yang kompleks secara bersamaan contohnya saat masa war KRS mahasiswa.

---

## Latihan 2 — Analisis Kasus Etika

Skenario: Seorang peneliti menemukan bahwa jika 3 data point outlier dihapus, hasil eksperimennya menjadi signifikan. Dengan outlier, hasilnya tidak signifikan.

| Perspektif | Analisis |
|------------|---------|
| Kejujuran ilmiah | Peneliti tidak boleh memanipulasi atau memilih milih data hanya agar eksperimen penelitiannya berhasil. Kalau hasil aslinya memang tidak signifikan, maka harus diakui dan dilaporkan apa adanya, bukan malah sengaja membuang data yang membuat hasil menjadi jelek. |
| Transparansi | Kalaupun ada data aneh (outlier) dan peneliti merasa itu harus dibuang, alasannya wajib dijelaskan dengan jujur di dalam laporan. Misalnya,ada error pada alat ukur, human error saat input data dan lain lain Jangan pernah menghapus data tersebut secara diam diam.|
| Peer review | Dengan melaporkan semua data secara jujur, penguji (reviewer) nanti bisa ikut menilai apakah outlier tersebut memang wajar dibuang atau tidak. Ini fungsinya untuk saling ngecek biar tidak ada kecurangan atau bias dari peneliti.|

**Keputusan akhir dan justifikasi:**
> keputusan akhirnya Sebaiknya peneliti sebaiknya melaporkan kedua hasilnya. Hasil utama tetap memakai data yang utuh termasuk outlier. Nanti, hasil yang tanpa outlier bisa dimasukkan sebagai tambahan atau perbandingan di bagian diskusi beserta  dengan alasan kenapa 3 data itu dianggap aneh.

justifikasi : Menghapus data cuma supaya hipotesisnya terbukti itu termasuk curang dan melanggar etika penelitian. Dengan tetap menampilkan semua datanya, peneliti membuktikan bahwa dia objektif. Biarkan pembaca dan penguji yang menilai sendiri apakah hasilnya masuk akal atau tidak.

---

## Latihan 3 — Posisi Paradigma

**Topik riset:** Implementasi Teknik Query Optimization (Indexing & Stored Procedures) untuk Meningkatkan Performa SQL Server.

| Kriteria | Positivis | Interpretivis | Design Science |
|----------|-----------|---------------|----------------|
| Kesesuaian dengan topik (1–5) | 4 | 1 | 5 |
| Jenis data yang dikumpulkan | Data kuantitatif karena berisi angka numerik mutlak seperti waktu eksekusi dalam milidetik dan persentase penggunaan CPU.| Data kualitatif karena berisi teks wawancara, observasi perilaku, atau pendapat pengguna SIAKAD.| Data kinerja sistem atau evaluasi artefak karena penelitian diatas adalah perbandingan performa sebelum dan sesudah metode baru diterapkan.|
| Limitasi paradigma | Hanya fokus pada angka baku tetapi Mengabaikan konteks nyata pengguna .misalnya, angka di sistem cepat, tapi mahasiswa aslinya tetap merasa aplikasinya susah dipakai.| Hasilnya terlalu subjektif dan tidak bisa diukur secara matematis. Tidak cocok untuk menguji sistem database.| Solusi yang dibuat  terlalu spesifik untuk satu masalah saja, sehingga belum tentu berhasil jika diterapkan di database atau kampus lain.|

**Paradigma yang dipilih:** Design Science
**Alasan:** Penelitian pada jurnal diatas tidak hanya sekadar mengamati fenomena saja, tetapi secara aktif merancang dan menciptakan sebuah solusi baru yaitu menerapkan teknik Indexing dan Stored Procedures untuk memecahkan masalah di dunia nyata yaitu database SIAKAD yang lambat. Setelah solusi itu dibuat, peneliti juga menguji dan mengevaluasi kinerjanya. Ini adalah inti dan tujuan utama dari paradigma Design Science dalam bidang Teknik Informatika.
---

## Refleksi

> Sebelum membaca materi ini, apakah pernah mempertanyakan klaim "95% akurat"? Setelah memahami rantai distorsi, pertanyaan apa yang sekarang akan diajukan saat membaca paper?

**Jawaban:**
> sebelum mempelajari materi ini, saya cenderung langsung percaya dan kagum saat melihat klaim seperti metode ini 95% akurat atau meningkatkan performa 95%. Saya sering menganggap persenan tersebut sebagau angka yang sudah pasti kebenaran atau mutlak serta otomatis membuktikan bahwa metode tersebut sangat hebat atau berhasil serta efisien dalam menyelesaikan masalah yang dihadapi, tanpa mempertanyakan bagaimana angka itu didapatkan.
Namun, setelah memahami adanya rantai distorsi (Research Trust Model) pada materi minggu ini, pola pikir saya berubah. Sekarang, ketika membaca klaim angka yang tinggi dalam sebuah paper ataupun jurnal, saya tidak akan langsung menelan mentah-mentah presentasi tersebut atau mempercayainya langsung. Pertanyaan-pertanyaan kritis yang  akan saya ajukan saat membaca paper dan melihat klaim presentasi tersebut adalah:
> 1. Terkait Datanya : Datanya asli atau buatan? Apakah klaim 95% itu didapat dari pengujian di dunia nyata , atau sekadar menggunakan data dummy yang sudah dikondisikan agar rapi?

2. Terkait Pengujian : Bagaimana alur pengujiannya dilakukan? Apakah angkanya tinggi karena hanya diuji pada kasus yang sangat sederhana misalnya hanya dites 5 kali dan tidak mencerminkan beban kerja aslinya?

3. Terkait Analisis & Etika : Apakah peneliti jujur? Jangan-jangan angka 95% itu didapat karena peneliti menghapus  data data yang bisa membuat hasil menjadi jelek (outlier) ?

4. Terkait Kesimpulan : Apakah peneliti ini overclaiming (memukul rata atau menganggap sama)? Apakah 95% ini benar benar bisa diterapkan di tempat lain juga, atau hanya berlaku di komputer dan laboratorium si peneliti itu saja?
