<?php

namespace App\Http\Controllers;

use App\Models\PetugasKandang;
use Illuminate\Http\Request;

class PetugasKandangController extends Controller
{
    /**
     * Menampilkan Halaman Master Petugas Kandang (Tabel)
     */
    public function index()
    {
        $petugas = PetugasKandang::latest()->get();

        // Sesuaikan dengan nama folder petugas-kandang
        return view('pages.petugas-kandang.index', compact('petugas'));
    }

    /**
     * Menampilkan Form Tambah Petugas Kandang
     */
    public function create()
    {
        return view('pages.petugas-kandang.create');
    }

    /**
     * Menyimpan Data Petugas Kandang Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'              => 'required|string|max:255',
            'alamat'            => 'nullable|string',
            'no_hp'             => 'nullable|string|max:15',
            'penugasan_kandang' => 'nullable|string',
        ]);

        PetugasKandang::create([
            'nama'              => $request->nama,
            'alamat'            => $request->alamat,
            'no_hp'             => $request->no_hp,
            'penugasan_kandang' => $request->penugasan_kandang,
            'status'            => 1, // 1 = Aktif
        ]);

        return redirect()->route('petugas-kandang.index')->with('success', 'Data petugas berhasil ditambahkan!');
    }

    /**
     * Menampilkan Form Edit Petugas Kandang
     */
    public function edit($id)
    {
        $petugas = PetugasKandang::findOrFail($id);

        return view('pages.petugas-kandang.edit', compact('petugas'));
    }

    /**
     * Memperbarui Data Petugas Kandang
     */
    public function update(Request $request, $id)
    {
        $petugas = PetugasKandang::findOrFail($id);

        $request->validate([
            'nama'              => 'required|string|max:255',
            'alamat'            => 'nullable|string',
            'no_hp'             => 'nullable|string|max:15',
            'penugasan_kandang' => 'nullable|string',
            'status'            => 'required|in:0,1',
        ]);

        $petugas->update([
            'nama'              => $request->nama,
            'alamat'            => $request->alamat,
            'no_hp'             => $request->no_hp,
            'penugasan_kandang' => $request->penugasan_kandang,
            'status'            => $request->status,
        ]);

        return redirect()->route('petugas-kandang.index')->with('success', 'Data petugas berhasil diperbarui!');
    }

    /**
     * Toggle Status (Aktif / Nonaktif)
     */
    public function toggleStatus($id)
    {
        $petugas = PetugasKandang::findOrFail($id);
        $petugas->status = $petugas->status == 1 ? 0 : 1;
        $petugas->save();

        return redirect()->back()->with('success', 'Status petugas berhasil diperbarui!');
    }

    /**
     * Menghapus Data Petugas Kandang
     */
    public function destroy($id)
    {
        $petugas = PetugasKandang::findOrFail($id);
        $petugas->delete();

        return redirect()->back()->with('success', 'Data petugas berhasil dihapus!');
    }
}