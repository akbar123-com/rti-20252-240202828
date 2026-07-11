# Landasan Teori & Metodologi Analisis Statistik Komparatif

Landasan teori metrik performa dan metodologi uji statistik  hasil **Tahap 1**.

## Isi Dokumen
- Landasan Teori Metrik Performa Database (*Insert Latency* & Beban Server)
- Konsep Dasar Pengujian *Paired Samples T-Test*
- Perumusan Hipotesis Penelitian
- Aturan Pengambilan Keputusan (Kriteria Signifikansi)

---

## 1. Landasan Teori Metrik Performa Database

Dalam mengevaluasi performa sistem manajemen basis data relasional (MySQL dan PostgreSQL) pada beban aliran data sensor berfrekuensi tinggi, dua metrik utama digunakan untuk merepresentasikan konsep efisiensi dan ketahanan sistem:

* **Insert Latency (Waktu Simpan):** Diukur dalam satuan milidetik (ms), yaitu durasi sejak perintah `INSERT` dikirim ke database engine hingga data berhasil ditulis dan dikonfirmasi tersimpan. Semakin kecil nilainya, semakin cepat dan efisien database tersebut merespons.
* **Beban Server (CPU/RAM Usage):** Diukur dalam persentase (%), merepresentasikan seberapa berat sumber daya komputasi yang ditarik oleh masing-masing database engine saat menahan hantaman data secara terus-menerus. Beban yang tinggi mengindikasikan potensi *bottleneck* yang dapat berujung pada penolakan data (*data loss*) jika dibiarkan melebihi kapasitas server.

Kedua metrik ini bersifat *ratio scale* (memiliki nol absolut dan jarak antar nilai bermakna), sehingga memenuhi syarat untuk dianalisis menggunakan uji beda rata-rata parametrik seperti *Paired Samples T-Test*.

---

## 2. Landasan Teori Paired Samples T-Test

*Paired Samples T-Test* (Uji T Sampel Berpasangan) adalah analisis statistik parametrik yang digunakan untuk membandingkan rata-rata (*mean*) dari **dua pengukuran yang berasal dari subjek/unit percobaan yang sama** pada dua kondisi berbeda. Uji ini dipilih bukan *Independent Samples T-Test*  karena setiap run replikasi ke-i menghasilkan sepasang pengukuran yang saling berhubungan: nilai Insert Latency (dan Beban Server) MySQL serta nilai Insert Latency (dan Beban Server) PostgreSQL yang diambil pada kondisi pengujian yang identik (stream data sensor MAX30102 yang sama, urutan replikasi yang sama, dan lingkungan eksekusi yang sama). Karena kedua nilai dalam satu pasangan dipengaruhi oleh kondisi run yang sama, keduanya tidak independen satu sama lain, sehingga variabilitas antar-kondisi run perlu dikendalikan dengan menganalisis *selisih (difference score)* dari tiap pasangan, bukan membandingkan dua kelompok terpisah.

Dalam eksperimen ini, uji dilakukan untuk membandingkan rata-rata **Insert Latency MySQL** dengan rata-rata **Insert Latency PostgreSQL** (begitu pula untuk metrik Beban Server), dengan **n = 35 pasangan run replikasi** sesuai batasan masalah pada proposal (setiap run menghasilkan satu pasang pengamatan MySQL–PostgreSQL).

### Model Matematika & Rumus Uji T Sampel Berpasangan

Dasar dari uji ini adalah menghitung selisih tiap pasangan pengamatan, kemudian menguji apakah rata-rata selisih tersebut berbeda signifikan dari nol:

```
dᵢ = X1ᵢ − X2ᵢ        (selisih tiap pasangan run ke-i, i = 1, 2, ..., n)

t = d̄ / (Sd / √n)     df = n − 1
```

**Keterangan:**
* **d̄** : Rata-rata selisih (*mean of differences*) Insert Latency atau Beban Server antara MySQL dan PostgreSQL untuk seluruh pasangan run
* **Sd** : Standar deviasi dari selisih dᵢ
* **n** : Jumlah pasangan run replikasi (n = 35)
* **df** (*Degree of Freedom*) : n − 1 = 34

