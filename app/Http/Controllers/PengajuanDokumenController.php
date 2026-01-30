<?php

namespace App\Http\Controllers;

use App\Models\PengajuanDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengajuanDokumenController extends Controller
{
    /**
     * Tampilan Riwayat Pengajuan (Halaman Warga)
     */
    public function index()
    {
        if (!Auth::user()->resident) {
            return redirect()->route('dashboard')
                ->with('error_resident', 'Lengkapi data penduduk Anda terlebih dahulu di menu profil.');
        }

        $data = PengajuanDokumen::with('resident')
            ->where('resident_id', Auth::user()->resident->id)
            ->latest()
            ->paginate(5);

        // Menampilkan file index.blade.php (Khusus Warga)
        return view('pages.pengajuan.index', compact('data'));
    }

    /**
     * Form Buat Pengajuan Baru (Halaman Warga)
     */
    public function create()
    {
        if (!Auth::user()->resident) {
            return redirect()->route('dashboard')
                ->with('error_resident', 'Lengkapi data penduduk terlebih dahulu sebelum mengajukan dokumen.');
        }

        return view('pages.pengajuan.create');
    }

    /**
     * Proses Simpan Pengajuan (Halaman Warga)
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_dokumen' => 'required|string|max:100',
            'keperluan' => 'required|string',
            'file_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $resident = Auth::user()->resident;
        if (!$resident) {
            return redirect()->route('dashboard')->with('error', 'Gagal: Data penduduk tidak ditemukan.');
        }

        $file = null;
        if ($request->hasFile('file_pendukung')) {
            $file = $request->file('file_pendukung')->store('pengajuan', 'public');
        }

        PengajuanDokumen::create([
            'user_id' => Auth::id(), 
            'resident_id' => $resident->id,
            'jenis_dokumen' => $request->jenis_dokumen,
            'keperluan' => $request->keperluan,
            'file_pendukung' => $file,
            'status' => 'diajukan', 
        ]);

        return redirect()->route('pengajuan.index')
            ->with('success', 'Pengajuan dokumen berhasil dikirim ke Admin Desa.');
    }

    /**
     * Tampilan Manajemen Pengajuan (Halaman Admin)
     */
    public function adminIndex()
    {
        $data = PengajuanDokumen::with('resident')->latest()->paginate(10);
        
        // PERBAIKAN: Harus memanggil 'pages.pengajuan.admin' agar tampilan manajemen admin muncul
        return view('pages.pengajuan.admin', compact('data'));
    }

    /**
     * Admin Update Status & Kirim Balasan (Halaman Admin)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diajukan,diproses,selesai',
            'catatan_admin' => 'nullable|string',
            'file_hasil' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $pengajuan = PengajuanDokumen::findOrFail($id);
        
        $updateData = [
            'status' => strtolower(trim($request->status)),
            'catatan_admin' => $request->catatan_admin
        ];

        if ($request->hasFile('file_hasil')) {
            if ($pengajuan->file_hasil) {
                Storage::disk('public')->delete($pengajuan->file_hasil);
            }
            $updateData['file_hasil'] = $request->file('file_hasil')->store('hasil_dokumen', 'public');
        }

        $pengajuan->update($updateData);

        return back()->with('success', 'Status pengajuan warga telah diperbarui.');
    }

    /**
     * Hapus Data Pengajuan
     */
    public function destroy($id)
    {
        $pengajuan = PengajuanDokumen::findOrFail($id);

        if ($pengajuan->file_pendukung) {
            Storage::disk('public')->delete($pengajuan->file_pendukung);
        }
        if ($pengajuan->file_hasil) {
            Storage::disk('public')->delete($pengajuan->file_hasil);
        }

        $pengajuan->delete();

        return back()->with('success', 'Data pengajuan telah dihapus permanen.');
    }
}