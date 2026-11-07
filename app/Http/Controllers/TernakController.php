<?php

namespace App\Http\Controllers;

use App\Exports\TemplateTernakExport;
use App\Models\Ternak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class TernakController extends Controller
{
    public function index(Request $request)
    {
        $query = Ternak::latest();

        // Filter Pencarian (Cari ID, RFID, atau Ras)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_ternak', 'like', "%{$search}%")
                  ->orWhere('no_ear_tag', 'like', "%{$search}%")
                  ->orWhere('ras', 'like', "%{$search}%");
            });
        }

        // Filter Komoditas (Sapi / Domba)
        if ($request->filled('komoditas')) {
            $query->where('jenis', $request->komoditas);
        }

        // Filter Status Kelayakan (1: Layak, 0: Belum Layak)
        if ($request->filled('status')) {
            $query->where('kelayakan', $request->status);
        }

        $ternaks = $query->paginate(10)->withQueryString();

        // OTOMATIS PREDIKSI PYTHON UNTUK DATA YANG HARGA/KELAYAKANNYA MASIH KOSONG / 0
        foreach ($ternaks as $item) {
            if (is_null($item->harga_jual) || $item->harga_jual == 0) {
                $hasilML = $this->hitungPrediksiPython($item);
                $item->kelayakan = $hasilML['kelayakan'];
                $item->harga_jual = $hasilML['harga_jual'];
                $item->save(); // Update langsung ke database
            }
        }

        return view('pages.ternak.index', compact('ternaks'));
    }

    public function create()
    {
        $tahun2Digit = date('y'); // Misal: 2026 -> 26

        // 1. Hitung Auto Increment ID & Ear Tag untuk Sapi (SPI-xxx & S26-xxx)
        $lastSapi = Ternak::where('jenis', 'Sapi')->orderBy('id', 'desc')->first();
        $nextSapiNum = $lastSapi ? ($this->extractLastNumber($lastSapi->kode_ternak) + 1) : 1;
        
        $lastSapiTag = Ternak::where('jenis', 'Sapi')
            ->where('no_ear_tag', 'like', "S{$tahun2Digit}-%")
            ->orderBy('id', 'desc')->first();
        $nextSapiTagNum = $lastSapiTag ? ($this->extractLastNumber($lastSapiTag->no_ear_tag) + 1) : 1;

        $autoSapiId  = 'SPI-' . str_pad($nextSapiNum, 3, '0', STR_PAD_LEFT);
        $autoSapiTag = 'S' . $tahun2Digit . '-' . str_pad($nextSapiTagNum, 3, '0', STR_PAD_LEFT);

        // 2. Hitung Auto Increment ID & Ear Tag untuk Domba (DMB-xxx & D26-xxx)
        $lastDomba = Ternak::where('jenis', 'Domba')->orderBy('id', 'desc')->first();
        $nextDombaNum = $lastDomba ? ($this->extractLastNumber($lastDomba->kode_ternak) + 1) : 1;
        
        $lastDombaTag = Ternak::where('jenis', 'Domba')
            ->where('no_ear_tag', 'like', "D{$tahun2Digit}-%")
            ->orderBy('id', 'desc')->first();
        $nextDombaTagNum = $lastDombaTag ? ($this->extractLastNumber($lastDombaTag->no_ear_tag) + 1) : 1;

        $autoDombaId  = 'DMB-' . str_pad($nextDombaNum, 3, '0', STR_PAD_LEFT);
        $autoDombaTag = 'D' . $tahun2Digit . '-' . str_pad($nextDombaTagNum, 3, '0', STR_PAD_LEFT);

        return view('pages.ternak.create', compact(
            'autoSapiId', 'autoSapiTag',
            'autoDombaId', 'autoDombaTag'
        ));
    }

    public function store(Request $request)
    {
        // Validasi Pencegahan Duplikasi ID Ternak & Ear Tag
        $request->validate([
            'kode_ternak'   => 'required|string|unique:ternaks,kode_ternak',
            'no_ear_tag'    => 'nullable|string|unique:ternaks,no_ear_tag',
            'jenis'         => 'required|string',
            'jenis_kelamin' => 'required|string',
            'berat'         => 'required|numeric|min:0',
        ], [
            'kode_ternak.unique' => 'ID Ternak "' . $request->kode_ternak . '" sudah terdaftar! Gunakan ID lain.',
            'no_ear_tag.unique'  => 'Nomor Ear Tag / RFID "' . $request->no_ear_tag . '" sudah digunakan oleh ternak lain!',
        ]);

        // 1. Buat Instance Object Ternak Baru
        $ternak = new Ternak([
            'kode_ternak'      => $request->kode_ternak,
            'no_ear_tag'       => $request->no_ear_tag,
            'tanggal_lahir'    => $request->tanggal_lahir,
            'jenis'            => $request->jenis,
            'ras'              => $request->ras,
            'jenis_kelamin'    => $request->jenis_kelamin,
            'lokasi_kandang'   => $request->lokasi_kandang,
            'berat'            => $request->berat,
            'tinggi_badan'     => $request->tinggi_badan,
            'bcs'              => $request->bcs,
            'skor_kesehatan'   => $request->skor_kesehatan,
            'kualitas_pakan'   => $request->kualitas_pakan,
            'status_vaksinasi' => $request->status_vaksinasi,
            'riwayat_penyakit' => $request->riwayat_penyakit,
            'catatan_petugas'  => $request->catatan_petugas,
        ]);

        // 2. Jalankan Prediksi Machine Learning Python Sebelum Disimpan
        $hasilML = $this->hitungPrediksiPython($ternak);
        $ternak->kelayakan = $hasilML['kelayakan'];
        $ternak->harga_jual = $hasilML['harga_jual'];

        // 3. Simpan Ke Database
        $ternak->save();

        return redirect()->route('data-ternak.index')->with('success', 'Data ternak baru berhasil ditambahkan dan dianalisis Machine Learning!');
    }

    public function destroy($id)
    {
        $ternak = Ternak::findOrFail($id);
        $ternak->delete();

        return redirect()->route('data-ternak.index')->with('success', 'Data ternak berhasil dihapus!');
    }

    /**
     * IMPORT EXCEL LANGSUNG DI CONTROLLER (OTOMATIS GENERATE ID & EAR TAG RESMI)
     */
    public function importExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_excel' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file_excel.required' => 'Silakan pilih file Excel terlebih dahulu.',
            'file_excel.mimes'    => 'Format file tidak sesuai! File yang diunggah harus berformat Excel (.xlsx, .xls, atau .csv).',
            'file_excel.max'      => 'Ukuran file terlalu besar, maksimal 5MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $tahun2Digit = date('y'); // Contoh: 26

            // 1. Dapatkan angka nomor urut terakhir di Database
            $lastSapi = Ternak::where('jenis', 'Sapi')->orderBy('id', 'desc')->first();
            $sapiNum = $lastSapi ? $this->extractLastNumber($lastSapi->kode_ternak) : 0;

            $lastSapiTag = Ternak::where('jenis', 'Sapi')
                ->where('no_ear_tag', 'like', "S{$tahun2Digit}-%")
                ->orderBy('id', 'desc')->first();
            $sapiTagNum = $lastSapiTag ? $this->extractLastNumber($lastSapiTag->no_ear_tag) : 0;

            $lastDomba = Ternak::where('jenis', 'Domba')->orderBy('id', 'desc')->first();
            $dombaNum = $lastDomba ? $this->extractLastNumber($lastDomba->kode_ternak) : 0;

            $lastDombaTag = Ternak::where('jenis', 'Domba')
                ->where('no_ear_tag', 'like', "D{$tahun2Digit}-%")
                ->orderBy('id', 'desc')->first();
            $dombaTagNum = $lastDombaTag ? $this->extractLastNumber($lastDombaTag->no_ear_tag) : 0;

            // 2. Baca isi file Excel ke dalam Array
            $rows = Excel::toArray([], $request->file('file_excel'))[0];

            if (count($rows) <= 1) {
                return redirect()->back()->with('error', 'File Excel kosong atau hanya berisi judul kolom.');
            }

            // Loop tiap baris data Excel (melewati baris ke-0/header)
            foreach (array_slice($rows, 1) as $row) {
                // Skip jika baris kosong
                if (empty(array_filter($row))) {
                    continue;
                }

                // FIX 1: Tentukan Jenis Komoditas DARI KOLOM D ($row[3])
                $jenisText = isset($row[3]) ? trim((string)$row[3]) : '';
                $isSapi = str_contains(strtolower($jenisText), 'sapi');
                $jenisNorm = $isSapi ? 'Sapi' : 'Domba';

                // PAKSA GENERATE ID & EAR TAG SESUAI URUTAN RESMI WEBSITE
                if ($isSapi) {
                    $sapiNum++;
                    $sapiTagNum++;
                    $kodeTernak = 'SPI-' . str_pad($sapiNum, 3, '0', STR_PAD_LEFT);
                    $noEarTag   = 'S' . $tahun2Digit . '-' . str_pad($sapiTagNum, 3, '0', STR_PAD_LEFT);
                } else {
                    $dombaNum++;
                    $dombaTagNum++;
                    $kodeTernak = 'DMB-' . str_pad($dombaNum, 3, '0', STR_PAD_LEFT);
                    $noEarTag   = 'D' . $tahun2Digit . '-' . str_pad($dombaTagNum, 3, '0', STR_PAD_LEFT);
                }

                // FIX 2: MAPPING INDEKS KOLOM EXCEL SESUAI TEMPLATE
                // Col 0 (A): kode_ternak (diabaikan)
                // Col 1 (B): no_ear_tag (diabaikan)
                // Col 2 (C): tanggal_lahir
                // Col 3 (D): jenis_komoditas
                // Col 4 (E): ras_varian
                // Col 5 (F): jenis_kelamin
                // Col 6 (G): lokasi_kandang
                // Col 7 (H): bobot_kg
                // Col 8 (I): tinggi_badan_cm
                // Col 9 (J): bcs
                // Col 10 (K): skor_kesehatan
                // Col 11 (L): kualitas_pakan
                // Col 12 (M): status_vaksinasi
                // Col 13 (N): riwayat_penyakit
                // Col 14 (O): catatan_petugas
                
                $tanggalLahir = $this->parseDateSafely($row[2] ?? null);

                $ternak = new Ternak([
                    'kode_ternak'      => $kodeTernak,               // ID Ternak Resmi Web (e.g. SPI-067)
                    'no_ear_tag'       => $noEarTag,                 // Ear Tag Resmi Web (e.g. S26-002)
                    'jenis'            => $jenisNorm,                // Sapi / Domba dari Kolom D
                    'tanggal_lahir'    => $tanggalLahir,             // Kolom C
                    'ras'              => $row[4] ?? null,           // Kolom E
                    'jenis_kelamin'    => $row[5] ?? 'Jantan',       // Kolom F
                    'lokasi_kandang'   => $row[6] ?? null,           // Kolom G
                    'berat'            => is_numeric($row[7] ?? null) ? $row[7] : 0,  // Kolom H
                    'tinggi_badan'     => is_numeric($row[8] ?? null) ? $row[8] : 0,  // Kolom I
                    'bcs'              => is_numeric($row[9] ?? null) ? $row[9] : 3,  // Kolom J
                    'skor_kesehatan'   => is_numeric($row[10] ?? null) ? $row[10] : 80,// Kolom K
                    'kualitas_pakan'   => is_numeric($row[11] ?? null) ? $row[11] : 3, // Kolom L
                    'status_vaksinasi' => $row[12] ?? 'Lengkap',      // Kolom M
                    'riwayat_penyakit' => $row[13] ?? 'Tidak Ada',    // Kolom N
                    'catatan_petugas'  => $row[14] ?? null,           // Kolom O
                ]);

                // Hitung analisis Machine Learning Python sebelum disimpan
                $hasilML = $this->hitungPrediksiPython($ternak);
                $ternak->kelayakan = $hasilML['kelayakan'];
                $ternak->harga_jual = $hasilML['harga_jual'];

                // Simpan ke Database
                $ternak->save();
            }

            return redirect()->route('data-ternak.index')->with('success', 'Data ternak dari Excel berhasil diimpor dengan ID & Eartag otomatis berurutan!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }

    /**
     * HELPER PARSER TANGGAL AMAN
     */
    private function parseDateSafely($value)
    {
        if (empty($value)) return null;

        try {
            // Jika tanggal bertipe serial number angka bawaan Excel
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
            }
            // Parse biasa dengan Carbon
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * METHOD UNTUK UNDUH TEMPLATE EXCEL RAPI (.XLSX)
     */
    public function downloadTemplate()
    {
        return Excel::download(new TemplateTernakExport, 'Template_Import_Data_Ternak.xlsx');
    }

    /**
     * HELPER REGEX: MENGAMBIL ANGKA DIBELAKANG STRIP (-) SECARA PRESISI
     * Contoh: "SPI-066" -> 66, "S26-001" -> 1
     */
    private function extractLastNumber($string)
    {
        if (!$string) return 0;
        if (preg_match('/-(\d+)$/', trim($string), $matches)) {
            return (int) $matches[1];
        }
        return 0;
    }

    /**
     * PRIVATE METHOD: INTEGRASI DENGAN PREDIKSI MACHINE LEARNING PYTHON
     */
    private function hitungPrediksiPython($ternak)
    {
        // 1. Preprocessing Input Data
        $jenisStr = strtolower(trim($ternak->jenis ?? ''));
        $isSapi = ($jenisStr === 'sapi' || $jenisStr === '0' || str_contains($jenisStr, 'sapi'));
        $jenisEncoded = $isSapi ? 0 : 1;

        $ras = strtolower(trim($ternak->ras ?? ''));
        $isMurni = str_contains($ras, 'murni');
        $isSemi = str_contains($ras, 'semi');

        $rasEncoded = 0;
        if ($isMurni) {
            $rasEncoded = 1;
        } elseif ($isSemi) {
            $rasEncoded = 2;
        }

        $jk = strtolower(trim($ternak->jenis_kelamin ?? ''));
        $isJantan = in_array($jk, ['jantan', '1', 'm', 'male']);
        $jenisKelaminEncoded = $isJantan ? 1 : 0;

        $bobot = (float) ($ternak->berat ?? 0);
        $tinggiBadan = (float) ($ternak->tinggi_badan ?? 120);
        $bcs = (int) ($ternak->bcs ?? 3);
        $skorKesehatan = (int) ($ternak->skor_kesehatan ?? 80);
        $kualitasPakan = (int) ($ternak->kualitas_pakan ?? 3);

        // Hitung Umur dalam Bulan
        $umurBulan = 0;
        if ($ternak->tanggal_lahir) {
            try {
                $umurBulan = (int) \Carbon\Carbon::parse($ternak->tanggal_lahir)->diffInMonths(now());
            } catch (\Exception $e) {
                $umurBulan = 0;
            }
        }

        $statusVaksin = strtolower(trim($ternak->status_vaksinasi ?? ''));
        $vaksinEncoded = in_array($statusVaksin, ['lengkap', '1', 'sudah', 'tervaksin']) ? 1 : 0;

        $inputData = [
            'jenis_komoditas'  => $jenisEncoded,
            'ras_varian'       => $rasEncoded,
            'jenis_kelamin'    => $jenisKelaminEncoded,
            'bobot_kg'         => $bobot,
            'tinggi_badan_cm'  => $tinggiBadan,
            'bcs'              => $bcs,
            'skor_kesehatan'   => $skorKesehatan,
            'kualitas_pakan'   => $kualitasPakan,
            'status_vaksinasi' => $vaksinEncoded
        ];

        $base64Input = base64_encode(json_encode($inputData));
        $pythonExec = 'C:\Users\PLN\AppData\Local\Programs\Python\Python314\python.exe';
        $scriptPath = base_path('app/ml/prediksi.py');

        try {
            if (file_exists($scriptPath) && file_exists($pythonExec)) {
                $command = "\"{$pythonExec}\" \"{$scriptPath}\" \"{$base64Input}\" 2>&1";
                $rawOutput = trim(shell_exec($command));

                if (!empty($rawOutput)) {
                    $output = json_decode($rawOutput, true);

                    if (isset($output['status']) && $output['status'] === 'success') {
                        $estimasiHarga = (float) $output['harga_jual'];
                        $mlLayak = (int) $output['is_layak'];

                        // Syarat Kelayakan Bisnis: Sapi >= 24 bulan (2 thn) / Domba >= 12 bulan (1 thn) & Kesehatan >= 70
                        $minUmurThreshold = $isSapi ? 24 : 12;
                        $isLayak = ($mlLayak == 1 || ($umurBulan >= $minUmurThreshold && $skorKesehatan >= 70)) ? 1 : 0;

                        return [
                            'kelayakan'  => $isLayak,
                            'harga_jual' => $estimasiHarga
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Exception Python ML: ' . $e->getMessage());
        }

        // =========================================================
        // RUMUS FALLBACK DENGAN TARIF PER KG TERBARU PETERNAKAN
        // =========================================================
        $hargaPerKg = 0;

        if ($isSapi) {
            // Sapi Jantan = 135rb/kg
            $hargaPerKg = $isJantan ? 135000 : 120000;
            $isLayakFallback = ($umurBulan >= 24 && $skorKesehatan >= 70);
        } else {
            // Domba Garut Murni / Semi
            if ($isMurni) {
                $hargaPerKg = $isJantan ? 100000 : 95000; // Murni Jantan: 100rb, Murni Betina: 95rb
            } else {
                $hargaPerKg = $isJantan ? 90000 : 85000;  // Semi Jantan: 90rb, Semi Betina: 85rb
            }
            $isLayakFallback = ($umurBulan >= 12 && $skorKesehatan >= 70);
        }

        $hargaFallback = $bobot * $hargaPerKg;

        return [
            'kelayakan'  => $isLayakFallback ? 1 : 0,
            'harga_jual' => $hargaFallback
        ];
    }
}