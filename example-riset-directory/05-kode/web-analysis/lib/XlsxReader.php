<?php
/**
 * XlsxReader.php
 * --------------
 * Pembaca file .xlsx SEDERHANA tanpa library eksternal (tidak butuh Composer).
 * XLSX sebenarnya adalah file ZIP berisi XML, jadi kita buka pakai ZipArchive
 * bawaan PHP dan parse XML-nya langsung.
 *
 * Catatan: hanya baca SHEET PERTAMA, cukup untuk kebutuhan tool ini.
 * Untuk file besar/kompleks (banyak style, merged cells, dst), pertimbangkan
 * pakai PhpSpreadsheet via Composer jika hasil baca tidak akurat.
 */

class XlsxReader
{
    /**
     * Baca file .xlsx dan kembalikan array 2D (baris x kolom), semua sebagai string.
     */
    public static function read(string $path): array
    {
        $zip = new ZipArchive();
        if ($zip->open($path) !== true) {
            throw new Exception("Gagal membuka file .xlsx (bukan file ZIP yang valid).");
        }

        // 1. Baca shared strings (tempat semua nilai teks disimpan terpisah dari sel)
        $sharedStrings = [];
        $sharedXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedXml !== false) {
            $sst = simplexml_load_string($sharedXml);
            foreach ($sst->si as $si) {
                if (isset($si->t)) {
                    $sharedStrings[] = (string) $si->t;
                } else {
                    // rich text (banyak <r><t>) -> gabungkan semua run teks
                    $text = '';
                    foreach ($si->r as $r) {
                        $text .= (string) $r->t;
                    }
                    $sharedStrings[] = $text;
                }
            }
        }

        // 2. Cari sheet pertama
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        if ($sheetXml === false) {
            $zip->close();
            throw new Exception("Tidak ditemukan xl/worksheets/sheet1.xml di dalam file.");
        }
        $zip->close();

        $sheet = simplexml_load_string($sheetXml);
        $rows = [];

        foreach ($sheet->sheetData->row as $row) {
            $rowData = [];
            $lastColIndex = -1;

            foreach ($row->c as $cell) {
                $ref = (string) $cell['r']; // contoh: "B3"
                $colLetters = preg_replace('/[0-9]/', '', $ref);
                $colIndex = self::colLettersToIndex($colLetters);

                // isi kolom yang di-skip (sel kosong) dengan string kosong
                while ($lastColIndex + 1 < $colIndex) {
                    $rowData[] = '';
                    $lastColIndex++;
                }

                $type = (string) $cell['t'];
                $rawValue = isset($cell->v) ? (string) $cell->v : '';

                if ($type === 's') {
                    // shared string
                    $value = $sharedStrings[(int) $rawValue] ?? '';
                } elseif ($type === 'inlineStr') {
                    $value = (string) $cell->is->t;
                } else {
                    // angka atau kosong
                    $value = $rawValue;
                }

                $rowData[] = $value;
                $lastColIndex = $colIndex;
            }

            $rows[] = $rowData;
        }

        return $rows;
    }

    /** Ubah "A", "B", ..., "Z", "AA", dst menjadi index 0-based */
    private static function colLettersToIndex(string $letters): int
    {
        $letters = strtoupper($letters);
        $index = 0;
        for ($i = 0; $i < strlen($letters); $i++) {
            $index = $index * 26 + (ord($letters[$i]) - ord('A') + 1);
        }
        return $index - 1;
    }

    /**
     * Baca file .xlsx ATAU .csv dan kembalikan array asosiatif per baris
     * (baris pertama dipakai sebagai nama kolom/header).
     */
    public static function readAsTable(string $path): array
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if ($ext === 'csv') {
            $rows = [];
            $fh = fopen($path, 'r');
            while (($row = fgetcsv($fh)) !== false) {
                $rows[] = $row;
            }
            fclose($fh);
        } else {
            $rows = self::read($path);
        }

        if (count($rows) < 2) {
            throw new Exception("File tidak berisi data yang cukup (minimal 1 header + 1 baris data).");
        }

        $header = array_map('trim', $rows[0]);
        $ncol = count($header);
        $data = [];

        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            // lewati baris yang benar-benar kosong
            if (count(array_filter($row, fn($v) => trim((string)$v) !== '')) === 0) continue;

            $assoc = [];
            for ($c = 0; $c < $ncol; $c++) {
                $assoc[$header[$c]] = $row[$c] ?? '';
            }
            $data[] = $assoc;
        }

        return ['header' => $header, 'data' => $data];
    }
}