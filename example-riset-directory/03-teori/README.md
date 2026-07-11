# 03-teori

Arsitektur, landasan teori, dan metodologi analisis statistik  hasil **Tahap 1**, untuk penelitian **Analisis Perbandingan Performa Database MySQL dan PostgreSQL pada Sistem Pemantauan Data Sensor Detak Jantung Frekuensi Tinggi**.

## Isi yang diharapkan

* Diagram alur data pengujian (ESP32 → Logger → MySQL/PostgreSQL) & skema database
* Landasan teori metrik performa (Insert Latency & Beban Server)
* Konsep dasar & rumus Paired Samples T-Test.
* Perumusan hipotesis (H0/H1) dan kriteria pengambilan keputusan (signifikansi)
* Tinjauan pustaka & research gap (Bab 2)

## Berkas

* [`arsitektur-dan-skema.md`](arsitektur-dan-skema.md)  diagram Mermaid alur data, skema database MySQL & PostgreSQL, konfigurasi eksperimen, dan keputusan teknis.
* [`landasan-teori-statistik.md`](landasan-teori-statistik.md)  landasan teori metrik performa, rumus Independent Samples T-Test, hipotesis, dan kriteria signifikansi.
* [`tinjauan-pustaka.md`](tinjauan-pustaka.md)  draf Bab 2: State of the Art, Research Gap, Landasan Teori Konsep, & Definisi Operasional Variabel.

## Sumber

Disusun berdasarkan hasil pengerjaan WS-02 (Problem Statement & System Context), WS-03 (Literature Gap), WS-04 (RQ & Hypothesis), WS-05 (Variabel & Metrik), dan WS-06 (System-Experiment Mapping).