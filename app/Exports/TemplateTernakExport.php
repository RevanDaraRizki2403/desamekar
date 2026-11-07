<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TemplateTernakExport implements WithHeadings, ShouldAutoSize, WithEvents
{
    /**
     * Define Header Baris Pertama (Nama Kolom Sahaja)
     */
    public function headings(): array
    {
        return [
            'kode_ternak',
            'no_ear_tag',
            'tanggal_lahir',
            'jenis_komoditas',
            'ras_varian',
            'jenis_kelamin',
            'lokasi_kandang',
            'bobot_kg',
            'tinggi_badan_cm',
            'bcs',
            'skor_kesehatan',
            'kualitas_pakan',
            'status_vaksinasi',
            'riwayat_penyakit'
        ];
    }

    /**
     * Styling Excel (Warna Header & Format Teks Kosong)
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // 1. Styling Header (Baris 1): Hijau Tua Peternakan (#0E6251), Teks Putih Tebal & Rata Tengah
                $sheet->getStyle('A1:N1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                        'size' => 11,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '0E6251'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Tinggi Baris Header
                $sheet->getRowDimension(1)->setRowHeight(28);

                // 2. Set Format Kolom Kode (A & B) sebagai TEXT agar angka 001/S26-001 tidak hilang formatnya saat diisi pengguna
                $sheet->getStyle('A2:B200')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
            },
        ];
    }
}