Berbeda dengan Independent Samples T-Test, pada Paired Samples T-Test **tidak diperlukan uji Levene's Test** (uji homogenitas varians dua kelompok terpisah), karena yang dianalisis adalah satu variabel baru berupa selisih (d) dari pasangan-pasangan pengamatan, bukan dua kelompok independen yang variansnya perlu dibandingkan.

---

## 3. Perumusan Hipotesis Penelitian

Pengujian hipotesis dilakukan untuk mengetahui secara valid apakah terdapat perbedaan performa yang signifikan antara MySQL dan PostgreSQL saat menangani aliran data sensor MAX30102 berfrekuensi tinggi, dengan **μd** melambangkan rata-rata selisih populasi (Insert Latency atau Beban Server) antara MySQL dan PostgreSQL:

* **H0 (Hipotesis Nol):** Tidak terdapat perbedaan rata-rata *insert latency* dan beban server yang signifikan antara MySQL dan PostgreSQL pada kondisi run yang sama (μd = 0).
* **H1 (Hipotesis Alternatif):** Terdapat perbedaan rata-rata *insert latency* dan beban server yang signifikan antara MySQL dan PostgreSQL pada kondisi run yang sama (μd ≠ 0), dengan selisih rata-rata minimal 10%.

---

## 4. Kriteria Pengambilan Keputusan (Signifikansi)

Analisis data dilakukan dengan menggunakan alat bantu **IBM SPSS Statistics**. Sebelum diolah, data mentah perlu melalui *preprocessing* (pembersihan karakter satuan seperti "ms"/"%" pada kolom metrik) karena SPSS mewajibkan format numerik murni untuk menjalankan Uji T-Test (lihat WS-13). Pengambilan keputusan untuk menolak atau menerima H0 didasarkan pada nilai signifikansi p-value (*Sig. 2-tailed*) pada output *Paired Samples Test*, dengan ketentuan:

> * Jika nilai Sig. (2-tailed) < 0.05 **dan** selisih rata-rata ≥ 10%, maka **H0 Ditolak** dan **H1 Diterima**. (Perbedaan performa bersifat signifikan secara statistik dan bermakna secara praktis).
> * Jika nilai Sig. (2-tailed) ≥ 0.05, maka **H0 Diterima** dan **H1 Ditolak**. (Perbedaan performa hanya terjadi karena faktor kebetulan/variasi acak, bukan karena arsitektur database).

**Effect Size (Cohen's d untuk sampel berpasangan):** Selain p-value, dilaporkan juga *effect size* Cohen's d untuk mengukur seberapa besar (bukan cuma "signifikan atau tidak") perbedaan performa kedua database penting karena signifikansi statistik saja tidak selalu berarti signifikansi praktis (lihat WS-14). Untuk desain berpasangan, effect size dihitung dari rata-rata dan standar deviasi selisih:

```
d = d̄ / Sd
```

| Nilai d | Interpretasi |
|---|---|
| ~0.2 | Small effect |
| ~0.5 | Medium effect |
| ~0.8 | Large effect |
| > 1.2 | Huge effect |

**Langkah pemeriksaan sebelum uji-t (asumsi):**
1. **Uji Normalitas pada selisih (*difference score*)** (mis. Shapiro-Wilk) — memastikan selisih dᵢ (bukan data mentah tiap kelompok) berdistribusi normal, karena syarat normalitas pada uji-t berpasangan berlaku untuk variabel selisih, bukan untuk MySQL dan PostgreSQL secara terpisah.
2. **Uji Homogenitas Varians (Levene's Test) tidak diperlukan** pada desain berpasangan, karena analisis dilakukan terhadap satu variabel selisih, bukan dua kelompok independen.
3. Jika asumsi normalitas pada selisih tidak terpenuhi, gunakan uji non-parametrik alternatif (**Wilcoxon Signed Rank Test**) sebagai cadangan.

---

## Referensi Internal

- Definisi variabel & metrik: lihat [`tinjauan-pustaka.md`](tinjauan-pustaka.md) bagian 4.
- Rantai operasionalisasi RQ → analisis: lihat WS-04 Latihan 3.
- Hipotesis awal (versi naratif): lihat WS-04 Latihan 2.