<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ResidentController extends Controller
{
    public function index()
    {
        // Mengambil data penduduk beserta akun usernya jika ada
        $residents = Resident::with('user')->latest()->get();
         
        return view('pages.resident.index', [
            'residents' => $residents,
        ]);
    }

    public function create()
    {
        return view('pages.resident.create');
    }

    public function store(Request $request)
    {
        // PERBAIKAN: Validasi hanya untuk kolom yang masih ada di database
        $validatedData = $request->validate([
            'nik'    => ['required', 'digits:16', 'unique:residents,nik'],
            'name'   => ['required', 'string', 'max:100'],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'status' => ['required', Rule::in(['active', 'moved', 'deceased'])],
        ]);

        // Menyimpan data yang sudah divalidasi
        Resident::create($validatedData);

        return redirect()->route('resident.index')->with('success', 'Berhasil menambahkan data penduduk.');
    }

    public function edit($id)
    {
        $resident = Resident::findOrFail($id);

        return view('pages.resident.edit', [
            'resident' => $resident,
        ]);
    }

    public function update(Request $request, $id)
    {
        // PERBAIKAN: Validasi update disesuaikan dengan atribut baru
        $validatedData = $request->validate([
            'nik'    => ['required', 'digits:16', Rule::unique('residents')->ignore($id)],
            'name'   => ['required', 'string', 'max:100'],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'status' => ['required', Rule::in(['active', 'moved', 'deceased'])],
        ]);

        $resident = Resident::findOrFail($id);
        
        // Melakukan update data
        $resident->update($validatedData);

        return redirect()->route('resident.index')->with('success', 'Data penduduk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $resident = Resident::findOrFail($id);
        $resident->delete();

        return redirect()->route('resident.index')->with('success', 'Berhasil menghapus data penduduk.');
    }
}