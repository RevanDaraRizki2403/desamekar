<?php

namespace App\Http\Controllers;

use App\Models\Ternak;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Populasi Ternak
        $totalTernak = Ternak::count();

        // 2. Populasi Sapi (jenis = 0 atau 'Sapi')
        $totalSapi = Ternak::whereIn('jenis', [0, '0', 'Sapi'])->count();

        // 3. Populasi Domba (jenis = 1 atau 'Domba')
        $totalDomba = Ternak::whereIn('jenis', [1, '1', 'Domba'])->count();

        // 4. Total Ternak Layak Jual
        $totalLayak = Ternak::where(function ($q) {
            $q->where('kelayakan', 1)
              ->orWhere('kelayakan', '1')
              ->orWhere('kelayakan', 'Layak')
              ->orWhere('kelayakan', 'Layak Jual');
        })->count();

        // 5. Total Estimasi Nilai Aset (Hanya Ternak yang LAYAK JUAL)
        $totalAset = Ternak::where(function ($q) {
            $q->where('kelayakan', 1)
              ->orWhere('kelayakan', '1')
              ->orWhere('kelayakan', 'Layak')
              ->orWhere('kelayakan', 'Layak Jual');
        })->sum('harga_jual');

        // 6. Ambil 5 Data Ternak Terbaru
        $latestTernak = Ternak::latest()->take(5)->get();

        return view('pages.dashboard', compact(
            'totalTernak',
            'totalSapi',
            'totalDomba',
            'totalLayak',
            'totalAset',
            'latestTernak'
        ));
    }
}