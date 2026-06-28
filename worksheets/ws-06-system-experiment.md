# WS-06: System-Experiment Mapping

> **Bab 6 — System Design sebagai Experimental Artifact**

---

## Ringkasan Materi

### Sistem = Instrumen Pengujian, Bukan Produk

Seorang engineer bertanya "apakah sistem bekerja?" — seorang peneliti bertanya "apa yang bisa dibuktikan sistem ini?" Sistem dalam riset adalah **artifact** — objek yang sengaja dibuat untuk menguji klaim spesifik.

### System as Experiment Model

```
RQ → Variable → System Component → Experimental Setup → Output
```

Setiap komponen sistem harus bisa ditelusuri ke variabel riset (top-down), dan setiap pengukuran harus menjawab RQ (bottom-up).

### Mapping Variabel ke Komponen

| Tipe Variabel | Peran di Sistem | Contoh |
|---------------|----------------|--------|
| **IV** (Independent) | Modul yang bisa di-toggle/swap | Algoritma A vs B |
| **DV** (Dependent) | Modul pengukuran | Logger, metrics collector |
| **CV** (Control) | Config yang dikunci | Dataset, parameter tetap |

Jika variabel tidak bisa di-map ke komponen apapun → arsitektur perlu didesain ulang.

### 4 Prinsip Desain Eksperimental

| Prinsip | Pertanyaan Kunci |
|---------|-----------------|
| **Traceability** | Komponen ini melayani variabel yang mana? |
| **Modularity** | Bisakah IV diubah tanpa memengaruhi yang lain? |
| **Controllability** | Apakah CV dieksternalisasi ke config file? |
| **Measurability** | Apakah sistem otomatis menghasilkan data yang dibutuhkan? |

### Variable Isolation melalui Arsitektur

- **Modular architecture** — Pisahkan berdasarkan variabel
- **Configuration-driven** — Ubah config (YAML/JSON), bukan code
- **Feature toggles** — On/off flag untuk ablation study

  Contoh config YAML dengan feature toggles:
  ```yaml
  model:
    type: cnn          # IV: ganti "rf" untuk kondisi baseline
  features:
    use_temporal: true  # toggle komponen temporal
    use_normalization: true  # toggle preprocessing
  experiment:
    seed: 42
    runs: 5
  ```
  Dengan pendekatan ini, berbeda kondisi eksperimen = berbeda satu baris config, **tanpa mengubah kode**.

### Research vs Engineering

| Aspek | Engineering | Research |
|-------|------------|----------|
| Tujuan sistem | Memenuhi kebutuhan user | Menguji hipotesis, menghasilkan bukti |
| Arsitektur | Optimasi performa & skalabilitas | Optimasi isolasi variabel & reprodusibilitas |
| Konfigurasi | Sering hardcoded | Dieksternalisasi ke config file |
| Fitur tambahan | Menambah nilai user | Menambah noise jika tidak terkait RQ |

### Istilah Penting

- **Artifact** — Objek yang sengaja dibuat untuk memecahkan masalah atau menguji proposisi
- **Traceability** — Kemampuan menelusuri hubungan RQ → variabel → komponen → output
- **Variable Isolation** — Mengubah hanya satu variabel sambil menahan yang lain konstan
- **Ablation Study** — Menguji kontribusi tiap komponen dengan melepasnya satu per satu
- **Configuration-driven Execution** — Semua parameter di config file, bukan hardcoded

---

## Template A.6 — Mapping RQ ke Arsitektur Sistem

```
SYSTEM-EXPERIMENT MAPPING

Research Question: ____________________

Variable → Component Mapping:
| Variabel | Tipe | Komponen Sistem | Cara Manipulasi/Pengukuran |
|----------|------|-----------------|---------------------------|
|       | IV   |                 |                           |
|       | DV   |                 |                           |
|       | CV   |                 |                           |

4 Prinsip Desain:
  [ ] Traceability — Setiap komponen bisa ditelusuri ke variabel
  [ ] Variable Isolation — IV bisa diubah tanpa mengubah CV
  [ ] Measurement Integration — Pengukuran DV built-in
  [ ] Reproducibility — Setup bisa direkonstruksi

Experimental Setup:
  Input data     : ____________________
  Parameter      : ____________________
  Output format  : ____________________
```

