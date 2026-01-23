<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Tambahkan ini untuk validasi Rule

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ACCOUNT REQUEST (PERMINTAAN AKUN BARU)
    |--------------------------------------------------------------------------
    */
    public function account_request_view()
    {
        $users = User::where('status', 'submitted')->get();
        $residents = Resident::where('user_id', null)->get();

        return view('pages.account-request.index', compact('users', 'residents'));
    }

    public function account_approval(Request $request, $id)
    {
        // 1. Tambahkan validasi untuk resident_id (opsional/nullable)
        $request->validate([
            'for' => ['required', Rule::in(['approve', 'reject'])],
            'user_id' => ['nullable', 'exists:residents,id'] 
        ]);

        $user = User::findOrFail($id);

        if ($request->for === 'approve') {
            $user->status = 'approved';
            
            // 2. Logika untuk mengaitkan data penduduk jika dipilih
            if ($request->has('resident_id') && $request->resident_id != null) {
                // Cari data penduduk berdasarkan ID yang dipilih
                $resident = Resident::findOrFail($request->resident_id);
                
                // Update kolom user_id pada tabel residents
                $resident->user_id = $user->id;
                $resident->save();
            }
        } else {
            $user->status = 'rejected';
        }

        $user->save();

        return redirect()->route('account.request')
            ->with('success', 'Status akun berhasil diperbarui');
        }

    /*
    |--------------------------------------------------------------------------
    | ACCOUNT LIST (DAFTAR AKUN PENDUDUK)
    |--------------------------------------------------------------------------
    */
    public function account_list_view()
    {
        $users = User::where('role_id', 2)
            ->where('status', '!=', 'submitted')
            ->get();

        return view('pages.account-list.index', compact('users'));
    }

    public function account_aktifkan(Request $request, $id)
    {
        // Menambahkan validasi keamanan 'activate' sesuai instruksi video
        $request->validate([
            'for' => ['required', Rule::in(['activate'])]
        ]);

        $user = User::findOrFail($id);
        $user->status = 'approved';
        $user->save();

        return redirect()->route('account.list')
            ->with('success', 'Akun berhasil diaktifkan');
    }

    public function account_nonaktif(Request $request, $id)
    {
        // Menambahkan validasi keamanan 'deactivate' sesuai instruksi video
        $request->validate([
            'for' => ['required', Rule::in(['deactivate'])]
        ]);

        $user = User::findOrFail($id);
        $user->status = 'rejected';
        $user->save();

        return redirect()->route('account.list')
            ->with('success', 'Akun berhasil dinonaktifkan');
    }

    public function show($id)
{
    $user = User::findOrFail($id);
    return view('pages.user.show', compact('user'));
}

}