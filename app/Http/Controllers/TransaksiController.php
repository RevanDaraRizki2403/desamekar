<?php

namespace App\Http\Controllers;

use App\Models\Ternak;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        // Ambil riwayat transaksi beserta data ternaknya
        $transaksis = Transaksi::with('ternak')->latest()->paginate(10);

        // Hanya ambil ternak yang LAYAK (kelayakan = 1) dan BELUM TERJUAL
        $ternaks = Ternak::where('kelayakan', 1)
            ->where(function($query) {
                $query->whereNull('status_penjualan')
                      ->orWhere('status_penjualan', '!=', 'Terjual');
            })->get();

        return view('pages.transaksi.index', compact('transaksis', 'ternaks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_transaksi'    => 'required|string|unique:transaksis,kode_transaksi',
            'ternak_id'         => 'required|exists:ternaks,id',
            'tanggal_transaksi' => 'required|date',
            'nama_pembeli'      => 'required|string|max:255',
            'harga_final'       => 'required|numeric|min:0',
            'status'            => 'required|in:Selesai,Pending,Batal',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Simpan Transaksi Penjualan
            Transaksi::create([
                'kode_transaksi'    => $request->kode_transaksi,
                'ternak_id'         => $request->ternak_id,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'nama_pembeli'      => $request->nama_pembeli,
                'harga_final'       => $request->harga_final,
                'status'            => $request->status,
            ]);

            // 2. Otomatis Update Status Ternak
            $ternak = Ternak::findOrFail($request->ternak_id);
            
            // Kita set status_penjualan jadi 'Terjual' AND kelayakan jadi 0 (supaya tidak muncul lagi di opsi jual)
            $ternak->update([
                'status_penjualan' => 'Terjual',
                'kelayakan'        => 0 // Opsional: dinonaktifkan dari daftar layak jual
            ]);
        });

        return back()->with('success', 'Transaksi berhasil dicatat dan status ternak otomatis diperbarui menjadi TERJUAL!');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $transaksi = Transaksi::findOrFail($id);

            // Kembalikan status ternak jika transaksi dihapus / dibatalkan
            $ternak = Ternak::find($transaksi->ternak_id);
            if ($ternak) {
                $ternak->update([
                    'status_penjualan' => 'Tersedia',
                    'kelayakan'        => 1 // Dikembalikan menjadi layak jual lagi
                ]);
            }

            $transaksi->delete();
        });

        return back()->with('success', 'Data transaksi berhasil dihapus dan status ternak dikembalikan!');
    }
}