---

## Latihan 1 — Variable-to-Component Mapping

Gunakan RQ dan variabel dari WS-05. Petakan ke komponen sistem.

**RQ:** Bagaimana perbandingan kecepatan waktu simpan (insert latency) dan beban memori server antara database MySQL dan PostgreSQL ketika digunakan untuk menangani aliran data berfrekuensi tinggi secara terus menerus (continuous streaming) dari sensor detak jantung MAX30102?

| Variabel | Tipe | Komponen Sistem | Cara Manipulasi / Pengukuran |
|----------|------|-----------------|---------------------------|
| Jenis database    | *IV* | Database Engine / Server Backend |  Mengubah setting koneksi di script pengujian (misalnya ganti tujuan port dari 3306 untuk MySQL menjadi 5432 untuk PostgreSQL) lalu jalankan ulang atau proses ulang data yang masuk dengan database yang berbeda. |
| Waktu simpan (insert latency) & Beban Server  | DV |  Log waktu di script program & Resource Monitor komputer | Membuat catatan waktu otomatis di dalam kodingan (mencatat waktu tepat sebelum dan sesudah data masuk) untuk melihat berapa milidetik kecepatan database saat menyimpan data. Bersamaan dengan itu,juga membuka Task Manager di komputer server untuk memantau persentase tarikan beban CPU dan RAM nya saat uji coba sedang berjalan |
| Frekuensi data masuk | CV |  Script Pengirim Data / Mikrokontroler |  Menyamakan delay atau kecepatan pengiriman data di dalam kode (misalnya dipatok pasti 100 data per detik) agar MySQL dan PostgreSQL menerima beban yang persis sama.di sini saya akan merekam detak jantung dari sempel dan menyimpannya dalam esp32 saya kemudian sempel itu kan memilii jumlah data yang sama akan dkirimkan atau diujikan ke database masing masing. |

**Apakah semua variabel bisa di-map?** [x] Ya / [ ] Tidak
> Jika tidak, komponen apa yang perlu ditambahkan? _________

---

## Latihan 2 — 4 Prinsip Desain

Evaluasi desain sistem terhadap 4 prinsip.

| Prinsip | Status | Bukti / Penjelasan |
|---------|--------|-------------------|
| Traceability | ✅  | Alurnya sangat jelas dan bisa dilacak. Data berawal dari array di ESP32, dikirim lewat WiFi, kemudian ditangkap oleh script server, dan masuk ke tabel database. Jika ada eror atau hilang, kita tahu  macetnya di jalur mana. |
| Modularity | ✅ | Komponennya terpisah dengan rapi (klien atau pengirim data ESP32 dan sensor  seerta server yaitu database yang digunakan itu berdiri sendiri). Kita bisa menukar tujuan mesin penyimpanannya (dari MySQL ke PostgreSQL) di sisi server tanpa harus merombak ulang kodingan ESP32 di sisi alat. |
| Controllability | ✅ | Beban pengujian teratur  dan  adil di kedua database karena isi angka detak jantung yang sudah disediakan itu data yang real dari manusia tetapi disimpan atau direkam didalam esp32nyaa itu sebelum diujikan ke kedua datbabase dan kecepatan tembakannya diatur sama untuk kedua database dengan delay kodingan (misal 100 data/detik).|
| Measurability | ✅ | Output langsung menghasilkan angka pasti. Selisih waktu simpan (insert latency) dihitung otomatis oleh kode menjadi satuan milidetik, dan beban ditarik murni berupa persentase (%) dari pantauan Task Manager komputer yang digunakan |

