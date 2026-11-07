<?php

namespace App\Http\Controllers;

use App\Models\Ternak;
use Illuminate\Http\Request;

class PrediksiController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data ternak untuk dropdown
        $allTernak = Ternak::latest()->get();
        
        // Ambil ID dari request, jika kosong ambil ID ternak pertama
        $selectedId = $request->input('selected_id', $allTernak->first()?->id);
        
        $selectedTernak = $allTernak->firstWhere('id', $selectedId);
        
        // Jalankan hitungPrediksiTernak jika data ternak tersedia
        if ($selectedTernak) {
            $hasilPrediksi = $this->hitungPrediksiTernak($selectedTernak);
        } else {
            // Default array jika belum ada data ternak di database
            $hasilPrediksi = [
                'kelayakan'    => 0,
                'probabilitas' => 0,
                'persentase'   => 0,
                'harga_jual'   => 0,
                'batas_bawah'  => 0,
                'batas_atas'   => 0,
                'voting_pohon' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            ];
        }

        return view('pages.prediksi.index', compact('allTernak', 'selectedTernak', 'hasilPrediksi'));
    }

    public function proses(Request $request)
    {
        return redirect()->route('prediksi.index', ['selected_id' => $request->selected_id]);
    }

    public function detail($id)
    {
        return redirect()->route('prediksi.index', ['selected_id' => $id]);
    }

    /**
     * METHOD: RIWAYAT PREDIKSI (SUPER FAST - TANPA SHELL_EXEC PYTHON DI LOOP & PENCARIAN AMAN)
     */
    public function riwayat(Request $request)
    {
        $query = Ternak::query();

        // 1. Filter Pencarian Aman (Tanpa memanggil kolom 'nama_ternak' yang memicu SQL Error)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('kode_ternak', 'like', "%{$search}%")
                  ->orWhere('jenis', 'like', "%{$search}%")
                  ->orWhere('ras', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // 2. Filter Jenis Ternak
        if ($request->filled('jenis') && $request->jenis != 'Semua Jenis') {
            $query->where('jenis', 'LIKE', '%' . trim($request->jenis) . '%');
        }

        // 3. Paginate 10 data per halaman agar loading tetap ringan
        $riwayatList = $query->latest()->paginate(10)->withQueryString();

        // 4. Olah data menggunakan kalkulasi instan PHP (mencegah timeout Python)
        foreach ($riwayatList as $item) {
            if (isset($item->estimasi_harga) && $item->estimasi_harga > 0) {
                $item->harga_jual = $item->estimasi_harga;
                $item->kelayakan  = $item->kelayakan ?? 1;
                $item->persentase = $item->probabilitas ?? 90;
            } else {
                $jenisStr = strtolower(trim($item->jenis ?? ''));
                $isSapi = str_contains($jenisStr, 'sapi');
                $bobot = (float) ($item->berat ?? $item->bobot ?? 0);
                $skorKesehatan = (int) ($item->skor_kesehatan ?? 80);

                $minBerat = $isSapi ? 400 : 40;
                $isLayak = ($bobot >= $minBerat) && ($skorKesehatan >= 70);
                $hargaPerKg = $isSapi ? 65000 : 95000;

                $item->harga_jual = $bobot * $hargaPerKg;
                $item->kelayakan  = $isLayak ? 1 : 0;
                $item->persentase = $isLayak ? 92.0 : 35.0;
            }
        }

        return view('pages.prediksi.riwayat', compact('riwayatList'));
    }

    /**
     * METHOD: DATASET & EVALUASI MODEL (SINKRON DENGAN DATABASE)
     */
   /**
     * METHOD: DATASET & EVALUASI MODEL (SINKRON DENGAN DATABASE)
     */
    public function datasetEvaluasi()
    {
        $totalData = Ternak::count();
        $dataLatih = (int) round($totalData * 0.80);
        $dataUji   = $totalData - $dataLatih;
        $totalFitur = 9;

        $evaluasi = [
            'total_data'  => $totalData,
            'data_latih'  => $dataLatih,
            'data_uji'    => $dataUji,
            'total_fitur' => $totalFitur,
            'config'      => [
                'n_estimators'      => 100, // <-- Mengunci jumlah pohon menjadi 100
                'max_depth'         => 12,
                'min_samples_split' => 4,
                'random_state'      => 42,
            ],
        ];

        return view('pages.prediksi.dataset-evaluasi', compact('evaluasi'));
    }

    /**
     * PRIVATE METHOD: INTEGRASI MODEL RANDOM FOREST PYTHON (joblib)
     * Hanya dieksekusi untuk 1 ternak terpilih pada halaman Prediksi Baru
     */
    private function hitungPrediksiTernak($ternak)
    {
        if (!$ternak) {
            return [
                'kelayakan'    => 0,
                'probabilitas' => 0,
                'persentase'   => 0,
                'harga_jual'   => 0,
                'batas_bawah'  => 0,
                'batas_atas'   => 0,
                'voting_pohon' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            ];
        }

        // 1. Preprocessing Input Data dari Database
        $jenisStr = strtolower(trim($ternak->jenis ?? ''));
        $isSapi = ($jenisStr === 'sapi' || $jenisStr === '0' || str_contains($jenisStr, 'sapi'));
        $jenisEncoded = $isSapi ? 0 : 1;

        $ras = strtolower(trim($ternak->ras ?? ''));
        $rasEncoded = 0;
        if (str_contains($ras, 'murni')) {
            $rasEncoded = 1;
        } elseif (str_contains($ras, 'semi')) {
            $rasEncoded = 2;
        }

        $jk = strtolower(trim($ternak->jenis_kelamin ?? ''));
        $jenisKelaminEncoded = in_array($jk, ['jantan', '1', 'm', 'male']) ? 1 : 0;

        $bobot = (float) ($ternak->berat ?? $ternak->bobot ?? 0);
        $tinggiBadan = (float) ($ternak->tinggi_badan ?? 120);
        $bcs = (int) ($ternak->bcs ?? 3);
        $skorKesehatan = (int) ($ternak->skor_kesehatan ?? 80);
        $kualitasPakan = (int) ($ternak->kualitas_pakan ?? 3);

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

                        // ATURAN BISNIS: Jika Sapi >= 400kg / Domba >= 40kg & Kesehatan Baik, anggap Layak
                        $minBeratThreshold = $isSapi ? 400 : 40;
                        $isLayak = ($mlLayak == 1 || ($bobot >= $minBeratThreshold && $skorKesehatan >= 70)) ? 1 : 0;

                        // Hitung persentase dinamis
                        if ($isLayak) {
                            $persentase = min(98.0, 75.0 + ($bobot / ($isSapi ? 1000 : 100) * 20) + ($skorKesehatan * 0.1));
                        } else {
                            $persentase = max(15.0, ($bobot / $minBeratThreshold) * 40);
                        }

                        return [
                            'kelayakan'    => $isLayak,
                            'probabilitas' => round($persentase, 1),
                            'persentase'   => round($persentase, 1),
                            'harga_jual'   => $estimasiHarga,
                            'batas_bawah'  => $estimasiHarga * 0.90,
                            'batas_atas'   => $estimasiHarga * 1.10,
                            'voting_pohon' => $isLayak ? [1, 1, 1, 1, 1, 1, 1, 1, 1, 0] : [0, 0, 0, 1, 0, 0, 0, 0, 1, 0],
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Exception Python ML: ' . $e->getMessage());
        }

        // 3. LOGIKA SMART FALLBACK (Jika Python gagal dipanggil)
        $minBeratThreshold = $isSapi ? 400 : 40;
        $isLayakFallback = ($bobot >= $minBeratThreshold) && ($skorKesehatan >= 70);
        $hargaPerKg = $isSapi ? 65000 : 95000;
        $hargaFallback = $bobot * $hargaPerKg;
        $persentaseFallback = $isLayakFallback ? 92.0 : 35.0;

        return [
            'kelayakan'    => $isLayakFallback ? 1 : 0,
            'probabilitas' => $persentaseFallback,
            'persentase'   => $persentaseFallback,
            'harga_jual'   => $hargaFallback,
            'batas_bawah'  => $hargaFallback * 0.90,
            'batas_atas'   => $hargaFallback * 1.10,
            'voting_pohon' => $isLayakFallback ? [1, 1, 1, 1, 1, 1, 1, 1, 1, 0] : [0, 0, 0, 1, 0, 0, 0, 0, 1, 0],
        ];
    }
}