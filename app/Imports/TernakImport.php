<?php

namespace App\Imports;

use App\Models\Ternak;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Carbon\Carbon;
use Exception;

class TernakImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        // 1. Daftar header kolom wajib
        $requiredHeaders = [
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

        if ($rows->isEmpty()) {
            throw new Exception("File Excel kosong atau tidak memiliki data baris.");
        }

        // 2. Cek kelengkapan header kolom
        $firstRow = $rows->first()->toArray();
        $fileHeaders = array_keys($firstRow);
        $missingHeaders = array_diff($requiredHeaders, $fileHeaders);

        if (!empty($missingHeaders)) {
            throw new Exception("Struktur kolom file Excel tidak sesuai! Kolom yang hilang/salah: " . implode(', ', $missingHeaders));
        }

        // 3. Proses simpan data ke database
        foreach ($rows as $row) {
            if (empty($row['kode_ternak'])) {
                continue;
            }

            // PERBAIKAN: FORMATTING TANGGAL LAHIR DARI EXCEL (SERIAL NUMBER / STRING)
            $tanggalLahir = null;
            if (!empty($row['tanggal_lahir'])) {
                if (is_numeric($row['tanggal_lahir'])) {
                    // Jika formatnya angka serial bawaan Excel (seperti 45067)
                    $tanggalLahir = Carbon::instance(ExcelDate::excelToDateTimeObject($row['tanggal_lahir']))->format('Y-m-d');
                } else {
                    // Jika formatnya sudah teks string tanggal biasa
                    $tanggalLahir = Carbon::parse($row['tanggal_lahir'])->format('Y-m-d');
                }
            }

            // Ambil atribut penting untuk ML
            $jenisInput = $row['jenis_komoditas'] ?? 'Sapi';
            $jenisLower = strtolower(trim($jenisInput));
            $isSapi = ($jenisLower === 'sapi' || $jenisLower === '0' || str_contains($jenisLower, 'sapi'));

            $bobot = (float) ($row['bobot_kg'] ?? 0);
            $skorKesehatan = (int) ($row['skor_kesehatan'] ?? 80);

            // Hitung status kelayakan dan harga
            $minBeratThreshold = $isSapi ? 400 : 40;
            $isLayak = ($bobot >= $minBeratThreshold && $skorKesehatan >= 70) ? 1 : 0;
            $hargaJual = $bobot * ($isSapi ? 65000 : 95000);

            Ternak::create([
                'kode_ternak'      => $row['kode_ternak'],
                'no_ear_tag'       => $row['no_ear_tag'] ?? null,
                'tanggal_lahir'    => $tanggalLahir,
                'jenis'            => $jenisInput,
                'ras'              => $row['ras_varian'] ?? null,
                'jenis_kelamin'    => $row['jenis_kelamin'] ?? 'Jantan',
                'lokasi_kandang'   => $row['lokasi_kandang'] ?? null,
                'berat'            => $bobot,
                'tinggi_badan'     => $row['tinggi_badan_cm'] ?? null,
                'bcs'              => $row['bcs'] ?? null,
                'skor_kesehatan'   => $skorKesehatan,
                'kualitas_pakan'   => $row['kualitas_pakan'] ?? null,
                'status_vaksinasi' => $row['status_vaksinasi'] ?? 'Belum',
                'riwayat_penyakit' => $row['riwayat_penyakit'] ?? 'Tidak Ada',
                'kelayakan'        => $isLayak,
                'harga_jual'       => $hargaJual,
            ]);
        }
    }
}