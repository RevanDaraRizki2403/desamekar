<?php

namespace App\Http\Controllers;

use App\Models\Pemeriksaan;
use App\Models\Ternak;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemeriksaanController extends Controller
{
    public function index()
    {
        $pemeriksaans = Pemeriksaan::with(['ternak', 'pemeriksa'])->latest()->paginate(10);
        $ternaks = Ternak::all();
        
        // Ambil daftar petugas kandang & admin untuk dropdown pilihan pemeriksa
        $petugas = User::whereIn('role_id', [1, 2])->get(); 
        
        return view('pages.pemeriksaan.index', compact('pemeriksaans', 'ternaks', 'petugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ternak_id'           => 'required|exists:ternaks,id',
            'user_id'             => 'nullable|exists:users,id',
            'tanggal_pemeriksaan' => 'required|date',
            'bobot'               => 'required|numeric|min:1',
        ]);

        DB::transaction(function () use ($request) {
            // Normalisasi Status Vaksin & Kesehatan
            $statusVaksin    = $request->status_vaksin ?? $request->status_vaksinasi ?? 'Lengkap';
            $statusKesehatan = $request->status_kesehatan ?? $request->skor_kesehatan ?? 'Sehat';

            // 1. Simpan Data Pemeriksaan
            Pemeriksaan::create([
                'ternak_id'           => $request->ternak_id,
                'user_id'             => $request->user_id ?? auth()->id(),
                'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
                'bobot'               => $request->bobot,
                'tinggi_badan'        => $request->tinggi_badan,
                'bcs'                 => $request->bcs,
                'skor_kesehatan'      => $request->skor_kesehatan,
                'status_kesehatan'    => $statusKesehatan,
                'kualitas_pakan'      => $request->kualitas_pakan,
                'status_vaksin'       => $statusVaksin,
                'status_vaksinasi'    => $statusVaksin,
                'riwayat_penyakit'    => $request->riwayat_penyakit,
                'catatan_petugas'     => $request->catatan_petugas,
            ]);

            // 2. Sinkronkan Otomatis ke Data Master Ternak
            $this->syncTernakData($request->ternak_id, $request);
        });

        return back()->with('success', 'Data pemeriksaan berhasil ditambahkan dan data ternak telah disinkronkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ternak_id'           => 'required|exists:ternaks,id',
            'user_id'             => 'nullable|exists:users,id',
            'tanggal_pemeriksaan' => 'required|date',
            'bobot'               => 'required|numeric|min:1',
        ]);

        DB::transaction(function () use ($request, $id) {
            $pemeriksaan = Pemeriksaan::findOrFail($id);

            $statusVaksin    = $request->status_vaksin ?? $request->status_vaksinasi ?? 'Lengkap';
            $statusKesehatan = $request->status_kesehatan ?? $request->skor_kesehatan ?? 'Sehat';

            // 1. Update Data Pemeriksaan
            $pemeriksaan->update([
                'ternak_id'           => $request->ternak_id,
                'user_id'             => $request->user_id ?? auth()->id(),
                'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
                'bobot'               => $request->bobot,
                'tinggi_badan'        => $request->tinggi_badan,
                'bcs'                 => $request->bcs,
                'skor_kesehatan'      => $request->skor_kesehatan,
                'status_kesehatan'    => $statusKesehatan,
                'kualitas_pakan'      => $request->kualitas_pakan,
                'status_vaksin'       => $statusVaksin,
                'status_vaksinasi'    => $statusVaksin,
                'riwayat_penyakit'    => $request->riwayat_penyakit,
                'catatan_petugas'     => $request->catatan_petugas,
            ]);

            // 2. Sinkronkan Ulang ke Data Master Ternak
            $this->syncTernakData($request->ternak_id, $request);
        });

        return back()->with('success', 'Data pemeriksaan berhasil diperbarui dan data ternak telah disinkronkan!');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $pemeriksaan = Pemeriksaan::findOrFail($id);
            $ternakId = $pemeriksaan->ternak_id;
            $pemeriksaan->delete();

            // Ambil riwayat pemeriksaan terakhir yang tersisa untuk ternak ini
            $lastPemeriksaan = Pemeriksaan::where('ternak_id', $ternakId)->latest('tanggal_pemeriksaan')->first();

            if ($lastPemeriksaan) {
                $fakeRequest = new Request([
                    'bobot'            => $lastPemeriksaan->bobot,
                    'tinggi_badan'     => $lastPemeriksaan->tinggi_badan,
                    'bcs'              => $lastPemeriksaan->bcs,
                    'skor_kesehatan'   => $lastPemeriksaan->skor_kesehatan,
                    'status_kesehatan' => $lastPemeriksaan->status_kesehatan,
                    'status_vaksin'    => $lastPemeriksaan->status_vaksin,
                    'kualitas_pakan'   => $lastPemeriksaan->kualitas_pakan,
                    'riwayat_penyakit' => $lastPemeriksaan->riwayat_penyakit,
                    'catatan_petugas'  => $lastPemeriksaan->catatan_petugas,
                ]);
                $this->syncTernakData($ternakId, $fakeRequest);
            }
        });

        return back()->with('success', 'Data pemeriksaan berhasil dihapus dan data ternak telah di-rollback!');
    }

    /**
     * Helper Sinkronisasi ke Data Master Ternak
     */
    private function syncTernakData($ternakId, Request $req)
    {
        $ternak = Ternak::find($ternakId);
        if (!$ternak) return;

        $jenis = $ternak->jenis;
        $bobotBaru = $req->bobot;

        $statusVaksin = $req->status_vaksin ?? $req->status_vaksinasi ?? 'Lengkap';
        $statusKesehatan = $req->status_kesehatan ?? $req->skor_kesehatan ?? 'Sehat';

        $isSehat = in_array(strtolower($statusKesehatan), [1, '1', 'sehat']) || (is_numeric($statusKesehatan) && $statusKesehatan >= 70);
        $isVaksin = in_array(strtolower($statusVaksin), [1, '1', 'sudah', 'tervaksin', 'lengkap']);
        $kepatuhanPakan = $req->kualitas_pakan ?? $ternak->kepatuhan_pakan ?? 1;

        // Hitung kelayakan
        $minBerat = ($jenis == 0 || strtolower($jenis) === 'sapi') ? 250 : 25;
        $isLayak = ($bobotBaru >= $minBerat) && $isSehat && $isVaksin;

        // Hitung estimasi harga
        $hargaPerKg = ($jenis == 0 || strtolower($jenis) === 'sapi') ? 65000 : 95000;
        $hargaJual = $isLayak ? ($bobotBaru * $hargaPerKg) : 0;

        // Update Tabel Master Ternak
        $ternak->update([
            'berat'            => $bobotBaru,
            'tinggi_badan'     => $req->tinggi_badan ?? $ternak->tinggi_badan,
            'bcs'              => $req->bcs ?? $ternak->bcs,
            'skor_kesehatan'   => $req->skor_kesehatan ?? $ternak->skor_kesehatan,
            'status_kesehatan' => $statusKesehatan,
            'status_vaksin'    => $statusVaksin,
            'status_vaksinasi' => $statusVaksin,
            'kualitas_pakan'   => $req->kualitas_pakan ?? $ternak->kualitas_pakan,
            'riwayat_penyakit' => $req->riwayat_penyakit ?? $ternak->riwayat_penyakit,
            'catatan_petugas'  => $req->catatan_petugas ?? $ternak->catatan_petugas,
            'kelayakan'        => $isLayak ? 1 : 0,
            'harga_jual'       => $hargaJual,
        ]);
    }
}