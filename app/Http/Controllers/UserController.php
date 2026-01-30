<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; 

class UserController extends Controller
{
    public function account_request_view()
    {
        $users = User::where('status', 'submitted')->get();
        $residents = Resident::where('user_id', null)->get();

        return view('pages.account-request.index', compact('users', 'residents'));
    }

    public function account_approval(Request $request, $id)
{
    $request->validate([
        'for' => ['required', Rule::in(['approve', 'reject'])],
        'resident_id' => ['nullable', 'exists:residents,id']
    ]);

    $user = User::findOrFail($id);

    if ($request->for === 'approve') {
        $user->status = 'approved';

        if ($request->filled('resident_id')) {
            $resident = Resident::findOrFail($request->resident_id);
            $resident->user_id = $user->id;
            $resident->save();
        }

    } else {
        $user->status = 'rejected';
    }

    $user->save();

    return redirect()
        ->route('account.request')
        ->with('success', 'Status akun berhasil diperbarui');
}

    /*
    |--------------------------------------------------------------------------
    | ACCOUNT LIST (DAFTAR AKUN PENDUDUK)
    |--------------------------------------------------------------------------
    */
    public function account_list_view()
    {
        // PERBAIKAN: Ganti get() menjadi paginate(5)
        // Menampilkan akun warga (role_id 2) yang sudah diproses (bukan submitted)
        $users = User::where('role_id', 2)
            ->where('status', '!=', 'submitted')
            ->latest() // Menampilkan akun terbaru di atas
            ->paginate(5); 

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