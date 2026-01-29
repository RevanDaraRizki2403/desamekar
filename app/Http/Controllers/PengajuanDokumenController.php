<?php

namespace App\Http\Controllers;

use App\Models\PengajuanDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengajuanDokumenController extends Controller
{
    // WARGA
    public function index()
    {
        if (!Auth::user()->resident) {
            return redirect()->route('dashboard')
                ->with('error_resident','Lengkapi data penduduk terlebih dahulu');
        }

        $data = PengajuanDokumen::where(
            'resident_id', Auth::user()->resident->id
        )->latest()->get();

        return view('pages.pengajuan.index', compact('data'));
    }

    public function create()
    {
        if (!Auth::user()->resident) {
            return redirect()->route('dashboard')
                ->with('error_resident','Lengkapi data penduduk terlebih dahulu');
        }

        return view('pages.pengajuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_dokumen' => 'required|string|max:100',
            'keperluan' => 'required|string',
            'file_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $file = null;
        if ($request->hasFile('file_pendukung')) {
            $file = $request->file('file_pendukung')
                ->store('pengajuan', 'public');
        }

        // Menambahkan user_id agar tidak eror "Field user_id doesn't have a default value"
        PengajuanDokumen::create([
            'user_id' => Auth::id(), 
            'resident_id' => Auth::user()->resident->id,
            'jenis_dokumen' => $request->jenis_dokumen,
            'keperluan' => $request->keperluan,
            'file_pendukung' => $file,
        ]);

        return redirect()->route('pengajuan.index')
            ->with('success','Pengajuan berhasil dikirim');
    }

    
public function adminIndex()
{
   
    $data = PengajuanDokumen::with('resident')->latest()->get();
    return view('pages.pengajuan.admin', compact('data'));
}

    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:diajukan,diproses,selesai',
        'catatan_admin' => 'nullable|string',
        'file_hasil' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    $pengajuan = PengajuanDokumen::findOrFail($id);
    
    $data = [
        'status' => strtolower(trim($request->status)),
        'catatan_admin' => $request->catatan_admin
    ];

    if ($request->hasFile('file_hasil')) {
        // Hapus file lama jika ada (optional)
        if ($pengajuan->file_hasil) {
            Storage::disk('public')->delete($pengajuan->file_hasil);
        }
        $data['file_hasil'] = $request->file('file_hasil')->store('hasil_dokumen', 'public');
    }

    $pengajuan->update($data);

    return back()->with('success', 'Status dan Dokumen berhasil diperbarui');
}

public function destroy($id)
{
    $pengajuan = PengajuanDokumen::findOrFail($id);

    // Hapus file fisik dari storage jika ada agar tidak memenuhi hosting
    if ($pengajuan->file_pendukung) {
        Storage::disk('public')->delete($pengajuan->file_pendukung);
    }
    if ($pengajuan->file_hasil) {
        Storage::disk('public')->delete($pengajuan->file_hasil);
    }

    $pengajuan->delete();

    return back()->with('success', 'Data pengajuan berhasil dihapus');
}
}