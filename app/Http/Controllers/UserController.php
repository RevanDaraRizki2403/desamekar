<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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

        return view('pages.account-request.index', compact('users'));
    }

    public function account_approval(Request $request, $id)
    {
        $request->validate([
            'for' => 'required|in:approve,reject'
        ]);

        $user = User::findOrFail($id);

        if ($request->for === 'approve') {
            $user->status = 'approved';
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

    public function account_aktifkan($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'approved';
        $user->save();

        return redirect()->route('account.list')
            ->with('success', 'Akun berhasil diaktifkan');
    }

    public function account_nonaktif($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'rejected';
        $user->save();

        return redirect()->route('account.list')
            ->with('success', 'Akun berhasil dinonaktifkan');
    }
}
