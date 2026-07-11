# Tahap 5  Penulisan Draf Paper Jurnal

**Status:** Konten naskah selesai  naskah konsolidasi utuh tersedia dalam format Markdown (`naskah-jurnal.md`) dan sudah dipindahkan ke template jurnal tujuan (`naskah-jurnal-TIIJ.docx`), lengkap dengan tinjauan pustaka pendukung bertema performa database & IoT kesehatan yang seluruh referensinya telah diverifikasi ke sumber aslinya. Sisa pekerjaan hanya melengkapi metadata penulis (lihat bagian "Yang Masih Perlu Dilengkapi").
**Bergantung pada:** [tahap-4-analisis-data.md](tahap-4-analisis-data.md)  *Selesai*

---

## Tujuan

Menyusun draf naskah ilmiah dengan gaya bahasa akademis yang jelas dan tidak berbelit, objektif, sesuai standar template jurnal tujuan (target publikasi Sinta 2 bidang Sistem Informasi/Teknik Informatika).

## Rencana Deliverable (Struktur Naskah)

| Bagian Bab | Deskripsi Isi Dokumen | Status |
|---|---|---|
| **Naskah Konsolidasi** | Dokumen utuh gabungan Abstrak s.d. Daftar Pustaka, format Markdown | Selesai  [`../07-manuskrip/naskah-jurnal.md`](../07-manuskrip/naskah-jurnal.md) |
| **Versi Template Jurnal** | Naskah dipindahkan ke template TIIJ (.docx), lengkap dengan tabel, diagram arsitektur, dan catatan kaki istilah teknis | Selesai  `naskah-jurnal-TIIJ.docx` |
| **Abstrak** | Ringkasan eksekutif mencakup latar belakang IoT kesehatan, metode N=35 run berpasangan, dan hasil uji Paired T-Test/Wilcoxon (Dwibahasa: ID & EN) | Selesai |
| **Bab 1: Pendahuluan** | Latar belakang kebutuhan database *real-time* untuk sensor detak jantung, rumusan masalah, tujuan, dan kontribusi riset bagi arsitektur backend IoT medis | Selesai |
| **Bab 2: Tinjauan Pustaka** | Landasan teori mengenai sensor MAX30102, arsitektur RDBMS (MySQL vs PostgreSQL), *insert latency*, serta 6 studi terdahulu yang telah diverifikasi kutipannya | Selesai referensi diverifikasi ke jurnal asli |
| **Bab 3: Metodologi Penelitian** | Desain eksperimen berpasangan, arsitektur sistem ESP32+PHP+database, prosedur pengujian 35 subjek, dan teknik analisis SPSS | Selesai |
| **Bab 4: Hasil & Analisis** | Statistik deskriptif, korelasi, uji normalitas, hasil Paired Samples T-Test (latency) dan Wilcoxon Signed-Rank Test (RAM), temuan tambahan *data loss* | Selesai (mengacu data Tahap 4) |
| **Bab 5: Kesimpulan & Saran** | Ringkasan jawaban atas rumusan masalah beserta rekomendasi penelitian lanjutan (uji signifikansi *data loss*, perbandingan NoSQL, dsb.) | Selesai |
| **Daftar Pustaka** | 6 referensi APA/IEEE, seluruhnya telah diverifikasi ke sumber jurnal asli (judul, penulis, volume, halaman, DOI bila tersedia) | Selesai |

---

## Yang Masih Perlu Dilengkapi Sebelum Submit

1. **Melengkapi Metadata Penulis**  mengisi nama lengkap, NIM, program studi, institusi Universitas Putra Bangsa (UPB) Kebumen, serta alamat email resmi pada bagian bawah judul naskah (saat ini masih placeholder `[Nama Peneliti]` / `[Nama Mahasiswa]`).
2. **Keputusan Bahasa Final Naskah**  saat ini isi bab utama ditulis dalam Bahasa Indonesia untuk target jurnal nasional Sinta 4/5 perlu keputusan final apakah tetap Bahasa Indonesia atau diterjemahkan penuh ke Bahasa Inggris jika target berubah ke Scopus Q3–Q4.
3. **Pengecekan Akhir Format Jurnal Tujuan**  memastikan margin, ukuran font, dan gaya sitasi pada `naskah-jurnal-TIIJ.docx` sudah sesuai *author guidelines* resmi jurnal yang dituju sebelum submit.
4. **Verifikasi Ulang Sebelum Cetak** mengecek ulang seluruh angka statistik di naskah agar tetap konsisten dengan [`../07-manuskrip/00-outline.md`](../07-manuskrip/00-outline.md) (daftar klaim kunci) jika ada revisi di menit-menit akhir.

---

## Referensi Terkait

- Naskah gabungan: [`../07-manuskrip/naskah-jurnal.md`](../07-manuskrip/naskah-jurnal.md)
- Peta sumber & klaim kunci: [`../07-manuskrip/00-outline.md`](../07-manuskrip/00-outline.md)
- Proses penulisan ilmiah (worksheet): [`../worksheets/ws-15-scientific-writing.md`](../../worksheets/ws-15-scientific-writing.md)
- Bahan presentasi/defense: [`../worksheets/ws-16-presentation-defense.md`](../../worksheets/ws-16-presentation-defense.md)