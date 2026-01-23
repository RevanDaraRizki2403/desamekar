<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ResidentController extends Controller
{
    public function index()
    {
        $residents = Resident::with('user')->get();
         
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
        $validatedData = $request->validate([
            // Gunakan 'digits:16' untuk memastikan NIK tepat 16 karakter angka
            'nik'            => ['required', 'digits:16', 'unique:residents,nik'],
            'name'           => ['required', 'max:100'],
            'gender'         => ['required', Rule::in(['male', 'female'])],
            'birth_date'     => ['required', 'date'], // Sebaiknya validasi sebagai 'date'
            'birth_place'    => ['required', 'max:100'],
            'address'        => ['required', 'max:700'],
            'religion'       => ['nullable', 'max:50'],
            'marital_status' => ['required', Rule::in(['single', 'married', 'divorced', 'widowed'])],
            'occupation'     => ['nullable', 'max:100'],
            'phone'          => ['nullable', 'max:15'],
            'status'         => ['required', Rule::in(['active', 'moved', 'deceased'])],
        ]);

        Resident::create($validatedData);

        // Gunakan redirect ke route name agar lebih aman
        return redirect()->route('resident.index')->with('success', 'Berhasil menambahkan data');
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
        $resident = Resident::findOrFail($id);

        $validatedData = $request->validate([
            // Tambahkan ignore ID pada unique agar NIK sendiri tidak dianggap duplikat saat update
            'nik'            => ['required', 'digits:16', Rule::unique('residents')->ignore($resident->id)],
            'name'           => ['required', 'max:100'],
            'gender'         => ['required', Rule::in(['male', 'female'])],
            'birth_date'     => ['required', 'date'],
            'birth_place'    => ['required', 'max:100'],
            'address'        => ['required', 'max:700'],
            'religion'       => ['nullable', 'max:50'],
            'marital_status' => ['required', Rule::in(['single', 'married', 'divorced', 'widowed'])],
            'occupation'     => ['nullable', 'max:100'],
            'phone'          => ['nullable', 'max:15'],
            'status'         => ['required', Rule::in(['active', 'moved', 'deceased'])],
        ]);

        $resident->update($validatedData);

        return redirect()->route('resident.index')->with('success', 'Berhasil mengubah data');
    }

    public function destroy($id)
    {
        $resident = Resident::findOrFail($id);
        $resident->delete();

        return redirect()->route('resident.index')->with('success', 'Berhasil menghapus data');
    }
}