**Prinsip mana yang paling sulit dipenuhi?** Controllability (Keterkendalian lingkungan)
**Strategi untuk mengatasinya:**
> Tantangan terbesarnya adalah menjaga kondisi jaringan Wi-Fi agar tidak mendadak delay atau mencegah komputer melakukan tugas latar belakang yang memakan CPU (seperti update Windows atau proses Antivirus) saat pengujian sedang berlangsung.

**Strategi:**
> memakai jaringan Wi-Fi yang tidak ada internetnya atau paket internetnya dimatikan agar pengiriman data dari ESP32 lancar tanpa gangguan. kemudian mematikan semua aplikasi lain di laptop, agar saat CPU-nya berat  itu berarti  karena proses database tersebut, bukan karena program lain pada laptop.



---

## Latihan 3 — Ablation Study Planning

Jika sistem memiliki 3 komponen utama, rencanakan ablation study.

> **Panduan jumlah kondisi:** Untuk 3 komponen (A, B, C), kondisi minimal yang direkomendasikan:
> Full + (-A) + (-B) + (-C) = **4 kondisi dasar**. Jika waktu memungkinkan, tambahkan kombinasi ganda: (-A,-B), (-A,-C), (-B,-C) = **7 kondisi**. Sesuaikan dengan *computational cost* dan tenggat waktu penelitian.

| Kondisi | Komponen A | Komponen B | Komponen C | Hasil yang Diharapkan |
|---------|-----------|-----------|-----------|----------------------|
| Full | ✅ PostgreSQL | ✅ dengan wifi | ✅ memakai index/ primarykey | Primary Key	Baseline penuh (Waktu simpan dan beban CPU dalam kondisi sistem nyata/lengkap). |
| – A | ❌ (ganti mySQL dengan postgree) | ✅ | ✅ | |Mengetahui selisih performa antara arsitektur mesin PostgreSQL vs MySQL dengan koneksi wifi.
| – B | ✅ | ❌ (ganti wifi dengan USB serial yang langsung dicolok ke laptop) | ✅ | Mengetahui seberapa besar delay/latency yang disebabkan oleh pengiriman data nirkabel (wifi).|
| – C | ✅ | ✅ | ❌ (Tanpa Index, tabel polos) | Mengetahui seberapa besar beban memori dan waktu yang digunakan hanya untuk menyusun atau mengurutkan data (indexing) di dalam masing masing server.|

**Komponen mana yang diprediksi paling berkontribusi?** Komponen A yaitu mesin database
**Mengapa?**
> Karena arsitektur inti dari MySQL (InnoDB) dan PostgreSQL memiliki cara yang sangat berbeda dalam menangani antrean memori saat menerima tembakan data kueri INSERT yang beruntun tanpa jeda, sehingga menghasilkan perbedaan grafik persentase pada CPU.

---

## Refleksi

> Apa risiko jika sistem dibangun seperti produk (monolitik, fitur lengkap) lalu baru dilakukan eksperimen? Mengapa arsitektur modular penting untuk riset?

**Jawaban:**
> Risiko terbesar membangun sistem monolitik  adalah kebutaan analitik saat terjadi hambatan (bottleneck). Jika pengujian langsung dilakukan dalam keadaan utuh dan ternyata waktu simpannya sangat lambat (misal latency tembus 1 detik per data), kita tidak akan bisa mengetahui siapa yang menyebabkan lambat tersebut. Apakah dari WiFinya yang lemot, Apakah struktur tabelnya yang salah, Atau apakah memang dari mesin databasenya yang tidak kuat?
> Di sini arsitektur modular sangat penting dalam riset. Dengan sistem yang terpisah pisah, kita bisa memetakan komponen untuk mencari sumber masalahnya tersebut. Jika jalur jaringan diputus dan data dikirim lewat kabel ternyata tetap lambat, kita bisa membuktikan secara ilmiah bahwa beban terberat memang murni berasal dari mesin databasenya itu sendiri, bukan karena gangguan koneksi dan lainnya.