<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AduanController extends Controller
{
   public function index()
{
    $aduan = Aduan::latest()->paginate(3); 
    return view('pages.Aduan.index', compact('aduan'));
    // 1. Jika Admin, tampilkan semua tanpa cek resident
    if (Auth::user()->role_id == 1) { 
        $aduan = Aduan::with('resident')->latest()->paginate(10); 
        return view('pages.Aduan.index', compact('aduan'));
    }

    // 2. Jika Warga, cek data penduduknya
    // Gunakan optional() atau pengecekan manual yang lebih ketat
    if (!Auth::user()->resident) {
        return redirect()->route('dashboard')->with('error_resident', 'Lengkapi data penduduk Anda.');
    }

    $aduan = Aduan::where('resident_id', Auth::user()->resident->id)->latest()->paginate(5);
    return view('pages.Aduan.index', compact('aduan'));
}

    public function create()
    {
        if (!Auth::user()->resident) {
            return redirect()->route('dashboard')->with('error_resident', 'Anda harus melengkapi data penduduk untuk membuat aduan.');
        }
        return view('pages.Aduan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'min:3', 'max:255'],
            'content' => ['required', 'min:3', 'max:255'],
            'photo_proof' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        $aduan = new Aduan();
        $aduan->resident_id = Auth::user()->resident->id;
        $aduan->title = $request->input('title');
        $aduan->content = $request->input('content');

        if ($request->hasFile('photo_proof')) {
            // PERBAIKAN: Gunakan disk public agar masuk ke storage/app/public/uploads
            $filePath = $request->file('photo_proof')->store('uploads', 'public');
            $aduan->photo_proof = $filePath;
        }

        $aduan->save();
        return redirect()->route('Aduan.index')->with('success', 'Berhasil membuat aduan');
    }

    public function edit($id)
    {
        $aduan = Aduan::findOrFail($id);
        return view('pages.Aduan.edit', compact('aduan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => ['required', 'min:3', 'max:255'],
            'content' => ['required', 'min:3', 'max:255'],
            'photo_proof' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        $aduan = Aduan::findOrFail($id);

        if (Auth::user()->role_id != 1 && $aduan->resident_id !== Auth::user()->resident->id) {
    abort(403);
}

        $aduan->title = $request->input('title');
        $aduan->content = $request->input('content');

        if ($request->hasFile('photo_proof')) {
            if ($aduan->photo_proof) {
                Storage::disk('public')->delete($aduan->photo_proof);
            }
            // PERBAIKAN: Gunakan disk public
            $filePath = $request->file('photo_proof')->store('uploads', 'public');
            $aduan->photo_proof = $filePath;
        }

        $aduan->save();
        return redirect()->route('Aduan.index')->with('success', 'Berhasil mengubah aduan');
    }

    public function destroy($id)
    {
        $aduan = Aduan::findOrFail($id);

        if ($aduan->resident_id !== Auth::user()->resident->id) {
            abort(403);
        }

        if ($aduan->photo_proof) {
            Storage::disk('public')->delete($aduan->photo_proof);
        }

        $aduan->delete();
        return redirect()->route('Aduan.index')->with('success', 'Aduan berhasil dihapus');
    }

  public function update_status(Request $request, $id)
{
    // 1. Validasi: Menambah aturan untuk kolom respon
    $request->validate([
        'status' => ['required', \Illuminate\Validation\Rule::in(['new', 'processing', 'completed'])],
        'response_admin' => ['nullable', 'string', 'max:1000'], // Validasi respon admin
    ]);

    // 2. Cari data aduan
    $aduan = Aduan::findOrFail($id);

    // 3. Simpan data (Status tetap tersimpan, Respon ditambahkan)
    $aduan->status = $request->input('status');
    $aduan->response_admin = $request->input('response_admin'); // Menyimpan teks tanggapan
    $aduan->save();

    // 4. Kembali dengan pesan sukses
    return redirect()->route('Aduan.index')->with('success', 'Tanggapan dan status laporan berhasil diperbarui.');
}

}