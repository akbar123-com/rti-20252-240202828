

CREATE TABLE IF NOT EXISTS analisis_riwayat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_file VARCHAR(255) NOT NULL COMMENT 'Nama file xlsx/csv yang diupload',
    label VARCHAR(255) NOT NULL COMMENT 'Label pasangan variabel, misal: Insert Latency',
    kolom_a VARCHAR(100) NOT NULL,
    kolom_b VARCHAR(100) NOT NULL,
    n INT NOT NULL COMMENT 'Jumlah pasangan data valid yang dianalisis',
    mean_a DECIMAL(12,4),
    mean_b DECIMAL(12,4),
    shapiro_w DECIMAL(8,4) COMMENT 'Statistik W uji normalitas pada selisih',
    shapiro_p DECIMAL(10,6) COMMENT 'Sig. uji normalitas pada selisih',
    uji_dipakai ENUM('paired_ttest', 'wilcoxon') NOT NULL,
    statistik_uji DECIMAL(12,4) COMMENT 't (jika paired) atau z (jika wilcoxon)',
    sig_p_value DECIMAL(10,6) NOT NULL,
    selisih_persen DECIMAL(8,2),
    cohen_d DECIMAL(8,4) NULL COMMENT 'Hanya terisi jika uji_dipakai = paired_ttest',
    h0_ditolak BOOLEAN NOT NULL,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);